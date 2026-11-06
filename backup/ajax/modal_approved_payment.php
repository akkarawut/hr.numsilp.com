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
    <div class="font-24px font-SemiBold text-center mb-5px">อนุมัติ<?= $type == 'day' ? 'จ่ายค่าจ้าง' : 'จ่ายเงินเดือน' ?></span></div>
    <div class="text-center">
      <p class="text-dark mb-5px">
        คุณแน่ใจหรือไม่ที่จะ <span class="text-success">อนุมัติ<?= $type == 'day' ? 'จ่ายค่าจ้าง' : 'จ่ายเงินเดือน' ?></span> ของ
      </p>
      <p class="fw-semibold mb-10px">
        <?= $data['user_fullname'] ?>
      </p>
    </div>
  </div>
  <div class="modal-footer">
    <input type="hidden" name="id" value="<?= $_POST['id'] ?>">
    <?php
    TiwForm::normal('btn', '', ['name' => '', 'type' => 'button', 'class' => 'btn-close-modal h-50px border-radius-15px', 'data-bs-dismiss' => 'modal'], ['text' => Ty::get(['ยกเลิก', ''])]);
    TiwForm::normal('btn', '', ['name' => 'submit_payment', 'type' => 'submit', 'class' => 'btn-success h-50px border-radius-15px'], ['text' => 'อนุมัติ']);
    ?>
  </div>
</form>