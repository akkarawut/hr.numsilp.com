<?php
class Test {
  static function ping() {
    return 'Wooof Woooooooof !!';
  }

  static function pingpong($message) {
    return 'Wooof : '.$message;
  }

  private static function testTransaction($code, $data) {
    $rep = [
      'test_result' => false
    ];

    $rep['getTransactionState'] = Amst::getTransactionState();
    Amst::transBegin(); // transaction begins
    $rep['getTransactionState2'] = Amst::getTransactionState();

    $insert_id = Amst::insert($code, $data);
    if ($insert_id) {
      $rep['insert_id1'] = $insert_id;

      $insert_id2 = Amst::insert($code, $data);
      $insert_id2 = false; // FORCE TO FAIL
      if ($insert_id2) {
        $rep['insert_id2'] = $insert_id2;
        Amst::transCommit(); // transaction is committed
        $rep['_log'][] = 'Database transaction was successful';
      } else {
        Amst::transRollback(); // transaction rolls back
        $rep['_log'][] = 'transaction rolled back on 2';
      }
    } else {
      Amst::transRollback(); // transaction rolls back
      $rep['_log'][] = 'transaction rolled back on 1';
    }

    return Amst::repSuccess($rep);
  }

  /**
  | Group | TEST
  | Description | หา 'คลาส' จากชื่อตาราง
   */
  private static function _ondev_getTableClass($code, $action = 'insert') {
    $return = false;

    if (Util::startsWith($code, 'sys_')) {
      return $code; // SYSTEM
    }

    if (Util::startsWith($code, Info::module_table_prefix)) {
      $return = substr($code, strlen(Info::module_table_prefix));
    }

    // $return = 'code_yyyyyyyyy';
    // Get Module Code
    $code_len = 5;
    if (isset($return[$code_len]) && ($return[$code_len] === '_')) {
      $module_code = substr($return, 5);
      $module = Func::getByCode(['id', 'code', 'module', 'name'], $module_code);
      if (isset($model['module'])) {
        return Config_Module::$module_file;
      } else {
        return false;
      }
    }

    return $return;
  }

  /**
  | Group | testQuery
  | Description | 3.testQuery
  | $abb | รหัสธนาคาร
   */
  public static function testQuery() {
    $rep = [

    ];

    $ids = [];
    $where = [
      'AND' => [
        'system_delete' => 0
      ]
    ];
    foreach (range(1, 5) as $i) {
      $ids[$i] = '"'.$i.'"';
    }
    $where['AND']['OR #shop_cat_id']['category_ids[~]'] = array_values($ids);

    $rep['select'] = Amst::selectNoKey('bnkql_shop', ['id', 'username'], $where);
    // SELECT `id`,`username` FROM `func_bnkql_shop` WHERE (`system_delete` = 0 AND ((`category_ids` LIKE '%\\\"1\\\"%' OR `category_ids` LIKE '%\\\"2\\\"%' OR `category_ids` LIKE '%\\\"3\\\"%' OR `category_ids` LIKE '%\\\"4\\\"%' OR `category_ids` LIKE '%\\\"5\\\"%'))) AND (`system_delete` = 0)

    return Amst::repSuccess($rep);
  }

