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
  'type' => 'Upload'
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
  'value' => 'db_column_name',
  'name' => 'name',
];
$options = TiwForm::generateSelectData($data_list, $key, ['is_data' => true]);
$index = isset($_POST['index']) ? $_POST['index'] : '';
$meta_data = (json_decode($_POST['meta_data'], true));

$image_slider_length = (isset($meta_data['image_slider_length']) && $meta_data['image_slider_length']) ? $meta_data['image_slider_length'] : '';
$image_slider_height = (isset($meta_data['image_slider_height']) && $meta_data['image_slider_height']) ? $meta_data['image_slider_height'] : '';
$type_display_image = (isset($meta_data['type_display_image']) && $meta_data['type_display_image']) ? $meta_data['type_display_image'] : '';
?>

<?php Tiwdal::startModal('modal_option_edit_slider_image', 'max-w-700px', ['slide' => true]); ?>
<form id="form_option_edit_slider_image" method="post">
  <div class="modal-body p-0">
    <div class="w-card h-auto">
      <div class="w-card-title-bar border-bottom">
        <div class="d-flex flex-wrap align-items-center">
          <div class="font-16px fw-semibold text-primary"> อัลบั้ม / สไลด์โชว์</div>
        </div>
        <div class="btn-edit-container">
          <input type="hidden" name="type" value="image_slider">
          <button type="button" data-bs-dismiss="modal" class="btn-edit cancel min-w-150px">Cancel</button>
          <button type="button" data-bs-dismiss="modal" class="btn-edit save min-w-150px" onclick="confirmEditImageSlider(<?= $index ?>);">Confirm</button>
        </div>
      </div>
    </div>
    <div class="container-fluid px-20px">
      <div class="row">
        <div class="col-sm-12 mt-20px mb-20px">
          <div class="font-15px fw-bold text-primary">กำหนดภาพประกอบ</div>
          <div class="font-15px text-secondary">ระบุหัวข้อช่องกรอก (ประเภทอัปโหลดรูปภาพ) ที่ต้องการนำข้อมูลมาแสดง</div>
        </div>
      </div>
      <?php foreach ($meta_data['image_slider'] as $key => $slider_img) { ?>
        <div class="row mb-15px">
          <div class="col-sm-4">
            <div class="font-16px text-secondary fw-semibold <?= $key == 0 ? 'required' : '' ?>">หัวข้อช่องกรอก <?= $key + 1 ?></div>
          </div>
          <div class="col-sm-8">
            <?= TiwForm::normal('select', $slider_img, ['name' => 'image_slider[]', 'id' => '', 'required' => true, 'id' => 'event_slider_image_' . $key], $options); ?>
          </div>
        </div>
      <?php } ?>

      <div class="row mb-15px">
        <div class="col-sm-4">
          <div class="font-16px text-secondary fw-semibold">พื้นที่แสดงผล (pixel)</div>
        </div>
        <div class="col-sm-8">
          <div class="d-flex align-items-center">
            <div class="w-100px mr-10px text-center">
              <?= TiwForm::normal('number', $image_slider_length, ['name' => 'image_slider_length', 'placeholder' => '']); ?>
              <div class="font-15px text-secondary mt-5px">กว้าง</div>
            </div>
            <div class="w-100px text-center">
              <?= TiwForm::normal('number', $image_slider_height, ['name' => 'image_slider_height', 'placeholder' => '']); ?>
              <div class="font-15px text-secondary mt-5px">สูง</div>
            </div>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-sm-12 mt-20px mb-20px">
          <div class="font-15px fw-bold text-dark">การแสดงผล</div>
        </div>
      </div>

      <div class="row">
        <div class="col-sm-4 mb-15px">
          <div class="font-16px text-secondary fw-semibold required">รูปแบบการแสดงผล</div>
        </div>
        <div class="col-sm-8 mb-15px">
          <div class="d-flex mb-10px">
            <div class="mr-10px">
              <?= TiwForm::normal('radio', '1', ['name' => 'type_display_image', 'checked' => $type_display_image == 1 ? 1 : 0], ['style' => '2']); ?>
            </div>
            <div>
              <div class="font-16px fw-medium">
                แสดงตามขนาดจริงของรูปภาพ
              </div>
            </div>
          </div>
          <div class="d-flex">
            <div class="mr-10px">
              <?= TiwForm::normal('radio', '2', ['name' => 'type_display_image', 'checked' => $type_display_image == 2 ? 1 : 0], ['style' => '2']); ?>
            </div>
            <div>
              <div class="font-16px fw-medium">
                แสดงรูปภาพภายในพื้นที่แสดงผล
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</form>
<?php Tiwdal::endModal() ?>