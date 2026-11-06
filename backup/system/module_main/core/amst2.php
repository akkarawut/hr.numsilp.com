<?php
class Amst
{

    private static $_transaction_state      = false; // false|ON|DONE


    /**
     * [IMPORT] Autoloader
     *
     * @author BALL
     * @return NULL
     */
    public static function autoLoad($class_name)
    {
        // system class
        if (isset(Info::$system_file[$class_name]['path'])) {
            if (!is_null(Info::$system_file[$class_name]['path']) || Info::$system_file[$class_name]['path'] != '') {
                if (!class_exists($class_name)) {
                    include_once ROOT_PHP . '/' . Info::$system_file[$class_name]['path'] . '.php';
                    return;
                }
            }
        }

        // Library
        if (file_exists(ROOT_PHP . "/library/{$class_name}.php")) {
            require_once ROOT_PHP . "/library/{$class_name}.php";
        }

        // module class
        $functions = Func::selectForAutoload();
        foreach ($functions as $function) {
            if (file_exists(ROOT_PHP . '/' . Config_Module::$module_file[$function['module']] . '/class/')) {
                foreach (glob(ROOT_PHP . '/' . Config_Module::$module_file[$function['module']] . '/class/*.php') as $file_name) {
                    $file_class_name = explode('/', $file_name);
                    $file_class_name = end($file_class_name);
                    if ($file_class_name != 'index.php') {
                        $file_class_name = substr($file_class_name, 0, strlen($file_class_name) - 4);
                        if (!class_exists($file_class_name) && (strtolower($class_name) == strtolower($file_class_name))) {
                            include_once $file_name;
                            return;
                        }
                    }
                }
            }
        }
    }

    /**
     * [HELPER] Check if code is sys_, Convert code into fully Table Name.
     *
     * @author BALL
     * @return STRING fully Table name
     */
    public static function checkCode($code)
    {
        if (strpos($code, Info::module_table_prefix) !== false || strpos($code, 'sys_') !== false) {

            return $code;
        }

        return Info::module_table_prefix . $code;
    }

    /**
     * [UTIL] Check Where is Array and not Empty
     *
     * @author BALL
     * @param ARRAY $where INPUT where
     * @return ARRAY
     */
    public static function checkWhere($where)
    {
        if (!$where || !is_array($where)) {
            return [];
        }

        return $where;
    }

    /**
     * [UTIL] Check WHERE is Array and not Empty + Auto where from Fron-end
     *
     * @author BALL
     * @param ARRAY $options INPUT options
     * @return ARRAY
     */
    public static function checkOption($options)
    {
        if (!$options || !is_array($options)) {
            return [];
        }

        return $options;
    }

    /**
   * [UTIL] Get Columns with Required Columns.
   * [20211210] Will not check with db, Let it ERROR (and Speed).
   *
   * @author Best
   * @param STRING $action 'insert', 'update'
   * @param ARRAY $table_info Table INFO
   * @return ARRAY
   */
  public static function getSelectedFields($options, $required_cols = [])
  {
    // Validate
    $options       = static::checkOption($options);
    $required_cols = static::checkOption($required_cols);

    $selected_fields = static::getOptionsValue('selected_fields', $options, '*');
    if ($selected_fields !== '*') {
      $selected_fields = array_values(Util::makeArray($selected_fields));
      foreach ($required_cols as $i_required_name) {
        if (!in_array($i_required_name, $selected_fields)) {
          $selected_fields[] = $i_required_name;
        }
      }
    }
    return $selected_fields;
  }

  /**
   * [Transaction] ดึงสถานะ ว่าเปิดอยู่ไหม
   *
   * @author Best
   * @return BOOL Transaction open state
   */
  public static function getTransactionState()
  {
    return static::$_transaction_state;
  }

