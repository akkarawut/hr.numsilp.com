<?php
class W_Main
{
  const DEFAULT_LANGUAGE = 'th';
  const SESSION_CODE     = 'wloves';
  const NULL             = 'SYSTEM_NULL_VALUE';

  private static $lang_dict = [];
  public static $debug      = [
    'data' => []
  ];

  /**
  | Group | Load Assets
  | Description | โหลด css, js
   */

  public static function assetPath($file_path)
  {
    if (file_exists($file_path)) {
      $filetime = filemtime($file_path);
      return $file_path . '?t=' . $filetime;
    } else {
      return $file_path;
    }
  }

  public static function loadAsset($file_path, $js_type = '')
  {
    // print_r(self::assetPath($file_path));
    // die;
    // $file_path = '/' . $file_path;


    // print_r(file_exists($file_path) ? 'true' : 'false');
    // die;
    if (file_exists($file_path)) {
      $type        = pathinfo($file_path, PATHINFO_EXTENSION);
      $file_exists = true;
    } else {
      $type        = '';
      $file_exists = false;
    }
    if (!$type) {
      $strexplode       = explode(".", $file_path);
      $strexplode_count = count($strexplode);
      $type             = $strexplode[$strexplode_count - 1];
    }

    $file_path = self::assetPath($file_path);

    if ($file_exists) {
      if ($type == 'js') {
        if ($js_type == 'module') {
          echo '<script type="module" src="' . $file_path . '"></script>';
          return;
        } else {
          echo '<script src="' . $file_path . '"></script>';
          return;
        }
      } else if ($type == 'css') {
        echo '<link rel="stylesheet" href="' . $file_path . '">';
        return;
      } else {
        echo 'Invalid file type. [' . $file_path . ']';
        return;
      }
    } else {
      echo $file_path . ' incorrect file path or file does not exists!';
      return;
    }
  }

  /**
  | Group | Log
  | Description | โชว์ ข้อมูล แบบ สวยๆ ยั่วๆ จ้า
   */

  public static function display($data)
  {
    if ($_SERVER['REMOTE_ADDR'] == 'localhost' || $_SERVER['REMOTE_ADDR'] == '127.0.0.1' || $_SERVER['REMOTE_ADDR'] == '::1' || $_SERVER['REMOTE_ADDR'] == '172.17.0.1') {
      echo '<pre>';
      print_r($data);
      echo '</pre>';
    }
  }

  public static function console($data)
  {
    echo '<script>
            console.log(' . json_encode($data) . ');
          </script>';
  }

  public static function consoleTable($data)
  {
    echo '<script>
            console.table(' . json_encode($data) . ');
          </script>';
  }

  /**
  | Group | URL
  | Description | จัดการเกี่ยวกับ URL
   */

  // Redirect
  public static function redirect($url = '', $delay = 0)
  {
    if (!is_null($url)) {
      if (trim($url) == '') {
        $url = CURRENT_URL;
      }
      header("Location: " . $url);
      // echo '<meta http-equiv="refresh" content="'.$delay.'; url='.$url.'" />';
    }
    exit();
  }

  public static function asyncRedirect($url = '')
  {
    if (trim($url) == '') {
      echo '<script>';
      echo 'document.body.classList.add("hidden");';
      echo 'window.location.reload();';
      echo '</script>';
    } else {
      echo '<script>';
      echo 'document.body.classList.add("hidden");';
      echo 'window.location.href = "' . $url . '";';
      echo '</script>';
    }
    exit();
  }

  public static function assignURL($url, $params = [])
  {
    $parts = parse_url($url);
    if (isset($parts['query'])) {
      parse_str($parts['query'], $query);
    } else {
      $query = [];
    }

    foreach ($params as $key => $value) {
      $query[$key] = $value;
    }

    // build query string
    $query_string = '';
    $isFirst      = true;
    foreach ($query as $key => $value) {
      if ($isFirst) {
        $query_string .= '?' . $key . '=' . $value;
        $isFirst = false;
      } else {
        $query_string .= '&' . $key . '=' . $value;
      }
    }
    $parts['query'] = $query_string;

    $url = $parts['scheme'] . '://' . $parts['host'] . $parts['path'] . $parts['query'];
    return $url;
  }

  public static function queryStringToInput($url, $ignore = [], $type = 'hidden')
  {
    $html  = '';
    $parts = parse_url($url);
    if (isset($parts['query'])) {
      parse_str($parts['query'], $query);
    } else {
      $query = [];
    }
    // build form input
    foreach ($query as $key => $value) {
      if (!in_array($key, $ignore)) {
        $html .= '<input type="' . $type . '" name="' . $key . '" value="' . $value . '">';
      }
    }
    return $html;
  }

  public static function arrayToInput($data = [], $type = 'hidden')
  {
    $html = '';
    // build form input
    foreach ($data as $key => $value) {
      $html .= '<input type="' . $type . '" name="' . $key . '" value="' . $value . '">';
    }
    return $html;
  }

  public static function createPagination($current, $total, $ignore = [], $param = 'p', $max_button = 3)
  {
    array_push($ignore, $param);
    $start = 1;
    $end   = $total;
    if ($total > $max_button + 2) {
      if ($current > 3) {
        $start = $current - 1;
        if ($current > $total - $max_button) {
          $start = $total - $max_button;
        }
      } else if ($current > 2) {
        $start = $current - 2;
      }

      if ($start + $max_button < $total) {
        if ($current < $max_button + 1) {
          $end = $start + $max_button;
        } else {
          $end = $start + $max_button - 1;
        }
      }
    }
    $html = '';
    echo '<nav class="z-pagination"><ul class="pagination">';
    if ($start > 2 && $total > $max_button + 2) {
      echo '<li class="page-item">
			<a class="page-link" href="' . self::assignURL(CURRENT_URL, [$param => 1]) . '">
			1
			</a>
			</li>';
      echo '<li class="page-item disabled"><a class="page-link">...</a></li>';
    }

    for ($i = $start; $i <= $end; $i++) {
      if ($current == $i) {
        if ($total > $max_button + 2) {
          echo '<li class="page-item active">
					<span class="dropup">
					<button class="dropdown-toggle" type="button" data-toggle="dropdown">' . $i . '</button>
					<div class="dropdown-menu scrollable-menu dropdown-menu-right">';

          if ($total > 50) {
            echo '<div class="dropdown-search">
						<form method="GET" action="" name="noconfirm">
						<div class="input-group">
						<input type="number" class="form-control" name="' . $param . '" placeholder="Page Number" value="' . $current . '" min="1" max="' . $total . '" required>
						<div class="input-group-btn">
						<button type="submit" class="btn">
						<i class="fa fa-search"></i>
						</button>
						</div>
						</div>';
            echo self::queryStringToInput(CURRENT_URL, $ignore);
            echo '</form>
						</div>';
          }

          for ($j = 1; $j <= $total; $j++) {
            echo '<a class="dropdown-item" href="' . self::assignURL(CURRENT_URL, [$param => $j]) . '">' . $j . '</a>';
          }
          echo '</div></span></li>';
        } else {
          echo '<li class="page-item active"><a class="page-link" href="' . self::assignURL(CURRENT_URL, [$param => $i]) . '">' . $i . '</a></li>';
        }
      } else {
        echo '<li class="page-item"><a class="page-link" href="' . self::assignURL(CURRENT_URL, [$param => $i]) . '">' . $i . '</a></li>';
      }
    }

    if ($end < $total) {
      echo '<li class="page-item disabled"><a class="page-link">...</a></li>';
      echo '<li class="page-item">
			<a class="page-link" href="' . self::assignURL(CURRENT_URL, [$param => $total]) . '">
			' . $total . '
			</a>
			</li>';
    }

    echo '</ul></nav>';
    return $html;
  }

