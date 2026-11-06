<?php
class W_Structure
{
  const OG_DATA = [
    'url'         => CURRENT_URL,
    'title'       => F_OG_TITLE,
    'image'       => F_ROOT_PHP . F_OG_IMAGE_URL,
    'description' => F_OG_DESCRIPTION
  ];

  public static $assets_header = [
    '_framework/structure/js/w.js',
    '_framework/structure/js/w-js/function.js',
    '_framework/library/jquery/jquery-3.5.1.min.js',
    '_framework/library/jquery/jquery.serializejson.min.js',
    '_framework/library/pace/theme/pace-flash.css',
    '_framework/library/pace/pace.min.js',
    '_framework/library/bootstrap_5.2.3/bootstrap.min.css',
    '_framework/library/toastr/toastr.min.css',
    '_framework/library/jquery-ui/jquery-ui.css',
    '_framework/library/rainbow/rainbow.min.css',
    '_framework/library/momentJS/moment.js',
    '_framework/library/momentJS/moment-with-locales.js',
    '_framework/structure/css/coreCSS.css',
    '_framework/structure/css/main.css',
    '_framework/library/rainbow/rainbow-custom.min.js',
  ];

  public static $assets_footer = [
    '_framework/library/jquery/jquery.mousewheel.min.js',
    '_framework/library/clipboard.js/clipboard.min.js',
    '_framework/library/toastr/toastr.min.js',
    '_framework/library/bootstrap_5.2.3/bootstrap.bundle.min.js',
    '_framework/library/jquery-ui/jquery-ui.js',
    '_framework/library/jquery/polyfill.js',
    '_framework/library/jquery/jquery.cookie.js',
    '_framework/structure/js/custom.js',
    '_framework/structure/js/theme_color.js',
    '_framework/structure/js/w-js/loading.js',
  ];

  /* LAYOUT */
  public static function loadModules($module_array = [])
  {
    // Load module
    if ($module_array) {
      foreach ($module_array as $module) {
        include F_ROOT_PHP . '/_framework/modules/sub/' . $module . '/config.php';

        if ($files_list['header']) {
          foreach ($files_list['header'] as $files) {
            $files_path = '_framework/modules/sub/' . $module . '/' . $files;
            array_push(self::$assets_header, $files_path);
          }
        }

        if ($files_list['footer']) {
          foreach ($files_list['footer'] as $files) {
            $files_path = '_framework/modules/sub/' . $module . '/' . $files;
            array_push(self::$assets_footer, $files_path);
          }
        }

        if (isset($files_list['php'])) {
          foreach ($files_list['php'] as $files) {
            $files_path = F_ROOT_PHP . '/_framework/modules/sub/' . $module . '/' . $files;
            include $files_path;
          }
        }
        if (isset($files_list['library']) && $files_list['library']) {
          foreach ($files_list['library'] as $files) {
            $files_path = '_framework/library/' . $files;
            array_push(self::$assets_header, $files_path);
          }
        }
      }
      return true;
    }
    return false;
  }

  public static function loadModulesForAjax($module_array = [], $prefix = "../../")
  {
    // Load module
    if ($module_array) {
      foreach ($module_array as $module) {
        include F_ROOT_PHP . '/_framework/modules/sub/' . $module . '/config.php';

        if ($files_list['php']) {
          foreach ($files_list['php'] as $files) {
            $files_path = F_ROOT_PHP . '/_framework/modules/sub/' . $module . '/' . $files;
            include $files_path;
          }
        }
      }
      return true;
    }
    return false;
  }

  private static function hexToRGB($color)
  {
    return implode(',', sscanf($color, "#%02x%02x%02x"));
  }

