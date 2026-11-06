<?php
require_once '../wloves/_framework/import_for_ajax.php';
W_Structure::loadMetaForAjax('../');

$id =  $_POST['id'];
$data = phr_commission::getCommissionByID(API_CODE, $id);
$setting_basic = phr_setting_basic::getSettingBasic(API_CODE);



?>
<form action="" method="post" enctype="multipart/form-data">
  <div class="modal-header justify-content-center">
    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
      <span class="font-18px" aria-hidden="true">&times;</span>
    </button>
  </div>
  <div class="modal-body px-20px m-auto max-w-450px pt-0">
    <div class="font-24px font-SemiBold text-center mb-5px"><?= Ty::get(['ยืนยันความถูกต้องค่าคอมมิชชั่น', '']) ?></div>
    <div class="text-center">
      <p class="text-dark mb-20px">
        คุณแน่ใจหรือไม่ว่าจะยืนยัน ค่าคอมมิชชั่นนี้ <br>
        หลังจากยืนยันแล้ว รายการค่าคอมมิชชั่นนี้จะถูกนำไปรวมกับรายได้ <br>
        ของพนักงาน
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
        <div class="text-dark font-Medium ">
          <span class="txt_commission"><?= number_format($data['commission_price'], 2) ?></span> บาท
        </div>
      </div>
    </div>
    <div class="row mb-20px">
      <div class="col-lg-12">
        <div class="fw-semibold">กำหนดภาษีหัก ณ ที่จ่าย</div>
        <div>กำหนดอัตราภาษีหัก ณ ที่จ่ายสำหรับยอดคอมมิชชั่นนี้</div>
      </div>
    </div>
    <div class="row  mb-20px">
      <div class="col-lg-12">
        <div class="hr-form-control">
          <div class="input-group">
            <div class="input-title required">อัตราภาษี</div>
            <input type="number" min="0" max="100" value="<?= $setting_basic['tax'] ?>" class="input" name="tax" onblur="calTotalPrice(this);" required>
            <div class="input-title-abs">%</div>
          </div>
        </div>
      </div>
    </div>
    <div class="row mb-20px">
      <div class="col-md-5">
        <div class="text-secondary font-Medium">
          ยอดที่ได้รับหลัง <br>
          คำนวณอัตราภาษี
        </div>
      </div>
      <div class="col-lg-7">
        <div class="text-dark font-Medium">
          <span class="txt_cal_txt">
            <?php
            $tax_price =  $data['commission_price'] * ($setting_basic['tax'] / 100);
            $sum_com_price = $data['commission_price'] - $tax_price;
            ?>
            <?= number_format($sum_com_price, 2) ?> บาท
          </span>
        </div>
      </div>
    </div>
    <div class="row mb-20px">
      <div class="col-md-5">
        <div class="text-secondary font-Medium">
          หมายเหตุ
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
          ไฟล์ประกอบ
        </div>
      </div>
      <div class="col-lg-7">
        <div class="text-dark font-Medium">
          <?= $data['image_list'] ? count($data['image_list']) : 0 ?> ไฟล์
        </div>
      </div>
    </div>

    <div class="row mb-20px">
      <div class="col-md-5">
        <div class="text-secondary font-Medium">
          อัพโหลดไฟล์
        </div>
      </div>
      <div class="col-lg-7">
        <div class="input-file-w-100">
          <?= TiwForm::normal('file', '', ['name' => 'file'], ['text' => '']) ?>
        </div>
      </div>
    </div>


  </div>
  <div class="modal-footer">
    <input type="hidden" name="id" value="<?= $_POST['id'] ?>">
    <?php
    TiwForm::normal('btn', '', ['name' => '', 'type' => 'button', 'class' => 'btn-close-modal h-50px border-radius-15px', 'data-bs-dismiss' => 'modal'], ['text' => Ty::get(['ยกเลิก', ''])]);
    TiwForm::normal('btn', '', ['name' => 'submit_approved_commission', 'type' => 'submit', 'class' => 'btn-success h-50px border-radius-15px'], ['text' => 'ยืนยันรายการ']);
    ?>
  </div>
</form>