  /**
   * [UTIL] AutoWhere จาก Frontend เบื้องต้น | Check WHERE is Array and not Empty + Auto where from Fron-end
   *
   * @author Best
   * @param ARRAY $frontend_where 'insert', 'update'
   * @param ARRAY $backend_where Table INFO
   * @param ARRAY $not_keys Table INFO
   * @return ARRAY
   */
  public static function mergeWhere($frontend_where, $backend_where = [], $not_keys = [])
  {
    // Validate
    $frontend_where = static::checkWhere($frontend_where);
    $backend_where  = static::checkWhere($backend_where);
    $not_keys       = static::checkOption($not_keys);

    // if ($frontend_where) {
    //   Util::recursive_unset_arraykey($frontend_where, 'AND'); // ไม่ให้ Where เอง
    //   Util::recursive_unset_arraykey($frontend_where, 'OR'); // ไม่ให้ Where เอง
    // }

    foreach ($not_keys as $i_key) {
      unset($frontend_where[$i_key]); // ไม่ให้ Where คีย์นี้
    }
    $backend_where['_frontend_where'] = $frontend_where;
    return static::_setAutoWhere($backend_where);
  }

  private static function _setAutoWhere($where)
  {
    // [WHERE][AUTO]
    $where = static::checkWhere($where); // หน้าบ้านชอบส่ง สตริงเปล่ามา
    if (isset($where['_frontend_where'])) {
      $_frontend_where = $where['_frontend_where'];

      // [TODO] Check Where-Table
      foreach ($_frontend_where as $key => $value) {
        // Has Cols
        if (Util::startsWith($key, 'is_')) {
          if (!Util::isEmptyString($value) && !Util::compareKey($value, 'all')) {
            $where[$key] = trim($value); // Boolean
          }
        } else if (strpos($key, 'status') !== false) {
          $where[$key] = trim($value); // STATUS
        } else {
          // error on array $i_str = trim("{$value}"); // Auto LIKE on VARCHAR
          $i_str = ($value); // Auto LIKE on VARCHAR
          if (strlen("{$i_str}")) {
            $where[$key] = $value;
          }
        }
      }
      unset($where['_frontend_where']);
    }
    return $where;
  }

    /**
  | Group | Helper
  | Description | Return OPTIONS value (Must be Key=>Value)
     */
    public static function getOptionsValue($key, $with_option, $default = false)
    {
        if (self::isHasOptions($key, $with_option)) {
            return isset($with_option[$key]) ? $with_option[$key] : $default;
        }
        // onlyKeyArray

        return $default;
    }

    /**
  | Group | Helper
  | Description | Check OPTIONS array has key or in array
     */
    public static function isHasOptions($key = '', $with_option = [])
    {
        if (is_array($with_option)) {
            return (isset($with_option[$key]) || in_array($key, $with_option, true));
        }
        return $with_option === $key;
    }

    /**
    | Group | ERROR
    | Description | แปลง ErrorCode(E4) เป็น ข้อความ
    | $error_code | EX00X รหัสเออเรอร์
    | $params | Array Params for vsprintf
     */
    public static function getErrorMsg($error_code, $params = '')
    {
        $_error_msg = [
            // 1.SYSTEM
            'E0000' => '%s',
            'E0001' => 'กรุณากรอกข้อมูล \'%s\'',
            'E0002' => 'ไม่มีข้อมูลสำหรับการเพิ่มข้อมูลใหม่',
            'E0003' => 'ไม่สามารถลบข้อมูลได้ เนื่องจาก \'%s\'',
            'E0004' => 'ข้อมูลไอดีไม่ถูกต้อง',
            'E0005' => 'ไม่พบข้อมูล \'%s\' ในระบบ',
            'E0006' => 'ข้อมูลการกรองไม่ถูกต้อง',
            'E0007' => 'ไม่สามารถส่ง Array ใน \'%s\' เพื่อบันทึกข้อมูลได้', // NO array
            'E0008' => 'พบข้อผิดพลาดรุนแรง', // FREE
            'E0009' => 'ช่วงเวลาที่กรอกไม่ถูกต้อง', // BETWEEN
            'E0010' => 'กรุณากรอกข้อมูล \'%s\' ให้ถูกต้องตามรูปแบบของเวลา HH:MM:SS',
            'E0011' => 'กรุณากรอกข้อมูล \'%s\' ให้ถูกต้องตามรูปแบบของวันที่ YYYY-MM-DD',
            'E0012' => 'กรุณากรอกข้อมูล \'%s\' ให้ถูกต้องตามรูปแบบของวันที่และเวลา',
            'E0013' => 'กรุณากรอกข้อมูล \'%s\' ในรูปแบบของตัวเลข',
            'E0014' => 'กรุณากรอกข้อมูล \'%s\' ที่มีค่าไม่น้อยกว่า 0',
            'E0015' => 'กรุณากรอกข้อมูล \'%s\' ที่มีค่ามากกว่า 0',
            'E0016' => 'กรุณากรอกข้อมูล \'%s\' ที่มีความยาว %s', // Strlen
            'E0017' => 'กรุณากรอกข้อมูล \'%s\' ที่มีความยาวน้อยกว่า %s ', // Strlen
            'E0018' => 'กรุณากรอกข้อมูล \'%s\' ที่มีความยาวมากกว่า %s', // Strlen
            'E0019' => 'กรุณากรอกข้อมูล \'%s\' ที่มีค่ามากกว่า %s',
            'E0020' => 'กรุณากรอกข้อมูล \'%s\' ที่มีค่าน้อยกว่า %s'
        ];

        return (vsprintf($_error_msg[$error_code], $params));
    }

