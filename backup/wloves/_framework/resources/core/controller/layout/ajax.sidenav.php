<?php
$_WLOVES['no_check_permission'] = 1;
require_once '../../../../_framework/import.php';

$color = [
  'color-highlight' => $_POST['color_highlight'],
  'bg-nav' => $_POST['bg_nav'],
  'color-hover-nav' => $_POST['color_hover_nav'],
  'color-icon-text' => $_POST['color_icon_text'],
];
if (isset($_POST['submit_read_notification'])) {
  header('Content-Type: application/json');
  $api_result = Notification::read(W_User::getCurrentUserID(), 'user');
  echo json_encode($api_result);
} else if (isset($_POST['submit_toggle_theme'])) {
  header('Content-Type: application/json');
  // $api_result = WLoves::setTheme($theme);
  // css color
  // $_POST['theme'] = 'white';
  $data = [
    'color_highlight' =>  $_POST['color_highlight'],
    'color_base_background' => $_POST['bg_nav'],
    'color_selected_menu' => $_POST['color_hover_nav'],
    'color_base_icon' => $_POST['color_icon_text'],
  ];
  WLoves::setProgramSetup($data);
  $myfile = fopen("../../../../_framework/core/css/theme.css", "w") or die("Unable to open file!");
  $css_color = ":root {\n";
  foreach ($color as $key => $value) {
    $css_color .= " --" . $key . ": " . $value . ";\n";
  }
  $css_color .= "}\n";

  fwrite($myfile, $css_color);
  fclose($myfile);
}
