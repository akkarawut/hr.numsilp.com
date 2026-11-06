<?php
require_once '../../../../_framework/import_for_ajax.php';
W_Structure::loadMetaForAjax();
$data_tab_permission = $_POST['json_tab_permission'];
if (!$data_tab_permission) {
  $data_tab_permission  = [
    'is_show_add_page' => 0,
    'is_show_add_profile' => 0,
  ];
}
?>

<form method="post" action="view/module/controller_setting.php?c=<?= $_POST['code'] ?>&page=page_setting">
  <div class="modal-body p-0">
    <div class="w-card h-auto">
      <div class="w-card-title-bar border-bottom">
        <div class="d-flex flex-wrap align-items-center">
          <div class="font-18px font-Bold text-primary">ปรับแต่งข้อมูลสิทธิการเข้าถึง (Permission)</div>
        </div>
        <div class="btn-edit-container">
          <button type="button" data-bs-dismiss="modal" class="btn-edit cancel min-w-150px">Cancel</button>
          <button type="submit" name="submit_setting_permission" class="btn-edit save min-w-150px">Confirm</button>
        </div>
      </div>
    </div>
    <div class="container-fluid px-20px">
      <div class="row my-20px">
        <div class="col-sm-12">
          <div class="font-15px fw-bold">การแสดงข้อมูล</div>
          <?php TiwForm::normal('hidden', $_POST['id'], ['name' =>  'id']); ?>
        </div>
      </div>
      <div class="row mb-15px">
        <div class="col-sm-12">
          <div class="d-flex align-items-center">
            <?= W_Structure::getfile('_framework/resources/module/assets/icon/check_icon.svg') ?>
            <span class="font-16px text-primary font-Medium ml-5px">บังคับให้มีในหน้า User Details (ภายใน Module)</span>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-sm-12 mb-15px">
          <?= TiwForm::normal('checkbox', 1, ['name' => 'is_show_add_page', 'checked' => $data_tab_permission['is_show_add_page'] ? true : false], ['style' => '1', 'label' => 'หน้าเพิ่มข้อมูล (Add)', 'is_on_off' => true]); ?>
        </div>
        <div class="col-sm-12 mb-15px">
          <?= TiwForm::normal('checkbox', 1, ['name' => 'is_show_add_profile', 'checked' => $data_tab_permission['is_show_add_profile'] ? true : false], ['style' => '1', 'label' => 'หน้าโปรไฟล์ (แสดงแต่ไม่สามารถแก้ไขได้)', 'is_on_off' => true]); ?>
        </div>
      </div>
      <hr class="border-color-bg-5px">
    </div>
  </div>
</form>