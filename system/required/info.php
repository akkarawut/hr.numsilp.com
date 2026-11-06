<?php
class Info {
  const version_main   = 3;
  const version_sub    = 1;
  const version_hotfix = 3;

  // 3.0beta - [20211116] init+clone from AmstX2
  // 3.0 - [20220101] Release X3 for a year.
  // 3.1 - [20220418] Installation + Documentation
  // 3.1.1 - [20220915] Fix bug db_init comment
  // 3.1.2 - [20220915] add more dynamic file_code
  // 3.1.3 - [20220922] Upgrade Medoo Library from 1.7.10 to 2.1.7. >> https://medoo.in/api/whatsnew
  // 3.2 - [NEXT] Library + Performance

  //// [SC][SITE]
  const site_name = ''; // wloves

  //// DATABASE
  const db_field_prefix  = "`id` BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY ,";
  const db_field_postfix = "
	`insert_date_time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'ระบบ : วันเวลาที่คนใส่ข้อมูล' ,
	`insert_user_id` BIGINT NOT NULL DEFAULT -1 COMMENT 'ระบบ : id คนใส่ข้อมูล' ,
	`insert_detail` VARCHAR(400) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT 'ระบบ : รายละเอียดการใส่ข้อมูล' ,
	`update_date_time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'ระบบ : วันเวลาที่คนแก้ไขล่าสุด' ,
	`update_user_id` BIGINT NOT NULL DEFAULT -1 COMMENT 'ระบบ : id คนแก้ไขล่าสุด' ,
	`update_detail` VARCHAR(400) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT 'ระบบ : รายละเอียดการแก้ไขข้อมูล' ,
	`sort_order_id` BIGINT NOT NULL COMMENT 'ระบบ : เอาไว้จัดอันดับ' ,
	`version` BIGINT NOT NULL DEFAULT 1 COMMENT 'ระบบ : อัพเดทที เลขจะบวกไปทีนึง' ,
	`system_delete` TINYINT NOT NULL DEFAULT 0 COMMENT 'ระบบ : ถ้าเป็น 1 แสดงว่าถูกลบ'";