  public static function createPaginationAsync($current, $total, $async_target, $data = [], $param = 'p')
  {
    $max_button = 3;
    $start      = 1;
    $end        = $total;
    if ($total > $max_button + 2) {
      if ($current > 3) {
        $start = $current - 1;
        if ($current > $total - $max_button) {
          $start = $total - $max_button;
        }
      } else if ($current > 2) {
        $start = $current - 2;
      }

      if ($start + $max_button < $total) {
        if ($current < $max_button + 1) {
          $end = $start + $max_button;
        } else {
          $end = $start + $max_button - 1;
        }
      }
    }
    $html = '';
    echo '<nav class="z-pagination"><ul class="pagination pagination-async" data-async-content="' . $async_target . '">';
    if ($start > 2 && $total > $max_button + 2) {
      echo '<li class="page-item">
			<a class="page-link aspg-link" data-param="' . $param . '" href="1">1</a>
			</li>';
      echo '<li class="page-item disabled"><a class="page-link">...</a></li>';
    }

    for ($i = $start; $i <= $end; $i++) {
      if ($current == $i) {
        if ($total > $max_button + 2) {
          echo '<li class="page-item active">
					<span class="dropup">
					<button class="dropdown-toggle" type="button" data-toggle="dropdown">' . $i . '</button>
					<div class="dropdown-menu scrollable-menu dropdown-menu-right">';

          if ($total > 50) {
            echo '<div class="dropdown-search">
						<form class="async-pagination-form" method="POST" action="" data-async-content="' . $async_target . '" data-param="' . $param . '">
						<div class="input-group">
						<input type="number" class="form-control" name="' . $param . '" placeholder="Page Number" value="' . $current . '" min="1" max="' . $total . '" required>
						<div class="input-group-btn">
						<button type="submit" class="btn">
						<i class="fa fa-search"></i>
						</button>
						</div>
						</div>';
            echo self::arrayToInput($data);
            echo '</form>
						</div>';
          }

          for ($j = 1; $j <= $total; $j++) {
            echo '<a class="dropdown-item aspg-link" data-param="' . $param . '" href="' . $j . '">' . $j . '</a>';
          }
          echo '</div></span></li>';
        } else {
          echo '<li class="page-item active"><a class="page-link aspg-link" data-param="' . $param . '" href="' . $i . '">' . $i . '</a></li>';
        }
      } else {
        echo '<li class="page-item"><a class="page-link aspg-link" data-param="' . $param . '" href="' . $i . '">' . $i . '</a></li>';
      }
    }

    if ($end < $total) {
      echo '<li class="page-item disabled"><a class="page-link">...</a></li>';
      echo '<li class="page-item">
			<a class="page-link aspg-link" data-param="' . $param . '" href="' . $total . '">' . $total . '</a></li>';
    }

    echo '</ul></nav>';
    return $html;
  }

  /**
  | Group | Transform Data
  | Description | แปลงข้อมูล Object, Array
   */

  public static function transformObject($arr, $key = 'id')
  {
    $ret = [];

    if (!is_null($key)) {
      for ($i = 0; $i < count($arr); $i++) {
        $ret[$arr[$i]->$key] = $arr[$i];
      }
    } else {
      // convert index array to assoc array
      for ($i = 0; $i < count($arr); $i++) {
        $ret[$arr[$i]] = true;
      }
    }
    return $ret;
  }

  public static function transformArray($arr, $key = 'id')
  {
    $ret = [];

    if (!is_null($key)) {
      for ($i = 0; $i < count($arr); $i++) {
        $ret[$arr[$i][$key]] = $arr[$i];
      }
    } else {
      // convert index array to assoc array
      for ($i = 0; $i < count($arr); $i++) {
        $ret[$arr[$i]] = true;
      }
    }
    return $ret;
  }

  public static function groupArray($arr, $key = 'id')
  {
    $ret = [];
    if (!is_null($key)) {
      for ($i = 0; $i < count($arr); $i++) {
        if (!isset($ret[$arr[$i]->$key])) {
          $ret[$arr[$i]->$key] = [];
        }
        array_push($ret[$arr[$i]->$key], $arr[$i]);
      }
    }
    return $ret;
  }

  public static function registerModel($name, $data)
  {
    echo '<script>';
    echo 'if (typeof($_MODEL) == \'undefined\') { var $_MODEL = {}; }';
    echo '$_MODEL[\'' . $name . '\'] = ' . json_encode($data) . ';';
    echo '</script>';
  }

  static function arraySortByColumn(&$arr, $col, $dir = SORT_ASC)
  {
    $sort_col = [];
    foreach ($arr as $key => $row) {
      $sort_col[$key] = $row[$col];
    }
    array_multisort($sort_col, $dir, $arr);

    return $arr;
  }

  // Date
  /**
  | Group | Date
  | Description | จัดการข้อมูล Date, Time
   */
  public static function formatDate($datetime, $format = 'd/m/Y, H:i', $thai_format = false, $year_th = true)
  {
    $result = '';
    if ($datetime === '0000-00-00 00:00:00') {
      $result = '';
    } else {
      $date = new DateTime($datetime);
      if ($thai_format) {
        if ($year_th) {
          $date->add(new DateInterval("P543Y"));
        }
        if (strpos($format, 'm') !== false) {
          $result = str_replace('m', self::formatMonthNameTH($date->format('m'), false), $format);
        } else if (strpos($format, 'M') !== false) {
          $result = str_replace('M', self::formatMonthNameTH($date->format('m'), true), $format);
        }
        $result = $date->format($result);
      } else {
        $result = $date->format($format);
      }
    }

    return $result;
  }

  static function formatDateDiff($date)
  {
    if ($date == "0000-00-00 00:00:00") {
      return 'Never';
    }

    $timestamp = strtotime($date);

    return "<small data-toggle='tooltip' data-placement='top' title='" . date("d/m/Y H:i:s", $timestamp) . "' data-livestamp='$timestamp'></small>";
  }

