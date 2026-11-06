<?php
class User_Verify {
  private static $conf = [
    'description'        => 'เก็บข้อมูลการยืนยันตัวตนของผู้ใช้',
    'tb_verify'          => 'sys_user_verify',
    'lbl_verify'         => 'ข้อมูลผู้ใช้ยืนยันตัวตน',
    'idcard_img_path'    => 'user_verify_idcard_',
    'bankcover_img_path' => 'user_verify_bankcover_'
  ];

  public static function setVerifyDataDetail($record, $options = []) {
    if (!isset($record['id'])) {
      return $record;
    }

    // Do Single Works...
    if (isset($record['idcard_img_path'])) {
      $getFileInfo = File::getFileInfo(static::$conf['idcard_img_path'].$record['user_id']);
      // $record['idcard_getFileInfo'] = $getFileInfo;
      if (isset($getFileInfo['id'])) {
        $record['idcard_file_name'] = "{$getFileInfo['file_name']}.{$getFileInfo['extension']}";
      } else {
        unset($record['idcard_img_path']); // No file
      }
    }

    if (isset($record['bankcover_img_path'])) {
      $getFileInfo = File::getFileInfo(static::$conf['bankcover_img_path'].$record['user_id']);
      // $record['bankcover_getFileInfo'] = $getFileInfo;
      if (isset($getFileInfo['id'])) {
        $record['bankcover_file_name'] = "{$getFileInfo['file_name']}.{$getFileInfo['extension']}";
      } else {
        unset($record['bankcover_img_path']); // No file
      }
    }

    return $record;
  }

  /**
  | Group | ข้อมูลผู้ใช้ยืนยันตัวตน
  | Description | 1.ดึงข้อมูล 'ข้อมูลผู้ใช้ยืนยันตัวตน' ด้วยไอดีผู้ใช้
  | $user_id | ไอดีผู้ใช้
  | $options | [idcard_img_path(รูปไอดีการ์ด), bankcover_img_path(รูปหน้าบุคแบงค์), not_view(true // เอาแค่ข้อมูลหลักไป edit *เร็ว), selected_fields('*' // คอลัมภ์ที่ต้องการ)]
  | "สถานะ(status)" | [pending(รอการอนุมัติ), approved(อนุมัติแล้ว), rejected(ปฏิเสธแล้ว)]
   */
  public static function getVerifyDataByUserID($user_id, $options = []) {
    $return = null;

    $options = Amst::checkOption($options);
    $table_name = !Util::isHasOptions('not_view', $options) ? 'sys_user_verify' : static::$conf['tb_verify'];
    $selected_fields = Amst::getSelectedFields($options, ['id', 'system_delete']);

    if ($user_id && is_numeric($user_id)) {
      if (Util::isHasOptions('idcard_img_path', $options)) {
        $options['image']['idcard_img_path'] = [
          'code'          => static::$conf['idcard_img_path'],
          'target_column' => 'user_id'
        ];
      }
      if (Util::isHasOptions('bankcover_img_path', $options)) {
        $options['image']['bankcover_img_path'] = [
          'code'          => static::$conf['bankcover_img_path'],
          'target_column' => 'user_id'
        ];
      }
      $model = Amst::get($table_name, $selected_fields, ['user_id' => $user_id], $options);
      if (isset($model['id'])) {
        $return = static::setVerifyDataDetail($model, $options);
      } else {
        // REGET
        $api = static::_addNewVerifyData(['user_id' => $user_id]);
        if (isset($api['response_status']) && ($api['response_status'] == true)) {
          $model = Amst::get($table_name, $selected_fields, ['user_id' => $user_id], $options);
          if (isset($model['id'])) {
            $return = static::setVerifyDataDetail($model, $options);
          }
        } else {
          Log::add(3, 'Fail to INSERT user_verify data api='.json_encode($api), 0, 'backend');
        }
      }
    }
    return $return;
  }

  private static function _addNewVerifyData($data) {
    $rep = [
      'insert_id' => false
    ];

    $data = Amst::checkOption($data);

    if (isset($data['user_id'])) {
      $model = User::getUserByID($data['user_id'], ['is_exists' => true]);
    }
    if (!isset($model['id'])) {
      return Amst::repError('E0005', 'User');
    }

    $data['status'] = 'pending';

    return Amst::insert(static::$conf['tb_verify'], $data);
  }

