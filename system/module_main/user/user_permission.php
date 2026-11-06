<?php
class User_Permission {
  private static $conf = [
    'description'         => 'คลาสสำหรับจัดการสิทธิผู้ใช้',
    'tb_user_permission'  => '',
    'lbl_user_permission' => 'สิทธิผู้ใช้'
  ];

  /**
  | Group | สิทธิผู้ใช้
  | Description | 1. ดึง permission ของ User ที่เปิดอยู่ ทั้งหมด
  | $user_id | ไอดีผู้ใช้
   */
  public static function getUserPermissionList($user_id) {
    $return = [];

    if (!$user_id) {
      return Amst::repError('E0001', 'User id');
    }

    $sys_user = Amst::getByID('sys_user', ['id', 'permission'], $user_id);
    if (isset($sys_user['id']) && $sys_user['id']) {
      $return = json_decode($sys_user['permission'], true);
    } else {
      return $return;
    }

    return $return;
  }

  /**
  | Group | สิทธิผู้ใช้
  | Description | 2. User มี Permission ไหม
  | $user_id | ไอดีผู้ใช้
  | $permission_code | ชื่อ permission เป็น String เท่านั้น
   */
  public static function hasUserPermission($user_id, $permission_code) {
    $return = false;

    $sys_user = Amst::getByID('sys_user', ['id', 'permission'], $user_id);
    if (isset($sys_user['id']) && $sys_user['id']) {
      if (strlen(strpos($sys_user['permission'], $permission_code))) {
        $return = true;
      }
    }
    return $return;
  }

  public static function selectPermissionByID($user_id) {
    $return = [];

    // remove_after_compatibility ยกเลิกการใช้งานเนื่องจากชื่อ select จะไม่ตรงกับ input
    if (!$user_id) {
      return Amst::repError('E0001', 'User id');
    }

    $sys_user = Amst::getByID('sys_user', ['id', 'permission'], $user_id);
    if (isset($sys_user['id']) && $sys_user['id']) {
      $return = json_decode($sys_user['permission'], true);
    } else {
      return $return;
    }

    return $return;
  }

  /**
  | Group | สิทธิผู้ใช้
  | Description | 3. เพิ่ม / ลบ Permission ให้ User รายคน
  | $user_id | ไอดีผู้ใช้
  | $permission_codes | โค้ดสิทธิที่หน้าบ้านตั้งชื่อไว้ เช่น 'user_menu_ja'
   */
  public static function triggerUserPermission($user_id, $permission_codes) {
    $rep = [
      'update_result' => false
    ];

    if (!$permission_codes) {
      return Amst::repError('nothing to do');
    }

    if (!is_array($permission_codes)) {
      $permission_codes = [$permission_codes];
    }

    if (!$user_id) {
      return Amst::repError('E0001', 'User id');
    }

    $sys_user = Amst::getByID('sys_user', ['id', 'permission'], $user_id);
    if (!isset($sys_user['id'])) {
      return Amst::repError('E0005', 'sys_user');
    }

    $is_update = 1;

    // if (!$is_update) {
    //   return Amst::repError('E0000', 'No update');
    // }

    $permission_codes = json_encode($permission_codes);

    $rep['update_result'] = Amst::updateByID('sys_user', ['permission' => $permission_codes], $user_id);

    return Amst::repSuccess($rep);
  }

  /**
  | Group | ซ่อนสิทธิผู้ใช้
  | Description | 1.ดึง permission ที่ต้องซ่อน ของ UserType ด้วยไอดีผู้ใช้
  | $user_id | ไอดีผู้ใช้
   */
  public static function getHiddenPermissionList($user_id) {
    // ช่วยดึงไปใช้หน้า UserPermission
    $user_type_id = User::getUserByID($user_id, ['selected_fields' => 'user_type_id']);
    // return User_type::getHiddenPermissionList($user_type_id);
  }
}
