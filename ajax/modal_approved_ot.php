<?php
require_once '../wloves/_framework/import_for_ajax.php';
W_Structure::loadMetaForAjax('../');

$id =  $_POST['id'];
$data = phr_ot::getOTByID(API_CODE, $id);
$dropdown_ot = phr_helper::dropdownOT(API_CODE, ['is_use' => 1]);
?>
<form action="" method="post">
  <div class="modal-header justify-content-center">
    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
      <span class="font-18px" aria-hidden="true">&times;</span>
    </button>
  </div>
  <div class="modal-body px-20px m-auto max-w-450px pt-0">
    <div class="font-24px font-SemiBold text-center mb-5px"><?= Ty::get(['อนุมัติการขอทำงานล่วงเวลา', '']) ?></div>
    <div class="text-center">
      <p class="text-dark mb-5px">
        คุณแน่ใจหรือไม่ที่จะ <span class="text-success">อนุมัติ</span> คำร้องการขอทำงานล่วงเวลา
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
      <div class="col-md-5">
        <div class="text-secondary font-Medium">
          เหตุผล
        </div>
      </div>
      <div class="col-lg-7">
        <div class="text-dark font-Medium">
          <?= $data['remark'] ?>
        </div>
      </div>
    </div>
    <div class="row mb-20px">
      <div class="col-md-5">
        <div class="text-secondary font-Medium">
          วันที่ต้องการขอ <br>
          ทำงานล่วงเวลา
        </div>
      </div>
      <div class="col-lg-7">
        <div class="text-dark font-Medium">
          <?= W_Main::formatDate($data['date'], 'd/m/Y') ?>,
          <?= substr($data['from_time'], 0, -3) ?> - <?= substr($data['to_time'], 0, -3) ?> น.
        </div>
      </div>
    </div>
    <div class="row mb-20px">
      <div class="col-md-5">
        <div class="text-secondary font-Medium">
          ระยะเวลาขอทำงาน <br>
          ล่วงเวลารวม
        </div>
      </div>
      <div class="col-lg-7">
        <div class="text-dark font-Medium">
          <Span class="scope_hours"><?= number_format($data['hour_total'], 0) ?></Span> ชั่วโมง
        </div>
      </div>
    </div>

    <div class="row mb-20px">
      <div class="col-lg-12">
        <div class="fw-semibold">กำหนดเงื่อนไข</div>
        <div class="font-15px mt-5px">เลือกเงื่อนไขอัตราการได้รับค่าทำงานล่วงเวลาของพนักงาน</div>
      </div>
    </div>


    <div class="row mb-20px">
      <div class="col-12 mb-15px">
        <div class="hr-form-control">
          <div class="input-group">
            <div class="input-title required">เงื่อนไข</div>
            <select name="setting_ot_id" class="input" required onchange="changeConditionOT(this);">
              <option value="" selected disabled>เลือก</option>
              <?php foreach ($dropdown_ot as $val) { ?>
                <option value="<?= $val['id'] ?>"><?= $val['title'] ?></option>
              <?php } ?>
            </select>
          </div>
        </div>
      </div>
    </div>

    <div class="row mb-20px">
      <div class="col-md-5">
        <div class="text-secondary font-Medium">
          อัตราจ่าย (บาท : ชั่วโมง)
        </div>
      </div>
      <div class="col-lg-7">
        <div class="scope_condition_ot text-dark font-Medium">
          <!-- 100 บาท : ชั่วโมง -->
        </div>
      </div>
    </div>

    <div class="row mb-20px">
      <div class="col-md-5">
        <div class="text-secondary font-Medium">
          ยอดที่พนักงานจะได้รับ
        </div>
      </div>
      <div class="col-lg-7">
        <div class="scope_price text-primary font-Medium">
          <!-- 200.00 บาท -->
        </div>
      </div>
    </div>
  </div>
  <div class="modal-footer">
    <input type="hidden" name="id" value="<?= $_POST['id'] ?>">
    <?php
    TiwForm::normal('btn', '', ['name' => '', 'type' => 'button', 'class' => 'btn-close-modal h-50px border-radius-15px', 'data-bs-dismiss' => 'modal'], ['text' => Ty::get(['ยกเลิก', ''])]);
    TiwForm::normal('btn', '', ['name' => 'submit_approved_ot', 'type' => 'submit', 'class' => 'btn-success h-50px border-radius-15px'], ['text' => 'อนุมัติคำขอ']);
    ?>
  </div>
</form>