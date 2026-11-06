<?php
require_once '../wloves/_framework/import_for_ajax.php';
W_Structure::loadMetaForAjax('../');
// $where = [
//   'xxx' => $_POST['xxx'],
// ];
// $options = [
//   'total_count' => true,
//   'page_no' => $_POST['page_no'],
//   'page_size' => $_POST['page_size'],
//   'is_log' => 1,
//   'sort' => [
//     'insert_date_time' => 'DESC'
//   ]
// ];

$data = [
  'total_count' => 20,
  'list' => [
    [
      'mock_data' => 'Text Text',
    ],
    [
      'mock_data' => 'Text Text',
    ],
    [
      'mock_data' => 'Text Text',
    ],
    [
      'mock_data' => 'Text Text',
    ],
    [
      'mock_data' => 'Text Text',
    ],
    [
      'mock_data' => 'Text Text',
    ],
    [
      'mock_data' => 'Text Text',
    ],
    [
      'mock_data' => 'Text Text',
    ],
    [
      'mock_data' => 'Text Text',
    ],
    [
      'mock_data' => 'Text Text',
    ],
    [
      'mock_data' => 'Text Text',
    ],
    [
      'mock_data' => 'Text Text',
    ],
  ]
];

$return = [
  'data' => $data['list'],
  'recordsTotal' => $data['total_count'],
  'recordsFiltered' => $data['total_count'],
];

echo json_encode($return);
