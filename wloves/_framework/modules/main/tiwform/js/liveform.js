$(function () {
  $(document).on("click", ".btn_edit_event", function (e) {
    e.preventDefault();
    var ele = $(this).parents(".live-form");
    ele.find(".form-group").show();
    ele.find(".form-content").hide();
    ele.find(".btn_edit_event").hide();
    ele.find(".btn_confirm_event").css("display", "flex");
    ele.find(".live_form_event").focus();
  });

  $(document).on("dblclick", ".live_form_db_click_event", function (e) {
    var ele = $(this).parents(".live-form");
    ele.find(".form-group").show();
    ele.find(".form-content").hide();
    ele.find(".btn_edit_event").hide();
    ele.find(".btn_confirm_event").css("display", "flex");
    ele.find(".live_form_event").focus();
  });

  $(document).on("click", ".btn_confirm_event", function (e) {
    e.preventDefault();
    var ele = $(this).parents(".live-form");
    callApiDataEvent(ele);
  });

  $(document).on("keyup", ".live_form_event", function (e) {
    var ele = $(this).parents(".live-form");
    if (e.which == 13) {
      callApiDataEvent(ele);
    }
    if (e.which == 27) {
      var pre_data = ele.find(".live_form_event").attr("data-pre");
      ele.find(".live_form_event").val(pre_data);
      resetEditFormEvent(ele);
    }
  });

  $(document).on("change", ".live_radio_event input", function (e) {
    var ele = $(this).parents(".live-form");
    callApiRadioEvent(ele);
  });

  function callApiRadioEvent(ele) {
    var uri = window.location.href;
    var uri = uri.split("/");
    var index_framework = uri.indexOf("_framework");
    if (index_framework != -1) {
      url = "../../../_framework/modules/main/tiwform/ajax/ajax_call_api.php";
    } else {
      url = "../../_framework/modules/main/tiwform/ajax/ajax_call_api.php";
    }
    var params = ele.attr("data-register");
    if (params) {
      params = jQuery.parseJSON($.base64.decode(params));
    } else {
      params = [];
    }
    var data_key = ele.find(".live_radio_event input").attr("name");
    var swap_value = ele.find(".live_radio_event input").attr("swap_value");
    var data_edit = "0";
    if (ele.find("input").prop("checked")) {
      data_edit = "1";
    }
    if (swap_value) {
      data_edit = "1";
      if (ele.find("input").prop("checked")) {
        data_edit = "0";
      }
    }
    var all_params = {
      params: params,
      data_key: data_key,
      data_edit: data_edit,
    };
    $.post(url, all_params)
      .done(function (data) {
        let result = JSON.parse(data);
        if (result.response_status == true) {
          w.notification("success", result.response_message);
        } else {
          w.notification("error", result.response_message);
        }
      })
      .fail(function (xhr, status, error) {
        console.log(error);
      });
  }

  function callApiDataEvent(ele) {
    var uri = window.location.href;
    var uri = uri.split("/");
    var index_framework = uri.indexOf("_framework");
    if (index_framework != -1) {
      url = "../../../_framework/modules/main/tiwform/ajax/ajax_call_api.php";
    } else {
      url = "../../_framework/modules/main/tiwform/ajax/ajax_call_api.php";
    }
    var params = ele.attr("data-register");
    if (params) {
      params = jQuery.parseJSON($.base64.decode(params));
    } else {
      params = [];
    }
    var input_type = ele.find(".live_form_event").attr("type");
    var data_edit = ele.find(".live_form_event").val();
    if (input_type == "email" && !validateEmail(data_edit)) {
      w.notification("error", "Enter the correct email.");
      return false;
    }
    var data_key = ele.find(".live_form_event").attr("name");
    var all_params = {
      params: params,
      data_key: data_key,
      data_edit: data_edit,
    };
    $.post(url, all_params)
      .done(function (data) {
        let result = JSON.parse(data);
        if (result.response_status == true) {
          //แทนที่ข้อมูลที่แก้ไข
          var data_edit = ele.find(".live_form_event").val();
          ele.find(".live_form_event").attr("data-pre", data_edit);
          if (ele.find("select").length > 0) {
            data_edit = ele.find(".live_form_event option:selected").text();
          }
          ele.find(".form-content").html(data_edit).trigger("change");

          resetEditFormEvent(ele);
          w.notification("success", result.response_message);
        } else {
          ele.find(".live_form_event").focus();
          w.notification("error", result.response_message);
        }
      })
      .fail(function (xhr, status, error) {
        console.log(error);
      });
  }

  function resetEditFormEvent(ele) {
    ele.find(".form-group").hide();
    ele.find(".form-content").show();
    ele.find(".btn_edit_event").show();
    ele.find(".btn_confirm_event").hide();
  }
});

function validateEmail($email) {
  var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
  return emailReg.test($email);
}
