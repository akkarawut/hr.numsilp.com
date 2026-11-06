<div class="card rounded-20px">
  <div class="card-body py-25px px-30px">
    <div class="row py-7px">
      <div class="col-12 mb-15px">
        <div class="font-15px fw-semibold">รายละเอียด</div>
      </div>
    </div>
    <div class="row py-7px">
      <div class="col-md-5">
        <div class="text-secondary font-Medium">ชื่อรูปแบบ</div>
      </div>
      <div class="col-md-7">
        <div class="font-Medium"><?= $holiday['title'] ?></div>
      </div>
    </div>
    <hr>

    <div class="row py-7px">
      <div class="col-md-5">
        <div class="text-secondary font-Medium">วันที่หยุด</div>
      </div>
      <div class="col-md-7">
        <div class="font-Medium">
          <?php
          if ($holiday['from_date'] == $holiday['to_date']) {
            echo W_Main::formatDate($holiday['from_date'], 'd/m/Y');
          } else {
            echo W_Main::formatDate($holiday['from_date'], 'd/m/Y') . '-' . W_Main::formatDate($holiday['to_date'], 'd/m/Y');
          }
          ?>
        </div>
      </div>
    </div>
    <hr>
    <div class="row py-7px">
      <div class="col-md-5">
        <div class="text-secondary font-Medium">ระยะเวลารวม</div>
      </div>
      <div class="col-md-7">
        <div class="font-Medium"><?= number_format($holiday['sum_day_work'], 0) ?> วัน</div>
      </div>
    </div>
    <hr>
    <div class="row py-7px">
      <div class="col-md-5">
        <div class="text-secondary font-Medium">รายละเอียดเพิ่มเติม</div>
      </div>
      <div class="col-md-7">
        <?= $holiday['description'] ?>
      </div>
    </div>
    <hr>

    <div class="row mb-15px mt-15px">
      <div class="col-md-12 mb-15px">
        <div class="font-Medium">พนักงานที่ได้สิทธิหยุดงาน</div>
      </div>
      <div class="col-md-12">
        <?php foreach ($type_dropdown as $type) { ?>
          <div class="font-Medium d-flex align-items-center mb-10px">
            <div class="me-2">
              <?php if (in_array($type['id'], $holiday['role_use'])) { ?>
                <?= file_get_contents('assets/icons/check-primary.svg'); ?>
              <?php } else { ?>
                <?= file_get_contents('assets/icons/cancel.svg'); ?>
              <?php } ?>
            </div>
            <div><?= $type['title'] ?></div>
          </div>
        <?php } ?>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12 mb-15px">
        <div class="font-Medium">พนักงานที่งดเว้นวันหยุด (ต้องมาทำงาน)</div>
      </div>
      <div class="col-md-12">
        <?php if ($holiday['request_user_ids']) { ?>
          <?php foreach ($emp_dropdown as $emp) { ?>
            <?php if (in_array($emp['id'], $holiday['request_user_ids'])) { ?>
              <div class="d-flex align-items-center">
                <?= $emp['title_dropdown'] ?>
              </div>
            <?php } ?>
          <?php } ?>
        <?php } else { ?>
          <div class="font-Medium text-secondary">ไม่ได้กำหนด</div>
        <?php } ?>
      </div>
    </div>
  </div>
</div>

<div class="d-flex align-items-center mt-20px mb-20px">
  <div class="me-3"> <?= file_get_contents('assets/icons/history.svg') ?></div>
  <div class="text-info"> แก้ไขข้อมูลล่าสุด : <?= W_Main::formatDate($holiday['update_date_time'], 'd M Y', true, false); ?> โดย <?= $holiday['update_by_fullname'] ?></div>
</div>