  public static function checkFileTheme()
  {
    global $program_wolves_setup;
    $program =  $program_wolves_setup['program'];
    $html = ' ';
    if (isset($program) && !isset($program['color_nav_border']) || !isset($program['dark_color_nav_border'])) {
      // $program['color_highlight'] = '#235E88';
      // light theme
      //color topbar
      $program['color_bg_top_bar'] = '#FFFFFF';
      $program['color_add_page_btn'] = '#676A6F';
      $program['color_selected_menu'] = '#F4F4F4';
      $program['color_base_icon'] = '#202125';
      $program['color_border'] = '#DBDCDF';
      //color nav
      $program['color_base_background'] = '#FFFFFF';
      $program['color_nav_selected'] = '#E3F5FF';
      $program['color_nav_hover'] = '#ECF3F7';
      $program['color_nav_font_primary'] = '#202125';
      $program['color_nav_font_secondary'] = '#676A6F';
      $program['color_nav_border'] = '#DBDCDF';

      // dark theme
      //color topbar
      $program['dark_color_bg_top_bar'] = '#17171C';
      $program['dark_color_add_page_btn'] = '#9CA2AC';
      $program['dark_color_selected_menu'] = '#111117';
      $program['dark_color_base_icon'] = '#9CA2AC';
      $program['dark_color_border'] = '#222E45';
      //color nav
      $program['dark_color_base_background'] = '#17171C';
      $program['dark_color_nav_selected'] = '#2C3341';
      $program['dark_color_nav_hover'] = '#111117';
      $program['dark_color_nav_font_primary'] = '#FFFFFF';
      $program['dark_color_nav_font_secondary'] = '#9CA2AC';
      $program['dark_color_nav_border'] = '#222E45';
    }
    $html .= "<style>\n";
    $html .= ":root {\n";
    $html .= '--color-highlight: ' . $program['color_highlight'] . ";\n";
    $html .= '--color-highlight-rgb: ' . self::hexToRGB($program['color_highlight']) . ";\n";
    //tobbar
    $html .= '--bg-top-bar: ' . $program['color_bg_top_bar'] . ";\n";
    $html .= '--bg-top-bar-add-page: ' . $program['color_add_page_btn'] . ";\n";
    $html .= '--hover-top-bar-add-page: ' . $program['color_selected_menu'] . ";\n";
    $html .= '--color-font-top-bar: ' . $program['color_base_icon'] . ";\n";
    $html .= '--color-border-top-bar: ' . $program['color_border'] . ";\n";
    //nav
    $html .= '--bg-nav: ' . $program['color_base_background'] . ";\n";
    $html .= '--bg-nav-selected: ' . $program['color_nav_selected'] . ";\n";
    $html .= '--bg-nav-hover: ' . $program['color_nav_hover'] . ";\n";
    $html .= '--color-font-nav-primary: ' . $program['color_nav_font_primary'] . ";\n";
    $html .= '--color-font-nav-secondary: ' . $program['color_nav_font_secondary'] . ";\n";
    $html .= '--color-border-nav: ' . $program['color_nav_border'] . ";\n";
    $html .= " } \n";
    $html .= "[data-theme='dark'] {\n";
    $html .= '--color-highlight-rgb: ' . self::hexToRGB($program['color_highlight']) . ";\n";
    //tobbar
    $html .= '--bg-top-bar: ' . $program['dark_color_bg_top_bar'] . ";\n";
    $html .= '--bg-top-bar-add-page: ' . $program['dark_color_add_page_btn'] . ";\n";
    $html .= '--hover-top-bar-add-page: ' . $program['dark_color_selected_menu'] . ";\n";
    $html .= '--color-font-top-bar: ' . $program['dark_color_base_icon'] . ";\n";
    $html .= '--color-border-top-bar: ' . $program['dark_color_border'] . ";\n";
    //nav
    $html .= '--bg-nav: ' . $program['dark_color_base_background'] . ";\n";
    $html .= '--bg-nav-selected: ' . $program['dark_color_nav_selected'] . ";\n";
    $html .= '--bg-nav-hover: ' . $program['dark_color_nav_hover'] . ";\n";
    $html .= '--color-font-nav-primary: ' . $program['dark_color_nav_font_primary'] . ";\n";
    $html .= '--color-font-nav-secondary: ' . $program['dark_color_nav_font_secondary'] . ";\n";
    $html .= '--color-border-nav: ' . $program['dark_color_nav_border'] . ";\n";
    $html .= " } \n";
    $html .= "</style>";
    echo $html;
  }

