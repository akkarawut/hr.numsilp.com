<?php

$is_full = W_Main::cookie('_menu_style') ? 'is-full' : '';
$count_menu = 0;
$active_menu = [
  'icon' => '',
  'name' => '',
];
$module_user_position = $program_wolves_setup['user_mng_postion'];
$user_type_menu = $program_wolves_setup['user_module'];
$basic_setting_menu = $program_wolves_setup['user_menu'];
$other_modules = $program_wolves_setup['permission']['module_list'];
$logo_image        = $program_wolves_setup['logo_image'];
$dark_logo_image   = $program_wolves_setup['dark_logo_image'];
$mobile_logo_image = $program_wolves_setup['mobile_logo_image'];
$wolve_permissions = $program_wolves_setup['user_permission'];
$module_sidebar_mode = $program_wolves_setup['program']['module_sidebar_mode'];
function sidenavMenu()
{
  global $_GET, $_POST, $_PAGE, $is_full, $count_menu, $active_menu, $logo_image, $dark_logo_image, $mobile_logo_image, $wolve_permissions;

  $has_sidenav = true;

  $menu_name = (isset($_POST['menu_name'])) ? $_POST['menu_name'] : '';

  $is_user = W_User::isLoginUser();
  $is_dev = W_User::isDev();
  $is_user_dev = $is_user && $is_dev;
  $user_info = W_User::getCurrentUser();
  $dev_info = W_User::getCurrentDev();
  $log = W_Log::$log_list;
  $permissions  = '';
  $favorite_list     = [];
  if ($user_info['id']) {
    $permissions       = $wolve_permissions;
  }
  $program_name      = W_WLoves::getProgramName();
  $modules           = W_Permission::$functions_available;


  if (W_Structure::getThemeClass() == 'dark') {
    $logo_image = $dark_logo_image;
  }

  $mini_menu = W_Main::cookie('_menu_style') ? 'is-hide' : '';
  $favorite = W_Main::cookie('_page_favorite');
  $favorite = $favorite ? json_decode($favorite, true) : [];

  //วาด menu log
  function btnlogSvg($log)
  {
    ob_start();
?>
    <svg id="Mediamodifier-Design" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20">
      <g id="Group_95777" data-name="Group 95777">
        <g id="Group_95776" data-name="Group 95776">
          <g id="Subtraction_2" transform="translate(9.997)">
            <path id="Path_139979" data-name="Path 139979" d="M-2848,10h-3V5a2,2,0,0,0-2-2h-5V0h7a3,3,0,0,1,3,3Z" transform="translate(2858)" fill="<?= $log['backend_log']['color'] ?>" />
          </g>
          <g id="Rectangle_75953" transform="translate(9.999 4.999)">
            <path id="Path_139980" data-name="Path 139980" d="M0,0H4A1,1,0,0,1,5,1V5H0Z" fill="<?= $log['backend_log']['color'] ?>" />
          </g>
          <g id="Subtraction_2-2" transform="translate(9.997 9.997)">
            <path id="Path_139981" data-name="Path 139981" d="M7,10H0V7H5A2,2,0,0,0,7,5V0h3V7A3,3,0,0,1,7,10Z" fill="<?= $log['frontend_log']['color'] ?>" />
          </g>
          <g id="Rectangle_75953-2" transform="translate(9.999 9.999)">
            <path id="Path_139982" data-name="Path 139982" d="M0,0H5V4A1,1,0,0,1,4,5H0Z" fill="<?= $log['frontend_log']['color'] ?>" />
          </g>
          <g id="Subtraction_3" transform="translate(10.003 10.003) rotate(180)">
            <path id="Path_139983" data-name="Path 139983" d="M7,10H0V7H5A2,2,0,0,0,7,5V0h3V7A3,3,0,0,1,7,10Z" fill="<?= $log['sql_log']['color'] ?>" />
          </g>
          <g id="Rectangle_75954" transform="translate(4.999 4.999)">
            <path id="Path_139984" data-name="Path 139984" d="M1,0H5V5H0V1A1,1,0,0,1,1,0Z" fill="<?= $log['sql_log']['color'] ?>" />
          </g>
          <g id="Subtraction_3-2" transform="translate(10.003 20) rotate(180)">
            <path id="Path_139985" data-name="Path 139985" d="M10,10H7V5A2,2,0,0,0,5,3H0V0H7a3,3,0,0,1,3,3Z" fill="<?= $log['web_log']['color'] ?>" />
          </g>
          <g id="Rectangle_75954-2" transform="translate(4.999 9.999)">
            <path id="Path_139986" data-name="Path 139986" d="M0,0H5V5H1A1,1,0,0,1,0,4Z" fill="<?= $log['web_log']['color'] ?>" />
          </g>
        </g>
      </g>
    </svg>
  <?php
    return ob_get_clean();
  }

  function navbarWolvesX($favorite = false, $_PAGE = [], $modules, $is_dev, $permissions)
  {
    global $count_menu, $active_menu, $module_sidebar_mode, $module_user_position, $user_type_menu, $basic_setting_menu, $other_modules;

    echo '<div class="nav-x-container">';
    //menu module core
    include 'template_core_menu.php';
    if ($module_user_position == 'top') {
      //menu module User
      include 'template_user_menu.php';
    }
    //menu module other
    if (isset($module_sidebar_mode) && $module_sidebar_mode == 'single') {
      include 'template_other_single_menu.php';
    } else {
      if (isset($modules) && $modules) {
        include 'template_other_menu.php';
      }
    }

    if ($module_user_position == 'bottom') {
      //menu module User
      include 'template_user_menu.php';
    }
    echo '</div>';
  }
  ?>
  <?php
  $path_link = '../../../';
  $this_url = $_SERVER['REQUEST_URI'];
  $this_url =  explode('/', $this_url);
  $key = array_search('_framework', $this_url);
  if (!$key) {
    $path_link = '../../';
  }
  $version_data = [];
  if (file_exists($path_link . 'version/ms_commit.json')) {
    $json_version = file_get_contents($path_link . 'version/ms_commit.json');
    $version_data = json_decode($json_version, true);
  } else {
    $version_data = [
      'version' => [
        'full' => 'not found file version',
        'major' => 0,
        'minor' => 0,
        'patch' => 0,
      ],
      'datetime' => '0000-00-00 00:00:00',
    ];
  }
  ?>


  <!-- left menu -->
  <div id="nav-x" class="<?= $mini_menu ?>">
    <?= navbarWolvesX(false, $_PAGE, $modules, $is_dev, $permissions) ?>
    <div class="x-version" title="<?= $version_data['datetime'] ?>">Version <?= $version_data['version']['full'] ?></div>
  </div>

  <!-- top menu -->
  <?php
  include_once 'top_menu.php';
  ?>

  <!-- favorite menu -->
  <div id="nav-x-favorite-menu">
    <div class="x-top-detail">Select Page for add to top bar.</div>
    <?php navbarWolvesX(true, $_PAGE, $modules, $is_dev, $permissions)
    ?>
  </div>

<?php
} //end function sidenavMenu

