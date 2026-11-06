<form id="form_user_detail" method="POST" enctype="multipart/form-data">
  <?php
  $template_name = (isset($is_profile) && $is_profile) ? 'profile' : 'user_detail';
  ?>
  <?= Jform::generate_form($code, $table_name = 'sys_user', $template_name, ['tab_id' => $page, 'id' => $id], $is_edit); ?>
</form>