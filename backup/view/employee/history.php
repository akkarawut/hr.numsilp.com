<?php
$txt_type = '';
if ($emp['employee_status'] == 'day') {
  $txt_type = 'ค่าจ้าง';
} else {
  $txt_type = 'เงินเดือน';
} ?>
<div class="container-fluid mt-5">
  <div class="row">
    <div class="col-md-12">
      <h1 class="title-header mb-10px">ประวัติการรับ<?= $txt_type ?></h1>
      <p class="sub-title text-secondary">
        รายการจ่าย<?= $txt_type ?>ที่ได้รับการอนุมัติแล้ว
      </p>
    </div>
  </div>

  <div class="row my-4">
    <div class="col-lg-12">
      <div class="card-box-tbl">
        <div class="table-responsive mt-10px">
          <table id="table_emp_salary" class="table">
            <thead>
              <tr>
                <th><?= $emp['employee_status'] == 'day' ? 'วันที่' : 'เดือน' ?></th>
                <th>วันที่อนุมัติ</th>
                <th>ธนาคาร</th>
                <th><?= $txt_type ?></th>
                <th>ค่าใช้จ่าย</th>
                <th>ยอดเงินที่ได้รับ</th>
                <th>หมายเลขอ้างอิง</th>
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

<div class="container-fluid mt-5">
  <div class="row">
    <div class="col-md-12">
      <h1 class="title-header mb-10px">ประวัติการรับค่าคอมมิชชั่น</h1>
      <p class="sub-title text-secondary">
        รายการค่าคอมมิชชั่นที่ได้รับการยืนยันแล้ว
      </p>
    </div>
  </div>

  <div class="row my-4">
    <div class="col-lg-12">
      <div class="card-box-tbl">
        <div class="table-responsive mt-10px">
          <table id="table_emp_commission" class="table">
            <thead>
              <tr>
                <th>เลขที่อ้างอิง</th>
                <th>วันที่ลงบันทึก</th>
                <th>บันทึกยอดขาย (บาท)</th>
                <th>คอมมิชชั่นที่ได้รับ (บาท)</th>
                <th>ภาษีหัก ณ ที่จ่าย</th>
                <th>ยอดที่ได้รับ (บาท)</th>
                <th>สถานะการตรวจสอบ</th>
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