<?php
class homepagination
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
    echo '<li  class="page-item "><a  disabled class="page-link ' . (($page == 1) ? 'disabled' : '') . '" data-page="' . $Prev . '" href="' . $url_link . '' . $params . '=' . $Prev . '">' . file_get_contents('../../_framework/module/homepagination/pre.svg') . '</a></li>';
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
    echo '<li class="page-item"><a class="page-link ' . (($page == $total || $total_count == 0) ? 'disabled' : '') . '" data-page=' . $next . '" href="' . $url_link . '' . $params . '=' . $next . '">' . file_get_contents('../../_framework/module/homepagination/next.svg') . '</a></li>';
    echo '</ul></nav>';
    return $html;
  }
}
