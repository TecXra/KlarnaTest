<?php
require_once(ABS_INTERNAL_FILES_ROOT."nets/Parameters.php");
require_once(ABS_INTERNAL_FILES_ROOT."nets/ClassOrder.php");
require_once(ABS_INTERNAL_FILES_ROOT."nets/ClassTerminal.php");
require_once(ABS_INTERNAL_FILES_ROOT."nets/ClassRegisterRequest.php");
require_once(ABS_INTERNAL_FILES_ROOT."nets/ClassEnvironment.php");

####  PARAMETERS IN ORDER  ####
$amount               = $amount; // The amount described as the lowest monetary unit, example: 100,00 NOK is noted as "10000", 9.99 USD is noted as "999".
$currencyCode         = $Currency;  //The currency code, following ISO 4217. Typical examples include "NOK" and "USD".
$force3DSecure        = null;   // Optional parameter
$orderNumber          = $tempOrderID;
$UpdateStoredPaymentInfo = null;


####  PARAMETERS IN Environment  ####
$Language             = 'sv_SE'; // Optional parameter
$OS                   = null; // Optional parameter
$WebServicePlatform   = 'PHP5'; // Required (for Web Services)

####  PARAMETERS IN TERMINAL  ####
$autoAuth             = null; // Optional parameter
$paymentMethodList    = null; // Optional parameter
$language             = 'sv_SE'; // Optional parameter
$orderDescription     = null; // Optional parameter
$redirectOnError      = null; // Optional parameter

####  PARAMETERS IN REGISTER REQUEST  ####
$AvtaleGiro           = null; // Optional parameter
$CardInfo             = null; // Optional parameter
$Customer             = null; // Optional parameter
$description          = null; // Optional parameter
$DnBNorDirectPayment  = null; // Optional parameter
$Environment          = null; // Optional parameter
$MicroPayment         = null; // Optional parameter
$serviceType          = null; // Optional parameter: null ==> default = "B" <=> BBS HOSTED
$Recurring            = null; // Optional parameter
$transactionId        = null; // Optional parameter
$transactionReconRef  = null; // Optional parameter

####  ENVIRONMENT OBJECT  ####
$Environment = new Environment($Language,$OS,$WebServicePlatform);

####  TERMINAL OBJECT  ####
$Terminal = new Terminal($autoAuth,$paymentMethodList,$language,$orderDescription,$redirectOnError,$redirect_url);

$ArrayOfItem = null; // no goods for Klana ==> normal transaction
####  ORDER OBJECT  ####
$Order = new Ordercart($amount,$currencyCode,$force3DSecure,$ArrayOfItem,$orderNumber,$UpdateStoredPaymentInfo);


####  START REGISTER REQUEST  ####
$RegisterRequest = new RegisterRequest($AvtaleGiro,$CardInfo,$Customer,$description,$DnBNorDirectPayment,$Environment,$MicroPayment,$Order,$Recurring,$serviceType,$Terminal,$transactionId,$transactionReconRef);


####  ARRAY WITH REGISTER PARAMETERS  ####
$InputParametersOfRegister = array
(
        "token"                 => $token,
        "merchantId"            => $merchantId,
        "request"               => $RegisterRequest
);

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
  
  $terminal_parameters = "?merchantId=".$merchantId."&transactionId=".$RegisterResult->TransactionId;  
  $process_parameters = "?transactionId=".$RegisterResult->TransactionId;  
  error_log('Kreditkort initialized. Ordernr: '.$tempOrderID);
  ?>
    <div class="row">
      <div class="col-sm-12 text-center text-danger" style="font-size:18px"><strong>*Viktigt! Ditt kort ska vara öppet för internetköp.</strong></div>
      <div class="col-sm-12">
        <iframe src="<?php echo $terminal.$terminal_parameters; ?>" height="800" width="100%" scrolling="auto"></iframe>
      </div>
    </div>
    <?php }catch (SoapFault $fault){ error_log('Kreditkort initialized exception. Ordernr: '.$tempOrderID.'. '.$fault->faultstring); ?>
    <div class="row">
      <div class="col-sm-12">
        <div class="alert alert-danger"><?php echo $fault->faultstring; ?></div>
        <div style="padding-top:10px;"> <a href="<?php echo SITEURL.$shopping_cart_link; ?>" class="btn btn-primary web_btn pull-right"><?php echo GO_BACK; ?></a></div>
      </div>
    </div>
    <?php } ?>
  </div>
</section>

?>
