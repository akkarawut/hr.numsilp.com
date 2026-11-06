<?php
class TiwForm
{
  private static $is_scan_modal = false;

  /**
  | Title | เรียกใช้ Auto form ในพื้นที่นั้น ๆ
  | Description | เมื่อเรียกใช้ register data แล้วจะทำการนำข้อมูลไปใส่ให้อัตโนมัติ
   */
  public static function autoForm($data = [])
  {
    echo 'auto-form="' . base64_encode(json_encode($data)) . '"';
  }

  /**
  | Title | random string and number
   */
  public static function randomName($length = 5)
  {
    $characters = 'abcdefghijklmnopqrstuvwxyz0123456789';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
      $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return '__' . $randomString;
  }

  /**
  | Title | Checkbox Type 1
  | Description | 
   */
  public static function checkboxRadio($style, $type, $class, $name, $value, $options = [])
  {
    $all_class = '';
    if (is_array($class)) {
      foreach ($class as $a_class) {
        $all_class .= ' ' . $a_class;
      }
    } else {
      $all_class = $class;
    }

    $is_return = (isset($options['is_return'])) ? $options['is_return'] : 0;
    unset($options['is_return']);

    if ($style == 1) {
      $data = [
        'name' => $name,
        'class' => $all_class,
      ];
      if (isset($options['checked']) && $options['checked']) $options['checked'] = true;
      unset($options['checked']);
      $html = self::checkbox1($type, $value, $data, $options);
    } else if ($style == 2) {
      $data = [
        'name' => $name,
        'class' => $all_class,
      ];
      if (isset($options['checked']) && $options['checked']) $options['checked'] = true;
      unset($options['checked']);
      $html = self::checkbox2($type, $value, $data, $options);
    } else if ($style == 3) {
      $data = [
        'name' => $name,
        'class' => $all_class,
      ];
      if (isset($options['checked']) && $options['checked']) $options['checked'] = true;
      unset($options['checked']);
      $html = self::checkbox3($type, $value, $data, $options);
    }
    if ($is_return) {
      return $html;
    } else {
      echo $html;
    }
  }
  private static function checkbox1($type, $value, $data, $options)
  {
    $for_id = isset($options['for']) ? $options['for'] : self::randomName(10);
    $label = isset($options['label']) ? $options['label'] : '';
    $text_on = isset($options['text_on']) ? $options['text_on'] : 'ON';
    $text_off = isset($options['text_off']) ? $options['text_off'] : 'OFF';
    $checked = (isset($data['checked']) && $data['checked']) ? 'checked' : '';

    $class = isset($data['class']) ? $data['class'] : '';
    $main_class = isset($data['main_class']) ? $data['main_class'] : '';
    $name = isset($data['name']) ? $data['name'] : '';

    unset($data['checked']);
    unset($data['class']);
    unset($data['name']);

    $all_attr = '';
    foreach ($data as $key => $attr) {
      $all_attr .= ' ' . $key . '="' . $attr . '"';
    }

    $on_off_html = '';
    if (isset($options['is_on_off']) && $options['is_on_off']) {
      $on_off_html = '
        <label class="on" for="' . $for_id . '">' . $text_on . '</label>
        <label class="off" for="' . $for_id . '">' . $text_off . '</label>
        ';
    }
    $html = '<div class="checkbox-slide ' . $class . '">
              <input id="' . $for_id . '" name="' . $name . '" type="' . $type . '" value="' . $value . '" ' . $checked . $all_attr . '>
              <label class="for_style mb-0" for="' . $for_id . '">
                ' . $on_off_html . '
              </label>
            </div>';

    if ($label) {
      $html = '<div class="scope_box_event d-flex align-items-center ' . $main_class . '">
                ' . $html . '
                <label for="' . $for_id . '" class="label_text ml-5px mb-0 cursor-pointer">' . $label . '</label>
              </div>';
    }

    return $html;
  }
  private static function checkbox2($type, $value, $data, $options)
  {
    $for_id = isset($options['for']) ? $options['for'] : self::randomName(10);
    $label = isset($options['label']) ? $options['label'] : '';
    $checked = (isset($data['checked']) && $data['checked']) ? 'checked' : '';

    $class = isset($data['class']) ? $data['class'] : '';
    $main_class = isset($data['main_class']) ? $data['main_class'] : '';
    $name = isset($data['name']) ? $data['name'] : '';

    unset($data['checked']);
    unset($data['class']);
    unset($data['name']);

    $all_attr = '';
    foreach ($data as $key => $attr) {
      $all_attr .= ' ' . $key . '="' . $attr . '"';
    }

    $html = '<div class="checkbox-sun ' . $class . '">
              <input type="' . $type . '" id="' . $for_id . '" name="' . $name . '" value="' . $value . '" ' . $checked . $all_attr . '/>
              <label class="mb-0" for="' . $for_id . '"></label>
            </div>';

    if ($label) {
      $html = '<div class="scope_box_event d-flex align-items-center ' . $main_class . '">
                ' . $html . '
                <label for="' . $for_id . '" class="label_text ml-5px mb-0 cursor-pointer">' . $label . '</label>
              </div>';
    }
    return $html;
  }
  private static function checkbox3($type, $value, $data, $options)
  {
    $for_id = isset($options['for']) ? $options['for'] : self::randomName(10);
    $label = isset($options['label']) ? $options['label'] : '';
    $checked = (isset($data['checked']) && $data['checked']) ? 'checked' : '';

    $class = isset($data['class']) ? $data['class'] : '';
    $main_class = isset($data['main_class']) ? $data['main_class'] : '';
    $name = isset($data['name']) ? $data['name'] : '';

    unset($data['checked']);
    unset($data['class']);
    unset($data['name']);

    $all_attr = '';
    foreach ($data as $key => $attr) {
      $all_attr .= ' ' . $key . '="' . $attr . '"';
    }

    $html = '<div class="checkbox-group ' . $class . '">
              <input type="' . $type . '" name="' . $name . '" value="' . $value . '" id="' . $for_id . '" ' . $checked . $all_attr . '>
              <label class="mb-0" for="' . $for_id . '"></label>
            </div>';
    if ($label) {
      $html = '<div class="scope_box_event d-flex align-items-center ' . $main_class . '">
              ' . $html . '
              <label for="' . $for_id . '" class="label_text ml-5px mb-0 cursor-pointer">' . $label . '</label>
            </div>';
    }
    return $html;
  }

