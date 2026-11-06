<?php

$prefix = '../../../../../';
require_once $prefix . '_framework/import_for_ajax.php';
W_Structure::loadMetaForAjax($prefix);
W_Structure::loadModules(['jform']);


$code  = isset($_GET['c']) ? $_GET['c'] : '';
$id  = isset($_GET['id']) ? $_GET['id'] : '';
$result =  Extra::getExtraColumnsByID($id);
// echo json_encode($result);
$data = [];
$html = '';
$form_condition = '';
$form_setting_template = '';
$type = $result['type'];
if ($result['type'] == 'Varchar' && $result['category'] == 'Tag') {
  $type = 'Tag';
} else if ($result['type'] == 'Varchar' && $result['category'] == 'Dropdown') {
  $type = 'Dropdown';
} else if ($result['type'] == 'Varchar' && $result['category'] == 'Auto address') {
  $type = 'Auto address';
} else if ($result['type'] == 'Varchar' && $result['category'] == 'Google map link') {
  $type = 'Google map link';
} else if ($result['type'] == 'Varchar' && $result['category'] == 'Link') {
  $type = 'Link';
} else if ($result['type'] == 'Varchar' && $result['category'] == 'Latitude_Longtitude') {
  $type = 'Latitude_Longtitude';
} else if ($result['type'] == 'Varchar' && $result['category'] == 'Youtube_embed') {
  $type = 'Youtube_embed';
} else if ($result['type'] == 'Varchar' && $result['category'] == 'Radio') {
  $type = 'Radio';
} else if ($result['type'] == 'Varchar' && $result['category'] == 'Checkbox') {
  $type = 'Checkbox';
} else if ($result['type'] == 'Varchar' && $result['category'] == 'Switch') {
  $type = 'Switch';
}
$html = '
          <div class="row">
            <div class="col-sm-12 mt-20px mb-20px">
              <div class="font-15px fw-bold">เชื่อมโยงข้อมูล</div>
            </div>
          </div>
          <div class="row mb-15px">
            <div class="col-sm-4">
              <div class="font-16px text-secondary fw-semibold">เชื่อมโยงกับ</div>
            </div>
            <div class="col-sm-8">
              <div class="font-16px text-dark fw-semibold">' . $result['name'] . '</div>
              <input type="hidden" name="field_name" value="' . $result['name'] . '">
              <input type="hidden" name="table_name" value="' . $result['table_name'] . '">
              <input type="hidden" name="code" value="' . $result['code'] . '">
              <input type="hidden" name="category" value="' . $result['category'] . '">
            </div>
          </div>
          <div class="row mb-15px">
            <div class="col-sm-4">
              <div class="font-16px text-secondary fw-semibold">DB Column name</div>
            </div>
            <div class="col-sm-8">
              <div class="font-16px text-primary fw-semibold" id="event_db_column_name" data-db_column_name="' . $result['db_column_name'] . '">' . $result['db_column_name'] . '</div>
              <input type="hidden" name="db_column_name" value="' . $result['db_column_name'] . '">
            </div>
          </div>
          <div class="row mb-15px">
            <div class="col-sm-4">
              <div class="font-16px text-secondary fw-semibold">รูปแบบช่องกรอก</div>
            </div>
            <div class="col-sm-8">
              <div class="font-16px text-dark fw-semibold" id="event_type" data-type="' . $result['type'] . '">' . $type . '</div>
              <input type="hidden" name="type" value="' . $result['type'] . '">
            </div>
          </div>
          <input type="hidden" name="field_id" value="' . $result['id'] . '">
          ';


if (($result['type'] == 'Varchar' || $result['type'] == 'Longtext') && $result['category'] == 'String') {
  $form_condition = template_form_text(false, [], $result);
  $form_setting_template = template_form_condition();
} else if ($result['type'] == 'Int') {
  $form_condition = template_form_number();
  $form_setting_template = template_form_condition();
} else if ($result['type'] == 'Decimal') {
  $form_condition = template_form_decimal(false, [], $result);
  $form_setting_template = template_form_condition();
} else if ($result['type'] == 'Date') {
  $form_condition = template_form_date();
  $form_setting_template = template_form_condition();
} else if ($result['type'] == 'Time') {
  $form_condition = template_form_time();
  $form_setting_template = template_form_condition();
} else if ($result['type'] == 'DateTime') {
  $form_condition = template_form_datetime();
  $form_setting_template = template_form_condition();
} else if ($result['type'] == 'Upload') {
  $form_condition = template_form_upload();
  $form_setting_template = template_form_condition(false, [], 'Upload');
} else if ($result['type'] == 'Varchar' && $result['category'] == 'Tag') {
  $form_condition = template_form_select_tag(false, [], $result);
  $form_setting_template = template_form_setting_condition_tag();
} else if ($result['type'] == 'Varchar' && $result['category'] == 'Dropdown') {
  $form_condition = template_form_select_dropdown(false, [], $result);
  $form_setting_template = template_form_setting_condition_dropdown();
} else if ($result['type'] == 'Varchar' && $result['category'] == 'Auto address') {
  $form_condition = template_form_auto_address();
  $form_setting_template = template_form_condition($is_edit = false, [], 'Auto address');
} else if ($result['type'] == 'Varchar' && $result['category'] == 'Latitude_Longtitude') {
  $form_condition = template_form_latitude_longitude();
} else if ($result['type'] == 'Varchar' && $result['category'] == 'Link') {
  $form_condition = template_form_link();
  $form_setting_template = template_form_condition($is_edit = false, [], 'Link');
} else if ($result['type'] == 'Varchar' && $result['category'] == 'Google map link') {
  $form_condition = template_form_google_map();
  $form_setting_template = template_form_condition($is_edit = false, [], 'Google map link');
} else if ($result['type'] == 'Varchar' && $result['category'] == 'Youtube_embed') {
  $form_condition = template_form_youtube_embed();
  $form_setting_template = template_form_condition($is_edit = false, [], 'Youtube_embed');
} else if ($result['type'] == 'Varchar' && $result['category'] == 'Radio') {
  $form_condition = template_form_radio(false, [], $result);
  $form_setting_template = template_form_setting_condition_radio();
} else if ($result['type'] == 'Varchar' &&  $result['category'] == 'Checkbox') {
  $form_condition = template_form_checkbox(false, [], $result);
  $form_setting_template = template_form_setting_condition_checkbox();
} else if ($result['type'] == 'Varchar' &&  $result['category'] == 'Switch') {
  $form_condition = template_form_switch(false, [], $result);
  $form_setting_template = template_form_setting_condition_switch();
} else {
  $form_condition = template_form_text();
  $form_setting_template = template_form_condition();
}


$data['html'] = $html;
$data['form_condition'] = $form_condition;
$data['form_setting_template'] = $form_setting_template;
// W_Main::display($data);
echo json_encode($data);
