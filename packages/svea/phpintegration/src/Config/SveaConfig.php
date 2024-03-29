<?php
namespace Svea;
/**
 * The SveaConfig class contains the authorization credentials used in requests
 * to Svea services. It provide credential data to SveaConfigurationProvider.
 *
 * You may modify this file to enable use of the integration package with your
 * own account credentials. Replace the values in the arrays in $prodConfig and
 * $testConfig with your credentials (supplied by your Svea account manager).
 */
class SveaConfig {

    const SWP_TEST_URL = "https://test.sveaekonomi.se/webpay/payment";
    const SWP_PROD_URL = "https://webpay.sveaekonomi.se/webpay/payment";
    const SWP_TEST_WS_URL = "https://webservices.sveaekonomi.se/webpay_test/SveaWebPay.asmx?WSDL";
    const SWP_PROD_WS_URL = "https://webservices.sveaekonomi.se/webpay/SveaWebPay.asmx?WSDL";
    const SWP_TEST_HOSTED_ADMIN_URL = "https://test.sveaekonomi.se/webpay/rest/"; // ends with "/" as we need to add request method
    const SWP_PROD_HOSTED_ADMIN_URL = "https://webpay.sveaekonomi.se/webpay/rest/"; // ends with "/" as we need to add request method

    const SWP_TEST_ADMIN_URL = "https://partnerweb.sveaekonomi.se/WebPayAdminService_test/AdminService.svc/backward"; // /backward => SOAP 1.1
    const SWP_PROD_ADMIN_URL = "https://partnerweb.sveaekonomi.se/WebPayAdminService/AdminService.svc/backward"; // /backward => SOAP 1.1

    const SWP_TEST_PREPARED_URL = "https://test.sveaekonomi.se/webpay/preparedpayment/";
    const SWP_PROD_PREPARED_URL = "https://webpay.sveaekonomi.se/webpay/preparedpayment/";

