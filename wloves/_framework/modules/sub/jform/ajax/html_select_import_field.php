<?php
$prefix = '../../../../../';
require_once $prefix . '_framework/import_for_ajax.php';
W_Structure::loadMetaForAjax($prefix);
$template_id = $_POST['id'];
$template_list = Extra::getExtraTemplateByID($template_id);

$tab = [];
$list = [];
$data_list = [];

foreach ($template_list['meta_data'] as $key_tab => $tab_data) {
  $tab[] = $tab_data;
}


foreach ($tab as $index => $list_tab) {
  foreach ($list_tab['meta_data'] as $index_data => $data_list_tab) {
    if (isset($data_list_tab['field_name'])) {
      $data_list[$index_data]['value'] = $data_list_tab['field_name'];
      $data_list[$index_data]['name'] = $list_tab['tab_name'] . ' | ' . $data_list_tab['input_title'];
    }
  }
}

$key = [
  'value' => 'value',
  'name' => 'name',
];

$options = TiwForm::generateSelectData($data_list, $key, []);

?>
<div class="row align-items-center mb-15px">
  <div class="col-sm-4">
    <div class="required font-16px text-secondary fw-semibold">หัวข้อช่องกรอก</div>
  </div>
  <div class="col-sm-8">
    <?php
    TiwForm::normal('select', '', ['id' => 'event_select_field_name', 'name' => 'field', 'onchange' => 'showDetailImport();'], $options);
    ?>
  </div>
</div>
<div class="row align-items-center mb-20px">
  <div class="col-sm-4">
    <div class="font-16px text-secondary fw-semibold">รูปแบบช่องกรอก</div>
  </div>
  <div class="col-sm-8">
    <span class="font-16px text-secondary" id="import_type">โปรดเลือกหน้าและหัวข้อช่องกรอกก่อน</span>
  </div>
</div>
<div class="row align-items-center mb-20px">
  <div class="col-sm-4">
    <div class="font-16px text-secondary fw-semibold">DB Column name</div>
  </div>
  <div class="col-sm-8">
    <span class="font-16px text-secondary" id="import_db_column">โปรดเลือกหน้าและหัวข้อช่องกรอกก่อน</span>
  </div>
</div>
<div class="row align-items-center mb-20px">
  <div class="col-sm-4">
    <div class="font-16px text-secondary fw-semibold">หมายเหตุ</div>
  </div>
  <div class="col-sm-8">
    <span class="font-16px text-secondary" id="import_db_remark">โปรดเลือกหน้าและหัวข้อช่องกรอกก่อน</span>
  </div>
</div>