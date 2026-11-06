<?php

$prefix = '../../../../../';
require_once $prefix . '_framework/import_for_ajax.php';
W_Structure::loadMetaForAjax($prefix);

$code  = isset($_GET['c']) ? $_GET['c'] : '';
$tab_id = isset($_GET['tab']) ? $_GET['tab'] : '';
$template = isset($_GET['template']) ? $_GET['template'] : '';
$func = isset($_GET['func']) ? $_GET['func'] : '';
$obj = [];
$file = [];


if ($tab_id) {
  $result = Extra::getExtraTemplateTab($tab_id);
  $data_template = $result['meta_data'][0];
} else {
  $result = Extra::getExtraTemplate($func, $template);
  $data_template = $result['meta_data'][0];
}
$meta_data = [];
if ($data_template) {
  if ($data_template['meta_data']) {
    foreach ($data_template['meta_data'] as $key => $data) {
      if (isset($data['unique_name'])) {
        $obj[$key] = $data['unique_name'];
      }
    }
  }
  foreach ($obj as $key => $file_path) {
    $file[$key] = File::getPath($file_path);
  }
}
$data_fill = [
  'list' => [
    'file_path' => $file,
    'meta_data' => $data_template['meta_data'],
  ],
  'tab_setting' => isset($data_template['setting']) ? $data_template['setting'] : '',
];
$meta_data = $data_fill['list']['meta_data'];
$tab_setting = $data_fill['tab_setting'];

// ตัวแปรที่มี ปล ยังไม่ครบนะครับ
// type
// title_text
// font_family
// font_size
// font_bold
// custom_color_text
// align_text
// size_display_text
// alignment_display
// field_name
// table_name
// code
// db_column_name
// type
// field_id
// is_show_title
// type_input_text
// input_title
// font_size_title
// color
// custom_color
// size_input
// input_inner_size
// color_inner
// custom_color_placeholder
// placeholder_text
// color_placeholder
// is_edit_fill
// is_required_fill
// is_show_list_fill
// is_same_data
// size_display
// alignment
// type_display
// is_show_placeholder
// alignment_line
// color_line
// size_line
?>

