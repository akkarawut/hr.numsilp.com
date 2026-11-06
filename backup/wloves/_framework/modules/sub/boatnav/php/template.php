<?php
class Boatnav
{
  public static function wolves($data_nav, $link = '', $prefix = '../../')
  {
    global $program_wolves_setup;
    $prefix_path = ($prefix) ? $prefix : '';
    $param_name = $data_nav['param_name'];
    $type = (isset($data_nav['type']) && $data_nav['type'] != '') ? $data_nav['type'] : 'single';
    $is_permission = (isset($data_nav['is_permission']) && $data_nav['is_permission'] != '') ? $data_nav['is_permission'] : false;
    $user_permission = [];
    if ($is_permission) {
      $user_permission = $program_wolves_setup['user_permission'];
    }
    if ($user_permission) {
      if ($type == 'mutiple') {
        foreach ($data_nav['title_list'] as $title_list_key => $title_list) {
          foreach ($title_list['list'] as $list_key => $list) {
            if (isset($user_permission[$list['permission']]) && $user_permission[$list['permission']] == 0) {
              unset($data_nav['title_list'][$title_list_key]['list'][$list_key]);
            }
          }
        }
        foreach ($data_nav['title_list'] as $title_list_key => $title_list) {
          if (count($title_list['list']) == 0) {
            unset($data_nav['title_list'][$title_list_key]);
          }
        }
        if (isset($_GET[$param_name]) && $_GET[$param_name]) {
          $get_page = $_GET[$param_name];
          $is_page = false;
          foreach ($data_nav['title_list'] as $title_list_key => $title_list) {
            foreach ($title_list['list'] as $list_key => $list) {
              if ($list['id'] ==  $get_page) {
                $is_page = true;
              }
            }
          }
          if (!$is_page) {
            W_Main::redirect('?c=' . $_GET['c']);
          }
        }
      }
    }
    if (!$link) {
      $link = $_SERVER['REQUEST_URI'];
      $link = explode("/", $link);
      $count = count($link);
      $link = $link[$count - 1];
      if (isset($_GET[$param_name])) {
        $link = str_replace('&' . $param_name . '=' . $_GET[$param_name], '', $link);
      }
    }
    $param_selected  = isset($_GET[$param_name]) ? $_GET[$param_name] : '1';
    $class = (isset($data_nav['class']) && $data_nav['class'] != '') ? $data_nav['class'] : '';
    $html  = '';
    if ($type == 'single') {
      $html .= '<div class="boat-nav-wolves ' . $class . '">';
      $html .= (isset($data_nav['title']) && $data_nav['title'] != '') ? '<div class="title font-14px text-uppercase mb-5px">' . $data_nav['title'] . '</div>' : '';
      foreach ($data_nav['list'] as $data) {
        $active       = ($data['id'] == $param_selected) ? 'active' : '';
        $icon = '';
        if (isset($data['icon']) && $data['icon']) {
          if (isset($data['type_icon']) && $data['type_icon'] == 'img') {
            $icon = '<img src="' . $prefix_path . $data['icon'] . '" alt="' . $data['name'] . '" />';
          } else {
            $icon = file_get_contents($prefix_path . $data['icon']);
          }
        }
        $color_status = (isset($data['color_status']) && $data['color_status'] != '') ? $data['color_status'] : '';
        $html .= '<a href="' . $link . '&' . $param_name . '=' . $data['id'] . '" class="list ' . $active . '">';
        $html .= (isset($data['icon']) && $data['icon'] != '') ? '<span class="icon">' . $icon . '</span>' : '';
        $html .= ($data['name']) ? '<span class="name">' . $data['name'] . '</span>' : '';
        $html .= (isset($data['count']) && $data['count'] !== '' && $data['count'] >= 0) ? '<span class="count ' . $color_status . '">(' . $data['count'] . ')</span>' : '';
        $html .= (isset($data['status']) && $data['status'] != '') ? '<span class="status ' . $color_status . '">' . $data['status'] . '</span>' : '';
        $html .= (isset($data['html']) && $data['html'] != '') ? $data['html'] : '';
        $html .= '</a>';
      }
      $html .= '</div>';
    } else if ($type == 'mutiple') {
      $html .= '<div class="boat-nav-wolves-v2 ' . $class . '">';
      foreach ($data_nav['title_list'] as $title_data) {
        $icon = '';
        if (isset($title_data['icon']) && $title_data['icon']) {
          if (isset($title_data['type_icon']) && $title_data['type_icon'] == 'img') {
            $icon = '<img src="' . $prefix_path . $title_data['icon'] . '" alt="' . $title_data['title'] . '" />';
          } else {
            $icon = W_Structure::getfile($title_data['icon']);
          }
        }
        if (isset($title_data['title']) && $title_data['title'] != '') {
          $html .=  '<div class="title">';
          $html .= (isset($title_data['icon']) && $title_data['icon'] != '') ? '<span class="icon">' . $icon . '</span>' : '';
          $html .=  $title_data['title'] . '</div>';
        }
        foreach ($title_data['list'] as $data) {
          $icon_list = '';
          if (isset($data['icon']) && $data['icon']) {
            if (isset($data['type_icon']) && $data['type_icon'] == 'img') {
              $icon_list = '<img src="' . $prefix_path . $data['icon'] . '" alt="' . $data['title'] . '" />';
            } else {
              $icon_list = W_Structure::getfile($data['icon']);
            }
          }
          $active       = ($data['id'] == $param_selected) ? 'active' : '';
          $color_status = (isset($data['color_status']) && $data['color_status'] != '') ? $data['color_status'] : '';

          if (isset($data['modal_id']) && $data['modal_id'] != '') {
            $modal = Tiwdal::register($data['modal_id'], [], ['is_ajax' => true, 'is_return' => true]);
            $html .= '<div class="cursor-pointer list ' . $active . '" ' . $modal . '>';
            $html .= (isset($data['icon']) && $data['icon'] != '') ? '<span class="icon mr-5px">' . $icon_list . '</span>' : '';
            $html .= ($data['name']) ? '<span class="name">' . $data['name'] . '</span>' : '';
            $html .= (isset($data['count']) && $data['count'] !== '' && $data['count'] >= 0) ? '<span class="count ' . $color_status . '">(' . $data['count'] . ')</span>' : '';
            $html .= (isset($data['status']) && $data['status'] != '') ? '<span class="status ' . $color_status . '">' . $data['status'] . '</span>' : '';
            $html .= (isset($data['html']) && $data['html'] != '') ? $data['html'] : '';
            $html .= '</div>';
          } else {
            $html .= '<a href="' . $link . '&' . $param_name . '=' . $data['id'] . '" class="list ' . $active . '">';
            $html .= (isset($data['icon']) && $data['icon'] != '') ? '<span class="icon mr-5px">' . $icon_list . '</span>' : '';
            $html .= ($data['name']) ? '<span class="name">' . $data['name'] . '</span>' : '';
            $html .= (isset($data['count']) && $data['count'] !== '' && $data['count'] >= 0) ? '<span class="count ' . $color_status . '">(' . $data['count'] . ')</span>' : '';
            $html .= (isset($data['status']) && $data['status'] != '') ? '<span class="status ' . $color_status . '">' . $data['status'] . '</span>' : '';
            $html .= (isset($data['html']) && $data['html'] != '') ? $data['html'] : '';
            $html .= '</a>';
          }
        }
      }
      $html .= '</div>';
    }
    echo $html;
  }

