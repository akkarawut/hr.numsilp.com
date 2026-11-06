<div class="row">
  <div class="col-lg-6">
    <div class="card rounded-20px">
      <div class="card-body py-25px px-30px">
        <div class="row py-7px">
          <div class="col-12 mb-15px">
            <div class="font-15px fw-semibold">ข้อมูลเบื้องต้นของพนักงาน </div>
          </div>
        </div>
        <div class="row py-7px">
          <div class="col-md-4">
            <div class="text-secondary font-Medium">ชื่อพนักงาน</div>
          </div>
          <div class="col-md-8">
            <div class="font-Medium"><?= $emp['full_name'] ?></div>
          </div>
        </div>
        <hr>
        <div class="row py-7px">
          <div class="col-md-4">
            <div class="text-secondary font-Medium">รหัสพนักงาน</div>
          </div>
          <div class="col-md-8">
            <div class="font-Medium"><?= $emp['employee_number'] ?></div>
          </div>
        </div>
        <hr>
        <div class="row py-7px">
          <div class="col-md-4">
            <div class="text-secondary font-Medium">รูปแบบการทำงาน</div>
          </div>
          <div class="col-md-8">
            <div class="font-Medium">
              <?= $emp['working_time_type_name'] ?>
            </div>
            <div class="font-14px text-secondary">
              <?= $emp['working_time_hour'] ?> ชั่วโมง : สัปดาห์
            </div>
          </div>
        </div>
        <hr>
        <div class="row py-7px">
          <div class="col-md-4">
            <div class="text-secondary font-Medium">สถานที่ทำงาน</div>
          </div>
          <div class="col-md-8">
            <div class="font-Medium"><?= $emp['branch_name'] ?></div>
          </div>
        </div>
        <hr>
        <div class="row py-7px">
          <div class="col-md-4">
            <div class="text-secondary font-Medium">สถานะพนักงาน</div>
          </div>
          <div class="col-md-8">
            <div class="font-Medium">
              <?= isset($status_emp_arr[$emp['employee_status']]) ? $status_emp_arr[$emp['employee_status']] : ''; ?>
            </div>
          </div>
        </div>
        <hr>

        <div class="row py-7px">
          <div class="col-md-4">
            <div class="text-secondary font-Medium"><?= $emp['employee_status'] == 'day' ? 'ค่าจ้างรายวัน' : 'เงินเดือน' ?> (บาท)</div>
          </div>
          <div class="col-md-8">
            <div class="font-Medium"><?= number_format($emp['salary'], 2) ?> บาท</div>
          </div>
        </div>
        <hr>

        <?php if ($emp['employee_status'] != 'day') { ?>
          <div class="row py-7px">
            <div class="col-md-4">
              <div class="text-secondary font-Medium">เงื่อนไขการคำนวณเงินเดือน</div>
            </div>
            <div class="col-md-8">
              <div class="font-Medium">คำนวณทุกวันที่ <?= $emp['day_cal_salary'] ?> และ <?= $emp['day_cal_salary2'] ?> ของเดือน </div>
            </div>
          </div>
          <hr>

          <div class="row py-7px">
            <div class="col-md-4">
              <div class="text-secondary font-Medium">ประกันสังคม</div>
            </div>
            <div class="col-md-8">
              <?php if ($emp['is_security'] == 1) { ?>
                <div class="font-Medium">หักจ่าย <?= number_format($emp['security_price'], 2) ?> <?= $emp['security_unit'] == 'percent' ? 'เปอร์เซ็นต์' : 'บาท' ?>ต่อเดือน</div>
              <?php } else { ?>
                <div class="font-Medium">ไม่หักประกันสังคม</div>
              <?php } ?>
            </div>
          </div>
          <hr>
        <?php } ?>
        <div class="row py-7px">
          <div class="col-md-4">
            <div class="text-secondary font-Medium">ฝ่าย</div>
          </div>
          <div class="col-md-8">
            <div class="font-Medium"><?= $emp['department'] ?></div>
          </div>
        </div>
        <hr>

        <div class="row py-7px">
          <div class="col-md-4">
            <div class="text-secondary font-Medium">แผนก</div>
          </div>
          <div class="col-md-8">
            <div class="font-Medium"><?= $emp['division'] ?></div>
          </div>
        </div>
        <hr>

        <div class="row py-7px">
          <div class="col-md-4">
            <div class="text-secondary font-Medium">ตำแหน่ง</div>
          </div>
          <div class="col-md-8">
            <div class="font-Medium"><?= $emp['position'] ?></div>
          </div>
        </div>
        <hr>
        <div class="row py-7px">
          <div class="col-md-4">
            <div class="text-secondary font-Medium">วันที่เริ่มทำงาน</div>
          </div>
          <div class="col-md-8">
            <div class="font-Medium"><?= W_Main::formatDate($emp['working_start_date'], 'd/m/Y') ?></div>
          </div>
        </div>
        <hr>
        <?php if ($emp['employee_status'] != 'trial_work') { ?>
          <div class="row py-7px">
            <div class="col-md-4">
              <div class="text-secondary font-Medium">อายุงาน</div>
            </div>
            <div class="col-md-8">
              <div class="font-Medium"><?= $emp['period_working'] ?></div>
            </div>
          </div>
          <hr>
        <?php } else { ?>
          <div class="row py-7px">
            <div class="col-md-4">
              <div class="text-secondary font-Medium">วันที่สิ้นสุด</div>
            </div>
            <div class="col-md-8">
              <div class="font-Medium"><?= W_Main::formatDate($emp['working_end_date'], 'd/m/Y') ?></div>
            </div>
          </div>
          <hr>
        <?php } ?>

        <div class="row py-7px">
          <div class="col-12 mb-15px">
            <div class="font-15px fw-semibold">ข้อมูลติดต่อ</div>
          </div>
        </div>
        <div class="row py-7px">
          <div class="col-md-4">
            <div class="text-secondary font-Medium">เบอร์โทรศัพท์</div>
          </div>
          <div class="col-md-8">
            <div class="font-Medium">
              <?php
              if ($emp['tel']) {
                echo implode(', ', $emp['tel']);
              }
              ?>
            </div>
          </div>
        </div>
        <hr>
        <div class="row py-7px">
          <div class="col-md-4">
            <div class="text-secondary font-Medium">อีเมล</div>
          </div>
          <div class="col-md-8">
            <div class="font-Medium">
              <?php
              if ($emp['email']) {
                echo implode(', ', $emp['email']);
              }
              ?>
            </div>
          </div>
        </div>
        <hr>
        <div class="row py-7px">
          <div class="col-md-4">
            <div class="text-secondary font-Medium">Facebook</div>
          </div>
          <div class="col-md-8">
            <div class="font-Medium"><?= $emp['social_facebook'] ?></div>
          </div>
        </div>
        <hr>
        <div class="row py-7px">
          <div class="col-md-4">
            <div class="text-secondary font-Medium">Line</div>
          </div>
          <div class="col-md-8">
            <div class="font-Medium"><?= $emp['social_line'] ?></div>
          </div>
        </div>
        <hr>
        <div class="row py-7px">
          <div class="col-md-4">
            <div class="text-secondary font-Medium">IG</div>
          </div>
          <div class="col-md-8">
            <div class="font-Medium"><?= $emp['social_ig'] ?></div>
          </div>
        </div>
        <hr>
        <div class="row py-7px">
          <div class="col-md-4">
            <div class="text-secondary font-Medium">TikTok</div>
          </div>
          <div class="col-md-8">
            <div class="font-Medium"><?= $emp['social_tiktok'] ?></div>
          </div>
        </div>
        <hr>

        <div class="row py-7px">
          <div class="col-12 mb-15px">
            <div class="font-15px fw-semibold">ข้อมูลประกอบอื่น ๆ</div>
          </div>
        </div>
        <div class="row py-7px">
          <div class="col-md-4">
            <div class="text-secondary font-Medium">ไซส์เสื้อ</div>
          </div>
          <div class="col-md-8">
            <div class="font-Medium"><?= $emp['size'] ?></div>
          </div>
        </div>
        <hr>
        <div class="row py-7px">
          <div class="col-md-4">
            <div class="text-secondary font-Medium">หมายเหตุพนักงาน</div>
          </div>
          <div class="col-md-8">
            <div class="font-Medium"><?= $emp['remark'] ?></div>
          </div>
        </div>
      </div>
    </div>

    <?php if ($emp['update_user_id'] > 0) { ?>
      <div class="d-flex align-items-center mt-20px mb-20px">
        <div class="me-3"> <?= file_get_contents('assets/icons/history.svg') ?></div>
        <div class="text-info"> แก้ไขข้อมูลล่าสุด : <?= W_Main::formatDate($emp['update_date_time'], 'd M Y', true, false); ?> โดย <?= $emp['update_by_fullname'] ?></div>
      </div>
    <?php } ?>

  </div>
  <div class="col-lg-6">
    <div class="card rounded-20px">
      <div class="card-body py-25px px-30px">
        <div class="row py-7px">
          <div class="col-12 mb-15px">
            <div class="font-15px fw-semibold">ข้อมูลบัญชีธนาคาร</div>
          </div>
        </div>
        <div class="row py-7px">
          <div class="col-md-4">
            <div class="text-secondary font-Medium">ธนาคาร</div>
          </div>
          <div class="col-md-8">
            <div class="font-Medium"><?= $emp['bank_name'] ?></div>
          </div>
        </div>
        <hr>
        <div class="row py-7px">
          <div class="col-md-4">
            <div class="text-secondary font-Medium">ชื่อบัญชี</div>
          </div>
          <div class="col-md-8">
            <div class="font-Medium"><?= $emp['bank_account_name'] ?></div>
          </div>
        </div>
        <hr>
        <div class="row py-7px">
          <div class="col-md-4">
            <div class="text-secondary font-Medium">เลขบัญชี</div>
          </div>
          <div class="col-md-8">
            <div class="font-Medium"><?= $emp['bank_account_number'] ?></div>
          </div>
        </div>
        <hr>
        <div class="row py-7px">
          <div class="col-md-12 mb-10px">
            <div class="font-Medium">การยืนยันตัวตน</div>
          </div>
          <div class="col-md-12 mt-5px">
            <?php if ($emp['file_id_card']['code']) {  ?>
              <div class="d-flex justify-content-between align-items-center mb-15px">
                <div class="d-flex">
                  บัตรประชาชน : &nbsp;
                  <a href="<?= $emp['file_id_card']['path_file'] ?>" target="_blank" class="font-Medium text-primary">
                    <?= $emp['file_id_card']['file_name'] ?>
                  </a>
                </div>
                <a href="<?= $emp['file_id_card']['path_file'] ?>" target="_blank" download>
                  <?= file_get_contents('assets/icons/down.svg') ?>
                </a>
              </div>
            <?php } else { ?>
              <div class="mb-15px">
                บัตรประชาชน : -
              </div>
            <?php } ?>

            <?php if ($emp['file_house_regis']['code']) {  ?>
              <div class="d-flex justify-content-between align-items-center mb-15px">
                <div class="d-flex">
                  ทะเบียนบ้าน : &nbsp;
                  <a href="<?= $emp['file_house_regis']['path_file'] ?>" target="_blank" class="font-Medium text-primary">
                    <?= $emp['file_house_regis']['file_name'] ?>
                  </a>
                </div>
                <a href="<?= $emp['file_house_regis']['path_file'] ?>" target="_blank" download>
                  <?= file_get_contents('assets/icons/down.svg') ?>
                </a>
              </div>
            <?php } else { ?>
              <div class="mb-15px">
                ทะเบียนบ้าน : -
              </div>
            <?php } ?>

            <?php if ($emp['file_edu_qual']['code']) {  ?>
              <div class="d-flex justify-content-between align-items-center mb-15px">
                <div class="d-flex">
                  วุฒิการศึกษา : &nbsp;
                  <a href="<?= $emp['file_edu_qual']['path_file'] ?>" target="_blank" class="font-Medium text-primary">
                    <?= $emp['file_edu_qual']['file_name'] ?>
                  </a>
                </div>
                <a href="<?= $emp['file_edu_qual']['path_file'] ?>" target="_blank" download>
                  <?= file_get_contents('assets/icons/down.svg') ?>
                </a>
              </div>
            <?php } else { ?>
              <div class="mb-15px">
                วุฒิการศึกษา : -
              </div>
            <?php } ?>

            <?php if ($emp['file_soldier_certificate']['code']) {  ?>
              <div class="d-flex justify-content-between align-items-center mb-15px">
                <div class="d-flex">
                  ใบผ่านการเกณฑ์ทหาร : &nbsp;
                  <a href="<?= $emp['file_soldier_certificate']['path_file'] ?>" target="_blank" class="font-Medium text-primary">
                    <?= $emp['file_soldier_certificate']['file_name'] ?>
                  </a>
                </div>
                <a href="<?= $emp['file_soldier_certificate']['path_file'] ?>" target="_blank" download>
                  <?= file_get_contents('assets/icons/down.svg') ?>
                </a>
              </div>
            <?php } else { ?>
              <div class="mb-15px">
                ใบผ่านการเกณฑ์ทหาร : -
              </div>
            <?php } ?>

          </div>
        </div>
      </div>
    </div>


    <div class="card rounded-20px my-15px">
      <div class="card-body pt-25px px-30px">
        <div class="row py-7px">
          <div class="col-12 mb-15px">
            <div class="font-15px fw-semibold">ข้อมูลสำหรับเข้าใช้งานระบบ</div>
          </div>
        </div>
        <div class="row py-7px">
          <div class="col-md-4">
            <div class="text-secondary font-Medium">บัญชีผู้ใช้</div>
          </div>
          <div class="col-md-8">
            <div class="font-Medium"><?= $emp['username'] ?></div>
          </div>
        </div>
        <hr>
        <div class="row py-7px">
          <div class="col-md-4">
            <div class="text-secondary font-Medium">รหัสผ่าน</div>
          </div>
          <div class="col-md-8">
            <div class="d-flex flex-wrap justify-content-between">
              <div>
                <div class="font-Medium"> ●●●●●●●●●</div>
                <div> แก้ไขข้อมูลล่าสุด : <?= W_Main::formatDate($emp['password_datetime'], 'd M Y', true, false); ?> โดย <?= $emp['password_username'] ? $emp['password_username']  : 'SYSTEM' ?> </div>
              </div>
              <button type="button" class="btn btn-link p-0" <?php Tiwdal::register('modal_change_password', $emp); ?>>แก้ไขรหัสผ่าน</button>
            </div>
          </div>
        </div>
        <?php if ($mode != 'my_profile') { ?>
          <hr>
          <div class="row py-7px">
            <div class="col-md-4">
              <div class="text-secondary font-Medium">การเข้าใช้งานระบบ</div>
            </div>
            <div class="col-md-8">
              <?php
              $text_show = $emp['is_can_login'] ? '<span class="text-primary ml-5px">เข้าใช้ได้</span>' : '<span class="text-secondary ml-5px">ปิดเข้าใช้งาน</span>';
              $check_can_login = $emp['is_can_login'] ? 'checked' : '';
              TiwForm::normal('checkbox', 1, ['name' => 'is_use', 'class' => 'event_change_txt_can_login', $check_can_login => 1, 'onchange' => 'eventCanLogin(this);'], ['style' => '1', 'label' => $text_show]);
              ?>
            </div>
          </div>
          <hr>
          <div class="row py-7px">
            <div class="col-md-4">
              <div class="text-secondary font-Medium">สิทธิการใช้งานระบบ</div>
            </div>
            <div class="col-md-8">
              <div class="d-flex flex-wrap justify-content-between">
                <div class="font-Medium"> <?= $emp['user_type'] ?></div>
                <button type="button" class="btn btn-link p-0" <?php Tiwdal::register('change_permission', $emp, ['is_ajax' => 1]); ?>>เปลี่ยนสิทธิการใช้</button>
              </div>
            </div>
          </div>
        <?php } ?>
      </div>
    </div>
  </div>
</div>