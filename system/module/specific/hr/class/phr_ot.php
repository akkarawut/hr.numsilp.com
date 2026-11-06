<?php

class Phr_ot
{

  public static function setOTListDetail($code, $return, $options = [])
  {
    // [ExtractList]
    $selected_records = isset($return['list']) ? $return['list'] : $return;

    // $setting_ot_ids   = array_unique(array_column($$selected_records, 'setting_ot_id'));
    // $setting_ot_ids[] = -1;
    // $setting_ot_list  = Amst::select($code . '_setting_ot', '*', ['id' => $setting_ot_ids]);

    $user_ids   = array_unique(array_column($selected_records, 'user_id'));
    $user_ids[] = -1;
    $user_list = Amst::select('sys_user', ['id', 'full_name'], ['id' => $user_ids]);

    // Do Each Works...
    foreach ($selected_records as $key => $record) {
      // $selected_records[$key]['other_key'] = 'other_value';

      $selected_records[$key]['user_fullname']        = isset($user_list[$record['user_id']]) ? $user_list[$record['user_id']]['full_name'] : '';
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
  public static function selectOT($code, $where = [], $options = [])
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
      $sql_where['AND']['insert_date_time[<>]'] = [$where['from_date'] . ' 00:00:00', $where['to_date'] . ' 23:59:59'];
    }

    $selected_fields = Amst::getSelectedFields($options);
    $return = Amst::selectNoKey($code . '_ot', $selected_fields, $sql_where, $options);
    return static::setOTListDetail($code, $return, $options);
  }

  public static function setOTDetail($code, $record, $options = [])
  {
    if (!isset($record['id'])) {
      return $record;
    }

    // Do Single Works...
    // $record['other_key'] = 'other_value';

    $user_info = Amst::getByID('sys_user', ['id', 'full_name'], $record['user_id']);
    $record['user_fullname'] = isset($user_info['id']) ? $user_info['full_name'] : '';

    $setting_ot_info  = Amst::getByID($code . '_setting_ot', ['id', 'title'], $record['setting_ot_id']);
    $record['ot_type_name'] = isset($setting_ot_info['id']) ? $setting_ot_info['title'] : '-';

    return $record;
  }

  /**
  | Group | Leave Work
  | Description | 2.ดึงข้อมูล 'Leave Work' ด้วยไอดี
  | $code | โค้ดโมดูล
  | $id | ไอดีรายการ
  | $options | [not_view(true|เอาแค่ข้อมูลหลักไป edit*เร็ว), selected_fields(String หรือ Array ของคอลัมภ์ที่ต้องการ)]
   */
  public static function getOTByID($code, $id, $options = [])
  {
    $return = null;

    $selected_fields = Amst::getSelectedFields($options, ['id', 'system_delete']);
    $model = Amst::getByID($code . '_ot', $selected_fields, $id, $options);
    if (isset($model['id'])) {
      $return = static::setOTDetail($code, $model, $options);
    }
    return $return;
  }

  /**
  | Group | Leave Work
  | Description | 3.เพิ่ม 'Leave Work'
  | $code | โค้ดโมดูล
  | $data | [
    user_id,
    remark,
    date,
    from_time,
    to_time
  ]
   */
  public static function addNewOT($code, $data = [])
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
    $count_ot  = Amst::count($code . '_ot', $where_count, ['show_all' => true]);
    $count_ot  = $count_ot + 1;
    $number_no = str_pad($count_ot, 5, '0',STR_PAD_LEFT);
    $no        = 'OT' . $date_set . $number_no;

    // คำนวณจำนวนชั่วโมง (รองรับ OT ข้ามเที่ยงคืน)
    $hour_total = static::_calHourTotal($data['date'], $data['from_time'], $data['to_time']);

    $i_data                   = $data;
    $i_data['no']             = $no;
    $i_data['status_confirm'] = 'waiting_confirm';
    $i_data['hour_total']     = $hour_total;