  public static function wolvesLink($data_nav, $link = '')
  {
    $param_name = $data_nav['param_name'];
    if (!$link) {
      $link = $_SERVER['REQUEST_URI'];
      $link = explode("/", $link);
      $count = count($link);
      $link = $link[$count - 1];
      if (isset($_GET[$param_name])) {
        $link = str_replace('&' . $param_name . '=' . $_GET[$param_name], '', $link);
      }
    }
    $param_selected  = isset($_GET[$param_name]) ? $_GET[$param_name] : '999';
    $class = (isset($data_nav['class']) && $data_nav['class'] != '') ? $data_nav['class'] : '';
    $html  = '';
    $html .= '<div class="boat-nav-wolves ' . $class . '">';
    $html .= (isset($data_nav['title']) && $data_nav['title'] != '') ? '<div class="title font-14px text-uppercase mb-5px">' . $data_nav['title'] . '</div>' : '';
    foreach ($data_nav['list'] as $data) {
      $active       = ($data['id'] == $param_selected) ? 'active' : '';
      $disabled = isset($data['disabled']) && $data['disabled'] ? 'disabled' : '';

      $icon = '';
      if (isset($data['icon']) && $data['icon']) {
        if (isset($data['type_icon']) && $data['type_icon'] == 'img') {
          $icon = '<img src="' . $data['icon'] . '" alt="' . $data['name'] . '" />';
        } else {
          $icon = file_get_contents($data['icon']);
        }
      }
      $color_status = (isset($data['color_status']) && $data['color_status'] != '') ? $data['color_status'] : '';
      if ($disabled) {
        $html .= '<a class="list disabled ' . $active . '">';
      } else {
        $html .= '<a href="' . $link . '&' . $param_name . '=' . $data['id'] . '" class="list ' . $active . '">';
      }
      $html .= (isset($data['icon']) && $data['icon'] != '') ? '<span class="icon">' . $icon . '</span>' : '';
      $html .= ($data['name']) ? '<span class="name">' . $data['name'] . '</span>' : '';
      $html .= (isset($data['count']) && $data['count'] !== '' && $data['count'] >= 0) ? '<span class="count ' . $color_status . '">(' . $data['count'] . ')</span>' : '';
      $html .= (isset($data['status']) && $data['status'] != '') ? '<span class="status ' . $color_status . '">' . $data['status'] . '</span>' : '';
      $html .= (isset($data['html']) && $data['html'] != '') ? $data['html'] : '';
      $html .= '</a>';
    }
    $html .= '</div>';
    echo $html;
  }

