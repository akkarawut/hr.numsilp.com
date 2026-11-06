<?php
$session_user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;
$init_data = phr_helper::getInit(API_CODE, $session_user_id, $user_permission['_permission']);

if (!$init_data['user_info']) {
  W_Main::redirect('logout.php');
} else if (!$init_data['user_info']['is_can_login']) {
  W_Main::redirect('logout.php');
} else if (!$init_data['is_permission']) {
  W_Main::redirect('logout.php');
}




$user_info = $init_data['user_info'];
$current_user_id = $user_info['id'];
