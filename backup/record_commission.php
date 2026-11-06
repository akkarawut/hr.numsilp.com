<?php
$user_permission['_permission'] = ['all', 'ywptz_user', 'niemz_user', 'bmiwk_user', 'noaxt_user', 'xacpn_user'];
require_once 'wloves/_framework/import_for_website.php';
require_once 'layout/init.php';
require_once 'component/navtab.php';
$name_page = 'ลงบันทึกยอดขายและค่าคอมมิชชั่น';
$page = isset($_GET['page']) ? $_GET['page'] : 'waiting_confirm';
$_GET['page'] = $page;

$count =  phr_commission::countControlCommission(API_CODE);
$data_nav = [
  'param_name' => 'page',
  'nav_list' => [
    [
      'id' => 'waiting_confirm',
      'name' => 'รอตรวจสอบความถูกต้อง',
      'count' => [
        'number' =>   number_format($count['count_waiting_confirm'], 0),
        'class' => ''
      ]
    ],
    [
      'id' => 'history',
      'name' => 'ประวัติการดำเนินการ',
      'count' => [
        'number' =>  number_format($count['count_confirm'], 0),
        'class' => ''
      ]
    ],
  ]
];

$page_size = isset($_GET['page_size']) ? $_GET['page_size'] : 10;
$month_filter = isset($_GET['month_filter']) ? $_GET['month_filter'] : '';
$month_dropdown = phr_helper::dropdownMonthCommission(API_CODE);
$ops_pagesize = [
  [
    'name' => 'แสดงผล 10 รายการต่อหน้า',
    'value' => 10,
  ],
  [
    'name' => 'แสดงผล 25 รายการต่อหน้า',
    'value' => 25,
  ],
  [
    'name' => 'แสดงผล 50 รายการต่อหน้า',
    'value' => 50,
  ],
  [
    'name' => 'ทั้งหมด',
    'value' => -1,
  ],
];
?>
<!-- MENU::ลงบันทึกยอดขายและค่าคอมมิชชั่น -->
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

        <div class="row">
          <div class="col-md-9 mb-4">
            <h1 class="title-header mb-10px">ลงบันทึกยอดขายและค่าคอมมิชชั่น</h1>
            <p class="sub-title text-secondary">ลงบันทึกค่าคอมมิชชั่นของพนักงาน เพื่อนำไปคำนวณรายได้</p>
          </div>
          <div class="col-md-3 mb-4">
            <a href="record_commission_add.php" class="btn btn-primary border-radius-15px btn-res-150 ms-auto">ลงบันทึกคอมมิชชั่น</a>
          </div>
        </div>

        <?= navTab::normal($data_nav, ' ', 'mb-4'); ?>

        <div class="row my-4">
          <div class="col-lg-12">
            <div class="card-box-tbl">

              <?php
              if (isset($_GET['page'])) {
                if ($_GET['page'] == 'history') {
              ?>
                  <form id="scope_form_filter" method="get">
                    <input type="hidden" name="page" value="history">
                    <div class="form-filter-tbl">
                      <div class="row">
                        <div class="col-lg-3">
                          <div class="hr-form-control">
                            <div class="input-group">
                              <select name="month_filter" class="input event_change_filter">
                                <option value="" <?= $month_filter == '' ? 'selected' : '' ?>>ทั้งหมด</option>
                                <?php foreach ($month_dropdown as $val) { ?>
                                  <option value="<?= $val['value'] ?>" <?= $month_filter == $val['value'] ? 'selected' : '' ?>> <?= $val['text'] ?> </option>
                                <?php } ?>
                              </select>
                            </div>
                          </div>
                        </div>
                        <div class="col-lg-3">
                          <div class="hr-form-control">
                            <div class="input-group">
                              <select name="page_size" class="input event_change_filter filter_page_size">
                                <?php foreach ($ops_pagesize as $val) { ?>
                                  <option value="<?= $val['value'] ?>" <?= $page_size == $val['value'] ? 'selected' : '' ?>>
                                    <?= $val['name'] ?>
                                  </option>
                                <?php } ?>
                              </select>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </form>
              <?php
                }
              }
              ?>

              <div class="table-responsive mt-10px">
                <table id="table_record_commission" class="table">
                  <thead>
                    <tr>
                      <th>เลขที่อ้างอิง</th>
                      <th>พนักงาน</th>
                      <th>วันที่ลงบันทึก</th>
                      <th>บันทึกยอดขาย (บาท)</th>
                      <th>คอมมิชชั่นที่ควรได้รับ (บาท)</th>
                      <th>หมายเหตุ</th>
                      <?php if ($page == 'history') { ?>
                        <th>สถานะการตรวจสอบ</th>
                      <?php } ?>
                      <th class="thin-cell"></th>
                    </tr>
                  </thead>
                  <tbody>
                  </tbody>
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
  <?php require_once 'layout/footer_js.php'; ?>
  <script>
    w(document).on('change', '.event_change_filter', function() {
      w('#scope_form_filter').submit();
    });

    var table_record_commission = new DataTable(document.getElementById('table_record_commission'), {
      ajax: {
        url: 'ajax/ajax_table_record_commission.php',
        type: 'POST',
        data: function(d) {
          let info = table_record_commission ?? '';
          let page_no = info ? info.page.info().page + 1 : 1;
          let page_size = info ? info.page.len() : 10;
          d.page_no = page_no;
          d.page_size = page_size;
          d.status = `<?= $page ?>`;
          d.month_filter = `<?= $month_filter  ?>`;
        }
      },
      columns: [{
          data: 'no',
          className: 'nowrap',
          render: function(data, type, row, meta) {
            return '<div class="nowrap">' + data + '</div>'
          }
        },
        {
          data: 'user_fullname',
          className: 'nowrap',
          render: function(data, type, row, meta) {
            return '<div class="nowrap">' + data + '</div>'
          }
        },
        {
          data: 'insert_date_time',
          className: 'nowrap',
          render: function(data, type, row, meta) {
            let html = ``;
            html = `
              <div class="nowrap">
               <div>${moment(data).format('LL, H:m')}</div>
              <div>
              <div class="sub-text-tbl">
               โดย ${row.insert_user_fullname}
              </div>
              </div>`
            return html;
          }
        },
        {
          data: 'sale_price',
          className: 'nowrap',
          render: function(data, type, row, meta) {
            return '<div class="nowrap">' + w.formatNumber(data, 2) + ' บาท</div>'
          }
        },
        {
          data: 'commission_price',
          className: 'nowrap',
          render: function(data, type, row, meta) {
            return '<div class="nowrap">' + w.formatNumber(data, 2) + ' บาท</div>'
          }
        },
        {
          data: 'remark',
          className: 'nowrap',
          render: function(data, type, row, meta) {
            return '<div class="nowrap">' + data + '</div>'
          }
        },
        <?php if ($page == 'history') { ?> {
            data: 'status',
            className: 'nowrap',
            render: function(data, type, row, meta) {
              let html = ``;
              let class_name = '';
              let status_name = '';
              if (data == 'approved') {
                status_name = 'ยืนยันแล้ว';
                class_name = 'success'
              } else if (data == 'reject') {
                status_name = 'ไม่อนุมัติ';
                class_name = 'danger'
              }
              html =
                `
              <div class="d-flex align-items-center">
                <span class="st-dot ${class_name} me-2"></span>
                <div class="text-${class_name}">${status_name}</div>
              </div>
                <div class="sub-text-tbl">
                ${row.status_update_by_fullname ? `โดย ${row.status_update_by_fullname}` : ''}
                ${row.status_update_datetime ? w.formatDate(row.status_update_datetime,'d/m/Y, H:i น.') : ''}
              </div>
              `;
              return html;
            }
          },
        <?php } ?> {
          data: 'id',
          className: 'nowrap',
          render: function(data, type, row, meta) {
            let html = `<a href="record_commission_detail.php?id=${data}">
                      <img src="assets/icons/arrow-right.svg" alt="">
                    </a>`
            return html;
          }
        },
      ],
    });
  </script>
</body>

</html>