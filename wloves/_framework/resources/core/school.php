<?php
$_PAGE['permission'] = ['core', 'core_dev', 'core_dev_school'];
require_once '../../../_framework/import.php';

W_Structure::loadModules(['boatnav', 'brandnote', 'artchart', 'artslide', 'brandontable']);

// initial data
$topic_list = [
  'name'        => 'School',
  'description' => 'dont be kak',
  'menu'        => [
    'basic' => [
      'title' => 'Basic : DO & Dont',
      'url'   => '#',
      'count' => 1
    ],
    'tiw_form' => [
      'title' => 'Module : TIW FORM',
      'url'   => '#',
      'count' => 5
    ],
    'tiwdal' => [
      'title' => 'Module : TIWDAL',
      'url'   => '#',
      'count' => 3
    ],
    'boat_nav' => [
      'title' => 'Module : BOAT NAV',
      'url'   => '#',
      'count' => 3
    ],
    'boat_color' => [
      'title' => 'Module : BOAT COLOR',
      'url'   => '#',
      'count' => 2
    ],
    'brand_note' => [
      'title' => 'Module : BRAND NOTE',
      'url'   => '#',
      'count' => 1
    ],
    'brandontable' => [
      'title' => 'Module : BRANDONTABLE',
      'url'   => '#',
      'count' => 2
    ],
    'homepagify' => [
      'title' => 'Module : Homepagify',
      'url'   => '#',
      'count' => 3
    ],
    'excel' => [
      'title' => 'Module : IT EXCEL',
      'url'   => '#',
      'count' => 1
    ],
    'art_chart' => [
      'title' => 'Module : ART CHART',
      'url'   => '#',
      'count' => 4
    ],
    'art_slide' => [
      'title' => 'Module : ART SLIDE',
      'url'   => '#',
      'count' => 1
    ],
    'translaty' => [
      'title' => 'Module : TRANSLATY',
      'url'   => '#',
      'count' => 5
    ],
  ]
  // I think frontend need some tutorial. no ?
];

$topic_sub_list = [
  'basic' => [
    'title' => 'Rule',
    'menu'  => [
      'vscode_setting' => [
        'name' => 'Setting Vscode',
        'url'  => '#'
      ],
      'name_setting' => [
        'name' => 'การตั้งชื่อ',
        'url'  => '#'
      ],
      'guide_commit_code' => [
        'name' => 'การ Commit code (Git)',
        'url'  => '#'
      ]
    ]
  ],
  'tiw_form' => [
    'title' => 'FORM',
    'menu'  => [
      'tiw_normal_form' => [
        'name' => 'Normal Form',
        'url'  => '#'
      ],
      'tiw_auto_form' => [
        'name' => 'Auto Form',
        'url'  => '#'
      ],
      'tiw_live_form' => [
        'name' => 'Live Form',
        'url'  => '#'
      ],
      'tiw_live_img' => [
        'name' => 'Live Img',
        'url'  => '#'
      ],
      'tiw_available_form' => [
        'name' => 'Available Form',
        'url'  => '#'
      ],
    ]
  ],
  'tiwdal' => [
    'title' => 'Modal',
    'menu'  => [
      'ex_normal_modal' => [
        'name' => 'ตัวอย่าง Modal ปกติ (แนะนำ)',
        'url'  => '#'
      ],
      'ex_ajax_modal' => [
        'name' => 'ตัวอย่าง Ajax Modal',
        'url'  => '#'
      ],
    ]
  ],
  'boat_color' => [
    'title' => 'Color',
    'menu'  => [
      'boat_color_store' => [
        'name' => 'ค้นหาสี',
        'url'  => '#'
      ],
      'boat_color_css' => [
        'name' => 'เขียน CSS แบบ WOLVES',
        'url'  => '#'
      ]
    ]
  ],
  'boat_nav' => [
    'title' => 'Nav',
    'menu'  => [
      'nav_short' => [
        'name' => 'แบบ Wolves (Short)',
        'url'  => '#'
      ],
      'nav_long' => [
        'name' => 'แบบ Milkclub (Long)',
        'url'  => '#'
      ],
      'nav_top' => [
        'name' => 'แบบ Dinner (Top)',
        'url'  => '#'
      ],
      'nav_generator' => [
        'name' => 'Generator Code',
        'url'  => '#'
      ]
    ]
  ],
  'excel' => [
    'title' => 'Excel',
    'menu'  => [
      'excel_import' => [
        'name' => 'ตัวอย่าง import to array',
        'url'  => '#'
      ]
    ]
  ],
  'brand_note' => [
    'title' => 'HTML Editor',
    'menu'  => [
      'note_editior' => [
        'name' => 'วิธีใช้งาน',
        'url'  => '#'
      ]
    ]
  ],
  'brandontable' => [
    'title' => 'Handsontable',
    'menu' => [
      'brandontable' => [
        'name' => 'วิธีใช้งาน',
        'url' => '#'
      ],
    ]
  ],
  'homepagify' => [
    'title' => 'Table pagination',
    'menu'  => [
      'pagify_generator' => [
        'name' => 'Generator Table',
        'url'  => '#'
      ],
      'pagify_table' => [
        'name' => 'Table',
        'url'  => '#'
      ],
      'pagify_function' => [
        'name' => 'Function',
        'url'  => '#'
      ]
    ]
  ],
  'art_chart' => [
    'title' => 'Chart',
    'menu' => [
      'art_pie_chart' => [
        'name' => 'Pie chart',
        'url' => '#'
      ],
      'art_bar_chart' => [
        'name' => 'Bar chart',
        'url' => '#'
      ],
      'art_schedule_chart' => [
        'name' => 'Schedule chart',
        'url' => '#'
      ],
      'art_line_chart' => [
        'name' => 'Line chart',
        'url' => '#'
      ],
      'art_stack_chart' => [
        'name' => 'Stack chart',
        'url' => '#'
      ],
    ]
  ],
  'art_slide' => [
    'title' => 'Slide',
    'menu' => [
      'normal_slide' => [
        'name' => 'Normal Slide',
        'url' => '#'
      ]
    ]
  ],
  'translaty' => [
    'title' => 'TRANSLATY',
    'menu' => [
      'translaty_setup_web' => [
        'name' => 'การตั้งค่าเบื้องต้น (WEB)',
        'url' => '#'
      ],
      'translaty_setup_admin' => [
        'name' => 'การตั้งค่าเบื้องต้น (WLOVES)',
        'url' => '#'
      ],
      'translaty_start' => [
        'name' => 'วิธีใช้งาน',
        'url' => '#'
      ],
      'translaty_example_website' => [
        'name' => 'ตัวอย่างการใช้งาน (WEB)',
        'url' => '#'
      ],
      'translaty_example_admin' => [
        'name' => 'ตัวอย่างการใช้งาน (WLOVES)',
        'url' => '#'
      ],
    ]
  ],
];