  public static function milkclub($data_nav, $link = '', $prefix = '')
  {
    $prefix_path = ($prefix) ? $prefix : '../../';
    $param_name = $data_nav['param_name'];
    if (!$link) {
      $link = $_SERVER['REQUEST_URI'];
      $link = explode("/", $link);
      $count = count($link);
      $link = $link[$count - 1];
      if (isset($_GET[$param_name])) {
        $link = str_replace('&' . $param_name . '=' . $_GET[$param_name], '', $link);
      }
    }
    $param_selected  = isset($_GET[$param_name]) ? $_GET[$param_name] : '';
    $class = (isset($data_nav['class']) && $data_nav['class'] != '') ? $data_nav['class'] : '';
    $html  = '';
    $html .= '<div class="boat-nav-milkclub ' . $class . '">';
    $html .= '<div class="nav-search"><input type="search" name="search">' . file_get_contents($prefix_path . 'structure/image/icon/form/icon-search.svg') . '</div>';
    $idx = 0;
    foreach ($data_nav['list'] as $listFirst) {
      if ($listFirst['list']) {
        $activeFirst = '';
        if (!$param_selected && $idx == 0) {
          $activeFirst = 'active';
          $idx++;
        } else {
          foreach ($listFirst['list'] as $listSecond) {
            if ($listSecond['list']) {
              foreach ($listSecond['list'] as $listThird) {
                if ($listThird['id'] == $param_selected) {
                  $activeFirst = 'active';
                  continue;
                }
              }
            }
          }
        }
        $html .= '<div class="list ' . $activeFirst . '">';
        $html .= '<div class="list-frist">';
        if (($listFirst['list'])) {
          $color_status = (isset($data['color_status']) && $data['color_status'] != '') ? $data['color_status'] : '';
          $html .= '<span class="name">';
          $html .= '<div>';
          $html .= (isset($listFirst['icon']) && $listFirst['icon'] != '') ? '<span class="icon">' . file_get_contents($prefix_path . $listFirst['icon']) . '</span>' : '';
          $html .= $listFirst['name'];
          $html .= (isset($listFirst['count']) && $listFirst['count'] !== '' && $listFirst['count'] >= 0) ? '<span class="count ' . $color_status . '">(' . $listFirst['count'] . ')</span>' : '';
          $html .= (isset($listFirst['status']) && $listFirst['status'] != '') ? '<span class="status ' . $color_status . '">' . $listFirst['status'] . '</span>' : '';
          $html .= (isset($listFirst['html']) && $listFirst['html'] != '') ? $listFirst['html'] : '';
          $html .= '</div>';
          $html .= file_get_contents($prefix_path . 'structure/image/icon/arrow/icon-arrow.svg');
          $html .= '</span>';
        }
        $html .= '</div>';
        foreach ($listFirst['list'] as $listSecond) {
          if ($listSecond['list']) {
            if ($listSecond['title']) {
              $html .= '<div class="list-second">';
              $html .= '<span class="title">' . $listSecond['title'] . '</span>';
              $html .= '</div>';
            }
            $html .= '<div class="scop-list-third">';
            foreach ($listSecond['list'] as $listThird) {
              $activeThird  = ($listThird['id'] == $param_selected) ? 'active' : '';
              $noListSecond = ($listSecond['title']) ? '' : 'no-list-second';
              $sendParam    = (isset($listThird['send_param']) && $listThird['send_param'] != '') ? $listThird['send_param'] : '';
              $html .= '<a href="' . $link . $sendParam . '&' . $param_name . '=' . $listThird['id'] . '" class="list-third ' . $noListSecond . ' ' . $activeThird . '">';
              $html .= (isset($listThird['icon']) && $listThird['icon'] != '') ? '<span class="icon">' . file_get_contents($prefix_path . $listThird['icon']) . '</span>' : '';
              $html .= (isset($listThird['name']) && $listThird['name'] != '') ? '<span class="name">' . $listThird['name'] . '</span>' : '';
              $html .= (isset($listThird['count']) && $listThird['count'] !== '' && $listThird['count'] >= 0) ? '<span class="count ' . $color_status . '">(' . $listThird['count'] . ')</span>' : '';
              $html .= (isset($listThird['status']) && $listThird['status'] != '') ? '<span class="status ' . $color_status . '">' . $listThird['status'] . '</span>' : '';
              $html .= (isset($listThird['html']) && $listThird['html'] != '') ? $listThird['html'] : '';
              $html .= '</a>';
            }
            $html .= '</div>';
          }
        }
        $html .= '</div>';
      }
    }
    $html .= '</div>';
    echo $html;
  }

