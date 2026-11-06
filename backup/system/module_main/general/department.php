<?php
class Department {
  private static $conf = [
    'description'    => 'จัดการแผนกผู้ใช้งาน',
    'tb_department'  => 'sys_department',
    'lbl_department' => 'ฝ่าย/แผนก'
  ];

  public static function setDepartmentListDetail($return, $options = []) {
    // [ExtractList]
    $selected_records = isset($return['list']) ? $return['list'] : $return;

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
  | Group | ฝ่าย/แผนก
  | Description | 1.ดึงข้อมูลลิส 'ฝ่าย/แผนก'
  | $where | [รองรับauto_search(ตาราง sys_department)]
  | $options | [not_view(true // เอาแค่ข้อมูลหลักไป edit *เร็ว), selected_fields('*' // คอลัมภ์ที่ต้องการ), total_count(true // พร้อมจำนวนข้อมูลทั้งหมด ตามการกรอง(ไม่แบ่งหน้า)), page_no(0-N // เลขหน้า 0 คือส่งทั้งหมด), page_size('*' // จำนวนข้อมูลต่อหน้า), selected_fields('*' // คอลัมภ์ที่ต้องการ), sort(['column_name' => 'ASC/DESC'] // เรียงคอลัมภ์)]
   */
  public static function selectDepartment($where = [], $options = []) {
    $return = []; // list

    $sql_where = Amst::mergeWhere($where); // Auto Where
    $table_name = !Util::isHasOptions('not_view', $options) ? 'sys_department' : static::$conf['tb_department'];
    $selected_fields = Amst::getSelectedFields($options);
    return Amst::selectNoKey($table_name, $selected_fields, $sql_where, $options);
  }

  public static function setDepartmentDetail($record, $options = []) {
    if (!isset($record['id'])) {
      return $record;
    }

    // Do Single Works...

    return $record;
  }

  /**
  | Group | ฝ่าย/แผนก
  | Description | 2.ดึงข้อมูล 'ฝ่าย/แผนก' ด้วยไอดี
  | $id | ไอดีรายการ
  | $options | [not_view(true // เอาแค่ข้อมูลหลักไป edit *เร็ว), selected_fields('*' // คอลัมภ์ที่ต้องการ)]
   */
  public static function getDepartmentByID($id, $options = []) {
    $return = null;

    $table_name = !Util::isHasOptions('not_view', $options) ? 'sys_department' : static::$conf['tb_department'];
    $selected_fields = Amst::getSelectedFields($options, ['id', 'system_delete']);

    if ($id) {
      $model = Amst::getByID($table_name, $selected_fields, $id, $options);
      if (isset($model['id'])) {
        $return = static::setDepartmentDetail($model, $options);
      }
    }
    return $return;
  }

  /**
  | Group | ฝ่าย/แผนก
  | Description | 3.เพิ่ม 'ฝ่าย/แผนก'
  | $data | [name(name), description(description)]
   */
  public static function addNewDepartment($data) {
    $rep = [
      'insert_id' => false
    ];

    return Amst::insert(static::$conf['tb_department'], $data);
  }

  /**
  | Group | ฝ่าย/แผนก
  | Description | 4.อัพเดท 'ฝ่าย/แผนก'
  | $data | [name(name), description(description)]
   */
  public static function updateDepartment($id, $data) {
    $rep = [
      'update_result' => false
    ];

    $model = Amst::getByID(static::$conf['tb_department'], ['id'], $id);
    if (!isset($model['id'])) {
      return Amst::repError('E0005', static::$conf['lbl_department']);
    }

    return Amst::updateByID(static::$conf['tb_department'], $data, $id);
  }

  /**
  | Group | ฝ่าย/แผนก
  | Description | 5.ลบ 'ฝ่าย/แผนก'
  | $id | ไอดีรายการ
   */
  public static function deleteDepartment($id) {
    $rep = [
      'delete_result' => false
    ];

    $api = static::_checkDeleteDepartment($id);
    if (isset($api['response_status']) && ($api['response_status'] == true)) {
      $rep['delete_result'] = Amst::deleteByID(static::$conf['tb_department'], $id);
      return Amst::repSuccess($rep);
    }
    return $api; // ERROR SURE
  }

  private static function _checkDeleteDepartment($id) {
    $rep = [
      'id' => $id
    ];

    // [Model_Exist]
    $model = Amst::getByID(static::$conf['tb_department'], ['id'], $id);
    if (!isset($model['id'])) {
      return Amst::repError('E0005', static::$conf['lbl_department']);
    }

    // Do Some Works...

    return Amst::repSuccess($rep);
  }
}
?>