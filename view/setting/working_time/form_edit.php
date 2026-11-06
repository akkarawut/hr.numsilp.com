<form method="post">
  <div class="container">
    <div class="max-w-750px mx-auto">
      <p class="sub-title my-25px">ตั้งค่าเวลาทำงานของพนักงาน กำหนดเงื่อนไขการเข้างานสาย สายมาก และขาดงาน</p>
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
                  <div class="input-title required">ชื่อรูปแบบ</div>
                  <input type="text" class="input" name="title" required value="<?= $working_time['title'] ?>">
                </div>
              </div>
            </div>

            <div class="col-lg-12 mb-15px">
              <div class="hr-form-control">
                <div class="input-group">
                  <div class="input-title">รายละเอียดเพิ่มเติม</div>
                  <textarea name="description" class="input" value="<?= $working_time['description'] ?>"><?= $working_time['description'] ?></textarea>
                </div>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-lg-12 mb-15px">
              <div class="font-15px fw-semibold mb-5px">กำหนดวันทำงาน</div>
              <div> เลือกวันที่ต้องมาทำงาน พร้อมกำหนดเวลาเข้า - ออกงาน </div>
            </div>
          </div>

          <div class="table-responsive">
            <table id="table_working_time" class="table">
              <thead>
                <tr>
                  <th nowrap class="text-secondary font-Medium font-15px">วันทำงาน</th>
                  <th nowrap class="text-secondary font-Medium font-15px">เวลาควรเข้างาน</th>
                  <th nowrap class="text-secondary font-Medium font-15px">เวลาออกงาน</th>
                  <th nowrap class="text-secondary font-Medium font-15px">เวลาทำงานรวม : วัน (ชั่วโมง)</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($arr_day as $day) { ?>
                  <?php
                  $set_attr = '';
                  if ($working_time[$day['check_box_name']] == 1) {
                    $set_attr = 'required';
                  } else {
                    $set_attr = 'readonly';
                  }
                  ?>
                  <tr class="scope_row_working" data-time="<?= $working_time[$day['from_time']] ?>" data-end_time="<?= $working_time[$day['to_time']] ?>">
                    <td nowrap>
                      <div class="d-flex align-items-center">
                        <div class="mr-15px">
                          <?= TiwForm::normal('checkbox', 1, ['name' => $day['check_box_name'], 'class' => '', 'checked' => $working_time[$day['check_box_name']] == 1 ? 1 : 0, 'class' => 'event_checkbox_day', 'onchange' => 'eventUseDay(this);'], ['style' => '3']); ?>
                        </div>
                        <div><?= $day['name'] ?></div>
                      </div>
                    </td>
                    <td nowrap class="box-disabled">
                      <div class="hr-form-control">
                        <div class="input-group">
                          <input type="time" class="input" value="<?= $working_time[$day['from_time']] ?>" name="<?= $day['from_time'] ?>" onchange="changeTime(this,'start_time');" <?= $set_attr ?>>
                        </div>
                      </div>
                    </td>
                    <td nowrap class="box-disabled">
                      <div class="hr-form-control">
                        <div class="input-group">
                          <input type="time" class="input" value="<?= $working_time[$day['to_time']] ?>" name="<?= $day['to_time'] ?>" onchange="changeTime(this,'end_time');" <?= $set_attr ?>>
                        </div>
                      </div>
                    </td>
                    <td nowrap>
                      <?php if ($working_time[$day['check_box_name']] == 1) { ?>
                        <span class="cal_time">
                          <span class="txt_hrs">
                            <?= $working_time[$day['hour']] ?>
                          </span> ชั่วโมง
                          <span class="txt_min ml-5px"><?= $working_time[$day['minute']] ?></span> นาที
                        </span>
                      <?php } else { ?>
                        <span class="cal_time">
                          <span class="text-primary">ไม่นับเวลาทำงาน</span>
                        </span>
                      <?php } ?>
                    </td>
                  </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>

          <div class="row ">
            <div class="col-12">
              <div class="text-primary font-15px fw-semibold mb-20px">รวมเวลาทำงานทั้งสัปดาห์ :
                <span class="sum_hrs">
                  <?= $working_time['hour_total'] ?>
                </span>
                ชั่วโมง
                <span class="sum_min">
                  <?= $working_time['minute_total'] ?>
                </span>
                นาที
              </div>
            </div>

            <div class="col-lg-12 mb-15px">
              <div class="font-15px fw-semibold">กำหนดเงื่อนไขสำหรับการเข้างานสาย</div>
            </div>
          </div>

          <div class="row align-items-center mb-15px">
            <div class="mb-5px col-sm-7">
              1. จะนับว่าพนักงานเข้างานสายเมื่อเกินเวลาเข้างาน
              <span class="text-danger">*</span>
            </div>
            <div class="mb-5px col-sm-2 col-12">
              <div class="hr-form-control pt-0">
                <div class="input-group">
                  <input type="number" class="input event_input" name="late_minute" value="<?= $working_time['late_minute'] ?>" <?= $working_time['is_late_minute'] == 1 ?  '' : 'required' ?>>
                  <div class="input-title-abs">นาที</div>
                </div>
              </div>
            </div>
            <div class="mb-5px col-sm-auto col-12">
              <?= TiwForm::normal('checkbox', 1, ['name' => 'is_late_minute', 'class' => 'event_checkbox_condtion', 'checked' => $working_time['is_late_minute'] == 1 ?  1 : 0], ['style' => '3', 'label' => 'มองข้ามเงื่อนไข']); ?>
            </div>
          </div>
          <div class="scope_condition row align-items-center mb-15px">
            <div class="mb-5px col-sm-7">
              2. จะนับว่าพนักงานเข้างานสายมากเมื่อเกินเวลาเข้างาน
              <span class="text-danger">*</span>
            </div>
            <div class="mb-5px col-sm-2 col-12">
              <div class="hr-form-control pt-0">
                <div class="input-group">
                  <input type="number" class="input event_input" name="very_late_minute" value="<?= $working_time['very_late_minute'] ?>" <?= $working_time['is_very_late_minute'] == 1 ?  '' : 'required' ?>>
                  <div class="input-title-abs">นาที</div>
                </div>
              </div>
            </div>
            <div class="mb-5px col-sm-auto col-12">
              <?= TiwForm::normal('checkbox', 1, ['name' => 'is_very_late_minute', 'class' => 'event_checkbox_condtion', 'checked' => $working_time['is_very_late_minute'] == 1 ?  1 : 0], ['style' => '3', 'label' => 'มองข้ามเงื่อนไข']); ?>
            </div>
          </div>
          <div class="scope_condition row align-items-center mb-15px">
            <div class="mb-5px col-sm-7">
              3. จะนับว่าพนักงานขาดงานเมื่อเกินเวลาเข้างาน
              <span class="text-danger">*</span>
            </div>
            <div class="mb-5px col-sm-2 col-12">
              <div class="hr-form-control pt-0">
                <div class="input-group">
                  <input type="number" class="input event_input" name="missing_minute" value="<?= $working_time['missing_minute'] ?>" <?= $working_time['is_missing_minute'] == 1 ?  '' : 'required' ?>>
                  <div class="input-title-abs">นาที</div>
                </div>
              </div>
            </div>
            <div class="mb-5px col-sm-auto col-12">
              <?= TiwForm::normal('checkbox', 1, ['name' => 'is_missing_minute', 'class' => 'event_checkbox_condtion', 'checked' => $working_time['is_missing_minute'] == 1 ?  1 : 0], ['style' => '3', 'label' => 'มองข้ามเงื่อนไข']); ?>
            </div>
          </div>

          <div class="scope_condition row mt-20px">
            <div class="col-lg-12">
              <div class="d-flex justify-content-end">
                <a href="working_time_detail.php?id=<?= $id ?>" class="btn btn-default btn-lg rounded-15px max-w-150px w-100 me-2">
                  <div class="fw-semibold">ยกเลิก</div>
                </a>
                <button type="submit" name="submit_update_working_time" class="btn btn-primary btn-lg rounded-15px max-w-150px w-100">
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