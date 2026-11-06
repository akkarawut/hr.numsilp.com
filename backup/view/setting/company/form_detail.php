<div class="container">
  <div class="max-w-1000px mx-auto">
    <div class="card rounded-20px">
      <div class="card-body py-25px px-30px">
        <div class="row py-7px">
          <div class="col-12 mb-15px">
            <div class="font-15px fw-semibold">รายละเอียด</div>
          </div>
        </div>
        <div class="row py-7px">
          <div class="col-md-5">
            <div class="title-field">
              <div class="icon">
                <?= file_get_contents('assets/icons/home.svg') ?>
              </div>
              <div class="text-secondary font-Medium">ชื่อองค์กร</div>
            </div>
          </div>
          <div class="col-md-7">
            <div class="font-Medium"><?= $company['name'] ?></div>
          </div>
        </div>
        <hr>
        <div class="row py-7px">
          <div class="col-md-5">
            <div class="title-field">
              <div class="icon"></div>
              <div class="text-secondary font-Medium">ที่ตั้ง</div>
            </div>
          </div>
          <div class="col-md-7">
            <div class="font-Medium"><?= $company['location'] ?></div>
          </div>
        </div>
        <hr>
        <div class="row py-7px">
          <div class="col-md-5">
            <div class="title-field">
              <div class="icon"></div>
              <div class="text-secondary font-Medium">รหัสสาขา</div>
            </div>
          </div>
          <div class="col-md-7">
            <?= $company['branch_code'] ?>
          </div>
        </div>
        <hr>
        <div class="row py-7px">
          <div class="col-md-5">
            <div class="title-field">
              <div class="icon"></div>
              <div class="text-secondary font-Medium">ประเภทธุรกิจ</div>
            </div>
          </div>
          <div class="col-md-7">
            <?= $company['type_business_name'] ?>
          </div>
        </div>
        <hr>
        <div class="row py-7px">
          <div class="col-md-5">
            <div class="title-field">
              <div class="icon"></div>
              <div class="text-secondary font-Medium">เลขประจำตัวผู้เสียภาษี</div>
            </div>
          </div>
          <div class="col-md-7">
            <?= $company['tax_number'] ?>
          </div>
        </div>
        <hr>
        <div class="row py-7px">
          <div class="col-md-5">
            <div class="title-field">
              <div class="icon">
                <?= file_get_contents('assets/icons/icon-email.svg') ?>
              </div>
              <div class="text-secondary font-Medium">อีเมล</div>
            </div>
          </div>
          <div class="col-md-7">
            <?php
            if ($company['email']) {
              echo implode(', ', $company['email']);
            }
            ?>
          </div>
        </div>
        <hr>
        <div class="row py-7px">
          <div class="col-md-5">
            <div class="title-field">
              <div class="icon">
                <?= file_get_contents('assets/icons/icon-call.svg') ?>
              </div>
              <div class="text-secondary font-Medium">เบอร์ติดต่อ</div>
            </div>
          </div>
          <div class="col-md-7">
            <?php
            if ($company['tel']) {
              echo implode(', ', $company['tel']);
            }
            ?>
          </div>
        </div>
        <hr>
        <div class="row py-7px">
          <div class="col-md-5">
            <div class="title-field">
              <div class="icon"></div>
              <div class="text-secondary font-Medium">FAX</div>
            </div>
          </div>
          <div class="col-md-7">
            <?= $company['fax_number'] ?>
          </div>
        </div>
        <hr>
      </div>
    </div>

    <div class="d-flex align-items-center mt-20px mb-20px">
      <div class="me-3"> <?= file_get_contents('assets/icons/history.svg') ?></div>
      <div class="text-info"> แก้ไขข้อมูลล่าสุด : <?= W_Main::formatDate($company['update_date_time'], 'd M Y', true,false); ?> โดย <?= $company['update_by_fullname'] ?></div>
    </div>
  </div>
</div>