  public static function loadMeta($prefix = "", $og_data = self::OG_DATA, $title = F_SITE_TITLE, $keywords = F_SITE_KEYWORDS, $description = F_SITE_DESCRIPTION)
  {
    global $program_wolves_setup;

    if (!$title && !$keywords && !$description) {
      return false;
    }

    if ($title == 'W.loves' || !$title) {
      $title = 'อ้าว ไม่เปลียนชื่อใน config';
    }
    if ($keywords == 'Backend system by WOLVES' || !$keywords) {
      $keywords = 'อ้าว ไม่เปลี่ยน keywords';
    }
    if ($description == 'Backend system by WOLVES' || !$description) {
      $description = 'อ้าว ยังไม่เปลี่ยนรายละเอียดอีกเร๊อะ';
    }

    // Load module_main
    $module_main_array = [];
    foreach (glob(F_ROOT_PHP . '/_framework/modules/main/*', GLOB_ONLYDIR) as $dir) {
      array_push($module_main_array, basename($dir));
    }

    if ($module_main_array) {
      foreach ($module_main_array as $module_main) {
        include F_ROOT_PHP . '/_framework/modules/main/' . $module_main . '/config.php';

        if ($files_list['header']) {
          foreach ($files_list['header'] as $files) {
            $files_path = '_framework/modules/main/' . $module_main . '/' . $files;
            array_push(self::$assets_header, $files_path);
          }
        }

        if ($files_list['footer']) {
          foreach ($files_list['footer'] as $files) {
            $files_path = '_framework/modules/main/' . $module_main . '/' . $files;
            array_push(self::$assets_footer, $files_path);
          }
        }

        if ($files_list['php']) {
          foreach ($files_list['php'] as $files) {
            $files_path = F_ROOT_PHP . '/_framework/modules/main/' . $module_main . '/' . $files;
            include $files_path;
          }
        }
        if (isset($files_list['library']) && $files_list['library']) {
          foreach ($files_list['library'] as $files) {
            $files_path = '_framework/library/' . $files;
            array_push(self::$assets_header, $files_path);
          }
        }
      }
    }

    $html = '';
    $html .= '<title> ' . $title . ' </title>';

    $html .= '<meta name="robots" content="noindex" />';

    $html .= '<meta charset="utf-8">';
    $html .= '<meta http-equiv="X-UA-Compatible" content="IE=edge, chrome=1" />';
    $html .= '<meta name="viewport" content="width=device-width, initial-scale=1.0" />';

    $html .= '<meta name="keywords" content="' . $keywords . '">';
    $html .= '<meta name="description" content="' . $description . '">';

    $html .= '<meta name="apple-mobile-web-app-capable" content="yes">';


    $icon = '';
    if (strpos($program_wolves_setup['favicon'], '/placeholder/') === false) {
      $icon = $program_wolves_setup['favicon'];
    } else {
      $path_link = '../../../../';
      $this_url = $_SERVER['REQUEST_URI'];
      $this_url =  explode('/', $this_url);
      $key = array_search('_framework', $this_url);
      if (!$key) {
        $path_link = '../../../';
      }
      $icon = str_replace('/var/www/html/', '', $og_data['image']);
      $icon = $path_link . $icon;
    }
    $html .= '<link rel="icon" href="' . $icon . '" type="image/x-icon">';
    $html .= '<link rel="shortcut icon" href="' . $icon . '" type="image/x-icon">';


    // $html .= '<link rel="shortcut icon" href="' . $link . '/_framework/structure/css/main.css" type="image/x-icon">';
    // $html .= '<script type="module" src="' . $link . '/_framework/modules/w.js"></script>';
    if ($og_data) {
      $html .= '<meta property="og:type" content="website"/>';
      $html .= ($og_data['url']) ? '<meta property="og:url" content="' . $og_data['url'] . '"/>' : '';
      $html .= ($og_data['title']) ? '<meta property="og:title" content="' . $og_data['title'] . '"/>' : '';
      $html .= ($og_data['image']) ? '<meta property="og:image" content="' . $og_data['image'] . '">' : '';
      $html .= ($og_data['description']) ? '<meta property="og:description" content="' . $og_data['description'] . '"/>' : '';
      if (isset($og_data['app_id'])) {
        $html .= ($og_data['app_id']) ? '<meta property="og:app_id" content="' . $og_data['app_id'] . '"/>' : '';
      }
    }

    echo $html;

    self::checkFileTheme();

    $path_link = '../../../';
    $this_url = $_SERVER['REQUEST_URI'];
    $this_url =  explode('/', $this_url);
    $key = array_search('_framework', $this_url);
    if (!$key) {
      $path_link = '../../';
    }

    // $path_ = str_replace('/var/www/html/', '', F_ROOT_PHP);
    // $link = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . '/' . $path_ . '/';
    if (self::$assets_header) {
      foreach (self::$assets_header as $load_header) {
        $strexplode       = explode(".", $load_header);
        $strexplode_count = count($strexplode);
        $type             = $strexplode[$strexplode_count - 1];
        $file_link        = $path_link . $load_header;
        $file_link        = W_Main::assetPath($file_link);

        if ($type == 'js') {
          echo '<script src="' . $file_link . '"></script>';
        } else if ($type == 'css') {
          echo '<link rel="stylesheet" href="' . $file_link . '">';
        } else {
          echo 'Invalid file type. [' . $file_link . ']';
        }
      }
    }

    return true;
  }

