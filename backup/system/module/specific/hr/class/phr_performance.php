<?php

class Phr_performance
{

  public static function setHistoryPerformanceListDetail($code, $return, $options = [])
  {
    // [ExtractList]
    $selected_records = isset($return['list']) ? $return['list'] : $return;

    // Do Each Works...
    foreach ($selected_records as $key => $record) {
      // $selected_records[$key]['other_key'] = 'other_value';
    }

    // [Repack]
    if (isset($return['list'])) {
      $return['list'] = $selected_records;
    } else {
      $return = $selected_records;
    }
    return $return;
  }

  /**
  | Group | History Performance
  | Description | 1.ดึงข้อมูลลิส 'History Performance'
  | $code | โค้ดโมดูล
  | $where | [user_id]
  | $options | [not_view(true|เอาแค่ข้อมูลหลักไป edit*เร็ว), selected_fields(String หรือ Array ของคอลัมภ์ที่ต้องการ), total_count(true|พร้อมจำนวนข้อมูลทั้งหมด ตามการกรองไม่แบ่งหน้า), page_no(1|เลขหน้า 0 คือส่งทั้งหมด), page_size(10|จำนวนข้อมูลต่อหน้า), sort_col(เรียงตามชื่อคอลัมภ์นี้), sort_by('ASC'|'DESC')]
   */
  public static function selectHistoryPerformance($code, $where = [], $options = [])
  {
    $return = []; // list

    $sql_where = [];

    if (isset($where['user_id']) && $where['user_id']) {
      $sql_where['AND']['user_id'] = $where['user_id'];
    }

    $sql_where['ORDER']['date'] = 'DESC';

    $selected_fields = Amst::getSelectedFields($options);
    $return = Amst::selectNoKey($code . '_performance', $selected_fields, $sql_where, $options);
    return static::setHistoryPerformanceListDetail($code, $return, $options);
  }

  public static function setHistoryPerformanceDetail($code, $record, $options = [])
  {
    if (!isset($record['id'])) {
      return $record;
    }

    // Do Single Works...
    // $record['other_key'] = 'other_value';

    return $record;
  }

  /**
  | Group | History Performance
  | Description | 2.ดึงข้อมูล 'History Performance' ด้วยไอดี
  | $code | โค้ดโมดูล
  | $id | ไอดีรายการ
  | $options | [not_view(true|เอาแค่ข้อมูลหลักไป edit*เร็ว), selected_fields(String หรือ Array ของคอลัมภ์ที่ต้องการ)]
   */
  public static function getHistoryPerformanceByID($code, $id, $options = [])
  {
    $return = null;

    $selected_fields = Amst::getSelectedFields($options, ['id', 'system_delete']);
    $model = Amst::getByID($code . '_performance', $selected_fields, $id, $options);
    if (isset($model['id'])) {
      $return = static::setHistoryPerformanceDetail($code, $model, $options);
    }
    return $return;
  }
}
