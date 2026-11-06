<?php
class W_Permission
{
  public static $has_permissions     = [];
  public static $functions_available = [];
  public static $main_menus          = [];
  public static $basic_settings      = [
    [
      'key' => 'permission_template',
      'name' => 'Permission Template',
    ],
    [
      'key' => 'team',
      'name' => 'Team',
    ],
    [
      'key' => 'category',
      'name' => 'Category',
    ],
    [
      'key' => 'tag',
      'name' => 'Tag',
    ],
  ];

  // check permission for core and module page

  public static function checkCore()
  {
    $is_dev = W_User::isDev();
    if ($is_dev) {
      return true;
    }

    W_Main::notification('No Dev Permission', 'error');
    W_Main::redirect(W_Structure::getLinkURL('_framework/resources/logout/index.php'));
    exit();
  }

  public static function checkModule($page, $is_ajax = false)
  {
    global $_GET;

    $permissions = '';
    $user_info = '';
    $is_login_user   = W_User::isLoginUser();
    $is_login_dev    = W_User::isDev();
    if ($is_login_user) {
      $user_info = W_User::getCurrentUser();
      $permissions = W_User::getPermission();
    }

    if ($page['permission'][0] == 'user') {
      if ($is_login_dev) {
        return true;
      }
      if ($is_login_user) {
        if (isset($permissions[$_GET['c'] . '_' . $page['permission'][0]]) && $permissions[$_GET['c'] . '_' . $page['permission'][0]] == 1) {
          return true;
        }
        if ($page['permission'][1] == 'user_profile') {
          return true;
        }
      }
    } else if (isset($_GET['c']) && $_GET['c']) {
      if ($page['permission'][2]) {
        if (isset($permissions[$_GET['c'] . '_' . $page['permission'][2]]) && $permissions[$_GET['c'] . '_' . $page['permission'][2]] == 1) {
          return true;
        }
      } else if ($page['permission'][1]) {
        if (isset($permissions[$_GET['c'] . '_' . $page['permission'][1]]) && $permissions[$_GET['c'] . '_' . $page['permission'][1]] == 1) {
          return true;
        }
      } else if ($page['permission'][0]) {
        if (isset($permissions[$_GET['c'] . '_' . $page['permission'][0]]) && $permissions[$_GET['c'] . '_' . $page['permission'][0]] == 1) {
          return true;
        }
      }
    } else if (($page['permission'][0] == 'landing' && $is_login_dev) || ($page['permission'][0] == 'landing' && $is_login_user)) {
      return true;
    } else if ($page['permission'][0] == 'core' && $is_login_dev) {
      return true;
    }
    // W_Main::console($is_login_user ? 'Login' : 'Not Login');
    // W_Main::console($permissions);
    // die;
    if ($is_ajax) {
      W_Main::display('{"status":"error","message":"No Permission"}');
      die;
    } else {
      W_Main::notification('No User Permission', 'error');
      W_Main::redirect(W_Structure::getLinkURL('_framework/resources/logout/index.php'));
    }
  }

  // redirect
  public static function redirectToFirstModuleAvailable()
  {
    foreach (W_Permission::$functions_available as $function) {
      W_WLoves::setConfigMenuList($function['module']);
      if (isset(W_Config::$menu_list[$function['module']])) {
        $menu = W_Config::$menu_list[$function['module']];
        foreach ($menu['menu_items'] as $menu_items) {
          foreach ($menu_items['sub_menu'] as  $sub_menu) {
            if (isset(W_Permission::$has_permissions[$function['code'] . '_' . $sub_menu['page_name']])) {
              if ($sub_menu['url']) {
                W_Main::redirect('module/' . $menu['page_name'] . '/' . $sub_menu['url'] . '?c=' . $function['code']);
              }
            }
          }
        }
      }
    }
    W_Main::redirect(W_Structure::getLinkURL('_framework/resources/landing/index.php?action=not_found'));
  }

