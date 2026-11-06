<?php
$prefix = '../../../../';
require_once $prefix . '_framework/import_for_ajax.php';
W_Structure::loadMetaForAjax($prefix);
W_Structure::loadModules(['jform']);
$code = isset($_GET['c']) ? $_GET['c'] : '';
$id = isset($_GET['id']) ? $_GET['id'] : '';

$result = Jform::post_extra_form($code, $id, $_POST['template']);
echo json_encode($result);
