<?php
if (!strpos(getcwd(), 'setting')) {
  Translaty::setPrefixPathImport(getcwd());
}
$lg = Translaty::getDefaultLg();
// ? set default lauguage according 'Lauguage' class if have that class. 
if ($lg  == '') {
  // ? set default lauguage according 'Lauguage' class if have that class.
  if (class_exists('Language') && isset($program_wolves_setup['default_system_language'])) {
    $language =  Language::getSystemDefaultLanguage();
    $lg_module_api = Translaty::getLanguageApitoModule();
    Translaty::setDefaultLg($lg_module_api[$language['locale_name']]);
  } else {
    $default_lg = Translaty::getDefaultLg();
  }
}
