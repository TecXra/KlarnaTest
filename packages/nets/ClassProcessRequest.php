<?php

namespace Nets;

class ProcessRequest {
  
  public $Description;
  public $Operation;
  public $TransactionAmount;
  public $TransactionId;
  public $TransactionReconRef;
 
  function __construct
  (
    $Description,
    $Operation,
    $TransactionAmount,
    $TransactionId,
    $TransactionReconRef
   )
   {
        $this->Description          = $Description;
        $this->Operation            = $Operation;
        $this->TransactionAmount    = $TransactionAmount;
        $this->TransactionId        = $TransactionId;
        $this->TransactionReconRef  = $TransactionReconRef;
   }
};

?>