    /**
    | Group | SELECT/GET
    | Description | 2.ดึงข้อมูลลิสเป็น Array คีย์เป็น id.
    | $code | Table Code
    | $columns | คอลัมภ์ที่ต้องการ
    | $where | [id(กรองตามไอดี), json(กรอก JSON มาละจะแปลงเป็น $where ให้ทั้งหมด)]
    | $options | [no_key(แบบไม่มีคีย์), show_all(เอาข้อมูลที่ถูกลบไปด้วย), random(ดึงข้อมูลแล้วเรียงแบบสุ่ม), log_last_query(Log ข้อมูลที่ใช้ในการ SELECT), total_count(true|พร้อมจำนวนข้อมูลทั้งหมด ตามการกรองไม่แบ่งหน้า), page_no(1|เลขหน้า 0 คือส่งทั้งหมด), page_size(10|จำนวนข้อมูลต่อหน้า), sort_col(เรียงตามชื่อคอลัมภ์นี้), sort_by('ASC'|'DESC')]
     */
    public static function select($code, $columns = '*', $where = [], $options = [])
    {
        $code = static::checkCode($code);
        $where = static::checkWhere($where);

        // SHOW ALL
        if (!static::getOptionsValue('show_all', $options, false)) {
            $where['AND #not_show_all'] = [
                'system_delete' => 0 // system_delete 1 = deleted
            ];
        }

        // [TODO] validate table&cols
        $record_list = DB::select($code, $columns, $where);

        // NO_KEY
        if (!static::getOptionsValue('no_key', $options, false) && isset($record_list[0]['id'])) {
            $temp_records = [];
            // re arrange array if id available
            foreach ($record_list as $result) {
                $temp_records[$result['id']] = $result;
            }
            $record_list = $temp_records;
            //return $record_list;
            unset($temp_records);
        }

        return $record_list;
    }

    /**
    | Group | SELECT/GET
    | Description | 3.ดึงข้อมูลลิส แบบไม่มีคีย์.
    | $code | Table Code
    | $columns | คอลัมภ์ที่ต้องการ
    | $where | [id(กรองตามไอดี), json(กรอก JSON มาละจะแปลงเป็น $where ให้ทั้งหมด)]
    | $options | [show_all(เอาข้อมูลที่ถูกลบไปด้วย)]
     */
    public static function selectNoKey($code, $columns = '*', $where = [], $options = [])
    {
        $options = static::checkOption($options);
        $options['no_key'] = true;

        return static::select($code, $columns, $where, $options);
    }

