<?php
$user_permission['_permission'] = ['all', 'ywptz_user', 'niemz_user', 'bmiwk_user', 'noaxt_user', 'xacpn_user'];
require_once 'wloves/_framework/import_for_website.php';
require_once 'layout/init.php';
$name_page = 'ข้อมูลพนักงาน';
$id = isset($_GET['id']) && $_GET['id'] ? $_GET['id'] : 0;
$user_id = isset($_GET['user_id']) && $_GET['user_id'] ? $_GET['user_id'] : 0;
$mode = isset($_GET['mode']) && $_GET['mode'] ? $_GET['mode'] : 'employee';
$page = isset($_GET['page']) && $_GET['page'] ? $_GET['page'] : 'emp_detail';
$cost =  phr_employee::getEmployeeCostByID(API_CODE, $id);

if ($_POST) {
  if (isset($_POST['submit_delete_cost'])) {
    $result = phr_employee::deleteEmployeeCost(API_CODE, $cost['id']);
    if ($result['response_status']) {
      $redirect = 'employee_detail.php?id=' . $user_id . '&mode=' . $mode . '&page=' . $page;
    }
  }
  W_Main::responseAPI(/*$result, $message, $redirect*/);
}

$arr_status = [
  'in_progress' => 'ดำเนินการอยู่',
  'success' => 'สำเร็จ',
  'cancel' => 'ยกเลิก',
];
$arr_status_color = [
  'in_progress' => 'primary',
  'success' => 'success',
  'cancel' => 'danger',
];

