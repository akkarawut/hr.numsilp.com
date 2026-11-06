<?php
//url at button edit, cancel and submit_edit_user success
$url = 'manage_user.php?c=' . $_GET['c'] . '&user_id=' . $user_id . '&page=1';

//controller submit form
if ($_POST) {
  if (isset($_POST['submit_edit_user'])) {
    unset($_POST['submit_edit_user']);
    unset($_POST['country_name']);

    $result = User::updateUser($user_id, $_POST);

    $result_log['user'] = $result;
    if ($result['response_status']) {
      $result = User::setUserCategory($user_id, $_POST['category_list']);
      $result_log['category'] = $result;

      if ($result['response_status']) {
        $result = User::setUserTag($user_id, $_POST['tag_list']);
        $result_log['tag'] = $result;

        if ($result['response_status']) {
          if (isset($_FILES['user_profile_image']) && !$_FILES['user_profile_image']['error']) {
            $result = File::add('user_profile_image_' . $user_id, $_FILES['user_profile_image']);
            $result_log['img'] = $result;
          }
          $response_redirect = $url;
        }
      }
    }
  }

  if (isset($result)) {
    $response_message = isset($response_message) ? $response_message : $result['response_message'];
    $response_status = $result['response_status'] ? 'success' : 'error';
    $response_redirect = isset($response_redirect) ? $response_redirect : '';

    W_Main::notification($response_message, $response_status);
    W_Main::redirect($response_redirect);
  }
}

function formProfileTemplate($title = '', $detail = '', $options = [])
{
  $is_return = (isset($options['is_return']) && $options['is_return']) ? true : false;
  $html = '<div class="information-row">
            <div class="col-title">' . $title . '</div>
            <div class="col-detail font-16px text-info">' . $detail . '</div>
          </div>';
  if ($is_return) {
    return $html;
  } else {
    echo $html;
  }
}

function form2col($detail1 = '', $detail2 = '')
{
  $detail1_html = $detail1 ? '<div class="col-xl-6">' . $detail1 . '</div>' : '';
  $detail2_html = $detail2 ? '<div class="col-xl-6">' . $detail2 . '</div>' : '';
  if ($detail1_html || $detail2_html) {
    echo '<div class="row">
          ' . $detail1_html . $detail2_html . '
        </div>';
  }
}

//team
$teams = User_Basic_Setting::selectTeam(['user_type_id' => $user_type_info['id']]);
$key = [
  'value' => 'id',
  'name' => 'name',
];
$team_options = TiwForm::generateSelectData($teams, $key, ['is_return' => true, 'is_edit' => $is_edit, 'is_search' => true]);

//category
$categories = User_Basic_Setting::selectCategory(['user_type_id' => $user_type_info['id']]);
$key = [
  'value' => 'id',
  'name' => 'name',
];
$category_options = TiwForm::generateSelectData($categories, $key, ['is_return' => true, 'is_edit' => $is_edit]);

