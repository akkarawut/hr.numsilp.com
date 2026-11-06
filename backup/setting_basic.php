<?php
$user_permission['_permission'] = ['all', 'ywptz_user', 'niemz_user', 'bmiwk_user', 'noaxt_user', 'xacpn_user'];
require_once 'wloves/_framework/import_for_website.php';
require_once 'layout/init.php';
$name_page = 'การทำงานของระบบ';
$page = isset($_GET['page']) ? $_GET['page'] : '';
$setting_basic = phr_setting_basic::getSettingBasic(API_CODE);
if ($_POST) {
  if (isset($_POST['submit_update_setting_basic'])) {
    unset($_POST['submit_update_setting_basic']);
    $result = phr_setting_basic::updateSettingBasic(API_CODE, $_POST);
    if ($result['response_status']) {
      $redirect = 'setting_system.php?page=set_setting_basic';
    }
  }
  W_Main::responseAPI(/*$result, $message, $redirect*/);
}

function templateSecure($setting_basic)
{
  ob_start(); ?>

  <div class="col-lg-6 mb-15px">
    <div class="hr-form-control">
      <div class="input-group">
        <div class="input-title required">อัตราหัก</div>
        <input type="number" step="0.01" class="input" name="security_price" value="<?= $setting_basic['security_price'] ?>" required>
      </div>
    </div>
  </div>
  <div class="col-lg-6 mb-15px">
    <div class="hr-form-control">
      <div class="input-group">
        <div class="input-title required">หน่วย</div>
        <select name="security_unit" class="input" required>
          <option value="" selected disabled>เลือก</option>
          <option value="bath" <?= $setting_basic['security_unit'] == 'bath' ? 'selected' : ''  ?>>บาท</option>
          <option value="percent" <?= $setting_basic['security_unit'] == 'percent' ? 'selected' : ''  ?>>เปอร์เซ็นต์</option>
        </select>
      </div>
    </div>
  </div>

<?php
  return ob_get_clean();
}

?>
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
                <a href="setting_system.php?page=set_setting_basic"> <?= file_get_contents('assets/icons/icon-left-arrow.svg') ?></a>
              </div>
              <h1 class="title-header m-0">ตั้งค่าข้อมูลเบื้องต้น</h1>
            </div>
          </div>
        </div>
        <hr>
      </div>

      <form method="post">
        <div class="container">
          <div class="max-w-500px mx-auto">
            <?php if ($page == 'setting_basic_security_deductions') { ?>
              <?php include_once 'view/setting/system/setting_basic_security_deductions.php' ?>
            <?php } else if ($page == 'setting_basic_tax') {  ?>
              <?php include_once 'view/setting/system/setting_basic_tax.php' ?>
            <?php } ?>
          </div>
        </div>
      </form>
    </div>
    <?php
    W_Structure::loadFooterForWebsite();
    W_Main::loadAsset('assets/js/main.js');
    ?>

    <script>
      w(document).on('change', '.event_select_type_secure input', function() {
        let val = w(this).val();
        if (val == 1) {
          w('#scope_type_secure').html(`<?= templateSecure($setting_basic); ?>`);
        } else {
          w('#scope_type_secure').html('');
        }
      });
    </script>
</body>

</html>