<?php
class ExtraInput
{
  public static function title($data)
  {
    ob_start(); ?>
    <?php
    $size_box = $data['size_display_text'] != 100 ? 'w-50' : 'w-100';
    $custom_color_text = isset($data['custom_color_text']) ? $data['custom_color_text'] : '';
    $alignment = '';
    $text_align = '';
    $is_bold = '';
    $is_italic = '';
    if ($data['alignment_display'] == 'left') {
      $alignment = 'me-auto';
    } else if ($data['alignment_display'] == 'center') {
      $alignment = 'mx-auto';
    } else if ($data['alignment_display'] == 'right') {
      $alignment = 'ms-auto';
    }

    if ($data['align_text'] == 'left') {
      $text_align = 'text-start';
    } else if ($data['align_text'] == 'center') {
      $text_align = 'text-center';
    } else if ($data['align_text'] == 'right') {
      $text_align = 'text-end';
    }

    if (isset($data['font_bold'])) {
      $is_bold = 'fw-bold';
    }
    if (isset($data['font_italic'])) {
      $is_italic = 'fst-italic';
    }
    ?>
    <div class="w-item <?= $size_box . ' ' . $alignment ?>">
      <div style="color:<?= $custom_color_text ?>;" class="font-<?= $data['font_size'] ?>px <?= $text_align . ' ' . $is_bold . ' ' . $is_italic ?> "><?= $data['title_text'] ?></div>
    </div>

  <?php
    return ob_get_clean();
  }

  public static function image($data, $file_path, $key = [])
  {
    ob_start(); ?>
    <?php
    $size_box = $data['option_file']['size_display_image'] != 100 ? 'w-50' : 'w-100';
    $alignment = '';
    if ($data['option_file']['alignment_img'] == 'left') {
      $alignment = 'text-start';
    } else if ($data['option_file']['alignment_img'] == 'center') {
      $alignment = 'text-center';
    } else if ($data['option_file']['alignment_img'] == 'right') {
      $alignment = 'text-end';
    }

    $size_image = 'width:' . $data['option_file']['img_width'] . 'px;height:' . $data['option_file']['img_height'] . 'px;';
    ?>
    <div class="<?= $size_box . ' ' . $alignment ?> mb-25px">
      <img src="<?= $file_path['list']['file_path'][$key] ?>" alt="" class="mw-100" style="<?= $size_image ?>">
    </div>

  <?php
    return ob_get_clean();
  }

  public static function line($data, $key = [])
  {
    ob_start(); ?>
    <?php
    $size_box = $data['size_display_line'] != 100 ? 'w-50' : 'w-100';
    $data_alignment_line = isset($data['alignment_line']) ? $data['alignment_line'] : '';
    $alignment_line = '';
    if ($data_alignment_line == 'left') {
      $alignment_line = 'me-auto';
    } else if ($data_alignment_line == 'center') {
      $alignment_line = 'mx-auto';
    } else if ($data_alignment_line == 'right') {
      $alignment_line = 'ms-auto';
    }

    $color_line = '';
    if (isset($data['color_line'])) {
      $color_line = $data['color_line'];
    } else {
      $color_line = isset($data['custom_color_line']) ? $data['custom_color_line'] : '';
    }
    ?>

    <div class="w-item <?= $size_box . ' ' . $alignment_line ?>">
      <div class="mx--20px" style="background-color:<?= $color_line ?>;height:<?= $data['size_line'] ?>px;"></div>
    </div>
  <?php return ob_get_clean();
  }

  public static function numberic($id, $code, $val, $data, $page_spacing, $key = [], $is_edit, $mode)
  {
    ob_start(); ?>
    <?php
    $temp_name = $code .  $data['table_name'];
    if (strpos($data['table_name'], 'sys_') !== false) {
      $temp_name = $code . '_' . $data['table_name'];
    }

    $color = isset($data['color']) ? $data['color'] : '';
    $color_inner = isset($data['color_inner']) ? $data['color_inner'] : '';
    $custom_color = isset($data['custom_color']) ? $data['custom_color'] : '';
    $custom_color_placeholder = isset($data['custom_color_placeholder']) ? $data['custom_color_placeholder'] : '';
    $alignment = isset($data['alignment']) ? $data['alignment'] : '';
    $is_bold = isset($data['font_bold']) ? $data['font_bold'] : '';
    $size_input = isset($data['size_input']) ? $data['size_input'] : '';
    $size_box = isset($data['size_display']) ? $data['size_display'] : '';
    $min = isset($data['min']) ? $data['min'] : '';
    $max = isset($data['max']) ? $data['max'] : '';
    $is_show_title = isset($data['is_show_title']) && $data['is_show_title'] ? 1 : 0;
    $is_required_field = '';
    if ($is_show_title == 1) {
      $is_required_field = isset($data['is_required_fill']) && $data['is_required_fill'] ? 'required' : '';
    }

    if ($is_bold == 1) {
      $is_bold = 'fw-bold';
    }
    if ($size_box != 100) {
      $size_box = 'w-50';
    } else {
      $size_box = 'w-100';
    }
    if ($alignment == 'left') {
      $alignment = 'me-auto';
    } else if ($alignment == 'center') {
      $alignment = 'mx-auto';
    } else if ($alignment == 'right') {
      $alignment = 'ms-auto';
    }
    if ($color == 'var(--v2-color-info)') {
      $color = 'var(--v2-color-info)';
    } else if ($color == 'var(--v2-color-secondary)') {
      $color = 'var(--v2-color-secondary)';
    } else if ($color == 'var(--v2-color-primary)') {
      $color = 'var(--v2-color-primary)';
    } else {
      $color = $custom_color;
    }
    if ($color_inner  == 'var(--v2-color-info)') {
      $color_inner = 'var(--v2-color-info)';
    } else if ($color_inner  == 'var(--v2-color-secondary)') {
      $color_inner = 'var(--v2-color-secondary)';
    } else if ($color_inner  == 'var(--v2-color-primary)') {
      $color_inner = 'var(--v2-color-primary)';
    } else {
      $color_inner = $custom_color_placeholder;
    }
    $data['category'] = isset($data['category']) ? $data['category'] : '';
    $not_varchar = ['Tag', 'Dropdown', 'Auto address', 'Google map link', 'Latitude_Longtitude', 'Link', 'Youtube_embed'];
    ?>

    <div class="w-item fill-list <?= $size_box . ' ' . $alignment ?> <?= $data['type_display'] != 1 ? 'stack-input' : '' ?>">
      <?php if ($is_show_title == 1) { ?>
        <div style="min-width:<?= $page_spacing  ?>px;max-width:<?= $page_spacing  ?>px;">
          <div class="font-<?= $data['font_size_title'] ?>px <?= $color ?> <?= $is_bold ?> <?= $is_edit == true ? $is_required_field : '' ?>">
            <?= $data['input_title'] ?>
          </div>
        </div>
      <?php } ?>
      <?php if ($is_edit == true && $data['is_edit_fill'] == 1) { ?>
        <div class="<?= $size_input ?>">
          <?= TiwForm::normal('number', $val, ['name' => 'template[' . $temp_name . '][' . $data['db_column_name'] . ']', 'min' => $min, 'max' => $max,  $is_required_field => ''], []); ?>
        </div>
      <?php } else { ?>
        <div class="font-<?= $data['input_inner_size'] ?>px" style="color:<?= $color_inner ?>;">
          <?php if (isset($data['decimal']) && $val != '') { ?>
            <?= number_format($val, $data['decimal'])  ?>
          <?php } else { ?>
            <?php if ($val != '') { ?>
              <?= number_format($val, 0) ?>
            <?php } ?>
          <?php } ?>
        </div>
      <?php } ?>
    </div>
  <?php return ob_get_clean();
  }

