<?php if (($_SERVER['REMOTE_ADDR'] == 'localhost' || $_SERVER['REMOTE_ADDR'] == '127.0.0.1' || $_SERVER['REMOTE_ADDR'] == '::1' || $_SERVER['REMOTE_ADDR'] == '172.17.0.1')) { ?>
  <div id="debug-code-modal">
    <div class="debug-code-header">
      <h5 class="debug-code-title">DEBUG MODE</h5>
      <button type="button" class="debug-code-close">
        <?= file_get_contents(F_ROOT_PHP . '/_framework/modules/main/detiw/icon/modal-close.svg') ?>
      </button>
    </div>
    <div class="debug-code-sub-header">
      <div class="expand">ขยาย</div>
      <div class="unexpand">ย่อ</div>
    </div>
    <div class="debug-code-body">
      <?php
      $unset_list = [];
      foreach (W_Main::$debug['data'] as $key => $debug_list) {
        if (!in_array($debug_list['call']['class'] . '::' . $debug_list['call']['function'], $unset_list)) {
      ?>
          <div class="code-group" data-id="<?= $key ?>">
            <span class="code-icon"><?= file_get_contents(F_ROOT_PHP . '/_framework/modules/main/detiw/icon/breadcrump-icon.svg') ?></span>
            <span class="code-class"><?= $debug_list['call']['class'] ?></span><span class="code-connect">::</span><span class="code-function"><?= $debug_list['call']['function'] ?></span>
            <span class="code-bracket">(</span>
            <span class="params-group">
              <?php
              $html = '';
              foreach ($debug_list['call']['params'] as $params) {
                $html .= '<span class="code-params">';
                if (is_array($params) && $params) {
                  $dot = '';
                  $html .= '[ ';
                  foreach ($params as $key_param => $param_name) {
                    $html .= $dot . '<span class="debug-code-param-key" title="' . $key_param . '">';
                    $html .= $param_name ? $param_name : "''";
                    $html .= '</span>';
                    $dot = ', ';
                  }
                  $html .= ' ]';
                } else {
                  $html .= $params ? $params : "''";
                }
                $html .= '</span>';
              }
              echo $html;
              ?>
            </span>
            <span class="code-bracket">)</span>
          </div>
          <div class="code-container code-response code-<?= $key ?>">
            <pre><code data-language="php"><?php print_r($debug_list['response'] ? $debug_list['response'] : 'NO RESPONSE!!!'); ?></code></pre>
          </div>
      <?php
        }
      }
      ?>
    </div>
  </div>
<?php } ?>