  public static function dinner($data_nav, $link = '', $prefix = '')
  {
    global $program_wolves_setup;

    $prefix_path = ($prefix) ? $prefix : '../../';
    $param_name = $data_nav['param_name'];
    $is_permission = (isset($data_nav['is_permission']) && $data_nav['is_permission'] != '') ? $data_nav['is_permission'] : false;
    $user_permission = [];
    if ($is_permission) {
      $user_permission = $program_wolves_setup['user_permission'];
    }
    if ($user_permission) {

      foreach ($data_nav['list'] as $list_key => $list) {
        if (!isset($user_permission[$list['permission']])) {
          unset($data_nav['list'][$list_key]);
        } else {
          if ($user_permission[$list['permission']] == 0) {
            unset($data_nav['list'][$list_key]);
          }
        }
      }
      if (isset($_GET[$param_name]) && $_GET[$param_name]) {
        $get_page = $_GET[$param_name];
        $is_page = false;
        foreach ($data_nav['list'] as $list) {
          if ($list['id'] ==  $get_page) {
            $is_page = true;
          }
        }
        if (!$is_page) {
          W_Main::redirect('?c=' . $_GET['c']);
        }
      }
    }
    if (!$link) {
      $link = $_SERVER['REQUEST_URI'];
      $link = explode("/", $link);
      $count = count($link);
      $link = $link[$count - 1];
      if (isset($_GET[$param_name])) {
        $link = str_replace('&' . $param_name . '=' . $_GET[$param_name], '', $link);
      }
    }
    $param_selected  = isset($_GET[$param_name]) ? $_GET[$param_name] : '1';
    $class = (isset($data_nav['class']) && $data_nav['class'] != '') ? $data_nav['class'] : '';
    $html  = '';

    $html .= '<div class="position-relative px-10px">';
    $html .= '<div class="boat-nav-dinner ' . $class . '">';
    $html .= '<div class="arrow-scroll-left"></div>';
    foreach ($data_nav['list'] as $data) {
      $active = ($data['id'] == $param_selected) ? 'active' : '';
      $color_status = (isset($data['color_status']) && $data['color_status'] != '') ? $data['color_status'] : '';
      $get_str = '?';
      if (strpos($link, '?') !== false) {
        $get_str = '&';
      }
      $html .= '<a href="' . $link . $get_str . $param_name . '=' . $data['id'] . '" class="list ' . $active . ' ' . $color_status . '">';
      $html .= (isset($data['icon']) && $data['icon'] != '') ? '<span class="icon">' . file_get_contents($prefix_path . $data['icon']) . '</span>' : '';
      $html .= '<span class="name">' . $data['name'] . '</span>';
      $html .= (isset($data['count']) && $data['count'] != '' && $data['count'] >= 0) ? '<span class="count">(' . $data['count'] . ')</span>' : '';
      $html .= '</a>';
    }
    $html .= '<div class="arrow-scroll-right"></div>';
    $html .= '</div>';
    $html .= '</div>';
    echo $html;
  }

