<?php
require_once '../../../../../_framework/import_for_ajax.php';


if (isset($_POST['option'])) {
  $_POST['option'] = array_values($_POST['option']);
}
echo json_encode($_POST);
