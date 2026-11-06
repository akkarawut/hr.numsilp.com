<?php
$department_options = [
  'list' => [
    [
      'value' => '',
      'name' => 'Select',
      'disabled' => true
    ],
  ]
];
foreach ($department as $department_list) {
  $department_options['list'][] = [
    'value' => $department_list['id'],
    'name' => $department_list['name'],
  ];
}
$working_shift_options = [
  'list' => [
    [
      'value' => '',
      'name' => 'Select',
      'disabled' => true
    ],
  ]
];
foreach ($working_shift as $working_shift_list) {
  $working_shift_options['list'][] = [
    'value' => $working_shift_list['id'],
    'name' => $working_shift_list['name'],
  ];
}
$leader_options = [
  'list' => [
    [
      'value' => '',
      'name' => 'Select',
      'disabled' => true
    ],
  ]
];
foreach ($leader as $leader_list) {
  $leader_options['list'][] = [
    'value' => $leader_list['id'],
    'name' => $leader_list['full_name'],
  ];
}
$subordinate_options = [];
foreach ($subordinate as $subordinate_list) {
  $subordinate_options['list'][] = [
    'value' => $subordinate_list['id'],
    'name' => $subordinate_list['full_name'],
    'selected' => (in_array($subordinate_list['id'], $user_workplace['subordinate_ids'])) ? true : false
  ];
}
?>
<div class="row">
  <div class="col-lg-12">
    <div class="title-detail">
      <div>
        <h3><?= Itlanguage::translate('WORKING DETAIL'); ?> </h3>
        <p><?= Itlanguage::translate('User working detail.'); ?></p>
      </div>
    </div>

    <div class="form-group-detail">
      <div class="form-title">
        <h5 class="">GENERAL</h5>
      </div>
      <div class="form-detail">
        <?php if ($status == 'edit') { ?>
          <form method="post" id="form_edit_working">
            <input type="hidden" name="submit_edit_working">
            <div class="row">
              <div class="col-md-6">
                <div class="row">
                  <div class="col-md-4">
                    <label class="">Employment Entry Date</label>
                  </div>
                  <div class="col-md-8">
                    <?= TiwForm::normal('date', $user_workplace['working_date_from'], ['name' => 'working_date_from', 'class' => 'read-progress']); ?>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="row">
                  <div class="col-md-4">
                    <label class="">Min Monthly Working time</label>
                  </div>
                  <div class="col-md-8">
                    <div class="row">
                      <div class="col">
                        <?= TiwForm::normal('number', $user_workplace['hours_per_month'], ['name' => 'hours_per_month', 'placeholder' => 'Hour', 'class' => 'read-progress']); ?>
                      </div>
                      <div class="col">
                        <?= TiwForm::normal('number', $user_workplace['minutes_per_month'], ['name' => 'minutes_per_month', 'placeholder' => 'Min', 'class' => 'read-progress']); ?>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="row">
                  <div class="col-md-4">
                    <label class="mt-7px">Working Shift</label>
                  </div>
                  <div class="col-md-8">
                    <?php
                    TiwForm::normal('select', $user_workplace['working_shift_id'], ['name' => 'working_shift_id', 'class' => 'read-progress'], $working_shift_options);
                    ?>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="row">
                  <div class="col-md-4">
                    <label class="mt-7px">Default Salary</label>
                  </div>
                  <div class="col-md-8">
                    <div class="d-flex">
                      <span class="mr-5px mt-7px">$ </span>
                      <?= TiwForm::normal('number', $user_workplace['default_salary'], ['name' => 'default_salary', 'placeholder' => '0.00', 'class' => 'read-progress']); ?>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="row">
                  <div class="col-md-4">
                    <label class="mt-7px">Department</label>
                  </div>
                  <div class="col-md-8">
                    <?php
                    TiwForm::normal('select', $user_workplace['department_id'], ['name' => 'department_id', 'class' => 'read-progress'], $department_options);
                    ?>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="row">
                  <div class="col-md-4">
                    <label class="mt-7px">Position</label>
                  </div>
                  <div class="col-md-8">
                    <?= TiwForm::normal('text', $user_workplace['position'], ['name' => 'position', 'placeholder' => 'Enter', 'class' => 'read-progress']); ?>
                  </div>
                </div>
              </div>
              <div class="col-md-12">
                <div class="row">
                  <div class="col-md-2">
                    <label class="mt-7px">Leader</label>
                  </div>
                  <div class="col-md-10">
                    <?php

                    TiwForm::normal('select', $user_workplace['leader_id'], ['name' => 'leader_id', 'placeholder' => 'Filter Data', 'class' => 'read-progress'], $leader_options);
                    ?>
                  </div>
                </div>
              </div>
              <div class="col-md-12">
                <div class="row">
                  <div class="col-md-2">
                    <label class="mt-7px">Subordinate</label>
                  </div>
                  <div class="col-md-10">
                    <?php
                    TiwForm::normal('select-tag', $user_workplace['subordinate_ids'], ['name' => 'subordinate_ids', 'placeholder' => 'Filter Data', 'class' => 'read-progress read-progress-subordinate'], $subordinate_options);
                    ?>
                  </div>
                </div>
              </div>
            </div>
          </form>
        <?php } else { ?>
          <div class="row">
            <div class="col-md-6">
              <div class="row">
                <div class="col-md-4">
                  <label class="">Employment Entry Date</label>
                </div>
                <div class="col-md-8">
                  <p class="text_progress">
                    <?php if (isset($user_workplace['working_date_from']) || $user_workplace['working_date_from'] != '') { ?>
                      <?= W_Main::formatDate($user_workplace['working_date_from'], 'd/m/Y');  ?>
                    <?php } else { ?>
                      -
                    <?php } ?>
                  </p>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="row">
                <div class="col-md-4">
                  <label class="">Min Monthly Working time</label>
                </div>
                <div class="col-md-8">
                  <div class="d-flex">
                    <p class="text_progress mr-5px"><?= $user_workplace['hours_per_month'] ?> hr </p>
                    <p class="text_progress"><?= $user_workplace['minutes_per_month'] ?> min</p>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="row">
                <div class="col-md-4">
                  <label class="">Working Shift</label>
                </div>
                <div class="col-md-8">
                  <?php $work_shift_name = Working_shift::getWorkingShiftByID($user_workplace['working_shift_id']); ?>
                  <p class="text_progress"><?= (isset($work_shift_name['name'])) ? $work_shift_name['name'] : ''; ?></p>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="row">
                <div class="col-md-4">
                  <label class="">Default Salary</label>
                </div>
                <div class="col-md-8">
                  <div class="d-flex">
                    <p class=" mr-5px"> $</p>
                    <p class="text_progress"><?= number_format($user_workplace['default_salary'], 2) ?></p>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="row">
                <div class="col-md-4">
                  <label class="">Department</label>
                </div>
                <div class="col-md-8">
                  <?php $department_name =  Department::getDepartmentByID($user_workplace['department_id']); ?>
                  <p class="text_progress"><?= (isset($department_name['name'])) ? $department_name['name'] : ''; ?></p>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="row">
                <div class="col-md-4">
                  <label class="">Position</label>
                </div>
                <div class="col-md-8">
                  <p class="text_progress"><?= $user_workplace['position'] ?></p>
                </div>
              </div>
            </div>
            <div class="col-md-12">
              <div class="row">
                <div class="col-md-2">
                  <label class="">Leader</label>
                </div>
                <div class="col-md-10">
                  <div class="d-flex flex-wrap py-5px text_progress">
                    <?php foreach ($user_workplace['user_list'] as $user_leader_list) {
                      if ($user_workplace['leader_id'] == $user_leader_list['id']) {
                    ?>
                        <form method="post">
                          <div class="tag-input-form mr-5px mb-5px">
                            <div class="tag-img mr-5px">
                              <img src="<?= $user_leader_list['user_profile_image'] ?>" alt="">
                            </div>
                            <span class="tag-name"><?= $user_leader_list['full_name'] ?></span>
                            <button type="submit" class="btn-remove-tag" name="submit_remove_leader">
                              <?= file_get_contents('assets/image/icon/icon-close.svg'); ?>
                            </button>
                          </div>
                        </form>
                    <?php }
                    } ?>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-12">
              <div class="row">
                <div class="col-md-2">
                  <label class="">Subordinate</label>
                </div>
                <div class="col-md-10">
                  <div class="d-flex flex-wrap py-5px text_progress">
                    <?php foreach ($user_workplace['user_list'] as $user_subordinate_list) {
                      if (in_array($user_subordinate_list['id'], $user_workplace['subordinate_ids'])) {
                    ?>
                        <form method="post">
                          <div class="tag-input-form mr-5px mb-5px">
                            <div class="tag-img mr-5px">
                              <img src="<?= $user_subordinate_list['user_profile_image'] ?>" alt="">
                            </div>
                            <span class="tag-name"><?= $user_subordinate_list['full_name'] ?></span>
                            <input type="hidden" name="subordinate_id" value="<?= $user_subordinate_list['id'] ?>">
                            <input type="hidden" name="submit_remove_subordinate">
                            <button type="submit" class="btn-remove-tag">
                              <?= file_get_contents('assets/image/icon/icon-close.svg'); ?>
                            </button>
                          </div>
                        </form>
                    <?php }
                    } ?>
                  </div>
                </div>
              </div>
            </div>
          </div>
        <?php } ?>
      </div>
    </div>
  </div>
</div>