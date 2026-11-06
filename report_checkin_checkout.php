<?php
$user_permission['_permission'] = ['all', 'ywptz_user', 'niemz_user', 'bmiwk_user', 'noaxt_user', 'xacpn_user'];
require_once 'wloves/_framework/import_for_website.php';
require_once 'layout/init.php';
$name_page = 'รายงานการเข้า - ออกงาน';


$dropdown_role = phr_helper::dropdownWorkingTime(API_CODE);
$dropdown_date = phr_helper::dropdownMonthCheckIn(API_CODE);
$role_ids = isset($_GET['role_ids']) ? $_GET['role_ids'] : '';
// $f_date = isset($_GET['f_date']) ? $_GET['f_date'] : $dropdown_date[0]['value'];
$f_date = isset($_GET['f_date']) ? $_GET['f_date'] : '';
$filter = isset($_GET['filter']) ? $_GET['filter'] : '';
$where = [
  'date' => $f_date,
  'role_ids' => $role_ids
];

$count = phr_checkin::reportCheckInHead(API_CODE, $where);



?>
<!-- MENU::ตั้งค่า_รายงานการเข้า - ออกงาน -->
<!DOCTYPE html>
<html lang="en">

<head>
  <?php
  $og_data = [
    'url'         => '',
    'title'       => 'HR',
    'image'       => '',
    'description' => '',
  ];
  W_Structure::loadMetaForWebsite('', $og_data);
  W_Main::loadAsset('wloves/_framework/library/datatable/datatables.css');
  W_Main::loadAsset('wloves/_framework/library/datatable/datatables.js');
  W_Main::loadAsset('assets/css/main.css');
  ?>
  <?php include_once 'layout/favicon.php' ?>
</head>

