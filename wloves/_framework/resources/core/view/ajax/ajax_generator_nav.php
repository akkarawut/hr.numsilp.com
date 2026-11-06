<?php
$_WLOVES['no_check_permission'] = 1;
require_once '../../../../_framework/import.php';
W_Structure::loadModules(['boatnav'], "../../../../");
// echo '<pre>';
// print_r($_POST);
// echo '</pre>';
$link = $_POST['link'];
$type = $_POST['data_form']['type'];
$title = isset($_POST['data_form']['title']) ? $_POST['data_form']['title'] : "";
$param_name = $_POST['data_form']['param_name'];
if ($type == 1) {
  $data_nav = [
    'title' => $title,
    'param_name'  => $param_name,
    'class' => '',
    'list' => []
  ];
  foreach ($_POST['data_form']['list_name'] as $key => $value) {
    $data_nav['list'][] = [
      'id'   => $key + 1,
      'name' => $value,
      'icon' => $_POST['data_form']['list_img'][$key],
      'count' => '',
      'status' => $_POST['data_form']['list_text_status'][$key],
      'color_status' => isset($_POST['data_form']['list_color_status'][$key]) ? $_POST['data_form']['list_color_status'][$key] : '',
      'html' => ''
    ];
  }
  $result = Boatnav::wolves($data_nav, $link, '../../../../');
  $review_code  = '';
  $review_code .= "<div class='code-container'><pre><code data-language='php'>";
  $review_code .= "\$data_nav = [";
  $review_code .= "\n'title'  => '" . $title . "',";
  $review_code .= "\n'param_name'  => '" . $param_name . "',";
  $review_code .= "\n'class' => '',";
  $review_code .= "\n'list' => [";
  foreach ($data_nav['list'] as $list) {
    $review_code .= "\n     [";
    $review_code .= "\n     'id'  => " . $list['id'] . ",";
    $review_code .= "\n     'name'  => '" . $list['name'] . "',";
    $review_code .= "\n     'icon'   => '" . $list['icon'] . "',";
    $review_code .= "\n     'count'  => '" . $list['count'] . "',";
    $review_code .= "\n     'status'  => '" . $list['status'] . "',";
    $review_code .= "\n     'color_status'  => '" . $list['color_status'] . "',";
    $review_code .= "\n     'html'  => '" . $list['html'] . "',";
    $review_code .= "\n     ],";
  }
  $review_code .= "\n  ]";
  $review_code .= "\n];";
  $review_code .= "\nBoatnav::wolves(\$data_nav);</code></pre></div>";
} else if ($type == 2) {
  $data_nav = [
    'param_name'  => $param_name,
    'class' => '',
    'list' => []
  ];
  $test_data = [
    'param_name'  => 'param_name',
    'class' => '',
    'list' => [
      [
        'name'  => 'User',
        'icon'   => '',
        'count'  => 10,
        'status' => '',
        'color_status' => '',
        'html' => '',
        'list'   => [
          [
            'title'  => 'Current User 01',
            'list'   => [
              [
                'id'  => 1,
                'name'  => 'Login',
                'icon'   => '',
                'count'  => '',
                'status' => '',
                'color_status' => '',
                'html' => '',
              ],
              [
                'id'  => 2,
                'name'  => 'Logout',
                'icon'   => '',
                'count'  => '',
                'status' => '',
                'color_status' => '',
                'html' => '',
              ]
            ]
          ],
          [
            'title'  => 'Current User 02',
            'list'   => []
          ]
        ]
      ],
      [
        'name'  => 'Lorem ipsum error',
        'icon'   => '',
        'count'  => 2,
        'status' => '',
        'color_status' => '',
        'html' => '',
        'list'   => [
          [
            'title'  => 'Current User 01',
            'list'   => []
          ],
          [
            'title'  => '',
            'list'   => [
              [
                'id'  => 3,
                'name'  => 'Login',
                'icon'   => '',
                'count'  => '',
                'status' => '',
                'color_status' => '',
                'html' => '',
              ],
              [
                'id'  => 4,
                'name'  => 'Logout',
                'icon'   => '',
                'count'  => '',
                'status' => '',
                'color_status' => '',
                'html' => '',
              ]
            ]
          ]
        ]
      ]
    ]
  ];
  foreach ($_POST['data_form']['data'] as $key => $value) {
    $data_list = [];
    foreach ($value['list'] as  $list) {
      $data_list[] = [
        'id'  => $key + 1,
        'name'  => $list['menu'],
        'icon'   => $list['img'],
        'count'  => '',
        'status' => '',
        'color_status' => '',
        'html' => '',
      ];
    }
    $data_nav['list'][] = [
      'name'  => $value['menu'],
      'icon'   =>  $value['img'],
      'count'  => '',
      'status' => '',
      'color_status' => '',
      'html' => '',
      'list'   => [
        [
          'title'  => '',
          'list'   => $data_list
        ]
      ]
    ];
  }
  $result = Boatnav::milkclub($data_nav, $link, '../../../../');
  $review_code  = '';
  $review_code .= "<div class='code-container'><pre><code data-language='php'>";
  $review_code .= "\$data_nav = [";
  $review_code .= "\n'param_name'  => '" . $param_name . "',";
  $review_code .= "\n'class' => '',";
  $review_code .= "\n'list' => [";
  foreach ($_POST['data_form']['data'] as $key => $value) {
    $review_code .= "\n     [";
    $review_code .= "\n     'name'  => " . $value['menu'] . ",";
    $review_code .= "\n     'icon'  => '" . $value['img'] . "',";
    $review_code .= "\n     'count'   => '',";
    $review_code .= "\n     'status'  => '',";
    $review_code .= "\n     'color_status'  => '',";
    $review_code .= "\n     'html'  => '',";
    $review_code .= "\n     'list'  => [";
    $review_code .= "\n          [,";
    $review_code .= "\n               'title'  => '',";
    $review_code .= "\n               'list'  => [";
    foreach ($value['list'] as  $list) {
      $review_code .= "\n                 'id'  => " . ($key + 1) . ",";
      $review_code .= "\n                 'name'  => '" . $list['menu'] . "',";
      $review_code .= "\n                 'icon'   => '" . $list['img'] . "',";
      $review_code .= "\n                 'count'  => '',";
      $review_code .= "\n                 'status'  => '',";
      $review_code .= "\n                 'color_status'  => '',";
      $review_code .= "\n                 'html'  => '',";
    }
    $review_code .= "\n                ],";
    $review_code .= "\n          ],";
    $review_code .= "\n     ],";
  }
  $review_code .= "\n  ]";
  $review_code .= "\n];";
  $review_code .= "\nBoatnav::milkclub(\$data_nav);</code></pre></div>";
} else if ($type == 3) {
  $data_nav = [
    'param_name'  => $param_name,
    'class' => '',
    'list' => []
  ];
  foreach ($_POST['data_form']['list_name'] as $key => $value) {
    $data_nav['list'][] = [
      'id'   => $key + 1,
      'name' => $value,
      'icon' => $_POST['data_form']['list_img'][$key],
      'count' => ''
    ];
  }

  $result = Boatnav::dinner($data_nav, $link, '../../../../');
  $review_code  = '';
  $review_code .= "<div class='code-container'><pre><code data-language='php'>";
  $review_code .= "\$data_nav = [";
  $review_code .= "\n'param_name'  => '" . $param_name . "',";
  $review_code .= "\n'class' => '',";
  $review_code .= "\n'list' => [";
  foreach ($data_nav['list'] as $list) {
    $review_code .= "\n     [";
    $review_code .= "\n     'id'  => " . $list['id'] . ",";
    $review_code .= "\n     'name'  => '" . $list['name'] . "',";
    $review_code .= "\n     'icon'   => '" . $list['icon'] . "',";
    $review_code .= "\n     'count'  => '" . $list['count'] . "',";
    $review_code .= "\n     ],";
  }
  $review_code .= "\n  ]";
  $review_code .= "\n];";
  $review_code .= "\nBoatnav::dinner(\$data_nav);</code></pre></div>";
} else {
  $result = 'no data';
}
echo $result;
echo $review_code;

?>
<script>
  Rainbow.color();
  startCopyToClipboard();
  $('.it-nav-milkclub .list-frist').click(function(e) {
    var scope = $(this).parents('.list');
    if (scope.hasClass('active')) {
      scope.removeClass('active');
    } else {
      // $(this).parents('.it-nav-milkclub').find('.list').removeClass('active');
      scope.addClass('active');
    }
  });
  $('.nav-search input[type="search"]').on('keyup', function() {
    let scope = $(this).parents('.it-nav-milkclub');
    let value = $(this).val().toLowerCase();
    scope.find('.list').filter(function() {
      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
      if ($(this).text().toLowerCase().indexOf(value) > 0) {
        $(this).removeClass('active');
        $(this).addClass('active');
      } else {
        $(this).removeClass('active');
      }
    });
  });
</script>