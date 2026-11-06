<?php

class Module_Section {

  public static function setSectionListDetail($return, $options = []) {
    // [ExtractList]
    $selected_records = isset($return['list']) ? $return['list'] : $return;

    // Do Each Works...
    foreach ($selected_records as $key => $record) {
      // $selected_records[$key]['other_key'] = 'other_value';
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
  | Group | MODULE SECTION
  | Description | 1.ดึงข้อมูลลิส 'MODULE SECTION'
  | $where | [function_id, page, name, is_show_section_name]
  | $options | [not_view(true|เอาแค่ข้อมูลหลักไป edit*เร็ว), selected_fields(String หรือ Array ของคอลัมภ์ที่ต้องการ), total_count(true|พร้อมจำนวนข้อมูลทั้งหมด ตามการกรองไม่แบ่งหน้า), page_no(1|เลขหน้า 0 คือส่งทั้งหมด), page_size(10|จำนวนข้อมูลต่อหน้า), sort_col(เรียงตามชื่อคอลัมภ์นี้), sort_by('ASC'|'DESC')]
   */
  public static function selectSection($where = [], $options = []) {
    $return    = []; // list
    $sql_where = []; // list

    $selected_fields = Amst::getSelectedFields($options);
    $return          = Amst::selectNoKey('sys_function_section', $selected_fields, $sql_where, $options);
    return static::setSectionListDetail($return, $options);
  }

  public static function setSectionDetail($record, $options = []) {
    if (!isset($record['id'])) {
      return $record;
    }

    // Do Single Works...
    // $record['other_key'] = 'other_value';

    return $record;
  }

  /**
  | Group | MODULE SECTION
  | Description | 2.ดึงข้อมูล 'MODULE SECTION' ด้วยไอดี
  | $id | ไอดีรายการ
  | $options | [not_view(true|เอาแค่ข้อมูลหลักไป edit*เร็ว), selected_fields(String หรือ Array ของคอลัมภ์ที่ต้องการ)]
   */
  public static function getSectionByID($id, $options = []) {
    $return = null;

    $selected_fields = Amst::getSelectedFields($options, ['id', 'system_delete']);
    $model           = Amst::getByID('sys_function_section', $selected_fields, $id, $options);
    if (isset($model['id'])) {
      $return = static::setSectionDetail($model, $options);
    }
    return $return;
  }

  /**
  | Group | MODULE SECTION
  | Description | 3.เพิ่ม 'MODULE SECTION'
  | $data | [function_id, page, name, is_show_section_name]
   */
  public static function addNewSection($data = []) {
    $rep = [
      'insert_id' => false
    ];

    $where = [
      'AND' => [
        'function_id'   => $data['function_id'],
        'system_delete' => 0
      ]
    ];
    $count           = Amst::count('sys_function_section', $where);
    $count           = $count + 1;
    $data['sort_no'] = $count;
    $api             = Amst::insert('sys_function_section', $data);
    if (isset($api['response_status']) && ($api['response_status'] == true)) {
      $insert_id = $api['response_data']['insert_id'];
    }
    return $api;
  }

  /**
  | Group | MODULE SECTION
  | Description | 4.อัพเดท 'MODULE SECTION'
  | $id | ไอดีรายการ
  | $data | [function_id, page, name, is_show_section_name]
   */
  public static function updateSection($id, $data = []) {
    $rep = [
      'update_result' => false
    ];

    $model = Amst::getByID('sys_function_section', ['id'], $id);
    if (!isset($model['id'])) {
      return Amst::repError('E0005', 'tablesys_function_section');
    }

    $api = Amst::updateByID('sys_function_section', $data, $id);
    if (isset($api['response_status']) && ($api['response_status'] == true)) {
    }
    return $api;
  }

  /**
  | Group | MODULE SECTION
  | Description | 5.ลบ 'MODULE SECTION'
  | $id | ไอดีรายการ
   */
  public static function deleteSection($id) {
    $rep = [
      'delete_result' => false
    ];

    $api = static::_checkDeleteSection($id);
    if (isset($api['response_status']) && ($api['response_status'] == true)) {
      $rep['delete_result'] = Amst::deleteByID('sys_function_section', $id);
      return Amst::repSuccess($rep);
    }
    return $api; // ERROR SURE
  }

  private static function _checkDeleteSection($id) {
    $rep = [
      'id' => $id
    ];

    // [Model_Exist]
    $model = Amst::getByID('sys_function_section', ['id'], $id);
    if (!isset($model['id'])) {
      return Amst::repError('E0005', 'tablesys_function_section');
    }

    return Amst::repSuccess($rep);
  }

  public static function setSectionFieldListDetail($return, $options = []) {
    // [ExtractList]
    $selected_records = isset($return['list']) ? $return['list'] : $return;

    // Do Each Works...
    foreach ($selected_records as $key => $record) {
      // $selected_records[$key]['other_key'] = 'other_value';
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
  | Group | SECTION FIELD
  | Description | 1.ดึงข้อมูลลิส 'SECTION FIELD'
  | $where | [function_id, section_id, sys_user_columns_id, is_lock, is_edit, required, is_show]
  | $options | [not_view(true|เอาแค่ข้อมูลหลักไป edit*เร็ว), selected_fields(String หรือ Array ของคอลัมภ์ที่ต้องการ), total_count(true|พร้อมจำนวนข้อมูลทั้งหมด ตามการกรองไม่แบ่งหน้า), page_no(1|เลขหน้า 0 คือส่งทั้งหมด), page_size(10|จำนวนข้อมูลต่อหน้า), sort_col(เรียงตามชื่อคอลัมภ์นี้), sort_by('ASC'|'DESC')]
   */
  public static function selectSectionField($where = [], $options = []) {
    $return    = []; // list
    $sql_where = []; // list

    $selected_fields = Amst::getSelectedFields($options);
    $return          = Amst::selectNoKey('sys_function_field', $selected_fields, $sql_where, $options);
    return static::setSectionFieldListDetail($return, $options);
  }

  public static function setSectionFieldDetail($record, $options = []) {
    if (!isset($record['id'])) {
      return $record;
    }

    // Do Single Works...
    // $record['other_key'] = 'other_value';

    return $record;
  }

  /**
  | Group | SECTION FIELD
  | Description | 2.ดึงข้อมูล 'SECTION FIELD' ด้วยไอดี
  | $id | ไอดีรายการ
  | $options | [not_view(true|เอาแค่ข้อมูลหลักไป edit*เร็ว), selected_fields(String หรือ Array ของคอลัมภ์ที่ต้องการ)]
   */
  public static function getSectionFieldByID($id, $options = []) {
    $return = null;

    $selected_fields = Amst::getSelectedFields($options, ['id', 'system_delete']);
    $model           = Amst::getByID('sys_function_field', $selected_fields, $id, $options);
    if (isset($model['id'])) {
      $return = static::setSectionFieldDetail($model, $options);
    }
    return $return;
  }

  /**
  | Group | SECTION FIELD
  | Description | 3.เพิ่ม 'SECTION FIELD'
  | $data | [function_id, section_id, sys_user_columns_id, is_lock, is_edit, required, is_show]
   */
  public static function addNewSectionField($data = []) {
    $rep = [
      'insert_id' => false
    ];

    $api = Amst::insert('sys_function_field', $data);
    if (isset($api['response_status']) && ($api['response_status'] == true)) {
      $insert_id = $api['response_data']['insert_id'];
    }
    return $api;
  }

  /**
  | Group | SECTION FIELD
  | Description | 4.อัพเดท 'SECTION FIELD'
  | $id | ไอดีรายการ
  | $data | [function_id, section_id, sys_user_columns_id, is_lock, is_edit, required, is_show]
   */
  public static function updateSectionField($id, $data = []) {
    $rep = [
      'update_result' => false
    ];

    $model = Amst::getByID('sys_function_field', ['id'], $id);
    if (!isset($model['id'])) {
      return Amst::repError('E0005', 'tablesys_function_field');
    }

    $api = Amst::updateByID('sys_function_field', $data, $id);
    if (isset($api['response_status']) && ($api['response_status'] == true)) {
    }
    return $api;
  }

  /**
  | Group | SECTION FIELD
  | Description | 5.ลบ 'SECTION FIELD'
  | $id | ไอดีรายการ
   */
  public static function deleteSectionField($id) {
    $rep = [
      'delete_result' => false
    ];

    $api = static::_checkDeleteSectionField($id);
    if (isset($api['response_status']) && ($api['response_status'] == true)) {
      $rep['delete_result'] = Amst::deleteByID('sys_function_field', $id);
      return Amst::repSuccess($rep);
    }
    return $api; // ERROR SURE
  }

  private static function _checkDeleteSectionField($id) {
    $rep = [
      'id' => $id
    ];

    // [Model_Exist]
    $model = Amst::getByID('sys_function_field', ['id'], $id);
    if (!isset($model['id'])) {
      return Amst::repError('E0005', 'tablesys_function_field');
    }

    return Amst::repSuccess($rep);
  }

  /**
  | Group | SWAP INDEX
  | Description | 'SWAP INDEX'
  | $id | ไอดีรายการ
  | $to_id | ไอดีรายการ
   */
  public static function swapSection($id, $to_id) {
    $section = Amst::getByID('sys_function_section', '*', $id);
    if (!isset($section['id'])) {
      return Amst::repError('E0000', 'Section not found (1)');
    }
    $to_section = Amst::getByID('sys_function_section', '*', $to_id);
    if (!isset($to_section['id'])) {
      return Amst::repError('E0000', 'Section not found (2)');
    }

    // swap sort_no
    $u_data = [
      'sort_no' => $to_section['sort_no']
    ];
    Amst::updateByID('sys_function_section', $u_data, $id);

    $u_data = [
      'sort_no' => $section['sort_no']
    ];
    return Amst::updateByID('sys_function_section', $u_data, $to_id);
  }

  /**
  | Group | SWAP INDEX
  | Description | 'SWAP INDEX'
  | $id | ไอดีรายการ
  | $to_id | ไอดีรายการ
   */
  public static function swapField($id, $to_id) {
    $field = Amst::getByID('sys_function_field', '*', $id);
    if (!isset($field['id'])) {
      return Amst::repError('E0000', 'Field not found (1)');
    }
    $to_field = Amst::getByID('sys_function_field', '*', $to_id);
    if (!isset($to_field['id'])) {
      return Amst::repError('E0000', 'Field not found (2)');
    }

    // swap sort_no
    $u_data = [
      'sort_no' => $to_field['sort_no']
    ];
    Amst::updateByID('sys_function_field', $u_data, $id);

    $u_data = [
      'sort_no' => $field['sort_no']
    ];
    return Amst::updateByID('sys_function_field', $u_data, $to_id);
  }
}
