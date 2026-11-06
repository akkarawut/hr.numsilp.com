<?php
$user_permission['_permission'] = ['all', 'ywptz_user', 'niemz_user', 'bmiwk_user', 'noaxt_user', 'xacpn_user'];
require_once 'wloves/_framework/import_for_website.php';
require_once 'layout/init.php';
$name_page = 'ข้อมูลพนักงาน';
if ($_POST) {
  if (isset($_POST['submit_add_employee'])) {
    unset($_POST['submit_add_employee']);
    // $file = isset($_FILES['file_others']) ? $_FILES['file_others'] : '';
    // $file_others = [];
    // if ($file) {
    //   foreach ($file['name'] as $key => $value) {
    //     $file_others[] = [
    //       'name' => $value,
    //       'type' => $file['type'][$key],
    //       'tmp_name' => $file['tmp_name'][$key],
    //       'error' => $file['error'][$key],
    //       'size' => $file['size'][$key],
    //     ];
    //   }
    // }
    $result = phr_employee::addNewEmployee(API_CODE, $_POST, $_FILES['file_profile'], $_FILES['file_id_card'], $_FILES['file_house_regis'], $_FILES['file_edu_qual'], $_FILES['file_soldier_certificate']);
    if ($result['response_status']) {
      $redirect = 'employee_information.php';
    }
  }
  W_Main::responseAPI( /*$result, $message, $redirect*/);
}

$status_emp_arr = [
  'month' => 'พนักงานประจำ (รายเดือน)',
  'day' => 'พนักงานรายวันชั่วคราว (รายวัน)',
  'trial_work' => 'พนักงานทดลองงาน',
];

$type_dropdown = phr_helper::dropdownWorkingTime(API_CODE);
$branch_dropdown = phr_helper::dropdownBranch(API_CODE);
$per_dropdown = phr_helper::dropdownRoleEmployee(API_CODE);
$setting_basic = phr_setting_basic::getSettingBasic(API_CODE);
$bank_dropdown = Bank::select();
?>
<!-- MENU::ตั้งค่า_ตั้งค่ารูปแบบเวลา -->
<!DOCTYPE html>
<html lang="en">

