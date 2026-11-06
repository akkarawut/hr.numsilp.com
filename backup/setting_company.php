<?php
$user_permission['_permission'] = ['all', 'ywptz_user', 'niemz_user', 'bmiwk_user', 'noaxt_user', 'xacpn_user'];
require_once 'wloves/_framework/import_for_website.php';
require_once 'layout/init.php';

$name_page = 'ข้อมูลองค์กร / บริษัท';
$is_edit = isset($_GET['is_edit']) && $_GET['is_edit'] ? $_GET['is_edit'] : 0;

$company = phr_setting_company::getSettingCompany(API_CODE);
$business_dropdown = phr_helper::dropdownBusinessType(API_CODE);
if ($_POST) {
  if (isset($_POST['submit_update_company'])) {
    unset($_POST['submit_update_company']);
    $result = phr_setting_company::updateSettingCompany(API_CODE, $company['id'], $_POST);
  }
  $redirect = 'setting_company.php';
  W_Main::responseAPI(/*$result, $message, $redirect*/);
}
?>
<!-- MENU::ตั้งค่า_ข้อมูลองค์กร / บริษัท -->
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
        <div class="max-w-1000px mx-auto">
          <div class="d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center">
              <?php if ($is_edit) { ?>
                <div class="icon-back mr-10px">
                  <a href="setting_company.php"> <?= file_get_contents('assets/icons/icon-left-arrow.svg') ?></a>
                </div>
              <?php } ?>
              <div>
                <h1 class="title-header m-0">ข้อมูลองค์กร / บริษัท</h1>
                <?php if (!$is_edit) { ?>
                  <p class="sub-title mt-5px">รายละเอียดองค์กรเบื้องต้น สำหรับใช้งานภายในระบบเท่านั้น</p>
                <?php } ?>
              </div>
            </div>
            <?php if (!$is_edit) { ?>
              <div class="icon-action">
                <div class="d-flex">
                  <a href="?is_edit=1" class="btn btn-c-icon circle btn-c-primary">
                    <?= file_get_contents('assets/icons/pencil.svg') ?>
                  </a>
                </div>
              </div>
            <?php } ?>
          </div>
        </div>
        <hr>
      </div>

      <?php if ($is_edit) { ?>
        <?php include_once 'view/setting/company/form_edit.php' ?>
      <?php } else {  ?>
        <?php include_once 'view/setting/company/form_detail.php' ?>
      <?php } ?>
    </div>
    <?php
    W_Structure::loadFooterForWebsite();
    W_Main::loadAsset('assets/js/main.js');
    ?>
    <script>
      function addRow(t, type) {
        let scope = document.getElementById(t);
        let html = ``;
        if (type == 'email') {
          html =
            `
          <div class="col-12 mb-15px __event_row">
            <div class="hr-form-control">
              <div class="input-group">
                <input type="text" class="input" name="email[]" required value="">
              </div>
              <div class="input-delete">
                <button type="button" class="btn-delete-text" onclick="deleteRow(this);">
                  <?= file_get_contents('assets/icons/remove.svg') ?>
                </button>
              </div>
            </div>
          </div>
          `;
          scope.insertAdjacentHTML('beforeend', html);
        } else if (type == 'tel') {
          html =
            `
          <div class="col-12 mb-15px __event_row">
            <div class="hr-form-control">
              <div class="input-group">
                <input type="text" class="input" name="tel[]" required value="">
              </div>
              <div class="input-delete">
                <button type="button" class="btn-delete-text" onclick="deleteRow(this);">
                  <?= file_get_contents('assets/icons/remove.svg') ?>
                </button>
              </div>
            </div>
          </div>
          `;
          scope.insertAdjacentHTML('beforeend', html);
        }
      }

      function deleteRow(t) {
        t.closest('.__event_row').remove();
      }
    </script>
</body>

</html>