  public static function wolves_module($data_nav, $link, $param_name, $param_selected)
  {
    if (isset($data['icon'])) {
      $icon = $data['icon'];
    } else {
      $icon = '../../structure/image/icon/general/icon-setting.svg';
    }


    $class = (isset($data_nav['class']) && $data_nav['class'] != '') ? $data_nav['class'] : '';
    $html  = '';
    $html .= '<div class="boat-nav-wolves module ' . $class . '">';
    $html .= (isset($data_nav['title']) && $data_nav['title'] != '') ? '<div class="title">' . $data_nav['title'] . '</div>' : '';
    foreach ($data_nav['list'] as $data) {
      $module = ($data['module']) ? $data['module'] : '';
      $height = '';
      if ($module != 'User') {
        $height = 'align-items-center';
      }
      $name = ($data['name']) ? $data['name'] : '';
      $manage_user_type = isset($data['manage_user_type']) ? ($data['manage_user_type']) ? $data['manage_user_type'] : 'n/a' : 'n/a';
      $active       = ($data['id'] == $param_selected) ? 'active' : '';
      $color_status = (isset($data['color_status']) && $data['color_status'] != '') ? $data['color_status'] : '';
      $html .= '<a href="' . $link . '&' . $param_name . '=' . $data['id'] . '" class="list ' . $active . ' ' . $height . '">';
      $html .= (isset($icon)) ? '<span class="icon">' . file_get_contents($icon) . '</span>' : '';
      $html .= '<span class="name">' . $module . ' | ' . $name . '<br>';
      if ($module == 'User') {
        $html .= 'User type: <span class=" text-primary ">' . $manage_user_type . '</span></span>';
      }
      $html .= (isset($data['count']) && $data['count'] !== '' && $data['count'] >= 0) ? '<span class="count ' . $color_status . '">(' . $data['count'] . ')</span>' : '';
      $html .= (isset($data['status']) && $data['status'] != '') ? '<span class="status ' . $color_status . '">' . $data['status'] . '</span>' : '';
      $html .= (isset($data['html']) && $data['html'] != '') ? $data['html'] : '';
      $html .= '</a>';
    }
    $html .= '</div>';
    echo $html;
  }
  public static function wolves_backup($data_nav, $link = '', $prefix = '')
  {
    $prefix_path = ($prefix) ? $prefix : '../../';
    $param_name = $data_nav['param_name'];
    if (!$link) {
      $link = $_SERVER['REQUEST_URI'];
      $link = explode("/", $link);
      $count = count($link);
      $link = $link[$count - 1];
      if (isset($_GET[$param_name])) {
        $link = str_replace('&' . $param_name . '=' . $_GET[$param_name], '', $link);
      }
    }
    $param_selected  = isset($_GET[$param_name]) ? $_GET[$param_name] : '1';
    $class = (isset($data_nav['class']) && $data_nav['class'] != '') ? $data_nav['class'] : '';
    $html  = '';
    $html .= '<div class="boat-nav-wolves ' . $class . '">';
    $html .= (isset($data_nav['title']) && $data_nav['title'] != '') ? '<div class="title font-14px text-uppercase mb-5px">' . $data_nav['title'] . '</div>' : '';
    foreach ($data_nav['list'] as $data) {
      $active       = ($data['id'] == $param_selected) ? 'active' : '';
      $icon = '';
      if (isset($data['icon']) && $data['icon']) {
        if (isset($data['type_icon']) && $data['type_icon'] == 'img') {
          $icon = '<img src="' . $prefix_path . $data['icon'] . '" alt="' . $data['name'] . '" />';
        } else {
          $icon = file_get_contents($prefix_path . $data['icon']);
        }
      }
      $color_status = (isset($data['color_status']) && $data['color_status'] != '') ? $data['color_status'] : '';
      $html .= '<a href="' . $link . '&' . $param_name . '=' . $data['id'] . '" class="list ' . $active . '">';
      $html .= (isset($data['icon']) && $data['icon'] != '') ? '<span class="icon">' . $icon . '</span>' : '';
      $html .= ($data['name']) ? '<span class="name">' . $data['name'] . '</span>' : '';
      $html .= (isset($data['count']) && $data['count'] !== '' && $data['count'] >= 0) ? '<span class="count ' . $color_status . '">(' . $data['count'] . ')</span>' : '';
      $html .= (isset($data['status']) && $data['status'] != '') ? '<span class="status ' . $color_status . '">' . $data['status'] . '</span>' : '';
      $html .= (isset($data['html']) && $data['html'] != '') ? $data['html'] : '';
      $html .= '</a>';
    }
    $html .= '</div>';
    echo $html;
  }
  public static function topbar($data_nav, $link = '', $prefix = '')
  {
    global $program_wolves_setup;

    $prefix_path = ($prefix) ? $prefix : '../../../';
    $param_name = $data_nav['param_name'];
    $hide_icon = (isset($data_nav['hide_icon']) && $data_nav['hide_icon']) ? true : false;

    $is_permission = (isset($data_nav['is_permission']) && $data_nav['is_permission'] != '') ? $data_nav['is_permission'] : false;
    $user_permission = [];
    if ($is_permission) {
      $user_permission = $program_wolves_setup['user_permission'];
    }
    if ($user_permission) {
      foreach ($data_nav['list'] as $list_key => $list) {
        if (!isset($user_permission[$list['permission']])) {
          unset($data_nav['list'][$list_key]);
        } else {
          if ($user_permission[$list['permission']] == 0) {
            unset($data_nav['list'][$list_key]);
          }
        }
      }
      if (isset($_GET[$param_name]) && $_GET[$param_name]) {
        $get_page = $_GET[$param_name];
        $is_page = false;
        foreach ($data_nav['list'] as $list) {
          if ($list['id'] ==  $get_page) {
            $is_page = true;
          }
        }
        if (!$is_page) {
          W_Main::redirect('?c=' . $_GET['c']);
        }
      }
    }
    if (!$link) {
      $link = $_SERVER['REQUEST_URI'];
      $link = explode("/", $link);
      $count = count($link);
      $link = $link[$count - 1];
      if (isset($_GET[$param_name])) {
        $link = str_replace('&' . $param_name . '=' . $_GET[$param_name], '', $link);
      }
    }
    $param_selected  = isset($_GET[$param_name]) ? $_GET[$param_name] : '1';
    $class = (isset($data_nav['class']) && $data_nav['class'] != '') ? $data_nav['class'] : '';
    $html  = '';
    $html .= '<div class="boat-nav-topbar ' . $class . '">';
    foreach ($data_nav['list'] as $data) {
      $active = ($data['id'] == $param_selected) ? 'active' : '';
      $color_status = (isset($data['color_status']) && $data['color_status'] != '') ? $data['color_status'] : '';
      $class = (isset($data['class']) && $data['class'] != '') ? $data['class'] : '';
      $get_str = '?';
      if (strpos($link, '?') !== false) {
        $get_str = '&';
      }
      $html .= '<a href="' . $link . $get_str . $param_name . '=' . $data['id'] . '" class="list ' . $active . ' ' . $color_status . ' ' . $class . '">';
      $html .= (isset($data['icon']) && $data['icon'] != '' &&  !$hide_icon) ? '<span class="icon">' . file_get_contents($prefix_path . $data['icon']) . '</span>' : '';
      $html .= '<span class="name">' . $data['name'] . '</span>';
      $html .= (isset($data['count']) && $data['count'] != '' && $data['count'] >= 0) ? '<span class="count">(' . $data['count'] . ')</span>' : '';
      $html .= '</a>';
    }
    $html .= '</div>';
    echo $html;
  }

