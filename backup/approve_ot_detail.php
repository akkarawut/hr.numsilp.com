<?php
$user_permission['_permission'] = ['all', 'ywptz_user', 'niemz_user', 'bmiwk_user', 'noaxt_user', 'xacpn_user'];
require_once 'wloves/_framework/import_for_website.php';
require_once 'layout/init.php';
$name_page = 'อนุมัติโอที';
$id = isset($_GET['id']) ? $_GET['id'] : 0;
$data_ot =  phr_ot::getOTByID(API_CODE, $id, []);
if ($_POST) {
  if (isset($_POST['submit_approved_ot'])) {
    $data = [
      'user_id' => $current_user_id,
      'status' => 'approved',
      'setting_ot_id' => $_POST['setting_ot_id'],
    ];
    $result = phr_ot::updateStatusOTByID(API_CODE, $_POST['id'], $data);
  } else if (isset($_POST['submit_ot_reject'])) {
    $data = [
      'user_id' => $current_user_id,
      'remark' => $_POST['remark'],
      'status' => 'reject',
    ];
    $result = phr_ot::updateStatusOTByID(API_CODE, $_POST['id'], $data);
  }
  W_Main::responseAPI(/*$result, $message, $redirect*/);
}

$arr_status = [
  'waiting_confirm' => 'รออนุมัติ',
  'approved' => 'อนุมัติแล้ว',
  'reject' => 'ไม่อนุมัติ',
];
$arr_status_color = [
  'waiting_confirm' => 'secondary',
  'approved' => 'success',
  'reject' => 'danger',
];

