<?php
$user_permission['_permission'] = ['all', 'ywptz_user', 'niemz_user', 'bmiwk_user', 'noaxt_user', 'xacpn_user'];
require_once 'wloves/_framework/import_for_website.php';
require_once 'layout/init.php';
require_once 'component/navtab.php';
$name_page = 'อนุมัติการออกเงินเดือน/ค่าจ้าง';
$page = isset($_GET['page']) ? $_GET['page'] : 'month';
$_GET['page'] = $page;
$status = '';
$type = '';
if ($page  == 'month') {
  $status = 'waiting_confirm';
  $type = 'month';
} else if ($page  == 'day') {
  $status = 'waiting_confirm';
  $type = 'day';
} else if ($page  == 'history_month') {
  $status = 'history_month';
  $type = 'month';
} else if ($page  == 'history_day') {
  $status = 'history_day';
  $type = 'day';
}



$count = phr_salary::countControlSalary(API_CODE);

$data_nav = [
  'param_name' => 'page',
  'nav_list' => [
    [
      'id' => 'month',
      'name' => 'รายการเงินเดือนรออนุมัติ',
      'count' => [
        'number' =>  number_format($count['count_month_waiting_confirm'], 0),
        'class' => 'primary'
      ]
    ],
    [
      'id' => 'day',
      'name' => 'รายการค่าจ้างรายวันรออนุมัติ',
      'count' => [
        'number' =>  number_format($count['count_day_waiting_confirm'], 0),
        'class' => 'primary'
      ]
    ],
    [
      'id' => 'history_month',
      'name' => 'ประวัติการอนุมัติเงินเดือน',
      'count' => [
        'number' =>  number_format($count['count_month_confirm'], 0),
        'class' => 'success'
      ]
    ],
    [
      'id' => 'history_day',
      'name' => 'ประวัติการอนุมัติค่าจ้าง',
      'count' => [
        'number' =>  number_format($count['count_day_confirm'], 0),
        'class' => 'success'
      ]
    ],
  ]
];

if ($_POST) {
  if (isset($_POST['submit_payment'])) {
    $data = [
      'user_id' => $current_user_id,
      'ids' => $_POST['id'],
      'status' => 'approved',
    ];
    $result = phr_salary::updateStatusSalaryByIDS(API_CODE, $data);
    if ($result['response_status']) {
      $redirect = 'approve_payment.php?page=' . $page;
    }
  } else if (isset($_POST['submit_payment_reject'])) {
    $data = [
      'user_id' => $current_user_id,
      'ids' => $_POST['id'],
      'status' => 'reject',
      'remark' => $_POST['remark']
    ];
    $result = phr_salary::updateStatusSalaryByIDS(API_CODE, $data);
    if ($result['response_status']) {
      $redirect = 'approve_payment.php?page=' . $page;
    }
  } else if (isset($_POST['submit_payment_multiple'])) {
    $data = [
      'user_id' => $current_user_id,
      'ids' => explode(',', $_POST['ids']),
      'status' => 'approved',
    ];
    $result = phr_salary::updateStatusSalaryByIDS(API_CODE, $data);
    if ($result['response_status']) {
      $redirect = 'approve_payment.php?page=' . $page;
    }
  } else if (isset($_POST['submit_payment_reject_multiple'])) {
    $data = [
      'user_id' => $current_user_id,
      'ids' => explode(',', $_POST['ids']),
      'status' => 'reject',
      'remark' => $_POST['remark']
    ];
    $result = phr_salary::updateStatusSalaryByIDS(API_CODE, $data);
    if ($result['response_status']) {
      $redirect = 'approve_payment.php?page=' . $page;
    }
  }
  W_Main::responseAPI(/*$result, $message, $redirect*/);
}
?>
<!-- MENU::ตั้งค่า_อนุมัติการลงเวลา -->
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
  <link rel="icon" type="image/x-icon" href="assets/img/favicon.png">
</head>

