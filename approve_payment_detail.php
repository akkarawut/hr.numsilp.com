<?php
$user_permission['_permission'] = ['all', 'ywptz_user', 'niemz_user', 'bmiwk_user', 'noaxt_user', 'xacpn_user'];
require_once 'wloves/_framework/import_for_website.php';
require_once 'layout/init.php';
$name_page = 'อนุมัติการออกเงินเดือน/ค่าจ้าง';
$id = isset($_GET['id']) ? $_GET['id'] : 0;
$type = isset($_GET['type']) ? $_GET['type'] : '';
$salary = phr_salary::getSalaryByID(API_CODE, $id);
$user_data = $salary['user_info'];
$performance_data = $salary['performance_info'];

if ($_POST) {
  if (isset($_POST['submit_payment'])) {
    $data = [
      'user_id' => $current_user_id,
      'ids' => $_POST['id'],
      'status' => 'approved',
    ];
    $result = phr_salary::updateStatusSalaryByIDS(API_CODE, $data);
  } else if (isset($_POST['submit_payment_reject'])) {
    $data = [
      'user_id' => $current_user_id,
      'ids' => $_POST['id'],
      'status' => 'reject',
      'remark' => $_POST['remark']
    ];
    $result = phr_salary::updateStatusSalaryByIDS(API_CODE, $data);
  } else if (isset($_POST['submit_payment_multiple'])) {
    $data = [
      'user_id' => $current_user_id,
      'ids' => $_POST['id'],
      'status' => 'approved',
    ];
    $result = phr_salary::updateStatusSalaryByIDS(API_CODE, $data);
  } else if (isset($_POST['submit_payment_reject_multiple'])) {
    $data = [
      'user_id' => $current_user_id,
      'ids' => $_POST['id'],
      'status' => 'reject',
      'remark' => $_POST['remark']
    ];
    $result = phr_salary::updateStatusSalaryByIDS(API_CODE, $data);
  }
  W_Main::responseAPI(/*$result, $message, $redirect*/);
}

$status_emp_arr = [
  'month' => 'พนักงานประจำ (รายเดือน)',
  'day' => 'พนักงานชั่วคราว (รายวัน)',
  'trial_work' => 'พนักงานทดลองงาน'
];