    /**
     * Replace the provided Svea test account credentials with your own to use
     * the package with your own account.
     *
     * @return \Svea\SveaConfigurationProvider
     */
    public static function getTestConfig() {
        $testConfig = array();


        // test credentials for Sweden
        $testConfig["SE"] =
            array("auth" =>
                array(
                    // invoice payment method credentials for SE, i.e. client number, username and password
                    // replace with your own, or leave blank
                    \ConfigurationProvider::INVOICE_TYPE =>
                    array(
                        "username" => "CITYDACK.SE",
                        "password" => "Re9GtXfI",
                        "clientNumber" => 77629
                    ),

                    // payment plan payment method credentials for SE
                    // replace with your own, or leave blank
                    \ConfigurationProvider::PAYMENTPLAN_TYPE =>
                    array(
                        "username" => "CITYDACK.SE",
                        "password" => "Re9GtXfI",
                        "clientNumber" => 55513
                    ),

                    // card and direct bank payment method credentials, i.e. merchant id and secret word
                    // replace with your own, or leave blank
                    // \ConfigurationProvider::HOSTED_TYPE =>
                    // array(
                    //     "merchantId" => 2021,
                    //     "secret" => "c584e38a3a5451ff7444d5ae1b899443bfe8535d747e59b588f92b35ae89a40ec0aeef280f492853c3a05b220e85831be8cbcb06a25d016e694d05df980f88fc"
                    // )
                    \ConfigurationProvider::HOSTED_TYPE =>
                    array(
                        "merchantId" => 2137,
                        "secret" => "db97ea4bd95f1dcf557556dff66ea3bc158e72bb8cc020774e7a980117c43be553349ed4540fbddc6a950c8c54921cb10edcd9e3e0a1326d78826ad5a99b4186"
                    )
                )
            )
        ;

        $testConfig["NO"] = array("auth" =>
                                array(
                                    \ConfigurationProvider::INVOICE_TYPE     => array(
                                            "username" => "CITYDACK.SE",
                                            "password" => "Re9GtXfI",
                                            "clientNumber" => 77629
                                        ),
                                    \ConfigurationProvider::PAYMENTPLAN_TYPE => array(
                                            "username" => "CITYDACK.SE",
                                            "password" => "Re9GtXfI",
                                            "clientNumber" => 55513
                                        ),
                                    \ConfigurationProvider::HOSTED_TYPE      => array(
                                            "merchantId" => 2137,
                                            "secret" => "db97ea4bd95f1dcf557556dff66ea3bc158e72bb8cc020774e7a980117c43be553349ed4540fbddc6a950c8c54921cb10edcd9e3e0a1326d78826ad5a99b4186"
                                        )
                                    )
                                );
        $testConfig["FI"] = array("auth" =>
                                array(
                                    \ConfigurationProvider::INVOICE_TYPE     => array(
                                            "username" => "CITYDACK.SE",
                                            "password" => "Re9GtXfI",
                                            "clientNumber" => 77629
                                        ),
                                    \ConfigurationProvider::PAYMENTPLAN_TYPE => array(
                                            "username" => "CITYDACK.SE",
                                            "password" => "Re9GtXfI",
                                            "clientNumber" => 55513
                                        ),
                                    \ConfigurationProvider::HOSTED_TYPE      => array(
                                            "merchantId" => 2137,
                                            "secret" => "db97ea4bd95f1dcf557556dff66ea3bc158e72bb8cc020774e7a980117c43be553349ed4540fbddc6a950c8c54921cb10edcd9e3e0a1326d78826ad5a99b4186"
                                        )
                                    )
                                );
        $testConfig["DK"] = array("auth" =>
                                array(
                                    \ConfigurationProvider::INVOICE_TYPE     => array(
                                            "username" => "CITYDACK.SE",
                                            "password" => "Re9GtXfI",
                                            "clientNumber" => 77629
                                        ),
                                    \ConfigurationProvider::PAYMENTPLAN_TYPE => array(
                                            "username" => "CITYDACK.SE",
                                            "password" => "Re9GtXfI",
                                            "clientNumber" => 55513
                                        ),
                                    \ConfigurationProvider::HOSTED_TYPE      => array(
                                            "merchantId" => 2137,
                                            "secret" => "db97ea4bd95f1dcf557556dff66ea3bc158e72bb8cc020774e7a980117c43be553349ed4540fbddc6a950c8c54921cb10edcd9e3e0a1326d78826ad5a99b4186"
                                        )
                                    )
                                );
        $testConfig["NL"] = array("auth" =>
                                array(
                                    \ConfigurationProvider::INVOICE_TYPE     => array("username"   => "hollandtest", "password" => "hollandtest", "clientNumber" => 85997),
                                    \ConfigurationProvider::PAYMENTPLAN_TYPE => array("username"   => "hollandtest", "password" => "hollandtest", "clientNumber" => 86997),
                                    \ConfigurationProvider::HOSTED_TYPE      => array("merchantId" => 1130, "secret" => "8a9cece566e808da63c6f07ff415ff9e127909d000d259aba24daa2fed6d9e3f8b0b62e8ad1fa91c7d7cd6fc3352deaae66cdb533123edf127ad7d1f4c77e7a3")
                                    )
                                );
        $testConfig["DE"] = array("auth" =>
                                array(
                                    \ConfigurationProvider::INVOICE_TYPE     => array("username"   => "germanytest", "password" => "germanytest", "clientNumber" => 14997),
                                    \ConfigurationProvider::PAYMENTPLAN_TYPE => array("username"   => "germanytest", "password" => "germanytest", "clientNumber" => 16997),
                                    \ConfigurationProvider::HOSTED_TYPE      => array("merchantId" => 1130, "secret" => "8a9cece566e808da63c6f07ff415ff9e127909d000d259aba24daa2fed6d9e3f8b0b62e8ad1fa91c7d7cd6fc3352deaae66cdb533123edf127ad7d1f4c77e7a3")
                                    )
                                );

        $url =             array(
                                \ConfigurationProvider::HOSTED_TYPE      => self::SWP_TEST_URL,
                                \ConfigurationProvider::INVOICE_TYPE     => self::SWP_TEST_WS_URL,
                                \ConfigurationProvider::PAYMENTPLAN_TYPE => self::SWP_TEST_WS_URL,
                                \ConfigurationProvider::HOSTED_ADMIN_TYPE => self::SWP_TEST_HOSTED_ADMIN_URL,
                                \ConfigurationProvider::ADMIN_TYPE       => self::SWP_TEST_ADMIN_URL,
                                \ConfigurationProvider::PREPARED_URL    => self::SWP_TEST_PREPARED_URL
                            );

        $integrationproperties = array(
                                'integrationcompany' => "myintegrationcompany",
                                'integrationversion' => "myintegrationversion",
                                'integrationplatform' => "myintegrationplatform"
                            )
        ;

        return new SveaConfigurationProvider(array("url" => $url, "credentials" => $testConfig, "integrationproperties" => $integrationproperties));
    }

