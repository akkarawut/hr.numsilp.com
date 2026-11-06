<?php
$user_permission['_permission'] = ['all', 'ywptz_user', 'niemz_user', 'bmiwk_user', 'noaxt_user', 'xacpn_user'];
require_once 'wloves/_framework/import_for_website.php';
require_once 'layout/init.php';
require_once 'component/navtab.php';
$name_page = 'ข้อมูลพนักงาน';
$id = isset($_GET['id']) && $_GET['id'] ? $_GET['id'] : 0;
$page = isset($_GET['page']) && $_GET['page'] ? $_GET['page'] : 'emp_detail';
$_GET['page'] = $page;
$is_edit = isset($_GET['is_edit']) && $_GET['is_edit'] ? $_GET['is_edit'] : 0;
$mode = isset($_GET['mode']) ? $_GET['mode'] : 'employee';
$emp = phr_employee::getEmployeeByID(API_CODE, $id);

$type_dropdown = phr_helper::dropdownWorkingTime(API_CODE);
$branch_dropdown = phr_helper::dropdownBranch(API_CODE);
$per_dropdown = phr_helper::dropdownRoleEmployee(API_CODE);
$bank_dropdown = Bank::select();
if ($_POST) {
  if (isset($_POST['submit_update_employee'])) {
    unset($_POST['submit_update_employee']);
    // $file = isset($_FILES['file_others']) ? $_FILES['file_others'] : '';
    // $file_others = [];
    // if ($file) {
    //   foreach ($file['name'] as $key => $value) {
    //     $file_others[] = [
    //       'name' => $value,
    //       'type' => $file['type'][$key],
    //       'tmp_name' => $file['tmp_name'][$key],
    //       'error' => $file['error'][$key],
    //       'size' => $file['size'][$key],
    //     ];
    //   }
    // }

    $file_del = '';
    if (isset($_POST['delete_file_arr']) && $_POST['delete_file_arr']) {
      $file_del = explode(',', $_POST['delete_file_arr']);
    }
    unset($_POST['delete_file_arr']);

    $result = phr_employee::updateEmployee(API_CODE, $emp['id'], $_POST, $_FILES['file_profile'], $_FILES['file_id_card'], $_FILES['file_house_regis'], $_FILES['file_edu_qual'], $_FILES['file_soldier_certificate'], $file_del);
    if ($result['response_status']) {
      $redirect = 'employee_detail.php?id=' . $emp['id'] . '&mode=' . $mode;
    }
  } else if (isset($_POST['submit_change_password'])) {
    if ($_POST['password'] == $_POST['confirm_password']) {
      unset($_POST['confirm_password']);
      $result = User::forceChangePassword($emp['id'], $_POST['password']);
      if ($result['response_status']) {
        $redirect = 'employee_detail.php?id=' . $emp['id'] . '&mode=' . $mode;
      }
    } else {
      $result = [
        'response_status' => false,
        'response_message' => 'Error',
      ];
      $message = 'รหัสผ่านไม่ตรงกัน';
    }
  } else if (isset($_POST['submit_change_permission'])) {
    unset($_POST['submit_change_permission']);
    $result = phr_employee::updateEmployeeLogin(API_CODE, $emp['id'], ['user_code' => $_POST['user_code']]);
    if ($result['response_status']) {
      $redirect = 'employee_detail.php?id=' . $emp['id'] . '&mode=' . $mode;
    }
  } else if (isset($_POST['submit_change_profile'])) {
    unset($_POST['submit_change_profile']);
    $result = phr_employee::updateEmployeeLogin(API_CODE, $emp['id'], [], $_FILES['profile_image']);
    if ($result['response_status']) {
      $redirect = 'employee_detail.php?id=' . $emp['id'] . '&mode=' . $mode;
    }
  } else if (isset($_POST['submit_delete_emp'])) {
    $result = phr_employee::deleteEmployee(API_CODE, $emp['id']);
    if ($result['response_status']) {
      $redirect = 'employee_information.php';
    }
  } else if (isset($_POST['submit_approved_probation'])) {
    $data = [
      'user_id' => $current_user_id,
      'status' => 'approved',
      'is_working' => isset($_POST['is_working']) ? 1 : 0,
    ];
    $result = phr_employee::updateStatusTrialEmployeeByID(API_CODE, $_POST['id'], $data);
    if ($result['response_status']) {
      $redirect = 'approve_probation.php';
    }
  } else if (isset($_POST['submit_probation_reject'])) {
    $data = [
      'user_id' => $current_user_id,
      'remark' => $_POST['remark'],
      'status' => 'reject',
    ];
    $result = phr_employee::updateStatusTrialEmployeeByID(API_CODE, $_POST['id'], $data);
    if ($result['response_status']) {
      $redirect = 'approve_probation.php';
    }
  }
  W_Main::responseAPI(/*$result, $message, $redirect*/);
}