?>
<!-- MENU::อนุมัติการออกเงินเดือน/ค่าจ้าง -->
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
      <div class="container mt-5">
        <div class="mx-auto">
          <div class="d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center">
              <div class="icon-back mr-10px">
                <a href="approve_payment.php?page=<?= $type ?>"> <?= file_get_contents('assets/icons/icon-left-arrow.svg') ?></a>
              </div>
              <?php if ($salary['status'] == 'waiting_confirm') { ?>
                <h1 class="title-header m-0">รายการขออนุมัติ<?= $user_data['employee_status'] == 'day' ? 'จ่ายค่าจ้าง' : 'จ่ายเงินเดือน' ?></h1>
              <?php } else { ?>
                <h1 class="title-header m-0"><?= $user_data['employee_status'] == 'day' ? 'ค่าจ้างรายวัน' : 'รายการขออนุมัติจ่ายเงินเดือน' ?></h1>
              <?php } ?>
            </div>
            <?php if ($salary['status'] == 'waiting_confirm') { ?>
              <?php $salary['type'] = $type ?>
              <div class="icon-action">
                <div class="d-flex">
                  <button type="button" class="btn btn-icon-squere danger mr-10px" <?= Tiwdal::register('approved_payment_reject', $salary, ['is_ajax' => 1]) ?>>
                    <?= file_get_contents('assets/icons/times.svg') ?>
                  </button>
                  <button type="button" class="btn btn-icon-squere success" <?= Tiwdal::register('approved_payment', $salary, ['is_ajax' => 1]) ?>>
                    <?= file_get_contents('assets/icons/check-success.svg') ?>
                  </button>
                </div>
              </div>
            <?php } ?>
          </div>
        </div>
        <hr>
      </div>

      <div class="container mb-20px">
        <div class="mx-auto">
          <?php if ($salary['status'] == 'waiting_confirm') { ?>
            <p class="sub-title my-25px">
              <?= $type == 'history_day' ? 'ตรวจเช็คความถูกต้องค่าจ้างรายวันก่อนส่งอนุมัติ' : 'ตรวจเช็คความถูกต้องก่อนส่งอนุมัติ' ?>
            </p>
          <?php } ?>

          <div class="row">
            <div class="col-lg-7">
              <div class="card rounded-20px">
                <div class="card-body py-25px px-30px">
                  <div class="row py-7px">
                    <div class="col-12 mb-15px">
                      <div class="font-15px fw-semibold">รายละเอียดพนักงานเบื้องต้น</div>
                    </div>
                  </div>
                  <div class="row py-7px">
                    <div class="col-md-4">
                      <div class="text-secondary font-Medium">ชื่อพนักงาน</div>
                    </div>
                    <div class="col-md-8">
                      <div class="font-Medium"><?= $user_data['full_name'] ?></div>
                    </div>
                  </div>
                  <hr>
                  <div class="row py-7px">
                    <div class="col-md-4">
                      <div class="text-secondary font-Medium">รหัสพนักงาน</div>
                    </div>
                    <div class="col-md-8">
                      <div class="font-Medium"><?= $user_data['employee_number'] ?></div>
                    </div>
                  </div>
                  <hr>
                  <div class="row py-7px">
                    <div class="col-md-4">
                      <div class="text-secondary font-Medium">รูปแบบการทำงาน</div>
                    </div>
                    <div class="col-md-8">
                      <div class="font-Medium"><?= $user_data['working_time_type_name'] ?></div>
                      <div class="font-14px text-secondary">
                        <?= $user_data['working_time_hour'] ?> ชั่วโมง : สัปดาห์
                      </div>
                    </div>
                  </div>
                  <hr>
                  <div class="row py-7px">
                    <div class="col-md-4">
                      <div class="text-secondary font-Medium">สถานที่ทำงาน</div>
                    </div>
                    <div class="col-md-8">
                      <div class="font-Medium"><?= $user_data['branch_name'] ?></div>
                    </div>
                  </div>
                  <hr>
                  <div class="row py-7px">
                    <div class="col-md-4">
                      <div class="text-secondary font-Medium">สถานะพนักงาน</div>
                    </div>
                    <div class="col-md-8">
                      <div class="font-Medium">
                        <?= $status_emp_arr[$user_data['employee_status']]; ?>
                      </div>
                    </div>
                  </div>
                  <hr>

                  <div class="row py-7px">
                    <div class="col-md-4">
                      <div class="text-secondary font-Medium"><?= $user_data['employee_status'] == 'day' ? 'ค่าจ้างต่อวัน' : 'เงินเดือน' ?> (บาท)</div>
                    </div>
                    <div class="col-md-8">
                      <div class="font-Medium"><?= number_format($user_data['salary'], 2) ?> บาท</div>
                    </div>
                  </div>
                  <hr>


                  <?php if ($user_data['employee_status'] != 'day') { ?>
                    <div class="row py-7px">
                      <div class="col-md-4">
                        <div class="text-secondary font-Medium">เงื่อนไขการคำนวณเงินเดือน</div>
                      </div>
                      <div class="col-md-8">
                        <div class="font-Medium">คำนวณทุกวันที่ <?= $user_data['day_cal_salary'] ?> และวันที่ <?= $user_data['day_cal_salary2'] ?> ของเดือน </div>
                      </div>
                    </div>
                    <hr>

                    <div class="row py-7px">
                      <div class="col-md-4">
                        <div class="text-secondary font-Medium">ประกันสังคม</div>
                      </div>
                      <div class="col-md-8">
                        <?php if ($user_data['is_security'] == 1) { ?>
                          <div class="font-Medium">หักจ่าย <?= number_format($user_data['security_price'], 2) ?>
                            <?= $user_data['security_unit'] == 'percent' ? 'เปอร์เซ็นต์' : 'บาท' ?> / ต่อเดือน</div>
                        <?php } else { ?>
                          <div class="font-Medium">ไม่หักประกันสังคม</div>
                        <?php } ?>
                      </div>
                    </div>
                    <hr>
                  <?php } else { ?>

                    <div class="row py-7px">
                      <div class="col-md-4">
                        <div class="text-secondary font-Medium">เงื่อนไขการคำนวณค่าจ้าง</div>
                      </div>
                      <div class="col-md-8">
                        <div class="font-Medium">หลังอนุมัติการลงเวลา</div>
                      </div>
                    </div>
                    <hr>
                  <?php } ?>

                  <div class="row py-7px">
                    <div class="col-12 mb-15px">
                      <div class="font-15px fw-semibold">ข้อมูลบัญชีธนาคาร</div>
                    </div>
                  </div>
                  <div class="row py-7px">
                    <div class="col-md-4">
                      <div class="text-secondary font-Medium">ธนาคาร</div>
                    </div>
                    <div class="col-md-8">
                      <div class="font-Medium"><?= $user_data['bank_name'] ?></div>
                    </div>
                  </div>
                  <hr>
                  <div class="row py-7px">
                    <div class="col-md-4">
                      <div class="text-secondary font-Medium">ชื่อบัญชี</div>
                    </div>
                    <div class="col-md-8">
                      <div class="font-Medium"><?= $user_data['bank_account_name'] ?></div>
                    </div>
                  </div>
                  <hr>
                  <div class="row py-7px">
                    <div class="col-md-4">
                      <div class="text-secondary font-Medium">เลขบัญชี</div>
                    </div>
                    <div class="col-md-8">
                      <div class="font-Medium"><?= $user_data['bank_account_number'] ?></div>
                    </div>
                  </div>
                </div>
              </div>
              <?php if ($user_data['employee_status'] != 'day') { ?>
                <div class="row mt-10px">
                  <div class="col-lg-12">
                    <div class="card rounded-20px mb-15px">
                      <div class="card-body px-30px pt-25px ">
                        <div class="fw-semibold">Performance ของพนักงานในช่วงที่ผ่านมา</div>
                        <div class="">นับตั้งแต่การคำนวณเงินเดือนครั้งที่ผ่านมาถึงครั้งนี้</div>
                        <div class="py-20px">
                          <div class="card-performance">
                            <div class="font-15px font-Medium text-secondary mb-10px">Performance <span class="text-primary"><?= $performance_data['percent_performance'] ?>%</span></div>
                            <div class="progress-bar">
                              <div class="inner-progress-bar" style="width:<?= $performance_data['percent_performance'] ?>%;"></div>
                            </div>
                            <div class="grid-perfomance --sm mt-20px">
                              <div class="list-perfomance">
                                <div class="number"><?= number_format($performance_data['count_day_work'], 0) ?></div>
                                <div class="text">วันทำงาน</div>
                              </div>
                              <div class="list-perfomance">
                                <div class="number <?= $performance_data['count_late'] > 0 ? 'text-danger' : '' ?>"><?= number_format($performance_data['count_late'], 0) ?></div>
                                <div class="text">มาสาย :
                                  <span class="<?= $performance_data['count_late'] > 0 ? 'text-danger' : '' ?>">
                                    <?= $performance_data['count_late_percent'] > 0 ? '-' . number_format($performance_data['count_late_percent'], 0) : 0 ?>%
                                  </span>
                                </div>
                              </div>
                              <div class="list-perfomance">
                                <div class="number <?= $performance_data['count_very_late'] > 0 ? 'text-danger' : '' ?>"><?= number_format($performance_data['count_very_late'], 0) ?></div>
                                <div class="text">สายมาก :
                                  <span class="<?= $performance_data['count_very_late'] > 0 ? 'text-danger' : '' ?>">
                                    <?= $performance_data['count_very_late_percent'] > 0 ? '-' . number_format($performance_data['count_very_late_percent'], 0) : 0 ?>%
                                  </span>
                                </div>
                              </div>
                              <div class="list-perfomance">
                                <div class="number <?= $performance_data['count_missing'] > 0 ? 'text-danger' : '' ?>"><?= number_format($performance_data['count_missing'], 0) ?></div>
                                <div class="text">ขาด :
                                  <span class="<?= $performance_data['count_missing'] > 0 ? 'text-danger' : '' ?>">
                                    <?= $performance_data['count_missing_percent'] > 0 ? '-' . number_format($performance_data['count_missing_percent'], 0) : 0 ?>%
                                  </span>
                                </div>
                              </div>
                              <div class="list-perfomance">
                                <div class="number <?= $performance_data['count_leave_work'] > 0 ? 'text-danger' : '' ?>"><?= number_format($performance_data['count_leave_work'], 0) ?></div>
                                <div class="text">ลา</div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              <?php } ?>
            </div>
            <div class="col-lg-5">
              <div class="card rounded-20px mb-15px">
                <div class="card-body pt-25px px-30px">
                  <div class="row py-7px">
                    <div class="col-12 mb-15px">
                      <div class="font-15px fw-semibold">รายละเอียดการเงิน</div>
                    </div>
                  </div>
                  <div class="row py-7px">
                    <div class="col-md-4">
                      <div class="text-secondary font-Medium"><?= $user_data['employee_status'] == 'day' ? 'ค่าจ้าง' : 'เงินเดือน' ?></div>
                    </div>
                    <div class="col-md-8 text-end">
                      <div class="font-Medium"><?= number_format($salary['salary'], 2) ?> บาท</div>
                    </div>
                  </div>
                  <hr>
                  <?php if ($salary['is_security']) { ?>
                    <div class="row py-7px">
                      <div class="col-md-4">
                        <div class="text-secondary font-Medium">ประกันสังคม</div>
                      </div>
                      <div class="col-md-8 text-end">
                        <div class="font-Medium text-danger">- <?= number_format($salary['security_price'], 2) ?> บาท</div>
                      </div>
                    </div>
                    <hr>
                  <?php } ?>
                  <?php if ($salary['is_fine']) { ?>
                    <div class="row py-7px">
                      <div class="col-md-4">
                        <div class="text-secondary font-Medium">ค่าปรับ (<?= $salary['count_fine'] ?>)</div>
                      </div>
                      <div class="col-md-8 text-end">
                        <div class="font-Medium text-danger">- <?= number_format($salary['fine_price'], 2) ?> บาท</div>
                      </div>
                    </div>
                    <hr>
                  <?php } ?>


                  <?php if ($salary['employee_cost_list']) { ?>
                    <div class="row py-7px">
                      <div class="col-12 mb-15px">
                        <div class="font-15px fw-semibold">ค่าใช้จ่าย</div>
                      </div>
                    </div>

                    <?php foreach ($salary['employee_cost_list'] as $cost) { ?>
                      <div class="row py-7px">
                        <div class="col-md-4">
                          <div class="font-Medium"><?= $cost['employee_cost_name'] ?></div>
                          <div class="text-secondary font-14px">งวดที่ <?= $cost['period_no'] ?>/<?= $cost['period_all'] ?></div>
                        </div>
                        <div class="col-md-8 text-end">
                          <div class="font-Medium text-danger">- <?= number_format($cost['price_per_period'], 2) ?> บาท</div>
                        </div>
                      </div>
                      <hr>
                    <?php } ?>
                  <?php } ?>


                  <div class="card border-radius-10px border-none  bg-c-1">
                    <div class="d-flex justify-content-between p-3 align-items-center">
                      <div class="fw-semibold text-primary font-15px">รวม</div>
                      <div class="fw-semibold font-20px text-primary"><?= number_format($salary['total_price'], 2) ?> <span class="font-14px fw-semibol">บาท</span></div>
                    </div>
                  </div>

                  <?php if ($salary['status'] == 'approved') { ?>
                    <div class="row py-7px mt-10px">
                      <div class="col-12 mb-15px">
                        <div class="font-15px fw-semibold">รายละเอียดเพิ่มเติม</div>
                      </div>
                    </div>

                    <div class="row py-7px">
                      <div class="col-md-4">
                        <div class="text-secondary font-Medium">สถานะการอนุมัติ</div>
                      </div>
                      <div class="col-md-8">
                        <div class="font-Medium">
                          <div class="d-flex align-items-center">
                            <span class="st-dot success me-2"></span>
                            <div class="text-success">
                              อนุมัติแล้ว
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <hr>
                    <div class="row py-7px">
                      <div class="col-md-4">
                        <div class="text-secondary font-Medium">วันที่อนุมัติจ่าย</div>
                      </div>
                      <div class="col-md-8">
                        <div class="font-Medium">
                          <?= W_Main::formatDate($salary['status_update_datetime'], 'd M Y, H:i', true, false) ?>
                        </div>
                      </div>
                    </div>
                    <hr>
                    <div class="row py-7px">
                      <div class="col-md-4">
                        <div class="text-secondary font-Medium">โดย</div>
                      </div>
                      <div class="col-md-8">
                        <div class="font-Medium">
                          <?= $salary['status_update_by_fullname'] ?>
                        </div>
                      </div>
                    </div>
                  <?php } else if ($salary['status'] == 'reject') { ?>

                    <div class="row py-7px mt-10px">
                      <div class="col-12 mb-15px">
                        <div class="font-15px fw-semibold">รายละเอียดเพิ่มเติม</div>
                      </div>
                    </div>

                    <div class="row py-7px">
                      <div class="col-md-4">
                        <div class="text-secondary font-Medium">สถานะการอนุมัติ</div>
                      </div>
                      <div class="col-md-8">
                        <div class="font-Medium">
                          <div class="d-flex align-items-center">
                            <span class="st-dot danger me-2"></span>
                            <div class="text-danger">
                              ไม่อนุมัติ
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <hr>
                    <div class="row py-7px">
                      <div class="col-md-4">
                        <div class="text-secondary font-Medium">วันที่ทำรายการ</div>
                      </div>
                      <div class="col-md-8">
                        <div class="font-Medium">
                          <?= W_Main::formatDate($salary['status_update_datetime'], 'd M Y, H:i', true, false) ?>
                        </div>
                      </div>
                    </div>
                    <hr>
                    <div class="row py-7px">
                      <div class="col-md-4">
                        <div class="text-secondary font-Medium">โดย</div>
                      </div>
                      <div class="col-md-8">
                        <div class="font-Medium">
                          <?= $salary['status_update_by_fullname'] ?>
                        </div>
                      </div>
                    </div>
                    <hr>
                    <div class="row py-7px">
                      <div class="col-md-4">
                        <div class="text-secondary font-Medium">สาเหตุที่ไม่อนุมัติ</div>
                      </div>
                      <div class="col-md-8">
                        <div class="font-Medium">
                          <?= $salary['status_update_remark'] ?>
                        </div>
                      </div>
                    </div>
                  <?php } ?>
                </div>
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>

  </div>

  <?php Tiwdal::ajaxModal('approved_payment', 'modal-md modal-dialog-centered mx-auto  max-w-500px'); ?>
  <?php Tiwdal::ajaxModal('approved_payment_reject', 'modal-md modal-dialog-centered mx-auto  max-w-500px'); ?>

  <?php
  W_Structure::loadFooterForWebsite();
  W_Main::loadAsset('assets/js/main.js');
  ?>
</body>

</html>