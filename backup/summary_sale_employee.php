<?php
$user_permission['_permission'] = ['all', 'ywptz_user', 'niemz_user', 'bmiwk_user', 'noaxt_user', 'xacpn_user'];
require_once 'wloves/_framework/import_for_website.php';
require_once 'layout/init.php';
W_Structure::loadModules(['artchart']);
$name_page = 'สรุปยอดขายของพนักงาน';
$dropdown_role = phr_helper::dropdownWorkingTime(API_CODE);
$dropdown_date = phr_helper::dropdownMonthCheckIn(API_CODE);
$dropdown_emp = phr_helper::dropdownEmployee(API_CODE);
$filter = isset($_GET['filter']) ? $_GET['filter'] : '';
$from_date = isset($_GET['from_date']) ? $_GET['from_date'] : '';
$to_date = isset($_GET['to_date']) ? $_GET['to_date'] : '';
$user_id = isset($_GET['user_id']) ? $_GET['user_id'] : '';

$where = [
  'from_date' => $from_date,
  'to_date' => $to_date,
  'user_id' => $user_id,
];

$data = phr_employee_sale_report::ReportEmployeeSaleHead(API_CODE, $where);

$f_day = '';
if ($from_date && $to_date) {
  $date1 = date_create($from_date);
  $date2 = date_create($to_date);
  $day = date_diff($date1, $date2);
  $f_day = $day->days;
}


