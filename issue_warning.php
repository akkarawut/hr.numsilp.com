<?php
$user_permission['_permission'] = ['all', 'ywptz_user', 'niemz_user', 'bmiwk_user', 'noaxt_user', 'xacpn_user'];
require_once 'wloves/_framework/import_for_website.php';
require_once 'layout/init.php';
$name_page = 'ออกใบเตือน';
$count_emp = phr_employee::countEmployee(API_CODE);
$page_size = isset($_GET['page_size']) ? $_GET['page_size'] : 10;
$user_filter_code = isset($_GET['user_code']) ? $_GET['user_code'] : '';
$search_emp = isset($_GET['search_emp']) ? $_GET['search_emp'] : '';

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

$per_dropdown = phr_helper::dropdownRoleEmployee(API_CODE);
?>
<!-- MENU::ออกใบเตือน -->
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
            <h1 class="title-header mb-10px">ออกใบเตือน</h1>
            <p class="sub-title text-secondary">
              รายการใบเตือนสำหรับการกระทำผิดของพนักงาน
            </p>
          </div>
          <div class="col-md-3">
            <a href="issue_warning_add.php" class="btn btn-primary border-radius-15px btn-res-150 ms-auto">
              ออกใบเตือน
            </a>
          </div>
        </div>

        <div class="row my-4">
          <div class="col-lg-12">
            <div class="card-box-tbl">

              <form id="scope_form_filter" method="get">
                <div class="form-filter-tbl">
                  <div class="row">
                    <div class="col-xl-3 col-md-4">
                      <div class="hr-form-control ">
                        <div class="input-group">
                          <input type="text" class="input event_keyup_filter" name="search_emp" value="<?= $search_emp ?>" placeholder="ค้นหาพนักงาน...">
                        </div>
                      </div>
                    </div>
                    <div class="col-xl-3 col-md-4">
                      <div class="hr-form-control ">
                        <div class="input-group">
                          <select name="user_code" class="input event_change_filter">
                            <option value="" <?= $user_filter_code == '' ? 'selected' : '' ?>>ทั้งหมด</option>
                            <?php foreach ($per_dropdown as $val) { ?>
                              <option value="<?= $val['code'] ?>" <?= $user_filter_code == $val['code'] ? 'selected' : '' ?>> <?= $val['name'] ?> </option>
                            <?php } ?>
                          </select>
                        </div>
                      </div>
                    </div>
                    <div class="col-xl-3 col-md-4">
                      <div class="hr-form-control ">
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
                <table id="table_issue_warning" class="table">
                  <thead>
                    <tr>
                      <th class="thin-cell">เลขที่เอกสาร</th>
                      <th class="thin-cell">ชื่อพนักงาน</th>
                      <th class="thin-cell">ครั้งที่</th>
                      <th>สาเหตุ</th>
                      <th>รายละเอียดเพิ่มเติม</th>
                      <th class="thin-cell"></th>
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
    w(document).on('change', '.event_change_filter', function() {
      w('#scope_form_filter').submit();
    });

    var table_issue_warning = new DataTable(document.getElementById('table_issue_warning'), {
      ajax: {
        url: 'ajax/ajax_table_issue_warning.php',
        type: 'POST',
        data: function(d) {
          let info = table_issue_warning ?? '';
          let page_no = info ? info.page.info().page + 1 : 1;
          let page_size = info ? info.page.len() : <?= $page_size ?>;
          d.page_no = page_no;
          d.page_size = page_size;
          d.keyword = `<?= $search_emp  ?>`;
          d.user_filter_code = `<?= $user_filter_code  ?>`;
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
  </script>
</body>

</html>