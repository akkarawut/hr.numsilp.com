<?php
if (isset($_GET['action']) && $_GET['action'] == 'welcome') {
  $active_menu['icon'] = '';
  $active_menu['name'] = 'Welcome!';
} else if (strpos($_SERVER['REQUEST_URI'], 'module_main/core/profile.php') !== false || strpos($_SERVER['REQUEST_URI'], 'module_main/core/profile_dev.php') !== false) {
  $active_menu['icon'] = 'structure/image/icon/general/user.svg';
  $active_menu['name'] = 'Profile';
}
$theme = W_Structure::getThemeClass();
$is_dark = false;
if ($theme == 'dark') {
  $is_dark = true;
}
$language = W_Structure::getLanguages();

try {

  if (method_exists('Structure', 'getLanguages')) {
    $language = W_Structure::getLanguages();
  }
} catch (Exception $e) {
  $language = false;
}
?>

<div id="nav-x-top">
  <div class="nav-x-left">
    <div class="nav-x-header <?= $mini_menu ?>">
      <div class="nav-x-logo">
        <?php if (strpos($logo_image, '/placeholder/')  === false) { ?>
          <img src="<?= $logo_image ?>" class="fix-img">
        <?php  } else { ?>
          <?= W_Structure::getfile('_framework/structure/image/logo/wloves-pro.svg'); ?>
        <?php } ?>
      </div>
      <div class="nav-x-chevron-arrow">
        <?= W_Structure::getfile('_framework/structure/image/layout/icon-menu-arrow.svg'); ?>
      </div>
      <div class="nav-x-menu">
        <div class="icon">
          <?= W_Structure::getfile('_framework/structure/image/etc/menu.svg'); ?>
        </div>
        <div class="icon-hover">
          <?= W_Structure::getfile('_framework/structure/image/layout/icon-menu-arrow.svg'); ?>
        </div>
      </div>
    </div>
  </div>

  <div class="nav-x-right-group">
    <div class="nav-x-center">
      <?php if ($count_menu > 0) { ?>
        <div class="btn-nav-x-add">
          <?= W_Structure::getfile('_framework/structure/image/etc/icon-plus.svg'); ?>
          <span class="nav-x-text">ADD PAGE</span>
        </div>
      <?php } ?>

      <div class="nav-x-favorite">
        <?php
        foreach ($favorite as $code => $data) {
          $code_module = explode('_', $code)[0];
          if (isset($permissions[$code]) || ($code_module == 'core' && $is_dev)) {
        ?>
            <div class="nav-x-favorite-list-group">
              <div class="nav-x-favorite-list">
                <a href="<?= $data['page'] ?>"><span><?= $data['name'] ?></span> </a>
                <div class="btn-delete-nav-x-favorite" data-code="<?= $code ?>">
                  <?= W_Structure::getfile('_framework/structure/image/etc/delete.svg') ?>
                </div>
              </div>
              <div class="nav-x-slash"></div>
            </div>
        <?php
          }
        }
        ?>
      </div>
    </div>

    <div class="nav-x-right">
      <div class="nav-x-slash"></div>

      <!-- <div class="nav-x-search"></div> -->

      <div class="nav-x-language">
        <div class="nav-x-language-dropdown">
          <?php
          $full_name_language = Translaty::getlanguageList();
          if ($language) {
            $current_module_lg_list = $language['system_languages'];
            $api_to_module = Translaty::getLanguageApitoModule();
            $lg = Ty::getLg();
            // ? if in core
            foreach ($current_module_lg_list as $list) { ?>
              <div class="language-list change_language_event <?= $api_to_module[$list['locale_name']] == $lg  ? 'active' : '' ?>" data-set="<?= $api_to_module[$list['locale_name']] ?>">
                <?= $full_name_language[$api_to_module[$list['locale_name']]] ?>
              </div>
              <?php }
          } else {
            $lg = Ty::getLg();
            $full_name_language = Translaty::getlanguageList();
            $language_list = Transalaty::getCurrentLanguage();
            if ($language_list) {
              foreach ($language_list as $list) { ?>
                <div class="language-list change_language_event <?= $list == $lg  ? 'active' : '' ?>" data-set="<?= $list ?>">
                  <?= $full_name_language[$list] ?>
                </div>
              <?php }
            } else { ?>
              <div class="language-list">
                'you must install module "Language" or Transalaty' first
              </div>
          <?php }
          }

          ?>
        </div>
      </div>

      <!-- <div class="nav-x-alert" is-alert="true"></div> -->
      <?php if ($is_dev && $log) { ?>

        <div class="nav-x-log <?= ($_PAGE['permission'][1] == 'core_log') ? 'active' : ''; ?>">
          <a href="<?= W_Structure::getLinkURL('_framework/resources/log/') ?>">
            <?= btnlogSvg($log); ?>
          </a>
        </div>
      <?php } ?>

      <div class="nav-x-profile">
        <div class="nav-x-btn-profile">
          <?php if ($is_user) { ?>
            <div class="nav-x-profile-name">
              <?= $user_info['username'] ?>
            </div>
            <div class="nav-x-profile-img">
              <img src="<?= $user_info['profile_image']; ?>">
            </div>
          <?php } else if ($is_dev) { ?>
            <?php if ($dev_info['username']) { ?>
              <div class="nav-x-profile-name">
                <?= $dev_info['username'] ?>
              </div>
            <?php } ?>
            <div class="nav-x-profile-img">
              <img src="<?= $dev_info['profile_image']; ?>">
            </div>
          <?php } ?>
        </div>
        <div class="nav-x-profile-dropdown">
          <?php if ($is_dev) { ?>
            <div class="nav-x-profile-dropdown-dev">
              <div class="nav-x-profile-dropdown-dev-card">
                <div class="nav-x-profile-dev">
                  <span class="text-primary font-SemiBold">Dev mode</span>
                </div>
                <div class="nav-x-logout" <?= Tiwdal::register('logout_popup', []); ?>>
                  <?= W_Structure::getfile('_framework/structure/image/logo/icon-logout.svg') ?>
                </div>
              </div>
            </div>
          <?php } ?>
          <?php if ($is_user) { ?>
            <div class="nav-x-profile-dropdown-user">
              <div class="nav-x-user-name py-15px"><?= $user_info['username']; ?></div>
              <hr>
              <?php
              $path_link = '../../../';
              $this_url = $_SERVER['REQUEST_URI'];
              $this_url =  explode('/', $this_url);
              $key = array_search('_framework', $this_url);
              if (!$key) {
                $path_link = '../../';
              }
              ?>
              <a href="<?= W_Structure::getLinkURL('_framework/resources/user_new/user.php') ?>">
                <div class="nav-x-edit-profile">
                  Manage profile
                </div>
              </a>
              <div class="nav-x-logout" data-toggle="modal" <?= Tiwdal::register('logout_user_popup', []); ?>>Log out</div>
              <hr>
            </div>
          <?php } else { ?>
            <div class="nav-x-profile-dropdown-user">
              <div class="nav-x-btn-user-login-area">
                <a href="<?= W_Structure::getLinkURL('_framework/resources/login/index.php') ?>" class="nav-x-btn-user-login">
                  <?= W_Structure::getfile('_framework/structure/image/icon/icon-user-logout.svg') ?>
                  User login
                </a>
              </div>
            </div>
          <?php } ?>
          <div class="switch-color-mode">
            <div class="icon-mode">
              <?= $is_dark ? 'Dark mode' : 'Light mode' ?>
            </div>
            <?php TiwForm::normal('checkbox', '', ['name' => '', 'class' => 'event-switch-color-mode', 'checked' => $is_dark], ['style' => '1']);  ?>
          </div>
        </div>
      </div>

    </div>

  </div>

</div>

<?php Tiwdal::ajaxModal('manage_profile', 'max-w-800px', ['data-bs-backdrop' => ' ', 'slide' => true]); ?>


<?php Tiwdal::ajaxModal('edit_password_profile', 'max-w-650px', []); ?>