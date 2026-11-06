<?php
require_once '../wloves/_framework/import_for_ajax.php';
W_Structure::loadMetaForAjax('../');

$id =  $_POST['id'];
$data = phr_checkin::getCheckInByID(API_CODE, $id);

$arr_checkinStatus = [
  'normal' => 'เข้างานตรงเวลา',
  'late' => 'เข้างานสาย',
  'very_late' => 'เข้างานสายมาก',
  'leave_work' => 'ลางาน',
  'missing' => 'ขาดงาน',
];

$arr_color_checkinStatus = [
  'normal' => 'success',
  'late' => 'warning',
  'very_late' => 'warning',
  'leave_work' => 'secondary',
  'missing' => 'danger',
];
?>
<form action="" method="post">
  <div class="modal-header justify-content-center">
    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
      <span class="font-18px" aria-hidden="true">&times;</span>
    </button>
  </div>
  <div class="modal-body px-20px m-auto max-w-450px pt-0">
    <div class="font-24px font-SemiBold text-center mb-5px"><?= Ty::get(['อนุมัติการบันทึกเวลาเข้างาน', '']) ?></div>
    <div class="text-center">
      <p class="text-dark mb-5px">
        คุณแน่ใจหรือไม่ที่จะ <span class="text-success">อนุมัติ</span> บันทึกเวลาเข้างานของ
      </p>
      <p class="fw-semibold mb-10px">
        <?= $data['user_fullname'] ?>
      </p>
    </div>

    <div class="row mb-20px">
      <div class="col-lg-12">
        <span class="fw-semibold">รายละเอียด</span>
      </div>
    </div>

    <div class="row mb-20px">
      <div class="col-md-4">
        <div class="text-secondary font-Medium">
          ชื่อพนักงาน
        </div>
      </div>
      <div class="col-lg-8">
        <div class="text-dark font-Medium">
          <?= $data['user_fullname'] ?>
        </div>
      </div>
    </div>
    <div class="row mb-20px">
      <div class="col-md-4">
        <div class="text-secondary font-Medium">
          เวลาเข้างาน
        </div>
      </div>
      <div class="col-lg-8">
        <div class="text-dark font-Medium">
          <?= W_Main::formatDate($data['checkin_datetime'], 'd M Y, H:i น.', true,false) ?>
        </div>
      </div>
    </div>
    <div class="row mb-20px">
      <div class="col-md-4">
        <div class="text-secondary font-Medium">
          สถานที่เข้างาน
        </div>
      </div>
      <div class="col-lg-8">
        <div class="text-dark font-Medium">
          <?= $data['checkin_branch_name'] ?>
        </div>
      </div>
    </div>
    <div class="row mb-20px">
      <div class="col-md-4">
        <div class="text-secondary font-Medium">
          สถานะการเข้างาน
        </div>
      </div>
      <div class="col-lg-8">
        <div class="d-flex align-items-center">
          <span class="st-dot <?= $arr_color_checkinStatus[$data['status_checkin']] ?> me-2"></span>
          <div class="text-<?= $arr_color_checkinStatus[$data['status_checkin']] ?>"><?= $arr_checkinStatus[$data['status_checkin']] ?></div>
        </div>
      </div>
    </div>

  </div>
  <div class="modal-footer">
    <input type="hidden" name="id" value="<?= $_POST['id'] ?>">
    <?php
    TiwForm::normal('btn', '', ['name' => '', 'type' => 'button', 'class' => 'btn-close-modal h-50px border-radius-15px', 'data-bs-dismiss' => 'modal'], ['text' => Ty::get(['ยกเลิก', ''])]);
    TiwForm::normal('btn', '', ['name' => 'submit_approved_checkin', 'type' => 'submit', 'class' => 'btn-success h-50px border-radius-15px'], ['text' => 'อนุมัติบันทึก']);
    ?>
  </div>
</form>