  /**
  | Title | Form
  | Description | สร้างฟอร์มที่กดแก้ไขแล้วเปลี่ยนเป็น form ให้และเวลากดบันทึกจะ ajax ไป save ให้ด้วยไม่รีเฟรชหน้า
   */
  public static function liveForm($type, $name, $value, $api, $options = [])
  {
    $main_class = isset($options['main_class']) ? $options['main_class'] : '';
    $class = isset($options['class']) ? $options['class'] : '';
    $detail = isset($options['detail']) ? $options['detail'] : $value;
    $placeholder = isset($options['placeholder']) ? $options['placeholder'] : '';

    if ($type == 'checkbox' || $type == 'radio') {
      $label = isset($options['label']) ? $options['label'] : '';
      $is_on_off = (isset($options['is_on_off']) && $options['is_on_off']) ? true : false;
      $checked = $value ? true : false;
      $required = (isset($options['required']) && $options['required']) ? true : false;
      $style = isset($options['style']) ? $options['style'] : 1;
      $attrs = isset($options['attr']) ? $options['attr'] : [];

      echo '<div class="live-form ' . $main_class . '" data-register="' . base64_encode(json_encode($api)) . '">';

      $data = [
        'name' => $name,
        'class' => 'live_radio_event ' . $class,
        'checked' => $checked,
        'required' => $required,
      ];

      foreach ($attrs as $key => $value) {
        $data[$key] = $value;
      }

      TiwForm::normal($type, 1, $data, ['style' => $style, 'label' => $label, 'is_on_off' => $is_on_off]);

      echo  '</div>';
    } else {
      $is_currency = '';
      $currency = '';
      if (isset($options['currency'])) {
        $is_currency = 'is_currency';
        $currency = 'data-currency="' . $options['currency'] . '"';
      }

      $form_html = '';
      if ($type == 'text' || $type == 'email' || $type == 'date' || $type == 'time' || $type == 'datetime' || $type == 'month' || $type == 'week') { //input:text
        $class .= ' form-text';
        if ($type == 'time' || $type == 'date') {
          $class .= ' form-date ' . $type;
        } else if ($type == 'datetime') {
          $class .= ' form-date ' . $type;
          $type = 'datetime-local';
        }
        $form_html = '<input type="' . $type . '" name="' . $name . '" class="live_form_event ' . $class . '" value="' . $value . '" placeholder="' . $placeholder . '" data-pre="' . $value . '">';
      } else if ($type == 'textarea') { //textarea
        $form_html = '<textarea name="' . $name . '" class="form form-controlarea live_form_event ' . $class . '" placeholder="' . $placeholder . '" data-pre="' . $value . '">' . $value . '</textarea>';
      } else if ($type == 'number') { //input:number
        $form_html = '<input type="number" name="' . $name . '" class="form form-number live_form_event" placeholder="' . $placeholder . '" value="' . $value . '" data-pre="' . $value . '">';
      } else if ($type == 'select') { //select option
        $form_html = '<select name="' . $name . '" class="form form-select live_form_event ' . $class . '" data-pre="' . $value . '">';
        if ($options) {
          foreach ($options['list'] as $option) {
            $selected = '';
            if ($value == $option['value']) {
              $selected = 'selected';
              $detail = $option['name'];
            }
            $disabled = (isset($option['disabled']) && $option['disabled']) ? 'disabled' : '';
            $form_html .= '<option value="' . $option['value'] . '" ' . $selected . ' ' . $disabled . '>' . $option['name'] . '</option>';
          }
        }
        $form_html .= '</select>';
      } else {
        $form_html = '<span class="text-danger">No form of this type.<span>';
        $detail = '<span class="text-danger">No form of this type.<span>';
      }

      $html = '<div class="live-form ' . $main_class . '" data-register="' . base64_encode(json_encode($api)) . '">
                <div class="live-form-body ' . $is_currency . '" ' . $currency . '">
                  <div class="form-group">
                    ' . $form_html . '
                  </div>
                  <div class="form-content live_form_db_click_event">' . $detail . '</div>
                </div>
                <div class="live-form-button">
                  <button class="btn-edit-form btn_edit_event">' . W_Structure::getfile('_framework/modules/main/tiwform/icon/edit.svg') . '</button>
                  <button class="btn-confirm-form btn_confirm_event">' . W_Structure::getfile('_framework/modules/main/tiwform/icon/check.svg') . '</button>
                </div>
              </div>';
      echo $html;
    } //end if else
  } //end function liveForm

  /**
  | Title | Form one image
  | Description | สร้างฟอร์มรูป
   */
  public static function liveImg($code = '', $bg_img = '', $options = [])
  {
    $id = self::randomName(10);
    $img = File::getPath($code);
    $is_btn = isset($options['is_btn']) ? $options['is_btn'] : 1;
    $is_view = isset($options['is_view']) ? $options['is_view'] : 1;
    $is_delete = isset($options['is_delete']) ? $options['is_delete'] : 1;
    $width = isset($options['width']) ? $options['width'] : '200px';
    $height = isset($options['height']) ? $options['height'] : '100%';
    unset($options['width']);
    unset($options['height']);
    $class = isset($options['class']) ? $options['class'] : '';
    unset($options['class']);
    $active = (strpos($img, '/placeholder/') === false) ? 'active' : '';
    $bg_html = $bg_img ? 'style="background-image: url(' . $bg_img . ');"' : '';

    $all_attr = '';
    foreach ($options as $name => $value) {
      $all_attr .= ' ' . $name . '="' . $value . '"';
    }

    echo '<div class="live-once-img-area ' . $class . ' ' . $active . '" style="max-width: ' . $width . ';">
            <div class="live-once-img-group" style="padding-top:' . $height . ';"' . $all_attr . '>
              <form enctype="multipart/form-data" id="' . $id . '">
                <input type="hidden" name="code" value="' . $code . '">
                <input type="file" name="once_img" class="once_img_event">
              </form>
              <div class="img live_img_event">
                <div class="bg-defult" ' . $bg_html . '></div>';
    if ($is_btn == 1) {
      echo '<div class="button-group">';
      if ($is_view == 1) {
        echo '<button type="button" class="open-image preview_img_event">' . file_get_contents(F_ROOT_PHP . '/_framework/modules/main/tiwform/icon/search.svg') . '</button>';
      }
      if ($is_delete == 1) {
        echo '<button type="button" class="delete delete_img_event">' . W_Structure::getfile('_framework/modules/main/tiwform/icon/bin.svg') . '</button>';
      }
      echo '</div>';
    }
    echo '<img class="preview" src="' . $img . '">
              </div>
            </div>
          </div>';
  }

  //uploaded file
  private static function uploaded_file($value, $prefix)
  {
    $html = '<div class="drag-drop-file-uploaded">';
    foreach ($value as $img) {
      $html .= '<div class="drag-drop-file-uploaded-list __' . $img['code'] . '">
                        <div class="file-uploaded-group">
                          ' . W_Structure::getfile('_framework/modules/main/tiwform/icon/file.svg') . '
                          <a href="' . $img['link'] . '" target="_blank">
                            <div class="uploaded-list-name">' . $img['file_name'] . '.' . $img['extension'] . '</div>
                          </a>
                        </div>
                        <div class="d-flex align-items-center">
                          <a href="' . $img['link'] . '" target="_blank" download class="d-flex mr-10px">
                          ' . W_Structure::getfile('_framework/modules/main/tiwform/icon/download.svg') . '
                          </a>
                          <div class="btn-delete-uploaded-file" toggle-edit-modal="#__delete_uploaded_modal" data-register="' . base64_encode(json_encode($img)) . '">
                          ' . W_Structure::getfile('_framework/modules/main/tiwform/icon/bin.svg') . '
                          </div>
                        </div>
                      </div>';
    }
    $html .= '</div>';

    //modal confirm delete file
    $html .= '<div class="modal render-modal fade show" id="__delete_uploaded_modal" data-bs-backdrop="static" aria-modal="true" role="dialog"><div class="modal-dialog modal-sm" role="document"><div class="modal-content"><button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button><div class="modal-body"><h3 class="text-center font-16px font-SemiBold text-uppercase">DELETE FILE</h3><p class="mb-0 text-center">Are you sure to delete <span class="text-danger text-uppercase"> “FILE” </span> form this system.</p></div><div class="modal-footer d-flex justify-content-between"><button class="form-btn btn-light min-w-100px" type="button" data-bs-dismiss="modal" value="">Maybe</button><button class="form-btn btn-danger min-w-120px __btn_delete_file_uploaded" type="button" value="">Yes!! I’m Sure</button></div></div></div></div>';

    return $html;
  }

