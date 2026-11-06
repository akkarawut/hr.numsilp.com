<?php

use JetBrains\PhpStorm\Language;
use Mpdf\Tag\Tr;

class SCT
{
  public static function get($key)
  {
    if (isset($_COOKIE[$key])) {
      return $_COOKIE[$key];
    } else {
      return '';
    }
  }
  public static function set($key, $value)
  {
    setcookie($key, $value, time() + (86400 * 30), "/");
    $_COOKIE[$key] = $value;
  }
}
class Translaty
{
  public static $default_lg = '';

  public static function setDefaultLg($lg)
  {
    SCT::set('lg_default', $lg);
    //? write default language in file system
    $data = manageFile::checkRead('system');
    $data['lg_default'] = $lg;
    manageFile::writeFile('system', $data);
    return self::$default_lg;
  }

  public static function getDefaultLg()
  {
    if (self::$default_lg == '') {
      if (Translaty::is_install()) {
        //? check in file system
        $data = manageFile::checkRead('system');

        if (!isset($data['lg_default']) ||  $data['lg_default'] == '') {
          //? default language form cookie if file have not.
          $default_lg =   SCT::get('lg_default');
        } else {
          //? default language form file
          $default_lg = $data['lg_default'];
        }
      } else {
        //? default language form cookie if file have not and module not install.
        $default_lg =   SCT::get('lg_default');
      }
      //? last default language is null it will setted to englsih language.
      $default_lg == '';
      self::$default_lg = $default_lg;
    }
    return self::$default_lg;
  }

  public static $list_language = array(
    'af' => 'Afrikaans',
    'sq' => 'Albanian - shqip',
    'am' => 'Amharic - አማርኛ',
    'ar' => 'Arabic - العربية',
    'an' => 'Aragonese - aragonés',
    'hy' => 'Armenian - հայերեն',
    'ast' => 'Asturian - asturianu',
    'az' => 'Azerbaijani - azərbaycan dili',
    'eu' => 'Basque - euskara',
    'be' => 'Belarusian - беларуская',
    'bn' => 'Bengali - বাংলা',
    'bs' => 'Bosnian - bosanski',
    'br' => 'Breton - brezhoneg',
    'bg' => 'Bulgarian - български',
    'ca' => 'Catalan - català',
    'ckb' => 'Central Kurdish - کوردی (دەستنوسی عەرەبی)',
    'zh' => 'Chinese - 中文',
    'zh-HK' => 'Chinese (Hong Kong) - 中文（香港）',
    'zh-CN' => 'Chinese (Simplified) - 中文（简体）',
    'zh-TW' => 'Chinese (Traditional) - 中文（繁體）',
    'co' => 'Corsican',
    'hr' => 'Croatian - hrvatski',
    'cs' => 'Czech - čeština',
    'da' => 'Danish - dansk',
    'nl' => 'Dutch - Nederlands',
    'en' => 'English',
    'en-AU' => 'English (Australia)',
    'en-CA' => 'English (Canada)',
    'en-IN' => 'English (India)',
    'en-NZ' => 'English (New Zealand)',
    'en-ZA' => 'English (South Africa)',
    'en-GB' => 'English (United Kingdom)',
    'en-US' => 'English (United States)',
    'eo' => 'Esperanto - esperanto',
    'et' => 'Estonian - eesti',
    'fo' => 'Faroese - føroyskt',
    'fil' => 'Filipino',
    'fi' => 'Finnish - suomi',
    'fr' => 'French - français',
    'fr-CA' => 'French (Canada) - français (Canada)',
    'fr-FR' => 'French (France) - français (France)',
    'fr-CH' => 'French (Switzerland) - français (Suisse)',
    'gl' => 'Galician - galego',
    'ka' => 'Georgian - ქართული',
    'de' => 'German - Deutsch',
    'de-AT' => 'German (Austria) - Deutsch (Österreich)',
    'de-DE' => 'German (Germany) - Deutsch (Deutschland)',
    'de-LI' => 'German (Liechtenstein) - Deutsch (Liechtenstein)',
    'de-CH' => 'German (Switzerland) - Deutsch (Schweiz)',
    'el' => 'Greek - Ελληνικά',
    'gn' => 'Guarani',
    'gu' => 'Gujarati - ગુજરાતી',
    'ha' => 'Hausa',
    'haw' => 'Hawaiian - ʻŌlelo Hawaiʻi',
    'he' => 'Hebrew - עברית',
    'hi' => 'Hindi - हिन्दी',
    'hu' => 'Hungarian - magyar',
    'is' => 'Icelandic - íslenska',
    'id' => 'Indonesian - Indonesia',
    'ia' => 'Interlingua',
    'ga' => 'Irish - Gaeilge',
    'it' => 'Italian - italiano',
    'it-IT' => 'Italian (Italy) - italiano (Italia)',
    'it-CH' => 'Italian (Switzerland) - italiano (Svizzera)',
    'jp' => 'Japanese - 日本語',
    'kn' => 'Kannada - ಕನ್ನಡ',
    'kk' => 'Kazakh - қазақ тілі',
    'km' => 'Khmer - ខ្មែរ',
    'ko' => 'Korean - 한국어',
    'ku' => 'Kurdish - Kurdî',
    'ky' => 'Kyrgyz - кыргызча',
    'lo' => 'Lao - ລາວ',
    'la' => 'Latin',
    'lv' => 'Latvian - latviešu',
    'ln' => 'Lingala - lingála',
    'lt' => 'Lithuanian - lietuvių',
    'mk' => 'Macedonian - македонски',
    'ms' => 'Malay - Bahasa Melayu',
    'ml' => 'Malayalam - മലയാളം',
    'mt' => 'Maltese - Malti',
    'mr' => 'Marathi - मराठी',
    'mn' => 'Mongolian - монгол',
    'ne' => 'Nepali - नेपाली',
    'no' => 'Norwegian - norsk',
    'nb' => 'Norwegian Bokmål - norsk bokmål',
    'nn' => 'Norwegian Nynorsk - nynorsk',
    'oc' => 'Occitan',
    'or' => 'Oriya - ଓଡ଼ିଆ',
    'om' => 'Oromo - Oromoo',
    'ps' => 'Pashto - پښتو',
    'fa' => 'Persian - فارسی',
    'pl' => 'Polish - polski',
    'pt' => 'Portuguese - português',
    'pt-BR' => 'Portuguese (Brazil) - português (Brasil)',
    'pt-PT' => 'Portuguese (Portugal) - português (Portugal)',
    'pa' => 'Punjabi - ਪੰਜਾਬੀ',
    'qu' => 'Quechua',
    'ro' => 'Romanian - română',
    'mo' => 'Romanian (Moldova) - română (Moldova)',
    'rm' => 'Romansh - rumantsch',
    'ru' => 'Russian - русский',
    'gd' => 'Scottish Gaelic',
    'sr' => 'Serbian - српски',
    'sh' => 'Serbo-Croatian - Srpskohrvatski',
    'sn' => 'Shona - chiShona',
    'sd' => 'Sindhi',
    'si' => 'Sinhala - සිංහල',
    'sk' => 'Slovak - slovenčina',
    'sl' => 'Slovenian - slovenščina',
    'so' => 'Somali - Soomaali',
    'st' => 'Southern Sotho',
    'es' => 'Spanish - español',
    'es-AR' => 'Spanish (Argentina) - español (Argentina)',
    'es-419' => 'Spanish (Latin America) - español (Latinoamérica)',
    'es-MX' => 'Spanish (Mexico) - español (México)',
    'es-ES' => 'Spanish (Spain) - español (España)',
    'es-US' => 'Spanish (United States) - español (Estados Unidos)',
    'su' => 'Sundanese',
    'sw' => 'Swahili - Kiswahili',
    'sv' => 'Swedish - svenska',
    'tg' => 'Tajik - тоҷикӣ',
    'ta' => 'Tamil - தமிழ்',
    'tt' => 'Tatar',
    'te' => 'Telugu - తెలుగు',
    'th' => 'Thai - ไทย',
    'ti' => 'Tigrinya - ትግርኛ',
    'to' => 'Tongan - lea fakatonga',
    'tr' => 'Turkish - Türkçe',
    'tk' => 'Turkmen',
    'tw' => 'Twi',
    'uk' => 'Ukrainian - українська',
    'ur' => 'Urdu - اردو',
    'ug' => 'Uyghur',
    'uz' => 'Uzbek - o‘zbek',
    'vi' => 'Vietnamese - Tiếng Việt',
    'wa' => 'Walloon - wa',
    'cy' => 'Welsh - Cymraeg',
    'fy' => 'Western Frisian',
    'xh' => 'Xhosa',
    'yi' => 'Yiddish',
    'yo' => 'Yoruba - Èdè Yorùbá',
    'zu' => 'Zulu - isiZulu'
  );

