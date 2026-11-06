<?php
$code = isset($_GET['c']) ? $_GET['c'] : '';

//ดึงไฟล์ config ของแต่ละ module ว่ามี page อะไรบ้าง
foreach ($modules as $function) {
  W_WLoves::setConfigMenuList($function['module'], '../../../');
}
$file_menu_list = W_Config::$menu_list;
//check permission and create menu list array
$sidenav_menu = [];
foreach ($modules as $function) {
  $code_permissions = $function['code'] . '_' . strtolower($function['module']);
  $menu = array_key_exists($function['module'], $file_menu_list) ? $file_menu_list[$function['module']] : '';
  if (!$menu) {
    continue;
  }
  //module list
  if (!$active_menu['name'] && $_PAGE['permission'][0] == strtolower($function['module'])) {
    $active_menu = [
      'icon' => isset($menu['icon']) ? $menu['icon'] : '',
      'name' => $function['name'],
    ];
  }
  if ($function['module'] != 'User') {
    $main_icon = isset($menu['icon']) ? $menu['icon'] : '';
    $sidenav_menu[$function['module']] = [
      'icon' => $main_icon,
      'name' => $function['name'],
      'code' => $function['code'],
      'count' => 0,
    ];
    //submenu
    foreach ($menu['menu_items'] as $menu_items) {
      $sub_icon = (isset($menu_items['icon']) && $menu_items['icon']) ? $menu_items['icon'] : '';
      $sidenav_menu[$function['module']]['sub_menu'][$menu_items['page_name']] = [
        'name' => $menu_items['title'],
        'icon' => $sub_icon,
        'list' => [],
        'page_name' => $menu_items['page_name']
      ];
      //menu
      if (isset($menu_items['sub_menu']) && $menu_items['sub_menu']) {
        foreach ($menu_items['sub_menu'] as $sub_menu) {
          $code_name = $function['code'] . '_' . $sub_menu['page_name'];
          $condition = $sub_menu['url'] && ($sub_menu['page_name'] && isset($permissions[$code_name]) && $permissions[$code_name] == 1);
          if ($condition) {
            $count_menu++;
            $page_url = 'module/' . $menu['page_name'] . '/' . $sub_menu['url'] . '?c=' . $function['code'];
            $active = (($_PAGE['permission'][2] == $sub_menu['page_name'] && $function['code'] == $_GET['c'] && !$favorite) ? 'active' : '');
            $sidenav_menu[$function['module']]['count']++;
            $sidenav_menu[$function['module']]['sub_menu'][$menu_items['page_name']]['list'][] = [
              'key'  => $sub_menu['page_name'],
              'icon' => ($sub_menu['icon']) ? $sub_menu['icon'] : 'structure/image/etc/ellipse.svg',
              'name' => $sub_menu['title'],
              'url' => $page_url,
              'active' => $active,
            ];
          }
        } //end foreach submenu
      }
    } //end foreach menu_items
  } else {
    if (isset($permissions[$code_permissions]) && $permissions[$code_permissions] == 1 && $function['display'] == 'notuser') {
      $i_module_key = "{$function['module']}_{$function['code']}";
      $main_icon = isset($menu['icon']) ? $menu['icon'] : 'structure/image/sidenav/default_icon.svg';
      $sidenav_menu[$i_module_key] = [
        'icon' => $main_icon,
        'name' => $function['name'],
        'code' => $function['code'],
        'count' => 0,
      ];
      //submenu
      foreach ($menu['menu_items'] as $menu_items) {
        $sidenav_menu[$i_module_key]['sub_menu'][$menu_items['page_name']] = [
          'page_name' => $menu_items['page_name'],
          'name' => $function['name'] . ' ' . $menu_items['title'],
          'icon' => '',
          'list' => [],
        ];
        //menu
        $page_url = '_framework/resources/user/manage_user.php?c=' . $function['code'];
        $active = ($function['code'] == $code && !$favorite ? 'active' : '');
        $sidenav_menu[$i_module_key]['count']++;
        $sidenav_menu[$i_module_key]['sub_menu'][$menu_items['page_name']]['list'][] = [
          'key'  => $function['name'] . '_' . $function['code'],
          'icon' => 'structure/image/etc/ellipse.svg',
          'name' => $function['name'] . ' list',
          'url' => $page_url,
          'active' => $active,
        ];
      } //end foreach menu_items
    }
  }
} //end foreach F_permission

//create menu
foreach ($sidenav_menu as $module_key => $module) {
  if ($module['count']) {
    //module
    //submenu
    foreach ($module['sub_menu'] as $module_sub_key => $sub_menu) {
      if ($sub_menu['list']) {
        //sub menu
?>
        <div class="nav-x-list-area <?= (!$favorite && $_PAGE['permission'][1] != strtolower($module_sub_key)) ? 'is-hide' : ''; ?>">
          <div class="nav-x-category <?= !$favorite ? 'nav_x_category_event' : ''; ?>">
            <div class="nav-x-logo">
              <?= W_Structure::getfile($sub_menu['icon']) ?>
              <span class="nav-x-project-name <?= ($sub_menu['icon']) ? '' : 'pl-0' ?> <?= ($_PAGE['permission'][1] == $sub_menu['page_name']) ? 'active' : ''; ?>"><?= ucfirst(strtolower($sub_menu['name'])) ?></span>
            </div>

            <?php if (!$favorite) { ?>
              <div class="nav-x-chevron-expand">
                <?= W_Structure::getfile("_framework/structure/image/etc/icon-arrow.svg") ?>
              </div>
            <?php } ?>

          </div>
          <div class="nav-x-menu-list">
            <div class="nav-x-list-group">
              <?php
              foreach ($sub_menu['list'] as $menu) {
                if (!$favorite) {
                  echo '<a href="' . W_Structure::getLinkURL($menu['url']) . '">';
                }
                //menu
              ?>
                <div class="nav-x-list <?= $menu['active'] ?> <?= $favorite ? 'nav-x-add-favorite' : '' ?>" data-name="<?= $menu['name'] ?>" data-url="<?= $menu['url'] ?>" data-code="<?= $module['code'] ?>_<?= $menu['key'] ?>">
                  <div class="bg-hover"></div>
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
        </div>

    <?php
      } //end if $sub_menu['list']
    } //end foreach sub_menu
    ?>
<?php
  } //end if count
} //end foreach sidenav_menu