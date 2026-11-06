<?php
class Tiwdal
{
  /**
  | Group | Modal สำหรับแก้ไข
  | Description | เซ็ตค่าให้ Modal ในปุ่มกด
  $id = id ของ Modal
  $data = ข้อมูลที่ต้องการให้แก้ไข
  $options = [
    'is_ajax' => 1, //ใส่กรณีที่ต้องการเรียก ajax modal
  ] //เงื่อนไขอื่น ๆ ที่ต้องการให้วาดลงไป
  ตัวอย่าง
   */
  public static function register($id = '', $data = [], $options = [])
  {
    global $_GET;
    $type = (isset($options['is_ajax']) && $options['is_ajax']) ? 'ajax' : 'edit';

    $data_options = '';
    if (isset($options['prefix'])) {
      $data_options .=  "prefix='" . base64_encode($options['prefix']) . "' ";
      unset($options['prefix']);
    }
    if (isset($options['modal_prefix'])) {
      $data_options .=  "modal_prefix='" . base64_encode($options['modal_prefix']) . "' ";
      unset($options['modal_prefix']);
    }

    if (isset($_GET['c'])) {
      $data_options .=  "code='" . base64_encode($_GET['c']) . "' ";
    }
    foreach ($options as $key => $option) {
      $data_options .= $key . "='" . $option . "' ";
    }

    $data_register = $data ? "data-register='" . base64_encode(json_encode($data)) . "'" : '';
    if (isset($options['is_return']) && $options['is_return']) {
      return "toggle-" . $type . "-modal='#" . $id . "' " . $data_register . " " . $data_options;
    } else {
      echo "toggle-" . $type . "-modal='#" . $id . "' " . $data_register . " " . $data_options;
    }
  }

  /**
  | Group | Modal สำหรับแก้ไข
  | Description | เซ็ตค่าให้ Modal ในปุ่มกด
  $id = id ของ Modal
  $size = ขนาด Modal
  $options = เงื่อนไขอื่น ๆ ที่ต้องการวาดที่หัว modal
   */
  public static function startModal($id = '', $size = 'modal-sm', $options = [])
  {
    if (!isset($options['data-bs-backdrop'])) {
      $options['data-bs-backdrop'] = 'static';
    }

    $data_options = '';
    foreach ($options as $key => $option) {
      if ($key != 'class') {
        $data_options .= $key . "='" . $option . "' ";
      }
    }

    $is_slide = isset($options['slide']) && $options['slide'] ? 'modal-slide' : '';

    $class = isset($options['class']) ? $options['class'] : '';
    echo "<div class='modal render-modal fade " . $is_slide . " " . $class . "' id='" . $id . "' " . $data_options . " aria-hidden='true'>
            <div class='modal-dialog " . $size . "' role='document'>
              <div class='modal-content'>";
  }
  public static function endModal()
  {
    echo "</div>
        </div>
      </div>";
  }

  /**
  | Group | Modal สำหรับ Ajax
  | Description | เซ็ตค่าให้ Modal ในปุ่มกด
  $id = id ของ Modal
  $size = ขนาด Modal
  $options = เงื่อนไขอื่น ๆ ที่ต้องการวาดที่หัว modal
   */
  public static function ajaxModal($id = '', $size = 'modal-sm', $options = [])
  {

    self::startModal($id, $size, $options);
    self::endModal();
  }
}

class AutoForm
{
  /**
  | Title | เรียกใช้ Auto form ในพื้นที่นั้น ๆ
  | Description | เมื่อเรียกใช้ register data แล้วจะทำการนำข้อมูลไปใส่ให้อัตโนมัติ
   */
  public static function register($data = [])
  {
    echo 'auto-form="' . base64_encode(json_encode($data)) . '"';
  }
}
