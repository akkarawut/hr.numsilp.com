<?php
function selecter($data, $options, $value = '')
{
  echo '<select name="' . $options['name'] . '" class="' . $options['class'] . '">';
  foreach ($data['list'] as $list) {
    if (isset($list['disable'])) {
      echo ' <option value="' . $list['value'] . '" disable ' . ($value ==  $list['value'] ? 'selected' : '') . ' >' . $list['name'] . '</option>';
    } else {
      echo ' <option value="' . $list['value'] . '" ' . ($value ==  $list['value'] ? 'selected' : '') .  '>' . $list['name'] . '</option>';
    }
  }
  echo '</select>';
}
