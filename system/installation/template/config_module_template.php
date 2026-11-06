<?php
  if (!isset($data)) {
    die(); // for security
  }
?>
class Config_Module {
 
  static $module_file = <?php echo isset($data['config_module_file']) ? var_export($data['config_module_file']) : '[]';?>;
  static $module_list = <?php echo isset($data['config_module_list']) ? var_export($data['config_module_list']) : '[]';?>;
  static $module_general_list = <?php echo isset($data['config_module_general_list']) ? var_export($data['config_module_general_list']) : '[]';?>;
  static $module_specific_list = <?php echo isset($data['config_module_specific_list']) ? var_export($data['config_module_specific_list']) : '[]';?>;

}