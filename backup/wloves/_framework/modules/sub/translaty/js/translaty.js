
let language
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
let url_check = document.getElementsByName('url_translaty')[0];
let is_front = url_check.search('module') == -1 ? true : false;
let prefix = is_front ? '../' : '../../../';
//? for modal
let btn_translaty = document.getElementById('btn-translaty');
let ele_modal_list = document.getElementsByClassName('event-modal-translaty');
let key_modal_list = Object.keys(ele_modal_list);

$(document).ready(() => {
  // setTimeout(() => {
  //   btn_translaty.classList.remove('d-none')
  // }, 2000);
  // genShortTextLanguage()
  $(document).on('change', 'input[name="select_language_name"]', function (e) {
    genShortTextLanguage()
  });
  $(document).on('click', 'button[name="btn_addlg"]', function (e) {
    showAddLanguage()
  });
  $(document).on('click', 'button[name="btn_dellg"]', function (e) {
    showDelLanguage()
  });
  $(document).on('click', 'button[name="btn_gentable"]', function (e) {
    ele_btn_post.click()
  });
  $(document).on('click', 'button[name="btn_import_file"]', function (e) {
    $('.file_import_translaty').click();
  });
  $(document).on('click', 'button[name="add_word_modal"]', function (e) {
    console.log('test')
    addWord();
  });
  $(document).on('click', 'button[name="edit_word_modal"]', function (e) {
    editWord();
  });
  $(document).on('click', '.event-copy-keyword', function (e) {
    navigator.clipboard.writeText("Ty::get('" + e.currentTarget.value + "')");
    showCopyNoti()
  });
  $(document).on('change', '.file_import_translaty', function (e) {
    $('#import_translaty').submit();
  });
  $(document).on('click', '.btn-translaty-panel', function (e) {
    showModalTraslaty(true);
  });
  $(document).on('click', '.event-close-translaty-modal', function (e) {
    showModalTraslaty(false);
  });
  $(document).on('click', '.event-disable', function (e) {
    $(this).submit();
    let target = $(this)
    let input_target = $(this).parent().find('.select-value-area').find('input').val();
    if (input_target != '') {
      setTimeout(function () {
        target.prop('disabled', true);
      }, 500)
      setTimeout(function () {
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
    let url = prefix + '_framework/module/translaty/' + 'ajax/ajax_addword.php';
    console.log(url);
    let params = data_addword;
    await $.post(url, params).done(function (data) {
      console.log(data)
      let result = JSON.parse(data);
      w.notification(result['response_status'], result['response_message']);
      if (result['response_status'] == 'success') {
        let focusItem = document.getElementsByClassName('event-focus-input')[0];
        focusItem.focus();
        resetInputAddModal()
      }
      return;
    });
    await refreshTable();
  }
  async function editWord() {
    let data_addword = serializeJson($('#editword_input'));
    let url = prefix + '_framework/module/translaty/' + 'ajax/ajax_editword.php';
    let params = data_addword;
    await $.post(url, params).done(function (data) {
      console.log(data)
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
  statement_page = $('#word_list .select-page').val() ? parseInt($('#word_list .select-page').val()) - 1 : 0;
  statement_size = parseInt($('#word_list .page-size').val());
  $("#word_list").pagify(statement_size, 0, 'Word List', '', {}, statement_page);
  return 0;
}