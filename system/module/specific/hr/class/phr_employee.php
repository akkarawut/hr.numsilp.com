<?php

class Phr_employee {

  /**
  | Group | Con Employee
  | Description | con 'Employee'
  | $code | โค้ดโมดูล
   */
  public static function handleSaveSalaryPerformanceMonthAuto($code) {
    $return = [
      'user_need_to_update' => 0,
      'total_user_update' => 0,
    ];

    // Initial data
    $date_now         = date('Y-m-d');
    $working_time_type_list = Amst::select($code . '_setting_working_time');
    $deduct_list = Amst::selectNoKey($code . '_setting_deduct_money', '*', ['is_use' => 1]);

    $sql_where = [
      'AND' => [
        'employee_status'   => ['month', 'trial_work'],
        'day_cal_salary[!]' => 0,
        'OR' => [
          'day_cal_salary' => date('d', strtotime($date_now)),
          'day_cal_salary2' => date('d', strtotime($date_now)),
        ]
      ]
    ];
    $user_list = Amst::selectNoKey('sys_user', '*', $sql_where);
    $return['user_need_to_update'] = count($user_list);

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
    foreach ($user_list as $key => $record) {

      $where = [
        'user_id' => $record['id'],
        'date' => $date_now
      ];
      $history_check = Amst::get($code . '_performance', '*', $where);

      if (!$history_check) {
        // find last date before today
        $first_day_to_calculate = $record['working_start_date'];

        $where = [
          'user_id' => $record['id'],
          'ORDER' => ['id' => 'DESC'],
        ];
        $performance = Amst::get($code . '_performance', '*', $where);
        if ($performance) {
          if ($performance['date'] >= $first_day_to_calculate) {
            $first_day_to_calculate = $performance['date'];
          }
        }


        // start doing performance
        $i_data = [
          'user_id'             => $record['id'],
          'employee_status'     => $record['employee_status'],
          'installment_date'    => $first_day_to_calculate,
          'date'                => $date_now, // date_check_month,
          'month'               => date('m', strtotime($date_now)),
          'year'                => date('Y', strtotime($date_now)),
          'count_day_work'      => 0,
          'count_missing'       => 0,
          'count_leave_work'    => 0,
          'count_late'          => 0,
          'count_very_late'     => 0,
          'percent_performance' => 100,
        ];

        $percent_performance = 100;

        $firstDay = new DateTime("$first_day_to_calculate");
        $lastDay  = new DateTime("$date_now");

        $temp_working_time_number_day_info = [];
        if (isset($temp_working_time_number_day[$record['working_time_type_id']])) {
          $temp_working_time_number_day_info = $temp_working_time_number_day[$record['working_time_type_id']];
        } else {
          $working_time_info = isset($working_time_type_list[$record['working_time_type_id']]) ? $working_time_type_list[$record['working_time_type_id']] : [];
          if (isset($working_time_info['id'])) {
            foreach ($arr_day as $name_day => $number) {
              if ($working_time_info[$name_day] == 1) {
                $temp_working_time_number_day[$working_time_info['id']][] = $number;
                $temp_working_time_number_day_info[] = $number;
              }
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
          $sql_where['AND']['user_id']  = $record['id'];
          $sql_where['AND']['date[>=]'] = $first_day_to_calculate;
          $sql_where['AND']['date[<=]'] = $date_now;
          $count                        = Amst::count($code . '_checkin', $sql_where);

          $i_data[$key_name]            = $count;
          $temp_count_status[$key_name] = $count;
          if ($count && $key_name != 'count_leave_work') {
            // if ($count) {
            $cal_percent                    = ($count / $count_day_work) * 100;
            $i_data[$key_name . '_percent'] = $cal_percent;
            $percent_performance            -= $cal_percent;
          }
        }

        $i_data['count_day_work']      = $count_day_work;
        $i_data['percent_performance'] = floor($percent_performance);

        Amst::insert($code . '_performance', $i_data);

        $count_fine = 0;
        $fine_price = 0;
        // foreach ($deduct_list as $key_deduct => $record_deduct) {
        //   $working_time_ids = json_decode($record_deduct['role_use'], false);
        //   if ($working_time_ids && in_array($record['working_time_type_id'], $working_time_ids)) {

        //     $deduct_info = $record_deduct;
        //     $type_deduct = 'count_' . $deduct_info['type'];
        //     $count_type  = $temp_count_status[$type_deduct];

        //     $amount           = 0;
        //     $cal_deduct_price = 0;

        //     if ($count_type >= $deduct_info['amount']) {
        //       $amount = floor($count_type / $deduct_info['amount']);
        //       $cal_deduct_price = $amount * $deduct_info['price'];
        //     }

        //     $count_fine += $amount;
        //     $fine_price += $cal_deduct_price;
        //   }
        // }

        $where_employee_cost = [
          'AND' => [
            'user_id' => $record['id'],
            'status'  => 'in_progress'
          ]
        ];
        $employee_cost_list = Amst::selectNoKey($code . '_employee_cost', '*', $where_employee_cost);

        $count_employee_cost       = 0;
        $employee_cost_total_price = 0;

        if (count($employee_cost_list) > 0) {
          foreach ($employee_cost_list as $key_cost => $record_cost) {

            $count_employee_cost       += 1;
            $employee_cost_total_price += $record_cost['price_per_period'];
            $period_sum_pay = $record_cost['period_sum_pay'] + 1;

            $i_cost_data = [
              'employee_cost_id'  => $record_cost['id'],
              'installment_date'  => $first_day_to_calculate, // date_check_month,
              'user_id'           => $record['id'],
              'period_no'         => $period_sum_pay,
              'period_all'        => $record_cost['period'],
              'price_per_period'  => $record_cost['price_per_period']
            ];
            $api_cost = Amst::insert($code . '_employee_cost_log', $i_cost_data);
            if (isset($api_cost['response_status']) && $api_cost['response_status'] == true) {
              $where_count_pay_cost = [
                'AND' => [
                  'employee_cost_id'  => $record_cost['id'],
                  'user_id'           => $record['id']
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

        $is_fine = 0;
        if ($count_fine > 0) {
          $is_fine = 1;
        }

        $total_price = ($record['salary'] / 2) - $fine_price - $employee_cost_total_price;

        $is_security    = $record['is_security'];
        $security_price = $record['security_price'] / 2;

        // ทดลองงานไม่หักประกันสังคม -> ต้องเช็คก่อนหักออกจากยอดสุทธิ
        // (เดิมหักไปแล้วค่อยตั้ง security_price = 0 ทำให้ยอดสุทธิไม่ตรงกับที่แสดง)
        if ($record['employee_status'] == 'trial_work') {
          $is_security    = 0;
          $security_price = 0;
        } else if ($record['is_security'] && $record['employee_status'] != 'day') {
          if ($record['security_unit'] == 'bath') {
            $total_price -= $security_price;
          } else {
            $security_price = (($record['salary'] / 2) * ($record['security_price'] / 100));
            $total_price -= $security_price;
          }
        }

        // gen no record
        $where_count = [
          // 'AND' => [
          //   'insert_date_time[~]' => $date_now
          // ]
        ];
        $count_commission = Amst::count($code . '_salary', $where_count);
        $count_commission = $count_commission + 1;
        $number_no        = str_pad($count_commission, 5, '0', STR_PAD_LEFT);
        $no               = 'S' . $number_no;

        $i_salary_data = [
          'user_id'                   => $record['id'],
          'ref_no'                    => $no,
          'status'                    => 'draft',
          'employee_status'           => $record['employee_status'],
          'installment_date'          => $first_day_to_calculate, // date_check_month, 
          'from_date'                 => $first_day_to_calculate,
          'to_date'                   => $date_now,
          'day'                       => date('d', strtotime($date_now)),
          'month'                     => date('m', strtotime($date_now)),
          'year'                      => date('Y', strtotime($date_now)),
          'salary'                    => $record['salary'] / 2,
          'is_security'               => $is_security,
          'security_price'            => $security_price,
          'security_unit'             => $record['security_unit'],
          'is_fine'                   => $is_fine,
          'count_fine'                => $count_fine,
          'fine_price'                => $fine_price,
          'count_employee_cost'       => $count_employee_cost,
          'employee_cost_total_price' => $employee_cost_total_price,
          'total_price'               => $total_price,
          'bank_code'                 => $record['bank_code'],
          'bank_name'                 => $record['bank_name'],
          'bank_account_name'         => $record['bank_account_name'],
          'bank_account_number'       => $record['bank_account_number']
        ];

        Amst::insert($code . '_salary', $i_salary_data);
        $return['total_user_update']++;
      }
    }

    return $return;
  }

  // /**
  // | Group | Con Employee
  // | Description | ล้างให้เกลี้ยง
  // | $code | โค้ดโมดูล
  //  */
  // public static function clearSalary($code) {
  //   $return = true;

  //   DB::delete('func_' . $code . '_salary');
  //   DB::delete('func_' . $code . '_performance');

  //   return true;
  // }

  /**
  | Group | Not Con Employee
  | Description | Not Con 'Employee'
  | $code | โค้ดโมดูล
  | $where | [
    date,
    user_id
  ]
   */
  public static function handleSaveSalaryPerformanceDayAuto($code, $where = []) {
    $return = true;

    $date    = isset($where['date']) && $where['date'] ? $where['date'] : null;
    $user_id = isset($where['user_id']) && $where['user_id'] ? $where['user_id'] : null;

    // ยิงทุกนาที ของ user แบบรายวันที่ checkin เท่านั้น

    $time_now = date('H:i:s');

    $date_now = date('Y-m-d');
    if ($date) {
      $date_now = $where['date'];
    }
    $day_name = strtolower(date('l', strtotime($date_now)));

    // $date_now   = '2024-02-29';

    $start_date_performance = date('Y-m-01', strtotime($date_now));
    $end_date_performance   = date('Y-m-t', strtotime($start_date_performance));
    // $end_date_performance   = $date_now;

    $day                    = date('d', strtotime($date_now));
    $month                  = date('m', strtotime($date_now));
    $year                   = date('Y', strtotime($date_now));

    $where_working_time_type_list = [
      'AND' => [
        $day_name => 1
      ]
    ];
    $working_time_type_list  = Amst::select($code . '_setting_working_time', '*', $where_working_time_type_list);

    $working_time_type_ids  = [];
    $temp_working_time_type = [];
    foreach ($working_time_type_list as $key => $record) {
      $set_date_check = date('H:i:s', strtotime($record[$day_name . '_totime']));
      if ($set_date_check <= $time_now) {
        $working_time_type_ids[] = $record['id'];
      }
    }
    $working_time_type_ids[] = -1;

    $where_deduct = [
      'AND' => [
        'is_use' => 1
      ]
    ];
    $deduct_list = Amst::selectNoKey($code . '_setting_deduct_money', '*', $where_deduct);

    $sql_where = [
      'AND' => [
        'employee_status'      => 'day',
        'working_time_type_id' => $working_time_type_ids
      ]
    ];

    if ($user_id) {
      $sql_where['AND']['id'] = $user_id;
    }

    $user_list  = Amst::selectNoKey('sys_user', '*', $sql_where);
    $user_ids   = array_unique(array_column($user_list, 'id'));

    if (count($user_ids) > 0) {
      $log_data = [
        'date_now' => $date_now,
        'time_now' => $time_now,
        'text'     => 'พนักงานรับเงินเดือนครบแล้วในช่วงวันเวลานี้'
      ];
      $log_data = json_encode($log_data);
      Log::add(0, $log_data, 0, 'backend');

      return Amst::repError('E0000', 'พนักงานรับเงินเดือนครบแล้วในช่วงวันเวลานี้');
    }

    $user_ids[] = -1;

    // เผื่อใช้
    // $where_checkin = [
    //   'AND' => [
    //     'date'    => $date_now,
    //     'user_id' => $user_ids,
    //     'is_last' => 1
    //   ]
    // ];
    // $checkin_list = Amst::selectNoKey($code . '_checkin', '*', $where_checkin);
    // $temp_checkin_list = [];
    // foreach ($checkin_list as $key => $record) {
    //   $temp_checkin_list[$record['user_id']] = $record;
    // }

    $where_salary_list = [
      'AND' => [
        'user_id'          => $user_ids,
        'employee_status'  => 'day',
        'installment_date' => $date_now
      ]
    ];
    $salary_list = Amst::selectNoKey($code . '_salary', ['id', 'user_id'], $where_salary_list);
    $temp_user_salary_list = [];
    foreach ($salary_list as $key => $record) {
      $temp_user_salary_list[$record['user_id']] = $record;
    }

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
    foreach ($user_list as $key => $record) {

      if (isset($temp_user_salary_list[$record['id']])) {
        continue;
      }

      $i_data = [
        'user_id'             => $record['id'],
        'employee_status'     => $record['employee_status'],
        'installment_date'    => $end_date_performance,
        'date'                => $end_date_performance, // date_check_month,
        'month'               => $month,
        'year'                => $year,
        'count_day_work'      => 0,
        'count_missing'       => 0,
        'count_leave_work'    => 0,
        'count_late'          => 0,
        'count_very_late'     => 0,
        'percent_performance' => 100,
      ];

      $percent_performance = 100;

      // if ($record['employee_status'] == 'day') {
      //   $working_start_date =  date('Y-m-01');
      // }

      $firstDay = new DateTime("$start_date_performance");
      $lastDay  = new DateTime("$end_date_performance");

      $temp_working_time_number_day_info = [];
      if (isset($temp_working_time_number_day[$record['working_time_type_id']])) {
        $temp_working_time_number_day_info = $temp_working_time_number_day[$record['working_time_type_id']];
      } else {
        $working_time_info = isset($working_time_type_list[$record['working_time_type_id']]) ? $working_time_type_list[$record['working_time_type_id']] : [];
        if (isset($working_time_info['id'])) {
          foreach ($arr_day as $name_day => $number) {
            if ($working_time_info[$name_day] == 1) {
              $temp_working_time_number_day[$working_time_info['id']][] = $number;
              $temp_working_time_number_day_info[] = $number;
            }
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
        $sql_where['AND']['user_id']  = $record['id'];
        $sql_where['AND']['date[>=]'] = $start_date_performance;
        $sql_where['AND']['date[<=]'] = $end_date_performance;
        $count                        = Amst::count($code . '_checkin', $sql_where);

        $i_data[$key_name]            = $count;
        $temp_count_status[$key_name] = $count;
        if ($count && $key_name != 'count_leave_work') {
          // if ($count) {
          $cal_percent                    = ($count / $count_day_work) * 100;
          $i_data[$key_name . '_percent'] = $cal_percent;
          $percent_performance            -= $cal_percent;
        }
      }

      $i_data['count_day_work']      = $count_day_work;
      $i_data['percent_performance'] = floor($percent_performance);

      Amst::insert($code . '_performance', $i_data);

      // รายวันไม่เข้าเงื่อนไขหักเงิน
      $count_fine = 0;
      $fine_price = 0;
      // foreach ($deduct_list as $key_deduct => $record_deduct) {
      //   $working_time_ids = json_decode($record_deduct['role_use'], false);
      //   if ($working_time_ids && in_array($record['working_time_type_id'], $working_time_ids)) {

      //     $deduct_info = $record_deduct;
      //     $type_deduct = 'count_' . $deduct_info['type'];
      //     $count_type  = $temp_count_status[$type_deduct];

      //     $amount           = 0;
      //     $cal_deduct_price = 0;

      //     if ($count_type >= $deduct_info['amount']) {
      //       $amount = floor($count_type / $deduct_info['amount']);
      //       $cal_deduct_price = $amount * $deduct_info['price'];
      //     }

      //     $count_fine += $amount;
      //     $fine_price += $cal_deduct_price;
      //   }
      // }

      $where_employee_cost = [
        'AND' => [
          'user_id' => $record['id'],
          'status'  => 'in_progress'
        ]
      ];
      $employee_cost_list = Amst::selectNoKey($code . '_employee_cost', '*', $where_employee_cost);

      $count_employee_cost       = 0;
      $employee_cost_total_price = 0;

      if (count($employee_cost_list) > 0) {
        foreach ($employee_cost_list as $key_cost => $record_cost) {

          $count_employee_cost       += 1;
          $employee_cost_total_price += $record_cost['price_per_period'];
          $period_sum_pay = $record_cost['period_sum_pay'] + 1;

          $i_cost_data = [
            'employee_cost_id'  => $record_cost['id'],
            'installment_date'  => $end_date_performance, // date_check_month,
            'user_id'           => $record['id'],
            'period_no'         => $period_sum_pay,
            'period_all'        => $record_cost['period'],
            'price_per_period'  => $record_cost['price_per_period']
          ];
          $api_cost = Amst::insert($code . '_employee_cost_log', $i_cost_data);
          if (isset($api_cost['response_status']) && $api_cost['response_status'] == true) {
            $where_count_pay_cost = [
              'AND' => [
                'employee_cost_id'  => $record_cost['id'],
                'user_id'           => $record['id']
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

      $is_fine = 0;
      if ($count_fine > 0) {
        $is_fine = 1;
      }

      $total_price = $record['salary'] - $fine_price - $employee_cost_total_price;

      // $is_security    = $record['is_security'];
      // $security_price = $record['security_price'];
      // if ($record['is_security'] && $record['employee_status'] == 'month') {
      //   $total_price -= $security_price;
      // }

      // if ($record['employee_status'] == 'trial_work') {
      $is_security    = 0;
      $security_price = 0;
      // }

      // gen no record
      $where_count = [
        // 'AND' => [
        //   'insert_date_time[~]' => $date_now
        // ]
      ];
      $count_commission = Amst::count($code . '_salary', $where_count);
      $count_commission = $count_commission + 1;
      $number_no        = str_pad($count_commission, 5, '0', STR_PAD_LEFT);
      $no               = 'S' . $number_no;

      $i_salary_data = [
        'user_id'                   => $record['id'],
        'ref_no'                    => $no,
        'status'                    => 'draft',
        'employee_status'           => $record['employee_status'],
        'installment_date'          => $date_now, // date_check_month, 
        'from_date'                 => $start_date_performance,
        'to_date'                   => $end_date_performance,
        'day'                       => $day,
        'month'                     => $month,
        'year'                      => $year,
        'salary'                    => $record['salary'],
        'is_security'               => $is_security,
        'security_price'            => $security_price,
        'security_unit'             => $record['security_unit'],
        'is_fine'                   => $is_fine,
        'count_fine'                => $count_fine,
        'fine_price'                => $fine_price,
        'count_employee_cost'       => $count_employee_cost,
        'employee_cost_total_price' => $employee_cost_total_price,
        'total_price'               => $total_price,
        'bank_code'                 => $record['bank_code'],
        'bank_name'                 => $record['bank_name'],
        'bank_account_name'         => $record['bank_account_name'],
        'bank_account_number'       => $record['bank_account_number']
      ];

      Amst::insert($code . '_salary', $i_salary_data);
    }

    return $return;
  }

  /**
  | Group | Not Con Employee
  | Description | Not Con 'Employee'
  | $code | โค้ดโมดูล
  | $where | [
    date,
    user_id
  ]
   */
  public static function handleSaveSalaryPerformanceDayByConfirm($code, $where = []) {
    $return = true;

    $date    = isset($where['date']) && $where['date'] ? $where['date'] : null;
    $user_id = isset($where['user_id']) && $where['user_id'] ? $where['user_id'] : null;

    // ยิงทุกนาที ของ user แบบรายวันที่ checkin เท่านั้น

    $time_now = date('H:i:s');

    $date_now = date('Y-m-d');
    if ($date) {
      $date_now = $where['date'];
    }
    $day_name = strtolower(date('l', strtotime($date_now)));

    // $date_now   = '2024-02-29';

    $start_date_performance = date('Y-m-01', strtotime($date_now));
    $end_date_performance   = date('Y-m-t', strtotime($start_date_performance));
    // $end_date_performance   = $date_now;

    $day                    = date('d', strtotime($date_now));
    $month                  = date('m', strtotime($date_now));
    $year                   = date('Y', strtotime($date_now));

    $where_deduct = [
      'AND' => [
        'is_use' => 1
      ]
    ];
    $deduct_list = Amst::selectNoKey($code . '_setting_deduct_money', '*', $where_deduct);

    $sql_where = [
      'AND' => [
        'employee_status'      => 'day',
        // 'working_time_type_id' => $working_time_type_ids
      ]
    ];

    if ($user_id) {
      $sql_where['AND']['id'] = $user_id;
    }

    $user_list        = Amst::selectNoKey('sys_user', '*', $sql_where);
    $user_ids         = array_unique(array_column($user_list, 'id'));
    $working_type_ids = array_unique(array_column($user_list, 'working_time_type_id'));

    $working_time_type_list  = Amst::select($code . '_setting_working_time', '*', ['id' => $working_type_ids]);

    // if (count($user_ids) > 0) {
    //   $log_data = [
    //     'date_now' => $date_now,
    //     'time_now' => $time_now,
    //     'text'     => 'พนักงานรับเงินรายวันครบแล้วในช่วงวันเวลานี้'
    //   ];
    //   $log_data = json_encode($log_data);
    //   Log::add(0, $log_data, 0, 'backend');

    //   return Amst::repError('E0000', 'พนักงานรับเงินรายวันครบแล้วในช่วงวันเวลานี้');
    // }

    // return $return;

    $user_ids[] = -1;

    // เผื่อใช้
    // $where_checkin = [
    //   'AND' => [
    //     'date'    => $date_now,
    //     'user_id' => $user_ids,
    //     'is_last' => 1
    //   ]
    // ];
    // $checkin_list = Amst::selectNoKey($code . '_checkin', '*', $where_checkin);
    // $temp_checkin_list = [];
    // foreach ($checkin_list as $key => $record) {
    //   $temp_checkin_list[$record['user_id']] = $record;
    // }

    $where_salary_list = [
      'AND' => [
        'user_id'          => $user_ids,
        'employee_status'  => 'day',
        'installment_date' => $date_now
      ]
    ];
    $salary_list = Amst::selectNoKey($code . '_salary', ['id', 'user_id'], $where_salary_list);
    $temp_user_salary_list = [];
    foreach ($salary_list as $key => $record) {
      $temp_user_salary_list[$record['user_id']] = $record;
    }

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
    foreach ($user_list as $key => $record) {

      if (isset($temp_user_salary_list[$record['id']])) {
        continue;
      }

      $i_data = [
        'user_id'             => $record['id'],
        'employee_status'     => $record['employee_status'],
        'installment_date'    => $date_now,
        'date'                => $date_now, // date_check_month,
        'month'               => $month,
        'year'                => $year,
        'count_day_work'      => 0,
        'count_missing'       => 0,
        'count_leave_work'    => 0,
        'count_late'          => 0,
        'count_very_late'     => 0,
        'percent_performance' => 100,
      ];

      $percent_performance = 100;

      $firstDay = new DateTime("$start_date_performance");
      $lastDay  = new DateTime("$end_date_performance");

      $temp_working_time_number_day_info = [];
      if (isset($temp_working_time_number_day[$record['working_time_type_id']])) {
        $temp_working_time_number_day_info = $temp_working_time_number_day[$record['working_time_type_id']];
      } else {
        $working_time_info = isset($working_time_type_list[$record['working_time_type_id']]) ? $working_time_type_list[$record['working_time_type_id']] : [];
        if (isset($working_time_info['id'])) {
          foreach ($arr_day as $name_day => $number) {
            if ($working_time_info[$name_day] == 1) {
              $temp_working_time_number_day[$working_time_info['id']][] = $number;
              $temp_working_time_number_day_info[] = $number;
            }
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
        $sql_where['AND']['user_id']  = $record['id'];
        $sql_where['AND']['date[>=]'] = $start_date_performance;
        $sql_where['AND']['date[<=]'] = $end_date_performance;
        $count                        = Amst::count($code . '_checkin', $sql_where);

        $i_data[$key_name]            = $count;
        $temp_count_status[$key_name] = $count;
        if ($count && $key_name != 'count_leave_work') {
          // if ($count) {
          $cal_percent                    = ($count / $count_day_work) * 100;
          $i_data[$key_name . '_percent'] = $cal_percent;
          $percent_performance            -= $cal_percent;
        }
      }

      $i_data['count_day_work']      = $count_day_work;
      $i_data['percent_performance'] = floor($percent_performance);

      Amst::insert($code . '_performance', $i_data);

      // รายวันไม่เข้าเงื่อนไขหักเงิน
      $count_fine = 0;
      $fine_price = 0;
      // foreach ($deduct_list as $key_deduct => $record_deduct) {
      //   $working_time_ids = json_decode($record_deduct['role_use'], false);
      //   if ($working_time_ids && in_array($record['working_time_type_id'], $working_time_ids)) {

      //     $deduct_info = $record_deduct;
      //     $type_deduct = 'count_' . $deduct_info['type'];
      //     $count_type  = $temp_count_status[$type_deduct];

      //     $amount           = 0;
      //     $cal_deduct_price = 0;

      //     if ($count_type >= $deduct_info['amount']) {
      //       $amount = floor($count_type / $deduct_info['amount']);
      //       $cal_deduct_price = $amount * $deduct_info['price'];
      //     }

      //     $count_fine += $amount;
      //     $fine_price += $cal_deduct_price;
      //   }
      // }

      $where_employee_cost = [
        'AND' => [
          'user_id' => $record['id'],
          'status'  => 'in_progress'
        ]
      ];
      $employee_cost_list = Amst::selectNoKey($code . '_employee_cost', '*', $where_employee_cost);

      $count_employee_cost       = 0;
      $employee_cost_total_price = 0;

      if (count($employee_cost_list) > 0) {
        foreach ($employee_cost_list as $key_cost => $record_cost) {

          $count_employee_cost       += 1;
          $employee_cost_total_price += $record_cost['price_per_period'];
          $period_sum_pay = $record_cost['period_sum_pay'] + 1;

          $i_cost_data = [
            'employee_cost_id'  => $record_cost['id'],
            'installment_date'  => $date_now, // date_check_month,
            'user_id'           => $record['id'],
            'period_no'         => $period_sum_pay,
            'period_all'        => $record_cost['period'],
            'price_per_period'  => $record_cost['price_per_period']
          ];
          $api_cost = Amst::insert($code . '_employee_cost_log', $i_cost_data);
          if (isset($api_cost['response_status']) && $api_cost['response_status'] == true) {
            $where_count_pay_cost = [
              'AND' => [
                'employee_cost_id'  => $record_cost['id'],
                'user_id'           => $record['id']
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

      $is_fine = 0;
      if ($count_fine > 0) {
        $is_fine = 1;
      }

      $total_price = $record['salary'] - $fine_price - $employee_cost_total_price;

      // $is_security    = $record['is_security'];
      // $security_price = $record['security_price'];
      // if ($record['is_security'] && $record['employee_status'] == 'month') {
      //   $total_price -= $security_price;
      // }

      // if ($record['employee_status'] == 'trial_work') {
      $is_security    = 0;
      $security_price = 0;
      // }

      // gen no record
      $where_count = [
        // 'AND' => [
        //   'insert_date_time[~]' => $date_now
        // ]
      ];
      $count_commission = Amst::count($code . '_salary', $where_count);
      $count_commission = $count_commission + 1;
      $number_no        = str_pad($count_commission, 5, '0', STR_PAD_LEFT);
      $no               = 'S' . $number_no;

      $i_salary_data = [
        'user_id'                   => $record['id'],
        'ref_no'                    => $no,
        'status'                    => 'draft',
        'employee_status'           => $record['employee_status'],
        'installment_date'          => $date_now, // date_check_month, 
        'from_date'                 => $start_date_performance,
        'to_date'                   => $end_date_performance,
        'day'                       => $day,
        'month'                     => $month,
        'year'                      => $year,
        'salary'                    => $record['salary'],
        'is_security'               => $is_security,
        'security_price'            => $security_price,
        'security_unit'             => $record['security_unit'],
        'is_fine'                   => $is_fine,
        'count_fine'                => $count_fine,
        'fine_price'                => $fine_price,
        'count_employee_cost'       => $count_employee_cost,
        'employee_cost_total_price' => $employee_cost_total_price,
        'total_price'               => $total_price,
        'bank_code'                 => $record['bank_code'],
        'bank_name'                 => $record['bank_name'],
        'bank_account_name'         => $record['bank_account_name'],
        'bank_account_number'       => $record['bank_account_number']
      ];

      Amst::insert($code . '_salary', $i_salary_data);
    }

    return $return;
  }

  public static function setEmployeeListDetail($code, $return, $options = []) {
    // [ExtractList]
    $selected_records = isset($return['list']) ? $return['list'] : $return;

    $date_first               = date('Y-m-01');
    $date_end                 = date('Y-m-t');

    $working_time_type_ids   = array_unique(array_column($selected_records, 'working_time_type_id'));
    $working_time_type_ids[] = -1;

    $branch_ids   = array_unique(array_column($selected_records, 'branch_id'));
    $branch_ids[] = -1;

    // ['id', 'title', 'hour_total', 'minute_total']
    $working_time_type_list = Amst::select($code . '_setting_working_time', '*', ['id' => $working_time_type_ids]);
    $branch_list            = Amst::select($code . '_setting_branch', ['id', 'name'], ['id' => $branch_ids]);

    $arr_day = [
      'sunday'    => 0,
      'monday'    => 1,
      'tuesday'   => 2,
      'wednesday' => 3,
      'thursday'  => 4,
      'friday'    => 5,
      'saturday'  => 6
    ];

    $temp_working_time_number_day = [];

    // Do Each Works...
    foreach ($selected_records as $key => $record) {
      // $selected_records[$key]['other_key'] = 'other_value';

      $working_time_type_name    = '';
      $working_time_hour_total   = '';
      $working_time_minute_total = '';
      if (isset($working_time_type_list[$record['working_time_type_id']])) {
        $working_time_type_info = $working_time_type_list[$record['working_time_type_id']];

        $working_time_type_name    = $working_time_type_info['title'];
        $working_time_hour_total   = $working_time_type_info['hour_total'];
        $working_time_minute_total = $working_time_type_info['minute_total'];
      }
      $selected_records[$key]['working_time_type_name']    = $working_time_type_name;
      $selected_records[$key]['working_time_hour_total']   = $working_time_hour_total;
      $selected_records[$key]['working_time_minute_total'] = $working_time_minute_total;

      $branch_name = '';
      if (isset($branch_list[$record['branch_id']])) {
        $branch_info = $branch_list[$record['branch_id']];

        $branch_name = $branch_info['name'];
      }
      $selected_records[$key]['branch_name'] = $branch_name;

      if (isset($options['is_performance']) && $options['is_performance']) {
        $percent_performance = 100;

        // รีเซ็ตทุกแถว ไม่งั้นค่า working_end_date ของพนักงานทดลองงานจะค้างไปแถวถัดไป
        $date_end = date('Y-m-t');

        $working_start_date = $record['working_start_date'];
        if ($record['employee_status'] != 'trial_work') {
          if ($working_start_date <= $date_first) {
            $working_start_date = $date_first;
          }

          if ($record['employee_status'] == 'day') {
            $working_start_date =  date('Y-m-01');
          }
        } else {
          $working_start_date = $record['working_start_date'];
          $date_end           = $record['working_end_date'];
        }

        $firstDay = new DateTime("$working_start_date");
        $lastDay  = new DateTime("$date_end");

        $temp_working_time_number_day_info = [];
        if (isset($temp_working_time_number_day[$record['working_time_type_id']])) {
          $temp_working_time_number_day_info = $temp_working_time_number_day[$record['working_time_type_id']];
        } else {
          $working_time_info = isset($working_time_type_list[$record['working_time_type_id']]) ? $working_time_type_list[$record['working_time_type_id']] : [];
          if (isset($working_time_info['id'])) {
            foreach ($arr_day as $name_day => $number) {
              if ($working_time_info[$name_day] == 1) {
                $temp_working_time_number_day[$working_time_info['id']][] = $number;
                $temp_working_time_number_day_info[] = $number;
              }
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

        foreach ($arr_where as $key_where => $record_where) {
          $key_name                           = $record_where['name'];
          $sql_where                          = $record_where['where'];
          $sql_where['AND']['user_id']        = $record['id'];
          $sql_where['AND']['date[>=]']       = $working_start_date;
          $sql_where['AND']['date[<=]']       = $date_end;
          $count                              = Amst::count($code . '_checkin', $sql_where);

          if ($count && $key_name != 'count_leave_work') {
            // if ($count) {
            // $selected_records[$key][$key_name]  = $count;
            // $cal_percent         = ($count / $count_day_work) * 100;
            $cal_percent         = $count * 5;
            $percent_performance -= $cal_percent;
          }
        }

        $selected_records[$key]['percent_performance'] = floor($percent_performance);
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
  | Group | Employee
  | Description | 1.ดึงข้อมูลลิส 'Employee'
  | $code | โค้ดโมดูล
  | $where | [name(full_name), user_code, is_trial_work(0 / 1 = ทดลองงาน), trial_work_status(waiting_confirm / approved / reject)]
  | $options | [is_performance(0 / 1), not_view(true|เอาแค่ข้อมูลหลักไป edit*เร็ว), selected_fields(String หรือ Array ของคอลัมภ์ที่ต้องการ), total_count(true|พร้อมจำนวนข้อมูลทั้งหมด ตามการกรองไม่แบ่งหน้า), page_no(1|เลขหน้า 0 คือส่งทั้งหมด), page_size(10|จำนวนข้อมูลต่อหน้า), sort_col(เรียงตามชื่อคอลัมภ์นี้), sort_by('ASC'|'DESC')]
   */
  public static function selectEmployee($code, $where = [], $options = []) {
    $return = []; // list
    $sql_where = [];

    if (isset($where['name']) && $where['name']) {
      $sql_where['AND']['full_name[~]'] = $where['name'];
    }

    if (isset($where['user_code']) && $where['user_code']) {
      $sql_where['AND']['user_code'] = $where['user_code'];
    }

    if (isset($where['is_trial_work']) && $where['is_trial_work']) {
      $sql_where['AND']['is_trial_work'] = $where['is_trial_work'];
    }

    if (isset($where['trial_work_status']) && $where['trial_work_status']) {
      $sql_where['AND']['trial_work_status'] = $where['trial_work_status'];
    }

    // $sql_where['AND']['user_type[!]'] = ['admin', 'Admin'];

    $sql_where['ORDER']['sort_order_id'] = 'DESC';

    $selected_fields = Amst::getSelectedFields($options);
    $options['image']['profile_image'] = [
      'code'          => 'user_profile_image_',
      'target_column' => 'id'
    ];
    $return = Amst::selectNoKey('sys_user', $selected_fields, $sql_where, $options);
    return static::setEmployeeListDetail($code, $return, $options);
  }

  /**
  | Group | Employee
  | Description | 1.count 'Employee'
  | $code | โค้ดโมดูล
  | $where | [name(ชื่อเว็บ)]
  | $options | [not_view(true|เอาแค่ข้อมูลหลักไป edit*เร็ว), selected_fields(String หรือ Array ของคอลัมภ์ที่ต้องการ), total_count(true|พร้อมจำนวนข้อมูลทั้งหมด ตามการกรองไม่แบ่งหน้า), page_no(1|เลขหน้า 0 คือส่งทั้งหมด), page_size(10|จำนวนข้อมูลต่อหน้า), sort_col(เรียงตามชื่อคอลัมภ์นี้), sort_by('ASC'|'DESC')]
   */
  public static function countEmployee($code, $where = [], $options = []) {
    $return = []; // list
    $sql_where = [];

    // $sql_where['AND']['user_type[!]'] = ['admin', 'Admin'];

    $return = Amst::count('sys_user', $sql_where);
    return $return;
  }

  public static function setEmployeeDetail($code, $record, $options = []) {
    if (!isset($record['id'])) {
      return $record;
    }

    // Do Single Works...
    // $record['other_key'] = 'other_value';

    $key_decode = [
      'tel', 'email'
    ];

    foreach ($key_decode as $key_check => $key_value) {
      $record[$key_value] = json_decode($record[$key_value], true);
    }

    $branch_info = Amst::getByID($code . '_setting_branch', ['id', 'name'], $record['branch_id']);
    $record['branch_name'] = $branch_info['name'];

    $working_time_info = Amst::getByID($code . '_setting_working_time', ['id', 'title', 'hour_total', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'], $record['working_time_type_id']);
    $record['working_time_type_name'] = $working_time_info['title'];
    $record['working_time_hour']      = $working_time_info['hour_total'];

    if (!isset($options['is_not_cal_performance'])) {
      $date_now  = date('Y-m-d');
      $dateStart = new DateTime($record['working_start_date']);
      $dateEnd   = new DateTime($date_now);
      $interval  = $dateStart->diff($dateEnd);

      $period_working_day = $dateStart > $dateEnd ? 0 : $interval->days;

      $period_working   = [];
      $period_working[] = $interval->format('%y') > 0 ? $interval->format('%y') . ' ปี' : '';
      $period_working[] = $period_working_day . ' วัน';
      $record['period_working'] = implode(' ', $period_working);

      $date_first          = date('Y-m-01');
      $date_end            = date('Y-m-t');
      $percent_performance = 100;

      $arr_day = [
        'sunday'    => 0,
        'monday'    => 1,
        'tuesday'   => 2,
        'wednesday' => 3,
        'thursday'  => 4,
        'friday'    => 5,
        'saturday'  => 6
      ];

      $temp_working_time_number_day = [];
      $working_start_date = $record['working_start_date'];
      if ($record['employee_status'] != 'trial_work') {
        if ($working_start_date <= $date_first) {
          $working_start_date = $date_first;
        }

        if ($record['employee_status'] == 'day') {
          $working_start_date =  date('Y-m-01');
        }
      } else {
        $working_start_date = $record['working_start_date'];
        $date_end           = $record['working_end_date'];
      }

      $firstDay = new DateTime("$working_start_date");
      $lastDay  = new DateTime("$date_end");

      $temp_number_day = [];
      foreach ($arr_day as $name_day => $number) {
        if ($working_time_info[$name_day] == 1) {
          $temp_number_day[] = $number;
        }
      }

      $count_day_work = 0;

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

      foreach ($arr_where as $key => $record_where) {
        $key_name                     = $record_where['name'];
        $sql_where                    = $record_where['where'];
        $sql_where['AND']['user_id']  = $record['id'];
        $sql_where['AND']['date[>=]'] = $working_start_date;
        $sql_where['AND']['date[<=]'] = $date_end;
        $count                        = Amst::count($code . '_checkin', $sql_where);

        $cal_percent           = 0;
        if ($count && $key_name != 'count_leave_work') {
          // if ($count) {
          // $cal_percent         = ($count / $count_day_work) * 100;
          $cal_percent         = $count * 5;
          $percent_performance -= $cal_percent;
        }

        $record[$key_name]              = $count;
        $record[$key_name . '_percent'] = $cal_percent;
      }

      $record['count_day_work']      = $count_day_work;
      $record['percent_performance'] = floor($percent_performance);
      $record['date_performance']    = $date_first;
    }

    // $where_history_performance = [
    //   'AND' => [
    //     'user_id' => $record['id']
    //   ],
    //   'ORDER' => [
    //     'date' => 'DESC'
    //   ]
    // ];
    // $history_performance_list = Amst::selectNoKey($code . '_performance', '*', $where_history_performance);
    // $record['history_performance_list'] = $history_performance_list;

    if (!isset($options['is_not_file'])) {
      // $where_file_other = [
      //   'AND' => [
      //     'code[~]' => $code . '_employee_other_file_' . $record['id'] . '_%'
      //   ]
      // ];
      // $code_file_other = Amst::select('sys_file', 'code', $where_file_other);
      // $file_list   = [];
      // if (count($code_file_other) > 0) {
      //   $placeholder = File::getPlaceHolder();
      //   $path        = File::selectPath($code_file_other);
      //   foreach ($code_file_other as $code_file) {
      //     $file_info   = File::getFileInfo($code_file);
      //     $file_info['path_file'] = isset($path[$code_file]) ? $path[$code_file] : $placeholder;
      //     $file_list[] = $file_info;
      //   }
      // }
      // $record['file_other_list'] = $file_list;

      $code_files = [
        $code . '_employee_file_id_card_' . $record['id'] => 'file_id_card',
        $code . '_employee_file_house_regis_' . $record['id'] => 'file_house_regis',
        $code . '_employee_file_edu_qual_' . $record['id'] => 'file_edu_qual',
        $code . '_employee_file_soldier_certificate_' . $record['id'] => 'file_soldier_certificate'
      ];
      foreach ($code_files as $code_file => $name_key) {
        $path                   = File::getPath($code_file);
        $file_info              = File::getFileInfo($code_file);
        if (!$file_info) {
          $file_info['code']      = '';
          $file_info['file_name'] = 'placeholder_square';
        }
        $file_info['path_file'] = $path;
        $record[$name_key]      = $file_info;
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
  | Group | Employee
  | Description | 2.ดึงข้อมูล 'Employee' ด้วยไอดี
  | $code | โค้ดโมดูล
  | $id | ไอดีรายการ
  | $options | [not_view(true|เอาแค่ข้อมูลหลักไป edit*เร็ว), selected_fields(String หรือ Array ของคอลัมภ์ที่ต้องการ)]
   */
  public static function getEmployeeByID($code, $id, $options = []) {
    $return = null;

    $selected_fields = Amst::getSelectedFields($options, ['id', 'system_delete']);
    $options['image']['profile_image'] = [
      'code'          => 'user_profile_image_',
      'target_column' => 'id'
    ];
    $model = Amst::getByID('sys_user', $selected_fields, $id, $options);
    if (isset($model['id'])) {
      $return = static::setEmployeeDetail($code, $model, $options);
    }
    return $return;
  }

  /**
  | Group | Employee
  | Description | 3.เพิ่ม 'Employee'
  | $code | โค้ดโมดูล
  | $data | [
    username,
    password,
    user_code,
    user_type,
    full_name,

    employee_number,
    working_time_type_id,
    working_time_type_name,
    working_start_date,
    working_end_date,
    branch_id,
    branch_name,
    employee_status,	
    salary,

    day_cal_salary,
    day_cal_salary2,
    is_security(0 / 1),
    security_price,
    security_unit,

    department,	
    division,	
    position,
    working_status,
    working_status_name,
    tel(array list),
    email(array list),
    social_facebook,
    social_line,
    social_ig,
    social_tiktok,
    size,
    remark,
    bank_code,
    bank_name,
    bank_account_name,
    bank_account_number,
    is_can_login,
  ]
  | $file_profile | file
  | $file_id_card | file
  | $file_house_regis | file
  | $file_edu_qual | file
  | $file_soldier_certificate | file
   */
  public static function addNewEmployee($code, $data = [], $file_profile = false, $file_id_card = false, $file_house_regis = false, $file_edu_qual = false, $file_soldier_certificate = false) {
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

    $last_id         = Amst::get('sys_user', 'id', ['ORDER' => ['id' => 'DESC']], ['show_all' => true]);
    $last_id         = $last_id + 1;
    $employee_number = 'N' . str_pad($last_id, 5, '0', STR_PAD_LEFT);

    $module_code               = $data['user_code'];
    $i_data                    = $data;
    $i_data['employee_number'] = $employee_number;

    if ($data['employee_status'] == 'trial_work') {
      // cal day
      $dateStart = new DateTime($data['working_start_date']);
      $dateEnd   = new DateTime($data['working_end_date']);

      $interval = $dateStart->diff($dateEnd);
      $sum_day_trial_work = $interval->days + 1;
      $i_data['is_trial_work']      = 1;
      $i_data['trial_work_status']  = 'waiting_confirm';
      $i_data['sum_day_trial_work'] = $sum_day_trial_work;
    }

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

    // update bank name
    $bank_dropdown = Bank::select();
    foreach ($bank_dropdown as $bank) {
      if ($bank['abb'] == $data['bank_code']) {
        $i_data['bank_name'] = $bank['abb'] . ' ' . $bank['name_th'];
        break;
      }
    }

    $api = User::addNewUser($module_code, $i_data, $file_profile);
    if (isset($api['response_status']) && ($api['response_status'] == true)) {
      $insert_id = $api['response_data']['insert_id'];

      if ($file_id_card && $file_id_card['error'] == 0) {
        $code_file = $code . '_employee_file_id_card_' . $insert_id;
        $log_file = File::add($code_file, $file_id_card);
      }

      if ($file_house_regis && $file_house_regis['error'] == 0) {
        $code_file = $code . '_employee_file_house_regis_' . $insert_id;
        $log_file = File::add($code_file, $file_house_regis);
      }

      if ($file_edu_qual && $file_edu_qual['error'] == 0) {
        $code_file = $code . '_employee_file_edu_qual_' . $insert_id;
        $log_file = File::add($code_file, $file_edu_qual);
      }

      if ($file_soldier_certificate && $file_soldier_certificate['error'] == 0) {
        $code_file = $code . '_employee_file_soldier_certificate_' . $insert_id;
        $log_file = File::add($code_file, $file_soldier_certificate);
      }

      // if ($file_others && is_array($file_others)) {
      //   $no = 1;
      //   foreach ($file_others as $key => $file_other) {
      //     if ($file_other['error'] == 0) {
      //       $code_file = $code . '_employee_other_file_' . $insert_id . '_' . $no;
      //       $log_file = File::add($code_file, $file_other);
      //     }
      //     $no++;
      //   }
      // }

      $temp_working_time_type_id = $i_data['working_time_type_id'];
      Phr_setting_working_time::handleCountUserWorkingTime($code, $temp_working_time_type_id);

      $temp_branch_id = $i_data['branch_id'];
      Phr_setting_branch::handleCountUserBranch($code, $temp_branch_id);
    }
    return $api;
  }

  /**
  | Group | Employee
  | Description | 4.อัพเดท 'Employee'
  | $code | โค้ดโมดูล
  | $id | ไอดีรายการ
  | $data | [
    username,
    password,
    user_type,
    full_name,

    employee_number,
    working_time_type_id,
    working_time_type_name,
    working_start_date,
    working_end_date,
    branch_id,
    branch_name,
    employee_status,	
    salary,
    department,	
    division,	
    position,
    working_status,
    working_status_name,
    tel(array list),
    email(array list),
    social_facebook,
    social_line,
    social_ig,
    social_tiktok,
    size,
    remark,
    bank_code,
    bank_name,
    bank_account_name,
    bank_account_number,
    is_can_login,
  ]
  | $file_profile | file
  | $file_id_card | file
  | $file_house_regis | file
  | $file_edu_qual | file
  | $file_soldier_certificate | file
  | $code_delete_file | array list
   */
  public static function updateEmployee($code, $id, $data = [], $file_profile = false, $file_id_card = false, $file_house_regis = false, $file_edu_qual = false, $file_soldier_certificate = false, $code_delete_file = false) {
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

    $model = Amst::getByID('sys_user', ['id', 'working_time_type_id', 'branch_id', 'last_image_others', 'employee_status', 'is_trial_work'], $id);
    if (!isset($model['id'])) {
      return Amst::repError('E0005', 'tbl_user not found.');
    }

    $where_check_username = [
      'AND' => [
        'id[!]'    => $model['id'],
        'username' => $data['username']
      ]
    ];
    $check_username = Amst::get('sys_user', ['id'], $where_check_username);
    if (isset($check_username['id'])) {
      return Amst::repError('E0005', 'Username is dupplicate.');
    }

    $key_encode = [
      'email', 'tel'
    ];

    $u_data = $data;
    // พนักงานที่เคยทดลองงาน (ผ่านแล้วเป็น month) ต้องคง is_trial_work ไว้
    // ไม่งั้นแก้ข้อมูลแล้วจะหายจากหน้าประวัติการประเมินทดลองงาน
    if (empty($model['is_trial_work'])) {
      $u_data['is_trial_work']      = 0;
      $u_data['sum_day_trial_work'] = 0;
    }

    if ($model['employee_status'] == 'trial_work') {
      // cal day
      $dateStart = new DateTime($data['working_start_date']);
      $dateEnd   = new DateTime($data['working_end_date']);

      $interval = $dateStart->diff($dateEnd);
      $sum_day_trial_work = $interval->days + 1;
      $u_data['is_trial_work']      = 1;
      $u_data['sum_day_trial_work'] = $sum_day_trial_work;
    }

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

    if (isset($data['user_code']) && $data['user_code']) {
      $where_role_user = [
        'AND' => [
          'code' => $data['user_code'],
        ]
      ];
      $role_user_info = Amst::get('sys_function', ['id', 'code', 'name', 'setting_permission'], $where_role_user);

      $u_data['user_type']  = $role_user_info['name'];
      $u_data['permission'] = $role_user_info['setting_permission'];
    }

    // update bank name
    $bank_dropdown = Bank::select();
    foreach ($bank_dropdown as $bank) {
      if ($bank['abb'] == $data['bank_code']) {
        $u_data['bank_name'] = $bank['abb'] . ' ' . $bank['name_th'];
        break;
      }
    }

    $api = Amst::updateByID('sys_user', $u_data, $id);
    if (isset($api['response_status']) && ($api['response_status'] == true)) {
      $insert_id = $id;

      if ($file_profile && $file_profile['error'] == 0) {
        User::setProfileImage($insert_id, $file_profile);
      }

      // if ($file_others && is_array($file_others)) {
      //   $last_image_others = $model['last_image_others'];
      //   foreach ($file_others as $key => $file_other) {
      //     if ($file_other['error'] == 0) {
      //       $last_image_others++;

      //       $code_file = $code . '_employee_other_file_' . $insert_id . '_' . $last_image_others;
      //       File::add($code_file, $file_other);
      //     }
      //   }

      //   Amst::updateByID('sys_user', ['last_image_others' => $last_image_others], $id);
      // }

      if ($code_delete_file) {
        File::delete($code_delete_file);
      }

      if ($file_id_card && $file_id_card['error'] == 0) {
        $code_file = $code . '_employee_file_id_card_' . $insert_id;
        $log_file = File::add($code_file, $file_id_card);
      }

      if ($file_house_regis && $file_house_regis['error'] == 0) {
        $code_file = $code . '_employee_file_house_regis_' . $insert_id;
        $log_file = File::add($code_file, $file_house_regis);
      }

      if ($file_edu_qual && $file_edu_qual['error'] == 0) {
        $code_file = $code . '_employee_file_edu_qual_' . $insert_id;
        $log_file = File::add($code_file, $file_edu_qual);
      }

      if ($file_soldier_certificate && $file_soldier_certificate['error'] == 0) {
        $code_file = $code . '_employee_file_soldier_certificate_' . $insert_id;
        $log_file = File::add($code_file, $file_soldier_certificate);
      }

      $temp_working_time_type_ids = [];
      $temp_working_time_type_ids[] = $model['working_time_type_id'];
      if (!in_array($u_data['working_time_type_id'], $temp_working_time_type_ids)) {
        $temp_working_time_type_ids[] = $u_data['working_time_type_id'];
      }
      Phr_setting_working_time::handleCountUserWorkingTime($code, $temp_working_time_type_ids);

      $temp_branch_ids = [];
      $temp_branch_ids[] = $model['branch_id'];
      if (!in_array($u_data['branch_id'], $temp_branch_ids)) {
        $temp_branch_ids[] = $u_data['branch_id'];
      }
      Phr_setting_branch::handleCountUserBranch($code, $temp_branch_ids);
    }
    return $api;
  }

  /**
  | Group | Employee
  | Description | 4.อัพเดท 'Employee'
  | $code | โค้ดโมดูล
  | $id | ไอดีรายการ
  | $data | [
    user_type,
    is_can_login,
  ]
  | $file_profile | file
   */
  public static function updateEmployeeLogin($code, $id, $data = [], $file_profile = false) {
    $rep = [
      'update_result' => false
    ];

    $model = Amst::getByID('sys_user', ['id', 'working_time_type_id', 'branch_id'], $id);
    if (!isset($model['id'])) {
      return Amst::repError('E0005', 'tbl_user not found.');
    }

    if ($data) {
      $u_data = $data;

      if (isset($data['user_code']) && $data['user_code']) {
        $where_role_user = [
          'AND' => [
            'code' => $data['user_code'],
          ]
        ];
        $role_user_info = Amst::get('sys_function', ['id', 'code', 'name', 'setting_permission'], $where_role_user);

        $u_data['user_type']  = $role_user_info['name'];
        $u_data['permission'] = $role_user_info['setting_permission'];
      }

      $api = Amst::updateByID('sys_user', $u_data, $id);
      if (isset($api['response_status']) && ($api['response_status'] == true)) {
      }
    }

    if ($file_profile && $file_profile['error'] == 0) {
      $api = User::setProfileImage($id, $file_profile);
    }
    return $api;
  }

  /**
  | Group | Employee
  | Description | 5.ลบ 'Employee'
  | $code | โค้ดโมดูล
  | $id | ไอดีรายการ
   */
  public static function deleteEmployee($code, $id) {
    $rep = [
      'delete_result' => false
    ];

    $api = static::_checkDeleteEmployee($code, $id);
    if (isset($api['response_status']) && ($api['response_status'] == true)) {
      $rep['delete_result'] = Amst::deleteByID('sys_user', $id);
      $rep['delete_profile_result'] = File::delete('user_profile_image_' . $id);

      $code_delete_file = [
        $code . '_employee_file_id_card_' . $id,
        $code . '_employee_file_house_regis_' . $id,
        $code . '_employee_file_edu_qual_' . $id,
        $code . '_employee_file_soldier_certificate_' . $id
      ];
      File::delete($code_delete_file);

      // $where = [
      //   'AND' => [
      //     'code[~]' => $code . '_employee_other_file_' . $id . '_%'
      //   ]
      // ];
      // $code_file_other = Amst::select('sys_file', 'code', $where);
      // if ($code_file_other) {
      //   $rep['delete_file_other_result'] = File::delete($code_file_other);
      // }

      $working_time_type_id = $api['response_data']['working_time_type_id'];
      Phr_setting_working_time::handleCountUserWorkingTime($code, $working_time_type_id);

      $branch_id    = $api['response_data']['branch_id'];
      Phr_setting_branch::handleCountUserBranch($code, $branch_id);

      return Amst::repSuccess($rep);
    }
    return $api; // ERROR SURE
  }

  private static function _checkDeleteEmployee($code, $id) {
    $rep = [
      'id' => $id
    ];

    // [Model_Exist]
    $model = Amst::getByID('sys_user', ['id', 'working_time_type_id', 'branch_id'], $id);
    if (!isset($model['id'])) {
      return Amst::repError('E0005', 'tbl_user not found.');
    }

    $rep['working_time_type_id'] = $model['working_time_type_id'];
    $rep['branch_id']            = $model['branch_id'];

    return Amst::repSuccess($rep);
  }

  /**
  | Group | Employee Password
  | Description | 1. เปลี่ยน 'Employee Password'
  | $code | โค้ดโมดูล
  | $user_id | user_id
  | $current_password | current_password
  | $new_password | new_password
   */
  public static function changePassword($code, $user_id, $current_password, $new_password) {
    $api = User::changePassword($user_id, $current_password, $new_password);
    if (isset($api['response_status']) && ($api['response_status'] == true)) {
    }
    return $api;
  }

  /**
  | Group | Control Employee
  | Description | 1 count 'Employee'
  | $code | โค้ดโมดูล
   */
  public static function countControlTrialEmployee($code) {
    $return = [
      'count_waiting_confirm' => 0,
      'count_confirm'         => 0
    ];

    $arr_where = [
      [
        'name'  => 'count_waiting_confirm',
        'where' => [
          'AND' => [
            'is_trial_work'     => 1,
            'trial_work_status' => 'waiting_confirm'
          ]
        ]
      ],
      [
        'name'  => 'count_confirm',
        'where' => [
          'AND' => [
            'is_trial_work'        => 1,
            'trial_work_status[!]' => 'waiting_confirm'
          ]
        ]
      ],
    ];

    foreach ($arr_where as $key => $record) {
      $key_name  = $record['name'];
      $sql_where = $record['where'];
      $count     = Amst::count('sys_user', $sql_where);

      $return[$key_name] = $count;
    }

    return $return;
  }

  /**
  | Group | Control Employee
  | Description | 1 ปรับสุถานะ 'Employee'
  | $code | โค้ดโมดูล
  | $id | id รายการ
  | $data | [
    user_id(ของคนปรับสถานะ),
    is_working(0 / 1),
    status(approved / reject),
    remark
  ]
   */
  public static function updateStatusTrialEmployeeByID($code, $id, $data = []) {
    $user_id       = isset($data['user_id']) && $data['user_id'] ? $data['user_id'] : -1;
    $is_working    = isset($data['is_working']) && $data['is_working'] ? $data['is_working'] : 0;
    $status        = isset($data['status']) && $data['status'] ? $data['status'] : '';
    $remark        = isset($data['remark']) && $data['remark'] ? $data['remark'] : '';

    if (!$status) {
      return Amst::repError('E0000', 'Status not found.');
    }

    $date_now  = date('Y-m-d H:i:s');
    $user_info = Amst::getByID('sys_user', ['id', 'full_name'], $user_id);

    $u_data = [
      'trial_work_status'             => $status,
      'trial_work_update_by_user_id'  => $user_id,
      'trial_work_update_by_fullname' => isset($user_info['id']) && $user_info['id'] ? $user_info['full_name'] : 'SYSTEM',
      'trial_work_update_by_datetime' => $date_now,
      'trial_work_update_by_remark'   => $remark
    ];

    if ($is_working == 1) {
      $u_data['employee_status'] = 'month';
    } else {
      $u_data['is_ban']       = 1;
      $u_data['is_can_login'] = 0;
    }

    $api = Amst::updateByID('sys_user', $u_data, $id);
    if (isset($api['response_status']) && ($api['response_status'] == true)) {
    }
    return $api;
  }

  public static function setEmployeeListDetailCost($code, $return, $options = []) {
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
  | Group | Employee Cost
  | Description | 1.ดึงข้อมูลลิส 'Employee Cost'
  | $code | โค้ดโมดูล
  | $where | [user_id]
  | $options | [is_performance(0 / 1), not_view(true|เอาแค่ข้อมูลหลักไป edit*เร็ว), selected_fields(String หรือ Array ของคอลัมภ์ที่ต้องการ), total_count(true|พร้อมจำนวนข้อมูลทั้งหมด ตามการกรองไม่แบ่งหน้า), page_no(1|เลขหน้า 0 คือส่งทั้งหมด), page_size(10|จำนวนข้อมูลต่อหน้า), sort_col(เรียงตามชื่อคอลัมภ์นี้), sort_by('ASC'|'DESC')]
   */
  public static function selectEmployeeCost($code, $where = [], $options = []) {
    $return = []; // list
    $sql_where = [];

    if (isset($where['user_id']) && $where['user_id']) {
      $sql_where['AND']['user_id'] = $where['user_id'];
    }

    $selected_fields = Amst::getSelectedFields($options);
    $return = Amst::selectNoKey($code . '_view_employee_cost', $selected_fields, $sql_where, $options);
    return static::setEmployeeListDetailCost($code, $return, $options);
  }

  public static function setEmployeeCostDetail($code, $record, $options = []) {
    if (!isset($record['id'])) {
      return $record;
    }

    // Do Single Works...
    // $record['other_key'] = 'other_value';

    return $record;
  }

  /**
  | Group | Employee Cost
  | Description | 2.ดึงข้อมูล 'Employee Cost' ด้วยไอดี
  | $code | โค้ดโมดูล
  | $id | ไอดีรายการ
  | $options | [not_view(true|เอาแค่ข้อมูลหลักไป edit*เร็ว), selected_fields(String หรือ Array ของคอลัมภ์ที่ต้องการ)]
   */
  public static function getEmployeeCostByID($code, $id, $options = []) {
    $return = null;

    $selected_fields = Amst::getSelectedFields($options, ['id', 'system_delete']);
    $model = Amst::getByID($code . '_view_employee_cost', $selected_fields, $id, $options);
    if (isset($model['id'])) {
      $return = static::setEmployeeCostDetail($code, $model, $options);
    }
    return $return;
  }

  /**
  | Group | Employee Cost
  | Description | 3.เพิ่ม 'Employee Cost'
  | $code | โค้ดโมดูล
  | $data | [
    user_id,
    name,
    total_price,
    type_pay(one / multiple),
    period,
    price_per_period,
    remark
  ]
   */
  public static function addNewEmployeeCost($code, $data = []) {
    $rep = [
      'insert_id' => false
    ];

    $i_data                    = $data;
    $i_data['type']            = 'cost';
    $i_data['status']          = 'in_progress';
    $i_data['total_price_pay'] = 0;
    $i_data['period_sum_pay']  = 0;

    if ($data['type_pay'] == 'one') {
      $i_data['period']           = 1;
      $i_data['price_per_period'] = $data['total_price'];
    }

    $api = Amst::insert($code . '_employee_cost', $i_data);
    if (isset($api['response_status']) && ($api['response_status'] == true)) {
      $insert_id = $api['response_data']['insert_id'];
    }
    return $api;
  }

  /**
  | Group | Employee Cost
  | Description | 4.อัพเดท 'Employee Cost'
  | $code | โค้ดโมดูล
  | $id | ไอดีรายการ
  | $data | [
    user_id,
    name,
    total_price,
    type_pay(one / multiple),
    period,
    price_per_period,
    remark
  ]
   */
  public static function updateEmployeeCost($code, $id, $data = []) {
    $rep = [
      'update_result' => false
    ];

    $model = Amst::getByID($code . '_employee_cost', ['id'], $id);
    if (!isset($model['id'])) {
      return Amst::repError('E0005', 'tbl_employee_cost not found.');
    }

    $u_data = $data;

    if ($data['type_pay'] == 'one') {
      $u_data['period']           = 1;
      $u_data['price_per_period'] = $data['total_price'];
    }

    $api = Amst::updateByID($code . '_employee_cost', $u_data, $id);
    if (isset($api['response_status']) && ($api['response_status'] == true)) {
      $insert_id = $id;
    }
    return $api;
  }

  /**
  | Group | Employee Cost
  | Description | 5.ลบ 'Employee Cost'
  | $code | โค้ดโมดูล
  | $id | ไอดีรายการ
   */
  public static function deleteEmployeeCost($code, $id) {
    $rep = [
      'delete_result' => false
    ];

    $api = static::_checkDeleteEmployeeCost($code, $id);
    if (isset($api['response_status']) && ($api['response_status'] == true)) {
      $rep['delete_result'] = Amst::deleteByID($code . '_employee_cost', $id);

      $where = [
        'AND' => [
          'employee_cost_id' => $id
        ]
      ];
      $rep['delete_sub_result'] = Amst::delete($code . '_employee_cost_log', $where);
    }
    return $api; // ERROR SURE
  }

  private static function _checkDeleteEmployeeCost($code, $id) {
    $rep = [
      'id' => $id
    ];

    // [Model_Exist]
    $model = Amst::getByID($code . '_employee_cost', ['id'], $id);
    if (!isset($model['id'])) {
      return Amst::repError('E0005', 'tbl_employee_cost not found.');
    }

    return Amst::repSuccess($rep);
  }

  /**
  | Group | Employee Cost
  | Description | 6.gen 'Employee Cost' deduct
  | $code | โค้ดโมดูล
  | $data | [
    user_id,
    type(on_time / late),
    status(normal / late / very_late / missing / leave_work),
    date
  ]
   */
  public static function handleGenEmployeeCostDeduct($code, $data = []) {
    $return  = [];

    $user_id = isset($data['user_id']) && $data['user_id'] ? $data['user_id'] : 0;
    $type    = isset($data['type']) && $data['type'] ? $data['type'] : '';
    $status  = isset($data['status']) && $data['status'] ? $data['status'] : '';
    $date    = isset($data['date']) && $data['date'] ? $data['date'] : '';

    $user_info = Amst::getByID('sys_user', '*', $user_id);
    if (!isset($user_info['id'])) {
      return Amst::repError('E0000', 'User not found.');
    }

    $date_now   = date('Y-m-d');
    if ($date) {
      $date_now = date('Y-m-d', strtotime($date));
    }

    $return['date_now'] = $date_now;

    // $date_now   = '2024-02-29';

    $end_date_performance   = date('Y-m-' . $user_info['day_cal_salary'], strtotime($date_now));
    $start_date_performance = date('Y-m-d', strtotime($end_date_performance . ' -1 month'));

    $is_pass = 0;
    if ($date_now > $end_date_performance) {
      $is_pass = 1;
      $end_date_performance   = date('Y-m-' . $user_info['day_cal_salary'], strtotime($date_now . ' +1 month'));
      $start_date_performance = date('Y-m-d', strtotime($end_date_performance . ' -1 month'));
    }

    $day                    = date('d', strtotime($date_now));
    $month                  = date('m', strtotime($date_now));
    $year                   = date('Y', strtotime($date_now));

    $check_month = date('m', strtotime($start_date_performance));
    if ($check_month == $month && !$is_pass) {
      $date = new DateTime("$date_now");
      $date->modify('-1 month');
      $date->modify('first day of this month');

      $start_date_performance = $date->format('Y-m-d');
    } else {
      $start_date_performance = date('Y-m-d', strtotime($start_date_performance . ' +1 day'));
    }

    $return['start_date_performance'] = $start_date_performance;
    $return['end_date_performance']   = $end_date_performance;

    $working_time_type_list = Amst::select($code . '_setting_working_time', '*', []);

    $where_deduct = [
      'AND' => [
        'is_use' => 1
      ]
    ];
    $deduct_list = Amst::select($code . '_setting_deduct_money', '*', $where_deduct);

    $arr_day = [
      'sunday'    => 0,
      'monday'    => 1,
      'tuesday'   => 2,
      'wednesday' => 3,
      'thursday'  => 4,
      'friday'    => 5,
      'saturday'  => 6
    ];

    if ($type == 'late') {
      $where_delete = [
        'AND' => [
          'user_id'     => $user_info['id'],
          'type'        => 'deduct',
          'date_deduct' => $date_now
        ]
      ];
    }

    $temp_working_time_number_day = [];
    $percent_performance = 100;

    $firstDay = new DateTime("$start_date_performance");
    $lastDay  = new DateTime("$end_date_performance");

    $temp_working_time_number_day_info = [];
    if (isset($temp_working_time_number_day[$user_info['working_time_type_id']])) {
      $temp_working_time_number_day_info = $temp_working_time_number_day[$user_info['working_time_type_id']];
    } else {
      $working_time_info = isset($working_time_type_list[$user_info['working_time_type_id']]) ? $working_time_type_list[$user_info['working_time_type_id']] : [];
      if (isset($working_time_info['id'])) {
        foreach ($arr_day as $name_day => $number) {
          if ($working_time_info[$name_day] == 1) {
            $temp_working_time_number_day[$working_time_info['id']][] = $number;
            $temp_working_time_number_day_info[] = $number;
          }
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

    $count_data = [
      'count_missing'       => 0,
      'count_leave_work'    => 0,
      'count_late'          => 0,
      'count_very_late'     => 0
    ];

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
      $sql_where['AND']['date[>=]'] = $start_date_performance;
      $sql_where['AND']['date[<=]'] = $end_date_performance;
      $count                        = Amst::count($code . '_checkin', $sql_where);

      $count_data[$key_name]        = $count;
      $temp_count_status[$key_name] = $count;
    }
    $return['count_data'] = $count_data;
    $return['temp_count_status'] = $temp_count_status;

    $temp_deduct = [];

    $count_fine = 0;
    $fine_price = 0;
    foreach ($deduct_list as $key_deduct => $record_deduct) {
      $working_time_ids = json_decode($record_deduct['role_use'], false);
      if ($working_time_ids && in_array($user_info['working_time_type_id'], $working_time_ids)) {

        $deduct_info = $record_deduct;
        $type_deduct = 'count_' . $deduct_info['type'];
        $count_type  = $temp_count_status[$type_deduct];

        $amount           = 0;
        $cal_deduct_price = 0;

        if ($count_type >= $deduct_info['amount']) {
          $amount = floor($count_type / $deduct_info['amount']);
          $cal_deduct_price = $amount * $deduct_info['price'];
        }

        $count_fine += $amount;
        $fine_price += $cal_deduct_price;

        $temp_deduct[$deduct_info['id']] = [
          'deduct_info' => $deduct_info,
          'amount'      => $amount
        ];
      }
    }
    $return['temp_deduct'] = $temp_deduct;

    $where_emp_cost_deduct = [
      'AND' => [
        'user_id'         => $user_info['id'],
        'type'            => 'deduct',
        'date_deduct[>=]' => $start_date_performance,
        'date_deduct[<=]' => $end_date_performance
      ]
    ];
    $emp_cost_deduct = Amst::selectNoKey($code . '_employee_cost', '*', $where_emp_cost_deduct);
    $temp_emp_cost_deduct = [];
    foreach ($emp_cost_deduct as $key => $record) {
      if (!isset($temp_emp_cost_deduct[$record['ref_deduct_id']])) {
        $temp_emp_cost_deduct[$record['ref_deduct_id']] = 0;
      }
      $temp_emp_cost_deduct[$record['ref_deduct_id']]++;
    }
    $return['temp_emp_cost_deduct'] = $temp_emp_cost_deduct;

    $text_type_deduct = [
      'normal'     => 'มาปกติ',
      'late'       => 'มาสาย',
      'very_late'  => 'มาสายมาก',
      'missing'    => 'ขาดงาน',
      'leave_work' => 'ลางาน'
    ];

    $temp_i_data_cost_log = [];
    foreach ($temp_deduct as $key => $record) {
      $loop = 0;
      $deduct_amount  = $record['amount'];
      $deduct_info    = $record['deduct_info'];

      if (isset($temp_emp_cost_deduct[$key])) {
        $history_amount = $temp_emp_cost_deduct[$key];

        if ($history_amount < $deduct_amount) {
          $loop = $deduct_amount - $history_amount;
        }
      } else {
        $loop = $deduct_amount;
      }

      if ($loop) {
        foreach (range(1, $loop) as $key => $value) {
          $text_deduct = 'ค่าปรับ ' . $text_type_deduct[$deduct_info['type']] . ' ' . $deduct_info['amount'] . ' ครั้งใบรอบเดือน';

          $i_data = [
            'ref_deduct_id'     => $deduct_info['id'],
            'user_id'           => $user_info['id'],
            'type'              => 'deduct',
            'type_deduct'       => $deduct_info['type'],
            'date_deduct'       => $date_now, // date ที่ทำการลงเวลา
            'status'            => 'in_progress',
            'name'              => $text_deduct,
            'total_price_pay'   => 0,
            'total_price'       => $deduct_info['price'],
            'type_pay'          => 'one',
            'period'            => 1,
            'period_sum_pay'    => 0,
            'price_per_period'  => $deduct_info['price'],
            'remark'            => '',
          ];

          $temp_i_data_cost_log[] = $i_data;
        }
      }
    }

    $return['temp_i_data_cost_log'] = $temp_i_data_cost_log;

    if (count($temp_i_data_cost_log) > 0) {
      foreach ($temp_i_data_cost_log as $key => $record) {
        $i_data = $record;
        Amst::insert($code . '_employee_cost', $i_data);
      }
    }

    return $return; // ERROR SURE
  }
}
