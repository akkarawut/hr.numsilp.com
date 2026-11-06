<?php
$table_column = DB_Init::diagnoseErrorTable();
// W_Main::display($table_column);
// $table_column = isset($table_column['response_status']) ? [] : $count;
$key = 0;
?>
<div class="w-loves-card border-radius-0 pt-0 pb-5px px-10px mb-10px">
  <div class="d-flex">
    <div class="font-15px font-Medium text-info mt-10px mb-20px mr-10px">Data compare : <?= W_Main::formatDate(date('Y-m-d H:i'), 'd/m/Y, H:i'); ?></div>
    <a href="" class="mt-7px">
      <img src="assets/icon/reloading.svg" alt="">
    </a>
  </div>
  <div class="font-16px font-Medium text-info">Compare <span class="text-primary">File => Server</span></div>
  <div class="font-15px font-Regular text-secondary mb-10px">Found on File but don’t have on server.</div>
</div>

<?php if ($table_column) { ?>
  <?php foreach ($table_column as $table => $columns) {
    $count = $columns ? count($columns) : 0;
  ?>
    <div class="w-loves-card border-radius-0 p-0 mb-10px">
      <div class="checking-table">
        <div class="checking-title">
          <div class="th-1 max-w-50px cursor-pointer _event_toggle_hide <?= ($key == 0) ? 'rotate' : '' ?>" onclick="toggleSlide(this)">
            <img src="assets/icon/arrow-down.svg" alt="">
          </div>
          <div class="th-2">
            <div><?= $table ?></div>
            <div class="ml-5px checking-count"><?= $count ?></div>
          </div>
          <button class="th-3 max-w-150px btn btn-success border-radius-0" onclick="addCompareAll(this)">ADD ALL</button>
        </div>
        <div class="checking-all-data <?= ($key == 0) ? '' : 'hide' ?>">
          <?php if ($columns) { ?>
            <?php foreach ($columns as $column => $type) {
              $data_table = [
                $table => [
                  $column => $type
                ]
              ];
            ?>
              <div class="checking-data" data-data_table="<?= base64_encode(json_encode($data_table)); ?>">
                <div class="td-1 max-w-250px"><?= $column ?></div>
                <div class="td-2">
                  <div class="mr-20px">=></div>
                  <div class="text-primary"><?= $type ?></div>
                </div>
                <div class="td-3 max-w-150px">
                  <div class="group-compare-db">
                    <div class="btn btn-delete-db" data-status="drop" onclick="setDataCompare(this)"><img src="assets/icon/icon-bin.svg" alt=""></div>
                    <div class="btn btn-primary" data-status="add" onclick="setDataCompare(this)">ADD</div>
                  </div>
                </div>
              </div>
            <?php } ?>
          <?php } ?>
        </div>
      </div>
    </div>
    <?php $key++; ?>
  <?php } ?>
<?php } ?>

<script>
  // function xhr post
  var xhrPost = function(url, data) {
    var xhr = new XMLHttpRequest();
    xhr.open("POST", url, false);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.send(data);
    return xhr.responseText;
  }

  // function toggleSlide
  function toggleSlide(element) {
    // element rotate
    element.classList.toggle('rotate');
    const _parent = element.closest('.checking-table');
    const _checking_data = _parent.querySelectorAll('.checking-all-data');
    _checking_data.forEach((element) => {
      element.classList.toggle('hide');
      // checking-data check has class
      // if (element.classList.contains('rotate')) {
      //   // element -> img rotate
      //   element.querySelector('img').style.transform = 'rotate(0deg)';
      // } else {
      //   // element.style.display = 'block';
      //   element.querySelector('img').style.transform = 'rotate(180deg)';
      // }
    });
  }

  function setDataCompare(element) {
    // add spinner in element group-compare-db
    const _scope = element.closest('.group-compare-db');
    const _data_status = element.getAttribute('data-status');
    const _parent = element.closest('.checking-data');
    // get data-id
    const _data_table = _parent.getAttribute('data-data_table');
    // set _data array
    const _data = {
      data_table: _data_table,
    };
    _scope.innerHTML = '<div class="spinner-border text-primary spinner-border-sm" role="status"><span class="visually-hidden">Loading...</span></div>';

    if (_data_status == 'add') {
      sendCompareDB(_data, _scope, _data_status);
    } else {
      dropCompareDB(_data, _scope, _data_status);
    }
  }

  function sendCompareDB(_data, _scope, _data_status) {
    // // call xhrPost
    const myRequest = new XMLHttpRequest();
    //send data
    myRequest.onload = () => {
      console.log(myRequest);
      // if and status code is OK
      if (myRequest.status === 200) {
        var result = JSON.parse(myRequest.responseText);
        if (result.response_status) {
          _scope.innerHTML = '<div class="d-flex justify-content-center align-items-center text-primary"><img class="mr-5px" src="assets/icon/icon-check.svg" alt=""><div>Done...</div></div>';
        } else {
          _scope.innerHTML = '<div class="d-flex justify-content-center align-items-center text-danger">Error...</div>';
        }
        // console.log(result);
      } else {
        console.log('Something went wrong')
      }
    }

    myRequest.open('POST', 'ajax/ajax_compare_db.php', true);
    myRequest.setRequestHeader('Content-Type', 'application/json;charset=UTF-8');
    myRequest.send(JSON.stringify(_data));
  }

  function dropCompareDB(_data, _scope, _data_status) {
    // // call xhrPost
    const myRequest = new XMLHttpRequest();
    //send data
    myRequest.onload = () => {
      console.log(myRequest);
      // if and status code is OK
      if (myRequest.status === 200) {
        var result = JSON.parse(myRequest.responseText);
        if (result.response_status) {
          _scope.innerHTML = '<div class="d-flex justify-content-center align-items-center text-danger"><img class="mr-5px" src="assets/icon/icon-close.svg" alt=""><div>Drop...</div></div>';
        } else {
          _scope.innerHTML = '<div class="d-flex justify-content-center align-items-center text-danger">Error...</div>';
        }
        // console.log(result);
      } else {
        console.log('Something went wrong')
      }
    }

    myRequest.open('POST', 'ajax/ajax_drop_db.php', true);
    myRequest.setRequestHeader('Content-Type', 'application/json;charset=UTF-8');
    myRequest.send(JSON.stringify(_data));
  }

  // function addCompareAll
  function addCompareAll(element) {
    // disabled button
    element.setAttribute('disabled', 'disabled');
    const _parent = element.closest('.checking-table');
    const _checking_data = _parent.querySelectorAll('.checking-data');
    _checking_data.forEach((element) => {
      const _scope = element.querySelector('.group-compare-db');

      const _data_table = element.getAttribute('data-data_table');
      const _data = {
        data_table: _data_table,
      };
      _scope.innerHTML = '<div class="spinner-border text-primary spinner-border-sm" role="status"><span class="visually-hidden">Loading...</span></div>';
      sendCompareDB(_data, _scope, 'add');
    });
  }
</script>