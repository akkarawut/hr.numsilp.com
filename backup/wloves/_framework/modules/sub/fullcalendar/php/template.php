<?php
class W_Calendar
{
  public static function full($id, $value, $class = [], $options = [])
  {
    $all_class = '';
    if (is_array($class)) {
      foreach ($class as $a_class) {
        $all_class .= ' ' . $a_class;
      }
    } else {
      $all_class = $class;
    }

    if (isset($options['headerToolbar']) && $options['headerToolbar']) {
      $options['headerToolbar'] = $options['headerToolbar'];
    } else {
      $options['headerToolbar'] =  [
        'left' => 'title',
        'center' => '',
        'right' => 'prev,today,next,timeGridDay,timeGridWeek,dayGridMonth'
      ];
    }

    if (isset($options['titleFormat']) && $options['titleFormat']) {
      $options['titleFormat'] = $options['titleFormat'];
    } else {
      $options['titleFormat'] = [
        'year' => 'numeric',
        'month' => 'short',
        'day' => 'numeric'
      ];
    }

    if (isset($options['initialView']) && $options['initialView']) {
      $options['initialView'] = $options['initialView'];
    } else {
      $options['initialView'] = 'dayGridMonth';
    }

    if (isset($options['initialDate']) && $options['initialDate']) {
      $options['initialDate'] = $options['initialDate'];
    } else {
      $options['initialDate'] = date('Y-m-d');
    }

    if (isset($options['link']) && $options['link']) {
      $options['link'] = $options['link'];
    } else {
      $options['link'] = '';
    }


    if (isset($options['connnet']) && $options['connnet']) {
      $options['connnet'] = $options['connnet'];
    } else {
      $options['connnet'] = '';
    }
    if (isset($options['month_type']) && $options['month_type']) {
      $options['month_type'] = $options['month_type'];
    } else {
      $options['month_type'] = '';
    }
    if (isset($options['locale']) && $options['locale']) {
      $options['locale'] = $options['locale'];
    } else {
      $options['locale'] = '';
    }

    if (isset($value['data'])) {
      $value = $value['data'];
    }
    $html = '<div class="scope_calendar">
              <div id=' . $id . ' class="' . $all_class . '" data-value="' . base64_encode(json_encode($value)) . '" data-options="' . base64_encode(json_encode($options)) . '"></div>
            </div>
            <input type="hidden" name="' . $id . '" value="' . $id . '">';
    echo $html;
  }

  public static function timegrid($id, $value, $title = '', $class = [], $options = [])
  {
    $all_class = '';
    if (is_array($class)) {
      foreach ($class as $a_class) {
        $all_class .= ' ' . $a_class;
      }
    } else {
      $all_class = $class;
    }

    if (isset($options['headerToolbar']) && $options['headerToolbar']) {
      $options['headerToolbar'] = $options['headerToolbar'];
    } else {
      $options['headerToolbar'] =  [
        'left' => 'today,prev,next,title',
        'center' => '',
        'right' => ''
      ];
    }

    if (isset($options['titleFormat']) && $options['titleFormat']) {
      $options['titleFormat'] = $options['titleFormat'];
    } else {
      $options['titleFormat'] = [
        'year' => 'numeric',
        'month' => 'short',
        'day' => 'numeric'
      ];
    }

    if (isset($options['initialView']) && $options['initialView']) {
      $options['initialView'] = $options['initialView'];
    } else {
      $options['initialView'] = 'timeGridWeek';
    }

    if (isset($options['initialDate']) && $options['initialDate']) {
      $options['initialDate'] = $options['initialDate'];
    } else {
      $options['initialDate'] = date('Y-m-d');
    }

    if (isset($options['link']) && $options['link']) {
      $options['link'] = $options['link'];
    } else {
      $options['link'] = '';
    }

    $theme = '';
    if (isset($options['theme']) && $options['theme']) {
      $theme = $options['theme'];
    } else {
      $theme = '';
    }

    $header = '<div class="header-title-calendar bg-color-calendar ' . $theme . '">-</div>';
    $footer = '';
    if ($title != '') {
      $header = '<div class="header-title-calendar bg-color-calendar ' . $theme . '">' . $title . '</div>';
    }

    if (isset($value['data'])) {
      $value = $value['data'];
    }
    $html = '<div>
              ' . $header . '
              <div class="scope_calendar">
                <div id=' . $id . ' class="card-calendar ' . $theme . ' ' . $all_class . '" data-value="' . base64_encode(json_encode($value)) . '" data-options="' . base64_encode(json_encode($options)) . '"></div>
              </div>
              <input type="hidden" name="' . $id . '" value="' . $id . '">
              <div class="footer-calendar">Event shown in time zone: Indochina Time - Bangkok</div>
              </div>
            ';
    echo $html;
  }

  public static function mini($id, $value, $class = [], $options = [])
  {
    $all_class = '';
    if (is_array($class)) {
      foreach ($class as $a_class) {
        $all_class .= ' ' . $a_class;
      }
    } else {
      $all_class = $class;
    }

    if ($value == '') {
      $value = date('Y-m-d');
    }

    if (isset($options['connnet']) && $options['connnet']) {
      $options['connnet'] = $options['connnet'];
    } else {
      $options['connnet'] = '';
    }

    $html = '<div class="scope_mini_calendar">
              <div id=' . $id . ' class="' . $all_class . '" data-date="' . $value . '" data-options="' . base64_encode(json_encode($options)) . '"></div>
            </div>
            <input type="hidden" name="' . $id . '" value="' . $value . '">';
    echo $html;
  }
}