    public static function getProdConfig() {
        $prodConfig = array();
        $prodConfig["SE"] = array("auth" =>
                                array(
                                    \ConfigurationProvider::INVOICE_TYPE     => 
                                    array(
                                        "username" => "CITYDACK.SE",
                                        "password" => "Re9GtXfI",
                                        "clientNumber" => 77629
                                    ),
                                    \ConfigurationProvider::PAYMENTPLAN_TYPE => 
                                    array(
                                        "username" => "CITYDACK.SE",
                                        "password" => "Re9GtXfI",
                                        "clientNumber" => 55513
                                    ),
                                    \ConfigurationProvider::HOSTED_TYPE      => 
                                    array(
                                        "merchantId" => 2137,
                                        "secret" => "efdd5e7ccf7ec7809d3523dfb786c27952d34eb6d5a9e0474d04dfac4db17bf32036be38993fbc537a352d1f4c8edbf03ebf47707d1c32054d0e97eda87e0239"
                                    )
                                    )
                                );
        $prodConfig["NO"] = array("auth" =>
                                array(
                                    \ConfigurationProvider::INVOICE_TYPE     => array("username"     => "", "password" => "", "clientNumber"=> ""),
                                    \ConfigurationProvider::PAYMENTPLAN_TYPE => array("username"     => "", "password" => "", "clientNumber"=> ""),
                                    \ConfigurationProvider::HOSTED_TYPE      => array("merchantId"   => "", "secret" => "")
                                    )
                                );
        $prodConfig["FI"] = array("auth" =>
                                array(
                                    \ConfigurationProvider::INVOICE_TYPE     => array("username"     => "", "password"    => "", "clientNumber"    => ""),
                                    \ConfigurationProvider::PAYMENTPLAN_TYPE => array("username"     => "", "password"    => "", "clientNumber"    => ""),
                                    \ConfigurationProvider::HOSTED_TYPE      => array("merchantId"   => "", "secret"    => "")
                                    )
                                );
        $prodConfig["DK"] = array("auth" =>
                                array(
                                    \ConfigurationProvider::INVOICE_TYPE     => array("username"   => "", "password" => "", "clientNumber" => ""),
                                    \ConfigurationProvider::PAYMENTPLAN_TYPE => array("username"   => "", "password" => "", "clientNumber" => ""),
                                    \ConfigurationProvider::HOSTED_TYPE      => array("merchantId" => "", "secret"   => "")
                                    )
                                );
        $prodConfig["NL"] = array("auth" =>
                                array(
                                    \ConfigurationProvider::INVOICE_TYPE     => array("username"   => "", "password" => "", "clientNumber" => ""),
                                    \ConfigurationProvider::PAYMENTPLAN_TYPE => array("username"   => "", "password" => "", "clientNumber" => ""),
                                    \ConfigurationProvider::HOSTED_TYPE      => array("merchantId" => "", "secret"   => "")
                                    )
                                );
        $prodConfig["DE"] = array("auth" =>
                               array(
                                    \ConfigurationProvider::INVOICE_TYPE     => array("username"   => "", "password" => "", "clientNumber" => ""),
                                    \ConfigurationProvider::PAYMENTPLAN_TYPE => array("username"   => "", "password" => "", "clientNumber" => ""),
                                    \ConfigurationProvider::HOSTED_TYPE      => array("merchantId" => "", "secret"   => "")
                                    )
                                );

        $url =              array(
                                \ConfigurationProvider::HOSTED_TYPE      => self::SWP_PROD_URL,
                                \ConfigurationProvider::INVOICE_TYPE     => self::SWP_PROD_WS_URL,
                                \ConfigurationProvider::PAYMENTPLAN_TYPE => self::SWP_PROD_WS_URL,
                                \ConfigurationProvider::HOSTED_ADMIN_TYPE => self::SWP_PROD_HOSTED_ADMIN_URL,
                                \ConfigurationProvider::ADMIN_TYPE       => self::SWP_PROD_ADMIN_URL,
                                \ConfigurationProvider::PREPARED_URL    => self::SWP_PROD_PREPARED_URL
                            )
        ;

        $integrationproperties = array(
                                'integrationcompany' => "myintegrationcompany",
                                'integrationversion' => "myintegrationversion",
                                'integrationplatform' => "myintegrationplatform"
                            )
        ;

        return new SveaConfigurationProvider(array("url" => $url, "credentials" => $prodConfig, "integrationproperties" => $integrationproperties));
    }

    /**
     * @return \Svea\SveaConfigurationProvider
     */
    public static function getDefaultConfig() {
        return self::getTestConfig();
    }

