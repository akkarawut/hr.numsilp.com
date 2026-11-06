<div class="card rounded-20px">
  <div class="card-body py-25px px-30px">
    <div class="row">
      <div class="col-12 mb-15px">
        <div class="font-15px fw-semibold">
          กำหนดอัตราหักจ่ายค่าประกันสังคมตั้งต้น จากฐานเงินเดือนของพนักงาน
          <span class="text-danger">*หลังจากตั้งค่าแล้วยังสามารถแก้ไขผ่านข้อมูลรายบุคคล ของพนักงานได้</span>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-12 my-15px">
        <div class="btn-group-radio">
          <label for="is_security_1" class="event_select_type_secure card-select-label">
            <input type="radio" id="is_security_1" name="is_security" value="1" <?= $setting_basic['is_security'] == '1' ? 'checked' : '' ?>>
            <div class="card-select-content">
              <div class="font-16px fw-medium txt-selected"> หักประกันสังคม </div>
            </div>
          </label>
          <label for="is_security_0" class="event_select_type_secure card-select-label">
            <input type="radio" id="is_security_0" name="is_security" value="0" <?= $setting_basic['is_security'] == '0' ? 'checked' : '' ?>>
            <div class="card-select-content">
              <div class="font-16px fw-medium txt-selected"> ไม่หักประกันสังคม </div>
            </div>
          </label>
        </div>
      </div>
    </div>
    <div id="scope_type_secure" class="row">
      <?php if ($setting_basic['is_security'] == 1) { ?>
        <?= templateSecure($setting_basic) ?>
      <?php } ?>
    </div>
    <div class="row">
      <div class="col-lg-12 my-10px">
        รายการนี้จะถูกคำนวณทุกครั้งเมื่อคำนวณเงินเดือน
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