  /**
  | Group | ข้อมูลผู้ใช้ยืนยันตัวตน
  | Description | 2.อัพเดท 'ข้อมูลผู้ใช้ยืนยันตัวตน' ด้วยไอดีผู้ใช้
  | $data | [id_card_no(id_card_no)]
  | "โค้ดอัพโหลดรูป" | [idcard_img_path('user_verify_idcard_'.$user_id // รูปไอดีการ์ด+ไอดีผู้ใช้), bankcover_img_path('user_verify_bankcover_'.$user_id // รูปหน้าบุคแบงค์+ไอดีผู้ใช้)]
   */
  public static function updateVerifyData($user_id, $data) {
    $rep = [
      'update_result' => false
    ];

    $model = static::getVerifyDataByUserID($user_id, ['selected_fields' => ['id', 'user_id']]);
    if (!isset($model['id'])) {
      return Amst::repError('E0005', static::$conf['lbl_verify']);
    }

    return Amst::updateByID(static::$conf['tb_verify'], $data, $model['id']);
  }

  /**
  | Group | ข้อมูลผู้ใช้ยืนยันตัวตน
  | Description | 3.อนุมัติ 'ข้อมูลผู้ใช้ยืนยันตัวตน'
  | $user_id | ไอดีผู้ใช้
  | $data | [admin_id(ไอดีคนกดปฏิเสท), approved_remark(หมายเหตุ)]
   */
  public static function approveVerifyData($user_id, $data = []) {
    $rep = [
      'update_result' => false
    ];

    if ($user_id) {
      $model = static::getVerifyDataByUserID($user_id, ['selected_fields' => ['id', 'user_id', 'id_card_no', 'status']]);
    }
    if (!isset($model['id'])) {
      return Amst::repError('E0005', static::$conf['lbl_verify']);
    }
    if (!$model['id_card_no']) {
      return Amst::repError('E0001', 'ID card no');
    }
    if ($model['status'] !== 'pending') {
      return Amst::repError('E0000', 'ไม่สามารถอนุมัติรายการที่ดำเนินการไปแล้วได้');
    }

    $u_data = [
      'status'             => 'approved',
      'is_approved'        => 1,
      'approved_by'        => (isset($data['admin_id'])) ? $data['admin_id'] : User::getCurrentUserID(),
      'approved_remark'    => (isset($data['approved_remark'])) ? $data['approved_remark'] : '',
      'approved_date_time' => Util::getSystemTimestamp()
    ];
    $api = Amst::updateByID(static::$conf['tb_verify'], $u_data, $model['id']);
    if (isset($api['response_status']) && ($api['response_status'] == true)) {
      $rep['update_result'] = $api['response_data']['update_result'];
      // [ETC] work ?
      $rep['update_user_result'] = Amst::updateByID('sys_user', ['verify_status' => 'verified'], $model['id']);
    } else {
      return $api;
    }
    return Amst::repSuccess($rep);
  }

  /**
  | Group | ข้อมูลผู้ใช้ยืนยันตัวตน
  | Description | 4.ปฏิเสท 'ข้อมูลผู้ใช้ยืนยันตัวตน'
  | $user_id | ไอดีผู้ใช้
  | $data | [admin_id(ไอดีคนกดปฏิเสท), rejected_remark(หมายเหตุ)]
   */
  public static function rejectVerifyData($user_id, $data = []) {
    $rep = [
      'update_result' => false
    ];

    if ($user_id) {
      $model = static::getVerifyDataByUserID($user_id, ['selected_fields' => ['id', 'user_id', 'id_card_no', 'status']]);
    }
    if (!isset($model['id'])) {
      return Amst::repError('E0005', static::$conf['lbl_verify']);
    }
    if (!$model['id_card_no']) {
      return Amst::repError('E0001', 'ID card no');
    }
    if ($model['status'] !== 'pending') {
      return Amst::repError('E0000', 'ไม่สามารถปฏิเสทรายการที่ดำเนินการไปแล้วได้');
    }

    $u_data = [
      'status'             => 'rejected',
      'is_approved'        => -1,
      'approved_by'        => (isset($data['admin_id'])) ? $data['admin_id'] : User::getCurrentUserID(),
      'approved_remark'    => (isset($data['approved_remark'])) ? $data['approved_remark'] : '',
      'approved_date_time' => Util::getSystemTimestamp()
    ];
    $api = Amst::updateByID(static::$conf['tb_verify'], $u_data, $model['id']);
    if (isset($api['response_status']) && ($api['response_status'] == true)) {
      $rep['update_result'] = $api['response_data']['update_result'];
      // [ETC] work ?
    } else {
      return $api;
    }
    return Amst::repSuccess($rep);
  }
}
