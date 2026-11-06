<?php
class Brandnote
{
  public static function startNote($id = '', $name = '', $value = '', $h = '400', $class = '')
  {
    if ($id) {
      echo '<div class="theme-summernote ' . $class . '">
            <textarea name="' . $name . '" id="' . $id . '" data-h="' . $h . '">' . $value . '</textarea>
          </div>';
    }
  }
}