    /**
     * getSingleCountryConfig() may be used to provide a specific single configuration to use in i.e. the package test suite.
     *
     * Provide the following credentials as parameters. If a value is set to null, a default value will be provided instead.
     *
     * @param string $countryCode
     * @param string $invoiceUsername
     * @param string $invoicePassword
     * @param string $invoiceClientNo
     * @param string $paymentplanUsername
     * @param string $paymentplanPassword
     * @param string $paymentplanClientNo
     * @param string $merchantId
     * @param string $secret
     * @param bool $prod
     * @return \Svea\SveaConfigurationProvider
     */
    public static function getSingleCountryConfig(
            $countryCode,
            $invoiceUsername, $invoicePassword, $invoiceClientNo,
            $paymentplanUsername, $paymentplanPassword, $paymentplanClientNo,
            $merchantId, $secret,
            $prod )
    {
        // if a parameter was set to null, use default instead
        $countryCode = ($countryCode == null) ? "SE" : $countryCode;

        $invoiceUsername = ($invoiceUsername == null) ? "sverigetest" : $invoiceUsername;
        $invoicePassword = ($invoicePassword == null) ? "sverigetest" : $invoicePassword;
        $invoiceClientNo = ($invoiceClientNo == null) ? "79021" : $invoiceClientNo;

        $paymentplanUsername = ($paymentplanUsername == null) ? "sverigetest" : $paymentplanUsername;
        $paymentplanPassword = ($paymentplanPassword == null) ? "sverigetest" : $paymentplanPassword;
        $paymentplanClientNo = ($paymentplanClientNo == null) ? "59999" : $paymentplanClientNo;

        $merchantId = ($merchantId == null) ? "1130" : $merchantId;
        $secret = ($secret == null ) ? "8a9cece566e808da63c6f07ff415ff9e127909d000d259aba24daa2fed6d9e3f8b0b62e8ad1fa91c7d7cd6fc3352deaae66cdb533123edf127ad7d1f4c77e7a3" : $secret;
        $prod = ($prod == null) ? false : $prod;

        // set up credentials array for given country:
        $singleCountryConfig[$countryCode] = array( "auth" =>
            array(
                \ConfigurationProvider::INVOICE_TYPE =>
                    array("username" => $invoiceUsername, "password" => $invoicePassword, "clientNumber" => $invoiceClientNo),
                \ConfigurationProvider::PAYMENTPLAN_TYPE =>
                    array("username" => $paymentplanUsername, "password" => $paymentplanPassword, "clientNumber" => $paymentplanClientNo),
                \ConfigurationProvider::HOSTED_TYPE =>
                    array("merchantId" => $merchantId, "secret" => $secret)
            )
        );

        // the prod/test endpoints
        $testurl = array(
                       \ConfigurationProvider::HOSTED_TYPE      => self::SWP_TEST_URL,
                       \ConfigurationProvider::INVOICE_TYPE     => self::SWP_TEST_WS_URL,
                       \ConfigurationProvider::PAYMENTPLAN_TYPE => self::SWP_TEST_WS_URL,
                       \ConfigurationProvider::HOSTED_ADMIN_TYPE => self::SWP_TEST_HOSTED_ADMIN_URL,
                       \ConfigurationProvider::ADMIN_TYPE  => self::SWP_TEST_ADMIN_URL,
                       \ConfigurationProvider::PREPARED_URL    => self::SWP_TEST_PREPARED_URL

        );
        $produrl = array(
                       \ConfigurationProvider::HOSTED_TYPE      => self::SWP_PROD_URL,
                       \ConfigurationProvider::INVOICE_TYPE     => self::SWP_PROD_WS_URL,
                       \ConfigurationProvider::PAYMENTPLAN_TYPE => self::SWP_PROD_WS_URL,
                       \ConfigurationProvider::HOSTED_ADMIN_TYPE => self::SWP_PROD_HOSTED_ADMIN_URL,
                       \ConfigurationProvider::ADMIN_TYPE => self::SWP_PROD_ADMIN_URL,
                       \ConfigurationProvider::PREPARED_URL    => self::SWP_PROD_PREPARED_URL
        );

        $integrationproperties = array(
                        'integrationcompany' => "myintegrationcompany",
                        'integrationversion' => "myintegrationversion",
                        'integrationplatform' => "myintegrationplatform"
                    )
        ;

        // return a ConfigurationProvider object
        return new SveaConfigurationProvider(
            array("url" => $prod ? $produrl : $testurl, "credentials" => $singleCountryConfig, "integrationproperties" => $integrationproperties)
        );
    }
}
