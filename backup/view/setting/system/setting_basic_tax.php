<div class="card rounded-20px">
  <div class="card-body py-25px px-30px">
    <div class="row">
      <div class="col-12 mb-15px">
        <div class="font-15px">
          กำหนดอัตราการหักภาษี ณ ที่จ่ายตั้งต้นเมื่ออนุมัติจ่าย ค่าคอมมิชชั่น ของพนักงาน
          <span class="text-danger">*หลังจากตั้งค่าแล้วยังสามารถแก้ไขได้ภายในตรวจสอบ ความถูกต้องค่าคอมมิชชั่น</span>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-12 mb-15px">
        <div class="hr-form-control">
          <div class="input-group">
            <div class="input-title required">ภาษี ณ ที่จ่าย</div>
            <input type="number" class="input" name="tax" required value="<?= $setting_basic['tax'] ?>">
            <div class="input-title-abs">%</div>
          </div>
        </div>
      </div>

      <div class="col-lg-12 mt-10px mb-20px">
        รายการนี้จะถูกคำนวณทุกครั้งเมื่อคำนวณค่าคอมมิชชั่นได้
      </div>

      <div class="col-lg-12">
        <div class="d-flex justify-content-end">
          <a href="setting_system.php?page=set_setting_basic" class="btn btn-default btn-lg rounded-15px max-w-150px w-100 me-2">
            <div class="fw-semibold">ยกเลิก</div>
          </a>
          <button type="submit" name="submit_update_setting_basic" class="btn btn-primary btn-lg rounded-15px max-w-150px w-100">
            <div class="fw-semibold">บันทึก</div>
          </button>
        </div>
      </div>
    </div>
  </div>
</div>