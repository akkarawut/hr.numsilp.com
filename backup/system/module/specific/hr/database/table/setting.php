<?php
$module_tables = [
  '_setting_holiday'     => [
    'title'            => 'TEXT',
    'type'             => 'TEXT', // one_day / period
    'year'             => 'TEXT',
    'from_date'        => 'DATE',
    'from_time'        => 'TIME',
    'from_datetime'    => 'DATETIME',
    'to_date'          => 'DATE',
    'to_time'          => 'TIME',
    'to_datetime'      => 'DATETIME',
    'description'      => 'TEXT',
    'role_use'         => 'TEXT', // json
    'request_user_ids' => 'TEXT', // json
  ],
  '_setting_working_time'     => [
    'title'               => 'TEXT',
    'description'         => 'TEXT',
    'hour_total'          => 'INT',
    'minute_total'        => 'INT',
    'count_user'          => 'INT',
    'sum_day_work'        => 'INT',

    'monday'              => 'TINYINT',
    'monday_fromtime'     => 'TIME',
    'monday_totime'       => 'TIME',
    'monday_hour'         => 'INT',
    'monday_minute'       => 'INT',
    'tuesday'             => 'TINYINT',
    'tuesday_fromtime'    => 'TIME',
    'tuesday_totime'      => 'TIME',
    'tuesday_hour'        => 'INT',
    'tuesday_minute'      => 'INT',
    'wednesday'           => 'TINYINT',
    'wednesday_fromtime'  => 'TIME',
    'wednesday_totime'    => 'TIME',
    'wednesday_hour'      => 'INT',
    'wednesday_minute'    => 'INT',
    'thursday'            => 'TINYINT',
    'thursday_fromtime'   => 'TIME',
    'thursday_totime'     => 'TIME',
    'thursday_hour'       => 'INT',
    'thursday_minute'     => 'INT',
    'friday'              => 'TINYINT',
    'friday_fromtime'     => 'TIME',
    'friday_totime'       => 'TIME',
    'friday_hour'         => 'INT',
    'friday_minute'       => 'INT',
    'saturday'            => 'TINYINT',
    'saturday_fromtime'   => 'TIME',
    'saturday_totime'     => 'TIME',
    'saturday_hour'       => 'INT',
    'saturday_minute'     => 'INT',
    'sunday'              => 'TINYINT',
    'sunday_fromtime'     => 'TIME',
    'sunday_totime'       => 'TIME',
    'sunday_hour'         => 'INT',
    'sunday_minute'       => 'INT',

    'late_minute'         => 'INT',
    'very_late_minute'    => 'INT',
    'missing_minute'      => 'INT',
    'is_late_minute'      => 'INT',
    'is_very_late_minute' => 'INT',
    'is_missing_minute'   => 'INT'
  ],
  '_setting_deduct_money'     => [ // ตั้งค่าหักเงิน
    'title'            => 'TEXT',
    'type'             => 'TEXT', // late / very_late / missing
    'count_user'       => 'INT',
    'amount'           => 'INT',
    'price'            => 'DECIMAL',
    'type_calculate'   => 'TEXT', // FIX month -- เผื่อปรับภายภาคหน้า
    'description'      => 'TEXT',
    'is_use'           => 'TINYINT',
    'role_use'         => 'TEXT'
  ],
  '_setting_ot'     => [
    'title'            => 'TEXT',
    'price'            => 'INT',
    'count_user'       => 'INT',
    'type_calculate'   => 'TEXT', // FIX hour -- เผื่อปรับภายภาคหน้า
    'description'      => 'TEXT',
    'is_use'           => 'TINYINT',
    'role_use'         => 'TEXT'
  ],
  '_setting_branch'     => [
    'name'               => 'TEXT',
    'location'           => 'TEXT',
    'count_user'         => 'INT',
    'type_branch'        => 'TEXT', // main / branch

    'branch_code'        => 'TEXT',
    'type_business_id'   => 'TEXT',
    'type_business_name' => 'TEXT',

    'contact_person'     => 'TEXT',
    'tax_number'         => 'TEXT',
    'email'              => 'TEXT', // json
    'tel'                => 'TEXT', // json
    'fax_number'         => 'TEXT',
    'description'        => 'TEXT'
  ],
  '_setting_business_type'     => [
    'name'               => 'TEXT',
  ],
  '_setting_basic'     => [
    'is_security'    => 'TINYINT',
    'security_price' => 'DECIMAL',
    'security_unit'  => 'TEXT',
    'tax'            => 'INT',
  ],
];