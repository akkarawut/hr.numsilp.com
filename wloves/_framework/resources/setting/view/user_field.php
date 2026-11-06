<?php
// Post Add and Edit Field
Jform::post_table_field($code, 'sys_user');
?>
<div class="col-lg-9">
  <!-- Table Field -->
  <?= Jform::generate_table_field($code, 'sys_user', 'User field & database column'); ?>
</div>