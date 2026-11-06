<?php
$_PAGE['permission'] = ['user', 'user_main', 'user_manage_basic_setting'];
require_once '../../../_framework/import.php';
W_Structure::loadModules(['input-pattern', 'boatnav']);

$is_edit = isset($_GET['is_edit']) ? $_GET['is_edit'] : '0';
$user_type = (isset($_GET['user_type']) && $_GET['user_type']) ? $_GET['user_type'] : '';
$type = (isset($_GET['type']) && $_GET['type']) ? $_GET['type'] : '';
$page = (isset($_GET['page']) && $_GET['page']) ? $_GET['page'] : '';

$user_data = WLoves::getInitialData();
$user_info = $user_data['current_user_data']['User'];
$user_id = $user_info['id'];
$login_user_type_id = $user_info['user_type_id'];

$menus = User_Basic_Setting::selectMenu();
$user_permission = User_Permission::getUserPermissionList($user_id);

$count_permission = 0;
//วาดเมนู User Basic Setting
function templateBasicSettingMenu($menu)
{
  global $_GET, $user_type, $page, $type, $user_permission, $login_user_type_id, $count_permission;

  $hide_permission = User_type::getHiddenPermissionList($menu['user_type_id']);

  $sub_menu = $menu['setting_list'];

  $nav_temp = [];
  if (
    isset($sub_menu['permission_template']) &&
    !isset($hide_permission['user_type_' . $menu['user_type_id'] . '_permission_template']) &&
    isset($user_permission['user_type_' . $login_user_type_id . '_permission_template'])
  ) {
    $nav_temp[] = [
      'id'    => $menu['user_type_id'] . '&type=1',
      'name'  => 'Permission Template',
      'icon'  => 'resources/user/assets/image/icon/navigate.svg',
      'count' => $sub_menu['permission_template'],
    ];
    $count_permission++;
  }
  if (
    isset($sub_menu['has_team']) &&
    !isset($hide_permission['user_type_' . $menu['user_type_id'] . '_team']) &&
    isset($user_permission['user_type_' . $login_user_type_id . '_team'])
  ) {
    $nav_temp[] = [
      'id'    => $menu['user_type_id'] . '&type=2',
      'name'  => 'Team',
      'icon'  => 'resources/user/assets/image/icon/icon-user.svg',
      'count' => $sub_menu['has_team'],
    ];
    $count_permission++;
  }
  if (
    isset($sub_menu['has_category']) &&
    !isset($hide_permission['user_type_' . $menu['user_type_id'] . '_category']) &&
    isset($user_permission['user_type_' . $login_user_type_id . '_category'])
  ) {
    $nav_temp[] = [
      'id'    => $menu['user_type_id'] . '&type=3',
      'name'  => 'Category',
      'icon'  => 'resources/user/assets/image/icon/group.svg',
      'count' => $sub_menu['has_category'],
    ];
    $count_permission++;
  }
  if (
    isset($sub_menu['has_tag']) &&
    !isset($hide_permission['user_type_' . $menu['user_type_id'] . '_tag']) &&
    isset($user_permission['user_type_' . $login_user_type_id . '_tag'])
  ) {
    $nav_temp[] = [
      'id'    => $menu['user_type_id'] . '&type=4',
      'name'  => 'Tag',
      'icon'  => 'resources/user/assets/image/icon/tag.svg',
      'count' => $sub_menu['has_tag'],
    ];
    $count_permission++;
  }

  if ($nav_temp) {
    $data_nav = [
      'param_name' => 'user_type',
      'title' => '<span class="font-SemiBold text-muted">' . $menu['name'] . '</span>',
      'class' => 'list-bg-none',
      'list' => $nav_temp
    ];
    $_GET['user_type'] = $user_type . '&type=' . $type;
    Boatnav::wolves($data_nav, '?c=');
  }
}

function redirectNoPermission()
{
  echo 3;
  echo 'getCurrent';
  W_Main::display(User::getCurrent());
  echo 'ini';
  W_Main::display(WLoves::getInitialData());
  echo 'F_user';
  W_Main::display(W_User::getCurrentUser());
  echo 'showConfig';
  W_Main::display(SC::showConfig());
  exit();
  W_Main::notification('No User Permission', 'error');
  W_Main::redirect('user_basic_setting.php');
}
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

  <div class="row p-20px">
    <div class="col-md-3">
      <div class="user-topic">
        <div>
          <h6 class="topic">User Type Basic Setting</h6>
          <p class="sub-topic">Set your basic data for use on user data.</p>
        </div>
      </div>
      <?php
      foreach ($menus as $key => $menu) {
        templateBasicSettingMenu($menu);
      }
      if ($count_permission == 0) {
        W_Main::notification('No User Permission', 'error');
        W_Main::redirect('../../module_main/login/logout.php');
      }
      ?>
    </div>
    <?php
    if ($user_type) {
      $user_info = User_type::getUserTypeByID($user_type);
      if (isset($user_info['id'])) {
        if ($page == 'template_detail') { //permission template detail
          if (!$user_info['is_permission_template']) {
            redirectNoPermission();
          }
          include 'views/basic_setting_permission_detail.php';
        } else if ($page == 'team_detail') { //team detail
          if (!$user_info['is_has_team']) {
            redirectNoPermission();
          }
          include 'views/basic_setting_team_detail.php';
        } else if ($type == 1) { //permission template
          if (!$user_info['is_permission_template']) {
            redirectNoPermission();
          }
          include 'views/basic_setting_permission.php';
        } else if ($type == 2) { //team
          if (!$user_info['is_has_team']) {
            redirectNoPermission();
          }
          include 'views/basic_setting_team.php';
        } else if ($type == 3) { //category
          if (!$user_info['is_has_category']) {
            redirectNoPermission();
          }
          include 'views/basic_setting_category.php';
        } else if ($type == 4) { //tag
          if (!$user_info['is_has_tag']) {
            redirectNoPermission();
          }
          include 'views/basic_setting_tag.php';
        }
      } else {
        redirectNoPermission();
      }
    } else {
    ?>
      <div class="col-md-9">
        <div class="w-loves-card px-0 py-0">
          <div class="w-loves-card-header px-15px mb-10px set-header-bg">
            <div class="d-flex align-items-center">
              <div class="d-flex align-items-center"><?= file_get_contents('assets/image/icon/lightbulb.svg') ?></div>
              <span class="font-16px font-weight-bold ml-5px">View User Type Basic Setting</span>
            </div>
            <p class="font-13px text-secondary">Please select a system user type You want to view information form the list on the left to show the details of the basic setting list.</p>
          </div>
          <div class="bg-manage-user calc-165px">
            <?= file_get_contents('assets/image/icon/bg-user.svg') ?>
          </div>
        </div>
      </div>
    <?php
    }
    ?>
  </div>

  <?php
  include_once '../layout/footer.php';
  W_Structure::loadFooter();
  ?>

  <script>
    $(function() {
      $(document).on('click', '.show-less', function(e) {
        var name = $(this).attr('data-permission_name');
        $(this).toggleClass('hide');
        $('.body_hide_' + name).toggleClass('hidden');
        var text = $('.text_more_' + name).text();
        if (text == 'Show More') {
          $('.text_more_' + name).text('Show Less');
          $(this).parents('.permission-wrap').find('.permission-header').css('border-bottom', '1px solid #00000020');
        } else {
          $('.text_more_' + name).text('Show More');
          $(this).parents('.permission-wrap').find('.permission-header').css('border-bottom', '0');
        }
      });
    });
  </script>

</body>

</html>