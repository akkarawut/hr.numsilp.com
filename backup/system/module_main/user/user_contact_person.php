<?php
class User_Contact_Person {
  private static $conf = [
    'tb_contact_person'  => 'sys_contact_person',
    'lbl_contact_person' => 'รายชื่อผู้ติดต่อ'
  ];

  /**
  | Group | รายชื่อผู้ติดต่อ
  | Description | 1.ดึงข้อมูลลิส 'รายชื่อผู้ติดต่อ'
  <hr>
  <h5>$where = [ //กรองข้อมูลตาม ส่งอันไหนก็จะกรองเฉพาะอันนั้น</h5>
  <ul>
  <li>'search_text' => $search_text, // "กรองข้อมูลจาก ชื่อบัญชี เลขบัญชี",</li>
  <li>'user_id' => $user_id, // ไอดีผู้ใช้ *บังคับส่ง</li>
  <li>];</li>
  </ul>
  <hr>
  <h5>$options = [ // วิธีการดึงข้อมูลเพิ่มเติม</h5>
  <ul>
  <li>'not_view' => true, // เอาแค่ข้อมูลหลักไป edit *เร็ว</li>
  <li>'total_count' => true, // พร้อมจำนวนข้อมูลทั้งหมด ตามการกรอง(ไม่แบ่งหน้า)</li>
  <li>'page_no' => 0-N, // เลขหน้า *=0 คือส่งทั้งหมด</li>
  <li>'page_size' => 1-N, // จำนวนข้อมูลต่อหน้า</li>
  <li>'selected_fields' => '*', // คอลัมภ์ที่ต้องการ</li>
  <li>'sort' => ['column_name' => 'ASC/DESC'], // เรียงคอลัมภ์</li>
  <li>];</li>
  </ul>
   */
  public static function selectContact($where = [], $options = []) {
    $return = []; // list

    $sql_where = [];
    if (isset($where['search_text']) && strlen("{$where['search_text']}")) {
      $sql_where['OR #search_text']['name[~]'] = "{$where['search_text']}";
      $sql_where['OR #search_text']['relationship[~]'] = "{$where['search_text']}";
    }
    if (isset($where['user_id']) && strlen($where['user_id'])) {
      $sql_where['AND']['user_id'] = $where['user_id'];
    } else {
      return Amst::selectEmptyList($options);
    }

    $table_name = !Util::isHasOptions('not_view', $options) ? 'sys_contact_person' : static::$conf['tb_contact_person'];
    $selected_fields = Util::getOptionsValue('selected_fields', $options, '*');

    return Amst::selectNoKey($table_name, $selected_fields, $sql_where, $options);
  }

  public static function setContactDetail($record, $options = []) {
    if (!isset($record['id'])) {
      return $record;
    }

    return $record;
  }

  /**
  | Group | รายชื่อผู้ติดต่อ
  | Description | 2.ดึงข้อมูล 'รายชื่อผู้ติดต่อ' ด้วยไอดี
  <hr>
  <h5>$options = [ // วิธีการดึงข้อมูลเพิ่มเติม</h5>
  <ul>
  <li>'not_view' => true, // เอาแค่ข้อมูลหลักไป edit *เร็ว</li>
  <li>'selected_fields' => '*', // คอลัมภ์ที่ต้องการ</li>
  <li>];</li>
  </ul>
   */
  public static function getContactByID($id, $options = []) {
    $return = null;

    $table_name = !Util::isHasOptions('not_view', $options) ? 'sys_contact_person' : static::$conf['tb_contact_person'];
    $selected_fields = Util::getOptionsValue('selected_fields', $options, '*');

    if ($id) {
      $model = Amst::getByID($table_name, $selected_fields, $id, $options);
      if (isset($model['id'])) {
        $return = static::setContactDetail($model, $options);
      }
    }
    return $return;
  }