  public static function textbox($id, $code, $val, $data, $page_spacing, $key = [], $is_edit, $mode)
  {
    ob_start(); ?>
    <?php
    $temp_name = $code .  $data['table_name'];
    if (strpos($data['table_name'], 'sys_') !== false) {
      $temp_name = $code . '_' . $data['table_name'];
    }
    $color = isset($data['color']) ? $data['color'] : '';
    $color_inner = isset($data['color_inner']) ? $data['color_inner'] : '';
    $custom_color = isset($data['custom_color']) ? $data['custom_color'] : '';
    $custom_color_placeholder = isset($data['custom_color_placeholder']) ? $data['custom_color_placeholder'] : '';
    $alignment = isset($data['alignment']) ? $data['alignment'] : '';
    $is_bold = isset($data['font_bold']) ? $data['font_bold'] : '';
    $size_input = isset($data['size_input']) ? $data['size_input'] : '';
    $size_box = isset($data['size_display']) ? $data['size_display'] : '';
    $min = isset($data['min']) ? $data['min'] : '';
    $max = isset($data['max']) ? $data['max'] : '';
    $is_show_title = isset($data['is_show_title']) && $data['is_show_title'] ? 1 : 0;
    $is_required_field = '';
    if ($is_show_title == 1) {
      $is_required_field = isset($data['is_required_fill']) && $data['is_required_fill'] ? 'required' : '';
    }

    if ($is_bold == 1) {
      $is_bold = 'fw-bold';
    }
    if ($size_box != 100) {
      $size_box = 'w-50';
    } else {
      $size_box = 'w-100';
    }
    if ($alignment == 'left') {
      $alignment = 'me-auto';
    } else if ($alignment == 'center') {
      $alignment = 'mx-auto';
    } else if ($alignment == 'right') {
      $alignment = 'ms-auto';
    }
    if ($color == 'var(--v2-color-info)') {
      $color = 'var(--v2-color-info)';
    } else if ($color == 'var(--v2-color-secondary)') {
      $color = 'var(--v2-color-secondary)';
    } else if ($color == 'var(--v2-color-primary)') {
      $color = 'var(--v2-color-primary)';
    } else {
      $color = $custom_color;
    }
    if ($color_inner  == 'var(--v2-color-info)') {
      $color_inner = 'var(--v2-color-info)';
    } else if ($color_inner  == 'var(--v2-color-secondary)') {
      $color_inner = 'var(--v2-color-secondary)';
    } else if ($color_inner  == 'var(--v2-color-primary)') {
      $color_inner = 'var(--v2-color-primary)';
    } else {
      $color_inner = $custom_color_placeholder;
    }
    $size_input = isset($data['size_input']) != 100 ? 'w-50' : 'w-100';
    $size_box = isset($data['size_display']) != 100 ? 'w-50' : 'w-100';
    $placeholder_text = isset($data['placeholder_text']) ? $data['placeholder_text'] : '';
    ?>
    <div class="w-item fill-list <?= $size_box . ' ' . $alignment ?> <?= $data['type_display'] != 1 ? 'stack-input' : '' ?>">
      <?php if ($is_show_title == 1) { ?>
        <div style="min-width:<?= $page_spacing  ?>px;max-width:<?= $page_spacing  ?>px;">
          <div class="font-<?= $data['font_size_title'] ?>px <?= $is_bold ?> <?= $is_edit == true ? $is_required_field : '' ?>" style="color:<?= $color ?>;">
            <?= $data['input_title'] ?>
          </div>
        </div>
      <?php } ?>
      <?php if ($is_edit == true && $data['is_edit_fill'] == 1) { ?>
        <div class="<?= $size_input ?>">
          <?php
          if (isset($data['type_input_text']) && $data['type_input_text'] == 'text') {
            TiwForm::normal('text', $val, ['name' => 'template[' . $temp_name . '][' . $data['db_column_name'] . ']', 'placeholder' => $placeholder_text,  $is_required_field => ''], []);
          } else if (isset($data['type_input_text']) && $data['type_input_text'] == 'textarea') {
            TiwForm::normal('textarea', $val, ['name' => 'template[' . $temp_name . '][' . $data['db_column_name'] . ']', 'class' => 'min-h-200px', 'placeholder' => $placeholder_text,  $is_required_field => ''], []);
          } else if (isset($data['type_input_text']) && $data['type_input_text'] == 'text_editor') { ?>
            <textarea id="summernote_<?= $key ?>" name="<?= 'template[' . $temp_name . '][' . $data['db_column_name'] . ']' ?>">
                <?= $val ?>
            </textarea>
            <script>
              $(document).ready(function() {
                $('#summernote_<?= $key ?>').summernote({
                  toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'underline', 'clear', 'fontname']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['table', ['table']],
                    ['view', ['fullscreen', 'codeview']]
                  ]
                });
              });
            </script>
          <?php } else {
            TiwForm::normal('text', $val, ['name' => 'template[' . $temp_name . '][' . $data['db_column_name'] . ']', 'placeholder' => $placeholder_text,  $is_required_field => ''], []);
          }
          ?>
        </div>
      <?php } else { ?>
        <div class="font-<?= $data['input_inner_size'] ?>px" style="color:<?= $color_inner ?>;">
          <?= $val  ?>
        </div>
      <?php } ?>
    </div>


  <?php return ob_get_clean();
  }

  public static function auto_address($id, $code, $val, $data, $page_spacing, $key = [], $is_edit, $mode)
  {
    ob_start(); ?>
    <?php
    $temp_name = $code .  $data['table_name'];
    if (strpos($data['table_name'], 'sys_') !== false) {
      $temp_name = $code . '_' . $data['table_name'];
    }
    $color = isset($data['color']) ? $data['color'] : '';
    $color_inner = isset($data['color_inner']) ? $data['color_inner'] : '';
    $custom_color = isset($data['custom_color']) ? $data['custom_color'] : '';
    $custom_color_placeholder = isset($data['custom_color_placeholder']) ? $data['custom_color_placeholder'] : '';
    $alignment = isset($data['alignment']) ? $data['alignment'] : '';
    $is_bold = isset($data['font_bold']) ? $data['font_bold'] : '';
    $size_input = isset($data['size_input']) ? $data['size_input'] : '';
    $size_box = isset($data['size_display']) ? $data['size_display'] : '';
    $min = isset($data['min']) ? $data['min'] : '';
    $max = isset($data['max']) ? $data['max'] : '';
    $is_show_title = isset($data['is_show_title']) && $data['is_show_title'] ? 1 : 0;
    $is_required_field = '';
    if ($is_show_title == 1) {
      $is_required_field = isset($data['is_required_fill']) && $data['is_required_fill'] ? 'required' : '';
    }

    if ($is_bold == 1) {
      $is_bold = 'fw-bold';
    }
    if ($size_box != 100) {
      $size_box = 'w-50';
    } else {
      $size_box = 'w-100';
    }
    if ($alignment == 'left') {
      $alignment = 'me-auto';
    } else if ($alignment == 'center') {
      $alignment = 'mx-auto';
    } else if ($alignment == 'right') {
      $alignment = 'ms-auto';
    }
    if ($color == 'var(--v2-color-info)') {
      $color = 'var(--v2-color-info)';
    } else if ($color == 'var(--v2-color-secondary)') {
      $color = 'var(--v2-color-secondary)';
    } else if ($color == 'var(--v2-color-primary)') {
      $color = 'var(--v2-color-primary)';
    } else {
      $color = $custom_color;
    }
    if ($color_inner  == 'var(--v2-color-info)') {
      $color_inner = 'var(--v2-color-info)';
    } else if ($color_inner  == 'var(--v2-color-secondary)') {
      $color_inner = 'var(--v2-color-secondary)';
    } else if ($color_inner  == 'var(--v2-color-primary)') {
      $color_inner = 'var(--v2-color-primary)';
    } else {
      $color_inner = $custom_color_placeholder;
    }
    $size_input = isset($data['size_input']) != 100 ? 'w-50' : 'w-100';
    $size_box = isset($data['size_display']) != 100 ? 'w-50' : 'w-100';
    $address = isset($data['address']) && $data['address'] ? $data['address'] : '';
    $sub_district = isset($data['sub_district']) && $data['sub_district'] ? $data['sub_district'] : '';
    $district = isset($data['district']) && $data['district'] ? $data['district'] : '';
    $city = isset($data['city']) && $data['city'] ? $data['city'] : '';
    $zip = isset($data['zip']) && $data['zip'] ? $data['zip'] : '';
    $placeholder_text = 'ที่อยู่ ' . $address . ' ตำบล / เขต ' .  $sub_district .  ' อำเภอ / แขวง ' . $district . ' จังหวัด ' .  $sub_district . ' รหัสไปษณีย์ ' . $zip;

    ?>
    <div class="w-item fill-list <?= $size_box . ' ' . $alignment ?> <?= $data['type_display'] != 1 ? 'stack-input' : '' ?>">
      <?php if ($is_show_title == 1) { ?>
        <div style="min-width:<?= $page_spacing  ?>px;max-width:<?= $page_spacing  ?>px;">
          <div class="font-<?= $data['font_size_title'] ?>px <?= $is_bold ?> <?= $is_edit == true ? $is_required_field : '' ?>" style="color:<?= $color ?>;">
            <?= $data['input_title'] ?>
          </div>
        </div>
      <?php } ?>
      <?php if ($is_edit == true && $data['is_edit_fill'] == 1) { ?>
        <div class="<?= $size_input ?>">
          <?php
          if (isset($data['type_input_text']) && $data['type_input_text'] == 'text') {
            TiwForm::normal('text', $val, ['name' => 'template[' . $temp_name . '][' . $data['db_column_name'] . ']', 'placeholder' => $placeholder_text,  $is_required_field => ''], []);
          } else if (isset($data['type_input_text']) && $data['type_input_text'] == 'textarea') {
            TiwForm::normal('textarea', $val, ['name' => 'template[' . $temp_name . '][' . $data['db_column_name'] . ']', 'class' => 'min-h-200px', 'placeholder' => $placeholder_text,  $is_required_field => ''], []);
          } else {
            TiwForm::normal('text', $val, ['name' => 'template[' . $temp_name . '][' . $data['db_column_name'] . ']', 'placeholder' => $placeholder_text,  $is_required_field => ''], []);
          }
          ?>
        </div>
      <?php } else { ?>
        <div class="font-<?= $data['input_inner_size'] ?>px" style="color:<?= $color_inner ?>;">
          <?= $val  ?>
        </div>
      <?php } ?>
    </div>
  <?php return ob_get_clean();
  }

  public static function youtube_embed($id, $code, $val, $data, $page_spacing, $key = [], $is_edit, $mode)
  {
    ob_start(); ?>
    <?php
    $temp_name = $code .  $data['table_name'];
    if (strpos($data['table_name'], 'sys_') !== false) {
      $temp_name = $code . '_' . $data['table_name'];
    }
    $color = isset($data['color']) ? $data['color'] : '';
    $color_inner = isset($data['color_inner']) ? $data['color_inner'] : '';
    $custom_color = isset($data['custom_color']) ? $data['custom_color'] : '';
    $custom_color_placeholder = isset($data['custom_color_placeholder']) ? $data['custom_color_placeholder'] : '';
    $alignment = isset($data['alignment']) ? $data['alignment'] : '';
    $is_bold = isset($data['font_bold']) ? $data['font_bold'] : '';
    $size_input = isset($data['size_input']) ? $data['size_input'] : '';
    $size_box = isset($data['size_display']) ? $data['size_display'] : '';
    $min = isset($data['min']) ? $data['min'] : '';
    $max = isset($data['max']) ? $data['max'] : '';
    $is_show_title = isset($data['is_show_title']) && $data['is_show_title'] ? 1 : 0;
    $is_required_field = '';
    if ($is_show_title == 1) {
      $is_required_field = isset($data['is_required_fill']) && $data['is_required_fill'] ? 'required' : '';
    }

    if ($is_bold == 1) {
      $is_bold = 'fw-bold';
    }
    if ($size_box != 100) {
      $size_box = 'w-50';
    } else {
      $size_box = 'w-100';
    }
    if ($alignment == 'left') {
      $alignment = 'me-auto';
    } else if ($alignment == 'center') {
      $alignment = 'mx-auto';
    } else if ($alignment == 'right') {
      $alignment = 'ms-auto';
    }
    if ($color == 'var(--v2-color-info)') {
      $color = 'var(--v2-color-info)';
    } else if ($color == 'var(--v2-color-secondary)') {
      $color = 'var(--v2-color-secondary)';
    } else if ($color == 'var(--v2-color-primary)') {
      $color = 'var(--v2-color-primary)';
    } else {
      $color = $custom_color;
    }
    if ($color_inner  == 'var(--v2-color-info)') {
      $color_inner = 'var(--v2-color-info)';
    } else if ($color_inner  == 'var(--v2-color-secondary)') {
      $color_inner = 'var(--v2-color-secondary)';
    } else if ($color_inner  == 'var(--v2-color-primary)') {
      $color_inner = 'var(--v2-color-primary)';
    } else {
      $color_inner = $custom_color_placeholder;
    }
    $size_input = isset($data['size_input']) != 100 ? 'w-50' : 'w-100';
    $size_box = isset($data['size_display']) != 100 ? 'w-50' : 'w-100';
    $placeholder_text = isset($data['placeholder_text']) ? $data['placeholder_text'] : '';
    ?>
    <div class="w-item fill-list <?= $size_box . ' ' . $alignment ?> <?= $data['type_display'] != 1 ? 'stack-input' : '' ?>">
      <?php if ($is_show_title == 1) { ?>
        <div style="min-width:<?= $page_spacing  ?>px;max-width:<?= $page_spacing  ?>px;">
          <div class="font-<?= $data['font_size_title'] ?>px <?= $is_bold ?> <?= $is_edit == true ? $is_required_field : '' ?>" style="color:<?= $color ?>;">
            <?= $data['input_title'] ?>
          </div>
        </div>
      <?php } ?>
      <?php if ($is_edit == true && $data['is_edit_fill'] == 1) { ?>
        <div class="<?= $size_input ?>">
          <?php
          if (isset($data['type_input_text']) && $data['type_input_text'] == 'text') {
            TiwForm::normal('text', $val, ['name' => 'template[' . $temp_name . '][' . $data['db_column_name'] . ']', 'placeholder' => $placeholder_text,  $is_required_field => ''], []);
          } else if (isset($data['type_input_text']) && $data['type_input_text'] == 'textarea') {
            TiwForm::normal('textarea', $val, ['name' => 'template[' . $temp_name . '][' . $data['db_column_name'] . ']', 'class' => 'min-h-200px', 'placeholder' => $placeholder_text,  $is_required_field => ''], []);
          } else {
            TiwForm::normal('text', $val, ['name' => 'template[' . $temp_name . '][' . $data['db_column_name'] . ']', 'placeholder' => $placeholder_text,  $is_required_field => ''], []);
          }
          ?>
        </div>
      <?php } else { ?>
        <div class="font-<?= $data['input_inner_size'] ?>px" style="color:<?= $color_inner ?>;">
          <?php if ($data['video_type_preview'] == 1) { ?>
            <?php if ($data['is_click_video'] == 1) { ?>
              <a href="<?= $val ?>">
              <?php } ?>
              <?= $val ?>
              <?php if ($data['is_click_video'] == 1) { ?>
              </a>
            <?php } ?>
          <?php } else if ($data['video_type_preview'] == 2) { ?>
            <iframe width="560" height="315" src="<?= $val ?>" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen> </iframe>
          <?php } ?>
        </div>
      <?php } ?>
    </div>
  <?php return ob_get_clean();
  }

  public static function google_map_link($id, $code, $val, $data, $page_spacing, $key = [], $is_edit, $mode)
  {
    ob_start(); ?>
    <?php
    $temp_name = $code .  $data['table_name'];
    if (strpos($data['table_name'], 'sys_') !== false) {
      $temp_name = $code . '_' . $data['table_name'];
    }
    $color = isset($data['color']) ? $data['color'] : '';
    $color_inner = isset($data['color_inner']) ? $data['color_inner'] : '';
    $custom_color = isset($data['custom_color']) ? $data['custom_color'] : '';
    $custom_color_placeholder = isset($data['custom_color_placeholder']) ? $data['custom_color_placeholder'] : '';
    $alignment = isset($data['alignment']) ? $data['alignment'] : '';
    $is_bold = isset($data['font_bold']) ? $data['font_bold'] : '';
    $size_input = isset($data['size_input']) ? $data['size_input'] : '';
    $size_box = isset($data['size_display']) ? $data['size_display'] : '';
    $min = isset($data['min']) ? $data['min'] : '';
    $max = isset($data['max']) ? $data['max'] : '';
    $is_show_title = isset($data['is_show_title']) && $data['is_show_title'] ? 1 : 0;
    $is_required_field = '';
    if ($is_show_title == 1) {
      $is_required_field = isset($data['is_required_fill']) && $data['is_required_fill'] ? 'required' : '';
    }

    if ($is_bold == 1) {
      $is_bold = 'fw-bold';
    }
    if ($size_box != 100) {
      $size_box = 'w-50';
    } else {
      $size_box = 'w-100';
    }
    if ($alignment == 'left') {
      $alignment = 'me-auto';
    } else if ($alignment == 'center') {
      $alignment = 'mx-auto';
    } else if ($alignment == 'right') {
      $alignment = 'ms-auto';
    }
    if ($color == 'var(--v2-color-info)') {
      $color = 'var(--v2-color-info)';
    } else if ($color == 'var(--v2-color-secondary)') {
      $color = 'var(--v2-color-secondary)';
    } else if ($color == 'var(--v2-color-primary)') {
      $color = 'var(--v2-color-primary)';
    } else {
      $color = $custom_color;
    }
    if ($color_inner  == 'var(--v2-color-info)') {
      $color_inner = 'var(--v2-color-info)';
    } else if ($color_inner  == 'var(--v2-color-secondary)') {
      $color_inner = 'var(--v2-color-secondary)';
    } else if ($color_inner  == 'var(--v2-color-primary)') {
      $color_inner = 'var(--v2-color-primary)';
    } else {
      $color_inner = $custom_color_placeholder;
    }
    $size_input = isset($data['size_input']) != 100 ? 'w-50' : 'w-100';
    $size_box = isset($data['size_display']) != 100 ? 'w-50' : 'w-100';
    $placeholder_text = isset($data['placeholder_text']) ? $data['placeholder_text'] : '';
    $link = isset($data['link']) && $data['link'] ? $data['link'] : '';
    $advice = isset($data['advice']) && $data['advice'] ? $data['advice'] : '';
    $is_show_advice = isset($data['is_show_advice']) && $data['is_show_advice'] ? $data['is_show_advice'] : '';
    $is_copy_link = isset($data['is_copy_link']) && $data['is_copy_link'] ? $data['is_copy_link'] : '';
    ?>
    <div class="w-item fill-list <?= $size_box . ' ' . $alignment ?> <?= $data['type_display'] != 1 ? 'stack-input' : '' ?>">
      <?php if ($is_show_title == 1) { ?>
        <div style="min-width:<?= $page_spacing  ?>px;max-width:<?= $page_spacing  ?>px;">
          <div class="font-<?= $data['font_size_title'] ?>px <?= $is_bold ?> <?= $is_edit == true ? $is_required_field : '' ?>" style="color:<?= $color ?>;">
            <?= $data['input_title'] ?>
          </div>
        </div>
      <?php } ?>
      <?php if ($is_edit == true && $data['is_edit_fill'] == 1) { ?>
        <div class="<?= $size_input ?>">
          <?php
          if (isset($data['type_input_text']) && $data['type_input_text'] == 'text') {
            TiwForm::normal('text', $val, ['name' => 'template[' . $temp_name . '][' . $data['db_column_name'] . ']', 'placeholder' => $placeholder_text,  $is_required_field => ''], []);
          } else if (isset($data['type_input_text']) && $data['type_input_text'] == 'textarea') {
            TiwForm::normal('textarea', $val, ['name' => 'template[' . $temp_name . '][' . $data['db_column_name'] . ']', 'class' => 'min-h-200px', 'placeholder' => $placeholder_text,  $is_required_field => ''], []);
          } else {
            TiwForm::normal('text', $val, ['name' => 'template[' . $temp_name . '][' . $data['db_column_name'] . ']', 'placeholder' => $placeholder_text,  $is_required_field => ''], []);
          }
          ?>
          <?php if ($is_show_advice == 1) : ?>
            <div class="font-14px text-secondary">
              <?= $advice ?>
            </div>
          <?php endif; ?>
        </div>
      <?php } else { ?>
        <div class="font-<?= $data['input_inner_size'] ?>px" style="color:<?= $color_inner ?>;">
          <?php if ($data['is_click_link'] == 1) { ?>
            <a href="<?= $val ?>">
            <?php } ?>
            <span><?= $val ?></span>
            <?php if ($data['is_click_link'] == 1) { ?>
            </a>
          <?php } ?>
          <?php if ($is_copy_link == 1) { ?>
            <span class="code-container custom-copy ">
              <span class="d-none"><?= $val ?></span>
            </span>
          <?php } ?>
        </div>
      <?php } ?>
    </div>
  <?php return ob_get_clean();
  }

  public static function link($id, $code, $val, $data, $page_spacing, $key = [], $is_edit, $mode)
  {
    ob_start(); ?>
    <?php
    $temp_name = $code .  $data['table_name'];
    if (strpos($data['table_name'], 'sys_') !== false) {
      $temp_name = $code . '_' . $data['table_name'];
    }
    $color = isset($data['color']) ? $data['color'] : '';
    $color_inner = isset($data['color_inner']) ? $data['color_inner'] : '';
    $custom_color = isset($data['custom_color']) ? $data['custom_color'] : '';
    $custom_color_placeholder = isset($data['custom_color_placeholder']) ? $data['custom_color_placeholder'] : '';
    $alignment = isset($data['alignment']) ? $data['alignment'] : '';
    $is_bold = isset($data['font_bold']) ? $data['font_bold'] : '';
    $size_input = isset($data['size_input']) ? $data['size_input'] : '';
    $size_box = isset($data['size_display']) ? $data['size_display'] : '';
    $min = isset($data['min']) ? $data['min'] : '';
    $max = isset($data['max']) ? $data['max'] : '';
    $is_show_title = isset($data['is_show_title']) && $data['is_show_title'] ? 1 : 0;
    $is_required_field = '';
    if ($is_show_title == 1) {
      $is_required_field = isset($data['is_required_fill']) && $data['is_required_fill'] ? 'required' : '';
    }

    if ($is_bold == 1) {
      $is_bold = 'fw-bold';
    }
    if ($size_box != 100) {
      $size_box = 'w-50';
    } else {
      $size_box = 'w-100';
    }
    if ($alignment == 'left') {
      $alignment = 'me-auto';
    } else if ($alignment == 'center') {
      $alignment = 'mx-auto';
    } else if ($alignment == 'right') {
      $alignment = 'ms-auto';
    }
    if ($color == 'var(--v2-color-info)') {
      $color = 'var(--v2-color-info)';
    } else if ($color == 'var(--v2-color-secondary)') {
      $color = 'var(--v2-color-secondary)';
    } else if ($color == 'var(--v2-color-primary)') {
      $color = 'var(--v2-color-primary)';
    } else {
      $color = $custom_color;
    }
    if ($color_inner  == 'var(--v2-color-info)') {
      $color_inner = 'var(--v2-color-info)';
    } else if ($color_inner  == 'var(--v2-color-secondary)') {
      $color_inner = 'var(--v2-color-secondary)';
    } else if ($color_inner  == 'var(--v2-color-primary)') {
      $color_inner = 'var(--v2-color-primary)';
    } else {
      $color_inner = $custom_color_placeholder;
    }
    $size_input = isset($data['size_input']) != 100 ? 'w-50' : 'w-100';
    $size_box = isset($data['size_display']) != 100 ? 'w-50' : 'w-100';
    $placeholder_text = isset($data['placeholder_text']) ? $data['placeholder_text'] : '';
    $advice = isset($data['advice']) && $data['advice'] ? $data['advice'] : '';
    $is_show_advice = isset($data['is_show_advice']) && $data['is_show_advice'] ? $data['is_show_advice'] : '';
    $is_copy_link = isset($data['is_copy_link']) && $data['is_copy_link'] ? $data['is_copy_link'] : '';
    ?>
    <div class="w-item fill-list <?= $size_box . ' ' . $alignment ?> <?= $data['type_display'] != 1 ? 'stack-input' : '' ?>">
      <?php if ($is_show_title == 1) { ?>
        <div style="min-width:<?= $page_spacing  ?>px;max-width:<?= $page_spacing  ?>px;">
          <div class="font-<?= $data['font_size_title'] ?>px <?= $is_bold ?> <?= $is_edit == true ? $is_required_field : '' ?>" style="color:<?= $color ?>;">
            <?= $data['input_title'] ?>
          </div>
        </div>
      <?php } ?>
      <?php if ($is_edit == true && $data['is_edit_fill'] == 1) { ?>
        <div class="<?= $size_input ?>">
          <?php
          if (isset($data['type_input_text']) && $data['type_input_text'] == 'text') {
            TiwForm::normal('text', $val, ['name' => 'template[' . $temp_name . '][' . $data['db_column_name'] . ']', 'placeholder' => $placeholder_text,  $is_required_field => ''], []);
          } else if (isset($data['type_input_text']) && $data['type_input_text'] == 'textarea') {
            TiwForm::normal('textarea', $val, ['name' => 'template[' . $temp_name . '][' . $data['db_column_name'] . ']', 'class' => 'min-h-200px', 'placeholder' => $placeholder_text,  $is_required_field => ''], []);
          } else {
            TiwForm::normal('text', $val, ['name' => 'template[' . $temp_name . '][' . $data['db_column_name'] . ']', 'placeholder' => $placeholder_text,  $is_required_field => ''], []);
          }
          ?>
          <?php if ($is_show_advice == 1) : ?>
            <div class="font-14px text-secondary">
              <?= $advice ?>
            </div>
          <?php endif; ?>
        </div>
      <?php } else { ?>
        <div class="font-<?= $data['input_inner_size'] ?>px" style="color:<?= $color_inner ?>;">
          <?php if ($data['is_click_link'] == 1) { ?>
            <a href="<?= $val ?>">
            <?php } ?>
            <?= $val ?>
            <?php if ($data['is_click_link'] == 1) { ?>
            </a>
            <?php if ($is_copy_link == 1) { ?>
              <span class="code-container custom-copy ">
                <span class="d-none"><?= $val ?></span>
              </span>
            <?php } ?>
          <?php } ?>
        </div>
      <?php } ?>
    </div>
  <?php return ob_get_clean();
  }

  public static function latitude_longitude($id, $code, $val, $data, $page_spacing, $key = [], $is_edit, $mode)
  {
    ob_start(); ?>
    <?php
    $temp_name = $code .  $data['table_name'];
    if (strpos($data['table_name'], 'sys_') !== false) {
      $temp_name = $code . '_' . $data['table_name'];
    }
    $color = isset($data['color']) ? $data['color'] : '';
    $color_inner = isset($data['color_inner']) ? $data['color_inner'] : '';
    $custom_color = isset($data['custom_color']) ? $data['custom_color'] : '';
    $custom_color_placeholder = isset($data['custom_color_placeholder']) ? $data['custom_color_placeholder'] : '';
    $alignment = isset($data['alignment']) ? $data['alignment'] : '';
    $is_bold = isset($data['font_bold']) ? $data['font_bold'] : '';
    $size_input = isset($data['size_input']) ? $data['size_input'] : '';
    $size_box = isset($data['size_display']) ? $data['size_display'] : '';
    $min = isset($data['min']) ? $data['min'] : '';
    $max = isset($data['max']) ? $data['max'] : '';
    $is_show_title = isset($data['is_show_title']) && $data['is_show_title'] ? 1 : 0;
    $is_required_field = '';
    if ($is_show_title == 1) {
      $is_required_field = isset($data['is_required_fill']) && $data['is_required_fill'] ? 'required' : '';
    }

    if ($is_bold == 1) {
      $is_bold = 'fw-bold';
    }
    if ($size_box != 100) {
      $size_box = 'w-50';
    } else {
      $size_box = 'w-100';
    }
    if ($alignment == 'left') {
      $alignment = 'me-auto';
    } else if ($alignment == 'center') {
      $alignment = 'mx-auto';
    } else if ($alignment == 'right') {
      $alignment = 'ms-auto';
    }
    if ($color == 'var(--v2-color-info)') {
      $color = 'var(--v2-color-info)';
    } else if ($color == 'var(--v2-color-secondary)') {
      $color = 'var(--v2-color-secondary)';
    } else if ($color == 'var(--v2-color-primary)') {
      $color = 'var(--v2-color-primary)';
    } else {
      $color = $custom_color;
    }
    if ($color_inner  == 'var(--v2-color-info)') {
      $color_inner = 'var(--v2-color-info)';
    } else if ($color_inner  == 'var(--v2-color-secondary)') {
      $color_inner = 'var(--v2-color-secondary)';
    } else if ($color_inner  == 'var(--v2-color-primary)') {
      $color_inner = 'var(--v2-color-primary)';
    } else {
      $color_inner = $custom_color_placeholder;
    }
    $size_input = isset($data['size_input']) != 100 ? 'w-50' : 'w-100';
    $size_box = isset($data['size_display']) != 100 ? 'w-50' : 'w-100';
    $latitude = isset($data['latitude']) && $data['latitude'] ? $data['latitude'] : '';
    $longitude = isset($data['longitude']) && $data['longitude'] ? $data['longitude'] : '';
    $advice = isset($data['advice']) && $data['advice'] ? $data['advice'] : '';
    $is_show_advice = isset($data['is_show_advice']) && $data['is_show_advice'] ? $data['is_show_advice'] : '';
    $is_copy_link = isset($data['is_copy_link']) && $data['is_copy_link'] ? $data['is_copy_link'] : '';
    $placeholder_text  = 'ละติจูด ' . $latitude . ' ลองจิจูด ' . $longitude;
    ?>
    <div class="w-item fill-list <?= $size_box . ' ' . $alignment ?> <?= $data['type_display'] != 1 ? 'stack-input' : '' ?>">
      <?php if ($is_show_title == 1) { ?>
        <div style="min-width:<?= $page_spacing  ?>px;max-width:<?= $page_spacing  ?>px;">
          <div class="font-<?= $data['font_size_title'] ?>px <?= $is_bold ?> <?= $is_edit == true ? $is_required_field : '' ?>" style="color:<?= $color ?>;">
            <?= $data['input_title'] ?>
          </div>
        </div>
      <?php } ?>
      <?php if ($is_edit == true && $data['is_edit_fill'] == 1) { ?>
        <div class="<?= $size_input ?>">
          <?php
          if (isset($data['type_input_text']) && $data['type_input_text'] == 'text') {
            TiwForm::normal('text', $val, ['name' => 'template[' . $temp_name . '][' . $data['db_column_name'] . ']', 'placeholder' => $placeholder_text,  $is_required_field => ''], []);
          } else if (isset($data['type_input_text']) && $data['type_input_text'] == 'textarea') {
            TiwForm::normal('textarea', $val, ['name' => 'template[' . $temp_name . '][' . $data['db_column_name'] . ']', 'class' => 'min-h-200px', 'placeholder' => $placeholder_text,  $is_required_field => ''], []);
          } else {
            TiwForm::normal('text', $val, ['name' => 'template[' . $temp_name . '][' . $data['db_column_name'] . ']', 'placeholder' => $placeholder_text,  $is_required_field => ''], []);
          }
          ?>
          <?php if ($is_show_advice == 1) : ?>
            <div class="font-14px text-secondary">
              <?= $advice ?>
            </div>
          <?php endif; ?>
        </div>
      <?php } else { ?>
        <div class="font-<?= $data['input_inner_size'] ?>px" style="color:<?= $color_inner ?>;">
          <?= $val  ?>
        </div>
      <?php } ?>
    </div>
  <?php return ob_get_clean();
  }

  public static function date_time($id, $code, $val, $data, $page_spacing, $key = [], $is_edit, $mode)
  {
    ob_start(); ?>
    <?php
    $temp_name = $code .  $data['table_name'];
    if (strpos($data['table_name'], 'sys_') !== false) {
      $temp_name = $code . '_' . $data['table_name'];
    }
    $color = isset($data['color']) ? $data['color'] : '';
    $color_inner = isset($data['color_inner']) ? $data['color_inner'] : '';
    $custom_color = isset($data['custom_color']) ? $data['custom_color'] : '';
    $custom_color_placeholder = isset($data['custom_color_placeholder']) ? $data['custom_color_placeholder'] : '';
    $alignment = isset($data['alignment']) ? $data['alignment'] : '';
    $is_bold = isset($data['font_bold']) ? $data['font_bold'] : '';
    $size_input = isset($data['size_input']) ? $data['size_input'] : '';
    $size_box = isset($data['size_display']) ? $data['size_display'] : '';
    $min = isset($data['min']) ? $data['min'] : '';
    $max = isset($data['max']) ? $data['max'] : '';
    $is_show_title = isset($data['is_show_title']) && $data['is_show_title'] ? 1 : 0;
    $is_required_field = '';
    if ($is_show_title == 1) {
      $is_required_field = isset($data['is_required_fill']) && $data['is_required_fill'] ? 'required' : '';
    }

    if ($is_bold == 1) {
      $is_bold = 'fw-bold';
    }
    if ($size_box != 100) {
      $size_box = 'w-50';
    } else {
      $size_box = 'w-100';
    }
    if ($alignment == 'left') {
      $alignment = 'me-auto';
    } else if ($alignment == 'center') {
      $alignment = 'mx-auto';
    } else if ($alignment == 'right') {
      $alignment = 'ms-auto';
    }
    if ($color == 'var(--v2-color-info)') {
      $color = 'var(--v2-color-info)';
    } else if ($color == 'var(--v2-color-secondary)') {
      $color = 'var(--v2-color-secondary)';
    } else if ($color == 'var(--v2-color-primary)') {
      $color = 'var(--v2-color-primary)';
    } else {
      $color = $custom_color;
    }
    if ($color_inner  == 'var(--v2-color-info)') {
      $color_inner = 'var(--v2-color-info)';
    } else if ($color_inner  == 'var(--v2-color-secondary)') {
      $color_inner = 'var(--v2-color-secondary)';
    } else if ($color_inner  == 'var(--v2-color-primary)') {
      $color_inner = 'var(--v2-color-primary)';
    } else {
      $color_inner = $custom_color_placeholder;
    }
    $size_input = isset($data['size_input']) != 100 ? 'w-50' : 'w-100';
    $size_box = isset($data['size_display']) != 100 ? 'w-50' : 'w-100';
    $placeholder_text = isset($data['placeholder_text']) ? $data['placeholder_text'] : '';
    $advice = isset($data['advice']) && $data['advice'] ? $data['advice'] : '';
    $is_show_advice = isset($data['is_show_advice']) && $data['is_show_advice'] ? $data['is_show_advice'] : '';
    $is_copy_link = isset($data['is_copy_link']) && $data['is_copy_link'] ? $data['is_copy_link'] : '';

    $size_input_date = isset($data['size_input_date']) ? $data['size_input_date'] : '';
    $type_format_datetime = isset($data['type_format_datetime']) ? $data['type_format_datetime'] : '';
    $type_format_date = isset($data['type_format_date']) ? $data['type_format_date'] : '';
    $placeholder_text_date = isset($data['placeholder_text_date']) ? $data['placeholder_text_date'] : '';
    $placeholder_text_time = isset($data['placeholder_text_time']) ? $data['placeholder_text_time'] : '';
    $input_inner_size_date = isset($data['input_inner_size_date']) ? $data['input_inner_size_date'] : '';
    $color_date = isset($data['color_date']) ? $data['color_date'] : '';
    $custom_color_date = isset($data['custom_color_date']) ? $data['custom_color_date'] : '';
    $type_format_init_date = isset($data['type_format_init_date']) ? $data['type_format_init_date'] : '';
    $size_input_time = isset($data['size_input_time']) ? $data['size_input_time'] : '';
    $type_format_time = isset($data['type_format_time']) ? $data['type_format_time'] : '';
    $color_time = isset($data['color_time']) ? $data['color_time'] : '';
    $custom_color_time = isset($data['custom_color_time']) ? $data['custom_color_time'] : '';
    $type_format_init_time = isset($data['type_format_init_time']) ? $data['type_format_init_time'] : '';
    $input_inner_size_time = isset($data['input_inner_size_time']) ? $data['input_inner_size_time'] : '';
    $format = '';
    $current = '';
    if ($data['type'] == 'DateTime' || $data['type'] == 'Datetime') {
      $format = $type_format_datetime;
      $current = $is_edit == true ? date($type_format_datetime) : '-';
      // $current = $val != '' ? W_Main::formatDate($val, $type_format_datetime) : ($is_edit == true ? date($type_format_datetime) : '-');
    } else if ($data['type'] == 'Date') {
      $format = $type_format_date;
      // $current = $val != '' ? W_Main::formatDate($val, $type_format_date) : ($is_edit == true ? date($type_format_date) : '-');
      $current = $is_edit == true ? date($type_format_date) : '-';
    } else if ($data['type'] == 'Time') {
      $format = $type_format_time;
      // $current = $val != '' ? W_Main::formatDate($val, $type_format_time) : ($is_edit == true ? date($type_format_time) : '-');
      $current = $is_edit == true ? date($type_format_time) : '-';
    }

    if ($color_date == 'var(--v2-color-info)') {
      $color_date = 'var(--v2-color-info)';
    } else if ($color_date == 'var(--v2-color-secondary)') {
      $color_date = 'var(--v2-color-secondary)';
    } else if ($color_date == 'var(--v2-color-primary)') {
      $color_date = 'var(--v2-color-primary)';
    } else {
      $color_date = $custom_color_date;
    }

    if ($color_time  == 'var(--v2-color-info)') {
      $color_time = 'var(--v2-color-info)';
    } else if ($color_time  == 'var(--v2-color-secondary)') {
      $color_time = 'var(--v2-color-secondary)';
    } else if ($color_time  == 'var(--v2-color-primary)') {
      $color_time = 'var(--v2-color-primary)';
    } else {
      $color_time = $custom_color_time;
    }

    ?>
    <div class="w-item fill-list <?= $size_box . ' ' . $alignment ?> <?= $data['type_display'] != 1 ? 'stack-input' : '' ?>">
      <?php if ($is_show_title == 1) { ?>
        <div style="min-width:<?= $page_spacing  ?>px;max-width:<?= $page_spacing  ?>px;">
          <div class="font-<?= $data['font_size_title'] ?>px  <?= $is_bold ?> <?= $is_edit == true ? $is_required_field : '' ?>" style="color:<?= $color ?> ;">
            <?= $data['input_title'] ?>
          </div>
        </div>
      <?php } ?>
      <?php if ($is_edit == true && $data['is_edit_fill'] == 1) {
      ?>
        <?php if ($data['type'] == 'DateTime' || $data['type'] == 'Datetime') : ?>
          <div class="d-flex align-items-center">
            <div style="width:<?= $size_input_date ?>px;">
              <?= TiwForm::normal('datetime', $val, ['name' => 'template[' . $temp_name . '][' . $data['db_column_name'] . ']', 'placeholder' => $placeholder_text_date,  $is_required_field => ''], []); ?>
            </div>
          </div>
        <?php elseif ($data['type'] == 'Time') : ?>
          <div style="width:<?= $size_input_time ?>px;">
            <?= TiwForm::normal('time', $val, ['name' => 'template[' . $temp_name . '][' . $data['db_column_name'] . ']', 'placeholder' => $placeholder_text_time,  $is_required_field => ''], []); ?>
          </div>
        <?php elseif ($data['type'] == 'Date') : ?>
          <div style="width:<?= $size_input_date ?>px;">
            <?= TiwForm::normal('date', $val, ['name' => 'template[' . $temp_name . '][' . $data['db_column_name'] . ']', 'placeholder' => $placeholder_text_date,  $is_required_field => ''], []); ?>
          </div>
        <?php endif; ?>
      <?php } else { ?>

        <?php if ($data['type'] == 'DateTime' || $data['type'] == 'Datetime') : ?>
          <div class="font-<?= $data['input_inner_size'] ?>px" style="color:<?= $color_inner ?>;">
            <?php
            $format = $type_format_datetime;
            echo $val != '' ? W_Main::formatDate($val, $type_format_datetime) : ($is_edit == true ? date($type_format_datetime) : '-');
            ?>
          </div>
        <?php elseif ($data['type'] == 'Time') : ?>
          <div class="font-<?= $data['input_inner_size_time'] ?>px" style="color:<?= $color_inner ?>;">
            <?php

            $format = $type_format_time;
            echo  $val != '' ? W_Main::formatDate($val, $type_format_time) : ($is_edit == true ? date($type_format_time) : '-');
            ?>
          </div>
        <?php elseif ($data['type'] == 'Date') : ?>
          <div class="font-<?= $data['input_inner_size_date'] ?>px" style="color:<?= $color_inner ?>;">
            <?php
            $format = $type_format_date;
            echo $val != '' ? W_Main::formatDate($val, 'd/m/Y') : ($is_edit == true ? date($type_format_date) : '-');
            ?>
          </div>
        <?php endif; ?>
      <?php } ?>
    </div>
  <?php return ob_get_clean();
  }

  public static function upload($id, $code, $val, $data, $extra_record, $page_spacing, $key = [], $is_edit, $mode)
  {
    ob_start(); ?>
    <?php
    $temp_name = $code .  $data['table_name'];
    if (strpos($data['table_name'], 'sys_') !== false) {
      $temp_name = $code . '_' . $data['table_name'];
    }
    $color = isset($data['color']) ? $data['color'] : '';
    $color_inner = isset($data['color_inner']) ? $data['color_inner'] : '';
    $custom_color = isset($data['custom_color']) ? $data['custom_color'] : '';
    $custom_color_placeholder = isset($data['custom_color_placeholder']) ? $data['custom_color_placeholder'] : '';
    $alignment = isset($data['alignment']) ? $data['alignment'] : '';
    $is_bold = isset($data['font_bold']) ? $data['font_bold'] : '';
    $size_input = isset($data['size_input']) ? $data['size_input'] : '';
    $size_box = isset($data['size_display']) ? $data['size_display'] : '';
    $min = isset($data['min']) ? $data['min'] : '';
    $max = isset($data['max']) ? $data['max'] : '';
    $is_show_title = isset($data['is_show_title']) && $data['is_show_title'] ? 1 : 0;
    $is_required_field = '';
    if ($is_show_title == 1) {
      $is_required_field = isset($data['is_required_fill']) && $data['is_required_fill'] ? 'required' : '';
    }

    if ($is_bold == 1) {
      $is_bold = 'fw-bold';
    }
    if ($size_box != 100) {
      $size_box = 'w-50';
    } else {
      $size_box = 'w-100';
    }
    if ($alignment == 'left') {
      $alignment = 'me-auto';
    } else if ($alignment == 'center') {
      $alignment = 'mx-auto';
    } else if ($alignment == 'right') {
      $alignment = 'ms-auto';
    }
    if ($color == 'var(--v2-color-info)') {
      $color = 'var(--v2-color-info)';
    } else if ($color == 'var(--v2-color-secondary)') {
      $color = 'var(--v2-color-secondary)';
    } else if ($color == 'var(--v2-color-primary)') {
      $color = 'var(--v2-color-primary)';
    } else {
      $color = $custom_color;
    }
    if ($color_inner  == 'var(--v2-color-info)') {
      $color_inner = 'var(--v2-color-info)';
    } else if ($color_inner  == 'var(--v2-color-secondary)') {
      $color_inner = 'var(--v2-color-secondary)';
    } else if ($color_inner  == 'var(--v2-color-primary)') {
      $color_inner = 'var(--v2-color-primary)';
    } else {
      $color_inner = $custom_color_placeholder;
    }
    $size_input = isset($data['size_input']) != 100 ? 'w-50' : 'w-100';
    $size_box = isset($data['size_display']) != 100 ? 'w-50' : 'w-100';

    $is_show_advice = isset($data['is_show_advice']) ? $data['is_show_advice'] : 0;
    $advice = isset($data['advice']) ? $data['advice'] : '';
    $array_checkbox = [
      'type_select_all',
      'type_img_all',
      'pdf',
      'png',
      'jpeg',
      'jpg',
      'type_video_all',
      'mp3',
      'mp4',
      'wav',
      'type_file_all',
      'svg',
      'json',
      'csv',
      'xlsx',
      'xlm',
      'rar',
      'zip',
      'db',
      'txt',
      'text',
      'doc',
      'xls',
      'ptt',
      'prg',
    ];
    $temp_file_type = [];
    foreach ($array_checkbox as $typefile) {
      ${$typefile} = (isset($data[$typefile])) ? $data[$typefile] : '0';
      $temp_file_type[$typefile] = ${$typefile};
    }
    $n_type_select_all = $type_select_all == 1 ? '*, ' : '';
    $n_type_img_all = $type_img_all == 1 ? 'image/*, ' : '';
    $n_type_video_all = $type_video_all == 1 ? 'video/*, ' : '';
    $n_type_file_all = $type_file_all == 1 ? '.pdf,.svg,.json,.csv,.xlsx,.xlm,.rar,.zip,.db.txt,.text,.doc,.xls,.ptt,.prg' : '';

    $n_png = $png == 1 ? '.png, ' : '';
    $n_jpeg = $jpeg == 1 ? '.jpeg, ' : '';
    $jpg = $jpg == 1 ? '.jpg, ' : '';

    $n_mp3 = $mp3 == 1 ? '.mp3, ' : '';
    $n_mp4 = $mp4 == 1 ? '.mp4, ' : '';
    $n_wav = $wav == 1 ? '.wav, ' : '';

    $n_pdf = $pdf == 1 ? '.pdf, ' : '';
    $n_svg = $svg == 1 ? '.svg, ' : '';
    $n_json = $json == 1 ? '.json, ' : '';
    $n_csv = $csv == 1 ? '.csv, ' : '';
    $n_xlsx = $xlsx == 1 ? '.xlsx, ' : '';
    $n_xlm = $xlm == 1 ? '.xlm, ' : '';
    $n_rar = $rar == 1 ? '.rar, ' : '';
    $n_zip = $zip == 1 ? '.zip, ' : '';
    $n_db = $db == 1 ? '.db, ' : '';
    $n_txt = $txt == 1 ? '.txt, ' : '';
    $n_text = $text == 1 ? '.text, ' : '';
    $n_doc = $doc == 1 ? '.doc, ' : '';
    $n_xls = $xls == 1 ? '.xls, ' : '';
    $n_ptt = $ptt == 1 ? '.ptt, ' : '';
    $n_prg = $prg == 1 ? '.prg, ' : '';

    $file_type = '';
    if ($type_select_all == 1) {
      $file_type = $n_type_select_all;
    } else if ($type_img_all == 1) {
      $file_type =  $n_type_img_all;
    } else if ($type_video_all == 1) {
      $file_type = $n_type_video_all;
    } else if ($type_file_all == 1) {
      $file_type = $n_type_file_all;
    } else {
      $file_type = $n_png . '' . $n_jpeg . '' . $jpg . '' . $n_mp3 . '' . $n_mp4 . '' . $n_wav . '' . $n_pdf . '' . $n_svg . '' . $n_json . '' . $n_json . '' . $n_csv . '' . $n_xlsx . '' . $n_xlm . '' . $n_rar . '' . $n_zip . '' . $n_db . '' . $n_txt . '' . $n_text . '' . $n_doc . '' . $n_xls . '' . $n_ptt . '' . $n_prg;
    }

    ?>
    <?php if ($mode == 'view' && $is_edit == true || $mode == '' && $is_edit == false) { ?>
      <div class="w-item fill-list <?= $size_box . ' ' . $alignment ?> <?= $data['type_display'] != 1 ? 'stack-input' : '' ?>">
        <?php if ($is_show_title == 1) { ?>
          <div style="min-width:<?= $page_spacing  ?>px;max-width:<?= $page_spacing  ?>px;">
            <div class="font-<?= $data['font_size_title'] ?>px <?= $is_bold ?> <?= $is_edit == true ? $is_required_field : '' ?>" style="color:<?= $color ?> ;">
              <?= $data['input_title'] ?>
            </div>
          </div>
        <?php } ?>
        <div class="<?= $size_input ?>">
          <?php
          $file_show =  isset($extra_record) ? $extra_record[$data['db_column_name']] : '';

          $file_path =  '';
          $is_required_file = isset($data['is_required_fill']) && $data['is_required_fill'] ? 'required' : '';
          if ($file_show != '') {
            $file_path = File::getPath($file_show);
            $file_path = strpos($file_path, '/placeholder/') === false ? $file_path : '';
            $datafile = W_Main::datafile($file_path);
            $is_required_file = '';
          }
          ?>
          <div class="scope_event_file">
            <?php
            TiwForm::normal('file', $file_path, ['name' => 'template[' . $temp_name . '][' . $data['db_column_name'] . ']',  $is_required_file => '', 'accept' => $file_type, 'onchange' => 'changeUploadFileUpload(event);'], []);
            TiwForm::normal('hidden', 0, ['name' => 'template[' . $temp_name . '][deletefile][]', 'class' => 'change_val_delete']);
            TiwForm::normal('hidden', $file_show, ['name' => 'template[' . $temp_name . '][filename][]']);
            ?>
          </div>
          <?php if ($is_show_advice == 1) : ?>
            <div>
              <?= $advice ?>
            </div>
          <?php endif; ?>
        </div>
      </div>
    <?php } else if ($mode == 'view' && $is_edit == false) { ?>
      <?php
      $file_show =  isset($extra_record) ? $extra_record[$data['db_column_name']] : '';
      $file_path =  '';
      $datafile = '';
      if ($file_show != '') {
        $file_path = File::getPath($file_show);
        $datafile = W_Main::datafile($file_path);
      }
      ?>
      <?php if ($file_path != '') { ?>
        <?php
        if ($datafile['ext'] == 'pdf' || $datafile['ext'] == 'svg' || $datafile['ext'] == 'json' ||  $datafile['ext'] == 'csv' ||  $datafile['ext'] == 'xlsx' || $datafile['ext'] == 'xlm' || $datafile['ext'] == 'rar' || $datafile['ext'] == 'zip' || $datafile['ext'] == 'db' || $datafile['ext'] == 'txt' ||  $datafile['ext'] == 'text' || $datafile['ext'] == 'doc' ||  $datafile['ext'] == 'xls' ||  $datafile['ext'] == 'ptt' ||  $datafile['ext'] == 'prg') { ?>
          <?php if ($is_show_title == 1) { ?>
            <div class="w-item fill-list  <?= $size_box . ' ' . $alignment ?> <?= $data['type_display'] != 1 ? 'stack-input' : '' ?>">
              <div class="font-<?= $data['font_size_title'] ?>px <?= $is_bold ?> title-link--download" style="color:<?= $color ?> ;">
                <?= $is_show_title == 1 ? $data['input_title'] : '' ?>
                <a href="<?= $file_path ?>" target="_blank" class="text-truncate-link ml-5px"> <?= $file_path ?></a>
              </div>
            </div>
          <?php } else { ?>
            <a href="<?= $file_path ?>" target="_blank"> <?= $file_path ?></a>
          <?php } ?>
        <?php } else if ($datafile['ext'] == 'png' || $datafile['ext'] == 'jpeg' || $datafile['ext'] == 'jpg') { ?>
          <img src="<?= $file_path ?>" alt="<?= $file_path ?>" class="img-fluid">
        <?php } else if ($datafile['ext'] == 'mp3' || $datafile['ext'] == 'mp4' || $datafile['ext'] == 'wav') { ?>
          <video width="100%" height="450px" controls>
            <source src="<?= $file_path ?>">
          </video>
        <?php } ?>
      <?php } ?>
    <?php } ?>
  <?php return ob_get_clean();
  }

  public static function tag($id, $code, $val, $data, $page_spacing, $key = [], $is_edit, $mode)
  {
    ob_start(); ?>
    <?php
    $temp_name = $code .  $data['table_name'];
    if (strpos($data['table_name'], 'sys_') !== false) {
      $temp_name = $code . '_' . $data['table_name'];
    }
    $color = isset($data['color']) ? $data['color'] : '';
    $color_inner = isset($data['color_inner']) ? $data['color_inner'] : '';
    $custom_color = isset($data['custom_color']) ? $data['custom_color'] : '';
    $custom_color_placeholder = isset($data['custom_color_placeholder']) ? $data['custom_color_placeholder'] : '';
    $alignment = isset($data['alignment']) ? $data['alignment'] : '';
    $is_bold = isset($data['font_bold']) ? $data['font_bold'] : '';
    $size_input = isset($data['size_input']) ? $data['size_input'] : '';
    $size_box = isset($data['size_display']) ? $data['size_display'] : '';
    $min = isset($data['min']) ? $data['min'] : '';
    $max = isset($data['max']) ? $data['max'] : '';
    $is_show_title = isset($data['is_show_title']) && $data['is_show_title'] ? 1 : 0;
    $is_required_field = '';
    if ($is_show_title == 1) {
      $is_required_field = isset($data['is_required_fill']) && $data['is_required_fill'] ? 'required' : '';
    }

    if ($is_bold == 1) {
      $is_bold = 'fw-bold';
    }
    if ($size_box != 100) {
      $size_box = 'w-50';
    } else {
      $size_box = 'w-100';
    }
    if ($alignment == 'left') {
      $alignment = 'me-auto';
    } else if ($alignment == 'center') {
      $alignment = 'mx-auto';
    } else if ($alignment == 'right') {
      $alignment = 'ms-auto';
    }
    if ($color == 'var(--v2-color-info)') {
      $color = 'var(--v2-color-info)';
    } else if ($color == 'var(--v2-color-secondary)') {
      $color = 'var(--v2-color-secondary)';
    } else if ($color == 'var(--v2-color-primary)') {
      $color = 'var(--v2-color-primary)';
    } else {
      $color = $custom_color;
    }
    if ($color_inner  == 'var(--v2-color-info)') {
      $color_inner = 'var(--v2-color-info)';
    } else if ($color_inner  == 'var(--v2-color-secondary)') {
      $color_inner = 'var(--v2-color-secondary)';
    } else if ($color_inner  == 'var(--v2-color-primary)') {
      $color_inner = 'var(--v2-color-primary)';
    } else {
      $color_inner = $custom_color_placeholder;
    }
    $size_input = isset($data['size_input']) != 100 ? 'w-50' : 'w-100';
    $size_box = isset($data['size_display']) != 100 ? 'w-50' : 'w-100';
    $placeholder_text = isset($data['placeholder_text']) ? $data['placeholder_text'] : '';
    $is_search = isset($data['is_search']) ? $data['is_search'] : '';
    ?>
    <div class="w-item fill-list <?= $size_box . ' ' . $alignment ?> <?= $data['type_display'] != 1 ? 'stack-input' : '' ?>">
      <?php if ($is_show_title == 1) { ?>
        <div style="min-width:<?= $page_spacing  ?>px;max-width:<?= $page_spacing  ?>px;">
          <div class="font-<?= $data['font_size_title'] ?>px <?= $is_bold ?> <?= $is_edit == true ? $is_required_field : '' ?>" style="color:<?= $color ?>;">
            <?= $data['input_title'] ?>
          </div>
        </div>
      <?php } else { ?>
        <div class="font-<?= $data['font_size_title'] ?>px <?= $is_bold ?> <?= $is_edit == true ? $is_required_field : '' ?>" style="color:<?= $color ?>;">
          <?= $placeholder_text ? $placeholder_text : $data['input_title'] ?>
        </div>
      <?php } ?>
      <div class="<?= $size_input ?>">
        <?php
        $attr = [
          'name' => 'template[' . $temp_name . '][' . $data['db_column_name'] . ']',
          'placeholder' => isset($list['is_show_placeholder']) ? $list['placeholder_text'] : '',
        ];
        $options = [
          'list' => [],
          'is_edit' => $is_edit,
        ];
        if (isset($data['option']) &&  $data['option']) {
          foreach ($data['option'] as $key_option => $list_option) {
            if (isset($list_option['is_open']) && $list_option['is_open'] == 1) {
              $options['list'][$key_option]['value'] = $list_option['name'];
              $options['list'][$key_option]['name'] = $list_option['name'];
            }
          }
        }
        $val = json_decode($val, true);
        TiwForm::normal('select-tag', $val, $attr, $options);
        ?>
      </div>
    </div>
  <?php return ob_get_clean();
  }

  public static function dropdown($id, $code, $val, $data, $page_spacing, $key = [], $is_edit, $mode)
  {
    ob_start(); ?>
    <?php
    $temp_name = $code .  $data['table_name'];
    if (strpos($data['table_name'], 'sys_') !== false) {
      $temp_name = $code . '_' . $data['table_name'];
    }
    $color = isset($data['color']) ? $data['color'] : '';
    $color_inner = isset($data['color_inner']) ? $data['color_inner'] : '';
    $custom_color = isset($data['custom_color']) ? $data['custom_color'] : '';
    $custom_color_placeholder = isset($data['custom_color_placeholder']) ? $data['custom_color_placeholder'] : '';
    $alignment = isset($data['alignment']) ? $data['alignment'] : '';
    $is_bold = isset($data['font_bold']) ? $data['font_bold'] : '';
    $size_input = isset($data['size_input']) ? $data['size_input'] : '';
    $size_box = isset($data['size_display']) ? $data['size_display'] : '';
    $min = isset($data['min']) ? $data['min'] : '';
    $max = isset($data['max']) ? $data['max'] : '';
    $is_show_title = isset($data['is_show_title']) && $data['is_show_title'] ? 1 : 0;
    $is_required_field = '';
    if ($is_show_title == 1) {
      $is_required_field = isset($data['is_required_fill']) && $data['is_required_fill'] ? 'required' : '';
    }

    if ($is_bold == 1) {
      $is_bold = 'fw-bold';
    }
    if ($size_box != 100) {
      $size_box = 'w-50';
    } else {
      $size_box = 'w-100';
    }
    if ($alignment == 'left') {
      $alignment = 'me-auto';
    } else if ($alignment == 'center') {
      $alignment = 'mx-auto';
    } else if ($alignment == 'right') {
      $alignment = 'ms-auto';
    }
    if ($color == 'var(--v2-color-info)') {
      $color = 'var(--v2-color-info)';
    } else if ($color == 'var(--v2-color-secondary)') {
      $color = 'var(--v2-color-secondary)';
    } else if ($color == 'var(--v2-color-primary)') {
      $color = 'var(--v2-color-primary)';
    } else {
      $color = $custom_color;
    }
    if ($color_inner  == 'var(--v2-color-info)') {
      $color_inner = 'var(--v2-color-info)';
    } else if ($color_inner  == 'var(--v2-color-secondary)') {
      $color_inner = 'var(--v2-color-secondary)';
    } else if ($color_inner  == 'var(--v2-color-primary)') {
      $color_inner = 'var(--v2-color-primary)';
    } else {
      $color_inner = $custom_color_placeholder;
    }
    $size_input = isset($data['size_input']) != 100 ? 'w-50' : 'w-100';
    $size_box = isset($data['size_display']) != 100 ? 'w-50' : 'w-100';
    $placeholder_text = isset($data['placeholder_text']) ? $data['placeholder_text'] : '';
    $is_search = isset($data['is_search']) ? $data['is_search'] : '';
    ?>
    <div class="w-item fill-list <?= $size_box . ' ' . $alignment ?> <?= $data['type_display'] != 1 ? 'stack-input' : '' ?>">
      <?php if ($is_show_title == 1) { ?>
        <div style="min-width:<?= $page_spacing  ?>px;max-width:<?= $page_spacing  ?>px;">
          <div class="font-<?= $data['font_size_title'] ?>px <?= $is_bold ?> <?= $is_edit == true ? $is_required_field : '' ?>" style="color:<?= $color ?>;">
            <?= $data['input_title'] ?>
          </div>
        </div>
      <?php } else { ?>
        <div class="font-<?= $data['font_size_title'] ?>px <?= $is_bold ?> <?= $is_edit == true ? $is_required_field : '' ?>" style="color:<?= $color ?>;">
          <?= $placeholder_text ? $placeholder_text : $data['input_title'] ?>
        </div>
      <?php } ?>
      <div class="<?= $size_input ?>">
        <?php
        $attr = [
          'name' => 'template[' . $temp_name . '][' . $data['db_column_name'] . ']',
          'placeholder' => isset($list['is_show_placeholder']) ? $list['placeholder_text'] : '',
        ];
        $options = [
          'list' => [],
          'is_edit' => $is_edit,
          'is_search' => $is_search,
        ];
        if (isset($data['option']) &&  $data['option']) {
          foreach ($data['option'] as $key_option => $list_option) {
            if (isset($list_option['is_open']) && $list_option['is_open'] == 1) {
              $options['list'][$key_option]['value'] = $list_option['name'];
              $options['list'][$key_option]['name'] = $list_option['name'];
            }
          }
        }
        TiwForm::normal('select', $val, $attr, $options);
        ?>
      </div>
    </div>
  <?php return ob_get_clean();
  }

  public static function radio($id, $code, $val, $data, $page_spacing, $key = [], $is_edit, $mode)
  {
    ob_start(); ?>
    <?php
    $temp_name = $code .  $data['table_name'];
    if (strpos($data['table_name'], 'sys_') !== false) {
      $temp_name = $code . '_' . $data['table_name'];
    }
    $color = isset($data['color']) ? $data['color'] : '';
    $color_inner = isset($data['color_inner']) ? $data['color_inner'] : '';
    $custom_color = isset($data['custom_color']) ? $data['custom_color'] : '';
    $custom_color_placeholder = isset($data['custom_color_placeholder']) ? $data['custom_color_placeholder'] : '';
    $alignment = isset($data['alignment']) ? $data['alignment'] : '';
    $is_bold = isset($data['font_bold']) ? $data['font_bold'] : '';
    $size_input = isset($data['size_input']) ? $data['size_input'] : '';
    $size_box = isset($data['size_display']) ? $data['size_display'] : '';
    $min = isset($data['min']) ? $data['min'] : '';
    $max = isset($data['max']) ? $data['max'] : '';
    $is_show_title = isset($data['is_show_title']) && $data['is_show_title'] ? 1 : 0;
    $is_required_field = '';
    if ($is_show_title == 1) {
      $is_required_field = isset($data['is_required_fill']) && $data['is_required_fill'] ? 'required' : '';
    }

    if ($is_bold == 1) {
      $is_bold = 'fw-bold';
    }
    if ($size_box != 100) {
      $size_box = 'w-50';
    } else {
      $size_box = 'w-100';
    }
    if ($alignment == 'left') {
      $alignment = 'me-auto';
    } else if ($alignment == 'center') {
      $alignment = 'mx-auto';
    } else if ($alignment == 'right') {
      $alignment = 'ms-auto';
    }
    if ($color == 'var(--v2-color-info)') {
      $color = 'var(--v2-color-info)';
    } else if ($color == 'var(--v2-color-secondary)') {
      $color = 'var(--v2-color-secondary)';
    } else if ($color == 'var(--v2-color-primary)') {
      $color = 'var(--v2-color-primary)';
    } else {
      $color = $custom_color;
    }
    if ($color_inner  == 'var(--v2-color-info)') {
      $color_inner = 'var(--v2-color-info)';
    } else if ($color_inner  == 'var(--v2-color-secondary)') {
      $color_inner = 'var(--v2-color-secondary)';
    } else if ($color_inner  == 'var(--v2-color-primary)') {
      $color_inner = 'var(--v2-color-primary)';
    } else {
      $color_inner = $custom_color_placeholder;
    }
    $size_input = isset($data['size_input']) != 100 ? 'w-50' : 'w-100';
    $size_box = isset($data['size_display']) != 100 ? 'w-50' : 'w-100';
    $placeholder_text = isset($data['placeholder_text']) ? $data['placeholder_text'] : '';
    $data_value = [];
    if ($val) {
      $data_value = json_decode($val, true);
    }

    ?>
    <div class="w-item fill-list align-items-start <?= $size_box . ' ' . $alignment ?> <?= $data['type_display'] != 1 ? 'stack-input' : '' ?>">
      <?php if ($is_show_title == 1) { ?>
        <div style="min-width:<?= $page_spacing  ?>px;max-width:<?= $page_spacing  ?>px;">
          <div class="font-<?= $data['font_size_title'] ?>px <?= $is_bold ?> <?= $is_edit == true ? $is_required_field : '' ?>" style="color:<?= $color ?>;">
            <?= $data['input_title'] ?>
          </div>
        </div>
      <?php } else { ?>
        <div class="font-<?= $data['font_size_title'] ?>px <?= $is_bold ?> <?= $is_edit == true ? $is_required_field : '' ?>" style="color:<?= $color ?>;">
          <?= $placeholder_text ? $placeholder_text : $data['input_title'] ?>
        </div>
      <?php } ?>
      <div class="<?= $size_input ?> <?= $data['format_display'] == 'lefttoright' ? 'd-flex' : '' ?>">
        <?php
        $value_checked = isset($data_value['value']) ? $data_value['value'] : '';
        if ($data['option']) {
          foreach ($data['option'] as $key_option => $list_option) {
            if (isset($list_option['is_open']) && $list_option['is_open'] == 1) {
              $attr_radio = [
                'name' => 'template[' . $temp_name . '][' . $data['db_column_name'] . '][value]',
                'checked' => $data['select_first_checkbox'] == $list_option['name'] ? true : false,
              ];
              if ($value_checked) {
                $attr_radio['checked'] = $value_checked == $list_option['name'] ? true : false;
              }
              $attr_input_text_radio = [
                'name' => 'template[' . $temp_name . '][' . $data['db_column_name'] . '][other]',
                'class' => 'max-w-150px',
              ];
              if (!$is_edit) {
                $attr_radio['disabled'] = 'disabled';
                $attr_input_text_radio['disabled'] = 'disabled';
              }
              $options_radio = [
                'label' => $list_option['name'],
                'style' => '2'
              ];
              if (isset($list_option['is_other']) && $list_option['is_other'] == 1) {
                $input_data_other = '';
                if ($value_checked == $list_option['name']) {
                  $input_data_other = $data_value['other'];
                }
                $attr_radio['main_class'] = 'mr-10px';
                echo '<div class="d-flex align-items-center mb-10px">';
                TiwForm::normal('radio', $list_option['name'],  $attr_radio, $options_radio);
                TiwForm::normal('text', $input_data_other, $attr_input_text_radio);
                echo '</div>';
              } else {
                $attr_radio['main_class'] = 'mb-10px mr-10px';
                TiwForm::normal('radio', $list_option['name'],  $attr_radio, $options_radio);
              }
            }
          }
        }

        ?>
      </div>
    </div>
  <?php return ob_get_clean();
  }

  public static function checkbox($id, $code, $val, $data, $page_spacing, $key = [], $is_edit, $mode)
  {
    ob_start(); ?>
    <?php
    $temp_name = $code .  $data['table_name'];
    if (strpos($data['table_name'], 'sys_') !== false) {
      $temp_name = $code . '_' . $data['table_name'];
    }
    $color = isset($data['color']) ? $data['color'] : '';
    $color_inner = isset($data['color_inner']) ? $data['color_inner'] : '';
    $custom_color = isset($data['custom_color']) ? $data['custom_color'] : '';
    $custom_color_placeholder = isset($data['custom_color_placeholder']) ? $data['custom_color_placeholder'] : '';
    $alignment = isset($data['alignment']) ? $data['alignment'] : '';
    $is_bold = isset($data['font_bold']) ? $data['font_bold'] : '';
    $size_input = isset($data['size_input']) ? $data['size_input'] : '';
    $size_box = isset($data['size_display']) ? $data['size_display'] : '';
    $min = isset($data['min']) ? $data['min'] : '';
    $max = isset($data['max']) ? $data['max'] : '';
    $is_show_title = isset($data['is_show_title']) && $data['is_show_title'] ? 1 : 0;
    $is_required_field = '';
    if ($is_show_title == 1) {
      $is_required_field = isset($data['is_required_fill']) && $data['is_required_fill'] ? 'required' : '';
    }

    if ($is_bold == 1) {
      $is_bold = 'fw-bold';
    }
    if ($size_box != 100) {
      $size_box = 'w-50';
    } else {
      $size_box = 'w-100';
    }
    if ($alignment == 'left') {
      $alignment = 'me-auto';
    } else if ($alignment == 'center') {
      $alignment = 'mx-auto';
    } else if ($alignment == 'right') {
      $alignment = 'ms-auto';
    }
    if ($color == 'var(--v2-color-info)') {
      $color = 'var(--v2-color-info)';
    } else if ($color == 'var(--v2-color-secondary)') {
      $color = 'var(--v2-color-secondary)';
    } else if ($color == 'var(--v2-color-primary)') {
      $color = 'var(--v2-color-primary)';
    } else {
      $color = $custom_color;
    }
    if ($color_inner  == 'var(--v2-color-info)') {
      $color_inner = 'var(--v2-color-info)';
    } else if ($color_inner  == 'var(--v2-color-secondary)') {
      $color_inner = 'var(--v2-color-secondary)';
    } else if ($color_inner  == 'var(--v2-color-primary)') {
      $color_inner = 'var(--v2-color-primary)';
    } else {
      $color_inner = $custom_color_placeholder;
    }
    $size_input = isset($data['size_input']) != 100 ? 'w-50' : 'w-100';
    $size_box = isset($data['size_display']) != 100 ? 'w-50' : 'w-100';
    $placeholder_text = isset($data['placeholder_text']) ? $data['placeholder_text'] : '';
    $data_value = [];
    if ($val) {
      $data_value = json_decode($val, true);
    }
    ?>
    <div class="w-item fill-list align-items-start <?= $size_box . ' ' . $alignment ?> <?= $data['type_display'] != 1 ? 'stack-input' : '' ?>">
      <?php if ($is_show_title == 1) { ?>
        <div style="min-width:<?= $page_spacing  ?>px;max-width:<?= $page_spacing  ?>px;">
          <div class="font-<?= $data['font_size_title'] ?>px <?= $is_bold ?> <?= $is_edit == true ? $is_required_field : '' ?>" style="color:<?= $color ?>;">
            <?= $data['input_title'] ?>
          </div>
        </div>
      <?php } else { ?>
        <div class="font-<?= $data['font_size_title'] ?>px <?= $is_bold ?> <?= $is_edit == true ? $is_required_field : '' ?>" style="color:<?= $color ?>;">
          <?= $placeholder_text ? $placeholder_text : $data['input_title'] ?>
        </div>
      <?php } ?>
      <div class="<?= $size_input ?> <?= $data['format_display'] == 'lefttoright' ? 'd-flex' : '' ?>">
        <?php
        if ($data['option']) {
          foreach ($data['option'] as $key_option => $list_option) {
            if (isset($list_option['is_open']) && $list_option['is_open'] == 1) {
              $attr_checkbox = [
                'name' => 'template[' . $temp_name . '][' . $data['db_column_name'] . '][' . $key_option . '][value]',
                'checked' => isset($data_value[$key_option]['value']) && $data_value[$key_option]['value'] == $list_option['name'] ? true : false,
              ];

              $attr_input_text_checkbox = [
                'name' => 'template[' . $temp_name . '][' . $data['db_column_name'] . '][' . $key_option . '][other]',
                'class' => 'max-w-150px',
              ];
              if (!$is_edit) {
                $attr_checkbox['disabled'] = 'disabled';
                $attr_input_text_checkbox['disabled'] = 'disabled';
              }
              $options_checkbox = [
                'label' => $list_option['name'],
                'style' => '3'
              ];
              if (isset($list_option['is_other']) && $list_option['is_other'] == 1) {
                $input_data_other = '';
                if (isset($data_value[$key_option]['value']) && $data_value[$key_option]['value'] == $list_option['name']) {
                  $input_data_other = $data_value[$key_option]['other'];
                }
                $attr_checkbox['main_class'] = 'mr-10px';
                echo '<div class="d-flex align-items-center mb-10px">';
                TiwForm::normal('checkbox', $list_option['name'],  $attr_checkbox, $options_checkbox);
                TiwForm::normal('text', $input_data_other, $attr_input_text_checkbox);
                echo '</div>';
              } else {
                $attr_checkbox['main_class'] = 'mb-10px mr-10px';
                TiwForm::normal('checkbox', $list_option['name'],  $attr_checkbox, $options_checkbox);
              }
            }
          }
        }

        ?>
      </div>
    </div>
  <?php return ob_get_clean();
  }

  public static function switch($id, $code, $val, $data, $page_spacing, $key = [], $is_edit, $mode)
  {
    ob_start(); ?>
    <?php
    $temp_name = $code .  $data['table_name'];
    if (strpos($data['table_name'], 'sys_') !== false) {
      $temp_name = $code . '_' . $data['table_name'];
    }
    $color = isset($data['color']) ? $data['color'] : '';
    $color_inner = isset($data['color_inner']) ? $data['color_inner'] : '';
    $custom_color = isset($data['custom_color']) ? $data['custom_color'] : '';
    $custom_color_placeholder = isset($data['custom_color_placeholder']) ? $data['custom_color_placeholder'] : '';
    $alignment = isset($data['alignment']) ? $data['alignment'] : '';
    $is_bold = isset($data['font_bold']) ? $data['font_bold'] : '';
    $size_input = isset($data['size_input']) ? $data['size_input'] : '';
    $size_box = isset($data['size_display']) ? $data['size_display'] : '';
    $min = isset($data['min']) ? $data['min'] : '';
    $max = isset($data['max']) ? $data['max'] : '';
    $is_show_title = isset($data['is_show_title']) && $data['is_show_title'] ? 1 : 0;
    $is_required_field = '';
    if ($is_show_title == 1) {
      $is_required_field = isset($data['is_required_fill']) && $data['is_required_fill'] ? 'required' : '';
    }

    if ($is_bold == 1) {
      $is_bold = 'fw-bold';
    }
    if ($size_box != 100) {
      $size_box = 'w-50';
    } else {
      $size_box = 'w-100';
    }
    if ($alignment == 'left') {
      $alignment = 'me-auto';
    } else if ($alignment == 'center') {
      $alignment = 'mx-auto';
    } else if ($alignment == 'right') {
      $alignment = 'ms-auto';
    }
    if ($color == 'var(--v2-color-info)') {
      $color = 'var(--v2-color-info)';
    } else if ($color == 'var(--v2-color-secondary)') {
      $color = 'var(--v2-color-secondary)';
    } else if ($color == 'var(--v2-color-primary)') {
      $color = 'var(--v2-color-primary)';
    } else {
      $color = $custom_color;
    }
    if ($color_inner  == 'var(--v2-color-info)') {
      $color_inner = 'var(--v2-color-info)';
    } else if ($color_inner  == 'var(--v2-color-secondary)') {
      $color_inner = 'var(--v2-color-secondary)';
    } else if ($color_inner  == 'var(--v2-color-primary)') {
      $color_inner = 'var(--v2-color-primary)';
    } else {
      $color_inner = $custom_color_placeholder;
    }
    $size_input = isset($data['size_input']) != 100 ? 'w-50' : 'w-100';
    $size_box = isset($data['size_display']) != 100 ? 'w-50' : 'w-100';
    $placeholder_text = isset($data['placeholder_text']) ? $data['placeholder_text'] : '';
    $data_value = [];
    if ($val) {
      $data_value = json_decode($val, true);
    }
    ?>
    <div class="w-item fill-list align-items-start <?= $size_box . ' ' . $alignment ?> <?= $data['type_display'] != 1 ? 'stack-input' : '' ?>">
      <?php if ($is_show_title == 1) { ?>
        <div style="min-width:<?= $page_spacing  ?>px;max-width:<?= $page_spacing  ?>px;">
          <div class="font-<?= $data['font_size_title'] ?>px <?= $is_bold ?> <?= $is_edit == true ? $is_required_field : '' ?>" style="color:<?= $color ?>;">
            <?= $data['input_title'] ?>
          </div>
        </div>
      <?php } else { ?>
        <div class="font-<?= $data['font_size_title'] ?>px <?= $is_bold ?> <?= $is_edit == true ? $is_required_field : '' ?>" style="color:<?= $color ?>;">
          <?= $placeholder_text ? $placeholder_text : $data['input_title'] ?>
        </div>
      <?php } ?>
      <div class="<?= $size_input ?> <?= ($data['type_display'] == 2) ? 'mt-10px' : '' ?>">
        <?php
        $attr_switch = [
          'name' => 'template[' . $temp_name . '][' . $data['db_column_name'] . '][checked]',
          'checked' => isset($data_value['checked']) ? $data_value['checked'] : false,
          'onchange' => 'eventCheckedLabel(event)'
        ];
        if (!$is_edit) {
          $attr_switch['disabled'] = 'disabled';
        }
        $attr_input_hidden_switch = [
          'name' => 'template[' . $temp_name . '][' . $data['db_column_name'] . '][name]',
        ];
        $options_switch = [
          'label' => isset($data_value['checked']) ? $data['true_text'] : $data['false_text'],
          'style' => '1',
          'is_on_off' => true
        ];
        TiwForm::normal('checkbox', '1',  $attr_switch, $options_switch);
        TiwForm::normal('hidden', $data['db_column_name'], $attr_input_hidden_switch);
        ?>
      </div>
      <script>
        function eventCheckedLabel(e) {
          let input = e.target;
          let scope = input.closest('.scope_box_event');
          if (input.checked) {
            scope.querySelector('.label_text').innerHTML = '<span class="text-primary"><?= $data['true_text'] ?></span>';
          } else {
            scope.querySelector('.label_text').innerHTML = '<span class="text-secondary"><?= $data['false_text'] ?></span>';
          }
        }
      </script>
    </div>
<?php return ob_get_clean();
  }
}
