<?php

class Phr_setting_working_time
{

  public static function setSettingWorkingTimeListDetail($code, $return, $options = [])
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
  | Group | Setting Working Time
  | Description | 1.ดึงข้อมูลลิส 'Setting Working Time'
  | $code | โค้ดโมดูล
  | $where | [name(ชื่อเว็บ)]
  | $options | [not_view(true|เอาแค่ข้อมูลหลักไป edit*เร็ว), selected_fields(String หรือ Array ของคอลัมภ์ที่ต้องการ), total_count(true|พร้อมจำนวนข้อมูลทั้งหมด ตามการกรองไม่แบ่งหน้า), page_no(1|เลขหน้า 0 คือส่งทั้งหมด), page_size(10|จำนวนข้อมูลต่อหน้า), sort_col(เรียงตามชื่อคอลัมภ์นี้), sort_by('ASC'|'DESC')]
   */
  public static function selectSettingWorkingTime($code, $where = [], $options = [])
  {
    $return = []; // list
    $sql_where = [];

    $sql_where['ORDER']['sort_order_id'] = 'DESC';

    $selected_fields = Amst::getSelectedFields($options);
    $return = Amst::selectNoKey($code . '_setting_working_time', $selected_fields, $sql_where, $options);
    return static::setSettingWorkingTimeListDetail($code, $return, $options);
  }

  /**
  | Group | Setting Working Time
  | Description | 1.ดึงข้อมูลลิส 'Setting Working Time'
  | $code | โค้ดโมดูล
  | $where | [name(ชื่อเว็บ)]
  | $options | [not_view(true|เอาแค่ข้อมูลหลักไป edit*เร็ว), selected_fields(String หรือ Array ของคอลัมภ์ที่ต้องการ), total_count(true|พร้อมจำนวนข้อมูลทั้งหมด ตามการกรองไม่แบ่งหน้า), page_no(1|เลขหน้า 0 คือส่งทั้งหมด), page_size(10|จำนวนข้อมูลต่อหน้า), sort_col(เรียงตามชื่อคอลัมภ์นี้), sort_by('ASC'|'DESC')]
   */
  public static function countSettingWorkingTime($code, $where = [], $options = [])
  {
    $return = []; // list
    $sql_where = [];

    $return = Amst::count($code . '_setting_working_time', $sql_where);
    return $return;
  }

  public static function setSettingWorkingTimeDetail($code, $record, $options = [])
  {
    if (!isset($record['id'])) {
      return $record;
    }

    // Do Single Works...
    // $record['other_key'] = 'other_value';

    $update_by_fullname = 'SYSTEM';
    $update_by_info = Amst::getByID('sys_user', ['id', 'full_name'], $record['update_user_id']);
    if (isset($update_by_info['full_name'])) {
      $update_by_fullname = $update_by_info['full_name'];
    }
    $record['update_by_fullname'] = $update_by_fullname;

    return $record;
  }

  /**
  | Group | Setting Working Time
  | Description | 2.ดึงข้อมูล 'Setting Working Time' ด้วยไอดี
  | $code | โค้ดโมดูล
  | $id | ไอดีรายการ
  | $options | [not_view(true|เอาแค่ข้อมูลหลักไป edit*เร็ว), selected_fields(String หรือ Array ของคอลัมภ์ที่ต้องการ)]
   */
  public static function getSettingWorkingTimeByID($code, $id, $options = [])
  {
    $return = null;

    $selected_fields = Amst::getSelectedFields($options, ['id', 'system_delete']);
    $model = Amst::getByID($code . '_setting_working_time', $selected_fields, $id, $options);
    if (isset($model['id'])) {
      $return = static::setSettingWorkingTimeDetail($code, $model, $options);
    }
    return $return;
  }

