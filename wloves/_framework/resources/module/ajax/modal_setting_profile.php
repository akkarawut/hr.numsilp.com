<?php
require_once '../../../../_framework/import_for_ajax.php';
W_Structure::loadMetaForAjax();

$data_image_profile = $_POST['json_image_profile'];

// ถ้าไม่มีข้อมูลให้ใช้ค่าเริ่มต้น
if (!$data_image_profile) {
  $data_image_profile  = [
    'profile_type' => 'circle',
    'profile_size' => '',
    'alignment' => '',
    'is_show_detail' => 0,
    'is_show_add' => 0,
    'is_show_profile' => 0,
    'is_update' => 0,
    'is_required' =>  0,
  ];
}
?>
<form method="post" action="view/module/controller_setting.php?c=<?= $_POST['code'] ?>&page=page_setting">
  <div class="modal-body p-0">
    <div class="w-card h-auto">
      <div class="w-card-title-bar border-bottom">
        <div class="d-flex flex-wrap align-items-center">
          <div class="font-18px font-Bold text-primary">กำหนดรูปแบบรูปโปรไฟล์</div>
        </div>
        <div class="btn-edit-container">
          <button type="button" data-bs-dismiss="modal" class="btn-edit cancel min-w-150px">Cancel</button>
          <button type="submit" name="submit_setting_profile" class="btn-edit save min-w-150px">Confirm</button>
        </div>
      </div>
    </div>
    <div class="container-fluid px-20px">
      <div class="row">
        <div class="col-sm-12 mt-20px mb-20px">
          <div class="font-15px fw-bold">การปรับแต่ง</div>
          <?php TiwForm::normal('hidden', $_POST['id'], ['name' =>  'id']); ?>
        </div>
      </div>
      <div class="row mb-15px">
        <div class="col-sm-4">
          <div class="font-16px text-secondary fw-semibold">รูปทรง<span class="text-danger">*</span></div>
        </div>
        <div class="col-sm-8">
          <div class="mb-15px">
            <?= TiwForm::normal('radio', 'circle', ['name' => 'profile_type', 'checked' => $data_image_profile['profile_type'] == 'circle' ? true : false], ['style' => '2', 'label' => 'วงกลม']); ?>
          </div>
          <div>
            <?= TiwForm::normal('radio', 'square', ['name' => 'profile_type', 'checked' => $data_image_profile['profile_type'] == 'square' ? true : false], ['style' => '2', 'label' => 'สี่เหลี่ยมจตุรัส']); ?>
          </div>
        </div>
      </div>
      <div class="row mb-15px">
        <div class="col-sm-4">
          <div class="font-16px text-secondary fw-semibold">ขนาด<span class="text-danger">*</span></div>
        </div>
        <div class="col-sm-8">
          <div class="d-flex align-items-center">
            <div class="max-w-100px w-100">
              <?= TiwForm::normal('number', $data_image_profile['profile_size'], ['name' => 'profile_size', 'required' => true]); ?>
            </div>
            <div class="fonf-16px fw-medium">Pixel</div>
          </div>
        </div>
      </div>
      <div class="row mb-15px">
        <div class="col-sm-4">
          <div class="font-16px text-secondary fw-semibold">ตำแหน่ง</div>
        </div>
        <div class="col-sm-8">
          <div class="d-flex">
            <?php
            $checked_left = $data_image_profile['alignment'] == 'left' ? 'checked' : '';
            $checked_center = $data_image_profile['alignment'] == 'center' ? 'checked' : '';
            $checked_right = $data_image_profile['alignment'] == 'right' ? 'checked' : '';
            ?>
            <div class="checkbox-bold mr-10px">
              <input type="radio" value="left" name="alignment" id="checkbox-align-left" <?= $checked_left ?>>
              <label for="checkbox-align-left">
                <div class="checkbox-img ">
                  <?= W_Structure::getfile('_framework/resources/module/assets/icon/icon-left-alignment.svg') ?>
                </div>
              </label>
            </div>
            <div class="checkbox-bold  mr-10px">
              <input type="radio" value="center" name="alignment" id="checkbox-align-center" <?= $checked_center ?>>
              <label for="checkbox-align-center">
                <div class="checkbox-img ">
                  <?= W_Structure::getfile('_framework/resources/module/assets/icon/icon-center-alignment.svg') ?>
                </div>
              </label>
            </div>
            <div class="checkbox-bold">
              <input type="radio" value="right" name="alignment" id="checkbox-align-right" <?= $checked_right ?>>
              <label for="checkbox-align-right">
                <div class="checkbox-img ">
                  <?= W_Structure::getfile('_framework/resources/module/assets/icon/icon-right-alignment.svg') ?>
                </div>
              </label>
            </div>
          </div>
        </div>
      </div>
      <hr class="border-color-bg-1px">
      <div class="row mb-20px">
        <div class="col-sm-12">
          <div class="font-15px fw-bold">การใช้งาน</div>
        </div>
      </div>
      <div class="row mb-20px">
        <div class="col-sm-12 mb-15px">
          <?= TiwForm::normal('checkbox', 1, ['name' => 'is_show_detail', 'checked' =>  $data_image_profile['is_show_detail'] ? true : false], ['style' => '1', 'label' => 'หน้า User Details (ภายใน Module)', 'is_on_off' => true]); ?>
        </div>
        <div class="col-sm-12 mb-15px">
          <?= TiwForm::normal('checkbox', 1, ['name' => 'is_show_add', 'checked' =>  $data_image_profile['is_show_add'] ? true : false], ['style' => '1', 'label' => 'หน้าเพิ่มข้อมูล (Add)', 'is_on_off' => true]); ?>
        </div>
        <div class="col-sm-12">
          <?= TiwForm::normal('checkbox', 1, ['name' => 'is_show_profile', 'checked' =>  $data_image_profile['is_show_profile'] ? true : false], ['style' => '1', 'label' => 'หน้าโปรไฟล์', 'is_on_off' => true]); ?>
        </div>
      </div>
      <hr class="border-color-bg-1px">
      <div class="row mb-20px">
        <div class="col-sm-12">
          <div class="font-15px fw-bold">ข้อจำกัด / ข้อกำหนดเพิ่มเติม</div>
        </div>
      </div>
      <div class="row">
        <div class="col-sm-12 mb-15px">
          <div class="d-flex align-items-center">
            <div>
              <?= W_Structure::getfile('_framework/resources/module/assets/icon/check-primary.svg') ?>
            </div>
            <div class="text-primary ml-10px">
              จำกัดการอัปโหลดเพียง 1 รูป
            </div>
          </div>
        </div>
        <div class="col-sm-12 mb-15px">
          <?= TiwForm::normal('checkbox', '', ['name' => 'is_update', 'checked' =>  $data_image_profile['is_update'] ? true : false], ['style' => '1', 'label' => 'สามารถแก้ไขได้หลังจากเพิ่มข้อมูล (อัปโหลดเพื่อแก้ไขภายหลัง)', 'is_on_off' => true]); ?>
        </div>
        <div class="col-sm-12 mb-15px">
          <?= TiwForm::normal('checkbox', '', ['name' => 'is_required', 'checked' =>  $data_image_profile['is_required'] ? true : false], ['style' => '1', 'label' => 'ตั้งเป็น “ข้อมูลจำเป็น” (<span class="text-danger">*</span>) จำเป็นต้องป้อนข้อมูล มิเช่นนั้นจะ ไม่สามารถบันทึกแบบฟอร์มได้', 'is_on_off' => true]); ?>
        </div>
      </div>
      <hr class="border-color-bg-5px">
    </div>
  </div>
</form>