  //  ไม่ได้ใช้
  public static function redirectToPageInsidePermission($page_name, $page_name_sub, $c)
  {
    $is_dev = W_User::isDev();

    foreach (W_Permission::$functions_available as $function) {
      W_WLoves::setConfigMenuList($function['module']);
    }

    $menu_list   = W_Config::$menu_list;
    $permissions = W_Permission::$has_permissions;

    foreach ($menu_list as $menu) {
      echo $menu['page_name'] . '<br />';
      if ($menu['page_name'] == $page_name) {

        foreach ($menu['menu_items'] as $menu_items) {
          if ($menu_items['page_name'] == $page_name_sub) {
            // Redirect if no sub_menu
            if (!$menu_items['sub_menu']) {
              W_Main::redirect('module/' . $menu['page_name'] . '/' . $menu_items['url'] . '?c=' . $c);
            }

            foreach ($menu_items['sub_menu'] as $sub_menu) {
              if (isset($permissions[$_GET['c'] . '_' . $sub_menu['page_name']])) {
                W_Main::redirect('module/' . $menu['page_name'] . '/' . $sub_menu['url'] . '?c=' . $c);
              } else if ($is_dev) {
                W_Main::redirect('module/' . $menu['page_name'] . '/' . $sub_menu['url'] . '?c=' . $c);
              }
            }
          }
        }
      }
    }
    User::logout();
    Dev::logout();
    W_Main::notification('Permission Denied! [' . $page_name . '][' . $page_name_sub . '][' . $c . ']', 'error');
    W_Main::redirect(W_Structure::getLinkURL('_framework/resources/login/index.php?error=Permission Denied!'));
  }


  public static function templateConfigPermission($options = [])
  {
    $is_edit = (isset($options['is_edit']) && $options['is_edit']) ? true : false;
    $prefix = (isset($options['prefix'])) ? $options['prefix'] : '../../';
    $permission_array = static::randerPermissionList($options);
    include 'template_permission.php';
  }

  public static function templateConfigHidePermission($options = [])
  {
    $is_edit = (isset($options['is_edit']) && $options['is_edit']) ? true : false;
    $prefix = (isset($options['prefix'])) ? $options['prefix'] : '../../';
    $permission_array = static::randerHidePermissionList($options);
    include 'template_hide_permission.php';
  }


  public static function templateConfigAutoPermission($options = [])
  {
    $is_edit = (isset($options['is_edit']) && $options['is_edit']) ? true : false;
    $prefix = (isset($options['prefix'])) ? $options['prefix'] : '../../';
    $permission_array = static::randerAutoPermissionList($options);
    include 'template_auto_permission.php';
  }

  public static function templateConfigModulePermission($options = [])
  {
    $permission_array = static::randerModulePermissionList($options);
    return $permission_array;
  }
  public static function templateConfigUserPermission($options = [])
  {
    $permission_array = static::randerUserPermissionList($options);
    return $permission_array;
  }
  public static function updateUserPermission($user_id, $options = [])
  {
    $setting_permission = (isset($options['setting_permission'])) ? $options['setting_permission'] : '';
    $permission = (isset($options['permission'])) ? $options['permission'] : '';
    // check permission
    $is_update = false;
    $temp = [];


    foreach ($setting_permission as $name => $value) {
      if (isset($permission[$name])) {
        if ($setting_permission[$name]) {
          if ($permission[$name] == 0) {
            $temp[$name] = 0;
          } else {
            $temp[$name] = 1;
          }
        } else {
          $temp[$name] = 0;
        }
      } else {
        $temp[$name] = 0;
      }
    }

    foreach ($temp as $name => $value) {
      if (isset($permission[$name])) {
        if ($value != $permission[$name]) {
          $is_update = true;
        }
      } else {
        $is_update = true;
      }
    }


    if ($is_update) {
      $data = [
        'permission' => $temp
      ];
      $result = User::updateUser($user_id, $data);
      W_Main::redirect('');
    }
  }
  private static function randerCheckbox($options)
  {
    $name = (isset($options['name']) && $options['name']) ? $options['name'] : '';
    $is_edit = (isset($options['is_edit']) && $options['is_edit']) ? true : false;
    $checked = (isset($options['checked']) && $options['checked']) ? true : false;
    $type = (isset($options['type']) && $options['type']) ? $options['type'] : '';

    $green_class = ($type == 'function') ? 'green' : '';
    if ($is_edit) {
      $checkbox = TiwForm::normal('checkbox', 1, ['name' => 'checked[' . $name . ']', 'class' => $green_class . ' __check_list_permission_event', 'checked' => $checked], ['style' => '3', 'is_return' => true]);
    } else {
      if ($checked) {
        $color = ($type == 'function') ? 'text-success' : 'text-primary';
        $checkbox = '<span class="' . $color . ' font-14px __check_list_permission_event __is_checked">YES</span>';
      } else {
        $checkbox = '<span class="font-14px __check_list_permission_event text-danger">NO</span>';
      }
    }
    echo $checkbox;
  }