  /**
  | Group | รายชื่อผู้ติดต่อ
  | Description | 2.ดึงข้อมูล 'รายชื่อผู้ติดต่อ' ด้วยไอดี
  <hr>
  <h5>$options = [ // วิธีการดึงข้อมูลเพิ่มเติม</h5>
  <ul>
  <li>'not_view' => true, // เอาแค่ข้อมูลหลักไป edit *เร็ว</li>
  <li>'selected_fields' => '*', // คอลัมภ์ที่ต้องการ</li>
  <li>];</li>
  </ul>
   */
  public static function getUserDefaultContact($user_id, $options = []) {
    $return = null;

    $table_name = static::$conf['tb_contact_person'];
    $selected_fields = Util::getOptionsValue('selected_fields', $options, '*');

    if ($user_id) {
      $where = [
        'AND' => [
          'user_id'       => $user_id,
          'system_delete' => 0
        ]
      ];
      $model = Amst::get($table_name, $selected_fields, $where, $options);
      if (isset($model['id'])) {
        $return = static::setContactDetail($model, $options);
      } else {
        // REGET
        $n_data = [
          'user_id' => $user_id
        ];
        static::addNewContact($n_data);
        // REGET
        $model = Amst::get($table_name, $selected_fields, $where, $options);
        if (isset($model['id'])) {
          $return = static::setContactDetail($model, $options);
        }
      }
    }
    return $return;
  }

  /**
  | Group | รายชื่อผู้ติดต่อ
  | Description | 3.เพิ่ม 'รายชื่อผู้ติดต่อ'
  <hr>
  <h5>$data = [ // กรอกข้อมูลเท่าที่ใช้</h5>
  <ul>
  <li>'user_id' => 'user_id', ** บังคับส่ง</li>
  <li>'name' => 'name',</li>
  <li>'relationship' => 'relationship',</li>
  <li>'tel_country_code' => 'tel_country_code',</li>
  <li>'tel_no' => 'tel_no',</li>
  <li>'line_id' => 'line_id',</li>
  <li>'email' => 'email',</li>
  <li>];</li>
  </ul>
  <hr>
  <h5>CODE อัพโหลดรูป + ไฟล์ **ได้อย่างละ 1 รูป/ไฟล์</h5>
  <ul>
  <li>'img_path' => '_contact_person_'.$id, // รูป Cover</li>
  </ul>
   */
  public static function addNewContact($data) {
    $rep = [
      'insert_id' => false
    ];

    if (!isset($data['user_id'])) {
      return Amst::repError('E0001', 'User ID');
    }

    return Amst::insert(static::$conf['tb_contact_person'], $data);
  }

  /**
  | Group | รายชื่อผู้ติดต่อ
  | Description | 4.อัพเดท 'รายชื่อผู้ติดต่อ'
  <hr>
  <h5>$data = [ // กรอกข้อมูลเท่าที่ใช้</h5>
  <ul>
  <li>'name' => 'name',</li>
  <li>'relationship' => 'relationship',</li>
  <li>'tel_country_code' => 'tel_country_code',</li>
  <li>'tel_no' => 'tel_no',</li>
  <li>'line_id' => 'line_id',</li>
  <li>'email' => 'email',</li>
  <li>];</li>
  </ul>
  <hr>
  <h5>CODE อัพโหลดรูป + ไฟล์ **ได้อย่างละ 1 รูป/ไฟล์</h5>
  <ul>
  <li>'img_path' => '_contact_person_'.$id, // รูป Cover</li>
  </ul>
   */
  public static function updateContact($id, $data) {
    $rep = [
      'update_result' => false
    ];

    $model = Amst::getByID(static::$conf['tb_contact_person'], ['id', 'user_id'], $id);
    if (!isset($model['id'])) {
      return Amst::repError('E0005', static::$conf['lbl_contact_person']);
    }

    return Amst::updateByID(static::$conf['tb_contact_person'], $data, $id);
  }

  /**
  | Group | รายชื่อผู้ติดต่อ
  | Description | 5.ลบ 'รายชื่อผู้ติดต่อ'
  | $id | ไอดีรายการ
   */
  public static function deleteContact($id) {
    $rep = [
      'delete_result' => false
    ];

    $api = static::_checkDeleteContact($id);
    if (isset($api['response_status']) && ($api['response_status'] == true)) {
      $rep['delete_result'] = Amst::deleteByID(static::$conf['tb_contact_person'], $id);
      return Amst::repSuccess($rep);
    }
    return $api; // ERROR SURE
  }

  private static function _checkDeleteContact($id) {
    $rep = [
      'id' => $id
    ];

    // [Model_Exist]
    $model = Amst::getByID(static::$conf['tb_contact_person'], ['id'], $id);
    if (!isset($model['id'])) {
      return Amst::repError('E0005', static::$conf['lbl_contact_person']);
    }

    return Amst::repSuccess($rep);
  }
}
?>