  public static function getlanguageList()
  {
    return self::$list_language;
  }

  public static function getKeyLanguageList()
  {
    return array_keys(self::$list_language);
  }

  public static $api_to_module_language = array(
    'thth' => 'th',
    'enus' => 'en',
    "zhcn" => "zh-CN"
  );

  public static function getLanguageApitoModule()
  {
    return self::$api_to_module_language;
  }

  public static $curr_language;

  public static function getlanguage()
  {
    if (self::$curr_language == '') {
      self::$curr_language = 'en';
    }
    return self::$curr_language;
  }

  public static function setLanguage($language)
  {
    self::$curr_language = $language;
  }

  public static function getCurrentLanguage($lg = '')
  {
    $path = Translaty::getPrefixPath();
    $has_file = file_exists($path);
    if ($has_file) {
      $filename = scandir($path, 1);
      $true_filename = Translaty::getKeyLanguageList();
      $curr_language_list = [];
      foreach ($filename as $filenames) {
        $search = '.json';
        $filenames = str_replace($search, '', $filenames);
        if (in_array($filenames, $true_filename)) {
          $curr_language_list[] = $filenames;
        }
      }
      // ? index langauge
      $lg == '' ? $lg = 'en' : $lg;
      usort($curr_language_list, function ($a, $b) {
        return strcmp($a, $b);
      });
      $pos = array_search($lg, $curr_language_list);
      if ($pos !== false) {
        unset($curr_language_list[$pos]);
        array_unshift($curr_language_list, $lg);
      }
      return $curr_language_list;
    } else {
      return false;
    }
  }

  public static function getLanguageModuleList($lg = '')
  {
    $file_language = self::getWordModule();
    $key = array_key_first($file_language);
    $language_key = array_keys($file_language[$key]);
    return  $language_key;
  }

  public static function getLocation($prefix = '')
  {
    $location = getcwd();
    $pos = strpos($location, 'module_main/core');
    if ($pos) {
      return substr_replace($location, '', $pos, strlen($location)) . 'module_main/';
    } else {
      return $prefix;
    }
  }

  public static $path = '';

