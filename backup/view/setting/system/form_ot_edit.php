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
            <div class="input-title required">ชื่อเงื่อนไข</div>
            <input type="text" class="input" name="title" value="<?= $ot['title'] ?>" required>
          </div>
        </div>
      </div>

      <div class="col-lg-8 mb-15px">
        <div class="d-flex align-items-center">
          <div class="hr-form-control">
            <div class="input-group">
              <div class="input-title required">อัตราจ่าย (บาท)</div>
              <input type="number" step="0.01" class="input" name="price" required value="<?= $ot['price'] ?>">
              <div class="input-title-abs">บาท</div>
            </div>
          </div>
          <div class="pt-15px ms-2 nowrap">ต่อ ชั่วโมง</div>
        </div>
      </div>


      <div class="col-lg-12 mb-15px">
        <div class="hr-form-control">
          <div class="input-group">
            <div class="input-title">รายละเอียดเพิ่มเติม</div>
            <textarea name="description" class="input" value="<?= $ot['description'] ?>"><?= $ot['description'] ?></textarea>
          </div>
        </div>
      </div>

      <div class="col-lg-12 mb-15px">
        <?php
        $text_show = $ot['is_use'] ? '<span class="text-primary ml-5px">เปิดใช้งาน</span>' : '<span class="text-secondary ml-5px">ปิดใช้งาน</span>';
        TiwForm::normal('checkbox', 1, ['name' => 'is_use', 'class' => 'event_change_txt_on_off', 'checked' => $ot['is_use'] ?  1 : 0], ['style' => '1', 'label' => $text_show]);
        ?>
      </div>

      <div class="col-lg-12 mb-15px">
        <div class="font-15px fw-semibold mb-5px">พนักงานที่เงื่อนไขนี้มีผล</div>
        <div>
          เลือกรูปแบบพนักงานที่ใช้เงื่อนไขนี้ (คุณสามารถตั้งค่ารูปแบบ
          พนักงานได้ที่ <a href="setting_working_time.php" class="text-primary text-decoration-underline">ตั้งค่ารูปแบบเวลาทำงาน</a>)
        </div>
      </div>

      <div class="col-lg-12 mb-15px">
        <?php foreach ($role_dropdown as $type) { ?>
          <?php
          $checked = '';
          if (in_array($type['id'], $ot['role_use'])) {
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

      <div class="col-lg-12">
        <div class="d-flex justify-content-end">
          <a href="ot_conditions_detail.php?id=<?= $id ?>" class="btn btn-default btn-lg rounded-15px max-w-150px w-100 me-2">
            <div class="fw-semibold">ยกเลิก</div>
          </a>
          <button type="submit" name="submit_update_ot" class="btn btn-primary btn-lg rounded-15px max-w-150px w-100">
            <div class="fw-semibold">บันทึก</div>
          </button>
        </div>
      </div>
    </div>
  </div>
</div>