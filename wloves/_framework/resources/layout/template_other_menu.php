<?php
$code = isset($_GET['c']) ? $_GET['c'] : '';
//ดึงไฟล์ config ของแต่ละ module ว่ามี page อะไรบ้าง
foreach ($modules as $function) {
  W_WLoves::setConfigMenuList($function['module']);
}
$file_menu_list = W_Config::$menu_list;
$has_permissions =  $permissions;
//check permission and create menu list array
$sidenav_menu = [];
foreach ($other_modules as $function) {
  // [20220609] ใช้ key แยกตามโมดูลที่เปิดจริงๆ ทำให้เปิดซ้ำได้
  $i_module_key = "{$function['module']}_{$function['code']}";
  $code_permissions = $function['code'] . '_' . strtolower($function['module']);
  $menu = array_key_exists($function['module'], $file_menu_list) ? $file_menu_list[$function['module']] : '';
  if (!$menu) {
    continue;
  }

  $hide_user = false;
  if ($function['module'] == 'User' && $hide_user) { //user module
    if (isset($has_permissions[$code_permissions]) && $has_permissions[$code_permissions] == 1) {
      $main_icon = isset($menu['icon']) ? $menu['icon'] : 'structure/image/sidenav/default_icon.svg';
      $sidenav_menu[$i_module_key] = [
        'icon' => $main_icon,
        'name'    => $function['name'],
        'code'    => $function['code'],
        'count'   => 0,
        'type'    => 'other',
        'page_name' => strtolower($function['module'])
      ];
      //submenu
      foreach ($menu['menu_items'] as $menu_items) {
        $sidenav_menu[$i_module_key]['sub_menu'][$menu_items['page_name']] = [
          'name' => $menu_items['title'],
          'list' => [],
        ];
        //menu
        $page_url = '_framework/resources/user/manage_user.php?c=' . $function['code'];
        $active = ($function['code'] == $code && !$favorite ? 'active' : '');
        $sidenav_menu[$i_module_key]['count']++;
        $sidenav_menu[$i_module_key]['sub_menu'][$menu_items['page_name']]['list'][] = [
          'key'  => $function['name'] . '_' . $function['code'],
          'icon' => (isset($menu_items['icon']) && $menu_items['icon']) ? $menu_items['icon'] : 'structure/image/etc/ellipse.svg',
          'name' => $function['name'] . ' list',
          'url' => $page_url,
          'active' => $active,
        ];
      } //end foreach menu_items
    }
  } else { //other module light_icon dark_icon
    $theme = W_Structure::getThemeClass();
    $main_icon = '';
    $type_icon = '';
    if (isset($menu['icon']) && $menu['icon']) {
      $main_icon = $menu['icon'];
      $type_icon = 'svg';
    } else {
      if (isset($function[$theme . '_icon'])) {
        $data_file = W_Main::datafile($function[$theme . '_icon']);
        if (!$data_file['is_placeholder']) {
          $main_icon = $function[$theme . '_icon'];
          $type_icon = 'image';
        }
      }
    }

    $sidenav_menu[$i_module_key] = [
      'icon' => $main_icon,
      'type_icon' => $type_icon,
      // 'is_hide_o' => (!$favorite && ($_PAGE['permission'][0] != strtolower($function['module'])) && ($function['code'] != $_GET['c'])) ? 1 : 0,
      'is_hide' => (!$favorite && ($function['code'] != $code)) ? 1 : 0,
      'name'    => $function['name'],
      'code'    => $function['code'],
      'count'   => 0,
      'type'    => 'other',
      'page_name' => strtolower($function['module'])
    ];

    if (isset($menu['main_setting_url']) && $menu['main_setting_url']) {
      $sidenav_menu[$i_module_key]['main_setting_url'] = 'module/' . $menu['page_name'] . '/' . $menu['main_setting_url'] . '?c=' . $function['code'];
    }

    //submenu
    foreach ($menu['menu_items'] as $menu_items) {
      $sidenav_menu[$i_module_key]['sub_menu'][$menu_items['page_name']] = [
        'name' => $menu_items['title'],
        'list' => [],
      ];
      //menu
      if (isset($menu_items['sub_menu']) && $menu_items['sub_menu']) {
        foreach ($menu_items['sub_menu'] as $sub_menu) {
          $code_name = $function['code'] . '_' . $sub_menu['page_name'];
          $condition = $sub_menu['url'] && ($sub_menu['page_name'] && isset($permissions[$code_name]) && $permissions[$code_name] == 1);
          // $condition = true;
          if ($condition) {
            $page_url = 'module/' . $menu['page_name'] . '/' . $sub_menu['url'] . '?c=' . $function['code'];
            $active = (($_PAGE['permission'][2] == $sub_menu['page_name'] && $function['code'] == $code && !$favorite) ? 'active' : '');
            $sidenav_menu[$i_module_key]['count']++;
            $sidenav_menu[$i_module_key]['sub_menu'][$menu_items['page_name']]['list'][] = [
              'key'  => $sub_menu['page_name'],
              'icon' => (isset($sub_menu['icon']) && $sub_menu['icon']) ? $sub_menu['icon'] : '',
              'name' => $sub_menu['title'],
              'url' => $page_url,
              'active' => $active,
              'count' => isset($sub_menu['count']) ? $sub_menu['count'] : []
            ];
          }
        } //end foreach submenu
      }
    } //end foreach menu_items
  }
} //end foreach F_permission
//create menu
foreach ($sidenav_menu as $module_key => $module) {
  if ($module['count']) {
    //module

?>
    <div data-layout-name="othermenu" class="nav-x-list-area <?= ($module['is_hide']) ? 'is-hide' : ''; ?>">
      <div class="nav-x-category <?= !$favorite ? 'nav_x_category_event' : ''; ?>">
        <div class="nav-x-logo">
          <?php if ($module['icon']) { ?>
            <?php if ($module['type_icon'] == 'svg') { ?>
              <?= W_Structure::getfile($module['icon']) ?>
            <?php } else { ?>
              <img src="<?= $module['icon'] ?>" alt="icon-module">
            <?php }  ?>
          <?php } else { ?>
            <?= W_Structure::getfile('_framework/structure/image/sidenav/default_icon.svg') ?>
          <?php } ?>
          <span class="nav-x-project-name <?= ($module['icon']) ? '' : 'pl-0' ?> <?= ($module['code'] == $code) ? 'active' : ''; ?>"><?= $module['name'] ?></span>
        </div>

        <?php if (!$favorite) { ?>
          <?php if (isset($module['main_setting_url']) && $module['main_setting_url']) { ?>
            <a href="<?= W_Structure::getLinkURL($module['main_setting_url']) ?> " class="nav-x-main-setting"></a>
          <?php } ?>

          <div class="nav-x-chevron-expand">
            <?= W_Structure::getfile("_framework/structure/image/etc/icon-arrow.svg") ?>
          </div>
        <?php } ?>

      </div>
      <?php
      //submenu
      foreach ($module['sub_menu'] as $sub_menu) {
        if ($sub_menu['list']) {
          //sub menu
      ?>
          <div class="nav-x-menu-list">
            <?php if ($module['type'] == 'other' && (isset($sub_menu['name']) && $sub_menu['name'])) { ?>
              <div class="nav-x-sub-category"><?= $sub_menu['name'] ?></div>
            <?php } ?>

            <div class="nav-x-list-group">
              <?php

              foreach ($sub_menu['list'] as $menu) {
                if (!$favorite) {
                  echo '<a href="' . W_Structure::getLinkURL($menu['url'])  . '">';
                }
                //menu'
              ?>
                <div class="nav-x-list <?= $menu['active'] ?> <?= $favorite ? 'nav-x-add-favorite' : '' ?>" data-name="<?= $menu['name'] ?>" data-url="<?= W_Structure::getLinkURL($menu['url'])  ?>" data-code="<?= $module['code'] ?>_<?= $menu['key'] ?>">
                  <div class="bg-hover"></div>
                  <?= (isset($menu['icon']) && $menu['icon']) ? '<div class="icon" style="-webkit-mask: url(' . W_Structure::getLinkURL($menu['icon']) . ') no-repeat 50% 50%; mask: url(' . W_Structure::getLinkURL($menu['icon']) . ') no-repeat 50% 50%;"></div>' : '';
                  ?>

                  <span class="nav-x-page-name"><?= $menu['name'] ?></span>
                  <div class="__nav_x_count <?= '__nav_x_count_' . $module['code'] . '_' . $menu['key'] ?>"></div>
                </div>
                <?php
                if (!$favorite) {
                  echo '</a>';
                } ?>
              <?php
              } //end foreach submenu
              ?>
            </div>
          </div>
      <?php
        } //end if $sub_menu['list']
      } //end foreach sub_menu
      ?>
    </div>
<?php
  } //end if count
} //end foreach sidenav_menu