  public static function setPrefixPathImport($paths)
  {
    $array_path = explode('/', $paths);
    $pos_html = false;
    $pos_module = false;

    foreach ($array_path as $key => $path) {
      if ($path == "html") {
        $pos_html = $key;
      } else if ($path == "module") {
        $pos_module  = $key;
      } else if ($path == 'translaty' || $path == 'module_main' || $path == 'core' || $path == 'ajax') {
        $not_condition = true;
      }
    }
    if (isset($not_condition) &&  $not_condition) {
      return;
    } else {
      $is_fornt = !$pos_module;
      $new_path = '';
      if ($is_fornt) {
        foreach ($array_path as $key => $path) {
          if ($key <= count($array_path) && $path != '') {
            $new_path = $new_path . '/' . $path;
          }
        }
      } else {
        foreach ($array_path as $key => $path) {
          if ($key <= $pos_module + 1 && $path != '') {
            $new_path = $new_path . '/'  . $path;
          }
        }
      }
      self::setPrefixPath($new_path . '/translaty');
    }
  }

  public static function getPrefixPath()
  {
    $path_cookie =  SCT::get('path_translaty');
    if ($path_cookie != '' && self::$path == '') {
      // self::$path = $_COOKIE['path_translaty'];
      self::$path = $path_cookie;
    } else if ($path_cookie == '' && self::$path == '') {
      return false;
    }
    return self::$path;
  }

  public static function setPrefixPath($paths)
  {
    // setcookie('path_translaty', $paths, time() + (86400 * 30 * 365));
    SCT::set('path_translaty', $paths);
    self::$path = $paths;
  }

  public static function getKeyword()
  {
    return array_keys(manageFile::checkRead('list_keyword'));
  }

  public static function getPathFileinModule($is_fornt = false, $script_name = '')
  {
    $module = $script_name;
    $pos_count = 0;
    if ($is_fornt) {
    } else {
      $pos = strpos($module, '/');
      $pos_count =   $pos_count + $pos;
      for ($x = 0; $x < 3; $x++) {
        $module = substr_replace($module, '', 0, $pos + 1);
        $pos = strpos($module, '/');
        $pos_count =   $pos_count + $pos + 1;
      }
      $module = substr_replace($script_name, '', $pos_count, strlen($script_name));
      $pos = strpos(F_ROOT_PHP, 'wloves-x3');
      $module = substr_replace(F_ROOT_PHP, $module, $pos - 1, strlen(F_ROOT_PHP));
    }
    return $module;
  }

  public static function getStart($module = '', $language = '', $paths = '')
  {
    $dir = Translaty::getPrefixPath();
    SCT::set('lg_default', $language);
    //? create language in web module out framework
    if ($module != '') {
      if (!file_exists($dir) || !is_dir($dir)) {
        mkdir($dir);
      }
      if (!file_exists($dir . "/list_keyword.json")) {
        $keyword_file = fopen($dir . "/list_keyword.json", "w");
      } else {
        manageFile::writeFile('list_keyword', []);
      }
      if ($language != '' && !file_exists($dir . "/" . $language . ".json")) {
        // $new_language = fopen($dir . "/" . $language . ".json", "w");
        Translaty::addLanguage($language);
        Ty::setLg($language);
        Translaty::setLanguage($language);
        $keyword = [
          'key' => 'keytest',
          $language . '_word' => $language . '_word for test'
        ];
        Translaty::setNewWord($keyword);
      }
      echo ($language);
      Translaty::setDefaultLg($language);
      Translaty::setDateUpdate(true);
    }
    Translaty::startImport();
  }

  public static function is_install()
  {
    $path_keyword =  Translaty::getPrefixPath() . '/' . 'list_keyword' . ".json";
    $bool_currlang = Translaty::getCurrentLanguage();
    if (file_exists($path_keyword) && $bool_currlang) {
      return true;
    } else {
      return false;
    }
  }

  public static function addLanguage($language)
  {
    if (isset($language) && $language != '') {
      $list_language = Translaty::getCurrentLanguage();
      W_Main::console(Translaty::getPrefixPath());
      $filePath =  Translaty::getPrefixPath() . '/' . $language . ".json";
      if (!in_array($language, $list_language) && !file_exists($filePath)) {
        fopen(Translaty::getPrefixPath() . "/" . $language . ".json", "w");
        // manageFile::writeFile('$language', $list_language);
      }
      $result = [];
      $result['response_status'] = 'success';
      $result['response_message'] = 'Add new language is success';
      return $result;
    } else {
      $result = [];
      $result['response_status'] = 'error';
      $result['response_message'] = 'Add language is errror';
      return $result;
    }
  }

  public static function delLanguage($language)
  {
    if (isset($language) && $language != '') {
      $list_language = Translaty::getCurrentLanguage();
      $filePath =  Translaty::getPrefixPath() . '/' . $language . ".json";
      if (in_array($language, $list_language) && file_exists($filePath)) {

        unlink(Translaty::getPrefixPath() . "/" . $language . ".json");
        // manageFile::writeFile('$language', $list_language);
      }
      $result = [];
      $result['response_status'] = 'success';
      $result['response_message'] = 'Delete language is success';
      Translat::exportFile();
      return $result;
    } else {
      $result = [];
      $result['response_status'] = 'error';
      $result['response_message'] = 'Delete language is error';
      return $result;
    }
  }

  public static function hasKeyword($keyword, $word)
  {
    $list_keyword = manageFile::checkRead('list_keyword');
    if (in_array($keyword, $list_keyword)) {
      $word_text_list = array_values($word);
      if (strpos($word_text_list['0'], '{{') || strpos($word_text_list['1'], '{{')) {
        $list_keyword[$keyword] = [
          'is_value' => true
        ];
      } else {
        $list_keyword[$keyword] = [
          'is_value' => false
        ];
      }
      manageFile::writeFile('list_keyword', $list_keyword);
      return true;
    } else {
      return false;
    }
  }

