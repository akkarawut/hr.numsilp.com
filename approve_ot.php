<?php
$user_permission['_permission'] = ['all', 'ywptz_user', 'niemz_user', 'bmiwk_user', 'noaxt_user', 'xacpn_user'];
require_once 'wloves/_framework/import_for_website.php';
require_once 'layout/init.php';
require_once 'component/navtab.php';
$name_page = 'อนุมัติโอที';
$page = isset($_GET['page']) ? $_GET['page'] : 'waiting_confirm';
$_GET['page'] = $page;

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

$status = '';
$type = '';
if ($page  == 'waiting_confirm') {
  $status = 'waiting_confirm';
} else if ($page  == 'approved') {
  $status = 'approved';
} else if ($page  == 'reject') {
  $status = 'reject';
}

$count = phr_ot::countControlOT(API_CODE);

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
  if (isset($_POST['submit_approved_ot'])) {
    $data = [
      'user_id' => $current_user_id,
      'status' => 'approved',
      'setting_ot_id' => $_POST['setting_ot_id'],
    ];
    $result = phr_ot::updateStatusOTByID(API_CODE, $_POST['id'], $data);
    // if ($result['response_status']) {
    //   $redirect = 'approve_ot_detail.php?page=waiting_confirm';
    // }
  } else if (isset($_POST['submit_ot_reject'])) {
    $data = [
      'user_id' => $current_user_id,
      'remark' => $_POST['remark'],
      'status' => 'reject',
    ];
    $result = phr_ot::updateStatusOTByID(API_CODE, $_POST['id'], $data);
    // if ($result['response_status']) {
    //   $redirect = 'approve_ot_detail.php?page=waiting_confirm';
    // }
  }
  W_Main::responseAPI(/*$result, $message, $redirect*/);
}
?>
<!-- MENU::อนุมัติโอที -->
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
            <h1 class="title-header mb-10px">อนุมัติการทำงานล่วงเวลา</h1>
            <p class="sub-title text-secondary">หลังจากอนุมัติคำขอทำงานล่วงเวลา ระบบจะเริ่มคำนวณยอดที่พนักงานจะได้รับจากการทำงานล่วงเวลาให้</p>
          </div>
        </div>

        <?= navTab::normal($data_nav, ' ', 'mb-4'); ?>

        <div class="row my-4">
          <div class="col-lg-12">
            <div class="card-box-tbl">

              <?php if ($page != 'waiting_confirm') { ?>
                <form id="scope_form_filter" method="get">
                  <?php
                  $date_filter = '';
                  if ($filter_from_date && $filter_to_date) {
                    $date_filter = $filter_from_date . ' to ' . $filter_to_date;
                  }
                  ?>
                  <div class="form-filter-tbl mt-4">
                    <input type="hidden" name="page" value="<?= $page ?>">
                    <div class="row">
                      <div class="col-lg-3 mb-10px">
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
                <table id="table_approve_ot" class="table">
                  <thead>
                    <tr>
                      <th>หมายเลขคำขอ</th>
                      <th>พนักงาน</th>
                      <th>วันที่ยื่นคำขอ</th>
                      <th>วันที่, เวลาที่ขอทำงานล่วงเวลา</th>
                      <?php if ($page == 'approved') { ?>
                        <th>ค่า OT ที่คาดว่าจะได้รับ</th>
                      <?php } ?>
                      <th>เหตุผล</th>
                      <th>สถานะคำขอ</th>
                      <?php if ($page == 'waiting_confirm') { ?>
                        <th class="thin-cell"></th>
                      <?php } ?>
                      <?php if ($page == 'reject') { ?>
                        <th>เหตุผลที่ไม่อนุมัติ</th>
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

      <?php Tiwdal::ajaxModal('approved_ot', 'modal-md modal-dialog-centered mx-auto  max-w-500px'); ?>

      <?php Tiwdal::startModal('approved_ot_reject', 'modal-md modal-dialog-centered mx-auto  max-w-500px'); ?>
      <form action="" method="post">
        <div class="modal-header justify-content-center">
          <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
            <span class="font-18px" aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body px-20px m-auto max-w-450px pt-0">
          <div class="font-24px font-SemiBold  text-center mb-15px"><?= Ty::get(['ไม่อนุมัติการขอทำงานล่วงเวลา', '']) ?></div>
          <div class="text-center mb-15px">
            <div>
              คุณแน่ใจหรือไม่ที่จะ <span class="text-danger">ไม่อนุมัติ</span> การขอทำงานล่วงเวลาของ
            </div>
            <div class="fw-semibold mb-10px" name="{user_fullname}"></div>
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
          <input type="hidden" name="{id}">
          <?php
          TiwForm::normal('btn', '', ['name' => '', 'type' => 'button', 'class' => 'btn-close-modal h-50px border-radius-15px', 'data-bs-dismiss' => 'modal'], ['text' => Ty::get(['ยกเลิก', ''])]);
          TiwForm::normal('btn', '', ['name' => 'submit_ot_reject', 'type' => 'submit', 'class' => 'btn-danger h-50px border-radius-15px'], ['text' => 'ไม่อนุมัติคำขอ']);
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
  W_Main::loadAsset('assets/js/approve_ot.js');
  ?>
  <?php require_once 'layout/footer_js.php'; ?>
  <script>
    var table_approve_ot = new DataTable(document.getElementById('table_approve_ot'), {
      ajax: {
        url: 'ajax/ajax_table_approve_ot.php',
        type: 'POST',
        data: function(d) {
          let info = table_approve_ot ?? '';
          let page_no = info ? info.page.info().page + 1 : 1;
          let page_size = info ? info.page.len() : 10;
          d.page_no = page_no;
          d.page_size = page_size;
          d.status = `<?= $status ?>`;
          d.filter_from_date = `<?= $filter_from_date ?>`;
          d.filter_to_date = `<?= $filter_to_date ?>`;
        }
      },
      columns: [{
          data: 'no',
          className: 'nowrap',
        }, {
          data: 'user_fullname',
          className: 'nowrap',
        },
        {
          data: 'insert_date_time',
          className: 'nowrap',
          render: function(data, type, row, meta) {
            let html = ``;
            html = `
              <div>${moment(data).format('LL')}</div>
              <div class="sub-text-tbl">
              ${moment(data).format('HH:mm')} น.
              </div>
              `
            return html;
          }
        },
        {
          data: 'date',
          className: 'nowrap',
          render: function(data, type, row, meta) {
            let html = ``;
            html = `
              <div>${moment(data).format('LL')}</div>
              <div class="sub-text-tbl">
              ${row.from_time} - ${row.to_time} , ${row.hour_total} ชั่วโมง
              </div>
              `
            return html;
          }
        },
        <?php if ($page == 'approved') { ?> {
            data: 'total_price',
            className: 'nowrap',
            render: function(data, type, row, meta) {
              let html = `${w.formatNumber(data,2)} บาท`
              return html;
            }
          },
        <?php } ?> {
          data: 'remark',
          className: 'nowrap',
          render: function(data, type, row, meta) {
            return data ? data : '-';
          }
        },
        {
          data: 'status_confirm',
          className: 'nowrap',
          render: function(data, type, row, meta) {
            let html = ``;
            let class_name = '';
            let status_name = '';
            if (data == 'waiting_confirm') {
              status_name = 'รออนุมัติ';
              class_name = 'secondary'
            } else if (data == 'approved') {
              status_name = 'อนุมัติแล้ว';
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
              `;
            if (data != 'waiting_confirm') {
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
        <?php if ($page == 'waiting_confirm') { ?> {
            data: 'id',
            className: 'nowrap',
            render: function(data, type, row, meta) {
              let html = ``;
              let json = {
                'id': data,
                'user_fullname': row.user_fullname,
              };
              let json_data = btoa(unescape(encodeURIComponent(JSON.stringify(json))));
              html = `

              <div class="d-flex">
                <button type="button" class="btn btn-icon-squere danger mr-10px" toggle-edit-modal="#approved_ot_reject" data-register="${json_data}">
                  <?= file_get_contents('assets/icons/times.svg') ?>
                </button>
                <button type="button" class="btn btn-icon-squere success" toggle-ajax-modal="#approved_ot" data-register="${json_data}" is_ajax="1">
                  <?= file_get_contents('assets/icons/check-success.svg') ?>
                </button>
              </div>
              `;
              return html;
            }
          },
        <?php } else if ($page == 'reject') { ?> {
            data: 'status_confirm_update_remark',
            className: 'nowrap',
            render: function(data, type, row, meta) {
              let html = `${data} `
              return html;
            }
          },
        <?php } ?> {
          data: 'id',
          className: 'nowrap',
          render: function(data, type, row, meta) {
            let html = `<a href="approve_ot_detail.php?id=${data}">
                      <img src="assets/icons/arrow-right.svg" alt="">
                    </a>`
            return html;
          }
        },

      ],
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