<div class="dashboard-box mt-20px">
  <div class="row">
    <div class="col-lg-6">
      <?php if ($mode != 'probation') { ?>
        <div class="py-4 px-5">
          <div class="fw-semibold font-24px mb-3">Performance ประจำเดือน
            <span class="text-primary"><?= W_Main::formatDate($emp['date_performance'], 'M Y', true, false) ?></span>
          </div>
          <div class="text-secondary font-15px">คิดจากประวัติการทำงานที่บันทึกแล้ว</div>
        </div>
      <?php } else { ?>
        <img src="assets/images/performance_img.png" alt="" class="img-fluid pt-20px">
      <?php } ?>
    </div>
    <div class="col-lg-6">
      <div class="py-20px">
        <div class="card-performance">
          <div class="font-15px font-Medium text-secondary mb-10px">Performance : <span class="text-primary fw-semibold"><?= $emp['percent_performance'] ?>%</span></div>

          <div class="progress-bar">
            <div class="inner-progress-bar" style="width:<?= $emp['percent_performance'] ?>%;"></div>
          </div>
          <div class="grid-perfomance --md mt-20px">
            <div class="list-perfomance">
              <div class="number"><?= number_format($emp['count_day_work'], 0) ?></div>
              <div class="text">วันทำงาน</div>
            </div>
            <div class="list-perfomance">
              <div class="number <?= $emp['count_late'] > 0 ? 'text-danger' : '' ?>"><?= number_format($emp['count_late'], 0) ?></div>
              <div class="text">มาสาย :
                <span class="<?= $emp['count_late'] > 0 ? 'text-danger' : '' ?>">
                  <?= $emp['count_late_percent'] > 0 ? '-' . number_format($emp['count_late_percent'], 0) : 0 ?>%
                </span>
              </div>
            </div>
            <div class="list-perfomance">
              <div class="number <?= $emp['count_very_late'] > 0 ? 'text-danger' : '' ?>"><?= number_format($emp['count_very_late'], 0) ?></div>
              <div class="text">สายมาก :
                <span class="<?= $emp['count_very_late'] > 0 ? 'text-danger' : '' ?>">
                  <?= $emp['count_very_late_percent'] > 0 ? '-' . number_format($emp['count_very_late_percent'], 0) : 0 ?>%
                </span>
              </div>
            </div>
            <div class="list-perfomance">
              <div class="number <?= $emp['count_missing'] > 0 ? 'text-danger' : '' ?>"><?= number_format($emp['count_missing'], 0) ?></div>
              <div class="text">ขาด :
                <span class="<?= $emp['count_missing'] > 0 ? 'text-danger' : '' ?>">
                  <?= $emp['count_missing_percent'] > 0 ? '-' . number_format($emp['count_missing_percent'], 0) : 0 ?>%
                </span>
              </div>
            </div>
            <div class="list-perfomance">
              <div class="number <?= $emp['count_leave_work'] > 0 ? 'text-danger' : '' ?>"><?= number_format($emp['count_leave_work'], 0) ?></div>
              <div class="text">ลา</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>


<?php if ($mode != 'probation') { ?>
  <div class="container-fluid mt-5">
    <div class="row">
      <div class="col-md-12">
        <h1 class="title-header mb-10px">ประวัติ Performance</h1>
        <p class="sub-title text-secondary">
          ตัดรอบเดือนทุกครั้งที่เงินเดือนออก
        </p>
      </div>
    </div>

    <div class="row my-4">
      <div class="col-lg-12">
        <div class="card-box-tbl">
          <div class="table-responsive mt-10px">
            <table id="table_emp_performance" class="table">
              <thead>
                <tr>
                  <th>เดือน</th>
                  <th class="text-center">วันทำงาน (วัน)</th>
                  <th class="text-center">มาสาย (ครั้ง)</th>
                  <th class="text-center">สายมาก (ครั้ง)</th>
                  <th class="text-center">ขาดงาน (ครั้ง)</th>
                  <th class="text-center">ลา (ครั้ง)</th>
                  <th class="text-center">Performance </th>
                </tr>
              </thead>
              <tbody></tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-md-12">
        <h1 class="title-header mb-10px">รายการยอดขาย</h1>
      </div>
    </div>

    <div class="row my-4">
      <div class="col-lg-12">
        <div class="card-box-tbl">
          <div class="table-responsive mt-10px">
            <table id="table_report_sale_employee" class="table">
              <thead>
                <tr>
                  <th class="text-center">บันทึกยอดขายและคอมมิชชั่น</th>
                  <th class="text-center">ยอดขายรวม (บาท)</th>
                  <th class="text-center">คอมมิชชั่นรวม (บาท)</th>
                </tr>
              </thead>
              <tbody></tbody>
            </table>
          </div>
        </div>
      </div>
    </div>


    <div class="row">
      <div class="col-md-12">
        <h1 class="title-header mb-10px">เอกสารใบเตือน</h1>
        <p class="sub-title text-secondary">
          รายการใบเตือนสำหรับการกระทำผิดของพนักงาน
        </p>
      </div>
    </div>

    <div class="row my-4">
      <div class="col-lg-12">
        <div class="card-box-tbl">
          <div class="table-responsive mt-10px">
            <table id="table_issue_warning" class="table">
              <thead>
                <tr>
                  <th class="thin-cell">เลขที่เอกสาร</th>
                  <th class="thin-cell">ชื่อพนักงาน</th>
                  <th class="thin-cell">ครั้งที่</th>
                  <th>สาเหตุ</th>
                  <th>รายละเอียดเพิ่มเติม</th>
                  <th class="thin-cell"></th>
                </tr>
              </thead>
              <tbody></tbody>
            </table>
          </div>
        </div>
      </div>
    </div>



  </div>
<?php } ?>