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
            <div class="input-title required">ชื่อวันหยุด</div>
            <input type="text" class="input" name="title" value="<?= $holiday['title'] ?>" required>
          </div>
        </div>
      </div>
      <div class="col-12 my-15px">
        <div class="btn-group-radio">
          <label for="type_full" class="card-select-label event_change_type">
            <input type="radio" id="type_full" name="type" value="one_day" <?= $holiday['type'] == 'one_day' ? 'checked' : '' ?>>
            <div class="card-select-content">
              <div class="font-16px fw-medium txt-selected"> หยุด 1 วัน </div>
            </div>
          </label>
          <label for="type_half" class="card-select-label event_change_type">
            <input type="radio" id="type_half" name="type" value="period" <?= $holiday['type'] == 'period' ? 'checked' : '' ?>>
            <div class="card-select-content">
              <div class="font-16px fw-medium txt-selected"> หยุดเป็นช่วง </div>
            </div>
          </label>
        </div>
      </div>

      <div class="scope_date">
        <?php if ($holiday['type'] == 'one_day') { ?>
          <?= templateTypeDateFull($holiday) ?>
        <?php } else { ?>
          <?= templateTypeDateHalf($holiday) ?>
        <?php } ?>
      </div>

      <div class="col-lg-12 mb-15px">
        <div class="hr-form-control">
          <div class="input-group">
            <div class="input-title">รายละเอียดเพิ่มเติม</div>
            <textarea name="description" class="input" value="<?= $holiday['description'] ?>"><?= $holiday['description'] ?></textarea>
          </div>
        </div>
      </div>

      <div class="col-lg-12 mb-15px">
        <div class="font-15px fw-semibold mb-5px">กำหนดสิทธิพนักงานที่ได้หยุดงาน</div>
        <div>
          เลือกรูปแบบพนักงานที่ได้รับสิทธิหยุดงาน (คุณสามารถตั้งค่ารูปแบบ
          พนักงานได้ที่ <a href="setting_working_time.php" class="text-primary text-decoration-underline">ตั้งค่ารูปแบบเวลาทำงาน</a>)
        </div>
      </div>

      <div class="col-lg-12 mb-15px">
        <?php foreach ($type_dropdown as $type) { ?>
          <?php
          $checked = '';
          if (in_array($type['id'], $holiday['role_use'])) {
            $checked = 'checked';
          }
          ?>
          <div class="mb-5px">
            <?php
            TiwForm::normal('checkbox', $type['id'], ['name' => 'role_use[]', 'class' => '', $checked => 1], ['style' => '3', 'label' => $type['title']]);
            ?>
          </div>
        <?php } ?>
      </div>

      <div class="col-lg-12 mb-15px">
        <div class="font-15px fw-semibold mb-5px">พนักงานที่ต้องมาทำงาน</div>
      </div>

      <div class="col-lg-12 mb-15px">
        <div class="hr-form-control">
          <div class="input-group">
            <div class="input-title">เลือกพนักงาน</div>
            <select class="select2-multiple" name="request_user_ids[]" multiple="multiple">
              <?php foreach ($emp_dropdown as $emp) { ?>
                <?php
                $selected = '';
                if (in_array($emp['id'], $holiday['request_user_ids'])) {
                  $selected = 'selected';
                }
                ?>
                <option value="<?= $emp['id'] ?>" <?= $selected ?>><?= $emp['title_dropdown'] ?></option>
              <?php } ?>

            </select>
          </div>
        </div>
      </div>

      <div class="col-lg-12">
        <div class="d-flex justify-content-end">
          <a href="scheduled_holidays_detail.php?id=<?= $id ?>" class="btn btn-default btn-lg rounded-15px max-w-150px w-100 me-2">
            <div class="fw-semibold">ยกเลิก</div>
          </a>
          <button type="submit" name="submit_update_holiday" class="btn btn-primary btn-lg rounded-15px max-w-150px w-100">
            <div class="fw-semibold">บันทึก</div>
          </button>
        </div>
      </div>
    </div>
  </div>
</div>