?>
<!-- MENU::บันทึกการขอล่วงเวลา (OT) -->
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
                <?php
                $qr_str = '';
                if ($data_ot['status_confirm'] == 'approved') {
                  $qr_str = '?page=approved';
                } else {
                  $qr_str = '?page=reject';
                }
                ?>
                <a href="approve_ot.php<?= $qr_str ?>"> <?= file_get_contents('assets/icons/icon-left-arrow.svg') ?></a>
              </div>
              <h1 class="title-header m-0">รายละเอียดบันทึกการขอทำงานล่วงเวลา (OT)</h1>
            </div>
            <?php if ($data_ot['status_confirm'] == 'waiting_confirm') { ?>
              <div class="icon-action">
                <div class="d-flex">
                  <button type="button" class="btn btn-icon-squere danger mr-10px" <?= Tiwdal::register('approved_ot_reject', $data_ot, []) ?>>
                    <?= file_get_contents('assets/icons/times.svg') ?>
                  </button>
                  <button type="button" class="btn btn-icon-squere success" <?= Tiwdal::register('approved_ot', $data_ot, ['is_ajax' => 1]) ?>>
                    <?= file_get_contents('assets/icons/check-success.svg') ?>
                  </button>
                </div>
              </div>
            <?php } ?>
          </div>
        </div>
        <hr>
      </div>

      <div class="container">
        <div class="max-w-750px mx-auto">
          <p class="sub-title my-25px">
            โปรดกรอกรายละเอียดให้ครบถ้วนเพื่อใช้ประกอบการอนุมัติขอทำงานล่วงเวลา (OT)
          </p>

          <div class="card rounded-20px">
            <div class="card-body py-25px px-30px">
              <div class="row py-7px">
                <div class="col-12 mb-15px">
                  <div class="font-15px fw-semibold">รายละเอียด</div>
                </div>
              </div>
              <div class="row py-7px">
                <div class="col-md-5">
                  <div class="text-secondary font-Medium">เหตุผล</div>
                </div>
                <div class="col-md-7">
                  <div class="font-Medium">
                    <?= $data_ot['remark'] ?>
                  </div>
                </div>
              </div>
              <hr>
              <div class="row py-7px">
                <div class="col-md-5">
                  <div class="text-secondary font-Medium">วันที่ต้องการขอทำงานล่วงเวลา</div>
                </div>
                <div class="col-md-7">
                  <div class="font-Medium">
                    <?= W_Main::formatDate($data_ot['date'], 'd/m/Y') ?>,
                    <?= substr($data_ot['from_time'], 0, -3) ?> - <?= substr($data_ot['to_time'], 0, -3) ?> น.
                  </div>
                </div>
              </div>
              <hr>
              <div class="row py-7px">
                <div class="col-md-5">
                  <div class="text-secondary font-Medium">ระยะเวลาขอทำงานล่วงเวลารวม</div>
                </div>
                <div class="col-md-7">
                  <div class="font-Medium"><?= number_format($data_ot['hour_total'], 0) ?> ชั่วโมง</div>
                </div>
              </div>
              <hr>
              <div class="row py-7px">
                <div class="col-md-5">
                  <div class="text-secondary font-Medium">สถานะคำขอ</div>
                </div>
                <div class="col-md-7">
                  <div class="font-Medium">
                    <div class="d-flex align-items-center">
                      <span class="st-dot <?= $arr_status_color[$data_ot['status_confirm']] ?> me-2"></span>
                      <div class="text-<?= $arr_status_color[$data_ot['status_confirm']] ?>">
                        <?= $arr_status[$data_ot['status_confirm']] ?>
                      </div>
                    </div>
                    <?php if ($data_ot['status_confirm'] != 'waiting_confirm') { ?>
                      <div class="text-secondary font-14px">
                        โดย <?= $data_ot['status_confirm_update_by_fullname'] ?> , <?= W_Main::formatDate($data_ot['status_confirm_update_datetime'], 'd/m/Y - H:i น.') ?>
                      </div>
                    <?php } ?>
                  </div>
                </div>
              </div>
              <hr>

              <?php if ($data_ot['status_confirm'] == 'reject') { ?>
                <div class="row py-7px">
                  <div class="col-md-5">
                    <div class="text-secondary font-Medium">สาเหตุที่ไม่อนุมัติ</div>
                  </div>
                  <div class="col-md-7">
                    <div class="font-Medium"><?= $data_ot['status_confirm_update_remark'] ?></div>
                  </div>
                </div>
                <hr>
              <?php } else if ($data_ot['status_confirm'] == 'approved') { ?>
                <div class="row py-7px">
                  <div class="col-md-5">
                    <div class="text-secondary font-Medium">เงื่อนไขการคำนวณโอที</div>
                  </div>
                  <div class="col-md-7">
                    <div class="font-Medium"><?= $data_ot['setting_ot_name'] ?> (<?= number_format($data_ot['late_cal_hour'], 2) ?> บาท : ชั่วโมง)</div>
                  </div>
                </div>
                <hr>
                <div class="row py-7px">
                  <div class="col-md-5">
                    <div class="text-secondary font-Medium">ค่า OT ที่คาดว่าจะได้รับ</div>
                  </div>
                  <div class="col-md-7">
                    <div class="font-Medium"><?= number_format($data_ot['total_price'], 2) ?> บาท</div>
                  </div>
                </div>
                <hr>
              <?php } ?>
              <div class="row py-7px">
                <div class="col-md-5">
                  <div class="text-secondary font-Medium">วันที่ยื่นคำร้อง</div>
                </div>
                <div class="col-md-7">
                  <div class="font-Medium"><?= W_Main::formatDate($data_ot['insert_date_time'], 'd/m/Y, H:i') ?></div>
                  <div class="font-Medium text-secondary font-15px">หมายเลขคำขอ : <?= $data_ot['no'] ?></div>
                </div>
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>

  </div>


  <?php Tiwdal::ajaxModal('approved_ot', 'modal-md modal-dialog-centered mx-auto  max-w-500px'); ?>

  <?php Tiwdal::startModal('approved_ot_reject', 'modal-md modal-dialog-centered mx-auto  max-w-500px'); ?>
  <form action="" method="post">
    <div class="modal-header justify-content-center">
      <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
        <span class="font-18px" aria-hidden="true">&times;</span>
      </button>
    </div>
    <div class="modal-body px-20px m-auto max-w-450px pt-0">
      <div class="font-24px font-SemiBold  text-center mb-15px"><?= Ty::get(['ไม่อนุมัติการขอทำงานล่วงเวลา', '']) ?></div>
      <div class="text-center mb-15px">
        <div>
          คุณแน่ใจหรือไม่ที่จะ <span class="text-danger">ไม่อนุมัติ</span> การขอทำงานล่วงเวลาของ
        </div>
        <div class="fw-semibold mb-10px" name="{user_fullname}"></div>
      </div>

      <div class="row mb-5px">
        <div class="col-lg-12">
          <span class="fw-semibold">บันทึกเพิ่มเติม</span>
        </div>
      </div>

      <div class="row mb-20px">
        <div class="col-lg-12">
          <div class="hr-form-control">
            <div class="input-group">
              <div class="input-title">สาเหตุที่ไม่อนุมัติ</div>
              <textarea name="remark" class="input"></textarea>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="modal-footer">
      <input type="hidden" name="{id}">
      <?php
      TiwForm::normal('btn', '', ['name' => '', 'type' => 'button', 'class' => 'btn-close-modal h-50px border-radius-15px', 'data-bs-dismiss' => 'modal'], ['text' => Ty::get(['ยกเลิก', ''])]);
      TiwForm::normal('btn', '', ['name' => 'submit_ot_reject', 'type' => 'submit', 'class' => 'btn-danger h-50px border-radius-15px'], ['text' => 'ไม่อนุมัติคำขอ']);
      ?>
    </div>
  </form>
  <?php Tiwdal::endModal(); ?>

  <?php
  W_Structure::loadFooterForWebsite();
  W_Main::loadAsset('assets/js/main.js');
  W_Main::loadAsset('assets/js/approve_ot.js');
  ?>
</body>

</html>