<form method="post">
  <div class="container">
    <div class="max-w-1000px mx-auto">
      <p class="sub-title my-25px">ตั้งค่ารายละเอียดองค์กรสำหรับใช้ภายในระบบ (นับเป็นสถานที่เข้างาน - ออกงานได้ของพนักงาน)</p>

      <div class="card rounded-20px">
        <div class="card-body py-25px px-30px">
          <div class="row">
            <div class="col-12 mb-15px">
              <div class="font-15px fw-semibold">รายละเอียด</div>
            </div>
          </div>
          <div class="row">
            <div class="col-12 mb-15px">
              <div class="hr-form-control">
                <div class="input-group">
                  <div class="input-title required">ชื่อองค์กร</div>
                  <input type="text" class="input" name="name" required value="<?= $company['name'] ?>">
                </div>
              </div>
            </div>

            <div class="col-lg-12 mb-15px">
              <div class="hr-form-control">
                <div class="input-group">
                  <div class="input-title">ที่ตั้ง</div>
                  <textarea name="location" class="input" value="<?= $company['location'] ?>"><?= $company['location'] ?></textarea>
                </div>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-6 mb-15px">
              <div class="hr-form-control">
                <div class="input-group">
                  <div class="input-title required">รหัสสาขา</div>
                  <input type="text" class="input" name="branch_code" required value="<?= $company['branch_code'] ?>">
                </div>
              </div>
            </div>

            <div class="col-lg-6 mb-15px">
              <div class="hr-form-control">
                <div class="input-group">
                  <div class="input-title required">ประเภทธุรกิจ</div>
                  <select name="type_business_id" class="input" required>
                    <option value="" selected disabled>เลือก</option>
                    <?php foreach ($business_dropdown as $business) {  ?>
                      <option value="<?= $business['id'] ?>" <?= $company['type_business_id'] == $business['id'] ? 'selected' : '' ?>>
                        <?= $business['name'] ?>
                      </option>
                    <?php  }  ?>
                  </select>
                </div>
              </div>
            </div>

            <div class="col-12 mb-15px">
              <div class="hr-form-control">
                <div class="input-group">
                  <div class="input-title required">เลขประจำตัวผู้เสียภาษี</div>
                  <input type="text" class="input" name="tax_number" required value="<?= $company['tax_number'] ?>">
                </div>
              </div>
            </div>
          </div>

          <div id="scope_row_email" class="row">
            <?php foreach ($company['email'] as $key_email => $email) { ?>
              <?php if ($key_email == 0) { ?>
                <div class="col-12 mb-15px">
                  <div class="hr-form-control">
                    <div class="input-group">
                      <div class="input-title">อีเมล</div>
                      <input type="text" class="input" name="email[]" value="<?= isset($company['email'][0]) ? $company['email'][0] : '' ?>">
                    </div>
                  </div>
                </div>
              <?php } else { ?>
                <div class="col-12 mb-15px __event_row">
                  <div class="hr-form-control">
                    <div class="input-group">
                      <input type="text" class="input" name="email[]" value="<?= $email ?>">
                    </div>
                    <div class="input-delete">
                      <button type="button" class="btn-delete-text" onclick="deleteRow(this);">
                        <?= file_get_contents('assets/icons/remove.svg') ?>
                      </button>
                    </div>
                  </div>
                </div>
              <?php } ?>
            <?php } ?>
          </div>
          <button type="button" class="btn btn-light-primary w-100 mb-15px" onclick="addRow('scope_row_email','email');">+ เพิ่มอีเมล</button>

          <div id="scope_row_tel" class="row">
            <?php foreach ($company['tel'] as $key_tel => $tel) { ?>
              <?php if ($key_tel == 0) { ?>
                <div class="col-12 mb-15px">
                  <div class="hr-form-control">
                    <div class="input-group">
                      <div class="input-title">เบอร์โทรศัพท์</div>
                      <input type="text" class="input" name="tel[]" value="<?= isset($company['tel'][0]) ? $company['tel'][0] : '' ?>">
                    </div>
                  </div>
                </div>
              <?php } else { ?>
                <div class="col-12 mb-15px __event_row">
                  <div class="hr-form-control">
                    <div class="input-group">
                      <input type="text" class="input" name="tel[]" value="<?= $tel ?>">
                    </div>
                    <div class="input-delete">
                      <button type="button" class="btn-delete-text" onclick="deleteRow(this);">
                        <?= file_get_contents('assets/icons/remove.svg') ?>
                      </button>
                    </div>
                  </div>
                </div>
              <?php } ?>
            <?php } ?>
          </div>

          <button type="button" class="btn btn-light-primary w-100 mb-15px" onclick="addRow('scope_row_tel','tel');">+ เพิ่มเบอร์โทรศัพท์</button>

          <div class="row">
            <div class="col-12 mb-15px">
              <div class="hr-form-control">
                <div class="input-group">
                  <div class="input-title">Fax</div>
                  <input type="text" class="input" name="fax_number" value="<?= $company['fax_number'] ?>">
                </div>
              </div>
            </div>
          </div>

          <div class="scope_condition row mt-20px">
            <div class="col-lg-12">
              <div class="d-flex justify-content-end">
                <a href="setting_company.php" class="btn btn-default btn-lg rounded-15px max-w-150px w-100 me-2">
                  <div class="fw-semibold">ยกเลิก</div>
                </a>
                <button type="submit" name="submit_update_company" class="btn btn-primary btn-lg rounded-15px max-w-150px w-100">
                  <div class="fw-semibold">บันทึก</div>
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</form>