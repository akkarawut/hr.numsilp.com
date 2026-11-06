<?php
class navTab
{
  public static function normal($data = [], $link = '', $class = '')
  {

    // Example list 
    if (!$data) {
      $data = [
        'param_name' => 'page',
        'nav_list' => [
          [
            'id' => '0',
            'name' => 'Mock_name',
            'count' => [
              'number' => 0,
              'class' => 'primary'
            ],
          ]
        ]
      ];
    }
    $param_name = $data['param_name'];
    if (!$link) {
      $link = $_SERVER['REQUEST_URI'];
      $link = explode("/", $link);
      $count = count($link);
      $link = $link[$count - 1];
      if (isset($_GET[$param_name])) {
        $link = str_replace('&' . $param_name . '=' . $_GET[$param_name], '', $link);
      }
    }

    ob_start(); ?>
    <div class="btn-tab-group <?= isset($class) && $class ? $class : '' ?>">
      <?php if (isset($data['nav_list']) && $data['nav_list']) { ?>
        <?php foreach ($data['nav_list'] as $nav) { ?>
          <?php
          $get_str = '?';
          if (strpos($link, '?') !== false) {
            $get_str = '&';
          }
          ?>
          <a href="<?= $link ?><?= $get_str . $param_name . '=' . $nav['id'] ?>" class="_btn-group <?= $_GET[$data['param_name']] == $nav['id'] ? 'active' : '' ?>">
            <div class="name"><?= $nav['name'] ?></div>
            <?php if (isset($nav['count']) && $nav['count'] &&  isset($nav['count']['number'])) { ?>
              <span class="count <?= $nav['count']['class'] ?  $nav['count']['class']  : 'primary' ?> ms-2"><?= $nav['count']['number'] ?></span>
            <?php } ?>
          </a>
        <?php } ?>
      <?php } ?>
    </div>
<?php
    return ob_get_clean();
  }
}
