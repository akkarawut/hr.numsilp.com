<?php
$log_mode = true;
require_once 'wloves/_framework/import_for_website.php';
require_once 'layout/init.php';
header("location: setting_holiday.php");
$name_page = '';
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <?php
  $og_data = [
    'url'         => '',
    'title'       => 'HR Numsilp',
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
    <div id="hr-content">
      <div class="container-fluid">
        <div class="w-loves-card my-3">
          <div class="row">
            <div class="col-12">
              <div class="fw-bold">COMPONENT FORM</div>
              <hr>
            </div>
            <div class="col-lg-4">
              <div class="hr-form-control">
                <div class="input-group">
                  <div class="input-title">Email</div>
                  <input type="email" class="input" name="">
                </div>
              </div>
            </div>
            <div class="col-lg-4">
              <div class="hr-form-control">
                <div class="input-group">
                  <div class="input-title">Text</div>
                  <input type="text" class="input" name="">
                </div>
              </div>
            </div>
            <div class="col-lg-4">
              <div class="hr-form-control">
                <div class="input-group">
                  <div class="input-title">Number</div>
                  <input type="number" class="input" name="">
                </div>
              </div>
            </div>
            <div class="col-lg-4">
              <div class="hr-form-control">
                <div class="input-group">
                  <div class="input-title">Password</div>
                  <input type="password" class="input" name="">
                  <button type="button" class="btn-password-manage" data-status="hide"></button>
                </div>
              </div>
            </div>
            <div class="col-lg-4">
              <div class="hr-form-control">
                <div class="input-group">
                  <div class="input-title">Date</div>
                  <input type="date" class="input" name="">
                </div>
              </div>
            </div>
            <div class="col-lg-4">
              <div class="hr-form-control">
                <div class="input-group">
                  <div class="input-title">Time</div>
                  <input type="time" class="input" name="">
                </div>
              </div>
            </div>
            <div class="col-lg-4">
              <div class="hr-form-control">
                <div class="input-group">
                  <div class="input-title">Select</div>
                  <select name="" class="input">
                    <option value="Option1">Option1</option>
                    <option value="Option2">Option2</option>
                  </select>
                </div>
              </div>
            </div>
            <div class="col-lg-12">
              <div class="hr-form-control set-mb-2">
                <div class="input-group">
                  <div class="input-title">Textarea</div>
                  <textarea name="" class="input"></textarea>
                </div>
              </div>
            </div>

            <div class="col-12 mt-10px">
              <div class="fw-bold">COMPONENT CHECKBOX</div>
              <hr>
            </div>
            <div class="col-lg-4">
              <div class="mb-5px">
                <?php
                TiwForm::normal('checkbox', 1, ['name' => '', 'class' => '', 'checked' => 1], ['style' => '1', 'text_on' => 'Yes', 'is_on_off' => true, 'label' => 'Yes']);
                ?>
              </div>
              <div class="mb-5px">
                <?php
                TiwForm::normal('checkbox', 1, ['name' => '', 'class' => '', 'checked' => 1], ['style' => '2', 'text_on' => 'Yes', 'is_on_off' => true, 'label' => 'Yes']);
                ?>
              </div>
              <div class="mb-5px">
                <?php
                TiwForm::normal('checkbox', 1, ['name' => '', 'class' => '', 'checked' => 1], ['style' => '3', 'text_on' => 'Yes', 'is_on_off' => true, 'label' => 'Yes']);
                ?>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="container-fluid">
        <div class="row">
          <div class="col-lg-12">
            <div class="card-box-tbl">
              <div class="table-responsive mt-10px">
                <table id="table_mock_template" class="table">
                  <thead>
                    <tr>
                      <th>Column 1</th>
                      <th>Column 2</th>
                      <th>Column 3</th>
                      <th>Column 4</th>
                      <th>Column 5</th>
                    </tr>
                  </thead>
                  <tbody> </tbody>
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
  ?>

  <script>
    var dom_table = "t<'d-flex align-items-center justify-content-end mt-3'<'select-l'l><'mx-3'i>p>";
    var table_mock_template = new DataTable(document.getElementById('table_mock_template'), {
      "ajax": {
        "url": 'ajax/ajax_table_template.php',
        "type": 'POST',
        "dataType": 'JSON',
        "dataSrc": 'data',
        "data": function(d) {
          let info = table_mock_template ?? '';
          let page_no = info ? info.page.info().page + 1 : 1;
          d.page_no = page_no;
          d.page_size = 10;
        },
      },
      "columns": [{
          "data": 'mock_data',
          // render: function(data, type, row, meta) {
          //   return '<div class="nowrap">' + data + '</div>'
          // }
        },
        {
          "data": 'mock_data',
          // render: function(data, type, row, meta) {
          //   return '<div class="nowrap">' + data + '</div>'
          // }
        },
        {
          "data": 'mock_data',
          // render: function(data, type, row, meta) {
          //   return '<div class="nowrap">' + data + '</div>'
          // }
        },
        {
          "data": 'mock_data',
          // render: function(data, type, row, meta) {
          //   return '<div class="nowrap">' + data + '</div>'
          // }
        },
        {
          "data": 'mock_data',
          // render: function(data, type, row, meta) {
          //   return '<div class="nowrap">' + data + '</div>'
          // }
        },
      ],
    });
  </script>
</body>

</html>