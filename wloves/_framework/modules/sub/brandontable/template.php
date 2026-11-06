<?php
class Brandontable
{
  public static function letMe($id, $data = [], $options = [])
  {
    $data_th = '';
    $data_td = '';
    $type = [];
    $th = [];
    $readonly = [];

    if (isset($data['data_th'])) {
      $th = array_column($data['data_th'], 'th');
      $type = array_column($data['data_th'], 'type');
    }



    $columns = [];
    $readOnly = [];
    foreach ($type as $key => $value) {
      $columns[] = [
        'type' => isset($value[0]) ? $value[0] : '',
        'readOnly' => isset($value[1]) ? $value[1] : 0,
        'source' => isset($value[2]) ? $value[2] : [],
      ];
    }

    $data_td_value = [];
    if (isset($data['data_td'])) {
      foreach ($data['data_td'] as $key => $value) {
        array_push($data_td_value, array_values($value));
      }
    }

    $data_th = isset($th) ? "data-data_th='" . base64_encode(json_encode($th)) . "'" : '';
    $data_td = $data_td_value ? "data-data_td='" . base64_encode(json_encode($data_td_value)) . "'" : '';
    $type = isset($columns) ? "data-type='" . base64_encode(json_encode($columns)) . "'" : '';


    //options
    $columnSorting = true;
    $rowHeaders = true;
    $autoInsertRow = false;
    $manualColumnMove = false;
    $manualRowMove = false;
    $dropdownMenu = false;
    $filters = false;
    $height = 'auto';
    $fixedRowsBottom = false;
    $fixedColumnsLeft = false;

    if (isset($options['columnSorting'])) {
      $columnSorting = $options['columnSorting'];
    }
    $columnSorting = 'data-columnSorting="' . $columnSorting . '"';

    if (isset($options['rowHeaders'])) {
      $rowHeaders = $options['rowHeaders'];
    }
    $rowHeaders = 'data-rowHeaders="' . $rowHeaders . '"';

    if (isset($options['autoInsertRow'])) {
      $autoInsertRow = $options['autoInsertRow'];
    }
    $autoInsertRow = 'data-autoInsertRow="' . $autoInsertRow . '"';

    if (isset($options['manualColumnMove'])) {
      $manualColumnMove = $options['manualColumnMove'];
    }
    $manualColumnMove = 'data-manualColumnMove="' . $manualColumnMove . '"';

    if (isset($options['manualRowMove'])) {
      $manualRowMove = $options['manualRowMove'];
    }
    $manualRowMove = 'data-manualRowMove="' . $manualRowMove . '"';

    if (isset($options['dropdownMenu'])) {
      $dropdownMenu = $options['dropdownMenu'];
    }
    $dropdownMenu = 'data-dropdownMenu="' . $dropdownMenu . '"';

    if (isset($options['filters'])) {
      $filters = $options['filters'];
    }
    $filters = 'data-filters="' . $filters . '"';

    if (isset($options['height'])) {
      $height = $options['height'];
    }
    $height = 'data-height="' . $height . '"';

    if (isset($options['fixedRowsBottom'])) {
      $fixedRowsBottom = $options['fixedRowsBottom'];
    }
    $fixedRowsBottom = 'data-fixedRowsBottom="' . $fixedRowsBottom . '"';

    if (isset($options['fixedColumnsLeft'])) {
      $fixedColumnsLeft = $options['fixedColumnsLeft'];
    }
    $fixedColumnsLeft = 'data-fixedColumnsLeft="' . $fixedColumnsLeft . '"';

    echo '<div class="form-handsontable">';
    echo '<div id="' . $id . '" class="handsontable no-edit" ' . $data_th . ' ' . $data_td . ' ' . $type . ' ' . $columnSorting . ' ' . $rowHeaders . ' ' . $autoInsertRow . ' ' . $manualColumnMove . ' ' . $manualRowMove . ' ' . $dropdownMenu . ' ' . $filters . ' ' .
      $height  . ' ' . $fixedRowsBottom . ' ' . $fixedColumnsLeft . '></div>';
    echo '</div>';
  }
}