sidenavMenu();
?>

<script>
  var hoverTimeoutX;
  var nav_x_hide = true;
  var hoverTimeoutLanguageX;
  var nav_x_language_hide = true;
  var hoverTimeoutProfileX;
  var nav_x_profile_hide = true;
  var initial_data = '<?= json_encode(W_Main::getMenuCode()) ?>';
  $(function() {
    //main setting  
    $(document).on('click', '.nav-x-main-setting', function(e) {
      e.stopImmediatePropagation();
    });

    //show hide nav cateogry
    $(document).on('click', '.nav_x_category_event', function(e) {
      $(this).parents('.nav-x-list-area').toggleClass('is-hide');
    });

    // menu full and mini
    $(document).on('click', '.nav-x-chevron-arrow', function(e) {
      $(this).parents('.nav-x-header').toggleClass('is-hide');
      $('#nav-x').toggleClass('is-hide');
      $('#content-x').toggleClass('is-full');
      $('#nav-x').removeClass('is-hover');
      saveFullminiMenuEvent();
    });

    // menu full and mini
    $(document).on('click', '.nav-x-menu', function(e) {
      $(this).parents('.nav-x-header').toggleClass('is-hide');
      $('#nav-x').toggleClass('is-hide');
      $('#content-x').toggleClass('is-full');
      saveFullminiMenuEvent();
    });

    //animation menu
    $('.nav-x-menu').hover(
      function() {
        // if ($('#nav-x').hasClass('is-hide')) {
        nav_x_hide = true;
        $('#nav-x').addClass('is-hover');
        clearTimeout(hoverTimeoutX);
        // }
      },
      function() {
        // if ($('#nav-x').hasClass('is-hide')) {
        hoverTimeoutX = setTimeout(function() {
          if (nav_x_hide) {
            $('#nav-x').removeClass('is-hover');
          }
        }, 500);
        // }
      }
    );
    $('#nav-x').hover(
      function() {
        nav_x_hide = false;
        $('#nav-x').addClass('is-hover');
      },
      function() {
        $('#nav-x').removeClass('is-hover');
      }
    );

    //animation language
    $('.nav-x-language').hover(
      function() {
        nav_x_language_hide = true;
        $('.nav-x-language-dropdown').addClass('active');
        clearTimeout(hoverTimeoutLanguageX);

      },
      function() {

        hoverTimeoutLanguageX = setTimeout(function() {
          if (nav_x_language_hide) {
            $('.nav-x-language-dropdown').removeClass('active');
          }
        }, 500);

      }
    );
    $('.nav-x-language-dropdown').hover(
      function() {
        nav_x_language_hide = false;
        $('.nav-x-language-dropdown').addClass('active');
      },
      function() {
        $('.nnav-x-language-dropdown').removeClass('active');
      }
    );

    //animation profile
    $('.nav-x-btn-profile').hover(
      function() {

        nav_x_profile_hide = true;
        $('.nav-x-profile-dropdown').addClass('active');
        clearTimeout(hoverTimeoutProfileX);

      },
      function() {

        hoverTimeoutProfileX = setTimeout(function() {
          if (nav_x_profile_hide) {
            $('.nav-x-profile-dropdown').removeClass('active');
          }
        }, 500);

      }
    );
    $('.nav-x-profile-dropdown').hover(
      function() {
        nav_x_profile_hide = false;
        $('.nav-x-profile-dropdown').addClass('active');
      },
      function() {
        $('.nav-x-profile-dropdown').removeClass('active');
      }
    );

    //animation favorite
    $('.btn-nav-x-add').hover(
      function() {
        nav_x_profile_hide = true;
        $('#nav-x-favorite-menu').addClass('active');
        clearTimeout(hoverTimeoutProfileX);

      },
      function() {
        hoverTimeoutProfileX = setTimeout(function() {
          if (nav_x_profile_hide) {
            $('#nav-x-favorite-menu').removeClass('active');
          }
        }, 500);
      }
    );
    $('#nav-x-favorite-menu').hover(
      function() {
        nav_x_profile_hide = false;
        $('#nav-x-favorite-menu').addClass('active');
      },
      function() {
        $('#nav-x-favorite-menu').removeClass('active');
      }
    );

    //scrollbar favotirt menu
    $(".nav-x-favorite").mousewheel(function(event, delta) {
      this.scrollLeft -= (delta * 30);
      event.preventDefault();
    });

    // //scrollbar top menu
    // $(".nav-x-favorite").mousewheel(function(event, delta) {
    //   this.scrollLeft -= (delta * 30);
    //   event.preventDefault();
    // });

    //delete favorite
    $(document).on('click', '.btn-delete-nav-x-favorite', function(e) {
      e.preventDefault()
      var scope = $(this).parents('.nav-x-favorite-list-group');
      scope.remove();
      var code = $(this).attr('data-code');
      var uri = window.location.href;
      var uri = uri.split('/');
      var index_framework = uri.indexOf('_framework');
      if (index_framework != -1) {
        url = '../../../_framework/resources/layout/ajax/ajax_delete_favorite.php';
      } else {
        url = '../../_framework/resources/layout/ajax/ajax_delete_favorite.php';
      }
      $.post(url, {
        'code': code
      }).done(function(data) {});
    });

    //add favorite
    $(document).on('click', '.nav-x-add-favorite', function(e) {
      var name = $(this).attr('data-name');
      var page = $(this).attr('data-url');
      var code = $(this).attr('data-code');
      var uri = window.location.href;
      var uri = uri.split('/');
      var index_framework = uri.indexOf('_framework');
      if (index_framework != -1) {
        url = '../../../_framework/resources/layout/ajax/ajax_add_favorite.php';
      } else {
        url = '../../_framework/resources/layout/ajax/ajax_add_favorite.php';
      }
      var params = {
        'code': code,
        'page': page,
        'name': name,
      }
      $.post(url, params).done(function(data) {
        $('.nav-x-favorite').html(data);
      });
    });
  });

  function saveFullminiMenuEvent() {
    var is_mini = $('#nav-x').hasClass('is-hide');
    var uri = window.location.href;
    var uri = uri.split('/');
    var index_framework = uri.indexOf('_framework');
    if (index_framework != -1) {
      url = '../../../_framework/resources/layout/ajax/ajax_menu_style.php';
    } else {
      url = '../../_framework/resources/layout/ajax/ajax_menu_style.php';
    }
    $.post(url, {
      'is_mini': is_mini
    }).done(function(data) {});
  }

  //change language
  $(document).on('click', '.change_language_event', function(e) {
    let language = e.target.dataset.set;
    e.preventDefault()
    var path = location.pathname.split('/');
    var path_sum = '';
    let inx_target = 0;
    path.forEach((ele, inx) => {
      if (ele == '_framework' || ele == 'module') {
        inx_target = inx;
      }
      if (inx > 0 && (inx < inx_target || inx_target == 0)) {
        path_sum += '/' + ele;
      }
    })
    if (inx_target == 0) {
      path_sum = '/' + path[1];
    }
    var url = path_sum + '/_framework/resources/layout/ajax/ajax_change_language.php';
    $.post(url, {
      'language': language
    }).done(function(data) {
      location.reload();
    });
    $('.language-list').removeClass('active');
    $(this).addClass('active');
  });
</script>

<div id="content-x" class="content-wrap <?= $is_full ?>">