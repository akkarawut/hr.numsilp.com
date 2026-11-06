<?php
class Bank {
  private static $conf = [
    'description' => 'คลาสสำหรับดึงข้อมูลธนาคาร',
    'lbl_bank'    => 'Bank'
  ];

  /**
  | Group | ดึงข้อมูลธนาคาร
  | Description | 1.select แบบ KEY เป็น abb
  | $where | ไม่มีเพราะ FIXED ARRAY
  | $options | ไม่มีเพราะ FIXED ARRAY
   */
  public static function select($where = [], $options = []) {
    $return = [
      'SCB'     => [
        'abb'     => 'SCB',
        'name_th' => 'ธนาคารไทยพาณิชย์',
        'name_en' => 'Siam Commercial Bank'
      ],
      'KTB'     => [
        'abb'     => 'KTB',
        'name_th' => 'ธนาคารกรุงไทย',
        'name_en' => 'Krung Thai Bank'
      ],
      'TBANK'   => [
        'abb'     => 'TBANK',
        'name_th' => 'ธนาคารธนชาต',
        'name_en' => 'Thanachart Bank'
      ],
      'KBANK'   => [
        'abb'     => 'KBANK',
        'name_th' => 'ธนาคารกสิกรไทย',
        'name_en' => 'Kasikorn Bank'
      ],
      'BBL'     => [
        'abb'     => 'BBL',
        'name_th' => 'ธนาคารกรุงเทพ',
        'name_en' => 'Bangkok Bank'
      ],
      'BAAC'    => [
        'abb'     => 'BAAC',
        'name_th' => 'ธนาคารเพื่อการเกษตรและสหกรณ์การเกษตร',
        'name_en' => 'Bank for Agriculture and Agricultural Cooperatives'
      ],
      'BAY'     => [
        'abb'     => 'BAY',
        'name_th' => 'ธนาคารกรุงศรีอยุธยา',
        'name_en' => 'Bank of Ayudhayak'
      ],
      'TTB'     => [
        'abb'     => 'TTB',
        'name_th' => 'ธนาคารทหารไทยธนชาต',
        'name_en' => 'TMBThanachart Bank Public Company Limited'
      ],
      'CITI'    => [
        'abb'     => 'CITI',
        'name_th' => 'ธนาคารซิตี้แบงค์',
        'name_en' => 'Citibank'
      ],
      'GHB'     => [
        'abb'     => 'GHB',
        'name_th' => 'ธนาคารอาคารสงเคราะห์',
        'name_en' => 'Government Housing Bank'
      ],
      'GSB'     => [
        'abb'     => 'GSB',
        'name_th' => 'ธนาคารออมสิน',
        'name_en' => 'Government Saving Bank'
      ],
      'ICBC'    => [
        'abb'     => 'ICBC',
        'name_th' => 'ธนาคารพาณิชย์อุตสาหกรรมแห่งประเทศจีน',
        'name_en' => 'Industrial and Commercial Bank of China'
      ],
      'IBANK'   => [
        'abb'     => 'IBANK',
        'name_th' => 'ธนาคารอิสลามแห่งประเทศไทย',
        'name_en' => 'Islamic Bank of Thailand'
      ],
      'KK'      => [
        'abb'     => 'KK',
        'name_th' => 'ธนาคารเกียรตินาคิน',
        'name_en' => 'Kiatnakin Bank'
      ],
      'SCIB'    => [
        'abb'     => 'SCIB',
        'name_th' => 'ธนาคารนครหลวงไทย',
        'name_en' => 'Siam City Bank'
      ],
      'SMEBANK' => [
        'abb'     => 'SMEBANK',
        'name_th' => 'ธนาคารพัฒนาวิสาหกิจขนาดกลางและขนาดย่อมแห่งประเทศไทย',
        'name_en' => 'SME Bank of Thailand'
      ],
      'TISCO'   => [
        'abb'     => 'TISCO',
        'name_th' => 'ธนาคารทิสโก้',
        'name_en' => 'Thai Investment and Securities Company Bank'
      ],
      'UOBT'    => [
        'abb'     => 'UOBT',
        'name_th' => 'ธนาคารยูโอบี',
        'name_en' => 'United Overseas Bank, Thailand'
      ],
      'TMB'     => [
        'abb'     => 'TMB',
        'name_th' => 'ธนาคารทหารไทย',
        'name_en' => 'Thai Military Bank'
      ],
      'TFB'     => [
        'abb'     => 'TFB',
        'name_th' => 'The Fauquier Bank',
        'name_en' => 'The Fauquier Bank'
      ],
      'JPMC'    => [
        'abb'     => 'JPMC',
        'name_th' => 'เจพีมอร์แกนเชส',
        'name_en' => 'JPMorgan Chase'
      ],
      'CIMB'    => [
        'abb'     => 'CIMB',
        'name_th' => 'ธนาคารซีไอเอ็มบีไทย',
        'name_en' => 'CIMB Thai Bank'
      ],
      'SMBC'    => [
        'abb'     => 'SMBC',
        'name_th' => 'ธนาคาร ซูมิโตโม มิตซุย แบงกิ้ง คอร์ปอเรชั่น',
        'name_en' => 'SUMITOMO MITSUI BANKING CORPORATION'
      ],
      'DB'      => [
        'abb'     => 'DB',
        'name_th' => 'ด็อยท์เชอบังค์',
        'name_en' => 'Deutsche Bank'
      ],
      'TCR'     => [
        'abb'     => 'TCR',
        'name_th' => 'ธนาคารไทยเครดิต เพื่อรายย่อย',
        'name_en' => 'The Thai Credit Retail Bank'
      ],
      'BOC'     => [
        'abb'     => 'BOC',
        'name_th' => 'ธนาคารแห่งประเทศจีน',
        'name_en' => 'Bank of China'
      ],
      'BofA'    => [
        'abb'     => 'BofA',
        'name_th' => 'ธนาคารแห่งอเมริกา',
        'name_en' => 'Bank of America'
      ],
      'BNP'     => [
        'abb'     => 'BNP',
        'name_th' => 'บีเอ็นพี พารีบาส์',
        'name_en' => 'BNP Paribas S.A.'
      ],
      'Mizuho'  => [
        'abb'     => 'Mizuho',
        'name_th' => 'ธนาคาร มิซูโฮ คอร์ปอเรต จำกัด',
        'name_en' => 'Mizuho Bank.'
      ],
      'Mega'    => [
        'abb'     => 'Mega',
        'name_th' => 'ธนาคารเมกะ สากลพาณิชย์ จำกัด (มหาชน)',
        'name_en' => 'Mega International Commercial Bank Public Company Limited'
      ],
      'LHBank'  => [
        'abb'     => 'LHBank',
        'name_th' => 'ธนาคารแลนด์ แอนด์ เฮ้าส์',
        'name_en' => 'Land and House Bank Public Company Limited'
      ],
      'STAN'    => [
        'abb'     => 'STAN',
        'name_th' => 'สแตนดาร์ดชาร์เตอร์ด',
        'name_en' => 'Standard Chartered'
      ],
      'HSBC'    => [
        'abb'     => 'HSBC',
        'name_th' => 'ฮ่องกงและเซี่ยงไฮ้แบงกิ้งคอร์ปอเรชั่น จำกัด',
        'name_en' => 'The Hongkong and Shanghai Banking Corporation Limited'
      ]

    ];

    $root_url = ROOT_URL.'/resource/bank/';
    foreach ($return as $key => $value) {
      $return[$key]['image'] = $root_url.$value['abb'].'.png';
    }
    return $return;
  }

  /**
  | Group | ดึงข้อมูลธนาคาร
  | Description | 2.select แบบไม่มี KEY
  | $where | ไม่มีเพราะ FIXED ARRAY
  | $options | ไม่มีเพราะ FIXED ARRAY
   */
  public static function selectNoKey($where = [], $options = []) {
    $return = [];

    $return = static::select($where, $options);
    if ($return) {
      $return = array_values($return);
    }
    return $return;
  }

  /**
  | Group | ดึงข้อมูลธนาคาร
  | Description | 3.get ข้อมูลธนาคารด้วย abb
  | $abb | รหัสธนาคาร
   */
  public static function get($abb) {
    $banks = static::select();
    if ($abb) {
      $abb = strtoupper(trim("{$abb}"));
    }
    $bank = isset($banks[$abb]) ? $banks[$abb] : [];
    return $bank;
  }
}