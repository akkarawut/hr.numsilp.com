<?php
class Info_Language {
  public static $all_lang = [
    'zhhk' => [
      'locale_name' => 'zhhk',
      'title'       => 'Traditional Chinese',
      'bcp47'       => 'zh-HK',
      'iso639-1'    => 'zh'
    ],
    'zhcn' => [
      'locale_name' => 'zhcn',
      'title'       => 'Simplified Chinese',
      'bcp47'       => 'zh-CN',
      'iso639-1'    => 'zh'
    ],
    'enus' => [
      'locale_name' => 'enus',
      'title'       => 'English',
      'bcp47'       => 'en-US',
      'iso639-1'    => 'en'
    ],
    'frfr' => [
      'locale_name' => 'frfr',
      'title'       => 'French',
      'bcp47'       => 'fr-FR',
      'iso639-1'    => 'fr'
    ],
    'dede' => [
      'locale_name' => 'dede',
      'title'       => 'German',
      'bcp47'       => 'de-DE',
      'iso639-1'    => 'de'
    ],
    'itit' => [
      'locale_name' => 'itit',
      'title'       => 'Italian',
      'bcp47'       => 'it-IT',
      'iso639-1'    => 'it'
    ],
    'jajp' => [
      'locale_name' => 'jajp',
      'title'       => 'Japanese',
      'bcp47'       => 'ja-JP',
      'iso639-1'    => 'ja'
    ],
    'kokr' => [
      'locale_name' => 'kokr',
      'title'       => 'Korean',
      'bcp47'       => 'ko-KR',
      'iso639-1'    => 'ko'
    ],
    'thth' => [
      'locale_name' => 'thth',
      'title'       => 'Thai',
      'bcp47'       => 'th-TH',
      'iso639-1'    => 'th'
    ],
    'vivn' => [
      'locale_name' => 'vivn',
      'title'       => 'Vietnamese',
      'bcp47'       => 'vi-VN',
      'iso639-1'    => 'vi'
    ],
    'ptpt' => [
      'locale_name' => 'ptpt',
      'title'       => 'Portuguese',
      'bcp47'       => 'pt-PT',
      'iso639-1'    => 'pt'
    ],
    'ruru' => [
      'locale_name' => 'ruru',
      'title'       => 'Russian',
      'bcp47'       => 'ru-RU',
      'iso639-1'    => 'ru'
    ],
    'eses' => [
      'locale_name' => 'eses',
      'title'       => 'Spanish',
      'bcp47'       => 'es-ES',
      'iso639-1'    => 'es'
    ],
    'trtr' => [
      'locale_name' => 'trtr',
      'title'       => 'Turkish',
      'bcp47'       => 'tr-TR',
      'iso639-1'    => 'tr'
    ],
    'areg' => [
      'locale_name' => 'areg',
      'title'       => 'Arabic',
      'bcp47'       => 'ar-EG',
      'iso639-1'    => 'ar'
    ]
  ];

  // EDIT List to Show in SYSTEM
  public static $supported_lang_list = [
    'zhcn' => [
      'locale_name' => 'zhcn',
      'title'       => 'Simplified Chinese',
      'bcp47'       => 'zh-CN',
      'iso639-1'    => 'zh'
    ],
    'enus' => [
      'locale_name' => 'enus',
      'title'       => 'English',
      'bcp47'       => 'en-US',
      'iso639-1'    => 'en'
    ],
    'thth' => [
      'locale_name' => 'thth',
      'title'       => 'Thai',
      'bcp47'       => 'th-TH',
      'iso639-1'    => 'th'
    ]
  ];

