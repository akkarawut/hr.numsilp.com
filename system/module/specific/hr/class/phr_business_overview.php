<?php

class Phr_business_overview {

  /**
  | Group | Business Overview
  | Description | 1.ดึงข้อมูลลิส 'Business Overview'
  | $code | โค้ดโมดูล
  | $where | [
    from_date,
    to_date,
    date
  ]
 */
  public static function BusinessOverView($code, $where = []) {
    $return = [
      'date_now'                  => '',
      'count_user'                => 0,
      'count_checkin'             => 0,
      'count_late'                => 0,
      'count_late_user'           => 0,
      'count_very_late'           => 0,
      'count_very_late_user'      => 0,
      'count_missing'             => 0,
      'count_missing_user'        => 0,

      'count_employee_sale'       => 0,
      'total_employee_sale'       => 0,
      'total_employee_commission' => 0,

      'chart_month_sale' => []
    ];

    $date_start = date('Y-m-01');
    $date_now   = date('Y-m-d');
    $date_month = date('Y-m');
    $return['date_now'] = $date_now;

    $where_count_user = [
      'AND' => [
        // 'user_type[!]' => ['admin', 'Admin', 'แอดมิน'],
        'is_ban'       => 0,
        'is_can_login' => 1
      ]
    ];
    $count_user = Amst::count('sys_user', $where_count_user);
    $return['count_user'] = $count_user;

    $where_count_checkin = [
      'AND' => [
        'date'    => $date_now,
        'is_last' => 1
      ]
    ];
    $count_checkin = Amst::count($code . '_checkin', $where_count_checkin);
    $return['count_checkin'] = $count_checkin;

    $arr_where = [
      [
        'name'  => 'count_missing',
        'where' => [
          'AND' => [
            'status_checkin' => 'missing',
            'status_confirm' => 'approved',
            'is_last'        => 1
          ]
        ]
      ],
      [
        'name'  => 'count_late',
        'where' => [
          'AND' => [
            'status_checkin' => 'late',
            'status_confirm' => 'approved',
            'is_last'        => 1
          ]
        ]
      ],
      [
        'name'  => 'count_very_late',
        'where' => [
          'AND' => [
            'status_checkin' => 'very_late',
            'status_confirm' => 'approved',
            'is_last'        => 1
          ]
        ]
      ],
    ];

    foreach ($arr_where as $key => $record_where) {
      $key_name                     = $record_where['name'];
      $sql_where                    = $record_where['where'];
      $sql_where['AND']['date']     = $date_now;
      $count                        = Amst::count($code . '_checkin', $sql_where);

      $return[$key_name]            = $count;
      $return[$key_name . '_user']  = $count;
    }

    $arr_count_employee_sale   = [];
    $count_employee_sale       = 0;
    $total_employee_sale       = 0;
    $total_employee_commission = 0;

    $temp_chart_month_sale = [
      'list' => [],
    ];

    if (isset($where['date']) && $where['date'] && $where['date'] != 'all') {
      $date_set   = $where['date'] . '-01';
      $date_start = date('Y-m-01', strtotime($date_set));
      $date_now   = date('Y-m-t', strtotime($date_set));
      $date_month = $where['date'];
    }

    // cal day
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
        'status_update_datetime[~]' => $date_month
      ]
    ];
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
}
