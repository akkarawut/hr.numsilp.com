<?php
class Amst_Table {
  private static function getPagingLimit($page_no = 1, $page_size = 100) {
    // CHECK INPUT
    if (is_numeric($page_no)) {
      $page_no = intval($page_no);
    } else {
      $page_no = 1;
    }
    if (is_numeric($page_size)) {
      $page_size = intval($page_size);
      if ($page_size <= 0) {
        return false; // show all
      }
    } else if (strtolower($page_size) == 'all') {
      return false; // show all
    } else {
      $page_size = 100;
    }

    // PAGINATION
    $offset = ($page_no > 0) ? ($page_no * $page_size) - $page_size : 0;
    $limit = ($page_size > 0) ? $page_size : 100;

    return [$offset, $limit];
  }

  public static function checkSort($sort) {
    if (!is_array($sort)) {
      return [$sort => 'ASC']; // STRING ColName
    }

    if (Util::isAssocArray($sort)) {
      foreach ($sort as $key => $by) {
        $by = strtoupper($by);
        if ($by == '') {
          $sort[$key] = [$by => 'ASC']; // STRING ColName
        }
      }
    } else {
    }

    return $sort;
  }

  /**
  | Group | PAGINATION
  | Description | ดึงข้อมูลแล้วนำมาแบ่งหน้า
  | $code | Table Code
  | $columns | คอลัมภ์ที่ต้องการ
  | $where | [id(กรองตามไอดี), json(กรอก JSON มาละจะแปลงเป็น $where ให้ทั้งหมด)]
  | $options | [total_count(นับจำนวนข้อมูลก่อนแบ่ง),page_no(หมายเลขหน้า),page_size(จำนวนข้อมูลต่อหน้า)]
   */
  public static function select($code, $where = [], $options = []) {
    // [JSON] support
    if (is_string($where)) {
      if (Json::isValidJson($where)) {
        $where = json_decode($where, true);
      }
    }

    // SHOW ALL
    $show_options = [
      'show_all' => true
    ];
    if (!Amst::getOptionsValue('show_all', $options, false)) {
      $show_options = [
        'show_all' => false
      ];
    }

    // WHERE CHECK
    $temp = [];
    foreach ($where as $key => $value) {
      if ($value == '' || $value === null) {
        continue;
      }
      $temp[$key] = $value;
    }
    $where = $temp;

    // COUNTING
    $page_no = Amst::getOptionsValue('page_no', $options, 0);
    $page_size = Amst::getOptionsValue('page_size', $options, 100);
    $return['total_count'] = Amst::count($code, $where, $show_options);

    // SORT
    if ($sort = Amst::getOptionsValue('sort', $options, false)) {
      $where['ORDER'] = static::checkSort($sort);
    }

    // SELECT LIMIT PAGE
    $where['LIMIT'] = static::getPagingLimit($page_no, $page_size);
    $record_list = Amst::select($code, '*', $where, $show_options);
    $return['list'] = array_values($record_list);
    // $return['total_count'] = count($return['list']);

    return $return;
  }
}
