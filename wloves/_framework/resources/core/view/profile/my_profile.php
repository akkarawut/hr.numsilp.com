<?php
//controller submit form
if ($_POST) {
  if (isset($_POST['submit_edit_profile'])) {
    unset($_POST['submit_edit_profile']);
    unset($_POST['country_name']);

    $result = W_User::updateUser($user_id, $_POST);

    if ($result['response_status']) {
      if (isset($_FILES['user_profile_image']) && !$_FILES['user_profile_image']['error']) {
        $result = File::add('user_profile_image_' . $user_id, $_FILES['user_profile_image']);
        $result_log['img'] = $result;
      }
      $response_redirect = $url;
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
?>

<form action="" method="post" enctype="multipart/form-data">
  <div class="bg-card br-bottom-10px">
    <div class="p-15px d-flex align-items-center justify-content-between">
      <div>
        <div class="text-uppercase font-Medium text-info">My Profile | <span class="text-primary"><?= $user_info['title'] . ' ' . $user_info['name'] . ' ' . $user_info['surname'] ?></span></div>
        <div class="font-14px text-secondary">Manage your General information profile.</div>
      </div>
      <div class="mb-10px d-flex">
        <?php
        if ($is_edit) {
          echo '<a href="' . $url . '" class="btn btn-light h-35px mr-5px">CANCEL</a>';
          TiwForm::normal('btn', '', ['name' => 'submit_edit_profile'], ['text' => 'SAVE']);
        } else {
          echo '<a href="' . $url . '&is_edit=1">' . TiwForm::normal('btn', '', ['type' => 'button', 'class' => 'btn-outline-info border'], ['text' => 'EDIT DATA', 'type' => '', 'is_return' => true]) . '</a>';
        }
        ?>
      </div>

    </div>
    <hr class="my-0">
    <div class="form-with-profile">
      <div class="form-profile">
        <div class="font-14px font-Medium text-info mb-10px">IMAGE</div>
        <?php
        $profile_options = [
          'width' => '140px',
          'height' => '100%',
          'bg-img' => '../../_framework/modules/tiwform/img/upload-profile.svg',
          'is_delete' => false,
          'is_upload' => $is_edit,
        ];
        TiwForm::normal('upload-img', $user_info['user_profile_image'], ['name' => 'user_profile_image'], $profile_options);
        ?>
      </div>
      <div class="form-detail">
        <div class="font-14px font-Medium text-info mb-10px text-uppercase">General information</div>
        <div class="font-20px font-Medium text-info mb-15px"><?= $user_info['title'] . ' ' . $user_info['name'] . ' ' . $user_info['surname'] ?></div>
        <div class="row">
          <?php
          profileTemplate('Title', TiwForm::normal('select-title', $user_info['title'], ['name' => 'title', 'placeholder' => 'Select', 'required' => true], ['is_return' => true, 'is_search' => true, 'is_edit' => false]));
          echo '<div class="col-lg-6"></div>';
          profileTemplate('Name', TiwForm::normal('text', $user_info['name'], ['name' => 'name', 'placeholder' => 'Enter', 'required' => true], ['is_return' => true, 'is_edit' => false]));
          profileTemplate('Surname', TiwForm::normal('text', $user_info['surname'], ['name' => 'surname', 'placeholder' => 'Enter', 'required' => true], ['is_return' => true, 'is_edit' => false]));
          profileTemplate('Preferred name', TiwForm::normal('text', $user_info['nick_name'], ['name' => 'nick_name', 'placeholder' => 'Enter', 'class' => 'mb-0'], ['is_return' => true, 'is_edit' => $is_edit]));
          ?>
        </div>
      </div>
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
      <div class="contace_detail p-15px">
        <div class="font-14px text-uppercase font-SemiBold mb-10px">CONTACT DETAIL</div>
        <?php
        //address type
        if ($user_type_info['is_user_address_type']) {
          $address_type_options = ['list_encode' => '[{"value":"Home","name":"Home"},{"value":"Office","name":"Office"}]', 'is_return' => true, 'is_search' => true, 'is_edit' => $is_edit];
          form2col(
            formProfileTemplate('Address Type', TiwForm::normal('select', $user_info['address_type'], ['name' => 'address_type', 'placeholder' => 'Please Select', 'class' => 'mb-10px'], $address_type_options), ['is_return' => true])
          );
        }
        //address
        if ($user_type_info['is_user_address']) {
          formProfileTemplate('Address',  TiwForm::normal('textarea', $user_info['address'], ['name' => 'address', 'placeholder' => 'Enter', 'class' => 'mb-10px'], ['is_return' => true, 'is_edit' => $is_edit]));
        }
        //tel
        $country_code =  $user_info['tel_country_code'] ? '+' . $user_info['tel_country_code'] : '';
        $tel_html = $user_type_info['is_tel'] ? formProfileTemplate('Telephone Number', TiwForm::normal('tel-flag', $country_code . ' ' . $user_info['tel_no'], ['name' => 'tel_no', 'placeholder' => 'Enter'], ['is_return' => true, 'is_edit' => $is_edit, 'country_code' => 'tel_country_code']), ['is_return' => true]) : '';
        //email
        $email_html = $user_type_info['is_has_email'] ? formProfileTemplate('Email', TiwForm::normal('email', $user_info['email'], ['name' => 'email', 'placeholder' => 'Enter'], ['is_return' => true, 'is_edit' => $is_edit]), ['is_return' => true]) : '';
        form2col($tel_html, $email_html);
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
            formProfileTemplate('Instagram', TiwForm::normal('text', $user_info['instagram_id'], ['name' => 'instagram_id', 'placeholder' => 'Enter'], ['is_return' => true, 'is_edit' => $is_edit]), ['is_return' => true]),
          );
        }
        ?>
      </div>
    <?php
    } //end if contact detail
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
      <div class="other_detail p-15px">
        <div class="font-14px text-uppercase font-SemiBold mb-10px">Other Detail</div>
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
</script>