  /**
  | Title | Form one image
  | Description | สร้างฟอร์มรูป
   */
  public static function normal($type = 'text', $value = '', $data = [], $options = [])
  {
    $main_class = isset($options['main_class']) ? $options['main_class'] : '';
    $class = '';
    if (isset($data['class'])) {
      $class = $data['class'];
      unset($data['class']);
    }
    if ($type == 'number' && !isset($data['step'])) {
      $data['step'] = 'any';
    }
    $is_return = isset($options['is_return']) ? 1 : 0;

    if (!isset($options['is_edit'])) {
      $is_edit = true;
    } else {
      $is_edit = $options['is_edit'] ? true : false;
    }

    $all_attr = '';
    foreach ($data as $key => $attr) {
      $all_attr .= ' ' . $key . '="' . $attr . '"';
    }

    $prefix = isset($options['prefix']) ? $options['prefix'] : '../../../';
    $modal_prefix = isset($options['modal_prefix']) ? $options['modal_prefix'] : '';

    $html = '';
    if ($is_edit) {
      if ($type == 'text' || $type == 'email' || $type == 'password' || $type == 'number' || $type == 'hidden' || $type == 'tel' || $type == 'url') {
        $html = '<input type="' . $type . '" value="' . $value . '" class="form-text ' . $class . '"' . $all_attr . '>';
      } else if ($type == 'date' || $type == 'time' || $type == 'month' || $type == 'week') {
        $html = '<input type="' . $type . '" value="' . $value . '" class="form-date ' . $type . ' ' . $class . '"' . $all_attr . '>';
      } else if ($type == 'datetime') {
        $html = '<input type="datetime-local" value="' . $value . '" class="form-date datetime ' . $class . '"' . $all_attr . '>';
      } else if ($type == 'daterange') {
        $random_id = self::randomName(10);
        $id = isset($data['id']) ? $data['id'] : $random_id;
        $html = '<fieldset class="form-date-range ' . $main_class . '">
                <input type="text" id="' . $id . '" ' . $all_attr . ' class="' . $class . '" value="' . $value . '">
                <div class="icon"></div>
              </fieldset>';
      } else if ($type == 'textarea') {
        $html = '<textarea class="form-text ' . $class . '"' . $all_attr . '>' . $value . '</textarea>';
      } else if ($type == 'select') {
        $options_list = isset($options['list_encode']) ? json_decode($options['list_encode'], true) : $options['list'];

        if (isset($options['is_search']) && $options['is_search']) {
          unset($options['is_search']);
          $html_list = '';
          $is_select = 0;
          $id = self::randomName(10);
          $name = isset($data['name']) ? $data['name'] : '';
          if (isset($options_list) && $options_list) {
            foreach ($options_list as $list) {
              $list_attr = '';
              if (isset($list['options'])) {
                foreach ($list['options'] as $key => $option) {
                  $list_attr .= ' ' . $key . '="' . $option . '"';
                }
              }

              $selected_class = '';
              if ($value == $list['value']) {
                $html_select = '<div class="name">' . $list['name'] . '</div>';
                $is_select = 1;
                $selected_class = 'selected';
              } else if ($is_select == 0) {
                $html_select = isset($data['placeholder']) ? '<div class="select-placeholder">' . $data['placeholder'] . '</div>' : '';
              }

              $html_list .= '<div class="dropdown-item select_img_event select_list_' . $list['value'] . ' ' . $selected_class . '" data-id="' . $list['value'] . '" ' . $list_attr . '>
                            <div class="name">' . $list['name'] . '</div>
                          </div>';
            }
          }
          if (!isset($html_select)) {
            $html_select = isset($data['placeholder']) ? '<div class="select-placeholder">' . $data['placeholder'] . '</div>' : '';
          }

          $html = '<div class="form-select-img ' . $class . '">
                  <div class="select-value-area"><input type="text" name="' . $name . '" value="' . $value . '" ' . $all_attr . ' select_value></div>
                  <div class="dropdown-toggle __dropdown_search_event" id="' . $id . '" data-bs-toggle="dropdown" aria-expanded="false">
                    <div class="select-img-group">
                      ' . $html_select . '
                    </div>
                    <div class="arrow">' . W_Structure::getfile('_framework/modules/main/tiwform/icon/arrow-down.svg') . '</div>
                  </div>
                  <div class="dropdown-menu" aria-labelledby="' . $id . '">
                    <div class="dropdown-search">
                      <input type="text" class="search_event" placeholder="Filter Data">
                    </div>
                    <div class="no-result">No results found</div>
                    <div class="select-group">
                      ' . $html_list . '
                    </div>
                  </div>
                </div>';
        } else {
          $html = '<select class="form-select ' . $class . '"' . $all_attr . ' >';
          if (isset($options_list) && $options_list) {
            foreach ($options_list as $key => $option) {
              $selected = ($option['value'] == $value) ? ' selected' : '';
              $name = isset($option['name']) ? $option['name'] : '';
              unset($option['name']);
              $option_attr = '';
              if (isset($option['options'])) {
                foreach ($option['options'] as $key_attr => $attr) {
                  $option_attr .= ' ' . $key_attr . '="' . $attr . '"';
                }
              }
              $html .= '<option value="' . $option['value'] . '" ' . $option_attr . $selected . '>' . $name . '</option>';
            }
          }
          $html .= '</select>';
        }
      } else if ($type == 'select-ajax') {
        $html_list = '';
        $id = self::randomName(10);
        $name = isset($data['name']) ? $data['name'] : '';

        if (isset($value[0]) && isset($value[0])) {
          $select_value = $value[0];
          $html_select = '<div class="name">' . $value[1] . '</div>';
        } else {
          $select_value = '';
          $html_select = isset($data['placeholder']) ? '<div class="select-placeholder">' . $data['placeholder'] . '</div>' : '';
        }

        $html = '<div class="form-select-img ' . $class . '" data-options="' . base64_encode(json_encode($options)) . '">
                  <div class="select-value-area"><input type="text" name="' . $name . '" value="' . $select_value . '" ' . $all_attr . ' select_value></div>
                  <div class="dropdown-toggle __dropdown_search_event" id="' . $id . '" data-bs-toggle="dropdown" aria-expanded="false">
                    <div class="select-img-group">
                      ' . $html_select . '
                    </div>
                    <div class="arrow">' . W_Structure::getfile('_framework/modules/main/tiwform/icon/arrow-down.svg') . '</div>
                  </div>
                  <div class="dropdown-menu" aria-labelledby="' . $id . '">
                    <div class="dropdown-search">
                      <input type="text" class="__search_for_ajax" placeholder="Please enter 1 or more characters">
                    </div>
                    <div class="select-group">
                      ' . $html_list . '
                    </div>
                  </div>
                </div>';
      } else if ($type == 'color') {
        $html = '<input type="' . $type . '" value="' . $value . '" class="form-color ' . $class . '"' . $all_attr . '>';
      } else if ($type == 'file') {
        $active = $value ? 'active' : '';

        $name = '';
        if (filter_var($value, FILTER_VALIDATE_URL) !== false) {
          $name = parse_url($value, PHP_URL_PATH);
          $name = basename($name);
        }

        $name = (isset($options['text']) && $options['text']) ? $options['text'] : 'Upload';

        $is_required = (isset($data['required']) && $data['required']) ? 'required="1"' : '';
        $main_required = $is_required;
        $is_required = ($is_required && $value && strpos($value, '/placeholder/') === false) ? '' : $is_required;

        unset($data['required']);

        $all_attr = '';
        foreach ($data as $key => $attr) {
          $all_attr .= ' ' . $key . '="' . $attr . '"';
        }

        $main_class = (isset($options['main_class']) && $options['main_class']) ? $options['main_class'] : '';
        $value_html = '<a href="' . $value . '" target="_blank">' . $name . '</a>';
        $html = '<div class="form-file ' . $main_class . ' ' . $active . '" ' . $main_required . '>
                <input type="file" class="file_upload_event ' . $class . '"' . $all_attr . ' ' . $is_required . '>
                <button class="btn-upload-file">' . W_Structure::getfile('_framework/modules/main/tiwform/icon/upload-1.svg') . ' <span>' . $name . '</span></button>
                <div class="detail">
                  <div class="detail-group">
                    <div class="folder-icon">' . W_Structure::getfile('_framework/modules/main/tiwform/icon/folder.svg') . '</div>
                    <div class="file-name file_name_evnet">' . $value_html . '</div>
                  </div>
                  <button type="button" class="btn-delete-file delete_normal_file_event">' . W_Structure::getfile('_framework/modules/main/tiwform/icon/close.svg') . '</button>
                </div>
              </div>';
      } else if ($type == 'btn') {
        if (isset($options['is_enter']) && $options['is_enter']) {
          $all_attr .= ' is_enter="on"';
        }

        $required_form = (isset($options['required_form']) && $options['required_form']) ? 'required_form="' . $options['required_form'] . '"' : '';

        $modal_attr = '';
        if (isset($options['modal_id'])) {
          if (isset($options['is_ajax']) && $options['is_ajax']) {
            $modal_attr = Tiwdal::register($options['modal_id'], $options['modal_data'], ['is_ajax' => true, 'is_return' => true, 'prefix' => $prefix, 'modal_prefix' => $modal_prefix]);
          } else {
            $modal_attr = Tiwdal::register($options['modal_id'], $options['modal_data'], ['is_return' => true, 'prefix' => $prefix, 'modal_prefix' => $modal_prefix]);
          }
        }

        if (isset($options['type'])) {
          $version = isset($options['version']) ? $options['version'] : '';
          if ($options['type'] == 'edit') {
            if ($version == '2') {
              $html = '<button class="form-btn-icon-edit v2 ' . $class . '"' . $all_attr . '  value="' . $value . '" ' . $modal_attr . '>' . W_Structure::getfile('_framework/modules/main/tiwform/icon/edit-v2-gray.svg') . '</button>';
            } else {
              $html = '<button class="form-btn-icon-edit ' . $class . '"' . $all_attr . '  value="' . $value . '" ' . $modal_attr . '>' . W_Structure::getfile('_framework/modules/main/tiwform/icon/edit.svg') . '</button>';
            }
          } else if ($options['type'] == 'delete') {
            if ($version == '2') {
              $html = '<button class="form-btn-icon-delete v2 ' . $class . '"' . $all_attr . '  value="' . $value . '" ' . $modal_attr . '>' . W_Structure::getfile('_framework/modules/main/tiwform/icon/delete-v2-red.svg') . '</button>';
            } else {
              $html = '<button class="form-btn-icon-delete ' . $class . '"' . $all_attr . '  value="' . $value . '" ' . $modal_attr . '>' . W_Structure::getfile('_framework/modules/main/tiwform/icon/bin.svg') . '</button>';
            }
          } else if ($options['type'] == 'icon') {
            $icon_path = (isset($options['icon_path'])) ? $options['icon_path'] : '';
            $icon_bg = (isset($options['icon_bg'])) ? ', ' . $options['icon_bg'] : '';

            $options_style = '';

            if ($icon_path) {
              $options['icon_bg'] = (isset($options['icon_bg'])) ? ' ,' . $options['icon_bg'] : '';
              $options_style = 'style="background: url(' . $icon_path . ') no-repeat center' . $icon_bg . ';"';
            }

            if (isset($options['style'])) {
              if ($options_style) {
                $options_style .= ' onpointerleave="this.setAttribute(`style`, `background: url(' . $icon_path . ') no-repeat center' . $icon_bg . '`)"';
              }

              if (isset($options['style']['hover'])) {
                $options_style .= ' onpointerover="this.setAttribute(`style`, `background: url(' . $options['style']['hover']['icon'] . ') no-repeat center, ' . $options['style']['hover']['bg'] . '`)"';
              }

              if (isset($options['style']['active'])) {
                $options_style .= ' onmousedown="this.setAttribute(`style`, `background: url(' . $options['style']['active']['icon'] . ') no-repeat center, ' . $options['style']['active']['bg'] . '`)"';
              }
            }

            if (isset($options['url']) && $options['url']) {
              $html = '<a href="' . $options['url'] . '" class="form-btn-icon ' . $class . '"' . $all_attr . ' value="' . $value . '" ' . $modal_attr . ' ' . $options_style . '></a>';
            } else {
              $html = '<button class="form-btn-icon ' . $class . '"' . $all_attr . ' ' . $required_form . ' value="' . $value . '" ' . $modal_attr . ' ' . $options_style . '></button>';
            }
          } else if ($options['type'] == 'minimize') {
            $html = '<button class="form-btn-minimize ' . $class . '"' . $all_attr . '  value="' . $value . '" ' . $modal_attr . '>' . W_Structure::getfile('_framework/modules/main/tiwform/icon/minimize.svg') . '</button>';
          } else {
            $icon_color = (isset($options['icon_class']) && $options['icon_class']) ? $options['icon_class'] : '';
            $icon_path = (isset($options['icon_path'])) ? '<div class="d-flex mr-8px ' . $icon_color . '">' . W_Structure::getfile($options['icon_path']) . '</div>' : '';
            $text = isset($options['text']) ? $options['text'] : 'SAVE';
            $html = '<button class="form-btn ' . $class . '"' . $all_attr . ' ' . $required_form . ' value="' . $value . '" ' . $modal_attr . '>' . $icon_path . $text . '</button>';
          }
        } else {
          $icon_color = (isset($options['icon_class']) && $options['icon_class']) ? $options['icon_class'] : '';
          $icon_path = (isset($options['icon_path'])) ? '<div class="d-flex mr-8px ' . $icon_color . '">' . W_Structure::getfile($options['icon_path']) . '</div>' : '';
          $text = isset($options['text']) ? $options['text'] : 'SAVE';

          if (isset($options['url']) && $options['url']) {
            $html = '<a href="' . $options['url'] . '" class="form-btn ' . $class . '"' . $all_attr . '  value="' . $value . '" ' . $modal_attr . '>' . $icon_path . $text . '</a>';
          } else {
            $html = '<button class="form-btn ' . $class . '"' . $all_attr . ' ' . $required_form . '  value="' . $value . '" ' . $modal_attr . '>' . $icon_path . $text . '</button>';
          }
        }
      } else if ($type == 'select-tag') {
        $placeholder = isset($data['placeholder']) ? $data['placeholder'] : '';
        $name = isset($data['name']) ? $data['name'] : '';
        unset($data['placeholder']);
        unset($data['name']);

        $required = (isset($data['required']) && $data['required']) ? '<input type="text" class="hidden_value_for_required" required>' : '';

        $all_attr = '';
        foreach ($data as $key => $attr) {
          $all_attr .= ' ' . $key . '="' . $attr . '"';
        }

        $scope_tag = self::randomName(10);
        $html = '<div class="form-select-tag ' . $class . '" ' . $all_attr . ' scope-tag="' . $scope_tag . '">
        ' . $required . '
        <input type="hidden" class="trigger_tag_event">
        <div class="selected-area">
          <div class="selected-template">
            <div class="remove-tag">
              ' . W_Structure::getfile('_framework/modules/main/tiwform/icon/close.svg') . '
            </div>
            <div class="name-area"></div>
            <input type="hidden" name="' . $name . '" value="" value-select>
          </div>
        </div>
        <div class="select-dropdown">
          <div class="search-tag-group">
            <input type="text" class="search-tag-event" placeholder="' . $placeholder . '">
            <div class="arrow">' . W_Structure::getfile('_framework/modules/main/tiwform/icon/arrow-down.svg') . '</div>
          </div>
          <div class="select-list-group">
            <div class="no-result hidden">No results found</div>';
        $options_list = isset($options['list_encode']) ? json_decode($options['list_encode'], true) : $options['list'];
        if (isset($options_list[0]['list'])) {
          foreach ($options_list as $lists) {
            $html .= '<div class="select-group">';
            if (isset($lists['name'])) {
              $html .= '<div class="select-title search-list">' . $lists['name'] . '</div>'; //title
            }
            foreach ($lists['list'] as $list) {
              $show_image = '';
              if (isset($list['img']) && $list['img']) {
                $show_image = '<div class="tag-image"><img src="' . $list['img'] . '"></div>';
              }
              $data_input = isset($list['input']) ? 'data-input="' . base64_encode($list['input']) . '"' : '';
              $list_class = str_replace(" ", "_", $list['value']);
              $list_class = str_replace("(", "", $list_class);
              $list_class = str_replace(")", "", $list_class);
              $list_class = str_replace(",", "", $list_class);
              $active = isset($list['selected']) ? 'active' : '';
              $html .= '<div class="select-list search-list list_' . $list_class . ' ' . $active . '" data-image="' . ((isset($list['img']) && $list['image']) ? $list['img'] : '') . '" data-value="' . $list['value'] . '" data-class="' . $list_class . '" ' . $data_input . '>' . $show_image . '<div class="name">' . $list['name'] . '</div></div>'; //list
            } //end foreach lists
            $html .= '</div>'; //end div select-group
          } //end foreach $options_list
        } else {
          $html .= '<div class="select-group">';
          foreach ($options_list as $list) {
            $show_image = '';
            $list_option = '';
            if (isset($list['options'])) {
              foreach ($list['options'] as $key => $option) {
                $list_option .= ' ' . $key . '="' . $option . '"';
              }
            }

            if (isset($list['img']) && $list['img']) {
              $show_image = '<div class="tag-image"><img src="' . $list['img'] . '"></div>';
            }
            $list_class = str_replace(" ", "_", $list['value']);
            $list_class = str_replace("(", "", $list_class);
            $list_class = str_replace(")", "", $list_class);
            $list_class = str_replace(",", "", $list_class);
            $active = isset($list['selected']) && $list['selected'] ? 'active' : '';
            $html .= '<div class="select-list search-list list_' . $list_class . ' ' . $active . '" data-image="' . ((isset($list['img']) && $list['img']) ? $list['img'] : '') . '" data-value="' . $list['value'] . '" data-class="' . $list_class . '" ' . $list_option . '>' . $show_image . '<div class="name">' . $list['name'] . '</div></div>'; //list
          } //end foreach lists
          $html .= '</div>'; //end div select-group
        }
        $html .= '</div>'; //end div select-list-group
        $html .= '</div>'; //end div select-dropdown
        $html .= '</div>'; //end div form-select-tag

        if ($value) {
          $html .= "<script scope='" . $scope_tag . "'>$(function () {w.tiwform.setTagData('div[scope-tag=" . $scope_tag . "]', JSON.parse('" . json_encode($value) . "'));});$('script[scope=" . $scope_tag . "]').remove();</>";
        }
      } else if ($type == 'select-img') {
        $html_list = '';
        $is_select = 0;
        $id = self::randomName(10);
        $name = isset($data['name']) ? $data['name'] : '';
        $options_list = isset($options['list_encode']) ? json_decode($options['list_encode'], true) : $options['list'];
        $html_select = '';
        foreach ($options_list as $list) {
          $img = $list['img'] ? '<div class="img"><img src="' . $list['img'] . '"></div>' : '';
          $html_list .= '<div class="dropdown-item select_img_event select_list_' . $list['value'] . '" data-id="' . $list['value'] . '">
                        ' . $img . '
                        <div class="name">' . $list['name'] . '</div>
                      </div>';
          if ($value == $list['value']) {
            $html_select = $img . '<div class="name">' . $list['name'] . '</div>';
            $is_select = 1;
          } else if ($is_select == 0) {
            $html_select = isset($data['placeholder']) ? '<div class="select-placeholder">' . $data['placeholder'] . '</div>' : '';
          }
        }
        if (isset($options['is_search']) && $options['is_search']) {
          $html_option = '<div class="dropdown-search">
                          <input type="text" class="search_event" placeholder="Filter Data">
                        </div>
                        <div class="no-result">No results found</div>
                        <div class="select-group">
                          ' . $html_list . '
                        </div>';
        } else {
          $html_option = $html_list;
        }
        $html = '<div class="form-select-img ' . $class . '">
                <div class="select-value-area"><input type="text" name="' . $name . '" value="' . $value . '" ' . $all_attr . ' select_value></div>
                <div class="dropdown-toggle" id="' . $id . '" data-bs-toggle="dropdown" aria-expanded="false">
                  <div class="select-img-group">
                    ' . $html_select . '
                  </div>
                  <div class="arrow">' . W_Structure::getfile('_framework/modules/main/tiwform/icon/arrow-down.svg') . '</div>
                </div>
                <div class="dropdown-menu" aria-labelledby="' . $id . '">
                  ' . $html_option . '
                </div>
              </div>';
      } else if ($type == 'select-bank') {
        $html_list = '';
        $is_select = 0;
        $id = self::randomName(10);
        $name = isset($data['name']) ? $data['name'] : '';
        $banks = Bank::selectNoKey();
        $options_list = [
          'list' => []
        ];
        foreach ($banks as $bank) {
          $options_list['list'][] = [
            'value' => $bank['abb'],
            'name' => $bank['name_en'],
            'img' => $bank['image'],
          ];
        }

        foreach ($options_list['list'] as $list) {
          $img = $list['img'] ? '<div class="img"><img src="' . $list['img'] . '"></div>' : '';
          $html_list .= '<div class="dropdown-item select_img_event select_list_' . $list['value'] . '" data-id="' . $list['value'] . '">
                        ' . $img . '
                        <div class="name">' . $list['name'] . '</div>
                      </div>';
          if ($value == $list['value']) {
            $html_select = $img . '<div class="name">' . $list['name'] . '</div>';
            $is_select = 1;
          } else if ($is_select == 0) {
            $html_select = isset($data['placeholder']) ? '<div class="select-placeholder">' . $data['placeholder'] . '</div>' : '';
          }
        }
        if (isset($options['is_search']) && $options['is_search']) {
          $html_option = '<div class="dropdown-search">
                          <input type="text" class="search_event" placeholder="Filter Data">
                        </div>
                        <div class="no-result">No results found</div>
                        <div class="select-group">
                          ' . $html_list . '
                        </div>';
        } else {
          $html_option = $html_list;
        }
        $html = '<div class="form-select-img ' . $class . '">
                <div class="select-value-area"><input type="text" name="' . $name . '" value="' . $value . '" ' . $all_attr . ' select_value></div>
                <div class="dropdown-toggle" id="' . $id . '" data-bs-toggle="dropdown">
                  <div class="select-img-group">
                    ' . $html_select . '
                  </div>
                  <div class="arrow">' . W_Structure::getfile('_framework/modules/main/tiwform/icon/arrow-down.svg') . '</div>
                </div>
                <div class="dropdown-menu" aria-labelledby="' . $id . '">
                  ' . $html_option . '
                </div>
              </div>';
      } else if ($type == 'select-color') {
        $html_list = '';
        $is_select = 0;
        $id = self::randomName(10);
        $name = isset($data['name']) ? $data['name'] : '';
        $options_list = isset($options['list_encode']) ? json_decode($options['list_encode'], true) : $options['list'];
        foreach ($options_list as $list) {
          $list_attr = '';
          if (isset($list['options'])) {
            foreach ($list['options'] as $key => $options) {
              $list_attr .= ' ' . $key . '="' . $options . '"';
            }
          }

          $list_value = isset($list['value']) ? $list['value'] : $list['color'];
          $img = $list['color'] ? '<div class="img-color" style="background-color:' . $list['color'] . ';"></div>' : '';
          $html_list .= '<div class="dropdown-item select_img_event ' . ($value == $list_value ? 'selected' : '') . ' select_list_' . str_replace('#', '', $list_value) . '" data-id="' . $list_value . '" ' . $list_attr . '>
            ' . $img . '
            <div class="name">' . $list['name'] . '</div>
            <div class="checked">' . W_Structure::getfile('_framework/modules/main/tiwform/icon/checked.svg') . '</div>
          </div>';
          if ($value == (isset($list['value']) ? $list['value'] : $list['color'])) {
            $html_select = $img . '<div class="name">' . $list['name'] . '</div>';
            $is_select = 1;
          } else if ($is_select == 0) {
            $html_select = isset($data['placeholder']) ? '<div class="select-placeholder">' . $data['placeholder'] . '</div>' : '';
          }
        }
        if (isset($options['is_search']) && $options['is_search']) {
          $html_option = '<div class="dropdown-search">
                          <input type="text" class="search_event" placeholder="Filter Data">
                        </div>
                        <div class="no-result">No results found</div>
                        <div class="select-group">
                          ' . $html_list . '
                        </div>';
        } else {
          $html_option = $html_list;
        }
        $html = '<div class="form-select-img ' . $class . '" __type="color">
                <div class="select-value-area"><input type="text" name="' . $name . '" value="' . $value . '" ' . $all_attr . ' select_value></div>
                <div class="dropdown-toggle" id="' . $id . '" data-bs-toggle="dropdown">
                  <div class="select-img-group">
                    ' . $html_select . '
                  </div>
                  <div class="arrow">' . file_get_contents($prefix . '_framework/modules/main/tiwform/icon/arrow-down.svg') . '</div>
                </div>
                <div class="dropdown-menu select-group" aria-labelledby="' . $id . '">
                  ' . $html_option . '
                </div>
              </div>';
      } else if ($type == 'select-language') {
        $languages = [
          'ab' => 'Abkhazian',
          'aa' => 'Afar',
          'af' => 'Afrikaans',
          'ak' => 'Akan',
          'sq' => 'Albanian',
          'am' => 'Amharic',
          'ar' => 'Arabic',
          'an' => 'Aragonese',
          'hy' => 'Armenian',
          'as' => 'Assamese',
          'av' => 'Avaric',
          'ae' => 'Avestan',
          'ay' => 'Aymara',
          'az' => 'Azerbaijani',
          'bm' => 'Bambara',
          'ba' => 'Bashkir',
          'eu' => 'Basque',
          'be' => 'Belarusian',
          'bn' => 'Bengali',
          'bh' => 'Bihari languages',
          'bi' => 'Bislama',
          'bs' => 'Bosnian',
          'br' => 'Breton',
          'bg' => 'Bulgarian',
          'my' => 'Burmese',
          'ca' => 'Catalan, Valencian',
          'km' => 'Central Khmer',
          'ch' => 'Chamorro',
          'ce' => 'Chechen',
          'ny' => 'Chichewa, Chewa, Nyanja',
          'zh' => 'Chinese',
          'cu' => 'Church Slavonic, Old Bulgarian, Old Church Slavonic',
          'cv' => 'Chuvash',
          'kw' => 'Cornish',
          'co' => 'Corsican',
          'cr' => 'Cree',
          'hr' => 'Croatian',
          'cs' => 'Czech',
          'da' => 'Danish',
          'dv' => 'Divehi, Dhivehi, Maldivian',
          'nl' => 'Dutch, Flemish',
          'dz' => 'Dzongkha',
          'en' => 'English',
          'eo' => 'Esperanto',
          'et' => 'Estonian',
          'ee' => 'Ewe',
          'fo' => 'Faroese',
          'fj' => 'Fijian',
          'fi' => 'Finnish',
          'fr' => 'French',
          'ff' => 'Fulah',
          'gd' => 'Gaelic, Scottish Gaelic',
          'gl' => 'Galician',
          'lg' => 'Ganda',
          'ka' => 'Georgian',
          'de' => 'German',
          'ki' => 'Gikuyu, Kikuyu',
          'el' => 'Greek (Modern)',
          'kl' => 'Greenlandic, Kalaallisut',
          'gn' => 'Guarani',
          'gu' => 'Gujarati',
          'ht' => 'Haitian, Haitian Creole',
          'ha' => 'Hausa',
          'he' => 'Hebrew',
          'hz' => 'Herero',
          'hi' => 'Hindi',
          'ho' => 'Hiri Motu',
          'hu' => 'Hungarian',
          'is' => 'Icelandic',
          'io' => 'Ido',
          'ig' => 'Igbo',
          'id' => 'Indonesian',
          'ia' => 'Interlingua (International Auxiliary Language Association)',
          'ie' => 'Interlingue',
          'iu' => 'Inuktitut',
          'ik' => 'Inupiaq',
          'ga' => 'Irish',
          'it' => 'Italian',
          'ja' => 'Japanese',
          'jv' => 'Javanese',
          'kn' => 'Kannada',
          'kr' => 'Kanuri',
          'ks' => 'Kashmiri',
          'kk' => 'Kazakh',
          'rw' => 'Kinyarwanda',
          'kv' => 'Komi',
          'kg' => 'Kongo',
          'ko' => 'Korean',
          'kj' => 'Kwanyama, Kuanyama',
          'ku' => 'Kurdish',
          'ky' => 'Kyrgyz',
          'lo' => 'Lao',
          'la' => 'Latin',
          'lv' => 'Latvian',
          'lb' => 'Letzeburgesch, Luxembourgish',
          'li' => 'Limburgish, Limburgan, Limburger',
          'ln' => 'Lingala',
          'lt' => 'Lithuanian',
          'lu' => 'Luba-Katanga',
          'mk' => 'Macedonian',
          'mg' => 'Malagasy',
          'ms' => 'Malay',
          'ml' => 'Malayalam',
          'mt' => 'Maltese',
          'gv' => 'Manx',
          'mi' => 'Maori',
          'mr' => 'Marathi',
          'mh' => 'Marshallese',
          'ro' => 'Moldovan, Moldavian, Romanian',
          'mn' => 'Mongolian',
          'na' => 'Nauru',
          'nv' => 'Navajo, Navaho',
          'nd' => 'Northern Ndebele',
          'ng' => 'Ndonga',
          'ne' => 'Nepali',
          'se' => 'Northern Sami',
          'no' => 'Norwegian',
          'nb' => 'Norwegian Bokmål',
          'nn' => 'Norwegian Nynorsk',
          'ii' => 'Nuosu, Sichuan Yi',
          'oc' => 'Occitan (post 1500)',
          'oj' => 'Ojibwa',
          'or' => 'Oriya',
          'om' => 'Oromo',
          'os' => 'Ossetian, Ossetic',
          'pi' => 'Pali',
          'pa' => 'Panjabi, Punjabi',
          'ps' => 'Pashto, Pushto',
          'fa' => 'Persian',
          'pl' => 'Polish',
          'pt' => 'Portuguese',
          'qu' => 'Quechua',
          'rm' => 'Romansh',
          'rn' => 'Rundi',
          'ru' => 'Russian',
          'sm' => 'Samoan',
          'sg' => 'Sango',
          'sa' => 'Sanskrit',
          'sc' => 'Sardinian',
          'sr' => 'Serbian',
          'sn' => 'Shona',
          'sd' => 'Sindhi',
          'si' => 'Sinhala, Sinhalese',
          'sk' => 'Slovak',
          'sl' => 'Slovenian',
          'so' => 'Somali',
          'st' => 'Sotho, Southern',
          'nr' => 'South Ndebele',
          'es' => 'Spanish, Castilian',
          'su' => 'Sundanese',
          'sw' => 'Swahili',
          'ss' => 'Swati',
          'sv' => 'Swedish',
          'tl' => 'Tagalog',
          'ty' => 'Tahitian',
          'tg' => 'Tajik',
          'ta' => 'Tamil',
          'tt' => 'Tatar',
          'te' => 'Telugu',
          'th' => 'Thai',
          'bo' => 'Tibetan',
          'ti' => 'Tigrinya',
          'to' => 'Tonga (Tonga Islands)',
          'ts' => 'Tsonga',
          'tn' => 'Tswana',
          'tr' => 'Turkish',
          'tk' => 'Turkmen',
          'tw' => 'Twi',
          'ug' => 'Uighur, Uyghur',
          'uk' => 'Ukrainian',
          'ur' => 'Urdu',
          'uz' => 'Uzbek',
          've' => 'Venda',
          'vi' => 'Vietnamese',
          'vo' => 'Volap_k',
          'wa' => 'Walloon',
          'cy' => 'Welsh',
          'fy' => 'Western Frisian',
          'wo' => 'Wolof',
          'xh' => 'Xhosa',
          'yi' => 'Yiddish',
          'yo' => 'Yoruba',
          'za' => 'Zhuang, Chuang',
          'zu' => 'Zulu'
        ];
        $select_options = [
          'list' => [
            [
              'value' => '',
              'name' => 'Select Language',
              'disabled' => true
            ],
          ]
        ];

        if (isset($options['is_search']) && $options['is_search']) {
          unset($select_options['list'][0]);
          $select_options['is_search'] = true;
        }
        if (isset($options['prefix'])) {
          $select_options['prefix'] = $options['prefix'];
        }

        foreach ($languages as $language) {
          $select_options['list'][] = [
            'value' => $language,
            'name' => $language,
          ];
        }
        static::normal('select', $value, $data, $select_options);
      } else if ($type == 'select-title') {
        if (!isset($options['is_search'])) {
          $select_options = [
            'list' => [
              [
                'value' => '',
                'name' => 'Select Title',
                'disabled' => true
              ]
            ]
          ];
        }
        if (isset($options['is_search']) && $options['is_search']) {
          $select_options['is_search'] = true;
        }
        if (isset($options['prefix'])) {
          $select_options['prefix'] = $options['prefix'];
        }
        if (isset($options['is_return'])) {
          $select_options['is_return'] = $options['is_return'];
        }

        $select_options['list'][] = [
          'value' => '',
          'name' => 'Not specified',
        ];
        $select_options['list'][] = [
          'value' => 'Mr.',
          'name' => 'Mr.'
        ];
        $select_options['list'][] = [
          'value' => 'Mrs.',
          'name' => 'Mrs.'
        ];
        $select_options['list'][] = [
          'value' => 'Ms.',
          'name' => 'Ms.'
        ];
        $select_options['list'][] = [
          'value' => 'Miss',
          'name' => 'Miss'
        ];
        $select_options['list'][] = [
          'value' => 'Master',
          'name' => 'Master'
        ];
        $select_options['list'][] = [
          'value' => 'Maid',
          'name' => 'Maid'
        ];
        $select_options['list'][] = [
          'value' => 'Madam',
          'name' => 'Madam'
        ];
        if ($class) {
          $data['class'] = $class;
        }

        $html = static::normal('select', $value, $data, $select_options);
      } else if ($type == 'upload-img') {
        $id = self::randomName(10);
        $is_btn = isset($options['is_btn']) ? $options['is_btn'] : 1;
        $is_view = isset($options['is_view']) ? $options['is_view'] : 1;
        $is_delete = isset($options['is_delete']) ? $options['is_delete'] : 1;
        $is_upload = true;
        if (isset($options['is_upload'])) {
          $is_upload = $options['is_upload'];
        }
        $is_upload_class = $is_upload ? 'upload_img_area_event' : '';

        $width = isset($options['width']) ? $options['width'] : '200px';
        $height = isset($options['height']) ? $options['height'] : '100%';
        $active = ($value && strpos($value, '/placeholder/') === false) ? 'active' : '';
        $is_placeholder = '';
        if (isset($data['is_placeholder']) && $data['is_placeholder'] && $value) {
          $active = 'active';
          $is_placeholder = 'is_placeholder="1"';
          $is_btn = 1;
        }
        unset($data['is_placeholder']);

        $preview_name = isset($options['preview_name']) ? $options['preview_name'] : '';
        unset($options['preview_name']);
        $bg_html = isset($options['bg-img']) ? 'is-bg="1" style="background-image: url(' . $options['bg-img'] . ');"' : ' is-bg="0"';

        $bg_html = isset($options['bg-img']) ? 'style="background-image: url(' . $options['bg-img'] . ');"' : '';

        $name = isset($data['name']) ? $data['name'] : '';
        unset($data['name']);
        $is_required = (isset($data['required']) && $data['required']) ? 'required="1"' : '';
        $main_required = $is_required;
        $is_required = ($is_required && $value && strpos($value, '/placeholder/') === false) ? '' : $is_required;

        unset($data['required']);

        $all_attr = '';
        foreach ($data as $key => $attr) {
          $all_attr .= ' ' . $key . '="' . $attr . '"';
        }

        $html = '<div class="live-once-img-area ' . $class . ' ' . $active . '" style="max-width: ' . $width . ';" is-bg="' . (isset($options['bg-img']) ? 1 : 0) . '" ' . $main_required . '>
            <div class="live-once-img-group ' . ($value && strpos($value, '/placeholder/') === false ? 'no-bg' : '') . '" style="padding-top:' . $height . ';"' . $all_attr . '>
              <input type="file" name="' . $name . '" class="upload_img_event" ' . $is_required . $all_attr . '>
              <div class="img ' . $is_upload_class . '">
                <div class="bg-defult" ' . $bg_html . '></div>';
        if ($is_btn == 1) {
          $html .= '<div class="button-group">';
          if ($is_view == 1) {
            $html .= '<button type="button" class="open-image preview_upload_img_event">' . file_get_contents(F_ROOT_PHP . '/_framework/modules/main/tiwform/icon/search.svg') . '</button>';
          }
          if ($is_delete == 1) {
            $html .= '<button type="button" class="delete delete_upload_img_event">' . file_get_contents(F_ROOT_PHP . '/_framework/modules/main/tiwform/icon/bin.svg') . '</button>';
          }
          $html .= '</div>';
        }
        $html .= '<img class="preview" name="' . $preview_name . '" src="' . $value . '" ' . $is_placeholder . '>
              </div>
            </div>
          </div>';
      } else if ($type == 'tel-flag') {
        $country_name_html = isset($options['country_name']) ? '<input class="country_name" type="hidden" name="' . $options['country_name'] . '">' : '';
        $country_code = isset($options['country_code']) ? $options['country_code'] : 'country_code';
        $html = '<div class="telephone-form-group ' . $main_class . '">
                <input type="tel" value="' . $value . '" class="form-text form-flags ' . $class . '"' . $all_attr . '>
                ' . $country_name_html . '
                <input class="country_code" type="hidden" name="' . $country_code . '">
              </div>';
      } else if ($type == 'checkbox' || $type == 'radio') {
        $style = isset($options['style']) ? $options['style'] : 1;
        $data['class'] = $class;
        if ($style == 1) {
          $html = self::checkbox1($type, $value, $data, $options);
        } else if ($style == 2) {
          $html = self::checkbox2($type, $value, $data, $options);
        } else if ($style == 3) {
          $html = self::checkbox3($type, $value, $data, $options);
        } else {
          $html = '<span class="text-danger">No style of this type.<span>';
        }
      } else if ($type == 'drag-drop-file') {
        //uploaded list
        if (is_array($value) && $value) {
          $html = self::uploaded_file($value, $prefix);
        }
        //form upload
        if ($is_edit) {
          $html .= '<div class="drag-drop-file-container ' . $class . '" ' . $all_attr . '>
                    <div class="drag-drop-file-area">
                      <input type="hidden" class="d-none drop_on_area_event">
                      <div class="drop-file-group"></div>
                    </div>
                  </div>';
        }
      } else if ($type == 'add-scan-tag') {
        $placeholder = (isset($data['placeholder']) && $data['placeholder']) ? $data['placeholder'] : 'Enter';
        $required = (isset($data['required']) && $data['required']) ? '<input type="input" class="input-required" required>' : '';
        $name = (isset($data['name']) && $data['name']) ? $data['name'] : '';

        $tag_html = '';
        if (is_array($value)) {
          foreach ($value as $tag) {
            $tag_html .= '<div class="add-scan-tag-list">
                          <input type="hidden" name="' . $name . '" value="' . $tag . '">
                          <div class="add-scan-tag-name" title="' . $tag . '">' . $tag . '</div>
                          <div class="add-scan-tag-delete __delete_tag_scan_event"><i></i></div>
                        </div>';
          }
          $required = $tag_html ? '' : $required;
        }

        $html = '<div class="add-scan-tag-container ' . $class . '" ' . $all_attr . '>
                  <div class="add-scan-tag-area">
                    <input type="text" class="input-text-enter __input_tag_event" placeholder="' . $placeholder . '">
                    <div class="btn-add-tag __add_tag_event">' . W_Structure::getfile('_framework/modules/main/tiwform/icon/add.svg') . '</div>
                  </div>
                  <div class="add-scan-tag-group">
                    ' . $required . $tag_html . '
                  </div>
                </div>';
      } else if ($type == 'upload-audio') {
        $html = self::formUploadAudio($value, $data, $options, $class, 1);
      } else if ($type == 'qrcode') {
        $options['size'] = isset($options['size']) ? $options['size'] : '200';
        $size = '500x500';

        $html = '<div class="form-qrcode-container" style="max-width: ' . $options['size'] . 'px">
                  <div class="form-qrcode-body">
                    <img src="https://chart.googleapis.com/chart?chs=' . $size . '&cht=qr&chl=' . $value . '&choe=UTF-8">
                  </div>
                </div>';
      } else {
        $html = '<span class="text-danger">No form of this type.<span>';
      }
    } else {
      if ($type == 'drag-drop-file' && is_array($value) && $value) {
        $html = self::uploaded_file($value, $prefix);
      } else if ($type == 'select-tag') {
        if (is_array($value) && $value) {
          $options_list = isset($options['list_encode']) ? json_decode($options['list_encode'], true) : $options['list'];
          if (isset($options_list)) {
            $html = '<div class="tag-group">';
            foreach ($options_list as $lists) {
              if (array_search($lists['value'], $value) !== false) {
                $html .= '<div class="tag-list bg-field">' . $lists['name'] . '</div>';
              }
            }
            $html .= '</div>';
          }
        }
      } else if ($type == 'select') {
        $options_list = isset($options['list_encode']) ? json_decode($options['list_encode'], true) : $options['list'];

        $key = array_search($value, array_column($options_list, 'value'));
        if (isset($options_list[$key]['name'])) {
          $html = '<div class="font-16px font-Medium text-info">' . $options_list[$key]['name'] . '</div>';
        }
      } else if ($type == 'select-ajax') {
        $html = '<div class="d-flex align-items-center text-info font-16px">' . (isset($value[1]) ? $value[1] : '') . '</div>';
      } else if ($type == 'upload-img') {
        $id = self::randomName(10);
        $is_btn = isset($options['is_btn']) ? $options['is_btn'] : 1;
        $is_view = isset($options['is_view']) ? $options['is_view'] : 1;
        $is_delete = isset($options['is_delete']) ? $options['is_delete'] : 1;
        $is_upload = true;
        if (isset($options['is_upload'])) {
          $is_upload = $options['is_upload'];
        }
        $is_upload_class = $is_upload ? 'upload_img_area_event' : '';

        $width = isset($options['width']) ? $options['width'] : '200px';
        $height = isset($options['height']) ? $options['height'] : '100%';
        $active = ($value && strpos($value, '/placeholder/') === false) ? 'active' : '';
        $is_placeholder = '';
        if (isset($data['is_placeholder']) && $data['is_placeholder'] && $value) {
          $active = 'active';
          $is_placeholder = 'is_placeholder="1"';
        }
        unset($data['is_placeholder']);

        $preview_name = isset($options['preview_name']) ? $options['preview_name'] : '';
        unset($options['preview_name']);
        // $is_show_preview = $preview_name ? 'is-show-preview' : '';
        $bg_html = isset($options['bg-img']) ? 'is-bg="1" style="background-image: url(' . $options['bg-img'] . ');"' : ' is-bg="0"';
        $name = isset($data['name']) ? $data['name'] : '';
        unset($data['name']);
        $is_required = (isset($data['required']) && $data['required'] == 1) ? 'required="1"' : '';
        unset($data['required']);

        $all_attr = '';
        foreach ($data as $key => $attr) {
          $all_attr .= ' ' . $key . '="' . $attr . '"';
        }

        $html = '<div class="live-once-img-area ' . $class . ' ' . $active . '" style="max-width: ' . $width . ';">
            <div class="live-once-img-group" style="padding-top:' . $height . ';"' . $all_attr . '>
              <div class="img ' . $is_upload_class . '">
                <div class="bg-defult" ' . $bg_html . '></div>';
        if ($is_btn == 1) {
          $html .= '<div class="button-group">';
          if ($is_view == 1) {
            $html .= '<button type="button" class="open-image preview_img_event">' . file_get_contents(F_ROOT_PHP . '/_framework/modules/main/tiwform/icon/search.svg') . '</button>';
          }
          if ($is_delete == 1) {
            $html .= '<button type="button" class="delete">' . file_get_contents(F_ROOT_PHP . '/_framework/modules/main/tiwform/icon/bin.svg') . '</button>';
          }
          $html .= '</div>';
        }
        $html .= '<img class="preview" name="' . $preview_name . '" src="' . $value . '" ' . $is_placeholder . '>
              </div>
            </div>
          </div>';
      } else if ($type == 'color') {
        $html = '<input type="' . $type . '" disabled value="' . $value . '" class="form-color ' . $class . '"' . $all_attr . '>';
      } else if ($type == 'url') {
        $html = '<a href="' . $value . '" ' . $all_attr . ' class="text-primary text-underline font-16px">' . $value . '</a>';
      } else if ($type == 'upload-audio') {
        $html = self::formUploadAudio($value, $data, $options, $class, 0);
      } else {
        if ($type == 'date' || $type == 'time' || $type == 'month' || $type == 'week' || $type == 'datetime') {
          $value = (isset($options['date_format']) && $options['date_format'] && $value) ? W_Main::formatDate($value, $options['date_format']) : $value;
        }
        $html = '<div class="d-flex align-items-center text-info font-16px">' . $value . '</div>';
      }
    }

    if ($is_return) {
      return $html;
    } else {
      echo $html;
    }
  }

