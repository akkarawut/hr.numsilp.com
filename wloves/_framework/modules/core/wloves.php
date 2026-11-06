<?php
class W_Wloves
{
  public static $initial_data    = [];
  public static $favorite_data   = [];
  public static $sidenav_is_open = [];
  public static $notification    = [];
  static function init()
  {
    global $program_wolves_setup;
    $can_import = true;

    // if (class_exists('Installation') && !Installation::hasConfigFile()) {
    //   $can_import = false;
    // }
    if ($can_import) {
      static::$initial_data = $program_wolves_setup;

      if (!static::$initial_data) {
        echo 'Empty initial_data';
        die();
      }
      W_User::$get_current = static::$initial_data['current_user_data'];
      W_User::$get_permission = static::$initial_data['user_permission'];


      // มี module อะไรบ้าง
      W_Permission::$functions_available = isset(static::$initial_data['permission']['module_list']) ? static::$initial_data['permission']['module_list'] : [];
      // มี permission อะไรบ้าง
      W_Permission::$has_permissions     = isset(static::$initial_data['permission']['allow_list']) ? static::$initial_data['permission']['allow_list'] : [];

      W_Config::$menu_core_list['Core']['menu_items'][1]['sub_menu'][3]['url'] = isLocalhost() ? 'dbadminer/' : 'dbadminer/adminer_login.php';

      W_Log::$log_list = isset(static::$initial_data['sidenav']['log']) ? static::$initial_data['sidenav']['log'] : [];

      W_Log::init();
    }
  }

  static function getProgramName()
  {
    $program_name = isset(static::$initial_data['program']['program_name']) ? static::$initial_data['program']['program_name'] : '';
    return $program_name;
  }

  static function setConfigMenuList($module_name, $path_prefix = '')
  {
    if (strtolower($module_name) == 'user') {
      if (file_exists(F_ROOT_PHP . '/_framework/resources/user/config/menu.php')) {
        include F_ROOT_PHP . '/_framework/resources/user/config/menu.php';
      }
    } else {
      if (file_exists(F_ROOT_PHP . '/module/' . strtolower($module_name) . '/config/menu.php')) {
        include F_ROOT_PHP . '/module/' . strtolower($module_name) . '/config/menu.php';
      }
    }
  }
}