  private static function randerCheckboxHidePermission($options)
  {
    $name = (isset($options['name']) && $options['name']) ? $options['name'] : '';
    $is_edit = (isset($options['is_edit']) && $options['is_edit']) ? true : false;
    $checked = (isset($options['checked']) && $options['checked']) ? true : false;

    if ($is_edit) {
      $checkbox = TiwForm::normal('checkbox', 1, ['name' => 'checked[' . $name . ']', 'class' => 'red __check_list_permission_event', 'checked' => $checked], ['style' => '3', 'is_return' => true]);
    } else {
      if ($checked) {
        $checkbox = '<span class="text-danger font-14px __check_list_permission_event __is_checked">Hide</span>';
      } else {
        $checkbox = '<span class="font-14px __check_list_permission_event text-primary">No</span>';
      }
    }
    echo $checkbox;
  }

  private static function randerHidePermissionList($options = [])
  {
    $module_list     = W_Permission::$functions_available;
    $menu_list       = W_Config::$menu_list;
    $permission      = (isset($options['permission'])) ? $options['permission'] : [];
    $permission_array = [];
    foreach ($module_list as $module) {
      $menu = array_key_exists($module['module'], $menu_list) ? $menu_list[$module['module']] : '';
      if (!$menu) {
        continue;
      }
      $permission_array[$module['code']] = $module;
      $permission_array[$module['code']]['list'] = [];
      if (isset($menu_list[$module['module']]) && $menu_list[$module['module']]['menu_items']) {
        if ($module['module'] == 'User') {
          $name = $module['code'] . '_user';
          $checked = (isset($permission[$name]) && $permission[$name]) ? true : false;
          $permission_array[$module['code']]['list'] = [
            'key' => $name,
            'title' => $module['name'],
            'name' => $module['name'] . ' List',
            'description' => ($module['description'] ? $module['description'] : '-'),
            'checked' => $checked,
          ];
        } else {

          $temp = [];
          foreach ($menu_list as $module_name => $menu) {
            $menu_items = isset($menu['menu_items']) ? $menu['menu_items'] : [];
            foreach ($menu_items as $menu_items_key => $menu_item) {
              $temp[$module_name][$menu_items_key] = [
                'title' => $menu_item['title'],
                'list' => [],
              ];
              $sub_menu = isset($menu_item['sub_menu']) ? $menu_item['sub_menu'] : [];
              foreach ($sub_menu as $key => $sub) {
                $name = $module['code'] . '_' . $sub['page_name'];
                $checked = (isset($permission[$name]) && $permission[$name]) ? true : false;
                $sub['key'] = $name;
                $sub['checked'] = $checked;
                $temp[$module_name][$menu_items_key]['list'][] = $sub;
              }
            }
          }
          $permission_array[$module['code']]['list'] = $temp[$module['module']];
        }
      }
    }
    return $permission_array;
  }
  private static function randerPermissionList($options = [])
  {
    $module_list     = W_Permission::$functions_available;
    $menu_list       = W_Config::$menu_list;
    $permission      = (isset($options['permission'])) ? $options['permission'] : [];
    $hide_permission      = (isset($options['hide_permission'])) ? $options['hide_permission'] : [];
    $permission_array = [];
    foreach ($module_list as $module) {
      $menu = array_key_exists($module['module'], $menu_list) ? $menu_list[$module['module']] : '';
      if (!$menu) {
        continue;
      }
      $permission_array[$module['code']] = $module;
      $permission_array[$module['code']]['list'] = [];
      if (isset($menu_list[$module['module']]) && $menu_list[$module['module']]['menu_items']) {
        if ($module['module'] == 'User') {
          $name = $module['code'] . '_user';
          $is_permission = '';
          if (!isset($hide_permission[$name])) {
            $is_permission = true;
          } else if ($hide_permission[$name] == 0) {
            $is_permission = true;
          } else {
            $is_permission = false;
          }
          if ($is_permission) {
            $checked = (isset($permission[$name]) && $permission[$name]) ? true : false;
            $permission_array[$module['code']]['list'] = [
              'key' => $name,
              'title' => $module['name'],
              'name' => $module['name'] . ' List',
              'description' => ($module['description'] ? $module['description'] : '-'),
              'checked' => $checked,
            ];
          }
        } else {

          $temp = [];
          foreach ($menu_list as $module_name => $menu) {
            $menu_items = isset($menu['menu_items']) ? $menu['menu_items'] : [];
            foreach ($menu_items as $menu_items_key => $menu_item) {
              $temp[$module_name][$menu_items_key] = [
                'title' => ($menu_item['title']) ? $menu_item['title'] : $menu_item['page_name'],
                'list' => [],
              ];
              $sub_menu = isset($menu_item['sub_menu']) ? $menu_item['sub_menu'] : [];
              foreach ($sub_menu as $key => $sub) {
                $name = $module['code'] . '_' . $sub['page_name'];
                $is_permission = '';
                if (!isset($hide_permission[$name])) {
                  $is_permission = true;
                } else if ($hide_permission[$name] == 0) {
                  $is_permission = true;
                } else {
                  $is_permission = false;
                }
                if ($is_permission) {
                  $checked = (isset($permission[$name]) && $permission[$name]) ? true : false;
                  $sub['key'] = $name;
                  $sub['checked'] = $checked;
                  $temp[$module_name][$menu_items_key]['list'][] = $sub;
                }
              }
            }
          }
          $permission_array[$module['code']]['list'] = $temp[$module['module']];
        }
      }
    }

    return $permission_array;
  }
  private static function randerAutoPermissionList($options = [])
  {
    $module_list     = W_Permission::$functions_available;
    $menu_list       = W_Config::$menu_list;
    $permission      = (isset($options['permission'])) ? $options['permission'] : [];
    $hide_permission      = (isset($options['hide_permission'])) ? $options['hide_permission'] : [];
    $permission_array = [];
    foreach ($module_list as $module) {
      $menu = array_key_exists($module['module'], $menu_list) ? $menu_list[$module['module']] : '';
      if (!$menu) {
        continue;
      }
      $permission_array[$module['code']] = $module;
      $permission_array[$module['code']]['list'] = [];
      if (isset($menu_list[$module['module']]) && $menu_list[$module['module']]['menu_items']) {
        if ($module['module'] == 'User') {
          $name = $module['code'] . '_user';
          if (isset($hide_permission[$name])) {
            if ($hide_permission[$name] == 0) {
              $is_permission = true;
            } else {
              $is_permission = false;
            }
          } else {
            $is_permission = true;
          }
          if ($is_permission) {
            $checked = (isset($permission[$name]) && $permission[$name]) ? true : false;
            $permission_array[$module['code']]['list'] = [
              'key' => $name,
              'title' => $module['name'],
              'name' => $module['name'] . ' List',
              'description' => ($module['description'] ? $module['description'] : '-'),
              'checked' => $checked,
            ];
          }
        } else {

          $temp = [];
          foreach ($menu_list as $module_name => $menu) {
            $menu_items = isset($menu['menu_items']) ? $menu['menu_items'] : [];
            foreach ($menu_items as $menu_items_key => $menu_item) {
              $temp[$module_name][$menu_items_key] = [
                'title' => $menu_item['title'],
                'list' => [],
              ];
              $sub_menu = isset($menu_item['sub_menu']) ? $menu_item['sub_menu'] : [];
              foreach ($sub_menu as $key => $sub) {
                $name = $module['code'] . '_' . $sub['page_name'];
                if (isset($hide_permission[$name])) {
                  if ($hide_permission[$name] == 0) {
                    $is_permission = true;
                  } else {
                    $is_permission = false;
                  }
                } else {
                  $is_permission = true;
                }
                if ($is_permission) {
                  $checked = (isset($permission[$name]) && $permission[$name]) ? true : false;
                  $sub['key'] = $name;
                  $sub['checked'] = $checked;
                  $temp[$module_name][$menu_items_key]['list'][] = $sub;
                }
              }
            }
          }
          $permission_array[$module['code']]['list'] = $temp[$module['module']];
        }
      }
    }
    // W_Main::display($permission_array);
    // die;
    return $permission_array;
  }
  private static function randerModulePermissionList($options = [])

