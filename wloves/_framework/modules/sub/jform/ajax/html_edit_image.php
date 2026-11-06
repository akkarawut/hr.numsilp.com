<?php
$prefix = '../../../../../';
require_once $prefix . '_framework/import_for_ajax.php';
require_once '../php/option.php';
W_Structure::loadMetaForAjax($prefix);
$sys_function_id = isset($_POST['sys_function_id']) ? $_POST['sys_function_id'] : '';
$index = isset($_POST['index']) ? $_POST['index'] : '';
$tab_id = isset($_GET['tab']) ? $_GET['tab'] : '';
$meta_data = (json_decode($_POST['meta_data'], true));
$unique_name = isset($meta_data['unique_name']) ? $meta_data['unique_name'] : '';
$image_url = File::getPath($unique_name);
?>

<?php Tiwdal::startModal('modal_edit_set_image', 'max-w-700px'); ?>
<form id="form_edit_set_image" method="post" enctype="multipart/form-data">
  <input type="hidden" name="type" value="image">
  <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"></button>
  <div class="modal-header">
    <h5 class="modal-title text-primary">การตกแต่งหน้ากระดาษ</h5>
  </div>
  <div class="modal-body p-0">
    <div class="container-fluid px-20px">
      <div class="row">
        <div class="col-sm-12 mt-20px mb-20px">
          <div class="font-15px fw-bold text-primary">กำหนดการแสดงรูปภาพ</div>
        </div>
      </div>
      <div class="row mb-20px">
        <div class="col-sm-4">
          <div class="font-16px text-secondary fw-semibold">อัปโหลดรูปภาพ</div>
        </div>
        <div class="col-sm-8">
          <div id="scope_edit_file">
            <?php TiwForm::normal('file', $image_url, ['id' => 'event_edit_file', 'name' => 'image', 'onchange' => "uploadImage(event,'event_show_preview_img')"], []); ?>
          </div>
          <div class="text-secondary font-15px">
            <span class="font-semibold">ข้อแนะนำ:</span> ไฟล์รูปภาพ .jpg หรือ .png ขนาดไฟล์ไม่เกิน 25MB <br>
            *อัปโหลดไฟล์ที่พอดีกับขนาดจอเพื่อการแสดงผลที่ดีที่สุด
          </div>

          <?php if ($image_url) { ?>
            <div id="event_show_preview_img" class="img-preview">
              <img src="<?= $image_url ?>" alt="" class="mt-20px mw-100">
            </div>
          <?php } ?>
        </div>
      </div>
      <div class="row mb-15px">
        <div class="col-sm-4">
          <div class="font-16px text-secondary fw-semibold">ขนาดรูปภาพ (pixel)</div>
        </div>
        <div class="col-sm-8">
          <div class="d-flex align-items-center">
            <div class="max-w-100px w-100">
              <?= TiwForm::normal('number', $meta_data['option_file']['img_width'], ['name' => 'img_width'], []); ?>
              <div class="mt-5px text-secondary">กว้าง</div>
            </div>
            <div class="mx-5px">
              <?= W_Structure::getfile('_framework/resources/module/assets/icon/lock.svg') ?>
            </div>
            <div class="max-w-100px w-100">
              <?= TiwForm::normal('number', $meta_data['option_file']['img_height'], ['name' => 'img_height'], []); ?>
              <div class="mt-5px text-secondary">สูง</div>
            </div>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-sm-12 mt-20px mb-20px">
          <div class="font-15px fw-bold text-dark">การแสดงผล</div>
        </div>
      </div>

      <div class="row mb-15px">
        <div class="col-sm-4">
          <div class="font-16px text-secondary fw-semibold">ขนาดแสดงผล</div>
        </div>
        <div class="col-sm-8">
          <div class="d-flex align-items-center">
            <div class="max-w-100px w-100">
              <?= TiwForm::normal('select', $meta_data['option_file']['size_display_image'], ['name' => 'size_display_image'], $size_display); ?>
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
              <input type="radio" value="left" name="alignment_img" id="checkbox-align-image--left" <?= $meta_data['option_file']['alignment_img'] == 'left' ? 'checked' : '' ?>>
              <label for="checkbox-align-image--left">
                <div class="checkbox-img ">
                  <?= W_Structure::getfile('_framework/resources/module/assets/icon/icon-left-alignment.svg') ?>
                </div>
              </label>
            </div>
            <div class="checkbox-bold mr-10px">
              <input type="radio" value="center" name="alignment_img" id="checkbox-align-image--center" <?= $meta_data['option_file']['alignment_img'] == 'center' ? 'checked' : '' ?>>
              <label for="checkbox-align-image--center">
                <div class="checkbox-img ">
                  <?= W_Structure::getfile('_framework/resources/module/assets/icon/center-alignment.svg') ?>
                </div>
              </label>
            </div>
            <div class="checkbox-bold">
              <input type="radio" value="right" name="alignment_img" id="checkbox-align-image--right" <?= $meta_data['option_file']['alignment_img'] == 'right' ? 'checked' : '' ?>>
              <label for="checkbox-align-image--right">
                <div class="checkbox-img ">
                  <?= W_Structure::getfile('_framework/resources/module/assets/icon/icon-right-alignment.svg') ?>
                </div>
              </label>
            </div>
          </div>
        </div>
      </div>
      <hr>
    </div>
  </div>
  <div class="modal-footer">
    <?=
    TiwForm::normal('btn', '', ['name' => '', 'type' => 'button', 'class' => 'btn-close-modal min-w-90px', 'data-bs-dismiss' => 'modal'], ['text' => 'ยกเลิก', 'type' => '']);
    TiwForm::normal('btn', '', ['id' => '', 'name' => 'submit_set_image_page', 'data-bs-dismiss' => 'modal', 'type' => 'button', 'class' => 'min-w-120px', 'onclick' => "confirmEditImage('$unique_name','$index');"], ['text' => 'ยินยัน', 'type' => '']);
    ?>
  </div>
</form>
<?php Tiwdal::endModal() ?>