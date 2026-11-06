<?php
$user_permission['_permission'] = ['all', 'ywptz_user', 'niemz_user', 'bmiwk_user', 'noaxt_user', 'xacpn_user'];
require_once 'wloves/_framework/import_for_website.php';
require_once 'layout/init.php';
$name_page = 'บันทึกเข้า - ออกงาน';
$count_emp = phr_employee::countEmployee(API_CODE);
$page_size = isset($_GET['page_size']) ? $_GET['page_size'] : 10;
$month_filter = isset($_GET['month_filter']) ? $_GET['month_filter'] : '';

$user_status_checkin = $user_info['checkin_status'];
$text_btn = '';
if ($user_status_checkin == 'checkin') {
  $text_btn = 'ลงบันทึกเข้างาน';
} else if ($user_status_checkin == 'checkout') {
  $text_btn = 'ลงบันทึกออกงาน';
} else if ($user_status_checkin == 'success') {
  $text_btn = 'ลงบันทึกเข้างาน';
}
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

$branch_dropdown = phr_helper::dropdownBranch(API_CODE);
$month_dropdown = phr_helper::dropdownMonthCheckIn(API_CODE);

if ($_POST) {
  if (isset($_POST['submit_record_checkin'])) {
    unset($_POST['submit_record_checkin']);
    $data = [
      'user_id' => $_POST['id'],
      'date' => $_POST['date'],
      'time' => $_POST['time'],
      'branch_id' =>  $_POST['branch_id'],
    ];
    if ($_POST['type'] == 'on_time') {
      $data['type'] = 'on_time';
    } else if ($_POST['type'] == 'late') {
      $data['time_end'] = $_POST['time_end'];
      $data['type'] = 'late';
      $data['remark'] = $_POST['remark'];
    }
    $result  = phr_checkin::userCheckInOut(API_CODE, $data);
    if ($result['response_status']) {
      $redirect = 'checkin_checkout.php';
    }
  }
  W_Main::responseAPI(/*$result, $message, $redirect*/);
}

