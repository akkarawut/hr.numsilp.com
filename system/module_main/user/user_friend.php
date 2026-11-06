<?php
class User_Friend {
  private static $conf = [
    'tb_user'    => 'sys_user',
    'lbl_user'   => 'ผู้ใช้งานระบบ',
    'tb_friend'  => 'sys_user_friend',
    'lbl_friend' => 'รายชื่อเพื่อน'
  ];

  public static function countFriendList($where = [], $options = []) {
    $return = 0; // count

    $options = Amst::checkOption($options);

    $sql_where = [
      'AND' => [
        'is_accepted' => 1
      ]
    ];
    if (isset($where['search_text']) && strlen("{$where['search_text']}")) {
      // VIEW $sql_where['OR #search_text']['username[~]'] = "{$where['search_text']}";
    }
    if (isset($where['my_user_id']) && strlen("{$where['my_user_id']}") && isset($where['friend_user_id']) && strlen("{$where['friend_user_id']}")) {
      $sql_where['OR']['AND #mutual1']['my_user_id'] = "{$where['my_user_id']}";
      $sql_where['OR']['AND #mutual1']['friend_user_id'] = "{$where['friend_user_id']}";
      $sql_where['OR']['AND #mutual2']['my_user_id'] = "{$where['friend_user_id']}";
      $sql_where['OR']['AND #mutual2']['friend_user_id'] = "{$where['my_user_id']}";
    } else if (isset($where['user_id']) && strlen("{$where['user_id']}")) {
      $sql_where['OR #mutual']['my_user_id'] = "{$where['user_id']}";
      $sql_where['OR #mutual']['friend_user_id'] = "{$where['user_id']}";
    } else {
      return $return;
    }

    $table_name = !Util::isHasOptions('not_view', $options) ? 'sys_user_friend' : static::$conf['tb_friend'];
    return Amst::count($table_name, $sql_where);
  }

  /**
  | Group | รายชื่อเพื่อน
  | Description | 1.ดึงข้อมูลลิส 'คำขอเป็นเพื่อน'
  <hr>
  <h5>$where = [ //กรองข้อมูลตาม ส่งอันไหนก็จะกรองเฉพาะอันนั้น</h5>
  <ul>
  <li>'search_text' => $search_text, // "กรองข้อมูลจาก username",</li>
  <li>'user_id' => $user_id, // "กรองข้อมูลจาก user_id",</li>
  <li>];</li>
  </ul>
  <hr>
  <h5>$options = [ // วิธีการดึงข้อมูลเพิ่มเติม</h5>
  <ul>
  <li>'not_view' => true, // เอาเฉพาะข้อมูลหลัก *ใช้ในการ edit *เร็วกว่าปกติ</li>
  <li>'total_count' => true, // พร้อมจำนวนข้อมูลทั้งหมด ตามการกรอง(ไม่แบ่งหน้า)</li>
  <li>'page_no' => 0-N, // เลขหน้า *=0 คือส่งทั้งหมด</li>
  <li>'page_size' => 1-N, // จำนวนข้อมูลต่อหน้า</li>
  <li>'selected_fields' => '*', // Array ของ คอลัมภ์ที่ต้องการ</li>
  <li>'sort' => ['column_name' => 'ASC/DESC'], // เรียงคอลัมภ์</li>
  <li>];</li>
  </ul>
   */
  public static function selectFriendRequest($where = [], $options = []) {
    $return = []; // friend_list

    $sql_where = [
      'AND' => [
        'is_accepted' => [0, -1] // *แสดงที่รอ + ปฏิเสทแล้ว เพื่อรอลบ
      ]
    ];
    if (isset($where['search_text']) && strlen("{$where['search_text']}")) {
      // VIEW $sql_where['OR #search_text']['username[~]'] = "{$where['search_text']}";
    }
    if (isset($where['user_id']) && strlen("{$where['user_id']}")) {
      $sql_where['AND']['friend_user_id'] = "{$where['user_id']}"; // เพื่อนปลายทางเท่านั้นที่จะเห็น
    }
    // <li>'is_accepted' => [0 รอการตอบรับ, 1 ยอมรับแล้ว, -1 ปฏิเสทแล้ว],</li>

    $table_name = !Util::isHasOptions('not_view', $options) ? 'sys_user_friend_request_list' : static::$conf['tb_friend'];
    $selected_fields = Util::getOptionsValue('selected_fields', $options, '*');

    return Amst::selectNoKey($table_name, $selected_fields, $sql_where, $options);
  }