    /**
    | Group | SELECT/GET
    | Description | 5.ดึงข้อมูลเจาะจง row
    | $code | Table Code
    | $columns | คอลัมภ์ที่ต้องการ
    | $where | [id(กรองตามไอดี), json(กรอก JSON มาละจะแปลงเป็น $where ให้ทั้งหมด)]
    | $options | [not_view(true|เอาแค่ข้อมูลหลักไป edit*เร็ว)]
     */
    public static function get($code, $columns = '*', $where = [], $options = [])
    {
        $return = null;
        $code = static::checkCode($code);
        $options = Amst::checkOption($options);

        // SHOW ALL
        if (!static::getOptionsValue('show_all', $options, false)) {
            $where['AND #not_show_all'] = [
                'system_delete' => 0 // system_delete 1 = deleted
            ];
        }

        $return = DB::get($code, $columns, $where);

        if (isset($options['hide_default_column']) && $return) {
            unset($return['insert_date_time']);
            unset($return['insert_user_id']);
            unset($return['insert_detail']);
            unset($return['update_date_time']);
            unset($return['update_user_id']);
            unset($return['update_detail']);
            unset($return['sort_order_id']);
            unset($return['version']);
            unset($return['system_delete']);
        }

        return $return;
    }

    /**
    | Group | SELECT/GET
    | Description | 6.ดึงข้อมูลตาม id ไม่ต้องใส่ where ให้เมื่อยมือ
    | $code | Table Code
    | $columns | คอลัมภ์ที่ต้องการ
    | $id | ไอดีรายการ
    | $options | [not_view(true|เอาแค่ข้อมูลหลักไป edit*เร็ว)]
    | $log | ส่ง log หากไม่ใส่อะไรมาเลยจะไม่ส่ง
     */
    public static function getByID($code, $columns = '*', $id = null, $options = [])
    {
        $return = null;

        if (is_int($id)) {
            $return = static::get($code, $columns, ['id' => $id], $options);
        }

        return $return;
    }

    /**
    | Group | OPERATION
    | Description | 7.ใส่ข้อมูลเข้าระบบ เช็คข้อมูลตาม INFO
    <hr><h3>นโยบายการ INSERT</h3>
    1. ส่ง $data ในรูปแบบ ของ $key => $value ระบบจะ INSERT ให้ทั้งหมด ทุกช่องที่ส่งมา<br>
    2. $value ที่เป็น NULL ที่ไม่ใช่ วัน/วันเวลา จะถูกเอาออกจาก $data<br>
    3. ถ้าส่ง $key ใดๆ ที่มีเงื่อนไขใน INFO จะถูกตรวจสอบเสมอ<br>
    4. ไม่มี REQUIRED หรือช่องที่บังคับส่งทุกครั้ง แต่ถ้าส่งมาจะมีการตรวจสอบ<br>
    | $code | Table Code
    | $data | Array or JSON STRING of Data
    | $options | [direct(true|insertตรงๆเลยส่งค่ากลับเป็น insert_id ไม่rep)]
     */
    public static function insert($code, $data = [], $options = [])
    {
        $rep = [
            'insert_id' => false
        ];

        // initial data
        $full_table_name = static::checkCode($code);
        $options = Amst::checkOption($options);
        $code = static::checkCode($code);

        // [JSON] support
        if (is_string($data)) {
            if (Json::isValidJson($data)) {
                $data = json_decode($data, true);
            }
        }

        if (!$data) {
            // No Data to INSERT
            return static::repError('E1001', ['code' => $code, 'full_table_name' => $full_table_name, 'data' => $data]);
        }

        // remove null data
        foreach ($data as $key => $value) {
            if (is_null($value)) {
                unset($data[$key]);
            } else {
                $data[$key] = $data[$key]; // TRIM
            }
        }

        // let's insert
        $data['insert_user_id'] = User::getCurrentUserID();
        $data['insert_detail']  = 'Dev[' . Dev::getCurrentUserID() . '] IP[' . Util::getClientIP() . ']';
        $insert_id= DB::insert($code, $data);

        if ($insert_id) {
            $u_data = [
              'sort_order_id'    => $insert_id,
              'update_date_time' => Util::getSystemTimestamp()
            ];
            $rep['update_result'] = DB::update($code, $u_data, ['id' => $insert_id]);
            // SUCCESS
            $rep['insert_id'] = $insert_id;
        } else {
            $db_err = DB::getLastError();
            // Database Failed to INSERT
            return static::repError('E1002', '', ['db_err' => $db_err]);
        }

        return Amst::repSuccess($rep);
    }

