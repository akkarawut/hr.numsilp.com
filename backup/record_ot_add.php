<?php
$user_permission['_permission'] = ['all', 'ywptz_user', 'niemz_user', 'bmiwk_user', 'noaxt_user', 'xacpn_user'];
require_once 'wloves/_framework/import_for_website.php';
require_once 'layout/init.php';
$name_page = 'บันทึกการขอล่วงเวลา (OT)';
if ($_POST) {
  if (isset($_POST['submit_add_ot'])) {
    unset($_POST['submit_add_ot']);
    $data = [
      'user_id' => $user_info['id'],
      'remark' => $_POST['remark'],
      'date' => $_POST['date'],
      'from_time' => $_POST['from_time'],
      'to_time' => $_POST['to_time'],
    ];
    $result = phr_ot::addNewOT(API_CODE, $data);
    if ($result['response_status']) {
      $redirect = 'record_ot.php';
    }
  }
  W_Main::responseAPI(/*$result, $message, $redirect*/);
}

?>
<!-- MENU::บันทึกการขอล่วงเวลา (OT) -->
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
                <a href="record_ot.php"> <?= file_get_contents('assets/icons/icon-left-arrow.svg') ?></a>
              </div>
              <h1 class="title-header m-0">ยื่นบันทึกการขอทำงานล่วงเวลา (OT)</h1>
            </div>
          </div>
        </div>
        <hr>
      </div>
      <form method="post" enctype="multipart/form-data">
        <div class="container">
          <div class="max-w-750px mx-auto">
            <p class="sub-title my-25px">
              โปรดกรอกรายละเอียดให้ครบถ้วนเพื่อใช้ประกอบการอนุมัติขอทำงานล่วงเวลา (OT)
            </p>
            <div class="card rounded-20px">
              <div class="card-body py-25px px-30px">
                <div class="row">
                  <div class="col-12 mb-15px">
                    <div class="font-15px fw-semibold">รายละเอียด</div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-lg-12 mb-15px">
                    <div class="hr-form-control">
                      <div class="input-group">
                        <div class="input-title required">เหตุผล</div>
                        <textarea name="remark" class="input" value="" required></textarea>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="scope_row_date row" data-time="0" data-end_time="0">

                  <div class="col-lg-6 mb-15px">
                    <div class="hr-form-control">
                      <div class="input-group">
                        <div class="input-title required">วันที่ต้องการขอทำงานล่วงเวลา</div>
                        <input id="date" type="date" class="input event_change_date" name="date" required>
                      </div>
                    </div>
                  </div>

                  <div class="col-lg-3 mb-15px">
                    <div class="hr-form-control">
                      <div class="input-group">
                        <div class="input-title required">ตั้งแต่เวลา</div>
                        <input type="time" name="from_time" class="input" required value="" onchange="changeTime(this,'start_time');">
                      </div>
                    </div>
                  </div>

                  <div class="col-lg-3 mb-15px">
                    <div class="hr-form-control">
                      <div class="input-group">
                        <div class="input-title required">ถึงเวลา</div>
                        <input type="time" name="to_time" class="input" required value="" onchange="changeTime(this,'end_time');">
                      </div>
                    </div>
                  </div>

                  <div class="col-12 mb-15px">
                    <span class="text-danger">*การคำนวณเงินได้พิเศษจาก การทำงานล่วงเวลาจะคำนวณเต็มชั่วโมงทำงานเท่านั้น</span>
                  </div>
                </div>

                <div class="row">
                  <div class="col-12">
                    <div class="text-primary font-15px fw-semibold mb-20px">ระยะเวลาขอทำงานล่วงเวลารวม : <span class="cal_time"></span></div>
                  </div>
                </div>


                <div class="row mt-20px">
                  <div class="col-lg-12">
                    <div class="d-flex justify-content-end">
                      <a href="record_ot.php" class="btn btn-default btn-lg rounded-15px max-w-150px w-100 me-2">
                        <div class="fw-semibold">ยกเลิก</div>
                      </a>
                      <button type="submit" name="submit_add_ot" class="btn btn-primary btn-lg rounded-15px max-w-150px w-100">
                        <div class="fw-semibold">ส่งคำขอ</div>
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
    function changeTime(t, type) {
      let scope = t.closest('.scope_row_date');
      if (type == 'start_time') {
        scope.setAttribute('data-time', t.value);
      } else if (type == 'end_time') {
        scope.setAttribute('data-end_time', t.value);
      }
      DiffHours(scope, 'data-time', 'data-end_time', '.cal_time');
    }

    function DiffHours(scope, data_start, data_end, ele_txt) {
      let start_time = scope.getAttribute(data_start);
      let end_time = scope.getAttribute(data_end);
      let arr_start_time = start_time.split(':');
      let arr_end_time = end_time.split(':');
      let startDate = new Date(0, 0, 0, arr_start_time[0], arr_start_time[1], 0);
      let endDate = new Date(0, 0, 0, arr_end_time[0], arr_end_time[1], 0);
      let diff = (endDate.getTime() ? endDate.getTime() : startDate.getTime()) - (startDate.getTime() ? startDate.getTime() : endDate.getTime());
      var hours = Math.floor(diff / 1000 / 60 / 60);
      diff -= hours * 1000 * 60 * 60;
      var minutes = Math.floor(diff / 1000 / 60);

      let html = ``;
      html = `
      <span class="txt_hrs">
        ${hours ? hours : 0}
      </span> ชั่วโมง
      <span class="txt_min ml-5px">${minutes ? minutes : 0}</span> นาที
      `;
      document.querySelector(ele_txt).innerHTML = html;
    }
  </script>
</body>

</html>