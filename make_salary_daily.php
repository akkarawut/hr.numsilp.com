<?php
$user_permission['_permission'] = ['all', 'ywptz_user', 'niemz_user', 'bmiwk_user', 'noaxt_user', 'xacpn_user'];
require_once 'wloves/_framework/import_for_website.php';
require_once 'layout/init.php';
$name_page = 'ค่าจ้างรายวัน';
$count_emp = phr_employee::countEmployee(API_CODE);
$page_size = isset($_GET['page_size']) ? $_GET['page_size'] : 10;
$user_filter_code = isset($_GET['user_code']) ? $_GET['user_code'] : '';
$search_emp = isset($_GET['search_emp']) ? $_GET['search_emp'] : '';

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

$per_dropdown = phr_helper::dropdownRoleEmployee(API_CODE);

if ($_POST) {
  if (isset($_POST['submit_send_salary'])) {
    unset($_POST['submit_send_salary']);
    $data = [
      'user_id' => $current_user_id,
      'ids' => $_POST['id'],
      'status' => 'waiting_confirm',
    ];
    $result = phr_salary::updateStatusSalaryByIDS(API_CODE, $data);
  }
  W_Main::responseAPI(/*$result, $message, $redirect*/);
}

?>
<!-- MENU::ทำเงินเดือน -->
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
          <div class="col-md-12">
            <h1 class="title-header mb-10px">คำนวณค่าจ้างพนักงานรายวัน</h1>
            <p class="sub-title text-secondary">
              รายการค่าจ้างของพนักงานที่คุณต้องจัดการยืนยันความถูกต้องของข้อมูลก่อนส่งอนุมัติจ่าย
            </p>
          </div>
        </div>

        <div class="row my-4">
          <div class="col-lg-12">
            <div class="card-box-tbl">

              <form id="scope_form_filter" method="get">
                <div class="form-filter-tbl">
                  <div class="row">
                    <div class="col-lg-3 col-md-4">
                      <div class="hr-form-control ">
                        <div class="input-group">
                          <input type="text" class="input event_keyup_filter" name="search_emp" value="<?= $search_emp ?>" placeholder="ค้นหาพนักงาน...">
                        </div>
                      </div>
                    </div>
                    <div class="col-lg-2 col-md-4">
                      <div class="hr-form-control ">
                        <div class="input-group">
                          <select name="user_code" class="input event_change_filter">
                            <option value="" <?= $user_filter_code == '' ? 'selected' : '' ?>>ทั้งหมด</option>
                            <?php foreach ($per_dropdown as $val) { ?>
                              <option value="<?= $val['code'] ?>" <?= $user_filter_code == $val['code'] ? 'selected' : '' ?>> <?= $val['name'] ?> </option>
                            <?php } ?>
                          </select>
                        </div>
                      </div>
                    </div>
                    <div class="col-lg-2 col-md-4">
                      <div class="hr-form-control ">
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

              <div class="table-responsive mt-10px">
                <table id="table_make_salary" class="table">
                  <thead>
                    <tr>
                      <th class="thin-cell">ชื่อพนักงาน</th>
                      <th>บัญชี</th>
                      <th>ค่าจ้าง</th>
                      <th>รายการหักค่าใช้จ่าย</th>
                      <th>ยอดหักค่าใช้จ่ายรวม (บาท)</th>
                      <th>ยอดที่ได้รับ (บาท)</th>
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
    </div>
  </div>

  <?php Tiwdal::startModal('modal_send_salary', 'modal-md modal-dialog-centered mx-auto  max-w-500px'); ?>
  <form action="" method="post">
    <div class="modal-header justify-content-center">
      <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
        <span class="font-18px" aria-hidden="true">&times;</span>
      </button>
    </div>
    <div class="modal-body px-20px m-auto max-w-450px pt-0">
      <div class="font-24px font-SemiBold  text-center mb-15px"><?= Ty::get(['ยืนยันการส่งข้อมูลค่าจ้าง', '']) ?></div>
      <div class="text-center mb-15px">
        <div>
          คุณแน่ใจหรือไม่ที่จะ <span class="text-primary">ส่งข้อมูลค่าจ้าง</span>
          ของ <span name={user_fullname} class="fw-semibold"></span> <br>
          เพื่อรอการอนุมัติจ่าย
        </div>
      </div>

    </div>
    <div class="modal-footer">
      <input type="hidden" name="{id}" class="">
      <?php
      TiwForm::normal('btn', '', ['name' => '', 'type' => 'button', 'class' => 'btn-close-modal h-50px border-radius-15px', 'data-bs-dismiss' => 'modal'], ['text' => Ty::get(['ยกเลิก', ''])]);
      TiwForm::normal('btn', '', ['name' => 'submit_send_salary', 'type' => 'submit', 'class' => 'btn-primary h-50px border-radius-15px'], ['text' => 'ส่งข้อมูล']);
      ?>
    </div>
  </form>
  <?php Tiwdal::endModal(); ?>

  <?php
  W_Structure::loadFooterForWebsite();
  W_Main::loadAsset('assets/js/main.js');
  W_Main::loadAsset('assets/js/datatable_option.js');
  ?>

  <script>
    w(document).on('change', '.event_change_filter', function() {
      w('#scope_form_filter').submit();
    });

    var table_make_salary = new DataTable(document.getElementById('table_make_salary'), {
      ajax: {
        url: 'ajax/ajax_table_make_salary.php',
        type: 'POST',
        data: function(d) {
          let info = table_make_salary ?? '';
          let page_no = info ? info.page.info().page + 1 : 1;
          let page_size = info ? info.page.len() : <?= $page_size ?>;
          d.page_no = page_no;
          d.page_size = page_size;
          d.keyword = `<?= $search_emp  ?>`;
          d.user_filter_code = `<?= $user_filter_code  ?>`;
          d.type = 'day';
        }
      },
      columns: [{
          data: 'user_fullname',
          className: 'nowrap',
        },
        {
          data: 'bank_name',
          className: 'nowrap',
          render: function(data, type, row, meta) {
            let html = ``;
            html = `
              <div class="nowrap d-flex align-items-center">
                <img src="${row.bank_logo}" class="img-bank-logo me-3">
                <span>${data}</span>,
                <span>${row.bank_account_number}</span>,
                <span>${row.bank_account_name}</span>
              </div>`
            return html;
          }
        },
        {
          data: 'salary',
          className: 'nowrap text-end',
          render: function(data, type, row, meta) {
            return '<div class="nowrap">' + w.formatNumber(data, 2) + '</div>'
          }
        },
        {
          data: 'count_employee_cost',
          className: 'nowrap text-end',
          render: function(data, type, row, meta) {
            return '<div class="nowrap">' + w.formatNumber(data, 0) + '</div>'
          }
        },
        {
          data: 'employee_cost_total_price',
          className: 'nowrap text-end',
          render: function(data, type, row, meta) {
            return `<div class="nowrap ${data > 0 ? 'text-danger' : ''} "> ${data > 0 ? '-'+ w.formatNumber(data, 2) : data}</div>`
          }
        },
        {
          data: 'total_price',
          className: 'nowrap text-end',
          render: function(data, type, row, meta) {
            return '<div class="nowrap">' + w.formatNumber(data, 2) + '</div>'
          }
        },
        {
          data: 'id',
          className: 'nowrap',
          render: function(data, type, row, meta) {
            let json = {
              'id': data,
              'user_fullname': row.user_fullname,
            };
            let json_data = btoa(unescape(encodeURIComponent(JSON.stringify(json))));

            let html = `<div class="icon-action d-flex align-items-center">
                  <button type="button" class="btn btn-c-icon  btn-c-primary-v2 me-4" toggle-edit-modal="#modal_send_salary" data-register="${json_data}">
                    <?= file_get_contents('assets/icons/send.svg') ?>
                  </button>
                  <a href="make_salary_detail.php?id=${data}&type=daily">
                      <img src="assets/icons/arrow-right.svg" alt="">
                  </a>
                </div>
                    `
            return html;
          }
        },
      ],
    });
  </script>
</body>

</html>