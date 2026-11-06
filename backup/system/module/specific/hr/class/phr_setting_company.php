<?php

class Phr_setting_company
{
  public static function setSettingCompanyDetail($code, $record, $options = [])
  {
    if (!isset($record['id'])) {
      return $record;
    }

    // Do Single Works...
    // $record['other_key'] = 'other_value';

    $business_name = Amst::getByID($code . '_setting_business_type', 'name', $record['type_business_id']);
    $record['type_business_name'] = $business_name;
    
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
   */
  public static function getSettingCompany($code)
  {
    $return = null;

    $where = [
      'AND' => [
        'type_branch' => 'main'
      ]
    ];
    $model = Amst::get($code . '_setting_branch', '*', $where);
    if (!isset($model['id'])) {
      $i_data = [
        'name'               => '',
        'location'           => '',
        'count_user'         => 0,
        'type_branch'        => 'main',

        'branch_code'        => '',
        'type_business_id'   => '',
        'type_business_name' => '',

        'contact_person'     => '',
        'tax_number'         => '',
        'email'              => json_encode([0 => '']),
        'tel'                => json_encode([0 => '']),
        'fax_number'         => ''
      ];
      Amst::insert($code . '_setting_branch', $i_data);

      $model = Amst::get($code . '_setting_branch', '*', $where);
    }
    return static::setSettingCompanyDetail($code, $model);
  }

  /**
  | Group | Setting Branch
  | Description | 4.อัพเดท 'Setting Branch'
  | $code | โค้ดโมดูล
  | $id | ไอดีรายการ
  | $data | [    
    name,
    location,

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
  public static function updateSettingCompany($code, $id, $data = [])
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
    $u_data['type_branch']   = 'main';

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
   */
  public static function deleteSettingCompany($code, $id)
  {
    $rep = [
      'delete_result' => false
    ];

    $api = static::_checkDeleteSettingCompany($code, $id);
    if (isset($api['response_status']) && ($api['response_status'] == true)) {
      $rep['delete_result'] = Amst::deleteByID($code . '_setting_branch', $id);
      return Amst::repSuccess($rep);
    }
    return $api; // ERROR SURE
  }

  private static function _checkDeleteSettingCompany($code, $id)
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
}
