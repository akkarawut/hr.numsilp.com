<?php
require_once 'wloves/_framework/import_for_website.php';
if ($_POST) {
  if (isset($_POST['submit_login'])) {
    $result =  User::login($_POST['username'], $_POST['password'], []);
    if ($result['response_status']) {
      $_SESSION['user_id'] = $result['response_data']['id'];
      if ($result['response_data']['is_can_login']) {
        $redirect = 'dashboard.php';
      } else {
        session_destroy();
        $message = 'User Not allowed';
      }
    }
  }
  W_Main::responseAPI(/*$result, $message, $redirect*/);
}
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
  W_Main::loadAsset('assets/css/main.css');
  ?>
  <?php include_once 'layout/favicon.php' ?>
</head>

<body class="main-page-login">


  <form method="post" class="card-box-login">
    <div class="logo">
      <img src="assets/images/logo-md.png" alt="">
    </div>

    <div class="box-login-body">
      <div class="text-secondary font-Medium text-center mb-50px">
        ยินดีต้อนรับ!
      </div>

      <div class="hr-form-control mb-30px">
        <div class="icon">
          <?= file_get_contents('assets/icons/mail.svg') ?>
        </div>
        <div class="input-group">
          <div class="input-title">บัญชีผู้ใช้</div>
          <input type="text" class="input" name="username" required>
        </div>
      </div>
      <div class="hr-form-control mb-50px">
        <div class="icon">
          <?= file_get_contents('assets/icons/padlock.svg') ?>
        </div>
        <div class="input-group">
          <div class="input-title">รหัสผ่าน</div>
          <input type="password" class="input" name="password" required>
          <button type="button" class="btn-password-manage" data-status="hide"></button>
        </div>
      </div>

      <button type="submit" name="submit_login" class="btn btn-primary btn-block h-60px font-18px fw-semibold w-100 border-radius-10px">เข้าสู่ระบบ</button>
    </div>

  </form>

  <?php
  W_Structure::loadFooterForWebsite();
  ?>

  <script>
    w(document).on(
      "click",
      ".hr-form-control .btn-password-manage",
      function() {
        var scope = w(this).parents(".hr-form-control");
        var status = w(this).attr("data-status");
        if (status == "show") {
          w(this).attr("data-status", "hide");
          scope.find(".input").attr("type", "password");
        } else {
          w(this).attr("data-status", "show");
          scope.find(".input").attr("type", "text");
        }
      }
    );
  </script>
</body>

</html>