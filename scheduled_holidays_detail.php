<?php
$user_permission['_permission'] = ['all', 'ywptz_user', 'niemz_user', 'bmiwk_user', 'noaxt_user', 'xacpn_user'];
require_once 'wloves/_framework/import_for_website.php';
require_once 'layout/init.php';
require_once 'component/navtab.php';
$name_page = 'ตั้งค่าวันหยุด';
$id = isset($_GET['id']) && $_GET['id'] ? $_GET['id'] : 0;
$is_edit = isset($_GET['is_edit']) && $_GET['is_edit'] ? $_GET['is_edit'] : 0;
$holiday = phr_setting_holiday::getSettingHolidayByID(API_CODE, $id);
$type_dropdown = phr_helper::dropdownWorkingTime(API_CODE);
$emp_dropdown = phr_helper::dropdownEmployee(API_CODE);

if ($_POST) {
  if (isset($_POST['submit_update_holiday'])) {
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
    $result = phr_setting_holiday::updateSettingHoliday(API_CODE, $holiday['id'], $data);
    if ($result['response_status']) {
      $redirect = 'scheduled_holidays_detail.php?id=' . $holiday['id'];
    }
  } else if (isset($_POST['submit_delete_data'])) {
    $result = phr_setting_holiday::deleteSettingHoliday(API_CODE, $_POST['id']);
    if ($result['response_status']) {
      $redirect = 'setting_holiday.php';
    }
  }
  W_Main::responseAPI(/*$result, $message, $redirect*/);
}

function templateTypeDateFull($data)
{
  ob_start(); ?>
  <div class="col-12 mb-15px">
    <div class="hr-form-control">
      <div class="input-group">
        <div class="input-title required">วันที่หยุด</div>
        <input type="date" class="input" value="<?= $data['from_date'] ?>" name="from_date" required>
      </div>
    </div>
  </div>
<?php
  return ob_get_clean();
}

function templateTypeDateHalf($data)
{
  ob_start(); ?>
  <div class="col-12 mb-15px">
    <div class="hr-form-control">
      <div class="input-group">
        <div class="input-title required">วันที่เริ่ม</div>
        <input type="date" class="input" value="<?= $data['from_date'] ?>" name="from_date" required>
      </div>
    </div>
  </div>
  <div class="col-12 mb-15px">
    <div class="hr-form-control">
      <div class="input-group">
        <div class="input-title required">ถึงวันที่</div>
        <input type="date" class="input" value="<?= $data['to_date'] ?>" name="to_date" required>
      </div>
    </div>
  </div>
<?php
  return ob_get_clean();
}
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
                <?php if ($is_edit) { ?>
                  <a href="scheduled_holidays_detail.php?id=<?= $id ?>"> <?= file_get_contents('assets/icons/icon-left-arrow.svg') ?></a>
                <?php } else { ?>
                  <a href="setting_holiday.php"> <?= file_get_contents('assets/icons/icon-left-arrow.svg') ?></a>
                <?php } ?>
              </div>
              <h1 class="title-header m-0">รายละเอียดวันหยุด</h1>
            </div>
            <?php if (!$is_edit) { ?>
              <div class="icon-action">
                <div class="d-flex">
                  <a href="scheduled_holidays_detail.php?id=<?= $id ?>&is_edit=1" class="btn btn-c-icon circle btn-c-primary">
                    <?= file_get_contents('assets/icons/pencil.svg') ?>
                  </a>
                  <button type="button" class="btn btn-c-icon circle btn-c-danger" <?php Tiwdal::register('delete_data', $holiday); ?>>
                    <?= file_get_contents('assets/icons/delete.svg') ?>
                  </button>
                </div>
              </div>
            <?php } ?>
          </div>
        </div>
        <hr>
      </div>

      <form method="post">
        <div class="container">
          <div class="max-w-500px mx-auto">
            <p class="sub-title my-25px">กำหนดรายละเอียดวันหยุดและเงื่อนไขการ ได้หยุดงานของพนักงาน</p>
            <?php if ($is_edit) { ?>
              <?php include_once 'view/setting/holiday/form_edit.php' ?>
            <?php } else {  ?>
              <?php include_once 'view/setting/holiday/form_detail.php' ?>
            <?php } ?>
          </div>
        </div>
      </form>
    </div>

    <?php Tiwdal::startModal('delete_data', 'modal-md modal-dialog-centered mx-auto  max-w-500px'); ?>
    <form action="" method="post">
      <div class="modal-header justify-content-center">
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
          <span class="font-18px" aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body px-20px m-auto max-w-450px pt-0">
        <div class="font-24px font-SemiBold  text-center mb-15px"><?= Ty::get(['ลบข้อมูลวันหยุด', '']) ?></div>
        <div class="text-center">
          <div>
            คุณแน่ใจหรือไม่ที่จะลบข้อมูลวันหยุดนี้ วันที่กำหนดไว้จะถูก <br>
            ตีกลับมาเป็นวันทำงานตามปกติอีกครั้ง
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <input type="hidden" name="{id}">
        <?php
        TiwForm::normal('btn', '', ['name' => '', 'type' => 'button', 'class' => 'btn-close-modal min-w-90px', 'data-bs-dismiss' => 'modal'], ['text' => Ty::get(['ยกเลิก', ''])]);
        TiwForm::normal('btn', '', ['name' => 'submit_delete_data', 'type' => 'submit', 'class' => 'btn-danger min-w-120px'], ['text' => Ty::get(['ลบข้อมูล', ''])]);
        ?>
      </div>
    </form>
    <?php Tiwdal::endModal(); ?>
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
          w('.scope_date').html(`<?= templateTypeDateFull($holiday); ?>`);
        } else {
          w('.scope_date').html(`<?= templateTypeDateHalf($holiday); ?>`);
        }
      });
    </script>
</body>

</html>