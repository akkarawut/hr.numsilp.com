<?php
$user_permission['_permission'] = ['all', 'ywptz_user', 'niemz_user', 'bmiwk_user', 'noaxt_user', 'xacpn_user'];
require_once 'wloves/_framework/import_for_website.php';
require_once 'layout/init.php';
$name_page = 'ข้อมูลพนักงาน';
$id = isset($_GET['id']) && $_GET['id'] ? $_GET['id'] : 0;
$user_id = isset($_GET['user_id']) && $_GET['user_id'] ? $_GET['user_id'] : 0;
$mode = isset($_GET['mode']) && $_GET['mode'] ? $_GET['mode'] : 'employee';
$page = isset($_GET['page']) && $_GET['page'] ? $_GET['page'] : 'emp_detail';
$salary_data = phr_salary::getSalaryByID(API_CODE, $id);

$txt_type = '';
if ($salary_data['employee_status'] == 'day') {
  $txt_type = 'ค่าจ้าง';
} else {
  $txt_type = 'เงินเดือน';
}
?>
<!-- MENU::ข้อมูลพนักงาน -->
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
        <div class="mx-auto  max-w-500px">
          <div class="d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center">
              <div class="icon-back mr-10px">
                <a href="employee_detail.php?id=<?= $user_id ?>&mode=<?= $mode ?>&page=<?= $page ?>"> <?= file_get_contents('assets/icons/icon-left-arrow.svg') ?></a>
              </div>
              <h1 class="title-header m-0">บันทึก<?= $txt_type ?></h1>
            </div>
            <div class="icon-action">
              <button type="button" class="btn btn-c-icon circle btn-c-primary">
                <?= file_get_contents('assets/icons/printer.svg') ?>
              </button>
            </div>
          </div>
        </div>
        <hr>
      </div>

      <div class="container mt-20px">
        <div class="mx-auto max-w-500px">
          <div class="card rounded-20px mb-15px">
            <div class="card-body pt-25px px-30px">
              <div class="row py-7px">
                <div class="col-12 mb-15px">
                  <div class="font-15px fw-semibold">รายละเอียดการเงิน</div>
                </div>
              </div>
              <div class="row py-7px">
                <div class="col-md-6">
                  <div class="text-secondary font-Medium"><?= $txt_type ?>:
                    <span class="text-dark">
                      <?php if ($salary_data['employee_status'] != 'day') { ?>
                        <?= W_Main::formatDate($salary_data['installment_date'], 'M,Y', true, false); ?>
                      <?php } else { ?>
                        <?= W_Main::formatDate($salary_data['installment_date'], 'd M,Y', true, false); ?>
                      <?php } ?>
                    </span>
                  </div>
                </div>
                <div class="col-md-6 text-end">
                  <div class="font-Medium"><?= number_format($salary_data['salary'], 2) ?> บาท</div>
                </div>
              </div>
              <hr>
              <?php if ($salary_data['is_security']) { ?>
                <div class="row py-7px">
                  <div class="col-md-4">
                    <div class="text-secondary font-Medium">ประกันสังคม</div>
                  </div>
                  <div class="col-md-8 text-end">
                    <div class="font-Medium text-danger">- <?= number_format($salary_data['security_price'], 2) ?> บาท</div>
                  </div>
                </div>
                <hr>
              <?php } ?>
              <?php if ($salary_data['is_fine']) { ?>
                <div class="row py-7px">
                  <div class="col-md-4">
                    <div class="text-secondary font-Medium">ค่าปรับ (<?= $salary_data['count_fine'] ?>)</div>
                  </div>
                  <div class="col-md-8 text-end">
                    <div class="font-Medium text-danger">- <?= number_format($salary_data['fine_price'], 2) ?> บาท</div>
                  </div>
                </div>
                <hr>
              <?php } ?>

              <?php if ($salary_data['employee_cost_list']) { ?>
                <div class="row py-7px">
                  <div class="col-12 mb-15px">
                    <div class="font-15px fw-semibold">ค่าใช้จ่าย</div>
                  </div>
                </div>

                <?php foreach ($salary_data['employee_cost_list'] as $cost) { ?>
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
                  <div class="fw-semibold font-20px text-primary"><?= number_format($salary_data['total_price'], 2) ?> <span class="font-14px fw-semibol">บาท</span></div>
                </div>
              </div>

              <div class="row py-7px mt-10px">
                <div class="col-12 mb-15px">
                  <div class="font-15px fw-semibold">รายละเอียดเพิ่มเติม</div>
                </div>
              </div>

              <div class="row py-7px">
                <div class="col-md-4">
                  <div class="font-Medium">สถานะการอนุมัติ</div>
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
                  <div class="font-Medium">วันที่อนุมัติจ่าย</div>
                </div>
                <div class="col-md-8">
                  <div class="font-Medium">
                    <?= W_Main::formatDate($salary_data['status_update_datetime'], 'd M Y, H:i', true, false) ?>
                  </div>
                </div>
              </div>
              <hr>
              <div class="row py-7px">
                <div class="col-md-4">
                  <div class="font-Medium">โดย</div>
                </div>
                <div class="col-md-8">
                  <div class="font-Medium">
                    <?= $salary_data['status_update_by_fullname'] ? $salary_data['status_update_by_fullname'] : 'SYSTEM' ?>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>


  <?php
  W_Structure::loadFooterForWebsite();
  W_Main::loadAsset('assets/js/main.js');
  ?>
</body>

</html>