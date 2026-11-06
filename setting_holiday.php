<?php
$user_permission['_permission'] = ['all', 'ywptz_user', 'niemz_user', 'bmiwk_user', 'noaxt_user', 'xacpn_user'];
require_once 'wloves/_framework/import_for_website.php';
require_once 'layout/init.php';
require_once 'component/navtab.php';
$name_page = 'ตั้งค่าวันหยุด';
$page = isset($_GET['page']) ? $_GET['page'] : 'is_coming';
$_GET['page'] = $page;

$count =  phr_setting_holiday::countSettingHoliday(API_CODE);
$data_nav = [
  'param_name' => 'page',
  'nav_list' => [
    [
      'id' => 'is_coming',
      'name' => 'กำลังจะมาถึง',
      'count' => [
        'number' =>   number_format($count['is_coming'], 0),
        'class' => ''
      ]
    ],
    [
      'id' => 'passed',
      'name' => 'ผ่านไปแล้ว',
      'count' => [
        'number' =>  number_format($count['is_pass'], 0),
        'class' => ''
      ]
    ],
  ]
];
?>
<!-- MENU::ตั้งค่า_ตั้งค่าวันหยุด -->
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
            <h1 class="title-header mb-10px">ตั้งค่าวันหยุด</h1>
            <p class="sub-title text-secondary">กำหนดวันหยุดของบริษัท รวมถึงตั้งค่าเงื่อนไขการนับวันทำงาน ของพนักงาน</p>
          </div>
          <div class="col-md-3 mb-4">
            <a href="scheduled_holidays.php" class="btn btn-primary border-radius-15px btn-res-150 ms-auto">กำหนดวันหยุดใหม่</a>
          </div>
        </div>

        <?= navTab::normal($data_nav, ' ', 'mb-4'); ?>

        <div class="row mb-4">
          <div class="col-lg-12">
            <?php
            if ($page == 'is_coming') {
              include_once 'view/setting/holiday/is_coming.php';
            } else if ($page == 'passed') {
              include_once 'view/setting/holiday/passed.php';
            }
            ?>
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
    var table_setting_holiday = new DataTable(document.getElementById('table_setting_holiday'), {
      ajax: {
        url: 'ajax/ajax_table_setting_holiday.php',
        type: 'POST',
        data: function(d) {
          let info = table_setting_holiday ?? '';
          let page_no = info ? info.page.info().page + 1 : 1;
          let page_size = info ? info.page.len() : 10;
          d.page_no = page_no;
          d.page_size = page_size;
          d.status = `<?= $page ?>`;
        }
      },
      columns: [{
          data: 'title',
          className: 'nowrap',
          render: function(data, type, row, meta) {
            return '<div class="nowrap">' + data + '</div>'
          }
        },
        {
          data: 'from_date',
          className: 'nowrap',
          render: function(data, type, row, meta) {
            let html = ``;
            if (data == row.to_date) {
              html = `
              <div class="nowrap">
               ${moment(data).format('LL')} <br> 
                <span class="font-14px font-Medium">${row.sum_day_work} วัน</span>
              </div>`
              return html;
            } else if (data != row.to_date) {
              html = `
              <div class="nowrap"> 
               ${moment(data).format('LL')} - ${moment(row.to_date).format('LL')} <br> 
                <span class="font-14px font-Medium">${row.sum_day_work} วัน</span>
              </div>`
              return html;
            } else {
              html = `
              <div class="nowrap">
               ${moment(data).format('LL')} <br> 
                <span class="font-14px font-Medium">${row.sum_day_work} วัน</span>
              </div>`
            }
          }
        },
        {
          data: 'description',
          className: 'nowrap',
          render: function(data, type, row, meta) {
            return '<div class="nowrap">' + data + '</div>'
          }
        },
        {
          data: 'id',
          className: 'nowrap',
          render: function(data, type, row, meta) {
            let html = `<a href="scheduled_holidays_detail.php?id=${data}">
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