<?php
$user_permission['_permission'] = ['all', 'ywptz_user', 'niemz_user', 'bmiwk_user', 'noaxt_user', 'xacpn_user'];
require_once 'wloves/_framework/import_for_website.php';
require_once 'layout/init.php';
$name_page = 'บันทึกการขอลางาน';
$count_emp = phr_employee::countEmployee(API_CODE);
$page_size = isset($_GET['page_size']) ? $_GET['page_size'] : 10;
$month_filter = isset($_GET['month_filter']) ? $_GET['month_filter'] : '';
$month_dropdown = phr_helper::dropdownMonthCheckIn(API_CODE);
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
?>
<!-- MENU:: บันทึกการขอลางาน -->
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
            <h1 class="title-header mb-10px">บันทึกการขอลางาน</h1>
            <p class="sub-title text-secondary">
              บันทึกการขอลาหยุด ลากิจ ของคุณ
            </p>
          </div>
          <div class="col-md-3 mb-4">
            <a href="record_leavework_add.php" class="btn btn-primary border-radius-15px btn-res-150 ms-auto">ลงบันทึกการลา</a>
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
                <table id="table_record_leavework" class="table">
                  <thead>
                    <tr>
                      <th>หมายเลขคำขอ</th>
                      <th>วันที่ยื่นคำขอ</th>
                      <th>ประเภทการลา</th>
                      <th>วันที่, เวลาที่ขอลา</th>
                      <th>สาเหตุการลา</th>
                      <th>สถานะคำขอ</th>
                      <th></th>
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

    var table_record_leavework = new DataTable(document.getElementById('table_record_leavework'), {
      "pageLength": `<?= $page_size ?>`,
      ajax: {
        url: 'ajax/ajax_table_record_leavework.php',
        type: 'POST',
        data: function(d) {
          let info = table_record_leavework ?? '';
          let page_no = info ? info.page.info().page + 1 : 1;
          let page_size = info ? info.page.len() : <?= $page_size ?>;
          let user_id = `<?= $user_info['id'] ? $user_info['id']  : '' ?>`;
          d.page_no = page_no;
          d.page_size = page_size;
          d.user_id = user_id;
          d.month_filter = `<?= $month_filter  ?>`;
        }
      },
      columns: [{
          data: 'no',
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
              ${moment(data).format('H:m')} น.
              </div>
              `
            return html;
          }
        },
        {
          data: 'type',
          className: 'nowrap',
          render: function(data, type, row, meta) {
            if (data == 'sick_leave') {
              return `<div>ลาป่วย</div>`;
            } else if (data == 'vacation_leave') {
              return `<div>ลาพักร้อน</div>`;
            } else {
              return `<div>ลากิจ</div>`;
            }
          }
        },
        {
          data: 'from_date',
          className: 'nowrap',
          render: function(data, type, row, meta) {
            let html = ``;
            if (data == row.to_date) {
              html = `<div>${moment(data).format('LL')}</div>`
            } else if (data != row.to_date) {
              let date1 = data.split('-');
              let date2 = row.to_date.split('-');
              if (date1[1] == date2[1]) {
                html = `${moment(data).format('D')} - ${moment(row.to_date).format('LL')}</div>`
              } else {
                html = `<div>${moment(data).format('LL')} - ${moment(row.to_date).format('LL')}</div>`
              }
            }
            html += `<div class="sub-text-tbl">${row.sum_day_leave_work} วัน</div>`
            return html;
          }
        },
        {
          data: 'detail',
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
        {
          data: 'id',
          className: 'nowrap',
          render: function(data, type, row, meta) {
            let html = `<a href="record_leavework_detail.php?id=${data}">
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