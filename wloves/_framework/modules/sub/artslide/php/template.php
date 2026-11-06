<?php
class Artslide
{

  public static function normalSlide($files = '', $options = [])
  {
    ob_start();
    $is_mockup = false;
    if (isset($files) && ($files)) {
      $id = 'slide_' . $files;
      $is_navigation = isset($options['navigation']) ? $options['navigation'] : true;
      $is_pagination = isset($options['pagination']) ? $options['pagination'] : true;
      $prefix = isset($options['prefix']) ? $options['prefix'] : '';
    } else {
      $is_mockup = true;
      $is_navigation = true;
      $is_pagination = true;
      $id = 'mockup_slide';
    }

    $options_temp = [
      'items_page' => isset($options['items_page']) ? $options['items_page'] : 1,
      'navigation' => isset($options['navigation']) ? $options['navigation'] : true,
      'pagination' => isset($options['pagination']) ? $options['pagination'] : true,
      'space_gap' => isset($options['space_gap']) ? $options['space_gap'] : 30,
      'loop' => isset($options['loop']) ? $options['loop'] : true,
      'start_center' => isset($options['start_center']) ? $options['start_center'] : false,
      'bullet_dynamic' => isset($options['bullet_dynamic']) ? $options['bullet_dynamic'] : false,
      'prefix' => isset($options['prefix']) ? $options['prefix'] : '',
      'autoplay' => isset($options['autoplay']) ? $options['autoplay'] : 4000,
    ];

    if (isset($options['breakpoints'])) {
      $options_temp['breakpoints'] = $options['breakpoints'];
    }
    W_Main::console($options_temp);
    $options_encode = base64_encode(json_encode($options_temp));
?>
    <div class="swiper art-slide d-none" id="<?= $id ?>" options-encode="<?= $options_encode ?>">
      <div class="swiper" id="<?= $id ?>_target">
        <div class="swiper-wrapper">
          <?php if ($is_mockup) { ?>
            <?php foreach (range(1, 12) as $val) { ?>
              <div class="items itme-<?= $val ?>">Slide <?= $val ?></div>
            <?php } ?>
          <?php } else { ?>
            <?php include($prefix . 'ajax/slide_' . $files . '.php'); ?>
          <?php } ?>
        </div>
      </div>
      <?php if ($is_navigation) { ?>
        <div class="swiper-button-next next_<?= $id ?>"></div>
        <div class="swiper-button-prev prev_<?= $id ?>"></div>
      <?php } ?>
      <?php if ($is_pagination) { ?>
        <div class="swiper-pagination pagination_<?= $id ?>"></div>
      <?php } ?>
      <div class="swiper-preload" id="preload_<?= $id ?>">
        <div class="i-chart-loading lds-dual-ring"></div>
      </div>
    </div>

    <script>
      $(document).ready(function() {
        w.artslide.slideStart('<?= $id ?>')
      });
    </script>
<?php
    echo ob_get_clean();
  }
}