  public static function setFriendListDetail($code, $return, $options = []) {
    $selected_records = (isset($return['list'])) ? $return['list'] : $return;

    // [MUTUAL]
    $user_id = Util::getOptionsValue('user_id', $options, false);
    if ($user_id) {
      $friend_ids = [];
      foreach ($selected_records as $key => $row) {
        $selected_records[$key]['friend_full_name'] = ''; // using Fields

        if (isset($row['my_user_id']) && ($row['my_user_id'] != $user_id)) {
          $friend_ids[$row['my_user_id']] = $row['my_user_id'];
        }
        if (isset($row['friend_user_id']) && ($row['friend_user_id'] != $user_id)) {
          $friend_ids[$row['friend_user_id']] = $row['friend_user_id'];
        }
      }
      if ($friend_ids) {
        $where = [
          'AND' => [
            'id' => array_values($friend_ids)
          ]
        ];
        $external_records = Amst::selectNoKey('sys_user', ['id', 'full_name'], $where);
        foreach ($external_records as $row) {
          $friend_list[$row['id']] = $row;
        }

        foreach ($selected_records as $key => $row) {
          if (($row['my_user_id'] != $user_id) && isset($friend_list[$row['my_user_id']])) {
            $selected_records[$key]['friend_full_name'] = $friend_list[$row['my_user_id']]['full_name']; // using Fields
          }
          if (($row['friend_user_id'] != $user_id) && isset($friend_list[$row['friend_user_id']])) {
            $selected_records[$key]['friend_full_name'] = $friend_list[$row['friend_user_id']]['full_name']; // using Fields
          }
        }
        unset($friend_list);
      }
    }

    if (isset($return['list'])) {
      $return['list'] = $selected_records;
    } else {
      $return = $selected_records;
    }

    return $return;
  }

  /**
  | Group | รายชื่อเพื่อน
  | Description | 1.ดึงข้อมูลลิส 'รายชื่อเพื่อน'
  <hr>
  <h5>$where = [ //กรองข้อมูลตาม ส่งอันไหนก็จะกรองเฉพาะอันนั้น</h5>
  <ul>
  <li>'search_text' => $search_text, // "กรองข้อมูลจาก username",</li>
  <li>'user_id' => $user_id, // "กรองข้อมูลจาก user_id", **บังคับส่ง</li>
  <li>'with_request' => 1, // "ลิสนี้จะรวมคำขอเป็นเพื่อนด้วย",</li>
  <li>];</li>
  </ul>
  <hr>
  <h5>$options = [ // วิธีการดึงข้อมูลเพิ่มเติม</h5>
  <ul>
  <li>'not_view' => true, // เอาเฉพาะข้อมูลหลัก *ใช้ในการ edit *เร็วกว่าปกติ</li>
  <li>'total_count' => true, // พร้อมจำนวนข้อมูลทั้งหมด ตามการกรอง(ไม่แบ่งหน้า)</li>
  <li>'page_no' => 0-N, // เลขหน้า *=0 คือส่งทั้งหมด</li>
  <li>'page_size' => 1-N, // จำนวนข้อมูลต่อหน้า</li>
  <li>'selected_fields' => '*', // Array ของ คอลัมภ์ที่ต้องการ</li>
  <li>'sort' => ['column_name' => 'ASC/DESC'], // เรียงคอลัมภ์</li>
  <li>];</li>
  </ul>
   */
  public static function selectFriend($where = [], $options = []) {
    $return = []; // list

    $options = Amst::checkOption($options);

    $sql_where = [
      'AND' => [
        'is_accepted' => 1 // onlyFan
      ]
    ];
    if (isset($where['search_text']) && strlen("{$where['search_text']}")) {
      // VIEW $sql_where['OR #search_text']['username[~]'] = "{$where['search_text']}";
    }
    if (isset($where['user_id']) && $where['user_id']) {
      $options['user_id'] = $where['user_id'];
      $sql_where['OR #mutual']['my_user_id'] = $where['user_id'];
      $sql_where['OR #mutual']['friend_user_id'] = $where['user_id'];
    } else {
      $options['err_msg'] = 'Invalid User ID'.json_encode($where);
      return Amst::selectEmptyList($options);
    }
    if (isset($where['with_request']) && $where['with_request']) {
      $sql_where['AND']['is_accepted'] = [0, 1, -1]; // SHOW ALL
    }

    $table_name = !Util::isHasOptions('not_view', $options) ? 'sys_user_friend' : static::$conf['tb_friend'];
    $selected_fields = Util::getOptionsValue('selected_fields', $options, '*');

    $return = Amst::selectNoKey($table_name, $selected_fields, $sql_where, $options);
    return static::setFriendListDetail('', $return, $options);
  }