  /**
  | Group | TEST
  | Description | insertBulk = data + sys_cols
  | $from_id | TableName
  | $to_id | TableName
   */
  public static function insertUserAddressTest($from_id = 1, $to_id = 10) {
    $rep = [
      'update_result' => false
    ];

    ini_set('memory_limit', '1024M');
    // Bulk INSERT Performance
    // 500 rows = 3s
    // 1000 rows = 14s
    // 500x2 rows = 7s
    // 100x10 rows = 1.49s
    // 100x51 rows = 6.41s
    // 200x26 rows = 14.19s
    // 50x101 rows = 1.67s
    // "q_count": 251, "page_size": 20, "data_count": 5002 = 0.497s
    // "q_count": 26, "page_size": 200, "data_count": 5002 = 14.27s
    // "q_count": 2501, "page_size": 2, "data_count": 5002 = 1.97s
    // "q_count": 2500, "memory_limit": "32M", "page_size": 20, "data_count": 50000 = 4.23s
    // "q_count": 1667, "memory_limit": "32M", "page_size": 30, "data_count": 50000 = 3.46s
    // "q_count": 1250, "memory_limit": "32M", "page_size": 40, "data_count": 50000 = 3.90s
    // "q_count": 3334, "memory_limit": "32M", "page_size": 30, "data_count": 100000 = 7.05s
    //  "q_count": 2500, "memory_limit": "32M", "page_size": 40, "data_count": 100000 = 7.78s
    // INSERT INTO tbl_name (a,b,c) VALUES(1,2,3),(4,5,6),(7,8,9);

    $table_name = 'sys_user_address';

    $data = [];
    foreach (range($from_id, $to_id) as $i) {
      $data[] = [
        'user_id'          => $i,
        'full_name'        => DB::setStringVal(''),
        'type_name'        => DB::setStringVal('user_address'),
        'address'          => DB::setStringVal(md5("address_{$i}")),
        'sub_district'     => DB::setStringVal(''),
        'district'         => DB::setStringVal(''),
        'province'         => DB::setStringVal(''),
        'country'          => DB::setStringVal(''),
        'zipcode'          => DB::setStringVal(''),
        'tel_country_code' => DB::setStringVal(''),
        'tel_no'           => DB::setStringVal(''),
        'latitude'         => DB::setStringVal(''),
        'longitude'        => DB::setStringVal(''),
        'is_default'       => 0
      ];
    }
    // $rep['data'] = $data;

    // [BULK]
    $rep['insertBulk'] = Amst::insertBulk($table_name, $data, ['page_size' => 40]);

    return Amst::repSuccess($rep);
  }

  /**
  | Group | TEST
  | Description | insertBulk = data + sys_cols
  | $from_id | TableName
  | $to_id | TableName
   */
  public static function insertDepartmentTest($from_id = 1, $to_id = 1000) {
    $rep = [
      'update_result'   => false,
      'start_date_time' => Util::getSystemTimestamp()
    ];

    ini_set('memory_limit', '1024M');

    $table_name = 'sys_department';

    $data = [];
    foreach (range($from_id, $to_id) as $i) {
      $data[] = [
        'name'        => DB::setStringVal(Util::getRandomString(rand(10, 255), 'abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ')),
        'description' => DB::setStringVal(Util::getRandomString(rand(0, 255), ' !@#$%^&*()_+abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'))
      ];
    }
    // $rep['data'] = $data;

    // [BULK]
    $rep['insertBulk'] = Amst::insertBulk($table_name, $data, ['page_size' => 30]); // 30-40 is GOOD
    return Amst::repSuccess($rep);
  }

  /**
  | Group | TEST
  | Description | insertBulk = data + sys_cols
  | $from_id | TableName
  | $to_id | TableName
   */
  public static function insertWorkPlaceTest($from_id = 1, $to_id = 10) {
    $rep = [
      'update_result'   => false,
      'start_date_time' => Util::getSystemTimestamp()
    ];

    ini_set('memory_limit', '1024M');
    // Bulk INSERT Performance
    // 500 rows = 3s
    // 1000 rows = 14s
    // 500x2 rows = 7s
    // 100x10 rows = 1.49s
    // 100x51 rows = 6.41s
    // 200x26 rows = 14.19s
    // 50x101 rows = 1.67s
    // "q_count": 251, "page_size": 20, "data_count": 5002 = 0.497s
    // "q_count": 26, "page_size": 200, "data_count": 5002 = 14.27s
    // "q_count": 2501, "page_size": 2, "data_count": 5002 = 1.97s
    // "q_count": 2500, "memory_limit": "32M", "page_size": 20, "data_count": 50000 = 4.23s
    // "q_count": 1667, "memory_limit": "32M", "page_size": 30, "data_count": 50000 = 3.46s
    // "q_count": 1250, "memory_limit": "32M", "page_size": 40, "data_count": 50000 = 3.90s
    // "q_count": 3334, "memory_limit": "32M", "page_size": 30, "data_count": 100000 = 7.05s
    //  "q_count": 2500, "memory_limit": "32M", "page_size": 40, "data_count": 100000 = 7.78s
    // INSERT INTO tbl_name (a,b,c) VALUES(1,2,3),(4,5,6),(7,8,9);

    $table_name = 'sys_user_workplace';

    $data = [];
    foreach (range($from_id, $to_id) as $i) {
      $data[] = [
        'user_id'           => $i,
        'department_id'     => rand(1, 1000),
        'position'          => DB::setStringVal(Util::getRandomString(32, ' !@#$%^&*()_+abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ')),
        'company_name'      => DB::setStringVal(Util::getRandomString(32, ' !@#$%^&*()_+abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ')),
        // 'address'          => DB::setStringVal(md5("address_{$i}")),
        'working_shift_id'  => rand(1, 1000),
        'default_salary'    => rand(10000, 1000000) / 10,
        'hours_per_month'   => rand(0, 730),
        'minutes_per_month' => rand(0, 43829),

        'working_date_from' => DB::setStringVal(util::getSystemDate(strtotime('2022-01-01') + rand(0, 31536000))),
        'working_date_to'   => DB::setStringVal(util::getSystemDate(strtotime('2022-01-01') + rand(0, 31536000))),

        'is_current_work'   => rand(0, 1)
      ];
    }
    //  $rep['data'] = $data;

    // [BULK]
    $rep['insertBulk'] = Amst::insertBulk($table_name, $data, ['page_size' => 30]); // 30-40 is GOOD

    return Amst::repSuccess($rep);
  }

