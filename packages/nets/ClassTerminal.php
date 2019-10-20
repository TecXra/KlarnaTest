<?php

namespace Nets;

class Terminal {
 public $AutoAuth;
 public $PaymentMethodList;
 public $Language;
 public $OrderDescription;
 public $RedirectOnError;
 public $RedirectUrl;
 
 function __construct
   (
        $AutoAuth,
        $PaymentMethodList,
        $Language,
        $OrderDescription,
        $RedirectOnError,
        $RedirectUrl
   )
   {
        $this->AutoAuth           = $AutoAuth;
        $this->PaymentMethodList  = $PaymentMethodList;
        $this->Language           = $Language;
        $this->OrderDescription   = $OrderDescription;
        $this->RedirectOnError    = $RedirectOnError;
        $this->RedirectUrl        = $RedirectUrl;
   }
};

?>
