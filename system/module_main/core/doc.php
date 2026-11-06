<?php
class Doc {
  private static $_module_tables = [];

  /**
  | Group | Module
  | Description | ดึง list module แบบกรุปไม่ซ้ำ มีการนับจำนวน
   */
  static function selectModule($where = [], $options = []) {
    $return = []; // LIST

    $module_list      = [];
    $selected_records = Func::select('*', $where, $options);
    foreach ($selected_records as $record) {
      if (($record['module'])) {
        // $record['manage_user_type'] = '';
        // $managed_user_type_id = Module::getCurrentSetting($record['code'], $record['module'], 'managed_user_type_id');
        // $managed_user_type_id = Module::getCurrentSetting($record['code'], $record['module'], 'managed_user_type_id');
        // if ($managed_user_type_id && is_numeric($managed_user_type_id)) {
        //   // $model = User_type::getUserTypeByID($managed_user_type_id);
        //   if (isset($model['id'])) {
        //     $record['manage_user_type'] = $model['name'];
        //   }
        // }
      }
      if (($record['module'] == 'User') || !isset(Info::$system_file[$record['module']])) {
        $module_list[$record['code']] = $record;
      }
    }

    // For It-Nav
    if (Util::getOptionsValue('it_nav_wolves', $options, false)) {
      foreach ($module_list as $key => $record) {
        $module_list[$key]['id'] = $record['code'];
      }
      return [
        'title' => '',
        'class' => '',
        'list'  => array_values($module_list)
      ];
    } else if (Util::getOptionsValue('it_nav_milk', $options, false)) {
      $nav_list = [];
      foreach ($module_list as $record) {
        // GROUPED by module
        $nav_list[$record['module']] = [
          'id'            => $record['module'],
          'name'          => $record['module'], // $record['name'],
          'icon' => '',
          'category_name' => 'category_name7',
          'count'         => '',
          'status'        => '',
          'color_status'  => '',
          'html'          => '',
          'send_param'    => '&type=module'
        ];
      }
      return [
        'name'          => 'Installed Module',
        'icon'          => '',
        'category_name' => 'category_name8',
        'count'         => count($nav_list),
        'status'        => '',
        'color_status'  => '',
        'html'          => '',
        'list'          => [[
          'title' => '',
          'list'  => array_values($nav_list)
        ]]
      ];
    }

    return array_values($module_list);
  }

  /**
  | Group | Module
  | Description | 1.ดึง list module ให้ it Nav *มันมี 2 ประเภทใน Nav เดียว ไม่รู้ทำไม
  <br> [20211124] remove User Module from GENERAL_SPECIFIC
   */
  public static function selectAllModule($nav_type = 'it_nav_milk') {
    $return = [
      'class' => '',
      'list'  => []
    ];

    // For it_nav_milk แบบ สายย่อ แยกพับทีละอัน
    if (true) {
      // GENERAL_SPECIFIC
      $where = [
        'module[!]' => 'User' // [20211124] remove User Module
      ];
      $return['list'][] = static::selectModule($where, ['it_nav_milk' => true]);
      // MAIN_CORE
      $main_list = static::selectModuleMain([], ['it_nav_milk' => true]);
      foreach ($main_list['list'] as $group) {
        $group_list = [
          'title' => '',
          'list'  => []
        ];
        foreach ($group['list'] as $class) {
          $group_list['title']  = ''; // ไอตรงที่วง ขอให้ไม่โชว์ได้มั้ยพี่ $class['name'];
          $group_list['list'][] = $class;
        }
        $return['list'][] = [
          'name'          => "Main ".ucfirst($class['category_name']),
          'icon'          => '',
          'category_name' => 'category_name10',
          'count'         => count($group_list['list']),
          'status'        => '',
          'color_status'  => '',
          'html'          => '',
          'list'          => [
            $group_list
          ]
        ];
      }
    }

    return $return;
  }

