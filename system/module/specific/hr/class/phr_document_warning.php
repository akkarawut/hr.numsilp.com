<?php

class phr_document_warning
{

  public static function setDocumentWarningListDetail($code, $return, $options = [])
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
  | Group | Document Warning
  | Description | 1.ดึงข้อมูลลิส 'Document Warning'
  | $code | โค้ดโมดูล
  | $where | [fullname, user_code]
  | $options | [not_view(true|เอาแค่ข้อมูลหลักไป edit*เร็ว), selected_fields(String หรือ Array ของคอลัมภ์ที่ต้องการ), total_count(true|พร้อมจำนวนข้อมูลทั้งหมด ตามการกรองไม่แบ่งหน้า), page_no(1|เลขหน้า 0 คือส่งทั้งหมด), page_size(10|จำนวนข้อมูลต่อหน้า), sort_col(เรียงตามชื่อคอลัมภ์นี้), sort_by('ASC'|'DESC')]
   */
  public static function selectDocumentWarning($code, $where = [], $options = [])
  {
    $return = []; // list
    $sql_where = [];

    if (isset($where['fullname']) && $where['fullname']) {
      $sql_where['AND']['user_fullname[~]'] = $where['fullname'];
    }
    
    if (isset($where['user_code']) && $where['user_code']) {
      $sql_where['AND']['user_code'] = $where['user_code'];
    }

    if (isset($where['user_id']) && $where['user_id'] && $where['user_id'] != 'all') {
      $sql_where['AND']['user_id'] = $where['user_id'];
    }

    $selected_fields = Amst::getSelectedFields($options);
    $return = Amst::selectNoKey($code . '_view_document_warning', $selected_fields, $sql_where, $options);
    return static::setDocumentWarningListDetail($code, $return, $options);
  }

  public static function setDocumentWarningDetail($code, $record, $options = [])
  {
    if (!isset($record['id'])) {
      return $record;
    }

    // Do Single Works...
    // $record['other_key'] = 'other_value';

    return $record;
  }

  /**
  | Group | Document Warning
  | Description | 2.ดึงข้อมูล 'Document Warning' ด้วยไอดี
  | $code | โค้ดโมดูล
  | $id | ไอดีรายการ
  | $options | [not_view(true|เอาแค่ข้อมูลหลักไป edit*เร็ว), selected_fields(String หรือ Array ของคอลัมภ์ที่ต้องการ)]
   */
  public static function getDocumentWarningByID($code, $id, $options = [])
  {
    $return = null;

    $selected_fields = Amst::getSelectedFields($options, ['id', 'system_delete']);
    $model = Amst::getByID($code . '_view_document_warning', $selected_fields, $id, $options);
    if (isset($model['id'])) {
      $return = static::setDocumentWarningDetail($code, $model, $options);
    }
    return $return;
  }

  /**
  | Group | Document Warning
  | Description | 3.check count 'Document Warning'
  | $code | โค้ดโมดูล
  | $data | [
    user_id
  ]
   */
  public static function checkUserDocumentWarning($code, $data = [])
  {
    $rep = [
      'insert_id' => false
    ];

    $where_count = [
      'AND' => [
        'user_id' => $data['user_id']
      ]
    ];
    $count = Amst::count($code . '_view_document_warning', $where_count);
    $count = $count + 1;

    return $count;
  }

  /**
  | Group | Document Warning
  | Description | 3.เพิ่ม 'Document Warning'
  | $code | โค้ดโมดูล
  | $data | [
    user_id,
    doc_no,
    detail,
    description
  ]
   */
  public static function addNewDocumentWarning($code, $data = [])
  {
    $rep = [
      'insert_id' => false
    ];

    $where_count = [
      'AND' => [
        'user_id' => $data['user_id']
      ]
    ];
    $count  = Amst::count($code . '_document_warning', $where_count);
    $count  = $count + 1;
    $doc_no = 'P' . str_pad($count, 5, '0', STR_PAD_LEFT);

    $i_data           = $data;
    $i_data['doc_no'] = $doc_no;
    $i_data['number'] = $count;

    $api = Amst::insert($code . '_document_warning', $i_data);
    if (isset($api['response_status']) && ($api['response_status'] == true)) {
      $insert_id = $api['response_data']['insert_id'];

      $model = Amst::getByID($code . '_document_warning', '*', $insert_id);

      $noti_data = [
        'user_id'      => $data['user_id'],
        'from_user_id' => $model['insert_user_id'],
        'type'         => 'system_warning',
        'number'       => $count,
        'detail'       => $data['detail'],
        'ref_id'       => $insert_id
      ];
      Phr_notification::addNewNotification($code, $noti_data);
    }
    return $api;
  }

  /**
  | Group | Document Warning
  | Description | 4.อัพเดท 'Document Warning'
  | $code | โค้ดโมดูล
  | $id | ไอดีรายการ
  | $data | [
    user_id,
    doc_no,
    detail,
    description
  ]
   */
  public static function updateDocumentWarning($code, $id, $data = [])
  {
    $rep = [
      'update_result' => false
    ];

    $model = Amst::getByID($code . '_document_warning', ['id', 'user_id'], $id);
    if (!isset($model['id'])) {
      return Amst::repError('E0005', 'tbl_document_warning not found.');
    }

    $u_data = $data;

    if ($data['user_id'] != $model['user_id']) {
      $where_count = [
        'AND' => [
          'user_id' => $data['user_id']
        ]
      ];
      $count = Amst::count($code . '_document_warning', $where_count);
      $count = $count + 1;
      
      $u_data['number'] = $count;
    }

    $api = Amst::updateByID($code . '_document_warning', $u_data, $id);
    if (isset($api['response_status']) && ($api['response_status'] == true)) {
      $insert_id = $id;
    }
    return $api;
  }

  /**
  | Group | Document Warning
  | Description | 5.ลบ 'Document Warning'
  | $code | โค้ดโมดูล
  | $id | ไอดีรายการ
   */
  public static function deleteDocumentWarning($code, $id)
  {
    $rep = [
      'delete_result' => false
    ];

    $api = static::_checkDeleteDocumentWarning($code, $id);
    if (isset($api['response_status']) && ($api['response_status'] == true)) {
      $rep['delete_result'] = Amst::deleteByID($code . '_document_warning', $id);
      return Amst::repSuccess($rep);
    }
    return $api; // ERROR SURE
  }

  private static function _checkDeleteDocumentWarning($code, $id)
  {
    $rep = [
      'id' => $id
    ];

    // [Model_Exist]
    $model = Amst::getByID($code . '_document_warning', ['id'], $id);
    if (!isset($model['id'])) {
      return Amst::repError('E0005', 'tbl_document_warning not found.');
    }

    return Amst::repSuccess($rep);
  }
}
