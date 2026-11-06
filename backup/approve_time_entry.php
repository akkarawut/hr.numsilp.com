<?php
$user_permission['_permission'] = ['all', 'ywptz_user', 'niemz_user', 'bmiwk_user', 'noaxt_user', 'xacpn_user'];
require_once 'wloves/_framework/import_for_website.php';
require_once 'layout/init.php';
require_once 'component/navtab.php';

$name_page = 'อนุมัติการลงเวลา';

$page = isset($_GET['page']) ? $_GET['page'] : 'waiting_confirm';
$filter_status = isset($_GET['filter_status']) ? $_GET['filter_status'] : '';
$filter_date = isset($_GET['filter_date']) ? $_GET['filter_date']  : '';
$filter_from_date = '';
$filter_to_date = '';
if ($filter_date) {
  $date_explode = explode(" - ", $filter_date);
  $from_date        = isset($date_explode[0]) ? $date_explode[0] : Date($date_format, strtotime(date('Y-m-d') . " -7 Day"));
  $to_date          = isset($date_explode[1]) ? $date_explode[1] : date($date_format);
  $filter_from_date = explode("/", $from_date)[2] . '/' . explode("/", $from_date)[1] . '/' . explode("/", $from_date)[0];
  $filter_to_date   = explode("/", $to_date)[2] . '/' . explode("/", $to_date)[1] . '/' . explode("/", $to_date)[0];
}
$_GET['page'] = $page;
$status = '';
$type = '';
if ($page  == 'waiting_confirm') {
  $status = 'waiting_confirm';
  $type = 'on_time';
} else if ($page  == 'waiting_confirm_late') {
  $status = 'waiting_confirm';
  $type = 'late';
} else if ($page  == 'approved') {
  $status = 'approved';
} else if ($page  == 'reject') {
  $status = 'reject';
}

$dropdown_status = [
  'normal' => 'เข้างานตรงเวลา',
  'late' => 'เข้างานสาย',
  'very_late' => 'เข้างานสายมาก',
  'leave_work' => 'ลางาน',
  'missing' => 'ขาดงาน',

];


$count = phr_checkin::countControlCheckIn(API_CODE);

$data_nav = [
  'param_name' => 'page',
  'nav_list' => [
    [
      'id' => 'waiting_confirm',
      'name' => 'รายการรออนุมัติ',
      'count' => [
        'number' =>  number_format($count['count_waiting_confirm'], 0),
        'class' => 'primary'
      ]
    ],
    [
      'id' => 'waiting_confirm_late',
      'name' => 'รายการบันทึกย้อนหลัง',
      'count' => [
        'number' =>  number_format($count['count_waiting_confirm_late'], 0),
        'class' => 'warning'
      ]
    ],
    [
      'id' => 'approved',
      'name' => 'รายการที่อนุมัติแล้ว',
      'count' => [
        'number' =>  number_format($count['count_approved'], 0),
        'class' => 'success'
      ]
    ],
    [
      'id' => 'reject',
      'name' => 'รายการที่ไม่อนุมัติ',
      'count' => [
        'number' =>  number_format($count['count_reject'], 0),
        'class' => 'danger'
      ]
    ],
  ]
];

