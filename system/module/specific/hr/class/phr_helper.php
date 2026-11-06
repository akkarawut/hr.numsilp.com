<?php

class Phr_helper {

  /**
  | Group | Init
  | Description | ดึงข้อมูลลิส 'Init'
  | $code | code
  | $user_id | user_id
  | $page_permission | check permission
   */
  public static function getInit($code, $user_id, $page_permission = []) {
    $return = [
      'is_noti'        => 0,
      'is_day_off'     => 0,
      'day_off_type'   => '',
      'menu'           => [],
      'user_info'      => [],
      'is_permission'  => 0
    ];

    $date_now = date('Y-m-d');
    $day_name = strtolower(date('l', strtotime($date_now)));

    $user_permission = User_Permission::getUserPermissionList($user_id);
    $options_user['image']['image_path'] = [
      'code'          => 'user_profile_image_',
      'target_column' => 'id'
    ];
    $user_info       = Amst::getByID('sys_user', '*', $user_id, $options_user);

    $is_admin     = 0;
    $is_day_off   = 0;
    $day_off_type = '';
    $permission   = [];
    if (isset($user_info['id'])) {
      $is_admin   = strtolower($user_info['user_code']) == 'ywptz' ? 1 : 0;
      $permission = json_decode($user_info['permission'], true);

      // =============================================================================
      // check checkin / out

      $checkin_status = 'checkin';

      $where_checkin = [
        'AND' => [
          'user_id'        => $user_info['id'],
          'status_checkin' => ['normal', 'late', 'very_late', 'missing'],
          'date'           => $date_now,
          'is_last'        => 1
        ]
      ];
      $model = Amst::get($code . '_checkin', '*', $where_checkin);
      if (!isset($model['id'])) {
        $checkin_status = 'checkin';
      } else {
        $checkin_status = 'checkout';

        if ($model['status'] == 'success') {
          $checkin_status = 'success';
        }
      }
      $user_info['checkin_status'] = $checkin_status;

      $where_noti_check = [
        'AND' => [
          'user_id' => $user_info['id'],
          'is_read' => 0
        ]
      ];
      $noti_check = Amst::get('sys_user_notification', 'id', $where_noti_check);
      $return['is_noti'] = $noti_check ? 1 : 0;

      // =============================================================================


      // =============================================================================
      // check working day
      $working_time_type_id = $user_info['working_time_type_id'];
      $working_time_info    = Amst::getByID($code . '_setting_working_time', '*', $working_time_type_id);
      if ($working_time_info[$day_name] == 0) {
        $is_day_off   = 1;
        $day_off_type = 'day_off';
      }

      // check holiday
      $where_holiday = [
        'AND' => [
          'from_date[<=]'        => $date_now,
          'to_date[>=]'          => $date_now,
          // 'request_user_ids[~]'  => '"' . $user_info['id'] . '"',
          'role_use[~]'          => '"' . $user_info['working_time_type_id'] . '"',
          'request_user_ids[!~]' => '"' . $user_info['id'] . '"',
        ]
      ];

      $holiday_id = Amst::get($code . '_setting_holiday', 'id', $where_holiday);
      if ($holiday_id) {
        $is_day_off = 1;
        $day_off_type = 'holiday';
      }

      if ($is_day_off == 0) {
        // check leave work
        $where_leave_work = [
          'AND' => [
            'user_id'        => $user_info['id'],
            'status_confirm' => 'approved',
            'from_date[<=]'  => $date_now,
            'to_date[>=]'    => $date_now,
          ]
        ];

        $leave_work_id = Amst::get($code . '_leave_work', 'id', $where_leave_work);
        if ($leave_work_id) {
          $is_day_off = 1;
          $day_off_type = 'leave_work';
        }
      }
      // =============================================================================

      $set_user_key = [
        'id',
        'username',
        'user_type',
        'permission',
        'is_ban',
        'full_name',
        'discord_user_id',
        'team_id',
        'insert_date_time',
        'user_profile_image',
        'checkin_status',
        'image_path',
        'email',
        'is_can_login'
      ];
      foreach ($user_info as $key => $value) {
        if (!in_array($key, $set_user_key)) {
          unset($user_info[$key]);
        }
      }
      $email = json_decode($user_info['email'], true);
      $user_info['email'] = isset($email[0]) ? $email[0] : '-';
    }

    $temp_user_permission = [];
    foreach ($user_permission as $key => $value) {
      if ($value) {
        $temp_user_permission[] = $key;
      }
    }

    if ($page_permission) {
      foreach ($page_permission as $value) {
        if (in_array($value, $temp_user_permission) || $value == 'all' || $is_admin == 1) {
          $return['is_permission'] = 1;
          break;
        }
      }
    }

    $return['is_day_off']   = $is_day_off;
    $return['day_off_type'] = $day_off_type;

    $return['user_info'] = $user_info;

    $select = [
      'id',
      'code',
      'name'
    ];
    $where = [
      'AND' => [
        'module' => 'User'
      ]
    ];
    $user_module = Amst::selectNoKey('sys_function', $select, $where);

    $temp_user_permission = [];
    foreach ($user_module as $key => $record) {
      $set_key   = strtolower($record['name']);
      $set_value = $record['code'] . '_user';

      if ((isset($permission[$set_value]) && $permission[$set_value] == 1) || $is_admin == 1) {
        $temp_user_permission[$set_key] = $set_value;
      }
    }

    // icon ของแต่ละเมนูกำหนดไว้ใน 'icon' ของเมนูนั้นโดยตรง (เดิมจับคู่ด้วย index ทำให้ไอคอนเลื่อน/หายเมื่อมีการ comment เมนู)

    $menu = [
      [
        'title' => 'การทำงานส่วนบุคคล',
        'permission_name' => '',
        'permission_show' => 0,
        'permission_type' => 'all',
        'sub_menu' => [
          [
            'name'            => 'ภาพรวมรายบุคคล',
            'permission_name' => '',
            'permission_show' => 0,
            'icon'            => 'homepage.svg',
            'path_url'        => 'dashboard.php',
            'order'           => 1,
          ],
          [
            'name'            => 'บันทึกเข้า - ออกงาน',
            'permission_name' => '',
            'permission_show' => 0,
            'icon'            => 'hourglass-menu.svg',
            'path_url'        => 'checkin_checkout.php',
            'order'           => 2,
          ],
          [
            'name'            => 'บันทึกการขอลางาน',
            'permission_name' => '',
            'permission_show' => 0,
            'icon'            => 'sticky-note.svg',
            'path_url'        => 'record_of_leave_request.php',
            'order'           => 3,
          ],
          [
            'name'            => 'บันทึกการขอล่วงเวลา (OT)',
            'permission_name' => '',
            'permission_show' => 0,
            'icon'            => 'clock.svg',
            'path_url'        => 'record_ot.php',
            'order'           => 4,
          ],
          // [
          //   'name'            => 'บันทึกการเงิน',
          //   'permission_name' => '',
          //   'permission_show' => 0,
          //   'icon'            => 'icon.svg',
          //   'path_url'        => '/',
          //   'order'           => 5,
          // ],
          // [
          //   'name'            => 'การทำงานของฉัน',
          //   'permission_name' => '',
          //   'permission_show' => 0,
          //   'icon'            => 'icon.svg',
          //   'path_url'        => '/',
          //   'order'           => 6,
          // ],
          [
            'name'            => 'ลงบันทึกยอดขายและค่าคอมมิชชั่น',
            'permission_name' => '',
            'permission_show' => 0,
            'icon'            => 'record_commission.svg',
            'path_url'        => 'record_commission.php',
            'order'           => 5,
          ],
        ]
      ],
      [
        'title' => 'ผู้บริหาร',
        'permission_name' => '',
        'permission_show' => 0,
        'permission_type' => 'ผู้บริหาร',
        'sub_menu' => [
          [
            'name'            => 'ภาพรวมธุรกิจ',
            'permission_name' => '',
            'permission_show' => 0,
            'icon'            => 'dashboard.svg',
            'path_url'        => 'overview_business.php',
            'order'           => 1,
          ],
          [
            'name'            => 'อนุมัติการออกเงินเดือน/ค่าจ้าง',
            'permission_name' => '',
            'permission_show' => 0,
            'icon'            => 'accounting.svg',
            'path_url'        => 'approve_payment.php',
            'order'           => 2,
          ],
        ]
      ],
      [
        'title' => 'หัวหน้างาน',
        'permission_name' => '',
        'permission_show' => 0,
        'permission_type' => 'หัวหน้างาน',
        'sub_menu' => [
          // [
          //   'name'            => 'ภาพรวมทีม',
          //   'permission_name' => '',
          //   'permission_show' => 0,
          //   'icon'            => 'icon.svg',
          //   'path_url'        => '/',
          //   'order'           => 1,
          // ],
          [
            'name'            => 'อนุมัติการลงเวลา',
            'permission_name' => '',
            'permission_show' => 0,
            'icon'            => 'checklist.svg',
            'path_url'        => 'approve_time_entry.php',
            'order'           => 2,
          ],
          [
            'name'            => 'อนุมัติการลา',
            'permission_name' => '',
            'permission_show' => 0,
            'icon'            => 'checklist.svg',
            'path_url'        => 'approve_leave.php',
            'order'           => 3,
          ],
          [
            'name'            => 'อนุมัติโอที',
            'permission_name' => '',
            'permission_show' => 0,
            'icon'            => 'checklist.svg',
            'path_url'        => 'approve_ot.php',
            'order'           => 4,
          ],
          [
            'name'            => 'อนุมัติผ่านการทดลองงาน',
            'permission_name' => '',
            'permission_show' => 0,
            'icon'            => 'checklist.svg',
            'path_url'        => 'approve_probation.php',
            'order'           => 5,
          ],
          [
            'name'            => 'ตรวจสอบความถูกต้องบันทึกยอดขายและค่าคอมมิชชั่น',
            'permission_name' => '',
            'permission_show' => 0,
            'icon'            => 'checklist.svg',
            'path_url'        => 'approve_commission.php',
            'order'           => 6,
          ],
          [
            'name'            => 'ลงบันทึกยอดขายและค่าคอมมิชชั่น',
            'permission_name' => '',
            'permission_show' => 0,
            'icon'            => 'record_commission.svg',
            'path_url'        => 'record_commission.php',
            'order'           => 7,
          ],
        ]
      ],
      [
        'title' => 'HR',
        'permission_name' => '',
        'permission_show' => 0,
        'permission_type' => 'hr',
        'sub_menu' => [
          [
            'name'            => 'ข้อมูลพนักงาน',
            'permission_name' => '',
            'permission_show' => 0,
            'icon'            => 'manager.svg',
            'path_url'        => 'employee_information.php',
            'order'           => 1,
          ],
          [
            'name'            => 'ทำเงินเดือน',
            'permission_name' => '',
            'permission_show' => 0,
            'icon'            => 'icon-bank.svg',
            'path_url'        => 'make_salary.php',
            'order'           => 2,
          ],
          [
            'name'            => 'ค่าจ้างรายวัน',
            'permission_name' => '',
            'permission_show' => 0,
            'icon'            => 'cash-register.svg',
            'path_url'        => 'make_salary_daily.php',
            'order'           => 3,
          ],
          // [
          //   'name'            => 'บันทึกข้อมูลประกันสังคม',
          //   'permission_name' => '',
          //   'permission_show' => 0,
          //   'icon'            => 'icon.svg',
          //   'path_url'        => '/',
          //   'order'           => 4,
          // ],
          // [
          //   'name'            => 'บันทึกค่าใช้จ่าย',
          //   'permission_name' => '',
          //   'permission_show' => 0,
          //   'icon'            => 'icon.svg',
          //   'path_url'        => '/',
          //   'order'           => 5,
          // ],
          // [
          //   'name'            => 'คำนวนการทำงานล่วงเวลา (OT)',
          //   'permission_name' => '',
          //   'permission_show' => 0,
          //   'icon'            => 'icon.svg',
          //   'path_url'        => '/',
          //   'order'           => 6,
          // ],
          [
            'name'            => 'สรุปข้อมูลเงินเดือน',
            'permission_name' => '',
            'permission_show' => 0,
            'icon'            => 'accounting.svg',
            'path_url'        => 'report_payment.php',
            'order'           => 7,
          ],
          // [
          //   'name'            => 'จัดการค่าคอมมิชชั่น',
          //   'permission_name' => '',
          //   'permission_show' => 0,
          //   'icon'            => 'icon.svg',
          //   'path_url'        => '/',
          //   'order'           => 8,
          // ],
          [
            'name'            => 'อนุมัติการลา',
            'permission_name' => '',
            'permission_show' => 0,
            'icon'            => 'check.svg',
            'path_url'        => 'approve_leave.php',
            'order'           => 9,
          ],
          // [
          //   'name'            => 'ตรวจสอบความถูกต้องบันทึกยอดขายและค่าคอมมิชชั่น',
          //   'permission_name' => '',
          //   'permission_show' => 0,
          //   'icon'            => 'icon.svg',
          //   'path_url'        => 'approve_commission.php',
          //   'order'           => 10,
          // ],
          [
            'name'            => 'สรุปยอดขายของพนักงาน',
            'permission_name' => '',
            'permission_show' => 0,
            'icon'            => 'analytics.svg',
            'path_url'        => 'summary_sale_employee.php',
            'order'           => 11,
          ],
          [
            'name'            => 'รายงานการเข้า - ออกงาน',
            'permission_name' => '',
            'permission_show' => 0,
            'icon'            => 'grid.svg',
            'path_url'        => 'report_checkin_checkout.php',
            'order'           => 12,
          ],
          [
            'name'            => 'ออกใบเตือน',
            'permission_name' => '',
            'permission_show' => 0,
            'icon'            => 'issue_warning.svg',
            'path_url'        => 'issue_warning.php',
            'order'           => 12,
          ],
          // [
          //   'name'            => 'ออกหัก ณ ที่จ่ายค่าคอมมิชชั่น',
          //   'permission_name' => '',
          //   'permission_show' => 0,
          //   'icon'            => 'icon.svg',
          //   'path_url'        => '/',
          //   'order'           => 13,
          // ],
        ]
      ],
      [
        'title' => 'การตั้งค่า',
        'permission_name' => '',
        'permission_show' => 0,
        'permission_type' => 'admin',
        'sub_menu' => [
          [
            'name'            => 'ตั้งค่าวันหยุด',
            'permission_name' => '',
            'permission_show' => 0,
            'icon'            => 'event.svg',
            'path_url'        => 'setting_holiday.php',
            'order'           => 1,
          ],
          [
            'name'            => 'ตั้งค่ารูปแบบเวลาทำงาน',
            'permission_name' => '',
            'permission_show' => 0,
            'icon'            => 'clock.svg',
            'path_url'        => 'setting_working_time.php',
            'order'           => 2,
          ],
          [
            'name'            => 'การทำงานของระบบ',
            'permission_name' => '',
            'permission_show' => 0,
            'icon'            => 'settings.svg',
            'path_url'        => 'setting_system.php',
            'order'           => 3,
          ],
          [
            'name'            => 'ข้อมูลองค์กร / บริษัท',
            'permission_name' => '',
            'permission_show' => 0,
            'icon'            => 'corporation.svg',
            'path_url'        => 'setting_company.php',
            'order'           => 4,
          ],
          [
            'name'            => 'ข้อมูลสาขา',
            'permission_name' => '',
            'permission_show' => 0,
            'icon'            => 'company.svg',
            'path_url'        => 'setting_branch.php',
            'order'           => 5,
          ],
        ]
      ],
    ];

    foreach ($menu as $key => $record) {
      $permission_name = null;
      $permission_show = 0;

      if (isset($temp_user_permission[$record['permission_type']]) || $record['permission_type'] == 'all') {
        $permission_name = isset($temp_user_permission[$record['permission_type']]) ? $temp_user_permission[$record['permission_type']] : 'all';
        $permission_show = 1;
      }

      $permission_show = ($is_admin == 1) ? 1 : $permission_show;

      if ($permission_show) {
        $menu[$key]['permission_name'] = $permission_name;
        $menu[$key]['permission_show'] = $permission_show;

        foreach ($menu[$key]['sub_menu'] as $key_sub => $record_sub) {

          $permission_sub_show = ($is_admin == 1) ? 1 : ($record_sub['path_url'] == '/' ? 0 : $permission_show);

          $menu[$key]['sub_menu'][$key_sub]['permission_name'] = $permission_name;
          $menu[$key]['sub_menu'][$key_sub]['permission_show'] = $permission_sub_show;

          if ($permission_sub_show == 0) {
            unset($menu[$key]['sub_menu'][$key_sub]);
          }
        }
      } else {
        if ($is_admin != 1) {
          unset($menu[$key]);
        }
      }
    }

    $menu           = array_values($menu);
    $return['menu'] = $menu;

    return $return;
  }

