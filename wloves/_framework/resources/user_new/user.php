<?php
$_PAGE['permission'] = ['user', 'user_profile', ''];
require_once '../../../_framework/import.php';
W_Structure::loadModules(['boatnav', 'jform']);

$id = isset($_GET['id']) ? $_GET['id'] : '';
$is_edit = isset($_GET['is_edit']) ? $_GET['is_edit'] : '';
$get_user = User::getCurrent();
$code = $get_user['user_code'];
$is_profile = true;
$id = $get_user['id'];
$module_setup =  User::getModuleConfigByUserID($get_user['id']);
if ($_POST) {
  if (isset($_POST['submit_change_user_password'])) {
    $no_verify = isset($_POST['no_verify']) ? $_POST['no_verify'] : false;
    $new_password = $_POST['new_password'];
    $verify_new_password = isset($_POST['verify_new_password']) ? $_POST['verify_new_password'] : '';
    if ($new_password == $verify_new_password || $no_verify) {
      $result = User::forceChangePassword($id, $new_password);
    } else {
      $result = [
        'response_message' => Ty::get('password_doesnt_match'),
        'response_status' => false
      ];
    }
  }
  W_Main::responseAPI();
}

$get_module = Module::getModuleByCode($code);
$module_id = $get_module['id'];
?>
<!DOCTYPE html>
<html lang="en" data-theme="<?= W_Structure::getThemeClass() ?>">

<head>
  <?php
  W_Structure::loadMeta();
  W_Main::loadAsset('assets/css/user.css');
  ?>
</head>

<body>
  <?php include_once '../layout/header-default.php'; ?>
  <div class="container-fluid ">
    <div class="row pt-10px justify-content-center">
      <div class="col-lg-6 col-md-space">
        <?php
        if ($module_setup['create_tab']) {
          include_once 'view/multiple.php';
        } else {
          include_once 'view/single.php';
        }
        ?>
      </div>
    </div>
  </div>
  <?php
  include_once '../layout/footer.php';
  W_Structure::loadFooter();
  ?>
  <script>
    function eventVerifyPassword(e) {
      let verify_password = e.target.value;
      let input_password = e.target.closest('.modal-body').querySelector('.new_password');
      let text_verify = e.target.closest('.modal-body').querySelector('.text_verify');
      let password = input_password.value;
      let is_match = verify_password == password;
      if (!is_match) {
        text_verify.innerHTML = 'รหัสผ่านไม่ตรงกัน';
      } else {
        text_verify.innerHTML = '';
      }
    }

    function eventToggleShow(e) {
      let target = e.target;
      let parent = target.closest('.permission-list');
      let table = parent.querySelector('.permission-list-table');
      let arrow = parent.querySelector('.arrow');
      if (arrow.classList.contains('active')) {
        arrow.classList.remove('active');
        table.classList.remove('active');
      } else {
        arrow.classList.add('active');
        table.classList.add('active');
      }
    }


    var rtime;
    var timeout = false;
    var delay = 400;

    function sendDataAutoPermission(e) {
      rtime = new Date();
      if (timeout === false) {
        timeout = true;
        setTimeout(sendData, delay);
      }
    }

    function sendData(e) {
      let url = 'ajax/ajax_user_permission.php';
      if (new Date() - rtime < delay) {
        setTimeout(sendData, delay);
      } else {
        timeout = false;
        wx.formData(url, 'form_permission', function(data) {
          var result = JSON.parse(data);
          w.notification(result.response_status ? 'success' : 'error', result.response_message);
          window.location.reload();
        });
      }
    }

    function eventUpload(e) {
      let target = e.target;
      let parent = target.closest('.profile-image');
      let input = parent.querySelector('input[type="file"]');
      input.addEventListener('change', uploadImageProfile);
      input.click();
    }

    function uploadImageProfile(e) {
      let url = 'ajax/ajax_user_images.php';
      wx.formData(url, 'form_user_images', function(data) {
        var result = JSON.parse(data);
        w.notification(result.response_status ? 'success' : 'error', result.response_message);
        window.location.reload();
      });
    }
    let submit_extra_form = document.getElementById('__submit_extra_form');
    let event_submit_form = document.getElementById('event_submit_form');
    let form_user_detail = document.getElementById('form_user_detail');
    event_submit_form.addEventListener('click', (e) => {
      submit_extra_form.click();
    });

    form_user_detail.addEventListener('submit', function(e) {
      e.preventDefault();
      let url = 'ajax/ajax_edit_user_detail.php?c=<?= $_GET['c'] ?>&id=<?= $id ?>';
      wx.formData(url, 'form_user_detail', function(data) {
        let result = JSON.parse(JSON.parse(data));
        w.notification(result.response_status ? 'success' : 'error', result.response_message);
        window.location.reload();
      });
    });
  </script>
</body>

</html>