<?php
$user_permission['_permission'] = ['all', 'ywptz_user', 'niemz_user', 'bmiwk_user', 'noaxt_user', 'xacpn_user'];
require_once 'wloves/_framework/import_for_website.php';
require_once 'layout/init.php';
$name_page = 'การแจ้งเตือน';
?>
<!-- MENU::การแจ้งเตือน-->
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
          <div class="col-md-12">
            <h1 class="title-header mb-10px">การแจ้งเตือน</h1>
          </div>
        </div>

        <div class="row my-4">
          <div class="col-lg-12">
            <div class="card-box-tbl">
              <div class="table-responsive mt-10px">
                <table id="table_notification" class="table">
                  <thead>
                    <tr>
                      <th>หัวข้อ</th>
                      <th>รายละเอียด</th>
                      <th>วันที่</th>
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
  <?php require_once 'layout/footer_js.php'; ?>

  <script>
    var table_notification = new DataTable(document.getElementById('table_notification'), {
      ajax: {
        url: 'ajax/ajax_table_notification_list.php',
        type: 'POST',
        data: function(d) {
          let info = table_notification ?? '';
          let page_no = info ? info.page.info().page + 1 : 1;
          let page_size = info ? info.page.len() : 10;
          d.page_no = page_no;
          d.page_size = page_size;
          d.user_id = `<?= $current_user_id ?>`;
        }
      },
      columns: [{
          data: 'topic',
          className: 'nowrap',
        },
        {
          data: 'description',
          className: 'nowrap',
        },
        {
          data: 'insert_date_time',
          className: 'nowrap',
          render: function(data, type, row, meta) {
            let html = `${moment(data).format('LL')}`
            return html;
          }
        },
        {
          data: 'link',
          className: 'nowrap',
          render: function(data, type, row, meta) {
            let html = `<a href="${data}" onclick="sendIsRead('${row.id}')">
                      <img src="assets/icons/arrow-right.svg" alt="">
                    </a>`
            return html;
          }
        },
      ],
      createdRow: function(row, data, dataIndex) {
        if (data.is_read == 0) {
          $(row).addClass('bg-c-4');
        }
      },
    });


    function sendIsRead(id) {
      let url = `ajax/ajax_send_is_read.php?id=${id}`;
      wx.get(url, function(res) {
        let result = JSON.parse(res);
      })
    }
  </script>
</body>

</html>