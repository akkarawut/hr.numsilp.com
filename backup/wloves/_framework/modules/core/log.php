<?php
// Fetch all log
class W_Log
{
  public static $log_list = [];

  static function init()
  {
    foreach (static::$log_list as $log_key => $log_data) {
      $color_status = 'green-status';
      $color = '#31ca48';
      $status       = 'เยี่ยม !';
      if (0 < $log_data['count'] && $log_data['count'] < 50) {
        $color_status = 'yellow-status';
        $color = '#ffbb39';
        $status       = 'เช็คด่วน';
      } else if (50 <= $log_data['count']) {
        $color_status = 'red-status';
        $color = '#fe635b';
        $status       = 'ชิบหาย';
      }

      static::$log_list[$log_key]['id']           = $log_data['type'];
      static::$log_list[$log_key]['color_status'] = $color_status;
      static::$log_list[$log_key]['status']       = $status;
      static::$log_list[$log_key]['color'] = $color;
      static::$log_list[$log_key]['icon']         = 'structure/image/icon/general/icon-log.svg';
    }
  }
}
