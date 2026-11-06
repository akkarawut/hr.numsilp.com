<?php
$_PAGE['permission']  = ['landing', '', ''];
$prefix = '../../../';
require $prefix . '_framework/import.php';

// $dev_id =  Dev::getCurrentUserID();
// $user_id = W_User::getCurrentUserID();
// if (!$dev_id && !$user_id) {
//   W_Main::notification('You are not logged in', 'error');
//   W_Main::redirect('../login/index.php');
// }
$action = isset($_GET['action']) ? $_GET['action'] : 'welcome';

$content = getContent($action);

function getContent($action)
{
  $return = [
    'title' => '',
    'info'  => ''
  ];

  if ($action == 'welcome') {
    $return['title'] = 'Welcome!';
    $return['info']  = 'To WLoves Management System.';
  } else if ($action == 'error') {
    $return['title'] = 'คุณไม่มีสิทธิเข้าถึงข้อมูลส่วนนี้';
    $return['info']  = 'โปรดติดต่อผู้ดูแลระบบ<br>
      Please contact your administrator.<br>
      管理者に連絡してください。<br>
      Обратитесь к своему администратору.<br>
      Bitte wenden Sie sich an Ihren Administrator.<br>
      សូមទាក់ទងអ្នកគ្រប់គ្រងរបស់អ្នក។ <br>
      ກະລຸນາຕິດຕໍ່ຜູ້ເບິ່ງແຍງລະບົບຂອງທ່ານ. <br>
      Vui lòng liên hệ với quản trị viên của bạn. <br>
      Sila hubungi pentadbir anda. <br>
      Neem contact op met uw administrator. <br>
      আপনার প্রশাসকের সাথে যোগাযোগ করুন। <br>
      Veuillez contacter votre administrateur. <br>
      Επικοινωνήστε με τον διαχειριστή σας.';
    $return['img'] = '../../structure/image/icon/bg-no-permission.svg';
  } else {
    $return['title'] = 'คุณไม่มีสิทธิเข้าถึงข้อมูลส่วนนี้';
    $return['info']  = 'หากมีความจำเป็น ต้องการเข้าถึงข้อมูลหรือมีข้อสงสัย โปรดติดต่อผู้ดูแลระบบ';
    $return['img']   = '../../structure/image/icon/bg-no-permission.svg';
  }

  return $return;
}
?>

<!DOCTYPE html>
<html lang="en" data-theme="<?= W_Structure::getThemeClass() ?>">

<head>
  <?php
  W_Structure::loadMeta($prefix);
  W_Main::loadAsset('../../structure/css/animate.css');
  ?>
</head>

<body>
  <?php include '../layout/header-default.php'; ?>

  <div class="container-fluid">
    <div class="row">
      <div class="col-12">
        <?php if ($action == 'welcome') { ?>
          <div class="landing-container animate__animated animate__fadeIn">
            <div class="landing-wrapper">
              <h2 class="landing-title"><?= $content['title']; ?></h2>
              <h4 class="landing-info"><?= $content['info']; ?></h4>
            </div>
          </div>
        <?php } else if ($action == 'error') { ?>
          <div class="row h-90vh">
            <div class="col-xl-5">
              <h3 class=""><?= $content['title']; ?></h3>
              <h4 class="text-secondary font-18px"><?= $content['info']; ?></h4>
            </div>
            <div class="col-xl-7 d-flex align-items-end">
              <div class="img-error-permission">
                <?= file_get_contents($content['img']); ?>
              </div>
            </div>

          </div>
        <?php } else { ?>
          <div class="area-no-permission">
            <div class="d-flex align-items-center">
              <div class="mr-5px">
                <?= file_get_contents('../../structure/image/icon/lightbulb.svg'); ?>
              </div>
              <h3 class=""><?= $content['title']; ?></h3>
            </div>
            <div class="area-text">
              <p class="text-secondary"><?= $content['info']; ?></p>
            </div>
            <div class="bg-permission calc-180px">
              <?= file_get_contents($content['img']); ?>
            </div>
          </div>
        <?php } ?>
      </div>
    </div>
  </div>

  <?php
  include '../layout/footer.php';
  W_Structure::loadFooter($prefix);
  ?>
</body>

</html>