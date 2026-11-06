<div class="container-fluid mt-5">
  <div class="max-w-750px mx-auto">
    <div class="d-flex align-items-center justify-content-between">
      <div class="d-flex align-items-center">
        <div class="icon-back mr-10px">
          <a href="employee_detail.php?id=<?= $id ?>&mode=<?= $mode ?>"> <?= file_get_contents('assets/icons/icon-left-arrow.svg') ?></a>
        </div>
        <h1 class="title-header m-0">แก้ไขข้อมูลพนักงาน</h1>
      </div>
    </div>
  </div>
  <hr>
</div>

<form method="post" enctype="multipart/form-data" class="mb-10px">
  <div class="container">
    <div class="max-w-750px mx-auto">

      <div class="d-flex justify-content-center my-25px">
        <div class="box-img-upload">
          <?php
          $options = [
            'width' => '150px',
            'height' => '150px',
            'bg-img' => 'assets/images/placeholder.png',
            'is_btn' => 0,
            'is_view' => 0,
            'is_delete' => 0,
            'preview_name' => '{name}',
          ];
          TiwForm::normal('upload-img', $emp['profile_image'], ['id' => 'profile-avatar', 'name' => 'file_profile'], $options);
          ?>
          <div class="icon-upload-file">
            <img src="assets/images/btn-upload.png" alt="">
          </div>
        </div>
      </div>

      <div class="row mb-15px">
        <div class="col-lg-12 text-center">
          <div class="fw-semibold font-22px"><?= $emp['full_name'] ?></div>
          <div class="font-Medium text-secondary"><?= $emp['username'] ?></div>
        </div>
      </div>


      <div class="card rounded-20px">
        <div class="card-body py-25px px-30px">
          <div class="row">
            <div class="col-12 mb-15px">
              <div class="font-15px fw-semibold">ข้อมูลเบื้องต้นของพนักงาน</div>
            </div>
          </div>
          <div class="row">
            <div class="col-12 mb-15px">
              <div class="hr-form-control">
                <div class="input-group">
                  <div class="input-title required">ชื่อพนักงาน</div>
                  <input type="text" class="input" name="full_name" required value="<?= $emp['full_name'] ?>" readonly>
                </div>
              </div>
            </div>

            <div class="col-12 mb-15px">
              <div class="hr-form-control">
                <div class="input-group">
                  <div class="input-title required">รหัสพนักงาน</div>
                  <input type="text" class="input" name="employee_number" required value="<?= $emp['employee_number'] ?>">
                </div>
              </div>
            </div>

            <div class="col-12 mb-15px">
              <div class="hr-form-control">
                <div class="input-group">
                  <div class="input-title required">รูปแบบการทำงาน</div>
                  <select name="working_time_type_id" class="input" required>
                    <option value="" selected disabled>เลือก</option>
                    <?php foreach ($type_dropdown as $val) { ?>
                      <option value="<?= $val['id'] ?>" <?= $emp['working_time_type_id'] == $val['id'] ? 'selected' : '' ?>><?= $val['title'] ?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>
            </div>


            <div class="col-12 mb-15px">
              <div class="hr-form-control">
                <div class="input-group">
                  <div class="input-title required">สถานที่ทำงาน</div>
                  <select name="branch_id" class="input" required>
                    <option value="" selected disabled>เลือก</option>
                    <?php foreach ($branch_dropdown as $val) { ?>
                      <option value="<?= $val['id'] ?>" <?= $emp['branch_id'] == $val['id'] ? 'selected' : '' ?>><?= $val['name'] ?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>
            </div>

            <div class="col-12 mb-15px">
              <div class="hr-form-control">
                <div class="input-group">
                  <div class="input-title required">สถานะพนักงาน</div>
                  <select name="employee_status" class="input" readonly required disabled>
                    <option value="" selected disabled>เลือก</option>
                    <?php foreach ($status_emp_arr as $key => $val) { ?>
                      <option value="<?= $key ?>" <?= $emp['employee_status'] == $key ? 'selected' : '' ?>><?= $val ?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>
            </div>

            <div class="col-12 mb-15px">
              <div class="hr-form-control">
                <div class="input-group">
                  <div class="input-title"><?= $emp['employee_status'] == 'day' ? 'ค่าจ้างรายวัน' : 'เงินเดือน' ?> (บาท)</div>
                  <input type="number" class="input" name="salary" value="<?= $emp['salary'] ?>">
                </div>
              </div>
            </div>

            <?php if ($emp['employee_status'] != 'day') { ?>
              <div class="col-6 mb-15px">
                <div class="d-flex align-items-center">
                  <div class="pt-15px">คำนวณเงินเดือนทุกวันที่</div>
                  <div class="hr-form-control max-w-100px mx-2">
                    <div class="input-group">
                      <div class="input-title required">วันที่</div>
                      <input type="number" step="1" class="input" name="day_cal_salary" value="<?= $emp['day_cal_salary'] ?>" required>
                    </div>
                  </div>
                  <div class="pt-15px">ของเดือน</div>
                </div>
              </div>

              <div class="col-6 mb-15px">
                <div class="d-flex align-items-center">
                  <div class="pt-15px">และวันที่</div>
                  <div class="hr-form-control max-w-100px mx-2">
                    <div class="input-group">
                      <div class="input-title required">วันที่</div>
                      <input type="number" step="1" class="input" name="day_cal_salary2" value="<?= $emp['day_cal_salary2'] ?>" required>
                    </div>
                  </div>
                  <div class="pt-15px">ของเดือน</div>
                </div>
              </div>
            <?php } else { ?>
              <div class="col-12 mb-15px">
                <span class="text-danger">** คำนวณค่าจ้างรายวันหลังจากเสร็จสิ้นเวลาทำงานของวัน</span>
              </div>
            <?php } ?>
          </div>

          <div id="scope_price_box" class="row">
            <?php if ($emp['employee_status'] == 'month') { ?>
              <div class="col-lg-6 mb-15px">
                <div class="hr-form-control">
                  <div class="input-group">
                    <div class="input-title required">ประกันสังคม</div>
                    <select name="is_security" class="input event_change_secure" required>
                      <option value="" selected disabled>เลือก</option>
                      <option value="1" <?= $emp['is_security'] == 1 ? 'selected' : '' ?>>หักประกันสังคม</option>
                      <option value="0" <?= $emp['is_security'] == 0 ? 'selected' : '' ?>>ไม่หักประกันสังคม</option>
                    </select>
                  </div>
                </div>
              </div>
              <div class="col-lg-3 event_display_box_secure mb-15px <?= $emp['is_security'] == 1 ? '' : 'd-none' ?>">
                <div class="hr-form-control">
                  <div class="input-group">
                    <div class="input-title required">ยอดหัก</div>
                    <input type="number" min="0" max="9999999" step="0.01" class="input" name="security_price" value="<?= $emp['security_price'] ? $emp['security_price'] : 0 ?>" required>
                  </div>
                </div>
              </div>
              <div class="col-lg-3 event_display_box_secure mb-15px <?= $emp['is_security'] == 1 ? '' : 'd-none' ?>">
                <div class="hr-form-control">
                  <div class="input-group">
                    <div class="input-title required">หน่วย</div>
                    <select name="security_unit" class="input" required>
                      <option value="bath" <?= $emp['security_unit'] == 'bath' ? 'selected' : '' ?>>บาท</option>
                      <option value="percent" <?= $emp['security_unit'] == 'percent' ? 'selected' : '' ?>>เปอร์เซ็นต์</option>
                    </select>
                  </div>
                </div>
              </div>
            <?php } ?>
          </div>

          <div class="row">
            <div class="col-6 mb-15px">
              <div class="hr-form-control">
                <div class="input-group">
                  <div class="input-title">ฝ่าย</div>
                  <input type="text" class="input" name="department" value="<?= $emp['department'] ?>">
                </div>
              </div>
            </div>

            <div class="col-6 mb-15px">
              <div class="hr-form-control">
                <div class="input-group">
                  <div class="input-title">แผนก</div>
                  <input type="text" class="input" name="division" value="<?= $emp['division'] ?>">
                </div>
              </div>
            </div>

            <div class="col-6 mb-15px">
              <div class="hr-form-control">
                <div class="input-group">
                  <div class="input-title">ตำแหน่ง</div>
                  <input type="text" class="input" name="position" value="<?= $emp['position'] ?>">
                </div>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="scope_chage_col <?= $emp['employee_status'] == 'trial_work' ? 'col-6' : 'col-12' ?> mb-15px">
              <div class="hr-form-control">
                <div class="input-group">
                  <div class="input-title required">วันที่เริ่มทำงาน</div>
                  <input type="date" class="input" name="working_start_date" value="<?= $emp['working_start_date'] ?>" required>
                </div>
              </div>
            </div>

            <div id="scope_row_date_end" class="col-6 mb-15px">
              <?php if ($emp['employee_status'] == 'trial_work') { ?>
                <div class="hr-form-control">
                  <div class="input-group">
                    <div class="input-title required">วันที่สิ้นสุดทดลองงาน</div>
                    <input type="date" class="input" name="working_end_date" value="<?= $emp['working_end_date'] ?>" required>
                  </div>
                </div>
              <?php } ?>
            </div>
          </div>


          <div class="row">
            <div class="col-12 mb-15px">
              <div class="font-15px fw-semibold">ข้อมูลติดต่อ</div>
            </div>
          </div>

          <div id="scope_row_tel" class="row">
            <?php if ($emp['tel']) { ?>
              <?php foreach ($emp['tel'] as $key_tel => $tel) { ?>
                <?php if ($key_tel == 0) { ?>
                  <div class="col-12 mb-15px">
                    <div class="hr-form-control">
                      <div class="input-group">
                        <div class="input-title">เบอร์โทรศัพท์</div>
                        <input type="number" class="input" name="tel[]" value="<?= isset($emp['tel'][0]) ? $emp['tel'][0] : '' ?>">
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

          <div id="scope_row_email" class="row">
            <?php if ($emp['email']) { ?>
              <?php foreach ($emp['email'] as $key_email => $email) { ?>
                <?php if ($key_email == 0) { ?>
                  <div class="col-12 mb-15px">
                    <div class="hr-form-control">
                      <div class="input-group">
                        <div class="input-title">อีเมล</div>
                        <input type="email" class="input" name="email[]" value="<?= isset($emp['email'][0]) ? $emp['email'][0] : '' ?>">
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


          <div class="row">
            <div class="col-lg-6 mb-15px">
              <div class="hr-form-control">
                <div class="input-group">
                  <div class="input-title">Facebook</div>
                  <input type="text" class="input" name="social_facebook" value="<?= $emp['social_facebook'] ?>">
                </div>
              </div>
            </div>
            <div class="col-lg-6 mb-15px">
              <div class="hr-form-control">
                <div class="input-group">
                  <div class="input-title">Line</div>
                  <input type="text" class="input" name="social_line" value="<?= $emp['social_line'] ?>">
                </div>
              </div>
            </div>
            <div class="col-lg-6 mb-15px">
              <div class="hr-form-control">
                <div class="input-group">
                  <div class="input-title">IG</div>
                  <input type="text" class="input" name="social_ig" value="<?= $emp['social_ig'] ?>">
                </div>
              </div>
            </div>
            <div class="col-lg-6 mb-15px">
              <div class="hr-form-control">
                <div class="input-group">
                  <div class="input-title">TikTok</div>
                  <input type="text" class="input" name="social_tiktok" value="<?= $emp['social_tiktok'] ?>">
                </div>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-12 mb-15px">
              <div class="font-15px fw-semibold">ข้อมูลประกอบอื่น ๆ</div>
            </div>
          </div>


          <div class="row">
            <div class="col-12 mb-15px">
              <div class="hr-form-control">
                <div class="input-group">
                  <div class="input-title">ไซส์เสื้อ</div>
                  <input type="size" class="input" name="size" value="<?= $emp['size'] ?>">
                </div>
              </div>
            </div>
            <div class="col-lg-12 mb-15px">
              <div class="hr-form-control">
                <div class="input-group">
                  <div class="input-title">หมายเหตุพนักงาน</div>
                  <textarea name="remark" class="input" value="<?= $emp['remark'] ?>"><?= $emp['remark'] ?></textarea>
                </div>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-12 mb-15px">
              <div class="font-15px fw-semibold">ข้อมูลธนาคาร</div>
            </div>
          </div>

          <div class="row">

            <div class="col-12 mb-15px">
              <div class="hr-form-control">
                <div class="input-group">
                  <div class="input-title">ธนาคาร</div>
                  <select name="bank_code" class="input" required>
                    <?php foreach ($bank_dropdown as $bank) { ?>
                      <option value="<?= $bank['abb'] ?>" <?= $emp['bank_code'] == $bank['abb'] ? 'selected' : '' ?>><?= $bank['name_th'] ?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>
            </div>

            <div class="col-6 mb-15px">
              <div class="hr-form-control">
                <div class="input-group">
                  <div class="input-title required">ชื่อบัญชี</div>
                  <input type="text" class="input" name="bank_account_name" value="<?= $emp['bank_account_name'] ?>" required>
                </div>
              </div>
            </div>
            <div class="col-6 mb-15px">
              <div class="hr-form-control">
                <div class="input-group">
                  <div class="input-title required">เลขที่บัญชี</div>
                  <input type="number" class="input" name="bank_account_number" value="<?= $emp['bank_account_number'] ?>" required>
                </div>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-12 mb-15px">
              <div class="font-15px fw-semibold">การยืนยันตัวตน</div>
            </div>
          </div>

          <!-- <div id="scope_row_file" class="row"> -->
          <div class="row mb-10px">
            <div class="col-md-3">
              <div class="input-title">บัตรประชาชน</div>
            </div>
            <div class="col-md-9 mb-15px __event_row">
              <?php if ($emp['file_id_card']['code']) { ?>
                <div class="d-flex align-items-center scope_event_file" data-file="<?= $emp['file_id_card']['code'] ?>">
                  <div class="input-file-w-100">
                    <?= TiwForm::normal('file', $emp['file_id_card']['path_file'], ['name' => 'file_id_card', 'class' => 'w-100'], ['text' => $emp['file_id_card']['file_name']]) ?>
                  </div>
                  <!-- <div class="min-50px">
                    <button type="button" class="btn-icon fill-danger" data-file="<?= $emp['file_id_card']['path_file'] ?>" onclick="deleteFile(this,'event_delete_file');">
                      <?= file_get_contents('assets/icons/remove.svg') ?>
                    </button>
                  </div> -->
                </div>
              <?php } else { ?>
                <div class="input-file-w-100">
                  <?= TiwForm::normal('file', '', ['name' => 'file_id_card'], []) ?>
                </div>
              <?php } ?>
            </div>
          </div>

          <div class="row mb-10px">
            <div class="col-md-3">
              <div class="input-title">ทะเบียนบ้าน</div>
            </div>
            <div class="col-md-9 mb-15px __event_row">
              <?php if ($emp['file_house_regis']['code']) { ?>
                <div class="d-flex align-items-center scope_event_file" data-file="<?= $emp['file_house_regis']['code'] ?>">
                  <div class="input-file-w-100">
                    <?= TiwForm::normal('file', $emp['file_house_regis']['path_file'], ['name' => 'file_house_regis', 'class' => 'w-100'], ['text' => $emp['file_house_regis']['file_name']]) ?>
                  </div>
                  <!-- <div class="min-50px">
                    <button type="button" class="btn-icon fill-danger" data-file="<?= $emp['file_house_regis']['code'] ?>" onclick="deleteFile(this,'event_delete_file');">
                      <?= file_get_contents('assets/icons/remove.svg') ?>
                    </button>
                  </div> -->
                </div>
              <?php } else { ?>
                <div class="input-file-w-100">
                  <?= TiwForm::normal('file', '', ['name' => 'file_house_regis'], []) ?>
                </div>
              <?php } ?>
            </div>
          </div>

          <div class="row mb-10px">
            <div class="col-md-3">
              <div class="input-title">วุฒิการศึกษา</div>
            </div>
            <div class="col-md-9 mb-15px __event_row">
              <?php if ($emp['file_edu_qual']['code']) { ?>
                <div class="d-flex align-items-center scope_event_file" data-file="<?= $emp['file_edu_qual']['code'] ?>">
                  <div class="input-file-w-100">
                    <?= TiwForm::normal('file', $emp['file_edu_qual']['path_file'], ['name' => 'file_edu_qual', 'class' => 'w-100'], ['text' => $emp['file_edu_qual']['file_name']]) ?>
                  </div>
                  <!-- <div class="min-50px">
                    <button type="button" class="btn-icon fill-danger" data-file="<?= $emp['file_edu_qual']['code'] ?>" onclick="deleteFile(this,'event_delete_file');">
                      <?= file_get_contents('assets/icons/remove.svg') ?>
                    </button>
                  </div> -->
                </div>
              <?php } else { ?>
                <div class="input-file-w-100">
                  <?= TiwForm::normal('file', '', ['name' => 'file_edu_qual'], []) ?>
                </div>
              <?php } ?>
            </div>
          </div>

          <div class="row mb-10px">
            <div class="col-md-3">
              <div class="input-title">ใบผ่านการเกณฑ์ทหาร</div>
            </div>
            <div class="col-md-9 mb-15px __event_row">
              <?php if ($emp['file_soldier_certificate']['code']) { ?>
                <div class="d-flex align-items-center scope_event_file" data-file="<?= $emp['file_soldier_certificate']['code'] ?>">
                  <div class="input-file-w-100">
                    <?= TiwForm::normal('file', $emp['file_soldier_certificate']['path_file'], ['name' => 'file_soldier_certificate', 'class' => 'w-100'], ['text' => $emp['file_soldier_certificate']['file_name']]) ?>
                  </div>
                  <!-- <div class="min-50px">
                    <button type="button" class="btn-icon fill-danger" data-file="<?= $emp['file_soldier_certificate']['code'] ?>" onclick="deleteFile(this,'event_delete_file');">
                      <?= file_get_contents('assets/icons/remove.svg') ?>
                    </button>
                  </div> -->
                </div>
              <?php } else { ?>
                <div class="input-file-w-100">
                  <?= TiwForm::normal('file', '', ['name' => 'file_soldier_certificate'], []) ?>
                </div>
              <?php } ?>
            </div>
          </div>

          <input type="hidden" class="event_delete_file" name="delete_file_arr" value="">

          <?php
          /*
<?php if ($emp['file_other_list']) {  ?>
<?php foreach ($emp['file_other_list'] as $file_verify) { ?>
<div class="col-12 mb-15px __event_row">
<div class="d-flex align-items-center">
<div class="input-file-w-100">
<?= TiwForm::normal('file', $file_verify['path_file'], ['name' => 'file_others[]'], ['text' => $file_verify['file_name']]) ?>
</div>
<div class="min-50px">
<button type="button" class="btn-icon fill-danger" data-file="<?= $file_verify['code']  ?>" onclick="deleteFile(this,'event_delete_file');">
<?= file_get_contents('assets/icons/remove.svg') ?>
</button>
</div>
</div>
</div>
<?php } ?>
<?php } ?>
</div>
 */ ?>

          <!-- <button type="button" class="btn btn-light-primary w-100 mb-15px" onclick="addRow('scope_row_file','verify');">+ เพิ่มเอกสารยืนยันตัวตน</button> -->

          <div class="row">
            <div class="col-12 mb-25px">
              <div class="font-15px fw-semibold">ข้อมูลสำหรับเข้าใช้งานระบบ</div>
            </div>
          </div>

          <div class="row">
            <div class="col-3 mb-15px">
              <div class="text-secondary">
                การเข้าใช้งานระบบ
              </div>
            </div>
            <div class="col-9 mb-15px">
              <?php
              $text_show = $emp['is_can_login'] ? '<span class="text-primary ml-5px">เข้าใช้ได้</span>' : '<span class="text-secondary ml-5px">ปิดเข้าใช้งาน</span>';
              $check_can_login = $emp['is_can_login'] ? 'checked' : '';
              TiwForm::normal('checkbox', 1, ['name' => 'is_can_login', 'class' => 'event_change_txt_can_login', $check_can_login => 1], ['style' => '1', 'label' => $text_show]);
              ?>
            </div>
          </div>
          <div class="row">
            <div class="col-12 mb-15px">
              <div class="hr-form-control">
                <div class="input-group">
                  <div class="input-title required">ชื่อบัญชี</div>
                  <input type="text" class="input" name="username" value="<?= $emp['username'] ?>" required>
                </div>
              </div>
            </div>

            <div class="col-12 mb-15px">
              <div class="hr-form-control">
                <div class="input-group">
                  <div class="input-title required">รหัสผ่าน</div>
                  <input type="password" class="input" name="password" value="<?= $emp['password'] ?>" required>
                </div>
              </div>
            </div>

            <?php
            /* <div class="col-12 mb-15px">
<div class="hr-form-control">
<div class="input-group">
<div class="input-title required">สิทธิการใช้งานระบบ</div>
<select name="user_code" class="input" required>
<option value="" selected disabled>เลือก</option>
<?php foreach ($per_dropdown as $val) { ?>
<option value="<?= $val['code'] ?>" <?= $emp['user_code'] == $val['code'] ? 'selected'  : '' ?>><?= $val['name'] ?></option>
<?php } ?>
</select>
</div>
</div>
</div>
 */ ?>
          </div>


          <div class="row mt-20px">
            <div class="col-lg-12">
              <div class="d-flex justify-content-end">
                <a href="employee_detail.php?id=<?= $emp['id'] ?>&mode=<?= $mode ?>" class="btn btn-default btn-lg rounded-15px max-w-150px w-100 me-2">
                  <div class="fw-semibold">ยกเลิก</div>
                </a>
                <button type="submit" name="submit_update_employee" class="btn btn-primary btn-lg rounded-15px max-w-150px w-100">
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