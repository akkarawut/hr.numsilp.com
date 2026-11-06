<?php
class Homepagify
{
  public static function createPagination($total_count = 0, $page_size = 0, $page = 0, $params = 'p', $max_button = 3)
  {
    $html  = '';
    $total = 0;
    if ($page_size > 0) {
      $total = ceil($total_count / $page_size);
    }

    $start = 1;
    $Prev  = $page - 1;
    $next  = $page + 1;
    $url   = isset($_GET) ? $_GET : [];
    $end   = $total;

    $url_link = '?';
    if (is_array($url)) {
      if (isset($url[$params])) {
        unset($url[$params]);
      }
      foreach ($url as $key => $value) {
        $url_link .= $key . '=' . $value . '&';
      }
    }

    if ($total > $max_button + 2) {
      if ($page > 3) {
        $start = $page - 1;
        if ($page > $total - $max_button) {
          $start = $total - $max_button;
        }
      } else if ($page > 2) {
        $start = $page - 2;
      }

      if ($start + $max_button < $total) {
        if ($page <= $max_button + 1) {
          $end = $start + $max_button;
        } else {
          $end = $start + $max_button - 1;
        }
      }
    }

    echo '<nav class="my-pagination" aria-label="Page navigation">
        <ul class="pagination">';
    echo '<li  class="page-item "><a  disabled class="page-link ' . (($page == 1) ? 'disabled' : '') . ' fas fa-angle-left" data-page="' . $Prev . '" href="' . $url_link . '' . $params . '=' . $Prev . '"></a></li>';
    if ($start > 2 && $total > $max_button + 2) {
      echo '<li class="page-item">
            <a class="page-link" href="' . $url_link . '' . $params . '=1">
              1
            </a>
          </li>';
      echo '<li class="page-link dots ">...</li>';
    }

    for ($i = $start; $i <= $end; $i++) {
      echo '<li class="page-item"><a class="page-link ' . (($page == $i) ? 'active' : '') . '" data-page="' . $i . '"href="' . $url_link . '' . $params . '=' . $i . '">' . $i . '</a></li>';
    }

    if ($end < $total) {
      echo '<li class="page-link dots ">...</li>';
      echo '<li class="page-item"><a class="page-link ' . (($page == $total) ? 'active' : '') . '" data-page=' . $total . '" href="' . $url_link . '' . $params . '=' . $total . '">' . $total . '</a></li>';
    }
    echo '<li class="page-item"><a class="page-link ' . (($page == $total) ? 'disabled' : '') . ' fas fa-angle-right" data-page=' . $next . '" href="' . $url_link . '' . $params . '=' . $next . '"></a></li>';
    echo '</ul></nav>';
    return $html;
  }

  public static function createFitter($table = '', $customInput = false, $get = 0)
  {
    $html = '';
    echo '<div class="filter-card"> ';
    echo '<div class="d-flex align-items-center justify-content-between px-15px py-10px">
              <div class="filter-title">
                <p class="mb-0">Filter</p>
              </div>
              <div>
                <div class="btn-hide-filter">
                  <span class="mr-10px">Hide filter</span>
                </div>
              </div>
            </div>';
    echo '<form method="post" class="homepage-form" data-homepagify="' . $table . '" data-send_get="' . $get . '">
              <div class="col-12">
                <div class="form-row">';
    foreach ($customInput as $input) {
      if (!isset($input['option'])) {
        $type = empty($input['type']) ? 'text' : $input['type'];
        $type = ($type == 'number_between') ?: 'number';
        if (!is_array($input['name'])) {
          echo '<div class="col-md-4 col-lg-2">
                    <div class="form-group">
                      <label>' . $input['title'] . '</label>
                      <input "' . $type . '" class="form-control filter-param" name="' . $input['name'] . '"  value="">
                    </div>
                  </div>';
        } else {
          echo '<div class="col-md-4 col-lg-2">
                    <div class="form-group">
                      <label>' . $input['title'] . '</label>';
          echo '<div class="form-row d-flex align-items-center">';
          foreach ($input['name'] as $key => $num) {
            $value = isset($value_input[$key]) ? $value_input[$key] : '';
            echo '<div class="col"><input type="' . $type . '" class="form-control filter-param" name="' . $num . '" ></div>';
            if ($key < count($input['name']) - 1) {
              echo '<div class="col-auto">-</div>';
            }
          }
          echo '</div>';
          echo '</div>
            </div>';
        }
      } else {
        echo '<div class="col-md-4 col-lg-2">
                  <div class="form-group">
                    <label>' . $input['title'] . '</label>
                    <select class="form-control filter-param" name="' . $input['name'] . '">
                      <option value="all">All</option>';
        foreach ($input['option'] as $option) {
          echo '<option value="' . $option['id'] . '">' . (isset($option['name']) ? $option['name'] : $option['id']) . '</option>';
        }
        echo '</select>
                  </div>
                </div>';
      }
    }
    echo '<div class="col-auto d-flex align-self-end pb-10px">
              <button type="submit" class="btn  btn-filter-search mr-5px">Search</button>
              <button type="button" class="btn  btn-filter-clear">Clear all</button>
            </div>';
    echo '</div>
            </div>
          </form>';
    echo '</div>';

    return $html;
  }

