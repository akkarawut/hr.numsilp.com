<?php
$_PAGE['permission']  = ['landing', '', ''];
$prefix = '../../../';
require $prefix . '_framework/import.php';

?>

<!DOCTYPE html>
<html lang="en" data-theme="<?= W_Structure::getThemeClass() ?>">

<head>
  <?php
  W_Structure::loadMeta($prefix);
  W_Main::loadAsset('../../structure/css/animate.css');
  ?>
</head>

<body>
  <?php include '../layout/header-default.php'; ?>

  <button class="btn btn-warning" id="button_slide">Toggle Slide</button>

  <?= TiwForm::normal('btn', '', ['type' => 'button', 'class' => 'w-100'], ['text' => '+ ADD NEW MODULE', 'modal_id' => 'test', 'modal_data' => [], 'is_ajax' => true, 'prefix' => '']); ?>

  <div class="card">
    <div class="container p-3">
      <h1 id="hello">Hello, world!</h1>
      <h1 id="date"></h1>
      <h1 id="tel"></h1>
      <h1 id="number"></h1>
      <form id="form_submit" method="post" enctype="multipart/form-data">
        <input type="text" id="test1" class="form-control test1" name="input1" value="asdasdasd" atr="111111111" placeholder="Data from select1">
        <input type="text" id="test2" class="form-control" name="input2" value="" placeholder="Data from select2">
        <input type="text" id="test3" class="form-control" name="input3" value="" placeholder="Data from click test">
        <input type="text" id="test4" class="form-control" name="input4" value="" placeholder="Data from change file">
        <input type="file" id="test5" class="form-control" name="input5" value="">

        <button type="button" id="button1" class="btn btn-primary button" _event_click>Button1</button>
        <button type="button" id="button2" class="btn btn-secondary button">Button2</button>
        <button type="button" id="button3" class="btn btn-warning button3">Button3</button>
      </form>

      <div class="form-check">
        <label class="form-check-label">
          <input type="checkbox" class="form-check-input" name="" id="" value="checkedValue1" checked>
          Display value
        </label>
      </div>
      <div class="form-check">
        <label class="form-check-label">
          <input type="checkbox" class="form-check-input" name="" id="" value="checkedValue2">
          Display value
        </label>
      </div>

      <select name="" id="select1" class="form-control change_select">
        <option value="1" data-test='test1'>1</option>
        <option value="2" data-test='test2'>2</option>
        <option value="3" data-test='test3'>3</option>
        <option value="4" data-test='test4'>4</option>
        <option value="5" data-test='test5'>5</option>
      </select>
      <select name="" id="select2" class="form-control change_select">
        <option value="11" data-test='test11'>1</option>
        <option value="22" data-test='test22' selected>2</option>
        <option value="33" data-test='test33'>3</option>
        <option value="44" data-test='test44'>4</option>
        <option value="55" data-test='test55'>5</option>
      </select>

      <div class="show_ajax" id="show_ajax"></div>

      <div class="show_append my-5" id="show_append"></div>

      <tag>Test</tag>

      <div id="scope1" data-test="1">
        <div id="scope2" data-test="2">
          <div id="scope3" data-test="3">
            BB
          </div>
        </div>
      </div>

      <a href="">Link</a>

      <!-- Button trigger modal -->
      <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal" id="show_modal">
        Launch demo modal
      </button>

      <!-- Button trigger modal -->
      <button type="button" class="btn btn-primary" id="show_modal2">
        Launch demo modal 2
      </button>
    </div>
  </div>

  <!-- Modal -->
  <?php Tiwdal::startModal('exampleModal'); ?>
  <form action="" method="post">
    <div class="modal-header">
      <h1 class="modal-title fs-5" id="exampleModalLabel">Modal title</h1>
      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body">
      <input type="text" class="ok_modal" value="OK modal T">
      <input type="text" class="ok_modal" value="OK modal R">
      <div>
        <?php
        $options = [
          'is_search' => true,
          'list' => [
            [
              'value' => '1',
              'name' => '1',
            ],
            [
              'value' => '2',
              'name' => '2',
            ],
            [
              'value' => '3',
              'name' => '3',
            ],
          ],
        ];
        TiwForm::normal('select', '2', ['name' => ''], $options);
        ?>
      </div>
    </div>
    <div class="modal-footer">
      <button type="button" id="button_close" class="btn btn-secondary">Close MODAL</button>
      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      <button type="button" class="btn btn-primary">Save changes</button>
    </div>
  </form>

  <?php Tiwdal::endModal(); ?>

  <?php Tiwdal::ajaxModal('test', 'modal-md'); ?>

  <?php
  include '../layout/footer.php';
  W_Structure::loadFooter($prefix);
  ?>
  <script>
    w('#form_submit').serializeJSON();
    console.log(w('#form_submit').serializeJSON());
    // var completed = true;

    // // Select the element that you want to observe for changes
    // const targetNode = document.querySelector('body');

    // // Create an observer instance
    // const observer = new MutationObserver(function(mutations) {
    //   mutations.forEach(function(mutation) {
    //     console.log('A mutation has occurred:', mutation.type);

    //     window.addEventListener("load", (event) => {
    //       document.removeEventListener("DOMContentLoaded", completed);
    //     });
    //   });
    // });

    // // Configuration of the observer:
    // const config = {
    //   attributes: true,
    //   childList: true,
    //   subtree: true
    // };

    // // Start observing the target element for specified changes
    // observer.observe(targetNode, config);

    // Function to disconnect the observer
    // function disconnectObserver() {
    //   observer.disconnect();
    // }

    // var test = w('#select1');
    // console.log(test);
    w.ready(function() {

      // $(document).ready(function() {
      w(document).on('change', '.change_select', function(e) {
        console.log('event select1');
        wx.post('ajax.php', 'params', function(data) {
          w('.show_ajax').html(data);
          // w.toggleEvent();
        });
      });

      w(document).on('click', '#button_slide', function() {
        w(this).animate({
          width: '400px',
          height: '100px',
        }, 5000);
      });

      w(document).on('click', '#button1', function() {
        console.log('button1');
        // w('#exampleModal').modal('show');
        w('#show_append').append('<input class="form-control keyup_form">');
      });

      w(document).on('click', '#button_close', function() {
        console.log('button_close');
        w('#exampleModal').modal('hide');
        // w('#show_append').append('<input class="form-control keyup_form">');
      });

      w(document).on('change', '#select_ajax', function() {
        console.log('change select ajax');
      });



      // resize
      // w.resize(function(data) {
      //   console.log(data);
      //   w('#exampleModal').modal('show');
      // });

      w(document).on('hidden.bs.modal', '#exampleModal', function() {
        console.log('hidden.bs.modal');
      });
      // }

    });

    // function keyup() {
    w.on('keyup', '.keyup_form', function() {
      console.log(w(this).val());
    });


    // w('#button1').trigger('click');
  </script>
</body>

</html>