  static function formatMonthName($month_index, $full_name = true)
  {
    $months       = ["", "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
    $month_shorts = ["", "Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];

    // convert to number
    $month_index *= 1;

    $month_list = $full_name ? $months : $month_shorts;

    return isset($month_list[$month_index]) ? $month_list[$month_index] : "";
  }

  static function formatMonthNameTH($month_index, $full_name = true)
  {
    $months       = ["", "มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน", "กรกฎาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม"];
    $month_shorts = ['', 'ม.ค.', 'ก.พ.', 'มี.ค.', 'เม.ย.', 'พ.ค.', 'มิ.ย.', 'ก.ค.', 'ส.ค.', 'ก.ย.', 'ต.ค.', 'พ.ย.', 'ธ.ค.'];

    // convert to number
    $month_index *= 1;

    $month_list = $full_name ? $months : $month_shorts;

    return isset($month_list[$month_index]) ? $month_list[$month_index] : "";
  }

  static function formatFullDateRange($year, $month = false)
  {
    if (!$month) {
      $month_start = 1;
      $month_end   = 12;
    } else {
      $month_start = $month;
      $month_end   = $month;
    }

    $start_date = strtotime('first day of ' . self::formatMonthName($month_start) . ' ' . $year);
    $end_date   = strtotime('last day of ' . self::formatMonthName($month_end) . ' ' . $year);

    $start_date = date('Y-m-d 00:00:00', $start_date);
    $end_date   = date('Y-m-d 23:59:59', $end_date);

    return [$start_date, $end_date];
  }

  static function formatDateRange($from_date, $str_range, $with_time = true)
  {
    $format_from_date = 'Y-m-d 00:00:00';
    $format_end_date  = 'Y-m-d 23:59:59';

    $from_date = strtotime($from_date);
    $end_date  = strtotime($str_range, $from_date);

    if ($with_time) {
      $end_date = strtotime('-1 day', $end_date);
    } else {
      $format_from_date = 'Y-m-d';
      $format_end_date  = 'Y-m-d';
    }

    $from_date = date($format_from_date, $from_date);
    $end_date  = date($format_end_date, $end_date);

    return [$from_date, $end_date];
  }

  public static function dateAdd($datetime, $interval, $format = 'Y-m-d H:i:s')
  {
    $date = new DateTime($datetime);
    $date->add(new DateInterval($interval));
    return $date->format($format);
  }

  public static function dateDiff($datetime1, $datetime2)
  {
    $date1 = new DateTime($datetime1);
    $date2 = new DateTime($datetime2);
    $diff  = $date1->diff($date2);
    $days  = $diff->days;
    if ($diff->invert == 1) {
      $days = -1 * $days;
    }
    return $days;
  }

  static function formatDayShortToFull($short_name)
  {
    $days = [
      'mon' => 'monday',
      'tue' => 'tuesday',
      'wed' => 'wednesday',
      'thu' => 'thursday',
      'fri' => 'friday',
      'sat' => 'saturday',
      'sun' => 'sunday'
    ];

    $short_name = strtolower($short_name);

    return isset($days[$short_name]) ? $days[$short_name] : '';
  }

  static function formatDayTH($day)
  {
    $days = [
      'monday'    => 'จันทร์',
      'tuesday'   => 'อังคาร',
      'wednesday' => 'พุธ',
      'thursday'  => 'พฤหัสบดี',
      'friday'    => 'ศุกร์',
      'saturday'  => 'เสาร์',
      'sunday'    => 'อาทิตย์'
    ];

    if (strlen($day) == 3) {
      $day = self::formatDayShortToFull($day);
    }

    $day = strtolower($day);

    return isset($days[$day]) ? $days[$day] : '';
  }

  public static function timeElapsedString($datetime, $full = false)
  {
    $now  = new DateTime();
    $ago  = new DateTime($datetime);
    $diff = $now->diff($ago);

    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;

    $string = [
      'y' => 'ปี',
      'm' => 'เดือน',
      'w' => 'สัปดาห์',
      'd' => 'วัน',
      'h' => 'ชั่วโมง',
      'i' => 'นาที',
      's' => 'วินาที'
    ];
    foreach ($string as $k => &$v) {
      if ($diff->$k) {
        $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? '' : '');
      } else {
        unset($string[$k]);
      }
    }

    if (!$full) {
      $string = array_slice($string, 0, 1);
    }

    return $string ? implode(', ', $string) . 'ที่ผ่านมา' : 'ตอนนี้';
  }

  public static function dayofweek($datetime)
  {
    $date = new DateTime($datetime);
    return $date->format('w');
  }

  public static function dayofweekThai($datetime)
  {
    $dayname = ['อาทิตย์', 'จันทร์', 'อังคาร', 'พุธ', 'พฤหัส', 'ศุกร์', 'เสาร์'];
    $n       = self::dayofweek($datetime);
    return $dayname[$n];
  }

  public static function convertDateTH($date, $time = false)
  {
    $thaimonth = ['', 'มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน', 'กรกฎาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม'];
    if ($time === true) {
      $date              = self::formatDate($date, 'Y-m-d H:i:s');
      list($date, $time) = explode(' ', $date);
      $date              = explode('-', $date);
      $time              = ' ' . $time;
    } else if ($time !== false) {
      $date              = self::formatDate($date, 'Y-m-d');
      list($date, $time) = explode(' ', $date);
      $date              = explode('-', $date);
      $time              = ' ' . $time;
    } else {
      $date = self::formatDate($date, 'Y-m-d');
      $date = explode('-', $date);
    }

    $day   = 1 * $date[2];
    $month = $thaimonth[1 * $date[1]];
    $year  = (1 * $date[0]) + 543;

    return $day . ' ' . $month . ' ' . $year . $time;
  }

  public static function convertDateTHShort($date, $time = false)
  {
    $thaimonth = ['', 'ม.ค.', 'ก.พ.', 'มี.ค.', 'เม.ย.', 'พ.ค.', 'มิ.ย.', 'ก.ค.', 'ส.ค.', 'ก.ย.', 'ต.ค.', 'พ.ย.', 'ธ.ค.'];
    if ($time === true) {
      $date              = self::formatDate($date, 'Y-m-d H:i:s');
      list($date, $time) = explode(' ', $date);
      $date              = explode('-', $date);
      $time              = ' ' . $time;
    } else if ($time !== false) {
      $date              = self::formatDate($date, 'Y-m-d');
      list($date, $time) = explode(' ', $date);
      $date              = explode('-', $date);
      $time              = ' ' . $time;
    } else {
      $date = self::formatDate($date, 'Y-m-d');
      $date = explode('-', $date);
    }

    $day   = 1 * $date[2];
    $month = $thaimonth[1 * $date[1]];
    $year  = (1 * $date[0]) + 543;

    return $day . ' ' . $month . ' ' . $year . $time;
  }

  public static function dayDiff($start_date, $end_date)
  {
    $start_date = date_create($start_date);
    $end_date   = date_create($end_date);
    $diff       = self::dateDiff($start_date, $end_date, false);
    $nDay       = $diff->format('%r%a');
    return $nDay;
  }

  public static function dateDiffMinutes($start_date, $end_date)
  {
    // Declare and define two dates
    $date1 = strtotime($start_date);
    $date2 = strtotime($end_date);

    // Formulate the Difference between two dates
    $diff = abs($date2 - $date1);

    $years = floor($diff / (365 * 60 * 60 * 24));

    $months = floor(($diff - $years * 365 * 60 * 60 * 24)
      / (30 * 60 * 60 * 24));

    $days = floor(($diff - $years * 365 * 60 * 60 * 24 -
      $months * 30 * 60 * 60 * 24) / (60 * 60 * 24));

    $hours = floor(($diff - $years * 365 * 60 * 60 * 24
      - $months * 30 * 60 * 60 * 24 - $days * 60 * 60 * 24)
      / (60 * 60));

    $minutes = floor(($diff - $years * 365 * 60 * 60 * 24
      - $months * 30 * 60 * 60 * 24 - $days * 60 * 60 * 24
      - $hours * 60 * 60) / 60);

    $seconds = floor(($diff - $years * 365 * 60 * 60 * 24
      - $months * 30 * 60 * 60 * 24 - $days * 60 * 60 * 24
      - $hours * 60 * 60 - $minutes * 60));

    $cal_min = 0;
    $cal_min += $days * 24 * 60;
    $cal_min += $hours * 60;
    $cal_min += $minutes;
    $cal_min += ($seconds >= 1) ? 1 : 0;

    return $cal_min;
  }

  public static function isBetweenDatetime($input, $dt1, $dt2)
  {
    if (date('Y-m-d H:i:s', strtotime($dt1)) <= date('Y-m-d H:i:s', strtotime($input)) && date('Y-m-d H:i:s', strtotime($dt2)) >= date('Y-m-d H:i:s', strtotime($input))) {
      return 1;
    } else {
      return 0;
    }
  }

  public static function dateDifference($date_1, $date_2, $differenceFormat = '%a')
  {
    $datetime1 = date_create($date_1);
    $datetime2 = date_create($date_2);
    $interval  = date_diff($datetime1, $datetime2);
    return $interval->format($differenceFormat);
  }

  public static function DayInRange($start_date, $end_date)
  {
    $return = [];

    if (!$start_date || !$end_date) {
      return false;
    }
    if ($start_date > $end_date) {
      return false;
    }

    $begin    = new DateTime($start_date . ' 00:00:00');
    $end      = new DateTime($end_date . ' 23:59:59');
    $interval = DateInterval::createFromDateString('1 day');
    $period   = new DatePeriod($begin, $interval, $end);

    foreach ($period as $dt) {
      array_push($return, $dt->format("Y-m-d"));
    }

    return $return;
  }

  /**
  | Group | Limit String
  | Description | จำกัดการแสดงผลตัวหนังสือ
   */
  public static function limitString($string, $length = 50, $tooltip = false)
  {
    $original_string = $string;
    if (mb_strlen($string) > $length) {
      $string = mb_substr($string, 0, $length) . '...';
      if ($tooltip) {
        $string = '<span title="' . $original_string . '" data-toggle="tooltip">' . $string . '</span>';
      } else {
        $string = '<span title="' . $original_string . '">' . $string . '</span>';
      }
    }
    return $string;
  }

  /**
  | Group | array & object
  | Description | array & object
   */
  static function entityToString($entity)
  {
    $description = "";

    if (is_array($entity)) {
      foreach ($entity as $key => $value) {
        if (is_array($value) || is_object($value)) {
          $description .= '<b>' . $key . '</b> : (' . gettype($value) . ') <span class="text-muted">' . ((!is_array($value)) ? $value : '[array_data]') . '</span><br />';
          foreach ($value as $valuekey1 => $valuedata1) {
            $description .= '&nbsp;&nbsp;<b>' . $valuekey1 . '</b> : (' . gettype($valuedata1) . ') <span class="text-muted">' . ((!is_array($valuedata1)) ? $valuedata1 : '[array_data]') . '</span><br />';

            if (is_array($valuedata1) || is_object($valuedata1)) {
              foreach ($valuedata1 as $valuekey2 => $valuedata2) {
                $description .= '&nbsp;&nbsp;&nbsp;&nbsp;<b>' . $valuekey2 . '</b> : (' . gettype($valuedata2) . ') <span class="text-muted">' . ((!is_array($valuedata2)) ? $valuedata2 : '[array_data]') . '</span><br />';

                if (is_array($valuedata2) || is_object($valuedata2)) {
                  foreach ($valuedata2 as $valuekey3 => $valuedata3) {
                    $description .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>' . $valuekey3 . '</b> : (' . gettype($valuedata3) . ') <span class="text-muted">' . ((!is_array($valuedata3)) ? $valuedata3 : '[array_data]') . '</span><br />';

                    if (is_array($valuedata3) || is_object($valuedata3)) {
                      foreach ($valuedata3 as $valuekey4 => $valuedata4) {
                        $description .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>' . $valuekey4 . '</b> : (' . gettype($valuedata4) . ') <span class="text-muted">' . ((!is_array($valuedata4)) ? $valuedata4 : '[array_data]') . '</span><br />';
                      }
                    }
                  }
                }
              }
            }
          }
        } else {
          $description .= '<b>' . $key . '</b> : (' . gettype($value) . ') <span class="text-muted">' . $value . '</span><br />';
        }
      }
    }

    return $description;
  }

  /**
  | Group | Check
  | Description | เช็คว่าเป็น Json รึเปล่า
   */
  static function isJson($anonymous_string)
  {
    if (!is_string($anonymous_string)) {
      return false;
    }

    return true;
  }

  public static function href($next_get, $i_class = 'fas fa-chevron-right', $btn_active_class = 'btn btn-info border btn-xs', $btn_standby_class = 'btn btn-light border btn-xs')
  {
    global $_GET;
    if (is_array($next_get)) {
      $next = '';
      foreach ($next_get as $key => $value) {
        $next .= "&" . $key . "=" . $value;
      }

      if (isset($_GET[array_keys($next_get)[count($next_get) - 1]])) {
        if ($_GET[array_keys($next_get)[count($next_get) - 1]] == $next_get[array_keys($next_get)[count($next_get) - 1]] || urlencode($_GET[array_keys($next_get)[count($next_get) - 1]]) == $next_get[array_keys($next_get)[count($next_get) - 1]]) {
          $btn_class = $btn_active_class;
        } else {
          $btn_class = $btn_standby_class;
        }
      } else {
        $btn_class = $btn_standby_class;
      }

      return "<a href='" . basename($_SERVER['PHP_SELF']) . "?c=" . $_GET['c'] . $next . "' class='" . $btn_class . "'><i class='" . $i_class . "'></i></a>";
    }
    return null;
  }

  /**
  | Group | Check
  | Description | ดึง IP ของผู้ใช้งานปัจจุบัน
   */
  static function getClientIP()
  {
    $ipaddress = '';
    if (isset($_SERVER['HTTP_CLIENT_IP'])) {
      $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
    } else if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
      $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else if (isset($_SERVER['HTTP_X_FORWARDED'])) {
      $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
    } else if (isset($_SERVER['HTTP_FORWARDED_FOR'])) {
      $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
    } else if (isset($_SERVER['HTTP_FORWARDED'])) {
      $ipaddress = $_SERVER['HTTP_FORWARDED'];
    } else if (isset($_SERVER['REMOTE_ADDR'])) {
      $ipaddress = $_SERVER['REMOTE_ADDR'];
    } else {
      $ipaddress = 'UNKNOWN';
    }

    return $ipaddress;
  }

  public static function hasValue($value, $default_result = '')
  {
    if (!$value) {
      return $default_result;
    }

    return $value;
  }

  //////////////////////////////////////////////////////////////////////

  // Session
  public static function session($name, $data = 'SYSTEM_NULL_VALUE')
  {
    if ($data !== 'SYSTEM_NULL_VALUE') {
      $_SESSION[self::SESSION_CODE . '_' . $name] = $data;
    }
    if (isset($_SESSION[self::SESSION_CODE . '_' . $name])) {
      return $_SESSION[self::SESSION_CODE . '_' . $name];
    } else {
      return null;
    }
  }

  public static function removeSession($name)
  {
    if (self::session($name)) {
      unset($_SESSION[self::SESSION_CODE . '_' . $name]);
    }
  }

  // Cookie
  public static function cookie($name, $data = 'SYSTEM_NULL_VALUE', $minute = 525600)
  {
    $minute = $minute * 60;
    if ($data !== self::NULL) {
      $data = json_encode($data);
      setcookie(self::SESSION_CODE . '_' . $name, $data, time() + $minute, "/");
      // accessing cookie immediately after setcookie
      $_COOKIE[self::SESSION_CODE . '_' . $name] = $data;
    }
    if (isset($_COOKIE[self::SESSION_CODE . '_' . $name])) {
      $data = json_decode($_COOKIE[self::SESSION_CODE . '_' . $name]);
      return $data;
    } else {
      return null;
    }
  }

  public static function removeCookie($name)
  {
    if (self::cookie($name)) {
      setcookie(self::SESSION_CODE . '_' . $name, null, time() - 3600, "/");
      $_COOKIE[self::SESSION_CODE . '_' . $name] = null;
    }
  }

  // Notification
  public static function notification($message = '', $type = 'info', $notification_type = 'notification_popup', $link = '')
  {
    self::session('notification', [
      'type'              => $type,
      'message'           => $message,
      'notification_type' => $notification_type,
      'link'              => $link
    ]);
  }

  public static function handleNotification()
  {
    $all_incluse_files = get_included_files();
    $view              = [];
    $controller        = [];

    if (self::session('notification')) {
      $message = str_replace('"', '', self::session('notification')['message']);
      $message = stripslashes($message);

      if (self::session('notification')['type'] == 'notification_popup' || self::session('notification')['type'] == 'notification') {
        echo '<script>';
        echo '$(document).ready(function () {';
        // ใช้ json_encode เพื่อ escape ข้อความก่อนใส่ใน <script> ป้องกัน XSS
        $js_flags   = JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE;
        $js_type    = json_encode((string) self::session('notification')['type'], $js_flags);
        $js_message = json_encode((string) $message, $js_flags);
        if (self::session('notification')['notification_type'] == 'notification_popup') {
          echo 'w.popup(' . $js_type . ',' . $js_message . ');';
        } else {
          echo 'w.notification(' . $js_type . ', ' . $js_message . ');';
        }
        echo '});';
        echo '</script>';
      } else {
        $notification_type = (self::session('notification')['type'] != 'success') ? 'false' : 'true';
        Brandtoast::notification($message, self::session('notification')['type'], $notification_type, self::session('notification')['link']);
      }
      self::removeSession('notification');
    }

    if (F_IS_BRIDGE) {
      if (isLocalhost()) {
        foreach ($all_incluse_files as $filepath) {
          if (mb_strpos($filepath, DIRECTORY_SEPARATOR . 'view' . DIRECTORY_SEPARATOR) !== false) {
            array_push($view, $filepath);
          }
          if (mb_strpos($filepath, DIRECTORY_SEPARATOR . 'controller' . DIRECTORY_SEPARATOR) !== false) {
            array_push($controller, $filepath);
          }
        }
      }
    }

    $controller = json_encode($controller);
    $view       = json_encode($view);

    echo '<script>';
    echo '$(document).ready(function () {';
    echo 'w.global.view = $.unique($.merge(w.global.view, ' . $view . '));';
    echo 'w.global.controller = $.unique($.merge(w.global.controller, ' . $controller . '));';
    echo '});';
    echo '</script>';
  }

  // Language
  public static function setLang($lang_dict)
  {
    self::$lang_dict = $lang_dict;
  }

  public static function setLangConfig($lang)
  {
    self::session('lang', $lang);
  }

  public static function getLangConfig()
  {
    $lang = self::session('lang');
    if (is_null($lang)) {
      $lang = self::DEFAULT_LANGUAGE;
    }
    return $lang;
  }

  public static function getText($key)
  {
    $lang = self::getLangConfig();
    if (isset(self::$lang_dict[$key][$lang])) {
      return self::$lang_dict[$key][$lang];
    } else {
      return 'Undefined';
    }
  }

  //email
  public static function compileEmail($path, $data = [])
  {
    $message = file_get_contents($path);
    foreach ($data as $key => $value) {
      $message = str_replace('{{' . $key . '}}', $value, $message);
    }
    return $message;
  }

  //datetime
  public static function monthThai($month)
  {
    $thaimonth = ['', 'มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน', 'กรกฎาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม'];
    return $thaimonth[$month * 1];
  }

  public static function monthThaiShort($month)
  {
    $thaimonth = ['', 'ม.ค.', 'ก.พ.', 'มี.ค.', 'เม.ย.', 'พ.ค.', 'มิ.ย.', 'ก.ค.', 'ส.ค.', 'ก.ย.', 'ต.ค.', 'พ.ย.', 'ธ.ค.'];
    return $thaimonth[$month * 1];
  }

  public static function hoursAndMins($time, $format = '%02d:%02d')
  {
    if ($time < 1) {
      return;
    }
    $hours = floor($time / 60);
    $minutes = ($time % 60);

    return sprintf($format, $hours, $minutes);
  }

  public static function calVat($price, $vat_type = 'exclude')
  {
    $result = [];

    if ($vat_type == 'exclude') {
      $result['vat']        = (($price) * 7) / 100;
      $result['totalprice'] = $price + (($price) * 7) / 107;
    } else {
      $result['vat']        = (($price) * 7) / 107;
      $result['totalprice'] = $price;
    }

    return $result;
  }

  public static function fetchArray($data = [])
  {
    if (isset($data['response_status'])) {
      if ($data['response_status']) {
        $result = $data['response_data'];
        return $result;
      } else {
        return false;
      }
    } else {
      return false;
    }
  }

  public static function getListProvince()
  {
    $province = [
      'กระบี่',
      'กรุงเทพมหานคร',
      'กาญจนบุรี',
      'กาฬสินธุ์',
      'กำแพงเพชร',
      'ขอนแก่น',
      'จันทบุรี',
      'ฉะเชิงเทรา',
      'ชลบุรี',
      'ชัยนาท',
      'ชัยภูมิ',
      'ชุมพร',
      'ตรัง',
      'ตราด',
      'ตาก',
      'นครนายก',
      'นครปฐม',
      'นครพนม',
      'นครราชสีมา',
      'นครศรีธรรมราช',
      'นครสวรรค์',
      'นนทบุรี',
      'นราธิวาส',
      'น่าน',
      'บึงกาฬ',
      'บุรีรัมย์',
      'ปทุมธานี',
      'ประจวบคีรีขันธ์',
      'ปราจีนบุรี',
      'ปัตตานี',
      'พระนครศรีอยุธยา',
      'พะเยา',
      'พังงา',
      'พัทลุง',
      'พิจิตร',
      'พิษณุโลก',
      'ภูเก็ต',
      'มหาสารคาม',
      'มุกดาหาร',
      'ยะลา',
      'ยโสธร',
      'ระนอง',
      'ระยอง',
      'ราชบุรี',
      'ร้อยเอ็ด',
      'ลพบุรี',
      'ลำปาง',
      'ลำพูน',
      'ศรีสะเกษ',
      'สกลนคร',
      'สงขลา',
      'สตูล',
      'สมุทรปราการ',
      'สมุทรสงคราม',
      'สมุทรสาคร',
      'สระบุรี',
      'สระแก้ว',
      'สิงห์บุรี',
      'สุพรรณบุรี',
      'สุราษฎร์ธานี',
      'สุรินทร์',
      'สุโขทัย',
      'หนองคาย',
      'หนองบัวลำภู',
      'อำนาจเจริญ',
      'อุดรธานี',
      'อุตรดิตถ์',
      'อุทัยธานี',
      'อุบลราชธานี',
      'อ่างทอง',
      'เชียงราย',
      'เชียงใหม่',
      'เพชรบุรี',
      'เพชรบูรณ์',
      'เลย',
      'แพร่',
      'แม่ฮ่องสอน'
    ];
    sort($province);
    return $province;
  }

  public static function getListBank()
  {
    $bank_list = [
      'ทหารไทย',
      'กสิกรไทย',
      'กรุงเทพ',
      'กรุงไทย',
      'กรุงศรีอยุธยา',
      'เกียรตินาคิน',
      'เจพีมอร์แกน เชส',
      'ซิตี้แบงค์',
      'ซีไอเอ็มบี ไทย',
      'ซูมิโตโม มิตซุย แบงกิ้ง คอร์ปอเรชั่น',
      'ดอยซ์แบงก์',
      'ทิสโก้',
      'ไทยเครดิต เพื่อรายย่อย',
      'ไทยพาณิชย์',
      'ธนชาต',
      'บีเอ็นพี พารีบาส์',
      'เพื่อการเกษตรและสหกรณ์การเกษตร',
      'มิซูโฮ คอร์ปอเรต',
      'เมกะ สากลพาณิชย์',
      'ยูโอบี',
      'แลนด์ แอนด์ เฮ้าส์',
      'สแตนดาร์ดชาร์เตอร์',
      'ธนาคารแห่งประเทศจีน',
      'ธนาคารแห่งอเมริกา',
      'ออมสิน',
      'อาคารสงเคราะห์',
      'อิสลามแห่งประเทศไทย',
      'ไอซีบีซี',
      'ฮ่องกงและเซี่ยงไฮ้',
      'The Fauquier Bank (TFB)'
    ];
    sort($bank_list);
    return $bank_list;
  }

  public static function getListDate()
  {
    $day_list                = [];
    $year_list               = [];
    $year_list['en']         = [];
    $year_list['th']         = [];
    $year_reverse_list       = [];
    $year_reverse_list['en'] = [];
    $year_reverse_list['th'] = [];
    $hour_list               = [];
    $minute_list             = [];
    for ($i = 0; $i < 60; $i++) {
      if ($i < 31) {
        array_push($day_list, sprintf('%02d', $i + 1));
      }
      if ($i < 25) {
        array_push($year_list['en'], date('Y') + $i);
        array_push($year_list['th'], date('Y') + $i + 543);
      }
      if ($i < 24) {
        array_push($hour_list, sprintf('%02d', $i));
      }
      array_push($minute_list, sprintf('%02d', $i));
    }
    for ($i = 0; $i < 100; $i++) {
      array_push($year_reverse_list['en'], date('Y') - $i);
      array_push($year_reverse_list['th'], date('Y') - $i - 543);
    }
    $date_list = (object) [
      'date'         => $day_list,
      'month'        => [
        (object) [
          'month'   => 1,
          'name'    => 'มกราคม',
          'name_en' => 'January'
        ],
        (object) [
          'month'   => 2,
          'name'    => 'กุมภาพันธ์',
          'name_en' => 'Febuary'
        ],
        (object) [
          'month'   => 3,
          'name'    => 'มีนาคม',
          'name_en' => 'March'
        ],
        (object) [
          'month'   => 4,
          'name'    => 'เมษายน',
          'name_en' => 'April'
        ],
        (object) [
          'month'   => 5,
          'name'    => 'พฤษภาคม',
          'name_en' => 'May'
        ],
        (object) [
          'month'   => 6,
          'name'    => 'มิถุนายน',
          'name_en' => 'June'
        ],
        (object) [
          'month'   => 7,
          'name'    => 'กรกฎาคม',
          'name_en' => 'July'
        ],
        (object) [
          'month'   => 8,
          'name'    => 'สิงหาคม',
          'name_en' => 'August'
        ],
        (object) [
          'month'   => 9,
          'name'    => 'กันยายน',
          'name_en' => 'September'
        ],
        (object) [
          'month'   => 1,
          'name'    => 'ตุลาคม',
          'name_en' => 'October'
        ],
        (object) [
          'month'   => 11,
          'name'    => 'พฤศจิกายน',
          'name_en' => 'November'
        ],
        (object) [
          'month'   => 12,
          'name'    => 'ธันวาคม',
          'name_en' => 'December'
        ]
      ],
      'year'         => $year_list,
      'year_reverse' => $year_reverse_list,
      'hour'         => $hour_list,
      'minute'       => $minute_list
    ];
    return $date_list;
  }

  // Font

  public static function setFontConfig($font)
  {
    self::cookie('font', $font);
  }

  // auto form
  public static function callAPIAutoForm($code, $data, $id, $is_files = false)
  {
    header('Content-Type: application/json');
    if ($is_files) {
      $where = [
        'id' => intval($id)
      ];
      $keys = '';
      foreach ($data as $idx => $data_files) {
        if ($data_files['size'] > 0) {
          $keys = $idx;
          break;
        }
      }
      if ($is_files == 'image') {
        $result = File::addImage($code . '_image_' . $keys . '_' . $id, $data[$keys]);
        if ($result) {
          $return = File::getPath($code . '_image_' . $keys . '_' . $id);
        } else {
          $return = false;
        }
      } else {
        $result = File::addFile($code . '_file_' . $keys . '_' . $id, $data[$keys]);
        if ($result) {
          $return = File::getPath($code . '_file_' . $keys . '_' . $id);
        } else {
          $return = false;
        }
      }
      echo json_encode($return);
    } else {
      // $_POST['status'] = 'Active';
      $where = [
        'id' => intval($id)
      ];
      unset($data['id']);
      $result = Amst::update($code, $_POST, $where);
      echo json_encode($result);
    }
    exit;
  }

  public static function fetchSingleArray($response)
  {
    $ret = [];
    if (is_array($response) && count($response) > 0) {
      $ret = $response[0];
    }
    return $ret;
  }

  public static function fetchArrayAsyncContent($data = [])
  {
    $data = htmlentities(json_encode($data));
    return $data;
  }

  public static function showDescription($text, $type = 'textarea')
  {
    $return = $text;
    if ($type == 'editor') {
      $return = '<div class="woofwup-description-detail">' . $text . '</div>';
    } else {
      $return = nl2br($text);
    }

    return $return;
  }

  public static function getRelativePath($to, $from = __DIR__)
  {
    // some compatibility fixes for Windows paths
    $from = is_dir($from) ? rtrim($from, '\/') . '/' : $from;
    $to   = is_dir($to) ? rtrim($to, '\/') . '/' : $to;
    $from = str_replace('\\', '/', $from);
    $to   = str_replace('\\', '/', $to);

    $from    = explode('/', $from);
    $to      = explode('/', $to);
    $relPath = $to;

    foreach ($from as $depth => $dir) {
      // find first non-matching dir
      if ($dir === $to[$depth]) {
        // ignore this directory
        array_shift($relPath);
      } else {
        // get number of remaining dirs to $from
        $remaining = count($from) - $depth;
        if ($remaining > 1) {
          // add traversals up to first matching dir
          $padLength = (count($relPath) + $remaining - 1) * -1;
          $relPath   = array_pad($relPath, $padLength, '..');
          break;
        } else {
          $relPath[0] = './' . $relPath[0];
        }
      }
    }
    return implode('/', $relPath);
  }

  static function formatPriceTH($number)
  {
    $num_word = ['ศูนย์', 'หนึ่ง', 'สอง', 'สาม', 'สี่', 'ห้า', 'หก', 'เจ็ด', 'แปด', 'เก้า', 'สิบ'];
    $units    = ['', 'สิบ', 'ร้อย', 'พัน', 'หมื่น', 'แสน', 'ล้าน', 'สิบ', 'ร้อย', 'พัน', 'หมื่น', 'แสน', 'ล้าน'];

    $number = str_replace(',', '', $number);
    $number = str_replace(' ', '', $number);
    $number = str_replace('บาท', '', $number);

    $number = explode('.', $number);

    if (sizeof($number) > 2) {
      return 'wrong number format';
    }

    $strlen    = strlen($number[0]);
    $converted = '';

    for ($i = 0; $i < $strlen; $i++) {
      $n = substr($number[0], $i, 1);

      if ($n != 0) {
        if ($i == ($strlen - 1) && $n == 1) {
          $converted .= 'เอ็ด';
        } else if ($i == ($strlen - 2) && $n == 2) {
          $converted .= 'ยี่';
        } else if ($i == ($strlen - 2) && $n == 1) {
          $converted .= '';
        } else {
          $converted .= $num_word[$n];
        }

        $converted .= $units[$strlen - $i - 1];
      } else {
        $converted .= '';
      }
    }

    $converted .= 'บาท';

    if ($number[1] == '0' || $number[1] == '00' || $number[1] == '') {
      $converted .= 'ถ้วน';
    } else {
      $strlen = strlen($number[1]);

      for ($i = 0; $i < $strlen; $i++) {
        $n = substr($number[1], $i, 1);
        if ($n != 0) {
          if ($i == ($strlen - 1) && $n == 1) {
            $converted .= 'เอ็ด';
          } else if ($i == ($strlen - 2) && $n == 2) {
            $converted .= 'ยี่';
          } else if ($i == ($strlen - 2) && $n == 1) {
            $converted .= '';
          } else {
            $converted .= $num_word[$n];
          }
          $converted .= $units[$strlen - $i - 1];
        }
      }
      $converted .= 'สตางค์';
    }

    return $converted;
  }

  public static function responseAPI()
  {
    global $result, $message, $redirect;

    if (isset($result) && $result) {
      $response_message = isset($message) ? $message : $result['response_message'];
      $response_status = isset($result['response_status']) && $result['response_status'] ? 'success' : 'error';
      $response_redirect = isset($redirect) && $response_status != 'error' ? $redirect : '';

      self::notification($response_message, $response_status);
      self::redirect($response_redirect);
    }
  }
  public static function autoLoad($class_name)
  {
    // system class
    $list_class = ['W_Bridge', 'W_Config', 'W_Log', 'W_Permission', 'W_Structure', 'W_User', 'W_Log', 'W_Wloves'];
    if (in_array($class_name, $list_class)) {
      if (isset(W_Info::$core_file[$class_name]['path'])) {
        if (file_exists(F_ROOT_PHP . '/' . W_Info::$core_file[$class_name]['path']) || W_Info::$core_file[$class_name]['path'] != '') {
          if (!class_exists($class_name)) {
            include_once F_ROOT_PHP . '/' . W_Info::$core_file[$class_name]['path'];
            return null;
          }
        }
      }
    } else {
      if (!class_exists($class_name)) {
        eval('class ' . $class_name . ' extends W_Bridge {}');
      }
    }


    // Library
    // if (file_exists(F_ROOT_PHP . "/library/{$class_name}.php")) {
    //   require_once F_ROOT_PHP . "/library/{$class_name}.php";
    // }

    //   // module class
    //   $functions = Func::selectForAutoload();
    //   foreach ($functions as $function) {
    //     if (file_exists(F_ROOT_PHP . '/' . Config_Module::$module_file[$function['module']] . '/class/')) {
    //       foreach (glob(F_ROOT_PHP . '/' . Config_Module::$module_file[$function['module']] . '/class/*.php') as $file_name) {
    //         $file_class_name = explode('/', $file_name);
    //         $file_class_name = end($file_class_name);
    //         if ($file_class_name != 'index.php') {
    //           $file_class_name = substr($file_class_name, 0, strlen($file_class_name) - 4);
    //           if (!class_exists($file_class_name) && (strtolower($class_name) == strtolower($file_class_name))) {
    //             include_once $file_name;
    //             return;
    //           }
    //         }
    //       }
    //     }
    //   }
  }
  public static function getMenuCode($options = [])
  {
    $is_get_title_name = isset($options['is_get_title_name']) ? $options['is_get_title_name'] : false;
    $temp = [];
    $module_list = W_Permission::$functions_available;
    foreach ($module_list as $key => $module) {
      W_Wloves::setConfigMenuList($module['module']);
    }
    $menu_list = W_Config::$menu_list;
    foreach ($module_list as $module) {
      $menu = array_key_exists($module['module'], $menu_list) ? $menu_list[$module['module']] : '';
      if (!$menu) {
        continue;
      }
      if (isset($menu_list[$module['module']]) && $menu_list[$module['module']]['menu_items']) {
        if ($module['module'] == 'User') {
          if ($is_get_title_name) {
            $name = $module['code'] . '_user';
            $title = $menu_list[$module['module']]['title'];
            $data_temp = [
              'name' => $name,
              'title' => $title
            ];
            array_push($temp, $data_temp);
          } else {
            $name = $module['code'] . '_user';
            array_push($temp, $name);
          }
        } else {
          foreach ($menu_list as $module_name => $menu) {
            $menu_items = isset($menu['menu_items']) ? $menu['menu_items'] : [];
            foreach ($menu_items as $menu_items_key => $menu_item) {
              $sub_menu = isset($menu_item['sub_menu']) ? $menu_item['sub_menu'] : [];
              foreach ($sub_menu as $key => $sub) {
                if ($is_get_title_name) {
                  $name = $module['code'] . '_' . $sub['page_name'];
                  $title = $sub['title'];
                  $data_temp = [
                    'name' => $name,
                    'title' => $title
                  ];
                  array_push($temp, $data_temp);
                } else {
                  $name = $module['code'] . '_' . $sub['page_name'];
                  array_push($temp, $name);
                }
              }
            }
          }
        }
      }
    }
    if ($is_get_title_name) {
      $menu = array_unique($temp, SORT_REGULAR);
    } else {
      $menu = array_unique($temp);
    }
    return $menu;
  }
  public static function getMenuFunctionCode()
  {
    $temp = [];
    $module_list = W_Permission::$functions_available;
    foreach ($module_list as $key => $module) {
      W_Wloves::setConfigMenuList($module['module']);
    }
    $menu_list = W_Config::$menu_list;
    foreach ($module_list as $module) {
      $menu = array_key_exists($module['module'], $menu_list) ? $menu_list[$module['module']] : '';
      if (!$menu) {
        continue;
      }
      if (isset($menu_list[$module['module']]) && $menu_list[$module['module']]['menu_items']) {
        if ($module['module'] == 'User') {
          $name = $module['code'] . '_user';
          array_push($temp, $name);
        } else {
          foreach ($menu_list as $module_name => $menu) {
            $menu_items = isset($menu['menu_items']) ? $menu['menu_items'] : [];
            foreach ($menu_items as $menu_items_key => $menu_item) {
              $sub_menu = isset($menu_item['sub_menu']) ? $menu_item['sub_menu'] : [];
              foreach ($sub_menu as $key => $sub) {
                $name = $module['code'] . '_' . $sub['page_name'];
                array_push($temp, $name);
                if (isset($sub['function'])) {
                  foreach ($sub['function'] as $function_key => $function) {
                    $function_name = $name . '_' . $function;
                    array_push($temp, $function_name);
                  }
                }
              }
            }
          }
        }
      }
    }
    $menu = array_unique($temp);
    return $menu;
  }


  public static function getCountMenuModule($module)
  {
    if (file_exists('../../../module/' . strtolower($module) . '/config/menu.php')) {
      include '../../../module/' . strtolower($module) . '/config/menu.php';
    }
    $menu_list = W_Config::$menu_list;
    $count = 0;
    if (isset($menu_list[$module]) && $menu_list[$module]['menu_items']) {
      foreach ($menu_list[$module]['menu_items'] as $menu_item) {
        $count += count($menu_item['sub_menu']);
      }
    }
    return $count;
  }

  public static function formatNumberTH($number, $include_unit = true, $display_zero = true)
  {
    // Format Number To Bath Thai
    $BAHT_TEXT_NUMBERS = array('ศูนย์', 'หนึ่ง', 'สอง', 'สาม', 'สี่', 'ห้า', 'หก', 'เจ็ด', 'แปด', 'เก้า');
    $BAHT_TEXT_UNITS = array('', 'สิบ', 'ร้อย', 'พัน', 'หมื่น', 'แสน', 'ล้าน');
    $BAHT_TEXT_ONE_IN_TENTH = 'เอ็ด';
    $BAHT_TEXT_TWENTY = 'ยี่';
    $BAHT_TEXT_INTEGER = 'ถ้วน';
    $BAHT_TEXT_BAHT = 'บาท';
    $BAHT_TEXT_SATANG = 'สตางค์';
    $BAHT_TEXT_POINT = 'จุด';

    /**
     * Convert baht number to Thai text
     * @param double|int $number
     * @param bool $include_unit
     * @param bool $display_zero
     * @return string|null
     */

    if (!is_numeric($number)) {
      return null;
    }

    $log = floor(log($number, 10));
    if ($log > 5) {
      $millions = floor($log / 6);
      $million_value = pow(1000000, $millions);
      $normalised_million = floor($number / $million_value);
      $rest = $number - ($normalised_million * $million_value);
      $millions_text = '';
      for ($i = 0; $i < $millions; $i++) {
        $millions_text .= $BAHT_TEXT_UNITS[6];
      }
      return self::formatNumberTH($normalised_million, false) . $millions_text . self::formatNumberTH($rest, true, false);
    }

    $number_str = (string)floor($number);
    $text = '';
    $unit = 0;

    if ($display_zero && $number_str == '0') {
      $text = $BAHT_TEXT_NUMBERS[0];
    } else for ($i = strlen($number_str) - 1; $i > -1; $i--) {
      $current_number = (int)$number_str[$i];

      $unit_text = '';
      if ($unit == 0 && $i > 0) {
        $previous_number = isset($number_str[$i - 1]) ? (int)$number_str[$i - 1] : 0;
        if ($current_number == 1 && $previous_number > 0) {
          $unit_text .= $BAHT_TEXT_ONE_IN_TENTH;
        } else if ($current_number > 0) {
          $unit_text .= $BAHT_TEXT_NUMBERS[$current_number];
        }
      } else if ($unit == 1 && $current_number == 2) {
        $unit_text .= $BAHT_TEXT_TWENTY;
      } else if ($current_number > 0 && ($unit != 1 || $current_number != 1)) {
        $unit_text .= $BAHT_TEXT_NUMBERS[$current_number];
      }

      if ($current_number > 0) {
        $unit_text .= $BAHT_TEXT_UNITS[$unit];
      }

      $text = $unit_text . $text;
      $unit++;
    }

    if ($include_unit) {
      $text .= $BAHT_TEXT_BAHT;

      $satang = explode('.', number_format($number, 2, '.', ''))[1];
      $text .= $satang == 0
        ? $BAHT_TEXT_INTEGER
        : self::formatNumberTH($satang, false) . $BAHT_TEXT_SATANG;
    } else {
      $exploded = explode('.', $number);
      if (isset($exploded[1])) {
        $text .= $BAHT_TEXT_POINT;
        $decimal = (string)$exploded[1];
        for ($i = 0; $i < strlen($decimal); $i++) {
          $text .=  $BAHT_TEXT_NUMBERS[$decimal[$i]];
        }
      }
    }

    return $text;
  }
  public static function datafile($url)
  {
    $result = [
      'full_name' => '',
      'name' => '',
      'ext' => '',
      'is_placeholder' => false,
    ];
    $result['name'] = pathinfo($url, PATHINFO_FILENAME);
    $result['ext'] = pathinfo($url, PATHINFO_EXTENSION);
    $result['ext'] = explode('?', $result['ext'])[0];
    $result['full_name'] = $result['name'] . '.' . $result['ext'];
    $result['is_placeholder'] = $result['name'] == 'placeholder_square' ? true : false;
    return $result;
  }

  public static function getExportLink($class = '', $api = '', $code = '', $where = [], $options = [], $manage = '')
  {
    if ($manage == '') {
      $API_URL  = F_BRIDGE_API_URL;
      if (isset($options['is_ip']) && $options['is_ip']) {
        $path = $options['path_ip'];

        $link_api = explode($path, F_BRIDGE_API_URL);
        $ip_current = $_SERVER['HTTP_ORIGIN'] . '' . $path . '' . $link_api[1];
        $API_URL = $ip_current;
      }
      $options['export'] = 'excel';
      if (!$where) {
        $where['system_delete'] = 0;
      }
      $array_link        = [
        'params'   => [$code, $where, $options],
        'class'    => $class,
        'function' => $api,
        'api_key'  => F_BRIDGE_API_KEY
      ];
      return $API_URL . '?' . http_build_query($array_link);
    } else if ($manage != '') {
      $API_URL           = F_BRIDGE_API_URL;
      // $options['export'] = 'excel';
      $array_link        = [
        'api' => $class . '::' . $api,
        'params' => [
          'code' => $code,
          'where' => $where,
          'options' => $options
        ]
      ];
      return base64_encode(json_encode($array_link)) . '<array>' . $manage;
    }
  }
}