?>
<!-- MENU::ออกใบเตื่อน -->
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
                <a href="employee_detail.php?id=<?= $user_id ?>&mode=<?= $mode ?>&page=<?= $page ?>"> <?= file_get_contents('assets/icons/icon-left-arrow.svg') ?></a>
              </div>
              <h1 class="title-header m-0">รายละเอียดค่าใช้จ่าย</h1>
            </div>
            <div class="icon-action">
              <div class="d-flex">
                <!-- <button type="button" class="btn btn-c-icon circle btn-c-primary">
                  <?= file_get_contents('assets/icons/printer.svg') ?>
                </button> -->
                <button type="button" class="btn btn-c-icon circle btn-c-danger" <?php Tiwdal::register('delete_cost', $cost); ?>>
                  <?= file_get_contents('assets/icons/delete.svg') ?>
                </button>
              </div>
            </div>
          </div>
        </div>
        <hr>
      </div>

      <div class="container">
        <div class="max-w-750px mx-auto">
          <p class="sub-title my-25px">
            ค่าใช้จ่ายนี้จะถูกคำนวณทุกครั้งที่คำนวณเงินเดือน จนกว่าจะครบงวด หรือ ถูกลบ
          </p>

          <div class="card rounded-20px">
            <div class="card-body py-25px px-30px">
              <div class="row py-7px">
                <div class="col-12 mb-15px">
                  <div class="font-15px fw-semibold">รายละเอียดค่าใช้จ่าย</div>
                </div>
              </div>
              <div class="row py-7px">
                <div class="col-md-5">
                  <div class="text-secondary font-Medium">ชื่อค่าใช้จ่าย</div>
                </div>
                <div class="col-md-7">
                  <div class="font-Medium"><?= $cost['name'] ?></div>
                </div>
              </div>
              <hr>
              <div class="row py-7px">
                <div class="col-md-5">
                  <div class="text-secondary font-Medium">ยอดเต็ม</div>
                </div>
                <div class="col-md-7">
                  <div class="font-Medium"><?= number_format($cost['total_price'], 2) ?> บาท</div>
                </div>
              </div>
              <hr>
              <div class="row py-7px">
                <div class="col-md-5">
                  <div class="text-secondary font-Medium">งวดหัก</div>
                </div>
                <div class="col-md-7">
                  <div class="font-Medium"><?= $cost['period'] ?> งวด</div>
                </div>
              </div>
              <hr>
              <div class="row py-7px">
                <div class="col-md-5">
                  <div class="text-secondary font-Medium">สาเหตุ</div>
                </div>
                <div class="col-md-7">
                  <div class="font-Medium"><?= number_format($cost['price_per_period'], 2) ?> บาท</div>
                </div>
              </div>
              <hr>
              <div class="row py-7px">
                <div class="col-md-5">
                  <div class="text-secondary font-Medium">หมายเหตุ</div>
                </div>
                <div class="col-md-7">
                  <?= $cost['remark'] ?>
                </div>
              </div>
              <hr>
              <div class="row py-7px">
                <div class="col-md-5">
                  <div class="text-secondary font-Medium">สถานะรายการ</div>
                </div>
                <div class="col-md-7">
                  <div class="d-flex align-items-center">
                    <span class="st-dot <?= $arr_status_color[$cost['status']] ?> me-2"></span>
                    <div class="text-<?= $arr_status_color[$cost['status']] ?>">
                      <?= $arr_status[$cost['status']] ?>
                    </div>
                  </div>
                </div>
              </div>
              <hr>
              <div class="row py-7px">
                <div class="col-md-5">
                  <div class="text-secondary font-Medium">งวดจ่ายปัจจุบัน</div>
                </div>
                <div class="col-md-7">
                  <?= $cost['period_sum_pay'] ?> งวด
                </div>
              </div>
              <hr>
              <div class="row py-7px">
                <div class="col-md-5">
                  <div class="text-secondary font-Medium">ยอดจ่ายแล้วทั้งหมด</div>
                </div>
                <div class="col-md-7">
                  <?= number_format($cost['total_price_pay'], 2)  ?> บาท
                </div>
              </div>
              <hr>
              <div class="row py-7px">
                <div class="col-md-5">
                  <div class="text-secondary font-Medium">วันที่สร้างรายการ</div>
                </div>
                <div class="col-md-7">
                  <?= W_Main::formatDate($cost['update_date_time'], 'd/m/Y, H:i') ?>
                  <div class="text-secondary font-14px">
                    โดย <?= $cost['insert_user_fullname'] ?>
                  </div>
                </div>
              </div>
              <hr>
            </div>
          </div>

        </div>
      </div>
    </div>



    <?php Tiwdal::startModal('delete_cost', 'modal-md modal-dialog-centered mx-auto  max-w-500px'); ?>
    <form action="" method="post">
      <div class="modal-header justify-content-center">
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
          <span class="font-18px" aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body px-20px m-auto max-w-450px pt-0">
        <div class="font-24px font-SemiBold  text-center mb-15px"><?= Ty::get(['ลบค่าใช้จ่ายพนักงาน', '']) ?></div>
        <div class="text-center">
          <div>
            คุณแน่ใจหรือไม่ที่จะลบ ค่าใช้จ่าย ของพนักงานรายนี้? <br>
            การลบรายการนี้จะไม่ส่งผลต่อประวัติการทำรายการที่เกิดขึ้นแล้ว
          </div>
        </div>

        <div class="p-3 bg-c-3 border-radius-10px mt-20px">
          <div class="d-flex align-items-center">
            <div class="me-3"><?= file_get_contents('assets/icons/alert-danger.svg') ?></div>
            <div class="text-danger">รายการอยู่ระหว่างดำเนินการ โปรดตรวจสอบให้แน่ใจก่อนลบ</div>
          </div>
        </div>

      </div>
      <div class="modal-footer">
        <input type="hidden" name="{id}">
        <?php
        TiwForm::normal('btn', '', ['name' => '', 'type' => 'button', 'class' => 'btn-close-modal min-w-90px', 'data-bs-dismiss' => 'modal'], ['text' => Ty::get(['ยกเลิก', ''])]);
        TiwForm::normal('btn', '', ['name' => 'submit_delete_cost', 'type' => 'submit', 'class' => 'btn-danger min-w-120px'], ['text' => Ty::get(['ลบข้อมูล', ''])]);
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