<?php
$module_tables = [
  '_performance'     => [
    'user_id'                   => 'BIGINT',
    'employee_status'           => 'TEXT',
    'installment_date'          => 'DATE', // จะเป็นวันแรกที่ทำงานของเดือนเสมอ
    'date'                      => 'DATE',
    'month'                     => 'INT',
    'year'                      => 'INT',
    'count_day_work'            => 'INT',
    'count_missing'             => 'INT',
    'count_missing_percent'     => 'INT',
    'count_leave_work'          => 'INT',
    'count_leave_work_percent'  => 'INT',
    'count_late'                => 'INT',
    'count_late_percent'        => 'INT',
    'count_very_late'           => 'INT',
    'count_very_late_percent'   => 'INT',
    'percent_performance'       => 'DECIMAL',
  ],
];
