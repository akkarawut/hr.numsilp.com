<?php
class Brandtoast
{
  public static function notification($message = '', $class = '', $type = 'false', $link = '')
  {
    $check_class = '';
    // $date        = W_Main::formatDate(date('Y-m-d'), 'd/m/Y');
    if ($class == 'success') {
      $check_class = 'Success';
    } else if ($class == 'error') {
      $check_class = 'Error';
    } else if ($class == 'info') {
      $check_class = '';
    }
    echo '<div class="toast-container fixed-bottom position-bottom p-0" data-class="' . $class . '">';
    echo '<div class="row remove-toast">';
    echo '<div class="col">';
    echo '<div role="alert" aria-live="assertive" aria-atomic="true" class="toast ' . $class . '" data-autohide="' . $type . '">';
    echo '<div class="toast-icon">';
    echo '<div class="icon-dot"></div>';
    echo '</div>';
    echo '<div class="toast-content">';
    echo '<p class="toast-type">' . $check_class . '</p>';
    echo '<div class="d-flex justify-content-between">';
    echo '<p class="toast-message">' . $message . '</p>';
    echo '</div>';
    echo '</div>';
    echo '<div class="toast-close">';
    echo '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 15.642 15.642" xmlns:xlink="http://www.w3.org/1999/xlink" enable-background="new 0 0 15.642 15.642">
            <path fill-rule="evenodd" d="M8.882,7.821l6.541-6.541c0.293-0.293,0.293-0.768,0-1.061  c-0.293-0.293-0.768-0.293-1.061,0L7.821,6.76L1.28,0.22c-0.293-0.293-0.768-0.293-1.061,0c-0.293,0.293-0.293,0.768,0,1.061  l6.541,6.541L0.22,14.362c-0.293,0.293-0.293,0.768,0,1.061c0.147,0.146,0.338,0.22,0.53,0.22s0.384-0.073,0.53-0.22l6.541-6.541  l6.541,6.541c0.147,0.146,0.338,0.22,0.53,0.22c0.192,0,0.384-0.073,0.53-0.22c0.293-0.293,0.293-0.768,0-1.061L8.882,7.821z"></path>
          </svg>';
    echo '</div>';
    echo '</div>';
    echo '</div>';
    echo '</div>';
    echo '</div>';
  }
}
