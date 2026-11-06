<?php
class Config_Module {
 
  static $module_file = array (
    'Version' => 'module/general/version',
    'User' => 'module/general/user',
    'HR' => 'module/specific/hr',
    'MYWINE' => 'module/specific/mywine',
  );

  static $module_list = array (
    'Version' => 
    array (
      'description' => 'FOR MOBILE',
      'prefix' => '',
      'path' => 'module/general/version',
    ),
    'User' => 
    array (
      'description' => 'สร้างไว้ให้มี DOC เฉยๆ',
      'prefix' => '',
      'path' => 'module/general/user',
    ),
    'HR' => 
    array (
      'description' => '',
      'prefix' => 'phr',
      'path' => 'module/specific/hr',
    ),
    'MYWINE' => 
    array (
      'description' => '',
      'prefix' => 'myw',
      'path' => 'module/specific/mywine',
    ),
  );

  static $module_general_list = array (
  );

  static $module_specific_list = array (
    'HR' => 
    array (
      'description' => '',
      'prefix' => 'phr',
      'path' => 'module/specific/hr',
    ),
    'MYWINE' => 
    array (
      'description' => '',
      'prefix' => 'myw',
      'path' => 'module/specific/mywine',
    ),
  );
}