<body>
  <?php include_once 'layout/navbar.php' ?>
  <div id="hr-body">
    <?php include_once 'layout/sidebar.php' ?>
    <div id="hr-content" class="bg-white">
      <div class="container-fluid mt-5">
        <div class="row align-items-end">
          <div class="col-md-12 mb-4">
            <h1 class="title-header mb-10px">อนุมัติการจ่ายเงิน</h1>
            <p class="sub-title text-secondary">รายการจ่ายเงินเดือนและค่าจ้างรายวันรออนุมัติจ่าย</p>
          </div>
        </div>

        <?= navTab::normal($data_nav, ' ', 'mb-4'); ?>

        <div class="row my-4">
          <div class="col-lg-12">
            <div class="card-box-tbl">

              <?php if ($page == 'month' || $page == 'day') { ?>
                <div id="event_muliple_select" class="d-flex align-items-center px-20px pt-10px">
                  <input type="hidden" name="ids" class="event_select_ids">
                  <div class="d-flex align-items-center">
                    <div class="me-3">
                      <?php
                      TiwForm::normal('checkbox', 1, ['name' => '', 'class' => 'event_unselected_row', 'checked' => 0, 'onchange' => 'checkAllRow(this);'], ['style' => '3', 'text_on' => 'Yes', 'is_on_off' => true, 'label' => '']);
                      ?>
                    </div>

                    <div class="text-secondary">
                      เลือกแล้ว <span class="count_select_item">0</span> รายการ :
                    </div>
                  </div>

                  <div class="d-flex ms-2">
                    <button type="button" class="btn btn-icon-squere danger mr-10px event_btn_approve" disabled <?= Tiwdal::register('approved_payment_reject_muliple', []) ?>>
                      <?= file_get_contents('assets/icons/times.svg') ?>
                    </button>
                    <button type="button" class="btn btn-icon-squere success event_btn_approve" disabled <?= Tiwdal::register('approved_payment_muliple', []) ?>>
                      <?= file_get_contents('assets/icons/check-success.svg') ?>
                    </button>
                  </div>
                </div>
              <?php } ?>

              <div class="table-responsive mt-10px">
                <table id="table_approve_payment" class="table">
                  <thead>
                    <tr>
                      <?php if ($page == 'month' || $page == 'day') { ?>
                        <th class="thin-cell"></th>
                      <?php } ?>
                      <th>ชื่อพนักงาน</th>
                      <th>บัญชี</th>
                      <th class="text-end"><?= $type == 'day' ? 'ค่าจ้าง' : 'เงินเดือน' ?> (บาท)</th>
                      <?php if ($page == 'month' || $page == 'history_month') { ?>
                        <th class="text-end">ประกันสังคม</th>
                      <?php } ?>
                      <th class="text-end">รายการหักค่าใช้จ่าย</th>
                      <th class="text-end">ยอดหักค่าใช้จ่ายรวม (บาท)</th>
                      <th class="text-end">ยอดที่ได้รับ (บาท)</th>
                      <?php if ($page != 'month' && $page != 'day') { ?>
                        <th>สถานะ</th>
                      <?php } else { ?>
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

      <?php Tiwdal::ajaxModal('approved_payment', 'modal-md modal-dialog-centered mx-auto  max-w-500px'); ?>
      <?php Tiwdal::ajaxModal('approved_payment_reject', 'modal-md modal-dialog-centered mx-auto  max-w-500px'); ?>


      <?php Tiwdal::startModal('approved_payment_muliple', 'modal-md modal-dialog-centered mx-auto  max-w-500px'); ?>
      <form action="" method="post">
        <div class="modal-header justify-content-center">
          <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
            <span class="font-18px" aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body px-20px m-auto max-w-450px pt-0">
          <div class="font-24px font-SemiBold  text-center mb-15px">อนุมัติการจ่าย<?= $type == 'day' ? 'ค่าจ้าง' : 'เงินเดือน' ?></div>
          <div class="text-center">
            <div class="max-w-380px mx-auto">
              คุณกำลังเลือก <span class="count_select_in_modal">0</span> รายการ คุณต้องการอนุมัติรายการที่เลือก ทั้งหมดหรือไม่
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <input type="hidden" name="ids" class="event_select_checkin_ids">
          <?php
          TiwForm::normal('btn', '', ['name' => '', 'type' => 'button', 'class' => 'btn-close-modal h-50px border-radius-15px', 'data-bs-dismiss' => 'modal'], ['text' => Ty::get(['ยกเลิก', ''])]);
          TiwForm::normal('btn', '', ['name' => 'submit_payment_multiple', 'type' => 'submit', 'class' => 'btn-success h-50px border-radius-15px'], ['text' => 'อนุมัติบันทึก <span class="count_select_in_modal mx-3px">0</span> รายการ']);
          ?>
        </div>
      </form>
      <?php Tiwdal::endModal(); ?>

      <?php Tiwdal::startModal('approved_payment_reject_muliple', 'modal-md modal-dialog-centered mx-auto  max-w-500px'); ?>
      <form action="" method="post">
        <div class="modal-header justify-content-center">
          <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
            <span class="font-18px" aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body px-20px m-auto max-w-450px pt-0">
          <div class="font-24px font-SemiBold  text-center mb-15px">ไม่อนุมัติจ่าย<?= $type == 'day' ? 'ค่าจ้าง' : 'เงินเดือน' ?></div>
          <div class="text-center mb-15px">
            <div>
              คุณกำลังเลือก <span class="count_select_in_modal">0</span> รายการ คุณต้องการ <span class="text-danger">ไม่อนุมัติ</span> รายการที่เลือก
              <br> ทั้งหมดหรือไม่
            </div>
          </div>

          <div class="row mb-5px">
            <div class="col-lg-12">
              <span class="fw-semibold">บันทึกเพิ่มเติม</span>
            </div>
          </div>

          <div class="row mb-20px">
            <div class="col-lg-12">
              <div class="hr-form-control">
                <div class="input-group">
                  <div class="input-title">สาเหตุที่ไม่อนุมัติ</div>
                  <textarea name="remark" class="input"></textarea>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <input type="hidden" name="ids" class="event_select_reject_ids">
          <?php
          TiwForm::normal('btn', '', ['name' => '', 'type' => 'button', 'class' => 'btn-close-modal h-50px border-radius-15px', 'data-bs-dismiss' => 'modal'], ['text' => Ty::get(['ยกเลิก', ''])]);
          TiwForm::normal('btn', '', ['name' => 'submit_payment_reject_multiple', 'type' => 'submit', 'class' => 'btn-danger h-50px border-radius-15px'], ['text' => 'ไม่อนุมัติบันทึก <span class="count_select_in_modal mx-3px">0</span> รายการ']);
          ?>
        </div>
      </form>
      <?php Tiwdal::endModal(); ?>

    </div>
  </div>

  <?php
  W_Structure::loadFooterForWebsite();
  W_Main::loadAsset('assets/js/main.js');
  W_Main::loadAsset('assets/js/datatable_option.js');
  ?>
  <?php require_once 'layout/footer_js.php'; ?>
  <script>
    var table_approve_payment = new DataTable(document.getElementById('table_approve_payment'), {
      ajax: {
        url: 'ajax/ajax_table_approve_payment.php',
        type: 'POST',
        data: function(d) {
          let info = table_approve_payment ?? '';
          let page_no = info ? info.page.info().page + 1 : 1;
          let page_size = info ? info.page.len() : 10;
          d.page_no = page_no;
          d.page_size = page_size;
          d.status = `<?= $status ?>`;
          d.type = `<?= $type ?>`;
        }
      },
      columns: [
        <?php if ($page == 'month' || $page == 'day') { ?> {
            data: 'id',
            className: 'nowrap',
            render: function(data, type, row, meta) {
              let html = ``;
              html = `
            <div class="checkbox-group event_checkbox">
              <input type="checkbox" name="" value="${data}" id="chk_id_${data}" onchange="selectRows(this);">
              <label class="mb-0" for="chk_id_${data}"></label>
            </div>
              `
              return html;
            }
          },
        <?php } ?> {
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
        <?php if ($page == 'month' || $page == 'history_month') { ?> {
            data: 'security_price',
            className: 'nowrap text-end',
            render: function(data, type, row, meta) {
              if (row.is_security != 0) {
                return `<div class="nowrap ${data > 0 ? 'text-danger' : ''}">${data > 0 ? '-'+ w.formatNumber(data, 2) : data}</div>`
              } else {
                return '<div class="nowrap">ไม่หัก</div>'
              }
            }
          },
        <?php } ?> {
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
            return `<div class="nowrap ${data > 0 ? 'text-danger' : ''}"> ${data > 0 ? '-'+ w.formatNumber(data, 2) : 0}</div>`
          }
        },
        {
          data: 'total_price',
          className: 'nowrap text-end',
          render: function(data, type, row, meta) {
            return '<div class="nowrap">' + w.formatNumber(data, 2) + '</div>'
          }
        },
        <?php if ($page != 'month' && $page != 'day') { ?> {
            data: 'status',
            className: 'nowrap',
            render: function(data, type, row, meta) {
              let html = ``;
              let class_name = '';
              let status_name = '';
              if (data == 'day') {
                status_name = 'รออนุมัติบันทึก...';
                class_name = 'dark'
              } else if (data == 'approved') {
                status_name = 'อนุมัติแล้ว';
                class_name = 'success'
              } else if (data == 'reject') {
                status_name = 'ไม่อนุมัติ';
                class_name = 'danger'
              }
              if (data != '') {
                html =
                  `
              <div class="d-flex align-items-center">
                <span class="st-dot ${class_name} me-2"></span>
                <div class="text-${class_name}">${status_name}</div>
              </div>
              `;
              } else {
                html = `  <div>${status_name}</div>`;
              }
              return html;
            }
          },
        <?php } ?>
        <?php if ($page == 'month' || $page == 'day') { ?> {
            data: 'id',
            className: 'nowrap',
            render: function(data, type, row, meta) {
              let html = ``;
              let json = {
                'id': data,
                'type': `<?= $type ?>`,
              };
              let id = btoa(JSON.stringify(json));
              html = `
              <div class="d-flex">
                <button type="button" class="btn btn-icon-squere danger mr-10px" toggle-ajax-modal="#approved_payment_reject" data-register="${id}" is_ajax="1">
                  <?= file_get_contents('assets/icons/times.svg') ?>
                </button>
                <button type="button" class="btn btn-icon-squere success" toggle-ajax-modal="#approved_payment" data-register="${id}" is_ajax="1">
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
            let html = `<a href="approve_payment_detail.php?id=${data}&type=<?= $page ?>">
                      <img src="assets/icons/arrow-right.svg" alt="">
                    </a>`
            return html;
          }
        },
      ],
    });

    function selectRows(t) {
      let scope = t.closest('#table_approve_payment');
      let checkbox = scope.querySelectorAll('.event_checkbox input:checked');
      let count_select_item = document.querySelector('.count_select_item');
      let input_ids = document.querySelector('.event_select_ids');
      let checkbox_unselect = document.querySelector('.event_unselected_row input');
      let event_btn_approve = document.querySelectorAll('.event_btn_approve');

      let ids = [];
      if (t.checked) {
        t.closest('tr').classList.add('bg-c-1');
      } else {
        t.closest('tr').classList.remove('bg-c-1');
      }
      count_select_item.innerHTML = checkbox.length;
      if (checkbox.length > 0) {
        checkbox_unselect.checked = true;
        event_btn_approve.forEach(e => {
          e.removeAttribute('disabled');
        });
      } else {
        checkbox_unselect.checked = false;
        event_btn_approve.forEach(e => {
          e.setAttribute('disabled', '');
        });
      }
      checkbox.forEach(e => {
        let id = e.value;
        ids.push(id);
        input_ids.value = ids;
      });
    }

    function checkAllRow(t) {
      let scope_tbl = document.getElementById('table_approve_payment');
      let tr_tbl = scope_tbl.querySelectorAll('tr');
      let event_btn_approve = document.querySelectorAll('.event_btn_approve');
      let checkbox = document.querySelectorAll('.event_checkbox input');
      let count_select_item = document.querySelector('.count_select_item');
      let input_ids = document.querySelector('.event_select_ids');
      let checkbox_unselect = document.querySelector('.event_unselected_row input');
      let ids = [];
      if (t.checked) {
        checkbox.forEach(e => {
          let id = e.value;
          e.checked = true;
          ids.push(id);
          input_ids.value = ids;
          event_btn_approve.forEach(btn => {
            btn.removeAttribute('disabled');
          });
        });
        tr_tbl.forEach(e => {
          e.classList.add('bg-c-1');
        });
      } else {
        checkbox.forEach(e => {
          e.checked = false;
          input_ids.value = '';
          event_btn_approve.forEach(btn => {
            btn.setAttribute('disabled', '');
          });
        });
        tr_tbl.forEach(e => {
          e.classList.remove('bg-c-1');
        });
      }
      let is_checkbox = document.querySelectorAll('.event_checkbox input:checked');
      count_select_item.innerHTML = is_checkbox.length;
    }

    function countSelected(id, ele) {
      let count_select_item = document.querySelector('.count_select_item');
      let scope = document.getElementById(id);
      let e = scope.querySelectorAll(ele);
      e.forEach(element => {
        element.innerHTML = count_select_item.innerHTML;
      });
    }

    document.getElementById('approved_payment_muliple').addEventListener('show.bs.modal', function(event) {
      countSelected('approved_payment_muliple', '.count_select_in_modal');
      let event_select_ids = document.querySelector('.event_select_ids');
      document.querySelector('.event_select_checkin_ids').value = event_select_ids.value;
    })

    document.getElementById('approved_payment_reject_muliple').addEventListener('show.bs.modal', function(event) {
      countSelected('approved_payment_reject_muliple', '.count_select_in_modal');
      let event_select_ids = document.querySelector('.event_select_ids');
      document.querySelector('.event_select_reject_ids').value = event_select_ids.value;
    })

    w(document).on('change', '.event_change_filter', function() {
      w(this).parents('form').submit();
    });

    w(document).on('change', '.event_change_filter_date', function() {
      let str = ' - ';
      let date = $(this).val();
      if (date.search(str) != -1) {
        w(this).parents('form').submit();
      }
    });
  </script>
</body>

</html>