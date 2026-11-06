<?php
$from_date = isset($_GET['from_date']) ? $_GET['from_date'] : '';
$to_date = isset($_GET['to_date']) ? $_GET['to_date'] : '';
$first_date = isset($_GET['first_date']) ? $_GET['first_date'] : '';

if (empty($from_date) || empty($to_date)) {
  $today = date('Y-m-d');
  $first_date = date('Y-m-01');
  $last_day_of_month = date('Y-m-t');
  $from_date = $today;
  $to_date = $last_day_of_month;
}
?>
<div class="content-header-container">
  <h3 class="header-title text-info">การเซ็ตวันเดือนปีใน Input Date</h3>
</div>
<div class="content-body-container">
  <p class="text-info mt-3 mb-1">
    วิธีที่ 1 ทำ Javascript
  <div class="row">
    <div class="col-4">
      <?= TiwForm::normal('date', $first_date, ['name' => 'date_end'], []); ?>
    </div>
    <div class="col-4">
      <?= TiwForm::normal('date', $today, ['name' => 'date_start'], []); ?>
    </div>
    <div class="col-4">
      <?= TiwForm::normal('date', $last_day_of_month, ['name' => 'date_end'], []); ?>
    </div>
  </div>
  </p>
  <pre>
  <code data-language="php">
    Example Code

    // เลือกวันปัจจุบัน
    $today = date('Y-m-d');

    // เลือกวันแรกของเดือนปัจจุบัน 
    $first_day_of_month = date('Y-m-01'); 
    หมายเหตุ : ตรง 01 เราสามารถเปลี่ยนเพื่อเลือกเฉพาะวันนั้นๆของเดือนได้

    // เลือกวันสุดท้ายของเดือนปัจจุบัน
    $last_day_of_month = date('Y-m-t');
  </code>
  </pre>

  <p class="text-info mt-3 mb-1">
    การเอาไปประกาศใช้เบื้องต้น
  </p>
  <pre>
  <code data-language="php">
  // ตัวอย่างแรกใช้ If Else
  if (empty($วันปัจจุบัน) || empty($วันสุดท้ายของเดือน)) {
    $today = date('Y-m-01');
    $last_day_of_month = date('Y-m-t');
    $from_date = $today;
    $to_date = $last_day_of_month;
  }

  // ตัวอย่างกรณีใช้ Short If
  $วันแรกของเดือน = isset($_GET['first_date']) ? $_GET['first_date'] : date('Y-m-01');
  $วันปัจจุบัน = isset($_GET['from_date']) ? $_GET['from_date'] : date('Y-m-d');
  $วันสุดท้ายของเดือน = isset($_GET['last_date']) ? $_GET['last_date'] : date('Y-m-t');
  </code>
  </pre>

</div>