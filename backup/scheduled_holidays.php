<?php
$user_permission['_permission'] = ['all', 'ywptz_user', 'niemz_user', 'bmiwk_user', 'noaxt_user', 'xacpn_user'];
require_once 'wloves/_framework/import_for_website.php';
require_once 'layout/init.php';
$name_page = 'ตั้งค่าวันหยุด';
if ($_POST) {
  if (isset($_POST['submit_add_holiday'])) {
    $data = [
      'title' => isset($_POST['title']) && $_POST['title'] ? $_POST['title'] : '',
      'type' => isset($_POST['type']) && $_POST['type'] ? $_POST['type'] : '',
      'from_date' => isset($_POST['from_date']) && $_POST['from_date'] ? $_POST['from_date'] : '',
      // 'from_time' => isset($_POST['from_time']) && $_POST['from_time'] ? $_POST['from_time'] : '',
      'to_date' => isset($_POST['to_date']) && $_POST['to_date'] ? $_POST['to_date'] : $_POST['from_date'],
      // 'to_time' =>isset($_POST['to_time']) && $_POST['to_time'] ? $_POST['to_time'] : '',
      'description' => isset($_POST['description']) && $_POST['description'] ? $_POST['description'] : '',
      'role_use' => isset($_POST['role_use']) && $_POST['role_use'] ? $_POST['role_use'] : [],
      'request_user_ids' => isset($_POST['request_user_ids']) && $_POST['request_user_ids'] ? $_POST['request_user_ids'] : [],
    ];
    $result  = phr_setting_holiday::addNewSettingHoliday(API_CODE, $data);
    if ($result['response_status']) {
      $redirect = 'scheduled_holidays_detail.php?id=' . $result['response_data']['insert_id'];
    }
  }
  W_Main::responseAPI(/*$result, $message, $redirect*/);
}


function templateTypeDateFull()
{
  ob_start(); ?>
  <div class="col-12 mb-15px">
    <div class="hr-form-control">
      <div class="input-group">
        <div class="input-title required">วันที่หยุด</div>
        <input type="date" class="input" name="from_date" required>
      </div>
    </div>
  </div>
<?php
  return ob_get_clean();
}

function templateTypeDateHalf()
{
  ob_start(); ?>
  <div class="col-12 mb-15px">
    <div class="hr-form-control">
      <div class="input-group">
        <div class="input-title required">วันที่เริ่ม</div>
        <input type="date" class="input" name="from_date" required>
      </div>
    </div>
  </div>
  <div class="col-12 mb-15px">
    <div class="hr-form-control">
      <div class="input-group">
        <div class="input-title required">ถึงวันที่</div>
        <input type="date" class="input" name="to_date" required>
      </div>
    </div>
  </div>
<?php
  return ob_get_clean();
}


$type_dropdown = phr_helper::dropdownWorkingTime(API_CODE);
$emp_dropdown = phr_helper::dropdownEmployee(API_CODE);
?>
<!-- MENU::ตั้งค่า_ตั้งค่าวันหยุด -->
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
  W_Main::loadAsset('assets/plugin/select2/css/select2.min.css');
  ?>
  <?php include_once 'layout/favicon.php' ?>
</head>

