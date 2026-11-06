<?php
  if (!isset($data)) {die();} // for security
?>
class Config {
  const version_main = 3;
  const version_sub = 1;
  const install_date_time = "<?=date('Y-m-d H:i:s');?>";
  const time_zone = "Asia/Bangkok";

  //// DB CONNECTION
  const db_type     = "mysql";
  const db_server   = "<?=isset($data['db_server']) ? $data['db_server'] : ''?>";
  const db_name     = "<?=isset($data['db_name']) ? $data['db_name'] : ''?>";
  const db_username = "<?=isset($data['db_username']) ? $data['db_username'] : ''?>";
  const db_password = "<?=isset($data['db_password']) ? $data['db_password'] : ''?>";
  const db_port     = <?=isset($data['db_port']) ? $data['db_port'] : 25060?>;

  //// MAIN - MASTER
  const allow_dev_login = true;
  const master_url     = "https://hq.wolves.bar/system/api/call.php";
  const master_api_key = "5981c6526ec5ae8de95309107a6b01f7";

  //// ADMIN
  const admin_id        = "<?=isset($data['admin_id']) ? $data['admin_id'] : ''?>";
  const admin_pw        = "<?=isset($data['admin_pw']) ? md5($data['admin_pw']) : 'you_cant_login'?>";
  const admin_user_type = "Guardian";

  /// SESSION
  const session_driver            = "<?=(isset($data['session_driver']) && ($data['session_driver'])) ? $data['session_driver'] : 'file'?>";
  const session_redis_url         = "<?=(isset($data['session_redis_url']) && ($data['session_redis_url'])) ? $data['session_redis_url'] : ''?>";
  const session_redis_port        = <?=(isset($data['session_redis_port']) && ($data['session_redis_port'])) ? $data['session_redis_port'] : 25061?>;
  const session_redis_password    = "<?=(isset($data['session_redis_password']) && ($data['session_redis_password'])) ? $data['session_redis_password'] : ''?>";
  const session_redis_expire_time = <?=(isset($data['session_redis_expire_time']) && ($data['session_redis_expire_time'])) ? $data['session_redis_expire_time'] : 3600?>;

  //// FIREBASE
  const firebaseAccessKey = "<?=(isset($data['firebaseAccessKey']) && ($data['firebaseAccessKey'])) ? $data['firebaseAccessKey'] : ''?>";

  // EMAIL SendGrid API KEY
  const sendGridapi_key = "<?=(isset($data['sendGridapi_key']) && ($data['sendGridapi_key'])) ? $data['sendGridapi_key'] : ''?>";
  const phpmailer_gmail_account = "<?=(isset($data['phpmailer_gmail_account']) && ($data['phpmailer_gmail_account'])) ? $data['phpmailer_gmail_account'] : ''?>";
  const phpmailer_gmail_password = "<?=(isset($data['phpmailer_gmail_password']) && ($data['phpmailer_gmail_password'])) ? $data['phpmailer_gmail_password'] : ''?>";

  // SMS
  // SMS 1st priority
  const sms_thaibulk_username = "<?=(isset($data['sms_thaibulk_username']) && ($data['sms_thaibulk_username'])) ? $data['sms_thaibulk_username'] : ''?>";
  const sms_thaibulk_password = "<?=(isset($data['sms_thaibulk_password']) && ($data['sms_thaibulk_password'])) ? $data['sms_thaibulk_password'] : ''?>";
  const sms_thaibulk_type     = "<?=(isset($data['sms_thaibulk_type']) && ($data['sms_thaibulk_type'])) ? $data['sms_thaibulk_type'] : 'standard'?>";
  // SMS 2nd priority
  const sms_bulksms_username = "<?=(isset($data['sms_bulksms_username']) && ($data['sms_bulksms_username'])) ? $data['sms_bulksms_username'] : ''?>";
  const sms_bulksms_password = "<?=(isset($data['sms_bulksms_password']) && ($data['sms_bulksms_password'])) ? $data['sms_bulksms_password'] : ''?>";
  // SMS 3rd priority
  const sms_twist_username = "<?=(isset($data['sms_twist_username']) && ($data['sms_twist_username'])) ? $data['sms_twist_username'] : ''?>";
  const sms_twist_password = "<?=(isset($data['sms_twist_password']) && ($data['sms_twist_password'])) ? $data['sms_twist_password'] : ''?>";

  // GOOGLE API - for Translate
  const google_api_key = "<?=(isset($data['google_api_key']) && ($data['google_api_key'])) ? $data['google_api_key'] : ''?>";

  // File - Save file Location
  const file_storage_type = 'local'; // local|oss|space
  const file_max_size = '50MB';
  const file_read_no_db   = false;
  const file_cache_timing   = 'update_date_time'; // raw(ไม่มี)|one_min(ทุกๆหนึ่งนาที)|one_hour|one_day|update_date_time(มีเป็นupdate_date_time)

  // File - The DigitalOcean Spaces API
  const space_bucket_name   = "<?=(isset($data['space_bucket_name']) && ($data['space_bucket_name'])) ? $data['space_bucket_name'] : ''?>";
  const space_access_key    = "<?=(isset($data['space_access_key']) && ($data['space_access_key'])) ? $data['space_access_key'] : ''?>";
  const space_access_secret = "<?=(isset($data['space_access_secret']) && ($data['space_access_secret'])) ? $data['space_access_secret'] : ''?>";
  const space_endpoint      = "<?=(isset($data['space_endpoint']) && ($data['space_endpoint'])) ? $data['space_endpoint'] : 'https://sgp1.digitaloceanspaces.com'?>";
  const space_region        = "<?=(isset($data['space_region']) && ($data['space_region'])) ? $data['space_region'] : 'us-east-1'?>";
  const space_root_folder   = "<?=(isset($data['space_root_folder']) && ($data['space_root_folder'])) ? $data['space_root_folder'] : ''?>"; // no tailing /
  const space_file_privacy  = "<?=(isset($data['space_file_privacy']) && ($data['space_file_privacy'])) ? $data['space_file_privacy'] : 'public'?>"; // public|private
  const space_url_expires   = <?=(isset($data['space_url_expires']) && ($data['space_url_expires'])) ? $data['space_url_expires'] : 3600?>;
  // File - AliCloud - Object Storage Service
  const oss_access_key    = "<?=(isset($data['oss_access_key']) && ($data['oss_access_key'])) ? $data['oss_access_key'] : ''?>";
  const oss_access_secret = "<?=(isset($data['oss_access_secret']) && ($data['oss_access_secret'])) ? $data['oss_access_secret'] : ''?>";
  const oss_endpoint      = "<?=(isset($data['oss_endpoint']) && ($data['oss_endpoint'])) ? $data['oss_endpoint'] : ''?>";
  const oss_bucket_name   = "<?=(isset($data['oss_bucket_name']) && ($data['oss_bucket_name'])) ? $data['oss_bucket_name'] : ''?>";
  const oss_url_expires   = <?=(isset($data['oss_url_expires']) && ($data['oss_url_expires'])) ? $data['oss_url_expires'] : 3600?>;
  const oss_folder        = "<?=(isset($data['oss_folder']) && ($data['oss_folder'])) ? $data['oss_folder'] : 'files/'?>";

  // ACTION LOG  (lv > fn)
  const log_max_count = 20;
  const log_level     = 5; /* Only >= ThanThisNumber will LOG, 1.Common - 5.Critical */
  const log_functions = [
    'class_name' => ['loging_method1', 'loging_method2', 'loging_method3']
  ];

  // for DB Backup
  const customer_name = "customer_name";

}