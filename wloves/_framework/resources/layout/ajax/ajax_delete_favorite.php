<?php
require_once  '../../../../_framework/import_for_ajax.php';


$code = isset($_POST['code']) ? $_POST['code'] : '';

$favorite = W_Main::cookie('_page_favorite');
$favorite = $favorite ? json_decode($favorite, true) : [];

unset($favorite[$code]);

W_Main::cookie('_page_favorite', json_encode($favorite));