  public static function searchMode($data_nav, $link = '', $prefix = '')
  {
    $prefix_path = ($prefix) ? $prefix : '../../../';
    $param_name = $data_nav['param_name'];
    if (!$link) {
      $link = $_SERVER['REQUEST_URI'];
      $link = explode("/", $link);
      $count = count($link);
      $link = $link[$count - 1];
      if (isset($_GET[$param_name])) {
        $link = str_replace('&' . $param_name . '=' . $_GET[$param_name], '', $link);
      }
    }
    $param_selected  = isset($_GET[$param_name]) ? $_GET[$param_name] : '1';
    $class = (isset($data_nav['class']) && $data_nav['class'] != '') ? $data_nav['class'] : '';
    $title = (isset($data_nav['title']) && $data_nav['title'] != '') ? $data_nav['title'] : '';
    $is_count = (isset($data_nav['is_count']) && $data_nav['is_count']) ? true : false;
    $title_name = (isset($data_nav['title_name']) && $data_nav['title_name'] != '') ? $data_nav['title_name'] : '';
    $title_count = (isset($data_nav['title_count']) && $data_nav['title_count'] != '') ? $data_nav['title_count'] : '';
    $is_search = isset($data_nav['is_search']) ? $data_nav['is_search'] : true;
    $placeholder = (isset($data_nav['placeholder']) && $data_nav['placeholder'] != '') ? $data_nav['placeholder'] : '';
    $html_count = $is_count ? '<span class="count_nav">(' . count($data_nav['list']) . ')</span>' : '';
    $html  = '';
    $html .= '<div class="boat-nav-search ' . $class . '">';
    $html .= '<div class="title">' . $title . ' ' . $html_count . '</div>';
    if ($is_search) {
      $html .= '<div class="search_nav">';
      $html .= '<input type="search" class="search" placeholder="' . $placeholder . '" oninput="searchNav(event)">';
      $html .= '</div>';
    }
    $html .= '<div class="title_search_nav">';
    $html .= '<span class="title_name title-line-clamp">' . $title_name . '</span>';
    $html .= '<span class="title_count title-line-clamp">' . $title_count . '</span>';
    $html .= '</div>';
    foreach ($data_nav['list'] as $data) {
      $active       = ($data['id'] == $param_selected) ? 'active' : '';
      $data_html = isset($data['html']) ? $data['html'] : '';
      $image = '';
      $arr_count =  isset($data['data_count']) ? $data['data_count'] : '';
      $html_count = '';
      if ($arr_count) {
        $html_count .= '<div class="ml-5px d-flex align-items-center flex-wrap">';
        foreach ($arr_count as  $count) {
          if ($count['amount'] != 0) {
            $html_count .= '<span class="count-frame ' . $count['color'] . '">' . $count['amount'] . '</span>';
          }
        }
        $html_count .= '</div>';
      }
      if (isset($data['image']) && $data['image']) {
        if (isset($data['type_image']) && $data['type_image'] == 'image') {
          $image = '<img src="'  . $data['image'] . '" alt="' . $data['name'] . '" />';
        } else {
          $image = file_get_contents($prefix_path . $data['image']);
        }
      }
      $html .= '<a href="' . $link . '&' . $param_name . '=' . $data['id'] . '" class="nav_search_list ' . $active . '">';
      if ($image) {
        $html .= '<div class="d-flex align-items-center">';
        $html .= '<div class="image">' . $image . '</div>';
        $html .=  ($data['name']) ? '<span class="name">' . $data['name'] . $html_count . '</span>' : '';
        $html .= '</div>';
      } else {
        $html .= ($data['name']) ? '<span class="name">' . $data['name'] . $html_count . '</span>' : '';
      }
      $html .= '<div class="d-flex align-items-center">';
      $html .=  $data_html;
      $html .= (isset($data['count']) && $data['count'] !== '' && $data['count'] >= 0) ? '<span class="count">' . $data['count'] . '</span>' : '';
      $html .= '</div>';

      $html .= '</a>';
    }
    $html .= '</div>';
    echo $html;
  }


