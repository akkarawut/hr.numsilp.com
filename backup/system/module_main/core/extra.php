<?php

class Extra {

  public static function setExtraColumnsListDetail($return, $options = []) {
    // [ExtractList]
    $selected_records = isset($return['list']) ? $return['list'] : $return;

    foreach ($selected_records as $key => $record) {
      $selected_records[$key]['json_data'] = json_decode($record['json_data'], true);
    }

    // [Repack]
    if (isset($return['list'])) {
      $return['list'] = $selected_records;
    } else {
      $return = $selected_records;
    }
    return $return;
  }

  /**
  | Group | EXTRA COLUMNS
  | Description | 1.ดึงข้อมูลลิส 'EXTRA COLUMNS'
  | $code | code module
  | $where | [code, table_name]
  | $options | [not_view(true|เอาแค่ข้อมูลหลักไป edit*เร็ว), selected_fields(String หรือ Array ของคอลัมภ์ที่ต้องการ), total_count(true|พร้อมจำนวนข้อมูลทั้งหมด ตามการกรองไม่แบ่งหน้า), page_no(1|เลขหน้า 0 คือส่งทั้งหมด), page_size(10|จำนวนข้อมูลต่อหน้า), sort_col(เรียงตามชื่อคอลัมภ์นี้), sort_by('ASC'|'DESC')]
   */
  public static function selectExtraColumns($code = [], $where = [], $options = []) {
    $return    = []; // list
    $sql_where = []; // list

    if (isset($where['code']) && $where['code']) {
      $sql_where['AND']['code'] = $where['code'];
    }

    if (isset($where['table_name']) && $where['table_name']) {
      $sql_where['AND']['table_name'] = $where['table_name'];
    }
    if (isset($where['name']) && $where['name']) {
      $sql_where['AND']['name'] = $where['name'];
    }

    if (isset($where['type']) && $where['type']) {
      $sql_where['AND']['type'] = $where['type'];
    }
    if (isset($where['category']) && $where['category']) {
      $sql_where['AND']['category[~]'] = $where['category'];
    }
    if (isset($where['db_column_name']) && $where['db_column_name']) {
      $sql_where['AND']['db_column_name[~]'] = $where['db_column_name'];
    }

    $selected_fields = Amst::getSelectedFields($options);
    $return          = Amst::selectNoKey('sys_extra_column', $selected_fields, $sql_where, $options);
    return static::setExtraColumnsListDetail($return, $options);
  }

  public static function setExtraColumnsDetail($record, $options = []) {
    if (!isset($record['id'])) {
      return $record;
    }

    $record['json_data'] = json_decode($record['json_data'], true);

    return $record;
  }

  /**
  | Group | EXTRA COLUMNS
  | Description | 2.ดึงข้อมูล 'EXTRA COLUMNS' ด้วยไอดี
  | $id | ไอดีรายการ
  | $options | [not_view(true|เอาแค่ข้อมูลหลักไป edit*เร็ว), selected_fields(String หรือ Array ของคอลัมภ์ที่ต้องการ)]
   */
  public static function getExtraColumnsByID($id, $options = []) {
    $return = null;

    $selected_fields = Amst::getSelectedFields($options, ['id', 'system_delete']);
    $model           = Amst::getByID('sys_extra_column', $selected_fields, $id, $options);
    if (isset($model['id'])) {
      $return = static::setExtraColumnsDetail($model, $options);
    }
    return $return;
  }

