<?php

class Phr_notification
{

  /**
  | Group | Notification
  | Description | 1.ดึงข้อมูลลิส 'Notification'
  | $code | โค้ดโมดูล
  | $where | [user_id, type(system_warning / system_reject_salary / system_leave_work / system_overtime / system_commission / system_approved_salary), date]
  | $options | [not_view(true|เอาแค่ข้อมูลหลักไป edit*เร็ว), selected_fields(String หรือ Array ของคอลัมภ์ที่ต้องการ), total_count(true|พร้อมจำนวนข้อมูลทั้งหมด ตามการกรองไม่แบ่งหน้า), page_no(1|เลขหน้า 0 คือส่งทั้งหมด), page_size(10|จำนวนข้อมูลต่อหน้า), sort_col(เรียงตามชื่อคอลัมภ์นี้), sort_by('ASC'|'DESC')]
   */
  public static function selectNotification($code, $where = [], $options = [])
  {
    $return = []; // list
    $sql_where = [];

    if (isset($where['user_id']) && $where['user_id'] && $where['user_id'] != 'all') {
      $sql_where['AND']['user_id'] = $where['user_id'];
    }

    if (isset($where['type']) && $where['type'] && $where['type'] != 'all') {
      $sql_where['AND']['type'] = $where['type'];
    }

    if (isset($where['date']) && $where['date']) {
      $sql_where['AND']['date[~]'] = $where['date'];
    }

    if (isset($where['is_read']) && ($where['is_read'] || $where['is_read'] == '0') && $where['is_read'] != 'all') {
      $sql_where['AND']['is_read'] = $where['is_read'];
    }

    $sql_where['ORDER']['insert_date_time'] = 'DESC';

    $selected_fields = Amst::getSelectedFields($options);
    $return = Amst::selectNoKey('sys_user_notification', $selected_fields, $sql_where, $options);
    return static::setNotificationListDetail($code, $return, $options);
  }

  public static function setNotificationListDetail($code, $return, $options = [])
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
  | Group | Notification
  | Description | 2.ดึงข้อมูล 'Notification' ด้วยไอดี
  | $code | โค้ดโมดูล
  | $id | ไอดีรายการ
  | $options | [not_view(true|เอาแค่ข้อมูลหลักไป edit*เร็ว), selected_fields(String หรือ Array ของคอลัมภ์ที่ต้องการ)]
   */
  public static function getNotificationByID($code, $id, $options = [])
  {
    $return = null;

    static::readNotification($code, $id);

    $selected_fields = Amst::getSelectedFields($options, ['id', 'system_delete']);
    $model = Amst::getByID('sys_user_notification', $selected_fields, $id, $options);
    if (isset($model['id'])) {
      $return = static::setNotificationDetail($code, $model, $options);
    }
    return $return;
  }

  public static function setNotificationDetail($code, $record, $options = [])
  {
    if (!isset($record['id'])) {
      return $record;
    }

    // Do Single Works...
    // $record['other_key'] = 'other_value';

    return $record;
  }

