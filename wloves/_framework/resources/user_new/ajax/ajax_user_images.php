<?php
require_once '../../../../_framework/import_for_ajax.php';

$user_id = $_POST['user_id'];
$file = $_FILES['user_profile_image'];
$result =  User::setProfileImage($user_id, $file);

echo json_encode($result);
