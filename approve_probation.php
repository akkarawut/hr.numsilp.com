<?php
$user_permission['_permission'] = ['all', 'ywptz_user', 'niemz_user', 'bmiwk_user', 'noaxt_user', 'xacpn_user'];
require_once 'wloves/_framework/import_for_website.php';
require_once 'layout/init.php';
require_once 'component/navtab.php';
$name_page = 'อนุมัติผ่านการทดลองงาน';
$page = isset($_GET['page']) ? $_GET['page'] : '1';
$_GET['page'] = $page;

$status = '';
$type = '';
if ($page  == '1') {
  $status = 'waiting_confirm';
} else if ($page  == '2') {
  $status = 'approved_reject';
}

$count = phr_employee::countControlTrialEmployee(API_CODE);

$data_nav = [
  'param_name' => 'page',
  'nav_list' => [
    [
      'id' => '1',
      'name' => 'รายการรอประเมินทดลองงาน',
      'count' => [
        'number' =>  number_format($count['count_waiting_confirm'], 0),
        'class' => 'primary'
      ]
    ],
    [
      'id' => '2',
      'name' => 'ประวัติการดำเนินการ',
      'count' => [
        'number' =>  number_format($count['count_confirm'], 0),
        'class' => 'success'
      ]
    ],
  ]
];