  /**
  | Group | รายชื่อเพื่อน
  | Description | 1. เช็ค 'ว่าเป็นเพื่อนกันหรือยัง'
   */
  public static function isMutalFriend($my_user_id, $friend_user_id) {
    $return = false; // true

    // [Mutual] - [LATER] select ?
    if ($my_user_id && $friend_user_id) {
      $where = [
        'my_user_id'     => $my_user_id,
        'friend_user_id' => $friend_user_id
      ];
      return static::countFriendList($where) == 1;
    }
    return $return;
  }

  /**
  | Group | รายชื่อเพื่อน
  | Description | 3.เพิ่ม 'รายชื่อเพื่อน'
  <hr>
  <h5>'sys_user_friend' => array(</h5>
  <ul>
  <li>'my_user_id' => 'my_user_id',</li>
  <li>'friend_user_id' => 'friend_user_id',</li>
  <li>'is_accepted' => 'is_accepted',</li>
  <li>);</li>
  </ul>
   */
  private static function _addNewFriend($data) {
    $rep = [
      'insert_id' => false
    ];

    $data['is_accepted'] = 0;

    if (!isset($data['my_user_id']) || !$data['my_user_id'] || !isset($data['friend_user_id']) || !$data['friend_user_id']) {
      return Amst::repError('E0000', 'Invalid Input');
    }
    if ($data['my_user_id'] == $data['friend_user_id']) {
      return Amst::repError('E0000', 'Are you alone ?');
    }

    $model_user = Amst::getByID(static::$conf['tb_user'], ['id', 'username'], $data['my_user_id']);
    if (!isset($model_user['id'])) {
      return Amst::repError('E0005', 'User');
    }

    // [Mutual] - [LATER] select ?
    $model1 = Amst::get(static::$conf['tb_friend'], ['id'], ['my_user_id' => $data['my_user_id'], 'friend_user_id' => $data['friend_user_id']]);
    if (isset($model1['id'])) {
      return Amst::repError('E0000', 'Already Friend or Request Sent');
    }
    $model2 = Amst::get(static::$conf['tb_friend'], ['id'], ['my_user_id' => $data['friend_user_id'], 'friend_user_id' => $data['my_user_id']]);
    if (isset($model2['id'])) {
      return Amst::repError('E0000', 'Already Friend or Request Sent');
    }

    $insert_id = Amst::insert(static::$conf['tb_friend'], $data, ['direct' => true]);
    if ($insert_id) {
      $model = Amst::getByID('sys_user_friend', '*', $insert_id);
    }
    if (isset($model['id'])) {
      $rep['insert_id'] = $insert_id;
      // [NOTI_1] After user accept the friend request system will send notification to user so they can add or reject request there
      $n_data = [ // กรอกข้อมูลเท่าที่ใช้
        'user_id'       => $data['friend_user_id'],
        'from_user_id'  => $data['my_user_id'],
        'topic'         => 'FRIEND REQUEST',
        'description'   => "User '{$model_user['username']}' sent you a friend request.",
        'type'          => 'friend_request',
        'code'          => '',
        'module'        => 'User',
        'ref_id'        => $insert_id,
        'ref_info_json' => json_encode([
          'request_id'     => $insert_id,
          'friend_user_id' => $model_user['id']
        ]),
        'is_read'       => 0,
        'is_show'       => '1'
      ];
      User_Notification::addNewNotification('code', $n_data);
    } else {
      return Amst::repError('E0005', static::$conf['lbl_friend']);
    }
    return Amst::repSuccess($rep);
  }

