<?php
$module_tables = [
  '_ot'     => [
    'user_id'                           => 'BIGINT',
    'no'                                => 'TEXT',
    'setting_ot_id'                     => 'BIGINT',
    'setting_ot_name'                   => 'TEXT',
    'late_cal_hour'                     => 'DECIMAL',
    'total_price'                       => 'DECIMAL',
    'status_confirm'                    => 'TEXT', // waiting_confirm / approved / reject (เผื่อไว้ ** แล้วต้องมาลงย้อนหลังใหม่)
    'date'                              => 'DATE',
    'from_time'                         => 'TIME',
    'to_time'                           => 'TIME',
    'hour_total'                        => 'INT',
    'remark'                            => 'TEXT',
    'status_confirm_update_by_user_id'  => 'BIGINT',
    'status_confirm_update_by_fullname' => 'TEXT',
    'status_confirm_update_datetime'    => 'DATETIME',
    'status_confirm_update_remark'      => 'TEXT',
  ],
];