<?php
define('F_WLOVES_VERSION', '2.0');
define('F_ROOT_PHP', dirname(dirname(__FILE__)));
// include for Bridge in WLoves is at the end of this file. !!

require_once F_ROOT_PHP . '/_framework/modules/core/connect.php';
require_once F_ROOT_PHP . '/_framework/modules/core/info.php';
require_once F_ROOT_PHP . '/_framework/modules/core/main.php';

require_once 'modules/sub/translaty/main.php';

spl_autoload_register('W_Main::autoLoad');
if (isset($_PAGE)) {
  $program_wolves_setup = '';
  if (F_IS_BRIDGE) {
    $program_wolves_setup = WLoves::getInitialData();
  }

  W_Wloves::init();

  W_Permission::checkModule($_PAGE, $is_ajax = true);
}
