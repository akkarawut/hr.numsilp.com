<?php

class phr_leave_work
{

  public static function setLeaveWorkListDetail($code, $return, $options = [])
  {
    // [ExtractList]
    $selected_records = isset($return['list']) ? $return['list'] : $return;

    $user_list = Amst::select('sys_user', ['id', 'full_name'], []);

    // Do Each Works...
    foreach ($selected_records as $key => $record) {
      // $selected_records[$key]['other_key'] = 'other_value';
      $selected_records[$key]['user_fullname'] = isset($user_list[$record['user_id']]) ? $user_list[$record['user_id']]['full_name'] : '';
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
  | Group | Leave Work
  | Description | 1.ดึงข้อมูลลิส 'Leave Work'
  | $code | โค้ดโมดูล
  | $where | [
    user_id,
    status_confirm(waiting_confirm / approved / reject), 
    from_date,
    to_date
  ]
  | $options | [not_view(true|เอาแค่ข้อมูลหลักไป edit*เร็ว), selected_fields(String หรือ Array ของคอลัมภ์ที่ต้องการ), total_count(true|พร้อมจำนวนข้อมูลทั้งหมด ตามการกรองไม่แบ่งหน้า), page_no(1|เลขหน้า 0 คือส่งทั้งหมด), page_size(10|จำนวนข้อมูลต่อหน้า), sort_col(เรียงตามชื่อคอลัมภ์นี้), sort_by('ASC'|'DESC')]
   */
  public static function selectLeaveWork($code, $where = [], $options = [])
  {
    $return = []; // list
    $sql_where = [];

    if (isset($where['user_id']) && $where['user_id']) {
      $sql_where['AND']['user_id'] = $where['user_id'];
    }

    if (isset($where['status_confirm']) && $where['status_confirm']) {
      $sql_where['AND']['status_confirm'] = $where['status_confirm'];
    }

    if ((isset($where['from_date']) && $where['from_date']) && (isset($where['to_date']) && $where['to_date'])) {
      // ใส่เวลาให้ครอบคลุมทั้งวัน ไม่งั้นรายการที่ยื่นในวันสุดท้ายจะหลุด
      $sql_where['AND']['insert_date_time[>=]'] = $where['from_date'] . ' 00:00:00';
      $sql_where['AND']['insert_date_time[<=]'] = $where['to_date'] . ' 23:59:59';
    }

    // กรองตามเดือน (Y-m) จาก dropdown เดือนในหน้าประวัติการลา
    if (isset($where['date']) && $where['date']) {
      $sql_where['AND']['from_date[~]'] = $where['date'] . '-%';
    }

    $selected_fields = Amst::getSelectedFields($options);
    $return = Amst::selectNoKey($code . '_leave_work', $selected_fields, $sql_where, $options);
    return static::setLeaveWorkListDetail($code, $return, $options);
  }

  public static function setLeaveWorkDetail($code, $record, $options = [])
  {
    if (!isset($record['id'])) {
      return $record;
    }

    // Do Single Works...
    // $record['other_key'] = 'other_value';

    $user_info = Amst::getByID('sys_user', ['id', 'full_name'], $record['user_id']);
    $record['user_fullname'] = isset($user_info['full_name']) ? $user_info['full_name'] : '';

    $where_file = [
      'AND' => [
        'code[~]' => str_replace('_', '\_', $code . '_leave_work_' . $record['id'] . '_') . '%' // escape _ ไม่ให้เป็น wildcard ของ LIKE
      ]
    ];
    $code_files = Amst::select('sys_file', 'code', $where_file);
    $file_list   = [];
    if (count($code_files) > 0) {
      $placeholder = File::getPlaceHolder();
      $path        = File::selectPath($code_files);
      foreach ($code_files as $code_file) {
        $file_info   = File::getFileInfo($code_file);
        $file_info['path_file'] = isset($path[$code_file]) ? $path[$code_file] : $placeholder;
        $file_list[] = $file_info;
      }
    }

    $record['image_list'] = $file_list;

    return $record;
  }

  /**
  | Group | Leave Work
  | Description | 2.ดึงข้อมูล 'Leave Work' ด้วยไอดี
  | $code | โค้ดโมดูล
  | $id | ไอดีรายการ
  | $options | [not_view(true|เอาแค่ข้อมูลหลักไป edit*เร็ว), selected_fields(String หรือ Array ของคอลัมภ์ที่ต้องการ)]
   */
  public static function getLeaveWorkByID($code, $id, $options = [])
  {
    $return = null;

    $selected_fields = Amst::getSelectedFields($options, ['id', 'system_delete']);
    $model = Amst::getByID($code . '_leave_work', $selected_fields, $id, $options);
    if (isset($model['id'])) {
      $return = static::setLeaveWorkDetail($code, $model, $options);
    }
    return $return;
  }

  /**
  | Group | Leave Work
  | Description | 3.เพิ่ม 'Leave Work'
  | $code | โค้ดโมดูล
  | $data | [
    user_id,
    type(sick_leave / personal_leave),
    detail,
    from_date,
    to_date,
  ]
  | $files | file
   */
  public static function addNewLeaveWork($code, $data = [], $files = [])
  {
    $rep = [
      'insert_id' => false
    ];

    $date_now = date('Y-m-d');
    $date_set = date('Ymd');

    // gen no record
    $where_count = [
      // 'AND' => [
      //   'insert_date_time[~]' => $date_now
      // ]
    ];
    // นับรวมรายการที่ถูกลบ (soft delete) ด้วย กันเลขคำขอซ้ำหลังมีการลบ
    $count_leave = Amst::count($code . '_leave_work', $where_count, ['show_all' => true]);
    $count_leave = $count_leave + 1;
    $number_no   = str_pad($count_leave, 5, '0', STR_PAD_LEFT);
    $no          = 'L' . $date_set . $number_no;

    // cal day
    $dateStart = new DateTime($data['from_date']);
    $dateEnd   = new DateTime($data['to_date']);

    $interval = $dateStart->diff($dateEnd);

    // $sum_day_leave_work = $interval->days + 1;

    $loop = 0;
    $check_date = [];
    while ($dateStart <= $dateEnd && $loop < 100) {
      $check_date[] = $dateStart->format('Y-m-d');
      $dateStart->add(new DateInterval('P1D')); // เพิ่มหนึ่งวัน

      $loop++;
    }

    $sum_day_leave_work = $loop;

    // แถว draft (รออนุมัติ) ไม่ได้ตั้ง is_last จึงต้องเช็คแยก ไม่งั้นยื่นลาวันเดิมซ้ำได้
    $where_dup = [
      'AND' => [
        'user_id' => $data['user_id'],
        'date'    => $check_date,
        'OR'      => [
          'AND #approved_leave' => [
            'status_checkin' => 'leave_work',
            'is_last'        => 1
          ],
          'status_checkin #draft_leave' => 'draft_leave_work'
        ]
      ]
    ];
    $check_dup = Amst::get($code . '_checkin', ['id'], $where_dup);
    if (isset($check_dup['id'])) {
      return Amst::repError('E0000', 'Unable to leave on this date, There may be duplicate dates.');
    }

    $i_data                       = $data;
    $i_data['no']                 = $no;
    $i_data['status_confirm']     = 'waiting_confirm';
    $i_data['sum_day_leave_work'] = $sum_day_leave_work;

    $api = Amst::insert($code . '_leave_work', $i_data);
    if (isset($api['response_status']) && ($api['response_status'] == true)) {
      $leave_work_id = $api['response_data']['insert_id'];

      $log_file = [];
      if ($files) {
        foreach ($files as $key => $file) {
          if ($file && $file['error'] == 0) {
            $code_file  = $code . '_leave_work_' . $leave_work_id . '_' . $key;
            $log_file[] = File::add($code_file, $file);
          }
        }
      }

      foreach ($check_date as $key_date => $date) {
        // $arr_data = [
        //   'user_id' => $data['user_id'],
        //   'type' => 'draft_leave_work',
        //   'date' => $date,
        //   'remark' => $data['detail'],
        //   'leave_work_id' => $insert_id
        // ];
        // Phr_checkin::userCheckInOut($code, $arr_data);

        $day_name = strtolower(date('l', strtotime($date)));

        $i_data = [
          'user_id'             => $data['user_id'],
          // 'type'                => 'leave_work',
          'status'              => 'in_progress',
          'status_checkin'      => 'draft_leave_work',
          'status_confirm'      => 'waiting_confirm', // default status
          'day_name'            => $day_name,
          'date'                => $date,
          'remark'              => isset($data['detail']) ? $data['detail'] : '',
          'leave_work_id'       => $leave_work_id
        ];

        $api_checkin = Amst::insert($code . '_checkin', $i_data);
        if (isset($api_checkin['response_status']) && ($api_checkin['response_status'] == true)) {
          $checkin_id  = $api_checkin['response_data']['insert_id'];

          // $i_log_data = [
          //   'user_id'    => $data['user_id'], // คนทำรายการ
          //   'checkin_id' => $checkin_id,
          //   'type'       => 'leave_work',
          //   'date'       => $data['date'],
          //   'time'       => $data['time'],
          // ];
          // Amst::insert($code . '_checkin_log', $i_log_data);
        }
      }

      $user_info = Amst::getByID('sys_user', ['id', 'full_name'], $data['user_id']);

      $where_user_send_noti = [
        'AND' => [
          'user_code'    => ['xacpn', 'noaxt'], // hr / manager
          'is_can_login' => 1,
          'is_ban'       => 0
        ]
      ];
      $user_send_noti_list  = Amst::select('sys_user', '*', $where_user_send_noti);
      // กันกรณีไม่มี HR / ผู้จัดการ -> ตัวแปรยังไม่ถูกสร้างแล้ว foreach error
      $noti_data_list = [];
      foreach ($user_send_noti_list as $key => $user_send_noti_record) {
        $noti_data = [
          'user_id'       => $user_send_noti_record['id'],
          'from_user_id'  => $data['user_id'],
          'type'          => 'system_leave_work',
          'employee_name' => isset($user_info['full_name']) ? $user_info['full_name'] : '-',
          'date'          => $sum_day_leave_work > 1 ? $data['from_date'] . ' - ' . $data['to_date'] : $data['from_date'],
          'remark'        => isset($data['detail']) ? $data['detail'] : '-',
          'ref_id'        => $leave_work_id
        ];

        $noti_data_list[] = $noti_data;
      }

      foreach ($noti_data_list as $key => $noti_data) {
        Phr_notification::addNewNotification($code, $noti_data);
      }
    }
    return $api;
  }

  /**
  | Group | Leave Work
  | Description | 4.อัพเดท 'Leave Work'
  | $code | โค้ดโมดูล
  | $id | ไอดีรายการ
  | $data | [
    user_id,
    type(sick_leave / personal_leave), 
    status_confirm(waiting_confirm / approved / reject),
    detail,
    from_date,
    to_date,
  ]
  | $files | file
   */
  public static function updateLeaveWork($code, $id, $data = [], $files = [])
  {
    $rep = [
      'update_result' => false
    ];

    $model = Amst::getByID($code . '_leave_work', ['id'], $id);
    if (!isset($model['id'])) {
      return Amst::repError('E0005', 'tbl_leave_work not found.');
    }

    // cal day
    $dateStart = new DateTime($data['from_date']);
    $dateEnd   = new DateTime($data['to_date']);

    $loop = 0;
    $check_date = [];
    while ($dateStart <= $dateEnd && $loop < 100) {
      $check_date[] = $dateStart->format('Y-m-d');
      $dateStart->add(new DateInterval('P1D')); // เพิ่มหนึ่งวัน

      $loop++;
    }

    $where_dup = [
      'AND' => [
        'user' => $data['user_id'],
        'date' => $check_date
      ]
    ];
    $check_dup = Amst::get($code . '_checkin', ['id'], $where_dup);
    if (isset($check_dup['id'])) {
      return Amst::repError('E0000', 'record is duplicate.');
    }

    $interval = $dateStart->diff($dateEnd);

    $sum_day_leave_work = $interval->days + 1;

    $u_data                       = $data;
    $u_data['sum_day_leave_work'] = $sum_day_leave_work;

    $api = Amst::updateByID($code . '_leave_work', $u_data, $id);
    if (isset($api['response_status']) && ($api['response_status'] == true)) {
      $log_file = [];
      if ($files) {
        foreach ($files as $key => $file) {
          if ($file && $file['error'] == 0) {
            $code_file  = $code . '_leave_work_' . $id . '_' . $key;
            $log_file[] = File::add($code_file, $file);
          }
        }
      }

      $where_delete_leave_work = [
        'AND' => [
          'leave_work_id' => $id
        ]
      ];
      $api_delete = Amst::delete($code . '_checkin', $where_delete_leave_work);
      if (isset($api_delete['response_status']) && ($api_delete['response_status'] == true)) {
        foreach ($check_date as $key_date => $date) {
          $day_name = strtolower(date('l', strtotime($data['date'])));

          $i_data = [
            'user_id'             => $data['user_id'],
            // 'type'                => 'leave_work',
            'status'              => 'in_progress',
            'status_checkin'      => 'draft_leave_work',
            'status_confirm'      => 'waiting_confirm', // default status
            'day_name'            => $day_name,
            'date'                => $date,
            'remark'              => isset($data['detail']) ? $data['detail'] : '',
            'leave_work_id'       => $id
          ];

          $api_insert = Amst::insert($code . '_checkin', $i_data);
          if (isset($api_insert['response_status']) && ($api_insert['response_status'] == true)) {
            $checkin_id  = $api_insert['response_data']['insert_id'];
          }
        }
      }
    }
    return $api;
  }

  /**
  | Group | Leave Work
  | Description | 5.ลบ 'Leave Work'
  | $code | โค้ดโมดูล
  | $id | ไอดีรายการ
   */
  public static function deleteLeaveWork($code, $id)
  {
    $rep = [
      'delete_result' => false
    ];

    $api = static::_checkDeleteLeaveWork($code, $id);
    if (isset($api['response_status']) && ($api['response_status'] == true)) {
      $rep['delete_result'] = Amst::deleteByID($code . '_leave_work', $id);

      $where_delete_leave_work = [
        'AND' => [
          'leave_work_id' => $id
        ]
      ];
      $rep['delete_result_checkin_leave_work'] = Amst::delete($code . '_checkin', $where_delete_leave_work);

      return Amst::repSuccess($rep);
    }
    return $api; // ERROR SURE
  }

  private static function _checkDeleteLeaveWork($code, $id)
  {
    $rep = [
      'id' => $id
    ];

    // [Model_Exist]
    $model = Amst::getByID($code . '_leave_work', ['id'], $id);
    if (!isset($model['id'])) {
      return Amst::repError('E0005', 'tbl_leave_work not found.');
    }

    return Amst::repSuccess($rep);
  }

  /**
  | Group | Control Leave Work
  | Description | 1 count 'Leave Work'
  | $code | โค้ดโมดูล
   */
  public static function countControlLeaveWork($code)
  {
    $return = [
      'count_waiting_confirm' => 0,
      'count_approved'        => 0,
      'count_reject'          => 0
    ];

    $arr_where = [
      [
        'name'  => 'count_waiting_confirm',
        'where' => [
          'AND' => [
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
      $count     = Amst::count($code . '_leave_work', $sql_where);

      $return[$key_name] = $count;
    }

    return $return;
  }

  /**
  | Group | Control Leave Work
  | Description | 1 ปรับสุถานะ 'Leave Work'
  | $code | โค้ดโมดูล
  | $data | [
    user_id(ของคนปรับสถานะ),
    ids(id รายการ),
    status(approved / reject),
    remark
  ]
   */
  public static function updateStatusLeaveWorkByIDS($code, $data = [])
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
    $api = Amst::update($code . '_leave_work', $u_data, $where);
    if (isset($api['response_status']) && ($api['response_status'] == true)) {

      $where_checkin = [
        'AND' => [
          'leave_work_id' => $temp_ids
        ]
      ];

      if ($status == 'approved') {
        $checkin_date_list   = Amst::select($code . '_checkin', ['id', 'user_id', 'date'], $where_checkin);
        $temp_checkin_ids    = [];
        $temp_update_checkin = [];
        foreach ($checkin_date_list as $key => $record) {
          if (!isset($temp_update_checkin[$record['user_id']])) {
            $temp_update_checkin[$record['user_id']] = [];
          }

          $temp_checkin_ids[] = $record['id'];

          $temp_update_checkin[$record['user_id']][] = $record['date'];
        }

        if (count($temp_update_checkin) > 0) {
          foreach ($temp_update_checkin as $key_user_id => $arr_date) {
            $where_update = [
              'AND' => [
                'user_id' => $key_user_id,
                'date'    => $arr_date
              ]
            ];

            Amst::update($code . '_checkin', ['is_last' => 0], $where_update);
          }
        }

        $u_data['status']         = 'success';
        $u_data['status_checkin'] = 'leave_work';
        $u_data['is_last']        = 1;

        Amst::update($code . '_checkin', $u_data, $where_checkin);

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
            'id'      => $temp_checkin_ids,
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

      } else if ($status == 'reject') {
        Amst::delete($code . '_checkin', $where_checkin);
      }

      // foreach ($temp_ids as $id) {
      //   $i_log_data = [
      //     'leave_work_id' => $id,
      //     'user_id'       => $user_id, // คนทำรายการ
      //     'type'          => 'update_status_confirm',
      //     'value'         => $status,
      //   ];
      //   Amst::insert($code . '_leave_work_log', $i_log_data);
      // }
    }
    return $api;
  }
}