  /**
  | Group | EXTRA COLUMNS
  | Description | 3.เพิ่ม 'EXTRA COLUMNS'
  | $data | [code, table_name, name, category, type]
   */
  public static function addNewExtraColumns($data = []) {
    $rep = [
      'insert_id' => false
    ];

    $code       = ($data['code']) ? trim($data['code']) : 'System';
    $table_name = ($data['table_name']) ? trim($data['table_name']) : '';
    $name       = ($data['name']) ? trim($data['name']) : '';
    $category   = ($data['category']) ? trim($data['category']) : '';
    $type       = ($data['type']) ? trim($data['type']) : '';
    if (!$name || !$category || !$type) {
      return Amst::repError('E0000', 'Missing Data');
    }

    $alter_table      = '';
    $check_table_name = 'func_'.$code.$table_name;
    if (strpos($table_name, 'sys_') !== false) {
      $check_table_name = $table_name;
      $alter_table      = $table_name;
    } else {
      $alter_table = 'func_'.$code.$table_name;
    }
    $table_exists = DB::selectTableStructure($check_table_name);

    if (!isset($table_exists[$check_table_name])) {
      return Amst::repError('E0000', 'Table not found');
    }

    $validate_name = static::validateChar($name);
    if (!$validate_name) {
      return Amst::repError('E0000', 'Invalid name (a-z A-Z 0-9 _ space)');
    }

    $columns_exists = [];
    $table          = $table_exists[$check_table_name];
    foreach ($table as $column) {
      $columns_exists[] = $column['name'];
    }

    $db_column_name = strtolower(str_replace(' ', '_', $name));
    if (in_array($db_column_name, $columns_exists)) {
      return Amst::repError('E0000', 'Column name already exist');
    }

    $where = [
      'AND' => [
        'code'           => $code,
        'table_name'     => $table_name,
        'db_column_name' => $db_column_name,
        'system_delete'  => 0
      ]
    ];
    $check = Amst::get('sys_extra_column', '*', $where);
    if (isset($check['id'])) {
      return Amst::repError('E0000', 'Column name already exist');
    }
    $data['type']           = ucfirst($type);
    $data['db_column_name'] = $db_column_name;
    if (isset($data['json_data'])) {
      $data['json_data'] = json_encode($data['json_data']);
    }
    $api = Amst::insert('sys_extra_column', $data);
    if (isset($api['response_status']) && ($api['response_status'] == true)) {
      $insert_id = $api['response_data']['insert_id'];

      if ($type == 'Decimal') {
        $digit   = ($data['digit']) ? $data['digit'] : 14;
        $decimal = ($data['decimal']) ? $data['decimal'] : 2;
        $type .= "($digit,$decimal)";
      }

      //alter
      $alter_data = [
        'force_alter' => true,
        'alter_data'  => [
          $alter_table => [
            $db_column_name => strtoupper($type)
          ]
        ]
      ];
      $options = [
        'no_log'             => true,
        'error_column_exist' => true
      ];
      $alter = DB_Init::alterColumns($alter_data, [], $options);
      if (!isset($alter['response_status']) || !$alter['response_status']) {
        Amst::deleteByID('sys_extra_column', $insert_id);

        return $alter;
      }
    }
    return $api;
  }

  public static function validateChar($name) {
    $chars = 'abcdefghijklmnopqrstuvwxyz1234567890_';
    $name  = strtolower($name);
    $name  = str_split($name);
    foreach ($name as $key => $char) {
      if (strpos($chars, $char) === false) {
        return false;
      }
    }
    return true;
  }

  /**
  | Group | EXTRA COLUMNS
  | Description | 4.อัพเดท 'EXTRA COLUMNS'
  | $id | ไอดีรายการ
  | $data | [-]
   */
  public static function updateExtraColumns($id, $data = []) {
    $rep = [
      'update_result' => false
    ];

    $model = Amst::getByID('sys_extra_column', ['id'], $id);
    if (!isset($model['id'])) {
      return Amst::repError('E0005', 'tablesys_extra_column');
    }

    if (isset($data['json_data'])) {
      $data['json_data'] = json_encode($data['json_data']);
    }
    $api = Amst::updateByID('sys_extra_column', $data, $id);
    if (isset($api['response_status']) && ($api['response_status'] == true)) {
    }
    return $api;
  }

