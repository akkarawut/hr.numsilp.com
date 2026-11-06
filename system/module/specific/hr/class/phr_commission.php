<?php

class Phr_commission {

  public static function setCommissionListDetail($code, $return, $options = []) {
    // [ExtractList]
    $selected_records = isset($return['list']) ? $return['list'] : $return;

    $user_list = Amst::select('sys_user', ['id', 'full_name'], []);

    // Do Each Works...
    foreach ($selected_records as $key => $record) {
      // $selected_records[$key]['other_key'] = 'other_value';
      $selected_records[$key]['user_fullname']        = isset($user_list[$record['user_id']]) ? $user_list[$record['user_id']]['full_name'] : '';
      $selected_records[$key]['insert_user_fullname'] = isset($user_list[$record['insert_user_id']]) ? $user_list[$record['insert_user_id']]['full_name'] : '';
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
  | Group | Commission
  | Description | 1.ดึงข้อมูลลิส 'Commission'
  | $code | โค้ดโมดูล
  | $where | [
    user_id,
    status(waiting_confirm / approved / reject)
  ]
  | $options | [not_view(true|เอาแค่ข้อมูลหลักไป edit*เร็ว), selected_fields(String หรือ Array ของคอลัมภ์ที่ต้องการ), total_count(true|พร้อมจำนวนข้อมูลทั้งหมด ตามการกรองไม่แบ่งหน้า), page_no(1|เลขหน้า 0 คือส่งทั้งหมด), page_size(10|จำนวนข้อมูลต่อหน้า), sort_col(เรียงตามชื่อคอลัมภ์นี้), sort_by('ASC'|'DESC')]
   */
  public static function selectCommission($code, $where = [], $options = []) {
    $return = []; // list
    $sql_where = [];

    if (isset($where['user_id']) && $where['user_id']) {
      $sql_where['AND']['user_id'] = $where['user_id'];
    }

    if (isset($where['status']) && $where['status'] && $where['status'] != 'all') {
      $sql_where['AND']['status'] = $where['status'];
    }

    if (isset($where['status_update_datetime[<>]']) && $where['status_update_datetime[<>]'] && $where['status_update_datetime[<>]'] != 'all') {
      $sql_where['AND']['status_update_datetime[<>]'] = $where['status_update_datetime[<>]'];
    }

    $selected_fields = Amst::getSelectedFields($options);
    $options['image']['file_deduct_tax_path'] = [
      'code'          => $code . '_commission_deduct_tax_',
      'target_column' => 'id'
    ];
    $return = Amst::selectNoKey($code . '_commission', $selected_fields, $sql_where, $options);
    return static::setCommissionListDetail($code, $return, $options);
  }

  public static function setCommissionDetail($code, $record, $options = []) {
    if (!isset($record['id'])) {
      return $record;
    }

    $user_list = Amst::select('sys_user', ['id', 'full_name'], []);
    $record['user_fullname']        = isset($user_list[$record['user_id']]) ? $user_list[$record['user_id']]['full_name'] : '';
    $record['insert_user_fullname'] = isset($user_list[$record['insert_user_id']]) ? $user_list[$record['insert_user_id']]['full_name'] : '';

    $where_file = [
      'AND' => [
        'code[~]' => str_replace('_', '\_', $code . '_commission_' . $record['id'] . '_') . '%' // escape _ ไม่ให้เป็น wildcard ของ LIKE (id=1 จะไปจับไฟล์ของ id=10-19)
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

    // Do Single Works...
    // $record['other_key'] = 'other_value';

    return $record;
  }

  /**
  | Group | Commission
  | Description | 2.ดึงข้อมูล 'Commission' ด้วยไอดี
  | $code | โค้ดโมดูล
  | $id | ไอดีรายการ
  | $options | [not_view(true|เอาแค่ข้อมูลหลักไป edit*เร็ว), selected_fields(String หรือ Array ของคอลัมภ์ที่ต้องการ)]
   */
  public static function getCommissionByID($code, $id, $options = []) {
    $return = null;

    $selected_fields = Amst::getSelectedFields($options, ['id', 'system_delete']);
    $options['image']['file_deduct_tax_path'] = [
      'code'          => $code . '_commission_deduct_tax_',
      'target_column' => 'id'
    ];
    $model = Amst::getByID($code . '_commission', $selected_fields, $id, $options);
    if (isset($model['id'])) {
      $return = static::setCommissionDetail($code, $model, $options);
    }
    return $return;
  }

  /**
  | Group | Commission
  | Description | 3.เพิ่ม 'Commission'
  | $code | โค้ดโมดูล
  | $data | [
    user_id,
    insert_user_id,
    sale_price,
    commission_price,
    remark,
  ]
  | $files | File
   */
  public static function addNewCommission($code, $data = [], $files = []) {
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
    $count_commission = Amst::count($code . '_commission', $where_count, ['show_all' => true]);
    $count_commission = $count_commission + 1;
    $number_no        = str_pad($count_commission, 5, '0', STR_PAD_LEFT);
    $no               = 'CMS' . $number_no;

    $i_data                   = $data;
    $i_data['no']             = $no;
    $i_data['status']         = 'waiting_confirm';

    $api = Amst::insert($code . '_commission', $i_data);
    if (isset($api['response_status']) && ($api['response_status'] == true)) {
      $commission_id = $api['response_data']['insert_id'];

      $log_file = [];
      if ($files) {
        foreach ($files as $key => $file) {
          if ($file && $file['error'] == 0) {
            $code_file  = $code . '_commission_' . $commission_id . '_' . $key;
            $log_file[] = File::add($code_file, $file);
          }
        }
      }

      $user_info = Amst::getByID('sys_user', ['id', 'full_name'], $data['user_id']);

      $where_hr = [
        'AND' => [
          'user_code'    => 'xacpn', // hr
          'is_can_login' => 1,
          'is_ban'       => 0
        ]
      ];
      $hr_list  = Amst::select('sys_user', '*', $where_hr);
      // กันกรณีไม่มี HR -> ตัวแปรยังไม่ถูกสร้างแล้ว foreach error
      $noti_data_list = [];
      foreach ($hr_list as $key_hr => $hr_record) {
        $noti_data = [
          'user_id'       => $hr_record['id'],
          'from_user_id'  => $data['user_id'],
          'type'          => 'system_commission',
          'employee_name' => isset($user_info['full_name']) ? $user_info['full_name'] : '-',
          'ref_id'        => $commission_id
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
  | Group | Commission
  | Description | 4.อัพเดท 'Commission'
  | $code | โค้ดโมดูล
  | $id | ไอดีรายการ  
  | $data | [
    user_id,
    sale_price,
    commission_price,
    remark
  ]
  | $files | File
   */
  public static function updateCommission($code, $id, $data = [], $files = []) {
    $rep = [
      'update_result' => false
    ];

    $model = Amst::getByID($code . '_commission', ['id'], $id);
    if (!isset($model['id'])) {
      return Amst::repError('E0005', 'tbl_commission not found.');
    }

    $u_data = $data;

    $api = Amst::updateByID($code . '_commission', $u_data, $id);
    if (isset($api['response_status']) && ($api['response_status'] == true)) {
      $log_file = [];
      if ($files) {
        foreach ($files as $key => $file) {
          if ($file && $file['error'] == 0) {
            $code_file  = $code . '_commission_' . $id . '_' . $key;
            $log_file[] = File::add($code_file, $file);
          }
        }
      }
    }
    // เดิมไม่ได้ return ผลลัพธ์ ผู้เรียกจะได้ null
    return $api;
  }

  /**
  | Group | Commission
  | Description | 5.ลบ 'Commission'
  | $code | โค้ดโมดูล
  | $id | ไอดีรายการ
   */
  public static function deleteCommission($code, $id) {
    $rep = [
      'delete_result' => false
    ];

    $api = static::_checkDeleteCommission($code, $id);
    if (isset($api['response_status']) && ($api['response_status'] == true)) {
      $rep['delete_result'] = Amst::deleteByID($code . '_commission', $id);

      $where_file = [
        'AND' => [
          'code[~]' => str_replace('_', '\_', $code . '_commission_' . $id . '_') . '%' // escape _ กันลบไฟล์ของรายการอื่น
        ]
      ];
      $code_files = Amst::select('sys_file', 'code', $where_file);
      if ($code_files) {
        File::delete($code_files);
      }

      return Amst::repSuccess($rep);
    }
    return $api; // ERROR SURE
  }

  private static function _checkDeleteCommission($code, $id) {
    $rep = [
      'id' => $id
    ];

    // [Model_Exist]
    $model = Amst::getByID($code . '_commission', ['id'], $id);
    if (!isset($model['id'])) {
      return Amst::repError('E0005', 'tbl_commission not found.');
    }

    return Amst::repSuccess($rep);
  }

  /**
  | Group | Control Commission
  | Description | 1 count 'Commission'
  | $code | โค้ดโมดูล
   */
  public static function countControlCommission($code) {
    $return = [
      'count_waiting_confirm' => 0,
      'count_confirm'         => 0
    ];

    $arr_where = [
      [
        'name'  => 'count_waiting_confirm',
        'where' => [
          'AND' => [
            'status' => 'waiting_confirm'
          ]
        ]
      ],
      [
        'name'  => 'count_confirm',
        'where' => [
          'AND' => [
            'status' => ['approved', 'reject']
          ]
        ]
      ]
    ];

    foreach ($arr_where as $key => $record) {
      $key_name  = $record['name'];
      $sql_where = $record['where'];
      $count     = Amst::count($code . '_commission', $sql_where);

      $return[$key_name] = $count;
    }

    return $return;
  }

  /**
  | Group | Control Commission
  | Description | 1 ปรับสุถานะ 'Commission'
  | $code | โค้ดโมดูล
  | $id | id รายการ
  | $data | [
    user_id(ของคนปรับสถานะ),
    status(approved / reject),
    tax,
    remark
  ]
  | $file | file
   */
  public static function updateStatusCommissionByID($code, $id, $data = [], $file = []) {
    $status  = isset($data['status']) && $data['status'] ? $data['status'] : '';
    $user_id = isset($data['user_id']) && $data['user_id'] ? $data['user_id'] : -1;
    $tax     = isset($data['tax']) && $data['tax'] ? $data['tax'] : 0;
    $remark  = isset($data['remark']) && $data['remark'] ? $data['remark'] : '';

    if (!$status) {
      return Amst::repError('E0000', 'Status not found.');
    }

    $date_now = date('Y-m-d H:i:s');
    $user_info = Amst::getByID('sys_user', ['id', 'full_name'], $user_id);

    $model = Amst::getByID($code . '_commission', '*', $id);
    if (!isset($model['id'])) {
      return Amst::repError('E0000', 'tbl_commission not found.');
    }
    $commission_price = $model['commission_price'];

    $tax_price   = 0;
    if ($tax) {
      $tax_price = $commission_price * ($tax / 100);
    }
    $total_price = $commission_price - $tax_price;

    $u_data = [
      'tax'                       => $tax,
      'tax_price'                 => $tax_price,
      'total_price'               => $total_price,
      'status'                    => $status,
      'status_update_by_user_id'  => $user_id,
      'status_update_by_fullname' => isset($user_info['id']) && $user_info['id'] ? $user_info['full_name'] : 'SYSTEM',
      'status_update_datetime'    => $date_now,
      'status_update_remark'      => $remark
    ];

    if ($file && $file['error'] == 0) {
      $u_data['is_file_deduct_tax'] = 1;
    }

    $where = [
      'AND' => [
        'id' => $id
      ]
    ];
    $api = Amst::update($code . '_commission', $u_data, $where);
    if (isset($api['response_status']) && ($api['response_status'] == true)) {

      if ($file && $file['error'] == 0) {
        $code_file = $code . '_commission_deduct_tax_' . $id;
        File::add($code_file, $file);
      }
    }
    return $api;
  }
}