  public static function addKeyWord($keyword, $word)
  {
    //? check has space && keyword isn't null
    $is_space = strpos($keyword, ' ');
    if (isset($keyword) && $keyword != '' && !$is_space) {
      $list_keyword = manageFile::checkRead('list_keyword');
      $list_module_text = self::getWordModule();
      $list_keyword_module = array_keys($list_module_text);
      //? check keyword in framework
      if (!in_array($keyword, $list_keyword) && !in_array($keyword, $list_keyword_module)) {
        $word_text_list = array_keys($word);
        if (strpos($word_text_list['0'], '{{') || strpos($word_text_list['1'], '{{')) {
          $list_keyword[$keyword] = [
            'is_value' => true
          ];
        } else {
          $list_keyword[$keyword] = [
            'is_value' => false
          ];
        }
        W_Main::console($list_keyword);
        manageFile::writeFile('list_keyword', $list_keyword);
        return true;
      } else {
        return false;
      }
    } else {
      return false;
    }
  }

  public static function delKeyWord($key)
  {
    $key_list = Translaty::getKeyword();
    $index_del = array_search($key, $key_list);
    unset($key_list[$index_del]);
    manageFile::writeFile('list_keyword', $key_list);
  }

  public static function setNewWord($keyword)
  {
    $key = $keyword['key'];
    $bool = Translaty::addKeyWord($key, $keyword);
    if ($bool) {
      $curr_language_list = Translaty::getCurrentLanguage();
      foreach ($curr_language_list as $keys => $language) {
        if (isset($keyword[$language . '_word'])) {
          $word = $keyword[$language . '_word'];
          $key_file = manageFile::checkRead($language);
          $key_file[$key] = $word;
          manageFile::writeFile($language, $key_file);
        }
      }
      $result = [];
      $result['response_status'] = 'success';
      $result['response_message'] = 'Add new word is complete';
      Translaty::exportFile();
      Translaty::setDateUpdate();
      return $result;
    } else {
      $result = [];
      $result['response_status'] = 'error';
      $result['response_message'] = 'key word has already in files';
      return $result;
    }
  }

  public static function setEditWord($keyword)
  {
    $curr_language = Translaty::getCurrentLanguage();
    if ($keyword != '') {
      $new_key = $keyword['key'];
      $key = $keyword['old_key'];
      $bool = Translaty::hasKeyword($key, $keyword);
      if ($bool) {
        foreach ($curr_language as $keys => $language) {
          if (isset($keyword[$language . '_word'])) {
            $word = $keyword[$language . '_word'];
            $key_file = manageFile::checkRead($language);
            unset($key_file[$key]);
            $key_file[$new_key] = $word;
            manageFile::writeFile($language, $key_file);
          }
        }
        Translaty::delKeyWord($key);
        Translaty::addKeyWord($new_key, $keyword);
        Translaty::setDateUpdate();
        $result = [];
        $result['response_status'] = 'success';
        $result['response_message'] = 'Edit word success';
        return $result;
      } else {
        $result = [];
        $result['response_status'] = 'error';
        $result['response_message'] = 'this keyword has not in keyword list';
        return $result;
        // W_Main::console('this keyword has not in keyword list');
      }
    } else {
      $result = [];
      $result['response_status'] = 'error';
      $result['response_message'] = 'key_word or language  is null';
      return $result;
      // echo 'error : key_word or language  is null';
      // W_Main::console('error : key_word or language  is null');
    }
  }

  function genFileTranslaty()
  {
    $data = manageFile::checkReadTextFile();
    $arr_test = [];
    $key_word_file = [];
    foreach ($data as $user => $keyword_list) {
      foreach ($keyword_list as $keyword => $text_list) {
        $key = $keyword;

        $curr_language_list = Translaty::getCurrentLanguage();
        foreach ($curr_language_list as $keys => $language) {
          if (!isset($text_list[$language])) {
            $arr_test[$language] = [];
          }
          $word = $text_list[$language];
          if (!isset($arr_test[$language][$key])) {
            $arr_test[$language][$key] = $word;
          }
        }
      }
    }
    Translaty::setDateUpdate();
    W_Main::console($arr_test);
  }
  public static function deleteWord($key)
  {
    $key_list = Translaty::getKeyword();
    $index_del = array_search($key, $key_list);
    if ($index_del) {
      $curr_language_list = Translaty::getCurrentLanguage();
      foreach ($curr_language_list as $language) {
        $key_file = manageFile::checkRead($language);
        unset($key_file[$key]);
        manageFile::writeFile($language, $key_file);
      }
      unset($key_list[$index_del]);
      manageFile::writeFile('list_keyword', $key_list);
      Translaty::setDateUpdate();
      $result = [];
      $result['response_status'] = 'success';
      $result['response_message'] = 'Delete word success';
    } else {
      $result = [];
      $result['response_status'] = 'error';
      $result['response_message'] = 'Delete word is error';
    }
  }

  public static function backupFile()
  {
    $key_file = manageFile::checkRead('langauge_file');
    $filename =  'langauge_file_backup_' . date("Y-m-d", strtotime('now'));
    manageFile::writeFile($filename, $key_file, true);
  }

  public static function startImport()
  {
    $filePath =  "startword.json";
    $objData = file_get_contents($filePath, true);
    $objData = json_decode($objData, true);
    Translaty::filetoText($objData);
  }

  public static function importFile($file)
  {
    Translaty::backupFile();
    $objData = file_get_contents($file['tmp_name'], true);
    $objData = json_decode($objData, true);
    Translaty::filetoText($objData);
    Translaty::setDateUpdate();
    // W_Main::console($objData);
  }