  /**
  | Group | EXTRA COLUMNS
  | Description | 5.ลบ 'EXTRA COLUMNS'
  | $id | ไอดีรายการ
   */
  public static function deleteExtraColumns($id) {
    $rep = [
      'delete_result' => false
    ];

    $record = Amst::getByID('sys_extra_column', '*', $id);
    if (isset($record['id'])) {
      $rep['delete_result'] = Amst::deleteByID('sys_extra_column', $id);

      $alter_table = $record['table_name'];
      if (strpos($record['table_name'], 'sys_') === false) {
        $alter_table = 'func_'.$record['code'].$record['table_name'];
      }

      $sql = "ALTER TABLE `".$alter_table."` DROP `".$record['db_column_name']."`";
      Amst::query($sql);

      return Amst::repSuccess($rep);
    }
    return Amst::repError('E0000', 'Delete ExtraColumns Fail');
  }

  /**
  | Group | EXTRA COLUMNS
  | Description | 'EXTRA COLUMNS'
  | $table_names | table_names
   */
  public static function handleTableColumns($code = [], $table_names = []) {
    $table_names = ['sys_user', 'lfqzf_order'];
    $table_names = (!is_array($table_names)) ? [$table_names] : $table_names;

    foreach ($table_names as $table_name) {
      $table_data = DB::selectTableStructure($table_name);
      if (!isset($table_data[$table_name])) {
        continue;
      }
      $table_data = $table_data[$table_name];
      $where      = [
        'AND' => [
          'table_name'    => $table_name,
          'system_delete' => 0
        ]
      ];
      $columns_extist = Amst::select('sys_extra_column', 'db_column_name', $where);
      foreach ($table_data as $data) {
        $name = $data['name'];

        if (in_array($name, $columns_extist)) {
          continue;
        }

        // 'code'           => 'VARCHAR',
        // 'table_name'     => 'VARCHAR',
        // 'name'           => 'VARCHAR',
        // 'category'       => 'VARCHAR',
        // 'type'           => 'VARCHAR',
        // 'db_column_name' => 'VARCHAR',
        // 'digit'          => 'INT',
        // 'decimal'        => 'INT',
        // 'remark'         => 'VARCHAR',
        // 'system_column'  => 'TINYINT',
        // 'json_data'      => 'LONGTEXT'

        $i_data = [
          'code'       => ($code) ? $code : 'ALL',
          'table_name' => $full_table_name
        ];
      }
    }
  }

  public static function setExtraTemplateListDetail($return, $options = []) {
    // [ExtractList]
    $selected_records = isset($return['list']) ? $return['list'] : $return;

    foreach ($selected_records as $key => $record) {
    }

    // [Repack]
    if (isset($return['list'])) {
      $return['list'] = $selected_records;
    } else {
      $return = $selected_records;
    }
    return $return;
  }

  /**
  | Group | EXTRA COLUMNS
  | Description |  'EXTRA COLUMNS'
  | $record_id | record_id
  | $update_data | update_data
   */
  public static function updateDataExtraColumns($record_id, $update_data = []) {
    //eg หน้าบ้านจะส่งมาแบบนี้
    // $data = [
    //   'code'       => 'xxsaw', || System for sys_...
    //   'table_name' => '_customer',
    //   'data'       => [
    //     'fullnamez' => 'name',
    //     'lastnamez' => 'surname'
    //   ]
    // ];
    $code       = $update_data['code'];
    $table_name = $update_data['table_name'];
    $u_data     = $update_data['data'];
    $u_table    = $code.$table_name;
    if (strpos($table_name, 'sys_') !== false) {
      $u_table = $table_name;
    }
    return Amst::updateByID($u_table, $u_data, $record_id);
  }

  /**
  | Group | EXTRA TEMPLATE
  | Description | 1.ดึงข้อมูลลิส 'EXTRA TEMPLATE'
  | $where | [-]
  | $options | [not_view(true|เอาแค่ข้อมูลหลักไป edit*เร็ว), selected_fields(String หรือ Array ของคอลัมภ์ที่ต้องการ), total_count(true|พร้อมจำนวนข้อมูลทั้งหมด ตามการกรองไม่แบ่งหน้า), page_no(1|เลขหน้า 0 คือส่งทั้งหมด), page_size(10|จำนวนข้อมูลต่อหน้า), sort_col(เรียงตามชื่อคอลัมภ์นี้), sort_by('ASC'|'DESC')]
   */
  public static function selectExtraTemplate($where = [], $options = []) {
    $return    = []; // list
    $sql_where = []; // list

    if (isset($where['code']) && $where['code']) {
      $sql_where['AND']['code'] = $where['code'];
    }

    $selected_fields = Amst::getSelectedFields($options);
    $return          = Amst::selectNoKey('sys_extra_template', $selected_fields, $sql_where, $options);
    return static::setExtraTemplateListDetail($return, $options);
  }

