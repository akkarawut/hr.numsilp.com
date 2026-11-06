<?php

//check permission and create menu list array
$sidenav_menu = [
  'list' => [
    'name' => 'USER LIST',
    'list' => [],
  ],
  'manage' => [
    'name' => 'USER TYPE MANAGE',
    'list' => [],
  ],
];
//setting user list menu
foreach ($user_type_menu as $sub_menu) {
  if ($sub_menu['module'] == 'User') {
    $condition = (isset($permissions[$sub_menu['code'] . '_user']) && $permissions[$sub_menu['code'] . '_user'] == 1);
    if (W_User::isDev()) {
      $condition = true;
    }
    if ($condition) {
      $count_menu++;
      $page_url = '_framework/resources/user_new/index.php?c=' . $sub_menu['code'];

      $code = (isset($_GET['c'])) ? $_GET['c'] : '';
      $active = (($code == $sub_menu['code'] && !$favorite) ? 'active' : '');

      // if ($active) {
      //   $active_menu = [
      //     'icon' => 'structure/image/icon/general/user.svg',
      //     'name' => 'User',
      //   ];
      // }

      $sidenav_menu['list']['list'][] = [
        'code' => $sub_menu['code'],
        'key' => $sub_menu['code'] . '_user',
        'name' => $sub_menu['name'],
        'icon' => 'structure/image/etc/ellipse.svg',
        'url' => $page_url,
        'active' => $active,
      ];
    }
  } //end if $condition
} //end foreach submenu

?>

<?php if ($sidenav_menu['list']['list']) { ?>
  <div class="nav-x-list-area <?= (!$favorite && $_PAGE['permission'][0] != 'user' ||  $_PAGE['permission'][1] == 'user_profile') ? 'is-hide' : ''; ?>">
    <div class="nav-x-category <?= !$favorite ? 'nav_x_category_event' : ''; ?>">
      <div class="nav-x-logo">
        <?= W_Structure::getfile('_framework/structure/image/icon/general/user.svg') ?>
        <span class="nav-x-project-name <?= ($_PAGE['permission'][0] == 'user') ? 'active' : ''; ?>">User management</span>
      </div>

      <?php if (!$favorite) { ?>
        <div class="nav-x-chevron-expand">
          <?= W_Structure::getfile("_framework/structure/image/etc/icon-arrow.svg") ?>
        </div>
      <?php } ?>

    </div>
    <?php if ($sidenav_menu['list']['list']) { ?>
      <div class="nav-x-menu-list">
        <div class="nav-x-list-group">
          <?php
          //menu
          foreach ($sidenav_menu['list']['list'] as $menu) {
            $count_menu++;
            if (!$favorite) {
              echo '<a href=" ' .  W_Structure::getLinkURL($menu['url']) . '">';
            }
          ?>
            <div class="nav-x-list <?= $menu['active'] ?> <?= $favorite ? 'nav-x-add-favorite' : '' ?>" data-name="<?= $menu['name'] ?>" data-url="<?= W_Structure::getLinkURL($menu['url']) ?>" data-code="<?= $menu['code'] ?>_<?= $sub_menu['name'] ?>">
              <div class="bg-hover"></div>
              <span class="nav-x-page-name"><?= $menu['name'] ?></span>
            </div>
            <?php
            if (!$favorite) {
              echo '</a>';
            }
            ?>
          <?php
          } //end foreach submenu
          ?>
        </div>
      </div>
    <?php } ?>
  </div>
<?php } ?>