  /**
  | Group | รายชื่อเพื่อน
  | Description | 4.ส่งคำขอเป็นเพื่อน
   */
  public static function sentFriendRequest($my_user_id, $friend_user_id) {
    $rep = [
      'update_result' => false
    ];

    $my_model = Amst::getByID(static::$conf['tb_user'], ['id', 'username'], $my_user_id);
    if (!isset($my_model['id'])) {
      return Amst::repError('E0005', static::$conf['lbl_user']);
    }
    $friend_model = Amst::getByID(static::$conf['tb_user'], ['id', 'username'], $friend_user_id);
    if (!isset($friend_model['id'])) {
      $friend_model = Amst::get(static::$conf['tb_user'], ['id', 'username'], ['username' => $friend_user_id]);
    }
    if (!isset($friend_model['id'])) {
      return Amst::repError('E0005', static::$conf['lbl_user']);
    }

    $n_data = [
      'my_user_id'     => $my_model['id'],
      'friend_user_id' => $friend_model['id']
    ];
    return static::_addNewFriend($n_data);
  }

  /**
  | Group | รายชื่อเพื่อน
  | Description | 4.ยอมรับเป็นเพื่อน
   */
  public static function acceptFriendRequest($request_id) {
    $rep = [
      'update_result' => false
    ];

    $model = Amst::getByID(static::$conf['tb_friend'], ['id'], $request_id);
    if (!isset($model['id'])) {
      return Amst::repError('E0005', static::$conf['lbl_friend']);
    }

    $u_data = [
      'is_accepted' => 1
    ];
    $rep['update_result'] = Amst::update(static::$conf['tb_friend'], $u_data, ['id' => $request_id, 'is_accepted' => 0], ['direct' => true]);
    return Amst::repSuccess($rep);
  }

  /**
  | Group | รายชื่อเพื่อน
  | Description | 4.ยอมรับเป็นเพื่อน
   */
  public static function rejectFriendRequest($request_id, $delete_request = true) {
    $rep = [
      'update_result' => false
    ];

    $model = Amst::getByID(static::$conf['tb_friend'], ['id', 'is_accepted'], $request_id);
    if (!isset($model['id'])) {
      return Amst::repError('E0005', static::$conf['lbl_friend']);
    }

    $u_data = [
      'is_accepted' => -1
    ];
    $rep['update_result'] = Amst::update(static::$conf['tb_friend'], $u_data, ['id' => $request_id, 'is_accepted' => 0], ['direct' => true]);
    if ($delete_request) {
      $rep['delete_result'] = static::deleteFriendRequest($request_id);
    }
    return Amst::repSuccess($rep);
  }

  /**
  | Group | รายชื่อเพื่อน
  | Description | 4.ลบคำขอ
   */
  public static function deleteFriendRequest($request_id) {
    $rep = [
      'update_result' => false
    ];

    $model = Amst::get(static::$conf['tb_friend'], ['id', 'is_accepted'], ['id' => $request_id, 'is_accepted[!]' => 1]);
    if (!isset($model['id'])) {
      return Amst::repError('E0005', static::$conf['lbl_friend']);
    }

    $rep['delete_result'] = Amst::deleteByID(static::$conf['tb_friend'], $request_id);
    return Amst::repSuccess($rep);
  }

  /**
  | Group | รายชื่อเพื่อน
  | Description | 5.ลบ 'รายชื่อเพื่อน'
  | $id | ไอดีรายการ
   */
  public static function deleteFriend($my_user_id, $friend_user_id) {
    $rep = [
      'delete_result' => false
    ];

    $api = static::_checkDeleteFriend($my_user_id, $friend_user_id);
    if (isset($api['response_status']) && ($api['response_status'] == true)) {
      // [Mutual]
      $rep['delete_result'] = Amst::delete(static::$conf['tb_friend'], ['my_user_id' => $friend_user_id, 'friend_user_id' => $my_user_id]);
      $rep['delete_result2'] = Amst::delete(static::$conf['tb_friend'], ['my_user_id' => $my_user_id, 'friend_user_id' => $friend_user_id]);
      return Amst::repSuccess($rep);
    }
    return $api; // ERROR SURE
  }

  private static function _checkDeleteFriend($my_user_id, $friend_user_id) {
    $rep = [
      'id' => false
    ];

    // [Model_Exist]
    // $model = Amst::getByID(static::$conf['tb_friend'], ['id'], ['my_user_id' => $my_user_id, 'friend_user_id' => $friend_user_id]);
    // if (!isset($model['id'])) {
    //   return Amst::repError('E0005', static::$conf['lbl_friend']);
    // }

    return Amst::repSuccess($rep);
  }
}
?>