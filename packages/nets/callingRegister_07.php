<?php
require_once("Parameters.php");
require_once("ClassOrder.php");
require_once("ClassRecurring.php");
require_once("ClassTerminal.php");
require_once("ClassRegisterRequest.php");
require_once("ClassEnvironment.php");

echo "<h3><font color='blue'>This example is showing you how to call Register function having Recurring Parameters (withdraw client money by using Panhash)</font></h3>";
echo "<h1><font color='blue'>Call Center</font></h1>";

####  PARAMETERS IN RECURRING  ####
$ExpiryDate           = null; // Required (if type “R”
$Frequency            = null; // Required (if type “R”)
$Type                 = "R"; // Optional parameter (unless Pan Hash is supplied, then it is required)
$PanHash              = "G29oMw8CT+kh1Nc9EwdcrHwR0IA="; // Optional parameter

####  PARAMETERS IN ORDER  ####
$amount               = 1*100; // The amount described as the lowest monetary unit, example: 100,00 NOK is noted as "10000", 9.99 USD is noted as "999".
$currencyCode         = "NOK";  //The currency code, following ISO 4217. Typical examples include "NOK" and "USD".
$force3DSecure        = null;   // Optional parameter
$orderNumber          = md5(uniqid('', true));
$UpdateStoredPaymentInfo = null;

####  PARAMETERS IN ENVIRONMENT  ####
$Language             = null; // Optional parameter
$OS                   = null; // Optional parameter
$WebServicePlatform   = 'PHP5'; // Required (for Web Services)

####  PARAMETERS IN TERMINAL  ####
$autoAuth             = null; // Optional parameter
$paymentMethodList    = null; // Optional parameter
$language             = null; // Optional parameter
$orderDescription     = null; // Optional parameter
$redirectOnError      = null; // Optional parameter

####  PARAMETERS IN REGISTER REQUEST  ####
$AvtaleGiro           = null; // Optional parameter
$CardInfo             = null; // Optional parameter
$Customer             = null; // Optional parameter
$description          = null; // Optional parameter
$DnBNorDirectPayment  = null; // Optional parameter
$Environment          = null; // Optional parameter for REST
$MicroPayment         = null; // Optional parameter
$serviceType          = "C"; // Optional parameter: null ==> default = "B" <=> BBS HOSTED
$Recurring            = null; // Optional parameter
$transactionId        = null; // Optional parameter
$transactionReconRef  = null; // Optional parameter

####  RECURRING OBJECT  ####
$Recurring = new Recurring(
        $ExpiryDate,
        $Frequency,
        $Type,
        $PanHash
);
 
####  ENVIRONMENT OBJECT  ####
$Environment = new Environment(
  $Language           ,
  $OS                 ,
  $WebServicePlatform 
);

$redirect_url = null; // not required in CallCenter interface
####  TERMINAL OBJECT  ####
$Terminal = new Terminal(
  $autoAuth,
  $paymentMethodList,
  $language,
  $orderDescription,
  $redirectOnError,
  $redirect_url
);

$ArrayOfItem = null; // no goods for Klana ==> normal transaction
####  ORDER OBJECT  ####
$Order = new Order(
  $amount,
  $currencyCode,
  $force3DSecure,
  $ArrayOfItem,
  $orderNumber,
  $UpdateStoredPaymentInfo
);


####  START REGISTER REQUEST  ####
$RegisterRequest = new RegisterRequest(
  $AvtaleGiro,
  $CardInfo,
  $Customer,
  $description,
  $DnBNorDirectPayment,
  $Environment,
  $MicroPayment,
  $Order,
  $Recurring,
  $serviceType,
  $Terminal,
  $transactionId,
  $transactionReconRef
);


####  ARRAY WITH REGISTER PARAMETERS  ####
$InputParametersOfRegister = array
(
        "token"                 => $token,
        "merchantId"            => $merchantId,
        "request"               => $RegisterRequest
);


####  Display all parameters  ####
echo "<h3><font color='gray'>Input parameters:</font></h3>";

echo "merchantId= " . $merchantId . "<br/>";
echo "token= " . $token . "<br/>";

echo "amount= " . $amount . "<br/>";
echo "currencyCode= " . $currencyCode . "<br/>";
echo "orderNumber= " . $orderNumber . "<br/>";
echo "serviceType= " . $serviceType . "<br/>";
echo "Type= " . $Type . "<br/>";
echo "PanHash= " . $PanHash . "<br/>";

/*
  // you can also display this way
  echo "<br/>Parameters in RegisterRequest<br/>"; 
  echo "<pre>"; 
  echo print_r($InputParametersOfRegister); 
  echo "</pre>";
*/


####  START REGISTER CALL  ####
try 
{
  if (strpos($_SERVER["HTTP_HOST"], 'uapp') > 0)
  {
  // Creating new client having proxy
  $client = new SoapClient($wsdl, array('proxy_host' => "isa4", 'proxy_port' => 8080, 'trace' => true,'exceptions' => true));
  }
  else
  {
  // Creating new client without proxy
  $client = new SoapClient($wsdl, array('trace' => true,'exceptions' => true ));
  }

  $OutputParametersOfRegister = $client->__call('Register' , array("parameters"=>$InputParametersOfRegister));
    
  // RegisterResult
  $RegisterResult = $OutputParametersOfRegister->RegisterResult; 
  
  $terminal_parameters = "?merchantId=". $merchantId . "&transactionId=" .  $RegisterResult->TransactionId;  
  $process_parameters = "?transactionId=" .  $RegisterResult->TransactionId;  
  
  
  echo "<h3><font color='gray'>Output parameters:</font></h3>";
  
  echo "TransactionId= " . $RegisterResult->TransactionId . "<br/>";

/*
  // you can also display this way
  echo "<br/>Parameters in RegisterResult<br/>"; 
  echo "<pre>"; 
  echo print_r($RegisterResult); 
  echo "</pre>";
*/

  echo "<h3><font color='green'>Register call successfully done.</font></h3>";
  
  echo "<br/>ResponseCode is OK, so you can call manually following action:";
  echo "<h3><a href='callingAuth.php$process_parameters'>Calling Auth</a></h3>";
  echo "<h3><a href='callingSale.php$process_parameters'>Calling Sale</a></h3>";
  echo "<h3><a href='callingQuery.php$process_parameters'>Calling Query</a></h3>";
}
catch (SoapFault $fault) 
{
  // Printing errors from the communication
  echo "<h3><a href='index.php'>Test Webshops</a><h3>";
  echo "<h3><font color='red'>EXCEPTION IN REGISTER CALL:</font></h3>";
  echo "<pre>"; 
  print_r($fault);
  echo "</pre>";
}
####  END   REGISTER CALL  ####


?>