  /**
  | Group | Setting Working Time
  | Description | 3.เพิ่ม 'Setting Working Time'
  | $code | โค้ดโมดูล
  | $data | [    
    title,
    description,
    hour_total,
    minute_total,
    count_user,

    monday(0 / 1),
    monday_fromtime,
    monday_totime,
    tuesday(0 / 1),
    tuesday_fromtime,
    tuesday_totime,
    wednesday(0 / 1),
    wednesday_fromtime,
    wednesday_totime,
    thursday(0 / 1),
    thursday_fromtime,
    thursday_totime,
    friday(0 / 1),
    friday_fromtime,
    friday_totime,
    saturday(0 / 1),
    saturday_fromtime,
    saturday_totime,
    sunday(0 / 1),
    sunday_fromtime,
    sunday_totime,

    late_minute,
    very_late_minute,
    missing_minute,
    is_late_minute(0 / 1),
    is_very_late_minute(0 / 1),
    is_missing_minute(0 / 1)
  ]
   */
  public static function addNewSettingWorkingTime($code, $data = [])
  {
    $rep = [
      'insert_id' => false
    ];

    $day = [
      'monday',
      'tuesday',
      'wednesday',
      'thursday',
      'friday',
      'saturday',
      'sunday'
    ];

    $sum_minute   = 0;
    $sum_day_work = 0;
    // cal time
    foreach ($day as $key => $value) {
      if (isset($data[$value]) && $data[$value] == 1) {
        $fromtime = $data[$value . '_fromtime'];
        $totime   = $data[$value . '_totime'];

        $from = new DateTime($fromtime);
        $to   = new DateTime($totime);
        // กะข้ามคืน (เช่น 22:00 - 06:00) ให้เวลาสิ้นสุดเป็นวันถัดไป ไม่งั้น diff ได้ 16 ชม. แทน 8 ชม.
        if ($to <= $from) {
          $to->modify('+1 day');
        }

        $interval = $from->diff($to);

        // hour in day
        $sum_minute_day = ($interval->days * 24 * 60) + ($interval->h * 60) + $interval->i;

        // sum hour
        $sum_minute += $sum_minute_day;

        $hour_total_day   = floor($sum_minute_day / 60);
        $minute_total_day = $sum_minute_day % 60;

        $data[$value . '_hour']   = $hour_total_day;
        $data[$value . '_minute'] = $minute_total_day;

        $sum_day_work++;
      } else {
        $data[$value] = 0;
        $data[$value . '_fromtime'] = '00:00:00';
        $data[$value . '_totime'] = '00:00:00';
      }
    }

    $hour_total   = $sum_minute ? floor($sum_minute / 60) : 0;
    $minute_total = $sum_minute ? $sum_minute % 60 : 0;

    $i_data                 = $data;
    $i_data['hour_total']   = $hour_total;
    $i_data['minute_total'] = $minute_total;
    $i_data['count_user']   = 0;
    $i_data['sum_day_work'] = $sum_day_work;

    // checkbox ที่ไม่ติ๊กจะไม่ถูกส่งมา ต้องตั้งเป็น 0 เหมือนตอน update ไม่งั้นกะใหม่จะไม่เช็คสาย
    $key_check_is = [
      'is_late_minute',
      'is_very_late_minute',
      'is_missing_minute'
    ];
    foreach ($key_check_is as $key => $value) {
      if (!isset($i_data[$value])) {
        $i_data[$value] = 0;
      }
    }

    $api = Amst::insert($code . '_setting_working_time', $i_data);
    if (isset($api['response_status']) && ($api['response_status'] == true)) {
      $insert_id = $api['response_data']['insert_id'];
    }
    return $api;
  }

