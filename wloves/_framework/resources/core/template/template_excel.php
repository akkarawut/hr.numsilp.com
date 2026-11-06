<?php
$_PAGE['permission'] = ['core', 'core_template', ''];
require_once '../../_framework/import.php';
W_Structure::loadModules(['brandontable']);

$data = [
  [
    'id' => 'Lorem',
    'country' => 'ipsum',
    'currency' => 'dolor',
    'level' => 'sit',
    'date' => '12/08/2015',
    'units' => 23
  ],
  [
    'id' => 'Lorem2',
    'country' => 'ipsum2',
    'currency' => 'dolor2',
    'level' => 'sit2',
    'date' => '15/12/2015',
    'units' => 35
  ],
  [
    'id' => 'Lorem',
    'country' => 'ipsum',
    'currency' => 'dolor',
    'level' => 'sit',
    'date' => '12/08/2015',
    'units' => 12
  ],
  [
    'id' => 'Lorem2',
    'country' => 'ipsum2',
    'currency' => 'dolor2',
    'level' => 'sit2',
    'date' => '15/12/2015',
    'units' => 5
  ],
  [
    'id' => 'Lorem',
    'country' => 'ipsum',
    'currency' => 'dolor',
    'level' => 'sit',
    'date' => '12/08/2015',
    'units' => 55
  ],
  [
    'id' => 'Lorem2',
    'country' => 'ipsum2',
    'currency' => 'dolor2',
    'level' => 'sit2',
    'date' => '15/12/2015',
    'units' => 64
  ],
  [
    'id' => 'Total',
    'country' => '',
    'currency' => '',
    'level' => '',
    'date' => '',
    'units' => 'SUM'
  ],
  [
    'id' => '=SUM(F:F)',
    'country' => '',
    'currency' => '',
    'level' => '',
    'date' => '',
    'units' => '=SUM(F1:F6)'
  ],
];

$data_th = [
  [
    'th' => 'ID',
    'type' => ['text', true],
  ],
  [
    'th' => 'Country',
    'type' => ['text']
  ],
  [
    'th' => 'Currency',
    'type' => ['text']
  ],
  [
    'th' => 'Level',
    'type' => ['text', true]
  ],
  [
    'th' => 'Date',
    'type' => ['date']
  ],
  [
    'th' => 'Units',
    'type' => ['numeric']
  ]
];

$options = [
  'columnSorting' => true,
  'rowHeaders' => true,
  'autoInsertRow' => true,
  'manualColumnMove' => true,
  'manualRowMove' => true,
  'dropdownMenu' => true,
  'filters' => true,
  'height' => 'auto',
  'fixedRowsBottom' => 1,
  'fixedColumnsLeft' => 1,
];
?>

<!DOCTYPE html>

<head>
  <?php
  W_Structure::loadMeta('../../');
  ?>
</head>

<body>
  <?php include_once 'structure/layout/header-default.php'; ?>

  <div class="content-header-container">
    <h3 class="header-title">การใช้งาน HTML Editor<p class="font-14px mt-5px">waiting . . .</p>
    </h3>
  </div>
  <div class="content-body-container">
    <div class="row">
      <div class="col-12">
        <div class="controls d-flex justify-content-end">
          <!-- <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#general_modal">
            General Modal
          </button> -->
          <button id="export-string" class="btn btn-outline-primary">Export Data</button>
          <button id="export-file" class="btn btn-outline-primary">Export file</button>
        </div>
        <?php Brandontable::letMe('example1', ['data_th' => $data_th, 'data_td' => $data], $options); ?>
      </div>
    </div>
  </div>



  <div class="modal fade" id="general_modal" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Ex. General Modal</h5>
          <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
          <?php Brandontable::letMe('example2', ['data_th' => $data_th, 'data_td' => $data], []); ?>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary">Save</button>
        </div>
      </div>
    </div>
  </div>

  <?php include_once 'structure/layout/footer.php'; ?>
  <?php W_Structure::loadFooter('../../'); ?>
</body>

</html>