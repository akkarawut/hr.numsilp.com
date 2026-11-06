<?php
require_once '../wloves/_framework/import_for_ajax.php';
W_Structure::loadMetaForAjax('../');

$id =  $_POST['id'];
$data = phr_employee::getEmployeeByID(API_CODE, $id);

?>
<form action="" method="post">
  <div class="modal-header justify-content-center">
    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
      <span class="font-18px" aria-hidden="true">&times;</span>
    </button>
  </div>
  <div class="modal-body px-20px m-auto max-w-450px pt-0">
    <div class="font-24px font-SemiBold text-center mb-5px"><?= Ty::get(['อนุมัติผ่านการทดลองงาน', '']) ?></div>
    <div class="text-center">
      <p class="text-dark mb-5px">
        คุณแน่ใจหรือไม่ที่จะ <span class="text-success">อนุมัติผ่าน</span> การทดลองงานของ
      </p>
      <p class="fw-semibold mb-10px">
        <?= $data['full_name'] ?>
      </p>
    </div>

    <div class="row mb-20px">
      <div class="col-lg-12">
        <div class="fw-semibold">บันทึกเพิ่มเติม</div>
        <div class="mb-10px mt-10px">
          <?php
          TiwForm::normal('checkbox', 1, ['name' => 'is_working', 'class' => '', 'checked' => 1], ['style' => '3', 'label' => 'สิทธิการเป็นพนักงานต่อ']);
          ?>
        </div>
        <div class="font-15px mt-5px">
          ในกรณีที่พนักงานได้รับสิทธิการเป็นพนักงานต่อ สถานะพนักงานจะ
          เปลี่ยนจาก พนักงานทดลองงาน เป็น พนักงานประจำ โดยอัตโนมัติ
          คุณสามารถปรับเรทเงินเดือนหรือ ข้อมูลต่าง ๆ อีกครั้งได้ที่เมนู
          ข้อมูลพนักงาน
        </div>
      </div>
    </div>

    <div class="row mb-20px">
      <div class="col-lg-12">
        <div class="hr-form-control">
          <div class="input-group">
            <div class="input-title">หมายเหตุเพิ่มเติม</div>
            <textarea name="remark" class="input"></textarea>
          </div>
        </div>
      </div>
    </div>


  </div>
  <div class="modal-footer">
    <input type="hidden" name="id" value="<?= $_POST['id'] ?>">
    <?php
    TiwForm::normal('btn', '', ['name' => '', 'type' => 'button', 'class' => 'btn-close-modal h-50px border-radius-15px', 'data-bs-dismiss' => 'modal'], ['text' => Ty::get(['ยกเลิก', ''])]);
    TiwForm::normal('btn', '', ['name' => 'submit_approved_probation', 'type' => 'submit', 'class' => 'btn-success h-50px border-radius-15px'], ['text' => 'ผ่านการทดลองงาน']);
    ?>
  </div>
</form>