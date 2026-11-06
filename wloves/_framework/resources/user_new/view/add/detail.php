<form method="POST" enctype="multipart/form-data">
  <?= Jform::generate_form($code, $table_name = 'sys_user', 'add_data', ['tab_name' => '1'], $is_edit = true); ?>
</form>