    /**
    | Group | OPERATION
    | Description | แก้ไขข้อมูลในระบบ เช็คข้อมูลตาม INFO
    <hr><h3>นโยบายการ UPDATE</h3>
    1. ส่ง $data ในรูปแบบ ของ $key => $value ระบบจะ UPDATE ให้ทั้งหมด ทุกช่องที่ส่งมา<br>
    2. $value ที่เป็น NULL ที่ไม่ใช่ วัน/วันเวลา จะถูกเอาออกจาก $data<br>
    3. ถ้าส่ง $key ใดๆ ที่มีเงื่อนไขใน INFO จะถูกตรวจสอบเสมอ<br>
    4. ไม่มี REQUIRED หรือช่องที่บังคับส่งทุกครั้ง แต่ถ้าส่งมาจะมีการตรวจสอบ<br>
    | $code | Table Code
    | $data | Array or JSON STRING of Data
    | $options | [direct(true|insertตรงๆเลยส่งค่ากลับเป็น insert_id ไม่rep)]
     */
    public static function update($code, $data, $where, $options = [])
    {
        $rep = [
            'update_result' => false // is Update Successful
        ];

        // initial data
        $full_table_name = static::checkCode($code);
        $options = Amst::checkOption($options);
        $code = static::checkCode($code);

        // ไม่ปล่อยให้อัพเดทแบบไม่มี WHERE
        if (!$where) {
            return static::repError('E0000', 'Invalid Update WHERE');
        }

        // [JSON] support
        if (is_string($data)) {
            if (Json::isValidJson($data)) {
                $data = json_decode($data, true);
            }
        }

        if (!$data) {
            // No Data to UPDATE
            return static::repError('E1003', ['code' => $code, 'full_table_name' => $full_table_name, 'data' => $data]);
        }

        $data['update_user_id'] = User::getCurrentUserID();
        $data['update_detail']  = 'Dev[' . Dev::getCurrentUserID() . '] IP[' . Util::getClientIP() . ']';
        if (!isset($data['update_date_time'])) {
           $data['update_date_time'] = Util::getSystemTimestamp();
        }
        $data['version[+]'] = 1;
        $rep['update_result'] = DB::update($code, $data, $where);

        return static::repSuccess($rep);
    }

    /**
    | Group | OPERATION
    | Description | แก้ไขข้อมูล เจาะจง id ไม่ต้องใส่ where ให้เมื่อยมือ
     */
    public static function updateByID($code, $data, $id, $options = [])
    {
        if (is_int($id)) {
            return static::update($code, $data, ['id' => $id], $options);
        }

        return Amst::repError('E0004');
    }

    /**
    | Group | OPERATION
    | Description | ลบแม่ง
    | $code | Table Code
    | $where | Array
     */
    public static function delete($code, $where)
    {
        $rep = [
            'delete_result' => true
        ];

        if ($where) {
            $rep['delete_result'] = static::update($code, ['system_delete' => 1], $where);
        }

        return Amst::repSuccess($rep);
    }

    /**
    | Group | OPERATION
    | Description | ลบแม่ง บัยส์ไอดี
    | $code | Table Code
    | $id | ID
     */
    public static function deleteByID($code, $id)
    {
        if (is_int($id)) {
            return static::delete($code, ['id' => $id]);
        }

        return Amst::repError('E0004');
    }

    /**
    | Group | OPERATION
    | Description | ไม่ลบละ เปลี่ยนใจละ
    | $code | Table Code
    | $where | Array
     */
    public static function deleteUndo($code, $where)
    {
        $rep = [
            'update_result' => true
        ];

        if (!is_array($where)) {
            return false;
        }

        $rep['update_result'] = static::update($code, ['system_delete' => 0], $where);

        return Amst::repSuccess($rep);
    }