  /**
  | Group | Module
  | Description | ดึง list class ใน module
   */
  static function selectModuleClass($module, $options = []) {
    if (!$module) {
      return false;
    }

    $wrap = [];

    if (file_exists(ROOT_PHP.'/'.Config_Module::$module_file[$module]."/class")) {
      $classes = scandir(ROOT_PHP.'/'.Config_Module::$module_file[$module]."/class");

      $index_class = 0;
      foreach ($classes as $class) {
        if ('.' == $class || '..' == $class) {
          continue;
        }

        $wrap[$index_class] = [
          'id'   => '',
          'name' => '',
          'list' => []
        ];

        $class = str_replace('.php', '', $class);

        // $reflects        = new ReflectionClass($class);
        // $class_functions = [];
        // foreach ($reflects->getMethods() as $reflect) {
        //   if (strtolower($reflect->class) == strtolower($class)) {
        //     $class_functions[] = $reflect->name;
        //   }
        // }
        $class_functions = get_class_methods($class);

        $wrap[$index_class]['name'] = $class;
        $wrap[$index_class]['id']   = $class;

        if ($class_functions) {
          $list_info_group = [];

          foreach ($class_functions as $class_function) {
            $extract_data = self::extractDoc($class, $class_function);

            if ($extract_data) {
              if (!isset($list_info_group[$extract_data['group']])) {
                $list_info_group[$extract_data['group']] = [];
              }

              array_push($list_info_group[$extract_data['group']], [
                'id'   => $extract_data['function'],
                'name' => $extract_data['function']
              ]);
            }
          }

          foreach ($list_info_group as $index_group => $list_func) {
            $group_name                              = $index_group;
            $wrap[$index_class]['list'][$group_name] = [
              'id'   => $index_group,
              'name' => $index_group,
              'list' => []
            ];
            foreach ($list_func as $index_function => $group) {
              $wrap[$index_class]['list'][$group_name]['list'][] = $group;
            }
          }
        }

        $index_class++;
      }
    }

    // For It-Nav
    if (Util::getOptionsValue('it_nav_wolves', $options, false)) {
      // UNUSED
    } else if (Util::getOptionsValue('it_nav_milk', $options, false)) {
      $module_list = [];
      foreach ($wrap as $group) {
        $group_name = $group['id'];
        $i_module   = [
          'name'          => $group['name'],
          'icon'          => '',
          'category_name' => 'category_name11',
          'count'         => 0,
          'status'        => '',
          'color_status'  => '',
          'html'          => '',
          'list'          => []
        ];
        $class_list = [];
        foreach ($group['list'] as $api1) {
          $api_list = [];
          foreach ($api1['list'] as $api) {
            $api_list[] = [
              'id'            => $api['id'],
              'name'          => $api['name'],
              'icon'          => '',
              'category_name' => 'category_name12',
              'count'         => '',
              'status'        => '',
              'color_status'  => '',
              'html'          => '',
              'send_param'    => '&class='.$group_name
            ];
          }
          $class_list[] = [
            'title' => $api1['name'],
            'list'  => array_values($api_list)
          ];
        }
        $i_module['count'] = count($class_list);
        $i_module['list']  = array_values($class_list);

        $module_list[] = $i_module;
      }
      return [
        "class" => "",
        "list"  => array_values($module_list)
      ];
    }

    return $wrap;
  }

