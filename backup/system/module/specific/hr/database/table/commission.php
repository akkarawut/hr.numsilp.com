<?php
$module_tables = [
  '_commission'     => [
    'user_id'                   => 'BIGINT',
    'status'                    => 'TEXT', // waiting_confirm / approved / reject
    'no'                        => 'TEXT',
    'sale_price'                => 'DECIMAL',
    'commission_price'          => 'DECIMAL',
    'tax'                       => 'INT',
    'tax_price'                 => 'DECIMAL',
    'total_price'               => 'DECIMAL',
    'remark'                    => 'TEXT',
    'status_update_by_user_id'  => 'BIGINT',
    'status_update_by_fullname' => 'TEXT',
    'status_update_datetime'    => 'DATETIME',
    'status_update_remark'      => 'TEXT',
    'is_file_deduct_tax'        => 'TINYINT',
  ],
];