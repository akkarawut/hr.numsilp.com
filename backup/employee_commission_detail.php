<?php
$user_permission['_permission'] = ['all', 'ywptz_user', 'niemz_user', 'bmiwk_user', 'noaxt_user', 'xacpn_user'];
require_once 'wloves/_framework/import_for_website.php';
require_once 'layout/init.php';
$name_page = 'ข้อมูลพนักงาน';
$id = isset($_GET['id']) && $_GET['id'] ? $_GET['id'] : 0;
$user_id = isset($_GET['user_id']) && $_GET['user_id'] ? $_GET['user_id'] : 0;
$mode = isset($_GET['mode']) && $_GET['mode'] ? $_GET['mode'] : 'employee';
$page = isset($_GET['page']) && $_GET['page'] ? $_GET['page'] : 'emp_detail';
$commission = phr_commission::getCommissionByID(API_CODE, $id);

$arr_status = [
  'waiting_confirm' => 'รอตรวจสอบความถูกต้อง',
  'approved' => 'ยืนยันแล้ว',
  'reject' => 'ไม่อนุมัติ',
];
$arr_status_color = [
  'waiting_confirm' => 'warning',
  'approved' => 'success',
  'reject' => 'danger',
];

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
      <div class="container-fluid mt-5">
        <div class="max-w-500px mx-auto">
          <div class="d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center">
              <div class="icon-back mr-10px">
                <a href="employee_detail.php?id=<?= $user_id ?>&mode=<?= $mode ?>&page=<?= $page ?>"> <?= file_get_contents('assets/icons/icon-left-arrow.svg') ?></a>
              </div>
              <h1 class="title-header m-0">บันทึกค่าคอมมิชชั่น</h1>
            </div>
            <?php if ($commission['is_file_deduct_tax']) { ?>
              <div class="icon-action">
                <a href="<?= $commission['file_deduct_tax_path'] ?>" class="btn btn-c-icon circle btn-c-primary" target="_blank" download>
                  <?= file_get_contents('assets/icons/printer.svg') ?>
                </a>
              </div>
            <?php } ?>
          </div>
        </div>
        <hr>
      </div>

      <div class="container">
        <div class="max-w-500px mx-auto">
          <p class="sub-title my-25px">
            บันทึกค่าคอมมิชชั่นที่ยืนยันแล้ว จะถูกนำไปคำนวณรายได้ของพนักงาน
          </p>

          <div class="card rounded-20px">
            <div class="card-body py-25px px-30px">
              <div class="row py-7px">
                <div class="col-12 mb-15px">
                  <div class="font-15px fw-semibold">รายละเอียดบันทึก</div>
                </div>
              </div>
              <div class="row py-7px">
                <div class="col-md-5">
                  <div class="text-secondary font-Medium">พนักงาน</div>
                </div>
                <div class="col-md-7">
                  <div class="font-Medium"><?= $commission['user_fullname'] ?></div>
                </div>
              </div>
              <hr>
              <div class="row py-7px">
                <div class="col-md-5">
                  <div class="text-secondary font-Medium">ยอดขาย</div>
                </div>
                <div class="col-md-7">
                  <div class="font-Medium"><?= number_format($commission['sale_price'], 2) ?> บาท</div>
                </div>
              </div>
              <hr>
              <div class="row py-7px">
                <div class="col-md-5">
                  <div class="text-secondary font-Medium">ค่าคอมมิชชั่นที่ได้รับ</div>
                </div>
                <div class="col-md-7">
                  <div class="font-Medium"><?= number_format($commission['commission_price'], 2) ?> บาท </div>
                </div>
              </div>
              <hr>
              <div class="row py-7px">
                <div class="col-md-5">
                  <div class="text-secondary font-Medium">ภาษีหัก ณ ที่จ่าย</div>
                </div>
                <div class="col-md-7">
                  <div class="font-Medium"><?= number_format($commission['tax'], 2) ?>% </div>
                </div>
              </div>
              <hr>
              <div class="row py-7px">
                <div class="col-md-5">
                  <div class="text-secondary font-Medium">ยอดที่ได้รับ</div>
                </div>
                <div class="col-md-7">
                  <div class="font-Medium"><?= number_format($commission['total_price'], 2) ?> บาท</div>
                </div>
              </div>
              <hr>
              <div class="row py-7px">
                <div class="col-md-5">
                  <div class="text-secondary font-Medium">หมายเหตุ</div>
                </div>
                <div class="col-md-7">
                  <div class="font-Medium"><?= $commission['remark'] ?></div>
                </div>
              </div>
              <hr>
              <div class="row py-7px">
                <div class="col-md-5">
                  <div class="text-secondary font-Medium">สถานะการตรวจสอบ</div>
                </div>
                <div class="col-md-7">
                  <div class="font-Medium">
                    <div class="d-flex align-items-center">
                      <span class="st-dot <?= $arr_status_color[$commission['status']] ?> me-2"></span>
                      <div class="text-<?= $arr_status_color[$commission['status']] ?>">
                        <?= $arr_status[$commission['status']] ?>
                      </div>
                    </div>
                    <?php if ($commission['status'] != 'waiting_confirm') { ?>
                      <div class="text-secondary font-14px">
                        โดย <?= $commission['status_update_by_fullname'] ?> <br>
                        <?= W_Main::formatDate($commission['status_update_datetime'], 'd/m/Y - H:i น.') ?>
                      </div>
                    <?php } ?>
                  </div>
                </div>
              </div>
              <hr>
              <div class="row py-7px">
                <div class="col-md-5">
                  <div class="text-secondary font-Medium">วันที่ลงบันทึก</div>
                </div>
                <div class="col-md-7">
                  <div class="font-Medium"><?= W_Main::formatDate($commission['insert_date_time'], 'd/m/Y, H:i') ?></div>
                  <div class="font-14px text-secondary">โดย <?= $commission['insert_user_fullname'] ?></div>
                </div>
              </div>
              <hr>
              <div class="row py-7px">
                <div class="col-md-5">
                  <div class="text-secondary font-Medium">เลขที่อ้างอิง</div>
                </div>
                <div class="col-md-7">
                  <div class="font-Medium text-secondary font-15px"><?= $commission['no'] ?></div>
                </div>
              </div>
              <hr>
              <div class="row py-7px">
                <div class="col-md-12 mb-5px">
                  <div class="font-semibold">หลักฐานประกอบ</div>
                  <div class="text-secondary">ไฟล์ประกอบการพิจารณาความถูกต้อง</div>
                </div>
                <div class="col-md-12">
                  <?php if ($commission['image_list']) { ?>
                    <?php foreach ($commission['image_list'] as $img) { ?>
                      <div class="d-flex justify-content-between align-items-center">
                        <a href="<?= $img['path_file'] ?>" target="_blank" class="font-Medium"><?= $img['file_name'] . '.' . $img['extension'] ?></a>
                        <a href="<?= $img['path_file'] ?>" target="_blank" download>
                          <?= file_get_contents('assets/icons/down.svg') ?>
                        </a>
                      </div>
                    <?php } ?>
                  <?php } else { ?>
                    <div class="text-secondary text-center mt-10px">ไม่มีไฟล์</div>
                  <?php } ?>
                </div>
              </div>
              <hr>
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