?>
<!-- MENU::สรุปยอดขายของพนักงาน -->
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
            <h1 class="title-header mb-10px">สรุปยอดขายของพนักงาน</h1>
            <p class="sub-title text-secondary">
              ภาพรวมและสถิติยอดขายของพนักงาน <span class="text-danger">*นับจากบันทึกยอดขายและคอมมิชชั่นที่ผ่านการตรวจสอบว่าถูกต้องแล้วเท่านั้น</span>
            </p>
            <div class="d-flex flex-wrap align-items-center mt-20px mb-5px">
              <?php if ($filter) { ?>
                <div class="text">ตัวกรองการแสดงผล :</div>
                <?php if ($from_date && $to_date) { ?>
                  <div class="chip mb-5px">วันที่ข้อมูล : <?= W_Main::formatDate($from_date, 'd/m/Y') . ' - ' . W_Main::formatDate($to_date, 'd/m/Y') ?></div>
                  <div class="chip mb-5px"> <?= $f_day + 1 ?> วัน</div>
                <?php } ?>
                <?php if ($user_id) { ?>
                  <?php foreach ($dropdown_emp as $val) { ?>
                    <?php if ($val['id'] == $user_id) {
                      $emp_text = '';
                    ?>
                      <div class="chip mb-5px">พนักงาน : <?= $val['full_name'] ?> </div>
                    <?php } ?>
                  <?php } ?>
                <?php } else { ?>
                  <div class="chip mb-5px">พนักงาน : ทั้งหมด </div>
                <?php  } ?>
              <?php } ?>
            </div>
          </div>
          <div class=" mb-4">
            <div class=" dropdown-c">
              <button class="dropdown-btn btn-icon dropdown-toggle" type="button" id="dropdown_filter" data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="outside" aria-expanded="false">
                <?php if ($filter) { ?>
                  <div class="count">3</div>
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
                        <div class="input-title">ตั้งแต่วันที่</div>
                        <input type="date" name="from_date" id="" value="<?= $from_date ?>" class="input">
                      </div>
                    </div>
                    <div class="hr-form-control mb-20px">
                      <div class="input-group">
                        <div class="input-title">ถึงวันที่</div>
                        <input type="date" name="to_date" id="" value="<?= $to_date ?>" class="input">
                      </div>
                    </div>
                    <div class="font-15px fw-semibold mb-15px">เฉพาะพนักงาน</div>
                    <div class="hr-form-control mb-20px">
                      <div class="input-group">
                        <div class="input-title">พนักงาน</div>
                        <select name="user_id" class="input">
                          <option value="" <?= $user_id == '' ? 'selected' : '' ?>>ทั้งหมด</option>
                          <?php foreach ($dropdown_emp as $val) { ?>
                            <option value="<?= $val['id'] ?>" <?= $user_id == $val['id'] ? 'selected' : '' ?>><?= $val['full_name'] ?></option>
                          <?php } ?>
                        </select>
                      </div>
                    </div>
                  </div>
                  <hr>
                  <div class="d-flex justify-content-end px-10px py-10px">
                    <a href="summary_sale_employee.php" class="btn btn-default border-radius-15px min-w-100px mr-5px">ล้าง</a>
                    <button type="submit" name="submit_filter" class="btn btn-primary border-radius-15px min-w-130px">ค้นหา</button>
                  </div>
                </form>
              </ul>
            </div>
          </div>
        </div>

        <div class="row mb-20px">
          <div class="col-12">
            <div class="card border-radius-20px">
              <div class="card-body">
                <div class="font-18px font-Medium">
                  กราฟแสดงยอดขายรวมรายวัน (ตามช่วงเวลาที่เลือก)
                </div>
                <div class="i-chart-ex">
                  <?php
                  $options = [
                    'layout'       => 'vertical',
                    'scrollbar'    => true,
                    'unit'         => 'บาท',
                    'random_color' => false,
                    'labels'       => [
                      // 'layout' => 'vertical',
                      'labelX' => '',
                      'labelY' => 'ยอดขาย',
                    ],
                    'legend'       => [
                      'is_show' => false,
                      'is_show_value' => false
                    ],
                  ];
                  ArtChart::stack($data['chart_month_sale']['list'], $data['chart_month_sale']['key_values'], $options);
                  ?>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="grid-summary-sale mb-20px">
          <div class="card border-radius-20px">
            <div class="card-body">
              <div class="text-center">
                <div class="font-15px mb-10px">พนักงานที่ถูกบันทึกยอดขาย</div>
                <div class="d-flex align-items-end justify-content-center lh-1 mb-10px">
                  <div class="font-50px fw-semibold">
                    <?= number_format($data['count_employee_sale'], 0) ?>
                  </div>
                  <div class="font-14px fw-semibold">
                    ราย
                  </div>
                </div>
                <div class="font-14px font-Medium">
                  จาก พนักงานทั้งหมด <?= number_format($data['count_user'], 0) ?> ราย
                </div>
              </div>
            </div>
          </div>
          <div class="card border-radius-20px">
            <div class="card-body">
              <div class="">
                <div class="font-15px mb-10px">ยอดขายรวมตามช่วงเวลาที่เลือก</div>
                <div class="d-flex align-items-end res-center lh-1 mb-10px">
                  <div class="font-50px fw-semibold text-blue">
                    <?= number_format($data['total_employee_sale'], 2) ?>
                  </div>
                  <div class="font-14px fw-semibold">
                    บาท
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="card border-radius-20px">
            <div class="card-body">
              <div class="">
                <div class="font-15px mb-10px">ค่าคอมมิชชั่นตามช่วงเวลาที่เลือก</div>
                <div class="d-flex align-items-end res-center lh-1 mb-10px">
                  <div class="font-50px fw-semibold text-pink">
                    <?= number_format($data['total_employee_commission'], 2) ?>
                  </div>
                  <div class="font-14px fw-semibold">
                    บาท
                  </div>
                </div>
                <div class="font-14px font-Medium text-danger">
                  *นับเฉพาะคอมมิชชั่นหลังหักภาษี ณ ที่จ่ายเท่านั้น
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="row mb-4">
          <div class="col-lg-12">
            <div class="card-box-tbl">
              <div class="table-responsive mt-10px">
                <table id="table_report_sale_employee" class="table">
                  <thead>
                    <tr>
                      <th>พนักงาน</th>
                      <th class="text-center">บันทึกยอดขายและคอมมิชชั่น</th>
                      <th class="text-end">ยอดขายรวม (บาท)</th>
                      <th class="text-end">คอมมิชชั่นรวม (บาท)</th>
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
    var table_report_sale_employee = new DataTable(document.getElementById('table_report_sale_employee'), {
      ajax: {
        url: 'ajax/ajax_table_report_sale_employee.php',
        type: 'POST',
        data: function(d) {
          let info = table_report_sale_employee ?? '';
          let page_no = info ? info.page.info().page + 1 : 1;
          let page_size = info ? info.page.len() : 10;
          d.page_no = page_no;
          d.page_size = page_size;
          d.from_date = `<?= $from_date ?>`;
          d.to_date = `<?= $to_date ?>`;
          d.user_id = `<?= $user_id ?>`;
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
          data: 'count_sale',
          className: 'nowrap text-center',
          render: function(data, type, row, meta) {
            return '<div class="nowrap">' + w.formatNumber(data, 0) + '</div>'
          }
        },
        {
          data: 'total_sale',
          className: 'nowrap text-end',
          render: function(data, type, row, meta) {
            return '<div class="nowrap">' + w.formatNumber(data, 2) + '</div>'
          }
        },
        {
          data: 'total_commission',
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