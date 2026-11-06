<div class="container">
  <div class="max-w-500px mx-auto">
    <div class="card rounded-20px">
      <div class="card-body py-25px px-30px">
        <div class="row py-7px">
          <div class="col-12 mb-15px">
            <div class="font-15px fw-semibold">รายละเอียด</div>
          </div>
        </div>
        <div class="row py-7px">
          <div class="col-md-5">
            <div class="text-secondary font-Medium">ชื่อเงื่อนไข</div>
          </div>
          <div class="col-md-7">
            <div class="font-Medium"><?= $deduct['title'] ?></div>
          </div>
        </div>
        <hr>
        <div class="row py-7px">
          <div class="col-md-5">
            <div class="text-secondary font-Medium">เงื่อนไข</div>
          </div>
          <div class="col-md-7">
            <div class="font-Medium"><?= $arr_condition_name[$deduct['type']] ?> <?= $deduct['amount'] ?> ครั้งในรอบเดือน</div>
          </div>
        </div>
        <hr>
        <div class="row py-7px">
          <div class="col-md-5">
            <div class="text-secondary font-Medium">ยอดปรับ</div>
          </div>
          <div class="col-md-7">
            <?= number_format($deduct['price'], 2) ?> บาท
          </div>
        </div>
        <hr>
        <div class="row py-7px">
          <div class="col-md-5">
            <div class="text-secondary font-Medium">รายละเอียดเพิ่มเติม</div>
          </div>
          <div class="col-md-7">
            <?= $deduct['description'] ?>
          </div>
        </div>
        <hr>
        <div class="row py-7px">
          <div class="col-md-5">
            <div class="text-secondary font-Medium">การใช้งาน</div>
          </div>
          <div class="col-md-7">
            <?php if ($deduct['is_use']) { ?>
              <div class="d-flex align-items-center">
                <span class="st-dot primary me-2"></span>
                <div class="text-primary">เปิดใช้งาน</div>
              </div>
            <?php } else { ?>
              <div class="d-flex align-items-center">
                <span class="st-dot secondary me-2"></span>
                <div class="text-secondary">ปิดใช้งาน</div>
              </div>
            <?php } ?>
          </div>
        </div>
        <hr>
        <div class="row mb-15px mt-15px">
          <div class="col-md-12 mb-15px">
            <div class="font-Medium">รูปแบบพนักงานที่ใช้งาน</div>
          </div>
          <div class="col-md-12">
            <?php foreach ($role_dropdown as $type) { ?>
              <div class="font-Medium d-flex align-items-center mb-10px">
                <div class="me-2">
                  <?php if (in_array($type['id'], $deduct['role_use'])) { ?>
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
      </div>
    </div>

    <div class="d-flex align-items-center mt-20px mb-20px">
      <div class="me-3"> <?= file_get_contents('assets/icons/history.svg') ?></div>
      <div class="text-info"> แก้ไขข้อมูลล่าสุด : <?= W_Main::formatDate($deduct['update_date_time'], 'd M Y', true, false); ?> โดย <?= $deduct['update_by_fullname'] ?></div>
    </div>
  </div>
</div>