  /**
  | Group | Module
  | Description | ดึง code ฟังชันใน module สำหรับ DOC ตัว Reflection
   */
  static function extractDoc($class, $function) {
    $reflection = new ReflectionMethod($class, $function);
    $comments   = $reflection->getDocComment();

    // no comment no show
    if (!$comments) {
      return null;
    }

    // operate
    $comments = str_replace('/**', '', $comments);
    $comments = str_replace('*/', '', $comments);
    $comments = trim($comments);
    $comments = explode('| ', $comments); // so you can use | in desp NOW.

    // ต้องมีคอมเม้นอย่างน้อย 2 ชุด
    if (isset($comments[2])) {
      $result = [
        'group'                => trim($comments[2]),
        'class'                => $class,
        'function'             => $function,
        'parameter'            => $reflection->getParameters(),
        'description'          => isset($comments[4]) ? trim($comments[4]) : '',
        'parameter_detail'     => [],
        'parameter_list'       => [],
        'parameter_input_list' => [],
        'other_detail'         => [],
        'start_line'           => $reflection->getStartLine(),
        'end_line'             => $reflection->getEndLine()
      ];
    } else {
      return [];
    }

    // [DOC 2.0]
    $parameter_list = [];
    foreach ($result['parameter'] as $i_param) {
      $parameter_list[$i_param->name] = [
        'name'        => $i_param->name,
        'is_required' => ($i_param->isOptional()) ? 0 : 1
      ];
    }
    $result['parameter_list'] = $parameter_list;

    foreach ($comments as $seq => $block) {
      $i_block_name = (trim($block));
      $i_param_name = (substr($i_block_name, 1));
      if (Util::startsWith($i_block_name, '$') && isset($parameter_list[$i_param_name]) && isset($comments[$seq + 1])) {
        $result['parameter_detail'][$i_block_name] = (trim($comments[$seq + 1]));
      }
      if ((strlen($i_block_name) > 2) && Util::startsWith($i_block_name, '"') && !isset($result['parameter_detail'][$i_block_name]) && isset($comments[$seq + 1])) {
        $i_name                          = (substr($i_block_name, 1, strlen($i_block_name) - 2));
        $result['other_detail'][$i_name] = (trim($comments[$seq + 1]));
      }
    }

    // PARAM
    if ($result['parameter_detail']) {
      $result['description'] .= "<hr><h5>INPUT</h5>";
      $html_params = [];
      foreach ($result['parameter_detail'] as $i_block_name => $params) {
        if (Util::startsWith($params, '[') && Util::endsWith($params, ']')) {
          $html = ("{$i_block_name} = [</br>");
          foreach (explode(',', substr($params, 1, strlen($params) - 2)) as $i_param) {
            // Naming
            $i_name = Util::trimRuleName(trim($i_param));
            if (Util::endsWith($i_name, '*')) {
              $i_name = substr($i_name, 0, -1);
              // $is_required = 1;
            }
            preg_match_all("/\((.+?)\)/", $i_param, $matches);
            if (isset($matches[1][0])) {
              $i_comment = $matches[1][0];
            } else {
              $i_comment = "'{$i_name}'";
            }
            $html .= "<span style=\"margin-left: 10px;\">'{$i_name}' => {$i_comment},</span></br>";
          }
          $html_params[] = "{$html}];";
        } else {
          $html_params[] = "{$i_block_name} = {$params};";
        }
      }
      $result['description'] .= implode('</br></br>', $html_params);
      $result['description'] .= '<hr>';
    }

    // [DOC 3.0]
    if ($result['parameter_detail']) {
      $input_list = [];
      foreach ($result['parameter_detail'] as $i_block_name => $params) {
        $i_input_name = $i_block_name;
        if (Util::startsWith($i_input_name, '$')) {
          $i_input_name = substr($i_input_name, 1);
        }
        if (Util::startsWith($params, '[') && Util::endsWith($params, ']')) {
          // Array
          foreach (explode(',', substr($params, 1, strlen($params) - 2)) as $i_param) {
            // Naming
            $i_name      = Util::trimRuleName(trim($i_param));
            $is_required = 0;
            if (Util::endsWith($i_name, '*')) {
              $i_name      = substr($i_name, 0, -1);
              $is_required = 1;
            }
            // Attr
            preg_match_all("/\((.+?)\)/", $i_param, $matches);
            if (isset($matches[1][0])) {
              $i_comment = $matches[1][0];
            } else {
              $i_comment = "'{$i_name}'";
            }
            $input_list[] = [
              'name'        => "{$i_input_name}[{$i_name}]",
              'label'       => "{$i_input_name}[{$i_name}]",
              'type'        => 'text',
              'is_required' => $is_required,
              'default'     => '',
              'placeholder' => $i_comment,
              'value'       => ''
            ];
          }
        } else {
          // String
          $input_list[] = [
            'name'        => $i_input_name,
            'placeholder' => $params,
            'type'        => 'text',
            'is_required' => isset($parameter_list[$i_input_name]['is_required']) ? $parameter_list[$i_input_name]['is_required'] : 0,
            'default'     => '',
            'label'       => $i_input_name,
            'value'       => ''
          ];
        }
      }
      $result['parameter_input_list'] = $input_list;
    }

    // OTHER DETAIL
    if ($result['other_detail']) {
      foreach ($result['other_detail'] as $i_block_name => $params) {
        $result['description'] .= "<hr><h5>{$i_block_name}</h5>";
        $html_params = [];
        if (Util::startsWith($params, '[') && Util::endsWith($params, ']')) {
          $i_html = ("<ul>");
          foreach (explode(',', substr($params, 1, strlen($params) - 2)) as $i_param) {
            $i_name = Util::trimRuleName(trim($i_param));
            preg_match_all("/\((.+?)\)/", $i_param, $matches);
            if (isset($matches[1][0])) {
              $i_comment = $matches[1][0];
              $i_html .= "<li>{$i_name} => {$i_comment}</li>";
            } else {
              $i_html = "<li>{$i_name}</li>";
            }
          }
          $html_params[] = "{$i_html}</ul>";
        } else {
          $html_params[] = "{$params}";
        }
        $result['description'] .= implode('</br>', $html_params);
      }
      $result['description'] .= '<hr>';
    }

    // Try Unused
    // if (isset($result['parameter'])) {
    //   unset($result['parameter']);
    // }
    if (isset($result['parameter_detail'])) {
      unset($result['parameter_detail']);
    }

    return $result;
  }

