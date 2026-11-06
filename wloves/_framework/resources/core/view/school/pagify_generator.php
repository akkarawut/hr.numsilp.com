<div class="content-header-container">
  <h3 class="header-title">Generator Table</h3>
</div>
<div class="p-3">
  <form method="post" id="generator_form">
    <div class="col-12">
      <label for="">ID</span></label>
      <?= TiwForm::normal('text', '', ['name' => 'homepagiy'], []); ?>
    </div>
    <div class="col-12 font-Medium bg-card-tap py-10px">
      Thead
    </div>
    <div class="col-12">
      <label for="">Name</span></label>
      <?= TiwForm::normal('text', '', ['name' => 'homepagiy'], []); ?>
    </div>
    <div class="form-group col-12">
      <div class="gen_form_event"></div>
      <div class="col-12 mt-40px">
        <?php
        TiwForm::normal('btn', '', ['class' => 'w-100 btn-primary generate_event', 'type' => 'button'], ['text' => 'Generate']);
        ?>
      </div>
    </div>
  </form>
</div>



<?php Tiwdal::startModal('delete_modal'); ?>
<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
  <span aria-hidden="true">&times;</span>
</button>
<form method="post">
  <div class="modal-body">
    <div class="col-12 form-group text-center">
      <p class="text-danger mb-5px mt-20px">Delete data</p>
      <p>
        Are you sure to delete
      </p>
      <input type="text" name="checked_list" class="form-control">
    </div>
  </div>
</form>

<?php Tiwdal::endModal() ?>

<script>
  $(document).ready(function() {
    $('#delete_modal').on('shown.bs.modal', function() {
      var checked_list = valueChecklist('example2');
      //นำค่าไปใส่ input ชื่อ checked_list ใน modal
      $("input[name='checked_list']").val(checked_list);

    });
  });
</script>