  public static function filetoText($objData)
  {
    if (json_last_error() === JSON_ERROR_NONE) {
      $key_default_list = Translaty::getKeyLanguageList();
      $key_word_list = array_keys($objData);
      $keyword_to_file = [];
      $curr_language_list = array_keys($objData[$key_word_list[0]]);
      $is_has_language = true;
      //? check import file has keyword same in language list
      foreach ($curr_language_list as $curr_language) {
        if (!in_array($curr_language, $key_default_list)) {
          $is_has_language = false;
        }
      }
      if ($is_has_language) {
        $word_list = [];
        //? loop language form data 
        foreach ($curr_language_list as $key => $curr_language) {
          $count = 1;
          foreach ($key_word_list as $inx_keyword => $key_word) {
            // ? check has word
            if (isset($objData[$key_word][$curr_language]) && $objData[$key_word][$curr_language] && $key_word != '') {
              // ? check has value
              if (!isset($keyword_to_file[$key_word]) && $objData[$key_word][$curr_language]) {
                $is_value = false;
                if (!isset($keyword_to_file[$key_word]['is_value']) || !$keyword_to_file[$key_word]['is_value']) {
                  if (strpos($objData[$key_word][$curr_language], '{{')) {
                    $is_value = true;
                  }
                  $keyword_to_file[$key_word]['is_value'] = $is_value;
                } else {
                  $keyword_to_file[$key_word] = [];
                }
              }
              // ? set word value
              $word_list[$curr_language][$key_word] = $objData[$key_word][$curr_language];
            } else {
              $word_list[$curr_language][$key_word] = '';
            }
          }
        }
        foreach ($curr_language_list as $curr_language) {
          manageFile::writeFile($curr_language, $word_list[$curr_language]);
        }
        manageFile::writeFile('list_keyword', $keyword_to_file);
      }
    } else {
      $result = [];
      $result['response_status'] = 'error';
      $result['response_message'] = 'File is not json';
    }
  }

  public static function exportFile()
  {
    $list_keyword = Translaty::getKeyword();
    $curr_language_list = Translaty::getCurrentLanguage();
    foreach ($curr_language_list as $curr_language) {
      $word[$curr_language] = manageFile::checkRead($curr_language);
    }
    $data_export = [];
    foreach ($list_keyword as $keyword) {
      foreach ($curr_language_list as $curr_language) {
        if (isset($word[$curr_language][$keyword])  && $word[$curr_language][$keyword]) {
          $data_export[$keyword][$curr_language] =  $word[$curr_language][$keyword];
        } else {
          $data_export[$keyword][$curr_language] =  '';
        }
      }
    }
    manageFile::writeFile('langauge_file', $data_export, true);
    return $data_export;
  }

  public static function setDateUpdate($is_start = false)
  {
    $defaut_path = Translaty::getPrefixPath();
    $filePath = $defaut_path . '/system.json';
    $data = manageFile::checkRead('system');
    $date =  new DateTime();
    $date = $date->format('Y-m-d H:i:s:u');
    $is_install = Translaty::is_install();
    if ($is_install || $is_start) {
      if (!file_exists($filePath)) {
        fopen($filePath, "w");
      }
      $data['date'] = $date;
      manageFile::writeFile('system', $data, true);
    }
  }

  public static function getDateUpdate()
  {
    $defaut_path = Translaty::getPrefixPath();
    $filePath = $defaut_path . '/system.json';
    $is_install = Translaty::is_install();
    if ($is_install) {
      if (file_exists($filePath)) {
        $data = manageFile::checkRead('system');
      }
    }
    return  $data['date'];
  }

  public static function getCurrentLanguageInCookie()
  {
    $lg = SCT::get('current_language_translaty');
    return  $lg;
  }

  public static function setCurrentLanguageInCookie($lg)
  {
    SCT::set('current_language_translaty', $lg);
  }

  public static  $textList = [];

  public static function getWordList($lg)
  {
    $cookie_lg = Translaty::getCurrentLanguageInCookie();

    if (Translaty::$textList != []) {
      // ? return old value
    } else {
      $list_language = Translaty::getCurrentLanguage();
      if ($list_language && in_array($lg, $list_language)) {
        $key_file = manageFile::checkRead($lg);
        Translaty::setCurrentLanguageInCookie($lg);
        Translaty::$textList = $key_file;
      }
    }
    return Translaty::$textList;
  }

  public static  $text_module_list = [];

  public static function getModuleWordList()
  {
    if (Translaty::$text_module_list == []) {
      Translaty::setModuleWordList();
    }
    return Translaty::$text_module_list;
  }

  public static function setModuleWordList()
  {
    Translaty::$text_module_list = self::getWordModule();
  }

  // public static function createDataTable($language)
  // {
  //   $data = [];
  //   $list_keyword = Translaty::getKeyword();
  //   $list_language = Translaty::getlanguageList();
  //   if ($language != '') {
  //     if (count($list_keyword) > 0) {
  //       $en_word = manageFile::checkRead('en');
  //       $target_word = manageFile::checkRead($language);
  //       foreach ($list_keyword as $item) {
  //         $data['list'][] = [
  //           'key' => $item,
  //           'en_word' => $en_word[$item],
  //           'word' => isset($target_word[$item]) ? $target_word[$item] : '',
  //           'language' => $list_language[$language],
  //           'language_short' => $language,
  //           'word' => isset($target_word[$item]) ? $target_word[$item] : '',
  //         ];
  //       }
  //     } else {
  //       $data['list'] = [];
  //     }
  //   } else {
  //     $list_curr_langauge = Translaty::getCurrentLanguage();
  //     if (count($list_keyword) > 0) {
  //       foreach ($list_curr_langauge as $language) {
  //         $en_word = manageFile::checkRead('en');
  //         $target_word = manageFile::checkRead($language);
  //         foreach ($list_keyword as $item) {
  //           $data['list'][] = [
  //             'key' => $item,
  //             'en_word' => $en_word[$item],
  //             'language' => $list_language[$language],
  //             'language_short' => $language,
  //             'word' => isset($target_word[$item]) ? $target_word[$item] : '',
  //           ];
  //         }
  //       }
  //     } else {
  //       $data['list'] = [];
  //     }
  //   }
  //   return $data;
  // }

