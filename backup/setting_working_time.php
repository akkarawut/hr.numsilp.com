<?php
$user_permission['_permission'] = ['all', 'ywptz_user', 'niemz_user', 'bmiwk_user', 'noaxt_user', 'xacpn_user'];
require_once 'wloves/_framework/import_for_website.php';
require_once 'layout/init.php';
$name_page = 'ตั้งค่ารูปแบบเวลาทำงาน';
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
      <div class="container-fluid mt-5">
        <div class="row">
          <div class="col-md-9 mb-4">
            <h1 class="title-header mb-10px">ตั้งค่ารูปแบบเวลาทำงาน</h1>
            <p class="sub-title text-secondary">ตั้งค่ารูปแบบเวลาทำงานของพนักงาน</p>
          </div>
          <div class="col-md-3 mb-4">
            <a href="working_time_add.php" class="btn btn-primary border-radius-15px btn-res-150 ms-auto">สร้างรูปแบบ</a>
          </div>
        </div>
        <div class="row mb-4">
          <div class="col-lg-12">
            <div class="card-box-tbl">
              <div class="table-responsive mt-10px">
                <table id="table_working_time" class="table">
                  <thead>
                    <tr>
                      <th>ชื่อรูปแบบ</th>
                      <th>วันทำงาน</th>
                      <th>เวลาทำงาน:สัปดาห์</th>
                      <th>พนักงานที่ใช้รูปแบบนี้</th>
                      <th>รายละเอียดเพิ่มเติม</th>
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

  <script>
    var table_working_time = new DataTable(document.getElementById('table_working_time'), {
      ajax: {
        url: 'ajax/ajax_table_setting_working_time.php',
        type: 'POST',
        data: function(d) {
          let info = table_working_time ?? '';
          let page_no = info ? info.page.info().page + 1 : 1;
          let page_size = info ? info.page.len() : 10;
          d.page_no = page_no;
          d.page_size = page_size;
          d.status = `<?= $page ?>`;
        }
      },
      columns: [{
          data: 'title',
          className: 'nowrap',
          render: function(data, type, row, meta) {
            return '<div class="nowrap">' + data + '</div>'
          }
        },
        {
          data: 'id',
          className: 'nowrap',
          render: function(data, type, row, meta) {
            let html = ``;
            let cls_d_1 = row.monday == 1 ? 'text-primary' : 'text-secondary';
            let cls_d_2 = row.tuesday == 1 ? 'text-primary' : 'text-secondary';
            let cls_d_3 = row.wednesday == 1 ? 'text-primary' : 'text-secondary';
            let cls_d_4 = row.thursday == 1 ? 'text-primary' : 'text-secondary';
            let cls_d_5 = row.friday == 1 ? 'text-primary' : 'text-secondary';
            let cls_d_6 = row.saturday == 1 ? 'text-primary' : 'text-secondary';
            let cls_d_7 = row.sunday == 1 ? 'text-primary' : 'text-secondary';
            html +=
              `
            <span class="mr-5px ${cls_d_1}">จ</span>
            <span class="mr-5px ${cls_d_2}">อ</span>
            <span class="mr-5px ${cls_d_3}">พ</span>
            <span class="mr-5px ${cls_d_4}">พฤ</span>
            <span class="mr-5px ${cls_d_5}">ศ</span>
            <span class="mr-5px ${cls_d_6}">ส</span>
            <span class="mr-5px ${cls_d_7}">อา</span>
            `;
            return html;
          }
        },
        {
          data: 'hour_total',
          className: 'nowrap',
          render: function(data, type, row, meta) {
            return '<div class="nowrap">' + data + ' ชั่วโมง ' + row.minute_total + ' นาที</div>'
          }
        },
        {
          data: 'count_user',
          className: 'nowrap',
          render: function(data, type, row, meta) {
            return '<div class="nowrap">' + data + ' ราย </div>'
          }
        },
        {
          data: 'description',
          className: 'nowrap',
          render: function(data, type, row, meta) {
            return '<div class="nowrap">' + data + '</div>'
          }
        },
        {
          data: 'id',
          className: 'nowrap',
          render: function(data, type, row, meta) {
            return `<a href="working_time_detail.php?id=${data}"><img src="assets/icons/arrow-right.svg" alt=""></a>`
          }
        },
      ],
    });
  </script>
</body>

</html>