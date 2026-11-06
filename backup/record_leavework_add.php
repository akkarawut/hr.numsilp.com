<?php
$user_permission['_permission'] = ['all', 'ywptz_user', 'niemz_user', 'bmiwk_user', 'noaxt_user', 'xacpn_user'];
require_once 'wloves/_framework/import_for_website.php';
require_once 'layout/init.php';
$name_page = 'บันทึกการขอลางาน';
if ($_POST) {
  if (isset($_POST['submit_add_leavework'])) {
    unset($_POST['submit_add_leavework']);
    $data = [
      'user_id' => $user_info['id'],
      'type' => $_POST['type'],
      'detail' => $_POST['detail'],
      'from_date' => $_POST['from_date'],
      'to_date' => $_POST['to_date'],
    ];

    $file = isset($_FILES['file_others']) ? $_FILES['file_others'] : '';
    $file_others = [];
    if ($file) {
      foreach ($file['name'] as $key => $value) {
        $file_others[] = [
          'name' => $value,
          'type' => $file['type'][$key],
          'tmp_name' => $file['tmp_name'][$key],
          'error' => $file['error'][$key],
          'size' => $file['size'][$key],
        ];
      }
    }
    $result  = phr_leave_work::addNewLeaveWork(API_CODE, $data, $file_others);
    if ($result['response_status']) {
      $redirect = 'record_leavework_detail.php?id=' . $result['response_data']['insert_id'];
    }
  }
  W_Main::responseAPI(/*$result, $message, $redirect*/);
}

$arr_status_leave = [
  'sick_leave' => 'ลาป่วย',
  'personal_leave' => 'ลากิจ',
  'vacation_leave' => 'ลาพักร้อน',
];


function templateInputUpload()
{
  ob_start(); ?>
  <div class="col-12 mb-15px __event_row">
    <div class="d-flex align-items-center">
      <div class="input-file-w-100">
        <?= TiwForm::normal('file', '', ['name' => 'file_others[]'], []) ?>
      </div>
      <div class="min-50px">
        <button type="button" class="btn-icon fill-danger" onclick="deleteRow(this);">
          <?= file_get_contents('assets/icons/remove.svg') ?>
        </button>
      </div>
    </div>
  </div>
<?php
  return ob_get_clean();
}
?>
<!-- MENU::ตั้งค่า_อนุมัติการลงเวลา -->
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
        <div class="max-w-750px mx-auto">
          <div class="d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center">
              <div class="icon-back mr-10px">
                <a href="record_of_leave_request.php"> <?= file_get_contents('assets/icons/icon-left-arrow.svg') ?></a>
              </div>
              <h1 class="title-header m-0">ยื่นบันทึกขอลางาน</h1>
            </div>
          </div>
        </div>
        <hr>
      </div>

      <form method="post" enctype="multipart/form-data">
        <div class="container">
          <div class="max-w-750px mx-auto">
            <p class="sub-title my-25px">
              โปรดกรอกรายละเอียดให้ครบถ้วนเพื่อใช้ประกอบการอนุมัติลางาน
            </p>

            <div class="card rounded-20px">
              <div class="card-body py-25px px-30px">
                <div class="row">
                  <div class="col-12 mb-15px">
                    <div class="font-15px fw-semibold">รายละเอียดการลา</div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-12 mb-15px">
                    <div class="hr-form-control">
                      <div class="input-group">
                        <div class="input-title required">ประเภทการลา</div>
                        <select name="type" class="input" required>
                          <option value="" selected disabled>เลือก</option>
                          <option value="sick_leave">ลาป่วย</option>
                          <option value="personal_leave">ลากิจ</option>
                          <option value="vacation_leave">ลาพักร้อน</option>
                        </select>
                      </div>
                    </div>
                  </div>

                  <div class="col-lg-12 mb-15px">
                    <div class="hr-form-control">
                      <div class="input-group">
                        <div class="input-title required">สาเหตุการลา</div>
                        <textarea name="detail" class="input" value="" required></textarea>
                      </div>
                    </div>
                  </div>

                  <div class="col-lg-6 mb-15px">
                    <div class="hr-form-control">
                      <div class="input-group">
                        <div class="input-title required">วันที่ต้องการลา</div>
                        <input id="fromDatePicker" type="date" class="input event_change_date" name="from_date" required>
                      </div>
                    </div>
                  </div>
                  <div class="col-lg-6 mb-15px">
                    <div class="hr-form-control">
                      <div class="input-group">
                        <div class="input-title required">ถึงวันที่</div>
                        <input id="toDatePicker" type="date" class="input event_change_date" name="to_date" required>
                      </div>
                    </div>
                  </div>
                </div>


                <div class="row">
                  <div class="col-12 mb-15px">
                    <div class="font-15px fw-semibold text-primary">รวมระยะเวลาที่ขอลา : <span class="date_count"></span></div>
                  </div>
                </div>


                <div class="row">
                  <div class="col-12 mb-15px">
                    <div class="font-15px fw-semibold">เอกสารประกอบการขอลางาน</div>
                    <div class="font-15px">โปรดตั้งชื่อเอกสารให้เรียบร้อยก่อนการอัปโหลด</div>
                  </div>

                  <div class="col-12 mb-15px">
                    <div class="d-flex align-items-center">
                      <div class="input-file-w-100">
                        <?= TiwForm::normal('file', '', ['name' => 'file_others[]'], []) ?>
                      </div>
                    </div>
                  </div>
                </div>

                <div id="scope_row_file" class="row"></div>

                <button type="button" class="btn btn-light-primary w-100 mb-15px" onclick="addRow('scope_row_file');">+ เพิ่มเอกสาร</button>


                <div class="row mt-20px">
                  <div class="col-lg-12">
                    <div class="d-flex justify-content-end">
                      <a href="record_of_leave_request.php" class="btn btn-default btn-lg rounded-15px max-w-150px w-100 me-2">
                        <div class="fw-semibold">ยกเลิก</div>
                      </a>
                      <button type="submit" name="submit_add_leavework" class="btn btn-primary btn-lg rounded-15px max-w-150px w-100">
                        <div class="fw-semibold">บันทึก</div>
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            </div>

          </div>
        </div>
      </form>
    </div>

  </div>

  <?php
  W_Structure::loadFooterForWebsite();
  W_Main::loadAsset('assets/js/main.js');
  ?>

  <script>
    function addRow(t) {
      let scope = document.getElementById(t);
      w('#' + t).append(`<?= templateInputUpload() ?>`);
    }

    function deleteRow(t) {
      t.closest('.__event_row').remove();
    }

    w(document).on('change', '.event_change_date', function() {
      let val_from_date = document.getElementById("fromDatePicker").value;
      let val_to_date = document.getElementById("toDatePicker").value;
      if (val_from_date == val_to_date && val_from_date != '' && val_to_date != '') {
        w('.date_count').html('1 วัน');
      } else if (val_from_date != '' && val_to_date != '') {
        let date1 = new Date(val_from_date);
        let date2 = new Date(val_to_date);
        let difference = date2 - date1;
        let days = difference / (24 * 3600 * 1000);
        w('.date_count').html((days + 1) + ' วัน');
      } else {
        w('.date_count').html(' ');
      }
    });
  </script>
</body>

</html>