  //// SYSTEM
  const system_table_prefix = 'sys_';
  // map relative between [classname => file] *only class
  static $system_file = [
    // main Info
    'Info'               => [
      'path'             => 'required/info',
      'icon'             => 'fad fa-info',
      'show_in_document' => false,
      'category'         => 'main'
    ],
    'Info_Language'      => [
      'path'             => 'required/info_language',
      'icon'             => 'fad fa-info',
      'show_in_document' => false,
      'category'         => 'main'
    ],
    // config
    'Config'             => [
      'path'             => 'config',
      'icon'             => 'fad fa-sliders-h',
      'show_in_document' => false,
      'category'         => 'config'
    ],
    'Config_Module'      => [
      'path'             => 'config',
      'icon'             => 'fad fa-sliders-h',
      'show_in_document' => false,
      'category'         => 'config'
    ],
    // core
    'Amst'               => [
      'path'             => 'module_main/core/amst',
      'icon'             => 'fab fa-old-republic',
      'show_in_document' => true,
      'category'         => 'core'
    ],
    'Amst_Table'         => [
      'path'             => 'module_main/core/amst_table',
      'icon'             => 'fab fa-old-republic',
      'show_in_document' => true,
      'category'         => 'core'
    ],
    'Amst_Handson_Table' => [
      'path'             => 'module_main/core/amst_handson_table',
      'icon'             => 'fab fa-old-republic',
      'show_in_document' => true,
      'category'         => 'core'
    ],
    'DB'                 => [
      'path'             => 'module_main/core/db',
      'icon'             => 'fad fa-database',
      'show_in_document' => false,
      'category'         => 'core'
    ],
    'DB_Init'            => [
      'path'             => 'module_main/core/db_init',
      'icon'             => 'fad fa-database',
      'show_in_document' => true,
      'category'         => 'core'
    ],
    'DBQuery'            => [
      'path'             => 'module_main/core/db_query',
      'icon'             => 'fad fa-database',
      'show_in_document' => false,
      'category'         => 'core'
    ],
    'Util'               => [
      'path'             => 'module_main/core/util',
      'icon'             => 'fad fa-alien-monster',
      'show_in_document' => true,
      'category'         => 'core'
    ],
    'API'                => [
      'path'             => 'module_main/core/api',
      'icon'             => 'fad fa-connectdevelop',
      'show_in_document' => true,
      'category'         => 'core'
    ],
    'Auth'               => [
      'path'             => 'module_main/core/auth',
      'icon'             => 'fad fa-cog',
      'show_in_document' => true,
      'category'         => 'wloves'
    ],
    'Doc'                => [
      'path'             => 'module_main/core/doc',
      'icon'             => 'fad fa-user',
      'show_in_document' => true,
      'category'         => 'core'
    ],
    'Func'               => [
      'path'             => 'module_main/core/func',
      'icon'             => 'fad fa-th-list',
      'show_in_document' => false,
      'category'         => 'core'
    ],
    'Language'           => [
      'path'             => 'module_main/core/language',
      'icon'             => 'fab fa-atom',
      'show_in_document' => true,
      'category'         => 'core'
    ],
    'Module'             => [
      'path'             => 'module_main/core/module',
      'icon'             => 'fab fa-atom',
      'show_in_document' => true,
      'category'         => 'core'
    ],
    'SC'                 => [
      'path'             => 'module_main/core/session_cookie',
      'icon'             => 'fad fa-cookie-bite',
      'show_in_document' => true,
      'category'         => 'core'
    ],
    'Extra'              => [
      'path'             => 'module_main/core/extra',
      'icon'             => 'fad fa-cookie-bite',
      'show_in_document' => true,
      'category'         => 'core'
    ],
    // wloves
    'WLoves'             => [
      'path'             => 'module_main/wloves/wloves',
      'icon'             => 'fad fa-cog',
      'show_in_document' => true,
      'category'         => 'wloves'
    ],
    // User
    'Dev'                => [
      'path'             => 'module_main/user/dev',
      'icon'             => 'fad fa-user',
      'show_in_document' => true,
      'category'         => 'user'
    ],
    'User'               => [
      'path'             => 'module_main/user/user',
      'icon'             => 'fad fa-user',
      'show_in_document' => true,
      'category'         => 'user'
    ],
    'User_Social'        => [
      'path'             => 'module_main/user/user_social',
      'icon'             => 'fad fa-user',
      'show_in_document' => true,
      'category'         => 'user'
    ],
    'User_page'          => [
      'path'             => 'module_main/user/user_page',
      'icon'             => 'fad fa-user',
      'show_in_document' => true,
      'category'         => 'user'
    ],
    // ],
    // 'User_type'          => [
    //   'path'             => 'module_main/user/user_type',
    //   'icon'             => 'fad fa-user',
    //   'show_in_document' => true,
    //   'category'         => 'user'
    // ],
    'User_Basic_Setting' => [
      'path'             => 'module_main/user/user_basic_setting',
      'icon'             => 'fad fa-user',
      'show_in_document' => true,
      'category'         => 'user'
    ],
    'User_Permission'    => [
      'path'             => 'module_main/user/user_permission',
      'icon'             => 'fad fa-users-cog',
      'show_in_document' => true,
      'category'         => 'user'
    ],
    'User_Log'           => [
      'path'             => 'module_main/user/user_log',
      'icon'             => 'fad fa-user',
      'show_in_document' => true,
      'category'         => 'user'
    ],
    'User_Firebase'      => [
      'path'             => 'module_main/user/user_firebase',
      'icon'             => 'fad fa-users-cog',
      'show_in_document' => true,
      'category'         => 'user'
    ],
    'User_Notification'  => [
      'path'             => 'module_main/user/user_notification',
      'icon'             => 'fad fa-users-cog',
      'show_in_document' => true,
      'category'         => 'user'
    ],
    'User_Line'          => [
      'path'             => 'module_main/user/user_line',
      'icon'             => 'fad fa-users-cog',
      'show_in_document' => true,
      'category'         => 'user'
    ],
    // file
    'File'               => [
      'path'             => 'module_main/file/file',
      'icon'             => 'fad fa-file-export',
      'show_in_document' => true,
      'category'         => 'file'
    ],
    'Excel'              => [
      'path'             => 'module_main/file/excel',
      'icon'             => 'fad fa-file',
      'show_in_document' => true,
      'category'         => 'file'
    ],
    // connection
    'Firebase'           => [
      'path'             => 'module_main/connection/firebase',
      'icon'             => 'fad fa-fire',
      'show_in_document' => false,
      'category'         => 'connection'
    ],
    'Notification'       => [
      'path'             => 'module_main/connection/notification',
      'icon'             => 'fad fa-bell',
      'show_in_document' => true,
      'category'         => 'connection'
    ],
    'Email'              => [
      'path'             => 'module_main/connection/email',
      'icon'             => 'fad fa-file',
      'show_in_document' => true,
      'category'         => 'connection'
    ],
    'SMS'                => [
      'path'             => 'module_main/connection/sms',
      'icon'             => 'fad fa-file',
      'show_in_document' => true,
      'category'         => 'connection'
    ],
    // debugger
    'Debug'              => [
      'path'             => 'module_main/core/debug',
      'icon'             => 'fab fa-atom',
      'show_in_document' => false,
      'category'         => 'debug'
    ],
    // general
    'Bank'               => [
      'path'             => 'module_main/general/bank',
      'icon'             => 'fad fa-alien-monster',
      'show_in_document' => true,
      'category'         => 'general'
    ],
    'Country'            => [
      'path'             => 'module_main/general/country',
      'icon'             => 'fad fa-alien-monster',
      'show_in_document' => true,
      'category'         => 'general'
    ],
    'Log'                => [
      'path'             => 'module_main/general/log',
      'icon'             => 'fas fa-clipboard-list',
      'show_in_document' => true,
      'category'         => 'general'
    ]
  ];
  static $system_table = [ // map relative between [functiontable => tablename]
    'function'                   => 'sys_function',
    'api_key'                    => 'sys_api_key',
    // setting module
    'language'                   => 'sys_language',
    'dev'                        => 'sys_dev',
    // UserType
    'user_type'                  => 'sys_user_type',
    // User
    'user'                       => 'sys_user',
    'user_notification'          => 'sys_user_notification',
    'user_notification_firebase' => 'sys_user_notification_firebase',
    'user_login_log'             => 'sys_user_login_log',
    // setting user --> unused ?
    'setting_user_team'          => 'sys_setting_user_team',
    // MISC
    'file'                       => 'sys_file',
    'log_error'                  => 'sys_log_error',
    'log_action'                 => 'sys_log_action',
    //db checker
    'ignore_column'              => 'sys_dbcheck_ignore_column',
    'db_log'                     => 'sys_db_log'
  ];
  static $system_tables = [ // map relative between [functiontable => tablename]

    'sys_function'                   => [
      'code'                  => 'VARCHAR(10)|UNIQUE|notupdate|COMMENT(code)',
      'module'                => 'VARCHAR(255)|notupdate|comment(module)',
      'name'                  => 'VARCHAR(255)|comment(ชื่อโมดูล)',
      'description'           => 'VARCHAR(1000)|comment(คำอธิบาย)',
      'json_image_code'       => 'TEXT|COMMENT(ลิสโค้ดรูป)',
      'json_exdata'           => 'TEXT|COMMENT(ข้อมูลอื่นๆ)',
      'hidden_permission'     => 'TEXT',
      'setting_permission'    => 'TEXT',
      'module_addnew_user'    => 'TINYINT',
      'allow_login'           => 'TINYINT',
      'create_tab'            => 'TINYINT',
      'page_display'          => 'VARCHAR',

      'json_list_table'       => 'LONGTEXT',
      'json_tab_default'      => 'LONGTEXT',
      'json_tab_profile'      => 'LONGTEXT',
      'json_tab_addnew'       => 'LONGTEXT',
      'json_tab_account_info' => 'LONGTEXT',
      'json_tab_permission'   => 'LONGTEXT',
      'json_image_profile'    => 'LONGTEXT'
    ],
    'sys_log_error'                  => [
      'code'        => 'VARCHAR(10)',
      'topic'       => 'TEXT',
      'file'        => 'VARCHAR(255)',
      'line'        => 'INT',
      'class'       => 'VARCHAR(50)',
      'func'        => 'VARCHAR(50)',
      'link'        => 'VARCHAR(500)',
      'file_source' => 'TEXT',
      'description' => 'TEXT',
      'type'        => 'VARCHAR(20)'
    ],
    'sys_log_action'                 => [
      'class'       => 'VARCHAR(255)',
      'func'        => 'VARCHAR(255)',
      'log_level'   => 'TINYINT',
      'work_name'   => 'VARCHAR(255)',
      'work_type'   => 'VARCHAR(255)',
      'description' => 'VARCHAR(255)',
      'work_result' => 'VARCHAR(255)',
      'json_text'   => 'TEXT'
    ],
    'sys_api_key'                    => [
      'api_key'     => 'CHAR(32)',
      'site_name'   => 'VARCHAR(255)',
      'description' => 'VARCHAR(255)'
    ],
    'sys_dev'                        => [
      'username'                 => 'VARCHAR(255)|UNIQUE|notupdate',
      'password'                 => 'CHAR(32)|hash(md5)|min_len(1)',
      'role'                     => 'VARCHAR',
      'name'                     => 'VARCHAR',
      'full_name'                => 'VARCHAR',
      'surname'                  => 'VARCHAR',
      'user_type'                => 'VARCHAR',
      'is_ban'                   => 'TINYINT',
      'is_on_debug'              => 'TINYINT',
      'firebase_registration_id' => 'VARCHAR',
      'last_online_date_time'    => 'DATETIME'
    ],
    'sys_user_columns'               => [
      'name'           => 'VARCHAR',
      'category'       => 'VARCHAR',
      'type'           => 'VARCHAR',
      'db_column_name' => 'VARCHAR',
      'digit'          => 'INT',
      'decimal'        => 'INT',
      'remark'         => 'VARCHAR',
      'system_column'  => 'TINYINT',
      'json_data'      => 'LONGTEXT'
    ],
    'sys_user'                       => [
      'username'               => 'VARCHAR',
      'password'               => 'CHAR(32)',
      'user_code'              => 'VARCHAR',
      'user_type'              => 'VARCHAR',
      'permission'             => 'LONGTEXT',
      'is_ban'                 => 'TINYINT',
      'last_online_date_time'  => 'DATETIME',
      'password_datetime'      => 'DATETIME',
      'password_user_id'       => 'BIGINT',
      'password_username'      => 'VARCHAR',
      'full_name'              => 'TEXT',
      'discord_user_id'        => 'TEXT',
      'team_id'                => 'BIGINT',

      'employee_number'        => 'TEXT',
      'working_time_type_id'   => 'BIGINT',
      'working_time_type_name' => 'TEXT',
      'working_start_date'     => 'DATE',
      'branch_id'              => 'BIGINT',
      'branch_name'            => 'TEXT',
      'working_status_id'      => 'TEXT',
      'working_status_name'    => 'TEXT',
      'tel'                    => 'TEXT',
      'email'                  => 'TEXT',
      'social_facebook'        => 'TEXT',
      'social_line'            => 'TEXT',
      'social_ig'              => 'TEXT',
      'social_tiktok'          => 'TEXT',
      'bank_code'              => 'TEXT',
      'bank_name'              => 'TEXT',
      'bank_account_name'      => 'TEXT',
      'bank_account_number'    => 'TEXT',
      'is_can_login'           => 'TINYINT'
    ],
    'sys_user_social'                => [
      'user_id'   => 'BIGINT',
      'social'    => 'TEXT',
      'social_id' => 'TEXT'
    ],
    // 'sys_user_log'                   => [
    //   'admin_id'  => 'BIGINT|INDEX|notupdate',
    //   'user_id'   => 'BIGINT|INDEX|notupdate',
    //   'type'      => 'VARCHAR(255)',
    //   'status'    => 'VARCHAR(255)',
    //   'json_data' => 'TEXT'
    // ],
    // 'sys_user_type'                  => [
    //   'name'                         => 'VARCHAR(255)',
    //   'description'                  => 'VARCHAR(255)',

    //   'is_allow_login'               => 'TINYINT',
    //   'is_allow_program_login'       => 'TINYINT',
    //   'is_allow_app_login'           => 'TINYINT',
    //   'icon'                         => 'VARCHAR(255)',
    //   'indentity_field'              => 'VARCHAR(255)',
    //   'basic_info_percent'           => 'TINYINT',
    //   'is_format_user_code'          => 'TINYINT',
    //   'user_code_format_text1'       => 'VARCHAR(255)',
    //   'user_code_format_text2'       => 'VARCHAR(255)',
    //   'user_code_format_digit3'      => 'TINYINT',
    //   'is_use_pin_code'              => 'TINYINT',
    //   'pin_code_format_digit'        => 'TINYINT',
    //   'user_info_percent'            => 'TINYINT',
    //   'using_name_type'              => 'VARCHAR(255)',
    //   'is_has_profile_image'         => 'TINYINT',
    //   'is_tel'                       => 'TINYINT',
    //   'is_has_nick_name'             => 'TINYINT',
    //   'is_has_email'                 => 'TINYINT',
    //   'is_has_facebook'              => 'TINYINT',
    //   'is_has_line'                  => 'TINYINT',
    //   'is_has_wechat'                => 'TINYINT',
    //   'is_has_twitter'               => 'TINYINT',
    //   'using_address_type'           => 'VARCHAR(255)',
    //   'is_multiple_address'          => 'TINYINT',
    //   'is_address_province'          => 'TINYINT',
    //   'is_address_country'           => 'TINYINT',
    //   'is_address_zipcode'           => 'TINYINT',
    //   'is_has_bank_account'          => 'TINYINT',
    //   'using_bank_account_type'      => 'VARCHAR(255)',
    //   'is_multiple_bank_account'     => 'TINYINT',
    //   'is_has_tax_no'                => 'TINYINT',
    //   'is_has_id_card_no'            => 'TINYINT',
    //   'is_has_passport_no'           => 'TINYINT',
    //   'is_has_passport_country'      => 'TINYINT',
    //   'is_has_nationality'           => 'TINYINT',
    //   'is_has_religion'              => 'TINYINT',
    //   'is_has_gender'                => 'TINYINT',
    //   'is_has_birthdate'             => 'TINYINT',
    //   'is_has_birth_place'           => 'TINYINT',
    //   'is_has_leader'                => 'TINYINT',
    //   'is_has_subordinate'           => 'TINYINT',
    //   'is_has_career'                => 'TINYINT',
    //   'is_has_company'               => 'TINYINT',
    //   'document_percent'             => 'TINYINT',
    //   'is_doc_id_card'               => 'TINYINT',
    //   'is_doc_passport_cover'        => 'TINYINT',
    //   'is_doc_bank_account_cover'    => 'TINYINT',
    //   'is_doc_statement_image'       => 'TINYINT',
    //   // new User Type
    //   'is_topbar_bank_account'       => 'TINYINT',
    //   'is_topbar_verify_data'        => 'TINYINT',
    //   'is_topbar_working_detail'     => 'TINYINT',
    //   'is_topbar_contact_person'     => 'TINYINT',
    //   'is_has_instagram'             => 'TINYINT',
    //   'is_user_address'              => 'TINYINT|COMMENT(ปิด address ทั้งหมด)',
    //   'is_has_working_time'          => 'TINYINT',
    //   'is_has_working_shift'         => 'TINYINT',
    //   'is_has_default_salary'        => 'TINYINT',
    //   'is_has_department'            => 'TINYINT',
    //   'is_has_position'              => 'TINYINT',
    //   // new User Type2
    //   'is_user_address_type'         => 'TINYINT',
    //   'is_has_emergency_contact'     => 'TINYINT',
    //   'is_has_emergency_tel'         => 'TINYINT',
    //   'is_has_relationship'          => 'TINYINT',
    //   'is_has_employment_entry_date' => 'TINYINT',
    //   'is_verify_password'           => 'TINYINT',
    //   // V3
    //   'is_strict_password'           => 'TINYINT',
    //   'is_action_pin'                => 'TINYINT',
    //   'is_permission_template'       => 'TINYINT',
    //   'is_has_category'              => 'TINYINT',
    //   'is_has_team'                  => 'TINYINT',
    //   'is_has_tag'                   => 'TINYINT',
    //   'hidden_permission'            => 'LONGTEXT',
    //   'setting_permission'           => 'LONGTEXT'
    // ],
    'sys_user_notification'          => [
      'user_id'       => 'BIGINT|INDEX|notupdate',
      'from_user_id'  => 'BIGINT|INDEX|notupdate',
      'topic'         => 'VARCHAR(100)',
      'description'   => 'VARCHAR(400)',
      'code'          => 'VARCHAR(10)',
      'module'        => 'VARCHAR(200)',
      'type'          => 'VARCHAR(50)',
      'ref_id'        => 'BIGINT',
      'ref_info_json' => 'VARCHAR(1000)',
      'link'          => 'VARCHAR(1000)',
      'is_read'       => 'TINYINT',
      'is_show'       => 'TINYINT',
      'firebase'      => 'TINYINT'
    ],
    'sys_user_notification_firebase' => [
      'user_id'                  => 'BIGINT',
      'type'                     => 'VARCHAR(50)',
      'firebase_registration_id' => 'VARCHAR(1000)',
      'sound'                    => 'VARCHAR(255)',
      'android_channel_id'       => 'VARCHAR(255)'
    ],
    // 'sys_user_login_log'             => [
    //   'user_id'         => 'BIGINT',
    //   'ip_address'      => 'VARCHAR(50)',
    //   'site_name'       => 'VARCHAR(255)',
    //   'http_user_agent' => 'VARCHAR(500)',
    //   'history_type'    => 'VARCHAR(255)',
    //   'device_name'     => 'VARCHAR(255)',
    //   'location'        => 'VARCHAR(255)',
    //   'country'         => 'VARCHAR(255)',
    //   'browser'         => 'VARCHAR(255)',
    //   'remark'          => 'VARCHAR(255)',
    //   'json_input'      => 'TEXT'
    // ],
    'sys_user_log'                   => [
      'user_id'         => 'BIGINT',
      'input_username'  => 'TEXT',
      'input_password'  => 'TEXT',
      'ip_address'      => 'TEXT',
      'site_name'       => 'TEXT',
      'http_user_agent' => 'TEXT',
      'type'            => 'TEXT',
      'sub_type'        => 'TEXT',
      'json'            => 'TEXT'
    ],
    'sys_setting_user_team'          => [
      'user_type_id' => 'BIGINT|INDEX|notupdate',
      'name'         => 'VARCHAR(255)',
      'description'  => 'VARCHAR(255)',
      'user_count'   => 'INT'
    ],
    'sys_file'                       => [
      'code'      => 'VARCHAR(100)|noupdate|INDEX',
      'path'      => 'VARCHAR(500)',
      'file_name' => 'VARCHAR(500)',
      'file_type' => 'VARCHAR(100)',
      'file_size' => 'VARCHAR(100)',
      'extension' => 'VARCHAR(50)'
    ],
    'sys_language'                   => [
      'locale_name'       => 'VARCHAR(10)|UNIQUE|notupdate',
      'display_name'      => 'VARCHAR(255)',
      'is_system_default' => 'TINYINT'
    ],
    'sys_dbcheck_ignore_column'      => [
      'table_name'  => 'VARCHAR(255)',
      'column_name' => 'VARCHAR(255)'
    ],
    'sys_dbcheck_diagnose_log'       => [
      'datetime'    => 'DATETIME',
      'dev_id'      => 'BIGINT',
      'username'    => 'VARCHAR',
      'action'      => 'VARCHAR',
      'type'        => 'VARCHAR',
      'table_name'  => 'VARCHAR',
      'column_name' => 'VARCHAR',
      'column_type' => 'VARCHAR'
    ],
    'sys_extra_column'               => [
      'code'           => 'VARCHAR',
      'table_name'     => 'VARCHAR',
      'name'           => 'VARCHAR',
      'category'       => 'VARCHAR',
      'type'           => 'VARCHAR',
      'db_column_name' => 'VARCHAR',
      'digit'          => 'INT',
      'decimal'        => 'INT',
      'remark'         => 'VARCHAR',
      'system_column'  => 'TINYINT',
      'json_data'      => 'LONGTEXT'
    ],
    'sys_extra_template'             => [
      'sys_function_id' => 'BIGINT',
      'code'            => 'VARCHAR',
      'template'        => 'VARCHAR',
      'remark'          => 'VARCHAR',
      'meta_data'       => 'LONGTEXT'
    ]
  ];

  //// MODULE
  // module prefix
  const module_table_prefix = 'func_';
  // Module List ย้ายไป /config/config_module.php
}