  public static function setExtraTemplateDetail($record, $options = []) {
    if (!isset($record['id'])) {
      return $record;
    }

    $record['meta_data'] = json_decode($record['meta_data'], true);

    return $record;
  }

  /**
  | Group | EXTRA TEMPLATE
  | Description | 2.ดึงข้อมูล 'EXTRA TEMPLATE' ด้วยไอดี
  | $id | ไอดีรายการ
  | $options | [not_view(true|เอาแค่ข้อมูลหลักไป edit*เร็ว), selected_fields(String หรือ Array ของคอลัมภ์ที่ต้องการ)]
   */
  public static function getExtraTemplateByID($id, $options = []) {
    $return = null;

    $selected_fields = Amst::getSelectedFields($options, ['id', 'system_delete']);
    $model           = Amst::getByID('sys_extra_template', $selected_fields, $id, $options);
    if (isset($model['id'])) {
      $return = static::setExtraTemplateDetail($model, $options);
    }
    return $return;
  }

  /**
  | Group | EXTRA TEMPLATE
  | Description | 3.เพิ่ม 'EXTRA TEMPLATE'
  | $code | โค้ดโมดูล
  | $data | [-]
   */
  public static function addNewExtraTemplate($code, $data = []) {
    $rep = [
      'insert_id' => false
    ];

    $api = Amst::insert($code.'sys_extra_template', $data);
    if (isset($api['response_status']) && ($api['response_status'] == true)) {
      $insert_id = $api['response_data']['insert_id'];
    }
    return $api;
  }

  /**
  | Group | EXTRA TEMPLATE
  | Description | 4.อัพเดท 'EXTRA TEMPLATE'
  | $code | โค้ดโมดูล
  | $id | ไอดีรายการ
  | $data | [-]
   */
  public static function updateExtraTemplate($code, $id, $data = []) {
    $rep = [
      'update_result' => false
    ];

    $model = Amst::getByID($code.'sys_extra_template', ['id'], $id);
    if (!isset($model['id'])) {
      return Amst::repError('E0005', 'tablesys_extra_template');
    }

    $api = Amst::updateByID($code.'sys_extra_template', $data, $id);
    if (isset($api['response_status']) && ($api['response_status'] == true)) {
    }
    return $api;
  }

  /**
  | Group | EXTRA TEMPLATE
  | Description | 5.ลบ 'EXTRA TEMPLATE'
  | $id | ไอดีรายการ
   */
  public static function deleteExtraTemplate($id) {
    $rep = [
      'delete_result' => false
    ];

    $record = Amst::getByID('sys_extra_template', '*', $id);
    if (isset($record['id'])) {
      $rep['delete_result'] = Amst::deleteByID('sys_extra_template', $id);
      return Amst::repSuccess($rep);
    }
    return Amst::repError('E0000', 'Delete ExtraTemplate Fail');
  }

  /**
  | Group | EXTRA TEMPLATE
  | Description |  'EXTRA TEMPLATE'
  | $func | code, id
  | $template | template name
  | $tab |  tab_name or 1..n
   */
  public static function getExtraTemplate($func, $template, $tab = null) {
    $where = [
      'AND' => [
        'template' => $template
      ],
      'OR'  => [
        'sys_function_id' => $func,
        'code'            => $func
      ]
    ];
    $extra_template = Amst::get('sys_extra_template', '*', $where);
    $meta_data      = [];
    if (isset($extra_template['id'])) {
      $meta_data                   = json_decode($extra_template['meta_data'], true);
      $extra_template['meta_data'] = $meta_data;
      if ($tab) {
        if (is_numeric($tab)) {
          $tab                         = $tab - 1;
          $meta_data                   = $meta_data[$tab];
          $extra_template['meta_data'] = [$meta_data];
        } else {
          foreach ($meta_data as $key => $meta) {
            if ($meta['tab_name'] == $tab) {
              $meta_data = $meta;
            }
          }
          $extra_template['meta_data'] = [$meta_data];
        }
      }
    }
    return $extra_template;
  }

