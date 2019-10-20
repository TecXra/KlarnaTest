<?php

namespace Nets;

// Please change the value of the following lines according to your environment

// MerchantId provided by Netaxept
$merchantId = "12001876";
//Test Admin password  = "aBsTest123";

// Token provided by Netaxept
$token = "W(t3~5G";

// WSDL location found in documentation
$wsdl="https://test.epayment.nets.eu/Netaxept.svc?wsdl";

// Redirect from Netaxept to your site
$path_parts = pathinfo($_SERVER["PHP_SELF"]);
// this is an example is showing how to add one (or several additional parameters on the terminal)
// $redirect_url = "http://" . $_SERVER["HTTP_HOST"] . $path_parts['dirname'] . "/Process.php?webshopParameter=1234567";
// $redirect_url = "http://" . $_SERVER["HTTP_HOST"] . $path_parts['dirname'] . "/Process.php";
$redirect_url = "http://". $path_parts['dirname'] . "/Process.php";

// Netaxept Terminal Location
$terminal = "https://test.epayment.nets.eu/terminal/default.aspx";

?>
