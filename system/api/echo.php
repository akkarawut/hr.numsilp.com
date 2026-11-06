<?php
include '../required/import.php';
include 'rep.php';

header('amst_version: '.Info::version_main.'.'.Info::version_sub.'.'.Info::version_hotfix);
header('Content-Type: application/json');
echo json_encode(['call' => 'echo', 'request' => $_REQUEST, 'files' => $_FILES]);
die();