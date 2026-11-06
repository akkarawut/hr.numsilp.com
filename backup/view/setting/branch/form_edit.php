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
            <div class="input-title required">ชื่อสาขา</div>
            <input type="text" class="input" name="name" required value="<?= $branch['name'] ?>">
          </div>
        </div>
      </div>

      <div class="col-6 mb-15px">
        <div class="hr-form-control">
          <div class="input-group">
            <div class="input-title required">รหัสสาขา</div>
            <input type="text" class="input" name="branch_code" required value="<?= $branch['branch_code'] ?>">
          </div>
        </div>
      </div>

      <div class="col-lg-12 mb-15px">
        <div class="hr-form-control">
          <div class="input-group">
            <div class="input-title required">ที่ตั้ง</div>
            <textarea name="location" class="input" value="<?= $branch['location'] ?>" rows="2" required><?= $branch['location'] ?></textarea>
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-12 mb-15px">
        <div class="hr-form-control">
          <div class="input-group">
            <div class="input-title">ผู้ดูแล / ผู้ที่ติดต่อได้</div>
            <input type="text" class="input" name="contact_person" value="<?= $branch['contact_person'] ?>">
          </div>
        </div>
      </div>
    </div>

    <div id="scope_row_email" class="row">
      <?php if ($branch['email']) { ?>
        <?php foreach ($branch['email'] as $key_email => $email) { ?>
          <?php if ($key_email == 0) { ?>
            <div class="col-12 mb-15px">
              <div class="hr-form-control">
                <div class="input-group">
                  <div class="input-title">อีเมล</div>
                  <input type="email" class="input" name="email[]" value="<?= isset($branch['email'][0]) ? $branch['email'][0] : '' ?>">
                </div>
              </div>
            </div>
          <?php } else { ?>
            <div class="col-12 mb-15px __event_row">
              <div class="hr-form-control">
                <div class="input-group">
                  <input type="email" class="input" name="email[]" value="<?= $email ?>">
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
      <?php } ?>
    </div>
    <button type="button" class="btn btn-light-primary w-100 mb-15px" onclick="addRow('scope_row_email','email');">+ เพิ่มอีเมล</button>

    <div id="scope_row_tel" class="row">
      <?php if ($branch['tel']) { ?>
        <?php foreach ($branch['tel'] as $key_tel => $tel) { ?>
          <?php if ($key_tel == 0) { ?>
            <div class="col-12 mb-15px">
              <div class="hr-form-control">
                <div class="input-group">
                  <div class="input-title">เบอร์โทรศัพท์</div>
                  <input type="number" class="input" name="tel[]" value="<?= isset($branch['tel'][0]) ? $branch['tel'][0] : '' ?>">
                </div>
              </div>
            </div>
          <?php } else { ?>
            <div class="col-12 mb-15px __event_row">
              <div class="hr-form-control">
                <div class="input-group">
                  <input type="number" class="input" name="tel[]" value="<?= $tel ?>">
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
      <?php } ?>
    </div>

    <button type="button" class="btn btn-light-primary w-100 mb-15px" onclick="addRow('scope_row_tel','tel');">+ เพิ่มเบอร์โทรศัพท์</button>

    <div class="row">
      <div class="col-lg-12 mb-15px">
        <div class="hr-form-control">
          <div class="input-group">
            <div class="input-title">รายละเอียดเพิ่มเติม</div>
            <textarea name="description" class="input" value="<?= $branch['description'] ?>"><?= $branch['description'] ?></textarea>
          </div>
        </div>
      </div>
    </div>

    <div class="row mt-20px">
      <div class="col-lg-12">
        <div class="d-flex justify-content-end">
          <a href="branch_detail.php?id=<?= $id ?>" class="btn btn-default btn-lg rounded-15px max-w-150px w-100 me-2">
            <div class="fw-semibold">ยกเลิก</div>
          </a>
          <button type="submit" name="submit_update_branch" class="btn btn-primary btn-lg rounded-15px max-w-150px w-100">
            <div class="fw-semibold">บันทึก</div>
          </button>
        </div>
      </div>
    </div>
  </div>
</div>