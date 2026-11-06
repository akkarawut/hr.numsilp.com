<?php
$options = [
  'list' => [
    [
      'value' => '',
      'name' => 'เลือกประเภท',
      'disabled' => true
    ],
    [
      'value' => 1,
      'name' => 'Wolves'
    ],
    [
      'value' => 2,
      'name' => 'Milkclub'
    ],
    [
      'value' => 3,
      'name' => 'Dinner'
    ],
  ]
];
?>
<div class="content-header-container">
  <h3 class="header-title text-pri">Generator Nav</h3>
</div>
<div class="content-body-container">
  <div class="form-group">
    <form method="post" id="generator_form">
      <label for="">Type Nav</label>
      <?= TiwForm::normal('select', '', ['name' => 'type'], $options); ?>
      <div class="title_wolves hidden">
        <label for="">Title</label>
        <?= TiwForm::normal('text', '', ['name' => 'title', 'placeholder' => 'หัวเมนู', 'disabled' => '']); ?>
      </div>
      <label for="">Param Name</label>
      <?= TiwForm::normal('text', '', ['name' => 'param_name', 'placeholder' => 'ตัวแปร $_GET']); ?>
      <div class="form-group">
        <div class="gen_form_event"></div>
        <div class="col-12 mt-40px">
          <?php
          TiwForm::normal('btn', '', ['class' => 'w-100 btn-primary generate_event', 'type' => 'button'], ['text' => 'Generate']);
          ?>
        </div>
      </div>
    </form>
  </div>
</div>
<div class="content-body-container">
  <div class="review bg-light  rounded"></div>
</div>

<script>
  $(document).ready(function() {

    $(document).on('click', '.add_list_event', function(e) {
      var clone = $('.list_template_event').html();
      var len = $('.list_area_event  .other_list_event').length;
      $('.list_area_event').append('<div class="d-flex align-items-end   other_list_event w-100 list_' + len + '">' + clone + '</div>');
      $('.list_' + len + ' .demo_name_event').attr('name', 'list_name[]');
      $('.list_' + len + ' .demo_image_event').attr('name', 'list_img[]');
      $('.list_' + len + ' .demo_select_color_event').attr('name', 'list_color_status[]');
      $('.list_' + len + ' .demo_text_status_event').attr('name', 'list_text_status[]');
    });

    $(document).on('click', '.add_list_type2_event', function(e) {
      var clone = $('.list_template_event').html();
      var len = $('.list_area_event  .other_list_event').length;
      $('.list_area_event').append('<div class="other_list_event  list_template_main w-100 list_' + (len + 1) + '" data-idx="' + (len + 1) + '">' + clone + '</div>');
      $('.list_' + (len + 1) + ' .demo_name_event').attr('name', 'data[' + (len + 1) + '][menu]');
      $('.list_' + (len + 1) + ' .demo_image_event').attr('name', 'data[' + (len + 1) + '][img]');
      $('.list_' + (len + 1) + ' .demo_name_sub_event').attr('name', 'data[' + (len + 1) + '][list][][menu]');
      $('.list_' + (len + 1) + ' .demo_image_sub_event').attr('name', 'data[' + (len + 1) + '][list][][img]');
    });

    $(document).on('click', '.add_list_sub_event', function(e) {
      var main = $(this).parents('.list_template_main');
      var idx = main.data('idx');
      var clone = $('.list_template_sub_event').html();
      var len = $('.list_area_event .other_list_event ').length;
      main.find('.list_area_sub_event').append('<div class="d-flex align-items-end other_list_sub_event w-100 list_' + idx + '">' + clone + '</div>');
      $('.list_' + idx + ' .demo_name_sub_event').attr('name', 'data[' + (idx) + '][list][][menu]');
      $('.list_' + idx + ' .demo_image_sub_event').attr('name', 'data[' + (idx) + '][list][][img]');
    });

    $('select[name="type"]').on("change", function() {
      if ($(this).val() == 1) {
        $('.title_wolves').show();
        $('.title_wolves').find('input').prop('disabled', false);
      } else {
        $('.title_wolves').hide();
        $('.title_wolves').find('input').prop('disabled', true);
      }
      var url = 'view/ajax/ajax_generate_nav_form.php';
      var params = {
        type: $(this).val()
      };
      $.post(url, params).done(function(data) {
        $('.gen_form_event').html(data);
        setTagAutoEvent();
      });
    });
    $(document).on('click', '.delete_list_event', function(e) {
      var scope = $(this).parents('.other_list_event');
      scope.remove();
    });
    $(document).on('click', '.delete_list_sub_event', function(e) {
      var scope = $(this).parents('.other_list_sub_event');
      scope.remove();
    });

    $(document).on('click', '.generate_event', function(e) {
      var url = 'view/ajax/ajax_generator_nav.php';
      var data = {
        data_form: $('#generator_form').serializeJSON(),
        link: 'school.php?topic=300&sub=304'
      };
      $.post(url, data).done(function(response) {
        $('.review').html(response);
      });
    });
  });
</script>