//tag
$tags = User_Basic_Setting::selectTag(['user_type_id' => $user_type_info['id']]);
$key = [
  'value' => 'id',
  'name' => 'name',
];
$tag_options = TiwForm::generateSelectData($tags, $key, ['is_return' => true, 'is_edit' => $is_edit]);
?>
<form action="" method="post" enctype="multipart/form-data">
  <div class="container-detail p-15px">

    <?php
    //title, edit, cancel, save
    ?>
    <div class="d-flex align-items-center justify-content-between flex-wrap">
      <div class="mb-10px">
        <div class="text-uppercase font-16px font-SemiBold">User details - <span class="text-primary"><?= $user_info['title'] . ' ' . $user_info['full_name'] ?></span></div>
        <div class="font-14px text-secondary">Manage user data.</div>
      </div>
      <div class="mb-10px d-flex">
        <?php
        if ($is_edit) {
          echo '<a href="' . $url . '" class="btn btn-light h-35px mr-5px">CANCEL</a>';
          TiwForm::normal('btn', '', ['name' => 'submit_edit_user', 'class' => 'save_edit_user_event'], ['text' => 'SAVE']);
        } else {
          echo '<a href="' . $url . '&is_edit=1">' . TiwForm::normal('btn', '', ['type' => 'button', 'class' => 'btn-outline-info border'], ['text' => 'EDIT DATA', 'type' => '', 'is_return' => true]) . '</a>';
        }
        ?>
      </div>
    </div>
    <hr class="mt-0 mb-15px mx--15px">

    <?php
    //user information
    ?>
    <div class="user_information">
      <div class="font-14px text-uppercase font-SemiBold mb-15px">General information</div>
      <div class="information-container">
        <div class="information-data">
          <?php
          if ($is_edit) {
            $name = '<div class="row w-100">
                    <div class="col-xl-3">' . TiwForm::normal('select-title', $user_info['title'], ['name' => 'title', 'placeholder' => 'Select', 'required' => true], ['is_return' => true, 'is_search' => true]) . '</div>
                    <div class="col-xl-4">' . TiwForm::normal('text', $user_info['name'], ['name' => 'name', 'placeholder' => 'Enter', 'required' => true], ['is_return' => true]) . '</div>
                    <div class="col-xl-4">' . TiwForm::normal('text', $user_info['surname'], ['name' => 'surname', 'placeholder' => 'Enter', 'required' => true], ['is_return' => true]) . '</div>
                  </div>';
          } else {
            $name = '<span class="font-18px font-SemiBold">' . $user_info['title'] . ' ' . $user_info['name'] . ' ' . $user_info['surname'] . '</span>';
          }
          $name_required = $is_edit ? '<span class="text-danger">*</span>' : '';
          formProfileTemplate('Name' . $name_required, $name);

          if ($user_type_info['is_has_nick_name']) {
            formProfileTemplate('Preferred Name', '<div class="row w-100"><div class="col-xl-6">' . TiwForm::normal('text', $user_info['nick_name'], ['name' => 'nick_name', 'placeholder' => 'Enter', 'class' => 'mb-0'], ['is_return' => true, 'is_edit' => $is_edit]) . '</div></div>');
          }

          if ($user_type_info['is_use_pin_code']) {
            formProfileTemplate('Code', $user_info['user_code']);
          }

          if ($user_type_info['is_has_team']) {
            $team_value = $is_edit ? $user_info['team_id'] : $user_info['team_name'];
            formProfileTemplate('Team', '<div class="row w-100"><div class="col-xl-6">' . TiwForm::normal('select', $team_value, ['name' => 'team_id', 'placeholder' => 'Please Select', 'is_edit' => $is_edit], $team_options) . '</div></div>');
          }
          ?>
        </div>

        <?php if ($user_type_info['is_has_profile_image']) { ?>
          <div class="information-image-profile mb-10px">
            <?php
            $options = [
              'width' => '140px',
              'height' => '100%',
              'bg-img' => '../../_framework/module_main/tiwform/img/upload-profile.svg',
              'is_btn' => $is_edit,
              'is_upload' => $is_edit,
              'is_delete' => 0,
            ];
            $is_placeholder = (!$is_edit) ? 'is_placeholder' : '';
            TiwForm::normal('upload-img', $user_info['user_profile_image'], ['name' => 'user_profile_image', $is_placeholder => true], $options);
            ?>
          </div>
        <?php } ?>
      </div>
      <?php
      $category_value = [];
      if ($user_type_info['is_has_category']) {
        if (isset($user_info['category_list'])) {
          foreach ($user_info['category_list'] as $data) {
            $category_value[] = $data['category_id'];
          }
        }
        formProfileTemplate('Category', TiwForm::normal('select-tag', $category_value, ['name' => 'category_list', 'placeholder' => 'Please Select', 'id' => 'user_category_event'], $category_options));
      }

      $tag_value = [];
      if ($user_type_info['is_has_tag']) {
        if (isset($user_info['tag_list'])) {
          foreach ($user_info['tag_list'] as $data) {
            $tag_value[] = $data['tag_id'];
          }
        }
        formProfileTemplate('Tag', TiwForm::normal('select-tag', $tag_value, ['name' => 'tag_list', 'placeholder' => 'Please Select', 'id' => 'user_tag_event'], $tag_options));
      }
      ?>
    </div>

    <?php
    //contact detail
    if (
      $user_type_info['is_user_address_type'] ||
      $user_type_info['is_user_address'] ||
      $user_type_info['is_tel'] ||
      $user_type_info['is_has_email'] ||
      $user_type_info['is_has_line'] ||
      $user_type_info['is_has_facebook'] ||
      $user_type_info['is_has_wechat'] ||
      $user_type_info['is_has_instagram']
    ) { //check permission
    ?>
      <div class="contace_detail pt-10px">
        <div class="font-14px text-uppercase font-SemiBold mb-15px">CONTACT DETAIL</div>
        <?php
        //address type
        if ($user_type_info['is_user_address_type']) {
          $address_type_options = ['list_encode' => '[{"value":"Home","name":"Home"},{"value":"Office","name":"Office"}]', 'is_return' => true, 'is_search' => true, 'is_edit' => $is_edit];
          form2col(
            formProfileTemplate('Address Type', TiwForm::normal('select', $user_info['address_type'], ['name' => 'address_type', 'placeholder' => 'Please Select'], $address_type_options), ['is_return' => true])
          );
        }
        //address
        if ($user_type_info['is_user_address']) {
          formProfileTemplate('Address',  TiwForm::normal('textarea', $user_info['address'], ['name' => 'address', 'placeholder' => 'Enter', 'class' => 'mb-0'], ['is_return' => true, 'is_edit' => $is_edit]));
        }
        //tel
        $country_code =  $user_info['tel_country_code'] ? '+' . $user_info['tel_country_code'] : '';
        $tel_html = $user_type_info['is_tel'] ? formProfileTemplate('Telephone Number', TiwForm::normal('tel-flag', $country_code . ' ' . $user_info['tel_no'], ['name' => 'tel_no', 'placeholder' => 'Enter'], ['is_return' => true, 'is_edit' => $is_edit, 'country_code' => 'tel_country_code']), ['is_return' => true]) : '';
        //email
        $email_html = $user_type_info['is_has_email'] ? formProfileTemplate('Email', TiwForm::normal('email', $user_info['email'], ['name' => 'email', 'placeholder' => 'Enter', 'class' => 'check_edit_email_event'], ['is_return' => true, 'is_edit' => $is_edit]), ['is_return' => true]) : '';
        ?>
        <div class="row">
          <div class="col-xl-6">
            <?= $tel_html ?>
          </div>
          <div class="col-xl-6">
            <?= $email_html ?>
            <div class="information-row">
              <div class="col-title"></div>
              <div class="col-detail font-16px text-info">
                <div class="msg_edit_email_event mt--10px mb-10px hidden">
                  <div class="d-flex align-items-start">
                    <div class="mt-3px d-flex"><?= file_get_contents('assets/image/icon/exclmation_mark.svg') ?></div>
                    <span class="font-13px text-danger ml-5px"></span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <?php
        //line
        $line_html = $user_type_info['is_has_line'] ? formProfileTemplate('Line ID', TiwForm::normal('text', $user_info['line_id'], ['name' => 'line_id', 'placeholder' => 'Enter'], ['is_return' => true, 'is_edit' => $is_edit]), ['is_return' => true]) : '';
        //facebook
        $facebook_html = $user_type_info['is_has_facebook'] ? formProfileTemplate('Facebook', TiwForm::normal('text', $user_info['facebook_id'], ['name' => 'facebook_id', 'placeholder' => 'Enter'], ['is_return' => true, 'is_edit' => $is_edit]), ['is_return' => true]) : '';
        form2col($line_html, $facebook_html);
        //wechat
        $wechat_html = $user_type_info['is_has_wechat'] ? formProfileTemplate('Wechat', TiwForm::normal('text', $user_info['we_chat_id'], ['name' => 'we_chat_id', 'placeholder' => 'Enter'], ['is_return' => true, 'is_edit' => $is_edit]), ['is_return' => true]) : '';
        //twitter
        $twitter_html = $user_type_info['is_has_twitter'] ? formProfileTemplate('Twiter', TiwForm::normal('text', $user_info['twitter_id'], ['name' => 'twitter_id', 'placeholder' => 'Enter'], ['is_return' => true, 'is_edit' => $is_edit]), ['is_return' => true]) : '';
        form2col($wechat_html, $twitter_html);
        //instagram
        if ($user_type_info['is_has_instagram']) {
          form2col(
            formProfileTemplate('Instagram', TiwForm::normal('text', $user_info['instagram_id'], ['name' => 'instagram_id', 'placeholder' => 'Enter'], ['is_return' => true, 'is_edit' => $is_edit]), ['is_return' => true])
          );
        }
        ?>
      </div>
    <?php
    } //end if contact detail
    ?>

    <?php
    //Emergency Contact Person
    if (
      $user_type_info['is_has_emergency_contact'] ||
      $user_type_info['is_has_relationship'] ||
      $user_type_info['is_has_emergency_tel']
    ) { //check permission
    ?>
      <div class="emergency_contact_person pt-10px">
        <div class="font-14px text-uppercase font-SemiBold mb-15px">Emergency Contact Person</div>
        <?php
        //name
        $emergency_name_html = $user_type_info['is_has_emergency_contact'] ? formProfileTemplate('Name', TiwForm::normal('text', $user_info['emergency_contact_person']['name'], ['name' => 'emergency_contact_person[name]', 'placeholder' => 'Enter'], ['is_return' => true, 'is_edit' => $is_edit]), ['is_return' => true]) : '';
        //relationship
        $emergency_relationship_hmtl = $user_type_info['is_has_relationship'] ? formProfileTemplate('Relationship info', TiwForm::normal('text', $user_info['emergency_contact_person']['relationship'], ['name' => 'emergency_contact_person[relationship]', 'placeholder' => 'Enter'], ['is_return' => true, 'is_edit' => $is_edit]), ['is_return' => true]) : '';
        form2col($emergency_name_html, $emergency_relationship_hmtl);
        //tel
        if ($user_type_info['is_has_emergency_tel']) {
          form2col(
            formProfileTemplate('Telephone Number', TiwForm::normal('tel-flag', '+' . ($user_info['emergency_contact_person']['tel_country_code'] ? $user_info['emergency_contact_person']['tel_country_code'] : 66) . ' ' . $user_info['emergency_contact_person']['tel_no'], ['name' => 'emergency_contact_person[tel_no]', 'placeholder' => 'Enter'], ['is_return' => true, 'is_edit' => $is_edit, 'country_code' => 'emergency_contact_person[tel_country_code]']), ['is_return' => true])
          );
        }
        ?>
      </div>
    <?php
    } //end if emergency contact person
    ?>

    <?php
    //other_detail
    if (
      $user_type_info['is_has_id_card_no'] ||
      $user_type_info['is_has_passport_no'] ||
      $user_type_info['is_has_passport_country'] ||
      $user_type_info['is_has_nationality'] ||
      $user_type_info['is_has_religion'] ||
      $user_type_info['is_has_gender'] ||
      $user_type_info['is_has_birthdate']
    ) { //check permission
    ?>
      <div class="other_detail pt-10px">
        <div class="font-14px text-uppercase font-SemiBold mb-15px">Other Detail</div>
        <?php
        //id card
        $id_card_html = $user_type_info['is_has_id_card_no'] ? formProfileTemplate('ID Card No.', TiwForm::normal('number', $user_info['identity_card'], ['name' => 'identity_card', 'placeholder' => 'Enter'], ['is_return' => true, 'is_edit' => $is_edit]), ['is_return' => true]) : '';
        //passport
        $passport_html = $user_type_info['is_has_passport_no'] ? formProfileTemplate('Passport No.', TiwForm::normal('text', $user_info['passport_no'], ['name' => 'passport_no', 'placeholder' => 'Enter'], ['is_return' => true, 'is_edit' => $is_edit]), ['is_return' => true]) : '';
        form2col($id_card_html, $passport_html);
        //Country of Passport
        $country_passport_html = $user_type_info['is_has_passport_country'] ? formProfileTemplate('Country of Passport', TiwForm::normal('text', $user_info['passport_country'], ['name' => 'passport_country', 'placeholder' => 'Enter'], ['is_return' => true, 'is_edit' => $is_edit]), ['is_return' => true]) : '';
        //nationality
        $nationality_html = $user_type_info['is_has_nationality'] ? formProfileTemplate('Nationality', TiwForm::normal('text', $user_info['nationality'], ['name' => 'nationality', 'placeholder' => 'Enter'], ['is_return' => true, 'is_edit' => $is_edit]), ['is_return' => true]) : '';
        form2col($country_passport_html, $nationality_html);
        //gender
        $gender_options = ['list_encode' => '[{"value":"Male","name":"Male"},{"value":"Female","name":"Female"}]', 'is_return' => true, 'is_search' => true, 'is_edit' => $is_edit];
        //religion
        $religion_html = $user_type_info['is_has_religion'] ? formProfileTemplate('Religion', TiwForm::normal('text', $user_info['religion'], ['name' => 'religion', 'placeholder' => 'Enter'], ['is_return' => true, 'is_edit' => $is_edit]), ['is_return' => true, 'is_edit' => $is_edit]) : '';
        //gender
        $gender_html = $user_type_info['is_has_gender'] ?  formProfileTemplate('Sex / Gender', TiwForm::normal('select', $user_info['gender'], ['name' => 'gender', 'placeholder' => 'Please Select'], $gender_options), ['is_return' => true]) : '';
        form2col($religion_html, $gender_html);
        //birthdate, age
        if ($user_type_info['is_has_birthdate']) {
          $birth_date = $is_edit ? $user_info['birth_date'] : W_Main::formatDate($user_info['birth_date'], 'd/m/Y');
          form2col(
            formProfileTemplate('Birthday', TiwForm::normal('date', $birth_date, ['name' => 'birth_date', 'class' => 'birth_date_event'], ['is_return' => true, 'is_edit' => $is_edit]), ['is_return' => true]),
            formProfileTemplate('Age', '<span class="text_age_event">' . ($user_info['age'] ? $user_info['age'] . ' years' : '-') . '</span>', ['is_return' => true])
          );
        }
        ?>
      </div>
    <?php
    } //end if other detail
    ?>

  </div>
