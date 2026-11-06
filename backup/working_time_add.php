<?php
$user_permission['_permission'] = ['all', 'ywptz_user', 'niemz_user', 'bmiwk_user', 'noaxt_user', 'xacpn_user'];
require_once 'wloves/_framework/import_for_website.php';
require_once 'layout/init.php';
$name_page = 'ตั้งค่ารูปแบบเวลาทำงาน';
if ($_POST) {
  if (isset($_POST['submit_add_working_time'])) {
    unset($_POST['submit_add_working_time']);
    $result  = phr_setting_working_time::addNewSettingWorkingTime(API_CODE, $_POST);
    if ($result['response_status']) {
      $redirect = 'working_time_detail.php?id=' . $result['response_data']['insert_id'];
    }
  }
  W_Main::responseAPI(/*$result, $message, $redirect*/);
}

$arr_day = [
  [
    'name' => 'วันจันทร์',
    'check_box_name' => 'monday',
    'from_time' => 'monday_fromtime',
    'to_time' => 'monday_totime',
  ],
  [
    'name' => 'วันอังคาร',
    'check_box_name' => 'tuesday',
    'from_time' => 'tuesday_fromtime',
    'to_time' => 'tuesday_totime',
  ],
  [
    'name' => 'วันพุธ',
    'check_box_name' => 'wednesday',
    'from_time' => 'wednesday_fromtime',
    'to_time' => 'wednesday_totime',
  ],
  [
    'name' => 'วันพฤหัสบดี',
    'check_box_name' => 'thursday',
    'from_time' => 'thursday_fromtime',
    'to_time' => 'thursday_totime',
  ],
  [
    'name' => 'วันศุกร์',
    'check_box_name' => 'friday',
    'from_time' => 'friday_fromtime',
    'to_time' => 'friday_totime',
  ],
  [
    'name' => 'วันเสาร์',
    'check_box_name' => 'saturday',
    'from_time' => 'saturday_fromtime',
    'to_time' => 'saturday_totime',
  ],
  [
    'name' => 'วันอาทิตย์',
    'check_box_name' => 'sunday',
    'from_time' => 'sunday_fromtime',
    'to_time' => 'sunday_totime',
  ],
]
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
                <a href="setting_working_time.php"> <?= file_get_contents('assets/icons/icon-left-arrow.svg') ?></a>
              </div>
              <h1 class="title-header m-0">รายละเอียดรูปแบบเวลาทำงาน</h1>
            </div>
          </div>
        </div>
        <hr>
      </div>

      <form method="post">
        <div class="container">
          <div class="max-w-750px mx-auto">
            <p class="sub-title my-25px">ตั้งค่าเวลาทำงานของพนักงาน กำหนดเงื่อนไขการเข้างานสาย สายมาก และขาดงาน</p>
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
                        <div class="input-title required">ชื่อรูปแบบ</div>
                        <input type="text" class="input" name="title" required>
                      </div>
                    </div>
                  </div>

                  <div class="col-lg-12 mb-15px">
                    <div class="hr-form-control">
                      <div class="input-group">
                        <div class="input-title">รายละเอียดเพิ่มเติม</div>
                        <textarea name="description" class="input"></textarea>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-lg-12 mb-15px">
                    <div class="font-15px fw-semibold mb-5px">กำหนดวันทำงาน</div>
                    <div> เลือกวันที่ต้องมาทำงาน พร้อมกำหนดเวลาเข้า - ออกงาน </div>
                  </div>
                </div>

                <div class="table-responsive">
                  <table id="table_working_time" class="table">
                    <thead>
                      <tr>
                        <th nowrap class="text-secondary font-Medium font-15px">วันทำงาน</th>
                        <th nowrap class="text-secondary font-Medium font-15px">เวลาควรเข้างาน</th>
                        <th nowrap class="text-secondary font-Medium font-15px">เวลาออกงาน</th>
                        <th nowrap class="text-secondary font-Medium font-15px">เวลาทำงานรวม : วัน (ชั่วโมง)</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach ($arr_day as $day) { ?>
                        <tr class="scope_row_working" data-time="0" data-end_time="0">
                          <td nowrap>
                            <div class="d-flex align-items-center">
                              <div class="mr-15px">
                                <?= TiwForm::normal('checkbox', 1, ['name' => $day['check_box_name'], 'class' => '', 'checked' => 0, 'class' => 'event_checkbox_day', 'onchange' => 'eventUseDay(this);'], ['style' => '3']); ?>
                              </div>
                              <div><?= $day['name'] ?></div>
                            </div>
                          </td>
                          <td nowrap class="box-disabled">
                            <div class="hr-form-control">
                              <div class="input-group">
                                <input type="time" class="input" name="<?= $day['from_time'] ?>" onchange="changeTime(this,'start_time');" readonly>
                              </div>
                            </div>
                          </td>
                          <td nowrap class="box-disabled">
                            <div class="hr-form-control">
                              <div class="input-group">
                                <input type="time" class="input" name="<?= $day['to_time'] ?>" onchange="changeTime(this,'end_time');" readonly>
                              </div>
                            </div>
                          </td>
                          <td nowrap>
                            <span class="cal_time">
                              <span class="text-primary">ไม่นับเวลาทำงาน</span>
                            </span>
                          </td>
                        </tr>
                      <?php } ?>
                    </tbody>
                  </table>
                </div>

                <div class="row ">
                  <div class="col-12">
                    <div class="text-primary font-15px fw-semibold mb-20px">รวมเวลาทำงานทั้งสัปดาห์ : <span class="sum_hrs">0</span> ชั่วโมง<span class="sum_min">0</span> นาที</div>
                  </div>

                  <div class="col-lg-12 mb-15px">
                    <div class="font-15px fw-semibold">กำหนดเงื่อนไขสำหรับการเข้างานสาย</div>
                  </div>
                </div>

                <div class="scope_condition row align-items-center mb-15px">
                  <div class="mb-5px col-sm-7">
                    1. จะนับว่าพนักงานเข้างานสายเมื่อเกินเวลาเข้างาน
                    <span class="text-danger">*</span>
                  </div>
                  <div class="mb-5px col-sm-2 col-12">
                    <div class="hr-form-control pt-0">
                      <div class="input-group">
                        <input type="number" class="input event_input" name="late_minute" required>
                        <div class="input-title-abs">นาที</div>
                      </div>
                    </div>
                  </div>
                  <div class="mb-5px col-sm-auto col-12">
                    <?= TiwForm::normal('checkbox', 1, ['name' => 'is_late_minute', 'class' => 'event_checkbox_condtion', 'checked' => 0], ['style' => '3', 'label' => 'มองข้ามเงื่อนไข']); ?>
                  </div>
                </div>
                <div class="scope_condition row align-items-center mb-15px">
                  <div class="mb-5px col-sm-7">
                    2. จะนับว่าพนักงานเข้างานสายมากเมื่อเกินเวลาเข้างาน
                    <span class="text-danger">*</span>
                  </div>
                  <div class="mb-5px col-sm-2 col-12">
                    <div class="hr-form-control pt-0">
                      <div class="input-group">
                        <input type="number" class="input event_input" name="very_late_minute" required>
                        <div class="input-title-abs">นาที</div>
                      </div>
                    </div>
                  </div>
                  <div class="mb-5px col-sm-auto col-12">
                    <?= TiwForm::normal('checkbox', 1, ['name' => 'is_very_late_minute', 'class' => 'event_checkbox_condtion', 'checked' => 0], ['style' => '3', 'label' => 'มองข้ามเงื่อนไข']); ?>
                  </div>
                </div>
                <div class="scope_condition row align-items-center mb-15px">
                  <div class="mb-5px col-sm-7">
                    3. จะนับว่าพนักงานขาดงานเมื่อเกินเวลาเข้างาน
                    <span class="text-danger">*</span>
                  </div>
                  <div class="mb-5px col-sm-2 col-12">
                    <div class="hr-form-control pt-0">
                      <div class="input-group">
                        <input type="number" class="input event_input" name="missing_minute" required>
                        <div class="input-title-abs">นาที</div>
                      </div>
                    </div>
                  </div>
                  <div class="mb-5px col-sm-auto col-12">
                    <?= TiwForm::normal('checkbox', 1, ['name' => 'is_missing_minute', 'class' => 'event_checkbox_condtion', 'checked' => 0], ['style' => '3', 'label' => 'มองข้ามเงื่อนไข']); ?>
                  </div>
                </div>

                <div class="row mt-20px">
                  <div class="col-lg-12">
                    <div class="d-flex justify-content-end">
                      <a href="setting_working_time.php" class="btn btn-default btn-lg rounded-15px max-w-150px w-100 me-2">
                        <div class="fw-semibold">ยกเลิก</div>
                      </a>
                      <button type="submit" name="submit_add_working_time" class="btn btn-primary btn-lg rounded-15px max-w-150px w-100">
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
    W_Main::loadAsset('assets/js/working_time.js');
    ?>

</body>

</html>