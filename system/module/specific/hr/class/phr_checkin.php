<?php

class Phr_checkin
{

  /**
  | Group | Con CheckIn
  | Description | 
  | $code | โค้ดโมดูล
   */
  public static function handleSetCheckOutMissingAuto($code)
  {

    // ยิงทุกเทียงคืนของวัน

    $date_now    = date('Y-m-d');
    $date_before = date('Y-m-d', strtotime('-1 day'));

    $day_before_name = strtolower(date('l', strtotime($date_before)));

    // check out auto
    $where = [
      'AND' => [
        'status' => 'in_progress',
        'date'   => $date_before
      ]
    ];
    $check_record    = Amst::get($code . '_checkin', ['id'], $where);
    if (isset($check_record['id'])) {
      $select = Amst::selectNoKey($code . '_checkin', '*', $where);
      // $ids = array_column($select, 'id');

      $user_ids   = array_column($select, 'user_id');
      $user_ids[] = -1;

      $user_list    = Amst::select('sys_user', '*', ['id' => $user_ids]);

      foreach ($select as $key => $record) {

        $checkout_datetime = $record['date'] . ' 23:59:59';

        $interval = Phr_helper::calDiffDatetime($record['checkin_datetime'], $checkout_datetime);
        $total_working_time = str_pad($interval->h, 2, '0', STR_PAD_LEFT) . ':' . str_pad($interval->i, 2, '0', STR_PAD_LEFT) . ':' . str_pad($interval->s, 2, '0', STR_PAD_LEFT);

        $u_set_checkout_data = [
          'status'             => 'success',
          'checkout_time'      => '23:59:59',
          'checkout_datetime'  => $checkout_datetime, // เดิมไม่ได้บันทึก ทำให้ตารางแสดงเวลาออกเป็น --:--
          'checkout_branch_id' => isset($user_list[$record['user_id']]) ? $user_list[$record['user_id']]['branch_id'] : 0,
          'working_day'        => $interval->days,
          'working_hour'       => $interval->h,
          'working_minute'     => $interval->i,
          'working_second'     => $interval->s,
          'total_working_time' => $total_working_time
        ];

        Amst::updateByID($code . '_checkin', $u_set_checkout_data, $record['id']);
      }
    }

    // missing auto
    $working_time_list = Amst::select($code . '_setting_working_time', '*', []);

    // หาวันหยุดที่ครอบคลุม $date_before (เดิมเงื่อนไขกลับด้าน เจอเฉพาะวันหยุดวันเดียว)
    $where = [
      'AND' => [
        'from_date[<=]' => $date_before,
        'to_date[>=]'   => $date_before
      ]
    ];
    $holiday_list = Amst::select($code . '_setting_holiday', '*', $where);
    $temp_holiday_user_ids              = [];
    $temp_holiday_working_time_type_ids = [];
    foreach ($holiday_list as $key => $record) {
      $working_time_type_ids = json_decode($record['role_use'], true);
      foreach ($working_time_type_ids as $key_wt => $working_time_type_id) {
        if (!in_array($working_time_type_id, $temp_holiday_working_time_type_ids)) {
          $temp_holiday_working_time_type_ids[] = $working_time_type_id;
        }
      }

      $request_user_ids = json_decode($record['request_user_ids'], true);
      foreach ($request_user_ids as $key_user => $request_user_id) {
        if (!in_array($request_user_id, $temp_holiday_user_ids)) {
          $temp_holiday_user_ids[] = $request_user_id;
        }
      }
    }

    $where_role_user = [
      'AND' => [
        // 'code[!]' => 'ywptz', // admin
        'module'  => 'User',
      ]
    ];
    $role_user_list = Amst::selectNoKey('sys_function', ['id', 'code', 'name', 'setting_permission'], $where_role_user);
    $role_user_code = array_unique(array_column($role_user_list, 'code'));

    $where_user = [
      'AND' => [
        'user_code' => $role_user_code
      ]
    ];
    $user_list = Amst::select('sys_user', ['id', 'working_time_type_id'], $where_user);

    $where = [
      'AND' => [
        'date'   => $date_before
      ]
    ];
    $history_checkin_list = Amst::selectNoKey($code . '_checkin', ['id', 'user_id', 'date'], $where);
    $temp_history_checkin_list = [];
    foreach ($history_checkin_list as $key => $record) {
      $temp_key = $record['user_id'] . '_' . $record['date'];
      $temp_history_checkin_list[$temp_key] = $record;
    }

    foreach ($user_list as $key_user => $record_user) {
      $working_time_info = isset($working_time_list[$record_user['working_time_type_id']]) ? $working_time_list[$record_user['working_time_type_id']] : [];
      $user_info         = $record_user;

      if (!isset($working_time_info['id'])) {
        continue;
      }

      if (in_array($user_info['working_time_type_id'], $temp_holiday_working_time_type_ids) && !in_array($user_info['id'], $temp_holiday_user_ids)) {
        continue;
      }

      if ($working_time_info[$day_before_name] == 1) {
        $temp_key = $user_info['id'] . '_' . $date_before;
        $history_checkin_info = isset($temp_history_checkin_list[$temp_key]) ? $temp_history_checkin_list[$temp_key] : [];
        if (
          isset($working_time_info['is_late_minute']) && $working_time_info['is_late_minute'] == 0 ||
          isset($working_time_info['is_very_late_minute']) && $working_time_info['is_very_late_minute'] == 0 ||
          isset($working_time_info['is_missing_minute']) && $working_time_info['is_missing_minute'] == 0
        )
          if (!isset($history_checkin_info['id'])) {
            $i_data = [
              // check in
              'user_id'             => $user_info['id'],
              'type'                => 'on_time',
              'status'              => 'success',
              'status_checkin'      => 'missing',
              'status_confirm'      => 'waiting_confirm', // default status
              'day_name'            => $day_before_name,
              'date'                => $date_before,
              // 'checkin_time'        => '00:00:00',
              // 'checkin_branch_id'   => $data['branch_id'],
              'checkin_datetime'    => $date_before . ' 00:00:00',
              // 'checkin_late_minute' => $return_checklate['late_minute'],
              'remark'              => 'ขาดงาน',

              // check out
              // 'checkout_time'      => '00:00:00',
              // 'checkout_branch_id' => $data['branch_id'],
              'checkout_datetime'  => $date_before . ' 00:00:00',
              // 'working_day'        => $interval->days,
              // 'working_hour'       => $interval->h,
              // 'working_minute'     => $interval->i,
              // 'working_second'     => $interval->s,
              'total_working_time' => '00:00:00',
              // 'is_overtime'        => $is_overtime,
              'is_last'            => 1
            ];

            $api = Amst::insert($code . '_checkin', $i_data);
          }
      }
    }

    return true;
  }