    /**
    | Group | OPERATION
    | Description | ไม่ลบละ เปลี่ยนใจละ บัยส์ไอดี
    | $code | Table Code
    | $id | ID
     */
    public static function deleteUndoByID($code, $id)
    {
        if (is_int($id)) {
            return static::deleteUndo($code, ['id' => $id]);
        }

        return Amst::repError('E0004');
    }

    /**
    | Group | AGGREGATE
    | Description | ถ้ามีตาม where ก็เป็น true
    | $code | Table Code
    | $where | [id(กรองตามไอดี), json(กรอก JSON มาละจะแปลงเป็น $where ให้ทั้งหมด)]
    | $options | [show_all(รวมข้อมูลที่ถูกลบด้วย)]
     */
    public static function has($code, $where = [], $options = [])
    {
        $code = static::checkCode($code);
        $where = static::checkWhere($where);

        return DB::has($code, $where);
    }

    /**
    | Group | AGGREGATE
    | Description | นับจำนวนตาม where ไม่นับที่ลบไปแล้ว
    | $code | Table Code
    | $where | [id(กรองตามไอดี), json(กรอก JSON มาละจะแปลงเป็น $where ให้ทั้งหมด)]
    | $options | [show_all(รวมข้อมูลที่ถูกลบด้วย), plus(บวกลบข้อมูลให้เลย)]
     */
    public static function count($code, $where = [], $options = [])
    {
        $code = static::checkCode($code);
        $where = static::checkWhere($where);

        if (!static::getOptionsValue('show_all', $options, false)) {
            $where['AND #not_show_all'] = [
                'system_delete' => 0 // system_delete 1 = deleted
            ];
        }

        $where_without_order = [];
        foreach ($where as $key => $value) {
            if (strtolower($key) != 'order') {
                $where_without_order[$key] = $value;
            }
        }

        return DB::count($code, $where_without_order);
    }

    /**
    | Group | AGGREGATE
    | Description | ขอค่าที่มากที่สุดตามคอลั่มนั้น ** ส่งกลับ NULL ถ้า ไม่เจอข้อมูลเลย
    | $code | Table Code
    | $column_name | ชื่อคอลัมภ์ ไม่ใช่ '*'
    | $where | [id(กรองตามไอดี), json(กรอก JSON มาละจะแปลงเป็น $where ให้ทั้งหมด)]
    | $options | [show_all(รวมข้อมูลที่ถูกลบด้วย)]
     */
    public static function max($code, $column_name, $where = [], $options = [])
    {
        $code = static::checkCode($code);
        $where = static::checkWhere($where);

        if (!static::getOptionsValue('show_all', $options, false)) {
            $where['AND #not_show_all'] = [
                'system_delete' => 0 // system_delete 1 = deleted
            ];
        }

        return DB::max($code, $column_name, $where);
    }

    /**
    | Group | AGGREGATE
    | Description | ขอค่าที่มากที่สุดตามคอลั่มนั้น ** ส่งกลับ NULL ถ้า ไม่เจอข้อมูลเลย
    | $code | Table Code
    | $column_name | ชื่อคอลัมภ์ ไม่ใช่ '*'
    | $where | [id(กรองตามไอดี), json(กรอก JSON มาละจะแปลงเป็น $where ให้ทั้งหมด)]
    | $options | [show_all(รวมข้อมูลที่ถูกลบด้วย)]
     */
    public static function min($code, $column_name, $where = [], $options = [])
    {
        $code = static::checkCode($code);
        $where = static::checkWhere($where);

        if (!static::getOptionsValue('show_all', $options, false)) {
            $where['AND #not_show_all'] = [
                'system_delete' => 0 // system_delete 1 = deleted
            ];
        }

        return DB::min($code, $column_name, $where);
    }

    /**
    | Group | AGGREGATE
    | Description | ค่าเฉลี่ยของคอลั่มนั้น ** ส่งกลับ NULL ถ้า ไม่เจอข้อมูลเลย
    | $code | Table Code
    | $column_name | ชื่อคอลัมภ์ ไม่ใช่ '*'
    | $where | [id(กรองตามไอดี), json(กรอก JSON มาละจะแปลงเป็น $where ให้ทั้งหมด)]
    | $options | [show_all(รวมข้อมูลที่ถูกลบด้วย)]
     */
    public static function avg($code, $column_name, $where = [], $options = [])
    {
        $code = static::checkCode($code);
        $where = static::checkWhere($where);

        if (!static::getOptionsValue('show_all', $options, false)) {
            $where['AND #not_show_all'] = [
                'system_delete' => 0 // system_delete 1 = deleted
            ];
        }

        return DB::avg($code, $column_name, $where);
    }

