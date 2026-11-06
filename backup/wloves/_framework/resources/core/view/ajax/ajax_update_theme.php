<?php
require_once '../../../../../_framework/import_for_ajax.php';


$data_input = file_get_contents('php://input');
$data_input = json_decode($data_input, true);
$theme_color = 'light';
if (isset($data_input['mode']) && $data_input['mode'] == 'dark') {
  $theme_color = 'dark';
}
// $result = WLoves::setProgramSetup($data_send);
// echo json_encode($result);

$result = SC::setCookie('theme', $theme_color);
echo json_encode($theme_color);
