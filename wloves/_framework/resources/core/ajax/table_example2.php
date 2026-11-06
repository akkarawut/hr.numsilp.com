<?php
$_WLOVES['no_check_permission'] = 1;
require_once '../../../_framework/import.php';
W_Structure::loadMetaForAjax('../../../');
$where = [
  'search_text' => isset($_POST['search_text']) ? $_POST['search_text'] : ''
];
$options = [
  'total_count' => true, //หลังบ้านส่งค่า จำนวนทั้งหมดให้
  'grouped_by'  => '',
  'page_no'     => ($_POST['page_size']) ? $_POST['page_no'] : 0,
  'page_size'   => ($_POST['page_size'] > 0) ? $_POST['page_size'] : '',
  'sort'        => isset($_POST['data_sort']) ? $_POST['data_sort'] : []

];
$data_list = [
  [
    'id_group' => 1,
    'group'    => 'group 1',
    'list'     => [
      [
        'id'     => 1,
        'name'   => 'Heattech fleece turtlene…',
        'qty'    => 60,
        'price'  => 300,
        'detail' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry.'
      ],
      [
        'id'     => 2,
        'name'   => 'Heattech fleece turtlene…',
        'qty'    => 60,
        'price'  => 300,
        'detail' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry.'
      ]
    ]
  ],
  [
    'id_group' => 2,
    'group'    => 'group 2',
    'list'     => [
      [
        'id'    => 3,
        'name'   => 'Heattech fleece turtlene…',
        'qty'    => 0,
        'price'  => 10,
        'detail' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry.'
      ],
      [
        'id'     => 4,
        'name'   => 'Heattech fleece turtlene…',
        'qty'    => 30,
        'price'  => 200,
        'detail' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry.'
      ],
      [
        'id'     => 5,
        'name'   => 'Heattech fleece turtlene…',
        'qty'    => 40,
        'price'  => 500,
        'detail' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry.'
      ]
    ]

  ]
];
?>

<tbody data-total_count="5">
  <?php
  foreach ($data_list as $idx => $group) {
  ?>
    <tr class="title-group" data-group="<?= $group['id_group'] . '_' . $group['id_group'] ?>">
      <td colspan="5">
        <div class="btn-toggle">
          <p> <?= $group['group'] ?> </p>
        </div>
      </td>
    </tr>
    <?php
    foreach ($group['list'] as $data) {
    ?>
      <tr class="tr-link <?= $group['id_group'] . '_' . $group['id_group'] ?>">
        <td>
          <?= Homepagify::createCheckboxTBody('checkbox_' . $data['id'], $data['id'], []); ?>
        </td>
        <td>
          <?= $data['name']; ?>
        </td>
        <td>
          <?= number_format($data['qty']); ?>
        </td>
        <td>
          <?= number_format($data['price'], 2); ?>
        </td>
        <td>
          <?= $data['detail']; ?>
        </td>
      </tr>
  <?php
    }
  }
  ?>
</tbody>