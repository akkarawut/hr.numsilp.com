<?php
require_once '../wloves/_framework/import_for_ajax.php';
W_Structure::loadMetaForAjax('../');
$id = $_GET['id'];

$result = phr_document_warning::checkUserDocumentWarning(API_CODE, ['user_id' => $id]);
echo json_encode($result);
