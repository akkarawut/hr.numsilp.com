<?php
$user_permission['_permission'] = ['all', 'ywptz_user', 'niemz_user', 'bmiwk_user', 'noaxt_user', 'xacpn_user'];
require_once 'wloves/_framework/import_for_website.php';
require_once 'layout/init.php';
$name_page = 'สรุปข้อมูลเงินเดือน';
$dropdown_role = phr_helper::dropdownWorkingTime(API_CODE);
$dropdown_date = phr_helper::dropdownMonthSalary(API_CODE);
$dropdown_emp = phr_helper::dropdownEmployee(API_CODE);
$role_ids = isset($_GET['role_ids']) ? $_GET['role_ids'] : '';
$user_id = isset($_GET['user_id']) ? $_GET['user_id'] : '';
$f_date = isset($_GET['f_date']) ? $_GET['f_date'] : '';
$filter = isset($_GET['filter']) ? $_GET['filter'] : '';

$where = [
  'date' => $f_date,
  'role_ids' => $role_ids,
  'user_id' => $user_id,
];

$count = phr_checkin::reportCheckInHead(API_CODE, $where);



?>
<!-- MENU::สรุปข้อมูลเงินเดือน -->
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
        <div class="d-flex justify-content-between">
          <div class="mb-4">
            <h1 class="title-header mb-10px">สรุปข้อมูลเงินเดือน</h1>
            <p class="sub-title text-secondary">สรุปข้อมูลเงินเดือนของพนักงาน</p>
            <div class="d-flex flex-wrap align-items-center mt-20px mb-5px">
              <?php if ($filter) { ?>
                <div class="text">ตัวกรองการแสดงผล :</div>
                <?php if ($f_date) { ?>
                  <div class="chip mb-5px">เดือน : <?= $count['month_name'] ? $count['month_name'] : $dropdown_date[0]['text_month']; ?></div>
                  <div class="chip mb-5px"> <?= $count['month_day_amount'] ? $count['month_day_amount'] : $dropdown_date[0]['month_day_amount']; ?> วัน</div>
                <?php } ?>
                <?php if ($role_ids) { ?>
                  <?php foreach ($dropdown_role as $val) { ?>
                    <?php if (in_array($val['id'], $role_ids)) { ?>
                      <div class="chip mb-5px">รูปแบบ : <?= $val['title'] ?></div>
                    <?php } ?>
                  <?php } ?>
                <?php } ?>
                <?php if ($user_id) { ?>
                  <?php foreach ($dropdown_emp as $val) { ?>
                    <?php if ($val['id'] == $user_id) { ?>
                      <div class="chip mb-5px">พนักงาน : <?= $val['full_name']; ?> </div>
                    <?php } ?>
                  <?php } ?>
                <?php } else { ?>
                  <div class="chip mb-5px">พนักงาน : ทั้งหมด </div>
                <?php } ?>
              <?php } ?>
            </div>
          </div>
          <div class=" mb-4">
            <div class=" dropdown-c">
              <button class="dropdown-btn btn-icon dropdown-toggle" type="button" id="dropdown_filter" data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="outside" aria-expanded="false">
                <?php if ($role_ids && count($role_ids) > 0) { ?>
                  <div class="count"><?= count($role_ids) ?></div>
                <?php } ?>
                <span class="icon">
                  <?= file_get_contents('assets/icons/adjust.svg') ?>
                </span>
              </button>
              <ul class="dropdown-menu dropdown-menu-end max-w-300px w-100" aria-labelledby="dropdown_filter">
                <form method="get">
                  <input type="hidden" name="filter" value="treu">
                  <div class="px-10px py-10px">
                    <div class="font-15px fw-semibold mb-15px">ช่วงเวลา</div>
                    <div class="hr-form-control mb-20px">
                      <div class="input-group">
                        <div class="input-title">เดือน</div>
                        <select name="f_date" class="input">
                          <option value="" <?= $f_date == '' ? 'selected' : '' ?>>ทั้งหมด</option>
                          <?php foreach ($dropdown_date as $val) { ?>
                            <option value="<?= $val['value'] ?>" <?= $f_date == $val['value'] ? 'selected' : '' ?>><?= $val['text'] ?></option>
                          <?php } ?>
                        </select>
                      </div>
                    </div>

                    <div class="font-15px mb-10px fw-semibold">รูปแบบการทำงานของพนักงาน</div>
                    <?php foreach ($dropdown_role as $val) { ?>
                      <?php
                      $checked = '';
                      if ($role_ids && in_array($val['id'], $role_ids)) {
                        $checked = 'checked';
                      }
                      ?>
                      <div class="mb-15px">
                        <?php
                        TiwForm::normal('checkbox', $val['id'], ['name' => 'role_ids[]', 'class' => '', $checked => 1], ['style' => '3', 'label' => $val['title']]);
                        ?>
                      </div>
                    <?php } ?>
                    <div class="font-15px fw-semibold mb-15px">เฉพาะพนักงาน</div>
                    <div class="hr-form-control mb-20px">
                      <div class="input-group">
                        <div class="input-title">พนักงาน</div>
                        <select name="user_id" class="input">
                          <option value="" <?= $user_id == '' ? 'selected' : '' ?>>ทั้งหมด</option>
                          <?php foreach ($dropdown_emp as $val) { ?>
                            <option value="<?= $val['id'] ?>" <?= $user_id == $val['id'] ? 'selected' : '' ?>><?= $val['full_name'] ?></option>
                          <?php } ?>
                        </select>
                      </div>
                    </div>
                  </div>
                  <hr>
                  <div class="d-flex justify-content-end px-10px py-10px">
                    <a href="report_payment.php" class="btn btn-default border-radius-15px min-w-100px mr-5px">ล้าง</a>
                    <button type="submit" name="submit_filter" class="btn btn-primary border-radius-15px min-w-130px">ค้นหา</button>
                  </div>
                </form>
              </ul>
            </div>
          </div>
        </div>

        <div class="row mb-4">
          <div class="col-lg-12">
            <div class="card-box-tbl">
              <div class="table-responsive mt-10px">
                <table id="table_report_payment" class="table">
                  <thead>
                    <tr>
                      <th>พนักงาน</th>
                      <th>ธนาคาร</th>
                      <th>เลขบัญชีธนาคาร</th>
                      <th>ชื่อบัญชี</th>
                      <th>ยอดเงินที่ได้รับ</th>
                      <th>หมายเลขอ้างอิง</th>
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

  <script>
    var table_report_payment = new DataTable(document.getElementById('table_report_payment'), {
      ajax: {
        url: 'ajax/ajax_table_report_payment.php',
        type: 'POST',
        data: function(d) {
          let info = table_report_payment ?? '';
          let page_no = info ? info.page.info().page + 1 : 1;
          let page_size = info ? info.page.len() : 10;
          d.page_no = page_no;
          d.page_size = page_size;
          d.role_ids = `<?= $role_ids ? json_encode($role_ids) : '' ?>`;
          d.f_date = `<?= $f_date ?>`;
          d.user_id = `<?= $user_id ?>`;
        }
      },
      columns: [{
          data: 'user_fullname',
          className: 'nowrap',
          render: function(data, type, row, meta) {
            return '<div class="nowrap">' + data + '</div>'
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
                <span>${data}</span>
              </div>`
            return html;
          }
        },
        {
          data: 'bank_account_number',
          className: 'nowrap text-center',
          render: function(data, type, row, meta) {
            return '<div class="nowrap">' + data + '</div>'
          }
        },
        {
          data: 'bank_account_name',
          className: 'nowrap text-center',
          render: function(data, type, row, meta) {
            return '<div class="nowrap">' + data + '</div>'
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
          data: 'ref_no',
          className: 'nowrap text-center',
          render: function(data, type, row, meta) {
            return `<div class="nowrap">${data ? data : ''}</div>`;
          }
        },
      ],
    });
  </script>
</body>

</html>