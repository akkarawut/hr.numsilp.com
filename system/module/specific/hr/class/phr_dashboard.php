<?php

class Phr_dashboard
{

  /**
  | Group | todo_change_FunctionGroup
  | Description | 2.ดึงข้อมูล 'todo_change_FunctionGroup' ด้วยไอดี
  | $code | โค้ดโมดูล
  | $user_id | ไอดีรายการ
   */
  public static function getDashBoardByUserID($code, $user_id)
  {
    $return = [
      'working_status'           => '',
      'working_time_millisecond' => 0,
      'working_branch_name'      => '',
      'working_checkin_time'     => '',
      'working_checkout_time'    => '',
      'total_working_time'       => '',

      'performance_percent'      => 0,
      'count_missing'            => 0,
      'count_leave_work'         => 0,
      'count_late'               => 0,
      'count_day_work'           => 0
    ];

    $is_day_off               = 0;
    $date_first               = date('Y-m-01');
    $date_end                 = date('Y-m-t');
    $date_month               = date('Y-m');
    $date_now                 = date('Y-m-d');
    $datetime_now             = date('Y-m-d H:i:s');
    $working_status           = 'not_doing_anything';
    $working_time_millisecond = 0;

    $user_info = Amst::getByID('sys_user', '*', $user_id);

    $where_checkin = [
      'AND' => [
        'user_id' => $user_id,
        'date'    => $date_now
      ]
    ];
    $checkin_info = Amst::get($code . '_view_checkin', '*', $where_checkin);
    if (isset($checkin_info['id'])) {
      $working_status = 'checkin';

      $from_datetime = new DateTime($checkin_info['date'] . ' ' . $checkin_info['checkin_time']);
      $to_datetime   = new DateTime();

      if ($checkin_info['status'] == 'success') {
        $working_status = 'checkout';
        $to_datetime = new DateTime($checkin_info['date'] . ' ' . $checkin_info['checkout_time']);
      }

      if ($checkin_info['status_checkin'] == 'missing') {
        $working_status = 'missing';
      }

      $interval = $from_datetime->diff($to_datetime);

      $working_time_millisecond = ($interval->s + $interval->i * 60 + $interval->h * 3600 + $interval->days * 86400) * 1000;

      $working_branch_name = $checkin_info['checkin_branch_name'] ? $checkin_info['checkin_branch_name'] : $checkin_info['checkin_branch_name'];
      $return['working_branch_name'] = $working_branch_name;

      $working_checkin_time = $checkin_info['checkin_datetime'];
      $return['working_checkin_time'] = $working_checkin_time;

      $working_checkout_time = $checkin_info['checkout_datetime'];
      $return['working_checkout_time'] = $working_checkout_time;

      $total_working_time = $checkin_info['total_working_time'];
      $return['total_working_time'] = $total_working_time;
    }

    // =============================================================================
    // check holiday
    $where_holiday = [
      'AND' => [
        'from_date[<=]'        => $date_now,
        'to_date[>=]'          => $date_now,
        'role_use[~]'          => '"' . $user_info['working_time_type_id'] . '"',
        'request_user_ids[!~]' => '"' . $user_info['id'] . '"',
      ]
    ];

    $holiday_id = Amst::get($code . '_setting_holiday', 'id', $where_holiday);
    if ($holiday_id) {
      $is_day_off = 1;
      $working_status = 'holiday';
      $working_time_millisecond = 0;
    }

    if ($is_day_off === 0) {
      // check leave work
      $where_leave_work = [
        'AND' => [
          'user_id'        => $user_info['id'],
          'status_confirm' => 'approved',
          'from_date[<=]'  => $date_now,
          'to_date[>=]'    => $date_now,
        ]
      ];

      $leave_work_id = Amst::get($code . '_leave_work', 'id', $where_leave_work);
      if ($leave_work_id) {
        $is_day_off = 1;
        $working_status = 'leave_work';
        $working_time_millisecond = 0;
      }
    }
    // =============================================================================

    $return['working_time_millisecond'] = $working_time_millisecond;
    $return['working_status'] = $working_status;

    // =============================================================================
    // check performance

    $performance_percent = 100;

    $working_time_id    = $user_info['working_time_type_id'];
    $working_time_info  = Amst::getByID($code . '_setting_working_time', '*', $working_time_id);

    $working_start_date = $user_info['working_start_date'];
    if ($user_info['employee_status'] != 'trial_work') {
      if ($working_start_date <= $date_first) {
        $working_start_date = $date_first;
      }

      if ($user_info['employee_status'] == 'day') {
        $working_start_date =  date('Y-m-01');
      }
    } else {
      $working_start_date = $user_info['working_start_date'];
      $date_end           = $user_info['working_end_date'];
    }

    $firstDay = new DateTime("$working_start_date");
    $lastDay  = new DateTime("$date_end");

    $count_day_work = 0;

    $arr_day = [
      'sunday'    => 0,
      'monday'    => 1,
      'tuesday'   => 2,
      'wednesday' => 3,
      'thursday'  => 4,
      'friday'    => 5,
      'saturday'  => 6
    ];

    $temp_number_day = [];
    foreach ($arr_day as $name_day => $number) {
      if ($working_time_info[$name_day] == 1) {
        $temp_number_day[] = $number;
      }
    }

    $loop = 0;
    $currentDay = $firstDay;
    while ($currentDay <= $lastDay && $loop < 1000) {
      if (in_array($currentDay->format('w'), $temp_number_day)) {
        $count_day_work++;
      }

      $currentDay->modify('+1 day');
      $loop++;
    }

    $arr_where = [
      [
        'name'  => 'count_missing',
        'where' => [
          'AND' => [
            // 'status'         => 'success',
            'status_checkin' => 'missing',
            'status_confirm' => 'approved',
            'is_last'        => 1
          ]
        ]
      ],
      [
        'name'  => 'count_leave_work',
        'where' => [
          'AND' => [
            // 'status'         => 'success',
            'status_checkin' => 'leave_work',
            'status_confirm' => 'approved',
            'is_last'        => 1
          ]
        ]
      ],
      [
        'name'  => 'count_late',
        'where' => [
          'AND' => [
            // 'status'         => 'success',
            'status_checkin' => 'late',
            'status_confirm' => 'approved',
            'is_last'        => 1
          ]
        ]
      ],
      [
        'name'  => 'count_very_late',
        'where' => [
          'AND' => [
            // 'status'         => 'success',
            'status_checkin' => 'very_late',
            'status_confirm' => 'approved',
            'is_last'        => 1
          ]
        ]
      ],
    ];

    foreach ($arr_where as $key => $record) {
      $key_name                     = $record['name'];
      $sql_where                    = $record['where'];
      $sql_where['AND']['user_id']  = $user_id;
      $sql_where['AND']['date[>=]'] = $working_start_date;
      $sql_where['AND']['date[<=]'] = $date_end;
      $count                        = Amst::count($code . '_checkin', $sql_where);

      $return[$key_name]     = $count;
      if ($count && $key_name != 'count_leave_work') {
      // if ($count) {
        // $cal_percent         = ($count / $count_day_work) * 100;
        $cal_percent         = $count * 5;
        $performance_percent -= $cal_percent;
      }
    }

    $return['performance_percent'] = floor($performance_percent);
    $return['count_day_work']      = $count_day_work;

    // =============================================================================

    return $return;
  }
}