  public static function loadMetaForAjax($prefix = "")
  {
    // Load module_main
    $module_main_array = [];
    foreach (glob(F_ROOT_PHP . '/_framework/modules/main/*', GLOB_ONLYDIR) as $dir) {
      array_push($module_main_array, basename($dir));
    }
    if ($module_main_array) {
      foreach ($module_main_array as $module_main) {
        include F_ROOT_PHP . '/_framework/modules/main/' . $module_main . '/config.php';

        if ($files_list['php']) {
          foreach ($files_list['php'] as $files) {
            $files_path = F_ROOT_PHP . '/_framework/modules/main/' . $module_main . '/' . $files;
            include $files_path;
          }
        }
      }
    }

    return true;
  }

  public static function loadFooter($prefix = "")
  {
    $load_footers = self::$assets_footer;

    $path_link = '../../../';
    $this_url = $_SERVER['REQUEST_URI'];
    $this_url =  explode('/', $this_url);
    $key = array_search('_framework', $this_url);
    if (!$key) {
      $path_link = '../../';
    }
    if (self::$assets_footer) {
      foreach (self::$assets_footer as $load_header) {
        $strexplode       = explode(".", $load_header);
        $strexplode_count = count($strexplode);
        $type             = $strexplode[$strexplode_count - 1];
        $file_link        = $path_link . $load_header;
        $file_link = W_Main::assetPath($file_link);

        if ($type == 'js') {
          echo '<script src="' . $file_link . '"></script>';
        } else if ($type == 'css') {
          echo '<link rel="stylesheet" href="' . $file_link . '">';
        } else {
          echo 'Invalid file type. [' . $file_link . ']';
        }
      }
    }

    W_Main::handleNotification();

    if (F_IS_BRIDGE) {
      if (isLocalhost()) {
        echo
        '<script>
        let curl_data = {
          CURL_INFO: true,
          curl_count: ' . count(W_Bridge::$curl_data) . ',
          function_call: ' . json_encode(W_Bridge::$curl_data) . '
        }

        // console.log(curl_data);
        </script>';
      }
    }

    include F_ROOT_PHP . '/_framework/modules/main/detiw/menu.php';
    include F_ROOT_PHP . '/_framework/modules/main/detiw/detiw.php';

    return true;
  }

  /* THEME */
  public static function getThemeClass()
  {
    global $get_theme;
    $theme = $get_theme;
    $class_name = 'light';
    if ($theme == 'dark') {
      $class_name = 'dark';
    }
    return $class_name;
  }


