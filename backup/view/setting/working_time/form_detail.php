<div class="container">
  <div class="max-w-750px mx-auto">
    <p class="sub-title my-25px">ตั้งค่าเวลาทำงานของพนักงาน กำหนดเงื่อนไขการเข้างานสาย สายมาก และขาดงาน</p>

    <div class="card rounded-20px">
      <div class="card-body py-25px px-0">

        <div class="px-30px">
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
              <div class="font-Medium"><?= $working_time['title'] ?></div>
            </div>
          </div>
          <hr>
          <div class="row py-7px">
            <div class="col-md-5">
              <div class="text-secondary font-Medium">รายละเอียดเพิ่มเติม</div>
            </div>
            <div class="col-md-7">
              <?= $working_time['description'] ?>
            </div>
          </div>
          <hr>

          <div class="row py-7px">
            <div class="col-12">
              <div class="font-15px fw-semibold mb-5px">วันทำงาน</div>
              <div>วันที่ต้องมาทำงาน พร้อมเวลาเข้า - ออกงาน</div>
            </div>
          </div>
        </div>

        <div class="table-responsive">
          <table id="table_working_time" class="table table-c-primary">
            <thead>
              <tr>
                <th nowrap>วันทำงาน</th>
                <th nowrap>เวลาควรเข้างาน - เวลาออกงาน</th>
                <th nowrap>เวลาทำงานรวม : วัน (ชั่วโมง)</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($arr_day as $day) { ?>
                <?php
                $class_row = 'filter-grey';
                $time = '';
                $total_time = '';
                $icon = file_get_contents('assets/icons/cancel.svg');
                if ($working_time[$day['check_box_name']]) {
                  $class_row = '';
                  $icon = file_get_contents('assets/icons/check-primary.svg');
                  $time =  W_Main::formatDate($working_time[$day['from_time']], 'H:i') . ' - ' . W_Main::formatDate($working_time[$day['to_time']], 'H:i')  . 'น.';
                  $total_time = $working_time[$day['hour']] . ' ชั่วโมง ' . $working_time[$day['minute']] . ' นาที';
                }
                ?>
                <tr class="scope_row_working <?= $class_row ?>" data-time="<?= $working_time[$day['from_time']] ?>" data-end_time="<?= $working_time[$day['to_time']] ?>">
                  <td nowrap>
                    <div class="d-flex align-items-center">
                      <div class="mr-15px">
                        <?= $icon  ?>
                      </div>
                      <div><?= $day['name'] ?></div>
                    </div>
                  </td>
                  <td nowrap>
                    <?= $time ?>
                  </td>
                  <td nowrap>
                    <?= $total_time ?>
                  </td>
                </tr>
              <?php } ?>
            </tbody>
            <tfoot>
              <tr class="bg-light-primary">
                <td colspan="3">
                  <span class="text-primary fw-semibold font-15px">
                    รวมเวลาทำงานทั้งสัปดาห์ : <?= $working_time['hour_total'] ?> ชั่วโมง <?= $working_time['minute_total'] ?> นาที
                  </span>
                </td>
              </tr>
            </tfoot>
          </table>
        </div>

        <div class="px-30px">
          <div class="row py-7px">
            <div class="col-12 mb-15px">
              <div class="font-15px fw-semibold">เงื่อนไขสำหรับการเข้างานสาย</div>
            </div>
          </div>
          <div class="row py-7px">
            <div class="col-12 mb-15px">
              <div class="font-15px fw-semibold">
                1. จะนับว่าพนักงานเข้างานสายเมื่อเกินเวลาเข้างาน :
                <?php if ($working_time['is_late_minute']) { ?>
                  <span class="text-primary fw-semibold">
                    มองข้ามเงื่อนไข
                  </span>
                <?php } else { ?>
                  <span class="text-primary fw-semibold">
                    <?= $working_time['late_minute'] ?> นาที
                  </span>
                <?php } ?>
              </div>
            </div>
          </div>
          <div class="row py-7px">
            <div class="col-12 mb-15px">
              <div class="font-15px fw-semibold">
                2. จะนับว่าพนักงานเข้างานสายมากเมื่อเกินเวลาเข้างาน :
                <?php if ($working_time['is_very_late_minute']) { ?>
                  <span class="text-primary fw-semibold">
                    มองข้ามเงื่อนไข
                  </span>
                <?php } else { ?>
                  <span class="text-primary fw-semibold">
                    <?= $working_time['very_late_minute'] ?> นาที
                  </span>
                <?php } ?>
              </div>
            </div>
          </div>
          <div class="row py-7px">
            <div class="col-12 mb-15px">
              <div class="font-15px fw-semibold">
                3. จะนับว่าพนักงานขาดงานเมื่อเกินเวลาเข้างาน :
                <?php if ($working_time['is_missing_minute']) { ?>
                  <span class="text-primary fw-semibold">
                    มองข้ามเงื่อนไข
                  </span>
                <?php } else { ?>
                  <span class="text-primary fw-semibold">
                    <?= $working_time['missing_minute'] ?> นาที
                  </span>
                <?php } ?>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="d-flex align-items-center mt-20px mb-20px">
      <div class="me-3"> <?= file_get_contents('assets/icons/history.svg') ?></div>
      <div class="text-info"> แก้ไขข้อมูลล่าสุด : <?= W_Main::formatDate($working_time['update_date_time'], 'd M Y', true, false); ?> โดย <?= $working_time['update_by_fullname'] ?></div>
    </div>
  </div>
</div>