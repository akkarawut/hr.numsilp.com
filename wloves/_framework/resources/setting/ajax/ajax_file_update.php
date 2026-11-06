<?php
require_once '../../../../_framework/import_for_ajax.php';

foreach ($_FILES as $file => $value) {
  if ($value['name']) {
    $result = File::add($file, $value);
  } else {
    $result = File::delete($file);
  }
}
echo json_encode($result);
