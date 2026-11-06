<?php
$_PAGE['permission'] = ['user', 'user_main', ''];
require_once '../../../_framework/import.php';
W_Structure::loadModules(['boatnav', 'jform']);

$code = $_GET['c'];
$id = isset($_GET['id']) ? $_GET['id'] : '';
$is_edit = isset($_GET['is_edit']) ? $_GET['is_edit'] : '';
$is_edit_permission = isset($_GET['is_edit_permission']) ? $_GET['is_edit_permission'] : '';
$module_setup =  Module::getModuleConfig($code);
if ($id) {
  $get_user = User::getUserByID($id);
}
if ($_POST) {
  if (isset($_POST['submit_add_user'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $verify_password = $_POST['verify_password'];
    if ($verify_password == $password) {

      $function = isset($_POST['function']) ? $_POST['function'] : [];
      $check_function = isset($_POST['check_function']) ? $_POST['check_function'] : [];

      if ($function) {
        foreach ($function as $key => $list) {
          $function[$key] = isset($check_function[$key]) ? 1 : 0;
        }
      }
      $data = [
        'username' => $username,
        'password' => $password,
      ];
      if ($_POST['is_permission']) {
        $data['permission'] = $function;
      }

      $file = '';
      if ($_POST['is_profile']) {
        $file = $_FILES['user_profile_image'];
      }
      $result = User::addNewUser($code, $data, $file);
      if ($result['response_status']) {
        if (isset($_POST['template'])) {
          $insert_id = $result['response_data']['insert_id'];
          Jform::post_extra_form($code, $insert_id, $_POST['template']);
        }
      }
    } else {
      $result = [
        'response_message' => 'รหัสผ่านไม่ตรงกัน',
        'response_status' => false
      ];
    }
  } else if (isset($_POST['submit_change_user_password'])) {

    $no_verify = isset($_POST['no_verify']) ? $_POST['no_verify'] : false;
    $new_password = $_POST['new_password'];
    $verify_new_password = isset($_POST['verify_new_password']) ? $_POST['verify_new_password'] : '';
    if ($new_password == $verify_new_password || $no_verify) {
      $result = User::forceChangePassword($id, $new_password);
    } else {
      $result = [
        'response_message' => 'รหัสผ่านไม่ตรงกัน',
        'response_status' => false
      ];
    }
  } else if (isset($_POST['submit_delete_user'])) {
    $user_id = $_POST['id'];
    $result = User::deleteUser($user_id);
    $redirect = '?c=' . $_GET['c'];
  } else if (isset($_POST['submit_update_user_permission'])) {
    $function = $_POST['function'];
    $check_function = isset($_POST['check_function']) ? $_POST['check_function'] : [];
    foreach ($function as $key => $list) {
      $function[$key] = isset($check_function[$key]) ? 1 : 0;
    }
    $data = [
      'permission' => $function
    ];
    $result = User::updateUser($_POST['id'], $data);
    $redirect = ($result['response_status']) ? '?c=' . $_GET['c'] . '&id=' . $_POST['id'] . '&page=' . $_GET['page'] : '';
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
  <?php
  if ($module_setup['page_display'] == 'half') {
    include_once 'view/user_half.php';
  } else {
    include_once 'view/user_full.php';
  }
  ?>

  <?php Tiwdal::startModal('modal_add_user', 'max-w-700px', ['slide' => true]); ?>
  <form method="post" enctype="multipart/form-data" class="form-loading">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><?= Ty::get('add_new_user') ?></h5>
        <div class="d-flex h-50px">
          <?php
          TiwForm::normal('btn', '', ['type' => 'button', 'class' => 'no-radius h-100 min-w-100px btn-outline-light br-left-only', 'data-bs-dismiss' => 'modal'], ['text' => Ty::get('cancel')]);
          TiwForm::normal('btn', '', ['name' => 'submit_add_user', 'type' => 'submit', 'class' => 'no-radius h-100 min-w-150px btn-primary'], ['text' => Ty::get('confirm')]);
          ?>
        </div>
      </div>
      <div class="modal-body p-0">
        <?php
        include_once 'view/add/account.php';
        include_once 'view/add/profile.php';
        include_once 'view/add/detail.php';
        include_once 'view/add/permission.php';
        ?>
      </div>
    </div>
  </form>
  <?php Tiwdal::endModal() ?>

  <?php Tiwdal::startModal('modal_delete_user', 'max-w-650px'); ?>
  <form action="" method="post">
    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"></button>
    <div class="modal-body">
      <div class="font-18px font-SemiBold text-info mb-15px text-center pt-10px"><?= Ty::get('delete_data') ?> <?= $module_setup['name'] ?></div>
      <div class="d-flex justify-content-center">
        <div class="font-16px font-Regular text-info text-center max-w-500px">
          <?= Ty::get('del_user_1', ['name' => '<span class="text-danger"> “ <span name="{username}"></span> ”</span>']) ?>
        </div>
      </div>
    </div>
    <div class="modal-footer py-5px">
      <?php
      TiwForm::normal('hidden', '', ['name' => '{id}']);
      TiwForm::normal('btn', '', ['name' => '', 'type' => 'button', 'class' => 'btn-close-modal min-w-90px', 'data-bs-dismiss' => 'modal'], ['text' =>  Ty::get('cancel'), 'type' => '']);
      TiwForm::normal('btn', '', ['name' => 'submit_delete_user', 'type' => 'submit', 'class' => 'btn-danger min-w-120px'], ['text' => 'ลบข้อมูล', 'type' => '']);
      ?>
    </div>
  </form>
  <?php Tiwdal::endModal() ?>



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

    function sendData(e) {
      let url = 'ajax/ajax_user_permission.php';
      wx.formData(url, 'form_permission', function(data) {
        var result = JSON.parse(data);
        w.notification(result.response_status ? 'success' : 'error', result.response_message);
        window.location.href = '?c=<?= $_GET['c'] ?>&id=<?= $id ?>'
      });
    }

    function eventUploadAdd(e) {
      let target = e.target;
      let parent = target.closest('.profile-image');
      let input = parent.querySelector('input[type="file"]');
      // add event change
      input.click();
    }

    function eventUpload(e) {
      let target = e.target;
      let parent = target.closest('.profile-image');
      let input = parent.querySelector('input[type="file"]');
      // add event change
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
    };


    function togglePermissionFn(t) {
      var check_view_ele = t.closest('tr').querySelector('.scope_fn_permission').querySelectorAll('div');
      check_view_ele.forEach(function(item) {
        if (t.checked) {
          item.classList.remove('d-none');
          item.querySelector('input[type="checkbox"]').checked = true;
        } else {
          item.classList.add('d-none');
          item.querySelector('input[type="checkbox"]').checked = false;
        }
      });
    }

    function checkAllPermission(t, bool) {
      var permission_lists = t.closest('.permission-list').querySelectorAll('.scope_default_permission');
      var is_active = t.closest('.permission-list').querySelector('.arrow').classList.contains('active');
      if (!is_active) {
        t.closest('.permission-list').querySelector('.arrow').classList.add('active');
        t.closest('.permission-list').querySelector('.permission-list-table').classList.add('active');
      }
      permission_lists.forEach(function(item) {
        var ele = item.querySelector('input[type="checkbox"]');
        ele.checked = bool;
        togglePermissionFn(ele);
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
        // window.location.reload();
        window.location.href = '?c=<?= $_GET['c'] ?>&id=<?= $id ?>'
      });
    });
  </script>
</body>

</html>