if ($_POST) {
  if (isset($_POST['submit_approved_probation'])) {
    $data = [
      'user_id' => $current_user_id,
      'status' => 'approved',
      'is_working' => isset($_POST['is_working']) ? 1 : 0,
      'remark' => $_POST['remark'],
    ];
    $result = phr_employee::updateStatusTrialEmployeeByID(API_CODE, $_POST['id'], $data);
  } else if (isset($_POST['submit_probation_reject'])) {
    $data = [
      'user_id' => $current_user_id,
      'remark' => $_POST['remark'],
      'status' => 'reject',
    ];
    $result = phr_employee::updateStatusTrialEmployeeByID(API_CODE, $_POST['id'], $data);
  }
  W_Main::responseAPI(/*$result, $message, $redirect*/);
}
?>
<!-- MENU::อนุมัติผ่านการทดลองงาน -->
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
            <h1 class="title-header mb-10px">อนุมัติผ่านการทดลองงาน</h1>
            <p class="sub-title text-secondary">
              ประเมินผลการทดลองงานของพนักงาน
            </p>
          </div>
        </div>

        <?= navTab::normal($data_nav, ' ', 'mb-4'); ?>

        <div class="row my-4">
          <div class="col-lg-12">
            <div class="card-box-tbl">
              <div class="table-responsive mt-10px">
                <table id="table_approve_probation" class="table">
                  <thead>
                    <tr>
                      <th>พนักงาน</th>
                      <th>ฝ่าย</th>
                      <th>แผนก</th>
                      <th>ตำแหน่ง</th>
                      <th>วันที่เริ่มทดลองงาน</th>
                      <th>ถึงวันที่</th>
                      <th>ระยะเวลาทดลองงานรวม</th>
                      <th>Performance</th>
                      <?php if ($page == '2') { ?>
                        <th>ผลประเมิน</th>
                        <th>หมายเหตุ / เหตุผล</th>
                      <?php } else { ?>
                        <th class="thin-cell"></th>
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

      <?php Tiwdal::ajaxModal('approved_probation', 'modal-md modal-dialog-centered mx-auto  max-w-500px'); ?>

      <?php Tiwdal::startModal('approved_probation_reject', 'modal-md modal-dialog-centered mx-auto  max-w-500px'); ?>
      <form action="" method="post">
        <div class="modal-header justify-content-center">
          <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
            <span class="font-18px" aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body px-20px m-auto max-w-450px pt-0">
          <div class="font-24px font-SemiBold  text-center mb-15px"><?= Ty::get(['ไม่ผ่านการทดลองงาน', '']) ?></div>
          <div class="text-center mb-15px">
            <div>
              คุณแน่ใจหรือไม่ที่จะ <span class="text-danger">ไม่ให้ผ่านการทดลองงาน</span> แก่
            </div>
            <div class="fw-semibold mb-10px" name="{full_name}"></div>
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
                  <div class="input-title">สาเหตุที่ไม่ให้ผ่าน</div>
                  <textarea name="remark" class="input"></textarea>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <input type="hidden" name="{id}">
          <?php
          TiwForm::normal('btn', '', ['name' => '', 'type' => 'button', 'class' => 'btn-close-modal h-50px border-radius-15px', 'data-bs-dismiss' => 'modal'], ['text' => Ty::get(['ยกเลิก', ''])]);
          TiwForm::normal('btn', '', ['name' => 'submit_probation_reject', 'type' => 'submit', 'class' => 'btn-danger h-50px border-radius-15px'], ['text' => 'ไม่ผ่านการทดลองงาน']);
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
    var table_approve_probation = new DataTable(document.getElementById('table_approve_probation'), {
      ajax: {
        url: 'ajax/ajax_table_approve_probation.php',
        type: 'POST',
        data: function(d) {
          let info = table_approve_probation ?? '';
          let page_no = info ? info.page.info().page + 1 : 1;
          let page_size = info ? info.page.len() : 10;
          d.page_no = page_no;
          d.page_size = page_size;
          d.status = `<?= $status ?>`;
        }
      },
      columns: [{
          data: 'full_name',
          className: 'nowrap',
        },
        {
          data: 'department',
          className: 'nowrap',
          render: function(data, type, row, meta) {
            let html = ``;
            html = `<div>${data ? data : '-'}</div>`
            return html;
          }
        },
        {
          data: 'division',
          className: 'nowrap',
          render: function(data, type, row, meta) {
            let html = ``;
            html = `<div>${data ? data : '-'}</div>`
            return html;
          }
        },
        {
          data: 'position',
          className: 'nowrap',
          render: function(data, type, row, meta) {
            let html = ``;
            html = `<div>${data ? data : '-'}</div>`
            return html;
          }
        },
        {
          data: 'working_start_date',
          className: 'nowrap',
          render: function(data, type, row, meta) {
            let html = ``;
            html = `<div>${moment(data).format('LL')}</div>`
            return html;
          }
        },
        {
          data: 'working_end_date',
          className: 'nowrap',
          render: function(data, type, row, meta) {
            let html = ``;
            html = `<div>${moment(data).format('LL')}</div>`
            return html;
          }
        },
        {
          data: 'sum_day_trial_work',
          className: 'nowrap',
          render: function(data, type, row, meta) {
            let html = ``;
            html = `<div>${data} วัน</div>`
            return html;
          }
        },
        {
          data: 'percent_performance',
          className: 'nowrap',
          render: function(data, type, row, meta) {
            let html = ``;
            html = `<div class="${data >= 80 ? 'text-success' : 'text-danger'}">${data ? data : 0}%</div>`
            return html;
          }
        },
        <?php if ($page != '1') { ?> {
            data: 'trial_work_status',
            className: 'nowrap',
            render: function(data, type, row, meta) {
              let html = ``;
              let class_name = '';
              let status_name = '';
              if (data == 'approved') {
                status_name = 'ผ่าน';
                class_name = 'success'
              } else if (data == 'reject') {
                status_name = 'ไม่ผ่าน';
                class_name = 'danger'
              }
              html =
                `
              <div class="d-flex align-items-center">
                <span class="st-dot ${class_name} me-2"></span>
                <div class="text-${class_name}">${status_name}</div>
              </div>
                <div class="sub-text-tbl">
                ${row.trial_work_update_by_fullname ? `โดย ${row.trial_work_update_by_fullname}` : ''}
                ${row.trial_work_update_by_datetime ? w.formatDate(row.trial_work_update_by_datetime,'d/m/Y, H:i น.') : ''}
              </div>
              `;
              return html;
            }
          },
          {
            data: 'trial_work_update_by_remark',
            className: 'nowrap',
            render: function(data, type, row, meta) {
              let html = ``;
              html = `<div>${data}</div>`
              return html;
            }
          },
        <?php } ?>
        <?php if ($page == '1') { ?> {
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
                <button type="button" class="btn btn-icon-squere danger mr-10px" toggle-edit-modal="#approved_probation_reject" data-register="${json_data}">
                  <?= file_get_contents('assets/icons/times.svg') ?>
                </button>
                <button type="button" class="btn btn-icon-squere success" toggle-ajax-modal="#approved_probation" data-register="${json_data}" is_ajax="1">
                  <?= file_get_contents('assets/icons/check-success.svg') ?>
                </button>
              </div>
              `;
              return html;
            }
          },
          {
            data: 'id',
            className: 'nowrap',
            render: function(data, type, row, meta) {
              let html = `<a href="employee_detail.php?id=${data}&mode=probation">
                      <img src="assets/icons/arrow-right.svg" alt="">
                    </a>`
              return html;
            }
          },
        <?php } ?>
      ],
    });
  </script>
</body>

</html>