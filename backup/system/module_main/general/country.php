<?php
class Country {
  private static $conf = [
    'description' => 'คลาสสำหรับดึงข้อมูลประเทศ',
    'lbl_country' => 'Country'
  ];

  /**
  | Group | ดึงข้อมูลประเทศ
  | Description | 1.select แบบ KEY เป็น code
  | $where | ไม่มีเพราะ FIXED ARRAY
  | $options | ไม่มีเพราะ FIXED ARRAY
   */
  public static function select($where = [], $options = []) {
    $return = [
      'BD' => [
        'name'     => 'Bangladesh',
        'code'     => 'BD',
        'phone'    => '+880',
        'currency' => 'BDT',
        'symbol'   => '৳'
      ],
      'BE' => [
        'name'     => 'Belgium',
        'code'     => 'BE',
        'phone'    => '+32',
        'currency' => 'EUR',
        'symbol'   => '€'
      ],
      'BF' => [
        'name'     => 'Burkina Faso',
        'code'     => 'BF',
        'phone'    => '+226',
        'currency' => 'XOF',
        'symbol'   => 'CFA'
      ],
      'BG' => [
        'name'     => 'Bulgaria',
        'code'     => 'BG',
        'phone'    => '+359',
        'currency' => 'BGN',
        'symbol'   => 'лв.'
      ],
      'BA' => [
        'name'     => 'Bosnia and Herzegovina',
        'code'     => 'BA',
        'phone'    => '+387',
        'currency' => 'BAM',
        'symbol'   => 'KM'
      ],
      'BB' => [
        'name'     => 'Barbados',
        'code'     => 'BB',
        'phone'    => '+1-246',
        'currency' => 'BBD',
        'symbol'   => '$'
      ],
      'WF' => [
        'name'     => 'Wallis and Futuna',
        'code'     => 'WF',
        'phone'    => '+681',
        'currency' => 'XPF',
        'symbol'   => 'F'
      ],
      'BL' => [
        'name'     => 'Saint Barthelemy',
        'code'     => 'BL',
        'phone'    => '+590',
        'currency' => 'EUR',
        'symbol'   => '€'
      ],
      'BM' => [
        'name'     => 'Bermuda',
        'code'     => 'BM',
        'phone'    => '+1-441',
        'currency' => 'BMD',
        'symbol'   => '$'
      ],
      'BN' => [
        'name'     => 'Brunei',
        'code'     => 'BN',
        'phone'    => '+673',
        'currency' => 'BND',
        'symbol'   => '$'
      ],
      'BO' => [
        'name'     => 'Bolivia',
        'code'     => 'BO',
        'phone'    => '+591',
        'currency' => 'BOB',
        'symbol'   => 'Bs'
      ],
      'BH' => [
        'name'     => 'Bahrain',
        'code'     => 'BH',
        'phone'    => '+973',
        'currency' => 'BHD',
        'symbol'   => 'د.ب.‏'
      ],
      'BI' => [
        'name'     => 'Burundi',
        'code'     => 'BI',
        'phone'    => '+257',
        'currency' => 'BIF',
        'symbol'   => 'FBu'
      ],
      'BJ' => [
        'name'     => 'Benin',
        'code'     => 'BJ',
        'phone'    => '+229',
        'currency' => 'XOF',
        'symbol'   => 'CFA'
      ],
      'BT' => [
        'name'     => 'Bhutan',
        'code'     => 'BT',
        'phone'    => '+975',
        'currency' => 'BTN',
        'symbol'   => 'Nu.'
      ],
      'JM' => [
        'name'     => 'Jamaica',
        'code'     => 'JM',
        'phone'    => '+1-876',
        'currency' => 'JMD',
        'symbol'   => '$'
      ],
      'BV' => [
        'name'     => 'Bouvet Island',
        'code'     => 'BV',
        'phone'    => '+55',
        'currency' => 'NOK',
        'symbol'   => 'kr'
      ],
      'BW' => [
        'name'     => 'Botswana',
        'code'     => 'BW',
        'phone'    => '+267',
        'currency' => 'BWP',
        'symbol'   => 'P'
      ],
      'WS' => [
        'name'     => 'Samoa',
        'code'     => 'WS',
        'phone'    => '+685',
        'currency' => 'WST',
        'symbol'   => 'ST'
      ],
      'BQ' => [
        'name'     => 'Bonaire, Saint Eustatius and Saba ',
        'code'     => 'BQ',
        'phone'    => '+599',
        'currency' => 'USD',
        'symbol'   => '$'
      ],
      'BR' => [
        'name'     => 'Brazil',
        'code'     => 'BR',
        'phone'    => '+55',
        'currency' => 'BRL',
        'symbol'   => 'R$'
      ],
      'BS' => [
        'name'     => 'Bahamas',
        'code'     => 'BS',
        'phone'    => '+1-242',
        'currency' => 'BSD',
        'symbol'   => '$'
      ],
      'JE' => [
        'name'     => 'Jersey',
        'code'     => 'JE',
        'phone'    => '+44-1534',
        'currency' => 'GBP',
        'symbol'   => '£'
      ],
      'BY' => [
        'name'     => 'Belarus',
        'code'     => 'BY',
        'phone'    => '+375',
        'currency' => 'BYR',
        'symbol'   => ''
      ],
      'BZ' => [
        'name'     => 'Belize',
        'code'     => 'BZ',
        'phone'    => '+501',
        'currency' => 'BZD',
        'symbol'   => '$'
      ],
      'RU' => [
        'name'     => 'Russia',
        'code'     => 'RU',
        'phone'    => '+7',
        'currency' => 'RUB',
        'symbol'   => '₽.'
      ],
      'RW' => [
        'name'     => 'Rwanda',
        'code'     => 'RW',
        'phone'    => '+250',
        'currency' => 'RWF',
        'symbol'   => 'FR'
      ],
      'RS' => [
        'name'     => 'Serbia',
        'code'     => 'RS',
        'phone'    => '+381',
        'currency' => 'RSD',
        'symbol'   => 'дин.'
      ],
      'TL' => [
        'name'     => 'East Timor',
        'code'     => 'TL',
        'phone'    => '+670',
        'currency' => 'USD',
        'symbol'   => '$'
      ],
      'RE' => [
        'name'     => 'Reunion',
        'code'     => 'RE',
        'phone'    => '+262',
        'currency' => 'EUR',
        'symbol'   => '€'
      ],
      'TM' => [
        'name'     => 'Turkmenistan',
        'code'     => 'TM',
        'phone'    => '+993',
        'currency' => 'TMT',
        'symbol'   => 'T'
      ],
      'TJ' => [
        'name'     => 'Tajikistan',
        'code'     => 'TJ',
        'phone'    => '+992',
        'currency' => 'TJS',
        'symbol'   => 'SM'
      ],
      'RO' => [
        'name'     => 'Romania',
        'code'     => 'RO',
        'phone'    => '+40',
        'currency' => 'RON',
        'symbol'   => 'RON'
      ],
      'TK' => [
        'name'     => 'Tokelau',
        'code'     => 'TK',
        'phone'    => '+690',
        'currency' => 'NZD',
        'symbol'   => '$'
      ],
      'GW' => [
        'name'     => 'Guinea-Bissau',
        'code'     => 'GW',
        'phone'    => '+245',
        'currency' => 'XOF',
        'symbol'   => 'CFA'
      ],
      'GU' => [
        'name'     => 'Guam',
        'code'     => 'GU',
        'phone'    => '+1-671',
        'currency' => 'USD',
        'symbol'   => '$'
      ],
      'GT' => [
        'name'     => 'Guatemala',
        'code'     => 'GT',
        'phone'    => '+502',
        'currency' => 'GTQ',
        'symbol'   => 'Q'
      ],
      'GS' => [
        'name'     => 'South Georgia and the South Sandwich Islands',
        'code'     => 'GS',
        'phone'    => '+500',
        'currency' => 'GBP',
        'symbol'   => '£'
      ],
      'GR' => [
        'name'     => 'Greece',
        'code'     => 'GR',
        'phone'    => '+30',
        'currency' => 'EUR',
        'symbol'   => '€'
      ],
      'GQ' => [
        'name'     => 'Equatorial Guinea',
        'code'     => 'GQ',
        'phone'    => '+240',
        'currency' => 'XAF',
        'symbol'   => 'FCFA'
      ],
      'GP' => [
        'name'     => 'Guadeloupe',
        'code'     => 'GP',
        'phone'    => '+590',
        'currency' => 'EUR',
        'symbol'   => '€'
      ],
      'JP' => [
        'name'     => 'Japan',
        'code'     => 'JP',
        'phone'    => '+81',
        'currency' => 'JPY',
        'symbol'   => '￥'
      ],
      'GY' => [
        'name'     => 'Guyana',
        'code'     => 'GY',
        'phone'    => '+592',
        'currency' => 'GYD',
        'symbol'   => '$'
      ],
      'GG' => [
        'name'     => 'Guernsey',
        'code'     => 'GG',
        'phone'    => '+44-1481',
        'currency' => 'GBP',
        'symbol'   => '£'
      ],
      'GF' => [
        'name'     => 'French Guiana',
        'code'     => 'GF',
        'phone'    => '+594',
        'currency' => 'EUR',
        'symbol'   => '€'
      ],
      'GE' => [
        'name'     => 'Georgia',
        'code'     => 'GE',
        'phone'    => '+995',
        'currency' => 'GEL',
        'symbol'   => 'GEL'
      ],
      'GD' => [
        'name'     => 'Grenada',
        'code'     => 'GD',
        'phone'    => '+1-473',
        'currency' => 'XCD',
        'symbol'   => '$'
      ],
      'GB' => [
        'name'     => 'United Kingdom',
        'code'     => 'GB',
        'phone'    => '+44',
        'currency' => 'GBP',
        'symbol'   => '£'
      ],
      'GA' => [
        'name'     => 'Gabon',
        'code'     => 'GA',
        'phone'    => '+241',
        'currency' => 'XAF',
        'symbol'   => 'FCFA'
      ],
      'SV' => [
        'name'     => 'El Salvador',
        'code'     => 'SV',
        'phone'    => '+503',
        'currency' => 'USD',
        'symbol'   => '$'
      ],
      'GN' => [
        'name'     => 'Guinea',
        'code'     => 'GN',
        'phone'    => '+224',
        'currency' => 'GNF',
        'symbol'   => 'FG'
      ],
      'GM' => [
        'name'     => 'Gambia',
        'code'     => 'GM',
        'phone'    => '+220',
        'currency' => 'GMD',
        'symbol'   => 'D'
      ],
      'GL' => [
        'name'     => 'Greenland',
        'code'     => 'GL',
        'phone'    => '+299',
        'currency' => 'DKK',
        'symbol'   => 'kr'
      ],
      'GI' => [
        'name'     => 'Gibraltar',
        'code'     => 'GI',
        'phone'    => '+350',
        'currency' => 'GIP',
        'symbol'   => '£'
      ],
      'GH' => [
        'name'     => 'Ghana',
        'code'     => 'GH',
        'phone'    => '+233',
        'currency' => 'GHS',
        'symbol'   => 'GH₵'
      ],
      'OM' => [
        'name'     => 'Oman',
        'code'     => 'OM',
        'phone'    => '+968',
        'currency' => 'OMR',
        'symbol'   => 'ر.ع.‏'
      ],
      'TN' => [
        'name'     => 'Tunisia',
        'code'     => 'TN',
        'phone'    => '+216',
        'currency' => 'TND',
        'symbol'   => 'د.ت.‏'
      ],
      'JO' => [
        'name'     => 'Jordan',
        'code'     => 'JO',
        'phone'    => '+962',
        'currency' => 'JOD',
        'symbol'   => 'د.أ.‏'
      ],
      'HR' => [
        'name'     => 'Croatia',
        'code'     => 'HR',
        'phone'    => '+385',
        'currency' => 'HRK',
        'symbol'   => 'kn'
      ],
      'HT' => [
        'name'     => 'Haiti',
        'code'     => 'HT',
        'phone'    => '+509',
        'currency' => 'HTG',
        'symbol'   => 'G'
      ],
      'HU' => [
        'name'     => 'Hungary',
        'code'     => 'HU',
        'phone'    => '+36',
        'currency' => 'HUF',
        'symbol'   => 'Ft'
      ],
      'HK' => [
        'name'     => 'Hong Kong',
        'code'     => 'HK',
        'phone'    => '+852',
        'currency' => 'HKD',
        'symbol'   => '$'
      ],
      'HN' => [
        'name'     => 'Honduras',
        'code'     => 'HN',
        'phone'    => '+504',
        'currency' => 'HNL',
        'symbol'   => 'L'
      ],
      'HM' => [
        'name'     => 'Heard Island and McDonald Islands',
        'code'     => 'HM',
        'phone'    => '+ ',
        'currency' => 'AUD',
        'symbol'   => '$'
      ],
      'VE' => [
        'name'     => 'Venezuela',
        'code'     => 'VE',
        'phone'    => '+58',
        'currency' => 'VEF',
        'symbol'   => 'Bs.F.'
      ],
      'PR' => [
        'name'     => 'Puerto Rico',
        'code'     => 'PR',
        'phone'    => '+1-787 and 1-939',
        'currency' => 'USD',
        'symbol'   => '$'
      ],
      'PS' => [
        'name'     => 'Palestinian Territory',
        'code'     => 'PS',
        'phone'    => '+970',
        'currency' => 'ILS',
        'symbol'   => '₪'
      ],
      'PW' => [
        'name'     => 'Palau',
        'code'     => 'PW',
        'phone'    => '+680',
        'currency' => 'USD',
        'symbol'   => '$'
      ],
      'PT' => [
        'name'     => 'Portugal',
        'code'     => 'PT',
        'phone'    => '+351',
        'currency' => 'EUR',
        'symbol'   => '€'
      ],
      'SJ' => [
        'name'     => 'Svalbard and Jan Mayen',
        'code'     => 'SJ',
        'phone'    => '+47',
        'currency' => 'NOK',
        'symbol'   => 'kr'
      ],
      'PY' => [
        'name'     => 'Paraguay',
        'code'     => 'PY',
        'phone'    => '+595',
        'currency' => 'PYG',
        'symbol'   => '₲'
      ],
      'IQ' => [
        'name'     => 'Iraq',
        'code'     => 'IQ',
        'phone'    => '+964',
        'currency' => 'IQD',
        'symbol'   => 'د.ع.‏'
      ],
      'PA' => [
        'name'     => 'Panama',
        'code'     => 'PA',
        'phone'    => '+507',
        'currency' => 'PAB',
        'symbol'   => 'B/.'
      ],
      'PF' => [
        'name'     => 'French Polynesia',
        'code'     => 'PF',
        'phone'    => '+689',
        'currency' => 'XPF',
        'symbol'   => 'F'
      ],
      'PG' => [
        'name'     => 'Papua New Guinea',
        'code'     => 'PG',
        'phone'    => '+675',
        'currency' => 'PGK',
        'symbol'   => 'K'
      ],
      'PE' => [
        'name'     => 'Peru',
        'code'     => 'PE',
        'phone'    => '+51',
        'currency' => 'PEN',
        'symbol'   => 'S/.'
      ],
      'PK' => [
        'name'     => 'Pakistan',
        'code'     => 'PK',
        'phone'    => '+92',
        'currency' => 'PKR',
        'symbol'   => '₨'
      ],
      'PH' => [
        'name'     => 'Philippines',
        'code'     => 'PH',
        'phone'    => '+63',
        'currency' => 'PHP',
        'symbol'   => '₱'
      ],
      'PN' => [
        'name'     => 'Pitcairn',
        'code'     => 'PN',
        'phone'    => '+870',
        'currency' => 'NZD',
        'symbol'   => '$'
      ],
      'PL' => [
        'name'     => 'Poland',
        'code'     => 'PL',
        'phone'    => '+48',
        'currency' => 'PLN',
        'symbol'   => 'zł'
      ],
      'PM' => [
        'name'     => 'Saint Pierre and Miquelon',
        'code'     => 'PM',
        'phone'    => '+508',
        'currency' => 'EUR',
        'symbol'   => '€'
      ],
      'ZM' => [
        'name'     => 'Zambia',
        'code'     => 'ZM',
        'phone'    => '+260',
        'currency' => 'ZMK',
        'symbol'   => ''
      ],
      'EH' => [
        'name'     => 'Western Sahara',
        'code'     => 'EH',
        'phone'    => '+212',
        'currency' => 'MAD',
        'symbol'   => 'د.م.‏'
      ],
      'EE' => [
        'name'     => 'Estonia',
        'code'     => 'EE',
        'phone'    => '+372',
        'currency' => 'EUR',
        'symbol'   => '€'
      ],
      'EG' => [
        'name'     => 'Egypt',
        'code'     => 'EG',
        'phone'    => '+20',
        'currency' => 'EGP',
        'symbol'   => 'ج.م.‏'
      ],
      'ZA' => [
        'name'     => 'South Africa',
        'code'     => 'ZA',
        'phone'    => '+27',
        'currency' => 'ZAR',
        'symbol'   => 'R'
      ],
      'EC' => [
        'name'     => 'Ecuador',
        'code'     => 'EC',
        'phone'    => '+593',
        'currency' => 'USD',
        'symbol'   => '$'
      ],
      'IT' => [
        'name'     => 'Italy',
        'code'     => 'IT',
        'phone'    => '+39',
        'currency' => 'EUR',
        'symbol'   => '€'
      ],
      'VN' => [
        'name'     => 'Vietnam',
        'code'     => 'VN',
        'phone'    => '+84',
        'currency' => 'VND',
        'symbol'   => '₫'
      ],
      'SB' => [
        'name'     => 'Solomon Islands',
        'code'     => 'SB',
        'phone'    => '+677',
        'currency' => 'SBD',
        'symbol'   => '$'
      ],
      'ET' => [
        'name'     => 'Ethiopia',
        'code'     => 'ET',
        'phone'    => '+251',
        'currency' => 'ETB',
        'symbol'   => 'Br'
      ],
      'SO' => [
        'name'     => 'Somalia',
        'code'     => 'SO',
        'phone'    => '+252',
        'currency' => 'SOS',
        'symbol'   => 'Ssh'
      ],
      'ZW' => [
        'name'     => 'Zimbabwe',
        'code'     => 'ZW',
        'phone'    => '+263',
        'currency' => 'ZWL',
        'symbol'   => 'ZWL$'
      ],
      'SA' => [
        'name'     => 'Saudi Arabia',
        'code'     => 'SA',
        'phone'    => '+966',
        'currency' => 'SAR',
        'symbol'   => 'ر.س.‏'
      ],
      'ES' => [
        'name'     => 'Spain',
        'code'     => 'ES',
        'phone'    => '+34',
        'currency' => 'EUR',
        'symbol'   => '€'
      ],
      'ER' => [
        'name'     => 'Eritrea',
        'code'     => 'ER',
        'phone'    => '+291',
        'currency' => 'ERN',
        'symbol'   => 'Nfk'
      ],
      'ME' => [
        'name'     => 'Montenegro',
        'code'     => 'ME',
        'phone'    => '+382',
        'currency' => 'EUR',
        'symbol'   => '€'
      ],
      'MD' => [
        'name'     => 'Moldova',
        'code'     => 'MD',
        'phone'    => '+373',
        'currency' => 'MDL',
        'symbol'   => 'MDL'
      ],
      'MG' => [
        'name'     => 'Madagascar',
        'code'     => 'MG',
        'phone'    => '+261',
        'currency' => 'MGA',
        'symbol'   => 'MGA'
      ],
      'MF' => [
        'name'     => 'Saint Martin',
        'code'     => 'MF',
        'phone'    => '+590',
        'currency' => 'EUR',
        'symbol'   => '€'
      ],
      'MA' => [
        'name'     => 'Morocco',
        'code'     => 'MA',
        'phone'    => '+212',
        'currency' => 'MAD',
        'symbol'   => 'د.م.‏'
      ],
      'MC' => [
        'name'     => 'Monaco',
        'code'     => 'MC',
        'phone'    => '+377',
        'currency' => 'EUR',
        'symbol'   => '€'
      ],
      'UZ' => [
        'name'     => 'Uzbekistan',
        'code'     => 'UZ',
        'phone'    => '+998',
        'currency' => 'UZS',
        'symbol'   => 'UZS'
      ],
      'MM' => [
        'name'     => 'Myanmar',
        'code'     => 'MM',
        'phone'    => '+95',
        'currency' => 'MMK',
        'symbol'   => 'K'
      ],
      'ML' => [
        'name'     => 'Mali',
        'code'     => 'ML',
        'phone'    => '+223',
        'currency' => 'XOF',
        'symbol'   => 'CFA'
      ],
      'MO' => [
        'name'     => 'Macao',
        'code'     => 'MO',
        'phone'    => '+853',
        'currency' => 'MOP',
        'symbol'   => 'MOP$'
      ],
      'MN' => [
        'name'     => 'Mongolia',
        'code'     => 'MN',
        'phone'    => '+976',
        'currency' => 'MNT',
        'symbol'   => '₮'
      ],
      'MH' => [
        'name'     => 'Marshall Islands',
        'code'     => 'MH',
        'phone'    => '+692',
        'currency' => 'USD',
        'symbol'   => '$'
      ],
      'MK' => [
        'name'     => 'Macedonia',
        'code'     => 'MK',
        'phone'    => '+389',
        'currency' => 'MKD',
        'symbol'   => 'MKD'
      ],
      'MU' => [
        'name'     => 'Mauritius',
        'code'     => 'MU',
        'phone'    => '+230',
        'currency' => 'MUR',
        'symbol'   => 'MURs'
      ],
      'MT' => [
        'name'     => 'Malta',
        'code'     => 'MT',
        'phone'    => '+356',
        'currency' => 'EUR',
        'symbol'   => '€'
      ],
      'MW' => [
        'name'     => 'Malawi',
        'code'     => 'MW',
        'phone'    => '+265',
        'currency' => 'MWK',
        'symbol'   => 'K'
      ],
      'MV' => [
        'name'     => 'Maldives',
        'code'     => 'MV',
        'phone'    => '+960',
        'currency' => 'MVR',
        'symbol'   => 'ރ'
      ],
      'MQ' => [
        'name'     => 'Martinique',
        'code'     => 'MQ',
        'phone'    => '+596',
        'currency' => 'EUR',
        'symbol'   => '€'
      ],
      'MP' => [
        'name'     => 'Northern Mariana Islands',
        'code'     => 'MP',
        'phone'    => '+1-670',
        'currency' => 'USD',
        'symbol'   => '$'
      ],
      'MS' => [
        'name'     => 'Montserrat',
        'code'     => 'MS',
        'phone'    => '+1-664',
        'currency' => 'XCD',
        'symbol'   => '$'
      ],
      'MR' => [
        'name'     => 'Mauritania',
        'code'     => 'MR',
        'phone'    => '+222',
        'currency' => 'MRO',
        'symbol'   => null
      ],
      'IM' => [
        'name'     => 'Isle of Man',
        'code'     => 'IM',
        'phone'    => '+44-1624',
        'currency' => 'GBP',
        'symbol'   => '£'
      ],
      'UG' => [
        'name'     => 'Uganda',
        'code'     => 'UG',
        'phone'    => '+256',
        'currency' => 'UGX',
        'symbol'   => 'USh'
      ],
      'TZ' => [
        'name'     => 'Tanzania',
        'code'     => 'TZ',
        'phone'    => '+255',
        'currency' => 'TZS',
        'symbol'   => 'TSh'
      ],
      'MY' => [
        'name'     => 'Malaysia',
        'code'     => 'MY',
        'phone'    => '+60',
        'currency' => 'MYR',
        'symbol'   => 'RM'
      ],
      'MX' => [
        'name'     => 'Mexico',
        'code'     => 'MX',
        'phone'    => '+52',
        'currency' => 'MXN',
        'symbol'   => '$'
      ],
      'IL' => [
        'name'     => 'Israel',
        'code'     => 'IL',
        'phone'    => '+972',
        'currency' => 'ILS',
        'symbol'   => '₪'
      ],
      'FR' => [
        'name'     => 'France',
        'code'     => 'FR',
        'phone'    => '+33',
        'currency' => 'EUR',
        'symbol'   => '€'
      ],
      'IO' => [
        'name'     => 'British Indian Ocean Territory',
        'code'     => 'IO',
        'phone'    => '+246',
        'currency' => 'USD',
        'symbol'   => '$'
      ],
      'SH' => [
        'name'     => 'Saint Helena',
        'code'     => 'SH',
        'phone'    => '+290',
        'currency' => 'SHP',
        'symbol'   => '£'
      ],
      'FI' => [
        'name'     => 'Finland',
        'code'     => 'FI',
        'phone'    => '+358',
        'currency' => 'EUR',
        'symbol'   => '€'
      ],
      'FJ' => [
        'name'     => 'Fiji',
        'code'     => 'FJ',
        'phone'    => '+679',
        'currency' => 'FJD',
        'symbol'   => '$'
      ],
      'FK' => [
        'name'     => 'Falkland Islands',
        'code'     => 'FK',
        'phone'    => '+500',
        'currency' => 'FKP',
        'symbol'   => '£'
      ],
      'FM' => [
        'name'     => 'Micronesia',
        'code'     => 'FM',
        'phone'    => '+691',
        'currency' => 'USD',
        'symbol'   => '$'
      ],
      'FO' => [
        'name'     => 'Faroe Islands',
        'code'     => 'FO',
        'phone'    => '+298',
        'currency' => 'DKK',
        'symbol'   => 'kr'
      ],
      'NI' => [
        'name'     => 'Nicaragua',
        'code'     => 'NI',
        'phone'    => '+505',
        'currency' => 'NIO',
        'symbol'   => 'C$'
      ],
      'NL' => [
        'name'     => 'Netherlands',
        'code'     => 'NL',
        'phone'    => '+31',
        'currency' => 'EUR',
        'symbol'   => '€'
      ],
      'NO' => [
        'name'     => 'Norway',
        'code'     => 'NO',
        'phone'    => '+47',
        'currency' => 'NOK',
        'symbol'   => 'kr'
      ],
      'NA' => [
        'name'     => 'Namibia',
        'code'     => 'NA',
        'phone'    => '+264',
        'currency' => 'NAD',
        'symbol'   => 'N$'
      ],
      'VU' => [
        'name'     => 'Vanuatu',
        'code'     => 'VU',
        'phone'    => '+678',
        'currency' => 'VUV',
        'symbol'   => 'VT'
      ],
      'NC' => [
        'name'     => 'New Caledonia',
        'code'     => 'NC',
        'phone'    => '+687',
        'currency' => 'XPF',
        'symbol'   => 'F'
      ],
      'NE' => [
        'name'     => 'Niger',
        'code'     => 'NE',
        'phone'    => '+227',
        'currency' => 'XOF',
        'symbol'   => 'CFA'
      ],
      'NF' => [
        'name'     => 'Norfolk Island',
        'code'     => 'NF',
        'phone'    => '+672',
        'currency' => 'AUD',
        'symbol'   => '$'
      ],
      'NG' => [
        'name'     => 'Nigeria',
        'code'     => 'NG',
        'phone'    => '+234',
        'currency' => 'NGN',
        'symbol'   => '₦'
      ],
      'NZ' => [
        'name'     => 'New Zealand',
        'code'     => 'NZ',
        'phone'    => '+64',
        'currency' => 'NZD',
        'symbol'   => '$'
      ],
      'NP' => [
        'name'     => 'Nepal',
        'code'     => 'NP',
        'phone'    => '+977',
        'currency' => 'NPR',
        'symbol'   => 'नेरू'
      ],
      'NR' => [
        'name'     => 'Nauru',
        'code'     => 'NR',
        'phone'    => '+674',
        'currency' => 'AUD',
        'symbol'   => '$'
      ],
      'NU' => [
        'name'     => 'Niue',
        'code'     => 'NU',
        'phone'    => '+683',
        'currency' => 'NZD',
        'symbol'   => '$'
      ],
      'CK' => [
        'name'     => 'Cook Islands',
        'code'     => 'CK',
        'phone'    => '+682',
        'currency' => 'NZD',
        'symbol'   => '$'
      ],
      'XK' => [
        'name'     => 'Kosovo',
        'code'     => 'XK',
        'phone'    => '+381',
        'currency' => 'EUR',
        'symbol'   => '€'
      ],
      'CI' => [
        'name'     => 'Ivory Coast',
        'code'     => 'CI',
        'phone'    => '+225',
        'currency' => 'XOF',
        'symbol'   => 'CFA'
      ],
      'CH' => [
        'name'     => 'Switzerland',
        'code'     => 'CH',
        'phone'    => '+41',
        'currency' => 'CHF',
        'symbol'   => 'CHF'
      ],
      'CO' => [
        'name'     => 'Colombia',
        'code'     => 'CO',
        'phone'    => '+57',
        'currency' => 'COP',
        'symbol'   => '$'
      ],
      'CN' => [
        'name'     => 'China',
        'code'     => 'CN',
        'phone'    => '+86',
        'currency' => 'CNY',
        'symbol'   => 'CN¥'
      ],
      'CM' => [
        'name'     => 'Cameroon',
        'code'     => 'CM',
        'phone'    => '+237',
        'currency' => 'XAF',
        'symbol'   => 'FCFA'
      ],
      'CL' => [
        'name'     => 'Chile',
        'code'     => 'CL',
        'phone'    => '+56',
        'currency' => 'CLP',
        'symbol'   => '$'
      ],
      'CC' => [
        'name'     => 'Cocos Islands',
        'code'     => 'CC',
        'phone'    => '+61',
        'currency' => 'AUD',
        'symbol'   => '$'
      ],
      'CA' => [
        'name'     => 'Canada',
        'code'     => 'CA',
        'phone'    => '+1',
        'currency' => 'CAD',
        'symbol'   => '$'
      ],
      'CG' => [
        'name'     => 'Republic of the Congo',
        'code'     => 'CG',
        'phone'    => '+242',
        'currency' => 'XAF',
        'symbol'   => 'FCFA'
      ],
      'CF' => [
        'name'     => 'Central African Republic',
        'code'     => 'CF',
        'phone'    => '+236',
        'currency' => 'XAF',
        'symbol'   => 'FCFA'
      ],
      'CD' => [
        'name'     => 'Democratic Republic of the Congo',
        'code'     => 'CD',
        'phone'    => '+243',
        'currency' => 'CDF',
        'symbol'   => 'FrCD'
      ],
      'CZ' => [
        'name'     => 'Czech Republic',
        'code'     => 'CZ',
        'phone'    => '+420',
        'currency' => 'CZK',
        'symbol'   => 'Kč'
      ],
      'CY' => [
        'name'     => 'Cyprus',
        'code'     => 'CY',
        'phone'    => '+357',
        'currency' => 'EUR',
        'symbol'   => '€'
      ],
      'CX' => [
        'name'     => 'Christmas Island',
        'code'     => 'CX',
        'phone'    => '+61',
        'currency' => 'AUD',
        'symbol'   => '$'
      ],
      'CR' => [
        'name'     => 'Costa Rica',
        'code'     => 'CR',
        'phone'    => '+506',
        'currency' => 'CRC',
        'symbol'   => '₡'
      ],
      'CW' => [
        'name'     => 'Curacao',
        'code'     => 'CW',
        'phone'    => '+599',
        'currency' => 'ANG',
        'symbol'   => 'ƒ'
      ],
      'CV' => [
        'name'     => 'Cape Verde',
        'code'     => 'CV',
        'phone'    => '+238',
        'currency' => 'CVE',
        'symbol'   => 'CV$'
      ],
      'CU' => [
        'name'     => 'Cuba',
        'code'     => 'CU',
        'phone'    => '+53',
        'currency' => 'CUP',
        'symbol'   => '₱'
      ],
      'SZ' => [
        'name'     => 'Swaziland',
        'code'     => 'SZ',
        'phone'    => '+268',
        'currency' => 'SZL',
        'symbol'   => 'L'
      ],
      'SY' => [
        'name'     => 'Syria',
        'code'     => 'SY',
        'phone'    => '+963',
        'currency' => 'SYP',
        'symbol'   => 'ل.س.‏'
      ],
      'SX' => [
        'name'     => 'Sint Maarten',
        'code'     => 'SX',
        'phone'    => '+599',
        'currency' => 'ANG',
        'symbol'   => 'ƒ'
      ],
      'KG' => [
        'name'     => 'Kyrgyzstan',
        'code'     => 'KG',
        'phone'    => '+996',
        'currency' => 'KGS',
        'symbol'   => 'С̲ '
      ],
      'KE' => [
        'name'     => 'Kenya',
        'code'     => 'KE',
        'phone'    => '+254',
        'currency' => 'KES',
        'symbol'   => 'Ksh'
      ],
      'SS' => [
        'name'     => 'South Sudan',
        'code'     => 'SS',
        'phone'    => '+211',
        'currency' => 'SSP',
        'symbol'   => '£'
      ],
      'SR' => [
        'name'     => 'Suriname',
        'code'     => 'SR',
        'phone'    => '+597',
        'currency' => 'SRD',
        'symbol'   => '$'
      ],
      'KI' => [
        'name'     => 'Kiribati',
        'code'     => 'KI',
        'phone'    => '+686',
        'currency' => 'AUD',
        'symbol'   => '$'
      ],
      'KH' => [
        'name'     => 'Cambodia',
        'code'     => 'KH',
        'phone'    => '+855',
        'currency' => 'KHR',
        'symbol'   => '៛'
      ],
      'KN' => [
        'name'     => 'Saint Kitts and Nevis',
        'code'     => 'KN',
        'phone'    => '+1-869',
        'currency' => 'XCD',
        'symbol'   => '$'
      ],
      'KM' => [
        'name'     => 'Comoros',
        'code'     => 'KM',
        'phone'    => '+269',
        'currency' => 'KMF',
        'symbol'   => 'FC'
      ],
      'ST' => [
        'name'     => 'Sao Tome and Principe',
        'code'     => 'ST',
        'phone'    => '+239',
        'currency' => 'STD',
        'symbol'   => null
      ],
      'SK' => [
        'name'     => 'Slovakia',
        'code'     => 'SK',
        'phone'    => '+421',
        'currency' => 'EUR',
        'symbol'   => '€'
      ],
      'KR' => [
        'name'     => 'South Korea',
        'code'     => 'KR',
        'phone'    => '+82',
        'currency' => 'KRW',
        'symbol'   => '₩'
      ],
      'SI' => [
        'name'     => 'Slovenia',
        'code'     => 'SI',
        'phone'    => '+386',
        'currency' => 'EUR',
        'symbol'   => '€'
      ],
      'KP' => [
        'name'     => 'North Korea',
        'code'     => 'KP',
        'phone'    => '+850',
        'currency' => 'KPW',
        'symbol'   => '₩'
      ],
      'KW' => [
        'name'     => 'Kuwait',
        'code'     => 'KW',
        'phone'    => '+965',
        'currency' => 'KWD',
        'symbol'   => 'د.ك.‏'
      ],
      'SN' => [
        'name'     => 'Senegal',
        'code'     => 'SN',
        'phone'    => '+221',
        'currency' => 'XOF',
        'symbol'   => 'CFA'
      ],
      'SM' => [
        'name'     => 'San Marino',
        'code'     => 'SM',
        'phone'    => '+378',
        'currency' => 'EUR',
        'symbol'   => '€'
      ],
      'SL' => [
        'name'     => 'Sierra Leone',
        'code'     => 'SL',
        'phone'    => '+232',
        'currency' => 'SLL',
        'symbol'   => 'Le'
      ],
      'SC' => [
        'name'     => 'Seychelles',
        'code'     => 'SC',
        'phone'    => '+248',
        'currency' => 'SCR',
        'symbol'   => 'SR'
      ],
      'KZ' => [
        'name'     => 'Kazakhstan',
        'code'     => 'KZ',
        'phone'    => '+7',
        'currency' => 'KZT',
        'symbol'   => 'тңг.'
      ],
      'KY' => [
        'name'     => 'Cayman Islands',
        'code'     => 'KY',
        'phone'    => '+1-345',
        'currency' => 'KYD',
        'symbol'   => '$'
      ],
      'SG' => [
        'name'     => 'Singapore',
        'code'     => 'SG',
        'phone'    => '+65',
        'currency' => 'SGD',
        'symbol'   => '$'
      ],
      'SE' => [
        'name'     => 'Sweden',
        'code'     => 'SE',
        'phone'    => '+46',
        'currency' => 'SEK',
        'symbol'   => 'kr'
      ],
      'SD' => [
        'name'     => 'Sudan',
        'code'     => 'SD',
        'phone'    => '+249',
        'currency' => 'SDG',
        'symbol'   => 'SDG'
      ],
      'DO' => [
        'name'     => 'Dominican Republic',
        'code'     => 'DO',
        'phone'    => '+1-809 and 1-829',
        'currency' => 'DOP',
        'symbol'   => 'RD$'
      ],
      'DM' => [
        'name'     => 'Dominica',
        'code'     => 'DM',
        'phone'    => '+1-767',
        'currency' => 'XCD',
        'symbol'   => '$'
      ],
      'DJ' => [
        'name'     => 'Djibouti',
        'code'     => 'DJ',
        'phone'    => '+253',
        'currency' => 'DJF',
        'symbol'   => 'Fdj'
      ],
      'DK' => [
        'name'     => 'Denmark',
        'code'     => 'DK',
        'phone'    => '+45',
        'currency' => 'DKK',
        'symbol'   => 'kr'
      ],
      'VG' => [
        'name'     => 'British Virgin Islands',
        'code'     => 'VG',
        'phone'    => '+1-284',
        'currency' => 'USD',
        'symbol'   => '$'
      ],
      'DE' => [
        'name'     => 'Germany',
        'code'     => 'DE',
        'phone'    => '+49',
        'currency' => 'EUR',
        'symbol'   => '€'
      ],
      'YE' => [
        'name'     => 'Yemen',
        'code'     => 'YE',
        'phone'    => '+967',
        'currency' => 'YER',
        'symbol'   => 'ر.ي.‏'
      ],
      'DZ' => [
        'name'     => 'Algeria',
        'code'     => 'DZ',
        'phone'    => '+213',
        'currency' => 'DZD',
        'symbol'   => 'د.ج.‏'
      ],
      'US' => [
        'name'     => 'United States',
        'code'     => 'US',
        'phone'    => '+1',
        'currency' => 'USD',
        'symbol'   => '$'
      ],
      'UY' => [
        'name'     => 'Uruguay',
        'code'     => 'UY',
        'phone'    => '+598',
        'currency' => 'UYU',
        'symbol'   => '$'
      ],
      'YT' => [
        'name'     => 'Mayotte',
        'code'     => 'YT',
        'phone'    => '+262',
        'currency' => 'EUR',
        'symbol'   => '€'
      ],
      'UM' => [
        'name'     => 'United States Minor Outlying Islands',
        'code'     => 'UM',
        'phone'    => '+1',
        'currency' => 'USD',
        'symbol'   => '$'
      ],
      'LB' => [
        'name'     => 'Lebanon',
        'code'     => 'LB',
        'phone'    => '+961',
        'currency' => 'LBP',
        'symbol'   => 'ل.ل.‏'
      ],
      'LC' => [
        'name'     => 'Saint Lucia',
        'code'     => 'LC',
        'phone'    => '+1-758',
        'currency' => 'XCD',
        'symbol'   => '$'
      ],
      'LA' => [
        'name'     => 'Laos',
        'code'     => 'LA',
        'phone'    => '+856',
        'currency' => 'LAK',
        'symbol'   => '₭'
      ],
      'TV' => [
        'name'     => 'Tuvalu',
        'code'     => 'TV',
        'phone'    => '+688',
        'currency' => 'AUD',
        'symbol'   => '$'
      ],
      'TW' => [
        'name'     => 'Taiwan',
        'code'     => 'TW',
        'phone'    => '+886',
        'currency' => 'TWD',
        'symbol'   => 'NT$'
      ],
      'TT' => [
        'name'     => 'Trinidad and Tobago',
        'code'     => 'TT',
        'phone'    => '+1-868',
        'currency' => 'TTD',
        'symbol'   => '$'
      ],
      'TR' => [
        'name'     => 'Turkey',
        'code'     => 'TR',
        'phone'    => '+90',
        'currency' => 'TRY',
        'symbol'   => 'TL'
      ],
      'LK' => [
        'name'     => 'Sri Lanka',
        'code'     => 'LK',
        'phone'    => '+94',
        'currency' => 'LKR',
        'symbol'   => 'SL Re'
      ],
      'LI' => [
        'name'     => 'Liechtenstein',
        'code'     => 'LI',
        'phone'    => '+423',
        'currency' => 'CHF',
        'symbol'   => 'CHF'
      ],
      'LV' => [
        'name'     => 'Latvia',
        'code'     => 'LV',
        'phone'    => '+371',
        'currency' => 'EUR',
        'symbol'   => '€'
      ],
      'TO' => [
        'name'     => 'Tonga',
        'code'     => 'TO',
        'phone'    => '+676',
        'currency' => 'TOP',
        'symbol'   => 'T$'
      ],
      'LT' => [
        'name'     => 'Lithuania',
        'code'     => 'LT',
        'phone'    => '+370',
        'currency' => 'LTL',
        'symbol'   => ''
      ],
      'LU' => [
        'name'     => 'Luxembourg',
        'code'     => 'LU',
        'phone'    => '+352',
        'currency' => 'EUR',
        'symbol'   => '€'
      ],
      'LR' => [
        'name'     => 'Liberia',
        'code'     => 'LR',
        'phone'    => '+231',
        'currency' => 'LRD',
        'symbol'   => '$'
      ],
      'LS' => [
        'name'     => 'Lesotho',
        'code'     => 'LS',
        'phone'    => '+266',
        'currency' => 'LSL',
        'symbol'   => 'L'
      ],
      'TH' => [
        'name'     => 'Thailand',
        'code'     => 'TH',
        'phone'    => '+66',
        'currency' => 'THB',
        'symbol'   => '฿'
      ],
      'TF' => [
        'name'     => 'French Southern Territories',
        'code'     => 'TF',
        'phone'    => '+262',
        'currency' => 'EUR',
        'symbol'   => '€'
      ],
      'TG' => [
        'name'     => 'Togo',
        'code'     => 'TG',
        'phone'    => '+228',
        'currency' => 'XOF',
        'symbol'   => 'CFA'
      ],
      'TD' => [
        'name'     => 'Chad',
        'code'     => 'TD',
        'phone'    => '+235',
        'currency' => 'XAF',
        'symbol'   => 'FCFA'
      ],
      'TC' => [
        'name'     => 'Turks and Caicos Islands',
        'code'     => 'TC',
        'phone'    => '+1-649',
        'currency' => 'USD',
        'symbol'   => '$'
      ],
      'LY' => [
        'name'     => 'Libya',
        'code'     => 'LY',
        'phone'    => '+218',
        'currency' => 'LYD',
        'symbol'   => 'د.ل.‏'
      ],
      'VA' => [
        'name'     => 'Vatican',
        'code'     => 'VA',
        'phone'    => '+379',
        'currency' => 'EUR',
        'symbol'   => '€'
      ],
      'VC' => [
        'name'     => 'Saint Vincent and the Grenadines',
        'code'     => 'VC',
        'phone'    => '+1-784',
        'currency' => 'XCD',
        'symbol'   => '$'
      ],
      'AE' => [
        'name'     => 'United Arab Emirates',
        'code'     => 'AE',
        'phone'    => '+971',
        'currency' => 'AED',
        'symbol'   => 'د.إ.‏'
      ],
      'AD' => [
        'name'     => 'Andorra',
        'code'     => 'AD',
        'phone'    => '+376',
        'currency' => 'EUR',
        'symbol'   => '€'
      ],
      'AG' => [
        'name'     => 'Antigua and Barbuda',
        'code'     => 'AG',
        'phone'    => '+1-268',
        'currency' => 'XCD',
        'symbol'   => '$'
      ],
      'AF' => [
        'name'     => 'Afghanistan',
        'code'     => 'AF',
        'phone'    => '+93',
        'currency' => 'AFN',
        'symbol'   => '؋'
      ],
      'AI' => [
        'name'     => 'Anguilla',
        'code'     => 'AI',
        'phone'    => '+1-264',
        'currency' => 'XCD',
        'symbol'   => '$'
      ],
      'VI' => [
        'name'     => 'U.S. Virgin Islands',
        'code'     => 'VI',
        'phone'    => '+1-340',
        'currency' => 'USD',
        'symbol'   => '$'
      ],
      'IS' => [
        'name'     => 'Iceland',
        'code'     => 'IS',
        'phone'    => '+354',
        'currency' => 'ISK',
        'symbol'   => 'kr'
      ],
      'IR' => [
        'name'     => 'Iran',
        'code'     => 'IR',
        'phone'    => '+98',
        'currency' => 'IRR',
        'symbol'   => '﷼'
      ],
      'AM' => [
        'name'     => 'Armenia',
        'code'     => 'AM',
        'phone'    => '+374',
        'currency' => 'AMD',
        'symbol'   => 'դր.'
      ],
      'AL' => [
        'name'     => 'Albania',
        'code'     => 'AL',
        'phone'    => '+355',
        'currency' => 'ALL',
        'symbol'   => 'Lek'
      ],
      'AO' => [
        'name'     => 'Angola',
        'code'     => 'AO',
        'phone'    => '+244',
        'currency' => 'AOA',
        'symbol'   => 'Kz'
      ],
      'AQ' => [
        'name'     => 'Antarctica',
        'code'     => 'AQ',
        'phone'    => '+672',
        'currency' => 'AAD',
        'symbol'   => ''
      ],
      'AS' => [
        'name'     => 'American Samoa',
        'code'     => 'AS',
        'phone'    => '+1-684',
        'currency' => 'USD',
        'symbol'   => '$'
      ],
      'AR' => [
        'name'     => 'Argentina',
        'code'     => 'AR',
        'phone'    => '+54',
        'currency' => 'ARS',
        'symbol'   => '$'
      ],
      'AU' => [
        'name'     => 'Australia',
        'code'     => 'AU',
        'phone'    => '+61',
        'currency' => 'AUD',
        'symbol'   => '$'
      ],
      'AT' => [
        'name'     => 'Austria',
        'code'     => 'AT',
        'phone'    => '+43',
        'currency' => 'EUR',
        'symbol'   => '€'
      ],
      'AW' => [
        'name'     => 'Aruba',
        'code'     => 'AW',
        'phone'    => '+297',
        'currency' => 'AWG',
        'symbol'   => 'Afl.'
      ],
      'IN' => [
        'name'     => 'India',
        'code'     => 'IN',
        'phone'    => '+91',
        'currency' => 'INR',
        'symbol'   => '₹'
      ],
      'AX' => [
        'name'     => 'Aland Islands',
        'code'     => 'AX',
        'phone'    => '+358-18',
        'currency' => 'EUR',
        'symbol'   => '€'
      ],
      'AZ' => [
        'name'     => 'Azerbaijan',
        'code'     => 'AZ',
        'phone'    => '+994',
        'currency' => 'AZN',
        'symbol'   => 'ман.'
      ],
      'IE' => [
        'name'     => 'Ireland',
        'code'     => 'IE',
        'phone'    => '+353',
        'currency' => 'EUR',
        'symbol'   => '€'
      ],
      'ID' => [
        'name'     => 'Indonesia',
        'code'     => 'ID',
        'phone'    => '+62',
        'currency' => 'IDR',
        'symbol'   => 'Rp'
      ],
      'UA' => [
        'name'     => 'Ukraine',
        'code'     => 'UA',
        'phone'    => '+380',
        'currency' => 'UAH',
        'symbol'   => '₴'
      ],
      'QA' => [
        'name'     => 'Qatar',
        'code'     => 'QA',
        'phone'    => '+974',
        'currency' => 'QAR',
        'symbol'   => 'ر.ق.‏'
      ],
      'MZ' => [
        'name'     => 'Mozambique',
        'code'     => 'MZ',
        'phone'    => '+258',
        'currency' => 'MZN',
        'symbol'   => 'MTn'
      ]
    ];
    return $return;
  }

  /**
  | Group | ดึงข้อมูลประเทศ
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
  | Group | ดึงข้อมูลประเทศ
  | Description | 3.get ข้อมูลประเทศด้วย code
  | $code | รหัสประเทศ
   */
  public static function get($code) {
    $countries = static::select();
    if ($code) {
      $code = strtoupper(trim("{$code}"));
    }
    $country = isset($countries[$code]) ? $countries[$code] : [];
    return $country;
  }
}