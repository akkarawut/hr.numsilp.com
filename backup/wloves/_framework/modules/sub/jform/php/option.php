<?php

$list_font_size = [];
foreach (range(10, 28) as $number) {
  $list_font_size[] = [
    'value' => $number,
    'name' => $number . 'px'
  ];
}

$font_size =
  [
    'list' => $list_font_size,
  ];

$size_display = [
  'list' => [
    [
      'value' => '49',
      'name' => '50%'
    ],
    [
      'value' => '100',
      'name' => '100%'
    ],
  ]
];