  // public static function exportTable($language, $where = [], $options = [])
  // {
  //   $data = Translaty::createDataTable('');
  //   $id = [];
  //   $dataTable = [];
  //   //? where part
  //   if ($where != '' && $where != []) {
  //     foreach ($where as $key => $filter) {
  //       if ($filter != '' && $filter != 'all') {
  //         $id[$key] = [];
  //         foreach ($data['list'] as $key_data => $item_data) {
  //           if (strpos($item_data[$key], $filter) !== false) {
  //             $id[$key][] = $key_data;
  //           }
  //         }
  //       }
  //     }
  //   }
  //   $ids = 'nodata';
  //   foreach ($id as $keyword) {
  //     if ($ids == 'nodata' && $keyword != []) {
  //       $ids = $keyword;
  //     } else if ($keyword != []) {
  //       $ids = array_intersect($keyword, $ids);
  //     }
  //   }
  //   if ($ids != [] && $ids != 'nodata') {
  //     foreach ($ids as $filter_id) {
  //       $dataTable['list'][] = $data['list'][$filter_id];
  //     }
  //   } else if ($ids == 'nodata' && count($id) == 0) {
  //     $dataTable = $data;
  //   } else {
  //     $dataTable['list'] = [];
  //   }

  //   //? options part
  //   if ($options != '' && $options != []) {
  //     if ($options['total_count']) {
  //       $dataTable['total_count'] = count($dataTable['list']);
  //     }
  //     if (isset($options['sort']) && $options['sort'] != '') {
  //       foreach ($options['sort'] as $key => $sort) {
  //         $GLOBALS['key'] = $key;
  //         if ($sort == 'ASC') {
  //           usort($dataTable['list'], function ($a, $b) {
  //             return strcmp($a[$GLOBALS['key']], $b[$GLOBALS['key']]);
  //           });
  //         } else if ($sort == 'DESC') {
  //           usort($dataTable['list'], function ($a, $b) {
  //             return strcmp($b[$GLOBALS['key']], $a[$GLOBALS['key']]);
  //           });
  //         }
  //       }
  //     }
  //     if ($options['page_no'] && $options['page_size']) {
  //       $options['page_no'] == 'NaN' ?  $options['page_no'] = 1 : $options['page_no'];
  //       $list = [];
  //       // W_Main::console($options);
  //       foreach ($dataTable['list'] as $key => $items) {
  //         if ($key < $options['page_no'] * $options['page_size'] && $key >= ($options['page_no'] - 1) * $options['page_size']) {
  //           $list[] =  $dataTable['list'][$key];
  //         }
  //       }
  //     }
  //   }
  //   return $dataTable;
  // }

  //? gen data to table
  public static function createDataTableList($language = '')
  {
    $data = [];
    $file_word_list = manageFile::checkReadModule();
    $list_keyword_data = Translaty::getKeyword();
    $list_keyword = $list_keyword_data;
    W_Main::console($list_keyword_data);
    $list_language = Translaty::getlanguageList();
    $list_current_language = Translaty::getCurrentLanguage();
    $count = 0;
    foreach ($file_word_list as $file_name => $list_word_module) {
      $list_keyword_module = array_keys($list_word_module);
      $is_framework = $file_name == 'framework' ? true : false;
      $location_name = $is_framework ?  'Framework' : 'Nav_' . $file_name;

      if (count($list_keyword_module) > 0) {
        foreach ($list_keyword_module as $key => $item) {
          $is_value = false;
          $data['list'][$count] = [
            'key' => $item,
          ];
          foreach ($list_current_language as $lg) {
            $target_word = $list_word_module[$item];
            if (isset($target_word[$lg])) {
              $data['list'][$count][$lg  . '_word'] = $target_word[$lg];
              if (!$is_value && strpos($target_word[$lg], '{{')) {
                $is_value = true;
              }
            } else {
              $data['list'][$count][$lg  . '_word'] = '';
            }
          }
          if ($is_value) {
            $data['list'][$count]['is_value'] = true;
          }
          $data['list'][$count]['is_framework'] = $is_framework;
          $data['list'][$count]['filename'] = $location_name;
          ++$count;
        }
      }
    }
    if (count($list_keyword) > 0) {
      foreach ($list_keyword as $keys => $item) {
        if (!in_array($item, $list_keyword_module)) {
          $key = $keys +  $count;
          $is_value = isset($list_keyword_data[$item]['is_value']);
          $data['list'][$key] = [
            'key' => $item,
          ];
          foreach ($list_current_language as $lg) {
            $target_word = manageFile::checkRead($lg);
            if (isset($target_word[$item])) {
              $data['list'][$key][$lg  . '_word'] = $target_word[$item];
            } else {
              $data['list'][$key][$lg  . '_word'] = '';
            }
          }
          if ($is_value) {
            $data['list'][$key]['is_value'] = true;
          }
        }
      }
    } else {
      $data['list'] = [];
    }
    return $data;
  }