  /**
  | Group | Module
  | Description | ดึง code ฟังชันใน module สำหรับ DOC
   */
  static function getExtractDoc($module, $class, $function) {
    if (!$class || !$function) {
      return false;
    }

    $where = [
      'AND' => [
        'module' => $module
      ]
    ];
    $module_code      = Amst::select(Info::$system_table['function'], ['code', 'id'], $where);
    $module_code_list = [];
    foreach ($module_code as $key => $value) {
      array_push($module_code_list, $value['code']);
    }

    $wrap = [
      'see_code'         => '',
      'code'             => '',
      'module_code_list' => $module_code_list,
      'file_name'        => '',
      'extract_data'     => [],
      'call_api'         => ROOT_URL.'/required/api/main/call.php'
    ];

    $file_name = Config_Module::$module_file[$module]."/class/".$class.".php";

    $wrap['file_name'] = $file_name;

    $file_datas = file_get_contents(ROOT_PHP.'/'.$file_name, true);

    $wrap['extract_data'] = self::extractDoc($class, $function);

    $wrap['see_code'] .= '<code data-language="php">';
    $file_datas = explode("\n", $file_datas);

    for ($i = $wrap['extract_data']['start_line'] - 1; $i < $wrap['extract_data']['end_line']; $i++) {
      if (isset($file_datas[$i])) {
        $wrap['see_code'] .= str_replace("\t", "  ", $file_datas[$i])."\n";
        $wrap['code'] .= str_replace("\t", "  ", $file_datas[$i])."\n";
      }
    }
    $wrap['see_code'] .= '</code>';

    // AUTOLOAD
    if (isset($wrap['extract_data'])) {
      $autoload_comment = '<h5>CODE</h5>';
      // [TODO] $autoload_comment = json_encode(static::_loadValidateTable($module));
      $wrap['extract_data']['description'] .= '<hr>'.$autoload_comment;
    }

    return $wrap;
  }

  /**
  | Group | Class in module_main
  | Description | ดึง list class ใน module_main
   */
  static function selectModuleMain($where = [], $options = []) {
    $wrap = [];

    $system_files = [];
    foreach (Info::$system_file as $key => $value) {
      if ($value['show_in_document']) {
        if (!isset($wrap[$value['category']])) {
          $wrap[$value['category']] = [];
        }

        $value['id']   = $key;
        $value['name'] = $key;
        unset($value['path']);
        unset($value['show_in_document']);
        unset($value['icon']);

        $wrap[$value['category']][] = $value;
      }
    }

    // For It-Nav
    if (Util::getOptionsValue('it_nav_wolves', $options, false)) {
      // UNUSED
    } else if (Util::getOptionsValue('it_nav_milk', $options, false)) {
      $module_list = [];
      foreach ($wrap as $group_name => $group) {
        $api_list = [];
        foreach ($group as $api) {
          $api_list[] = [
            'id'            => $api['id'],
            'name'          => $api['name'],
            'icon'          => '',
            'category_name' => $group_name,
            'count'         => '',
            'status'        => '',
            'color_status'  => '',
            'html'          => '',
            'send_param'    => '&type=module_main'
          ];
        }
        $module_list[] = [
          'title' => $group_name,
          'list'  => $api_list
        ];
      }
      return [
        'name'          => 'Main Module'.time(),
        'category_name' => 'category_name14',
        'icon'          => '',
        'count'         => count($module_list),
        'status'        => '',
        'color_status'  => '',
        'html'          => '',
        'list'          => array_values($module_list)
      ];
    }

    return $wrap;
  }