  /**
  | Group | EXTRA TEMPLATE
  | Description |  'EXTRA TEMPLATE'
  | $func | code, id
  | $template | template name
  | $tab | tab
   */
  public static function getExtraTemplateTabCount($func, $template) {
    $where = [
      'AND' => [
        'template' => $template
      ],
      'OR'  => [
        'sys_function_id' => $func,
        'code'            => $func
      ]
    ];
    $extra_template = Amst::get('sys_extra_template', '*', $where);
    $count          = 0;
    if (isset($extra_template['id'])) {
      $meta_data = json_decode($extra_template['meta_data'], true);
      $count     = count($meta_data);
    }
    return $count;
  }

  /**
  | Group | COPY EXTRA TEMPLATE
  | Description |  'COPY EXTRA TEMPLATE'
  | $code | code
  | $template | template name
   */
  public static function copyTemplate($template_id, $code, $template) {
    $extra_template = Amst::getByID('sys_extra_template', '*', $template_id);
    if (!isset($extra_template['id'])) {
      return Amst::repError('E0000', 'Extra template not found');
    }
    $current_template = static::getExtraTemplate($code, $template);
    if (!isset($current_template['id'])) {
      return Amst::repError('E0000', 'Current template not found');
    }

    if ($extra_template['code'] != $current_template['code']) {
      return Amst::repError('E0000', 'Code template not match');
    }

    $copy_meta_data    = json_decode($extra_template['meta_data'], true);
    $current_meta_data = $current_template['meta_data'];
    if (!$current_meta_data) {
      $current_meta_data = [];
    }

    foreach ($copy_meta_data as $tab) {
      $tab_id              = strtotime('now').'_'.rand(1, 9999);
      $tab['tab_id']       = $tab_id;
      $current_meta_data[] = $tab;
    }

    $u_data = [
      'meta_data' => json_encode($current_meta_data)
    ];

    return Amst::updateByID('sys_extra_template', $u_data, $current_template['id']);
  }

  /**
  | Group | EXTRA RECORD DATA
  | Description |  'EXTRA RECORD DATA'
  | $func | code, id
  | $table_name | table name
  | $record_id | record_id
   */
  public static function getExtraRecordData($func, $table_name, $record_id) {
    $where = [
      'OR' => [
        'id'   => $func,
        'code' => $func
      ]
    ];
    $module = Amst::get('sys_function', '*', $where);
    if (!isset($module['id'])) {
      return null;
    }
    $system_modules = ['User'];
    $code           = '';
    if (!in_array($module['module'], $system_modules)) {
      $code = $module['code'];
    }
    $table = $code.$table_name;
    if (strpos($table_name, 'sys_') !== false) {
      $table = $table_name;
    }
    $data = Amst::getByID($table, '*', $record_id);
    if (!isset($data['id'])) {
      return null;
    }
    return $data;
  }

