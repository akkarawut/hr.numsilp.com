<?php

class Phr_setting_deduct_money
{

  public static function setSettingDeductMoneyListDetail($code, $return, $options = [])
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
  | Group | Setting Deduct Money
  | Description | 1.ดึงข้อมูลลิส 'Setting Deduct Money'
  | $code | โค้ดโมดูล
  | $where | [name(ชื่อเว็บ)]
  | $options | [not_view(true|เอาแค่ข้อมูลหลักไป edit*เร็ว), selected_fields(String หรือ Array ของคอลัมภ์ที่ต้องการ), total_count(true|พร้อมจำนวนข้อมูลทั้งหมด ตามการกรองไม่แบ่งหน้า), page_no(1|เลขหน้า 0 คือส่งทั้งหมด), page_size(10|จำนวนข้อมูลต่อหน้า), sort_col(เรียงตามชื่อคอลัมภ์นี้), sort_by('ASC'|'DESC')]
   */
  public static function selectSettingDeductMoney($code, $where = [], $options = [])
  {
    $return = []; // list
    $sql_where = [];

    $sql_where['ORDER']['sort_order_id'] = 'DESC';

    $selected_fields = Amst::getSelectedFields($options);
    $return = Amst::selectNoKey($code . '_setting_deduct_money', $selected_fields, $sql_where, $options);
    return static::setSettingDeductMoneyListDetail($code, $return, $options);
  }

  /**
  | Group | Setting Deduct Money
  | Description | 1.count 'Setting Deduct Money' รวมกับ OT
  | $code | โค้ดโมดูล
  | $where | [name(ชื่อเว็บ)]
  | $options | [not_view(true|เอาแค่ข้อมูลหลักไป edit*เร็ว), selected_fields(String หรือ Array ของคอลัมภ์ที่ต้องการ), total_count(true|พร้อมจำนวนข้อมูลทั้งหมด ตามการกรองไม่แบ่งหน้า), page_no(1|เลขหน้า 0 คือส่งทั้งหมด), page_size(10|จำนวนข้อมูลต่อหน้า), sort_col(เรียงตามชื่อคอลัมภ์นี้), sort_by('ASC'|'DESC')]
   */
  public static function countSettingDeductMoney($code, $where = [], $options = [])
  {
    $return = [
      'count_deduct' => 0,
      'count_ot'     => 0,
    ]; // list
    $sql_where = [];

    $count_deduct = Amst::count($code . '_setting_deduct_money', []);
    $return['count_deduct'] = $count_deduct;

    $count_ot = Amst::count($code . '_setting_ot', []);
    $return['count_ot'] = $count_ot;
    return $return;
  }

  public static function setSettingDeductMoneyDetail($code, $record, $options = [])
  {
    if (!isset($record['id'])) {
      return $record;
    }

    // Do Single Works...
    // $record['other_key'] = 'other_value';
 
    $key_decode = [
      'role_use'
    ];

    foreach ($key_decode as $key => $value) {
      $record[$value] = json_decode($record[$value], true);
    }

    $update_by_fullname = 'SYSTEM';
    $update_by_info = Amst::getByID('sys_user', ['id', 'full_name'], $record['update_user_id']);
    if (isset($update_by_info['full_name'])) {
      $update_by_fullname = $update_by_info['full_name'];
    }
    $record['update_by_fullname'] = $update_by_fullname;

    return $record;
  }

  /**
  | Group | Setting Deduct Money
  | Description | 2.ดึงข้อมูล 'Setting Deduct Money' ด้วยไอดี
  | $code | โค้ดโมดูล
  | $id | ไอดีรายการ
  | $options | [not_view(true|เอาแค่ข้อมูลหลักไป edit*เร็ว), selected_fields(String หรือ Array ของคอลัมภ์ที่ต้องการ)]
   */
  public static function getSettingDeductMoneyByID($code, $id, $options = [])
  {
    $return = null;

    $selected_fields = Amst::getSelectedFields($options, ['id', 'system_delete']);
    $model = Amst::getByID($code . '_setting_deduct_money', $selected_fields, $id, $options);
    if (isset($model['id'])) {
      $return = static::setSettingDeductMoneyDetail($code, $model, $options);
    }
    return $return;
  }

