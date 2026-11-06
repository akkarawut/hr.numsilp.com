<?php
class User_Bank_Account {
  private static $conf = [
    'description'      => 'ใช้จัดการบัญชีธนาคารของผู้ใช้งาน',
    'tb_bank_account'  => 'sys_bank_account',
    'lbl_bank_account' => 'บัญชีธนาคาร',
    'bank_img_path'    => 'util_bank_'
  ];

  public static function setBankAccountListDetail($return, $options = []) {
    // [ExtractList]
    $selected_records = isset($return['list']) ? $return['list'] : $return;

    // Do Each Works...
    $bank_list = Bank::select();
    foreach ($selected_records as $key => $row) {
      $selected_records[$key]['bank_name'] = '';
      if (isset($bank_list[$row['bank_abb']])) {
        $selected_records[$key]['bank_name'] = $bank_list[$row['bank_abb']]['name_th'];
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
  | Group | บัญชีธนาคาร
  | Description | 1.ดึงข้อมูลลิส 'บัญชีธนาคาร'
  | $where | [id(กรองตามไอดี), status(กรองตามสถานะ), is_approved(กรองตามการยืนยัน), is_rejected(กรองตามการปฏิเสท)]
  | $options | [not_view(true|เอาแค่ข้อมูลหลักไป edit*เร็ว), selected_fields(*|คอลัมภ์ที่ต้องการ), total_count(true|พร้อมจำนวนข้อมูลทั้งหมด ตามการกรอง(ไม่แบ่งหน้า)), page_no(1|เลขหน้า 0 คือส่งทั้งหมด), page_size(10|จำนวนข้อมูลต่อหน้า), sort_col(เรียงตามคอลัมภ์), sort_by(DESC|เรียงจากมากไปน้อย)]
   */
  public static function selectBankAccount($where = [], $options = []) {
    $return = []; // list

    $sql_where = [];
    if (isset($where['search_text']) && strlen("{$where['search_text']}")) {
      $sql_where['OR #search_text']['account_no[~]'] = "{$where['search_text']}";
      $sql_where['OR #search_text']['account_name[~]'] = "{$where['search_text']}";
    }
    if (isset($where['user_id']) && strlen($where['user_id'])) {
      $sql_where['AND']['user_id'] = $where['user_id'];
    } else {
      return Amst::selectEmptyList($options);
    }
    if (isset($where['is_default_account']) && strlen("{$where['is_default_account']}")) {
      $sql_where['AND']['is_default_account'] = $where['is_default_account'];
    }

    $table_name = !Util::isHasOptions('not_view', $options) ? 'sys_bank_account' : static::$conf['tb_bank_account'];
    $selected_fields = Util::getOptionsValue('selected_fields', $options, '*');
    if (Util::isHasOptions('bank_img_path', $options)) {
      $options['image']['bank_img_path'] = [
        'code'          => static::$conf['bank_img_path'],
        'target_column' => 'bank_abb',
        'debug'         => true
      ];
    }
    $return = Amst::selectNoKey($table_name, $selected_fields, $sql_where, $options);
    return static::setBankAccountListDetail($return, $options);
  }

  public static function setBankAccountDetail($record, $options = []) {
    if (!isset($record['id'])) {
      return $record;
    }

    // Do Single work..
    $bank_list = Bank::select();
    $record['bank_name'] = '';
    if (isset($bank_list[$record['bank_abb']])) {
      $record['bank_name'] = $bank_list[$record['bank_abb']]['name_th'];
    }

    return $record;
  }

  /**
  | Group | บัญชีธนาคาร
  | Description | 2.ดึงข้อมูล 'บัญชีธนาคาร' ด้วยไอดี
  | $id | ไอดีรายการ
  | $options | [not_view(true|เอาแค่ข้อมูลหลักไป edit*เร็ว), selected_fields(*|คอลัมภ์ที่ต้องการ)]
   */
  public static function getBankAccountByID($id, $options = []) {
    $return = null;

    $table_name = !Util::isHasOptions('not_view', $options) ? 'sys_bank_account' : static::$conf['tb_bank_account'];
    $selected_fields = Util::getOptionsValue('selected_fields', $options, '*');

    if ($id) {
      if (Util::isHasOptions('bank_img_path', $options)) {
        $options['image']['bank_img_path'] = [
          'code'          => static::$conf['bank_img_path'],
          'target_column' => 'bank_abb'
        ];
      }
      $model = Amst::getByID($table_name, $selected_fields, $id, $options);
      if (isset($model['id'])) {
        $return = static::setBankAccountDetail($model, $options);
      }
    }
    return $return;
  }

  /**
  | Group | บัญชีธนาคาร
  | Description | 2.ดึงข้อมูล 'บัญชีธนาคาร' ประจำตัวผู้ใช้ด้วยไอดี
  | $user_id | ไอดีผู้ใช้
  | $options | [not_view(true|เอาแค่ข้อมูลหลักไป edit*เร็ว), selected_fields(*|คอลัมภ์ที่ต้องการ)]
   */
  public static function getDefaltBankAccount($user_id, $options = []) {
    $return = null;

    $table_name = !Util::isHasOptions('not_view', $options) ? 'sys_bank_account' : static::$conf['tb_bank_account'];
    $selected_fields = Util::getOptionsValue('selected_fields', $options, '*');

    if ($user_id) {
      if (Util::isHasOptions('bank_img_path', $options)) {
        $options['image']['bank_img_path'] = [
          'code'          => static::$conf['bank_img_path'],
          'target_column' => 'bank_abb'
        ];
      }
      $where = [
        'AND' => [
          'user_id'            => $user_id,
          'is_default_account' => 1
        ]
      ];
      $model = Amst::get($table_name, $selected_fields, $where, $options);
      if (isset($model['id'])) {
        $return = static::setBankAccountDetail($model, $options);
      }
    }
    return $return;
  }

  /**
  | Group | บัญชีธนาคาร
  | Description | 3.เพิ่ม 'บัญชีธนาคาร'
  | $data | [user_id(user_id), bank_abb(bank_abb), account_no(account_no), account_name(account_name), is_default_account(is_default_account)]
  | CODE อัพโหลดรูป | 'img_path' => '_bank_account_'.$id = รูป Book Bank
   */
  public static function addNewBankAccount($data) {
    $rep = [
      'insert_id' => false
    ];

    if (!isset($data['user_id'])) {
      return Amst::repError('E0001', 'User ID');
    }

    if (!isset($data['bank_abb'])) {
      return Amst::repError('E0001', 'Bank ABB');
    }

    if (isset($data['bank_abb'])) {
      $data['bank_abb'] = trim(strtoupper($data['bank_abb']));
    }

    // [Single] = set all to 0 before INSERT
    if (isset($data['is_default_account']) && ($data['is_default_account'] == 1)) {
      $rep['update_result'] = Amst::update(static::$conf['tb_bank_account'], ['is_default_account' => 0], ['is_default_account' => 1, 'user_id' => $data['user_id']], ['direct' => true]);
    }

    return Amst::insert(static::$conf['tb_bank_account'], $data);
  }

  /**
  | Group | บัญชีธนาคาร
  | Description | 4.อัพเดท 'บัญชีธนาคาร'
  | $id | ไอดีรายการ
  | $data | [user_id(user_id), bank_abb(bank_abb), account_no(account_no), account_name(account_name), is_default_account(is_default_account)]
   */
  public static function updateBankAccount($id, $data) {
    $rep = [
      'update_result' => false
    ];

    $model = Amst::getByID(static::$conf['tb_bank_account'], ['id', 'user_id'], $id);
    if (!isset($model['id'])) {
      return Amst::repError('E0005', static::$conf['lbl_bank_account']);
    }

    if (isset($data['is_default_account']) && ($data['is_default_account'] == 1)) {
      $rep['update_result'] = Amst::update(static::$conf['tb_bank_account'], ['is_default_account' => 0], ['is_default_account' => 1, 'user_id' => $model['user_id']], ['direct' => true]);
    }

    return Amst::updateByID(static::$conf['tb_bank_account'], $data, $id);
  }

  /**
  | Group | บัญชีธนาคาร
  | Description | 4.อัพเดท 'รูปหน้าสมุดบัญชีธนาคาร' โค้ด คือ 'user_bank_account_'.$id
  | $id | ไอดีรายการ
  | $file | FILE_INPUT
   */
  public static function updateBookBankImage($id, $file = null) {
    $rep = [
      'update_result' => false
    ];

    if ($file) {
      return Amst::repError('E0005', static::$conf['lbl_bank_account']);
    }

    return Amst::repSuccess($rep);
  }

  /**
  | Group | บัญชีธนาคาร
  | Description | 5.ลบ 'บัญชีธนาคาร'
  | $id | ไอดีรายการ
   */
  public static function deleteBankAccount($id) {
    $rep = [
      'delete_result' => false
    ];

    $api = static::_checkDeleteAccount($id);
    if (isset($api['response_status']) && ($api['response_status'] == true)) {
      $new_account_id = $api['response_data']['new_account_id'];
      $rep['delete_result'] = Amst::deleteByID(static::$conf['tb_bank_account'], $id);
      // Change Default
      if ($new_account_id) {
        $rep['update_result'] = Amst::updateByID(static::$conf['tb_bank_account'], ['is_default_account' => 1], $new_account_id, ['direct' => true]);
      }
      return Amst::repSuccess($rep);
    }
    return $api; // ERROR SURE
  }

  private static function _checkDeleteAccount($id) {
    $rep = [
      'id'             => $id,
      'new_account_id' => false
    ];

    // [Model_Exist]
    $model = Amst::getByID(static::$conf['tb_bank_account'], ['id', 'is_default_account'], $id);
    if (!isset($model['id'])) {
      return Amst::repError('E0005', static::$conf['lbl_bank_account']);
    }

    // Change Default
    if ($model['is_default_account'] == 1) {
      $where = [
        'AND' => [
          'id[!]' => $id
        ]
      ];
      $other_model = Amst::get(static::$conf['tb_bank_account'], ['id'], $where);
      if (isset($other_model['id'])) {
        $rep['new_account_id'] = $other_model['id'];
      }
    }

    return Amst::repSuccess($rep);
  }
}