  /**
  | Group | EXTRA TEMPLATE TAB
  | Description | 'EXTRA TEMPLATE TAB'
  | $func | code, id
  | $template | template
  | $tab_name | tab_name
  | $data | [meta_data, setting]
   */
  public static function addExtraTemplateTab($func, $template, $tab_name, $data = []) {
    $where = [
      'OR' => [
        'id'   => $func,
        'code' => $func
      ]
    ];
    $module = Amst::get('sys_function', '*', $where);
    if (!isset($module['id'])) {
      return Amst::repError('E0000', 'Function not found');
    }

    $meta_data = [];
    if ($tab_name) {
      $meta_data[] = [
        'tab_id'    => strtotime('now').'_'.rand(1, 9999),
        'tab_name'  => $tab_name,
        'meta_data' => ($data['meta_data']) ? $data['meta_data'] : [],
        'setting'   => ($data['setting']) ? $data['setting'] : []
      ];
    }
    $n_data = [
      'sys_function_id' => $module['id'],
      'code'            => $module['code'],
      'template'        => $template,
      'meta_data'       => ($meta_data) ? json_encode($meta_data) : ''
    ];

    $where = [
      'AND' => [
        'sys_function_id' => $module['id'],
        'code'            => $module['code'],
        'template'        => $template,
        'system_delete'   => 0
      ]
    ];

    $check = Amst::get('sys_extra_template', '*', $where);
    if (isset($check['id'])) {
      $old_meta_data = json_decode($check['meta_data'], true);
      if ($old_meta_data) {
        if ($meta_data) {
          $meta_data = array_merge($old_meta_data, $meta_data);
        } else {
          $meta_data = $old_meta_data;
        }
      }
      $n_data['meta_data'] = json_encode($meta_data);
      Amst::updateByID('sys_extra_template', $n_data, $check['id']);
      return Amst::repSuccess(true);
    }
    Amst::insert('sys_extra_template', $n_data);
    return Amst::repSuccess(true);
  }

  /**
  | Group | EXTRA TEMPLATE TAB
  | Description | 'EXTRA TEMPLATE TAB'
  | $tab_id | tab_id
  | $data | [tab_name, meta_data, setting]
   */
  public static function updateExtraTemplateTab($tab_id, $data = []) {
    $temp_tab_id = str_replace('_', '\_', $tab_id);
    $where       = [
      'AND' => [
        'meta_data[~]'  => '%'.$temp_tab_id.'%',
        'system_delete' => 0
      ]
    ];
    $extra_template = Amst::get('sys_extra_template', '*', $where);
    if (!isset($extra_template['id'])) {
      return Amst::repError('E0000', 'Extra template not found');
    }

    $meta_data = json_decode($extra_template['meta_data'], true);
    $temp      = [];
    foreach ($meta_data as $tab) {
      if ($tab['tab_id'] == $tab_id) {
        $tab['tab_name']  = isset(($data['tab_name'])) ? $data['tab_name'] : $tab['tab_name'];
        $tab['meta_data'] = isset(($data['meta_data'])) ? $data['meta_data'] : $tab['meta_data'];
        $tab['setting']   = isset(($data['setting'])) ? $data['setting'] : $tab['setting'];
      }
      $temp[] = $tab;
    }
    $u_data = [
      'meta_data' => json_encode($temp)
    ];
    return Amst::updateByID('sys_extra_template', $u_data, $extra_template['id']);
  }

  /**
  | Group | EXTRA TEMPLATE TAB
  | Description |  'EXTRA TEMPLATE TAB'
  | $tab_id | tab_id
   */
  public static function getExtraTemplateTab($tab_id) {
    $temp_tab_id = str_replace('_', '\_', $tab_id);
    $where       = [
      'AND' => [
        'meta_data[~]'  => '%'.$temp_tab_id.'%',
        'system_delete' => 0
      ]
    ];
    $extra_template = Amst::get('sys_extra_template', '*', $where);
    if (!isset($extra_template['id'])) {
      return null;
    }

    $meta_data = json_decode($extra_template['meta_data'], true);
    $temp      = [];
    foreach ($meta_data as $tab) {
      if ($tab['tab_id'] == $tab_id) {
        $temp[] = $tab;
      }
    }
    $extra_template['meta_data'] = $temp;
    return $extra_template;
  }

