<?php

class Phr_setting_business_type
{

  public static function setSettingBusinessTypeListDetail($code, $return, $options = [])
  {
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
  | Group | Setting Business Type
  | Description | 1.ดึงข้อมูลลิส 'Setting Business Type'
  | $code | โค้ดโมดูล
  | $where | [name(ชื่อเว็บ)]
  | $options | [not_view(true|เอาแค่ข้อมูลหลักไป edit*เร็ว), selected_fields(String หรือ Array ของคอลัมภ์ที่ต้องการ), total_count(true|พร้อมจำนวนข้อมูลทั้งหมด ตามการกรองไม่แบ่งหน้า), page_no(1|เลขหน้า 0 คือส่งทั้งหมด), page_size(10|จำนวนข้อมูลต่อหน้า), sort_col(เรียงตามชื่อคอลัมภ์นี้), sort_by('ASC'|'DESC')]
   */
  public static function selectSettingBusinessType($code, $where = [], $options = [])
  {
    $return = []; // list
    $sql_where = [];

    $selected_fields = Amst::getSelectedFields($options);
    $return = Amst::selectNoKey($code . '_setting_business_type', $selected_fields, $sql_where, $options);
    return static::setSettingBusinessTypeListDetail($code, $return, $options);
  }

  public static function setSettingBusinessTypeDetail($code, $record, $options = [])
  {
    if (!isset($record['id'])) {
      return $record;
    }

    // Do Single Works...
    // $record['other_key'] = 'other_value';

    return $record;
  }

  /**
  | Group | Setting Business Type
  | Description | 2.ดึงข้อมูล 'Setting Business Type' ด้วยไอดี
  | $code | โค้ดโมดูล
  | $id | ไอดีรายการ
  | $options | [not_view(true|เอาแค่ข้อมูลหลักไป edit*เร็ว), selected_fields(String หรือ Array ของคอลัมภ์ที่ต้องการ)]
   */
  public static function getSettingBusinessTypeByID($code, $id, $options = [])
  {
    $return = null;

    $selected_fields = Amst::getSelectedFields($options, ['id', 'system_delete']);
    $model = Amst::getByID($code . '_setting_business_type', $selected_fields, $id, $options);
    if (isset($model['id'])) {
      $return = static::setSettingBusinessTypeDetail($code, $model, $options);
    }
    return $return;
  }

  /**
  | Group | Setting Business Type
  | Description | 3.เพิ่ม 'Setting Business Type'
  | $code | โค้ดโมดูล
  | $data | [name]
   */
  public static function addNewSettingBusinessType($code, $data = [])
  {
    $rep = [
      'insert_id' => false
    ];

    $i_data = $data;

    $api = Amst::insert($code . '_setting_business_type', $i_data);
    if (isset($api['response_status']) && ($api['response_status'] == true)) {
      $insert_id = $api['response_data']['insert_id'];
    }
    return $api;
  }

  /**
  | Group | Setting Business Type
  | Description | 4.อัพเดท 'Setting Business Type'
  | $code | โค้ดโมดูล
  | $id | ไอดีรายการ
  | $data | [name]
   */
  public static function updateSettingBusinessType($code, $id, $data = [])
  {
    $rep = [
      'update_result' => false
    ];

    $model = Amst::getByID($code . '_setting_business_type', ['id'], $id);
    if (!isset($model['id'])) {
      return Amst::repError('E0005', 'tbl_setting_business_type not found.');
    }

    $u_data = $data;

    $api = Amst::updateByID($code . '_setting_business_type', $u_data, $id);
    if (isset($api['response_status']) && ($api['response_status'] == true)) {
    }
    return $api;
  }

  /**
  | Group | Setting Business Type
  | Description | 5.ลบ 'Setting Business Type'
  | $code | โค้ดโมดูล
  | $id | ไอดีรายการ
   */
  public static function deleteSettingBusinessType($code, $id)
  {
    $rep = [
      'delete_result' => false
    ];

    $api = static::_checkDeleteSettingBusinessType($code, $id);
    if (isset($api['response_status']) && ($api['response_status'] == true)) {
      $rep['delete_result'] = Amst::deleteByID($code . '_setting_business_type', $id);
      return Amst::repSuccess($rep);
    }
    return $api; // ERROR SURE
  }

  private static function _checkDeleteSettingBusinessType($code, $id)
  {
    $rep = [
      'id' => $id
    ];

    // [Model_Exist]
    $model = Amst::getByID($code . '_setting_business_type', ['id'], $id);
    if (!isset($model['id'])) {
      return Amst::repError('E0005', 'tbl_setting_business_type not found.');
    }

    return Amst::repSuccess($rep);
  }
}