  public static function getLanguages()
  {
    global $program_wolves_setup;
    $program = $program_wolves_setup['program'];
    $languages = [];
    $languages['system_languages'] = isset($program['system_languages']) ? $program['system_languages'] : [];
    $languages['default_system_language'] = isset($program['default_system_language']) ? $program['default_system_language'] : [];
    return $languages;
  }
  public static function getfile($file_path)
  {
    if ($file_path) {
      $file_path = F_ROOT_PHP . '/' . $file_path;
      return file_get_contents($file_path);
    } else {
      return false;
    }
  }
  public static function getLinkURL($url)
  {
    if ($url) {
      // $path_ = str_replace('/var/www/html/', '', F_ROOT_PHP);
      // $link = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . '/' . $path_ . '/' . $url;

      // แปลง \ เป็น / ก่อนตัด DOCUMENT_ROOT เพราะบน Windows สอง path ใช้ตัวคั่นต่างกัน
      $document_root = rtrim(str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT']), '/');
      $root_php      = str_replace('\\', '/', F_ROOT_PHP);
      if (stripos($root_php, $document_root) === 0) {
        $path_ = substr($root_php, strlen($document_root));
      } else {
        $path_ = $root_php;
      }
      $scheme = isset($_SERVER['REQUEST_SCHEME']) ? $_SERVER['REQUEST_SCHEME'] : ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http');
      $link   = $scheme . '://' . $_SERVER['HTTP_HOST'] . $path_ . '/' . $url;

      return $link;
    } else {
      return false;
    }
  }
  public static function getFileName($url)
  {
    $result = [
      'full_name' => '',
      'name' => '',
      'ext' => '',
      'is_placeholder' => false,
    ];
    $result['name'] = pathinfo($url, PATHINFO_FILENAME);
    $result['ext'] = pathinfo($url, PATHINFO_EXTENSION);
    $result['ext'] = explode('?', $result['ext'])[0];
    $result['full_name'] = $result['name'] . '.' . $result['ext'];
    $result['is_placeholder'] = $result['name'] == 'placeholder_square' ? true : false;
    return $result;
  }
  public static function loadMetaForWebsite($prefix = "", $og_data = self::OG_DATA, $title = F_SITE_TITLE, $keywords = F_SITE_KEYWORDS, $description = F_SITE_DESCRIPTION)
  {
    if (!$title && !$keywords && !$description) {
      return false;
    }

    if ($title == 'W.loves' || !$title) {
      $title = 'อ้าว ไม่เปลียนชื่อใน config';
    }
    if ($keywords == 'Backend system by WOLVES' || !$keywords) {
      $keywords = 'อ้าว ไม่เปลี่ยน keywords';
    }
    if ($description == 'Backend system by WOLVES' || !$description) {
      $description = 'อ้าว ยังไม่เปลี่ยนรายละเอียดอีกเร๊อะ';
    }

    // Load module_main
    $module_main_array = [];
    foreach (glob(F_ROOT_PHP . '/_framework/modules/main/*', GLOB_ONLYDIR) as $dir) {
      array_push($module_main_array, basename($dir));
    }

    if ($module_main_array) {
      foreach ($module_main_array as $module_main) {
        include F_ROOT_PHP . '/_framework/modules/main/' . $module_main . '/config.php';

        if ($files_list['header']) {
          foreach ($files_list['header'] as $files) {
            $files_path = '_framework/modules/main/' . $module_main . '/' . $files;
            array_push(self::$assets_header, $files_path);
          }
        }

        if ($files_list['footer']) {
          foreach ($files_list['footer'] as $files) {
            $files_path = '_framework/modules/main/' . $module_main . '/' . $files;
            array_push(self::$assets_footer, $files_path);
          }
        }

        if ($files_list['php']) {
          foreach ($files_list['php'] as $files) {
            $files_path = F_ROOT_PHP . '/_framework/modules/main/' . $module_main . '/' . $files;
            include $files_path;
          }
        }
        if (isset($files_list['library']) && $files_list['library']) {
          foreach ($files_list['library'] as $files) {
            $files_path = $prefix . '_framework/library/' . $files;
            array_push(self::$assets_header, $files_path);
          }
        }
      }
    }
    $html = '';
    $html .= '<title> ' . $title . ' </title>';

    $html .= '<meta charset="utf-8">';
    $html .= '<meta http-equiv="X-UA-Compatible" content="IE=edge, chrome=1" />';
    $html .= '<meta name="viewport" content="width=device-width, initial-scale=1.0" />';

    $html .= '<meta name="keywords" content="' . $keywords . '">';
    $html .= '<meta name="description" content="' . $description . '">';

    $html .= '<meta name="apple-mobile-web-app-capable" content="yes">';

    $icon = '../../favicon.ico';
    if (file_exists($icon)) {
      $html .= '<link rel="icon" href="' . $icon . '" type="image/x-icon">';
      $html .= '<link rel="shortcut icon" href="' . $icon . '" type="image/x-icon">';
    }


    // $html .= '<link rel="shortcut icon" href="' . $link . '/_framework/structure/css/main.css" type="image/x-icon">';
    // $html .= '<script type="module" src="' . $link . '/_framework/modules/w.js"></script>';
    if ($og_data) {
      $html .= '<meta property="og:type" content="website"/>';
      $html .= ($og_data['url']) ? '<meta property="og:url" content="' . $og_data['url'] . '"/>' : '';
      $html .= ($og_data['title']) ? '<meta property="og:title" content="' . $og_data['title'] . '"/>' : '';
      $html .= ($og_data['image']) ? '<meta property="og:image" content="' . $og_data['image'] . '">' : '';
      $html .= ($og_data['description']) ? '<meta property="og:description" content="' . $og_data['description'] . '"/>' : '';
      if (isset($og_data['app_id'])) {
        $html .= ($og_data['app_id']) ? '<meta property="og:app_id" content="' . $og_data['app_id'] . '"/>' : '';
      }
    }

    echo $html;

    $path_link = 'wloves/';


    if (self::$assets_header) {
      foreach (self::$assets_header as $key_header =>  $load_header) {
        if ($load_header == '_framework/library/rainbow/rainbow-custom.min.js') {
          continue;
        }
        if ($load_header == '_framework/library/rainbow/rainbow.min.css') {
          continue;
        }

        $strexplode       = explode(".", $load_header);
        $strexplode_count = count($strexplode);
        $type             = $strexplode[$strexplode_count - 1];
        $file_link        = $path_link . $load_header;
        $file_link        = W_Main::assetPath($file_link);
        if ($type == 'js') {
          echo '<script src="' . $prefix . $file_link . '"></script>';
        } else if ($type == 'css') {
          echo '<link rel="stylesheet" href="' . $prefix . $file_link . '">';
        } else {
          echo 'Invalid file type. [' . $prefix . $file_link . ']';
        }
      }
    }


    return true;
  }

  public static function loadFooterForWebsite($prefix = "")
  {
    $load_footers = self::$assets_footer;

    $path_link = 'wloves/';

    if (self::$assets_footer) {
      echo '<div class="toast-noti fixed-bottom position-bottom p-0"></div>';
      foreach (self::$assets_footer as $load_header) {
        $strexplode       = explode(".", $load_header);
        $strexplode_count = count($strexplode);
        $type             = $strexplode[$strexplode_count - 1];
        $file_link        = $path_link . $load_header;
        $file_link = W_Main::assetPath($file_link);

        if ($type == 'js') {
          echo '<script src="' . $prefix . $file_link . '"></script>';
        } else if ($type == 'css') {
          echo '<link rel="stylesheet" href="' . $prefix . $file_link . '">';
        } else {
          echo 'Invalid file type. [' . $prefix . $file_link . ']';
        }
      }
    }

    W_Main::handleNotification();

    if (F_IS_BRIDGE) {
      if (isLocalhost()) {
        echo
        '<script>
        let curl_data = {
          CURL_INFO: true,
          curl_count: ' . count(W_Bridge::$curl_data) . ',
          function_call: ' . json_encode(W_Bridge::$curl_data) . '
        }

        // console.log(curl_data);
        </script>';
      }
    }

    include F_ROOT_PHP . '/_framework/modules/main/detiw/menu.php';
    include F_ROOT_PHP . '/_framework/modules/main/detiw/detiw.php';

    return true;
  }
  public static function loadModulesForWebsite($module_array = [], $prefix = "")
  {
    // Load module
    if ($module_array) {
      foreach ($module_array as $module) {
        include F_ROOT_PHP . '/_framework/modules/sub/' . $module . '/config.php';

        if ($files_list['header']) {
          foreach ($files_list['header'] as $files) {
            $files_path = '_framework/modules/sub/' . $module . '/' . $files;
            array_push(self::$assets_header, $files_path);
          }
        }

        if ($files_list['footer']) {
          foreach ($files_list['footer'] as $files) {
            $files_path = '_framework/modules/sub/' . $module . '/' . $files;
            array_push(self::$assets_footer, $files_path);
          }
        }

        if ($files_list['php']) {
          foreach ($files_list['php'] as $files) {
            $files_path = F_ROOT_PHP . '/_framework/modules/sub/' . $module . '/' . $files;
            include $files_path;
          }
        }
        if (isset($files_list['library']) && $files_list['library']) {
          foreach ($files_list['library'] as $files) {
            $files_path = '_framework/library/' . $files;
            array_push(self::$assets_header, $files_path);
          }
        }
      }
      return true;
    }
    return false;
  }
}
