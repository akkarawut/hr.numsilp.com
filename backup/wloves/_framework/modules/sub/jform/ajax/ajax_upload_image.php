<?php

$prefix = '../../../../../';
require_once $prefix . '_framework/import_for_ajax.php';
W_Structure::loadMetaForAjax($prefix);

$code  = isset($_GET['c']) ? $_GET['c'] : '';
$is_edit  = isset($_GET['is_edit']) ? $_GET['is_edit'] : '';
$index  = isset($_GET['index']) ? $_GET['index'] : '';
$unique_name  = isset($_GET['unique_name']) ? $_GET['unique_name'] : '';

if ($is_edit == 1) {
  if ($_FILES && $_FILES['image']['size'] > 0) {
    $file = isset($_FILES['image']) ? $_FILES['image'] : '';
    $result = File::add($unique_name, $file);
    $return = [
      'unique_name' => $unique_name,
      'option_file' => $_POST,
    ];
  } else {
    $return = [
      'unique_name' => $unique_name,
      'option_file' => $_POST,
    ];
  }
} else {
  if ($_FILES && $_FILES['image']['size'] > 0) {
    $date = date('d-m-Y');
    $random_number = (rand(10, 10000));
    $name = strtotime($date);
    $unique_name  = 'jform_' . $name . '' . $random_number;
    $file = isset($_FILES['image']) ? $_FILES['image'] : '';
    $result = File::add($unique_name, $file);
    $return = [
      'unique_name' => $unique_name,
      'option_file' => $_POST
    ];
  }
}
echo json_encode($return);
