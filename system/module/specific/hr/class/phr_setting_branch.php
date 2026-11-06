<?php

class Phr_setting_branch
{

  public static function setSettingBranchListDetail($code, $return, $options = [])
  {
    // [ExtractList]
    $selected_records = isset($return['list']) ? $return['list'] : $return;

    $key_decode = [
      'tel', 'email'
    ];

    // Do Each Works...
    foreach ($selected_records as $key => $record) {
      // $selected_records[$key]['other_key'] = 'other_value';
  
      foreach ($key_decode as $key_check => $key_value) {
        $selected_records[$key][$key_value] = json_decode($record[$key_value], true);
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
  | Group | Setting Branch
  | Description | 1.ดึงข้อมูลลิส 'Setting Branch'
  | $code | โค้ดโมดูล
  | $where | [not_id]
  | $options | [is_dropdown(0/1), not_view(true|เอาแค่ข้อมูลหลักไป edit*เร็ว), selected_fields(String หรือ Array ของคอลัมภ์ที่ต้องการ), total_count(true|พร้อมจำนวนข้อมูลทั้งหมด ตามการกรองไม่แบ่งหน้า), page_no(1|เลขหน้า 0 คือส่งทั้งหมด), page_size(10|จำนวนข้อมูลต่อหน้า), sort_col(เรียงตามชื่อคอลัมภ์นี้), sort_by('ASC'|'DESC')]
   */
  public static function selectSettingBranch($code, $where = [], $options = [])
  {
    $return = []; // list
    $sql_where = [];

    if (isset($where['not_id']) && $where['not_id'] && $where['not_id'] != 'all') {
      $sql_where['AND']['id[!]'] = $where['not_id'];
    }

    if (!isset($options['is_dropdown'])) {
      $sql_where['AND']['type_branch'] = 'branch';

      $sql_where['ORDER']['sort_order_id'] = 'DESC';
    } else {
      $sql_where['ORDER']['type_branch'] = 'DESC';
      $sql_where['ORDER']['id']          = 'ASC';
    }

    $selected_fields = Amst::getSelectedFields($options);
    $return = Amst::selectNoKey($code . '_setting_branch', $selected_fields, $sql_where, $options);
    return static::setSettingBranchListDetail($code, $return, $options);
  }

  /**
  | Group | Setting Branch
  | Description | 1.count 'Setting Branch'
  | $code | โค้ดโมดูล
  | $where | [name(ชื่อเว็บ)]
  | $options | [not_view(true|เอาแค่ข้อมูลหลักไป edit*เร็ว), selected_fields(String หรือ Array ของคอลัมภ์ที่ต้องการ), total_count(true|พร้อมจำนวนข้อมูลทั้งหมด ตามการกรองไม่แบ่งหน้า), page_no(1|เลขหน้า 0 คือส่งทั้งหมด), page_size(10|จำนวนข้อมูลต่อหน้า), sort_col(เรียงตามชื่อคอลัมภ์นี้), sort_by('ASC'|'DESC')]
   */
  public static function countSettingBranch($code, $where = [], $options = [])
  {
    $return = []; // list
    $sql_where = [];

    $sql_where['AND']['type_branch'] = 'branch';

    $return = Amst::count($code . '_setting_branch', $sql_where);
    return $return;
  }

  public static function setSettingBranchDetail($code, $record, $options = [])
  {
    if (!isset($record['id'])) {
      return $record;
    }

    // Do Single Works...
    // $record['other_key'] = 'other_value';

    $key_decode = [
      'tel', 'email'
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
  | Group | Setting Branch
  | Description | 2.ดึงข้อมูล 'Setting Branch' ด้วยไอดี
  | $code | โค้ดโมดูล
  | $id | ไอดีรายการ
  | $options | [not_view(true|เอาแค่ข้อมูลหลักไป edit*เร็ว), selected_fields(String หรือ Array ของคอลัมภ์ที่ต้องการ)]
   */
  public static function getSettingBranchByID($code, $id, $options = [])
  {
    $return = null;

    $selected_fields = Amst::getSelectedFields($options, ['id', 'system_delete']);
    $model = Amst::getByID($code . '_setting_branch', $selected_fields, $id, $options);
    if (isset($model['id'])) {
      $return = static::setSettingBranchDetail($code, $model, $options);
    }
    return $return;
  }

  /**
  | Group | Setting Branch
  | Description | 3.เพิ่ม 'Setting Branch'
  | $code | โค้ดโมดูล
  | $data | [
    name,
    location,
    type_branch(FIX branch),

    branch_code,
    type_business_id,
    type_business_name,

    contact_person,
    tax_number,
    email(array list),
    tel(array list),
    fax_number
  ]
   */
  public static function addNewSettingBranch($code, $data = [])
  {
    // email
    $ex_email = [
      'ex_email_01@email.com',
      'ex_email_02@email.com'
    ];

    // tel
    $ex_tel = [
      '0123456789',
      '0123456788'
    ];

    $rep = [
      'insert_id' => false
    ];

    $key_encode = [
      'email', 'tel'
    ];

    $i_data                  = $data;
    $i_data['type_branch']   = 'branch';

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

    $api = Amst::insert($code . '_setting_branch', $i_data);
    if (isset($api['response_status']) && ($api['response_status'] == true)) {
      $insert_id = $api['response_data']['insert_id'];
    }
    return $api;
  }

  /**
  | Group | Setting Branch
  | Description | 4.อัพเดท 'Setting Branch'
  | $code | โค้ดโมดูล
  | $id | ไอดีรายการ
  | $data | [    
    name,
    location,
    type_branch(FIX branch),

    branch_code,
    type_business_id,
    type_business_name,

    contact_person,
    tax_number,
    email(array list),
    tel(array list),
    fax_number
  ]
   */
  public static function updateSettingBranch($code, $id, $data = [])
  {
    // email
    $ex_email = [
      'ex_email_01@email.com',
      'ex_email_02@email.com'
    ];

    // tel
    $ex_tel = [
      '0123456789',
      '0123456788'
    ];

    $rep = [
      'update_result' => false
    ];

    $model = Amst::getByID($code . '_setting_branch', ['id'], $id);
    if (!isset($model['id'])) {
      return Amst::repError('E0005', 'tbl_setting_branch not found.');
    }

    $key_encode = [
      'email', 'tel'
    ];

    $u_data                  = $data;
    $u_data['type_branch']   = 'branch';

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

    $api = Amst::updateByID($code . '_setting_branch', $u_data, $id);
    if (isset($api['response_status']) && ($api['response_status'] == true)) {
    }
    return $api;
  }

  /**
  | Group | Setting Branch
  | Description | 5.ลบ 'Setting Branch'
  | $code | โค้ดโมดูล
  | $id | ไอดีรายการ
  | $to_id | ไอดีรายการ
   */
  public static function deleteSettingBranch($code, $id, $to_id = 0)
  {
    $rep = [
      'delete_result' => false
    ];

    $api = static::_checkDeleteSettingBranch($code, $id);
    if (isset($api['response_status']) && ($api['response_status'] == true)) {

      if ($to_id) {
        $u_data = [
          'branch_id' => $to_id
        ];
        $u_where = [
          'AND' => [
            'branch_id' => $id
          ]
        ];
        $api_update = Amst::update('sys_user', $u_data, $u_where);
        if (isset($api_update['response_status']) && ($api['response_status'] == true)) {
          $count = Amst::count('sys_user', ['branch_id' => $to_id]);
          Amst::updateByID($code . '_setting_branch', ['count_user' => $count], $to_id);
        }
      }

      $rep['delete_result'] = Amst::deleteByID($code . '_setting_branch', $id);
      return Amst::repSuccess($rep);
    }
    return $api; // ERROR SURE
  }

  private static function _checkDeleteSettingBranch($code, $id)
  {
    $rep = [
      'id' => $id
    ];

    // [Model_Exist]
    $model = Amst::getByID($code . '_setting_branch', ['id', 'type_branch'], $id);
    if (!isset($model['id'])) {
      return Amst::repError('E0005', 'tbl_setting_branch not found.');
    }

    if ($model['type_branch'] == 'main') {
      return Amst::repError('E0005', 'tbl_setting_branch cant delete.');
    }

    return Amst::repSuccess($rep);
  }

  public static function handleCountUserBranch($code, $ids)
  {
    $temp_ids = [];
    if (!is_array($ids)) {
      $temp_ids[] = $ids;
    } else {
      $temp_ids = $ids;
    }

    foreach ($temp_ids as $key => $id) {
      $count = Amst::count('sys_user', ['branch_id' => $id]);
      Amst::updateByID($code . '_setting_branch', ['count_user' => $count], $id);
    }

    return true;
  }
}