  public static function getExportLink($class = '', $api = '', $code = '', $where = [], $options = [], $manage = '')
  {
    if ($manage == '') {
      $API_URL  = F_BRIDGE_API_URL;
      if (isset($options['is_ip']) && $options['is_ip']) {
        $path = $options['path_ip'];

        $link_api = explode($path, F_BRIDGE_API_URL);
        $ip_current = $_SERVER['HTTP_ORIGIN'] . '' . $path . '' . $link_api[1];
        $API_URL = $ip_current;
      }
      $options['export'] = 'excel';
      if (!$where) {
        $where['system_delete'] = 0;
      }

      $array_link        = [
        'params'   => [$code, $where, $options],
        'class'    => $class,
        'function' => $api,
        'api_key'  => F_BRIDGE_API_KEY
      ];
      return $API_URL . '?' . http_build_query($array_link) . '<array>0';
    } else if ($manage != '') {
      $API_URL           = F_BRIDGE_API_URL;
      // $options['export'] = 'excel';
      $array_link        = [
        'api' => $class . '::' . $api,
        'params' => [
          'code' => $code,
          'where' => $where,
          'options' => $options
        ]
      ];
      return base64_encode(json_encode($array_link)) . '<array>' . $manage;
    }
  }

  public static function dataFilter($name = '', $type = 'text', $option = false, $value = '')
  {
    // if (is_array($name)) {
    $name = base64_encode(json_encode($name));
    // }

    if (is_array($option)) {
      $option = base64_encode(json_encode($option));
    }
    $value = base64_encode(json_encode($value));
    $filter = '';
    $filter = $name . ',' . $type . ',' . $option . ',' . $value;
    return $filter;
  }

  public static function createCheckboxThead($name = 'checkboxAll', $value = '', $options = [])
  {
    $style_list = [];
    $options_list = $options;
    if (isset($options_list['class'])) {
      $options_list['class'] = $options_list['class'] . ' table-checked-all';
    } else {
      $options_list['class'] = 'table-checked-all';
    }

    $options_list['id']  = $name;
    $options_list['for']  = $name;
    $style_list['style']  = '3';

    $checkbox =  TiwForm::normal('checkbox', $value, $options_list, $style_list);
    return $checkbox;
  }

  public static function createCheckboxTBody($name = 'checkbox', $value = '', $options = [])
  {
    $style_list = [];
    $options_list = $options;


    if (isset($options_list['class'])) {
      $options_list['class'] = $options_list['class'] . ' table-checked';
    } else {
      $options_list['class'] = 'table-checked';
    }
    $options_list['name']  = isset($options_list['name']) ? $options_list['name'] : 'checkbox_list';
    $options_list['id']  = isset($options_list['id']) ? $options_list['id'] : $name;
    $options_list['for']  = isset($options_list['id']) ? $options_list['id'] : $name;



    $style_list['style']  = '3';
    $checkbox =  TiwForm::normal('checkbox', $value, $options_list, $style_list);
    return $checkbox;
  }

  public static function createHomepagify($id = 'table', $send_param = '', $active = '', $header_name = '', $prefix = '', $page_no = 1, $page_size = 10, $customButton = false, $name_states = '')
  {

    $get          = $send_param;
    $get_url      = base64_encode(json_encode($_GET));
    $button       = base64_encode(json_encode($customButton));
    $header_name  = empty($header_name) ? 'All' : $header_name;
    $code         = isset($_GET['c']) ? $_GET['c'] : '';
    $html         = '';
    $pathname     = $_SERVER['PHP_SELF'];
    $name_state   = 'homepgify_table_' . $id . '' . $code . '_' . $pathname;
    $get_state    = SC::get($name_state);
    // $get_state    = [];
    $state_filter = isset($get_state['param_send']) ? base64_encode(json_encode($get_state['param_send'])) : base64_encode(json_encode([]));
    $page_no      = isset($get_state['page_no']) ? $get_state['page_no'] : 1;
    $page_size    = isset($get_state['page_size']) ? $get_state['page_size'] : 10;
    $data_sort    = isset($get_state['data_sort']) ? base64_encode(json_encode($get_state['data_sort'])) : base64_encode(json_encode([]));
    $data_column  = isset($get_state['colum_index']) ? base64_encode(json_encode($get_state['colum_index'])) : base64_encode(json_encode([]));
    $item_pagify_count  = isset($get_state['item_pagify_count']) ? $get_state['item_pagify_count'] : 0;

    echo 'data-url=' . '"' . $prefix . 'ajax/table_' . $id . '.php' . $get . '"';
    echo 'data-active="' . $active . '"';
    echo 'data-code="' . $code . '"';
    echo 'data-header_name="' . $header_name . '"';
    echo 'data-page_no="' . $page_no . '"';
    echo 'data-page_size="' . $page_size . '"';
    echo 'data-state_filter="' . $state_filter . '"';
    echo 'data-state_sort="' . $data_sort . '"';
    echo 'data-name ="' . $name_state . '"';
    echo 'data-state_column  ="' . $data_column . '"';
    echo 'data-item_pagify_count  ="' . $item_pagify_count . '"';


    // echo 'data-state"'.$get_state.'"';


    return $html;
  }
}
