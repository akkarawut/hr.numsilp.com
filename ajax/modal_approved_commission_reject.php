<?php
require_once '../wloves/_framework/import_for_ajax.php';
W_Structure::loadMetaForAjax('../');

$id =  $_POST['id'];
$data = phr_commission::getCommissionByID(API_CODE, $id);

?>
<form action="" method="post">
  <div class="modal-header justify-content-center">
    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
      <span class="font-18px" aria-hidden="true">&times;</span>
    </button>
  </div>
  <div class="modal-body px-20px m-auto max-w-450px pt-0">
    <div class="font-24px font-SemiBold text-center mb-5px"><?= Ty::get(['ไม่อนุมัติค่าคอมมิชชั่น', '']) ?></div>
    <div class="text-center">
      <p class="text-dark mb-5px">
        คุณแน่ใจหรือไม่ที่จะ <span class="text-danger">ไม่อนุมัติ</span> ค่าคอมมิชชั่น ของ
      </p>
      <p class="fw-semibold mb-10px">
        <?= $data['user_fullname'] ?>
      </p>
    </div>

    <div class="row mb-20px">
      <div class="fw-semibold">
        รายละเอียด
      </div>
    </div>
    <div class="row mb-20px">
      <div class="col-md-5">
        <div class="text-secondary font-Medium">
          ชื่อพนักงาน
        </div>
      </div>
      <div class="col-lg-7">
        <div class="text-dark font-Medium">
          <?= $data['user_fullname'] ?>
        </div>
      </div>
    </div>
    <div class="row mb-20px">
      <div class="col-md-5">
        <div class="text-secondary font-Medium">
          ยอดขาย
        </div>
      </div>
      <div class="col-lg-7">
        <div class="text-dark font-Medium">
          <?= number_format($data['sale_price'], 2) ?> บาท
        </div>
      </div>
    </div>
    <div class="row mb-20px">
      <div class="col-md-5">
        <div class="text-secondary font-Medium">
          ค่าคอมมิชชั่น
        </div>
      </div>
      <div class="col-lg-7">
        <div class="text-dark font-Medium">
          <?= number_format($data['commission_price'], 2) ?> บาท
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-lg-12  mb-20px">
        <div class="fw-semibold">บันทึกเพิ่มเติม</div>
      </div>

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
    TiwForm::normal('btn', '', ['name' => 'submit_approved_commission_reject', 'type' => 'submit', 'class' => 'btn-danger h-50px border-radius-15px'], ['text' => 'ไม่อนุมัติบันทึก']);
    ?>
  </div>
</form>