</form>

<script>
  $(function() {
    select_category_event();
    select_tag_event();

    var __checkh_edit_email = true;

    $(document).on('keyup change', '.check_edit_email_event', async function(e) {
      var email = $(this).val();
      if (email) {
        //check username format
        if (!email.match(/^([a-zA-Z0-9@.])+$/i) || email.indexOf(".") <= 0) {
          __checkh_edit_email = await false;
          checkAllowSaveEvent();
          return false;
        } else {
          __checkh_edit_email = await true;
          checkAllowSaveEvent();
        }
      } else {
        $('.msg_edit_email_event').hide();
        $('.save_edit_user_event').attr('disabled', false);
      }
    });

    function checkAllowSaveEvent() {
      if (!__checkh_edit_email) {
        $('.msg_edit_email_event span').html('Sorry. only letters (a-z), number (0-9), and periods (.) are allowed.');
        $('.msg_edit_email_event').show();
        $('.save_edit_user_event').attr('disabled', true);
        return false;
      }

      $('.msg_edit_email_event').hide();
      $('.save_edit_user_event').attr('disabled', false);
    }

    //คำนวนอายุ
    $(document).on('blur', '.birth_date_event', function(e) {
      var today = new Date();
      var birth_date = new Date($(this).val());
      var age = today.getFullYear() - birth_date.getFullYear();
      var m = today.getMonth() - birth_date.getMonth();
      if (m < 0 || (m === 0 && today.getDate() < birth_date.getDate())) {
        age--;
      }
      $('.text_age_event').html(age + ' years');
    });
  });

  //set select category
  function select_category_event() {
    var category_ids = (true) ? JSON.parse('<?= json_encode($category_value) ?>') : [];
    w.tiwform.setTagData('#user_category_event', category_ids);
  }

  //set select tag
  function select_tag_event() {
    var tag_ids = (true) ? JSON.parse('<?= json_encode($tag_value) ?>') : [];
    w.tiwform.setTagData('#user_tag_event', tag_ids);
  }
</script>