<?php
if (!isset($_PAGE)) {
  $_PAGE['permission'] = ['', '', ''];
}
if ($is_dev && isset($_PAGE)) {
  $menu_core = W_Config::$menu_core_list;
?>
  <div class="nav-x-list-area <?= (!$favorite && $_PAGE['permission'][0] != 'core') ? 'is-hide' : ''; ?>">
    <div class="nav-x-category <?= !$favorite ? 'nav_x_category_event' : ''; ?>">
      <div class="nav-x-logo">
        <?= W_Structure::getfile('_framework/structure/image/icon/icon-core.svg') ?>
        <span class="nav-x-project-name <?= ($_PAGE['permission'][0] == $menu_core['Core']['page_name']) ? 'active' : ''; ?>"><?= $menu_core['Core']['title'] ?></span>
      </div>

      <?php if (!$favorite) { ?>
        <?php if (isset($menu_core['Core']['main_setting_url']) && $menu_core['Core']['main_setting_url']) { ?>
          <a href="<?= W_Structure::getLinkURL($menu_core['Core']['main_setting_url'])  ?>" class="nav-x-main-setting"></a>
        <?php } ?>

        <div class="nav-x-chevron-expand">
          <?= W_Structure::getfile("_framework/structure/image/etc/icon-arrow.svg") ?>
        </div>
      <?php } ?>
    </div>
    <?php
    foreach ($menu_core as $menu) {
      foreach ($menu['menu_items'] as $menu_items) {
    ?>
        <div class="nav-x-menu-list">
          <!-- sub title -->
          <?php if (isset($menu_items['title']) && $menu_items['title']) { ?>
            <div class="nav-x-sub-category"><?= $menu_items['title'] ?></div>
          <?php } ?>

          <div class="nav-x-list-group">
            <?php
            foreach ($menu_items['sub_menu'] as $sub_menu) {
              $count_menu++;
              $core_url = '';
              if ($sub_menu['page_name'] == 'core_dev_database' && isLocalhost($sub_menu['page_name'] == 'core_dev_database')) {
                // $core_url =  dirname(dirname(F_BRIDGE_API_URL)) . '/wloves/_framework/resources/core/' . $sub_menu['url'];
                // $config_server =  WLoves::getDatabaseSetting();

                $core_url = W_Structure::getLinkURL('_framework/resources/core/' . $sub_menu['url']);
              } else {
                $core_url = W_Structure::getLinkURL('_framework/resources/core/' . $sub_menu['url']);
              }
              // new module
              // else if ($sub_menu['page_name'] == 'core_module_setup') {
              //   $core_url = W_Structure::getLinkURL('_framework/resources/' . $sub_menu['url']);
              // }
              $active = (($_PAGE['permission'][2] == $sub_menu['page_name'] && !$favorite) ? 'active' : '');
              $target_blank = ($sub_menu['title'] == 'Database') ? 'target="_blank"' : '';
              if (!$favorite) {
                echo '<a href="' . $core_url . '" ' . $target_blank . '>';
              }
            ?>
              <div class="nav-x-list <?= $active ?> <?= $favorite ? 'nav-x-add-favorite' : '' ?>" data-name="<?= $sub_menu['title'] ?>" data-url="<?= $core_url ?>" data-code="<?= $sub_menu['page_name'] ?>">
                <div class="bg-hover"></div>
                <span class="nav-x-page-name"><?= $sub_menu['title'] ?></span>
                <?php if (isset($sub_menu['count']['number']) && $sub_menu['count']['number']) { ?>
                  <span class="nav-x-page-count <?= isset($sub_menu['count']['class']) ? $sub_menu['count']['class'] : ''; ?>"><?= ($sub_menu['count']['number'] > 99) ? '99+' : $sub_menu['count']['number']; ?></span>
                <?php } ?>
              </div>
            <?php
              if (!$favorite) {
                echo '</a>';
              }
            } //end foreach $menu_items['sub_menu']
            ?>
          </div>
        </div>
    <?php
      } //end foreach $menu['menu_items']
    } //end foreach W_Config::$menu_core_list
    ?>
  </div>
<?php } ?>