<?php

namespace Nets;

class Environment {
  
 public $Language;
 public $OS;
 public $WebServicePlatform;
 
 function __construct
   (
        $Language,
        $OS,
        $WebServicePlatform
   )
   {
        $this->Language           = $Language;
        $this->OS                 = $OS;
        $this->WebServicePlatform = $WebServicePlatform;
   }
};

?>
