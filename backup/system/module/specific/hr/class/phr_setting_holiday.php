<?php

class Phr_setting_holiday
{

  public static function setSettingHolidayListDetail($code, $return, $options = [])
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
  | Group | Setting Holiday
  | Description | 1.ดึงข้อมูลลิส 'Setting Holiday'
  | $code | โค้ดโมดูล
  | $where | [is_pass(0 / 1)]
  | $options | [not_view(true|เอาแค่ข้อมูลหลักไป edit*เร็ว), selected_fields(String หรือ Array ของคอลัมภ์ที่ต้องการ), total_count(true|พร้อมจำนวนข้อมูลทั้งหมด ตามการกรองไม่แบ่งหน้า), page_no(1|เลขหน้า 0 คือส่งทั้งหมด), page_size(10|จำนวนข้อมูลต่อหน้า), sort_col(เรียงตามชื่อคอลัมภ์นี้), sort_by('ASC'|'DESC')]
   */
  public static function selectSettingHoliday($code, $where = [], $options = [])
  {
    $return = []; // list
    $sql_where = [];

    $date_now = date('Y-m-d');

    if (isset($where['is_pass']) && $where['is_pass'] == 1) {
      $sql_where['AND']['to_date[<]']   = $date_now;
      $sql_where['AND']['from_date[<]'] = $date_now;
    } else {
      $sql_where['AND']['OR']['to_date[>=]']   = $date_now;
      $sql_where['AND']['OR']['from_date[>=]'] = $date_now;
    }

    $sql_where['ORDER']['sort_order_id'] = 'DESC';

    $selected_fields = Amst::getSelectedFields($options);
    $return = Amst::selectNoKey($code . '_setting_holiday', $selected_fields, $sql_where, $options);
    return static::setSettingHolidayListDetail($code, $return, $options);
  }

  /**
  | Group | Setting Holiday
  | Description | 1.count 'Setting Holiday'
  | $code | โค้ดโมดูล
  | $options | [not_view(true|เอาแค่ข้อมูลหลักไป edit*เร็ว), selected_fields(String หรือ Array ของคอลัมภ์ที่ต้องการ), total_count(true|พร้อมจำนวนข้อมูลทั้งหมด ตามการกรองไม่แบ่งหน้า), page_no(1|เลขหน้า 0 คือส่งทั้งหมด), page_size(10|จำนวนข้อมูลต่อหน้า), sort_col(เรียงตามชื่อคอลัมภ์นี้), sort_by('ASC'|'DESC')]
   */
  public static function countSettingHoliday($code, $where = [], $options = [])
  {
    $return = [
      'is_pass'   => 0,
      'is_coming' => 0
    ]; // list
    $sql_where_is_pass   = [];
    $sql_where_is_coming = [];

    $date_now = date('Y-m-d');

    $sql_where_is_pass['AND']['to_date[<]']   = $date_now;
    $sql_where_is_pass['AND']['from_date[<]'] = $date_now;
    
    $sql_where_is_coming['AND']['OR']['to_date[>=]']   = $date_now;
    $sql_where_is_coming['AND']['OR']['from_date[>=]'] = $date_now;

    $is_pass   = Amst::count($code . '_setting_holiday', $sql_where_is_pass);
    $is_coming = Amst::count($code . '_setting_holiday', $sql_where_is_coming);

    $return['is_pass']   = $is_pass;
    $return['is_coming'] = $is_coming;
    return $return;
  }

