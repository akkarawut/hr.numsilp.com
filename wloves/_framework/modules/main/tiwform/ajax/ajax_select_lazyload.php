<?php
$_WLOVES['no_check_permission'] = 1;
require_once '../../../../import_for_ajax.php';

$api = $_POST['data'];
$search = $_POST['search'];
$value = $_POST['value'];

$is_data = isset($api['is_data']) ? $api['is_data'] : false;
$key_value = isset($api['key_value']) ? $api['key_value'] : '';
$key_name = isset($api['key_name']) ? $api['key_name'] : '';

$api['params'][0] = isset($api['params'][0]) ? $api['params'][0] : '';
$api['params'][1] = isset($api['params'][1]) ? $api['params'][1] : [];
if ($api['params'][1]) {
  $data_search = preg_replace('/(\r|\n|"|\')/', '', trim($_POST['search']));
  $text_params = json_encode($api['params'][1]);
  $text_params = str_replace('{search}', $data_search, $text_params);
  $api['params'][1] = json_decode($text_params, true);
}

$api['params'][2] = isset($api['params'][2]) ? $api['params'][2] : [
  'page_no' => 1,
  'page_size' => 200,
];

$select_list = call_user_func_array($api['api'], $api['params']);

$html_list = '';
if ($select_list) {
  foreach ($select_list as $list) {
    $selected_class = ($list[$api['value']] == $value) ? 'selected' : '';
    $encode_data = base64_encode(json_encode($list));
    $html_list .= '<div class="dropdown-item select_img_event select_list_' . $list[$api['value']] . ' ' . $selected_class . '" data-id="' . $list[$api['value']] . '" data-list="' . $encode_data . '">
                <div class="name">' . $list[$api['name']] . '</div>
              </div>';
  }
}

echo $html_list;