<?php
if ($meta_data) {
  foreach ($meta_data as $meta_data_key => $list) {
    $rand_id = TiwForm::randomName(5);
    $file_path = isset($data_fill['list']['file_path'][$meta_data_key]) ? $data_fill['list']['file_path'][$meta_data_key] : '';
    $type = '';
    if (isset($list['type']) && $list['type']) {
      $type = $list['type'];
    } else {
      $type = $list['option_file']['type'];
    }

    $category = isset($list['category']) ? $list['category'] : '';
    $width = '';
    $font_bold = '';
    $font_italic = '';
    $size = '';
    $size_display_text = isset($list['size_display_text']) && $list['size_display_text'] ? $list['size_display_text'] : '';

    $image_slider = isset($list['image_slider']) && $list['image_slider'] ? $list['image_slider'] : ''; // Field รูป Slider เป็น Array
    $image_slider_length  = isset($list['image_slider_length']) && $list['image_slider_length'] ? $list['image_slider_length'] : ''; // ความกว้างของ Slider
    $image_slider_height = isset($list['image_slider_height']) && $list['image_slider_height'] ? $list['image_slider_height'] : ''; // ความสูงของ Slider

    // รูปแบบการแสดง Slider
    // ค่า 1 เป็น แสดงตามขนาดจริงของรูปภาพ
    // ค่า 2 เป็น แสดงรูปภาพภายในพื้นที่แสดงผล
    $type_display_image = isset($list['type_display_image']) && $list['type_display_image'] ? $list['type_display_image'] : '';
    $type_display = isset($list['type_display']) && $list['type_display'] ? $list['type_display'] : '';

    if (isset($list['size_display']) && $list['size_display']) {
      $width = 'width:' . $list['size_display'] . '%;';
    }
    if (isset($list['font_bold']) && $list['font_bold'] == 1) {
      $font_bold = 'font-Bold';
    }
    if (isset($list['font_italic']) && $list['font_italic'] == 1) {
      $font_italic = 'fst-italic';
    }
    if ($tab_setting && $tab_setting['page_col'] == 1) {
      $size = '100%';
    } else {
      $size = $size_display_text ? $size_display_text . '%' : '';
    }

    $placeholder_text = isset($list['placeholder_text']) && $list['placeholder_text'] ? $list['placeholder_text'] : ''; // ตัวอย่างช่องกรอก

    // Line
    $alignment_line = isset($list['alignment_line']) && $list['alignment_line'] ? $list['alignment_line'] : ''; // ตำแหน่งของเส้น
    if ($alignment_line == 'left') {
      $alignment_line = 'me-auto';
    } else if ($alignment_line == 'center') {
      $alignment_line = 'mx-auto';
    } else if ($alignment_line == 'right') {
      $alignment_line = 'ms-auto';
    } else {
      $alignment_line = '';
    }

    // ขนาด Box ของ Image
    $size_display_image = (isset($list['option_file']['size_display_image']) && $list['option_file']['size_display_image']) ? $list['option_file']['size_display_image'] : '';

    $size_line = (isset($list['size_line']) && $list['size_line']) ? $list['size_line'] : ''; // ความหนาของเส้น
    $color_line = (isset($list['color_line']) && $list['color_line']) ? $list['color_line'] : ''; //สีของเส้น
    $custom_color_line = (isset($list['custom_color_line']) && $list['custom_color_line']) ? $list['custom_color_line'] : ''; //สี Custom ของเส้น

    if ($color_line) {
      $color_line = $color_line;
    } else {
      $color_line = $custom_color_line;
    }

    // Text
    $align_text = isset($list['align_text']) && $list['align_text'] ? $list['align_text'] : ''; // ตำแหน่งของข้อความ
    $custom_color_text  = isset($list['custom_color_text']) && $list['custom_color_text'] ? $list['custom_color_text'] : ''; //สี Custom ข้อความ
    $font_size = isset($list['font_size']) && $list['font_size'] ? $list['font_size'] : ''; // ขนาดตัวอักษร
    $font_family = isset($list['font_family']) && $list['font_family'] ? $list['font_family'] : ''; // รูปแบบตัวอักษร
    $size_display_text = isset($list['size_display_text']) && $list['size_display_text'] ? $list['size_display_text'] : ''; // ขนาดช่องของข้อความ
    $alignment_display = isset($list['alignment_display']) && $list['alignment_display'] ? $list['alignment_display'] : ''; // ตำแหน่งแสดงของ Box ซ้าย กลาง ขวา ใช้เฉพาะที่ไม่ใช่ Field
    $title_text = isset($list['title_text']) && $list['title_text'] ? $list['title_text'] : ''; // ข้อความของ Text
    if ($alignment_display == 'left') {
      $alignment_display = 'me-auto';
    } else if ($alignment_display == 'center') {
      $alignment_display = 'mx-auto';
    } else if ($alignment_display == 'right') {
      $alignment_display = 'ms-auto';
    } else {
      $alignment_display = '';
    }
    if ($align_text == 'left') {
      $align_text_class = 'text-start';
    } else if ($align_text == 'center') {
      $align_text_class = 'text-center';
    } else if ($align_text == 'right') {
      $align_text_class = 'text-end';
    } else {
      $align_text_class = '';
    }

    // Image
    $alignment_img = isset($list['option_file']['alignment_img']) && $list['option_file']['alignment_img'] ? $list['option_file']['alignment_img'] : '';
    if ($alignment_img == 'left') {
      $alignment_img = 'me-auto';
    } else if ($alignment_img == 'center') {
      $alignment_img = 'mx-auto';
    } else if ($alignment_img == 'right') {
      $alignment_img = 'ms-auto';
    } else {
      $alignment_img = '';
    }

    $alignment = isset($list['alignment']) && $list['alignment'] ? $list['alignment'] : '';  // ตำแหน่งแสดงของ Box ซ้าย กลาง ขวา
    if ($alignment == 'left') {
      $alignment = 'me-auto';
    } else if ($alignment == 'center') {
      $alignment = 'mx-auto';
    } else if ($alignment == 'right') {
      $alignment = 'ms-auto';
    } else {
      $alignment = '';
    }

    // type Auto Address
    $address = isset($list['address']) && $list['address'] ? $list['address'] : ''; // ที่อยู่
    $sub_district = isset($list['sub_district']) && $list['sub_district'] ? $list['sub_district'] : ''; // ตำบล / เขต
    $district = isset($list['district']) && $list['district'] ? $list['district'] : ''; // อำเภอ / แขวง
    $city = isset($list['city']) && $list['city'] ? $list['city'] : ''; // จังหวัด
    $zip = isset($list['zip']) && $list['zip'] ? $list['zip'] : ''; // รหัสไปษณีย์
    $str_address = 'ที่อยู่ ' . $address . ' ตำบล / เขต ' .  $sub_district .  ' อำเภอ / แขวง ' . $district . ' จังหวัด ' .  $sub_district . ' รหัสไปษณีย์ ' . $zip;

    // type latitude  longitude
    $latitude = isset($list['latitude']) && $list['latitude'] ? $list['latitude'] : ''; // ละติจูด
    $longitude = isset($list['longitude']) && $list['longitude'] ? $list['longitude'] : ''; // ลองจิจูด
    $lat_long  = 'ละติจูด ' . $latitude . ' ลองจิจูด ' . $longitude;

    // Type Link And google map link
    $link = isset($list['link']) && $list['link'] ? $list['link'] : ''; // ลิงค์
    $advice = isset($list['advice']) && $list['advice'] ? $list['advice'] : ''; // คำแนะนำ
    $is_show_advice = isset($list['is_show_advice']) && $list['is_show_advice'] ? $list['is_show_advice'] : ''; // แสดงคำแนะนำ
    $is_click_link = isset($list['is_click_link']) && $list['is_click_link'] ? $list['is_click_link'] : ''; // คลิกลิงค์
    $is_copy_link = isset($list['is_copy_link']) && $list['is_copy_link'] ? $list['is_copy_link'] : ''; // คลิกลิ้งไปยังปลายทาง

    // Type Video Embed
    $is_click_video = isset($list['is_click_video']) && $list['is_click_video'] ? $list['is_click_video'] : ''; // คลิกลิ้งไปยังปลายทาง
    $video_type_preview = isset($list['video_type_preview']) && $list['video_type_preview'] ? $list['video_type_preview'] : ''; // พริวิว Video
?>
    <?php
    if ($type == 'line') {
    ?>
      <div id="scope_list_<?= $meta_data_key ?>" class="card-sortable <?= $alignment_line ?>" style="<?= $width  ?>">
        <input type="hidden" class="event_get_dataset" name="list[<?= $rand_id ?>][dataset]" value='<?= json_encode($list, true) ?>'>
        <div class="handle">
          <?= W_Structure::getfile('_framework/modules/sub/jform/icon/drag.svg') ?>
        </div>
        <div class="body-fill">
          <div class="__field-input">
            <div style="background-color:<?= $color_line  ?>;height:<?= $size_line ?>px;"></div>
          </div>
          <div class="__field-action">
            <button type="button" class="btn-action __btn--clone js-clone" onclick="cloneItem('scope_list_<?= $meta_data_key ?>','line');"></button>
            <button type="button" class="btn-action __btn--delete js-remove"></button>
            <button type="button" class="btn-action __btn--edit js-edit" onclick="editLine('scope_list_<?= $meta_data_key ?>','<?= $meta_data_key ?>')"></button>
          </div>
        </div>
      </div>
    <?php
    } else if ($type == 'text') { ?>
      <div id="scope_list_<?= $meta_data_key ?>" class="card-sortable <?= $alignment_display ?>" style="width:<?= $size ?>%">
        <input type="hidden" class="event_get_dataset" name="list[<?= $rand_id ?>][dataset]" value='<?= json_encode($list, true) ?>'>
        <div class="handle">
          <?= W_Structure::getfile('_framework/modules/sub/jform/icon/drag.svg') ?>
        </div>
        <div class="body-fill">
          <div class="__field-input">
            <div class="<?= $align_text_class . ' ' . $font_bold . ' ' . $font_italic ?> " style="font-size:<?= $font_size ?>px;color:<?= $custom_color_text ?>;font-family:<?= $font_family ?>">
              <?= $title_text ?>
            </div>
          </div>
          <div class="__field-action">
            <button type="button" class="btn-action __btn--clone js-clone" onclick="cloneItem('scope_list_<?= $meta_data_key ?>','text');"></button>
            <button type="button" class="btn-action __btn--delete js-remove"></button>
            <button type="button" class="btn-action __btn--edit js-edit" onclick="editText('scope_list_<?= $meta_data_key ?>','<?= $meta_data_key ?>')"></button>
          </div>
        </div>
      </div>
    <?php
    } else if ($type == 'image_slider') { ?>
      <div id="scope_list_<?= $meta_data_key ?>" class="card-sortable">
        <input type="hidden" class="event_get_dataset" name="list[<?= $rand_id ?>][dataset]" value='<?= json_encode($list, true) ?>'>
        <div class="handle">
          <?= W_Structure::getfile('_framework/modules/sub/jform/icon/drag.svg') ?>
        </div>
        <div class="body-fill">
          <div class="__field-input">
            Slider
          </div>
          <div class="__field-action">
            <button type="button" class="btn-action __btn--delete js-remove"></button>
            <button type="button" class="btn-action __btn--edit js-edit" onclick="editImageSlider('scope_list_<?= $meta_data_key ?>','<?= $meta_data_key ?>')"></button>
          </div>
        </div>
      </div>
    <?php
    } else if ($type == 'image') {
    ?>
      <div id="scope_list_<?= $meta_data_key ?>" class="card-sortable" style="width:<?= $size_display_image  ?>%;">
        <div class="handle">
          <?= W_Structure::getfile('_framework/modules/sub/jform/icon/drag.svg') ?>
        </div>
        <div class="flex-column w-100">
          <div class="<?= $alignment_img ?>" style="max-width:100%;width:<?= $list['option_file']['img_width'] ?>px ;height:<?= $list['option_file']['img_height'] ?>px;">
            <input type="hidden" class="event_get_dataset" name="list[<?= $rand_id ?>][dataset]" value='<?= json_encode($list, true) ?>'>
            <img src="<?= $file_path ?>" class="w-100 h-100">
          </div>
          <div class="__field-action d-flex justify-content-center text-align-center">
            <button type="button" class="btn-action __btn--clone js-clone" onclick="cloneItem('scope_list_<?= $meta_data_key ?>','image');"></button>
            <button type="button" class="btn-action __btn--delete js-remove"></button>
            <button type="button" class="btn-action __btn--edit js-edit" onclick="editImage('scope_list_<?= $meta_data_key ?>','<?= $meta_data_key ?>')"></button>
          </div>
        </div>
      </div>
    <?php
    } else if ($type == 'Varchar' && $category == 'Auto address') {
    ?>
      <div id="scope_list_<?= $meta_data_key ?>" class="card-sortable <?= $alignment ?>" style="width:<?= $size ?>">
        <input type="hidden" class="event_get_dataset" name="list[<?= $rand_id ?>][dataset]" value='<?= json_encode($list, true) ?>'>
        <div class="handle">
          <?= W_Structure::getfile('_framework/modules/sub/jform/icon/drag.svg') ?>
        </div>
        <div class="body-fill">
          <?php ($type_display == 2) ? '<div class="input-reverse">' : '' ?>
          <?php if (isset($list['is_show_title']) && $list['is_show_title']) { ?>
            <div class="__field-name" style="width:<?= $spacing ?>;">
              <div class="<?= $font_bold ?>" style="color:<?= $list['color']  ?>;"> <?= $list['input_title'] . ' ' . $is_required ?></div>
            </div>
          <?php } ?>
          <div class="__field-input"> <?= $str_address ?> </div>
          <?php ($type_display == 2) ? '</div>' : '' ?>
          <div class="__field-action">
            <button type="button" class="btn-action __btn--delete js-remove"></button>
            <button type="button" class="btn-action __btn--edit js-edit" onclick="editFill('scope_list_<?= $meta_data_key ?>','<?= $meta_data_key ?>')"></button>
          </div>
        </div>
      </div>
    <?php
    } else if ($type == 'Varchar' && $category == 'Latitude_Longtitude') {
    ?>
      <div id="scope_list_<?= $meta_data_key ?>" class="card-sortable <?= $alignment ?>" style="width:<?= $size ?>">
        <input type="hidden" class="event_get_dataset" name="list[<?= $rand_id ?>][dataset]" value='<?= json_encode($list, true) ?>'>
        <div class="handle">
          <?= W_Structure::getfile('_framework/modules/sub/jform/icon/drag.svg') ?>
        </div>
        <div class="body-fill">
          <?php ($type_display == 2) ? '<div class="input-reverse">' : '' ?>
          <?php if (isset($list['is_show_title']) && $list['is_show_title']) { ?>
            <div class="__field-name" style="width:<?= $spacing ?>;">
              <div class="<?= $font_bold ?>" style="color:<?= $list['color']  ?>;"> <?= $list['input_title'] . ' ' . $is_required ?></div>
            </div>
          <?php } ?>
          <div class="__field-input">
            <?= $lat_long ?>
          </div>
          <?php ($type_display == 2) ? '</div>' : '' ?>
          <div class="__field-action">
            <button type="button" class="btn-action __btn--delete js-remove"></button>
            <button type="button" class="btn-action __btn--edit js-edit" onclick="editFill('scope_list_<?= $meta_data_key ?>','<?= $meta_data_key ?>')"></button>
          </div>
        </div>
      </div>
    <?php
    } else if ($type == 'Varchar' && $category == 'Google map link') {
    ?>
      <div id="scope_list_<?= $meta_data_key ?>" class="card-sortable <?= $alignment ?>" style="width:<?= $size ?>">
        <input type="hidden" class="event_get_dataset" name="list[<?= $rand_id ?>][dataset]" value='<?= json_encode($list, true) ?>'>
        <div class="handle">
          <?= W_Structure::getfile('_framework/modules/sub/jform/icon/drag.svg') ?>
        </div>
        <div class="body-fill">
          <?php ($type_display == 2) ? '<div class="input-reverse">' : '' ?>
          <?php if (isset($list['is_show_title']) && $list['is_show_title']) { ?>
            <div class="__field-name" style="width:<?= $spacing ?>;">
              <div class="<?= $font_bold ?>" style="color:<?= $list['color']  ?>;"> <?= $list['input_title'] . ' ' . $is_required ?></div>
            </div>
          <?php } ?>
          <div class="__field-input">
            <div class="mb-5px">
              <?php if ($is_click_link == 1) { ?>
                <a href="<?= $link ?>">
                  <?= $link ?>
                </a>
              <?php } else { ?>
                <?= $link ?>
              <?php } ?>
            </div>
            <?php if ($is_show_advice == 1) { ?>
              <div class="font-14px text-secondary">
                <?= $advice ?>
              </div>
            <?php } ?>
          </div>
          <?php ($type_display == 2) ? '</div>' : '' ?>
          <div class="__field-action">
            <button type="button" class="btn-action __btn--delete js-remove"></button>
            <button type="button" class="btn-action __btn--edit js-edit" onclick="editFill('scope_list_<?= $meta_data_key ?>','<?= $meta_data_key ?>')"></button>
          </div>
        </div>
      </div>
    <?php
    } else if ($type == 'Varchar' && $category == 'Link') {
    ?>
      <div id="scope_list_<?= $meta_data_key ?>" class="card-sortable <?= $alignment ?>" style="width:<?= $size ?>">
        <input type="hidden" class="event_get_dataset" name="list[<?= $rand_id ?>][dataset]" value='<?= json_encode($list, true) ?>'>
        <div class="handle">
          <?= W_Structure::getfile('_framework/modules/sub/jform/icon/drag.svg') ?>
        </div>
        <div class="body-fill">
          <?php ($type_display == 2) ? '<div class="input-reverse">' : '' ?>
          <?php if (isset($list['is_show_title']) && $list['is_show_title']) { ?>
            <div class="__field-name" style="width:<?= $spacing ?>;">
              <div class="<?= $font_bold ?>" style="color:<?= $list['color']  ?>;"> <?= $list['input_title'] . ' ' . $is_required ?></div>
            </div>
          <?php } ?>
          <div class="__field-input">
            <div class="mb-5px">
              <?php if ($is_click_link == 1) { ?>
                <a href="<?= $placeholder_text ?>">
                  <?= $placeholder_text ?>
                </a>
              <?php } else { ?>
                <?= $placeholder_text ?>
              <?php } ?>
            </div>
            <?php if ($is_show_advice == 1) { ?>
              <div class="font-14px text-secondary">
                <?= $advice ?>
              </div>
            <?php } ?>
          </div>
          <?php ($type_display == 2) ? '</div>' : '' ?>
          <div class="__field-action">
            <button type="button" class="btn-action __btn--delete js-remove"></button>
            <button type="button" class="btn-action __btn--edit js-edit" onclick="editFill('scope_list_<?= $meta_data_key ?>','<?= $meta_data_key ?>')"></button>
          </div>
        </div>
      </div>
    <?php
    } else if ($type == 'Varchar' && $category == 'Youtube_embed') {
    ?>
      <div id="scope_list_<?= $meta_data_key ?>" class="card-sortable <?= $alignment ?>" style="width:<?= $size ?>">
        <input type="hidden" class="event_get_dataset" name="list[<?= $rand_id ?>][dataset]" value='<?= json_encode($list, true) ?>'>
        <div class="handle">
          <?= W_Structure::getfile('_framework/modules/sub/jform/icon/drag.svg') ?>
        </div>
        <div class="body-fill">
          <?php ($type_display == 2) ? '<div class="input-reverse">' : '' ?>
          <?php if (isset($list['is_show_title']) && $list['is_show_title']) { ?>
            <div class="__field-name" style="width:<?= $spacing ?>;">
              <div class="<?= $font_bold ?>" style="color:<?= $list['color']  ?>;"> <?= $list['input_title'] . ' ' . $is_required ?></div>
            </div>
          <?php } ?>
          <div class="__field-input">
            <div class="mb-5px">
              <?php if ($video_type_preview == 1) { ?>
                <?php if ($is_click_video == 1) { ?>
                  <a href="<?= $placeholder_text ?>">
                  <?php } ?>
                  <?= $placeholder_text ?>
                  <?php if ($is_click_link == 1) { ?>
                  </a>
                <?php } ?>
              <?php } else if ($video_type_preview == 2) { ?>
                <?= $placeholder_text ?>
                <!-- <iframe width="560" height="315" src="<?= $placeholder_text ?>" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen> </iframe> -->
              <?php } ?>
            </div>
            <?php if ($is_show_advice == 1) { ?>
              <div class="font-14px text-secondary">
                <?= $advice ?>
              </div>
            <?php } ?>
          </div>
          <?php ($type_display == 2) ? '</div>' : '' ?>
          <div class="__field-action">
            <button type="button" class="btn-action __btn--delete js-remove"></button>
            <button type="button" class="btn-action __btn--edit js-edit" onclick="editFill('scope_list_<?= $meta_data_key ?>','<?= $meta_data_key ?>')"></button>
          </div>
        </div>
      </div>
    <?php
    } else if ($type == 'Varchar' && $category == 'Tag') {
      $is_required = isset($list['is_required_fill']) && $list['is_required_fill'] == 1 ? '<span class="text-danger">*</span> ' : '';
      $spacing = '';
      $size = '';
      if ($tab_setting) {
        if ($tab_setting['page_spacing']) {
          $spacing = $tab_setting['page_spacing'] . 'px';
        }
      }
      if ($tab_setting && $tab_setting['page_col'] == 1) {
        $size = '100%';
      } else {
        $size = $list['size_display'] ? $list['size_display'] . '%' : '';
      }
    ?>
      <div id="scope_list_<?= $meta_data_key ?>" class="card-sortable <?= $alignment ?>" data-fill="<?= $list['field_id'] ?>" style="width:<?= $size ?>;">
        <input type="hidden" class="event_get_dataset" name="list[<?= $rand_id ?>][dataset]" value='<?= json_encode($list, true) ?>'>
        <div class="handle">
          <?= W_Structure::getfile('_framework/modules/sub/jform/icon/drag.svg') ?>
        </div>
        <div class="body-fill">
          <?php ($type_display == 2) ? '<div class="input-reverse">' : '' ?>
          <?php if (isset($list['is_show_title']) && $list['is_show_title']) { ?>
            <div class="__field-name" style="width:<?= $spacing ?>;">
              <div class="<?= $font_bold ?>" style="color:<?= $list['color']  ?>;"> <?= $list['input_title'] . ' ' . $is_required ?></div>
            </div>
          <?php } ?>
          <div class="__field-input">
            <?php
            $attr = [
              'placeholder' => isset($list['is_show_placeholder']) ? $list['placeholder_text'] : '',
              'disabled' => true
            ];
            $options = [
              'list' => []
            ];
            if (isset($list['option']) &&  $list['option']) {
              foreach ($list['option'] as $key_option => $list_option) {
                if (isset($list_option['is_open']) && $list_option['is_open'] == 1) {
                  $options['list'][$key_option]['value'] = $list_option['name'];
                  $options['list'][$key_option]['name'] = $list_option['name'];
                }
              }
            }
            TiwForm::normal('select-tag', '', $attr, $options);
            ?>
          </div>
          <?php ($type_display == 2) ? '</div>' : '' ?>
          <div class="__field-action">
            <button class="btn-action __btn--delete js-remove"></button>
            <button type="button" class="btn-action __btn--edit js-edit" onclick="editFill('scope_list_<?= $meta_data_key ?>','<?= $meta_data_key ?>')"></button>
          </div>
        </div>
      </div>
    <?php
    } else if ($type == 'Varchar' && $category == 'Dropdown') {
      $is_required = isset($list['is_required_fill']) && $list['is_required_fill'] == 1 ? '<span class="text-danger">*</span> ' : '';
      $spacing = '';
      $size = '';
      if ($tab_setting) {
        if ($tab_setting['page_spacing']) {
          $spacing = $tab_setting['page_spacing'] . 'px';
        }
      }
      if ($tab_setting && $tab_setting['page_col'] == 1) {
        $size = '100%';
      } else {
        $size = $list['size_display'] ? $list['size_display'] . '%' : '';
      }
    ?>
      <div id="scope_list_<?= $meta_data_key ?>" class="card-sortable <?= $alignment ?>" data-fill="<?= $list['field_id'] ?>" style="width:<?= $size ?>;">
        <input type="hidden" class="event_get_dataset" name="list[<?= $rand_id ?>][dataset]" value='<?= json_encode($list, true) ?>'>
        <div class="handle">
          <?= W_Structure::getfile('_framework/modules/sub/jform/icon/drag.svg') ?>
        </div>
        <div class="body-fill">
          <?php ($type_display == 2) ? '<div class="input-reverse">' : '' ?>
          <?php if (isset($list['is_show_title']) && $list['is_show_title']) { ?>
            <div class="__field-name" style="width:<?= $spacing ?>;">
              <div class="<?= $font_bold ?>" style="color:<?= $list['color']  ?>;"> <?= $list['input_title'] . ' ' . $is_required ?></div>
            </div>
          <?php } ?>
          <div class="__field-input">
            <?php
            $attr = [
              'placeholder' => isset($list['is_show_placeholder']) ? $list['placeholder_text'] : '',
              'disabled' => true
            ];
            $options = [
              'list' => []
            ];
            if (isset($list['option']) &&  $list['option']) {
              foreach ($list['option'] as $key_option => $list_option) {
                if (isset($list_option['is_open']) && $list_option['is_open'] == 1) {
                  $options['list'][$key_option]['value'] = $list_option['name'];
                  $options['list'][$key_option]['name'] = $list_option['name'];
                }
              }
            }
            TiwForm::normal('select', '', $attr, $options);
            ?>
          </div>
          <?php ($type_display == 2) ? '</div>' : '' ?>
          <div class="__field-action">
            <button class="btn-action __btn--delete js-remove"></button>
            <button type="button" class="btn-action __btn--edit js-edit" onclick="editFill('scope_list_<?= $meta_data_key ?>','<?= $meta_data_key ?>')"></button>
          </div>
        </div>
      </div>
    <?php
    } else if ($type == 'Varchar' && $category == 'Radio') {
      $is_required = isset($list['is_required_fill']) && $list['is_required_fill'] == 1 ? '<span class="text-danger">*</span> ' : '';
      $alignment  =  '';
      if ($list['alignment'] == 'left') {
        $alignment = 'me-auto';
      } else if ($list['alignment'] == 'right') {
        $alignment = 'ms-auto';
      }
      $spacing = '';
      $size = '';
      if ($tab_setting) {
        if ($tab_setting['page_spacing']) {
          $spacing = $tab_setting['page_spacing'] . 'px';
        }
      }
      if ($tab_setting && $tab_setting['page_col'] == 1) {
        $size = '100%';
      } else {
        $size = $list['size_display'] ? $list['size_display'] . '%' : '';
      }
    ?>
      <div id="scope_list_<?= $meta_data_key ?>" class="card-sortable <?= $alignment ?>" data-fill="<?= $list['field_id'] ?>" style="width:<?= $size ?>;">
        <input type="hidden" class="event_get_dataset" name="list[<?= $rand_id ?>][dataset]" value='<?= json_encode($list, true) ?>'>
        <div class="handle">
          <?= W_Structure::getfile('_framework/modules/sub/jform/icon/drag.svg') ?>
        </div>
        <div class="body-fill align-items-start">
          <?php ($type_display == 2) ? '<div class="input-reverse">' : '' ?>
          <?php if (isset($list['is_show_title']) && $list['is_show_title']) { ?>
            <div class="__field-name" style="width:<?= $spacing ?>;">
              <div class="<?= $font_bold ?>" style="color:<?= $list['color']  ?>;"> <?= $list['input_title'] . ' ' . $is_required ?></div>
            </div>
          <?php } ?>
          <div class="__field-input <?= $list['format_display'] == 'lefttoright' ? 'd-flex' : '' ?>">
            <?php
            if ($list['option']) {
              foreach ($list['option'] as $key_option => $list_option) {
                if (isset($list_option['is_open']) && $list_option['is_open'] == 1) {
                  $attr_radio = [
                    'name' => '',
                    'checked' => $list['select_first_checkbox'] == $list_option['name'] ? true : false,
                    'disabled' => true,
                  ];
                  $options_radio = [
                    'value' => $list_option['name'],
                    'label' => $list_option['name'],
                    'style' => '2'
                  ];
                  if (isset($list_option['is_other']) && $list_option['is_other'] == 1) {
                    $attr_radio['main_class'] = 'mr-10px';
                    echo '<div class="d-flex align-items-center mb-10px">';
                    TiwForm::normal('radio', '',  $attr_radio, $options_radio);
                    TiwForm::normal('text', '', ['name' => '', 'class' => 'max-w-150px']);
                    echo '</div>';
                  } else {
                    $attr_radio['main_class'] = 'mb-10px mr-10px';
                    TiwForm::normal('radio', '',  $attr_radio, $options_radio);
                  }
                }
              }
            }
            ?>
          </div>
          <?php ($type_display == 2) ? '</div>' : '' ?>
          <div class="__field-action">
            <button class="btn-action __btn--delete js-remove"></button>
            <button type="button" class="btn-action __btn--edit js-edit" onclick="editFill('scope_list_<?= $meta_data_key ?>','<?= $meta_data_key ?>')"></button>
          </div>
        </div>
      </div>
    <?php
    } else if ($type == 'Varchar' && $list['category'] == 'Checkbox') {
      $is_required = isset($list['is_required_fill']) && $list['is_required_fill'] == 1 ? '<span class="text-danger">*</span> ' : '';
      $alignment  =  '';
      if ($list['alignment'] == 'left') {
        $alignment = 'me-auto';
      } else if ($list['alignment'] == 'right') {
        $alignment = 'ms-auto';
      }
      $spacing = '';
      $size = '';
      if ($tab_setting) {
        if ($tab_setting['page_spacing']) {
          $spacing = $tab_setting['page_spacing'] . 'px';
        }
      }
      if ($tab_setting && $tab_setting['page_col'] == 1) {
        $size = '100%';
      } else {
        $size = $list['size_display'] ? $list['size_display'] . '%' : '';
      }
    ?>
      <div id="scope_list_<?= $meta_data_key ?>" class="card-sortable <?= $alignment ?>" data-fill="<?= $list['field_id'] ?>" style="width:<?= $size ?>;">
        <input type="hidden" class="event_get_dataset" name="list[<?= $rand_id ?>][dataset]" value='<?= json_encode($list, true) ?>'>
        <div class="handle">
          <?= W_Structure::getfile('_framework/modules/sub/jform/icon/drag.svg') ?>
        </div>
        <div class="body-fill align-items-start">
          <?php ($list['type_display'] == 2) ? '<div class="input-reverse">' : '' ?>
          <?php if (isset($list['is_show_title']) && $list['is_show_title']) { ?>
            <div class="__field-name" style="width:<?= $spacing ?>;">
              <div class="<?= $font_bold ?>" style="color:<?= $list['color']  ?>;"> <?= $list['input_title'] . ' ' . $is_required ?></div>
            </div>
          <?php } ?>
          <div class="__field-input <?= $list['format_display'] == 'lefttoright' ? 'd-flex' : '' ?>">
            <?php
            if ($list['option']) {
              foreach ($list['option'] as $key_option => $list_option) {
                if (isset($list_option['is_open']) && $list_option['is_open'] == 1) {
                  $attr_checkbox = [
                    'name' => '',
                    'disabled' => true,
                  ];
                  $options_checkbox = [
                    'value' => $list_option['name'],
                    'label' => $list_option['name'],
                    'style' => '3'
                  ];
                  if (isset($list_option['is_other']) && $list_option['is_other'] == 1) {
                    $attr_checkbox['main_class'] = 'mr-10px';
                    echo '<div class="d-flex align-items-center mb-10px">';
                    TiwForm::normal('checkbox', '',  $attr_checkbox, $options_checkbox);
                    TiwForm::normal('text', '', ['name' => '', 'class' => 'max-w-150px']);
                    echo '</div>';
                  } else {
                    $attr_checkbox['main_class'] = 'mb-10px mr-10px';
                    TiwForm::normal('checkbox', '',  $attr_checkbox, $options_checkbox);
                  }
                }
              }
            }
            ?>
          </div>
          <?php ($list['type_display'] == 2) ? '</div>' : '' ?>
          <div class="__field-action">
            <button class="btn-action __btn--delete js-remove"></button>
            <button type="button" class="btn-action __btn--edit js-edit" onclick="editFill('scope_list_<?= $meta_data_key ?>','<?= $meta_data_key ?>')"></button>
          </div>
        </div>
      </div>
    <?php
    } else if ($type == 'Varchar' && $list['category'] == 'Switch') {
      $is_required = isset($list['is_required_fill']) && $list['is_required_fill'] == 1 ? '<span class="text-danger">*</span> ' : '';
      $alignment  =  '';
      if ($list['alignment'] == 'left') {
        $alignment = 'me-auto';
      } else if ($list['alignment'] == 'right') {
        $alignment = 'ms-auto';
      }
      $spacing = '';
      $size = '';
      if ($tab_setting) {
        if ($tab_setting['page_spacing']) {
          $spacing = $tab_setting['page_spacing'] . 'px';
        }
      }
      if ($tab_setting && $tab_setting['page_col'] == 1) {
        $size = '100%';
      } else {
        $size = $list['size_display'] ? $list['size_display'] . '%' : '';
      }
    ?>
      <div id="scope_list_<?= $meta_data_key ?>" class="card-sortable <?= $alignment ?>" data-fill="<?= $list['field_id'] ?>" style="width:<?= $size ?>;">
        <input type="hidden" class="event_get_dataset" name="list[<?= $rand_id ?>][dataset]" value='<?= json_encode($list, true) ?>'>
        <div class="handle">
          <?= W_Structure::getfile('_framework/modules/sub/jform/icon/drag.svg') ?>
        </div>
        <div class="body-fill">
          <?= ($list['type_display'] == 2) ? '<div class="input-reverse">' : '' ?>
          <?php if (isset($list['is_show_title']) && $list['is_show_title']) { ?>
            <div class="__field-name" style="width:<?= $spacing ?>;">
              <div class="<?= $font_bold ?>" style="color:<?= $list['color']  ?>;"> <?= $list['input_title'] . ' ' . $is_required ?></div>
            </div>
          <?php } ?>
          <div class="__field-input  <?= ($list['type_display'] == 2) ? 'mt-10px' : '' ?>">
            <?php
            $attr_switch = [
              'name' => '',
              'disabled' => true,
            ];
            $options_switch = [
              'value' => 1,
              'label' => $list['true_text'],
              'style' => '1',
              'is_on_off' => true
            ];
            TiwForm::normal('checkbox', '',  $attr_switch, $options_switch);
            ?>
          </div>
          <?= ($list['type_display'] == 2) ? '</div>' : '' ?>
          <div class=" __field-action">
            <button class="btn-action __btn--delete js-remove"></button>
            <button type="button" class="btn-action __btn--edit js-edit" onclick="editFill('scope_list_<?= $meta_data_key ?>','<?= $meta_data_key ?>')"></button>
          </div>
        </div>
      </div>
    <?php
    } else {
      $is_required = isset($list['is_required_fill']) && $list['is_required_fill'] == 1 ? '<span class="text-danger">*</span> ' : '';
      $spacing = '';
      $size = '';
      if ($tab_setting) {
        if ($tab_setting['page_spacing']) {
          $spacing = $tab_setting['page_spacing'] . 'px';
        }
      }
      if ($tab_setting && $tab_setting['page_col'] == 1) {
        $size = '100%';
      } else {
        $size = $list['size_display'] ? $list['size_display'] . '%' : '';
      }
      $input = '';
    ?>
      <?php
      $option = [
        'is_return' => true,
      ];
      if ($type == 'Int' || $type == 'Decimal' || $type == 'Bigint' || $type == 'Tinyint') {
        $attr = [
          'min' => $list['min'],
          'max' => $list['max'],
          'name' => $list['db_column_name'],
          'style' => 'width:' . $list['size_input'] . 'px;color:' .  $list['color_inner']  . ';',
          'placeholder' => isset($list['is_show_placeholder']) ? $list['placeholder_text'] : '',
          'readonly' => true
        ];

        $input = TiwForm::normal('number', '',  $attr, $option);
      } else if ($type == 'DateTime') {
        $size_input_date = isset($list['size_input_date']) && $list['size_input_date'] ? $list['size_input_date'] : '';
        $input = '<div class="d-flex">';
        $input .= '<div style="width:' . $size_input_date . 'px">';
        $input .=  TiwForm::normal('datetime', '', ['name' => '', 'readonly' => 'true', 'disabled' => 'true'], $option);
        $input .= '</div>';
        $input .= '</div>';
      } else if ($type == 'Time') {
        $size_input_time = isset($list['size_input_time']) && $list['size_input_time'] ? $list['size_input_time'] : '';
        $input = '<div class="d-flex">';
        $input .= '<div style="width:' . $size_input_time . 'px">';
        $input .=  TiwForm::normal('time', '', ['name' => '', 'readonly' => 'true', 'disabled' => 'true'], $option);
        $input .= '</div>';
        $input .= '</div>';
      } else if ($type == 'Date') {
        $size_input_date = isset($list['size_input_date']) && $list['size_input_date'] ? $list['size_input_date'] : '';
        $input = '<div class="d-flex">';
        $input .= '<div style="width:' . $size_input_date . 'px">';
        $input .=  TiwForm::normal('date', '', ['name' => '', 'readonly' => 'true', 'disabled' => 'true'], $option);
        $input .= '</div>';
        $input .= '</div>';
      } else if ($type == 'Upload') {
        $size_input = isset($list['size_input']) && $list['size_input'] ? $list['size_input'] : '';
        $input = '<div style="width:' . $size_input . 'px">';
        $input .=  TiwForm::normal('file', '', ['disabled' => 'true'], $option);
        $input .= '</div>';
        if (isset($list['is_show_advice']) && $list['is_show_advice'] == 1) {
          $input .= '<div class="font-15px">' . $list['advice'] . '</div>';
        }
      } else {
        if (isset($list['type_input_text']) && $list['type_input_text'] == 'text') {
          $attr = [
            'style' => 'width:' . $list['size_input'] . '%;color:' .   $list['color_inner']  . ';',
            'placeholder' => isset($list['is_show_placeholder']) ? (isset($list['placeholder_text']) ? $list['placeholder_text'] : '-') : '',
            'readonly' => true
          ];

          $input = TiwForm::normal('text', '',  $attr, $option);
        } else {
          $size_input = isset($list['size_input']) && $list['size_input'] ? $list['size_input'] : '';
          $attr = [
            'style' => 'width:' . $size_input . '%;color:' .   $list['color_inner']  . ';',
            'placeholder' => isset($list['is_show_placeholder']) ? $list['placeholder_text'] : '',
            'readonly' => true
          ];

          $input = TiwForm::normal('textarea', '',  $attr, $option);
        }
      }
      ?>
      <div id="scope_list_<?= $meta_data_key ?>" class="card-sortable <?= $alignment ?>" data-fill="<?= $list['field_id'] ?>" style="width:<?= $size ?>;">
        <input type="hidden" class="event_get_dataset" name="list[<?= $rand_id ?>][dataset]" value='<?= json_encode($list, true) ?>'>
        <div class="handle">
          <?= W_Structure::getfile('_framework/modules/sub/jform/icon/drag.svg') ?>
        </div>
        <div class="body-fill">
          <?php ($type_display == 2) ? '<div class="input-reverse">' : '' ?>
          <?php if (isset($list['is_show_title']) && $list['is_show_title']) { ?>
            <div class="__field-name" style="width:<?= $spacing ?>;">
              <div class="<?= $font_bold ?>" style="color:<?= $list['color']  ?>;"> <?= $list['input_title'] . ' ' . $is_required ?></div>
            </div>
          <?php } ?>
          <div class="__field-input"> <?= $input ?> </div>
          <?php ($type_display == 2) ? '</div>' : '' ?>
          <div class="__field-action">
            <button class="btn-action __btn--delete js-remove"></button>
            <button type="button" class="btn-action __btn--edit js-edit" onclick="editFill('scope_list_<?= $meta_data_key ?>','<?= $meta_data_key ?>')"></button>
          </div>
        </div>
      </div>
    <?php } ?>
<?php }
} ?>