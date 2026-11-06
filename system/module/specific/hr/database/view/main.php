<?php
$sql_view_info[$modulename] = [
  '_checkin'   => [
    'name'        => '_checkin',
    'description' => 'SELECT _checkin',
    'query'       => '_checkin` AS
        SELECT tbl_checkin.*,
        tbl_setting_branch_in.name AS checkin_branch_name,
        tbl_setting_branch_out.name AS checkout_branch_name

        FROM '.Config::db_name.'.'.Info::module_table_prefix.$function['code'].'_checkin AS tbl_checkin

        LEFT JOIN '.Config::db_name.'.'.Info::module_table_prefix.$function['code'].'_setting_branch AS tbl_setting_branch_in
        ON tbl_checkin.checkin_branch_id = tbl_setting_branch_in.id

        LEFT JOIN '.Config::db_name.'.'.Info::module_table_prefix.$function['code'].'_setting_branch AS tbl_setting_branch_out
        ON tbl_checkin.checkout_branch_id = tbl_setting_branch_out.id

        WHERE tbl_checkin.system_delete = 0
    ;'
  ],
  '_document_warning'   => [
    'name'        => '_document_warning',
    'description' => 'SELECT _document_warning',
    'query'       => '_document_warning` AS
        SELECT tbl_document_warning.*,
        tbl_user.full_name AS user_fullname,
        tbl_user.user_code AS user_code,
        tbl_user_insert.full_name AS insert_user_fullname

        FROM '.Config::db_name.'.'.Info::module_table_prefix.$function['code'].'_document_warning AS tbl_document_warning

        LEFT JOIN sys_user AS tbl_user
        ON tbl_document_warning.user_id = tbl_user.id

        LEFT JOIN sys_user AS tbl_user_insert
        ON tbl_document_warning.insert_user_id = tbl_user_insert.id

        WHERE tbl_document_warning.system_delete = 0
    ;'
  ],
  '_employee_cost'   => [
    'name'        => '_employee_cost',
    'description' => 'SELECT _employee_cost',
    'query'       => '_employee_cost` AS
        SELECT tbl_employee_cost.*,
        tbl_user.full_name AS user_fullname,
        tbl_user_insert.full_name AS insert_user_fullname

        FROM '.Config::db_name.'.'.Info::module_table_prefix.$function['code'].'_employee_cost AS tbl_employee_cost

        LEFT JOIN sys_user AS tbl_user
        ON tbl_employee_cost.user_id = tbl_user.id

        LEFT JOIN sys_user AS tbl_user_insert
        ON tbl_employee_cost.insert_user_id = tbl_user_insert.id

        WHERE tbl_employee_cost.system_delete = 0
    ;'
  ],
  '_salary'   => [
    'name'        => '_salary',
    'description' => 'SELECT _salary',
    'query'       => '_salary` AS
        SELECT tbl_salary.*,
        tbl_user.full_name AS user_fullname,
        tbl_user_insert.full_name AS insert_user_fullname

        FROM '.Config::db_name.'.'.Info::module_table_prefix.$function['code'].'_salary AS tbl_salary

        LEFT JOIN sys_user AS tbl_user
        ON tbl_salary.user_id = tbl_user.id

        LEFT JOIN sys_user AS tbl_user_insert
        ON tbl_salary.insert_user_id = tbl_user_insert.id

        WHERE tbl_salary.system_delete = 0
    ;'
  ],
  '_employee_cost_log'   => [
    'name'        => '_employee_cost_log',
    'description' => 'SELECT _employee_cost_log',
    'query'       => '_employee_cost_log` AS
        SELECT tbl_employee_cost_log.*,
        tbl_employee_cost.name AS employee_cost_name

        FROM '.Config::db_name.'.'.Info::module_table_prefix.$function['code'].'_employee_cost_log AS tbl_employee_cost_log

        LEFT JOIN '.Config::db_name.'.'.Info::module_table_prefix.$function['code'].'_employee_cost AS tbl_employee_cost
        ON tbl_employee_cost_log.employee_cost_id = tbl_employee_cost.id

        WHERE tbl_employee_cost_log.system_delete = 0
    ;'
  ],
];