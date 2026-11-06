<div class="container">
  <div class="max-w-750px mx-auto">
    <div class="card rounded-20px">
      <div class="card-body py-25px px-30px">
        <div class="row py-7px">
          <div class="col-12 mb-15px">
            <div class="font-15px fw-semibold">รายละเอียด</div>
          </div>
        </div>
        <div class="row py-7px">
          <div class="col-md-5">
            <div class="text-secondary font-Medium">ชื่อสาขา</div>
          </div>
          <div class="col-md-7">
            <div class="font-Medium"><?= $branch['name'] ?></div>
          </div>
        </div>
        <hr>
        <div class="row py-7px">
          <div class="col-md-5">
            <div class="text-secondary font-Medium">ที่ตั้ง</div>
          </div>
          <div class="col-md-7">
            <div class="font-Medium"><?= $branch['location'] ?></div>
          </div>
        </div>
        <hr>
        <div class="row py-7px">
          <div class="col-md-5">
            <div class="text-secondary font-Medium">รหัสสาขา</div>
          </div>
          <div class="col-md-7">
            <?= $branch['branch_code'] ?>
          </div>
        </div>
        <hr>
        <div class="row py-7px">
          <div class="col-md-5">
            <div class="text-secondary font-Medium">ผู้ดูแล / ผู้ที่ติดต่อได้</div>
          </div>
          <div class="col-md-7">
            <?= $branch['contact_person'] ?>
          </div>
        </div>
        <hr>
        <div class="row py-7px">
          <div class="col-md-5">
            <div class="text-secondary font-Medium">เบอร์ติดต่อ</div>
          </div>
          <div class="col-md-7">
            <?php
            if ($branch['tel']) {
              echo implode(', ', $branch['tel']);
            }
            ?>
          </div>
        </div>
        <hr>
        <div class="row py-7px">
          <div class="col-md-5">
            <div class="text-secondary font-Medium">อีเมล</div>
          </div>
          <div class="col-md-7">
            <?php
            if ($branch['email']) {
              echo implode(', ', $branch['email']);
            }
            ?>
          </div>
        </div>
        <hr>
        <div class="row py-7px">
          <div class="col-md-5">
            <div class="text-secondary font-Medium">พนักงานประจำสาขา</div>
          </div>
          <div class="col-md-7">
            <?= number_format($branch['count_user'], 0) ?> คน
          </div>
        </div>
        <hr>
        <div class="row py-7px">
          <div class="col-md-5">
            <div class="text-secondary font-Medium">รายละเอียดเพิ่มเติม</div>
          </div>
          <div class="col-md-7">
            <?= $branch['description'] ?>
          </div>
        </div>
        <hr>
      </div>
    </div>

    <div class="d-flex align-items-center mt-20px mb-20px">
      <div class="me-3"> <?= file_get_contents('assets/icons/history.svg') ?></div>
      <div class="text-info"> แก้ไขข้อมูลล่าสุด : <?= W_Main::formatDate($branch['update_date_time'], 'd M Y', true,false); ?> โดย <?= $branch['update_by_fullname'] ?></div>
    </div>
  </div>
</div>