  /**
  | Title | check password and check match word1 word2
  | Description | สร้างฟอร์มตรวจสอบรหัสผ่าน
   */
  public static function checkForm($input_class = '', $btn_class = '', $options = '')
  {
    $title = (isset($options['title']) && $options['title']) ? $options['title'] : 'Create a password that:';
    $is_match = (isset($options['is-match']) && $options['is-match']) ? 'is-match="*Please enter the same password."' : '';
    echo '<div class="check-password-group mt-20px" check-password-class="' . $input_class . '" check-btn-class="' . $btn_class . '" ' . $is_match . '>';
    if ((isset($options['is-char']) || isset($options['is-word']) || isset($options['is-number'])) && ($options['is-char'] || $options['is-word'] || $options['is-number'])) {
      echo '<div class="check-password-title">' . $title . '</div>';
    }
    if (isset($options['is-char']) && $options['is-char']) {
      $char_number = (isset($options['is-char-number']) && $options['is-char-number']) ? $options['is-char-number'] : 8;

      echo '<div class="check-list-condition conditon_length_event" char_number="' . $char_number . '">
                <div class="icon"></div>
                <div class="description">contains at least ' . $char_number . ' characters</div>
              </div>';
    }
    if (isset($options['is-word']) && $options['is-word']) {
      echo '<div class="check-list-condition conditon_word_event">
                <div class="icon"></div>
                <div class="description">contains both lower (a-z) and upper case letters (A-Z)</div>
              </div>';
    }
    if (isset($options['is-number']) && $options['is-number']) {
      echo '<div class="check-list-condition conditon_number_event">
                <div class="icon"></div>
                <div class="description">contains at least one number (0-9) or a symbol</div>
              </div>';
    }
    echo '</div>';
  }

