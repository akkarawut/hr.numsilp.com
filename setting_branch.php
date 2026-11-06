<?php
$user_permission['_permission'] = ['all', 'ywptz_user', 'niemz_user', 'bmiwk_user', 'noaxt_user', 'xacpn_user'];
require_once 'wloves/_framework/import_for_website.php';
require_once 'layout/init.php';
$name_page = 'ข้อมูลสาขา';
?>
<!-- MENU::ตั้งค่า_ข้อมูลสาขา -->
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
            <h1 class="title-header mb-10px">ตั้งค่าข้อมูลสาขา</h1>
            <p class="sub-title text-secondary">ตั้งค่าข้อมูลสาขา สำหรับลงบันทึกเข้า - ออกงานของพนักงาน รวมถึงข้อมูลพนักงาน</p>
          </div>
          <div class="col-md-3">
            <a href="branch_add.php" class="btn btn-primary border-radius-15px btn-res-150 ms-auto">สร้างสาขา</a>
          </div>
        </div>

        <div class="row my-4">
          <div class="col-lg-12">
            <div class="card-box-tbl">
              <div class="table-responsive mt-10px">
                <table id="table_branch" class="table">
                  <thead>
                    <tr>
                      <th>ชื่อสาขา</th>
                      <th>รหัสสาขา</th>
                      <th>ที่ตั้ง</th>
                      <th>เบอร์ติดต่อ</th>
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
    var table_branch = new DataTable(document.getElementById('table_branch'), {
      ajax: {
        url: 'ajax/ajax_table_setting_branch.php',
        type: 'POST',
        data: function(d) {
          let info = table_branch ?? '';
          let page_no = info ? info.page.info().page + 1 : 1;
          let page_size = info ? info.page.len() : 10;
          d.page_no = page_no;
          d.page_size = page_size;
        }
      },
      columns: [{
          data: 'name',
          className: 'nowrap',
        },
        {
          data: 'branch_code',
          className: 'nowrap',
        },
        {
          data: 'location',
          className: 'nowrap',
        },

        {
          data: 'tel',
          className: 'nowrap',
          render: function(data, type, row, meta) {
            let tel = data ? data.join(',') : '';
            return tel;
          }
        },
        {
          data: 'description',
          className: 'nowrap',
          render: function(data, type, row, meta) {
            return `<div class="des-truncate">${data}</div>`;
          }
        },
        {
          data: 'id',
          className: 'nowrap',
          render: function(data, type, row, meta) {
            let html = `<a href="branch_detail.php?id=${data}">
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