  // ERROR TRANSLATER
  public static $error_msg_enus = [
    // 1.SYSTEM
    'E0000' => '%s',
    'E0001' => 'Input \'%s\'',
    'E0002' => 'No data',
    'E0003' => 'Unable to DELETE, \'%s\'',
    'E0004' => 'Invalid ID input',
    'E0005' => '\'%s\' not found',
    'E0006' => 'Invalid Filter input',
    'E0007' => 'Invalid Array Input \'%s\'', // NO array
    'E0008' => 'Invalid \'%s\' as ID input',
    'E0009' => 'Invalid Timespan Input', // BETWEEN
    'E0010' => 'Input Time \'%s\' in format HH:MM:SS',
    'E0011' => 'Input Date \'%s\' in format YYYY-MM-DD',
    'E0012' => 'Invalid \'%s\' Datetime input',
    'E0013' => 'Invalid \'%s\' Numeric input',
    'E0014' => 'Input Numeric \'%s\' as Positive',
    'E0015' => 'Input Numeric \'%s\' Greater than 0',
    'E0016' => 'Input String \'%s\' Exact %s characters', // Strlen
    'E0017' => 'Input String \'%s\' not Longer than %s ', // Strlen
    'E0018' => 'Input String \'%s\' Longer than %s ', // Strlen
    'E0019' => 'Input Value \'%s\' Greater than %s',
    'E0020' => 'Input Value \'%s\' Less than %s',
    // SQL
    'E1001' => 'No Data to INSERT',
    'E1002' => 'Database Failed to INSERT',
    'E1003' => 'No Data to UPDATE',
    'E1004' => 'Database Failed to UPDATE'
  ];

  public static $error_msg_thth = [
    // 1.SYSTEM
    'E0000' => '%s',
    'E0001' => 'กรุณากรอกข้อมูล \'%s\'',
    'E0002' => 'ไม่มีข้อมูลสำหรับการเพิ่มข้อมูลใหม่',
    'E0003' => 'ไม่สามารถลบข้อมูลได้ เนื่องจาก \'%s\'',
    'E0004' => 'ข้อมูลไอดีไม่ถูกต้อง',
    'E0005' => 'ไม่พบข้อมูล \'%s\' ในระบบ',
    'E0006' => 'ข้อมูลการกรองไม่ถูกต้อง',
    'E0007' => 'ไม่สามารถส่ง Array ใน \'%s\' เพื่อบันทึกข้อมูลได้', // NO array
    'E0008' => 'กรุณากรอกข้อมูล \'%s\' ในรูปแบบของไอดี',
    'E0009' => 'ช่วงเวลาที่กรอกไม่ถูกต้อง', // BETWEEN
    'E0010' => 'กรุณากรอกข้อมูล \'%s\' ให้ถูกต้องตามรูปแบบของเวลา HH:MM:SS',
    'E0011' => 'กรุณากรอกข้อมูล \'%s\' ให้ถูกต้องตามรูปแบบของวันที่ YYYY-MM-DD',
    'E0012' => 'กรุณากรอกข้อมูล \'%s\' ให้ถูกต้องตามรูปแบบของวันที่และเวลา',
    'E0013' => 'กรุณากรอกข้อมูล \'%s\' ในรูปแบบของตัวเลข',
    'E0014' => 'กรุณากรอกข้อมูล \'%s\' ที่มีค่าไม่น้อยกว่า 0',
    'E0015' => 'กรุณากรอกข้อมูล \'%s\' ที่มีค่ามากกว่า 0',
    'E0016' => 'กรุณากรอกข้อมูล \'%s\' ที่มีความยาว %s', // Strlen
    'E0017' => 'กรุณากรอกข้อมูล \'%s\' ที่มีความยาวน้อยกว่า %s ', // Strlen
    'E0018' => 'กรุณากรอกข้อมูล \'%s\' ที่มีความยาวมากกว่า %s', // Strlen
    'E0019' => 'กรุณากรอกข้อมูล \'%s\' ที่มีค่ามากกว่า %s',
    'E0020' => 'กรุณากรอกข้อมูล \'%s\' ที่มีค่าน้อยกว่า %s',
    // SQL
    'E1001' => 'ไม่มีข้อมูลสำหรับการเพิ่มลงฐานข้อมูล',
    'E1002' => 'ล้มเหลวในการเพิ่มข้อมูลลงฐานข้อมูล',
    'E1003' => 'ไม่มีข้อมูลสำหรับการอัพเดทฐานข้อมูล',
    'E1004' => 'ล้มเหลวในการอัพเดทข้อมูลลงฐานข้อมูล'
  ];

}