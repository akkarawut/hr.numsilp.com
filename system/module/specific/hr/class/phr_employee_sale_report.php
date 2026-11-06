<?php

class Phr_employee_sale_report {

  /**
  | Group | Repory Employee Sale
  | Description | 1.ดึงข้อมูล 'Repory Employee Sale' Head
  | $code | โค้ดโมดูล
  | $where | [
    user_id,
    from_date,
    to_date
  ]
 */
  public static function ReportEmployeeSaleHead($code, $where = []) {
    $return = [
      'count_user'                => 0,
      'count_employee_sale'       => 0,
      'total_employee_sale'       => 0,
      'total_employee_commission' => 0,

      'chart_month_sale'          => []
    ];

    $date_start = date('Y-m-01');
    $date_now   = date('Y-m-d');
    $date_month = date('Y-m');
    // $return['date_now'] = $date_now;

    $where_count_user = [
      'AND' => [
        // 'user_type[!]' => ['admin', 'Admin', 'แอดมิน'],
        'is_ban'       => 0,
        'is_can_login' => 1
      ]
    ];

    if (isset($where['user_id']) && $where['user_id'] && $where['user_id'] != 'all') {
      $where_count_user['AND']['id'] = $where['user_id'];
    } 

    $user_ids   = Amst::selectNoKey('sys_user', 'id', $where_count_user);
    $count_user = count($user_ids);
    $return['count_user'] = $count_user;

    $user_ids[] = -1;

    $arr_count_employee_sale   = [];
    $count_employee_sale       = 0;
    $total_employee_sale       = 0;
    $total_employee_commission = 0;

    $temp_chart_month_sale = [
      'list' => [],
    ];

    // cal day

    if ((isset($where['from_date']) && $where['from_date']) && (isset($where['to_date']) && $where['to_date'])) {
      $date_start = $where['from_date'];
      $date_now   = $where['to_date'];
    }

    $dateStart = new DateTime($date_start);
    $dateEnd   = new DateTime($date_now);

    $loop = 0;
    while ($dateStart <= $dateEnd && $loop < 100) {

      $date_gen = $dateStart->format('Y-m-d');

      if (!isset($temp_chart_month_sale['list'][$date_gen])) {
        $temp_chart_month_sale['list'][$date_gen] = [
          [
            'name'  => 'sale',
            'value' => 0
          ],
          [
            'name'  => 'commission',
            'value' => 0
          ]
        ];
      }

      $dateStart->add(new DateInterval('P1D')); // เพิ่มหนึ่งวัน

      $loop++;
    }

    $where_count_sale = [
      'AND' => [
        'status'                    => 'approved',
        // 'status_update_datetime[~]' => $date_month
      ]
    ];

    if ((isset($where['from_date']) && $where['from_date']) && (isset($where['to_date']) && $where['to_date'])) {
      $where_count_sale['AND']['status_update_datetime[<>]'] = [$where['from_date'] . ' 00:00:00', $where['to_date'] . ' 23:59:59'];

      // $where_count_sale['AND']['status_update_datetime[>=]'] = $where['from_date'] . ' 00:00:00';
      // $where_count_sale['AND']['status_update_datetime[<=]'] = $where['to_date'] . ' 23:59:59';
    } else {
      $where_count_sale['AND']['status_update_datetime[~]'] = $date_month;
    }

    if (isset($where['user_id']) && $where['user_id'] && $where['user_id'] != 'all') {
      $where_count_sale['AND']['user_id'] = $where['user_id'];
    } 

    $commission_list = Amst::selectNoKey($code . '_commission', '*', $where_count_sale);
    foreach ($commission_list as $key => $record) {
      if (!in_array($record['user_id'], $arr_count_employee_sale)) {
        $arr_count_employee_sale[] = $record['user_id'];
      }

      $total_employee_sale       += $record['sale_price'];
      $total_employee_commission += $record['total_price'];

      $check_date = date('Y-m-d', strtotime($record['status_update_datetime']));
      if (isset($temp_chart_month_sale['list'][$check_date])) {
        $temp_chart_month_sale['list'][$check_date][0]['value'] += $record['sale_price'];
        $temp_chart_month_sale['list'][$check_date][1]['value'] += $record['total_price'];
      }
    }

    $chart_month_sale = [
      'list' => [],
      'key_values' => [
        [
          'key'   => 'sale',
          'name'  => 'ยอดขายรายวัน',
          'color' => '#6794DC'
        ],
        [
          'key'   => 'commission',
          'name'  => 'ค่าคอมมิชชั่น',
          'color' => '#67B7DC'
        ]
      ]
    ];
    foreach ($temp_chart_month_sale['list'] as $date => $record) {
      $strYear     = date('Y', strtotime($date)) + 543;
      $strMonth    = date('n', strtotime($date));
      $strDay      = date('j', strtotime($date));
      $strHour     = date('H', strtotime($date));
      $strMinute   = date('i', strtotime($date));
      $strSeconds  = date('s', strtotime($date));
      $strMonthCut = [
        '',
        'ม.ค.',
        'ก.พ.',
        'มี.ค.',
        'เม.ย.',
        'พ.ค.',
        'มิ.ย.',
        'ก.ค.',
        'ส.ค.',
        'ก.ย.',
        'ต.ค.',
        'พ.ย.',
        'ธ.ค.'
      ];
      $strMonthThai = $strMonthCut[$strMonth];

      $date_gen = $strDay . ' ' . $strMonthThai;

      $chart_month_sale['list'][$date_gen] = $record;
    }

    $count_employee_sale = count($arr_count_employee_sale);

    $return['count_employee_sale']       = $count_employee_sale;
    $return['total_employee_sale']       = $total_employee_sale;
    $return['total_employee_commission'] = $total_employee_commission;

    $return['chart_month_sale']          = $chart_month_sale;

    return $return;
  }

