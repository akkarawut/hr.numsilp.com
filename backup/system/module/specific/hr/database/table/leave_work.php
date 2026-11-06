<?php
$module_tables = [
  '_leave_work'     => [
    'user_id'                           => 'BIGINT',
    'no'                                => 'TEXT',
    'type'                              => 'TEXT', // sick_leave / personal_leave
    'status_confirm'                    => 'TEXT', // waiting_confirm / approved / reject (เผื่อไว้ ** แล้วต้องมาลงย้อนหลังใหม่)
    'detail'                            => 'TEXT',
    'from_date'                         => 'DATE',
    'to_date'                           => 'DATE',
    'sum_day_leave_work'                => 'INT',
    'status_confirm_update_by_user_id'  => 'BIGINT',
    'status_confirm_update_by_fullname' => 'TEXT',
    'status_confirm_update_datetime'    => 'DATETIME'
  ],
  '_leave_work_log' => [
    'leave_work_id' => 'BIGINT',
    'user_id'       => 'BIGINT',
    'type'          => 'TEXT', // update_status_confirm
    'value'         => 'TEXT'
  ]
];