  /**
  | Group | Dropdown
  | Description | 1.ดึงข้อมูลลิส 'Role'
  | $code | โค้ดโมดูล
   */
  public static function dropdownRoleEmployee($code) {
    $return = []; // list
    $sql_where = [
      'AND' => [
        'module'  => 'User',
        'name[!]' => 'Admin'
      ]
    ];

    $return = Amst::selectNoKey('sys_function', ['id', 'code', 'name', 'description'], $sql_where);
    return $return;
  }

  /**
  | Group | Dropdown
  | Description | 1.ดึงข้อมูลลิส 'Role'
  | $code | โค้ดโมดูล
   */
  public static function dropdownEmployee($code) {
    $return = []; // list
    $sql_where = [
      // 'AND' => [
      //   'user_type[!]' => 'Admin'
      // ]
    ];

    $return = Amst::selectNoKey('sys_user', ['id', 'user_code', 'user_type', 'full_name'], $sql_where);
    foreach ($return as $key => $record) {
      $return[$key]['title_dropdown'] = $record['full_name'] . ' (' . $record['user_type'] . ')';
    }
    return $return;
  }

  /**
  | Group | Dropdown
  | Description | 1.ดึงข้อมูลลิส 'Working Time'
  | $code | โค้ดโมดูล
  | $where | [not_id]
   */
  public static function dropdownWorkingTime($code, $where = []) {
    $return = []; // list
    $sql_where = [];

    if (isset($where['not_id']) && $where['not_id'] && $where['not_id'] != 'all') {
      $sql_where['AND']['id[!]'] = $where['not_id'];
    }

    $sql_where['ORDER']['sort_order_id'] = 'DESC';

    $return = Amst::selectNoKey($code . '_setting_working_time', ['id', 'title'], $sql_where);
    return $return;
  }

