<?php
class User_Address {
  private static $conf = [
    'tb_address'  => 'sys_user_address',
    'lbl_address' => 'ที่อยู่'
  ];

  /**
  | Group | ที่อยู่
  | Description | 1.ดึงข้อมูลลิส 'ที่อยู่'
  | $where | [search_text(address), user_id(user_id), type_name(type_name)]
  | $options | [not_view(true|เอาแค่ข้อมูลหลักไป edit*เร็ว), selected_fields(*|คอลัมภ์ที่ต้องการ), total_count(true|พร้อมจำนวนข้อมูลทั้งหมด ตามการกรอง(ไม่แบ่งหน้า)), page_no(1|เลขหน้า 0 คือส่งทั้งหมด), page_size(10|จำนวนข้อมูลต่อหน้า), sort_col(เรียงตามคอลัมภ์), sort_by(DESC|เรียงจากมากไปน้อย)]
   */
  public static function selectAddress($where = [], $options = []) {
    $return = []; // address_list

    $sql_where = [];
    if (isset($where['search_text']) && strlen("{$where['search_text']}")) {
      $sql_where['OR #search_text']['address[~]'] = "{$where['search_text']}";
    }
    if (isset($where['user_id']) && strlen("{$where['user_id']}")) {
      $sql_where['AND']['user_id'] = "{$where['user_id']}";
    }
    if (isset($where['type_name']) && strlen("{$where['type_name']}")) {
      $sql_where['AND']['type_name'] = "{$where['type_name']}";
    }

    $table_name = !Util::isHasOptions('not_view', $options) ? 'sys_user_address' : static::$conf['tb_address'];
    $selected_fields = Util::getOptionsValue('selected_fields', $options, '*');

    return Amst::selectNoKey($table_name, $selected_fields, $sql_where, $options);
  }

  public static function setAddressDetail($record, $options = []) {
    if (!isset($record['id'])) {
      return $record;
    }

    return $record;
  }

  /**
  | Group | ที่อยู่
  | Description | 2.ดึงข้อมูล 'ที่อยู่' ด้วยไอดี
  | $id | ไอดีที่อยู่
  | $options | [not_view(true|เอาแค่ข้อมูลหลักไป edit*เร็ว), selected_fields(*|คอลัมภ์ที่ต้องการ)]
   */
  public static function getAddressByID($id, $options = []) {
    $return = null;

    $table_name = !Util::isHasOptions('not_view', $options) ? 'sys_user_address' : static::$conf['tb_address'];
    $selected_fields = Util::getOptionsValue('selected_fields', $options, '*');

    if ($id) {
      $model = Amst::getByID($table_name, $selected_fields, $id, $options);
      if (isset($model['id'])) {
        $return = static::setAddressDetail($model, $options);
      }
    }
    return $return;
  }

  /**
  | Group | ที่อยู่
  | Description | 2.ดึงข้อมูล 'ที่อยู่' ด้วยไอดี
  | $user_id | ไอดีผู้ใช้งาน
  | $options | [not_view(true|เอาแค่ข้อมูลหลักไป edit*เร็ว), selected_fields(*|คอลัมภ์ที่ต้องการ)]
   */
  public static function getAddressByUserID($user_id, $options = []) {
    $return = null;

    $table_name = !Util::isHasOptions('not_view', $options) ? 'sys_user_address' : static::$conf['tb_address'];
    $selected_fields = Util::getOptionsValue('selected_fields', $options, '*');

    if ($user_id) {
      $model = Amst::get($table_name, $selected_fields, ['user_id' => $user_id, 'type_name' => 'user_address'], $options);
      if (isset($model['id'])) {
        $return = static::setAddressDetail($model, $options);
      } else {
        // [SINGLE_ADDRESS]
        $n_data = [
          'user_id' => $user_id
        ];
        static::addNewAddress($n_data);
        // REGET **DO NOT RECURSIVE
        $model = Amst::get($table_name, $selected_fields, ['user_id' => $user_id, 'type_name' => 'user_address'], $options);
        if (isset($model['id'])) {
          $return = static::setAddressDetail($model, $options);
        }
      }
    }
    return $return;
  }

  /**
  | Group | ที่อยู่
  | Description | 2.ดึงข้อมูล 'ที่อยู่ สำหรับจัดส่ง ตั้งต้น' ด้วยไอดี
  | $user_id | ไอดีผู้ใช้งาน
  | $options | [not_view(true|เอาแค่ข้อมูลหลักไป edit*เร็ว), selected_fields(*|คอลัมภ์ที่ต้องการ)]
   */
  public static function getDefaltShippingAddress($user_id, $options = []) {
    $return = null;

    $table_name = !Util::isHasOptions('not_view', $options) ? 'sys_user_address' : static::$conf['tb_address'];
    $selected_fields = Util::getOptionsValue('selected_fields', $options, '*');

    if ($user_id) {
      $model = Amst::get($table_name, $selected_fields, ['user_id' => $user_id, 'type_name' => 'user_address', 'is_default' => 1], $options);
      if (isset($model['id'])) {
        $return = static::setAddressDetail($model, $options);
      }
    }
    return $return;
  }

