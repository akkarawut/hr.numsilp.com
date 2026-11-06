<?php
$user_permission['_permission'] = ['all', 'ywptz_user', 'niemz_user', 'bmiwk_user', 'noaxt_user', 'xacpn_user'];
require_once 'wloves/_framework/import_for_website.php';
require_once 'layout/init.php';
$name_page = 'ทำเงินเดือน';
$id = isset($_GET['id']) ? $_GET['id'] : 0;
$type = isset($_GET['type']) ? $_GET['type'] : '';
$salary = phr_salary::getSalaryByID(API_CODE, $id);
$user_data = $salary['user_info'];
$performance_data = $salary['performance_info'];
if ($_POST) {
  if (isset($_POST['submit_send_salary'])) {
    unset($_POST['submit_send_salary']);
    $data = [
      'user_id' => $current_user_id,
      'ids' => $_POST['id'],
      'status' => 'waiting_confirm',
    ];
    $result = phr_salary::updateStatusSalaryByIDS(API_CODE, $data);
    if ($result['response_status']) {
      if ($type == 'daily') {
        $redirect = 'make_salary_daily.php';
      } else {
        $redirect = 'make_salary.php';
      }
    }
  } else if (isset($_POST['submit_refresh'])) {
    unset($_POST['submit_refresh']);
    $result = phr_salary::RefreshSalary(API_CODE, $_POST['id']);
  }
  W_Main::responseAPI(/*$result, $message, $redirect*/);
}

if ($type == 'daily') {
  $name_page = 'ค่าจ้างรายวัน';
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
                <a href="<?= $type == 'daily' ? 'make_salary_daily.php' : 'make_salary.php' ?>"> <?= file_get_contents('assets/icons/icon-left-arrow.svg') ?></a>
              </div>
              <h1 class="title-header m-0"><?= $type == 'daily' ? 'ค่าจ้างรายวัน' : 'รายการทำเงินเดือน' ?></h1>
            </div>
            <div class="icon-action">
              <div class="d-flex">
                <form action="" method="post">
                  <input type="hidden" name="id" value="<?= $id ?>">
                  <button type="submit" name="submit_refresh" class="btn btn-c-icon btn-c-light-v2 mr-10px">
                    <?= file_get_contents('assets/icons/refresh.svg') ?>
                  </button>
                </form>

                <button type="button" class="btn btn-c-icon btn-c-primary-v2" <?= Tiwdal::register('modal_send_salary', $salary, []) ?>>
                  <?= file_get_contents('assets/icons/send.svg') ?>
                </button>
              </div>
            </div>
          </div>
        </div>
        <hr>
      </div>

      <div class="container mb-10px">
        <div class="mx-auto">
          <p class="sub-title mt-25px">
            ตรวจเช็คความถูกต้องก่อนส่งอนุมัติ
          </p>

          <div class="d-flex flex-wrap my-10px">
            <a href="employee_detail.php?id=<?= $salary['user_id'] ?>&mode=employee&page=emp_detail" class="btn btn-outline-default max-w-150px me-2">
              <div class="font-Medium text-secondary font-16px ms-2">ข้อมูลพนักงาน</div>
            </a>
            <a href="employee_detail.php?id=<?= $salary['user_id'] ?>&mode=employee&page=emp_financial" class="btn btn-outline-default max-w-200px ">
              <div class="font-Medium text-secondary font-16px ms-2">จัดการค่าใช้จ่ายพนักงาน</div>
            </a>
          </div>

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
                      <div class="text-secondary font-Medium"><?= $user_data['employee_status'] == 'day' ? 'ค่าจ้างรายวัน' : 'เงินเดือน' ?> (บาท)</div>
                    </div>
                    <div class="col-md-8">
                      <div class="font-Medium"><?= number_format($user_data['salary'], 2) ?> บาท</div>
                    </div>
                  </div>
                  <hr>

                  <?php if ($user_data['employee_status'] != 'day') { ?>
                    <div class="row py-7px">
                      <div class="col-md-4">
                        <div class="text-secondary font-Medium">การคำนวณเงินเดือน</div>
                      </div>
                      <div class="col-md-8">
                        <div class="font-Medium">รอบวันที่ <?= date('d/m/Y', strtotime($salary['to_date'])) ?></div>
                      </div>
                    </div>
                    <hr>

                    <div class="row py-7px">
                      <div class="col-md-4">
                        <div class="text-secondary font-Medium">ประกันสังคม</div>
                      </div>
                      <div class="col-md-8">
                        <?php if ($user_data['is_security'] == 1) { ?>
                          <div class="font-Medium">หักจ่าย <?= number_format($user_data['security_price'], 2) ?> <?= $user_data['security_unit'] == 'percent' ? 'เปอร์เซ็นต์' : 'บาท' ?>ต่อเดือน</div>
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

              <?php if ($type != 'daily') { ?>
                <div class="row my-20px">
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
                      <div class="text-secondary font-Medium"><?= $type == 'daily' ? 'ค่าจ้างรายวัน' : 'เงินเดือน' ?></div>
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
                    <div class="d-flex justify-content-between px-3 py-4 align-items-center">
                      <div class="fw-semibold text-primary font-15px">รวม</div>
                      <div class="fw-semibold font-20px text-primary"><?= number_format($salary['total_price'], 2) ?> <span class="font-14px fw-semibol">บาท</span></div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="my-20px text-danger font-14px text-center">
                *ในกรณีที่ต้องการเพิ่มค่าใช้จ่ายให้พนักงานโปรดกด “จัดการค่าใช้จ่ายพนักงาน” <br>
                หลังจากเสร็จสิ้นแล้ว โปรดกดปุ่ม “ประมวลผลใหม่” เพื่อเรียกรายการค่าใช้จ่าย
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>

  </div>

  <?php Tiwdal::startModal('modal_send_salary', 'modal-md modal-dialog-centered mx-auto  max-w-500px'); ?>
  <form action="" method="post">
    <div class="modal-header justify-content-center">
      <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
        <span class="font-18px" aria-hidden="true">&times;</span>
      </button>
    </div>
    <div class="modal-body px-20px m-auto max-w-450px pt-0">
      <div class="font-24px font-SemiBold  text-center mb-15px">
        <?= $type == 'daily' ? 'ยืนยันการส่งข้อมูลค่าจ้าง' : 'ยืนยันการส่งข้อมูลเงินเดือน' ?>
      </div>
      <div class="text-center mb-15px">
        <div>
          คุณแน่ใจหรือไม่ที่จะ <span class="text-primary" <?= $type == 'daily' ? 'ส่งข้อมูลค่าจ้าง' : 'ส่งข้อมูลเงินเดือน' ?>></span>
          ของ <span name={user_fullname} class="fw-semibold"></span> <br>
          เพื่อรอการอนุมัติจ่าย
        </div>
      </div>

    </div>
    <div class="modal-footer">
      <input type="hidden" name="{id}" class="">
      <?php
      TiwForm::normal('btn', '', ['name' => '', 'type' => 'button', 'class' => 'btn-close-modal h-50px border-radius-15px', 'data-bs-dismiss' => 'modal'], ['text' => Ty::get(['ยกเลิก', ''])]);
      TiwForm::normal('btn', '', ['name' => 'submit_send_salary', 'type' => 'submit', 'class' => 'btn-primary h-50px border-radius-15px'], ['text' => 'ส่งข้อมูล']);
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