$data_nav = [
  'param_name' => 'page',
  'nav_list' => [
    [
      'id' => 'emp_detail',
      'name' => 'รายละเอียดพนักงาน',
    ],
    [
      'id' => 'emp_performance',
      'name' => $mode == 'my_profile' ? 'Performance ของฉัน' : 'Performance ของพนักงาน',
    ],
  ]
];

if ($mode == 'employee' && $mode != 'my_profile') {
  array_push(
    $data_nav['nav_list'],
    [
      'id' => 'emp_financial',
      'name' => 'รายการค่าใช้จ่ายของพนักงาน',
    ],
    [
      'id' => 'emp_history_money',
      'name' => 'ประวัติการรับเงิน',
    ],
  );
} else if ($mode == 'my_profile') {
  array_push(
    $data_nav['nav_list'],
    [
      'id' => 'emp_history_money',
      'name' => 'ประวัติการรับเงิน',
    ],
  );
  $name_page = 'ข้อมูลส่วนตัว';
}

if ($mode == 'probation') {
  $name_page = 'อนุมัติผ่านการทดลองงาน';
}

$status_emp_arr = [
  'month' => 'พนักงานประจำ (รายเดือน)',
  'day' => 'พนักงานชั่วคราว (รายวัน)',
  'trial_work' => 'พนักงานทดลองงาน'
];
?>
<!-- MENU::ตั้งค่า_ตั้งค่ารูปแบบเวลา -->
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
      <?php if ($is_edit) { ?>
        <?php include_once 'view/employee/form_edit.php' ?>
      <?php } else {  ?>
        <?php include_once 'view/employee/form_detail.php' ?>
      <?php } ?>
    </div>

    <?php Tiwdal::startModal('delete_emp', 'modal-md modal-dialog-centered mx-auto  max-w-500px'); ?>
    <form action="" method="post">
      <div class="modal-header justify-content-center">
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
          <span class="font-18px" aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body px-20px m-auto max-w-450px pt-0">
        <div class="font-24px font-SemiBold  text-center mb-15px"><?= Ty::get(['ลบข้อมูลพนักงาน', '']) ?></div>
        <div class="text-center">
          <div>
            คุณแน่ใจหรือไม่ที่จะลบข้อมูลพนักงานรายนี้ รายการใด ๆ ที่เกิดขึ้นจาก <br>
            พนักงานรายนี้แล้วจะไม่ได้ถูกลบด้วยและยังคงบันทึกไว้ในระบบ
          </div>
        </div>

      </div>
      <div class="modal-footer">
        <input type="hidden" name="{id}">
        <?php
        TiwForm::normal('btn', '', ['name' => '', 'type' => 'button', 'class' => 'btn-close-modal min-w-90px', 'data-bs-dismiss' => 'modal'], ['text' => Ty::get(['ยกเลิก', ''])]);
        TiwForm::normal('btn', '', ['name' => 'submit_delete_emp', 'type' => 'submit', 'class' => 'btn-danger min-w-120px'], ['text' => Ty::get(['ลบข้อมูล', ''])]);
        ?>
      </div>
    </form>
    <?php Tiwdal::endModal(); ?>



    <?php
    W_Structure::loadFooterForWebsite();
    W_Main::loadAsset('assets/js/main.js');
    W_Main::loadAsset('assets/js/datatable_option.js');
    ?>

    <?php include_once 'layout/footer_js.php' ?>
    <?php include_once 'assets/js/employee.php' ?>
    <?php if ($page == 'emp_performance' && $mode != 'probation') { ?>
      <script>
        var table_emp_performance = new DataTable(document.getElementById('table_emp_performance'), {
          ajax: {
            url: 'ajax/ajax_table_emp_performance_list.php',
            type: 'POST',
            data: function(d) {
              let info = table_emp_performance ?? '';
              let page_no = info ? info.page.info().page + 1 : 1;
              let page_size = info ? info.page.len() : 10;
              d.id = `<?= $id ?>`;
              d.page_no = page_no;
              d.page_size = page_size;
            }
          },
          columns: [{
              data: 'date',
              className: 'nowrap',
              render: function(data, type, row, meta) {
                html = `<div>${moment(data).format('MMMM Y')}</div>`;
                return html;
              }
            },
            {
              data: 'count_day_work',
              className: 'nowrap text-center',
              render: function(data, type, row, meta) {
                return `<div class="nowrap">${data}</div>`
              }
            },
            {
              data: 'count_late',
              className: 'nowrap text-center',
              render: function(data, type, row, meta) {
                return `<div class="nowrap ${data > 0 ? 'text-danger' : ''}">${data}</div>`
              }
            },
            {
              data: 'count_very_late',
              className: 'nowrap text-center',
              render: function(data, type, row, meta) {
                return `<div class="nowrap ${data > 0 ? 'text-danger' : ''}">${data}</div>`
              }
            },
            {
              data: 'count_missing',
              className: 'nowrap text-center',
              render: function(data, type, row, meta) {
                return `<div class="nowrap ${data > 0 ? 'text-danger' : ''}">${data}</div>`
              }
            },
            {
              data: 'count_leave_work',
              className: 'nowrap text-center',
              render: function(data, type, row, meta) {
                return `<div class="nowrap ${data > 0 ? 'text-danger' : ''}">${data}</div>`
              }
            },
            {
              data: 'percent_performance',
              className: 'nowrap text-center',
              render: function(data, type, row, meta) {
                return `<div class="nowrap fw-semibold ${data > 80 ? 'text-primary' : 'text-danger'}">${w.formatNumber(data, 0) }%</div>`
              }
            },
          ],
        });

        var table_issue_warning = new DataTable(document.getElementById('table_issue_warning'), {
          ajax: {
            url: 'ajax/ajax_table_issue_warning.php',
            type: 'POST',
            data: function(d) {
              let info = table_issue_warning ?? '';
              let page_no = info ? info.page.info().page + 1 : 1;
              let page_size = info ? info.page.len() : 10;
              d.page_no = page_no;
              d.page_size = page_size;
              d.user_id = `<?= $emp['id'] ?>`;
            }
          },
          columns: [{
              data: 'doc_no',
              className: 'nowrap',
            },
            {
              data: 'user_fullname',
              className: 'nowrap',
              render: function(data, type, row, meta) {
                let html = `
            <div>${data}</div>
            `
                return html;
              }
            },
            {
              data: 'number',
              className: 'nowrap',
            },
            {
              data: 'detail',
              className: 'nowrap',
            },
            {
              data: 'description',
              className: 'nowrap',
            },
            {
              data: 'id',
              className: 'nowrap',
              render: function(data, type, row, meta) {
                let html = `<a href="issue_warning_detail.php?id=${data}">
                      <img src="assets/icons/arrow-right.svg" alt="">
                    </a>`
                return html;
              }
            },
          ],
        });

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
              d.user_id = `<?= $emp['id'] ?>`;
            }
          },
          columns: [{
              data: 'count_sale',
              className: 'nowrap text-center',
              render: function(data, type, row, meta) {
                return '<div class="nowrap">' + w.formatNumber(data, 0) + '</div>'
              }
            },
            {
              data: 'total_sale',
              className: 'nowrap text-center',
              render: function(data, type, row, meta) {
                return '<div class="nowrap">' + w.formatNumber(data, 2) + '</div>'
              }
            },
            {
              data: 'total_commission',
              className: 'nowrap text-center',
              render: function(data, type, row, meta) {
                return '<div class="nowrap">' + w.formatNumber(data, 2) + '</div>'
              }
            },
          ],
        });
      </script>
    <?php } else  if ($page == 'emp_financial') { ?>
      <script>
        var table_emp_financial = new DataTable(document.getElementById('table_emp_financial'), {
          ajax: {
            url: 'ajax/ajax_table_emp_financial_list.php',
            type: 'POST',
            data: function(d) {
              let info = table_emp_financial ?? '';
              let page_no = info ? info.page.info().page + 1 : 1;
              let page_size = info ? info.page.len() : 10;
              d.page_no = page_no;
              d.page_size = page_size;
              d.user_id = `<?= $id ?>`;
            }
          },
          columns: [{
              data: 'name',
              className: 'nowrap',
              render: function(data, type, row, meta) {
                return '<div class="nowrap">' + data + '</div>'
              }
            },
            {
              data: 'total_price',
              className: 'nowrap',
              render: function(data, type, row, meta) {
                return '<div class="nowrap">' + w.formatNumber(data, 2) + '</div>'
              }
            },
            {
              data: 'period',
              className: 'nowrap',
              render: function(data, type, row, meta) {
                return `<div class="nowrap"><span class="text-primary">${row.period_sum_pay}</span> / ${data}</div>`
              }
            },
            {
              data: 'price_per_period',
              className: 'nowrap',
              render: function(data, type, row, meta) {
                return '<div class="nowrap">' + w.formatNumber(data, 2) + '</div>'
              }
            },
            {
              data: 'total_price',
              className: 'nowrap',
              render: function(data, type, row, meta) {
                return `<div class="nowrap"><span class="text-primary">${row.total_price_pay}</span> / ${data}</div>`
              }
            },
            {
              data: 'status',
              className: 'nowrap',
              render: function(data, type, row, meta) {
                let html = ``;
                let class_name = '';
                let status_name = '';
                if (data == 'in_progress') {
                  status_name = 'ดำเนินการอยู่';
                  class_name = 'primary'
                } else if (data == 'success') {
                  status_name = 'เสร็จสิ้น';
                  class_name = 'success'
                } else if (data == 'cancel') {
                  status_name = 'ยกเลิก';
                  class_name = 'danger'
                }
                html =
                  `
              <div class="d-flex align-items-center">
                <span class="st-dot ${class_name} me-2"></span>
                <div class="text-${class_name}">${status_name}</div>
              </div>
              `;
                return html;
              }
            },
            {
              data: 'remark',
              className: 'nowrap',
            },
            {
              data: 'id',
              className: 'nowrap',
              render: function(data, type, row, meta) {
                let html = `<a href="employee_cost_detail.php?id=${data}&mode=<?= $mode ?>&page=<?= $page ?>&user_id=<?= $id ?>">
                      <img src="assets/icons/arrow-right.svg" alt="">
                    </a>`
                return html;
              }
            },
          ],
        });
      </script>
    <?php } else if ($page == 'emp_history_money') { ?>
      <script>
        var table_emp_salary = new DataTable(document.getElementById('table_emp_salary'), {
          ajax: {
            url: 'ajax/ajax_table_emp_salary_list.php',
            type: 'POST',
            data: function(d) {
              let info = table_emp_salary ?? '';
              let page_no = info ? info.page.info().page + 1 : 1;
              let page_size = info ? info.page.len() : 10;
              d.page_no = page_no;
              d.page_size = page_size;
              d.user_id = `<?= $id ?>`;
            }
          },
          columns: [{
              data: 'installment_date',
              className: 'nowrap',
              render: function(data, type, row, meta) {
                if (row.employee_status != 'day') {
                  return `<div>${moment(data).format('MMMM, Y')}</div>`;
                } else {
                  return `<div>${moment(data).format('D MMMM, Y')}</div>`;
                }
              }
            },
            {
              data: 'status_update_datetime',
              className: 'nowrap',
              render: function(data, type, row, meta) {
                return `<div class="nowrap">${moment(data).format('L, Y')} โดย ${row.status_update_by_fullname}</div>`;
              }
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
              className: 'nowrap',
              render: function(data, type, row, meta) {
                return '<div class="nowrap">' + w.formatNumber(data, 2) + '</div>'
              }
            },
            {
              data: 'employee_cost_total_price',
              className: 'nowrap',
              render: function(data, type, row, meta) {
                return `<div class="nowrap ${data > 0 ? 'text-danger' : ''}"> ${data > 0 ? '-'+ w.formatNumber(data, 2) : 0}</div>`
              }
            },
            {
              data: 'total_price',
              className: 'nowrap',
              render: function(data, type, row, meta) {
                return '<div class="nowrap">' + w.formatNumber(data, 2) + '</div>'
              }
            },
            {
              data: 'ref_no',
              className: 'nowrap text-center',
              render: function(data, type, row, meta) {
                return `<div class="nowrap">${data ? data : ''}</div>`;
              }
            },
            {
              data: 'id',
              className: 'nowrap',
              render: function(data, type, row, meta) {
                let html = `
                <div class="icon-action d-flex align-items-center">
                  <button type="button" class="btn btn-c-icon circle btn-c-primary me-3">
                    <?= file_get_contents('assets/icons/printer.svg') ?>
                  </button>
                  <a href="employee_salary_detail.php?id=${data}&mode=<?= $mode ?>&page=<?= $page ?>&user_id=<?= $id ?>">
                      <img src="assets/icons/arrow-right.svg" alt="">
                  </a>
                </div>`
                return html;
              }
            },
          ],
        });


        var table_emp_commission = new DataTable(document.getElementById('table_emp_commission'), {
          ajax: {
            url: 'ajax/ajax_table_emp_commission_list.php',
            type: 'POST',
            data: function(d) {
              let info = table_emp_commission ?? '';
              let page_no = info ? info.page.info().page + 1 : 1;
              let page_size = info ? info.page.len() : 10;
              d.page_no = page_no;
              d.page_size = page_size;
              d.user_id = `<?= $id ?>`;
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
              data: 'insert_date_time',
              className: 'nowrap',
              render: function(data, type, row, meta) {
                let html = ``;
                html = `
              <div class="nowrap">
               <div>${w.formatDate(data,'d/m/Y, H:i น.')}</div>
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
              data: 'tax',
              className: 'nowrap',
              render: function(data, type, row, meta) {
                return '<div class="nowrap">' + w.formatNumber(data, 0) + ' %</div>'
              }
            },
            {
              data: 'total_price',
              className: 'nowrap',
              render: function(data, type, row, meta) {
                return '<div class="nowrap">' + w.formatNumber(data, 2) + ' บาท</div>'
              }
            },
            {
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
            {
              data: 'id',
              className: 'nowrap',
              render: function(data, type, row, meta) {

                let html = ``;
                html += `
                <div class="icon-action d-flex align-items-center justify-content-end">`
                if (row.is_file_deduct_tax == 1) {
                  html += `
                  <a href="${row.file_deduct_tax_path}" class="btn btn-c-icon circle btn-c-primary me-3" target="_blank" download>
                    <?= file_get_contents('assets/icons/printer.svg') ?>
                  </a>`;
                }
                html += `<a href="employee_commission_detail.php?id=${data}&mode=<?= $mode ?>&page=<?= $page ?>&user_id=<?= $id ?>">
                      <img src="assets/icons/arrow-right.svg" alt="">
                  </a>
                </div>`
                return html;
              }
            },
          ],
        });
      </script>
    <?php } ?>

    <script>
      function checkPassword(t, ele) {
        let myInput = t.value;
        let sibInput = document.getElementById(ele).value;
        let event_btn_password = document.getElementById('event_btn_password');
        let alert_txt_error = document.querySelector('.alert_txt_error');
        let event_password = document.getElementById('event_password');
        let event_confirm_password = document.getElementById('event_confirm_password');
        if (event_confirm_password.value != '' && event_password.value != '') {
          if (myInput != sibInput) {
            alert_txt_error.innerHTML = 'รหัสผ่านไม่ตรงกันโปรดตรวจสอบอีกครั้ง'
            event_btn_password.setAttribute('disabled', '1');
          } else {
            alert_txt_error.innerHTML = ''
            event_btn_password.removeAttribute('disabled');
          }
        } else if (event_password.value == '') {
          alert_txt_error.innerHTML = ''
        } else if (event_confirm_password.value == '') {
          alert_txt_error.innerHTML = ''
        }
      }

      function event_upload_image() {
        document.getElementById('scope_form_upload_image').submit();
      }

      function eventCanLogin(t) {
        let checked = t.checked ? 1 : 0;
        let url = `ajax/ajax_change_can_login.php?id=<?= $emp['id'] ?>&is_can_login=${checked}`;
        wx.get(url, function(res) {
          let result = JSON.parse(res);
          w.notification(result.response_status ? 'success' : 'error', result.response_message);
        })
      }
    </script>

</body>

</html>