  /**
  | Group | Notification
  | Description | 3.เพิ่ม 'Notification'
  | $code | โค้ดโมดูล
  | $data | [
    user_id,
    from_user_id,
    type(system_warning / system_reject_salary / system_leave_work / system_overtime / system_commission / system_approved_salary),
    ref_id,
    
    number,
    detail,
    ceo_name,
    salary_type(day / month),
    salary,
    employee_name,
    date
  ]
   */
  public static function addNewNotification($code, $data = [])
  {
    $rep = [
      'insert_id' => false
    ];

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

    $user_id       = isset($data['user_id']) && $data['user_id'] ? $data['user_id'] : 0;
    $from_user_id  = isset($data['from_user_id']) && $data['from_user_id'] ? $data['from_user_id'] : 0;
    $type          = isset($data['type']) && $data['type'] ? $data['type'] : '-';
    $ref_id        = isset($data['ref_id']) && $data['ref_id'] ? $data['ref_id'] : 0;

    $number        = isset($data['number']) && $data['number'] ? $data['number'] : 0;
    $detail        = isset($data['detail']) && $data['detail'] ? $data['detail'] : '-';

    $ceo_name      = isset($data['ceo_name']) && $data['ceo_name'] ? $data['ceo_name'] : '-';
    $salary_type   = isset($data['salary_type']) && $data['salary_type'] ? $data['salary_type'] : '';
    $salary_text   = $salary_type ? ($salary_type == 'day' ? 'ค่าจ้าง' : 'เงินเดือน') : '-';
    $salary_name   = $salary_type ? ($salary_type == 'day' ? 'วัน' : 'เดือน') : '-';
    $employee_name = isset($data['employee_name']) && $data['employee_name'] ? $data['employee_name'] : '-';
    $remark        = isset($data['remark']) && $data['remark'] ? $data['remark'] : '-';

    $date          = isset($data['date']) && $data['date'] ? $data['date'] : '-';

    $salary        = isset($data['salary']) && $data['salary'] ? $data['salary'] : 0;

    $topic = '-';
    $description = '-';
    $link = '';

    if ($type == 'system_warning') { //
      $topic = 'แจ้งเตือนใบเตือน';
      $description = 'คุณได้รับใบเตือนครั้งที่ ' . $number . ' เนื่องด้วย ' . $detail;
      $link = 'issue_warning_detail.php?id=' . $ref_id;
    } else if ($type == 'system_reject_salary') { //
      $topic = 'แจ้งเตือนไม่อนุมัติเงินเดือน';
      $description = 'ผู้บริหาร ' . $ceo_name . ' ไม่อนุมัติ ' . $salary_text . ' ของ ' . $employee_name . ' เนื่องจาก ' . $remark;
      // $link = 'make_salary_detail.php?id=' . $ref_id;
      // if ($salary_type == 'day') {
      //   $link .= '&type=daily';
      // }
      $link = 'employee_salary_detail.php?id=' . $ref_id . '&mode=my_profile&page=emp_history_money&user_id=' . $user_id;
    } else if ($type == 'system_leave_work') { // 
      $topic = 'แจ้งเตือนลางาน';
      $description = $employee_name . ' ลงบันทึกการขอลางาน วันที่ ' . $date . ' เนื่องจาก ' . $remark;
      $link = 'leavework_detail.php?id=' . $ref_id;
    } else if ($type == 'system_overtime') { //
      $topic = 'แจ้งเตือนล่วงเวลา';
      $description = $employee_name . ' ลงบันทึกการขอทำงานล่วงเวลา วันที่ ' . $date . ' เนื่องจาก ' . $remark;
      $link = 'approve_ot_detail.php?id=' . $ref_id;
    } else if ($type == 'system_commission') { //
      $topic = 'แจ้งเตือนค่าคอม';
      $description = $employee_name . ' มีค่าคอมที่กำลังรอการอนุมัติ';
      $link = 'approve_commission_detail.php?id=' . $ref_id . '&page=waiting_confirm';
    } else if ($type == 'system_approved_salary') { //

      $year  = date('Y', strtotime($date));
      $month = date('m', strtotime($date));
      $day   = date('d', strtotime($date));

      $topic = 'แจ้งเตือนค่าจ้าง/เงินเดือน';
      $description = 'คุณได้รับการอนุมัติ ' . $salary_text . ' ประจำ' . $salary_name . ' ' . ($salary_type == 'day' ? $day . ' ' . $month_arr[(int)$month] . ' ' . $year : $month_arr[(int)$month] . ' ' . $year);
      // $link = 'make_salary_detail.php?id=' . $ref_id;
      // if ($salary_type == 'day') {
      //   $link .= '&type=daily';
      // }
      $link = 'employee_salary_detail.php?id=' . $ref_id . '&mode=my_profile&page=emp_history_money&user_id=' . $user_id;
    }

    $link .= '&noti=true';

    $i_data = [
      'user_id' => $user_id,
      'from_user_id' => $from_user_id,
      'topic' => $topic,
      'description' => $description,
      'code' => '',
      'module' => '',
      'type' => $type,
      'ref_id' => $ref_id,
      'link' => $link,
      'is_show' => 1,
    ];

    $api = Amst::insert('sys_user_notification', $i_data);
    if (isset($api['response_status']) && ($api['response_status'] == true)) {
    }
    return $api;
  }

  /**
  | Group | Notification
  | Description | 4.อัพเดท 'Notification'
  | $code | โค้ดโมดูล
  | $id | ไอดีรายการ
  | $data | []
   */
  public static function updateNotification($code, $id, $data = [])
  {
    $rep = [
      'update_result' => false
    ];

    $model = Amst::getByID('sys_user_notification', ['id'], $id);
    if (!isset($model['id'])) {
      return Amst::repError('E0005', 'tbl_sys_user_notification not found.');
    }

    return Amst::updateByID('sys_user_notification', $data, $id);
  }

  /**
  | Group | Notification
  | Description | 5.ลบ 'Notification'
  | $code | โค้ดโมดูล
  | $id | ไอดีรายการ
   */
  public static function deleteNotification($code, $id)
  {
    $rep = [
      'delete_result' => false
    ];

    $api = static::_checkDeleteNotification($code, $id);
    if (isset($api['response_status']) && ($api['response_status'] == true)) {
      $rep['delete_result'] = Amst::deleteByID('sys_user_notification', $id);
      return Amst::repSuccess($rep);
    }
    return $api; // ERROR SURE
  }

  private static function _checkDeleteNotification($code, $id)
  {
    $rep = [
      'id' => $id
    ];

    // [Model_Exist]
    $model = Amst::getByID('sys_user_notification', ['id'], $id);
    if (!isset($model['id'])) {
      return Amst::repError('E0005', 'tbl_sys_user_notification not found.');
    }

    return Amst::repSuccess($rep);
  }

  /**
  | Group | Read
  | Description | 6.'Read' notification
  | $code | โค้ดโมดูล
  | $id | ไอดีรายการ
   */
  public static function readNotification($code, $id)
  {
    $rep = [
      'delete_result' => false
    ];  

    $u_data = [
      'is_read' => 1
    ];
    $api = static::updateNotification($code, $id, $u_data);
    return $api;
  }
}