<body>
  <?php include_once 'layout/navbar.php' ?>
  <div id="hr-body">
    <?php include_once 'layout/sidebar.php' ?>
    <div id="hr-content" class="bg-white">
      <div class="container-fluid mt-5">
        <div class="max-w-500px mx-auto">
          <div class="d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center">
              <div class="icon-back mr-10px">
                <a href="setting_holiday.php"> <?= file_get_contents('assets/icons/icon-left-arrow.svg') ?></a>
              </div>
              <h1 class="title-header m-0">กำหนดวันหยุดใหม่</h1>
            </div>
          </div>
        </div>
        <hr>
      </div>

      <form method="post">
        <div class="container">
          <div class="max-w-500px mx-auto">
            <p class="sub-title my-25px">กำหนดรายละเอียดวันหยุดและเงื่อนไขการ ได้หยุดงานของพนักงาน</p>
            <div class="card rounded-20px">
              <div class="card-body py-25px px-30px">
                <div class="row">
                  <div class="col-12 mb-15px">
                    <div class="font-15px fw-semibold">รายละเอียด</div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-12 mb-15px">
                    <div class="hr-form-control">
                      <div class="input-group">
                        <div class="input-title required">ชื่อวันหยุด</div>
                        <input type="text" class="input" name="title" placeholder="ระบุ" required>
                      </div>
                    </div>
                  </div>

                  <div class="col-12 my-15px">
                    <div class="btn-group-radio">
                      <label for="type_full" class="card-select-label event_change_type">
                        <input type="radio" id="type_full" name="type" value="one_day" checked>
                        <div class="card-select-content">
                          <div class="font-16px fw-medium txt-selected"> หยุด 1 วัน </div>
                        </div>
                      </label>
                      <label for="type_half" class="card-select-label event_change_type">
                        <input type="radio" id="type_half" name="type" value="period">
                        <div class="card-select-content">
                          <div class="font-16px fw-medium txt-selected"> หยุดเป็นช่วง </div>
                        </div>
                      </label>
                    </div>
                  </div>

                  <div class="scope_date">
                    <?= templateTypeDateFull() ?>
                  </div>

                  <div class="col-lg-12 mb-15px">
                    <div class="hr-form-control">
                      <div class="input-group">
                        <div class="input-title">รายละเอียดเพิ่มเติม</div>
                        <textarea name="description" class="input"></textarea>
                      </div>
                    </div>
                  </div>

                  <div class="col-lg-12 mb-15px">
                    <div class="font-15px fw-semibold mb-5px">กำหนดสิทธิพนักงานที่ได้หยุดงาน</div>
                    <div>
                      เลือกรูปแบบพนักงานที่ได้รับสิทธิหยุดงาน (คุณสามารถตั้งค่ารูปแบบ
                      พนักงานได้ที่ <a href="setting_working_time.php" class="text-primary text-decoration-underline">ตั้งค่ารูปแบบเวลาทำงาน</a>)
                    </div>
                  </div>

                  <div class="col-lg-12 mb-15px">
                    <?php foreach ($type_dropdown as $type) { ?>
                      <div class="mb-5px">
                        <?php
                        TiwForm::normal('checkbox', $type['id'], ['name' => 'role_use[]', 'class' => '', 'checked' => 1], ['style' => '3', 'label' => $type['title']]);
                        ?>
                      </div>
                    <?php } ?>
                  </div>

                  <div class="col-lg-12 mb-15px">
                    <div class="font-15px fw-semibold mb-5px">พนักงานที่ต้องมาทำงาน</div>
                  </div>

                  <div class="col-lg-12 mb-15px">
                    <div class="hr-form-control">
                      <div class="input-group">
                        <div class="input-title">เลือกพนักงาน</div>
                        <select class="select2-multiple" name="request_user_ids[]" multiple="multiple">
                          <?php foreach ($emp_dropdown as $emp) { ?>
                            <option value="<?= $emp['id'] ?>"><?= $emp['title_dropdown'] ?></option>
                          <?php } ?>
                        </select>
                      </div>
                    </div>
                  </div>
                  <div class="col-lg-12">
                    <div class="d-flex justify-content-end">
                      <a href="setting_holiday.php" class="btn btn-default btn-lg rounded-15px max-w-150px w-100 me-2">
                        <div class="fw-semibold">ยกเลิก</div>
                      </a>
                      <button type="submit" name="submit_add_holiday" class="btn btn-primary btn-lg rounded-15px max-w-150px w-100">
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

    <?php
    W_Structure::loadFooterForWebsite();
    W_Main::loadAsset('assets/js/main.js');
    W_Main::loadAsset('assets/plugin/select2/js/select2.min.js');
    ?>

    <script>
      $('.select2-multiple').select2({
        placeholder: 'พิมพ์ชื่อ หรือ เลือกพนักงาน',
        allowClear: true,
        // minimumInputLength: 1
      });

      w(document).on('click', '.event_change_type', function() {
        let val = w(this).find('input').val();
        if (val == 'one_day') {
          w('.scope_date').html(`<?= templateTypeDateFull(); ?>`);
        } else {
          w('.scope_date').html(`<?= templateTypeDateHalf(); ?>`);
        }
      });
    </script>
</body>

</html>