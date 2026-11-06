<?php
class artCalendar
{

  private $active_year, $active_month, $active_day, $current_date;
  private $events = [];

  public function __construct($date = null)
  {
    $this->active_year = $date != null ? date('Y', strtotime($date)) : date('Y');
    $this->active_month = $date != null ? date('m', strtotime($date)) : date('m');
    $this->active_day = $date != null ? date('d', strtotime($date)) : date('d');
    $this->current_date = $date;
  }

  public function add_event($txt, $date, $days = 1, $color = '')
  {
    $color = $color ? ' ' . $color : $color;
    $this->events[] = [$txt, $date, $days, $color];
  }

  public function __toString()
  {

    $next_month = date('Y-m-d', strtotime('first day of next month', strtotime($this->active_day . '-' . $this->active_month . '-' . $this->active_year)));
    $prev_month = date('Y-m-d', strtotime('first day of previous month', strtotime($this->active_day . '-' . $this->active_month . '-' . $this->active_year)));

    $num_days = date('t', strtotime($this->active_day . '-' . $this->active_month . '-' . $this->active_year));
    $num_days_last_month = date('j', strtotime('last day of previous month', strtotime($this->active_day . '-' . $this->active_month . '-' . $this->active_year)));
    $days = [0 => 'Sun', 1 => 'Mon', 2 => 'Tue', 3 => 'Wed', 4 => 'Thu', 5 => 'Fri', 6 => 'Sat'];
    $first_day_of_week = array_search(date('D', strtotime($this->active_year . '-' . $this->active_month . '-1')), $days);
    $html = '<div class="art-calendar" data-current-date="' . 5 . '">';
    $html .= '<div class="header">';
    $html .= '<div class="month-year">';
    $html .= date('F, Y', strtotime($this->active_year . '-' . $this->active_month . '-' . $this->active_day));
    $html .= '</div>';

    $html .= '<div class="nav">';
    $html .= '<button class="btn btn-warning" onclick="fullCalendarPrev(`' . $prev_month . '`)">PREV</button>';
    $html .= '<button class="btn btn-primary ml-25px" onclick="fullCalendarNext(`' . $next_month . '`)">NEXT</button>';
    $html .= '</div>';

    $html .= '</div>';
    $html .= '<div class="days">';
    foreach ($days as $day) {
      $html .= '
                <div class="day_name">
                    ' . $day . '
                </div>
            ';
    }
    for ($i = $first_day_of_week; $i > 0; $i--) {
      $html .= '
                <div class="day_num ignore">
                    ' . ($num_days_last_month - $i + 1) . '
                </div>
            ';
    }
    for ($i = 1; $i <= $num_days; $i++) {
      $selected = '';
      if ($i == $this->active_day) {
        $selected = ' selected';
      }
      $html .= '<div class="day_num' . $selected . '">';
      $html .= '<span>' . $i . '</span>';
      foreach ($this->events as $event) {
        for ($d = 0; $d <= ($event[2] - 1); $d++) {
          if (date('y-m-d', strtotime($this->active_year . '-' . $this->active_month . '-' . $i . ' -' . $d . ' day')) == date('y-m-d', strtotime($event[1]))) {
            $event_position = '';
            if ($event[2] > 1) {
              if ($d == 0) {
                $event_position = 'start';
              }
              if ($d > 0 && $d < $event[2] - 1) {
                $event_position = 'middle';
              }
              if ($d == $event[2] - 1) {
                $event_position = 'end';
              }
            }

            $html .= '<div class="event ' . $event[3] . $event_position . '">';
            $html .= $event[0];
            $html .= '</div>';
          }
        }
      }
      $html .= '</div>';
    }
    for ($i = 1; $i <= (42 - $num_days - max($first_day_of_week, 0)); $i++) {
      $html .= '
                <div class="day_num ignore">
                    ' . $i . '
                </div>
            ';
    }
    $html .= '</div>';
    $html .= '</div>';
    return $html;
  }

  public static function fullCalendar()
  {
    $current_date = W_Main::formatDate('', 'Y-m-d');
    $calendar = new artCalendar($current_date);
    $calendar->add_event('Birthday', '2023-04-04', 1, 'green');
    $calendar->add_event('tes', '2023-04-04', 1, 'red');
    $calendar->add_event('3', '2023-04-04', 1, 'blue');
    $calendar->add_event('3', '2023-04-04', 1, 'blue');
    $calendar->add_event('3', '2023-04-04', 1, 'blue');
    $calendar->add_event('3', '2023-04-04', 1, 'blue');
    $calendar->add_event('3', '2023-04-04', 1, 'blue');
    $calendar->add_event('3', '2023-04-04', 1, 'blue');
    $calendar->add_event('Doctors', '2023-04-04', 1, 'red');
    $calendar->add_event('Holiday', '2023-04-16', 7);

    return $calendar;
  }
}
