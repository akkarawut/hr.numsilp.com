<?php
$user_permission['_permission'] = ['all', 'ywptz_user', 'niemz_user', 'bmiwk_user', 'noaxt_user', 'xacpn_user'];
require_once 'wloves/_framework/import_for_website.php';
require_once 'layout/init.php';
W_Structure::loadModules(['artchart']);
$name_page = 'ภาพรวมธุรกิจ';

$dd_month = phr_helper::dropdownMonthCommission(API_CODE);

$f_date = isset($_GET['f_date']) ? $_GET['f_date'] : date('Y-m');
$where = [
  'date' => $f_date
];
$data = phr_business_overview::BusinessOverView(API_CODE, $where);

?>
<!-- MENU::ภาพรวมธุรกิจ -->
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

        <div class="font-18px font-Medium mb-10px">สถิติการเข้างานรายวัน : <span class="text-primary"><?= W_Main::formatDate($data['date_now'], 'd M Y', true, false) ?></span></div>
        <div class="row">
          <div class="col-lg-3 mb-20px">
            <div class="card border-radius-20px">
              <div class="card-body">
                <div class="text-center">
                  <div class="font-15px mb-10px">พนักงานที่ลงบันทึกเข้างาน</div>
                  <div class="d-flex align-items-end justify-content-center lh-1 mb-10px">
                    <div class="font-50px fw-semibold">
                      <?= number_format($data['count_checkin'], 0) ?>
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
          </div>
          <div class="col-lg-3 mb-20px">
            <div class="card border-radius-20px">
              <div class="card-body">
                <div class="text-center">
                  <div class="font-15px mb-10px">จำนวนครั้งที่พนักงานมาสาย</div>
                  <div class="d-flex align-items-end justify-content-center lh-1 mb-10px">
                    <div class="font-50px fw-semibold text-danger">
                      <?= number_format($data['count_late'], 0) ?>
                    </div>
                    <div class="font-14px fw-semibold">
                      ครั้ง
                    </div>
                  </div>
                  <div class="font-14px font-Medium">
                    จาก พนักงาน <?= number_format($data['count_late_user'], 0) ?> ราย
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
                      <?= number_format($data['count_very_late'], 0) ?>
                    </div>
                    <div class="font-14px fw-semibold">
                      ครั้ง
                    </div>
                  </div>
                  <div class="font-14px font-Medium">
                    จาก พนักงาน <?= number_format($data['count_very_late_user'], 0) ?> ราย
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
                      <?= number_format($data['count_missing'], 0) ?>
                    </div>
                    <div class="font-14px fw-semibold">
                      ครั้ง
                    </div>
                  </div>
                  <div class="font-14px font-Medium">
                    จาก พนักงาน <?= number_format($data['count_missing_user'], 0) ?> ราย
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="d-flex justify-content-between align-items-center flex-wrap ">
          <div class="font-18px font-Medium mb-20px">
            สถิติการยอดขายรวมประจำเดือน : <span class="text-primary"><?= W_Main::formatDate($f_date, 'M Y', true, false) ?></span>
          </div>
          <div>
            <form id="form_filter" method="get" class="mb-20px">
              <div class="hr-form-control min-w-200px">
                <div class="input-group">
                  <div class="input-title">ช่วงเดือน</div>
                  <select name="f_date" class="input " onchange="submitFormFIlter(this)">
                    <?php foreach ($dd_month as $val) { ?>
                      <option value="<?= $val['value'] ?>" <?= $f_date == $val['value'] ? 'selected' : '' ?>><?= $val['text'] ?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>
            </form>

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
                <div class="d-flex align-items-end res-center  lh-1 mb-10px">
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
                <div class="d-flex align-items-end res-center  lh-1 mb-10px">
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

        <div class="row mb-20px">
          <div class="col-12">
            <div class="card border-radius-20px">
              <div class="card-body">
                <div class="font-18px font-Medium">กราฟแสดงยอดขายรวมประจำเดือน</div>
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

        <div class="row mb-4">
          <div class="col-lg-12">
            <div class="font-18px font-Medium mb-20px">
              ตารางแสดงยอดขายของพนักงานประจำเดือน : <span class="text-primary"><?= W_Main::formatDate($f_date, 'M Y', true, false) ?></span>
            </div>
          </div>
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

    <?php
    W_Structure::loadFooterForWebsite();
    W_Main::loadAsset('assets/js/main.js');
    W_Main::loadAsset('assets/js/datatable_option.js');
    ?>

    <script>
      function submitFormFIlter() {
        document.getElementById('form_filter').submit();
      }

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
            d.date = `<?= $f_date ?>`;
            d.type = `ceo`;
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