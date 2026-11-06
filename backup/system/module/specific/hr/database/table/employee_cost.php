<?php
$module_tables = [
  '_employee_cost'     => [
    'ref_deduct_id'     => 'BIGINT', // ** ใช้กับ type => deduct เอาไว้เช็คครั้ง
    'user_id'           => 'BIGINT',
    'type'              => 'TEXT', // cost ** ค่าใช้จ่ายที่เพิ่มเอง / deduct ** ค่าใช้จ่ายที่เกิดจากการ ขาด ลา มาสาย
    'type_deduct'       => 'TEXT', // late / very_late / missing / leave_work ** ใช้กับ type => deduct เอาไว้เช็คครั้ง
    'date_deduct'       => 'DATE', // ** ใช้กับ type => deduct เอาไว้เช็คครั้ง
    'status'            => 'TEXT', // in_progress / success / cancel (เผื่อไว้)
    'name'              => 'TEXT',
    'total_price_pay'   => 'DECIMAL',
    'total_price'       => 'DECIMAL',
    'type_pay'          => 'TEXT', // one / multiple
    'period'            => 'INT',
    'period_sum_pay'    => 'INT',
    'price_per_period'  => 'DECIMAL',
    'remark'            => 'TEXT',
  ],
  '_employee_cost_log'     => [
    'employee_cost_id'  => 'BIGINT',
    'installment_date'  => 'DATE', // จะเป็นวันแรกที่ทำงานของเดือนเสมอ
    'user_id'           => 'BIGINT',
    'period_no'         => 'INT',
    'period_all'        => 'INT',
    'price_per_period'  => 'DECIMAL'
  ],
];
