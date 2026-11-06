<?php
$_WLOVES['no_check_permission'] = 1;
$path_wloves = $_SERVER['DOCUMENT_ROOT'] . '/' . explode('/', $_SERVER['REQUEST_URI'])[1];
require_once $path_wloves . '/_framework/import.php';
require_once 'function.php';
$pure_links = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[SCRIPT_NAME]";
$is_localhost = ($_SERVER['REMOTE_ADDR'] == 'localhost' || $_SERVER['REMOTE_ADDR'] == '127.0.0.1' || $_SERVER['REMOTE_ADDR'] == '::1' || $_SERVER['REMOTE_ADDR'] == '172.17.0.1');
if ($is_localhost) {
  if ($_POST) {
    if (isset($_POST['btn_gentable_post'])) {
      unset($_POST['btn_gentable_post']);
      $result = [
        'response_status' => 'success',
        'response_message' => 'Select language success.',
      ];
      $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
      if (isset($_GET['language']) && $_GET['language'] != '') {
        $pos = strpos($actual_link, 'language=');
        $language_len = strlen('language=') + strlen($_GET['language']);
        $actual_link = substr_replace($actual_link, 'language=' . $_POST['select_language_name'], $pos, $language_len);
        $response_redirect = $actual_link;
      } else if (isset($_GET['language']) && $_GET['language'] == '') {
        $response_redirect = $actual_link . $_POST['select_language_name'];
      } else {
        $response_redirect = $actual_link . '&language=' . $_POST['select_language_name'];
      }
    } else if (isset($_POST['delete_word_modal'])) {
      unset($_POST['delete_word_modal']);
      $key =  $_POST['key'];
      $result = Translaty::deleteWord($key);
    } else if (isset($_POST['submit_import_translaty'])) {
      if (isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
        $result = Translaty::importFile($_FILES['file']);
      } else {
        $result = [
          'response_status' => 'error',
          'response_message' => 'No file uploaded.',
        ];
      }
    } else if (isset($_POST['btn_install'])) {
      $text = Translaty::getStart('module', $_POST['select_language_start'], $paths = '.');
      W_Main::console($text);
      $result = [
        'response_status' => 'success',
        'response_message' => 'Install success',
      ];
    } else if (isset($_POST['btn_confirm_addlg'])) {
      $result = Translaty::addLanguage($_POST['selcet_new_language_name']);
    } else if (isset($_POST['btn_confirm_dellg'])) {
      $result = Translaty::delLanguage($_POST['selcet_delete_language_name']);
    } else if (isset($_POST['btn_module'])) {
      $result = [
        'response_status' => 'success',
        'response_message' => 'Change module success.',
      ];
      $response_redirect = $pure_links . '?is_front=false&path=' . $_POST['select_module'];
    }
    if (isset($result)) {
      $response_message = isset($response_message) ? $response_message : $result['response_message'];
      $response_status = $result['response_status'] ? $result['response_status'] : 'error';
      $response_redirect = isset($response_redirect) ? $response_redirect : '';
      sleep(3);
      W_Main::notification($response_message, $response_status);
      W_Main::redirect($response_redirect);
    }
  }
  $is_front = $_GET['is_front'];
  $check_path = dirname(__DIR__, 3) . '/translaty';
  $module_path = Translaty::getPrefixPath();
  $path = isset($_GET['path']) ? $_GET['path'] . '/translaty' : '';
  $dir_list = array_filter(glob('../../../module/*'), 'is_dir');
  $option_dir = [];
  $option_dir['list'] = [
    [
      'name' => 'Please select',
      'value' =>  '',
      'disable' => true,
    ]
  ];
  $path_backend = '/var/www/html/wloves-pro/module/';
  foreach ($dir_list as $key => $dir_path) {
    $dir_list[$key] = str_replace("../../../module/", "", $dir_path);
    $option_dir['list'][] = [
      'name' => $dir_list[$key],
      'value' =>  $path_backend . $dir_list[$key]
    ];
  }
  $is_main = strpos($_SERVER['SCRIPT_NAME'], 'wloves') &&  strpos($module_path, 'wloves');
  if (strpos($_SERVER['SCRIPT_NAME'], 'wloves') &&  strpos($module_path, 'wloves')) {
    $print = '(หลังบ้าน)';
    if ($path != '') {
      Translaty::setPrefixPath($path);
    }
  } else if (strpos($_SERVER['SCRIPT_NAME'], 'wloves') ==  !strpos($module_path, 'wloves')) {
    if ($is_front == 'true') {
      Translaty::setPrefixPath($check_path);
      $print = '(หน้าบ้าน)';
    } else {
      $print = '(หลังบ้าน)';
      if ($path != '') {
        Translaty::setPrefixPath($path);
      }
    }
  } else {
    $print = '(หน้าบ้าน)';
    Translaty::setPrefixPath($check_path);
  }
  $is_install = Translaty::is_install();
}
?>

