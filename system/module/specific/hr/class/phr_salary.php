<?php

class Phr_salary {

  public static function setSalaryListDetail($code, $return, $options = []) {
    // [ExtractList]
    $selected_records = isset($return['list']) ? $return['list'] : $return;

    $bank_list         = Bank::select();
    $image_placeholder = File::getPlaceHolder();

    // Do Each Works...
    foreach ($selected_records as $key => $record) {
      // $selected_records[$key]['other_key'] = 'other_value';

      $bank_logo = $image_placeholder;
      $bank_info = isset($bank_list[$record['bank_code']]) ? $bank_list[$record['bank_code']] : [];
      if (isset($bank_info['abb'])) {
        $bank_logo = $bank_info['image'];
      }
      $selected_records[$key]['bank_logo'] = $bank_logo;
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
  | Group | Salary
  | Description | 1.ดึงข้อมูลลิส 'Salary'
  | $code | โค้ดโมดูล
  | $where | [
    user_id,
    user_fullname,
    status(draft / waiting_confirm / approved / reject),
    employee_status(day / month),
    date,
    user_code
  ]
  | $options | [not_view(true|เอาแค่ข้อมูลหลักไป edit*เร็ว), selected_fields(String หรือ Array ของคอลัมภ์ที่ต้องการ), total_count(true|พร้อมจำนวนข้อมูลทั้งหมด ตามการกรองไม่แบ่งหน้า), page_no(1|เลขหน้า 0 คือส่งทั้งหมด), page_size(10|จำนวนข้อมูลต่อหน้า), sort_col(เรียงตามชื่อคอลัมภ์นี้), sort_by('ASC'|'DESC')]
   */
  public static function selectSalary($code, $where = [], $options = []) {
    $return = []; // list
    $sql_where = [];

    if (isset($where['user_id']) && $where['user_id'] && $where['user_id'] != 'all') {
      $sql_where['AND']['user_id'] = $where['user_id'];
    }

    if (isset($where['user_fullname']) && $where['user_fullname']) {
      $sql_where['AND']['user_fullname[~]'] = $where['user_fullname'];
    }

    if (isset($where['status']) && $where['status'] && $where['status'] != 'all') {
      $sql_where['AND']['status'] = $where['status'];
    }

    if (isset($where['employee_status']) && $where['employee_status'] && $where['employee_status'] != 'all') {
      $employee_status = [];
      if ($where['employee_status'] == 'month') {
        $employee_status = [
          'month', 'trial_work'
        ];
      } else {
        $employee_status = [
          'day'
        ];
      }
      $sql_where['AND']['employee_status'] = $employee_status;
    }

    if (isset($where['date']) && $where['date']) {
      $sql_where['AND']['installment_date[~]'] = $where['date'];
    }

    if (isset($where['installment_date']) && $where['installment_date']) {
      $sql_where['AND']['installment_date'] = $where['installment_date'];
    }

    $user_code = isset($where['user_code']) ? $where['user_code'] : [];
    if ($user_code && count($user_code) > 0 && !in_array('all', $user_code)) {
      $where_user_role = [
        'AND' => [
          'working_time_type_id' => $user_code
        ]
      ];
      $user_ids = Amst::selectNoKey('sys_user', 'id', $where_user_role);
      $user_ids[] = -1;

      $sql_where['AND']['user_id'] = $user_ids;
    }

    $user_filter_code = isset($where['user_filter_code']) ? $where['user_filter_code'] : '';
    if ($user_filter_code && $user_filter_code != 'all') {
      $where_user_role = [
        'AND' => [
          'user_code' => $user_filter_code
        ]
      ];
      $user_ids = Amst::selectNoKey('sys_user', 'id', $where_user_role);
      $user_ids[] = -1;

      $sql_where['AND']['user_id'] = $user_ids;
    }

    $role_ids = isset($where['role_ids']) ? $where['role_ids'] : [];
    if ($role_ids && count($role_ids) > 0 && !in_array('all', $role_ids)) {
      $where_user_role = [
        'AND' => [
          'working_time_type_id' => $role_ids
        ]
      ];
      $user_ids = Amst::selectNoKey('sys_user', 'id', $where_user_role);
      $user_ids[] = -1;

      $sql_where['AND']['user_id'] = $user_ids;
    }

    $sql_where['ORDER']['installment_date'] = 'DESC';

    $selected_fields = Amst::getSelectedFields($options);
    $return = Amst::selectNoKey($code . '_view_salary', $selected_fields, $sql_where, $options);
    return static::setSalaryListDetail($code, $return, $options);
  }

  public static function setSalaryDetail($code, $record, $options = []) {
    if (!isset($record['id'])) {
      return $record;
    }

    $user_info = Phr_employee::getEmployeeByID($code, $record['user_id'], ['is_not_cal_performance' => true, 'is_not_file' => true]);

    $where_performance = [
      'AND' => [
        'user_id' => $record['user_id'],
        'date'    => $record['installment_date']
      ]
    ];
    $performance_info = Amst::get($code . '_performance', '*', $where_performance);

    $where_employee_cost_log_list = [
      'AND' => [
        'user_id'          => $record['user_id'],
        'installment_date' => $record['installment_date']
      ]
    ];
    $employee_cost_list = Amst::selectNoKey($code . '_view_employee_cost_log', '*', $where_employee_cost_log_list);

    $record['user_info']        = $user_info;
    $record['performance_info'] = $performance_info;
    $record['employee_cost_list'] = $employee_cost_list;

    // Do Single Works...
    // $record['other_key'] = 'other_value';

    return $record;
  }

  /**
  | Group | Salary
  | Description | 2.ดึงข้อมูล 'Salary' ด้วยไอดี
  | $code | โค้ดโมดูล
  | $id | ไอดีรายการ
  | $options | [not_view(true|เอาแค่ข้อมูลหลักไป edit*เร็ว), selected_fields(String หรือ Array ของคอลัมภ์ที่ต้องการ)]
   */
  public static function getSalaryByID($code, $id, $options = []) {
    $return = null;

    $selected_fields = Amst::getSelectedFields($options, ['id', 'system_delete']);
    $model = Amst::getByID($code . '_view_salary', $selected_fields, $id, $options);
    if (isset($model['id'])) {
      $return = static::setSalaryDetail($code, $model, $options);
    }
    return $return;
  }

  /**
  | Group | Salary
  | Description | 3.เพิ่ม 'Salary'
  | $code | โค้ดโมดูล
  | $data | [
    
  ]
   */
  public static function addNewSalary($code, $data = []) {
    $rep = [
      'insert_id' => false
    ];

    return Amst::insert($code . '_salary', $data);
  }

  /**
  | Group | Salary
  | Description | 4.อัพเดท 'Salary'
  | $code | โค้ดโมดูล
  | $id | ไอดีรายการ
  | $data | [

  ]
   */
  public static function updateSalary($code, $id, $data = []) {
    $rep = [
      'update_result' => false
    ];

    $model = Amst::getByID($code . '_salary', ['id'], $id);
    if (!isset($model['id'])) {
      return Amst::repError('E0005', 'tbl_salary not found.');
    }

    $u_data = $data;

    $api = Amst::updateByID($code . '_salary', $u_data, $id);
    if (isset($api['response_status']) && ($api['response_status'] == true)) {
    }
    return $api;
  }

  /**
  | Group | Salary
  | Description | 5.ลบ 'Salary'
  | $code | โค้ดโมดูล
  | $id | ไอดีรายการ
   */
  public static function deleteSalary($code, $id) {
    $rep = [
      'delete_result' => false
    ];

    $api = static::_checkDeleteSalary($code, $id);
    if (isset($api['response_status']) && ($api['response_status'] == true)) {
      $rep['delete_result'] = Amst::deleteByID($code . '_salary', $id);
      return Amst::repSuccess($rep);
    }
    return $api; // ERROR SURE
  }

  private static function _checkDeleteSalary($code, $id) {
    $rep = [
      'id' => $id
    ];

    // [Model_Exist]
    $model = Amst::getByID($code . '_salary', ['id'], $id);
    if (!isset($model['id'])) {
      return Amst::repError('E0005', 'tbl_salary not found.');
    }

    return Amst::repSuccess($rep);
  }

  /**
  | Group | Control Salary
  | Description | 1 count 'Salary'
  | $code | โค้ดโมดูล
   */
  public static function countControlSalary($code) {
    $return = [
      'count_month_waiting_confirm' => 0,
      'count_day_waiting_confirm'   => 0,
      'count_month_confirm'         => 0,
      'count_day_confirm'           => 0
    ];

    $arr_where = [
      [
        'name'  => 'count_month_waiting_confirm',
        'where' => [
          'AND' => [
            'status'          => 'waiting_confirm',
            'employee_status' => ['month', 'trial_work']
          ]
        ]
      ],
      [
        'name'  => 'count_day_waiting_confirm',
        'where' => [
          'AND' => [
            'status'          => 'waiting_confirm',
            'employee_status' => ['day']
          ]
        ]
      ],
      [
        'name'  => 'count_month_confirm',
        'where' => [
          'AND' => [
            'status'          => ['approved', 'reject'],
            'employee_status' => ['month', 'trial_work']
          ]
        ]
      ],
      [
        'name'  => 'count_day_confirm',
        'where' => [
          'AND' => [
            'status'          => ['approved', 'reject'],
            'employee_status' => ['day']
          ]
        ]
      ],
    ];

    foreach ($arr_where as $key => $record) {
      $key_name  = $record['name'];
      $sql_where = $record['where'];
      $count     = Amst::count($code . '_salary', $sql_where);

      $return[$key_name] = $count;
    }

    return $return;
  }

  /**
  | Group | Control Salary
  | Description | 2.Update status 'Salary' by IDS
  | $code | โค้ดโมดูล
  | $data | [
    user_id(ของคนปรับสถานะ),
    ids(id รายการ),
    status(draft / waiting_confirm / approved / reject),
    remark
  ]
   */
  public static function updateStatusSalaryByIDS($code, $data = []) {
    $user_id = isset($data['user_id']) && $data['user_id'] ? $data['user_id'] : -1;
    $ids     = isset($data['ids']) && $data['ids'] ? $data['ids'] : 0;
    $status  = isset($data['status']) && $data['status'] ? $data['status'] : '';
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
      'status'                    => $status,
      'status_update_by_user_id'  => $user_id,
      'status_update_by_fullname' => isset($user_info['id']) && $user_info['id'] ? $user_info['full_name'] : 'SYSTEM',
      'status_update_datetime'    => $date_now,
      'status_update_remark'      => $remark
    ];
    $where = [
      'AND' => [
        'id' => $temp_ids
      ]
    ];

    $api = Amst::update($code . '_salary', $u_data, $where);
    if (isset($api['response_status']) && ($api['response_status'] == true)) {

      $cep_info = $user_info;

      if ($status == 'approved') {

        $salary_list = Amst::selectNoKey($code . '_salary', ['id', 'user_id', 'salary', 'employee_status', 'installment_date'], $where);

        $noti_data_list = [];
        foreach ($salary_list as $key => $salary_record) {
          $noti_data = [
            'user_id'      => $salary_record['user_id'],
            'from_user_id' => $cep_info['id'],
            'type'         => 'system_approved_salary',
            'salary'       => $salary_record['salary'],
            'salary_type'  => $salary_record['employee_status'],
            'date'         => $salary_record['installment_date'],
            'ref_id'       => $salary_record['id']
          ];

          $noti_data_list[] = $noti_data;
        }

        foreach ($noti_data_list as $key => $noti_data) {
          Phr_notification::addNewNotification($code, $noti_data);
        }
      } else if ($status == 'reject') {

        $salary_list     = Amst::selectNoKey($code . '_salary', ['id', 'user_id', 'salary', 'employee_status', 'installment_date'], $where);
        $user_salary_ids = array_unique(array_column($salary_list, 'user_id'));

        $where_hr = [
          'AND' => [
            'user_code'    => 'xacpn', // hr
            'is_can_login' => 1,
            'is_ban'       => 0
          ]
        ];
        $hr_list  = Amst::select('sys_user', '*', $where_hr);

        $user_salary_list  = Amst::select('sys_user', ['id', 'full_name'], ['id' => $user_salary_ids]);

        $noti_data_list = [];
        foreach ($hr_list as $key_hr => $hr_record) {
          foreach ($salary_list as $key_salary => $salary_record) {

            $user_salary_info = isset($user_salary_list[$salary_record['user_id']]) ? $user_salary_list[$salary_record['user_id']] : [];

            $noti_data = [
              'user_id'       => $hr_record['id'],
              'from_user_id'  => $cep_info['id'],
              'type'          => 'system_reject_salary',
              'ceo_name'      => $cep_info['full_name'],
              'salary_type'   => $salary_record['employee_status'],
              'employee_name' => isset($user_salary_info['full_name']) ? $user_salary_info['full_name'] : '-',
              'remark'        => $remark,
              'ref_id'        => $salary_record['id']
            ];

            $noti_data_list[] = $noti_data;
          }
        }

        foreach ($noti_data_list as $key => $noti_data) {
          Phr_notification::addNewNotification($code, $noti_data);
        }
      }
    }
    return $api;
  }

  /**
  | Group | Refresh Salary
  | Description | 1.Refresh 'Salary'
  | $code | โค้ดโมดูล
  | $id | ไอดีรายการ
   */
  public static function RefreshSalary($code, $id) {
    $rep = [
      'delete_result' => false
    ];

    $date_now   = date('Y-m-d');

    $model = Amst::getByID($code . '_salary', '*', $id);
    if (!isset($model['id'])) {
      return Amst::repError('E0005', 'tbl_salary not found.');
    }

    $where_deduct = [
      'AND' => [
        'is_use' => 1
      ]
    ];
    $deduct_list = Amst::selectNoKey($code . '_setting_deduct_money', '*', $where_deduct);

    $user_info = Amst::getByID('sys_user', '*', $model['user_id']);

    // performance ของรอบนี้บันทึก date = to_date ของรอบเงินเดือน (ทั้งรายเดือนและรายวัน)
    // เดิมใช้ installment_date ซึ่งเป็นวันของรอบก่อน ทำให้ไปเขียนทับ performance รอบก่อน
    $sql_where = [
      'AND' => [
        'user_id' => $model['user_id'],
        'date'    => $model['to_date']
      ]
    ];
    $history_performance_info = Amst::get($code . '_performance', '*', $sql_where);

    if (isset($history_performance_info['id'])) {
      $arr_day = [
        'sunday'    => 0,
        'monday'    => 1,
        'tuesday'   => 2,
        'wednesday' => 3,
        'thursday'  => 4,
        'friday'    => 5,
        'saturday'  => 6
      ];

      $temp_salary = [];

      $temp_working_time_number_day = [];

      $u_data = [
        'count_day_work'      => 0,
        'count_missing'       => 0,
        'count_leave_work'    => 0,
        'count_late'          => 0,
        'count_very_late'     => 0,
        'percent_performance' => 100,
      ];

      $percent_performance = 100;

      $installment_date   = '';
      $working_start_date = $model['from_date'];
      $date_end           = $model['to_date'];

      if ($user_info['employee_status'] == 'day') {
        $installment_date   = $model['installment_date'];
        $working_start_date = date('Y-m-01', strtotime($model['installment_date']));
        $date_end           = date('Y-m-t', strtotime($working_start_date));
      }

      $firstDay = new DateTime("$working_start_date");
      $lastDay  = new DateTime("$date_end");

      $working_time_info = Amst::getByID($code . '_setting_working_time', '*', $user_info['working_time_type_id']);

      $temp_working_time_number_day_info = [];
      if (isset($working_time_info['id'])) {
        foreach ($arr_day as $name_day => $number) {
          if ($working_time_info[$name_day] == 1) {
            $temp_working_time_number_day_info[] = $number;
          }
        }
      }

      $count_day_work = 0;

      $loop = 0;
      $currentDay = $firstDay;
      while ($currentDay <= $lastDay && $loop < 1000) {
        if (in_array($currentDay->format('w'), $temp_working_time_number_day_info)) {
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

      $temp_count_status = [];
      foreach ($arr_where as $key => $record_where) {
        $key_name                     = $record_where['name'];
        $sql_where                    = $record_where['where'];
        $sql_where['AND']['user_id']  = $user_info['id'];
        $sql_where['AND']['date[>=]'] = $working_start_date;
        $sql_where['AND']['date[<=]'] = $date_end;
        $count                        = Amst::count($code . '_checkin', $sql_where);

        $u_data[$key_name]            = $count;
        $temp_count_status[$key_name] = $count;
        if ($count && $key_name != 'count_leave_work') {
          // if ($count) {
          // $cal_percent         = ($count / $count_day_work) * 100;
          $cal_percent         = $count * 5;
          $percent_performance -= $cal_percent;

          $u_data[$key_name . '_percent'] = $cal_percent;
        }
      }

      $u_data['count_day_work']      = $count_day_work;
      $u_data['percent_performance'] = floor($percent_performance);

      Amst::updateByID($code . '_performance', $u_data, $history_performance_info['id']);

      $count_fine = 0;
      $fine_price = 0;
      // if ($user_info['employee_status'] != 'day') {
      //   foreach ($deduct_list as $key_deduct => $record_deduct) {
      //     $working_time_ids = json_decode($record_deduct['role_use'], false);
      //     if ($working_time_ids && in_array($user_info['working_time_type_id'], $working_time_ids)) {

      //       $deduct_info = $record_deduct;
      //       $type_deduct = 'count_' . $deduct_info['type'];
      //       $count_type  = $temp_count_status[$type_deduct];

      //       $amount           = 0;
      //       $cal_deduct_price = 0;

      //       if ($count_type >= $deduct_info['amount']) {
      //         $amount = floor($count_type / $deduct_info['amount']);
      //         $cal_deduct_price = $amount * $deduct_info['price'];
      //       }

      //       $count_fine += $amount;
      //       $fine_price += $cal_deduct_price;
      //     }
      //   }
      // }

      $where_employee_cost = [
        'AND' => [
          'user_id'          => $user_info['id'],
          'installment_date' => $working_start_date
        ]
      ];

      if ($user_info['employee_status'] == 'day') {
        $where_employee_cost = [
          'AND' => [
            'user_id'          => $user_info['id'],
            'installment_date' => $installment_date
          ]
        ];
      }

      $log_employee_cost_list    = Amst::selectNoKey($code . '_employee_cost_log', '*', $where_employee_cost);
      $temp_employee_cost_list   = array_unique(array_column($log_employee_cost_list, 'employee_cost_id'));
      $temp_employee_cost_list[] = -1;

      $where_employee_cost = [
        'AND' => [
          'id[!]'   => $temp_employee_cost_list,
          'user_id' => $user_info['id'],
          'status'  => 'in_progress'
        ]
      ];
      $employee_cost_list = Amst::selectNoKey($code . '_employee_cost', '*', $where_employee_cost);

      $count_employee_cost       = 0;
      $employee_cost_total_price = 0;

      foreach ($log_employee_cost_list as $key_cost_log => $record_cost_log) {
        $count_employee_cost       += 1;
        $employee_cost_total_price += $record_cost_log['price_per_period'];
      }

      if (count($employee_cost_list) > 0) {
        foreach ($employee_cost_list as $key_cost => $record_cost) {
          $count_employee_cost       += 1;
          $employee_cost_total_price += $record_cost['price_per_period'];

          if (!in_array($record_cost['id'], $temp_employee_cost_list)) {
            $period_sum_pay = $record_cost['period_sum_pay'] + 1;

            $i_cost_data = [
              'employee_cost_id'  => $record_cost['id'],
              'installment_date'  => $working_start_date, // date_check_month,
              'user_id'           => $user_info['id'],
              'period_no'         => $period_sum_pay,
              'period_all'        => $record_cost['period'],
              'price_per_period'  => $record_cost['price_per_period']
            ];

            if ($user_info['employee_status'] == 'day') {
              $i_cost_data['installment_date'] = $installment_date;
            }

            $api_cost = Amst::insert($code . '_employee_cost_log', $i_cost_data);
            if (isset($api_cost['response_status']) && $api_cost['response_status'] == true) {
              $where_count_pay_cost = [
                'AND' => [
                  'employee_cost_id'  => $record_cost['id'],
                  'user_id'           => $user_info['id']
                ]
              ];
              $count_pay_cost = Amst::count($code . '_employee_cost_log', $where_count_pay_cost);

              $u_data_count_pay_cost = [
                'total_price_pay[+]' => $record_cost['price_per_period'],
                'period_sum_pay'     => $count_pay_cost
              ];

              if ($count_pay_cost >= $record_cost['period']) {
                $u_data_count_pay_cost['status'] = 'success';
              }

              Amst::updateByID($code . '_employee_cost', $u_data_count_pay_cost, $record_cost['id']);
            }
          }
        }
      }

      $is_fine = 0;
      if ($count_fine > 0) {
        $is_fine = 1;
      }

      // รายเดือน/ทดลองงาน จ่ายเดือนละ 2 รอบ -> ใช้ครึ่งหนึ่งให้ตรงกับตอนสร้างรายการ (Phr_employee)
      // เดิมใช้ยอดเต็ม ทำให้กด refresh แล้วเงินเดือนกลายเป็น 2 เท่า
      $salary_period = $user_info['salary'];
      if ($user_info['employee_status'] != 'day') {
        $salary_period = $user_info['salary'] / 2;
      }

      $total_price = $salary_period - $fine_price - $employee_cost_total_price;

      $is_security    = 0;
      $security_price = 0;
      // ทดลองงานไม่หักประกันสังคม -> เช็คก่อนหัก (เดิมหักไปแล้วค่อยตั้งเป็น 0 ทำให้ยอดสุทธิไม่ตรงกับที่แสดง)
      if ($user_info['employee_status'] != 'day' && $user_info['employee_status'] != 'trial_work') {
        $is_security    = $user_info['is_security'];
        $security_price = $user_info['security_price'] / 2;

        if ($user_info['is_security']) {
          if ($user_info['security_unit'] == 'bath') {
            $total_price -= $security_price;
          } else {
            $security_price = ($salary_period * ($user_info['security_price'] / 100));
            $total_price -= $security_price;
          }
        }
      }

      $u_salary_data = [
        'salary'                    => $salary_period,
        'is_security'               => $is_security,
        'security_price'            => $security_price,
        'is_fine'                   => $is_fine,
        'count_fine'                => $count_fine,
        'fine_price'                => $fine_price,
        'count_employee_cost'       => $count_employee_cost,
        'employee_cost_total_price' => $employee_cost_total_price,
        'total_price'               => $total_price
      ];

      Amst::updateByID($code . '_salary', $u_salary_data, $model['id']);
    }

    return Amst::repSuccess();
  }
}
