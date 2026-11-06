<?php
$user_permission['_permission'] = ['all', 'ywptz_user', 'niemz_user', 'bmiwk_user', 'noaxt_user', 'xacpn_user'];
require_once 'wloves/_framework/import_for_website.php';
require_once 'layout/init.php';
require_once 'component/navtab.php';
$page = isset($_GET['page']) ? $_GET['page'] : 'set_deduction_conditions';
$type = isset($_GET['type']) ? $_GET['type'] : '';
$_GET['page'] = $page;
$name_page = 'การทำงานของระบบ';

$count = phr_setting_deduct_money::countSettingDeductMoney(API_CODE);
$data_nav = [
  'param_name' => 'page',
  'nav_list' => [
    [
      'id' => 'set_deduction_conditions',
      'name' => 'ตั้งค่าเงื่อนไขการหักเงิน',
      'count' => [
        'number' =>   number_format($count['count_deduct'], 0),
        'class' => ''
      ]
    ],
    [
      'id' => 'set_ot_payment_rate',
      'name' => 'ตั้งค่าอัตราการจ่ายค่า OT',
      'count' => [
        'number' =>   number_format($count['count_ot'], 0),
        'class' => ''
      ]
    ],
    [
      'id' => 'set_setting_basic',
      'name' => 'ตั้งค่าข้อมูลเบื้องต้น',
    ],
  ]
];
?>
<!-- MENU::ตั้งค่า_การทำงานของระบบ -->
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

        <?= navTab::normal($data_nav, ' ', 'mb-4'); ?>

        <?php if ($page == 'set_deduction_conditions') { ?>
          <div class="row row align-items-end">
            <div class="col-md-9 mb-4">
              <h1 class="title-header mb-10px">ตั้งค่าเงื่อนไขการหักเงิน</h1>
              <p class="sub-title text-secondary">ตั้งค่าเงื่อนไขการหักเงินในกรณีขาด ลา มาสายตรงตามเงื่อนไข</p>
            </div>
            <div class="col-md-3 mb-4">
              <a href="deduction_conditions_add.php" class="btn btn-primary border-radius-15px btn-res-150 ms-auto">เพิ่มเงื่อนไข</a>
            </div>
          </div>
        <?php } else if ($page == 'set_ot_payment_rate') { ?>
          <div class="row row align-items-end">
            <div class="col-md-9 mb-4">
              <h1 class="title-header mb-10px">ตั้งค่าอัตราการจ่ายค่า OT</h1>
              <p class="sub-title text-secondary">
                ตั้งค่าเงื่อนไขและอัตราการจ่ายค่าทำงานล่วงเวลาของพนักงาน <span class="text-danger">**เงื่อนไขจะมีผลเมื่ออนุมัติคำร้องขอทำงานล่วงเวลาแล้วเท่านั้น</span>
              </p>
            </div>
            <div class="col-md-3 mb-4">
              <a href="ot_conditions_add.php" class="btn btn-primary border-radius-15px btn-res-150 ms-auto">เพิ่มเงื่อนไข</a>
            </div>
          </div>
        <?php } else if ($page == 'set_setting_basic') { ?>
          <div class="row row align-items-end">
            <div class="col-md-12 mb-4">
              <h1 class="title-header mb-10px">ตั้งค่าข้อมูลเบื้องต้น</h1>
              <p class="sub-title text-secondary">
                ตั้งค่าข้อมูลเบื้องต้นสำหรับการทำงานด้านต่าง ๆ ภายในระบบ
              </p>
            </div>
          </div>
        <?php } ?>



        <div class="row mb-4">
          <div class="col-lg-12">
            <?php
            if ($page == 'set_deduction_conditions') {
              include_once 'view/setting/system/set_deduction_conditions.php';
            } else if ($page == 'set_ot_payment_rate') {
              include_once 'view/setting/system/set_ot_payment_rate.php';
            } else if ($page == 'set_setting_basic') {
              include_once 'view/setting/system/setting_basic.php';
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

  <script>
    <?php if ($page == 'set_deduction_conditions') { ?>
      var table_setting_deduct = new DataTable(document.getElementById('table_setting_deduct'), {
        ajax: {
          url: 'ajax/ajax_table_setting_deduct.php',
          type: 'POST',
          data: function(d) {
            let info = table_setting_deduct ?? '';
            let page_no = info ? info.page.info().page + 1 : 1;
            let page_size = info ? info.page.len() : 10;
            d.page_no = page_no;
            d.page_size = page_size;
          }
        },
        columns: [{
            data: 'title',
            className: 'nowrap',
          },
          {
            data: 'type',
            className: 'nowrap',
            render: function(data, type, row, meta) {
              if (data == 'late') {
                return `มาสาย ${row.amount} ครั้งในรอบเดือน`;
              } else if (data == 'very_late') {
                return `มาสายมาก ${row.amount} ครั้งในรอบเดือน`;
              } else if (data == 'missing') {
                return `ขาด ${row.amount} ครั้งในรอบเดือน`;
              } else if (data == 'leave_work') {
                return `ลางาน ${row.amount} ครั้งในรอบเดือน`;
              } else {
                return '';
              }
            }
          },
          {
            data: 'price',
            className: 'nowrap',
            render: function(data, type, row, meta) {
              return '<div class="nowrap">' + w.formatNumber(data, 2) + '</div>'
            }
          },
          {
            data: 'count_user',
            className: 'nowrap  text-center',
            render: function(data, type, row, meta) {
              return '<div class="nowrap">' + data + '</div>'
            }
          },
          {
            data: 'description',
            className: 'nowrap',
          },
          {
            data: 'is_use',
            className: 'nowrap',
            render: function(data, type, row, meta) {
              if (data == 1) {
                return `
              <div class="d-flex align-items-center">
                <span class="st-dot primary me-2"></span>
                <div class="text-primary">เปิดใช้งาน</div>
              </div>
              `
              } else {
                return `
              <div class="d-flex align-items-center">
                <span class="st-dot secondary me-2"></span>
                <div class="text-secondary">ปิดใช้งาน</div>
              </div>
              `
              }
            }
          },
          {
            data: 'id',
            className: 'nowrap',
            render: function(data, type, row, meta) {
              return `<a href="deduction_conditions_detail.php?id=${data}"><img src="assets/icons/arrow-right.svg" alt=""></a>`
            }
          },
        ],
      });

    <?php } else if ($page == 'set_ot_payment_rate') { ?>
      var table_setting_ot = new DataTable(document.getElementById('table_setting_ot'), {
        ajax: {
          url: 'ajax/ajax_table_setting_ot.php',
          type: 'POST',
          data: function(d) {
            let info = table_setting_ot ?? '';
            let page_no = info ? info.page.info().page + 1 : 1;
            let page_size = info ? info.page.len() : 10;
            d.page_no = page_no;
            d.page_size = page_size;
          }
        },
        columns: [{
            data: 'title',
            className: 'nowrap',
          },
          {
            data: 'price',
            className: 'nowrap',
            render: function(data, type, row, meta) {
              return '<div class="nowrap">' + w.formatNumber(data, 2) + ' บาท : ชั่วโมง</div>'
            }
          },
          {
            data: 'count_user',
            className: 'nowrap text-center',
            render: function(data, type, row, meta) {
              return '<div class="nowrap">' + data + '</div>'
            }
          },
          {
            data: 'description',
            className: 'nowrap',
          },
          {
            data: 'is_use',
            className: 'nowrap',
            render: function(data, type, row, meta) {
              if (data == 1) {
                return `
              <div class="d-flex align-items-center">
                <span class="st-dot primary me-2"></span>
                <div class="text-primary">เปิดใช้งาน</div>
              </div>
              `
              } else {
                return `
              <div class="d-flex align-items-center">
                <span class="st-dot secondary me-2"></span>
                <div class="text-secondary">ปิดใช้งาน</div>
              </div>
              `
              }
            }
          },
          {
            data: 'id',
            className: 'nowrap',
            render: function(data, type, row, meta) {
              return `<a href="ot_conditions_detail.php?id=${data}"><img src="assets/icons/arrow-right.svg" alt=""></a>`
            }
          },
        ],
      });
    <?php } ?>
  </script>
</body>

</html>