  public static function milkclubAPI($data_nav, $link, $param_name, $param_selected)
  {
    $class = (isset($data_nav['class']) && $data_nav['class'] != '') ? $data_nav['class'] : '';
    $html  = '';
    $html .= '<div class="boat-nav-milkclub ' . $class . '">';
    $html .= '<div class="nav-search"><input type="search" name="search">' . file_get_contents('../../structure/image/icon/form/icon-search.svg') . '</div>';
    $idx = 0;
    foreach ($data_nav['list'] as $listFirst) {
      if ($listFirst['list']) {
        $activeFirst = '';
        if (!$param_selected && $idx == 0) {
          $activeFirst = 'active';
          $idx++;
        } else {
          foreach ($listFirst['list'] as $listSecond) {
            if ($listSecond['list']) {
              foreach ($listSecond['list'] as $listThird) {
                if ($listThird['id'] == $param_selected) {
                  $activeFirst = 'active';
                  continue;
                }
              }
            }
          }
        }
        $html .= '<div class="list ' . $activeFirst . '">';
        $html .= '<div class="list-frist">';
        if (($listFirst['list'])) {
          $color_status = (isset($data['color_status']) && $data['color_status'] != '') ? $data['color_status'] : '';
          $html .= '<span class="name">';
          $html .= '<div>';
          $html .= (isset($listFirst['icon']) && $listFirst['icon'] != '') ? '<span class="icon">' . file_get_contents($listFirst['icon']) . '</span>' : '';
          $html .= $listFirst['name'];
          $html .= (isset($listFirst['count']) && $listFirst['count'] !== '' && $listFirst['count'] >= 0) ? '<span class="count ' . $color_status . '">(' . $listFirst['count'] . ')</span>' : '';
          $html .= (isset($listFirst['status']) && $listFirst['status'] != '') ? '<span class="status ' . $color_status . '">' . $listFirst['status'] . '</span>' : '';
          $html .= (isset($listFirst['html']) && $listFirst['html'] != '') ? $listFirst['html'] : '';
          $html .= '</div>';
          $html .= file_get_contents('../../structure/image/icon/arrow/icon-arrow.svg');
          $html .= '</span>';
        }
        $html .= '</div>';
        foreach ($listFirst['list'] as $listSecond) {
          if ($listSecond['list']) {
            if ($listSecond['title']) {
              $html .= '<div class="list-second">';
              $html .= '<span class="title text-break">' . $listSecond['title'] . '</span>';
              $html .= '</div>';
            }
            $html .= '<div class="scop-list-third">';
            foreach ($listSecond['list'] as $listThird) {
              $activeThird  = ($listThird['id'] == $param_selected) ? 'active' : '';
              $noListSecond = ($listSecond['title']) ? '' : 'no-list-second';
              $sendParam    = (isset($listThird['send_param']) && $listThird['send_param'] != '') ? $listThird['send_param'] : '';
              $html .= '<a href="' . $link . $sendParam . '&' . $param_name . '=' . $listThird['id'] . '" class="list-third ' . $noListSecond . ' ' . $activeThird . '">';
              $html .= (isset($listThird['icon']) && $listThird['icon'] != '') ? '<span class="icon">' . file_get_contents($listThird['icon']) . '</span>' : '';
              $html .= (isset($listThird['name']) && $listThird['name'] != '') ? '<span class="name">' . $listThird['name'] . '</span>' : '';
              $html .= (isset($listThird['count']) && $listThird['count'] !== '' && $listThird['count'] >= 0) ? '<span class="count ' . $color_status . '">(' . $listThird['count'] . ')</span>' : '';
              $html .= (isset($listThird['status']) && $listThird['status'] != '') ? '<span class="status ' . $color_status . '">' . $listThird['status'] . '</span>' : '';
              $html .= (isset($listThird['html']) && $listThird['html'] != '') ? $listThird['html'] : '';
              $html .= '</a>';
            }
            $html .= '</div>';
          }
        }
        $html .= '</div>';
      }
    }
    $html .= '</div>';
    echo $html;
  }
}
