<?php
$setting['User'] = [
  'is_unique_email'       => false, // ห้ามเมล์ซ้ำ
  'is_unique_tel'         => false, // ห้ามเบอร์โทรซ้ำ
  'is_verify_system'      => false, // สมัคร
  'default_verify_status' => 'verified', // สมัคร not_verify/ verified
  'default_status'        => 'enabled', // สมัคร
  'image_code_list'       => [
    'user_profile_image_{$id}'
  ]
];