    $api = Amst::insert($code . '_ot', $i_data);
    if (isset($api['response_status']) && ($api['response_status'] == true)) {
      $ot_id = $api['response_data']['insert_id'];

      $user_info = Amst::getByID('sys_user', ['id', 'full_name'], $data['user_id']);

      $where_user_send_noti = [
        'AND' => [
          'user_code'    => 'noaxt', // manager
          'is_can_login' => 1,
          'is_ban'       => 0
        ]
      ];
      $manager_list  = Amst::select('sys_user', '*', $where_user_send_noti);
      // กันกรณีไม่มีผู้จัดการ -> ตัวแปรยังไม่ถูกสร้างแล้ว foreach error
      $noti_data_list = [];
      foreach ($manager_list as $key => $manager_record) {
        $noti_data = [
          'user_id'       => $manager_record['id'],
          'from_user_id'  => $data['user_id'],
          'type'          => 'system_overtime',
          'employee_name' => isset($user_info['full_name']) ? $user_info['full_name'] : '-',
          'date'          => isset($data['date']) ? $data['date'] : '-',
          'remark'        => isset($data['remark']) ? $data['remark'] : '-',
          'ref_id'        => $ot_id
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
    remark,
    date,
    from_time,
    to_time
  ]
   */
  public static function updateOT($code, $id, $data = [])
  {
    $rep = [
      'update_result' => false
    ];

    $model = Amst::getByID($code . '_ot', ['id'], $id);
    if (!isset($model['id'])) {
      return Amst::repError('E0005', 'tbl_ot not found.');
    }

    $u_data = $data;
    // ฟิลด์ต้องตรงกับตอนเพิ่ม (date / from_time / to_time) เดิมอ่าน from_date / to_date ที่ไม่มีอยู่จริง
    if (isset($data['date'], $data['from_time'], $data['to_time']) && $data['date'] && $data['from_time'] && $data['to_time']) {
      $u_data['hour_total'] = static::_calHourTotal($data['date'], $data['from_time'], $data['to_time']);
    }

    $api = Amst::updateByID($code . '_ot', $u_data, $id);
    if (isset($api['response_status']) && ($api['response_status'] == true)) {
    }
    return $api;
  }

  /**
  | Group | Leave Work
  | Description | 5.ลบ 'Leave Work'
  | $code | โค้ดโมดูล
  | $id | ไอดีรายการ
   */
  public static function deleteOT($code, $id)
  {
    $rep = [
      'delete_result' => false
    ];

    $api = static::_checkDeleteOT($code, $id);
    if (isset($api['response_status']) && ($api['response_status'] == true)) {
      $rep['delete_result'] = Amst::deleteByID($code . '_ot', $id);
      return Amst::repSuccess($rep);
    }
    return $api; // ERROR SURE
  }

  /**
   * คำนวณจำนวนชั่วโมง OT (ปัดเศษนาทีทิ้ง เพราะคอลัมน์ hour_total เป็น INT)
   * ถ้าเวลาสิ้นสุดน้อยกว่า/เท่ากับเวลาเริ่ม ถือว่าข้ามไปวันถัดไป
   */
  private static function _calHourTotal($date, $from_time, $to_time)
  {
    $date_start = new DateTime($date . ' ' . $from_time);
    $date_end   = new DateTime($date . ' ' . $to_time);
    if ($date_end <= $date_start) {
      $date_end->modify('+1 day');
    }

    $interval = $date_start->diff($date_end);
    // ใช้ days * 24 + h เพื่อไม่ให้ชั่วโมงหายเมื่อช่วงเวลาเกิน 1 วัน
    return ($interval->days * 24) + $interval->h;
  }

  private static function _checkDeleteOT($code, $id)
  {
    $rep = [
      'id' => $id
    ];

    // [Model_Exist]
    $model = Amst::getByID($code . '_ot', ['id'], $id);
    if (!isset($model['id'])) {
      return Amst::repError('E0005', 'tbl_ot not found.');
    }

    return Amst::repSuccess($rep);
  }

    /**
  | Group | Control Leave Work
  | Description | 1 count 'Leave Work'
  | $code | โค้ดโมดูล
   */
  public static function countControlOT($code)
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
      $count     = Amst::count($code . '_ot', $sql_where);

      $return[$key_name] = $count;
    }

    return $return;
  }

  /**
  | Group | Control Leave Work
  | Description | 1 ปรับสุถานะ 'Leave Work'
  | $code | โค้ดโมดูล
  | $id | id รายการ
  | $data | [
    user_id(ของคนปรับสถานะ),
    setting_ot_id,
    status(approved / reject),
    remark
  ]
   */
  public static function updateStatusOTByID($code, $id, $data = [])
  {
    $user_id       = isset($data['user_id']) && $data['user_id'] ? $data['user_id'] : -1;
    $status        = isset($data['status']) && $data['status'] ? $data['status'] : '';
    $remark        = isset($data['remark']) && $data['remark'] ? $data['remark'] : '';

    if (!$status) {
      return Amst::repError('E0000', 'Status not found.');
    }
    
    $model = Amst::getByID($code . '_ot', ['id', 'hour_total'], $id);
    if (!isset($model['id'])) {
      return Amst::repError('E0005', 'tbl_ot not found.');
    }

    $date_now  = date('Y-m-d H:i:s');
    $user_info = Amst::getByID('sys_user', ['id', 'full_name'], $user_id);

    $u_data = [
      'status_confirm'                    => $status,
      'status_confirm_update_by_user_id'  => $user_id,
      'status_confirm_update_by_fullname' => isset($user_info['id']) && $user_info['id'] ? $user_info['full_name'] : 'SYSTEM',
      'status_confirm_update_datetime'    => $date_now,
      'status_confirm_update_remark'      => $remark
    ];

    if (isset($data['setting_ot_id']) && $data['setting_ot_id']) {
      $model_setting_ot_info = Amst::getByID($code . '_setting_ot', '*', $data['setting_ot_id']);

      $hour_total  = $model['hour_total'];
      $total_price = 0;
      if ($model_setting_ot_info['type_calculate'] == 'hour') {
        $total_price = $model['hour_total'] * $model_setting_ot_info['price'];
      }

      $u_data['setting_ot_id']   = $model_setting_ot_info['id'];
      $u_data['setting_ot_name'] = $model_setting_ot_info['title'];
      $u_data['late_cal_hour']   = $model_setting_ot_info['price'];
      $u_data['total_price']     = $total_price;
    }

    $api = Amst::updateByID($code . '_ot', $u_data, $id);
    if (isset($api['response_status']) && ($api['response_status'] == true)) {
    }
    return $api;
  }

}