  /**
  | Group | Repory Employee Sale
  | Description | 2.ดึงข้อมูลลิส 'Repory Employee Sale' Table
  | $code | โค้ดโมดูล
  | $where | [
    user_id,
    from_date,
    to_date
  ]
  | $options | []
 */
  public static function ReportEmployeeSaleTable($code, $where = [], $options = []) {
    $return = [];

    $date_month = date('Y-m');

    $where_user_com = [
      'AND' => [
        'status' => 'approved',
      ]
    ];

    if ((isset($where['from_date']) && $where['from_date']) && (isset($where['to_date']) && $where['to_date'])) {
      $where_user_com['AND']['status_update_datetime[<>]'] = [$where['from_date'] . ' 00:00:00', $where['to_date'] . ' 23:59:59'];

      // $where_user_com['AND']['status_update_datetime[>=]'] = $where['from_date'] . ' 00:00:00';
      // $where_user_com['AND']['status_update_datetime[<=]'] = $where['to_date'] . ' 23:59:59';
    } else {
      $where_user_com['AND']['status_update_datetime[~]'] = $date_month;
    }

    if (isset($where['date']) && $where['date']) {
      $start_date = date('Y-m-1 00:00:00', strtotime($where['date']));
      $end_date   = date('Y-m-t 23:59:59', strtotime($where['date']));
      $where_user_com['AND']['status_update_datetime[<>]'] = [$start_date, $end_date];
    }

    $defualt_user_ids   = Amst::selectNoKey($code . '_commission', 'user_id', $where_user_com);
    $defualt_user_ids[] = -1;

    $sql_where = [
      'AND' => [
        // 'user_type[!]' => ['admin', 'Admin', 'แอดมิน'],
        'id'           => $defualt_user_ids
      ]
    ];

    if (isset($where['user_id']) && $where['user_id'] && $where['user_id'] != 'all') {
      $sql_where['AND']['id'] = $where['user_id'];
    } 

    $return = Amst::select('sys_user', ['id', 'full_name'], $sql_where, $options);
    return static::setReportEmployeeSaleTableListDetail($code, $return, $where, $options);
  }

  public static function setReportEmployeeSaleTableListDetail($code, $return, $where = [], $options = [])
  {
    // [ExtractList]
    $selected_records = isset($return['list']) ? $return['list'] : $return;

    $date_start = date('Y-m-01');
    $date_now   = date('Y-m-d');
    $date_month = date('Y-m');

    $where_count_sale = [
      'AND' => [
        'status'                    => 'approved',
        // 'status_update_datetime[~]' => $date_month
      ]
    ];

    if ((isset($where['from_date']) && $where['from_date']) && (isset($where['to_date']) && $where['to_date'])) {
      $where_count_sale['AND']['status_update_datetime[<>]'] = [$where['from_date'] . ' 00:00:00', $where['to_date'] . ' 23:59:59'];

      // $where_count_sale['AND']['status_update_datetime[>=]'] = $where['from_date'] . ' 00:00:00';
      // $where_count_sale['AND']['status_update_datetime[<=]'] = $where['to_date'] . ' 23:59:59';
    } else {
      $where_count_sale['AND']['status_update_datetime[~]'] = $date_month;
    }

    if (isset($where['user_id']) && $where['user_id'] && $where['user_id'] != 'all') {
      $where_count_sale['AND']['user_id'] = $where['user_id'];
    } 

    $commission_list = Amst::selectNoKey($code . '_commission', '*', $where_count_sale);
    $temp_user_sale  = [];
    foreach ($commission_list as $key => $record) {
      if (!isset($temp_user_sale[$record['user_id']])) {
        $temp_user_sale[$record['user_id']] = [
          'count'      => 0,
          'sale'       => 0,
          'commission' => 0
        ];
      }

      $temp_user_sale[$record['user_id']]['count']      += 1;
      $temp_user_sale[$record['user_id']]['sale']       += $record['sale_price'];
      $temp_user_sale[$record['user_id']]['commission'] += $record['total_price'];
    }

    // Do Each Works...
    foreach ($selected_records as $key => $record) {

      $count_sale       = 0;
      $total_sale       = 0;
      $total_commission = 0;

      if (isset($temp_user_sale[$record['id']])) {
        $count_sale       = $temp_user_sale[$record['id']]['count'];
        $total_sale       = $temp_user_sale[$record['id']]['sale'];
        $total_commission = $temp_user_sale[$record['id']]['commission'];
      }

      $selected_records[$key]['count_sale']       = $count_sale;
      $selected_records[$key]['total_sale']       = $total_sale;
      $selected_records[$key]['total_commission'] = $total_commission;
    }

    $selected_records = array_values($selected_records);

    // [Repack]
    if (isset($return['list'])) {
      $return['list'] = $selected_records;
    } else {
      $return = $selected_records;
    }
    return $return;
  }
}
