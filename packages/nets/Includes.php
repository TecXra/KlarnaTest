<?php

namespace Nets;


if (!defined('NETS_REQUEST_DIR'))
    define('NETS_REQUEST_DIR', dirname(__FILE__));

require_once(NETS_REQUEST_DIR . "/Parameters.php");
require_once(NETS_REQUEST_DIR . "/ClassOrder.php");
require_once(NETS_REQUEST_DIR . "/ClassTerminal.php");
require_once(NETS_REQUEST_DIR . "/ClassRegisterRequest.php");
require_once(NETS_REQUEST_DIR . "/ClassEnvironment.php");
require_once(NETS_REQUEST_DIR . "/ClassProcessRequest.php");
require_once(NETS_REQUEST_DIR . "/ClassQueryRequest.php");