  /**
  | Group | Setting Deduct Money
  | Description | 3.เพิ่ม 'Setting Deduct Money'
  | $code | โค้ดโมดูล
  | $data | [    
    title,
    type(late/very_late/missing),
    amount,
    price,
    type_calculate(FIX month ไปก่อน),
    description,
    is_use,
    role_use
  ]
   */
  public static function addNewSettingDeductMoney($code, $data = [])
  {
    // role_use
    $ex_role_use = [
      1,
      2
    ];

    $rep = [
      'insert_id' => false
    ];

    $count_user = isset($data['role_use']) && $data['role_use'] ? count($data['role_use']) : 0; // ไม่ติ๊ก role เลยจะ TypeError บน PHP 8

    $i_data                   = $data;
    $i_data['type_calculate'] = 'month'; // FIX
    $i_data['count_user']     = $count_user;

    $key_encode = [
      'role_use'
    ];

    foreach ($key_encode as $key => $value) {
      $temp_data  = [];
      if (isset($i_data[$value]) && $i_data[$value]) {
        $check_data = $i_data[$value];
        if (is_array($check_data)) {
          $temp = [];
          foreach ($check_data as $key_check => $value_check) {
            if ($value_check) {
              $temp[] = $value_check;
            }
          }
          $temp_data = $temp;
        }
      }
      $i_data[$value] = json_encode($temp_data);
    }

    $key_check_is = [
      'is_use'
    ];
    foreach ($key_check_is as $key => $value) {
      if (!isset($i_data[$value])) {
        $i_data[$value] = 0;
      }
    }

    $api = Amst::insert($code . '_setting_deduct_money', $i_data);
    if (isset($api['response_status']) && ($api['response_status'] == true)) {
      $insert_id = $api['response_data']['insert_id'];
    }
    return $api;
  }

  /**
  | Group | Setting Deduct Money
  | Description | 4.อัพเดท 'Setting Deduct Money'
  | $code | โค้ดโมดูล
  | $id | ไอดีรายการ
  | $data | [    
    title,
    type(late/very_late/missing),
    amount,
    price,
    type_calculate(FIX month ไปก่อน),
    description,
    is_use,
    role_use
  ]
   */
  public static function updateSettingDeductMoney($code, $id, $data = [])
  {
    // role_use
    $ex_role_use = [
      1,
      2
    ];

    $rep = [
      'update_result' => false
    ];

    $model = Amst::getByID($code . '_setting_deduct_money', ['id'], $id);
    if (!isset($model['id'])) {
      return Amst::repError('E0005', 'tbl_setting_deduct_money not found.');
    }
    
    $count_user = isset($data['role_use']) && $data['role_use'] ? count($data['role_use']) : 0; // ไม่ติ๊ก role เลยจะ TypeError บน PHP 8

    $u_data                   = $data;
    $u_data['type_calculate'] = 'month'; // FIX
    $u_data['count_user']     = $count_user;

    $key_encode = [
      'role_use'
    ];

    foreach ($key_encode as $key => $value) {
      $temp_data  = [];
      if (isset($u_data[$value]) && $u_data[$value]) {
        $check_data = $u_data[$value];
        if (is_array($check_data)) {
          $temp = [];
          foreach ($check_data as $key_check => $value_check) {
            if ($value_check) {
              $temp[] = $value_check;
            }
          }
          $temp_data = $temp;
        }
      }
      $u_data[$value] = json_encode($temp_data);
    }

    $key_check_is = [
      'is_use'
    ];
    foreach ($key_check_is as $key => $value) {
      if (!isset($u_data[$value])) {
        $u_data[$value] = 0;
      }
    }

    $api = Amst::updateByID($code . '_setting_deduct_money', $u_data, $id);
    if (isset($api['response_status']) && ($api['response_status'] == true)) {
    }
    return $api;
  }

  /**
  | Group | Setting Deduct Money
  | Description | 5.ลบ 'Setting Deduct Money'
  | $code | โค้ดโมดูล
  | $id | ไอดีรายการ
   */
  public static function deleteSettingDeductMoney($code, $id)
  {
    $rep = [
      'delete_result' => false
    ];

    $api = static::_checkDeleteSettingDeductMoney($code, $id);
    if (isset($api['response_status']) && ($api['response_status'] == true)) {
      $rep['delete_result'] = Amst::deleteByID($code . '_setting_deduct_money', $id);
      return Amst::repSuccess($rep);
    }
    return $api; // ERROR SURE
  }

  private static function _checkDeleteSettingDeductMoney($code, $id)
  {
    $rep = [
      'id' => $id
    ];

    // [Model_Exist]
    $model = Amst::getByID($code . '_setting_deduct_money', ['id'], $id);
    if (!isset($model['id'])) {
      return Amst::repError('E0005', 'tbl_setting_deduct_money not found.');
    }

    return Amst::repSuccess($rep);
  }
}
