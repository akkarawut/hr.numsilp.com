<?php
$user_permission['_permission'] = ['all', 'ywptz_user', 'niemz_user', 'bmiwk_user', 'noaxt_user', 'xacpn_user'];
require_once 'wloves/_framework/import_for_website.php';
require_once 'layout/init.php';
$name_page = 'ภาพรวมรายบุคคล';
$dashboard = phr_dashboard::getDashBoardByUserID(API_CODE, $user_info['id']);

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
  }
  W_Main::responseAPI(/*$result, $message, $redirect*/);
}

?>
<!-- MENU::ภาพรวมรายบุคคล -->
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

      <div class="section-dashboard my-5">
        <div class="container max-w-1000px">
          <div class="d-flex justify-content-center mb-15px">
            <?php if (strpos($user_info['image_path'], '/placeholder') !== false) { ?>
              <div class="profile-img-letter">
                <?= $user_info['full_name'] == '' ? 'A' : mb_substr($user_info['full_name'], 0, 1) ?>
              </div>
            <?php } else { ?>
              <div class="profile-img-letter --img">
                <img src="<?= $user_info['image_path'] ?>" alt="">
              </div>
            <?php } ?>

          </div>

          <div class="text-center mb-10px">
            <div class="title"> ยินดีต้อนรับ, <?= $user_info['full_name'] ?></div>
            <div class="sub-title">
              ลงบันทึกเข้า - ออกงาน, บันทึกการขอลางาน, ขอล่วงเวลา (OT) และดูผลการทำงานของคุณ
            </div>
          </div>

          <div class="dashboard-box box-ovv-1">
            <div class="grid-container-ovv">
              <div class="grid-col-1">
                <div class="box-left">
                  <div class="header-ovv">
                    <div class="title">ลงบันทึกการเข้า - ออกงาน</div>
                    <div class="sub-title">ลงเวลาเข้า และ ออกงานของคุณ</div>
                  </div>
                  <div class="show-time">
                    <img src="assets/images/bg-count-time.png" alt="">
                    <div class="time-type">AM</div>
                    <div class="time">08:00</div>
                  </div>
                </div>
              </div>
              <div class="grid-col-2">
                <?php if ($dashboard["working_status"] == 'not_doing_anything') { ?>
                  <div class="box-right cursor-pointer" <?php Tiwdal::register('record_checkin', ['user_data' => $user_info], ['is_ajax' => 1]); ?>>
                    <div class="header-status">
                      <div class="sub-title mb-25px text-secondary">การลงบันทึกประจำวัน</div>
                      <div class="title text-secondary mb-5px">คุณยังไม่ได้เข้างาน...</div>
                      <div class="sub-title text-secondary">ไม่สามารถระบุสถานที่, เวลาเข้างานได้</div>
                    </div>
                    <div class="box-time">
                      <div class="icon me-3">
                        <?= file_get_contents('assets/icons/hourglass.svg') ?>
                      </div>
                      <div class="time-count">
                        <span class="count_time_hr">00 :</span> <span class="count_time_min">00 :</span> <span class="count_time_sec">00 </span>
                      </div>
                    </div>
                  </div>
                <?php } else if ($dashboard["working_status"] == 'checkin') { ?>
                  <div class="box-right bg-c-1">
                    <div class="header-status">
                      <div class="sub-title mb-25px text-primary">การลงบันทึกประจำวัน</div>
                      <div class="title text-primary mb-5px">ลงบันทึกเข้างาน</div>
                      <div class="sub-title text-primary">สถานที่ <?= $dashboard['working_branch_name'] ?></div>
                    </div>
                    <div class="box-time">
                      <div class="icon me-3">
                        <?= file_get_contents('assets/icons/hourglass.svg') ?>
                      </div>
                      <div class="time-count event_time text-primary" data-time="<?= $dashboard['working_time_millisecond'] ?>">
                        <span class="count_time_hr">00 :</span> <span class="count_time_min">00 :</span> <span class="count_time_sec">00 </span>
                      </div>
                    </div>
                  </div>
                <?php } else if ($dashboard["working_status"] == 'checkout') { ?>
                  <div class="box-right bg-c-2">
                    <div class="header-status">
                      <div class="sub-title mb-25px text-success">การลงบันทึกประจำวัน</div>
                      <div class="title text-success mb-5px">ลงบันทึกออกงาน</div>
                      <div class="sub-title text-success">สถานที่ <?= $dashboard['working_branch_name'] ?></div>
                    </div>
                    <div class="box-time">
                      <div class="icon me-3">
                        <?= file_get_contents('assets/icons/hourglass.svg') ?>
                      </div>
                      <div class="time-count text-success">
                        <span> <?= W_Main::formatDate($dashboard['total_working_time'], 'H: i: s') ?> </span>
                      </div>
                    </div>
                  </div>
                <?php } else if ($dashboard["working_status"] == 'leave_work') { ?>
                  <div class="box-right">
                    <div class="header-status">
                      <div class="sub-title mb-25px text-secondary">การลงบันทึกประจำวัน</div>
                      <div class="title text-secondary mb-5px">คุณลางาน</div>
                      <div class="sub-title text-secondary">ไม่สามารถระบุสถานที่, เวลาเข้างานได้</div>
                    </div>
                    <div class="box-time">
                      <div class="icon me-3">
                        <?= file_get_contents('assets/icons/hourglass.svg') ?>
                      </div>
                      <div class="time-count text-secondary">
                        <span class="count_time_hr">00 :</span> <span class="count_time_min">00 :</span> <span class="count_time_sec">00 </span>
                      </div>
                    </div>
                  </div>
                <?php } else if ($dashboard["working_status"] == 'missing') { ?>
                  <div class="box-right bg-c-3">
                    <div class="header-status">
                      <div class="sub-title mb-25px text-danger">การลงบันทึกประจำวัน</div>
                      <div class="title text-danger mb-5px">คุณขาดงาน</div>
                      <div class="sub-title text-danger">สถานที่ <?= $dashboard['working_branch_name'] ?>, เวลาเข้างาน <?= W_Main::formatDate($dashboard['working_checkin_time'], 'H:i น.') ?></div>
                    </div>
                    <div class="box-time">
                      <div class="icon me-3">
                        <?= file_get_contents('assets/icons/hourglass.svg') ?>
                      </div>
                      <?php if ($dashboard['working_checkout_time']) { ?>
                        <div class="time-count text-danger">
                          <span> <?= W_Main::formatDate($dashboard['total_working_time'], 'H: i: s') ?> </span>
                        </div>
                      <?php } else { ?>
                        <div class="time-count event_time text-danger" data-time="<?= $dashboard['working_time_millisecond'] ?>">
                          <span class="count_time_hr">00 :</span> <span class="count_time_min">00 :</span> <span class="count_time_sec">00 </span>
                        </div>
                      <?php } ?>
                    </div>
                  </div>
                <?php } else if ($dashboard["working_status"] == 'holiday') { ?>
                  <div class="box-right">
                    <div class="header-status">
                      <div class="sub-title mb-25px text-secondary">การลงบันทึกประจำวัน</div>
                      <div class="title text-secondary mb-5px">วันหยุด</div>
                      <div class="sub-title text-secondary">ไม่สามารถระบุสถานที่, เวลาเข้างานได้</div>
                    </div>
                    <div class="box-time">
                      <div class="icon me-3">
                        <?= file_get_contents('assets/icons/hourglass.svg') ?>
                      </div>
                      <div class="time-count">
                        <span class="count_time_hr">00 :</span> <span class="count_time_min">00 :</span> <span class="count_time_sec">00 </span>
                      </div>
                    </div>
                  </div>
                <?php } ?>
              </div>
            </div>
            <a href="checkin_checkout.php" class="footer-action">
              <div>ประวัติการเข้า - ออกงาน</div>
              <div class="icon-arrow">
                <?= file_get_contents('assets/icons/arrow-right.svg') ?>
              </div>
            </a>
          </div>


          <div class="row mt-20px">
            <div class="col-lg-6 mb-20px">
              <div class="dashboard-box  box-ovv-2">
                <a href="record_ot_add.php" class="grid-container-ovv pb-0 d-block">
                  <div class="box-rlt">
                    <div class="header-ovv">
                      <div class="title">ขอล่วงเวลา (OT)</div>
                      <div class="sub-title">ยื่นคำร้องขอทำงานล่วงเวลา</div>
                    </div>
                    <img src="assets/images/dashboard-card-1.png" alt="">
                  </div>
                </a>
                <a href="record_ot.php" class="footer-action">
                  <div>ประวัติการขอทำงานล่วงเวลา</div>
                  <div class="icon-arrow">
                    <?= file_get_contents('assets/icons/arrow-right.svg') ?>
                  </div>
                </a>
              </div>
            </div>
            <div class="col-lg-6 mb-20px">
              <div class="dashboard-box  box-ovv-2">
                <a href="record_leavework_add.php" class="grid-container-ovv d-block pb-0">
                  <div class="box-rlt">
                    <div class="header-ovv">
                      <div class="title">ขอลางาน</div>
                      <div class="sub-title">
                        ยื่นขอลางานพร้อมแนบ <br>
                        เอกสารประกอบ
                      </div>
                    </div>
                    <img src="assets/images/dashboard-card-2.png" alt="">
                  </div>
                </a>
                <a href="record_of_leave_request.php" class="footer-action">
                  <div>ประวัติการขอลางาน</div>
                  <div class="icon-arrow">
                    <?= file_get_contents('assets/icons/arrow-right.svg') ?>
                  </div>
                </a>
              </div>
            </div>
          </div>

          <div class="dashboard-box">
            <div class="row">
              <div class="col-lg-6 mb-20px">
                <div class="card-dashboard">
                  <div class="grid-container-ovv pb-0">
                    <div class="header-ovv mb-20px">
                      <div class="title">การทำงานของฉัน</div>
                      <div class="sub-title">Performance การทำงานของฉันพร้อมสถิติการทำงาน</div>
                    </div>
                    <img src="assets/images/dashboard-card-3.png" alt="" class="img-fluid">
                  </div>
                </div>
              </div>
              <div class="col-lg-6 mb-20px">
                <div class="py-20px">
                  <div class="card-performance">
                    <div class="font-15px font-Medium text-secondary mb-10px">Performance</div>
                    <div class="progress-bar">
                      <div class="inner-progress-bar" style="width:<?= $dashboard['performance_percent'] ?>%"></div>
                    </div>
                    <div class="grid-perfomance mt-20px">
                      <div class="list-perfomance">
                        <div class="number"><?= $dashboard['count_missing'] ?></div>
                        <div class="text">ขาด</div>
                      </div>
                      <div class="list-perfomance">
                        <div class="number"><?= $dashboard['count_leave_work'] ?></div>
                        <div class="text">ลา</div>
                      </div>
                      <div class="list-perfomance">
                        <div class="number"><?= $dashboard['count_late'] ?></div>
                        <div class="text">มาสาย</div>
                      </div>
                      <div class="list-perfomance">
                        <div class="number"><?= $dashboard['count_day_work'] ?></div>
                        <div class="text">วันทำงาน</div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <?php Tiwdal::ajaxModal('record_checkin', 'modal-md modal-dialog-centered mx-auto  max-w-400px'); ?>
  <?php
  W_Structure::loadFooterForWebsite();
  W_Main::loadAsset('assets/js/main.js');
  ?>

  <?php if ($dashboard["working_status"] == 'checkin' || ($dashboard["working_status"] == 'missing' && !$dashboard['working_checkout_time'])) { ?>
    <script>
      var hoursLabel = document.querySelector(".count_time_hr");
      var minutesLabel = document.querySelector(".count_time_min");
      var secondsLabel = document.querySelector(".count_time_sec");
      var totalSeconds = parseInt(document.querySelector('.event_time').getAttribute('data-time'));
      setInterval(setTime, 1000);

      function setTime() {
        totalSeconds += 1000;
        var sec = Math.floor(totalSeconds / 1000);
        var minnute = Math.floor(sec / 60);
        var hr = Math.floor(minnute / 60);
        var real_sec = parseInt(sec % 60);
        var real_min = parseInt(minnute % 60);
        hoursLabel.innerHTML = pad(hr, 'hr');
        secondsLabel.innerHTML = pad(real_sec, 'sec');
        minutesLabel.innerHTML = pad(real_min, 'min');
      }

      function pad(val, mode) {
        var valString = val + "";
        if (valString.length < 2) {
          if (mode == 'min' || mode == 'hr') {

            return "0" + valString + " :";
          } else {
            return "0" + valString;
          }
        } else {
          if (mode == 'min' || mode == 'hr') {
            return valString + " :";
          } else {
            return valString;
          }
        }
      }
    </script>
  <?php } ?>
</body>

</html>