  private static $conf = [
    'description' => 'คลาสสำหรับจัดการการตั้งค่าที่เกี่ยวข้องกับผู้ใช้',
    'tb_user'     => 'sys_user',
    'lbl_user'    => 'ผู้ใช้'
  ];

  public static function setTeamListDetail($return, $options = []) {
    // [ExtractList]
    $selected_records = isset($return['list']) ? $return['list'] : $return;

    // Do Each Works...
    if (Util::getOptionsValue('not_view', $options, false)) {
      // ? 'sys_user' : 'sys_view_user_four_detail
      $user_ids = [];
      foreach ($selected_records as $key => $row) {
        $selected_records[$key]['default_address_id'] = 0;
        $selected_records[$key]['default_address_type_name'] = '';
        $selected_records[$key]['default_address_full_name'] = '';
        $selected_records[$key]['default_address'] = '';
        $selected_records[$key]['default_address_sub_district'] = '';
        $selected_records[$key]['default_address_district'] = '';
        $selected_records[$key]['default_address_province'] = '';
        $selected_records[$key]['default_address_country'] = '';
        $selected_records[$key]['default_address_zipcode'] = '';
        $selected_records[$key]['default_address_tel_country_code'] = '';
        $selected_records[$key]['default_address_tel_no'] = '';
        $selected_records[$key]['default_address_latitude'] = '';
        $selected_records[$key]['default_address_longitude'] = '';
        $selected_records[$key]['workplace_department_id'] = 0;
        $selected_records[$key]['workplace_position'] = '';
        $selected_records[$key]['workplace_company_name'] = '';
        $selected_records[$key]['workplace_default_salary'] = '';
        $selected_records[$key]['workplace_department_name'] = '';
        $selected_records[$key]['workplace_department_description'] = '';
        // 
        $user_ids[$row['id']] = $row['id'];
      }

      if ($user_ids) {
        $user_address_list = [];
        $user_workplace_list = [];
        $dep_list = [];
        $where = [
          'AND' => [
            'user_id'       => array_values($user_ids),
            'type_name'     => 'user_address',
            'is_default'    => 1,
            'system_delete' => 0
          ]
        ];
        $selected_records = Amst::selectNoKey('sys_user_address', '*', $where, $options);
        foreach ($selected_records as $row) {
          $user_address_list[$row['user_id']] = $row;
        }

        $dep_ids = [];
        $where = [
          'AND' => [
            'user_id'       => array_values($user_ids),
            'system_delete' => 0
          ]
        ];
        $selected_records = Amst::selectNoKey('sys_user_workplace', '*', $where, $options);
        foreach ($selected_records as $row) {
          $dep_ids[$row['department_id']] = $row['department_id'];
          $user_workplace_list[$row['user_id']] = $row;
        }

        if ($dep_ids) {
          $where = [
            'AND' => [
              'id'            => array_values($dep_ids),
              'system_delete' => 0
            ]
          ];
          $dep_list = Amst::select('sys_department', '*', $where, $options);
        }

        foreach ($selected_records as $key => $row) {
          if (isset($user_address_list[$row['id']])) {
            $selected_records[$key]['default_address_id'] = $user_address_list[$row['id']]['id'];
            $selected_records[$key]['default_address_type_name'] = $user_address_list[$row['id']]['type_name'];
            $selected_records[$key]['default_address_full_name'] = $user_address_list[$row['id']]['full_name'];
            $selected_records[$key]['default_address'] = $user_address_list[$row['id']]['address'];
            $selected_records[$key]['default_address_sub_district'] = $user_address_list[$row['id']]['sub_district'];
            $selected_records[$key]['default_address_district'] = $user_address_list[$row['id']]['district'];
            $selected_records[$key]['default_address_province'] = $user_address_list[$row['id']]['province'];
            $selected_records[$key]['default_address_country'] = $user_address_list[$row['id']]['country'];
            $selected_records[$key]['default_address_zipcode'] = $user_address_list[$row['id']]['zipcode'];
            $selected_records[$key]['default_address_tel_country_code'] = $user_address_list[$row['id']]['tel_country_code'];
            $selected_records[$key]['default_address_tel_no'] = $user_address_list[$row['id']]['tel_no'];
            $selected_records[$key]['default_address_latitude'] = $user_address_list[$row['id']]['latitude'];
            $selected_records[$key]['default_address_longitude'] = $user_address_list[$row['id']]['longitude'];
          }
          if (isset($user_workplace_list[$row['id']])) {
            $i_dep_id = $user_workplace_list[$row['id']]['department_id'];
            $selected_records[$key]['workplace_department_id'] = $i_dep_id;
            $selected_records[$key]['workplace_position'] = $user_workplace_list[$row['id']]['position'];
            $selected_records[$key]['workplace_company_name'] = $user_workplace_list[$row['id']]['company_name'];
            $selected_records[$key]['workplace_default_salary'] = $user_workplace_list[$row['id']]['default_salary'];
            if (isset($dep_list[$i_dep_id])) {
              $selected_records[$key]['workplace_department_name'] = $dep_list[$i_dep_id]['name'];
              $selected_records[$key]['workplace_department_description'] = $dep_list[$i_dep_id]['description'];
            }
          }

          // 
          $user_ids[$row['id']] = $row['id'];
        }
      }
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
  | Group | ตั้งค่าทีม
  | Description | 1.ดึงข้อมูลลิส 'ตั้งค่าทีม'
  | $where | [รองรับauto_search(ตาราง _setting_team)]
  | $options | [not_view(true|เอาแค่ข้อมูลหลักไป edit*เร็ว), selected_fields(*|คอลัมภ์ที่ต้องการ), total_count(true|พร้อมจำนวนข้อมูลทั้งหมด ตามการกรอง(ไม่แบ่งหน้า)), page_no(1|เลขหน้า 0 คือส่งทั้งหมด), page_size(10|จำนวนข้อมูลต่อหน้า), sort_col(เรียงตามคอลัมภ์), sort_by(DESC|เรียงจากมากไปน้อย)]
   */
  public static function selectTeam($where = [], $options = []) {
    $return = []; // list

    $sql_where = [];

    if (isset($where['id']) && strlen($where['id'])) {
      $sql_where['AND']['id'] = $where['id'];
    }
    if (isset($where['username']) && strlen("{$where['search_text']}")) {
      $sql_where['AND']['name[~]'] = "{$where['search_text']}";
    }

    $options = Amst::checkOption($options);
    $options['where'] = $where;
    // WHERE `username` LIKE '%12b%' 
    // AND `workplace_department_description` LIKE '%33b%' 
    // AND `shopee_id` = '' 
    // AND `workplace_default_salary` >= '20000' 
    // AND `default_address` LIKE '%2%' 
    // AND `id` = '63754' LIMIT 50;

    $table_name = (Util::getOptionsValue('not_view', $options, false) ? 'sys_user' : 'sys_view_user_four_detail');
    $selected_fields = Amst::getSelectedFields($options, ['id', 'system_delete']);
    $return = Amst::selectNoKey($table_name, $selected_fields, $sql_where, $options);
    return static::setTeamListDetail($return, $options);
  }

  public static function setTeamDetail($record, $options = []) {
    if (!isset($record['id'])) {
      return $record;
    }

    // Do Single Works...
    if (Util::getOptionsValue('not_view', $options, false)) {
      // ? 'sys_user' : 'sys_view_user_four_detail
    }

    // $record['other_key'] = 'other_data';

    return $record;
  }

  /**
  | Group | ตั้งค่าทีม
  | Description | 2.ดึงข้อมูล 'ตั้งค่าทีม' ด้วยไอดี
  | $id | ไอดีรายการ
  | $options | [selected_fields('*' // คอลัมภ์ที่ต้องการ)]
   */
  public static function getTeamByID($id, $options = []) {
    $return = null;

    $table_name = (Util::getOptionsValue('not_view', $options, false) ? 'sys_user' : 'sys_view_user_four_detail');
    $selected_fields = Amst::getSelectedFields($options, ['id', 'system_delete']);
    if ($id) {
      $model = Amst::getByID($table_name, $selected_fields, $id, $options);
      if (isset($model['id'])) {
        $return = static::setTeamDetail($model, $options);
      }
    }
    return $return;
  }

  /**
  | Group | ตั้งค่าทีม
  | Description | 3.เพิ่ม 'ตั้งค่าทีม'
  | $data | [name(ชื่อทีม), description(คำอธิบาย)]
   */
  public static function addNewTeam($data) {
    $rep = [
      'insert_id' => false
    ];

    return Amst::insert(static::$conf['sys_user'], $data);
  }

  /**
  | Group | ตั้งค่าทีม
  | Description | 4.อัพเดท 'ตั้งค่าทีม'
  | $id | ไอดีรายการ
  | $data | [name(name)]
   */
  public static function updateTeam($id, $data) {
    $rep = [
      'update_result' => false
    ];

    $model = Amst::getByID(static::$conf['sys_user'], ['id'], $id);
    if (!isset($model['id'])) {
      return Amst::repError('E0005', static::$conf['lbl_user']);
    }

    return Amst::updateByID(static::$conf['sys_user'], $data, $id);
  }

  /**
  | Group | ตั้งค่าทีม
  | Description | 5.ลบ 'ตั้งค่าทีม'
  | "เงื่อนไขในการลบ" | [ลบซ้ำไม่ได้]
   */
  public static function deleteTeam($id) {
    $rep = [
      'delete_result' => false
    ];

    $api = static::_checkDeleteTeam($id);
    if (isset($api['response_status']) && ($api['response_status'] == true)) {
      $rep['delete_result'] = Amst::deleteByID(static::$conf['sys_user'], $id);
      return Amst::repSuccess($rep);
    }
    return $api; // ERROR SURE
  }

  private static function _checkDeleteTeam($id) {
    $rep = [
      'id' => $id
    ];

    // [Model_Exist]
    $model = Amst::getByID(static::$conf['sys_user'], ['id'], $id);
    if (!isset($model['id'])) {
      return Amst::repError('E0005', static::$conf['lbl_user']);
    }

    return Amst::repSuccess($rep);
  }

  /**
  | Group | TEST ตั้งค่าทีม
  | Description | 1.ดึงข้อมูลลิส 'ตั้งค่าทีม'
  | $where | [id(id),full_name(username),username(username),is_block(is_block)]
  | $options | [not_view(true|เอาแค่ข้อมูลหลักไป edit*เร็ว), selected_fields(*|คอลัมภ์ที่ต้องการ), total_count(true|พร้อมจำนวนข้อมูลทั้งหมด ตามการกรอง(ไม่แบ่งหน้า)), page_no(1|เลขหน้า 0 คือส่งทั้งหมด), page_size(10|จำนวนข้อมูลต่อหน้า), sort_col(เรียงตามคอลัมภ์), sort_by(DESC|เรียงจากมากไปน้อย)]
   */
  public static function selectTeamTest($where = [], $options = []) {
    $rep = [
      'profiler' => [
        'start_microseconds' => microtime(true)
      ]
    ]; // DEBUG

    $options = Amst::checkOption($options);
    $options['debug'] = 'debug';

    // EXPLAIN SELECT * FROM `sys_view_user_four_detail` 
    // WHERE `username` LIKE '%12b%' 
    // AND `workplace_department_description` LIKE '%33b%' 
    // AND `shopee_id` = '' 
    // AND `workplace_default_salary` >= '20000' 
    // AND `default_address` LIKE '%2%' 
    // AND `id` = '63754' LIMIT 50;

    $rep['where'] = $where;
    $rep['where'] = $where;
    $rep['options'] = $options;
    $sr = static::selectTeam($where, $options);
    $rep['select_count'] = count($sr);
    // $rep['select_count'] = isset($sr['list']) ? count($sr['list']) : 0;
    // $rep['select_debug'] = isset($sr['debug']) ? ($sr['debug']) : [];
    unset($sr);
    $rep['profiler']['memory_get_usage'] = (($usage = memory_get_usage()) != '') ? number_format($usage) : 0;
    $rep['profiler']['memory_get_usage'] = (($usage = memory_get_usage()) != '') ? number_format($usage) : 0;
    $rep['profiler']['memory_allowcated'] = (($usage = memory_get_usage(true)) != '') ? number_format($usage) : 0;
    $rep['profiler']['total_microseconds'] = microtime(true) - ($rep['profiler']['start_microseconds']);
    return Amst::repSuccess($rep);
  }
}
