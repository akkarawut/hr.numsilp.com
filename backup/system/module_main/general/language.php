<?php
class Language {
  private static $conf = [
    'description' => 'คลาสสำหรับจัดการภาษา',
    'tb_lang'     => 'sys_language',
    'lbl_lang'    => 'Language'
  ];

  /**
   * [IMPORT] Set First System Language.
   *
   * @author TULA
   * @param String $locale_name code like enus thth
   * @return String
   */
  public static function initSystemLanguage() {
    $rep = [
      'insert_id' => false
    ];

    if (Amst::count(static::$conf['tb_lang']) == 0) {
      $n_data = [
        'locale_name'       => 'enus',
        'is_system_default' => '1'
      ];
      return static::addNewSystemLanguage($n_data);
    }
    return Amst::repSuccess($rep);
  }

  /**
   * [HELPER] Get Full (URL)Path for Image.
   *
   * @author TULA
   * @param String $locale_name code like enus thth
   * @return String
   */
  public static function getImageUrl($locale_name) {
    return ROOT_URL."/resource/lang/{$locale_name}.png";
  }

  /**
   * [HELPER] Get Full Error Message with code then translate.
   *
   * @author TULA
   * @param String $error_code code like enus thth
   * @param String $locale_name code like enus thth
   * @return String
   */
  public static function getErrorMsg($error_code, $locale_name = 'enus') {
    $return = $error_code;
    if (Util::startsWith($return, 'E') && (strlen($return) == 5)) {
      // [TRANSLATE]
      if (($locale_name == 'enus') && isset(Info_Language::$error_msg_enus[$error_code])) {
        $return = Info_Language::$error_msg_enus[$error_code];
      } else if (($locale_name == 'thth') && isset(Info_Language::$error_msg_thth[$error_code])) {
        $return = Info_Language::$error_msg_thth[$error_code];
      } else if ($locale_name == 'enus') {
        $return = 'Fatal ERROR';
      } else if ($locale_name == 'thth') {
        $return = 'พบข้อผิดพลาดรุนแรง';
      }
    }
    return $return;
  }

