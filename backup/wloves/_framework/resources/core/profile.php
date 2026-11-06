<?php
$_WLOVES['no_check_permission'] = 1;
$_PAGE['permission'] = ['index', '', 'profile'];
require_once '../../../_framework/import.php';

W_Structure::loadModules(['datatables', 'boatnav']);

$is_edit = isset($_GET['is_edit']) ? $_GET['is_edit'] : '0';
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$url = 'profile.php?c=';

$user_data = W_WLoves::$initial_data['current_user_data'];
if (!$user_data['User']['id']) {
  W_Main::notification('User are not logged in', 'error');
  W_Main::redirect('index.php');
}
$user_id = $user_data['User']['id'];
$user_info = User::getUserByID($user_id, ['img_path' => true]);

$user_type_info = User_type::getUserTypeByID($user_info['user_type_id']);

$data_nav = [
  'param_name'  => 'page',
  'class' => '',
  'list' => [
    [
      'id'  => 1,
      'name'  => 'My Profile',
    ],
    [
      'id'  => 2,
      'name'  => 'Login System',
    ],
    // [
    //   'id'  => 6,
    //   'name'  => 'Verify Data',
    // ],
    // [
    //   'id'  => 7,
    //   'name'  => 'Working Detail',
    // ]
  ]
];

if ($user_type_info['is_multiple_bank_account']) {
  $data_nav['list'][] = [
    'id'  => 3,
    'name'  => 'Bank Account',
  ];
}

if ($user_type_info['is_multiple_address']) {
  $data_nav['list'][] = [
    'id'  => 4,
    'name'  => 'Address',
  ];
}
if ($user_type_info['is_topbar_contact_person']) {
  $data_nav['list'][] = [
    'id'  => 5,
    'name'  => 'Contact Person',
  ];
}

function profileTemplate($title = '', $detail = '', $options = [])
{
  echo '<div class="col-6 col-md-5 col-lg-3 col-xl-2 font-14px text-secondary pt-10px">' . $title . '</div>
        <div class="col-6 col-md-7 col-lg-3 col-xl-4 text-info">' . $detail . '</div>';
}

function form2col($detail1 = '', $detail2 = '')
{
  $detail1_html = $detail1 ? '<div class="col-xl-6">' . $detail1 . '</div>' : '';
  $detail2_html = $detail2 ? '<div class="col-xl-6">' . $detail2 . '</div>' : '';
  if ($detail1_html || $detail2_html) {
    echo '<div class="row">
          ' . $detail1_html . $detail2_html . '
        </div>';
  }
}

function formProfileTemplate($title = '', $detail = '', $options = [])
{
  $is_return = (isset($options['is_return']) && $options['is_return']) ? true : false;
  $html = '<div class="profile-row">
            <div class="col-title">' . $title . '</div>
            <div class="col-detail font-16px text-info">' . $detail . '</div>
          </div>';
  if ($is_return) {
    return $html;
  } else {
    echo $html;
  }
}
?>

<!DOCTYPE html>
<html lang="en" data-theme="<?= W_Structure::getThemeClass() ?>">

<head>
  <?php W_Structure::loadMeta('../../'); ?>
</head>

<body>
  <?php
  include_once 'structure/layout/header.php';
  include_once 'structure/layout/sidenav.php';
  ?>

  <div class="top-card-back-title">Profile</div>
  <div class="nav-card">
    <?= Boatnav::dinner($data_nav); ?>
  </div>
  <?php
  if ($page == 1) {
    include 'view/profile/my_profile.php';
  } else if ($page == 2) {
    include 'view/profile/login_system.php';
  } else if ($page == 3) {
    include 'view/profile/bank_account.php';
  } else if ($page == 4) {
    include 'view/profile/address.php';
  } else if ($page == 5) {
    include 'view/profile/contact_person.php';
  } else if ($page == 6) {
    include 'view/profile/verify_data.php';
  } else if ($page == 7) {
    include 'view/profile/working_detail.php';
  }
  ?>

  <?php
  include_once 'structure/layout/footer.php';
  W_Structure::loadFooter('../../');
  ?>
</body>

</html>