<!DOCTYPE html>
<html lang="en" data-theme="<?= W_Structure::getThemeClass() ?>">

<head>
  <?php W_Structure::loadMeta('../../../../'); ?>
  <?= W_Main::loadAsset('ztranslaty.css'); ?>
</head>

<body>
  <?php if ($is_localhost) { ?>
    <div id="translaty_content">
      <div id="modal_translaty" class="modal-translaty event-modal-translaty">
        <div class="modal-body">
          <?php
          $language = isset($_GET['language']) ? $_GET['language'] : '';
          $language_list =  Translaty::getlanguageList();
          if ($is_install) {
            $options_case = [
              'list' => [
                [
                  'value' => '',
                  'name' => 'No',
                ],
                [
                  'value' => 'strtoupper',
                  'name' => 'EXAMPLE WORD',
                ],

                [
                  'value' => 'ucfirst',
                  'name' => 'Example word',
                ],
                [
                  'value' => 'ucwords',
                  'name' => 'Example Word',
                ],
                [
                  'value' => 'strtolower',
                  'name' => 'example word',
                ],
                [
                  'value' => 'lcfirst',
                  'name' => 'eXAMPLE WORD',
                ],
              ]
            ];
            $curr_language_list = Translaty::getCurrentLanguage();
            $options_language = [
              'is_search' => true,
              'prefix' => $is_front == 'true' ? '../../../' : '../../../',
              'list' => [
                [
                  'value' => '',
                  'name' => 'Please Select',
                  'disabled' => true
                ],
              ]
            ];
            foreach ($curr_language_list as $key => $item) {
              $options_language['list'][] = [
                'value' => $item,
                'name' => $language_list[$item],
              ];
            }
            $options_new_language = [
              'is_search' => true,
              'prefix' =>  $is_front == 'true' ? '../../../' : '../../../',
              'list' => [
                [
                  'value' => '',
                  'name' => 'Please Select',
                  'disabled' => true
                ],
              ]
            ];
            foreach ($language_list as $key => $item) {
              $bool = true;
              foreach ($curr_language_list as $curr_key => $curr_item) {
                if ($key == $curr_item) {
                  $bool = false;
                }
              }
              if ($bool) {
                $options_new_language['list'][] = [
                  'value' => $key,
                  'name' => $item,
                ];
              }
            }
          }
          $options_start_language = [
            'is_search' => true,
            'prefix' =>  $is_front == 'true' ? '../../../' : '../../../',
            'list' => [
              [
                'value' => '',
                'name' => 'Please Select',
                'disabled' => true,
              ],
            ]
          ];
          foreach ($language_list as $key => $item) {
            $options_start_language['list'][] = [
              'value' => $key,
              'name' => $item,
            ];
          }

          ?>
          <div class="content-header-container">
            <input type="hidden" name="url_translaty" value="<?= Translaty::getPrefixPath() ?>">
            <form action="" method="post" id="import_translaty" class="d-none" enctype="multipart/form-data">
              <input type="hidden" name="submit_import_translaty">
              <input type="file" name="file" class="file_import_translaty">
            </form>
            <div class="d-flex justify-content-between w-100">
              <div class="header-title d-flex align-items-center">
                <h3>เพิ่ม ลบ และแสดงคำศัพท์ <span><?= $print ?></span></h3>
                <?php if ($is_front != 'true') { ?>
                  <form method="post" class="select-module">
                    <?php TiwForm::normal('btn', '', ['name' => 'btn_module', 'class' => 'event_btn_module btn-warning min-w-120px  h-40px mr-2px', 'type' => 'submit'], ['text' => 'Select module',]); ?>
                    <?php
                    selecter($option_dir, ['name' => 'select_module', 'class' => 'mx-10px min-w-300px h-40px'], isset($_GET['path']) ? $_GET['path'] : '');
                    ?>
                  </form>
                <?php } ?>
              </div>
              <?php if ($is_localhost && $is_install) {
                Translaty::exportFile();

              ?>

                <div class="d-flex">
                  <button name="btn_import_file" class="form-btn event_btn_add_word btn-success h-45px" type="button">Import file</button>
                  <a href="<?= str_replace("/var/www/html", "", Translaty::getPrefixPath()) . "/langauge_file.json" ?>" target="_blank" download>
                    <?= TiwForm::normal('btn', '', ['name' => '', 'type' => 'button', 'class' => 'ml-2px btn-primary min-w-130px'], ['text' => 'Export File']); ?>
                  </a>
                </div>
              <?php } else if (!$is_install && $is_localhost) { ?>
                <form method="post" class="d-flex flex-wrap">
                  <div class="w-200px mt-10px font-Bold">
                    Select langauge to install
                  </div>
                  <?php //TiwForm::normal('select', $language, ['name' => 'select_language_start', 'class' => 'mx-10px w-200px', 'required' => true], $options_start_language); 
                  selecter($options_start_language, ['name' => 'select_language_start', 'class' => 'min-w-300px h-40px'])
                  ?>
                  <button name="btn_install" class="form-btn event_btn_install btn-success h-45px mx-5px min-w-140px event-disable" type="submit">Install Module</button>
                </form>
              <?php } ?>
            </div>
          </div>
          <?php if ($is_install) {
          ?>
            <div class="content-body-container" style="min-height: 400px;">
              <div class="form-row">
                <!-- Btn Add new language -->
                <?php if ($is_localhost) { ?>
                  <div class="col-12">
                    <form method="POST" class="row mt-10px">
                      <div class="col-12">
                        <div class="d-flex just">
                          <?php TiwForm::normal('btn', '', ['name' => 'btn_addlg', 'class' => 'event_btn_addlg btn-warning min-w-180px  h-45px mr-2px', 'type' => 'button'], ['text' => 'Add New Language',]); ?>
                          <?php TiwForm::normal('btn', '', ['name' => 'btn_dellg', 'class' => 'event_btn_dellg btn-danger min-w-160px  h-45px', 'type' => 'button'], ['text' => 'Delete Language',]); ?>
                          <div class="event_addlg d-none ">
                            <div class="position-relative">
                              <?php //TiwForm::normal('select', '', ['name' => 'selcet_new_language_name', 'class' => 'selcet_new_language min-w-200px', 'id' => 'selcet_new_language_id'], $options_new_language);
                              selecter($options_new_language, ['name' => 'selcet_new_language_name', 'class' => 'selcet_new_language min-w-300px h-40px my-5px mx-20px'])
                              ?>
                            </div>
                            <div class="ml-20px">
                              <?php TiwForm::normal('btn', '', ['name' => 'btn_confirm_addlg', 'class' => 'event_btn_confirm_addlg btn-primary h-45px event-disable', 'type' => 'submit'], ['text' => 'Confirm',]); ?>
                            </div>
                          </div>
                          <div class="event_dellg d-none ">
                            <div class="position-relative">
                              <?php //TiwForm::normal('select', '', ['name' => 'selcet_delete_language_name', 'class' => 'selcet_delete_language min-w-200px ', 'id' => 'selcet_delete_language_id'], $options_language);
                              selecter($options_language, ['name' => 'selcet_delete_language_name', 'class' => 'selcet_delete_language min-w-300px h-40px my-5px mx-20px'])
                              ?>
                            </div>
                            <div class="ml-20px">
                              <?php TiwForm::normal('btn', '', ['name' => 'btn_confirm_dellg', 'class' => 'event_btn_confirm_dellg btn-primary h-45px event-disable', 'type' => 'submit'], ['text' => 'Confirm',]); ?>
                            </div>
                          </div>
                        </div>
                      </div>

                      <div class="event_not_lg col-12 pt-5px">
                        <div class="d-flex justify-content-between">
                          <div class="d-flex justify-content-start">
                            <button name="btn_add_word" class="form-btn event_btn_add_word btn-success h-45px" type="button" <?= Tiwdal::register('add_modal', []); ?>>Add New Word</button>
                            <label class="container">Show Framework
                              <input type="checkbox" id="check_framework" checked="checked">
                              <span class="checkmark"></span>
                            </label>
                          </div>
                          <div class="d-flex ">
                            <div class="pt-10px font-18px font-SemiBold">
                              Do you need Uppercase ?
                            </div>
                            <?php
                            selecter($options_case, ['name' => 'selcet_uppercase_name', 'class' => 'selcet_uppercase min-w-300px h-40px my-5px mx-20px'])
                            ?>
                          </div>
                        </div>
                      </div>
                    </form>
                  </div>
                <?php } ?>
                <!-- Btn generate table -->
                <div class="col-12">
                </div>
              </div>

              <!-- Word List tabel -->
              <?php
              $options_language_full = [];
              foreach ($curr_language_list as $key => $value) {
                $options_language_full[] = [
                  'value' => $language_list[$value],
                  'name' => $language_list[$value],
                ];
              }
              // $link_table = $is_front == 'true' ? '_framework/module/translaty/' : '../../_framework/module/translaty/';
              // $link_table = '';
              $data =  Translaty::exportTableList('');;
              ?>
              <div class="table-overflow-y-scorll">
                <div class="table-container table-responsive ">
                  <table id="myTable">
                    <thead>
                      <tr class="header">
                        <th></th>
                        <th>Keyword
                        </th>
                        <?php
                        foreach ($curr_language_list as $lg) {
                        ?>
                          <th class="max-w-200px">Word (<?= $lg ?>)</th>
                        <?php
                        }
                        ?>
                      </tr>
                      <tr>
                        <th></th>
                        <th class="filter">
                          <input type="text" placeholder="Search for Keyword.." class="myInput">
                        </th>
                        <?php
                        foreach ($curr_language_list as $index => $lg) {
                        ?>
                          <th class="max-w-200px filter"> <input type="text" placeholder="Search for word (<?= $lg ?>).." class="myInput"></th>
                        <?php
                        }
                        ?>
                      </tr>
                    </thead>
                    <tbody class="refresh_table_event">
                      <?php include './ajax/html_table_new_word_list.php' ?>
                    </tbody>
                  </table>
                </div>
              </div>
              <!-- //? old table -->
              <!-- <div id="word_list" class="container-pagination table-overflow-y-scorll mt-10px" <?= Homepagify::createHomepagify('word_list', '?is_front=' . $is_front . 'path=' . $path, '', 'Word List', $link_table) ?>>
              <div class="table-responsive">
                <table class="table table-sort">
                  <thead>
                    <tr>
                      <th nowrap>
                      </th>
                      <th nowrap data-sort="key" data-filter="<?= Homepagify::dataFilter('key', 'text') ?>">Keyword</th>
                      <?php
                      foreach ($curr_language_list as $lg) {
                      ?>
                        <th nowrap data-sort="<?= $lg ?>_word" data-filter="<?= Homepagify::dataFilter($lg . '_word', 'text') ?>" class="max-w-200px">Word (<?= $lg ?>)</th>
                      <?php
                      }
                      ?>
                    </tr>
                  </thead>
                </table>
              </div>
            </div> -->

            </div>
          <?php } ?>

        </div>
      </div>
      <div class="event-notification-copy d-none">
        Copy Complete !!!
      </div>
      <div class="modal-backdrop-translaty z-index-1038 event-modal-translaty d-none"></div>

      <!-- Add Modal -->
      <?php Tiwdal::startModal('add_modal', 'modal-md'); ?>
      <button type='button' class='close event-close-modal' data-dismiss='modal' aria-label='Close'></button>
      <div class="modal-header">
        <h5 class="modal-title">Add Word Modal</h5>
      </div>
      <form method="post" id="addword_input">
        <div class="modal-body">
          <div class="form-row mb-15px">
            <div class="col-sm-4 pt-7px">Keyword<span class="text-danger">*</span></div>
            <div class="col-sm-8">
              <?= TiwForm::normal('text', '', ['name' => 'key', 'placeholder' => 'Enter', 'class' => 'mb-0  event-reset-input event-focus-input', 'required' => 'true']) ?>
            </div>
          </div>
          <?php foreach ($curr_language_list as $lg) {
          ?>
            <div class="form-row">
              <div class="col-sm-4 pt-7px">Word ( <?= $lg ?> )</div>
              <div class="col-sm-8">
                <?= TiwForm::normal('textarea', '', ['name' => $lg . '_word', 'placeholder' => 'Enter', 'class' => 'mb-0 min-h-50px event-reset-input']) ?>
              </div>
            </div>
          <?php
          } ?>
        </div>
      </form>
      <div class="modal-footer">
        <?php
        TiwForm::normal('btn', '', ['name' => '', 'type' => 'button', 'class' => 'btn-close-modal event-close-modal min-w-90px', 'data-dismiss' => 'modal'], ['text' => 'Cancel', 'type' => '']);
        TiwForm::normal('btn', '', ['name' => 'add_word_modal', 'type' => 'submit', 'class' => 'min-w-120px btn-success'], ['text' => 'ADD', 'type' => '']);
        ?>
      </div>
      <?php Tiwdal::endModal() ?>

      <!-- Edit Modal -->
      <?php Tiwdal::startModal('edit_modal', 'modal-md'); ?>
      <button type='button' class='close event-close-edit-modal event-close-modal' data-dismiss='modal' aria-label='Close'></button>
      <div class="modal-header">
        <h5 class="modal-title">Edit Modal</h5>
      </div>
      <form method="post" id="editword_input">
        <div class="modal-body">
          <div class="form-row mb-15px">
            <div class="col-sm-4 pt-7px">Keyword</div>
            <div class="col-sm-8">
              <?= TiwForm::normal('text', '', ['name' => '{key}', 'placeholder' => 'Enter', 'class' => 'mb-0 min-h-50px']) ?>
              <?= TiwForm::normal('hidden', '', ['name' => '{old_key}', 'readonly' => 'true', 'class' => 'mb-0 min-h-50px']) ?>
            </div>
          </div>
          <?php foreach ($curr_language_list as $lg) {
          ?>
            <div class="form-row">
              <div class="col-sm-4 pt-7px">Word ( <?= $lg ?> )</div>
              <div class="col-sm-8">
                <?= TiwForm::normal('textarea', '', ['name' => '{' . $lg . '_word}', 'placeholder' => 'Enter', 'class' => 'mb-0 min-h-50px']) ?>
              </div>
            </div>
          <?php
          } ?>
        </div>
      </form>
      <div class="modal-footer">
        <?php
        TiwForm::normal('btn', '', ['name' => '', 'type' => 'button', 'class' => 'btn-close-modal event-close-modal min-w-90px', 'data-dismiss' => 'modal'], ['text' => 'Cancel', 'type' => '']);
        TiwForm::normal('btn', '', ['name' => 'edit_word_modal', 'type' => 'submit', 'class' => 'min-w-120px btn-success'], ['text' => 'EDIT', 'type' => '']);
        ?>
      </div>
      <?php Tiwdal::endModal() ?>

      <!-- Delete Modal -->
      <?php Tiwdal::startModal('delete_modal', 'max-w-650px'); ?>
      <button type="button" class="close event-close-modal" data-dismiss="modal" aria-label="Close"></button>
      <div class="delete_picklist_modals">
        <div class="modal-body">
          <div class="text-uppercase font-18px text-info font-SemiBold text-center mt-10px mb-15px">Delete word</div>
          <div class="font-16px text-secondary text-center mb-5px">
            Are you sure to delete this <span class="text-danger text-uppercase">“ Keyword -<span id="name_del"></span> ”</span><br>
            from the system ?
          </div>
        </div>
        <div class="modal-footer">
          <form action="" method="post">
            <div class="d-flex align-items-center">
              <input type="hidden" value="" name="{key}">
              <?php
              TiwForm::normal('btn', '', ['name' => '', 'type' => 'button', 'class' => 'btn-close-modal event-close-modal min-w-90px ', 'data-dismiss' => 'modal'], ['text' => 'Cancel', 'type' => '']);
              TiwForm::normal('btn', '', ['name' => 'delete_word_modal', 'type' => 'submit', 'class' => 'min-w-120px btn-danger'], ['text' => 'DELETE', 'type' => '']);
              ?>
            </div>
          </form>
        </div>
      </div>
      <?php Tiwdal::endModal() ?>
    </div>
  <?php } ?>
  <?php W_Structure::loadFooter('../../../../'); ?>
  <script>
    let language, wordcase;
    let wordcase_print = '';
    let input_keyword_edit_modal = document.getElementsByClassName('keyword_edit_modal')[0];
    let keyword_edit_value = document.getElementsByClassName('keyword_edit_modal')[0];
    let ele_keyowrd_modal = document.getElementsByClassName('print_keyword_edit_modal')[0];
    let ele_language_text = document.getElementById('language_text');
    let ele_btn = document.getElementsByName('btn_gentable')[0];
    let ele_btn_post = document.getElementsByName('btn_gentable_post')[0];
    let ele_btn_addlg = document.getElementsByName('btn_addlg')[0];
    let ele_btn_dellg = document.getElementsByName('btn_dellg')[0];
    let ele_addlg = document.getElementsByClassName('event_addlg')[0];
    let ele_dellg = document.getElementsByClassName('event_dellg')[0];
    let eles_addmodal_input = document.getElementsByClassName('event-reset-input');
    let ele_noti_copy = document.getElementsByClassName('event-notification-copy')[0];
    let ele_close_edit_modal = document.getElementsByClassName('event-close-edit-modal')[0];
    let ele_div_add_word = document.getElementsByClassName('event_not_lg')[0];
    let select_input_add = document.getElementsByName('selcet_new_language_name')[0];
    let select_input_del = document.getElementsByName('selcet_delete_language_name')[0];
    let is_showAddLanguage = false;
    let is_showDelLanguage = false;
    let url_check = <?= '"' . Translaty::getPrefixPath() . '"' ?>;
    let is_front = url_check.search('module') == -1 ? true : false;
    let prefix = is_front ? '../' : '../../../';
    //? for modal
    let btn_translaty = document.getElementById('btn-translaty');
    let ele_modal_list = document.getElementsByClassName('event-modal-translaty');
    let key_modal_list = Object.keys(ele_modal_list);

    $(document).ready(() => {
      $(document).on('change', 'input[name="select_language_name"]', function(e) {
        genShortTextLanguage()
      });
      $(document).on('change', '[name="selcet_uppercase_name"]', function(e) {
        wordcase = $(this).val();
        if (wordcase) {
          wordcase_print = ',["case"=>"' + wordcase + '"]';
        } else {
          wordcase_print = '';
        }
      });
      $(document).on('click', 'button[name="btn_addlg"]', function(e) {
        showAddLanguage()
      });
      $(document).on('click', 'button[name="btn_dellg"]', function(e) {
        showDelLanguage()
      });
      $(document).on('click', 'button[name="btn_gentable"]', function(e) {
        ele_btn_post.click()
      });
      $(document).on('click', 'button[name="btn_import_file"]', function(e) {
        $('.file_import_translaty').click();
      });
      $(document).on('click', 'button[name="add_word_modal"]', function(e) {
        $(this).prop('disabled', true);
        addWord();
      });
      $(document).on('click', 'button[name="edit_word_modal"]', function(e) {
        editWord();
      });
      $(document).on('click', '.event-copy-keyword', function(e) {
        let is_class = this.classList.contains("event-is-value");
        let print_value = '';
        if (is_class) {
          print_value = ",['key'=>'value']";
        } else if (wordcase_print) {
          print_value = ",[]";
        }

        let str = "Ty::get('" + e.currentTarget.value + "'" + print_value + wordcase_print + ")";
        navigator.clipboard.writeText(str);
        showCopyNoti()
      });
      $(document).on('click', '.event-copy-keyword-html', function(e) {
        let is_class = this.classList.contains("event-is-value");
        let print_value = '';
        if (is_class) {
          print_value = ",['key'=>'value']";
        } else if (wordcase_print) {
          print_value = ",[]";
        }

        let str = "<" + "?= " + "Ty::get('" + e.currentTarget.value + "'" + print_value + wordcase_print + ")" + " ?" + ">";
        navigator.clipboard.writeText(str);
        showCopyNoti()
      });
      $(document).on('change', '.file_import_translaty', function(e) {
        $('#import_translaty').submit();
      });
      $(document).on('click', '.btn-translaty-panel', function(e) {
        showModalTraslaty(true);
      });
      $(document).on('click', '.event-close-modal', function(e) {
        closeModal();
      })
      // $(document).on('click', '.event-close-translaty-modal', function(e) {
      //   showModalTraslaty(false);
      // });
      $(document).on('click', '.event-disable', function(e) {
        $(this).submit();
        let target = $(this)
        let input_target = $(this).parent().find('.select-value-area').find('input').val();
        if (input_target != '') {
          setTimeout(function() {
            target.prop('disabled', true);
          }, 500)
          setTimeout(function() {
            target.prop('disabled', false);
          }, 2000);
        }
      });

      // $("#btn-translaty").draggable({
      //   containment: "body",
      //   axis: "y",
      // });
      const sleep = ms => new Promise(r => setTimeout(r, ms));

      function genShortTextLanguage() {
        let select_language = document.getElementsByName('select_language_name')[0];
        if (select_language.value != language) {
          language = select_language.value
          ele_language_text.innerHTML = language;
        }
        if (select_language.value == '') {
          ele_btn.disabled = true;
        } else {
          ele_btn.disabled = false;
        }
      }

      function showAddLanguage() {
        if (!is_showAddLanguage) {
          ele_addlg.classList.remove('d-none')
          ele_addlg.classList.add('d-flex')
          ele_btn_dellg.classList.add('d-none')
          ele_div_add_word.classList.add('d-none');
          select_input_add.required = true;
        } else {
          ele_addlg.classList.add('d-none')
          ele_addlg.classList.remove('d-flex')
          ele_btn_dellg.classList.remove('d-none')
          ele_div_add_word.classList.remove('d-none');
          select_input_add.required = false;
        }
        is_showAddLanguage = !is_showAddLanguage;
      }

      function showDelLanguage() {
        if (!is_showDelLanguage) {
          ele_dellg.classList.remove('d-none')
          ele_dellg.classList.add('d-flex')
          ele_btn_addlg.classList.add('d-none')
          ele_div_add_word.classList.add('d-none');
          select_input_del.required = true;
        } else {
          ele_dellg.classList.add('d-none')
          ele_dellg.classList.remove('d-flex')
          ele_btn_addlg.classList.remove('d-none')
          ele_div_add_word.classList.remove('d-none');
          select_input_del.required = false;
        }
        is_showDelLanguage = !is_showDelLanguage;
      }

      function showModalTraslaty(bool) {
        if (bool) {
          key_modal_list.forEach((element, index) => {
            ele_modal_list[index].classList.remove('d-none');
          });
        } else {
          key_modal_list.forEach((element, index) => {
            ele_modal_list[index].classList.add('d-none');
          });
        }
      }

      function serializeJson(ele) {
        let arr = ele.serializeArray();
        let obj = {}
        arr.forEach((e, i) => {
          obj[e['name']] = e['value'];
        });
        return obj;
      }

      async function addWord() {
        let data_addword = serializeJson($('#addword_input'));
        let url = 'ajax/ajax_addword.php';
        let params = data_addword;
        await $.post(url, params).done(function(data) {
          console.log(data);
          let result = JSON.parse(data);
          w.notification(result['response_status'], result['response_message']);
          if (result['response_status'] == 'success') {
            let focusItem = document.getElementsByClassName('event-focus-input')[0];
            focusItem.focus();
            resetInputAddModal()
          }
          return;
        });
        await $('button[name="add_word_modal"]').prop('disabled', false);
        await refreshTable();
      }
      async function editWord() {
        let data_addword = serializeJson($('#editword_input'));
        let url = 'ajax/ajax_editword.php';
        let params = data_addword;
        await $.post(url, params).done(function(data) {
          let result = JSON.parse(data);
          if (result['response_status']) {
            w.notification(result['response_status'], result['response_message']);
          } else {
            w.notification('error', data);
          }
          if (result['response_status'] == 'success') {
            resetInputAddModal()
          }
          return;
        });
        await refreshTable();
        await ele_close_edit_modal.click();
        await closeModal();

      }

      function resetInputAddModal() {
        let keyInput = Object.keys(eles_addmodal_input);
        keyInput.forEach((ele, inx) => {
          eles_addmodal_input[inx].value = '';
        })
      }

      async function showCopyNoti() {
        await ele_noti_copy.classList.remove('d-none');
        await sleep(2000);
        await ele_noti_copy.classList.add('d-none');
      }
    })

    function refreshTable() {
      var xhr = new XMLHttpRequest();
      xhr.open('POST', 'ajax/html_table_new_word_list.php?is_ajax=true', true);
      xhr.onload = function() {
        if (this.status == 200) {
          let table_container = document.getElementsByClassName('refresh_table_event')[0];
          table_container.innerHTML = this.response;
          // console.log(this.response);
        }
      };
      xhr.send();
      return 0;
    }

    function closeModal() {
      $('.modal').removeAttr('style');
      $('.modal-backdrop').remove();

    }

    function filterColumns($table) {
      const colFilters = {};
      let checkbox = document.getElementById('check_framework').checked;
      $table.find("thead tr .filter").each(function() {
        colFilters[$(this).index()] = {
          // agg: $(this).find("select").val(),
          val: $(this).find("input").val(),
        }
      });
      $table.find("tbody tr").each(function() {
        const $tr = $(this);
        const shouldHide = Object.entries(colFilters).some(([k, v]) => {
          const tdVal = $tr.find(`td:eq(${k})`).text().toLowerCase().replace(/['‘’"“”]/g, '');
          //? td value
          // console.log(tdVal)
          //? value input
          let is_framework = $tr.hasClass('is_framework_scope');
          let is_show = true;
          if (is_framework && !checkbox) {
            is_show = false;
          }
          let returns = !(tdVal.includes(v.val.toLowerCase().replace(/['‘’"“”]/g, '')) && is_show);
          return returns;
        });
        $tr.toggleClass("d-none", shouldHide);
      });
    }
    $(".filter").on("input", ":input", function(ev) {
      filterColumns($(this).closest("table"));
    });
    $("#check_framework").change((e) => {
      filterColumns($('#myTable'));
    })
  </script>
</body>

</html>