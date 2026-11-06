<?php
$user_permission['_permission'] = ['all', 'ywptz_user', 'niemz_user', 'bmiwk_user', 'noaxt_user', 'xacpn_user'];
require_once 'wloves/_framework/import_for_website.php';
require_once 'layout/init.php';
require_once 'component/navtab.php';
$name_page = 'ตรวจสอบความถูกต้องบันทึกยอดขายและค่าคอมมิชชั่น';
$id = isset($_GET['id']) ? $_GET['id'] : 0;
$page = isset($_GET['page']) ? $_GET['page'] : 'waiting_confirm';
$commission = phr_commission::getCommissionByID(API_CODE, $id);

if ($_POST) {
  if (isset($_POST['submit_approved_commission'])) {
    $data = [
      'user_id' => $current_user_id,
      'status' => 'approved',
      'tax' => $_POST['tax'],
    ];
    $file = isset($_FILES['file']) ? $_FILES['file'] : '';
    $result = phr_commission::updateStatusCommissionByID(API_CODE, $_POST['id'], $data, $file);
  } else if (isset($_POST['submit_approved_commission_reject'])) {
    $data = [
      'user_id' => $current_user_id,
      'remark' => $_POST['remark'],
      'status' => 'reject',
    ];
    $result = phr_commission::updateStatusCommissionByID(API_CODE, $_POST['id'], $data);
  }
  W_Main::responseAPI(/*$result, $message, $redirect*/);
}


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
<!-- MENU::ลงบันทึกยอดขายและค่าคอมมิชชั่น -->
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
                <a href="approve_commission.php?page=<?= $page ?>"> <?= file_get_contents('assets/icons/icon-left-arrow.svg') ?></a>
              </div>
              <h1 class="title-header m-0">บันทึกค่าคอมมิชชั่น</h1>
            </div>
            <?php if ($commission['status'] == 'waiting_confirm') { ?>
              <div class="icon-action">
                <div class="d-flex align-items-center">
                  <button type="button" class="btn btn-icon-squere danger mr-10px" <?= Tiwdal::register('approved_commission_reject', $commission,  ['is_ajax' => 1]) ?>>
                    <?= file_get_contents('assets/icons/times.svg') ?>
                  </button>
                  <button type="button" class="btn btn-icon-squere success" <?= Tiwdal::register('approved_commission', $commission, ['is_ajax' => 1]) ?>>
                    <?= file_get_contents('assets/icons/check-success.svg') ?>
                  </button>
                </div>
              </div>
            <?php } else if ($commission['status'] == 'approved' && $commission['is_file_deduct_tax']) { ?>
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
            <?php if ($commission['status'] == 'waiting_confirm') { ?>
              หลังจากลงบันทึกแล้ว ไม่สามารถแก้ไขได้
            <?php } else { ?>
              บันทึกค่าคอมมิชชั่นที่ยืนยันแล้ว จะถูกนำไปคำนวณรายได้ของพนักงาน
            <?php } ?>
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
              <?php if ($commission['status'] == 'approved') { ?>
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
              <?php } ?>
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
              <?php if ($commission['status'] == 'reject') { ?>
                <div class="row py-7px">
                  <div class="col-md-5">
                    <div class="text-secondary font-Medium">สาเหตุที่ไม่อนุมัติ</div>
                  </div>
                  <div class="col-md-7">
                    <div class="font-Medium"><?= $commission['status_update_remark'] ?></div>
                  </div>
                </div>
                <hr>
              <?php } ?>
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


  <?php Tiwdal::ajaxModal('approved_commission', 'modal-md modal-dialog-centered mx-auto  max-w-500px'); ?>
  <?php Tiwdal::ajaxModal('approved_commission_reject', 'modal-md modal-dialog-centered mx-auto  max-w-500px'); ?>


  <?php Tiwdal::startModal('delete_data', 'modal-md modal-dialog-centered mx-auto  max-w-500px'); ?>
  <form action="" method="post">
    <div class="modal-header justify-content-center">
      <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
        <span class="font-18px" aria-hidden="true">&times;</span>
      </button>
    </div>
    <div class="modal-body px-20px m-auto max-w-450px pt-0">
      <div class="font-24px font-SemiBold  text-center mb-15px"><?= Ty::get(['ลบข้อมูลบันทึกค่าคอมมิชชั่น', '']) ?></div>
      <div class="text-center">
        <div>
          คุณแน่ใจหรือไม่ที่จะลบข้อมูล บันทึกค่าคอมมิชชั่นนี้ ออกจากระบบ? <br>
          หลังจากลบแล้วจะไม่สามารถกู้คืนรายการนี้ได้อีก
        </div>
      </div>
    </div>
    <div class="modal-footer">
      <input type="hidden" name="{id}">
      <?php
      TiwForm::normal('btn', '', ['name' => '', 'type' => 'button', 'class' => 'btn-close-modal min-w-90px', 'data-bs-dismiss' => 'modal'], ['text' => Ty::get(['ยกเลิก', ''])]);
      TiwForm::normal('btn', '', ['name' => 'submit_delete_data', 'type' => 'submit', 'class' => 'btn-danger min-w-120px'], ['text' => Ty::get(['ลบข้อมูล', ''])]);
      ?>
    </div>
  </form>
  <?php Tiwdal::endModal(); ?>

  <?php
  W_Structure::loadFooterForWebsite();
  W_Main::loadAsset('assets/js/main.js');
  ?>

  <script>
    function calTotalPrice(t) {
      let tax_val = t.value;
      let commission = parseFloat(document.querySelector('.txt_commission').innerHTML.replace(/,/g, '')); // ตัด , ออกก่อน ไม่งั้น 1,500.00 จะได้ 1
      let tax_price = commission * (tax_val / 100);
      let total = commission - tax_price;
      let txt_cal_txt = document.querySelector('.txt_cal_txt').innerHTML = w.formatNumber(total) + ' บาท';
    }
  </script>
</body>

</html>