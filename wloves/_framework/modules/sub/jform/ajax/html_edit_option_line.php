<?php
$prefix = '../../../../../';
require_once $prefix . '_framework/import_for_ajax.php';
W_Structure::loadMetaForAjax($prefix);
require_once '../php/option.php';
$sys_function_id = isset($_POST['sys_function_id']) ? $_POST['sys_function_id'] : '';
$index = isset($_POST['index']) ? $_POST['index'] : '';
$tab_id = isset($_GET['tab']) ? $_GET['tab'] : '';
$meta_data = (json_decode($_POST['meta_data'], true));

$size_line = (isset($meta_data['size_line']) && $meta_data['size_line']) ? $meta_data['size_line'] : '';
$color_line = (isset($meta_data['color_line']) && $meta_data['color_line']) ? $meta_data['color_line'] : '';
$custom_color_line = (isset($meta_data['custom_color_line']) && $meta_data['custom_color_line']) ? $meta_data['custom_color_line'] : '';
$size_display_line = (isset($meta_data['size_display_line']) && $meta_data['size_display_line']) ? $meta_data['size_display_line'] : '';
$alignment_line = (isset($meta_data['alignment_line']) && $meta_data['alignment_line']) ? $meta_data['alignment_line'] : 'left';
?>

<form id="form_option_line">
  <div class="modal-body p-0">
    <div class="w-card h-auto">
      <div class="w-card-title-bar border-bottom">
        <div class="d-flex flex-wrap align-items-center">
          <div class="font-16px fw-semibold text-primary"> อัลบั้ม / สไลด์โชว์</div>
        </div>
        <div class="btn-edit-container">
          <button type="button" data-bs-dismiss="modal" class="btn-edit cancel min-w-150px">Cancel</button>
          <button type="button" data-bs-dismiss="modal" class="btn-edit save min-w-150px">Confirm</button>
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

      <?php foreach (range(1, 5) as $key => $slider_img) { ?>
        <div class="row mb-15px">
          <div class="col-sm-4">
            <div class="font-16px text-secondary fw-semibold <?= $key == 0 ? 'required' : '-' ?>">หัวข้อช่องกรอก <?= $key + 1 ?></div>
          </div>
          <div class="col-sm-8">
            <?= TiwForm::normal('select', '', ['name' => 'image_slider[]', 'placeholder' => 'เลือก'], $size_display); ?>
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
              <?= TiwForm::normal('number', '', ['name' => 'slider_img_length', 'placeholder' => '']); ?>
              <div class="font-15px text-secondary mt-5px">กว้าง</div>
            </div>
            <div class="w-100px text-center">
              <?= TiwForm::normal('number', '', ['name' => 'slider_img_height', 'placeholder' => '']); ?>
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
              <?= TiwForm::normal('radio', '1', ['name' => 'type_display_image'], ['style' => '2']); ?>
            </div>
            <div>
              <div class="font-16px fw-medium">
                แสดงตามขนาดจริงของรูปภาพ
              </div>
            </div>
          </div>
          <div class="d-flex">
            <div class="mr-10px">
              <?= TiwForm::normal('radio', '2', ['name' => 'type_display_image'], ['style' => '2']); ?>
            </div>
            <div>
              <div class="font-16px fw-medium">
                แสดงรูปภาพภายในพื้นที่แสดงผล
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="row mb-15px">
        <div class="col-sm-4">
          <div class="font-16px text-secondary fw-semibold">ขนาดแสดงผล</div>
        </div>
        <div class="col-sm-8">
          <div class="d-flex align-items-center">
            <div class="max-w-100px w-100">
              <?= TiwForm::normal('select', '100', ['name' => 'size_display_line'], $size_display); ?>
            </div>
            <div class="fonf-16px fw-medium">ของพื้นที่</div>
          </div>
        </div>
      </div>

      <div class="row mb-15px">
        <div class="col-sm-4">
          <div class="font-16px text-secondary fw-semibold">ตำแหน่งแสดงผล</div>
        </div>
        <div class="col-sm-8">
          <div class="d-flex">
            <div class="checkbox-bold mr-10px">
              <input type="radio" value="left" name="alignment_line" id="checkbox-align-line-left" checked>
              <label for="checkbox-align-line-left">
                <div class="checkbox-img ">
                  <?= W_Structure::getfile('_framework/modules/sub/jform/icon/icon-left-alignment.svg') ?>
                </div>
              </label>
            </div>
            <div class="checkbox-bold mr-10px">
              <input type="radio" value="center" name="alignment_line" id="checkbox-align-line-center">
              <label for="checkbox-align-line-center">
                <div class="checkbox-img ">
                  <?= W_Structure::getfile('_framework/modules/sub/jform/icon/center-alignment.svg') ?>
                </div>
              </label>
            </div>
            <div class="checkbox-bold">
              <input type="radio" value="right" name="alignment_line" id="checkbox-align-line-right">
              <label for="checkbox-align-line-right">
                <div class="checkbox-img ">
                  <?= W_Structure::getfile('_framework/modules/sub/jform/icon/icon-right-alignment.svg') ?>
                </div>
              </label>
            </div>
          </div>
        </div>
      </div>
      <hr>
    </div>
  </div>
</form>