  public static function setSettingHolidayDetail($code, $record, $options = [])
  {
    if (!isset($record['id'])) {
      return $record;
    }

    // Do Single Works...
    // $record['other_key'] = 'other_value';

    $key_decode = [
      'role_use', 'request_user_ids'
    ];

    foreach ($key_decode as $key => $value) {
      if (isset($record[$value]) && $record[$value]) {
        $record[$value] = json_decode($record[$value], true);
      }
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
  | Group | Setting Holiday
  | Description | 2.ดึงข้อมูล 'Setting Holiday' ด้วยไอดี
  | $code | โค้ดโมดูล
  | $id | ไอดีรายการ
  | $options | [not_view(true|เอาแค่ข้อมูลหลักไป edit*เร็ว), selected_fields(String หรือ Array ของคอลัมภ์ที่ต้องการ)]
   */
  public static function getSettingHolidayByID($code, $id, $options = [])
  {
    $return = null;

    $selected_fields = Amst::getSelectedFields($options, ['id', 'system_delete']);
    $model = Amst::getByID($code . '_setting_holiday', $selected_fields, $id, $options);
    if (isset($model['id'])) {
      $return = static::setSettingHolidayDetail($code, $model, $options);
    }
    return $return;
  }

  /**
  | Group | Setting Holiday
  | Description | 3.เพิ่ม 'Setting Holiday'
  | $code | โค้ดโมดูล
  | $data | [
    title,
    type(one_day / period),
    from_date,
    from_time,
    to_date,
    to_time,
    description,
    role_use(array list),
    request_user_ids(array list)
  ]
   */
  public static function addNewSettingHoliday($code, $data = [])
  {
    // role_use
    $ex_role_use = [
      1,
      2
    ];

    // request_user_ids
    $ex_request_user_ids = [
      1, 2, 3, '...', 'n'
    ];

    $rep = [
      'insert_id' => false
    ];

    $type_setting = $data['type'];

    $year          = date('Y');
    $from_datetime = '';
    $to_datetime   = '';
    $from_time     = '00:00:00';
    $to_time       = '23:59:59';
    $sum_day_work  = 0;
    if ($type_setting == 'one_day') {
      $year_from = date('Y', strtotime($data['from_date']));

      $year = $year_from;

      $from_datetime = $data['from_date'] . ' 00:00:00';
      $to_datetime   = $data['from_date'] . ' 23:59:59';

      $sum_day_work = 1;
    } else {
      $year_from = date('Y', strtotime($data['from_date']));
      $year_to   = date('Y', strtotime($data['to_date']));
  
      $year_arr = [
        $year_from,
        $year_to
      ];
  
      $year_arr = array_unique($year_arr);
      $year     = implode(',', $year_arr);

      $from_datetime = $data['from_date'] . ' 00:00:00';
      $to_datetime   = $data['to_date'] . ' 23:59:59';

      // cal day
      $dateStart = new DateTime($data['from_date']);
      $dateEnd   = new DateTime($data['to_date']);

      $interval = $dateStart->diff($dateEnd);

      $sum_day_work = $interval->days + 1;
    }

    $i_data                  = $data;
    $i_data['year']          = $year;
    $i_data['from_datetime'] = $from_datetime;
    $i_data['from_time']     = $from_time;
    $i_data['to_datetime']   = $to_datetime;
    $i_data['to_time']       = $to_time;
    $i_data['sum_day_work']  = $sum_day_work;

    $key_encode = [
      'role_use', 'request_user_ids'
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

    $api = Amst::insert($code . '_setting_holiday', $i_data);
    if (isset($api['response_status']) && ($api['response_status'] == true)) {
      $insert_id = $api['response_data']['insert_id'];
    }
    return $api;
  }

  /**
  | Group | Setting Holiday
  | Description | 4.อัพเดท 'Setting Holiday'
  | $code | โค้ดโมดูล
  | $id | ไอดีรายการ
  | $data | [    
    title,
    from_date,
    from_time,
    to_date,
    to_time,
    description,
    role_use(array list),
    request_user_ids(array list)
  ]
   */
  public static function updateSettingHoliday($code, $id, $data = [])
  {
    // role_use
    $ex_role_use = [
      1,
      2
    ];

    // request_user_ids
    $ex_request_user_ids = [
      1, 2, 3, '...', 'n'
    ];
    $rep = [
      'update_result' => false
    ];

    $model = Amst::getByID($code . '_setting_holiday', ['id'], $id);
    if (!isset($model['id'])) {
      return Amst::repError('E0005', 'tbl_setting_holiday not found.');
    }

    $type_setting = $data['type'];

    $year          = date('Y');
    $from_datetime = '';
    $to_datetime   = '';
    $from_time     = '00:00:00';
    $to_time       = '23:59:59';
    $sum_day_work  = 0;
    if ($type_setting == 'one_day') {
      $year_from = date('Y', strtotime($data['from_date']));

      $year = $year_from;

      $from_datetime = $data['from_date'] . ' 00:00:00';
      $to_datetime   = $data['from_date'] . ' 23:59:59';

      $sum_day_work = 1;
    } else {
  
      $year_from = date('Y', strtotime($data['from_date']));
      $year_to   = date('Y', strtotime($data['to_date']));
  
      $year_arr = [
        $year_from,
        $year_to
      ];
  
      $year_arr = array_unique($year_arr);
      $year     = implode(',', $year_arr);

      $from_datetime = $data['from_date'] . ' 00:00:00';
      $to_datetime   = $data['to_date'] . ' 23:59:59';

      // cal day
      $dateStart = new DateTime($data['from_date']);
      $dateEnd   = new DateTime($data['to_date']);

      $interval = $dateStart->diff($dateEnd);

      $sum_day_work = $interval->days + 1;
    }

    $u_data                  = $data;
    $u_data['year']          = $year;
    $u_data['from_datetime'] = $from_datetime;
    $u_data['to_datetime']   = $to_datetime;
    $u_data['sum_day_work']  = $sum_day_work;

    $key_encode = [
      'role_use', 'request_user_ids'
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

    $api = Amst::updateByID($code . '_setting_holiday', $u_data, $id);
    if (isset($api['response_status']) && ($api['response_status'] == true)) {
    }
    return $api;
  }

  /**
  | Group | Setting Holiday
  | Description | 5.ลบ 'Setting Holiday'
  | $code | โค้ดโมดูล
  | $id | ไอดีรายการ
   */
  public static function deleteSettingHoliday($code, $id)
  {
    $rep = [
      'delete_result' => false
    ];

    $api = static::_checkDeleteSettingHoliday($code, $id);
    if (isset($api['response_status']) && ($api['response_status'] == true)) {
      $rep['delete_result'] = Amst::deleteByID($code . '_setting_holiday', $id);
      return Amst::repSuccess($rep);
    }
    return $api; // ERROR SURE
  }

  private static function _checkDeleteSettingHoliday($code, $id)
  {
    $rep = [
      'id' => $id
    ];

    // [Model_Exist]
    $model = Amst::getByID($code . '_setting_holiday', ['id'], $id);
    if (!isset($model['id'])) {
      return Amst::repError('E0005', 'tbl_setting_holiday not found.');
    }

    return Amst::repSuccess($rep);
  }
}
