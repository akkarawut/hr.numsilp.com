<?php
class Working_shift {
  private static $conf = [
    'tb_working_shift'  => 'sys_working_shift',
    'lbl_working_shift' => 'รูปแบบเวลาทำงาน'
  ];

  public static function setWorkingShiftListDetail($return, $options = []) {
    // [ExtractList]
    $selected_records = isset($return['list']) ? $return['list'] : $return;

    // Do Works...
    foreach ($selected_records as $key => $row) {
      // Do Each Works...
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
  | Group | รูปแบบเวลาทำงาน
  | Description | 1.ดึงข้อมูลลิส 'รูปแบบเวลาทำงาน'
  | $where | [รองรับauto_search(ตาราง sys_working_shift)]
  | $options | [not_view(true // เอาแค่ข้อมูลหลักไป edit *เร็ว), selected_fields('*' // คอลัมภ์ที่ต้องการ), total_count(true // พร้อมจำนวนข้อมูลทั้งหมด ตามการกรอง(ไม่แบ่งหน้า)), page_no(0-N // เลขหน้า 0 คือส่งทั้งหมด), page_size('*' // จำนวนข้อมูลต่อหน้า), selected_fields('*' // คอลัมภ์ที่ต้องการ), sort(['column_name' => 'ASC/DESC'] // เรียงคอลัมภ์)]
   */
  public static function selectWorkingShift($where = [], $options = []) {
    $return = []; // list

    $sql_where = Amst::mergeWhere($where); // Auto Where
    $table_name = !Util::isHasOptions('not_view', $options) ? 'sys_working_shift' : static::$conf['tb_working_shift'];
    $selected_fields = Amst::getSelectedFields($options);
    return Amst::selectNoKey($table_name, $selected_fields, $sql_where, $options);
  }

  public static function setWorkingShiftDetail($record, $options = []) {
    if (!isset($record['id'])) {
      return $record;
    }

    // Do Single Works...

    return $record;
  }

  /**
  | Group | รูปแบบเวลาทำงาน
  | Description | 2.ดึงข้อมูล 'รูปแบบเวลาทำงาน' ด้วยไอดี
  | $id | ไอดีรายการ
  | $options | [not_view(true // เอาแค่ข้อมูลหลักไป edit *เร็ว), selected_fields('*' // คอลัมภ์ที่ต้องการ)]
   */
  public static function getWorkingShiftByID($id, $options = []) {
    $return = null;

    $table_name = !Util::isHasOptions('not_view', $options) ? 'sys_working_shift' : static::$conf['tb_working_shift'];
    $selected_fields = Amst::getSelectedFields($options, ['id', 'system_delete']);

    if ($id) {
      $model = Amst::getByID($table_name, $selected_fields, $id, $options);
      if (isset($model['id'])) {
        $return = static::setWorkingShiftDetail($model, $options);
      }
    }
    return $return;
  }

  /**
  | Group | รูปแบบเวลาทำงาน
  | Description | 3.เพิ่ม 'รูปแบบเวลาทำงาน'
  | $data | [name(name), description(description)]
   */
  public static function addNewWorkingShift($data) {
    $rep = [
      'insert_id' => false
    ];

    return Amst::insert(static::$conf['tb_working_shift'], $data);
  }

  /**
  | Group | รูปแบบเวลาทำงาน
  | Description | 4.อัพเดท 'รูปแบบเวลาทำงาน'
  | $data | [name(name), description(description)]
   */
  public static function updateWorkingShift($id, $data) {
    $rep = [
      'update_result' => false
    ];

    $model = Amst::getByID(static::$conf['tb_working_shift'], ['id'], $id);
    if (!isset($model['id'])) {
      return Amst::repError('E0005', static::$conf['lbl_working_shift']);
    }

    return Amst::updateByID(static::$conf['tb_working_shift'], $data, $id);
  }

  /**
  | Group | รูปแบบเวลาทำงาน
  | Description | 5.ลบ 'รูปแบบเวลาทำงาน'
  | $id | ไอดีรายการ
   */
  public static function deleteWorkingShift($id) {
    $rep = [
      'delete_result' => false
    ];

    $api = static::_checkDeleteWorkingShift($id);
    if (isset($api['response_status']) && ($api['response_status'] == true)) {
      $rep['delete_result'] = Amst::deleteByID(static::$conf['tb_working_shift'], $id);
      return Amst::repSuccess($rep);
    }
    return $api; // ERROR SURE
  }

  private static function _checkDeleteWorkingShift($id) {
    $rep = [
      'id' => $id
    ];

    // [Model_Exist]
    $model = Amst::getByID(static::$conf['tb_working_shift'], ['id'], $id);
    if (!isset($model['id'])) {
      return Amst::repError('E0005', static::$conf['lbl_working_shift']);
    }

    // [TODO] Using

    return Amst::repSuccess($rep);
  }
}
?>