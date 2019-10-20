<?php

namespace Nets;

class QueryRequest {
  
 public $TransactionId;

 
 function __construct   (
        $TransactionId
   )
   {
        $this->TransactionId = $TransactionId;

   }
};

?>
