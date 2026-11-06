<?php
define('F_WLOVES_VERSION', '2.0');
define('F_ROOT_PHP', dirname(dirname(__FILE__)));
// include for Bridge in WLoves is at the end of this file. !!

require_once F_ROOT_PHP . '/_framework/modules/core/connect.php';
require_once F_ROOT_PHP . '/_framework/modules/core/info.php';
require_once F_ROOT_PHP . '/_framework/modules/core/main.php';

require_once 'modules/sub/translaty/main.php';

spl_autoload_register('W_Main::autoLoad');


if (!isLocalhost()) {
  ini_set('display_errors', 0);
  ini_set('display_startup_errors', 0);
  error_reporting(-1);
}
?>


<?php if (!isIP()) { ?>
  <script>
    // (function() {
    //   var method;
    //   var wloves_hide = function wloves_hide() {};
    //   var methods = [
    //     'assert', 'clear', 'count', 'debug', 'dir', 'dirxml', 'error',
    //     'exception', 'group', 'groupCollapsed', 'groupEnd', 'info', 'log',
    //     'markTimeline', 'profile', 'profileEnd', 'table', 'time', 'timeEnd',
    //     'timeStamp', 'trace', 'warn'
    //   ];
    //   var length = methods.length;
    //   var console = (window.console = window.console || {});

    //   while (length--) {
    //     method = methods[length];
    //     console[method] = wloves_hide;
    //   }
    // }());
    // window.onerror = function(message, url, lineNumber) {
    //   return true;
    // };
  </script>

<?php } ?>