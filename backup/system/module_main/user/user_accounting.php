<?php
class User_Accounting {
  private static $conf = [
    'tb_accounting'  => 'sys_user_accounting',
    'lbl_accounting' => 'รายการฝากถอน'
  ];

  public static function setAccountingListDetail($code, $return, $options = []) {
    // [ExtractList]
    $selected_records = isset($return['list']) ? $return['list'] : $return;

    // Do Works...
    foreach ($selected_records as $key => $row) {
      // Do Each Works...
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
  | Group | รายการฝากถอน
  | Description | 1.ดึงข้อมูลลิส 'รายการฝากถอน'
  | $where | [รองรับauto_search(ตาราง sys_user_accounting)]
  | $options | [not_view(true // เอาแค่ข้อมูลหลักไป edit *เร็ว), selected_fields('*' // คอลัมภ์ที่ต้องการ), total_count(true // พร้อมจำนวนข้อมูลทั้งหมด ตามการกรอง(ไม่แบ่งหน้า)), page_no(0-N // เลขหน้า 0 คือส่งทั้งหมด), page_size('*' // จำนวนข้อมูลต่อหน้า), selected_fields('*' // คอลัมภ์ที่ต้องการ), sort(['column_name' => 'ASC/DESC'] // เรียงคอลัมภ์)]
   */
  public static function selectAccounting($code, $where = [], $options = []) {
    $return = []; // list

    $sql_where = Amst::mergeWhere($where); // Auto Where
    $table_name = !Util::isHasOptions('not_view', $options) ? 'sys_user_accounting' : static::$conf['tb_accounting'];
    $selected_fields = Amst::getSelectedFields($options);
    return Amst::selectNoKey($table_name, $selected_fields, $sql_where, $options);
  }

  public static function setAccountingDetail($code, $record, $options = []) {
    if (!isset($record['id'])) {
      return $record;
    }

    // Do Single Works...

    return $record;
  }

  /**
  | Group | รายการฝากถอน
  | Description | 2.ดึงข้อมูล 'รายการฝากถอน' ด้วยไอดี
  | $id | ไอดีรายการ
  | $options | [not_view(true // เอาแค่ข้อมูลหลักไป edit *เร็ว), selected_fields('*' // คอลัมภ์ที่ต้องการ)]
   */
  public static function getAccountingByID($code, $id, $options = []) {
    $return = null;

    $table_name = !Util::isHasOptions('not_view', $options) ? 'sys_user_accounting' : static::$conf['tb_accounting'];
    $selected_fields = Amst::getSelectedFields($options, ['id', 'system_delete']);

    if ($id) {
      $model = Amst::getByID($table_name, $selected_fields, $id, $options);
      if (isset($model['id'])) {
        $return = static::setAccountingDetail($code, $model, $options);
      }
    }
    return $return;
  }

  public static function addNewAccounting($code, $data) {
    $rep = [
      'insert_id' => false
    ];

    if (isset($data['user_id'])) {
      $model = Amst::getByID('sys_user', ['id', 'money_balance'], $data['user_id']);
    }
    if (!isset($model['id'])) {
      return Amst::repError('E0005', 'User');
    }

    if (!isset($data['slip_date_time'])) {
      $data['slip_date_time'] = Util::getSystemTimestamp();
    }

    if (isset($data['type'])) {
      if ($data['type'] == 'deposit' && isset($data['price']) && ($data['price'] > 0)) {
        $data['before_price'] = $model['money_balance'];
        $data['after_price'] = $model['money_balance'] + $data['price'];
        $u_data = [
          'money_balance[+]' => $data['price']
        ];
        $rep['update_result'] = Amst::updateByID('sys_user', $u_data, $model['id']);
      } else if (($data['type'] == 'withdraw') && isset($data['price']) && ($data['price'] > 0)) {
        $data['before_price'] = $model['money_balance'];
        $data['after_price'] = $model['money_balance'] - $data['price'];
        $u_data = [
          'money_balance[-]' => $data['price']
        ];
        $rep['update_result'] = Amst::updateByID('sys_user', $u_data, $model['id']);
      } else if (($data['type'] == 'withdraw_rejected') && isset($data['price']) && ($data['price'] > 0)) {
        $data['before_price'] = $model['money_balance'];
        $data['after_price'] = $model['money_balance'] + $data['price'];
        $u_data = [
          'money_balance[+]' => $data['price']
        ];
        $rep['update_result'] = Amst::updateByID('sys_user', $u_data, $model['id']);
      } else if (($data['type'] == 'remark')) {
        $data['before_price'] = $model['money_balance'];
        $data['after_price'] = $model['money_balance'];
        // DO nothing to User
      } else {
        return Amst::repError('E0000', 'Invalid Type');
      }
    } else {
      return Amst::repError('E0000', 'Invalid Type');
    }

    $api = Amst::insert(static::$conf['tb_accounting'], $data);
    if (isset($api['response_status']) && ($api['response_status'] == true)) {
      $insert_id = $api['response_data']['insert_id'];
      // Success
      $rep['insert_id'] = $insert_id;
    } else {
      return $api;
    }
    return Amst::repSuccess($rep);
  }
}
?>