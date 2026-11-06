<?php
$_WLOVES['no_check_permission'] = 1;
// require_once '../../../import.php';
$path_arr = explode('/', $_SERVER['REQUEST_URI']);
$inx_framwork = array_search('_framework', $path_arr);

foreach ($path_arr as $i => $item) {
  if ($i < $inx_framwork && $i != 0) {
    $path_add .= $item . '/';
  }
}
$path_wloves = $_SERVER['DOCUMENT_ROOT'] . '/' . $path_add;
require_once $path_wloves . '/_framework/import.php';
W_Structure::loadMetaForAjax('../../../../');

$result = Translaty::setEditWord($_POST);
echo json_encode($result);
