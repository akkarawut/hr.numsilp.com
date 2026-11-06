<?php
class ArtChart
{
  public static function dateMinMax($data)
  {
    usort($data, function ($a, $b) {
      $dateTimestamp1 = strtotime($a);
      $dateTimestamp2 = strtotime($b);

      return $dateTimestamp1 < $dateTimestamp2 ? -1 : 1;
    });

    $result = [
      'min' => $data[0],
      'max' => $data[count($data) - 1],
    ];
    return $result;
  }

  public static function validateDate($date, $format = 'Y-m-d')
  {
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) === $date;
  }

  public static function generateRandomString($length = 20)
  {
    $characters       = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString     = '';
    for ($i = 0; $i < $length; $i++) {
      $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
  }

  public static function generatorData($type = '', $data = [], $data_key = [], $options = [])
  {
    $result = [];
    if ($type == 'pie' || $type == 'bar' || $type == 'donut') {
      foreach ($data as $key => $value) {
        $temp = [
          'category' => $value[$data_key['name']],
          'value'    => floatval($value[$data_key['value']]),
        ];
        array_push($result, $temp);
      }
    } else if ($type == 'line') {
      foreach ($data as $group_key => $group_value) {
        foreach ($options['data_key'] as $option_key) {
          if ($group_key == $option_key['key']) {
            $result[$option_key['name']] = [];
            foreach ($group_value as $value) {
              $temp = [
                'labels' => $value[$data_key['name']],
                'value'  => floatval($value[$data_key['value']]),
              ];
              array_push($result[$option_key['name']], $temp);
            }
          }
        }
      }
    } else if ($type == 'stack') {
      $result = [];
      foreach ($data as $key => $group) {
        $temp['category'] = $key;
        foreach ($group as $value) {
          $temp[$value['name']] = $value['value'];
        }
        $result[] = $temp;
        $temp     = [];
      }
    }

    return $result;
  }

  public static function generateScheduleChartData($data)
  {
    $temp_date = [];
    foreach ($data as $date_key => $data_value) {
      $temp_date[] = W_Main::formatDate($date_key, 'Y-m-d');
    }
    $date_min_max = ArtChart::dateMinMax($temp_date);

    $start_date = new DateTime($date_min_max['min']);
    $end_date   = new DateTime($date_min_max['max']);
    $interval   = new DateInterval("P1D");
    $range      = new DatePeriod($start_date, $interval, $end_date);
    $dates      = [];
    foreach ($range as $date) {
      if (!in_array($date->format("Y-m-d"), $temp_date)) {
        array_push($temp_date, W_Main::formatDate($date->format("Y-m-d"), 'Y-m-d'));
      }
    }
    sort($temp_date);
    foreach ($temp_date as $temp_date_value) {
      if (!isset($data[$temp_date_value])) {
        $data[$temp_date_value] = [];
      }
    }

    // จัดข้อมูลที่เกิน 24 ชั่วโมง
    $category_temp = [];
    foreach ($data as $key => $value) {
      $category_temp[] = $key;
    }

    $i = 0;
    foreach ($data as $key_category => $value_category) {
      if ($value_category) {
        foreach ($value_category as $key => $value) {
          $from_date = $value['fromDate'];
          $to_date   = $value['toDate'];
          $from_day  = W_Main::formatDate($from_date, 'd');
          $to_day    = W_Main::formatDate($to_date, 'd');

          if ($to_day > $from_day) {
            $to_date_start      = W_Main::formatDate($to_date, 'd-m-Y') . ' 00:01';
            $duration           = strtotime($to_date) - strtotime($to_date_start);
            $result_from_date   = date('Y-m-d 00:01', strtotime($to_date_start));
            $result_to_datedate = date('Y-m-d H:i', strtotime($to_date_start) + $duration);
            $temp_date_over     = [
              'fromDate' => $result_from_date,
              'toDate'   => $result_to_datedate,
              'type'     => $value['type'],
              'detail'   => $value['detail'],
            ];

            $temp_after_to_date = [
              'fromDate' => $value['fromDate'],
              'toDate'   => W_Main::formatDate($value['toDate'], 'd-m-Y') . ' 23:59',
              'type'     => $value['type'],
              'detail'   => $value['detail'],
            ];
            $data[$category_temp[$i + 1]][] = $temp_date_over;
            $data[$category_temp[$i]][$key] = $temp_after_to_date;
          }
        }
      } else {
        $data_for_empty = [
          'fromDate' => W_Main::formatDate('', 'Y-m-d') . '00:00',
          'toDate'   => W_Main::formatDate('', 'Y-m-d') . '23:59',
          'type'     => 'timeline',
          'detail'   => 'Timeline',
        ];
        $data[$key_category][] = $data_for_empty;
      }
      $i++;
    }

    // จัดformat ข้อมูล
    $result_data = [];
    ksort($data);
    foreach ($data as $group_key => $group_value) {
      foreach ($group_value as $value) {
        $temp = [
          'category' => W_Main::formatDate($group_key, 'd/m/Y'),
          'fromDate' => W_Main::formatDate($value['fromDate'], 'H:i'),
          'toDate'   => W_Main::formatDate($value['toDate'], 'H:i'),
          'type'     => $value['type'],
          'detail'   => $value['detail'],
        ];
        array_push($result_data, $temp);
      }
    }
    return $result_data;
  }

  public static function mockUpData($type)
  {
    $result = [];
    if ($type == 'pie' || $type == 'donut') {
      for ($i = 0; $i < 5; $i++) {
        $result[] = [
          'label'            => 'Ex_label' . ($i + 1),
          'amount'           => strval(rand(1000, 2000)),
          'insert_date_time' => date('Y-m-d H:i:s'),
          'update_date_time' => date('Y-m-d H:i:s'),
        ];
      }
    } else if ($type == 'schedule') {
      $result = [
        '2022-10-03' => [
          [
            'fromDate' => '2023-04-01 10:30',
            'toDate'   => ' 2023-04-01 13:15',
            'type'     => 'normal',
            'detail'   => 'Task : 0001',
          ],
          [
            'fromDate' => '2023-04-01 14:30',
            'toDate'   => ' 2023-04-02 17:15',
            'type'     => 'late_all',
            'detail'   => 'Task : 0002',
          ],
        ],
        '2022-10-04' => [],
        '2022-10-06' => [
          [
            'fromDate' => '2023-04-03 10:30',
            'toDate'   => ' 2023-04-03 13:15',
            'type'     => 'late_due_date',
            'detail'   => 'Task : 0003',
          ],
          [
            'fromDate' => '2023-04-03 14:30',
            'toDate'   => ' 2023-04-03 17:15',
            'type'     => 'late_due_date',
            'detail'   => 'Task : 0004',
          ],
        ],
        '2022-10-07' => [
          [
            'fromDate' => '2023-04-05 12:00',
            'toDate'   => ' 2023-04-05 12:35',
            'type'     => 'normal',
            'detail'   => 'Task : 0007',
          ],
          [
            'fromDate' => '2023-04-05 12:45',
            'toDate'   => ' 2023-04-05 13:00',
            'type'     => 'normal',
            'detail'   => 'Task : 0008',
          ],
          [
            'fromDate' => '2023-04-05 13:05',
            'toDate'   => ' 2023-04-05 17:15',
            'type'     => 'late_from_estimate',
            'detail'   => 'Task : 0009',
          ],
          [
            'fromDate' => '2023-04-05 17:20',
            'toDate'   => ' 2023-04-05 19:15',
            'type'     => 'late_from_estimate',
            'detail'   => 'Task : 00010',
          ],
        ],
        '2022-10-08' => [],
      ];
      $result = ArtChart::generateScheduleChartData($result);
    } else if ($type == 'bar') {
      for ($i = 0; $i < 5; $i++) {
        $result[] = [
          'shop'             => 'Shop ' . ($i + 1),
          'total_sale'       => strval(rand(1000, 2000)),
          'insert_date_time' => date('Y-m-d H:i:s'),
          'update_date_time' => date('Y-m-d H:i:s'),
        ];
      }
    } else if ($type == 'line') {
      for ($i = 0; $i < 6; $i++) {
        $result['line_1'][] = [
          'date'             => date('Y-m-d', strtotime("+" . $i . " days")),
          'value'            => strval(rand(1000, 2000)),
          'insert_date_time' => Aww::formatDate('', 'd/m/Y H:i:s'),
        ];
        $result['line_2'][] = [
          'date'             => date('Y-m-d', strtotime("+" . $i . " days")),
          'value'            => strval(rand(1000, 2000)),
          'insert_date_time' => Aww::formatDate('', 'd/m/Y H:i:s'),
        ];
        $result['line_3'][] = [
          'date'             => date('Y-m-d', strtotime("+" . $i . " days")),
          'value'            => strval(rand(1000, 2000)),
          'insert_date_time' => Aww::formatDate('', 'd/m/Y H:i:s'),
        ];
      }
    }

    return $result;
  }

  public static function checkError($type, $data = [], $name = '', $value = '', $options = [])
  {
    $is_error = false;
    if ($type == 'pie' || $type == 'bar') {
      if (!$data) {
        $is_error = true;
        Aww::console('ArtChart : Data is empty');
      } else {
        if (is_array($data)) {
          if (!array_key_exists($name, $data[0])) {
            $is_error = true;
            Aww::console('ArtChart : Parameter(2) not found in data lists');
          }
          if (!array_key_exists($value, $data[0])) {
            $is_error = true;
            Aww::console('ArtChart : Parameter(3) not found in data lists');
          }
        } else {
          $is_error = true;
          Aww::console('ArtChart : Data is not Array.');
        }
      }
      if (!$name) {
        $is_error = true;
        Aww::console('ArtChart : Parameter(2) is empty');
      }
      if (!$value) {
        $is_error = true;
        Aww::console('ArtChart : Parameter(3) is empty');
      }
    } else if ($type == 'schedule') {
      if (!$data) {
        $is_error = true;
        Aww::console('ArtChart : Data is empty');
      } else {
        foreach ($data as $key => $value) {
          $is_date_format = ArtChart::validateDate($key, 'Y-m-d');
          if (!$is_date_format) {
            $is_error = true;
            Aww::console('ArtChart : Data key (' . $key . ') is not Date Format.');
            break;
          }
        }

        if (isset($options['color_template'])) {
          $color_temp = [];
          foreach ($options['color_template'] as $color_value) {
            $color_temp[] = $color_value['type'];
          }
          foreach ($data as $group) {
            foreach ($group as $value) {
              if (in_array($value['type'], $color_temp) == false) {
                Aww::console('ArtChart : Data type (' . $value['type'] . ') is not found in color_template.');
              }
            }
          }
        }
      }
    } else if ($type == 'line') {
      if (!$data) {
      } else {
        foreach ($data as $group) {
          foreach ($group as $group_val) {
            if (!isset($group_val[$name])) {
              $is_error = true;
              Aww::console('ArtChart : Parameter(2) not found in data lists');
              break;
            }
            if (!isset($group_val[$value])) {
              $is_error = true;
              Aww::console('ArtChart : Parameter(3) not found in data lists');
              break;
            }
          }
        }
      }
    }
    return $is_error;
  }

  public static function scheduleChart($data = null, $options = [])
  {
    ob_start();
    $rand_id = ArtChart::generateRandomString();
    if (is_null($data)) {
      $is_error = false;
      $data     = ArtChart::mockUpData('schedule');
    } else {
      $is_error = ArtChart::checkError('schedule', $data, '', '', $options);
      $data     = ($is_error) ? [] : ArtChart::generateScheduleChartData($data);
    }
    if (!$options) {
      $options = [
        'color_template' => [
          [
            'type'  => 'normal',
            'color' => '#3872E1',
          ],
          [
            'type'  => 'late_from_estimate',
            'color' => '#FFAD00',
          ],
          [
            'type'  => 'late_due_date',
            'color' => '#ED4234',
          ],
          [
            'type'  => 'late_all',
            'color' => '#A738E1',
          ],
        ],
      ];
    }

    $data_dummy = [
      'category' => 'Timeline',
      'fromDate' => '00:00',
      'toDate'   => ' 23:59',
      'type'     => 'timeline',
      'detail'   => 'Timeline',
    ];

    array_push($data, $data_dummy);

    $data           = base64_encode(json_encode($data));
    $options_encode = base64_encode(json_encode($options));
?>
    <div class="i-chart-card">
      <div class="i-chart" id="<?= $rand_id ?>" type="bar" data-encode="<?= $data ?>" options-encode="<?= $options_encode ?>">
        <div class="i-chart-loading lds-dual-ring"></div>
      </div>
    </div>
    <script>
      am5.ready(function() {
        var is_error = '<?= $is_error ?>';
        if (!is_error) {
          var id = '<?= $rand_id ?>';
          var div_chart = w('#' + id);
          var data = div_chart.attr("data-encode");
          var options = div_chart.attr("options-encode");
          w.artChart.scheduleChart(id, data, options)
        }
      });
    </script>
  <?php
    echo ob_get_clean();
  }

  public static function Bar($data = null, $key_name = '', $key_value = '', $options = [])
  {
    ob_start();
    $rand_id = ArtChart::generateRandomString();
    if (is_null($data)) {
      $data     = ArtChart::mockUpData('bar');
      $temp_key = [
        'name'  => 'shop',
        'value' => 'total_sale',
      ];
      $is_error = false;
    } else {
      $is_error = ArtChart::checkError('bar', $data, $key_name, $key_value);
      $temp_key = [
        'name'  => $key_name,
        'value' => $key_value,
      ];
    }
    $data_generate  = (!$is_error) ? ArtChart::generatorData('pie', $data, $temp_key) : [];
    $data           = base64_encode(json_encode($data_generate));
    $options_encode = base64_encode(json_encode($options));
    $layout         = isset($options['layout']) ? $options['layout'] : 'vertical';
  ?>
    <div class="i-chart-card">
      <div class="i-chart" id="<?= $rand_id ?>" type="bar" data-encode="<?= $data ?>" options-encode="<?= $options_encode ?>" layout="<?= $layout ?>">
        <div class="i-chart-loading lds-dual-ring"></div>
      </div>
    </div>
    <script>
      am5.ready(function() {
        var is_error = '<?= $is_error ?>';
        if (!is_error) {
          var id = '<?= $rand_id ?>';
          var div_chart = w('#' + id);
          var data = div_chart.attr("data-encode");
          var options = div_chart.attr("options-encode");
          var layout = div_chart.attr("layout");
          if (layout == 'vertical') {
            w.artChart.barChartVertical(id, data, options)
          } else {
            w.artChart.barChartHorizontal(id, data, options)
          }
        }
      });
    </script>
  <?php
    echo ob_get_clean();
  }

  public static function Pie($data = null, $key_name = '', $key_value = '', $options = [])
  {
    ob_start();
    $rand_id = ArtChart::generateRandomString();

    if (is_null($data)) {
      $data     = ArtChart::mockUpData('pie');
      $temp_key = [
        'name'  => 'label',
        'value' => 'amount',
      ];
      $is_error = false;
    } else {
      $is_error = ArtChart::checkError('pie', $data, $key_name, $key_value);
      $temp_key = [
        'name'  => $key_name,
        'value' => $key_value,
      ];
    }

    $data_generate  = (!$is_error) ? ArtChart::generatorData('pie', $data, $temp_key) : [];
    $data           = base64_encode(json_encode($data_generate));
    $options_encode = base64_encode(json_encode($options));

  ?>
    <div class="i-chart-card">
      <div class="i-chart" id="<?= $rand_id ?>" type="pie" data-encode="<?= $data ?>" options-encode="<?= $options_encode ?>">
        <div class="i-chart-loading lds-dual-ring"></div>
      </div>
    </div>
    <script>
      am5.ready(function() {
        var is_error = '<?= $is_error ?>';
        if (!is_error) {
          var id = '<?= $rand_id ?>';
          var div_chart = w('#' + id);
          var data = div_chart.attr("data-encode");
          var options = div_chart.attr("options-encode");
          w.artChart.pieChart(id, data, options)
        }
      });
    </script>
  <?php
    echo ob_get_clean();
  }

  public static function Donut($data = null, $key_name = '', $key_value = '', $options = [])
  {
    ob_start();
    $rand_id = ArtChart::generateRandomString();

    if (is_null($data)) {
      $data     = ArtChart::mockUpData('donut');
      $temp_key = [
        'name'  => 'label',
        'value' => 'amount',
      ];
      $is_error = false;
      if (!$options) {
        $options = [
          'text_inside' => "[fontSize:18px #05ff00]total[/]:\n[bold fontSize:30px]999.99[/]",
          'color'       => ['#fcba03', '#6eb344', '#44adb3', '#7044b3', '#b3445e'],
          'legend'      => [
            'is_show' => true,
          ],
        ];
      }
    } else {
      $is_error = ArtChart::checkError('donut', $data, $key_name, $key_value);
      $temp_key = [
        'name'  => $key_name,
        'value' => $key_value,
      ];
      $temp_color = isset($options['color']) ? $options['color'] : [];
      if ($temp_color) {
        if (count($data) > count($temp_color)) {
          unset($options['color']);
        }
      }

      $legend_temp = [
        'is_show'       => isset($options['legend']['is_show']) ? $options['legend']['is_show'] : true,
        'is_show_value' => isset($options['legend']['is_show_value']) ? $options['legend']['is_show_value'] : false,
        'value_type'    => isset($options['legend']['value_type']) ? $options['legend']['value_type'] : 'percent',
        'unit'          => isset($options['legend']['unit']) ? $options['legend']['unit'] : '',
        'is_circle'     => isset($options['legend']['is_circle']) ? $options['legend']['is_circle'] : false,
      ];
      $options['legend'] = $legend_temp;
    }
    $data_generate  = (!$is_error) ? ArtChart::generatorData('donut', $data, $temp_key) : [];
    $data           = base64_encode(json_encode($data_generate));
    $options_encode = base64_encode(json_encode($options));

  ?>
    <div class="i-chart-card">
      <div class="i-chart" id="<?= $rand_id ?>" type="pie" data-encode="<?= $data ?>" options-encode="<?= $options_encode ?>">
        <div class="i-chart-loading lds-dual-ring"></div>
      </div>
    </div>
    <script>
      am5.ready(function() {
        var is_error = '<?= $is_error ?>';
        if (!is_error) {
          var id = '<?= $rand_id ?>';
          var div_chart = w('#' + id);
          var data = div_chart.attr("data-encode");
          var options = div_chart.attr("options-encode");
          w.artChart.donutChart(id, data, options)
        }
      });
    </script>
  <?php
    echo ob_get_clean();
  }

  public static function Line($data = null, $key_name = '', $key_value = '', $options = [])
  {
    ob_start();
    $rand_id = ArtChart::generateRandomString();
    if (is_null($data)) {
      $data     = ArtChart::mockUpData('line');
      $temp_key = [
        'name'  => 'date',
        'value' => 'value',
      ];
      $options = [
        'data_key' => [
          [
            'name' => 'Line 1',
            'key'  => 'line_1',
          ],
          [
            'name' => 'Line 2',
            'key'  => 'line_2',
          ],
          [
            'name' => 'Line 3',
            'key'  => 'line_3',
          ],
        ],
        'unit'     => 'บาท',
      ];
      $is_error = false;
    } else {
      $is_error = ArtChart::checkError('line', $data, $key_name, $key_value);
      $temp_key = [
        'name'  => $key_name,
        'value' => $key_value,
      ];
    }

    $labels_is_string = isset($options['labels_is_string']) ? $options['labels_is_string'] : false;
    $data_generate    = (!$is_error) ? ArtChart::generatorData('line', $data, $temp_key, $options) : [];
    $data             = base64_encode(json_encode($data_generate));
    $options_encode   = base64_encode(json_encode($options));
  ?>
    <div class="i-chart-card">
      <div class="i-chart" id="<?= $rand_id ?>" type="pie" data-encode="<?= $data ?>" options-encode="<?= $options_encode ?>">
        <div class="i-chart-loading lds-dual-ring"></div>
      </div>
    </div>
    <script>
      am5.ready(function() {
        var is_error = '<?= $is_error ?>';
        var labels_is_string = '<?= $labels_is_string ?>';
        if (!is_error) {
          var id = '<?= $rand_id ?>';
          var div_chart = w('#' + id);
          var data = div_chart.attr("data-encode");
          var options = div_chart.attr("options-encode");
          if (labels_is_string) {
            w.artChart.lineChartString(id, data, options)
          } else {
            w.artChart.lineChart(id, data, options)
          }
        }
      });
    </script>
  <?php
    echo ob_get_clean();
  }
  public static function stack($data = null, $data_key = [], $options = [])
  {
    ob_start();
    $rand_id                 = ArtChart::generateRandomString();
    $data_generate           = ArtChart::generatorData('stack', $data);
    $data                    = base64_encode(json_encode($data_generate));
    $options['data_key']     = $data_key;
    $options['random_color'] = isset($options['random_color']) ? $options['random_color'] : true;
    $options['scrollbar'] = isset($options['scrollbar']) ? $options['scrollbar'] : false;
    $layout                  = isset($options['layout']) ? $options['layout'] : 'vertical';

    $legend_temp = [
      'is_show'       => isset($options['legend']['is_show']) ? $options['legend']['is_show'] : true,
      'is_show_value' => isset($options['legend']['is_show_value']) ? $options['legend']['is_show_value'] : false,
      'value_type'    => isset($options['legend']['value_type']) ? $options['legend']['value_type'] : 'percent',
      'unit'          => isset($options['legend']['unit']) ? $options['legend']['unit'] : '',
      'is_circle'     => isset($options['legend']['is_circle']) ? $options['legend']['is_circle'] : false,
    ];
    $options['legend'] = $legend_temp;

    $options = base64_encode(json_encode($options));
  ?>
    <div class="i-chart-card">
      <div class="i-chart" id="<?= $rand_id ?>" type="bar" data-encode="<?= $data ?>" options-encode="<?= $options ?>" layout="<?= $layout ?>">
        <div class="i-chart-loading lds-dual-ring"></div>
      </div>
    </div>
    <script>
      am5.ready(function() {
        var id = '<?= $rand_id ?>';
        var div_chart = w('#' + id);
        var layout = div_chart.attr("layout");
        var data = div_chart.attr("data-encode");
        var options = div_chart.attr("options-encode");
        if (layout == 'horizontal') {
          w.artChart.stackHorizontal(id, data, options);
        } else {
          w.artChart.stackVertical(id, data, options);
        }
      });
    </script>
<?php
    echo ob_get_clean();
  }
}