  public static function setCheckInListDetail($code, $return, $options = [])
  {
    // [ExtractList]
    $selected_records = isset($return['list']) ? $return['list'] : $return;

    $date_now = date('Y-m-d');

    $thai_day_arr = [
      'sunday'    => 'อาทิตย์',
      'monday'    => 'จันทร์',
      'tuesday'   => 'อังคาร',
      'wednesday' => 'พุธ',
      'thursday'  => 'พฤหัสบดี',
      'friday'    => 'ศุกร์',
      'saturday'  => 'เสาร์'
    ];

    $branch_list = Amst::select($code . '_setting_branch', ['id', 'name'], []);

    $user_ids   = array_unique(array_column($selected_records, 'user_id'));
    $user_ids[] = -1;
    $user_list = Amst::select('sys_user', ['id', 'full_name'], ['id' => $user_ids]);

    // Do Each Works...
    foreach ($selected_records as $key => $record) {
      // $selected_records[$key]['other_key'] = 'other_value';

      $selected_records[$key]['is_today']             = $record['date'] == $date_now ? 1 : 0;

      $selected_records[$key]['user_fullname']        = isset($user_list[$record['user_id']]) ? $user_list[$record['user_id']]['full_name'] : '';
      $selected_records[$key]['checkin_branch_name']  = isset($branch_list[$record['checkin_branch_id']]) ? $branch_list[$record['checkin_branch_id']]['name'] : '';
      $selected_records[$key]['checkout_branch_name'] = isset($branch_list[$record['checkout_branch_id']]) ? $branch_list[$record['checkout_branch_id']]['name'] : '';
      $selected_records[$key]['day_name_th']          = isset($thai_day_arr[$record['day_name']]) ? $thai_day_arr[$record['day_name']] : '';
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
  | Group | CheckIn
  | Description | 1.ดึงข้อมูลลิส 'CheckIn'
  | $code | โค้ดโมดูล
  | $where | [
    user_id,
    date,
    type(on_time / late),
    status_checkin(normal / late / very_late / missing / draft_leave_work / leave_work),
    status_confirm(waiting_confirm / approved / reject),
    from_date,
    to_date,
    is_show_history(0/1),
  ]
  | $options | [ not_view(true|เอาแค่ข้อมูลหลักไป edit*เร็ว), selected_fields(String หรือ Array ของคอลัมภ์ที่ต้องการ), total_count(true|พร้อมจำนวนข้อมูลทั้งหมด ตามการกรองไม่แบ่งหน้า), page_no(1|เลขหน้า 0 คือส่งทั้งหมด), page_size(10|จำนวนข้อมูลต่อหน้า), sort_col(เรียงตามชื่อคอลัมภ์นี้), sort_by('ASC'|'DESC')]
   */
  public static function selectCheckIn($code, $where = [], $options = [])
  {
    $return = []; // list
    $sql_where = [];

    $date_now = date('Y-m-d');

    // static::handleSetCheckOutMissingAuto($code);

    if (isset($where['user_id']) && $where['user_id']) {
      $sql_where['AND']['user_id'] = $where['user_id'];
      // draft_leave_work เป็นค่าของ status_checkin ไม่ใช่ status_confirm
      $sql_where['AND']['status_checkin[!]'] = ['draft_leave_work'];
    }

    if (isset($where['date']) && $where['date']) {
      $sql_where['AND']['date[~]'] = $where['date'];
    }

    if (isset($where['type']) && $where['type']) {
      $sql_where['AND']['type'] = $where['type'];
    }

    if (isset($where['status_checkin']) && $where['status_checkin']) {
      $sql_where['AND']['status_checkin'] = $where['status_checkin'];
    }

    if (isset($where['status_confirm']) && $where['status_confirm']) {
      $sql_where['AND']['status_confirm'] = $where['status_confirm'];
    }

    if ((isset($where['from_date']) && $where['from_date']) && isset($where['to_date']) && $where['to_date']) {
      $sql_where['AND']['date[>=]'] = $where['from_date'];
      $sql_where['AND']['date[<=]'] = $where['to_date'];
    }

    if (!isset($where['is_show_history'])) {
      $sql_where['AND']['is_last'] = 1;
    }

    $sql_where['AND']['status[!]'] = 'draft';
    $sql_where['ORDER']['date'] = 'DESC';

    $selected_fields = Amst::getSelectedFields($options);
    $return = Amst::selectNoKey($code . '_checkin', $selected_fields, $sql_where, $options);
    return static::setCheckInListDetail($code, $return, $options);
  }

  /**
  | Group | CheckIn
  | Description | 1.count 'CheckIn'
  | $code | โค้ดโมดูล
  | $where | [
    date,
    type(on_time / late),
    status_confirm(waiting_confirm / approved / reject)
  ]
  | $options | [not_view(true|เอาแค่ข้อมูลหลักไป edit*เร็ว), selected_fields(String หรือ Array ของคอลัมภ์ที่ต้องการ), total_count(true|พร้อมจำนวนข้อมูลทั้งหมด ตามการกรองไม่แบ่งหน้า), page_no(1|เลขหน้า 0 คือส่งทั้งหมด), page_size(10|จำนวนข้อมูลต่อหน้า), sort_col(เรียงตามชื่อคอลัมภ์นี้), sort_by('ASC'|'DESC')]
   */
  public static function countCheckIn($code, $where = [], $options = [])
  {
    $return = []; // list
    $sql_where = [];

    if (isset($where['date']) && $where['date']) {
      $sql_where['AND']['date[~]'] = $where['date'];
    }

    if (isset($where['type']) && $where['type']) {
      $sql_where['AND']['type'] = $where['type'];
    }

    if (isset($where['status_confirm']) && $where['status_confirm']) {
      $sql_where['AND']['status_confirm'] = $where['status_confirm'];
    }

    $return = Amst::count($code . '_checkin', $sql_where);
    return $return;
  }

  public static function setCheckInDetail($code, $record, $options = [])
  {
    if (!isset($record['id'])) {
      return $record;
    }

    // Do Single Works...
    // $record['other_key'] = 'other_value';

    $thai_day_arr = [
      'sunday'    => 'อาทิตย์',
      'monday'    => 'จันทร์',
      'tuesday'   => 'อังคาร',
      'wednesday' => 'พุธ',
      'thursday'  => 'พฤหัสบดี',
      'friday'    => 'ศุกร์',
      'saturday'  => 'เสาร์'
    ];

    $branch_list = Amst::select($code . '_setting_branch', ['id', 'name'], []);
    $record['checkin_branch_name']  = isset($branch_list[$record['checkin_branch_id']]) ? $branch_list[$record['checkin_branch_id']]['name'] : '';
    $record['checkout_branch_name'] = isset($branch_list[$record['checkout_branch_id']]) ? $branch_list[$record['checkout_branch_id']]['name'] : '';

    $record['day_name_th'] = isset($thai_day_arr[$record['day_name']]) ? $thai_day_arr[$record['day_name']] : '';

    $user_info = Amst::getByID('sys_user', '*', $record['user_id']);
    $record['user_fullname'] = $user_info['full_name'];

    return $record;
  }

  /**
  | Group | CheckIn
  | Description | 2.ดึงข้อมูล 'CheckIn' ด้วยไอดี
  | $code | โค้ดโมดูล
  | $id | ไอดีรายการ
  | $options | [not_view(true|เอาแค่ข้อมูลหลักไป edit*เร็ว), selected_fields(String หรือ Array ของคอลัมภ์ที่ต้องการ)]
   */
  public static function getCheckInByID($code, $id, $options = [])
  {
    $return = null;

    $selected_fields = Amst::getSelectedFields($options, ['id', 'system_delete']);
    $model = Amst::getByID($code . '_checkin', $selected_fields, $id, $options);
    if (isset($model['id'])) {
      $return = static::setCheckInDetail($code, $model, $options);
    }
    return $return;
  }

  /**
  | Group | CheckIn
  | Description | 3.check 'CheckIn' status
  | $code | โค้ดโมดูล
  | $data | [
    user_id,
    date,
    time
  ]
   */
  public static function checkCheckInStatus($code, $data = [])
  {
    $check_user = Amst::getByID('sys_user', ['id', 'working_time_type_id'], $data['user_id']);
    if (!isset($check_user['id'])) {
      return Amst::repError('E0000', 'User not found.');
    }

    $day_name = strtolower(date('l', strtotime($data['date'])));

    $data_check_late = [
      'user_id'              => $data['user_id'],
      'working_time_type_id' => $check_user['working_time_type_id'],
      'date'                 => $data['date'],
      'time'                 => $data['time'],
      'day_name'             => $day_name,
    ];

    $return_checklate = static::handleCheckInLate($code, $data_check_late);

    return $return_checklate;
  }

  /**
  | Group | CheckIn
  | Description | 3.check 'CheckOut' time
  | $code | โค้ดโมดูล
  | $data | [
    user_id,
    date,
    time
  ]
   */
  public static function checkCheckOutTime($code, $data = [])
  {
    $return = [
      'working_hour'   => 0,
      'working_minute' => 0,
      'working_second' => 0,
    ];

    $where_checkin = [
      'AND' => [
        'user_id' => $data['user_id'],
        'date'    => $data['date']
      ],
      'ORDER' => [
        'id' => 'DESC'
      ]
    ];
    $checkin_info         = Amst::get($code . '_checkin', '*', $where_checkin);
    if (!isset($checkin_info['id'])) {
      return false;
    }

    $default_setting_date = $checkin_info['checkin_datetime'];
    $user_checkout        = $data['date'] . ' ' . $data['time'];

    $default_setting_date = new DateTime($default_setting_date); // default setting
    $user_checkout        = new DateTime($user_checkout); // user checkin

    if ($default_setting_date < $user_checkout) {
      $interval = $default_setting_date->diff($user_checkout);

      $total_minutes = ($interval->days * 24 * 60) + ($interval->h * 60) + $interval->i;

      $return['working_hour']   = str_pad($interval->h, 2, '0', STR_PAD_LEFT);
      $return['working_minute'] = str_pad($interval->i, 2, '0', STR_PAD_LEFT);
      $return['working_second'] = str_pad($interval->s, 2, '0', STR_PAD_LEFT);
    }

    return $return;
  }

  /**
  | Group | CheckIn
  | Description | 3.เพิ่ม 'CheckIn'
  | $code | โค้ดโมดูล
  | $data | [
    user_id,
    type(on_time / late),
    date,
    time,
    time_end,
    branch_id,
    remark
  ]
   */
  public static function userCheckInOut($code, $data = [])
  {
    $check_user = Amst::getByID('sys_user', ['id', 'employee_status', 'working_time_type_id'], $data['user_id']);
    if (!isset($check_user['id'])) {
      return Amst::repError('E0000', 'User not found.');
    }

    $date_now = date('Y-m-d');
    if (isset($data['type']) && $data['type'] == 'late') {
      if ($date_now <= $data['date']) {
        return Amst::repError('E0000', 'Date not before.');
      }
    }

    $day_name = strtolower(date('l', strtotime($data['date'])));

    $i_log_data = [
      'user_id'    => $data['user_id'], // คนทำรายการ
      'checkin_id' => 0,
      'type'       => '',
      'date'       => $data['date'],
      'time'       => $data['time'],
    ];

    $where = [
      'AND' => [
        'user_id'        => $data['user_id'],
        'status_checkin' => ['normal', 'late', 'very_late', 'missing'],
        'date'           => $data['date'],
        'is_last'        => 1
      ],
      'ORDER' => [
        'id' => 'DESC'
      ]
    ];
    $model = Amst::get($code . '_checkin', '*', $where);
    $return_checklate = [];
    $api = [];
    $checkin_id = 0;
    if (isset($data['type']) && $data['type'] == 'on_time') {
      if (!isset($model['id'])) { // checkin 
        $checkin_datetime = $data['date'] . ' ' . $data['time'];

        $data_check_late = [
          'user_id'              => $data['user_id'],
          'working_time_type_id' => $check_user['working_time_type_id'],
          'date'                 => $data['date'],
          'time'                 => $data['time'],
          'day_name'             => $day_name,
        ];

        $return_checklate = static::handleCheckInLate($code, $data_check_late);

        $i_data = [
          'user_id'             => $data['user_id'],
          'type'                => $data['type'],
          'status'              => 'in_progress',
          'status_checkin'      => $return_checklate['status'],
          'status_confirm'      => 'waiting_confirm', // default status
          'day_name'            => $day_name,
          'date'                => $data['date'],
          'checkin_time'        => $data['time'],
          'checkin_branch_id'   => $data['branch_id'],
          'checkin_datetime'    => $checkin_datetime,
          'checkin_late_minute' => $return_checklate['late_minute'],
          'remark'              => isset($data['remark']) ? $data['remark'] : '',
          'is_last'             => 1
        ];

        $api = Amst::insert($code . '_checkin', $i_data);
        if (isset($api['response_status']) && ($api['response_status'] == true)) {
          $insert_id  = $api['response_data']['insert_id'];
          $checkin_id = $insert_id;

          $i_log_data['checkin_id'] = $checkin_id;
          $i_log_data['type']       = 'checkin';
        }
      } else { // checkout
        if ($model['status'] == 'success') { // ถ้าทำรายการทั้ง checkin checkout แล้วสถานะเป็น success แล้วจะตบออก
          return Amst::repError('E0000', 'Today CheckIn was successful.');
        }

        $checkin_id = $model['id'];

        $data_check_overtime = [
          'user_id'              => $data['user_id'],
          'working_time_type_id' => $check_user['working_time_type_id'],
          'date'                 => $data['date'],
          'time'                 => $data['time'],
          'day_name'             => $day_name,
        ];

        $is_overtime = static::handleCheckOutOverTime($code, $data_check_overtime);

        $i_log_data['checkin_id'] = $checkin_id;
        $i_log_data['type']       = 'checkout';

        $checkout_datetime = $data['date'] . ' ' . $data['time'];

        // ใช้เวลา checkout ที่เพิ่งได้มา (ค่าใน DB ยังว่างอยู่)
        $interval = Phr_helper::calDiffDatetime($model['checkin_datetime'], $checkout_datetime);
        $total_working_time = str_pad($interval->h, 2, '0', STR_PAD_LEFT) . ':' . str_pad($interval->i, 2, '0', STR_PAD_LEFT) . ':' . str_pad($interval->s, 2, '0', STR_PAD_LEFT);

        $u_data = [
          'status'             => 'success',
          'checkout_time'      => $data['time'],
          'checkout_branch_id' => $data['branch_id'],
          'checkout_datetime'  => $checkout_datetime,
          'working_day'        => $interval->days,
          'working_hour'       => $interval->h,
          'working_minute'     => $interval->i,
          'working_second'     => $interval->s,
          'total_working_time' => $total_working_time,
          'is_overtime'        => $is_overtime
        ];

        $api = Amst::updateByID($code . '_checkin', $u_data, $model['id']);
        if (isset($api['response_status']) && ($api['response_status'] == true)) {
          if ($check_user['employee_status'] == 'day') {
            $data_salary = [
              'user_id' => $data['user_id']
            ];
            Phr_employee::handleSaveSalaryPerformanceDayAuto($code, $data_salary);
          }
        }
      }

      if (isset($api['response_status']) && ($api['response_status'] == true)) {
        // addlog
        if ($checkin_id) {
          Amst::insert($code . '_checkin_log', $i_log_data);
        }
      }
    } else if (isset($data['type']) && $data['type'] == 'late') {

      $check_date = $data['date'] . ' ' . $data['time'];
      $is_day_off = 0;

      // =============================================================================
      // check holiday
      $where_holiday = [
        'AND' => [
          'from_date[<=]'        => $check_date,
          'to_date[>=]'          => $check_date,
          // 'request_user_ids[~]'  => '"' . $check_user['id'] . '"',
          'role_use[~]'          => '"' . $check_user['working_time_type_id'] . '"',
          'request_user_ids[!~]' => '"' . $check_user['id'] . '"',
        ]
      ];

      $holiday_id = Amst::get($code . '_setting_holiday', 'id', $where_holiday);
      if ($holiday_id) {
        $is_day_off = 1;
      }

      if ($is_day_off === 0) {
        // check leave work
        $where_leave_work = [
          'AND' => [
            'user_id'       => $check_user['id'],
            'from_date[<=]' => $check_date,
            'to_date[>=]'   => $check_date,
          ]
        ];

        $leave_work_id = Amst::get($code . '_leave_work', 'id', $where_leave_work);
        if ($leave_work_id) {
          $is_day_off = 1;
        }
      }
      // =============================================================================

      if ($is_day_off == 1) {
        return Amst::repError('E0000', 'Is Holiday.');
      }

      if (isset($model['id'])) {
        // if ($model['status_confirm'] != 'reject') {
        //   return Amst::repError('E0000', 'Checkin is already.');
        // }

        // ต้องจำกัดเฉพาะพนักงานคนนี้ เดิมล้าง is_last ของทุกคนในวันนั้น
        Amst::update($code . '_checkin', ['is_last' => 0], ['AND' => ['date' => $data['date'], 'user_id' => $data['user_id']]]);
      }

      $i_log_data_list = [];

      $checkin_datetime  = $data['date'] . ' ' . $data['time'];
      $checkout_datetime = $data['date'] . ' ' . $data['time_end'];

      $data_check_late = [
        'user_id'              => $data['user_id'],
        'working_time_type_id' => $check_user['working_time_type_id'],
        'date'                 => $data['date'],
        'time'                 => $data['time'],
        'day_name'             => $day_name,
      ];
      $return_checklate = static::handleCheckInLate($code, $data_check_late);

      if ($return_checklate['is_day_off'] == 1) {
        return Amst::repError('E0000', 'Is Day Off.');
      }

      $data_check_overtime = [
        'user_id'              => $data['user_id'],
        'working_time_type_id' => $check_user['working_time_type_id'],
        'date'                 => $data['date'],
        'time'                 => $data['time_end'], // เช็ค OT จากเวลาออกงาน ไม่ใช่เวลาเข้างาน
        'day_name'             => $day_name,
      ];
      $is_overtime = static::handleCheckOutOverTime($code, $data_check_overtime);

      $interval = Phr_helper::calDiffDatetime($checkin_datetime, $checkout_datetime);
      $total_working_time = str_pad($interval->h, 2, '0', STR_PAD_LEFT) . ':' . str_pad($interval->i, 2, '0', STR_PAD_LEFT) . ':' . str_pad($interval->s, 2, '0', STR_PAD_LEFT);

      $i_data = [
        // check in
        'user_id'             => $data['user_id'],
        'type'                => $data['type'],
        'status'              => 'success', // ลงย้อนหลังมีทั้งเวลาเข้า-ออกแล้ว (เดิมสะกดผิดเป็น is_progress)
        'status_checkin'      => $return_checklate['status'],
        'status_confirm'      => 'waiting_confirm', // default status
        'day_name'            => $day_name,
        'date'                => $data['date'],
        'checkin_time'        => $data['time'],
        'checkin_branch_id'   => $data['branch_id'],
        'checkin_datetime'    => $checkin_datetime,
        'checkin_late_minute' => $return_checklate['late_minute'],
        'remark'              => isset($data['remark']) ? $data['remark'] : '',

        // check out
        'checkout_time'      => $data['time_end'],
        'checkout_branch_id' => $data['branch_id'],
        'checkout_datetime'  => $checkout_datetime,
        'working_day'        => $interval->days,
        'working_hour'       => $interval->h,
        'working_minute'     => $interval->i,
        'working_second'     => $interval->s,
        'total_working_time' => $total_working_time,
        'is_overtime'        => $is_overtime,
        'is_last'            => 1
      ];

      $api = Amst::insert($code . '_checkin', $i_data);
      if (isset($api['response_status']) && ($api['response_status'] == true)) {
        $insert_id  = $api['response_data']['insert_id'];
        $checkin_id = $insert_id;

        $i_log_data_list[] = [
          'user_id'    => $data['user_id'], // คนทำรายการ
          'checkin_id' => $checkin_id,
          'type'       => 'checkin',
          'date'       => $data['date'],
          'time'       => $data['time'],
        ];

        $i_log_data_list[] = [
          'user_id'    => $data['user_id'], // คนทำรายการ
          'checkin_id' => $checkin_id,
          'type'       => 'checkout',
          'date'       => $data['date'],
          'time'       => $data['time_end'],
        ];

        // addlog
        if ($checkin_id) {
          foreach ($i_log_data_list as $key => $record) {
            Amst::insert($code . '_checkin_log', $record);
          }
        }
      }
    }

    if (isset($api['response_status']) && ($api['response_status'] == true)) {

      // if ($check_user['employee_status'] != 'day') {
      //   $log_cost_data = [
      //     'user_id' => $data['user_id'],
      //     'type'    => $data['type'],
      //     'status'  => $return_checklate['status'],
      //     'date'    => $data['date']
      //   ];

      //   $log_cost_deduct = Phr_employee::handleGenEmployeeCostDeduct($code, $log_cost_data);
      // }
    }
    return $api;
  }

  /**
  | Group | CheckIn
  | Description | 
  | $code | โค้ดโมดูล
  | $data | [
    user_id,
    working_time_type_id,
    date,
    time,
    day_name
  ]
   */
  public static function handleCheckInLate($code, $data = [])
  {
    $return = [
      'is_day_off'     => 0,
      'status'         => 'normal',
      'late_minute'    => 0,
      'working_hour'   => 0,
      'working_minute' => 0,
      'working_second' => 0,
    ];

    $working_time_type_id = $data['working_time_type_id'];
    $working_time_info    = Amst::getByID($code . '_setting_working_time', '*', $working_time_type_id);
    if (!isset($working_time_info['id'])) {
      // ไม่มีกะงาน -> คืนค่า default แทน false เพราะผู้เรียกอ่าน ['status'] ต่อทันที
      return $return;
    }

    $default_setting_date = $data['date'] . ' ' . $working_time_info[$data['day_name'] . '_fromtime'];
    $user_checkin         = $data['date'] . ' ' . $data['time'];
    $check_late_date      = date('Y-m-d H:i:s', strtotime($data['date'] . ' ' . $working_time_info[$data['day_name'] . '_fromtime'] . ' +' . $working_time_info['late_minute'] . ' minute'));

    $default_setting_date = new DateTime($default_setting_date); // default setting
    $user_checkin         = new DateTime($user_checkin); // user checkin
    $check_late_date      = new DateTime($check_late_date); // default setting

    if ($default_setting_date <= $user_checkin) {
      $interval      = $default_setting_date->diff($user_checkin);
      $interval_late = $check_late_date->diff($user_checkin);

      $total_minutes = ($interval->days * 24 * 60) + ($interval->h * 60) + $interval->i;
      $late_minute   = ($interval_late->days * 24 * 60) + ($interval_late->h * 60) + $interval_late->i;
      // เข้างานก่อนหมดเวลาผ่อนผัน -> diff() คืนค่าสัมบูรณ์ ต้องไม่นับเป็นนาทีที่สาย
      if ($interval_late->invert) {
        $late_minute = 0;
      }

      $key_check_time_late = [
        'late_minute',
        'very_late_minute',
        'missing_minute'
      ];

      $status = 'normal';
      if ($working_time_info[$data['day_name']] == 1) {
        if (
          isset($working_time_info['is_late_minute']) && $working_time_info['is_late_minute'] == 0 ||
          isset($working_time_info['is_very_late_minute']) && $working_time_info['is_very_late_minute'] == 0 ||
          isset($working_time_info['is_missing_minute']) && $working_time_info['is_missing_minute'] == 0
        ) {
          foreach ($key_check_time_late as $key => $value) {
            if ($working_time_info['is_' . $value] == 0) {
              if ($total_minutes <= $working_time_info[$value]) {
                break;
              } else {
                $status = str_replace('_minute', '', $value);
              }
            }
          }
        }
      } else {
        $return['is_day_off'] = 1;
      }

      $return['status']         = $status;
      // $return['late_minute']    = $total_minutes;
      $return['late_minute']    = $late_minute;
      $return['working_hour']   = str_pad($interval->h, 2, '0', STR_PAD_LEFT);
      $return['working_minute'] = str_pad($interval->i, 2, '0', STR_PAD_LEFT);
      $return['working_second'] = str_pad($interval->s, 2, '0', STR_PAD_LEFT);
    }

    return $return;
  }

  public static function handleCheckOutOverTime($code, $data = [])
  {
    $working_time_type_id = $data['working_time_type_id'];
    $working_time_info    = Amst::getByID($code . '_setting_working_time', '*', $working_time_type_id);
    if (!isset($working_time_info['id'])) {
      return 0;
    }

    $is_overtime = 0;

    if ($working_time_info[$data['day_name']] == 1) {
      $default_setting_date = $data['date'] . ' ' . $working_time_info[$data['day_name'] . '_totime'];
      $user_checkout        = $data['date'] . ' ' . $data['time'];

      if (strtotime($user_checkout) > strtotime($default_setting_date)) {
        $is_overtime = 1;
      }
    }

    return $is_overtime;
  }

  /**
  | Group | Control Working Time
  | Description | 1 count 'Working Time'
  | $code | โค้ดโมดูล
   */
  public static function countControlCheckIn($code)
  {
    $return = [
      'count_waiting_confirm'      => 0,
      'count_waiting_confirm_late' => 0,
      'count_approved'             => 0,
      'count_reject'               => 0
    ];

    $arr_where = [
      [
        'name'  => 'count_waiting_confirm',
        'where' => [
          'AND' => [
            'type'           => 'on_time',
            'status_confirm' => 'waiting_confirm'
          ]
        ]
      ],
      [
        'name'  => 'count_waiting_confirm_late',
        'where' => [
          'AND' => [
            'type'           => 'late',
            'status_confirm' => 'waiting_confirm'
          ]
        ]
      ],
      [
        'name'  => 'count_approved',
        'where' => [
          'AND' => [
            'status_confirm' => 'approved'
          ]
        ]
      ],
      [
        'name'  => 'count_reject',
        'where' => [
          'AND' => [
            'status_confirm' => 'reject'
          ]
        ]
      ],
    ];

    foreach ($arr_where as $key => $record) {
      $key_name  = $record['name'];
      $sql_where = $record['where'];
      $count     = Amst::count($code . '_checkin', $sql_where);

      $return[$key_name] = $count;
    }

    return $return;
  }

  /**
  | Group | Control Working Time
  | Description | 1 ปรับสุถานะ 'Working Time'
  | $code | โค้ดโมดูล
  | $data | [
    user_id(ของคนปรับสถานะ),
    ids(id รายการ),
    status(approved / reject),
    remark
  ]
   */
  public static function updateStatusCheckInByIDS($code, $data = [])
  {
    $ids     = isset($data['ids']) && $data['ids'] ? $data['ids'] : 0;
    $status  = isset($data['status']) && $data['status'] ? $data['status'] : '';
    $user_id = isset($data['user_id']) && $data['user_id'] ? $data['user_id'] : -1;
    $remark  = isset($data['remark']) && $data['remark'] ? $data['remark'] : '';
    if (!$ids) {
      return Amst::repError('E0000', 'IDs not found.');
    }

    if (!$status) {
      return Amst::repError('E0000', 'Status not found.');
    }

    $temp_ids = [];
    if (is_array($ids)) {
      $temp_ids = $ids;
    } else {
      $temp_ids[] = $ids;
    }

    $date_now = date('Y-m-d H:i:s');
    $user_info = Amst::getByID('sys_user', ['id', 'full_name'], $user_id);

    $u_data = [
      'status_confirm'                    => $status,
      'status_confirm_update_by_user_id'  => $user_id,
      'status_confirm_update_by_fullname' => isset($user_info['id']) && $user_info['id'] ? $user_info['full_name'] : 'SYSTEM',
      'status_confirm_update_datetime'    => $date_now,
      'status_confirm_update_remark'      => $remark
    ];
    $where = [
      'AND' => [
        'id' => $temp_ids
      ]
    ];
    $api = Amst::update($code . '_checkin', $u_data, $where);
    if (isset($api['response_status']) && ($api['response_status'] == true)) {
      foreach ($temp_ids as $id) {
        $i_log_data = [
          'user_id'    => $user_id, // คนทำรายการ
          'checkin_id' => $id,
          'type'       => 'update_status_confirm',
          'value'      => $status,
        ];
        Amst::insert($code . '_checkin_log', $i_log_data);
      }

      $where_user = [
        'AND' => [
          // 'user_type[!]'       => ['admin', 'Admin', 'แอดมิน'],
          'is_ban'             => 0,
          'employee_status[!]' => 'day',
          'is_can_login'       => 1
        ]
      ];
      $user_ids = Amst::selectNoKey('sys_user', 'id', $where_user);

      $select_check_deduct = [
        'id', 'user_id', 'type', 'status_checkin', 'date'
      ];
      $where_check_deduct = [
        'AND' => [
          'id'      => $temp_ids,
          'user_id' => $user_ids
        ]
      ];
      $check_deduct_list = Amst::selectNoKey($code . '_checkin', $select_check_deduct, $where_check_deduct);
      foreach ($check_deduct_list as $key => $record) {
        $log_cost_data = [
          'user_id' => $record['user_id'],
          'type'    => $record['type'],
          'status'  => $record['status_checkin'],
          'date'    => $record['date']
        ];

        $log_cost_deduct = Phr_employee::handleGenEmployeeCostDeduct($code, $log_cost_data);
      }

      if ($status == 'approved') {
        $where_user = [
          'AND' => [
            // 'user_type[!]'    => ['admin', 'Admin', 'แอดมิน'],
            'is_ban'          => 0,
            'employee_status' => 'day',
            'is_can_login'    => 1
          ]
        ];
        $user_ids = Amst::selectNoKey('sys_user', 'id', $where_user);
        $select = [
          'id', 'user_id', 'type', 'status_checkin', 'date'
        ];
        $where = [
          'AND' => [
            'id'                => $temp_ids,
            'user_id'           => $user_ids,
            'status_checkin[!]' => 'missing'
          ]
        ];
        $checkin_list = Amst::selectNoKey($code . '_checkin', $select, $where);
        foreach ($checkin_list as $key => $record) {
          $log_cost_data = [
            'user_id' => $record['user_id'],
            'date'    => $record['date']
          ];
          Phr_employee::handleSaveSalaryPerformanceDayByConfirm($code, $log_cost_data);
        }
      }
    }
    return $api;
  }

  /**
  | Group | Report
  | Description | 1 สรุป 'checkin' head
  | $code | โค้ดโมดูล
  | $where | [
    date,
    role_ids(all หรือ id ต่างๆ)
  ]
   */
  public static function reportCheckInHead($code, $where = [])
  {
    $return = [
      'month_name'               => '',
      'month_day_amount'         => 0,
      'count_employee_checkin'   => 0,
      'count_employee_all'       => 0,
      'count_late'               => 0,
      'count_employee_late'      => 0,
      'count_very_late'          => 0,
      'count_employee_very_late' => 0,
      'count_missing'            => 0,
      'count_employee_missing'   => 0
    ];

    $date     = isset($where['date']) && $where['date'] ? $where['date'] : '';
    $role_ids = isset($where['role_ids']) ? $where['role_ids'] : [];

    $month_arr = [
      '',
      'มกราคม',
      'กุมภาพันธ์',
      'มีนาคม',
      'เมษายน',
      'พฤษภาคม',
      'มิถุนายน',
      'กรกฎาคม',
      'สิงหาคม',
      'กันยายน',
      'ตุลาคม',
      'พฤศจิกายน',
      'ธันวาคม'
    ];

    // ใช้ int เพราะ '05' ไม่ถูกแปลงเป็น key 5 ของ array
    $month = (int)date('n', strtotime($date));
    $month_name = isset($month_arr[$month]) ? $month_arr[$month] : '';
    $return['month_name'] = $month_name;

    // ใช้ปีของวันที่ที่เลือก ไม่ใช่ปีปัจจุบัน
    $year = (int)date('Y', strtotime($date));
    $month_day_amount = cal_days_in_month(CAL_GREGORIAN, $month, $year);
    $return['month_day_amount'] = $month_day_amount;

    $status_check = [
      'late',
      'very_late',
      'missing'
    ];

    $where_count_user = [
      // 'AND' => [
      //   'user_type[!]' => ['admin', 'Admin', 'แอดมิน'],
      // ]
    ];
    $where_count_emp_checkin_status = [
      'AND' => [
        'status_confirm' => 'approved',
        'is_last'        => 1
      ]
    ];
    $where_count_emp = [
      'AND' => [
        'status_confirm' => 'approved',
        'is_last'        => 1
      ],
      'GROUP' => 'user_id'
    ];
    if ($date) {
      $where_count_emp_checkin_status['AND']['date[~]'] = $date;
      $where_count_emp['AND']['date[~]']                = $date;
    }

    if ($role_ids && count($role_ids) > 0 && !in_array('all', $role_ids)) {
      $where_user = [
        'AND' => [
          'working_time_type_id' => $role_ids
        ]
      ];
      $user_ids = Amst::selectNoKey('sys_user', 'id', $where_user);
      $user_ids[] = -1;

      $where_count_user['AND']['id']                    = $user_ids;
      $where_count_emp_checkin_status['AND']['user_id'] = $user_ids;
      $where_count_emp['AND']['user_id']                = $user_ids;
    }

    $where_count_emp_checkin = $where_count_emp_checkin_status;
    // $where_count_emp_checkin['AND']['status_checkin'] = 'normal';
    $where_count_emp_checkin['AND']['status_checkin'] = ['normal', 'late', 'very_late'];
    $employee_checkin_list = Amst::selectNoKey($code . '_checkin', ['id', 'user_id'], $where_count_emp_checkin);
    $count_employee_checkin = 0;
    $temp_employee_checkin_user_ids = [];
    foreach ($employee_checkin_list as $key => $record) {
      if (!in_array($record['user_id'], $temp_employee_checkin_user_ids)) {
        $temp_employee_checkin_user_ids[] = $record['user_id'];
      }
    }
    $count_employee_checkin = count($temp_employee_checkin_user_ids);

    $count_employee_all     = Amst::count('sys_user', $where_count_user);

    $return['count_employee_checkin'] = $count_employee_checkin;
    $return['count_employee_all']     = $count_employee_all;

    foreach ($status_check as $value) {
      $where_count_emp_checkin_status['AND']['status_checkin'] = $value;
      $where_count_emp['AND']['status_checkin']                = $value;

      $count_emp_checkin = Amst::count($code . '_checkin', $where_count_emp_checkin_status);
      $emp_user_list     = Amst::selectNoKey($code . '_checkin', 'user_id', $where_count_emp);
      $count_emp         = count($emp_user_list);

      $return['count_' . $value]          = $count_emp_checkin;
      $return['count_employee_' . $value] = $count_emp;
    }

    return $return;
  }

  public static function setreportCheckInTableListDetail($code, $return, $where = [], $options = [])
  {
    // [ExtractList]
    $selected_records = isset($return['list']) ? $return['list'] : $return;

    $status_check = [
      'normal',
      'late',
      'very_late',
      'missing',
      'leave_work'
    ];

    $where_count_status = [
      'AND' => [
        'status_confirm' => 'approved',
        'is_last'        => 1
      ]
    ];

    if (isset($where['date']) && $where['date']) {
      $where_count_status['AND']['date[~]'] = $where['date'];
    }

    $where_deduct = [
      'AND' => [
        'is_use' => 1
      ]
    ];
    $deduct_list = Amst::selectNoKey($code . '_setting_deduct_money', '*', $where_deduct);

    $temp_deduct_list = [
      'late'      => [],
      'very_late' => [],
      'missing'   => []
    ];

    // foreach ($deduct_list as $key => $record) {
    //   if (isset($temp_deduct_list[$record['type']])) { 
    //     $temp_deduct_list[$record['type']][] = $record;
    //   }
    // }

    // Do Each Works...
    foreach ($selected_records as $key => $record) {
      // $selected_records[$key]['other_key'] = 'other_value';

      $where_count_status['AND']['user_id'] = $record['id'];

      $temp_count_status = [
        'late'       => 0,
        'very_late'  => 0,
        'missing'    => 0,
        'leave_work' => 0
      ];

      foreach ($status_check as $value) {
        $where_count_status['AND']['status_checkin'] = $value;
        $count = Amst::count($code . '_checkin', $where_count_status);

        $selected_records[$key]['count_' . $value] = $count;
        $temp_count_status[$value] = $count;
      }

      $where_sum_late = [
        'AND' => [
          'user_id'        => $record['id'],
          'status_checkin' => ['late', 'very_late'],
          'status_confirm' => 'approved',
          'is_last'        => 1, // ไม่รวมรายการที่ถูกแทนด้วยการลงย้อนหลังแล้ว
        ]
      ];
      if (isset($where['date']) && $where['date']) {
        $where_sum_late['AND']['date[~]'] = $where['date'];
      }
      $sum_late_minute = Amst::sum($code . '_checkin', 'checkin_late_minute', $where_sum_late);
      $selected_records[$key]['sum_late_minute'] = $sum_late_minute;

      $deduct_amount = 0;
      $deduct_price  = 0;

      $where_cost = [
        'AND' => [
          'user_id' => $record['id'],
          'type'    => 'deduct',
        ]
      ];

      if (isset($where['date']) && $where['date']) {
        $where_cost['AND']['date_deduct[~]'] = $where['date'];
      }

      $deduct_amount = Amst::count($code . '_employee_cost', $where_cost);
      $deduct_price  = Amst::sum($code . '_employee_cost', 'total_price', $where_cost);

      // foreach ($deduct_list as $key_deduct => $record_deduct) {
      //   $working_time_ids = json_decode($record_deduct['role_use'], false);
      //   if ($working_time_ids && in_array($record['working_time_type_id'], $working_time_ids)) {

      //     $deduct_info = $record_deduct;
      //     $type_deduct = $deduct_info['type'];
      //     $count_type  = $temp_count_status[$type_deduct];

      //     $amount           = 0;
      //     $cal_deduct_price = 0;

      //     if ($count_type >= $deduct_info['amount']) {
      //       $amount = floor($count_type / $deduct_info['amount']);
      //       $cal_deduct_price = $amount * $deduct_info['price'];
      //     }

      //     $deduct_amount += $amount;
      //     $deduct_price  += $cal_deduct_price;
      //   }
      // }

      $selected_records[$key]['deduct_amount'] = $deduct_amount;
      $selected_records[$key]['deduct_price']  = $deduct_price;
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
  | Group | Report
  | Description | 1 สรุป 'checkin' table
  | $code | โค้ดโมดูล
  | $where | [
    date,
    role_ids(all หรือ id ต่างๆ)
  ]
  | $options | [not_view(true|เอาแค่ข้อมูลหลักไป edit*เร็ว), selected_fields(String หรือ Array ของคอลัมภ์ที่ต้องการ), total_count(true|พร้อมจำนวนข้อมูลทั้งหมด ตามการกรองไม่แบ่งหน้า), page_no(1|เลขหน้า 0 คือส่งทั้งหมด), page_size(10|จำนวนข้อมูลต่อหน้า), sort_col(เรียงตามชื่อคอลัมภ์นี้), sort_by('ASC'|'DESC')]
   */
  public static function reportCheckInTable($code, $where = [], $options = [])
  {
    $return = []; // list
    $sql_where = [];

    $role_ids = isset($where['role_ids']) ? $where['role_ids'] : [];
    if ($role_ids && count($role_ids) > 0 && !in_array('all', $role_ids)) {
      $where_user_role = [
        'AND' => [
          'working_time_type_id' => $role_ids
        ]
      ];
      $user_ids = Amst::selectNoKey('sys_user', 'id', $where_user_role);
      $user_ids[] = -1;

      $sql_where['AND']['id'] = $user_ids;
    }

    $return = Amst::selectNoKey('sys_user', ['id', 'full_name', 'working_time_type_id'], $sql_where, $options);
    return static::setreportCheckInTableListDetail($code, $return, $where, $options);
  }
}