  /**
  | Group | ที่อยู่
  | Description | 3.เพิ่ม 'ที่อยู่'
  | $data | [user_id(user_id), type_name(type_name)]
   */
  public static function addNewAddress($data) {
    $rep = [
      'insert_id' => false
    ];

    if (!isset($data['type_name'])) {
      $data['type_name'] = 'user_address'; // DEF
    }

    // [SINGLE_ADDRESS] เคลียค่า default ออกก่อน ถ้าส่งมาเป็น 1
    if (isset($data['is_default']) && $data['is_default']) {
      $where = [
        'AND' => [
          'user_id' => $data['user_id']
        ]
      ];
      $data_update = [
        'is_default' => 0
      ];
      $un_default = Amst::update(static::$conf['tb_address'], $data_update, $where);
    }

    return Amst::insert(static::$conf['tb_address'], $data);
  }

  /**
  | Group | ที่อยู่
  | Description | 4.อัพเดท 'ที่อยู่'
  | $id | ไอดีรายการ
  | $data | [user_id(user_id), type_name(type_name)]
   */
  public static function updateAddress($id, $data) {
    $rep = [
      'update_result' => false
    ];

    $model = Amst::getByID(static::$conf['tb_address'], ['id', 'user_id'], $id);
    if (!isset($model['id'])) {
      return Amst::repError('E0005', static::$conf['lbl_address']);
    }

    // เคลียค่า default ออกก่อน ถ้าส่งมาเป็น 1
    if (isset($data['is_default']) && ($data['is_default'] == 1)) {
      $where = [
        'AND' => [
          'user_id' => $model['user_id']
        ]
      ];
      $data_update = [
        'is_default' => 0
      ];
      $un_default = Amst::update(static::$conf['tb_address'], $data_update, $where);
    }

    return Amst::updateByID(static::$conf['tb_address'], $data, $id);
  }

  /**
  | Group | ที่อยู่
  | Description | 4.อัพเดท 'ที่อยู่'
  | $user_id | ไอดีผู้ใช้
  | $data | [user_id(user_id), type_name(type_name)]
   */
  public static function updateAddressByUserID($user_id, $data) {
    $rep = [
      'update_result' => false
    ];

    $model = static::getAddressByUserID($user_id, ['selected_fields' => ['id', 'user_id']]);
    if (!isset($model['id'])) {
      return Amst::repError('E0005', static::$conf['lbl_address']);
    }

    // [Single] เคลียค่า default ออกก่อน ถ้าส่งมาเป็น 1
    if (isset($data['is_default']) && $data['is_default']) {
      $where = [
        'AND' => [
          'user_id' => $model['user_id']
        ]
      ];
      $u_data = [
        'is_default' => 0
      ];
      $un_default = Amst::update(static::$conf['tb_address'], $u_data, $where);
    }

    return Amst::updateByID(static::$conf['tb_address'], $data, $model['id']);
  }

  /**
  | Group | ที่อยู่
  | Description | 5.ลบ 'ที่อยู่'
  | $id | ไอดีรายการ
   */
  public static function deleteAddress($id) {
    $rep = [
      'delete_result' => false
    ];

    $api = static::_checkDeleteAddress($id);
    if (isset($api['response_status']) && ($api['response_status'] == true)) {
      $rep['delete_result'] = Amst::deleteByID(static::$conf['tb_address'], $id);
      return Amst::repSuccess($rep);
    }
    return $api; // ERROR SURE
  }

  private static function _checkDeleteAddress($id) {
    $rep = [
      'id' => $id
    ];

    // [Model_Exist]
    $model = Amst::getByID(static::$conf['tb_address'], ['id'], $id);
    if (!isset($model['id'])) {
      return Amst::repError('E0005', static::$conf['lbl_address']);
    }

    return Amst::repSuccess($rep);
  }

  /**
  | Group | ที่อยู่สำหรับติดต่อ
  | Description | 2.ดึงข้อมูล 'ที่อยู่สำหรับติดต่อ' ด้วยไอดีผู้ใช้
  | $user_id | ไอดีผู้ใช้งาน
  | $options | [not_view(true|เอาแค่ข้อมูลหลักไป edit*เร็ว), selected_fields(*|คอลัมภ์ที่ต้องการ)]
   */
  public static function getContactAddressByUserID($user_id, $options = []) {
    $return = null;

    $table_name = !Util::isHasOptions('not_view', $options) ? 'sys_user_address' : static::$conf['tb_address'];
    $selected_fields = Util::getOptionsValue('selected_fields', $options, '*');

    if ($user_id) {
      $model = Amst::get($table_name, $selected_fields, ['user_id' => $user_id, 'type_name' => 'contact'], $options);
      if (isset($model['id'])) {
        $return = static::setAddressDetail($model, $options);
      } else {
        // [SINGLE_ADDRESS]
        $n_data = [
          'user_id'   => $user_id,
          'type_name' => 'contact'
        ];
        static::addNewAddress($n_data);
        // REGET **DO NOT RECURSIVE
        $model = Amst::get($table_name, $selected_fields, ['user_id' => $user_id, 'type_name' => 'contact'], $options);
        if (isset($model['id'])) {
          $return = static::setAddressDetail($model, $options);
        }
      }
    }
    return $return;
  }
}
