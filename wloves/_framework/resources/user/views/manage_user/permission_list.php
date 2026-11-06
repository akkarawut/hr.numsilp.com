<?php
$user_list = User::selectWithPermission('*');
?>
<table class="table w-data-table w-table-exportable w-table-fixedcolumns" data-scrollX="100%" data-exportable-type="colvis,excel" data-fixed-right="1">
  <thead>
    <tr>
      <th nowrap>Information</th>
      <th nowrap>Permission</th>
      <th nowrap>User Role</th>
      <th nowrap class="thin-cell no-sort no-colvis no-search"></th>
    </tr>
  </thead>
  <tbody>
    <?php
    foreach ($user_list as $idx => $data) {
    ?>
      <tr>
        <td>
          <p class="font-weight-bold mb-0">
            <?php
            if ($data['is_gen_username'] == 0) {
              echo $data['username'];
            } else {
              echo '-';
            }
            ?>
          </p>
          <p>
            <small>Full Name: <?= $data['full_name']; ?></small>
          </p>
        </td>
        <td>
          <?php
          foreach ($data['permission'] as $data_permission) {
            echo $data_permission . '<br />';
          }

          ?>
        </td>
        <td><?= $data['user_type']; ?></td>
        <td>
          <div class="btn-table-wrap justify-content-end">
            <?php if ($data['system_delete'] == 1) { ?>
              <a href="#" class="btn btn-icon-table btn-secondary">
                <?= file_get_contents('../../structure/images/icon/ban.svg'); ?>
              </a>
            <?php } ?>
            <a href="manage_user_detail.php?c=<?= $_GET['c']; ?>&id=<?= $data['id']; ?>" class="btn btn-icon-table btn-secondary">
              <?= file_get_contents('../../structure/image/icon/general/view.svg'); ?>
            </a>
          </div>
        </td>

      </tr>
    <?php
    }
    ?>
  </tbody>
</table>