  //?  main table
  public static function exportTableList($language, $where = [], $options = [])
  {
    $data = Translaty::createDataTableList();
    $id = [];
    $dataTable = [];
    //? where part
    if ($where != '' && $where != []) {
      foreach ($where as $key => $filter) {
        if ($filter != '' && $filter != 'all') {
          $id[$key] = [];
          foreach ($data['list'] as $key_data => $item_data) {
            if (strpos($item_data[$key], $filter) !== false) {
              $id[$key][] = $key_data;
            }
          }
        }
      }
    }
    $ids = 'nodata';
    foreach ($id as $keyword) {
      if ($ids == 'nodata' && $keyword != []) {
        $ids = $keyword;
      } else if ($keyword != []) {
        $ids = array_intersect($keyword, $ids);
      }
    }
    if ($ids != [] && $ids != 'nodata') {
      foreach ($ids as $filter_id) {
        $dataTable['list'][] = $data['list'][$filter_id];
      }
    } else if ($ids == 'nodata' && count($id) == 0) {
      $dataTable = $data;
    } else {
      $dataTable['list'] = [];
    }

    //? options part
    if ($options != '' && $options != []) {
      if ($options['total_count']) {
        $dataTable['total_count'] = count($dataTable['list']);
      }
      if (isset($options['sort']) && $options['sort'] != '') {
        foreach ($options['sort'] as $key => $sort) {
          $GLOBALS['key'] = $key;
          if ($sort == 'ASC') {
            usort($dataTable['list'], function ($a, $b) {
              return strcmp($a[$GLOBALS['key']], $b[$GLOBALS['key']]);
            });
          } else if ($sort == 'DESC') {
            usort($dataTable['list'], function ($a, $b) {
              return strcmp($b[$GLOBALS['key']], $a[$GLOBALS['key']]);
            });
          }
        }
      }
      if ($options['page_no'] && $options['page_size']) {
        $options['page_no'] == 'NaN' ?  $options['page_no'] = 1 : $options['page_no'];
        $list = [];
        // W_Main::console($options);
        foreach ($dataTable['list'] as $key => $items) {
          if ($key < $options['page_no'] * $options['page_size'] && $key >= ($options['page_no'] - 1) * $options['page_size']) {
            $list[] =  $dataTable['list'][$key];
          }
        }
      }
    }
    return $dataTable;
  }

  public static function getWordModule()
  {
    $file_language = [];
    $module_list_word = manageFile::checkReadModule();
    foreach ($module_list_word as $data) {
      $file_language  = array_merge($file_language, $data);
    }
    return $file_language;
  }
}

class Ty
{
  public static $lg = '';
  public static function getLg()
  {
    //? singletan
    if (self::$lg == '') {
      $lg_cookie = Translaty::getCurrentLanguageInCookie();
      //? if cookie is empty use language form default
      if (!isset($lg_cookie) ||  $lg_cookie == '') {
        $default_lg = Translaty::getDefaultLg();
        // self::$lg =  Translaty::getDefaultLg();
        Ty::setLg($default_lg);
        Translaty::setCurrentLanguageInCookie($default_lg);
      } else {
        self::$lg = $lg_cookie;
      }
    }
    return self::$lg;
  }

  public static function setLg($language)
  {
    // $curr_language_list = Translaty::getCurrentLanguage();
    // if (!$curr_language_list) {
    //   $curr_language_list = Translaty::getLanguageModuleList();
    // }
    // if (in_array($language, $curr_language_list)) {
    //   Translaty::setCurrentLanguageInCookie($language);
    //   self::$lg = $language;
    // } else {
    //   W_Main::console('Error in function setLg');
    // }
    Translaty::setCurrentLanguageInCookie($language);
  }

  public static function get($keyword, $value = [], $options = [])
  {
    $language = isset($options['language']) && $options['language'] != '' ?   $options['language'] : '';
    $word_case = isset($options['case']) && $options['case'] != '' ?   $options['case'] : '';
    $bool = true;
    $lg = Ty::getLg();
    if ($language) {
      $lg =  $language;
    }
    $module_file = Translaty::getModuleWordList();
    $in_module = isset($module_file[$keyword][$lg]);
    if (!$in_module) {
      $text_list = Ty::getTextList();
      if (isset($text_list[$keyword]) && $text_list[$keyword]) {
        $return =  $text_list[$keyword];
        if ($word_case) {
          $return = strtolower($return);
          if ($word_case == 'strtoupper') {
            $return = strtoupper($return);
          } else if ($word_case == 'strtolower') {
            $return = strtolower($return);
          } else if ($word_case == 'ucfirst') {
            $return = ucfirst($return);
          } else if ($word_case == 'ucwords') {
            $return = ucwords($return);
          } else if ($word_case == 'lcfirst') {
            $return = lcfirst($return);
          }
        }
      } else {
        // $return =  '<span>"' . $keyword . '"</span>';
        $bool = false;
        $return  =  $keyword;
      }
    } else {
      $return = $module_file[$keyword][$lg];
      if ($word_case) {
        $return = strtolower($return);
        if ($word_case == 'strtoupper') {
          $return = strtoupper($return);
        } else if ($word_case == 'strtolower') {
          $return = strtolower($return);
        } else if ($word_case == 'ucfirst') {
          $return = ucfirst($return);
        } else if ($word_case == 'ucwords') {
          $return = ucwords($return);
        } else if ($word_case == 'lcfirst') {
          $return = lcfirst($return);
        }
      }
    }

    if (isset($value) && $value != [] && $bool) {

      foreach ($value as $key => $text) {
        if ($word_case == 'strtoupper') {
          $key = strtoupper($key);
        } else if ($word_case == 'strtolower') {
          $key = strtolower($key);
        }
        $key = '{{' . $key . '}}';
        $pos = strpos($return, $key);
        if ($pos || $pos == 0) {
          $return = str_replace($key, $text, $return);
        }
      }
    }
    return $return;
  }

  public static $text_list = [];
  public static function getTextList()
  {
    if (Ty::$text_list == []) {
      $lg = Ty::getLg();
      Ty::setTextList($lg);
    }
    return Ty::$text_list;
  }
  public static function setTextList($lg)
  {
    Ty::$text_list = Translaty::getWordList($lg);
  }
}