$topic_id     = isset($_GET['topic']) ? $_GET['topic'] : 'basic';                                 // default backend can change later
$topic_sub_id = isset($_GET['sub']) ? $_GET['sub'] : key($topic_sub_list[$topic_id]['menu']); // default backend can change later
?>

<!DOCTYPE html>
<html lang="en" data-theme="<?= W_Structure::getThemeClass() ?>">

<head>
  <?php
  W_Structure::loadMeta();
  ?>
</head>

<body>
  <?php include_once '../layout/header-default.php'; ?>

  <!-- Main menu -->
  <div class="container-fluid m-0 p-20px">
    <div class="row gx-3">
      <div class="col-xl-2 col-lg-3">
        <div class="topic-container filter-menu-container pt-0">
          <div class="topic-header-wrap">
            <h3 class="header-title"><?= $topic_list['name']; ?></h3>
            <p class="header-description"><?= $topic_list['description']; ?></p>
          </div>
          <div class="topic-items-wrap">
            <?php
            foreach ($topic_list['menu'] as $topic_key => $topic) {
              $active = ($topic_id == $topic_key) ? 'active' : '';
            ?>
              <a href="?topic=<?= $topic_key; ?>" class="topic-items filter-menu-items <?= $active; ?>">
                <span class="topic-title"><?= $topic['title']; ?></span>
                <span class="topic-count">(<?= number_format($topic['count']); ?>)</span>
              </a>
            <?php
            }
            ?>
          </div>
        </div>
      </div>

      <div class="col-xl-2 col-lg-3">
        <div class="w-loves-card p-0 mb-10px">
          <div class="sub-topic-container filter-menu-container">
            <div class="p-15px">
              <h5 class="mb-0"><?= $topic_list['menu'][$topic_id]['title']; ?></h5>
            </div>
            <hr>
            <div class="sub-topic-items-wrap">
              <?php
              foreach ($topic_sub_list as $topic_key => $topic_sub_list_data) {
                if ($topic_key == $topic_id) {
              ?>
                  <div class="sub-topic-items-group">
                    <div class="px-15px">
                      <h3><?= $topic_sub_list_data['title']; ?></h3>
                    </div>
                    <?php
                    foreach ($topic_sub_list_data['menu'] as $topic_sub_key => $topic_sub) {
                      $active = ($topic_sub_id == $topic_sub_key) ? 'active' : '';
                    ?>
                      <a href="?topic=<?= $topic_id; ?>&sub=<?= $topic_sub_key; ?>" class="sub-topic-items filter-menu-items <?= $active; ?>">
                        <span class="sub-topic-title">
                          <?= $topic_sub['name']; ?>
                        </span>
                      </a>
                    <?php
                    }
                    ?>
                  </div>
              <?php
                }
              }
              ?>
            </div>
          </div>
        </div>
      </div>

      <div class="col-xl-8 col-lg-6">
        <div class="w-love-content-container">
          <div class="w-love-content-container-wrap m-0">
            <?php
            if ($topic_sub_id == 'vscode_setting') {
              include_once 'view/school/basic_vscode_setting.php';
            } else if ($topic_sub_id == 'name_setting') {
              include_once 'view/school/basic_name_setting.php';
            } else if ($topic_sub_id == 'guide_commit_code') {
              include_once 'view/school/basic_guide_commit_code.php';
            } else if ($topic_sub_id == 'ex_normal_modal') {
              include_once 'view/school/ex_normal_modal.php';
            } else if ($topic_sub_id == 'ex_ajax_modal') {
              include_once 'view/school/ex_ajax_modal.php';
            } else if ($topic_sub_id == 'nav_short') {
              include_once 'view/school/boat_nav_short.php';
            } else if ($topic_sub_id == 'nav_long') {
              include_once 'view/school/boat_nav_long.php';
            } else if ($topic_sub_id == 'nav_top') {
              include_once 'view/school/boat_nav_top.php';
            } else if ($topic_sub_id == 'nav_generator') {
              include_once 'view/school/boat_nav_generator.php';
            } else if ($topic_sub_id == 'excel_import') {
              include_once 'view/school/excel_import.php';
            } else if ($topic_sub_id == 'note_editior') {
              include_once 'view/school/brand_note_editior.php';
            } else if ($topic_sub_id == 'tiw_normal_form') {
              include_once 'view/school/tiw_normal_form.php';
            } else if ($topic_sub_id == 'tiw_auto_form') {
              include_once 'view/school/tiw_auto_form.php';
            } else if ($topic_sub_id == 'tiw_live_form') {
              include_once 'view/school/tiw_live_form.php';
            } else if ($topic_sub_id == 'tiw_live_img') {
              include_once 'view/school/tiw_live_img.php';
            } else if ($topic_sub_id == 'tiw_available_form') {
              include_once 'view/school/tiw_available_form.php';
            } else if ($topic_sub_id == 'pagify_generator') {
              include_once 'view/school/pagify_generator.php';
            } else if ($topic_sub_id == 'pagify_table') {
              include_once 'view/school/pagify_table.php';
            } else if ($topic_sub_id == 'pagify_function') {
              include_once 'view/school/pagify_function.php';
            } else if ($topic_sub_id == 'boat_color_store') {
              include_once 'view/school/boat_color_store.php';
            } else if ($topic_sub_id == 'boat_color_css') {
              include_once 'view/school/boat_color_css.php';
            } else if ($topic_sub_id == 803) {
              include_once 'view/school/fontend803.php';
            } else if ($topic_sub_id == 'art_pie_chart') {
              include_once 'view/school/art_pie_chart.php';
            } else if ($topic_sub_id == 'art_bar_chart') {
              include_once 'view/school/art_bar_chart.php';
            } else if ($topic_sub_id == 'art_schedule_chart') {
              include_once 'view/school/art_schedule_chart.php';
            } else if ($topic_sub_id == 'art_line_chart') {
              include_once 'view/school/art_line_chart.php';
            } else if ($topic_sub_id == 'brandontable') {
              include_once 'view/school/brand_table.php';
            } else if ($topic_sub_id == 'translaty_setup_web') {
              include_once 'view/school/transty_web_setting.php';
            } else if ($topic_sub_id == 'translaty_setup_admin') {
              include_once 'view/school/transty_admin_setting.php';
            } else if ($topic_sub_id == 'translaty_start') {
              include_once 'view/school/transty_usage.php';
            } else if ($topic_sub_id == 'translaty_example_website') {
              include_once 'view/school/transty_web_example.php';
            } else if ($topic_sub_id == 'translaty_example_admin') {
              include_once 'view/school/transty_admin_example.php';
            } else if ($topic_sub_id == 'normal_slide') {
              include_once 'view/school/art_slide.php';
            } else if ($topic_sub_id == 'art_stack_chart') {
              include_once 'view/school/art_stack_chart.php';
            }
            ?>
          </div>
        </div>
      </div>
    </div>
  </div>
  <?php
  include_once '../layout/footer.php';
  W_Structure::loadFooter();
  ?>
</body>

</html>