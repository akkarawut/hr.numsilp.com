<?php
// Config API URL & KEY
class W_Config
{
  public static $menu_core_list = [
    'Core' => [
      'title'       => 'Core',
      'page_name'   => 'core',
      'module_name' => 'Core',
      'icon'        => 'structure/image/sidenav/chip.svg',
      'menu_items'  => [
        [
          'title'     => 'Mandatory configure',
          'page_name' => 'core_program_setting',
          'icon'      => 'structure/image/sidenav/chip_x.svg',
          'url'       => 'program_setting.php',
          'sub_menu'  => [
            [
              'title'     => 'Program Setting',
              'page_name' => 'core_program_setting_setup',
              'icon'      => 'structure/image/sidenav/chip_x.svg',
              // 'url'       => 'program_setting.php',
              'url'       => '../setting/',
            ],
            [
              'title'     => 'Module',
              'page_name' => 'core_module_setup',
              'icon'      => 'structure/image/sidenav/chip_x.svg',
              'url'       => '../module/',
              // 'url'       => 'module_setting.php',
              'count'     => [
                'number' => 0,
                'class'  => 'bg-primary'
              ],
            ],
          ]
        ],
        [
          'title'     => 'Dev',
          'page_name' => 'core_dev',
          'icon'      => 'structure/image/layout/menu-school.svg',
          'url'       => 'school.php',
          'sub_menu'  => [
            [
              'title'     => 'Checking',
              'page_name' => 'core_dev_checking',
              'icon'      => 'structure/image/layout/menu-dev.svg',
              'url'       => '../checking',
              'count'     => [
                'number' => 0,
                'class'  => 'bg-danger'
              ],
            ],
            [
              'title'     => 'API',
              'page_name' => 'core_dev_api',
              'icon'      => 'structure/image/layout/menu-dev.svg',
              'url'       => 'api.php'
            ],
            [
              'title'     => 'API KEYs',
              'page_name' => 'core_program_setting_api_key',
              'icon'      => 'structure/image/sidenav/api_keys.svg',
              'url'       => 'program_setting_api_key.php'
            ],
            [
              'title'     => 'Database',
              'page_name' => 'core_dev_database',
              'icon'      => 'structure/image/layout/menu-database.svg',
              'url'       => '',
              'target'    => '_blank'
            ],
            // [
            //   'title'     => 'School',
            //   'page_name' => 'core_dev_school',
            //   'icon'      => 'structure/image/layout/menu-school.svg',
            //   'url'       => '../school/',
            // ],
          ]
        ]
      ]
    ]
  ];

  public static $menu_list = [];

  // primary secondary tertiary, quaternary, quinary, senary, septenary, octonary, nonary, and denary
  public static function getBreadCrumpMenu($primary = '', $secondary = '', $tertiary = '', $options = [])
  {

    /* ตัวอย่างการส่ง option
    $_PAGE['bread_crumps_option'] = array(
    'primary' => array(
    'title' => 'Core Controller', //แก้ไขหัวข้อ
    'url' => 'test.php', //แก้ไขลิงค์
    'is_hide' => 1, //ไม่แสดงหัวข้อนั้นๆ
    ),
    'secondary' => array(
    'url' => 'test.php', //แก้ไขลิงค์
    ),
    'tertiary' => array(
    'is_hide' => 1, //ไม่แสดงหัวข้อนั้นๆ
    ),
    );
     */
    if ($primary == 'core') {
      $bread_crumps_list = static::$menu_core_list;
    } else {
      if (file_exists('../../module/' . strtolower($primary) . '/config/menu.php')) {
        include '../../module/' . strtolower($primary) . '/config/menu.php';
      }
      $bread_crumps_list = static::$menu_list;
    }

    $active_primary   = '';
    $active_secondary = '';
    $active_tertiary  = '';

    //set active bread crumps ตัวสุดท้าย
    if ($tertiary) {
      $active_tertiary = 'active';
    } else if ($secondary) {
      $active_secondary = 'active';
    } else if ($primary) {
      $active_primary = 'active';
    }

    $return_bread_crump = [];
    if ($primary) {
      //เช็ค primary_page_name
      foreach ($bread_crumps_list as $list) {
        if ($primary == $list['page_name']) {
          //ถ้ามี option แทนค่า config menu
          if (isset($options['primary'])) {
            foreach ($options['primary'] as $primary_key => $primary_data) {
              $list[$primary_key] = $primary_data;
            }
          }
          $list['active']                = $active_primary; //set active
          $return_bread_crump['primary'] = $list;           //set return breadcrump
          unset($return_bread_crump['primary']['menu_items']);

          if (isset($options['primary']['is_hide']) && $options['primary']['is_hide'] == 1) {
            //unset ค่าเมื่อ is_hide=1
            unset($return_bread_crump['primary']);
          }

          if ($secondary) {
            //เช็ค secondary_page_name
            foreach ($list['menu_items'] as $menus) {
              if ($secondary == $menus['page_name']) {
                //ถ้ามี option แทนค่า config menu
                if (isset($options['secondary'])) {
                  foreach ($options['secondary'] as $secondary_key => $secondary_data) {
                    $menus[$secondary_key] = $secondary_data;
                  }
                }
                $menus['active']                 = $active_secondary; //set active
                $return_bread_crump['secondary'] = $menus;            //set return breadcrump
                unset($return_bread_crump['secondary']['sub_menu']);

                if (isset($options['secondary']['is_hide']) && $options['secondary']['is_hide'] == 1) {
                  //unset ค่าเมื่อ is_hide=1
                  unset($return_bread_crump['secondary']);
                  //ถ้า is_hide=1 active จะไปเลือกอันก่อนแทน
                  $return_bread_crump['primary']['active'] = 'active';
                }

                if ($tertiary) {
                  //เช็ค tertiary_page_name
                  foreach ($menus['sub_menu'] as $topbar) {
                    if ($tertiary == $topbar['page_name']) {
                      //ถ้ามี option แทนค่า config menu
                      if (isset($options['tertiary'])) {
                        foreach ($options['tertiary'] as $tertiary_key => $tertiary_data) {
                          $topbar[$tertiary_key] = $tertiary_data;
                        }
                      }
                      $topbar['active']               = $active_tertiary; //set active
                      $return_bread_crump['tertiary'] = $topbar;          //set return breadcrump

                      if (isset($options['tertiary']['is_hide']) && $options['tertiary']['is_hide'] == 1) {
                        //unset ค่าเมื่อ is_hide=1
                        unset($return_bread_crump['tertiary']);
                        //ถ้า is_hide=1 active จะไปเลือกอันก่อนแทน
                        $return_bread_crump['secondary']['active'] = 'active';
                      }
                    }
                  }
                } //end if tertiary
              } //end if secondary
            } //end foreach secondary
          } //end if isset secondary
        }
      } //end foreach primary
    }
    return $return_bread_crump;
  }
}