<body>
  <?php include_once 'layout/navbar.php' ?>
  <div id="hr-body">
    <?php include_once 'layout/sidebar.php' ?>
    <div id="hr-content" class="bg-white">
      <div class="container-fluid mt-5">
        <div class="d-flex justify-content-between">
          <div class="mb-4">
            <h1 class="title-header mb-10px">รายงานการเข้า - ออกงาน ของพนักงาน</h1>
            <p class="sub-title text-secondary">ภาพรวมและสถิติการเข้า - ออกงานของพนักงานที่เข้าใช้งานระบบ</p>
            <div class="d-flex flex-wrap align-items-center mt-20px mb-5px">
              <?php if ($filter) { ?>
                <div class="text">ตัวกรองการแสดงผล :</div>
                <?php if ($f_date) { ?>
                  <div class="chip mb-5px">เดือน : <?= $count['month_name'] ? $count['month_name'] : $dropdown_date[0]['text_month']; ?></div>
                  <div class="chip mb-5px"> <?= $count['month_day_amount'] ? $count['month_day_amount'] : $dropdown_date[0]['month_day_amount']; ?> วัน</div>
                <?php } ?>
                <?php if ($role_ids) { ?>
                  <?php foreach ($dropdown_role as $val) { ?>
                    <?php if (in_array($val['id'], $role_ids)) {
                      $emp_text = '';
                    ?>
                      <div class="chip mb-5px">พนักงาน :
                        <?php
                        $emp_text = $val['title'];
                        echo $emp_text;
                        ?>
                      </div>
                    <?php } ?>
                  <?php } ?>
                <?php } ?>
              <?php } ?>
            </div>
          </div>
          <div class=" mb-4">
            <div class=" dropdown-c">
              <button class="dropdown-btn btn-icon dropdown-toggle" type="button" id="dropdown_filter" data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="outside" aria-expanded="false">
                <?php if ($role_ids && count($role_ids) > 0) { ?>
                  <div class="count"><?= count($role_ids) ?></div>
                <?php } ?>
                <span class="icon">
                  <?= file_get_contents('assets/icons/adjust.svg') ?>
                </span>
              </button>
              <ul class="dropdown-menu dropdown-menu-end max-w-300px w-100" aria-labelledby="dropdown_filter">
                <form method="get">
                  <input type="hidden" name="filter" value="true">
                  <div class="px-10px py-10px">
                    <div class="font-15px fw-semibold mb-15px">ช่วงเวลา</div>
                    <div class="hr-form-control mb-20px">
                      <div class="input-group">
                        <div class="input-title">เดือน</div>
                        <select name="f_date" class="input">
                          <option value="" <?= $f_date == '' ? 'selected' : '' ?>>ทั้งหมด</option>
                          <?php foreach ($dropdown_date as $val) { ?>
                            <option value="<?= $val['value'] ?>" <?= $f_date == $val['value'] ? 'selected' : '' ?>><?= $val['text'] ?></option>
                          <?php } ?>
                        </select>
                      </div>
                    </div>

                    <div class="font-15px mb-10px fw-semibold">รูปแบบการทำงานของพนักงาน</div>
                    <?php foreach ($dropdown_role as $val) { ?>
                      <?php
                      $checked = '';
                      if ($role_ids && in_array($val['id'], $role_ids)) {
                        $checked = 'checked';
                      }
                      ?>
                      <div class="mb-15px">
                        <?php
                        TiwForm::normal('checkbox', $val['id'], ['name' => 'role_ids[]', 'class' => '', $checked => 1], ['style' => '3', 'label' => $val['title']]);
                        ?>
                      </div>
                    <?php } ?>
                  </div>
                  <hr>
                  <div class="d-flex justify-content-end px-10px py-10px">
                    <a href="report_checkin_checkout.php" class="btn btn-default border-radius-15px min-w-100px mr-5px">ล้าง</a>
                    <button type="submit" name="submit_filter" class="btn btn-primary border-radius-15px min-w-130px">ค้นหา</button>
                  </div>
                </form>

              </ul>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-lg-3 mb-20px">
            <div class="card border-radius-20px">
              <div class="card-body">
                <div class="text-center">
                  <div class="font-15px mb-10px">พนักงานที่ลงบันทึกเข้างาน</div>
                  <div class="d-flex align-items-end justify-content-center lh-1 mb-10px">
                    <div class="font-50px fw-semibold">

                      <?= number_format($count['count_employee_checkin'], 0) ?>
                    </div>
                    <div class="font-14px fw-semibold">
                      ราย
                    </div>
                  </div>
                  <div class="font-14px font-Medium">
                    จาก พนักงานทั้งหมด <?= number_format($count['count_employee_all'], 0) ?> ราย
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-lg-3 mb-20px">
            <div class="card border-radius-20px">
              <div class="card-body">
                <div class="text-center">
                  <div class="font-15px mb-10px">จำนวนครั้งที่พนักงานมาสาย</div>
                  <div class="d-flex align-items-end justify-content-center lh-1 mb-10px">
                    <div class="font-50px fw-semibold text-danger">
                      <?= number_format($count['count_late'], 0) ?>
                    </div>
                    <div class="font-14px fw-semibold">
                      ครั้ง
                    </div>
                  </div>
                  <div class="font-14px font-Medium">
                    จาก พนักงาน <?= number_format($count['count_employee_late'], 0) ?> ราย
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-lg-3 mb-20px">
            <div class="card border-radius-20px">
              <div class="card-body">
                <div class="text-center">
                  <div class="font-15px mb-10px">จำนวนครั้งที่พนักงานมาสายมาก</div>
                  <div class="d-flex align-items-end justify-content-center lh-1 mb-10px">
                    <div class="font-50px fw-semibold text-danger">
                      <?= number_format($count['count_very_late'], 0) ?>
                    </div>
                    <div class="font-14px fw-semibold">
                      ครั้ง
                    </div>
                  </div>
                  <div class="font-14px font-Medium">
                    จาก พนักงาน <?= number_format($count['count_employee_very_late'], 0) ?> ราย
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-lg-3 mb-20px">
            <div class="card border-radius-20px">
              <div class="card-body">
                <div class="text-center">
                  <div class="font-15px mb-10px">จำนวนครั้งที่พนักงานขาดงาน</div>
                  <div class="d-flex align-items-end justify-content-center lh-1 mb-10px">
                    <div class="font-50px fw-semibold text-danger">
                      <?= number_format($count['count_missing'], 0) ?>
                    </div>
                    <div class="font-14px fw-semibold">
                      ครั้ง
                    </div>
                  </div>
                  <div class="font-14px font-Medium">
                    จาก พนักงาน <?= number_format($count['count_employee_missing'], 0) ?> ราย
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="row mb-4">
          <div class="col-lg-12">
            <div class="card-box-tbl">
              <div class="table-responsive mt-10px">
                <table id="table_report_checkin_checkout" class="table">
                  <thead>
                    <tr>
                      <th>พนักงาน</th>
                      <th class="text-center">วันทำงาน</th>
                      <th class="text-center">ขาด (ครั้ง)</th>
                      <th class="text-center">มาสาย (ครั้ง)</th>
                      <th class="text-center">สายมาก (ครั้ง)</th>
                      <th class="text-center">เวลาสายรวม (นาที)</th>
                      <th class="text-center">จำนวนครั้งการโดนหักเงิน</th>
                      <th class="text-end">ยอดหักเงินรวม (บาท)</th>
                    </tr>
                  </thead>
                  <tbody></tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <?php
  W_Structure::loadFooterForWebsite();
  W_Main::loadAsset('assets/js/main.js');
  W_Main::loadAsset('assets/js/datatable_option.js');
  ?>

  <script>
    var table_report_checkin_checkout = new DataTable(document.getElementById('table_report_checkin_checkout'), {
      ajax: {
        url: 'ajax/ajax_table_report_checkin_checkout.php',
        type: 'POST',
        data: function(d) {
          let info = table_report_checkin_checkout ?? '';
          let page_no = info ? info.page.info().page + 1 : 1;
          let page_size = info ? info.page.len() : 10;
          d.page_no = page_no;
          d.page_size = page_size;
          d.role_ids = `<?= $role_ids ? json_encode($role_ids) : '' ?>`;
          d.f_date = `<?= $f_date ?>`;
        }
      },
      columns: [{
          data: 'full_name',
          className: 'nowrap',
          render: function(data, type, row, meta) {
            return '<div class="nowrap">' + data + '</div>'
          }
        },
        {
          data: 'count_normal',
          className: 'nowrap text-center',
          render: function(data, type, row, meta) {
            return '<div class="nowrap">' + data + '</div>'
          }
        },
        {
          data: 'count_missing',
          className: 'nowrap text-center',
          render: function(data, type, row, meta) {
            return '<div class="nowrap">' + data + '</div>'
          }
        },
        {
          data: 'count_late',
          className: 'nowrap text-center',
          render: function(data, type, row, meta) {
            return '<div class="nowrap">' + data + '</div>'
          }
        },
        {
          data: 'count_very_late',
          className: 'nowrap text-center',
          render: function(data, type, row, meta) {
            return '<div class="nowrap">' + data + '</div>'
          }
        },
        {
          data: 'sum_late_minute',
          className: 'nowrap text-center',
          render: function(data, type, row, meta) {
            return '<div class="nowrap">' + data + ' นาที</div>'
          }
        },
        {
          data: 'deduct_amount',
          className: 'nowrap text-center',
          render: function(data, type, row, meta) {
            return '<div class="nowrap">' + data + '</div>'
          }
        },
        {
          data: 'deduct_price',
          className: 'nowrap text-end',
          render: function(data, type, row, meta) {
            return '<div class="nowrap">' + w.formatNumber(data, 2) + '</div>'
          }
        },
      ],
    });
  </script>
</body>

</html>