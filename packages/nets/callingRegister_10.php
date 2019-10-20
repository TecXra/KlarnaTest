<?php
require_once("Parameters.php");
require_once("ClassRegisterRequest.php");
require_once("ClassCustomer.php");
require_once("ClassTerminal.php");
require_once("ClassItem.php");
require_once("ClassArrayOfItem.php");
require_once("ClassOrder.php");
require_once("ClassEnvironment.php");

echo "<h3><font color='blue'>This example is showing you how to call Register function with Customer info Parameters + Goods for Klarna</font></h3>";
echo "<h1><font color='blue'>BBS hosted</font></h1>";

####  PARAMETERS IN ORDER  ####
$amount               = 20*100; // The amount described as the lowest monetary unit, example: 100,00 NOK is noted as "10000", 9.99 USD is noted as "999".
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
$language             = "en_GB"; // Optional parameter
$orderDescription     = "Order Description"; // Optional parameter
$redirectOnError      = null; // Optional parameter

####  PARAMETERS IN REGISTER REQUEST  ####
$AvtaleGiro           = null; // Optional parameter
$CardInfo             = null; // Optional parameter
$description          = "description of Order Registration"; // Optional parameter
$DnBNorDirectPayment  = null; // Optional parameter
$Environment          = null; // Optional parameter for REST
$MicroPayment         = null; // Optional parameter
$serviceType          = null; // Optional parameter
$Recurring            = null; // Optional parameter
$transactionId        = null; // Optional parameter
$transactionReconRef  = null; // Optional parameter

####  PARAMETERS IN CUSTOMER  ####

/*
$customerCompanyName      = "Netcom";                       // ???????? parameter
$customerCompanyRegistrationNumber      = "NO 951 589 888 MVA";                       // ???????? parameter

AUTH:
[message:protected] => Det oppstod en feil mellom butikken og Klarna. Vennligst kontakt butikken for mer informasjon eller velg en annen betalingsmetode.
*/

$customerAddress1                   = "Hundremeterskogen 100";    // Optional parameter (required if DnBNorDirectPayment)
$customerAddress2                   = "";                         // Optional parameter
$customerCompanyName                = null;                       // ???????? parameter
$customerCompanyRegistrationNumber  = null;                       // ???????? parameter
$customerCountry                    = "NO";                       // Optional parameter
$customerFirstName                  = "Petter";                   // Optional parameter (required if DnBNorDirectPayment)
$customerLastName                   = "Testmann";                 // Optional parameter (required if DnBNorDirectPayment)
$customerNumber                     = "1234567";                  // Optional parameter
$customerEmail                      = "petter@kaninklubben.no";   // Optional parameter
$customerPhoneNumber                = "40 123 456";               // Optional parameter
$customerPostcode                   = "0563";                     // Optional parameter (required if DnBNorDirectPayment)
$customerSocialSecurityNumber       = "18106500157";              // ???????? parameter 
$customerTown                       = "Oslo";                     // Optional parameter (required if DnBNorDirectPayment)


$item1_Amount         = "100";
$item1_ArticleNumber  = 14294413;
$item1_Discount       = "0.33"; // "0.33" ==>> 33%  
$item1_Handling       = true;
$item1_IsVatIncluded  = true;
$item1_Quantity       = 2;
$item1_Shipping       = true;
$item1_Title          = "TEST01";
$item1_VAT            = "0.25"; // "0.25" ==>> 25%
//$item1_VAT            = rand(15,40)/100;    


$item1 = new Item(
        $item1_Amount,
        $item1_ArticleNumber,
        $item1_Discount,
        $item1_Handling,
        $item1_IsVatIncluded,
        $item1_Quantity,
        $item1_Shipping,
        $item1_Title,
        $item1_VAT
);

$item2_Amount         = "200";
//$item2_ArticleNumber  = rand(1000,10000);
$item2_ArticleNumber  = 13760205;
$item2_Discount       = "0.25"; // "0.25" ==>> 25%  
$item2_Handling       = true;
$item2_IsVatIncluded  = true;
$item2_Quantity       = 3;
$item2_Shipping       = true;
$item2_Title          = "TEST02";
$item2_VAT            = "0.17";    
//$item2_VAT            = rand(15,40)/100;    


$item2 = new Item(
        $item2_Amount,
        $item2_ArticleNumber,
        $item2_Discount,
        $item2_Handling,
        $item2_IsVatIncluded,
        $item2_Quantity,
        $item2_Shipping,
        $item2_Title,
        $item2_VAT
);