  /**
  | Title | เช็คค่าซ้ำใน database
  | Description | สร้างฟอร์มตรวจค่าซ้ำ
   */
  public static function availableForm($api = [], $message = '')
  {
    $html = 'data-available="' . base64_encode(json_encode($api)) . '" available-scope="' . self::randomName(5) . '"';
    if ($message) {
      $html .= ' available-message="' . $message . '"';
    }
    echo $html;
  }

  public static function generateSelectData($list, $key = [], $options = [])
  {
    $return_options = [
      'list' => [],
    ];
    if (isset($options['is_search']) && $options['is_search']) {
      $return_options['is_search'] = true;
    }
    if (isset($options['prefix'])) {
      $return_options['prefix'] = $options['prefix'];
    }
    if (isset($options['is_return'])) {
      $return_options['is_return'] = $options['is_return'];
    }
    if (isset($options['is_edit'])) {
      $return_options['is_edit'] = $options['is_edit'];
    }

    foreach ($list as $i => $data) {
      if (isset($key['color'])) {
        $return_options['list'][$i] = [
          'color' => isset($data[$key['color']]) ? $data[$key['color']] : '',
          'name' => isset($data[$key['name']]) ? $data[$key['name']] : '',
        ];

        if (isset($data[$key['value']])) {
          $return_options['list'][$i]['value'] = $data[$key['value']];
        }

        if (isset($options['is_data']) && $options['is_data']) {
          $return_options['list'][$i]['options']['data-list'] = base64_encode(json_encode($data));
        }
      } else {
        $return_options['list'][$i] = [
          'value' => isset($data[$key['value']]) ? $data[$key['value']] : '',
          'name' => isset($data[$key['name']]) ? $data[$key['name']] : '',
          'img' => (isset($key['img']) && isset($data[$key['img']])) ? $data[$key['img']] : '',
        ];
        if (isset($options['is_data']) && $options['is_data']) {
          $return_options['list'][$i]['options']['data-list'] = base64_encode(json_encode($data));
        }
      }
    }
    return $return_options;
  }