  /**
  | Group | EXTRA TEMPLATE TAB
  | Description |  'EXTRA TEMPLATE TAB'
  | $tab_id | tab_id
   */
  public static function deleteExtraTemplateTab($tab_id) {
    $temp_tab_id = str_replace('_', '\_', $tab_id);
    $where       = [
      'AND' => [
        'meta_data[~]'  => '%'.$temp_tab_id.'%',
        'system_delete' => 0
      ]
    ];
    $extra_template = Amst::get('sys_extra_template', '*', $where);
    if (!isset($extra_template['id'])) {
      return Amst::repError('E0000', 'Tab not found');
    }

    $meta_data = json_decode($extra_template['meta_data'], true);
    $temp      = [];
    foreach ($meta_data as $tab) {
      if ($tab['tab_id'] != $tab_id) {
        $temp[] = $tab;
      }
    }
    $u_data = [
      'meta_data' => ($temp) ? json_encode($temp) : ''
    ];
    return Amst::updateByID('sys_extra_template', $u_data, $extra_template['id']);

    return Amst::repSuccess(true);
  }

  /**
  | Group | COPY EXTRA TEMPLATE TAB FIELD
  | Description |  'COPY EXTRA TEMPLATE TAB FIELD'
  | $tab_id | tab_id
  | $field_name | field_name
  | $to_tab_id | to_tab_id
   */
  public static function copyTabField($tab_id, $field_name, $to_tab_id) {
    $from_tab = static::getExtraTemplateTab($tab_id);
    if (!isset($from_tab['id'])) {
      return Amst::repError('E0000', 'Tab not found (1)');
    }
    $from_meta_data = $from_tab['meta_data'];

    $to_tab = static::getExtraTemplateTab($to_tab_id);
    if (!isset($to_tab['id'])) {
      return Amst::repError('E0000', 'Tab not found (2)');
    }
    $to_template  = static::getExtraTemplateByID($to_tab['id']); //for all tab in template
    $to_meta_data = $to_template['meta_data'];

    $copy_field = [];
    foreach ($from_meta_data as $tab) {
      if ($tab['tab_id'] == $tab_id) {
        foreach ($tab['meta_data'] as $field) {
          if ($field['field_name'] == $field_name) {
            $copy_field = $field;
          }
        }
      }
    }
    if (!$copy_field) {
      return Amst::repError('E0000', 'Field not found');
    }

    $temp = [];
    foreach ($to_meta_data as $tab) {
      if ($tab['tab_id'] == $to_tab_id) {
        if (!$tab['meta_data']) {
          $tab['meta_data'] = [];
        }
        $tab['meta_data'][] = $copy_field;
      }
      $temp[] = $tab;
    }
    $u_data = [
      'meta_data' => json_encode($temp)
    ];
    return Amst::updateByID('sys_extra_template', $u_data, $to_template['id']);
  }

  /**
  | Group | SORT EXTRA TEMPLATE TAB
  | Description |  'SORT EXTRA TEMPLATE TAB'
  | $template_id | template_id
  | $tab_ids | tab_ids
   */
  public static function sortTabs($template_id, $tab_ids) {
    $template = Amst::getByID('sys_extra_template', '*', $template_id);
    if (!isset($template['id'])) {
      return Amst::repError('E0000', 'Template not found');
    }
    $meta_data = json_decode($template['meta_data'], true);

    $temp = [];
    foreach ($tab_ids as $tab_id) {
      foreach ($meta_data as $key => $tab) {
        if ($tab['tab_id'] == $tab_id) {
          $temp[] = $tab;
        }
      }
    }
    $u_data = [
      'meta_data' => json_encode($temp)
    ];
    return Amst::updateByID('sys_extra_template', $u_data, $template['id']);
  }

  /**
  | Group | EXTRA COLUMNS
  | Description | 1.count 'EXTRA COLUMNS'
  | $code | code module
  | $where | [code, table_name, name]
   */
  public static function countExtraColumns($code = [], $where = []) {
    $return    = []; // list
    $sql_where = []; // list

    if (isset($where['code']) && $where['code']) {
      $sql_where['AND']['code'] = $where['code'];
    }

    if (isset($where['table_name']) && $where['table_name']) {
      $sql_where['AND']['table_name'] = $where['table_name'];
    }

    if (isset($where['name']) && $where['name']) {
      $sql_where['AND']['name'] = $where['name'];
    }

    return Amst::count('sys_extra_column', $sql_where);
  }
}

?>