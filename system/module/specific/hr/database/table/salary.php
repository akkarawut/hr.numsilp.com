<?php
$module_tables = [
  '_salary'     => [
    'user_id'                   => 'BIGINT',
    'status'                    => 'TEXT', // draft / waiting_confirm / approved / reject
    'employee_status'           => 'TEXT', // day / month / trial_work
    'installment_date'          => 'DATE', // จะเป็นวันแรกที่ทำงานของเดือนเสมอ
    'from_date'                 => 'DATE',
    'to_date'                   => 'DATE',
    'day'                       => 'TEXT',
    'month'                     => 'TEXT',
    'year'                      => 'TEXT',
    'salary'                    => 'DECIMAL',
    'is_security'               => 'TINYINT',
    'security_price'            => 'DECIMAL',
    'security_unit'             => 'TEXT',
    'is_fine'                   => 'TINYINT',
    'count_fine'                => 'INT',
    'fine_price'                => 'DECIMAL',
    'count_employee_cost'       => 'INT',
    'employee_cost_total_price' => 'DECIMAL',
    'total_price'               => 'DECIMAL',
    'status_update_by_user_id'  => 'BIGINT',
    'status_update_by_fullname' => 'TEXT',
    'status_update_datetime'    => 'DATETIME',
    'status_update_remark'      => 'TEXT',
    'bank_code'                 => 'TEXT',
    'bank_name'                 => 'TEXT',
    'bank_account_name'         => 'TEXT',
    'bank_account_number'       => 'TEXT'
  ],
];
