<?php
require_once '../../../../_framework/import_for_ajax.php';
$language = isset($_POST['language']) ? $_POST['language'] : '';
$result = Ty::setLg($language);
echo json_encode($result);
