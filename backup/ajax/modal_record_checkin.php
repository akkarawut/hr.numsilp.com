<?php
require_once '../wloves/_framework/import_for_ajax.php';
W_Structure::loadMetaForAjax('../');


$id = $_POST['user_data']['id'];
$date = date('Y-m-d');
$time = date('H:i:s');
$user_status_checkin = $_POST['user_data']['checkin_status'];
$data = [
  'user_id' => $_POST['user_data']['id'],
  'date' => $date,
  'time' => $time,
];
$branch_dropdown = phr_helper::dropdownBranch(API_CODE);
if ($user_status_checkin == 'checkin') {
  $check_status = phr_checkin::checkCheckInStatus(API_CODE, $data);
} else if ($user_status_checkin == 'checkout') {
  $check_status = phr_checkin::checkCheckOutTime(API_CODE, $data);
}

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
    <?php if ($user_status_checkin == 'checkin') { ?>
      <div class="font-24px font-SemiBold  text-center mb-5px"><?= Ty::get(['ลงบันทึกการเข้างาน', '']) ?></div>
      <div class="text-center">
        <p class="text-secondary mb-30px">บันทึกเวลาเข้างานของฉัน</p>
        <p class="text-dark mb-10px">เวลาเข้างานของคุณคือ</p>
      </div>
    <?php } else if ($user_status_checkin == 'checkout') { ?>
      <div class="font-24px font-SemiBold  text-center mb-5px"><?= Ty::get(['ลงบันทึกการออกงาน', '']) ?></div>
      <div class="text-center">
        <p class="text-secondary mb-30px">บันทึกเวลาออกงานของฉัน</p>
        <p class="text-dark mb-10px">เวลาออกงานของคุณคือ</p>
      </div>
    <?php } ?>

    <div class="text-center text-primary font-24px fw-semibold mb-5px">
      <?= W_Main::formatDate($time, 'H : i') ?> น.
    </div>

    <div class="d-flex align-items-center justify-content-center mb-15px">
      <div class="me-2"><?= W_Main::formatDate($date, 'd M Y', true, false) ?>,</div>
      <?php if ($user_status_checkin == 'checkin') { ?>
        <div class="d-flex align-items-center">
          <span class="st-dot <?= $arr_color_checkinStatus[$check_status['status']] ?> me-2"></span>
          <div class="text-<?= $arr_color_checkinStatus[$check_status['status']] ?>"><?= $arr_checkinStatus[$check_status['status']] ?></div>
        </div>
      <?php } else if ($user_status_checkin == 'checkout') { ?>
        <div>เวลารวม :
          <?= $check_status['working_hour'] ?>
          :
          <?= $check_status['working_minute'] ?>
          :
          <?= $check_status['working_second'] ?>
        </div>
      <?php } ?>
    </div>
    <?php
    $text_input = '';
    $text_btn = '';
    if ($user_status_checkin == 'checkin') {
      $text_input = 'สถานที่เข้างาน';
      $text_btn = 'บันทึกเวลาเข้างาน';
    } else if ($user_status_checkin == 'checkout') {
      $text_input = 'สถานที่ออกงาน';
      $text_btn = 'บันทึกเวลาออกงาน';
    } ?>
    <div class="row">
      <div class="col-lg-12 mb-15px">
        <div class="hr-form-control mt-15px">
          <div class="input-group">
            <div class="input-title required"><?= $text_input ?></div>
            <select name="branch_id" class="input" required>
              <option value="" selected disabled>เลือก</option>
              <?php foreach ($branch_dropdown as $val) { ?>
                <option value="<?= $val['id'] ?>"><?= $val['name'] ?></option>
              <?php } ?>
            </select>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="modal-footer">
    <input type="hidden" name="type" value="on_time">
    <input type="hidden" name="date" value="<?= $date ?>">
    <input type="hidden" name="time" value="<?= $time ?>">
    <input type="hidden" name="id" value="<?= $id ?>">
    <?php
    TiwForm::normal('btn', '', ['name' => 'submit_record_checkin', 'type' => 'submit', 'class' => 'btn-primary w-100 mx-0 mb-5px h-50px border-radius-15px'], ['text' => $text_btn]);
    TiwForm::normal('btn', '', ['name' => '', 'type' => 'button', 'class' => 'btn-close-modal w-100 m-0 h-50px border-radius-15px', 'data-bs-dismiss' => 'modal'], ['text' => Ty::get(['ยกเลิก', ''])]);
    ?>
  </div>
</form>