class manageFile
{
  public static function checkRead($filename, $prefix = '', $is_json = true)
  {
    $defaut_path = Translaty::getPrefixPath();
    if ($prefix == '' && $defaut_path != '') {
      $filePath =  $defaut_path  . '/' . $filename . ".json";
    } else if ($defaut_path == '') {
      $prefix = '../../';
      $filePath =   $prefix . $filename . ".json";
    } else {
      $filePath =   $prefix . $filename . ".json";
    }
    $objData = file_get_contents($filePath, true);
    if ($is_json) {
      if ($objData == '' || $objData == []) {
        $objData = array();
      } else {
        $objData = json_decode($objData, true);
      }
    }
    switch (json_last_error()) {
      case JSON_ERROR_NONE:
        break;
      case JSON_ERROR_DEPTH:
        echo ' - Maximum stack depth exceeded';
        break;
      case JSON_ERROR_STATE_MISMATCH:
        echo ' - Underflow or the modes mismatch';
        break;
      case JSON_ERROR_CTRL_CHAR:
        echo ' - Unexpected control character found';
        break;
      case JSON_ERROR_SYNTAX:
        echo ' - Syntax error, malformed JSON (Read)';
        break;
      case JSON_ERROR_UTF8:
        echo ' - Malformed UTF-8 characters, possibly incorrectly encoded';
        break;
      default:
        echo ' - Unknown error';
        break;
    }
    // print_r($objData);
    return $objData;
  }

  public static function writeFile($filename, $obj, $print_form = true)
  {
    $filePath =  Translaty::getPrefixPath() . '/' . $filename . ".json";
    $data = json_encode($obj);
    if ($print_form) {
      $data = json_encode($obj, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }
    switch (json_last_error()) {
      case JSON_ERROR_NONE:
        break;
      case JSON_ERROR_DEPTH:
        echo ' - Maximum stack depth exceeded2';
        break;
      case JSON_ERROR_STATE_MISMATCH:
        echo ' - Underflow or the modes mismatch2';
        break;
      case JSON_ERROR_CTRL_CHAR:
        echo ' - Unexpected control character found2';
        break;
      case JSON_ERROR_SYNTAX:
        echo ' - Syntax error, malformed JSON2';
        break;
      case JSON_ERROR_UTF8:
        echo ' - Malformed UTF-8 characters, possibly incorrectly encoded2';
        break;
      default:
        echo ' - Unknown error2';
        break;
    }
    file_put_contents($filePath, $data);
  }

  public static function isValidJSON($str)
  {
    json_decode($str);
    return json_last_error() == JSON_ERROR_NONE;
  }

  public static function checkReadModule(): array
  {
    $path = __DIR__;
    $path = str_replace('php', '', $path);
    $folder = 'lang/';
    $folder_path = $path . $folder;

    $filelist = scandir($folder_path, 1);
    $final_data_list = [];
    foreach ($filelist as $filename) {
      if ($filename != '.' && $filename != '..') {
        if (strpos($filename, '.json')) {
          $filePath = $folder_path . $filename;
          $objData = file_get_contents($filePath, true);
          $objData = json_decode($objData, true);
          $name = str_replace('.json', '', $filename);
          $final_data_list[$name] =  $objData;
        } else {
          $filelist_nav = scandir($folder_path . $filename, 1);
          foreach ($filelist_nav as $filename_nav) {
            if ($filename_nav != '.' && $filename_nav != '..') {
              $filePath = $folder_path . $filename . '/' . $filename_nav;
              $objData = file_get_contents($filePath, true);
              $objData = json_decode($objData, true);
              $name = str_replace('.json', '', $filename_nav);
              $final_data_list[$name] =  $objData;
            }
          }
        }



        // $final_data_list  = array_merge($final_data_list, $objData);
      }
    }

    switch (json_last_error()) {
      case JSON_ERROR_NONE:
        break;
      case JSON_ERROR_DEPTH:
        echo ' - Maximum stack depth exceeded';
        break;
      case JSON_ERROR_STATE_MISMATCH:
        echo ' - Underflow or the modes mismatch';
        break;
      case JSON_ERROR_CTRL_CHAR:
        echo ' - Unexpected control character found';
        break;
      case JSON_ERROR_SYNTAX:
        echo ' - Syntax error, malformed JSON (Read)';
        break;
      case JSON_ERROR_UTF8:
        echo ' - Malformed UTF-8 characters, possibly incorrectly encoded';
        break;
      default:
        echo ' - Unknown error';
        break;
    }
    return $final_data_list;
  }

  public static function checkReadTextFile($path = ''): array
  {
    $defaut_path = Translaty::getPrefixPath();
    $defaut_path .= '/text_file/';
    $folder_path = $path . $defaut_path;

    $filelist = scandir($folder_path, 1);
    $final_data_list = [];
    foreach ($filelist as $filename) {
      if ($filename != '.' && $filename != '..') {
        if (strpos($filename, '.json')) {
          $filePath = $folder_path . $filename;
          $objData = file_get_contents($filePath, true);
          $objData = json_decode($objData, true);
          $name = str_replace('.json', '', $filename);
          $final_data_list[$name] =  $objData;
        }
        // $final_data_list  = array_merge($final_data_list, $objData);
      }
    }

    switch (json_last_error()) {
      case JSON_ERROR_NONE:
        break;
      case JSON_ERROR_DEPTH:
        echo ' - Maximum stack depth exceeded';
        break;
      case JSON_ERROR_STATE_MISMATCH:
        echo ' - Underflow or the modes mismatch';
        break;
      case JSON_ERROR_CTRL_CHAR:
        echo ' - Unexpected control character found';
        break;
      case JSON_ERROR_SYNTAX:
        echo ' - Syntax error, malformed JSON (Read)';
        break;
      case JSON_ERROR_UTF8:
        echo ' - Malformed UTF-8 characters, possibly incorrectly encoded';
        break;
      default:
        echo ' - Unknown error';
        break;
    }
    return $final_data_list;
  }
}
