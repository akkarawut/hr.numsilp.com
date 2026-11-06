<?php
require_once '../wloves/_framework/import_for_ajax.php';
W_Structure::loadMetaForAjax('../');

$id =  $_POST['id'];
$type =  $_POST['type'];
$data = phr_salary::getSalaryByID(API_CODE, $id);

?>
<form action="" method="post">
  <div class="modal-header justify-content-center">
    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
      <span class="font-18px" aria-hidden="true">&times;</span>
    </button>
  </div>
  <div class="modal-body px-20px m-auto max-w-450px pt-0">
    <div class="font-24px font-SemiBold text-center mb-5px">ไม่อนุมัติ<?= $type == 'day' ? 'จ่ายค่าจ้าง' : 'จ่ายเงินเดือน' ?></div>
    <div class="text-center">
      <p class="text-dark mb-5px">
        คุณแน่ใจหรือไม่ที่จะ <span class="text-danger">ไม่อนุมัติ</span> <?= $type == 'day' ? 'จ่ายค่าจ้าง' : 'จ่ายเงินเดือน' ?>
      </p>
      <p class="fw-semibold mb-10px">
        <?= $data['user_fullname'] ?>
      </p>
    </div>

    <div class="row mb-20px">
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
    <input type="hidden" name="id" value="<?= $_POST['id'] ?>">
    <?php
    TiwForm::normal('btn', '', ['name' => '', 'type' => 'button', 'class' => 'btn-close-modal h-50px border-radius-15px', 'data-bs-dismiss' => 'modal'], ['text' => Ty::get(['ยกเลิก', ''])]);
    TiwForm::normal('btn', '', ['name' => 'submit_payment_reject', 'type' => 'submit', 'class' => 'btn-danger h-50px border-radius-15px'], ['text' => 'ไม่อนุมัติบันทึก']);
    ?>
  </div>
</form>