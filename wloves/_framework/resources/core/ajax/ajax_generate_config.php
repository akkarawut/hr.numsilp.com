<?php
$_WLOVES['no_check_permission'] = 1;
require_once '../../../_framework/import.php';

$myfile = fopen("../download/config.text", "w") or die("Unable to open file!");

$html = "<?php
define('F_IS_BRIDGE', '" . F_IS_BRIDGE . "');
define('F_SYSTEM_PATH', '" . F_SYSTEM_PATH . "');

define('F_BRIDGE_API_URL', '" . F_BRIDGE_API_URL . "');
define('F_BRIDGE_API_KEY', '" . F_BRIDGE_API_KEY . "');

define('F_SITE_TITLE', '" . F_SITE_TITLE . "');
define('F_SITE_KEYWORDS', '" . F_SITE_KEYWORDS . "');
define('F_SITE_DESCRIPTION', '" . F_SITE_DESCRIPTION . "');

define('F_OG_TITLE', '" . F_OG_TITLE . "');
define('F_OG_IMAGE_URL', '" . F_OG_IMAGE_URL . "');
define('F_OG_DESCRIPTION', '" . F_OG_DESCRIPTION . "');
?>";

$status = fwrite($myfile, $html);
fclose($myfile);

echo json_encode($status);