  public static function setLanguageListDetail($return, $options = []) {
    // [ExtractList]
    $selected_records = isset($return['list']) ? $return['list'] : $return;

    // Do Each Works...
    foreach ($selected_records as $key => $record) {
      $selected_records[$key]['img_path'] = static::getImageUrl($record['locale_name']);
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
  | Group | ภาษาที่รองรับ
  | Description | 1.ดึงข้อมูลลิส 'ภาษา' ทั้งหมดที่รองรับ *แก้ไขลิสได้ที่ Info_Language::$supported_lang_list
  | $where | [not_exists(ไม่เอาภาษาที่ติดตั้งไปแล้ว)]
  | $options | []
   */
  public static function selectLanguage($where = [], $options = []) {
    $return = []; // list

    $return = Info_Language::$supported_lang_list;

    // Remove Installed Lang
    if (isset($where['not_exists']) && $where['not_exists']) {
      $selected_records = static::selectSystemLanguage();
      foreach ($selected_records as $row) {
        if (isset($return[$row['locale_name']])) {
          unset($return[$row['locale_name']]);
        }
      }
      unset($selected_records);
    }

    // [IMG_PATH]
    foreach ($return as $key => $record) {
      $return[$key]['img_path'] = static::getImageUrl($record['locale_name']);
    }
    return array_values($return);
  }

  /**
  | Group | ภาษาที่รองรับ
  | Description | 1.นับจำนวนข้อมูลลิส 'ภาษา' ที่ติดตั้งในระบบแล้ว
  | $where | []
  | $options | []
   */
  public static function countSystemLanguage($where = [], $options = []) {
    $return = 0; // count
    return Amst::count(static::$conf['tb_lang'], $where);
  }

  /**
  | Group | ภาษาที่ติดตั้งในระบบแล้ว
  | Description | 1.ดึงข้อมูลลิส 'ภาษา' ที่ติดตั้งในระบบแล้ว
  | $where | [locale_name(โค้ดภาษา), display_name(ชื่อที่แสดง)]
  | $options | [not_view(BOOL;1;ข้อมูลดิบ), selected_fields(STRING;'*';คอลัมภ์ที่ต้องการ), total_count(BOOL;0;พร้อมจำนวนข้อมูลทั้งหมด ตามการกรอง(ไม่แบ่งหน้า)), page_no(INT;0;เลขหน้า 0 คือทั้งหมด), page_size(INT;0;จำนวนข้อมูลต่อหน้า 0 คือทั้งหมด), sort(JSON;['column_name' => 'ASC/DESC'];เรียงคอลัมภ์)]
   */
  public static function selectSystemLanguage($where = [], $options = []) {
    $return = []; // list

    $sql_where = Amst::mergeWhere($where); // Auto Where

    $selected_fields = Amst::getSelectedFields($options);
    $return = Amst::selectNoKey(static::$conf['tb_lang'], $selected_fields, $sql_where, $options);
    return static::setLanguageListDetail($return, $options);
  }

  public static function setLanguageDetail($record, $options = []) {
    if (!isset($record['id'])) {
      return $record;
    }

    // Do Single Works...
    $record['img_path'] = static::getImageUrl($record['locale_name']);

    return $record;
  }

  /**
  | Group | ภาษาที่ติดตั้งในระบบแล้ว
  | Description | 2.ดึงข้อมูล 'ภาษา' ด้วยไอดี
  | $id | ไอดีรายการ
  | $options | [not_view(true // เอาแค่ข้อมูลหลักไป edit *เร็ว), selected_fields('*' // คอลัมภ์ที่ต้องการ)]
   */
  public static function getSystemLanguageByID($id, $options = []) {
    $return = null;

    $selected_fields = Amst::getSelectedFields($options, ['id', 'system_delete']);
    if ($id) {
      $model = Amst::getByID(static::$conf['tb_lang'], $selected_fields, $id, $options);
      if (isset($model['id'])) {
        $return = static::setLanguageDetail($model, $options);
      }
    }
    return $return;
  }

  /**
  | Group | ภาษาที่ติดตั้งในระบบแล้ว
  | Description | 3.ดึงข้อมูล 'ภาษา' ของระบบ
  | $options | []
   */
  public static function getSystemDefaultLanguage($options = []) {
    $return = null;

    $selected_fields = Amst::getSelectedFields($options, ['id', 'system_delete']);
    $where = [
      'AND' => [
        'is_system_default' => 1,
        'system_delete'     => 0
      ]
    ];
    $model = Amst::get(static::$conf['tb_lang'], $selected_fields, $where, $options);
    if (isset($model['id'])) {
      $return = static::setLanguageDetail($model, $options);
    }
    return $return;
  }

  /**
  | Group | ภาษาที่ติดตั้งในระบบแล้ว
  | Description | 4.เพิ่ม 'ภาษา'
  | $data | [locale_name(รหัสภาษา4หลัก เช่น enus thth)]
   */
  public static function addNewSystemLanguage($data) {
    $rep = [
      'insert_id' => false
    ];

    $data = Amst::checkWhere($data);
    $locale_name = isset($data['locale_name']) ? strtolower(trim($data['locale_name'])) : false;
    if ($locale_name && isset(Info_Language::$supported_lang_list[$locale_name])) {
      $data['display_name'] = Info_Language::$supported_lang_list[$locale_name]['title'];
    } else {
      return Amst::repError('E0000', 'Language not support');
    }

    if (!isset($data['is_system_default'])) {
      $data['is_system_default'] = (Amst::count(static::$conf['tb_lang']) == 0) ? 1 : 0;
    }

    // [SINGLE]
    if (isset($data['is_system_default']) && ($data['is_system_default'] == 1)) {
      Amst::update(static::$conf['tb_lang'], ['is_system_default' => 0], ['system_delete' => 0], ['direct' => true]);
    }

    return Amst::insert(static::$conf['tb_lang'], $data);
  }

  /**
  | Group | ภาษาที่ติดตั้งในระบบแล้ว
  | Description | 5.อัพเดท 'ภาษา'
  | $id | ไอดีรายการ
  | $data | [is_system_default(1 = make default)]
   */
  public static function updateSystemLanguage($id, $data) {
    $rep = [
      'update_result' => false
    ];

    $model = Amst::getByID(static::$conf['tb_lang'], ['id'], $id);
    if (!isset($model['id'])) {
      return Amst::repError('E0005', static::$conf['lbl_lang']);
    }

    // [SINGLE]
    if (isset($data['is_system_default']) && ($data['is_system_default'] == 1)) {
      Amst::update(static::$conf['tb_lang'], ['is_system_default' => 0], ['system_delete' => 0], ['direct' => true]);
    }

    return Amst::updateByID(static::$conf['tb_lang'], $data, $id);
  }

  /**
  | Group | ภาษาที่ติดตั้งในระบบแล้ว
  | Description | 6.ลบ 'ภาษา' *ห้ามลบภาษาที่ตั้งเป็นค่าเริ่มต้นไว้
  | $id | ไอดีรายการ
   */
  public static function deleteSystemLanguage($id) {
    $rep = [
      'delete_result' => false
    ];

    $api = static::_checkDeleteLanguage($id);
    if (isset($api['response_status']) && ($api['response_status'] == true)) {
      $rep['delete_result'] = DB::delete(static::$conf['tb_lang'], ['id' => $id]);
      return Amst::repSuccess($rep);
    }
    return $api; // ERROR SURE
  }

  private static function _checkDeleteLanguage($id) {
    $rep = [
      'id' => $id
    ];

    // [Model_Exist]
    $model = Amst::getByID(static::$conf['tb_lang'], ['id', 'is_system_default'], $id);
    if (!isset($model['id'])) {
      return Amst::repError('E0005', static::$conf['lbl_lang']);
    }

    if ($model['is_system_default'] != 0) {
      return Amst::repError('E0000', 'Can not delete Default');
    }

    return Amst::repSuccess($rep);
  }
}