$item3_Amount         = "300";
$item3_ArticleNumber  = 20434752;
$item3_Discount       = "0.75"; // "0.75" ==>> 75%  
$item3_Handling       = true;
$item3_IsVatIncluded  = true;
$item3_Quantity       = 4;
$item3_Shipping       = true;
$item3_Title          = "TEST03";
$item3_VAT            = "0.25"; 
//$item3_VAT            = "0,25"; // 25% ==> 25,75 : System.ArgumentException: Parameter value '25,00' is out of range.     at System.Data.SqlClient.TdsParser.TdsExecuteRPC(_SqlRPC[] rpcArray, Int32 timeout, Boolean inSchema, SqlNotificationRequest notificationRequest, TdsParserStateObject stateObj, Boolean isCommandProc)     at System.Data.SqlClient.SqlCommand.RunExecuteReaderTds(CommandBehavior cmdBehavior, RunBehavior runBehavior, Boolean returnStream, Boolean async)     at System.Data.SqlClient.SqlCommand.RunExecuteReader(CommandBehavior cmdBehavior, RunBehavior runBehavior, Boolean returnStream, String method, DbAsyncResult result)     at System.Data.SqlClient.SqlCommand.RunExecuteReader(CommandBehavior cmdBehavior, RunBehavior runBehavior, Boolean returnStream, String method)     at System.Data.SqlClient.SqlCommand.ExecuteReader(CommandBehavior behavior, String method)     at System.Data.SqlClient.SqlCommand.ExecuteDbDataReader(CommandBehavior behavior)     at System.Data.Common.DbCommand.ExecuteReader()     at System.Data.Linq.SqlClient.SqlProvider.Execute(Expression query, QueryInfo queryInfo, IObjectReaderFactory factory, Object[] parentArgs, Object[] userArgs, ICompiledSubQuery[] subQueries, Object lastResult)     at System.Data.Linq.SqlClient.SqlProvider.ExecuteAll(Expression query, QueryInfo[] queryInfos, IObjectReaderFactory factory, Object[] userArguments, ICompiledSubQuery[] subQueries)     at System.Data.Linq.SqlClient.SqlProvider.System.Data.Linq.Provider.IProvider.Execute(Expression query)     at System.Data.Linq.ChangeDirector.StandardChangeDirector.DynamicInsert(TrackedObject item)     at System.Data.Linq.ChangeDirector.StandardChangeDirector.Insert(TrackedObject item)     at System.Data.Linq.ChangeProcessor.SubmitChanges(ConflictMode failureMode)     at System.Data.Linq.DataContext.SubmitChanges(ConflictMode failureMode)     at System.Data.Linq.DataContext.SubmitChanges()     at LinqInserters.<>c__DisplayClass16.<Setup>b__11() in C:\bbs\ServiceImplementation\LinqInserters.cs:line 344     at LinqInserters.Retry(Action method, Action`2 onFail, TimeSpan timeout, Boolean containFailExceptions) in C:\bbs\ServiceImplementation\LinqInserters.cs:line 59


$item3 = new Item(
        $item3_Amount,
        $item3_ArticleNumber,
        $item3_Discount,
        $item3_Handling,
        $item3_IsVatIncluded,
        $item3_Quantity,
        $item3_Shipping,
        $item3_Title,
        $item3_VAT
);


$GoodsArray = array();
// hardcoded just for testing
array_push($GoodsArray, $item1);
array_push($GoodsArray, $item2);
array_push($GoodsArray, $item3);

// dynamic:
/*
$i_counter       = rand(1,10);
for ($i = 0; $i <= $i_counter; $i++) 
{
  $itemn_Amount         = rand(100000,1000000);
  $itemn_ArticleNumber  = rand(1000,10000);
  $itemn_Discount       = rand(1,99)/100;    
  $itemn_Handling       = true;
  $itemn_IsVatIncluded  = true;
  $itemn_Quantity       = rand(1,100);
  $itemn_Shipping       = true;
  $itemn_Title          = "TEST_item_#" . $i;
  $itemn_VAT            = rand(15,40)/100;    
  
  
  $itemn = new Item(
          $itemn_Amount,
          $itemn_ArticleNumber,
          $itemn_Discount,
          $itemn_Handling,
          $itemn_IsVatIncluded,
          $itemn_Quantity,
          $itemn_Shipping,
          $itemn_Title,
          $itemn_VAT
  );
  array_push($GoodsArray, $itemn);
  
}
*/

// ok for n item
$ArrayOfItem = new ArrayOfItem(
  $GoodsArray
);


/*
// ok for 1 item
$ArrayOfItem = new ArrayOfItem(
  $item1
);
*/

//$ArrayOfItem = null;


####  ENVIRONMENT OBJECT  ####
$Environment = new Environment(
  $Language           ,
  $OS                 ,
  $WebServicePlatform 
);

####  TERMINAL OBJECT  ####
$Terminal = new Terminal(
  $autoAuth,
  $paymentMethodList,
  $language,
  $orderDescription,
  $redirectOnError,
  $redirect_url
);

####  ORDER OBJECT  ####
$Order = new Order(
  $amount,
  $currencyCode,
  $force3DSecure,
  $ArrayOfItem,
  $orderNumber,
  $UpdateStoredPaymentInfo
);

####  CUSTOMER OBJECT  ####
$Customer = new Customer(
  $customerAddress1,
  $customerAddress2,
  $customerCompanyName,
  $customerCompanyRegistrationNumber,
  $customerCountry,
  $customerNumber,
  $customerEmail,
  $customerFirstName,
  $customerLastName,
  $customerPhoneNumber,
  $customerPostcode,
  $customerSocialSecurityNumber,
  $customerTown
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
echo "redirect_url= " . $redirect_url . "<br/>";

echo "description= " . $description . "<br/>";
echo "orderDescription= " . $orderDescription . "<br/>";
echo "language= " . $language . "<br/>";

echo "customerNumber= " . $customerNumber . "<br/>";
echo "customerFirstName= " . $customerFirstName . "<br/>";
echo "customerLastName= " . $customerLastName . "<br/>";
echo "customerAddress1= " . $customerAddress1 . "<br/>";
echo "customerAddress2= " . $customerAddress2 . "<br/>";
echo "customerPostcode= " . $customerPostcode . "<br/>";
echo "customerTown= " . $customerTown . "<br/>";
echo "customerCountry= " . $customerCountry . "<br/>";
echo "customerPhoneNumber= " . $customerPhoneNumber . "<br/>";
echo "customerEmail= " . $customerEmail . "<br/>";

/*
  // you can also display this way
*/
  echo "<br/>Parameters in RegisterRequest<br/>"; 
  echo "<pre>"; 
  echo print_r($InputParametersOfRegister); 
  echo "</pre>";


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
  
  echo "<h3><a href='$terminal$terminal_parameters'>Go to Netaxept Terminal 2.0</a></h3>";

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