  /**
  | Group | Dropdown
  | Description | 1.ดึงข้อมูลลิส 'Branch'
  | $code | โค้ดโมดูล
  | $where | [not_id]
   */
  public static function dropdownBranch($code, $where = []) {
    $return = []; // list

    $return = Phr_setting_branch::selectSettingBranch($code, $where, ['is_dropdown' => 1]);
    return $return;
  }

  /**
  | Group | Dropdown
  | Description | 1.ดึงข้อมูลลิส 'Business Type'
  | $code | โค้ดโมดูล
  | $where | [not_id]
   */
  public static function dropdownBusinessType($code, $where = []) {
    $return = []; // list
    $sql_where = [];

    if (isset($where['not_id']) && $where['not_id']) {
      $sql_where['AND']['id[!]'] = $where['not_id'];
    }

    $return = Amst::selectNoKey($code . '_setting_business_type', ['id', 'name'], $sql_where);
    return $return;
  }

  /**
  | Group | Dropdown
  | Description | 1.ดึงข้อมูลลิส 'OT'
  | $code | โค้ดโมดูล
  | $where | [
    not_id,
    is_use(0 / 1)
  ]
   */
  public static function dropdownOT($code, $where = []) {
    $return = []; // list
    $sql_where = [];

    if (isset($where['not_id']) && $where['not_id']) {
      $sql_where['AND']['id[!]'] = $where['not_id'];
    }

    if (isset($where['is_use']) && $where['is_use']) {
      $sql_where['AND']['is_use'] = $where['is_use'];
    }

    $return = Amst::selectNoKey($code . '_setting_ot', ['id', 'title'], $sql_where);
    return $return;
  }

