<?php
require_once("Parameters.php");
require_once("ClassQueryRequest.php");

$transactionId = "";
if (isset($_GET['transactionId']))
{
  $transactionId = $_GET['transactionId'];
}
if (isset($_POST['transactionId']))
{
  $transactionId = $_POST['transactionId'];
}


echo "<h3><font color='gray'>Input parameters:</font></h3>";

echo "merchantId= " . $merchantId . "<br/>";
echo "token= " . $token . "<br/>";
echo "transactionId= " . $transactionId . "<br/>";


####  QUERY OBJECT  ####
$QueryRequest = new QueryRequest(
  $transactionId
); 

####  ARRAY WITH QUERY PARAMETERS  ####
$InputParametersOfQuery = array
(
  "token"       => $token,
  "merchantId"  => $merchantId,
  "request"     => $QueryRequest 
);


####  START QUERY CALL  ####
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
  
  $OutputParametersOfQuery = $client->__call('Query' , array("parameters"=>$InputParametersOfQuery));
  
  $QueryResult = $OutputParametersOfQuery->QueryResult; 
  
  echo "<h3><font color='gray'>Output parameters:</font></h3>";
  echo "<pre>"; 
  print_r($OutputParametersOfQuery);
  echo "</pre>";
  
  echo "<h3><font color='green'>Query call successfully done.</font></h3>";
  echo "<h3><a href='index.php'>Test Webshops</a><h3>";
  
} // End try
catch (SoapFault $fault) 
{
  ## Do some error handling in here...
  echo "<h3><a href='index.php'>Test Webshops</a><h3>";

  echo "<br/><font color='red'>EXCEPTION!";   
  echo "<br/><br/><h3><font color='red'>Query call failed</font></h3>";
  echo "<pre>"; 
  print_r($fault);
  echo "</pre>";
} // End catch
####  END   QUERY CALL  ####

?>
