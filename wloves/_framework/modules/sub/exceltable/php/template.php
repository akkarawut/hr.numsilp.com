<?php

function templateLoaddingPage()
{
  ob_start();
?>

  <div id="loading" class="d-none scope_loading_page">
    <div class="loading__page">
      <div class="loading__div">
        <div class="loading__icon"></div>
        <div class="loading-text">Loading...</div>
      </div>
    </div>
  </div>
<?php
  return ob_get_clean();
}

function templateTitleTable($total_count_page, $title, $sub_title)
{
  ob_start();
?>
  <div class="handson-title">
    <div class="pl-15px">
      <span class="font-18px text-info font-Bold">
        <?= $title ?>
      </span>
      <span class="font-15px font-Medium mt-5px"> <span class="font-15px font-Medium pt-3px"> <span class="scope_count_product"><?= number_format($total_count_page, 0)  ?></span> list(s) </span><span class="text-grey font-15px font-Regular"><?= $sub_title ?></span></span>
    </div>
  </div>
<?php
  return ob_get_clean();
}

function templateHandsonPagination($total_count_page)
{
  ob_start();
?>
  <div class="handson-pagination">
    <div class="box-page">
      <div class="arrow-previous event_previous_handson  disabled">
        <?= W_Structure::getfile('_framework/modules/sub/exceltable/src/arrow-left.svg')  ?>
      </div>
      <div class="arrow-next event_next_handson <?= $total_count_page < 100 ? 'disabled' : ''  ?>">
        <?= W_Structure::getfile('_framework/modules/sub/exceltable/src/arrow-right.svg')  ?>
      </div>
      <div class="mx-15px"> Page <span class="scope_page"></span></div>
    </div>
    <div class="d-flex align-items-center">
      <div class="mx-15px">Go to page</div>
      <div class="custom-select-page ">
        <?php

        $total_count_page = $total_count_page == 0 ? 1 : $total_count_page;
        $page_list = ceil($total_count_page / 100);
        $options = [
          'list' => []
        ];
        foreach (range(1, $page_list) as $key => $value) {
          $options['list'][] = [
            'value' => $value,
            'name' => $value
          ];
        }
        tiwform::normal('select', '', ['name' => '', 'class' => 'event_change_page'], $options);
        ?>
      </div>
    </div>
    <div class="d-flex align-items-center">
      <div class="custom-select-all-page ">
        <?php
        $options = [
          'list' => [
            [
              'value' => '100',
              'name' => '100/page'
            ],
            [
              'value' => '300',
              'name' => '300/page'
            ],
            [
              'value' => '500',
              'name' => '500/page'
            ],
            [
              'value' => '0',
              'name' => 'All /page'
            ],
          ]

        ];

        tiwform::normal('select', '100/page', ['name' => '', 'class' => 'event_change_all_page'], $options);
        ?>
      </div>
    </div>
  </div>
<?php
  return ob_get_clean();
}
class Exceltable
{
  public static function genTable($id, $column = [], $api_select = [], $api_update = [], $options = [])
  {

    $code = $api_select['code'];
    $api_select = $api_select['class'] . '::' . $api_select['function'];


    $options_select = [
      'total_count' => true,
      'page_no' => 1,
      'page_size' => 100,
    ];

    $data_select = $api_select($code, [], $options_select);

    //data th
    $data['data_th'] = $column;

    $data_td = [];
    $type = [];
    $th = [];
    $columns  = [];

    $temp_data = [
      'th' => 'Id',
      'data' => 'id'
    ];

    array_unshift($data['data_th'], $temp_data);

    if (isset($data['data_th'])) {
      $th = array_column($data['data_th'], 'th');
      $key = array_column($data['data_th'], 'data');
    }


    foreach ($data_select['list'] as $key_data => $data_sl) {
      foreach ($data_sl as $key_value => $value) {
        if (in_array($key_value, $key)) {
          $data_td[$key_data][$key_value] = $value;
        }
      }
    };

    //data td
    $data['data_td'] = $data_td;

    $columns = $data['data_th'];

    foreach ($columns as $idx => $value) {
      unset($columns[$idx]['th']);
    }

    $data_th = isset($th) ? "data-data_th='" . base64_encode(json_encode($th)) . "'" : '';
    $data_td = $data['data_td'] ? "data-data_td='" . base64_encode(json_encode($data['data_td'])) . "'" : '';
    $columns = isset($columns) ? "data-columns='" . base64_encode(json_encode($columns)) . "'" : '';
    $api_update = isset($api_update) && $api_update ? "data-api_update='" . base64_encode(json_encode($api_update)) . "'" : '';
    $api_select = isset($api_select) && $api_select ? "data-api_select='" . base64_encode(json_encode($api_select)) . "'" : '';

    // //options
    $fixed_columns_start = false;
    $row_headers = false;
    $is_checkbox_all = false;
    $custom_handson_class = '';
    $total_count_page = $data_select['total_count'] ? $data_select['total_count'] : 0;

    if (isset($options['fixed_columns_start'])) {
      $fixed_columns_start = $options['fixed_columns_start'];
    }
    if (isset($options['row_headers'])) {
      $row_headers = $options['row_headers'];
    }
    if (isset($options['is_checkbox_all'])) {
      $is_checkbox_all = $options['is_checkbox_all'];
    }
    if (isset($options['custom_handson_class'])) {
      $custom_handson_class = $options['custom_handson_class'];
    }
    $title = '';
    if (isset($options['title'])) {
      $title = $options['title'];
    }
    $sub_title = '';
    if (isset($options['sub_title'])) {
      $sub_title = $options['sub_title'];
    }

    $path = '../../../_framework/modules/sub/exceltable/';
    $this_url = $_SERVER['REQUEST_URI'];
    $this_url =  explode('/', $this_url);
    $key = array_search('_framework', $this_url);
    if (!$key) {
      $path = '../../_framework/modules/sub/exceltable/';
    }

    $path = 'data-path="' . $path . '"';
    $fixed_columns_start = 'data-fixed_columns_start="' . $fixed_columns_start . '"';
    $row_headers = 'data-row_headers="' . $row_headers . '"';
    $is_checkbox_all = 'data-is_checkbox_all="' . $is_checkbox_all . '"';
    $custom_handson_class = 'data-custom_handson_class="' . $custom_handson_class . '"';
    $total_count = 'data-total_count="' . $total_count_page . '"';


    echo templateLoaddingPage();
    echo $title ? templateTitleTable($total_count_page, $title, $sub_title) : '';
    echo templateHandsonPagination($total_count_page);
    echo '<div class="form-handsontable">';
    echo '<div id="' . $id . '" class="custom-handsontable" ' . $data_th . ' ' . $data_td . ' ' . $columns . ' ' .  $fixed_columns_start . ' ' . $row_headers . ' ' . $is_checkbox_all . ' '
      . $custom_handson_class . ' ' . $total_count . ' ' . $api_update . ' '  . $api_select . ' ' . $path . '></div>';
    echo '</div>';
    echo '<input type ="hidden" name="ids_handson" class="scope_ids_handson"  />
          <div class="scope_check_list_handson d-none"></div>';
  }
}
?>
<script>
  //validate Date
  function validateDate(instance, td, row, col, prop, value, cellProperties) {
    Handsontable.renderers.TextRenderer.apply(this, arguments);

    let isNotValid = true;

    if (value) {
      let temp_value = value.split("/");
      if (
        temp_value.length != 3 ||
        temp_value[0].length != 2 ||
        temp_value[1].length != 2 ||
        temp_value[2].length != 4 ||
        isNaN(temp_value[0]) ||
        isNaN(temp_value[1]) ||
        isNaN(temp_value[2])
      ) {
        isNotValid = false;
      }
      date_value = `${temp_value[2]}-${temp_value[1]}-${temp_value[0]}`;
      let date = new Date(date_value);
      if (!(date instanceof Date && isFinite(date.getTime()))) {
        isNotValid = false;
      }
    }

    if (isNotValid == false) {
      td.style.background = "#ff4c42";
      cellProperties.comment = {
        value: "โปรดระบุเป็นวันที่",
        readOnly: true,
        style: {
          width: 100,
          height: 50,
        },
      };
    } else {
      // cellProperties.type = 'date'
      td.style.background = "";
      cellProperties.comment = {};
    }
  }

  // Validate Number
  function validateNumber(instance, td, row, col, prop, value, cellProperties) {
    Handsontable.renderers.NumericRenderer.apply(this, arguments);

    if (cellProperties.valid == false || value < 0 || isNaN(value)) {
      td.style.background = '#ff4c42';
      cellProperties.comment = {
        value: 'โปรดระบุค่าเป็นจำนวนเต็มเท่านั้น',
        readOnly: true,
        style: {
          width: 100,
          height: 50,
        }
      }
    } else {
      td.style.background = '';
      cellProperties.comment = {}
    }
  }
</script>