<head>
  <?php
  $og_data = [
    'url' => '',
    'title' => 'HR',
    'image' => '',
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
                <a href="employee_information.php"> <?= file_get_contents('assets/icons/icon-left-arrow.svg') ?></a>
              </div>
              <h1 class="title-header m-0">ข้อมูลพนักงาน</h1>
            </div>
          </div>
        </div>
        <hr>
      </div>
      <form method="post" enctype="multipart/form-data">
        <div class="container">
          <div class="max-w-750px mx-auto">

            <div class="d-flex justify-content-center my-25px">
              <div class="box-img-upload">
                <?php
                $options = [
                  'width' => '150px',
                  'height' => '150px',
                  'bg-img' => 'assets/images/placeholder.png',
                  'is_btn' => 0,
                  'is_view' => 0,
                  'is_delete' => 0,
                  'preview_name' => '{name}',
                ];
                TiwForm::normal('upload-img', '', ['id' => 'profile-avatar', 'name' => 'file_profile'], $options);
                ?>
                <div class="icon-upload-file">
                  <img src="assets/images/btn-upload.png" alt="">
                </div>
              </div>
            </div>

            <div class="card rounded-20px">
              <div class="card-body py-25px px-30px">
                <div class="row">
                  <div class="col-12 mb-15px">
                    <div class="font-15px fw-semibold">ข้อมูลเบื้องต้นของพนักงาน</div>
                  </div>
                </div>
                <div class="row">

                  <div class="col-12 mb-15px">
                    <span class="fw-semibold">รหัสพนักงาน : </span>
                    <span class="text-primary fw-semibold">รหัสพนักงานจะถูกสร้างอัตโนมัติหลังจากบันทึก</span>
                  </div>

                  <div class="col-12 mb-15px">
                    <div class="hr-form-control">
                      <div class="input-group">
                        <div class="input-title required">ชื่อพนักงาน</div>
                        <input type="text" class="input" name="full_name" required value="">
                      </div>
                    </div>
                  </div>

                  <!-- <div class="col-12 mb-15px">
                    <div class="hr-form-control">
                      <div class="input-group">
                        <div class="input-title required">รหัสพนักงาน</div>
                        <input type="text" class="input" name="employee_number" required value="">
                      </div>
                    </div>
                  </div> -->

                  <div class="col-12 mb-15px">
                    <div class="hr-form-control">
                      <div class="input-group">
                        <div class="input-title required">รูปแบบการทำงาน</div>
                        <select name="working_time_type_id" class="input" required>
                          <option value="" selected disabled>เลือก</option>
                          <?php foreach ($type_dropdown as $val) { ?>
                            <option value="<?= $val['id'] ?>"><?= $val['title'] ?></option>
                          <?php } ?>
                        </select>
                      </div>
                    </div>
                  </div>



                  <div class="col-12 mb-15px">
                    <div class="hr-form-control">
                      <div class="input-group">
                        <div class="input-title required">สถานที่ทำงาน</div>
                        <select name="branch_id" class="input" required>
                          <option value="" selected disabled>เลือก</option>
                          <?php foreach ($branch_dropdown as $val) { ?>
                            <option value="<?= $val['id'] ?>"><?= $val['name'] ?></option>
                          <?php } ?>
                        </select>
                      </div>
                    </div>
                  </div>

                  <div class="col-12 mb-15px">
                    <div class="hr-form-control">
                      <div class="input-group">
                        <div class="input-title required">สถานะพนักงาน</div>
                        <select name="employee_status" class="input event_change_status_emp" required>
                          <option value="" selected disabled>เลือก</option>
                          <option value="month">พนักงานประจำ (รายเดือน)</option>
                          <option value="day">พนักงานชั่วคราว (รายวัน)</option>
                          <option value="trial_work">พนักงานทดลองงาน</option>
                        </select>
                      </div>
                    </div>
                  </div>
                </div>

                <div id="scope_salary" class="row">
                  <div class="col-12 mb-15px">
                    <div class="hr-form-control">
                      <div class="input-group">
                        <div class="input-title">เงินเดือน (บาท)</div>
                        <input type="number" class="input" name="salary" value="">
                      </div>
                    </div>
                  </div>

                  <div class="col-6 mb-15px">
                    <div class="d-flex align-items-center">
                      <div class="pt-15px">คำนวณเงินเดือนทุกวันที่</div>
                      <div class="hr-form-control max-w-100px mx-2">
                        <div class="input-group">
                          <div class="input-title required">วันที่</div>
                          <input type="number" step="1" min="1" max="31" class="input" name="day_cal_salary" value="" required>
                        </div>
                      </div>
                      <div class="pt-15px">ของเดือน</div>
                    </div>
                  </div>

                  <div class="col-6 mb-15px">
                    <div class="d-flex align-items-center">
                      <div class="pt-15px">และวันที่</div>
                      <div class="hr-form-control max-w-100px mx-2">
                        <div class="input-group">
                          <div class="input-title required">วันที่</div>
                          <input type="number" step="1" min="1" max="31" class="input" name="day_cal_salary2" value="" required>
                        </div>
                      </div>
                      <div class="pt-15px">ของเดือน</div>
                    </div>
                  </div>
                </div>

                <div id="scope_price_box" class="row">
                  <div class="col-lg-6 mb-15px">
                    <div class="hr-form-control">
                      <div class="input-group">
                        <div class="input-title required">ประกันสังคม</div>
                        <select name="is_security" class="input event_change_secure" required>
                          <option value="" selected disabled>เลือก</option>
                          <option value="1" <?= $setting_basic['is_security'] ? 'selected' : '' ?>>หักประกันสังคม</option>
                          <option value="0" <?= !$setting_basic['is_security'] ? 'selected' : '' ?>>ไม่หักประกันสังคม</option>
                        </select>
                      </div>
                    </div>
                  </div>
                  <div class="col-lg-3 event_display_box_secure mb-15px <?= !$setting_basic['is_security'] ? 'd-none' : '' ?>">
                    <div class="hr-form-control">
                      <div class="input-group">
                        <div class="input-title required">ยอดหัก</div>
                        <input type="number" class="input" min="0" max="9999999" step="0.01" name="security_price" value="<?= $setting_basic['security_price'] ? $setting_basic['security_price'] : 0 ?>" required>
                      </div>
                    </div>
                  </div>
                  <div class="col-lg-3 event_display_box_secure mb-15px <?= !$setting_basic['is_security'] ? 'd-none' : '' ?>">
                    <div class="hr-form-control">
                      <div class="input-group">
                        <div class="input-title required">หน่วย</div>
                        <select name="security_unit" class="input" required>
                          <option value="bath" <?= $setting_basic['security_unit'] == 'bath' ? 'selected' : '' ?>>บาท</option>
                          <option value="percent" <?= $setting_basic['security_unit'] == 'percent' ? 'selected' : '' ?>>เปอร์เซ็นต์</option>
                        </select>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-6 mb-15px">
                    <div class="hr-form-control">
                      <div class="input-group">
                        <div class="input-title">ฝ่าย</div>
                        <input type="text" class="input" name="department" value="">
                      </div>
                    </div>
                  </div>

                  <div class="col-6 mb-15px">
                    <div class="hr-form-control">
                      <div class="input-group">
                        <div class="input-title">แผนก</div>
                        <input type="text" class="input" name="division" value="">
                      </div>
                    </div>
                  </div>

                  <div class="col-6 mb-15px">
                    <div class="hr-form-control">
                      <div class="input-group">
                        <div class="input-title">ตำแหน่ง</div>
                        <input type="text" class="input" name="position" value="">
                      </div>
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="scope_chage_col col-12 mb-15px">
                    <div class="hr-form-control">
                      <div class="input-group">
                        <div class="input-title required">วันที่เริ่มทำงาน</div>
                        <input type="date" class="input" name="working_start_date" value="" required>
                      </div>
                    </div>
                  </div>
                  <div id="scope_row_date_end" class="col-6 mb-15px"> </div>
                </div>

                <div class="row">
                  <div class="col-12 mb-15px">
                    <div class="font-15px fw-semibold">ข้อมูลติดต่อ</div>
                  </div>
                </div>

                <div id="scope_row_tel" class="row">
                  <div class="col-12 mb-15px">
                    <div class="hr-form-control">
                      <div class="input-group">
                        <div class="input-title">เบอร์โทรศัพท์</div>
                        <input type="number" class="input" name="tel[]" value="">
                      </div>
                    </div>
                  </div>
                </div>

                <button type="button" class="btn btn-light-primary w-100 mb-15px" onclick="addRow('scope_row_tel','tel');">+ เพิ่มเบอร์โทรศัพท์</button>

                <div id="scope_row_email" class="row">
                  <div class="col-12 mb-15px">
                    <div class="hr-form-control">
                      <div class="input-group">
                        <div class="input-title">อีเมล</div>
                        <input type="email" class="input" name="email[]" value="">
                      </div>
                    </div>
                  </div>
                </div>
                <button type="button" class="btn btn-light-primary w-100 mb-15px" onclick="addRow('scope_row_email','email');">+ เพิ่มอีเมล</button>


                <div class="row">
                  <div class="col-lg-6 mb-15px">
                    <div class="hr-form-control">
                      <div class="input-group">
                        <div class="input-title">Facebook</div>
                        <input type="text" class="input" name="social_facebook" value="">
                      </div>
                    </div>
                  </div>
                  <div class="col-lg-6 mb-15px">
                    <div class="hr-form-control">
                      <div class="input-group">
                        <div class="input-title">Line</div>
                        <input type="text" class="input" name="social_line" value="">
                      </div>
                    </div>
                  </div>
                  <div class="col-lg-6 mb-15px">
                    <div class="hr-form-control">
                      <div class="input-group">
                        <div class="input-title">IG</div>
                        <input type="text" class="input" name="social_ig" value="">
                      </div>
                    </div>
                  </div>
                  <div class="col-lg-6 mb-15px">
                    <div class="hr-form-control">
                      <div class="input-group">
                        <div class="input-title">TikTok</div>
                        <input type="text" class="input" name="social_tiktok" value="">
                      </div>
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-12 mb-15px">
                    <div class="font-15px fw-semibold">ข้อมูลประกอบอื่น ๆ</div>
                  </div>
                </div>


                <div class="row">
                  <div class="col-12 mb-15px">
                    <div class="hr-form-control">
                      <div class="input-group">
                        <div class="input-title">ไซส์เสื้อ</div>
                        <input type="size" class="input" name="size" value="">
                      </div>
                    </div>
                  </div>
                  <div class="col-lg-12 mb-15px">
                    <div class="hr-form-control">
                      <div class="input-group">
                        <div class="input-title">หมายเหตุพนักงาน</div>
                        <textarea name="remark" class="input" value=""></textarea>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-12 mb-15px">
                    <div class="font-15px fw-semibold">ข้อมูลธนาคาร</div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-12 mb-15px">
                    <div class="hr-form-control">
                      <div class="input-group">
                        <div class="input-title">ธนาคาร</div>
                        <select name="bank_code" class="input" required>
                          <?php foreach ($bank_dropdown as $bank) { ?>
                            <option value="<?= $bank['abb'] ?>"><?= $bank['name_th'] ?></option>
                          <?php } ?>
                        </select>
                      </div>
                    </div>
                  </div>
                  <div class="col-6 mb-15px">
                    <div class="hr-form-control">
                      <div class="input-group">
                        <div class="input-title required">ชื่อบัญชี</div>
                        <input type="text" class="input" name="bank_account_name" value="" required>
                      </div>
                    </div>
                  </div>
                  <div class="col-6 mb-15px">
                    <div class="hr-form-control">
                      <div class="input-group">
                        <div class="input-title required">เลขที่บัญชี</div>
                        <input type="number" class="input" name="bank_account_number" value="" required>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-12 mb-15px">
                    <div class="font-15px fw-semibold">การยืนยันตัวตน</div>
                  </div>
                </div>

                <!-- <div id="scope_row_file" class="row"> -->
                <div class="row mb-10px">
                  <div class="col-md-3">
                    <div class="input-title">บัตรประชาชน</div>
                  </div>
                  <div class="col-md-9">
                    <?= TiwForm::normal('file', '', ['name' => 'file_id_card'], []) ?>
                  </div>
                </div>
                <div class="row mb-10px">
                  <div class="col-md-3">
                    <div class="input-title">ทะเบียนบ้าน</div>
                  </div>
                  <div class="col-md-9">
                    <?= TiwForm::normal('file', '', ['name' => 'file_house_regis'], []) ?>
                  </div>
                </div>
                <div class="row mb-10px">
                  <div class="col-md-3">
                    <div class="input-title">วุฒิการศึกษา</div>
                  </div>
                  <div class="col-md-9">
                    <?= TiwForm::normal('file', '', ['name' => 'file_edu_qual'], []) ?>
                  </div>
                </div>
                <div class="row mb-10px">
                  <div class="col-md-3">
                    <div class="input-title">ใบผ่านการเกณฑ์ทหาร</div>
                  </div>
                  <div class="col-md-9">
                    <?= TiwForm::normal('file', '', ['name' => 'file_soldier_certificate'], []) ?>
                  </div>
                </div>


                <!-- <button type="button" class="btn btn-light-primary w-100 mb-15px" onclick="addRow('scope_row_file','verify');">+ เพิ่มเอกสารยืนยันตัวตน</button> -->

                <div class="row">
                  <div class="col-12 mb-25px">
                    <div class="font-15px fw-semibold">ข้อมูลสำหรับเข้าใช้งานระบบ</div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-3 mb-15px">
                    <div class="text-secondary">
                      การเข้าใช้งานระบบ
                    </div>
                  </div>
                  <div class="col-9 mb-15px">
                    <?php
                    TiwForm::normal('checkbox', 1, ['name' => 'is_can_login', 'class' => 'event_change_txt_can_login', 'checked' => 1], ['style' => '1', 'label' => '<span class="text-primary ml-5px">เข้าใช้ได้</span>']);
                    ?>
                  </div>
                </div>
                <div class="row">
                  <div class="col-12 mb-15px">
                    <div class="hr-form-control">
                      <div class="input-group">
                        <div class="input-title required">ชื่อบัญชี</div>
                        <input type="text" class="input" name="username" value="" required>
                      </div>
                    </div>
                  </div>

                  <div class="col-12 mb-15px">
                    <div class="hr-form-control">
                      <div class="input-group">
                        <div class="input-title required">รหัสผ่าน</div>
                        <input type="password" class="input" name="password" value="" required>
                      </div>
                    </div>
                  </div>

                  <div class="col-12 mb-15px">
                    <div class="hr-form-control">
                      <div class="input-group">
                        <div class="input-title required">สิทธิการใช้งานระบบ</div>
                        <select name="user_code" class="input" required>
                          <option value="" selected disabled>เลือก</option>
                          <?php foreach ($per_dropdown as $val) { ?>
                            <option value="<?= $val['code'] ?>"><?= $val['name'] ?></option>
                          <?php } ?>
                        </select>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="row mt-20px">
                  <div class="col-lg-12">
                    <div class="d-flex justify-content-end">
                      <a href="employee_information.php" class="btn btn-default btn-lg rounded-15px max-w-150px w-100 me-2">
                        <div class="fw-semibold">ยกเลิก</div>
                      </a>
                      <button type="submit" name="submit_add_employee" class="btn btn-primary btn-lg rounded-15px max-w-150px w-100">
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

    <?php include_once 'assets/js/employee.php' ?>
</body>

</html>