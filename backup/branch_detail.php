<?php
$user_permission['_permission'] = ['all', 'ywptz_user', 'niemz_user', 'bmiwk_user', 'noaxt_user', 'xacpn_user'];
require_once 'wloves/_framework/import_for_website.php';
require_once 'layout/init.php';
require_once 'component/navtab.php';
$name_page = 'ข้อมูลสาขา';
$id = isset($_GET['id']) && $_GET['id'] ? $_GET['id'] : 0;
$is_edit = isset($_GET['is_edit']) && $_GET['is_edit'] ? $_GET['is_edit'] : 0;
$branch = phr_setting_branch::getSettingBranchByID(API_CODE, $id);
$branch_dropdown = phr_helper::dropdownBranch(API_CODE, ['not_id' => $branch['id']]);
if ($_POST) {
  if (isset($_POST['submit_update_branch'])) {
    unset($_POST['submit_update_branch']);
    $result = phr_setting_branch::updateSettingBranch(API_CODE, $branch['id'], $_POST);
    if ($result['response_status']) {
      $redirect = 'branch_detail.php?id=' . $branch['id'];
    }
  } else if (isset($_POST['submit_delete_data'])) {
    $to_id = isset($_POST['to_id']) && $_POST['to_id'] ? $_POST['to_id'] : '';
    $result = phr_setting_branch::deleteSettingBranch(API_CODE, $branch['id'], $to_id);
    if ($result['response_status']) {
      $redirect = 'setting_branch.php';
    }
  }
  W_Main::responseAPI(/*$result, $message, $redirect*/);
}

?>
<!-- MENU::ตั้งค่า_ตั้งค่ารูปแบบเวลา -->
<!DOCTYPE html>
<html lang="en">

<head>
  <?php
  $og_data = [
    'url'         => '',
    'title'       => 'HR',
    'image'       => '',
    'description' => '',
  ];
  W_Structure::loadMetaForWebsite('', $og_data);
  W_Main::loadAsset('assets/css/main.css');
  ?>
  <?php include_once 'layout/favicon.php' ?>
</head>

<body>
  <?php include_once 'layout/navbar.php' ?>
  <div id="hr-body">
    <?php include_once 'layout/sidebar.php' ?>
    <div id="hr-content" class="bg-white">
      <div class="container-fluid mt-5">
        <div class="max-w-750px mx-auto">
          <div class="d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center">
              <div class="icon-back mr-10px">
                <?php if ($is_edit) { ?>
                  <a href="branch_detail.php?id=<?= $id ?>"> <?= file_get_contents('assets/icons/icon-left-arrow.svg') ?></a>
                <?php } else { ?>
                  <a href="setting_branch.php"> <?= file_get_contents('assets/icons/icon-left-arrow.svg') ?></a>
                <?php } ?>
              </div>
              <h1 class="title-header m-0">รายละเอียดสาขา</h1>
            </div>
            <?php if (!$is_edit) { ?>
              <div class="icon-action">
                <div class="d-flex">
                  <a href="branch_detail.php?id=<?= $id ?>&is_edit=1" class="btn btn-c-icon circle btn-c-primary">
                    <?= file_get_contents('assets/icons/pencil.svg') ?>
                  </a>
                  <button type="button" class="btn btn-c-icon circle btn-c-danger" <?php Tiwdal::register('delete_data', $branch); ?>>
                    <?= file_get_contents('assets/icons/delete.svg') ?>
                  </button>
                </div>
              </div>
            <?php } ?>
          </div>
        </div>
        <hr>
      </div>

      <form method="post">
        <div class="container">
          <div class="max-w-750px mx-auto">
            <p class="sub-title my-25px">
              ตั้งค่าข้อมูลสาขา สำหรับลงบันทึกเข้า - ออกงานของพนักงาน รวมถึงในข้อมูลพนักงาน
            </p>
            <?php if ($is_edit) { ?>
              <?php include_once 'view/setting/branch/form_edit.php' ?>
            <?php } else {  ?>
              <?php include_once 'view/setting/branch/form_detail.php' ?>
            <?php } ?>
          </div>
        </div>
      </form>

      <?php Tiwdal::startModal('delete_data', 'modal-md modal-dialog-centered mx-auto  max-w-500px'); ?>
      <form action="" method="post">
        <div class="modal-header justify-content-center">
          <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
            <span class="font-18px" aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body px-20px m-auto max-w-450px pt-0">
          <div class="font-24px font-SemiBold  text-center mb-15px"><?= Ty::get(['ลบข้อมูลสาขา', '']) ?></div>
          <div class="text-center">
            <div>
              คุณแน่ใจหรือไม่ที่จะลบข้อมูลสาขา นี้ออกจากระบบ <br>
              คุณจำเป็นต้องย้ายข้อมูลพนักงานที่ทำงานประจำสาขานี้ <br>
              ไปยังที่ทำงานอื่น <br>
            </div>
          </div>

          <div class="alert-noti my-20px">
            <div class="me-3">
              <?= file_get_contents('assets/icons/alert-danger.svg') ?>
            </div>
            <div>
              มีพนักงาน <?= $branch['count_user'] ?> ทำงานประจำที่สาขานี้
            </div>
          </div>

          <div class="font-15px fw-semibold">ย้ายไปยังสาขาอื่น หรือ สำนักงานใหญ่</div>
          <div class="font-14px text-danger">* ระบบจะย้ายข้อมูลให้หลังจากคุณยืนยันที่จะลบ</div>

          <div class="hr-form-control mt-15px">
            <div class="input-group">
              <div class="input-title required">ชื่อรูปแบบ</div>
              <select name="to_id" class="input" required>
                <option value="" selected disabled>เลือก</option>
                <?php foreach ($branch_dropdown as $val) { ?>
                  <option value="<?= $val['id'] ?>"><?= $val['name'] ?></option>
                <?php } ?>
              </select>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <input type="hidden" name="{id}">
          <?php
          TiwForm::normal('btn', '', ['name' => '', 'type' => 'button', 'class' => 'btn-close-modal min-w-90px', 'data-bs-dismiss' => 'modal'], ['text' => Ty::get(['ยกเลิก', ''])]);
          TiwForm::normal('btn', '', ['name' => 'submit_delete_data', 'type' => 'submit', 'class' => 'btn-danger min-w-120px'], ['text' => Ty::get(['ลบข้อมูล', ''])]);
          ?>
        </div>
      </form>
      <?php Tiwdal::endModal(); ?>
      <?php
      W_Structure::loadFooterForWebsite();
      W_Main::loadAsset('assets/js/main.js');
      ?>
      <?php include_once 'assets/js/branch.php' ?>
</body>

</html>