<?php
$user_permission['_permission'] = ['all', 'ywptz_user', 'niemz_user', 'bmiwk_user', 'noaxt_user', 'xacpn_user'];
require_once 'wloves/_framework/import_for_website.php';
require_once 'layout/init.php';
$name_page = 'อนุมัติการลา';
$id = isset($_GET['id']) ? $_GET['id'] : 0;
$get_leave = phr_leave_work::getLeaveWorkByID(API_CODE, $id, []);
if ($_POST) {
  if (isset($_POST['submit_approved_leavework'])) {
    $data = [
      'user_id' => $current_user_id,
      'ids' => $_POST['id'],
      'status' => 'approved',
    ];
    $result = phr_leave_work::updateStatusLeaveWorkByIDS(API_CODE, $data);
  } else if (isset($_POST['submit_leavework_reject'])) {
    $data = [
      'user_id' => $current_user_id,
      'ids' => $_POST['id'],
      'remark' => $_POST['remark'],
      'status' => 'reject',
    ];
    $result = phr_leave_work::updateStatusLeaveWorkByIDS(API_CODE, $data);
  }
  W_Main::responseAPI(/*$result, $message, $redirect*/);
}

$arr_status_leave = [
  'sick_leave' => 'ลาป่วย',
  'personal_leave' => 'ลากิจ',
  'vacation_leave' => 'ลาพักร้อน',
];
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
<!-- MENU::ตั้งค่า_อนุมัติการลงเวลา -->
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
                <a href="approve_leave.php"> <?= file_get_contents('assets/icons/icon-left-arrow.svg') ?></a>
              </div>
              <h1 class="title-header m-0">รายละเอียดคำร้องขอลางาน</h1>
            </div>
            <?php if ($get_leave['status_confirm'] == 'waiting_confirm') { ?>
              <div class="icon-action">
                <div class="d-flex">
                  <button type="button" class="btn btn-icon-squere danger mr-10px" <?= Tiwdal::register('approved_leavework_reject', $get_leave) ?>>
                    <?= file_get_contents('assets/icons/times.svg') ?>
                  </button>
                  <button type="button" class="btn btn-icon-squere success" <?= Tiwdal::register('approved_leavework', $get_leave) ?>>
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
            รายละเอียดคำร้องขอลางาน
          </p>

          <div class="card rounded-20px">
            <div class="card-body py-25px px-30px">
              <div class="row py-7px">
                <div class="col-12 mb-15px">
                  <div class="font-15px fw-semibold">รายละเอียดการลา</div>
                </div>
              </div>
              <div class="row py-7px">
                <div class="col-md-5">
                  <div class="text-secondary font-Medium">ประเภทการลา</div>
                </div>
                <div class="col-md-7">
                  <div class="font-Medium"><?= $arr_status_leave[$get_leave['type']] ?></div>
                </div>
              </div>
              <hr>
              <div class="row py-7px">
                <div class="col-md-5">
                  <div class="text-secondary font-Medium">สาเหตุการลา</div>
                </div>
                <div class="col-md-7">
                  <div class="font-Medium"><?= $get_leave['detail'] ?></div>
                </div>
              </div>
              <hr>
              <div class="row py-7px">
                <div class="col-md-5">
                  <div class="text-secondary font-Medium">วันที่ต้องการลา</div>
                </div>
                <div class="col-md-7">
                  <div class="font-Medium">
                    <?php if ($get_leave['from_date'] != $get_leave['to_date']) { ?>
                      <?= W_Main::formatDate($get_leave['from_date'], 'd/m/Y') ?> - <?= W_Main::formatDate($get_leave['to_date'], 'd/m/Y') ?>
                    <?php } else { ?>
                      <?= W_Main::formatDate($get_leave['from_date'], 'd/m/Y') ?>
                    <?php } ?>
                  </div>
                </div>
              </div>
              <hr>
              <div class="row py-7px">
                <div class="col-md-5">
                  <div class="text-secondary font-Medium">ระยะเวลารวมการลา</div>
                </div>
                <div class="col-md-7">
                  <div class="font-Medium"><?= number_format($get_leave['sum_day_leave_work'], 0) ?> วัน</div>
                </div>
              </div>
              <hr>
              <div class="row py-7px">
                <div class="col-md-5">
                  <div class="text-secondary font-Medium">สถานะคำร้อง</div>
                </div>
                <div class="col-md-7">
                  <div class="font-Medium">
                    <div class="d-flex align-items-center">
                      <span class="st-dot <?= $arr_status_color[$get_leave['status_confirm']] ?> me-2"></span>
                      <div class="text-<?= $arr_status_color[$get_leave['status_confirm']] ?>">
                        <?= $arr_status[$get_leave['status_confirm']] ?>
                      </div>
                    </div>
                    <?php if ($get_leave['status_confirm'] != 'waiting_confirm') { ?>
                      <div class="text-secondary font-14px">
                        โดย <?= $get_leave['status_confirm_update_by_fullname'] ?> , <?= W_Main::formatDate($get_leave['status_confirm_update_datetime'], 'd/m/Y - H:i น.') ?>
                      </div>
                    <?php } ?>
                  </div>
                </div>
              </div>
              <hr>

              <?php if ($get_leave['status_confirm'] == 'reject') { ?>
                <div class="row py-7px">
                  <div class="col-md-5">
                    <div class="text-secondary font-Medium">สาเหตุที่ไม่อนุมัติ</div>
                  </div>
                  <div class="col-md-7">
                    <div class="font-Medium"><?= $get_leave['status_confirm_update_remark'] ?></div>
                  </div>
                </div>
                <hr>
              <?php } ?>

              <div class="row py-7px">
                <div class="col-md-5">
                  <div class="text-secondary font-Medium">วันที่ยื่นคำร้อง</div>
                </div>
                <div class="col-md-7">
                  <div class="font-Medium"><?= W_Main::formatDate($get_leave['insert_date_time'], 'd/m/Y, H:i') ?></div>
                  <div class="font-Medium text-secondary font-15px">หมายเลขคำร้อง : <?= $get_leave['no'] ?></div>
                </div>
              </div>
              <hr>
              <div class="row py-7px">
                <div class="col-md-5">
                  <div class="text-secondary font-Medium">เอกสารประกอบการลา</div>
                </div>
                <div class="col-md-7">
                  <?php if ($get_leave['image_list']) { ?>
                    <?php foreach ($get_leave['image_list'] as $img) { ?>
                      <div class="d-flex justify-content-between align-items-center">
                        <a href="<?= $img['path_file'] ?>" target="_blank" class="font-Medium"><?= $img['file_name'] . '.' . $img['extension'] ?></a>
                        <a href="<?= $img['path_file'] ?>" target="_blank">
                          <?= file_get_contents('assets/icons/down.svg') ?>
                        </a>
                      </div>
                    <?php } ?>
                  <?php } else { ?>
                    <div class="text-secondary">ไม่ได้แนบไฟล์</div>
                  <?php } ?>
                </div>
              </div>
              <hr>
            </div>
          </div>

        </div>
      </div>
    </div>

    <?php Tiwdal::startModal('approved_leavework', 'modal-md modal-dialog-centered mx-auto  max-w-500px'); ?>
    <form action="" method="post">
      <div class="modal-header justify-content-center">
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
          <span class="font-18px" aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body px-20px m-auto max-w-450px pt-0">
        <div class="font-24px font-SemiBold  text-center mb-15px"><?= Ty::get(['อนุมัติการขอลางาน', '']) ?></div>
        <div class="text-center">
          <div class="max-w-380px mx-auto">
            คุณแน่ใจหรือไม่ที่จะ อนุมัติ คำร้องการขอลางานของ
          </div>
          <div class="fw-semibold mb-10px" name="{user_fullname}"></div>
        </div>
      </div>
      <div class="modal-footer">
        <input type="hidden" name="{id}">
        <?php
        TiwForm::normal('btn', '', ['name' => '', 'type' => 'button', 'class' => 'btn-close-modal h-50px border-radius-15px', 'data-bs-dismiss' => 'modal'], ['text' => Ty::get(['ยกเลิก', ''])]);
        TiwForm::normal('btn', '', ['name' => 'submit_approved_leavework', 'type' => 'submit', 'class' => 'btn-success h-50px border-radius-15px'], ['text' => 'อนุมัติบันทึก']);
        ?>
      </div>
    </form>
    <?php Tiwdal::endModal(); ?>

    <?php Tiwdal::startModal('approved_leavework_reject', 'modal-md modal-dialog-centered mx-auto  max-w-500px'); ?>
    <form action="" method="post">
      <div class="modal-header justify-content-center">
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
          <span class="font-18px" aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body px-20px m-auto max-w-450px pt-0">
        <div class="font-24px font-SemiBold  text-center mb-15px"><?= Ty::get(['ไม่อนุมัติการขอลางาน', '']) ?></div>
        <div class="text-center mb-15px">
          <div>
            คุณแน่ใจหรือไม่ที่จะ <span class="text-danger">ไม่อนุมัติ</span> การขอลางานของ
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
        TiwForm::normal('btn', '', ['name' => 'submit_leavework_reject', 'type' => 'submit', 'class' => 'btn-danger h-50px border-radius-15px'], ['text' => 'ไม่อนุมัติบันทึก']);
        ?>
      </div>
    </form>
    <?php Tiwdal::endModal(); ?>
  </div>

  <?php
  W_Structure::loadFooterForWebsite();
  W_Main::loadAsset('assets/js/main.js');
  ?>
  <?php require_once 'layout/footer_js.php'; ?>
</body>

</html>