  /**
  | Group | Setting Working Time
  | Description | 4.อัพเดท 'Setting Working Time'
  | $code | โค้ดโมดูล
  | $id | ไอดีรายการ
  | $data | [    
    title,
    description,
    hour_total,
    minute_total,
    count_user,

    monday(0 / 1),
    monday_fromtime,
    monday_totime,
    tuesday(0 / 1),
    tuesday_fromtime,
    tuesday_totime,
    wednesday(0 / 1),
    wednesday_fromtime,
    wednesday_totime,
    thursday(0 / 1),
    thursday_fromtime,
    thursday_totime,
    friday(0 / 1),
    friday_fromtime,
    friday_totime,
    saturday(0 / 1),
    saturday_fromtime,
    saturday_totime,
    sunday(0 / 1),
    sunday_fromtime,
    sunday_totime,

    late_minute,
    very_late_minute,
    missing_minute,    
    is_late_minute(0 / 1),
    is_very_late_minute(0 / 1),
    is_missing_minute(0 / 1)
  ]
   */
  public static function updateSettingWorkingTime($code, $id, $data = [])
  {
    $rep = [
      'update_result' => false
    ];

    $model = Amst::getByID($code . '_setting_working_time', ['id'], $id);
    if (!isset($model['id'])) {
      return Amst::repError('E0005', 'tbl_setting_working_time not found.');
    }

    $day = [
      'monday',
      'tuesday',
      'wednesday',
      'thursday',
      'friday',
      'saturday',
      'sunday'
    ];

    $sum_minute   = 0;
    $sum_day_work = 0;
    // cal time
    foreach ($day as $key => $value) {
      if (isset($data[$value]) && $data[$value] == 1) {
        $fromtime = $data[$value . '_fromtime'];
        $totime   = $data[$value . '_totime'];

        $from = new DateTime($fromtime);
        $to   = new DateTime($totime);
        // กะข้ามคืน (เช่น 22:00 - 06:00) ให้เวลาสิ้นสุดเป็นวันถัดไป ไม่งั้น diff ได้ 16 ชม. แทน 8 ชม.
        if ($to <= $from) {
          $to->modify('+1 day');
        }

        $interval = $from->diff($to);

        // hour in day
        $sum_minute_day = ($interval->days * 24 * 60) + ($interval->h * 60) + $interval->i;

        // sum hour
        $sum_minute += $sum_minute_day;

        $hour_total_day   = floor($sum_minute_day / 60);
        $minute_total_day = $sum_minute_day % 60;

        $data[$value . '_hour']   = $hour_total_day;
        $data[$value . '_minute'] = $minute_total_day;

        $sum_day_work++;
      } else {
        $data[$value] = 0;
        $data[$value . '_fromtime'] = '00:00:00';
        $data[$value . '_totime'] = '00:00:00';
      }
    }

    $hour_total   = $sum_minute ? floor($sum_minute / 60) : 0;
    $minute_total = $sum_minute ? $sum_minute % 60 : 0;

    $u_data                 = $data;
    $u_data['hour_total']   = $hour_total;
    $u_data['minute_total'] = $minute_total;
    // $u_data['count_user']   = 0;
    $u_data['sum_day_work'] = $sum_day_work;

    $key_check_is = [
      'is_late_minute',
      'is_very_late_minute',
      'is_missing_minute'
    ];
    foreach ($key_check_is as $key => $value) {
      if (!isset($u_data[$value])) {
        $u_data[$value] = 0;
      }
    }

    $api = Amst::updateByID($code . '_setting_working_time', $u_data, $id);
    if (isset($api['response_status']) && ($api['response_status'] == true)) {
    }
    return $api;
  }

  /**
  | Group | Setting Working Time
  | Description | 5.ลบ 'Setting Working Time'
  | $code | โค้ดโมดูล
  | $id | ไอดีรายการ
  | $to_id | ไอดีรายการ
   */
  public static function deleteSettingWorkingTime($code, $id, $to_id = 0)
  {
    $rep = [
      'delete_result' => false
    ];

    $api = static::_checkDeleteSettingWorkingTime($code, $id);
    if (isset($api['response_status']) && ($api['response_status'] == true)) {

      if ($to_id) {
        $u_data = [
          'working_time_type_id' => $to_id
        ];
        $u_where = [
          'AND' => [
            'working_time_type_id' => $id
          ]
        ];
        $api_update = Amst::update('sys_user', $u_data, $u_where);
        if (isset($api_update['response_status']) && ($api_update['response_status'] == true)) {
          $count = Amst::count('sys_user', ['working_time_type_id' => $to_id]);
          Amst::updateByID($code . '_setting_working_time', ['count_user' => $count], $to_id);
        }
      }

      $rep['delete_result'] = Amst::deleteByID($code . '_setting_working_time', $id);
      return Amst::repSuccess($rep);
    }
    return $api; // ERROR SURE
  }

  private static function _checkDeleteSettingWorkingTime($code, $id)
  {
    $rep = [
      'id' => $id
    ];

    // [Model_Exist]
    $model = Amst::getByID($code . '_setting_working_time', ['id'], $id);
    if (!isset($model['id'])) {
      return Amst::repError('E0005', 'tbl_setting_working_time not found.');
    }

    return Amst::repSuccess($rep);
  }

  public static function handleCountUserWorkingTime($code, $ids)
  {
    $temp_ids = [];
    if (!is_array($ids)) {
      $temp_ids[] = $ids;
    } else {
      $temp_ids = $ids;
    }

    foreach ($temp_ids as $key => $id) {
      $count = Amst::count('sys_user', ['working_time_type_id' => $id]);
      Amst::updateByID($code . '_setting_working_time', ['count_user' => $count], $id);
    }

    return true;
  }
}
