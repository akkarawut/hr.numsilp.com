<?php
$user_permission['_permission'] = ['all', 'ywptz_user', 'niemz_user', 'bmiwk_user', 'noaxt_user', 'xacpn_user'];
require_once 'wloves/_framework/import_for_website.php';
require_once 'layout/init.php';
$name_page = 'ข้อมูลสาขา';
if ($_POST) {
  if (isset($_POST['submit_add_branch'])) {
    unset($_POST['submit_add_branch']);
    $result  = phr_setting_branch::addNewSettingBranch(API_CODE, $_POST);
    if ($result['response_status']) {
      $redirect = 'branch_detail.php?id=' . $result['response_data']['insert_id'];
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
                <a href="setting_branch.php"> <?= file_get_contents('assets/icons/icon-left-arrow.svg') ?></a>
              </div>
              <h1 class="title-header m-0">บันทึกข้อมูลสาขาใหม่</h1>
            </div>
          </div>
        </div>
        <hr>
      </div>

      <form method="post">
        <div class="container">
          <div class="max-w-750px mx-auto">
            <p class="sub-title my-25px">ตั้งค่าข้อมูลสาขา สำหรับลงบันทึกเข้า - ออกงานของพนักงาน รวมถึงในข้อมูลพนักงาน</p>

            <div class="card rounded-20px">
              <div class="card-body py-25px px-30px">
                <div class="row">
                  <div class="col-12 mb-15px">
                    <div class="font-15px fw-semibold">รายละเอียด</div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-12 mb-15px">
                    <div class="hr-form-control">
                      <div class="input-group">
                        <div class="input-title required">ชื่อสาขา</div>
                        <input type="text" class="input" name="name" required value="">
                      </div>
                    </div>
                  </div>

                  <div class="col-6 mb-15px">
                    <div class="hr-form-control">
                      <div class="input-group">
                        <div class="input-title required">รหัสสาขา</div>
                        <input type="text" class="input" name="branch_code" required value="">
                      </div>
                    </div>
                  </div>

                  <div class="col-lg-12 mb-15px">
                    <div class="hr-form-control">
                      <div class="input-group">
                        <div class="input-title required">ที่ตั้ง</div>
                        <textarea name="location" class="input" value="" rows="2" required></textarea>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-12 mb-15px">
                    <div class="hr-form-control">
                      <div class="input-group">
                        <div class="input-title">ผู้ดูแล / ผู้ที่ติดต่อได้</div>
                        <input type="text" class="input" name="contact_person" value="">
                      </div>
                    </div>
                  </div>
                </div>

                <div id="scope_row_email" class="row">
                  <div class="col-12 mb-15px">
                    <div class="hr-form-control">
                      <div class="input-group">
                        <div class="input-title">อีเมล</div>
                        <input type="text" class="input" name="email[]" value="">
                      </div>
                    </div>
                  </div>
                </div>
                <button type="button" class="btn btn-light-primary w-100 mb-15px" onclick="addRow('scope_row_email','email');">+ เพิ่มอีเมล</button>

                <div id="scope_row_tel" class="row">
                  <div class="col-12 mb-15px">
                    <div class="hr-form-control">
                      <div class="input-group">
                        <div class="input-title">เบอร์โทรศัพท์</div>
                        <input type="text" class="input" name="tel[]" value="">
                      </div>
                    </div>
                  </div>
                </div>

                <button type="button" class="btn btn-light-primary w-100 mb-15px" onclick="addRow('scope_row_tel','tel');">+ เพิ่มเบอร์โทรศัพท์</button>

                <div class="row">
                  <div class="col-lg-12 mb-15px">
                    <div class="hr-form-control">
                      <div class="input-group">
                        <div class="input-title">รายละเอียดเพิ่มเติม</div>
                        <textarea name="description" class="input" value=""></textarea>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="row mt-20px">
                  <div class="col-lg-12">
                    <div class="d-flex justify-content-end">
                      <a href="setting_branch.php" class="btn btn-default btn-lg rounded-15px max-w-150px w-100 me-2">
                        <div class="fw-semibold">ยกเลิก</div>
                      </a>
                      <button type="submit" name="submit_add_branch" class="btn btn-primary btn-lg rounded-15px max-w-150px w-100">
                        <div class="fw-semibold">บันทึก</div>
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </form>
    </div>

    <?php
    W_Structure::loadFooterForWebsite();
    W_Main::loadAsset('assets/js/main.js');
    ?>

    <?php include_once 'assets/js/branch.php' ?>
</body>

</html>