?>
<!-- MENU::ตั้งค่า_บันทึกเข้า - ออกงาน -->
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
          <div class="col-md-9">
            <h1 class="title-header mb-10px">บันทึกเข้า - ออกงานของฉัน</h1>
            <p class="sub-title text-secondary">
              บันทึกการลงเวลาเข้า - ออกงานของฉันย้อนหลัง, ข้อมูลแสดงเฉพาะวันที่คุณต้องเข้างาน
            </p>
          </div>
          <div class="col-md-3">
            <div class="d-flex justify-content-end">
              <div class="dropdown dropdown-c">
                <button class="btn btn-primary btn-dropdown dropdown-toggle" type="button" id="dropdown_record_work" data-bs-toggle="dropdown" aria-expanded="false">
                  <span class="txt"><?= $text_btn ?></span>
                  <span class="icon">
                    <?= file_get_contents('assets/icons/arrow-dropdown.svg') ?>
                  </span>
                </button>
                <ul class="dropdown-menu" aria-labelledby="dropdown_record_work">
                  <?php if ($user_status_checkin != 'success' && $init_data['is_day_off'] != 1) { ?>
                    <li class="mt-3"><button type="button" class="dropdown-item" <?php Tiwdal::register('record_checkin', ['user_data' => $user_info], ['is_ajax' => 1]); ?>><?= $text_btn ?></button></li>
                  <?php } ?>
                  <li class="mb-3"><button type="button" class="dropdown-item" <?php Tiwdal::register('modal_record_past_time', ['id' => $current_user_id]); ?>>ลงบันทึกย้อนหลัง</button></li>
                </ul>
              </div>
            </div>
          </div>
        </div>

        <div class="row my-4">
          <div class="col-lg-12">
            <div class="card-box-tbl">

              <form id="scope_form_filter" method="get">
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

              <div class="table-responsive mt-10px">
                <table id="table_record_checkin" class="table">
                  <thead>
                    <tr>
                      <th>วันที่</th>
                      <th>เวลา, สถานที่เข้างาน</th>
                      <th>เวลา, สถานที่ออกงาน</th>
                      <th>สถานะการเข้างาน</th>
                      <th>รวมเวลาทำงาน</th>
                      <th>สถานะบันทึก</th>
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

  <?php Tiwdal::startModal('modal_record_past_time', 'modal-md modal-dialog-cent ered max-w-500px'); ?>
  <form action="" method="post">
    <div class="modal-header justify-content-center">
      <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
        <span class="font-18px" aria-hidden="true">&times;</span>
      </button>
    </div>
    <div class="modal-body px-20px m-auto max-w-450px pt-0">
      <div class="font-24px font-SemiBold  text-center mb-15px"><?= Ty::get(['ลงบันทึกการเข้า - ออกงานย้อนหลัง', '']) ?></div>
      <div class="font-15px text-secondary text-center">
        หากคุณบันทึกเวลาเข้า - ออกงานย้อนหลัง ข้อมูลจะไม่บันทึกจนกว่า <br> หัวหน้างานจะอนุมัติ
      </div>

      <div class="row my-15px">
        <div class="col-12 fw-semibold">ลงบันทึกเวลา</div>
      </div>
      <div class="row ">
        <div class="col-lg-12 mb-15px">
          <div class="hr-form-control">
            <div class="input-group">
              <div class="input-title required">วันที่</div>
              <input type="date" class="input" name="date" value="" required max="<?= date('Y-m-d', strtotime('-1 days')) ?>">
            </div>
          </div>
        </div>
        <div class="col-lg-6 mb-15px">
          <div class="hr-form-control">
            <div class="input-group">
              <div class="input-title required">เวลาเข้างาน</div>
              <input type="time" class="input" name="time" value="" required>
            </div>
          </div>
        </div>
        <div class="col-lg-6 mb-15px">
          <div class="hr-form-control">
            <div class="input-group">
              <div class="input-title required">เวลาออกงาน</div>
              <input type="time" class="input" name="time_end" value="" required>
            </div>
          </div>
        </div>

        <div class="col-lg-12 mb-15px">
          <div class="hr-form-control mt-15px">
            <div class="input-group">
              <div class="input-title required">สถานที่เข้างาน</div>
              <select name="branch_id" class="input" required>
                <option value="" selected disabled>เลือก</option>
                <?php foreach ($branch_dropdown as $val) { ?>
                  <option value="<?= $val['id'] ?>"><?= $val['name'] ?></option>
                <?php } ?>
              </select>
            </div>
          </div>
        </div>
        <div class="col-lg-12 mb-15px">
          <div class="hr-form-control">
            <div class="input-group">
              <div class="input-title required">สาเหตุ</div>
              <textarea type="text" class="input" rows="3" name="remark" value="" required></textarea>
            </div>
          </div>
        </div>
      </div>

    </div>
    <div class="modal-footer">
      <input type="hidden" name="type" value="late">
      <input type="hidden" name="{id}">
      <?php
      TiwForm::normal('btn', '', ['name' => 'submit_record_checkin', 'type' => 'submit', 'class' => 'btn-warning w-100 mx-0 mb-5px h-50px border-radius-15px'], ['text' => Ty::get(['บันทึกเวลาย้อนหลัง', ''])]);
      TiwForm::normal('btn', '', ['name' => '', 'type' => 'button', 'class' => 'btn-close-modal w-100 m-0 h-50px border-radius-15px', 'data-bs-dismiss' => 'modal'], ['text' => Ty::get(['ยกเลิก', ''])]);
      ?>
    </div>
  </form>
  <?php Tiwdal::endModal(); ?>

  <?php Tiwdal::ajaxModal('record_checkin', 'modal-md modal-dialog-centered mx-auto  max-w-400px'); ?>

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

    var table_record_checkin = new DataTable(document.getElementById('table_record_checkin'), {
      "pageLength": `<?= $page_size ?>`,
      ajax: {
        url: 'ajax/ajax_table_record_checkin.php',
        type: 'POST',
        data: function(d) {
          let info = table_record_checkin ?? '';
          let page_no = info ? info.page.info().page + 1 : 1;
          let page_size = info ? info.page.len() : <?= $page_size ?>;
          let user_id = `<?= $user_info['id'] ? $user_info['id']  : '' ?>`;
          d.page_no = page_no;
          d.page_size = page_size;
          d.user_id = user_id;
          d.keyword = `<?= $search_emp  ?>`;
          d.month_filter = `<?= $month_filter  ?>`;
        }
      },
      columns: [{
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
            if (data) {
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
        {
          data: 'checkout_datetime',
          className: 'nowrap',
          render: function(data, type, row, meta) {
            if (data) {
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
        {
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
              // } else if (data == 'normal') {
              //   class_name = 'secondary'
              //   status_name = 'ยังไม่ได้เข้างาน';
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
        {
          data: 'total_working_time',
          className: 'nowrap',
          render: function(data, type, row, meta) {
            if (data && row.status_checkin != 'leave_work' && row.status_checkin != 'missing') {
              html = `
              <div>${data}</div>
              ${row.is_overtime == 1 ? `<div class="sub-text-tbl text-secondary">เกินเวลา</div>` : '' }
              `
            } else {
              html = `<div>--:--</div>`
            }
            return html;
          }
        },
        {
          data: 'status_confirm',
          className: 'nowrap',
          render: function(data, type, row, meta) {
            let html = ``;
            let status_name = '';
            if (data == 'waiting_confirm') {
              status_name = 'รออนุมัติบันทึก...';
            } else if (data == 'approved') {
              status_name = 'อนุมัติแล้ว';
            } else if (data == 'reject') {
              status_name = 'ไม่อนุมัติ';
            }

            if (data) {
              html = `
              <div>${status_name}</div>
                <div class="sub-text-tbl">
                ${row.status_confirm_update_by_fullname ? `โดย ${row.status_confirm_update_by_fullname}` : ''}
                ${row.status_confirm_update_datetime ? w.formatDate(row.status_confirm_update_datetime,'d/m/Y, H:i น.') : ''}
              </div>
              `;
            }
            return html;
          }
        },
      ],
      createdRow: function(row, data, dataIndex) {
        if (data.status_checkin == 'missing') {
          $(row).addClass('bg-c-3');
        } else if (data.status_checkin == 'late' || data.status_checkin == 'very_late') {
          $(row).addClass('bg-c-4');
        }
      },
    });
  </script>
</body>

</html>