  /**
  | Group | Class in module_main
  | Description | ดึง list function ใน class module_main
   */
  static function selectModuleMainClass($class, $options = []) {
    if (!$class) {
      return false;
    }
    if (!isset(Info::$system_file[$class])) {
      return false;
    }

    $wrap = [];

    // $reflects        = new ReflectionClass($class);
    // $class_functions = [];
    // foreach ($reflects->getMethods() as $reflect) {
    //   if ($reflect->class == $class) {
    //     $class_functions[] = $reflect->name;
    //   }
    // }
    $class_functions = get_class_methods($class);

    $function_to_show = [];

    if ($class_functions) {
      foreach ($class_functions as $class_function) {
        $extract_data = self::extractDoc($class, $class_function);

        if ($extract_data) {
          if (!isset($function_to_show[$extract_data['group']])) {
            $function_to_show[$extract_data['group']] = [];
          }

          array_push($function_to_show[$extract_data['group']], [
            'function'    => $extract_data['function'],
            'description' => $extract_data['description']
          ]);

          if (!isset($wrap[$extract_data['group']])) {
            $wrap[$extract_data['group']] = [
              'id'   => $extract_data['group'],
              'name' => $extract_data['group'],
              'list' => [
                0 => [
                  'id'   => 'function',
                  'name' => 'function',
                  'list' => []
                ]
              ]
            ];
          }

          $data_return = [
            'id'          => $extract_data['function'],
            'name'        => $extract_data['function'],
            'description' => $extract_data['description']
          ];
          array_push($wrap[$extract_data['group']]['list'][0]['list'], $data_return);
        }
      }
    }

    // For It-Nav
    if (Util::getOptionsValue('it_nav_wolves', $options, false)) {
      // UNUSED
    } else if (Util::getOptionsValue('it_nav_milk', $options, false)) {
      $module_list = [];
      foreach ($wrap as $group_name => $group) {
        $api_count = 0; // [NO_TITLE]
        $i_module  = [
          'name'          => $group_name,
          'category_name' => 'category_name15',
          'icon'          => '',
          'count'         => 0,
          'status'        => '',
          'color_status'  => '',
          'html'          => '',
          'list'          => []
        ];
        $class_list = [];
        foreach ($group['list'] as $api1) {
          $api_list = [];
          foreach ($api1['list'] as $api) {
            $api_list[] = [
              'id'            => $api['id'],
              'name'          => $api['name'],
              'category_name' => 'category_name16',
              'icon'          => '',
              'count'         => '',
              'status'        => '',
              'color_status'  => '',
              'html'          => '',
              'send_param'    => '&class='.$group_name
            ];
            $api_count++;
          }
          $class_list[] = [
            'title' => '', // [NO_TITLE]
            'list' => array_values($api_list)
          ];
        }
        $i_module['count'] = $api_count; // [NO_TITLE]
        $i_module['list']  = array_values($class_list);

        $module_list[] = $i_module;
      }
      return [
        "class" => "",
        "list"  => array_values($module_list)
      ];
    }

    return $wrap;
  }

  /**
  | Group | Class in module_main
  | Description | ดึง code ฟังชันใน module_main
   */
  static function getExtractDocMain($class, $function) {
    if (!$class || !$function) {
      return false;
    }

    if (!isset(Info::$system_file[$class])) {
      return false;
    }

    $wrap = [
      'see_code'     => '',
      'code'         => '',
      'file_name'    => '',
      'extract_data' => [],
      'call_api'     => ROOT_URL.'/required/api/main/call.php'
      // เอาออกไปก่อนไม่น่าได้ใช้ 'conf'         => WLoves::getLinkInfo()
    ];

    $file_name  = Info::$system_file[$class]['path'].'.php';
    $file_datas = file_get_contents(ROOT_PHP.'/'.$file_name, true);

    $wrap['file_name']    = $file_name;
    $wrap['extract_data'] = self::extractDoc($class, $function);

    $wrap['see_code'] .= '<code data-language="php">';
    $file_datas = explode("\n", $file_datas);

    for ($i = $wrap['extract_data']['start_line'] - 1; $i < $wrap['extract_data']['end_line']; $i++) {
      if (isset($file_datas[$i])) {
        $wrap['see_code'] .= str_replace("\t", "  ", $file_datas[$i])."\n";
        $wrap['code'] .= str_replace("\t", "  ", $file_datas[$i])."\n";
      }
    }
    $wrap['see_code'] .= '</code>';

    return $wrap;
  }

  private static function _loadValidateTable($module_name) {
    if (!isset(static::$_module_tables[$module_name])) {
      if (isset(Config_Module::$module_file[$module_name]) && file_exists(ROOT_PHP.'/'.Config_Module::$module_file[$module_name].'/database/table/')) {
        // Load ทั้ง Folder
        $files = scandir(ROOT_PHP.'/'.Config_Module::$module_file[$module_name].'/database/table/');
        foreach ($files as $file) {
          $module_tables = null;
          $type          = pathinfo($file, PATHINFO_EXTENSION);
          if (('.' != $file) && ('..' != $file) && ($type == 'php')) {
            include ROOT_PHP.'/'.Config_Module::$module_file[$module_name].'/database/table/'.$file;
            if (isset($module_tables)) {
              foreach ($module_tables as $i_table_name => $i_table_data) {
                static::$_module_tables[$module_name][$i_table_name] = Amst::getTableValidateData('insert', $i_table_data);
              }
            }
          }
        }
      }
    }

    return isset(static::$_module_tables[$module_name]) ? static::$_module_tables[$module_name] : [];
  }
}