if ($_POST) {
  if (isset($_POST['submit_approved_checkin'])) {
    $data = [
      'user_id' => $current_user_id,
      'ids' => $_POST['id'],
      'status' => 'approved',
    ];
    $result = phr_checkin::updateStatusCheckInByIDS(API_CODE, $data);
    if ($result['response_status']) {
      $redirect = 'approve_time_entry.php?page=' . $page;
    }
  } else if (isset($_POST['submit_approved_reject'])) {
    $data = [
      'user_id' => $current_user_id,
      'ids' => $_POST['id'],
      'remark' => $_POST['remark'],
      'status' => 'reject',
    ];
    $result = phr_checkin::updateStatusCheckInByIDS(API_CODE, $data);
    if ($result['response_status']) {
      $redirect = 'approve_time_entry.php?page=' . $page;
    }
  } else if (isset($_POST['submit_approved_checkin_multiple'])) {
    $data = [
      'user_id' => $current_user_id,
      'ids' => explode(',', $_POST['ids']),
      'status' => 'approved',
    ];
    $result = phr_checkin::updateStatusCheckInByIDS(API_CODE, $data);
    if ($result['response_status']) {
      $redirect = 'approve_time_entry.php?page=' . $page;
    }
  } else if (isset($_POST['submit_approved_reject_multiple'])) {
    $data = [
      'user_id' => $current_user_id,
      'ids' => explode(',', $_POST['ids']),
      'remark' => $_POST['remark'],
      'status' => 'reject',
    ];
    $result = phr_checkin::updateStatusCheckInByIDS(API_CODE, $data);
    if ($result['response_status']) {
      $redirect = 'approve_time_entry.php?page=' . $page;
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
  <?php include_once 'layout/favicon.php' ?>
</head>

<body>
  <?php include_once 'layout/navbar.php' ?>
  <div id="hr-body">
    <?php include_once 'layout/sidebar.php' ?>
    <div id="hr-content" class="bg-white">
      <div class="container-fluid mt-5">
        <div class="row align-items-end">
          <div class="col-md-12 mb-4">
            <h1 class="title-header mb-10px">อนุมัติการลงเวลา</h1>
            <p class="sub-title text-secondary">การลงเวลาของพนักงานจะยังอยู่ในสถานะรออนุมัติจนกว่าจะได้รับการอนุมัติ</p>
          </div>
        </div>

        <?= navTab::normal($data_nav, ' ', 'mb-4'); ?>

        <div class="row my-4">
          <div class="col-lg-12">
            <div class="card-box-tbl">

              <?php if ($page == 'waiting_confirm' || $page == 'waiting_confirm_late') { ?>
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
                    <button type="button" class="btn btn-icon-squere danger mr-10px event_btn_approve" disabled <?= Tiwdal::register('approved_record_reject_muliple', []) ?>>
                      <?= file_get_contents('assets/icons/times.svg') ?>
                    </button>
                    <button type="button" class="btn btn-icon-squere success event_btn_approve" disabled <?= Tiwdal::register('approved_record_checkin_muliple', []) ?>>
                      <?= file_get_contents('assets/icons/check-success.svg') ?>
                    </button>
                  </div>
                </div>
              <?php } else { ?>

                <form id="scope_form_filter" method="get">
                  <div class="form-filter-tbl mt-4">
                    <div class="row">
                      <input type="hidden" name="page" value="<?= $page ?>">
                      <div class="col-lg-3 mb-10px">
                        <div class="d-flex align-items-center">
                          <div class="me-2 text-secondary nowrap">สถานะการเข้างาน :</div>
                          <div class="hr-form-control w-100 pt-0">
                            <div class="input-group">
                              <select name="filter_status" class="input event_change_filter">
                                <option value="" <?= $filter_status == '' ?  'selected' : '' ?>>ทั้งหมด</option>
                                <?php foreach ($dropdown_status as $key => $val) { ?>
                                  <option value="<?= $key ?>" <?= $filter_status == $key ?  'selected' : '' ?>><?= $val ?></option>
                                <?php } ?>
                              </select>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="col-lg-2 mb-10px">
                        <?php
                        $date_filter = '';
                        if ($filter_from_date && $filter_to_date) {
                          $date_filter = $filter_from_date . ' to ' . $filter_to_date;
                        }
                        ?>
                        <div class="d-flex align-items-center">
                          <div class="me-2 text-secondary nowrap">วันที่ :</div>
                          <div class="hr-form-control w-100 pt-0">
                            <div class="input-group">
                              <?= TiwForm::normal('daterange', $date_filter, ['name' => 'filter_date', 'class' => 'input event_change_filter_date'], []) ?>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>


                  </div>
                </form>
              <?php } ?>

              <div class="table-responsive mt-10px">
                <table id="table_time_entry" class="table">
                  <thead>
                    <tr>
                      <?php if ($page == 'waiting_confirm' || $page == 'waiting_confirm_late') { ?>
                        <th class="thin-cell"></th>
                      <?php } ?>
                      <th>พนักงาน</th>
                      <th>วันที่</th>
                      <th>เวลา,สถานที่เข้างาน</th>
                      <?php if ($page != 'waiting_confirm') { ?>
                        <th>เวลา,สถานที่ออกงาน</th>
                      <?php } ?>
                      <th>สถานะการเข้างาน</th>
                      <?php if ($page != 'waiting_confirm') { ?>
                        <th>เหตุผล</th>
                      <?php } ?>
                      <th>สถานะบันทึก</th>
                      <?php if ($page == 'waiting_confirm' || $page == 'waiting_confirm_late') { ?>
                        <th class="thin-cell"></th>
                      <?php } ?>
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

      <?php Tiwdal::ajaxModal('approved_record_checkin', 'modal-md modal-dialog-centered mx-auto  max-w-500px'); ?>
      <?php Tiwdal::ajaxModal('approved_record_reject', 'modal-md modal-dialog-centered mx-auto  max-w-500px'); ?>


      <?php Tiwdal::startModal('approved_record_checkin_muliple', 'modal-md modal-dialog-centered mx-auto  max-w-500px'); ?>
      <form action="" method="post">
        <div class="modal-header justify-content-center">
          <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
            <span class="font-18px" aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body px-20px m-auto max-w-450px pt-0">
          <div class="font-24px font-SemiBold  text-center mb-15px"><?= Ty::get(['อนุมัติการบันทึกเวลาเข้างาน', '']) ?></div>
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
          TiwForm::normal('btn', '', ['name' => 'submit_approved_checkin_multiple', 'type' => 'submit', 'class' => 'btn-success h-50px border-radius-15px'], ['text' => 'อนุมัติบันทึก <span class="count_select_in_modal mx-3px">0</span> รายการ']);
          ?>
        </div>
      </form>
      <?php Tiwdal::endModal(); ?>

      <?php Tiwdal::startModal('approved_record_reject_muliple', 'modal-md modal-dialog-centered mx-auto  max-w-500px'); ?>
      <form action="" method="post">
        <div class="modal-header justify-content-center">
          <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
            <span class="font-18px" aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body px-20px m-auto max-w-450px pt-0">
          <div class="font-24px font-SemiBold  text-center mb-15px"><?= Ty::get(['ไม่อนุมัติการบันทึกเวลาเข้างาน', '']) ?></div>
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
          TiwForm::normal('btn', '', ['name' => 'submit_approved_reject_multiple', 'type' => 'submit', 'class' => 'btn-danger h-50px border-radius-15px'], ['text' => 'ไม่อนุมัติบันทึก <span class="count_select_in_modal mx-3px">0</span> รายการ']);
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
    var table_time_entry = new DataTable(document.getElementById('table_time_entry'), {
      ajax: {
        url: 'ajax/ajax_table_time_entry.php',
        type: 'POST',
        data: function(d) {
          let info = table_time_entry ?? '';
          let page_no = info ? info.page.info().page + 1 : 1;
          let page_size = info ? info.page.len() : 10;
          d.page_no = page_no;
          d.page_size = page_size;
          d.status = `<?= $status ?>`;
          d.type = `<?= $type ?>`;
          d.filter_status = `<?= $filter_status ?>`;
          d.filter_from_date = `<?= $filter_from_date ?>`;
          d.filter_to_date = `<?= $filter_to_date ?>`;
        }
      },
      columns: [
        <?php if ($page == 'waiting_confirm' || $page == 'waiting_confirm_late') { ?> {
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
          data: 'date',
          className: 'nowrap',
          render: function(data, type, row, meta) {
            let html = ``;
            if (row.is_today) {
              html = `
              <div>${moment(data).format('LL')}</div>
              <div class="sub-text-tbl text-primary">วันนี้, ${row.day_name_th}</div>
              `
            } else {
              html = `
              <div>${moment(data).format('LL')}</div>
              <div class="sub-text-tbl text-secondary">${row.day_name_th}</div>
              `
            }
            return html;
          }
        },
        {
          data: 'checkin_datetime',
          className: 'nowrap',
          render: function(data, type, row, meta) {
            if (data && row.status_checkin != 'leave_work') {
              html = `
              <div>${w.formatDate(data,'H:i')} น.</div>
              <div class="sub-text-tbl text-secondary">${row.checkin_branch_name}</div>
              `
            } else {
              html = `<div>--:--</div>`
            }
            return html;
          }
        },
        <?php if ($page != 'waiting_confirm') { ?> {
            data: 'checkout_datetime',
            className: 'nowrap',
            render: function(data, type, row, meta) {
              if (data && row.status_checkin != 'leave_work') {
                html = `
              <div>${w.formatDate(data,'H:i')} น.</div>
              <div class="sub-text-tbl text-secondary">${row.checkout_branch_name}</div>
              `
              } else {
                html = `<div>--:--</div>`
              }
              return html;
            }
          },
        <?php } ?> {
          data: 'status_checkin',
          className: 'nowrap',
          render: function(data, type, row, meta) {
            let class_name = '';
            let status_name = '';
            if (data == 'normal') {
              class_name = 'success'
              status_name = 'เข้างานตรงเวลา';
            } else if (data == 'late') {
              class_name = 'warning'
              status_name = 'เข้างานสาย';
            } else if (data == 'very_late') {
              class_name = 'warning'
              status_name = 'เข้างานสายมาก';
            } else if (data == 'leave_work') {
              class_name = 'secondary'
              status_name = 'ลางาน';
            } else if (data == 'missing') {
              class_name = 'danger'
              status_name = 'ขาดงาน';
            }
            return `
              <div class="d-flex align-items-center">
                <span class="st-dot ${class_name} me-2"></span>
                <div class="text-${class_name}">${status_name}</div>
              </div>   `
          }
        },
        <?php if ($page != 'waiting_confirm') { ?> {
            data: 'remark',
            className: 'nowrap',
          },
        <?php } ?> {
          data: 'status_confirm',
          className: 'nowrap',
          render: function(data, type, row, meta) {
            let html = ``;
            let class_name = '';
            let status_name = '';
            if (data == 'waiting_confirm') {
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

            if (data) {
              html += `
            
                <div class="sub-text-tbl">
                ${row.status_confirm_update_by_fullname ? `โดย ${row.status_confirm_update_by_fullname}` : ''}
                ${row.status_confirm_update_datetime ? w.formatDate(row.status_confirm_update_datetime,'d/m/Y, H:i น.') : ''}
              </div>
              `;
            }
            return html;
          }
        },
        <?php if ($page == 'waiting_confirm' || $page == 'waiting_confirm_late') { ?> {
            data: 'id',
            className: 'nowrap',
            render: function(data, type, row, meta) {
              let html = ``;
              let json = {
                'id': data
              };
              let id = btoa(JSON.stringify(json));
              html = `
              <div class="d-flex">
                <button type="button" class="btn btn-icon-squere danger mr-10px" toggle-ajax-modal="#approved_record_reject" data-register="${id}" is_ajax="1">
                  <?= file_get_contents('assets/icons/times.svg') ?>
                </button>
                <button type="button" class="btn btn-icon-squere success" toggle-ajax-modal="#approved_record_checkin" data-register="${id}" is_ajax="1">
                  <?= file_get_contents('assets/icons/check-success.svg') ?>
                </button>
              </div>
              `;
              return html;
            }
          },
        <?php } ?>
      ],
      createdRow: function(row, data, dataIndex) {
        if (data.status_checkin == 'missing') {
          $(row).addClass('bg-c-3');
        } else if (data.status_checkin == 'late' || data.status_checkin == 'very_late') {
          $(row).addClass('bg-c-4');
        }
      },
    });

    function selectRows(t) {
      let scope = t.closest('#table_time_entry');
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
      let scope_tbl = document.getElementById('table_time_entry');
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

    document.getElementById('approved_record_checkin_muliple').addEventListener('show.bs.modal', function(event) {
      countSelected('approved_record_checkin_muliple', '.count_select_in_modal');
      let event_select_ids = document.querySelector('.event_select_ids');
      document.querySelector('.event_select_checkin_ids').value = event_select_ids.value;
    })

    document.getElementById('approved_record_reject_muliple').addEventListener('show.bs.modal', function(event) {
      countSelected('approved_record_reject_muliple', '.count_select_in_modal');
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