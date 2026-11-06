<?php
$user_permission['_permission'] = ['all', 'ywptz_user', 'niemz_user', 'bmiwk_user', 'noaxt_user', 'xacpn_user'];
require_once 'wloves/_framework/import_for_website.php';
require_once 'layout/init.php';
$name_page = 'การทำงานของระบบ';
$id = isset($_GET['id']) && $_GET['id'] ? $_GET['id'] : 0;
$is_edit = isset($_GET['is_edit']) && $_GET['is_edit'] ? $_GET['is_edit'] : 0;
$ot = phr_setting_ot::getSettingOTByID(API_CODE, $id);
$role_dropdown = phr_helper::dropdownWorkingTime(API_CODE);

if ($_POST) {
  if (isset($_POST['submit_update_ot'])) {
    unset($_POST['submit_update_ot']);
    $result = phr_setting_ot::updateSettingOT(API_CODE, $ot['id'], $_POST);
    if ($result['response_status']) {
      $redirect = 'ot_conditions_detail.php?id=' . $ot['id'];
    }
  } else if (isset($_POST['submit_delete_ot'])) {
    $result = phr_setting_ot::deleteSettingOT(API_CODE, $ot['id']);
    if ($result['response_status']) {
      $redirect = 'setting_system.php?page=set_ot_payment_rate';
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
        <div class="max-w-500px mx-auto">
          <div class="d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center">
              <div class="icon-back mr-10px">
                <?php if (!$is_edit) { ?>
                  <a href="setting_system.php?page=set_ot_payment_rate"> <?= file_get_contents('assets/icons/icon-left-arrow.svg') ?></a>
                <?php } else {  ?>
                  <a href="ot_conditions_detail.php?id=<?= $id ?>"> <?= file_get_contents('assets/icons/icon-left-arrow.svg') ?></a>
                <?php } ?>
              </div>
              <h1 class="title-header m-0">อัตราการจ่ายค่า OT</h1>
            </div>
            <?php if (!$is_edit) { ?>
              <div class="icon-action">
                <div class="d-flex">
                  <a href="ot_conditions_detail.php?id=<?= $id ?>&is_edit=1" class="btn btn-c-icon circle btn-c-primary">
                    <?= file_get_contents('assets/icons/pencil.svg') ?>
                  </a>
                  <button type="button" class="btn btn-c-icon circle btn-c-danger" <?php Tiwdal::register('delete_ot', $ot); ?>>
                    <?= file_get_contents('assets/icons/delete.svg') ?>
                  </button>
                </div>
              </div>
            <?php } ?>
          </div>
        </div>
        <hr>
      </div>

      <form method="post" class="mb-20px">
        <div class="container">
          <div class="max-w-500px mx-auto">
            <div class="sub-title my-25px">
              <div>ตั้งค่าเงื่อนไขและอัตราการจ่ายค่าทำงานล่วงเวลาของพนักงาน</div>
              <div class="text-danger">**เงื่อนไขจะมีผลเมื่ออนุมัติคำร้องขอทำงานล่วงเวลาแล้วเท่านั้น</div>
            </div>
            <?php if ($is_edit) { ?>
              <?php include_once 'view/setting/system/form_ot_edit.php' ?>
            <?php } else {  ?>
              <?php include_once 'view/setting/system/form_ot_detail.php' ?>
            <?php } ?>
          </div>
        </div>
      </form>
    </div>



    <?php Tiwdal::startModal('delete_ot', 'modal-md modal-dialog-centered mx-auto  max-w-500px'); ?>
    <form action="" method="post">
      <div class="modal-header justify-content-center">
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
          <span class="font-18px" aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body px-20px m-auto max-w-450px pt-0">
        <div class="font-24px font-SemiBold  text-center mb-15px"><?= Ty::get(['ลบอัตราการจ่ายค่าตอบแทน OT', '']) ?></div>
        <div class="text-center">
          <div>

            คุณแน่ใจหรือไม่ที่จะลบข้อมูลเงื่อนไขนี้ หลังจากลบแล้ว จะไม่สามารถ <br>
            กู้คืนข้อมูลได้อีก แต่การลบของคุณจะไม่ส่งผลต่อรายการที่เกิดขึ้นแล้ว
          </div>
        </div>

      </div>
      <div class="modal-footer">
        <input type="hidden" name="{id}">
        <?php
        TiwForm::normal('btn', '', ['name' => '', 'type' => 'button', 'class' => 'btn-close-modal min-w-90px', 'data-bs-dismiss' => 'modal'], ['text' => Ty::get(['ยกเลิก', ''])]);
        TiwForm::normal('btn', '', ['name' => 'submit_delete_ot', 'type' => 'submit', 'class' => 'btn-danger min-w-120px'], ['text' => Ty::get(['ลบข้อมูล', ''])]);
        ?>
      </div>
    </form>
    <?php Tiwdal::endModal(); ?>
    <?php
    W_Structure::loadFooterForWebsite();
    W_Main::loadAsset('assets/js/main.js');
    ?>
</body>

</html>