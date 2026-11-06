<?php

$prefix = '../../../../../';
require_once $prefix . '_framework/import_for_ajax.php';
W_Structure::loadMetaForAjax($prefix);

$code  = isset($_GET['c']) ? $_GET['c'] : '';
$template_id = isset($_GET['template_id']) ? $_GET['template_id'] : '';
$template = isset($_GET['template']) ? $_GET['template'] : '';

$return_data = Extra::getExtraTemplateByID($template_id);
echo json_encode($return_data);
