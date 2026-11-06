<!-- UI Setting -->
<?= Jform::extra_template($code, $template, $tab_id, $table_name = 'sys_user'); ?>
<?php
include_once '../layout/footer.php';
W_Structure::loadFooter();
?>

<script>
  // init Tooltip
  var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
  var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl)
  })
</script>