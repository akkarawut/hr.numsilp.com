<?php
$user_permission['_permission'] = ['all', 'ywptz_user', 'niemz_user', 'bmiwk_user', 'noaxt_user', 'xacpn_user'];
require_once 'wloves/_framework/import_for_website.php';
require_once 'layout/init.php';
require_once 'component/navtab.php';
$name_page = 'ตรวจสอบความถูกต้องบันทึกยอดขายและค่าคอมมิชชั่น';
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

if ($_POST) {
  if (isset($_POST['submit_approved_commission'])) {
    $data = [
      'user_id' => $current_user_id,
      'status' => 'approved',
      'tax' => $_POST['tax'],
    ];
    $file = isset($_FILES['file']) ? $_FILES['file'] : '';
    $result = phr_commission::updateStatusCommissionByID(API_CODE, $_POST['id'], $data, $file);
  } else if (isset($_POST['submit_approved_commission_reject'])) {
    $data = [
      'user_id' => $current_user_id,
      'remark' => $_POST['remark'],
      'status' => 'reject',
    ];
    $result = phr_commission::updateStatusCommissionByID(API_CODE, $_POST['id'], $data);
  }
  W_Main::responseAPI(/*$result, $message, $redirect*/);
}
?>
<!-- MENU::ตรวจสอบความถูกต้องบันทึกยอดขายและค่าคอมมิชชั่น -->
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
          <div class="col-md-12 mb-4">
            <h1 class="title-header mb-10px">ตรวจสอบความถูกต้องบันทึกยอดขายและค่าคอมมิชชั่น</h1>
            <p class="sub-title text-secondary">รายการลงบันทึกค่าคอมมิชชั่น รอการตรวจสอบความถูกต้อง</p>
          </div>
        </div>

        <?= navTab::normal($data_nav, ' ', 'mb-4'); ?>

        <div class="row my-4">
          <div class="col-lg-12">
            <div class="card-box-tbl">
              <div class="table-responsive mt-10px">
                <table id="table_approve_commission" class="table">
                  <thead>
                    <tr>
                      <th>เลขที่อ้างอิง</th>
                      <th>พนักงาน</th>
                      <th>วันที่ลงบันทึก</th>
                      <th>บันทึกยอดขาย (บาท)</th>
                      <th>คอมมิชชั่นที่ควรได้รับ (บาท)</th>
                      <?php if ($page == 'history') { ?>
                        <th>ภาษีหัก ณ ที่จ่าย</th>
                        <th>ยอดที่ได้รับ (บาท)</th>
                      <?php } else { ?>
                        <th>หมายเหตุ</th>
                      <?php } ?>
                      <?php if ($page == 'history') { ?>
                        <th>สถานะการตรวจสอบ</th>
                      <?php } ?>
                      <?php if ($page == 'waiting_confirm') { ?>
                        <th class="thin-cell"></th>
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

  <?php Tiwdal::ajaxModal('approved_commission', 'modal-md modal-dialog-centered mx-auto  max-w-500px'); ?>
  <?php Tiwdal::ajaxModal('approved_commission_reject', 'modal-md modal-dialog-centered mx-auto  max-w-500px'); ?>


  <?php
  W_Structure::loadFooterForWebsite();
  W_Main::loadAsset('assets/js/main.js');
  W_Main::loadAsset('assets/js/datatable_option.js');
  ?>
  <?php require_once 'layout/footer_js.php'; ?>
  <script>
    var table_approve_commission = new DataTable(document.getElementById('table_approve_commission'), {
      ajax: {
        url: 'ajax/ajax_table_approve_commission.php',
        type: 'POST',
        data: function(d) {
          let info = table_approve_commission ?? '';
          let page_no = info ? info.page.info().page + 1 : 1;
          let page_size = info ? info.page.len() : 10;
          d.page_no = page_no;
          d.page_size = page_size;
          d.status = `<?= $page ?>`;
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
        <?php if ($page == 'history') { ?> {
            data: 'tax',
            className: 'nowrap',
            render: function(data, type, row, meta) {
              return '<div class="nowrap">' + w.formatNumber(data, 0) + '%</div>'
            }
          },
          {
            data: 'total_price',
            className: 'nowrap',
            render: function(data, type, row, meta) {
              return '<div class="nowrap">' + w.formatNumber(data, 2) + ' บาท</div>'
            }
          },
        <?php } ?>
        <?php if ($page != 'history') { ?> {
            data: 'remark',
            className: 'nowrap',
            render: function(data, type, row, meta) {
              return '<div class="nowrap">' + data + '</div>'
            }
          },
        <?php } ?>
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
        <?php } ?>

        <?php if ($page == 'waiting_confirm') { ?> {
            data: 'id',
            className: 'nowrap',
            render: function(data, type, row, meta) {
              let html = ``;
              let json = {
                'id': data,
                'full_name': row.full_name,
              };
              let json_data = btoa(unescape(encodeURIComponent(JSON.stringify(json))));
              html = `
              <div class="d-flex">
                <button type="button" class="btn btn-icon-squere danger mr-10px" toggle-ajax-modal="#approved_commission_reject" data-register="${json_data}" is_ajax="1">
                  <?= file_get_contents('assets/icons/times.svg') ?>
                </button>
                <button type="button" class="btn btn-icon-squere success" toggle-ajax-modal="#approved_commission" data-register="${json_data}" is_ajax="1">
                  <?= file_get_contents('assets/icons/check-success.svg') ?>
                </button>
              </div>
              `;
              return html;
            }
          },
        <?php } ?> {
          data: 'id',
          className: 'nowrap',
          render: function(data, type, row, meta) {
            let html = `<a href="approve_commission_detail.php?id=${data}&page=<?= $page ?>">
                      <img src="assets/icons/arrow-right.svg" alt="">
                    </a>`
            return html;
          }
        },
      ],
    });

    function calTotalPrice(t) {
      let tax_val = t.value;
      let commission = parseFloat(document.querySelector('.txt_commission').innerHTML);
      let tax_price = commission * (tax_val / 100);
      let total = commission - tax_price;
      let txt_cal_txt = document.querySelector('.txt_cal_txt').innerHTML = w.formatNumber(total) + ' บาท';
    }
  </script>
</body>

</html>