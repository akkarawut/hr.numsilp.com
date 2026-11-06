<?php
$top_bar_list = [];
if (!isset($_PAGE)) {
  $_PAGE['permission'] = ['', '', ''];
}
$menu_list    = ($_PAGE['permission'][0] == 'core') ? W_Config::$menu_core_list : W_Config::$menu_list;
foreach ($menu_list as $menu) {
  foreach ($menu['menu_items'] as $items) {
    if ($items['page_name'] == $_PAGE['permission'][1]) {
      $top_bar_list = $items['sub_menu'];
      break;
    }
  }
}

if (!$top_bar_list) {
?>
  <div id="topbar">
    <div class="top-bar-wrap">
      <?php
      foreach ($top_bar_list as $top_bar) {
        $is_show = 0;
        if ($_PAGE['permission'][0] == 'core') {
          $is_show = 1;
        } else {
          if (isset(W_Permission::$has_permissions[$_GET['c'] . '_' . $top_bar['page_name']])) {
            $is_show = 1;
          }
        }
        if ($is_show) {
          $active = ($_PAGE['permission'][2] == $top_bar['page_name']) ? 'active' : '';
          echo '<a href="' . $top_bar['url'] . (($_PAGE['permission'][0] != 'core') ? '?c=' . $_GET['c'] : '') . '" class="top-bar-items text-nowrap ' . $active . '">
                  <div class="top-bar-icon">
                    ' . file_get_contents('../../' . $top_bar['icon']) . '
                  </div>
                  <span>
                    ' . $top_bar['title'] . '
                  </span>
                </a>';
        }
      }
      ?>
    </div>
  </div>
<?php
}
?>