<?php
if (isset($_POST['submit_add_module'])) {
  $api_result = Func::add($_POST['module_name'], $_POST['module']);
  if ($api_result['response_status']) {
    W_Main::notification('Success', 'success');
    W_Main::redirect('');
  } else {
    W_Main::notification($api_result['response_message'], 'error');
    W_Main::redirect('');
  }
} else if (isset($_POST['submit_remove_module'])) {
  $api_result = Func::remove($_POST['id']);
  if ($api_result['response_status']) {
    W_Main::notification('Success', 'success');
    W_Main::redirect('program_setting.php?type=');
  } else {
    W_Main::notification($api_result['response_message'], 'error');
    W_Main::redirect('');
  }
} else if (isset($_POST['submit_edit_program_setting'])) {
  if (isset($_POST)) {
    $data_file = [
      'program_name' => $_POST['program_name'],
      'customer'     => $_POST['customer'],
      'color_theme'  => $_POST['color_theme'],
      'start_date'   => $_POST['start_date']
    ];
    $api_result = WLoves::setProgramSetup($data_file);
  }
  if ($api_result['response_status']) {
    W_Main::notification('Success', 'success');
    W_Main::redirect('');
  } else {
    W_Main::notification($api_result['response_message'], 'error');
    W_Main::redirect('');
  }
} else if (isset($_POST['submit_add_person_program_setting'])) {
  if (isset($_POST)) {
    $api_result = WLoves::addContactList($_POST['name'], $_POST['phone_number'], $_POST['line_id']);
  }
  if ($api_result['response_status']) {
    W_Main::notification('Success', 'success');
    W_Main::redirect('');
  } else {
    W_Main::notification($api_result['response_message'], 'error');
    W_Main::redirect('');
  }
} else if (isset($_POST['submit_edit_person_program_setting'])) {
  if (isset($_POST)) {
    $api_result = WLoves::editContactList($_POST['id'], $_POST['name'], $_POST['phone_number'], $_POST['line_id']);
  }
  if ($api_result['response_status']) {
    W_Main::notification('Success', 'success');
    W_Main::redirect('');
  } else {
    W_Main::notification($api_result['response_message'], 'error');
    W_Main::redirect('');
  }
} else if (isset($_POST['submit_delete_person_program_setting'])) {
  if (isset($_POST)) {
    $api_result = WLoves::deleteContactList($_POST['id']);
  }
  if ($api_result['response_status']) {
    W_Main::notification('Success', 'success');
    W_Main::redirect('');
  } else {
    W_Main::notification($api_result['response_message'], 'error');
    W_Main::redirect('');
  }
} else if (isset($_POST['submit_upload_logo_image'])) {
  if (count($_FILES) > 0) {
    $api_result = File::add('logo_image', $_FILES['logo_image']);

    if ($api_result['response_status']) {
      W_Main::notification('Success', 'success');
      W_Main::redirect('');
    } else {
      W_Main::notification($api_result['response_message'], 'error');
      W_Main::redirect('');
    }
  }
} else if (isset($_POST['submit_upload_mobile_logo_image'])) {
  if (count($_FILES) > 0) {
    $api_result = File::add('mobile_logo_image', $_FILES['mobile_logo_image']);

    if ($api_result['response_status']) {
      W_Main::notification('Success', 'success');
      W_Main::redirect('');
    } else {
      W_Main::notification($api_result['response_message'], 'error');
      W_Main::redirect('');
    }
  }
} else if (isset($_POST['submit_set_module_pos'])) {
  $data = [
    'stock_link'          => $_POST['stock_link'],
    'this_is_pos_setting' => $_POST['this_is_pos_setting'],
    'enable_pos_product'  => $_POST['enable_pos_product']
  ];

  $api_result = Module::set($_POST['code'], $data);

  if ($api_result['response_status']) {
    W_Main::notification('Success', 'success');
    W_Main::redirect('');
  } else {
    W_Main::notification($api_result['response_message'], 'error');
    W_Main::redirect('');
  }
} else if (isset($_POST['submit_set_module_booking'])) {
  $data = [
    'stock_link'              => $_POST['stock_link'],
    'this_is_booking_setting' => $_POST['this_is_booking_setting'],
    'enable_pos_product'      => $_POST['enable_pos_product']
  ];

  $api_result = Module::set($_POST['code'], $data);

  if ($api_result['response_status']) {
    W_Main::notification('Success', 'success');
    W_Main::redirect('');
  } else {
    W_Main::notification($api_result['response_message'], 'error');
    W_Main::redirect('');
  }
} else if (isset($_POST['submit_set_module_stock'])) {
  $data = [
    'use_many_design'          => $_POST['use_many_design'],
    'enable_stock_product'     => $_POST['enable_stock_product'],
    'has_field_pv'             => $_POST['has_field_pv'],
    'has_web_category'         => $_POST['has_web_category'],
    'has_category_img'         => $_POST['has_category_img'],
    'category_img_recommended' => $_POST['category_img_recommended'],
    'shop_link'                => isset($_POST['shop_link']) ? $_POST['shop_link'] : []
  ];
  $api_result = Module::set($_POST['code'], $data);

  if ($api_result['response_status']) {
    W_Main::notification('Success', 'success');
    W_Main::redirect('');
  } else {
    W_Main::notification($api_result['response_message'], 'error');
    W_Main::redirect('');
  }
} else if (isset($_POST['submit_set_module_banner'])) {
  $data = [
    'banner_img_recommended' => $_POST['banner_img_recommended']
  ];
  $api_result = Module::set($_POST['code'], $data);

  if ($api_result['response_status']) {
    W_Main::notification('Success', 'success');
    W_Main::redirect('');
  } else {
    W_Main::notification($api_result['response_message'], 'error');
    W_Main::redirect('');
  }
} else if (isset($_POST['submit_set_module_user'])) {
  $data = [
    'use_crm' => $_POST['use_crm']
  ];

  $api_result = Module::set($_POST['code'], $data);

  if ($api_result['response_status']) {
    W_Main::notification('Success', 'success');
    W_Main::redirect('');
  } else {
    W_Main::notification($api_result['response_message'], 'error');
    W_Main::redirect('');
  }
} else if (isset($_POST['submit_set_module_moveon'])) {
  $data = [
    'stock_link'  => isset($_POST['stock_link']) ? $_POST['stock_link'] : [],
    'shop_link'   => isset($_POST['shop_link']) ? $_POST['shop_link'] : [],
    'bill_link'   => isset($_POST['bill_link']) ? $_POST['bill_link'] : [],
    'chat_link'   => isset($_POST['chat_link']) ? $_POST['chat_link'] : [],
    'banner_link' => isset($_POST['banner_link']) ? $_POST['banner_link'] : [],
    'mlm_link'    => isset($_POST['mlm_link']) ? $_POST['mlm_link'] : []
  ];
  // W_Main::display($data);
  // die();
  $api_result = Module::set($_POST['code'], $data);
  // W_Main::display($api_result);
  // die();
  if ($api_result['response_status']) {
    W_Main::notification('Success', 'success');
    W_Main::redirect('');
  } else {
    W_Main::notification($api_result['response_message'], 'error');
    W_Main::redirect('');
  }
} else if (isset($_POST['submit_set_module_bill'])) {
  $data = [
    'stock_link'         => isset($_POST['stock_link']) ? $_POST['stock_link'] : [],
    'has_payment_system' => $_POST['has_payment_system']
  ];
  $api_result = Module::set($_POST['code'], $data);

  if ($api_result['response_status']) {
    W_Main::notification('Success', 'success');
    W_Main::redirect('');
  } else {
    W_Main::notification($api_result['response_message'], 'error');
    W_Main::redirect('');
  }
} else if (isset($_POST['submit_set_module_logistic'])) {
  $data = [
    'bill_link' => isset($_POST['bill_link']) ? $_POST['bill_link'] : [],
    'shop_link' => isset($_POST['shop_link']) ? $_POST['shop_link'] : []
  ];

  $api_result = Module::set($_POST['code'], $data);

  if ($api_result['response_status']) {
    W_Main::notification('Success', 'success');
    W_Main::redirect('');
  } else {
    W_Main::notification($api_result['response_message'], 'error');
    W_Main::redirect('');
  }
} else if (isset($_POST['submit_set_module_chat'])) {
  $data = [
    'shop_link' => isset($_POST['shop_link']) ? $_POST['shop_link'] : [],
    'bill_link' => isset($_POST['bill_link']) ? $_POST['bill_link'] : []
  ];

  $api_result = Module::set($_POST['code'], $data);

  if ($api_result['response_status']) {
    W_Main::notification('Success', 'success');
    W_Main::redirect('');
  } else {
    W_Main::notification($api_result['response_message'], 'error');
    W_Main::redirect('');
  }
} else if (isset($_POST['submit_set_module_dynamic'])) {
  unset($_POST['submit_set_module_dynamic']);
  $code = $_POST['code'];
  unset($_POST['code']);
  $data = $_POST;
  // W_Main::display($data);
  // die();
  $api_result = Module::set($code, $data);

  if ($api_result['response_status']) {
    W_Main::notification('Success', 'success');
    W_Main::redirect('');
  } else {
    W_Main::notification($api_result['response_message'], 'error');
    W_Main::redirect('');
  }
} else if (isset($_POST['submit_set_module_img'])) {

  $image_code_list = [];
  foreach ($_POST['image_code_list'] as $key => $value) {
    if ($value) {
      $image_code_list[] = $value;
    }
  }

  $data = [
    'image_code_list' => isset($image_code_list) ? $image_code_list : [],
  ];

  $api_result = Module::set($_POST['code'], $data);

  if ($api_result['response_status']) {
    W_Main::notification('Success', 'success');
    W_Main::redirect('');
  } else {
    W_Main::notification($api_result['response_message'], 'error');
    W_Main::redirect('');
  }
} else if (isset($_POST['submit_set_module_content'])) {
  $data = [
    'multiple_content_category' => isset($_POST['multiple_content_category']) ? true : false,
    'cover'                     => isset($_POST['cover']) ? true : false,
    'cover_size'                => $_POST['cover_size'],
    'description'               => isset($_POST['description']) ? true : false,
    'text_editor'               => isset($_POST['text_editor']) ? true : false,
    'from_date'                 => isset($_POST['from_date']) ? true : false,
    'to_date'                   => isset($_POST['to_date']) ? true : false,
    'from_time'                 => isset($_POST['from_time']) ? true : false,
    'to_time'                   => isset($_POST['to_time']) ? true : false,
    'location'                  => isset($_POST['location']) ? true : false,
    'coordinate'                => isset($_POST['coordinate']) ? true : false,
    'author'                    => isset($_POST['author']) ? true : false,
    'youtube_link'              => isset($_POST['youtube_link']) ? true : false,
    'attach_file'               => isset($_POST['attach_file']) ? true : false,
    'attach_file_maximum'       => $_POST['attach_file_maximum'],
    'attach_file_explanation'   => $_POST['attach_file_explanation'],
    'gallery'                   => isset($_POST['gallery']) ? true : false,
    'gallery_maximum'           => $_POST['gallery_maximum'],
    'gallery_explanation'       => $_POST['gallery_explanation'],
    'count_viewer'              => isset($_POST['count_viewer']) ? true : false
  ];

  $api_result = Module::set($_POST['code'], $data);

  if ($api_result['response_status']) {
    W_Main::notification('Success', 'success');
    W_Main::redirect('');
  } else {
    W_Main::notification($api_result['response_message'], 'error');
    W_Main::redirect('');
  }
}
