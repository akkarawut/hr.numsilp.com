<?php
$prefix = '../../../../../';
require_once $prefix . '_framework/import_for_ajax.php';
W_Structure::loadMetaForAjax($prefix);

$code = isset($_GET['c']) ? $_GET['c'] : '';
$table_name = isset($_POST['table_name']) ? $_POST['table_name'] : '';
$temp_table_name = str_replace('func_' . $code, '', $table_name);

$where = [
  'code' => $code,
  'table_name' => $temp_table_name,
];

if (strpos($temp_table_name, 'sys_') !== false) {
  unset($where['code']);
}

$data_list = Extra::selectExtraColumns($code, $where);
array_unshift($data_list, [
  'name' => 'Select Field',
  'id' => '',
  'options' => [
    'disabled' => true
  ]
]);

$key = [
  'value' => 'id',
  'name' => 'name',
];

$options = TiwForm::generateSelectData($data_list, $key, ['is_data' => true]);
?>

<form method="post" id="form_add_field">
  <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"></button>
  <div class="modal-header">
    <h5 class="modal-title text-primary">เพิ่มช่องกรอกข้อมูล</h5>
  </div>
  <div class="modal-body">
    <div class="row mb-15px">
      <div class="col-12">
        <div class="font-15px fw-bold text-primary">เลือกข้อมูลที่ต้องการเชื่อมโยง</div>
      </div>
    </div>
    <div class="row mb-15px align-items-center">
      <div class="col-3">
        <div class="font-16px fw-semibold text-secondary">หัวข้อช่องกรอก<span class="text-danger">*</span></div>
      </div>
      <div class="col-9">
        <?= TiwForm::normal('select', '', ['name' => 'select_user_field', 'id' => 'event_select_field', 'required' => true], $options); ?>
      </div>
    </div>
    <div id="scope_show_detail"></div>
  </div>
  <div class="modal-footer">
    <input type="hidden" name="fill_value" id="input_fill" value="">
    <?=
    TiwForm::normal('btn', '', ['name' => '', 'type' => 'button', 'class' => 'btn-close-modal min-w-90px', 'data-bs-dismiss' => 'modal'], ['text' => 'ยกเลิก', 'type' => '']);
    // TiwForm::normal('btn', '', ['id' => 'event_submit_add_fill', 'name' => 'submit_add_file', 'type' => 'button', 'class' => 'min-w-120px', 'type' => 'button'], ['text' => 'ต่อไป', 'modal_id' => 'modal_add_option_fill', 'modal_data' => []]);
    TiwForm::normal('btn', '', ['id' => 'event_submit_add_fill', 'name' => 'submit_add_file', 'type' => 'button', 'class' => 'min-w-120px', 'type' => 'button'], ['text' => 'ต่อไป']);
    ?>
  </div>
</form>

<script>
  var event_select_field = document.getElementById('event_select_field');
  var input_fill = document.getElementById('input_fill');
  var event_submit_add_fill = document.getElementById('event_submit_add_fill');
  var select_field_val = event_select_field.value;
  input_fill.value = select_field_val;

  selectUserField(select_field_val);
  hideOptionOnSelect();
  event_select_field.addEventListener('change', () => {
    selectUserField(event_select_field.value);
    input_fill.value = event_select_field.value
  });

  event_submit_add_fill.addEventListener('click', () => {
    var form_add_field = document.getElementById("form_add_field")
    if (document.getElementById("form_add_field").checkValidity()) {
      let modal_select_fill = document.getElementById('add_fill');
      let modal = bootstrap.Modal.getInstance(modal_select_fill);
      modal.hide();
      let modal_add_option_fill = new bootstrap.Modal(document.getElementById('modal_add_option_fill'));
      modal_add_option_fill.show();
      let form_option = document.getElementById('form_option');
      form_option.reset();
      getOptionSelectField(event_select_field.value);
    } else {
      form_add_field.reportValidity();
    }
  })
</script>