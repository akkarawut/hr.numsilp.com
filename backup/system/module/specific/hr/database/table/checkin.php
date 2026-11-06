<?php
$module_tables = [
  '_checkin'     => [
    'user_id'                           => 'BIGINT',
    'type'                              => 'TEXT', // on_time / late
    'status'                            => 'TEXT', // in_progress / success
    'status_checkin'                    => 'TEXT', // normal / late / very_late / missing / draft_leave_work / leave_work
    'status_confirm'                    => 'TEXT', // waiting_confirm / approved / reject (เผื่อไว้ ** แล้วต้องมาลงย้อนหลังใหม่)
    'day_name'                          => 'TEXT',
    'date'                              => 'DATE',
    'checkin_time'                      => 'TIME',
    'checkin_branch_id'                 => 'BIGINT',
    'checkin_datetime'                  => 'DATETIME',
    'checkin_late_minute'               => 'INT',
    'checkout_time'                     => 'TIME',
    'checkout_branch_id'                => 'BIGINT',
    'checkout_datetime'                 => 'DATETIME',
    'working_day'                       => 'INT',
    'working_hour'                      => 'INT',
    'working_minute'                    => 'INT',
    'working_second'                    => 'INT',
    'total_working_time'                => 'TEXT',
    'is_overtime'                       => 'TINYINT',
    'remark'                            => 'TEXT',
    'status_confirm_update_by_user_id'  => 'BIGINT',
    'status_confirm_update_by_fullname' => 'TEXT',
    'status_confirm_update_datetime'    => 'DATETIME',
    'status_confirm_update_remark'      => 'TEXT',
    'leave_work_id'                     => 'BIGINT',
    'is_last'                           => 'TINYINT',
  ],
  '_checkin_log' => [
    'checkin_id' => 'BIGINT',
    'user_id'    => 'BIGINT',
    'type'       => 'TEXT', // checkin / checkout / update_status_confirm
    'date'       => 'DATE',
    'time'       => 'TIME',
    'value'      => 'TEXT'
  ]
];