  /**
  | Group | Dropdown
  | Description | 1.ดึงข้อมูลลิส 'Month Checkin'
  | $code | โค้ดโมดูล
  | $where | [not_id]
   */
  public static function dropdownMonthCheckIn($code) {
    $return = []; // list
    $month = [
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

    $sql_where = [
      'GROUP' => 'date',
      'ORDER' => [
        'date' => 'DESC'
      ]
    ];
    $select = Amst::selectNoKey($code . '_checkin', ['date'], $sql_where);
    $temp_month = [];
    foreach ($select as $key => $record) {
      $month_number = date('m', strtotime($record['date']));
      $month_number = (int)$month_number;
      $year_number  = date('Y', strtotime($record['date']));

      $month_name_th = $month[$month_number];
      $set_date = $month_name_th . ' ' . $year_number;

      if (!isset($temp_month[$set_date])) {
        $temp_month[$set_date] = [
          'text' => $set_date,
          'text_month' => $month_name_th,
          'value' => date('Y-m', strtotime($record['date']))
        ];
      }
    }

    $temp_month = array_values($temp_month);
    // $temp_month = Util::arraySortByColumn($temp_month, 'value', SORT_DESC);

    return $temp_month;
  }

  /**
  | Group | Dropdown
  | Description | 1.ดึงข้อมูลลิส 'Month Salary'
  | $code | โค้ดโมดูล
   */
  public static function dropdownMonthSalary($code) {
    $return = []; // list
    $month = [
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

    $sql_where = [
      'GROUP' => 'installment_date',
      'ORDER' => [
        'installment_date' => 'DESC'
      ]
    ];
    $select = Amst::selectNoKey($code . '_salary', ['installment_date'], $sql_where);
    $temp_month = [];
    foreach ($select as $key => $record) {
      $month_number = date('m', strtotime($record['installment_date']));
      $month_number = (int)$month_number;
      $year_number  = date('Y', strtotime($record['installment_date']));

      $month_name_th = $month[$month_number];
      $set_date = $month_name_th . ' ' . $year_number;

      if (!isset($temp_month[$set_date])) {
        $temp_month[$set_date] = [
          'text' => $set_date,
          'text_month' => $month_name_th,
          'value' => date('Y-m', strtotime($record['installment_date']))
        ];
      }
    }

    $temp_month = array_values($temp_month);
    // $temp_month = Util::arraySortByColumn($temp_month, 'value', SORT_DESC);

    return $temp_month;
  }

  /**
  | Group | Dropdown
  | Description | 1.ดึงข้อมูลลิส 'Month Commission'
  | $code | โค้ดโมดูล
   */
  public static function dropdownMonthCommission($code) {
    $return = []; // list
    $month = [
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

    $sql_where = [
      'AND' => [
        'status' => 'approved'
      ],
      // 'GROUP' => 'installment_date',
      'ORDER' => [
        'status_update_datetime' => 'DESC'
      ]
    ];
    $select = Amst::selectNoKey($code . '_commission', ['status_update_datetime'], $sql_where);
    $temp_month = [];
    foreach ($select as $key => $record) {
      $month_number = date('m', strtotime($record['status_update_datetime']));
      $month_number = (int)$month_number;
      $year_number  = date('Y', strtotime($record['status_update_datetime']));

      $month_name_th = $month[$month_number];
      $set_date = $month_name_th . ' ' . $year_number;

      if (!isset($temp_month[$set_date])) {
        $temp_month[$set_date] = [
          'text' => $set_date,
          'text_month' => $month_name_th,
          'value' => date('Y-m', strtotime($record['status_update_datetime']))
        ];
      }
    }

    $temp_month = array_values($temp_month);
    // $temp_month = Util::arraySortByColumn($temp_month, 'value', SORT_DESC);

    return $temp_month;
  }

  /**
  | Group | Diff Date
  | Description | 1.Diff Date time
  | $start_datetime | start_datetime
  | $end_datetime | end_datetime
   */
  public static function calDiffDatetime($start_datetime, $end_datetime) {
    // กำหนดวันที่และเวลาเริ่มต้น
    $dateStart = new DateTime($start_datetime);

    // กำหนดวันที่และเวลาสิ้นสุด
    $dateEnd = new DateTime($end_datetime);

    // หาเวลาที่ห่างกัน
    $interval = $dateStart->diff($dateEnd);

    // echo 'วันทั้งหมด: ' . $interval->days . ' วัน<br>';
    // echo 'ชั่วโมงทั้งหมด: ' . $interval->h . ' ชั่วโมง<br>';
    // echo 'นาทีทั้งหมด: ' . $interval->i . ' นาที<br>';
    // echo 'วินาทีทั้งหมด: ' . $interval->s . ' วินาที<br>';

    return $interval;
  }
}
