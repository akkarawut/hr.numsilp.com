<?php
$user_permission['_permission'] = ['all', 'ywptz_user', 'niemz_user', 'bmiwk_user', 'noaxt_user', 'xacpn_user'];
require_once 'wloves/_framework/import_for_website.php';
require_once 'layout/init.php';
$name_page = 'การทำงานของระบบ';
if ($_POST) {
  if (isset($_POST['submit_add_setting_ot'])) {
    unset($_POST['submit_add_setting_ot']);
    $result  = phr_setting_ot::addNewSettingOT(API_CODE, $_POST);
    if ($result['response_status']) {
      $redirect = 'ot_conditions_detail.php?id=' . $result['response_data']['insert_id'];
    }
  }
  W_Main::responseAPI(/*$result, $message, $redirect*/);
}
$role_dropdown = phr_helper::dropdownWorkingTime(API_CODE);
?>
<!-- MENU::ตั้งค่า_ตั้งค่าวันหยุด -->
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
                <a href="setting_system.php?page=set_ot_payment_rate"> <?= file_get_contents('assets/icons/icon-left-arrow.svg') ?></a>
              </div>
              <h1 class="title-header m-0">กำหนดอัตราการจ่ายค่า OT ใหม่</h1>
            </div>
          </div>
        </div>
        <hr>
      </div>

      <form method="post">
        <div class="container">
          <div class="max-w-500px mx-auto">
            <div class="sub-title my-25px">
              <div>ตั้งค่าเงื่อนไขและอัตราการจ่ายค่าทำงานล่วงเวลาของพนักงาน</div>
              <div class="text-danger">**เงื่อนไขจะมีผลเมื่ออนุมัติคำร้องขอทำงานล่วงเวลาแล้วเท่านั้น</div>
            </div>
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
                        <div class="input-title required">ชื่อเงื่อนไข</div>
                        <input type="text" class="input" name="title" required>
                      </div>
                    </div>
                  </div>

                  <div class="col-lg-8 mb-15px">
                    <div class="d-flex align-items-center">
                      <div class="hr-form-control">
                        <div class="input-group">
                          <div class="input-title required">อัตราจ่าย (บาท)</div>
                          <input type="number" step="0.01" class="input" name="price" required>
                          <div class="input-title-abs">บาท</div>
                        </div>
                      </div>
                      <div class="pt-15px ms-2 nowrap">ต่อ ชั่วโมง</div>
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-lg-12 mb-15px">
                    <div class="hr-form-control">
                      <div class="input-group">
                        <div class="input-title">รายละเอียดเพิ่มเติม</div>
                        <textarea name="description" class="input"></textarea>
                      </div>
                    </div>
                  </div>

                  <div class="col-lg-12 mb-15px">
                    <?php
                    TiwForm::normal('checkbox', 1, ['name' => 'is_use', 'class' => 'event_change_txt_on_off', 'checked' => 1], ['style' => '1', 'label' => '<span class="text-primary ml-5px">เปิดใช้งาน</span>', 'label_on' => 'เปิดใช้งาน']);
                    ?>
                  </div>

                  <div class="col-lg-12 mb-15px">
                    <div class="font-15px fw-semibold mb-5px">พนักงานที่เงื่อนไขนี้มีผล</div>
                    <div>
                      เลือกรูปแบบพนักงานที่ใช้เงื่อนไขนี้ (คุณสามารถตั้งค่ารูปแบบ
                      พนักงานได้ที่ <a href="setting_working_time.php" class="text-primary text-decoration-underline">ตั้งค่ารูปแบบเวลาทำงาน</a>)
                    </div>
                  </div>

                  <div class="col-lg-12 mb-15px">
                    <?php foreach ($role_dropdown as $type) { ?>
                      <div class="mb-5px">
                        <?php
                        TiwForm::normal('checkbox', $type['id'], ['name' => 'role_use[]', 'class' => '', 'checked' => 1], ['style' => '3', 'label' => $type['title']]);
                        ?>
                      </div>
                    <?php } ?>
                  </div>
                </div>

                <div class="row">
                  <div class="col-lg-12">
                    <div class="d-flex justify-content-end">
                      <a href="setting_system.php?page=set_ot_payment_rate" class="btn btn-default btn-lg rounded-15px max-w-150px w-100 me-2">
                        <div class="fw-semibold">ยกเลิก</div>
                      </a>
                      <button type="submit" name="submit_add_setting_ot" class="btn btn-primary btn-lg rounded-15px max-w-150px w-100">
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
</body>

</html>