<?php

class Phr_setting_basic {

  public static function handleSettingBasicDefault($code) {
    $data = [
      'id'             => 1,
      'is_security'    => 1,
      'security_price' => 250,
      'security_unit'  => 'month',
      'tax'            => 3,
    ];

    $model = Amst::getByID($code . '_setting_basic', ['id'], 1);
    if(!isset($model['id'])) {
      Amst::insert($code . '_setting_basic', $data);
    }

    return true;
  }

  public static function setSettingBasicDetail($code, $record, $options = []) {
    if (!isset($record['id'])) {
      return $record;
    }

    // Do Single Works...
    // $record['other_key'] = 'other_value';

    return $record;
  }

  /**
  | Group | Setting Basic
  | Description | 1.ดึงข้อมูล 'Setting Basic' ด้วยไอดี
  | $code | โค้ดโมดูล
   */
  public static function getSettingBasic($code) {
    $return = null;

    static::handleSettingBasicDefault($code);

    $model = Amst::getByID($code.'_setting_basic', '*', 1);
    if (isset($model['id'])) {
      $return = static::setSettingBasicDetail($code, $model);
    }
    return $return;
  }

  /**
  | Group | Setting Basic
  | Description | 4.อัพเดท 'Setting Basic'
  | $code | โค้ดโมดูล
  | $data | [
    is_security(0 / 1),
    security_price,
    security_unit(bath / percent),
    tax
  ]
   */
  public static function updateSettingBasic($code, $data = []) {
    $rep = [
      'update_result' => false
    ];

    $model = Amst::getByID($code.'_setting_basic', ['id'], 1);
    if (!isset($model['id'])) {
      return Amst::repError('E0005', 'tbl_setting_basic not found.');
    }

    $data['is_security'] = isset($data['is_security']) ? $data['is_security'] : 0;

    return Amst::updateByID($code.'_setting_basic', $data, 1);
  }
}
