<?php
class Config {
  const version_main = 3;
  const version_sub = 1;
  const install_date_time = "2023-11-28 16:15:55";
  const time_zone = "Asia/Bangkok";

  //// DB CONNECTION
  const db_type     = "mysql";
  const db_server   = "localhost";
  const db_name     = "numsilp_hr";
  const db_username = "numsilp_hr";
  const db_password = "zz2qugpgTZRU";
  const db_port     = 3306;

  //// MAIN - MASTER
  const allow_dev_login = true;
  const master_url     = "https://hq.wolves.bar/system/api/call.php";
  const master_api_key = "5981c6526ec5ae8de95309107a6b01f7";

  //// ADMIN
  const admin_id        = "";
  const admin_pw        = "you_cant_login";
  const admin_user_type = "Guardian";

  /// SESSION
  const session_driver            = "file";
  const session_redis_url         = "";
  const session_redis_port        = 25061;
  const session_redis_password    = "";
  const session_redis_expire_time = 3600;

  //// FIREBASE
  const firebaseAccessKey = "";

  // EMAIL SendGrid API KEY
  const sendGridapi_key = "";
  const phpmailer_gmail_account = "";
  const phpmailer_gmail_password = "";

  // SMS
  // SMS 1st priority
  const sms_thaibulk_username = "";
  const sms_thaibulk_password = "";
  const sms_thaibulk_type     = "standard";
  // SMS 2nd priority
  const sms_bulksms_username = "";
  const sms_bulksms_password = "";
  // SMS 3rd priority
  const sms_twist_username = "";
  const sms_twist_password = "";

  // GOOGLE API - for Translate
  const google_api_key = "";

  // File - Save file Location
  const file_storage_type = 'local'; // local|oss|space
  const file_max_size = '50MB';
  const file_read_no_db   = false;
  const file_cache_timing   = 'update_date_time'; // raw(ไม่มี)|one_min(ทุกๆหนึ่งนาที)|one_hour|one_day|update_date_time(มีเป็นupdate_date_time)

  // File - The DigitalOcean Spaces API
  const space_bucket_name   = "";
  const space_access_key    = "";
  const space_access_secret = "";
  const space_endpoint      = "https://sgp1.digitaloceanspaces.com";
  const space_region        = "us-east-1";
  const space_root_folder   = ""; // no tailing /
  const space_file_privacy  = "public"; // public|private
  const space_url_expires   = 3600;
  // File - AliCloud - Object Storage Service
  const oss_access_key    = "";
  const oss_access_secret = "";
  const oss_endpoint      = "";
  const oss_bucket_name   = "";
  const oss_url_expires   = 3600;
  const oss_folder        = "files/";

  // ACTION LOG  (lv > fn)
  const log_max_count = 20;
  const log_level     = 5; /* Only >= ThanThisNumber will LOG, 1.Common - 5.Critical */
  const log_functions = [
    'class_name' => ['loging_method1', 'loging_method2', 'loging_method3']
  ];

  // for DB Backup
  const customer_name = "customer_name";
}