    /**
    | Group | AGGREGATE
    | Description | รวมค่าคอลั่มนั้น ** ส่งกลับ NULL ถ้า WHERE ไม่เจอข้อมูลเลย
    | $code | Table Code
    | $column_name | ชื่อคอลัมภ์ ไม่ใช่ '*'
    | $where | [id(กรองตามไอดี), json(กรอก JSON มาละจะแปลงเป็น $where ให้ทั้งหมด)]
    | $options | [show_all(รวมข้อมูลที่ถูกลบด้วย)]
     */
    public static function sum($code, $column_name, $where = [], $options = [])
    {
        $code = static::checkCode($code);
        $where = static::checkWhere($where);

        if (!static::getOptionsValue('show_all', $options, false)) {
            $where['AND #not_show_all'] = [
                'system_delete' => 0 // system_delete 1 = deleted
            ];
        }

        // May return NULL => return 0
        $sum = DB::sum($code, $column_name, $where);

        return (!$sum ? 0 : $sum);
    }

    /**
    | Group | DIRECT_QUERY
    | Description | เรียก SQL QUERY เฉยๆ
    | $sql | SQL Query String
     */
    public static function query($sql)
    {
        return DB::query($sql);
    }

    /**
    | Group | DIRECT_QUERY
    | Description | เรียก SQL SELECT เพื่อดึงข้อมูลตามปกติ
    | $sql | SQL Query String
     */
    public static function queryFetchAll($sql)
    {
        return static::query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
    | Group | DIRECT_QUERY
    | Description | เรียก SQL FUNTION จะส่งกลับค่าเดียว ช่องแรกช่องเดียว ไว
    | $sql | SQL Query String
     */
    public static function queryFetchColumn($sql)
    {
        // PDOStatement::fetchColumn — Returns a single column from the next row of a result set
        return static::query($sql)->fetchColumn();
    }



    /**
     * Base API Reponse.
     *
     * @author BALL
     * @param BOOL $status of Success or Error
     * @param STRING $message Must be ERR_CODE - EX00X
     * @param ARRAY $data additional data to log
     * @return JSON API Response
     */
    public static function rep($status, $message, $data = [])
    {
        $result = [
            'response_status'    => $status,
            'response_message'   => $message,
            'response_date_time' => date('Y-m-d H:i:s'),
            'response_type'      => gettype($data),
            'response_data'      => $data
        ];

        return $result;
    }

    /**
     * Error API Reponse.
     *
     * @author BALL
     * @param STRING $message Must be ERR_CODE - EX00X
     * @param ARRAY $params additional data to log
     * @param ARRAY $data additional data to log
     * @return JSON API Response
     */
    public static function repError($message = 'error', $params = [], $data = [])
    {
        // [20211201] clickbit มาขอให้ปรับกลับก่อน
        if (false && "return as ECODE") {
            $data = Amst::checkOption($data);
            $data['msg_params'] = $params;
            $data['readable_message'] = static::getErrorMsg($message, $params);
        } else {
            $message = static::getErrorMsg($message, $params);
        }

        return static::rep(false, $message, $data);
    }

    /**
     * Success API Reponse.
     *
     * @author BALL
     * @param ARRAY $data to return
     * @return JSON API Response
     */
    public static function repSuccess($data = [])
    {
        // Production Array Rep
        if (!Dev::getCurrentUserID() && is_array($data)) {
            $prodution_rep = [];
            foreach ($data as $key => $value) {
                if (!Util::startsWith($key, '_')) {
                    $prodution_rep[$key] = $value;
                }
            }
            unset($data);

            return static::rep(true, 'success', $prodution_rep);
        }

        return static::rep(true, 'success', $data);
    }
}
