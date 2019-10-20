<?php
//Test merchantId = "12001876";
//Test Admin password  = "aBsTest123";
//Test Token = "W(t3~5G";
//Test admin link =  https://test.epayment.nets.eu/
//Test WSDL "https://test.epayment.nets.eu/netaxept.svc?wsdl";
//Test $terminal = "https://test.epayment.nets.eu/terminal/default.aspx";
//http://www.betalingsterminal.no/Netthandel-forside/Teknisk-veiledning/Test-cards/


//Production $merchantId = "495897";
//Production $token = "n+3F*Ec4";
//Production $wsdl="https://epayment.nets.eu/netaxept.svc?wsdl";

$merchantId = "12001876";
$token = "W(t3~5G";
// $wsdl="https://epayment.nets.eu/netaxept.svc?wsdl";
$wsdl="https://test.epayment.nets.eu/netaxept.svc?wsdl";
// Redirect from Netaxept to your site
$path_parts = pathinfo($_SERVER["PHP_SELF"]);
// this is an example is showing how to add one (or several additional parameters on the terminal)
// $redirect_url = "http://" . $_SERVER["HTTP_HOST"] . $path_parts['dirname'] . "/Process.php?webshopParameter=1234567";

// $redirect_url = SITEURL."kredit_kort_response.php";
$redirect_url = "kredit_kort_response.php";
// Netaxept Terminal Location
// $terminal = "https://epayment.nets.eu/terminal/default.aspx";
$terminal = "https://test.epayment.nets.eu/terminal/default.aspx";


//$merchantId = "12001876";
//$token = "W(t3~5G";
//$wsdl="https://test.epayment.nets.eu/netaxept.svc?wsdl";
//$path_parts = pathinfo($_SERVER["PHP_SELF"]);
//$redirect_url = SITEURL."kredit_kort_response.php";
//$terminal = "https://test.epayment.nets.eu/terminal/default.aspx";

?>
