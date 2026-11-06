<?php
$_WLOVES['no_check_permission'] = 1;
$path_link = '../../../../../';
require_once  $path_link . '_framework/import_for_ajax.php';


$id          = isset($_POST['id']) ? $_POST['id'] : '';
$code        = isset($_POST['code']) ? $_POST['code'] : '';
$name_state  = 'homepgify_table_' . $_POST['id'] . '' . $code . '_' . $_POST['pathname'];
$data_sort   = isset($_POST['data_sort']) ? $_POST['data_sort'] : [];
$colum_index = isset($_POST['colum_index']) ? $_POST['colum_index'] : '';
$item_pagify_count = isset($_POST['item_pagify_count']) ? $_POST['item_pagify_count'] : '';


$save_state  = (SC::get($name_state)) ? SC::get($name_state) : [];

$page_no   = $_POST['page_no'];
$page_size = $_POST['page_size'];
unset($_POST['id']);
unset($_POST['code']);
unset($_POST['pathname']);
unset($_POST['data_sort']);
unset($_POST['page_no']);
unset($_POST['page_size']);
unset($_POST['colum_index']);
unset($_POST['item_pagify_count']);


$save_state['page_size']   = $page_size;
$save_state['page_no']     = $page_no;
$save_state['data_sort']   = $data_sort;
$save_state['param_send']  = $_POST;
$save_state['colum_index'] = $colum_index;
$save_state['item_pagify_count'] = $item_pagify_count;


SC::setSession($name_state, $save_state);

echo json_encode($_POST);
