<?php
$_PAGE['permission'] = ['user', 'user_main', ''];
require_once '../../../../_framework/import_for_ajax.php';

$id = isset($_GET['id']) ? $_GET['id'] : '';

$where = [
  'username' => isset($_POST['username']) ? $_POST['username'] : '',
  // 'fullname' => isset($_POST['fullname']) ? $_POST['fullname'] : '',
];

$options = [
  'page_no'     => $_POST['page_no'],
  'page_size'   => $_POST['page_size'],
  'sort'        => isset($_POST['data_sort']) ? $_POST['data_sort'] : []
];

$user_lists = User::selectUser($_GET['c'], $where, $options);
?>

<tbody>
  <?php foreach ($user_lists as $user) {  ?>
    <?php
    $active = ($id == $user['id']) ? 'active' : '';
    ?>
    <tr class="tr-link cursor-pointer <?= $active ?>" data-link="?c=<?= $_GET['c'] ?>&id=<?= $user['id'] ?>">
      <td nowrap>
        <div class="w-40px h-40px rounded-circle border overflow-hidden">
          <div class=" img-1by1 holder">
            <img src="<?= $user['user_profile_image'] ?>" alt="">
          </div>
        </div>
      </td>
      <td nowrap class=" align-middle"><?= $user['username']; ?></td>
      <!-- <td nowrap class=" align-middle"><?= $user['fullname']; ?></td> -->
      <td class="thin-cell py-5px ">
        <?= W_Structure::getfile('_framework/resources/user_new/assets/image/icon/arrow_table.svg') ?>
      </td>
    </tr>
  <?php } ?>
</tbody>