  {
    $module_list     = W_Permission::$functions_available;
    $menu_list       = W_Config::$menu_list;
    $permission      = (isset($options['permission'])) ? $options['permission'] : [];
    $auto_permission = (isset($options['auto_permission'])) ? $options['auto_permission'] : [];

    $permission_array = [];
    foreach ($module_list as $module) {
      $menu = array_key_exists($module['module'], $menu_list) ? $menu_list[$module['module']] : '';
      if (!$menu) {
        continue;
      }
      $permission_array[$module['code']] = $module;
      $permission_array[$module['code']]['count_page'] = 0;
      $permission_array[$module['code']]['count_have_page'] = 0;
      $permission_array[$module['code']]['count_function'] = 0;
      $permission_array[$module['code']]['count_have_function'] = 0;
      $permission_array[$module['code']]['list'] = [];
      if (isset($menu_list[$module['module']]) && $menu_list[$module['module']]['menu_items']) {
        if ($module['module'] == 'User') {
          $name = $module['code'] . '_user';
          $checked = (isset($permission[$name]) && $permission[$name]) ? true : false;
          $auto_checked = (isset($auto_permission[$name]) && $auto_permission[$name]) ? true : false;
          $permission_array[$module['code']]['count_page'] += 1;
          $permission_array[$module['code']]['count_have_page'] += $checked ? 1 : 0;
          $permission_array[$module['code']]['count_function'] += 1;
          $permission_array[$module['code']]['count_have_function'] += $auto_checked ? 1 : 0;
          $permission_array[$module['code']]['list'] = [
            'key' => $name,
            'title' => $module['name'],
            'name' => $module['name'] . ' List',
            'description' => ($module['description'] ? $module['description'] : '-'),
            'checked' => $checked,
            'auto_checked' => $auto_checked,
          ];
        } else {

          $temp = [];

          foreach ($menu_list as $module_name => $menu) {
            $permission_array[$module['code']]['icon'] = $menu['icon'];
            $menu_items = isset($menu['menu_items']) ? $menu['menu_items'] : [];
            foreach ($menu_items as $menu_items_key => $menu_item) {
              $temp[$module_name][$menu_items_key] = [
                'title' => $menu_item['title'],
                'list' => [],
              ];
              $sub_menu = isset($menu_item['sub_menu']) ? $menu_item['sub_menu'] : [];
              foreach ($sub_menu as $key => $sub) {
                $name = $module['code'] . '_' . $sub['page_name'];
                $checked = (isset($permission[$name]) && $permission[$name]) ? true : false;
                $auto_checked = (isset($auto_permission[$name]) && $auto_permission[$name]) ? true : false;
                $permission_array[$module['code']]['count_page'] += 1;
                $permission_array[$module['code']]['count_have_page'] += $checked ? 1 : 0;
                $permission_array[$module['code']]['count_function'] += 1;
                $permission_array[$module['code']]['count_have_function'] += $auto_checked ? 1 : 0;
                $sub['key'] = $name;
                $sub['checked'] = $checked;
                $sub['auto_checked'] = $auto_checked;
                $temp_function = [];
                if (isset($sub['function'])) {
                  foreach ($sub['function'] as $function_key => $function) {
                    $function_name = $name . '_' . $function['key'];
                    $function_checked = (isset($auto_permission[$function_name]) && $auto_permission[$function_name]) ? true : false;
                    $permission_array[$module['code']]['count_function'] += 1;
                    $permission_array[$module['code']]['count_have_function'] += $function_checked ? 1 : 0;
                    $temp_function[$function_key]['name'] = $function['name'];
                    $temp_function[$function_key]['key'] = $function_name;
                    $temp_function[$function_key]['checked'] = $function_checked;
                  }
                  $sub['function'] = $temp_function;
                }
                $temp[$module_name][$menu_items_key]['list'][] = $sub;
              }
            }
          }
          $permission_array[$module['code']]['list'] = $temp[$module['module']];
        }
      }
    }
    $permission_user = [];
    $permission_module = [];

    foreach ($permission_array as $key => $value) {
      if ($value['module'] == 'User') {
        $permission_user[] = $value;
      } else {
        $permission_module[] = $value;
      }
    }
    $permission_array = array_merge($permission_module, $permission_user);
    return $permission_array;
  }
  private static function randerUserPermissionList($options = [])
  {

    $module_list     = W_Permission::$functions_available;
    $menu_list       = W_Config::$menu_list;
    $permission      = (isset($options['permission'])) ? $options['permission'] : [];
    $hidden_permission      = (isset($options['hidden_permission'])) ? $options['hidden_permission'] : [];
    $permission_array = [];
    foreach ($module_list as $module) {
      $menu = array_key_exists($module['module'], $menu_list) ? $menu_list[$module['module']] : '';
      if (!$menu) {
        continue;
      }
      $permission_array[$module['code']] = $module;
      $permission_array[$module['code']]['count_function'] = 0;
      $permission_array[$module['code']]['count_have_function'] = 0;
      $permission_array[$module['code']]['list'] = [];
      if (isset($menu_list[$module['module']]) && $menu_list[$module['module']]['menu_items']) {
        if ($module['module'] == 'User') {
          $name = $module['code'] . '_user';
          $is_permission = (isset($hidden_permission[$name]) && $hidden_permission[$name]) ? true  : false;
          $checked = (isset($permission[$name]) && $permission[$name]) ? true : false;
          $permission_array[$module['code']]['count_function'] += $is_permission ? 1 : 0;
          $permission_array[$module['code']]['count_have_function'] += $checked && $is_permission ? 1 : 0;
          $permission_array[$module['code']]['list'] = [
            'key' => $name,
            'title' => $module['name'],
            'name' => $module['name'] . ' List',
            'description' => ($module['description'] ? $module['description'] : '-'),
            'checked' => $checked,
            'is_permission' => $is_permission,
          ];
        } else {

          $temp = [];
          foreach ($menu_list as $module_name => $menu) {
            $permission_array[$module['code']]['icon'] = $menu['icon'];
            $menu_items = isset($menu['menu_items']) ? $menu['menu_items'] : [];
            foreach ($menu_items as $menu_items_key => $menu_item) {
              $temp[$module_name][$menu_items_key] = [
                'title' => $menu_item['title'],
                'list' => [],
              ];
              $sub_menu = isset($menu_item['sub_menu']) ? $menu_item['sub_menu'] : [];
              foreach ($sub_menu as $key => $sub) {
                $name = $module['code'] . '_' . $sub['page_name'];
                $is_permission = (isset($hidden_permission[$name]) && $hidden_permission[$name]) ?  true : false;
                $checked = (isset($permission[$name]) && $permission[$name]) ? true : false;
                $permission_array[$module['code']]['count_function'] += $is_permission ? 1 : 0;
                $permission_array[$module['code']]['count_have_function'] += $checked && $is_permission ? 1 : 0;
                $sub['key'] = $name;
                $sub['checked'] = $checked;
                $sub['is_permission'] = $is_permission;
                $temp_function = [];
                if (isset($sub['function'])) {
                  foreach ($sub['function'] as $function_key => $function) {
                    $function_name = $name . '_' . $function['key'];
                    $is_function_permission = (isset($hidden_permission[$function_name]) && $hidden_permission[$function_name]) ?  true : false;
                    $function_checked = (isset($permission[$function_name]) && $permission[$function_name]) ? true : false;
                    $permission_array[$module['code']]['count_function'] += $is_function_permission ? 1 : 0;
                    $permission_array[$module['code']]['count_have_function'] += $function_checked && $is_function_permission ? 1 : 0;
                    $temp_function[$function_key]['name'] = $function['name'];
                    $temp_function[$function_key]['key'] = $function_name;
                    $temp_function[$function_key]['checked'] = $function_checked;
                    $temp_function[$function_key]['is_permission'] = $is_function_permission;
                  }
                  $sub['function'] = $temp_function;
                }
                $temp[$module_name][$menu_items_key]['list'][] = $sub;
              }
            }
          }
          $permission_array[$module['code']]['list'] = $temp[$module['module']];
        }
      }
    }
    $permission_user = [];
    $permission_module = [];

    foreach ($permission_array as $key => $value) {
      if ($value['module'] == 'User') {
        $permission_user[] = $value;
      } else {
        $permission_module[] = $value;
      }
    }

    $permission_array = array_merge($permission_module, $permission_user);
    return $permission_array;
  }
}