  private static function formUploadAudio($url = '', $data = [], $options = [], $class = '', $is_edit = 1)
  {
    //ชื่อไฟล์ที่ตัดมาจาก url
    $file_name = '';
    $is_file_name = false;
    if (isset($options['file_name'])) {
      $file_name = $options['file_name'];
      $is_file_name = true;
    } else if (filter_var($url, FILTER_VALIDATE_URL) !== false) {
      $file_name = parse_url($url, PHP_URL_PATH);
      $file_name = basename($file_name);
    }
    //ตัวหนังสือของปุ่ม upload
    $text_btn = (isset($options['text_btn']) && $options['text_btn']) ? $options['text_btn'] : 'อัปโหลด';
    //attribute
    $all_attr = '';
    $is_required = false;
    foreach ($data as $key => $attr) {
      if ($key == 'required') {
        $is_required = true;
      } else {
        $all_attr .= ' ' . $key . '="' . $attr . '"';
      }
    }
    //ถ้าไม่ใช่ไฟล์เสียง เช็คจาก url
    if (filter_var($url, FILTER_VALIDATE_URL) !== false) {
      $file_type = '.' . pathinfo($url, PATHINFO_EXTENSION);
      $file_type = explode('?', $file_type);
      $file_type = $file_type[0];
      $accept = isset($data['accept']) ? $data['accept'] : [];
      $accept = explode(',', $accept);
      foreach ($accept as $i => $type) {
        $accept[$i] = trim($type);
      }
      if (isset($data['accept']) && !in_array($file_type, $accept)) {
        $url = '';
      }
    }
?>
    <?php if ($is_edit || !$is_edit && $is_file_name) { ?>
      <div class="__form-upload-sound <?= $class ?>" is-upload="<?= $is_file_name ? 1 : 0; ?>" <?= $is_required ? 'required' : ''; ?>>
        <?php if ($url) { ?>
          <audio src="<?= $url ?>"></audio>
        <?php } ?>
        <div class="__sound-uploaded" data-status="pause">
          <div class="__sound-play">
            <?= W_Structure::getfile('_framework/modules/main/tiwform/icon/play.svg') ?>
          </div>
          <div class="__sound-pause">
            <?= W_Structure::getfile('_framework/modules/main/tiwform/icon/pause.svg') ?>
          </div>
          <div class="__sound-name"><?= $file_name ?></div>
          <?php if ($is_edit) { ?>
            <div class="__sound-delete">
              <?= W_Structure::getfile('_framework/modules/main/tiwform/icon/close.svg') ?>
            </div>
          <?php } ?>
        </div>
        <div class="__sound-blank">
          <input type="file" class="__sound-file" <?= $all_attr ?> <?= ($url && $is_required) ? '' : 'required'; ?>>

          <div class="__sound-btn-icon">
            <?= W_Structure::getfile('_framework/modules/main/tiwform/icon/upload.svg') ?>
          </div>
          <div class="__sound-btn-upload"><?= $text_btn ?></div>
        </div>
      </div>
    <?php } else { ?>
      Undefined
    <?php } ?>
<?php
    return ob_get_clean();
  }
}
