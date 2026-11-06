$(function () {
  autoFormSet();
});

function autoFormSet() {
  var len = $("[auto-form]").length;
  for (var i = 0; i < len; i++) {
    var this_ele = $($("[auto-form]")[i]);
    var params = jQuery.parseJSON($.base64.decode(this_ele.attr("auto-form")));
    autoFormEvent(params, this_ele);
  }
}

var cloneDataModal = [];
function autoFormEvent(params, ele_name) {
  if (!cloneDataModal[ele_name.attr("id")]) {
    cloneDataModal[ele_name.attr("id")] = ele_name.html();
  } else {
    ele_name.html(cloneDataModal[ele_name.attr("id")]);
  }
  __replaceForm(params, ele_name);
}

function __replaceForm(params, ele_name) {
  $.each(params, function (key, value) {
    $is_name = 0;
    if (ele_name.find('input[name="{' + key + '}"]').length > 0) {
      //input
      let input_type = ele_name
        .find('input[name="{' + key + '}"]')
        .attr("type");
      if (input_type == "checkbox") {
        //type checkbox
        if (value != 0) {
          ele_name.find('input[name="{' + key + '}"]').attr("checked", true);
        } else {
          ele_name.find('input[name="{' + key + '}"]').attr("checked", false);
        }
      } else if (input_type == "radio") {
        //type radio
        let element_radio = ele_name.find('input[name="{' + key + '}"]');
        for (var i = 0; i < element_radio.length; i++) {
          if ($(element_radio[i]).val() == value) {
            $(element_radio[i]).attr("checked", true);
          }
        }
      } else if (input_type == "tel") {
        var tel_input = ele_name.find('input[name="{' + key + '}"]');
        var tel_scope = tel_input.parents(".telephone-form-group");
        if (tel_scope.length > 0) {
          var intlt = ele_name.find(".telephone-form-group .form-flags");
          var $intlt = intlt.intlTelInput({
            preferredCountries: ["th"],
            separateDialCode: true,
          });
          if (tel_scope.find(".iti__flag-container").length > 1) {
            $(tel_scope.find(".iti__flag-container")[0]).remove();
          }

          var tel_name = tel_input.attr("phone-no-name");
          setTelScopeEvent(tel_scope, "+" + value);
          tel_input.attr("name", tel_name);
        }
      } else if (input_type == "file") {
        //upload audio
        var ele_upload_audio = ele_name.find(
          'input[name="{' + key + '}"].__sound-file'
        );
        //upload img
        var ele_upload_img = ele_name
          .find('input[name="{' + key + '}"]')
          .parents(".live-once-img-area");
        if (ele_upload_audio.length > 0) {
          var scope = ele_name
            .find('input[name="{' + key + '}"]')
            .parents(".__form-upload-sound");
          w.tiwform.setAudioData(scope, value);
        } else if (ele_upload_img.length > 0) {
          if (value) {
            var is_required = ele_upload_img.attr("required");
            ele_upload_img.find(".live-once-img-group").addClass("no-bg");
            ele_upload_img.find(".preview").attr("src", value);
            ele_upload_img.find(".preview").show();
            ele_upload_img.find(".button-group").show();
            ele_upload_img.addClass("active");
            ele_upload_img.find(".event_input_delete_img").remove();
            if (is_required) {
              ele_upload_img.find(".upload_img_event").removeAttr("required");
            }
          }
        }
      } else {
        //type text
        ele_name.find('input[name="{' + key + '}"]').val(value);
        //select img
        var select_ele = ele_name
          .find('input[name="{' + key + '}"]')
          .parents(".form-select-img");
        //select ajax
        var select_ajax_ele = ele_name
          .find('input[name="{' + key + '}"]')
          .parents(".form-select-img")
          .find(".__search_for_ajax");
        //select tag
        var select_tag_ele = ele_name
          .find('input[name="{' + key + '}"]')
          .parents(".form-select-tag");

        if (select_ajax_ele.length > 0) {
          var ele = ele_name
            .find('input[name="{' + key + '}"]')
            .parents(".form-select-img");
          var key_name = ele
            .find('input[name="{' + key + '}"]')
            .attr("select_name");
          var name = params[key_name] ? params[key_name] : "";
          selectAjaxEvent(ele, value, name);
        } else if (select_ele.length > 0) {
          selectImgEvent(select_ele, value); //select img, select color
        } else if (select_tag_ele.length > 0) {
          w.tiwform.setTagData(select_tag_ele, value, function () {
            select_tag_ele
              .find('input[name="{' + key + '}[]"]')
              .attr("name", key + "[]");
          });
        }
      }
      ele_name.find('input[name="{' + key + '}"]').attr("name", key);
      $is_name = 1;
    } else if (ele_name.find('textarea[name="{' + key + '}"]').length > 0) {
      //textarea
      ele_name.find('textarea[name="{' + key + '}"]').val(value);
      ele_name.find('textarea[name="{' + key + '}"]').attr("name", key);
      $is_name = 1;
    } else if (ele_name.find('img[name="{' + key + '}"]').length > 0) {
      //img
      if (
        ele_name.find('img[name="{' + key + '}"]').attr("is_placeholder") ==
          "1" ||
        value.indexOf("placeholder") == -1
      ) {
        ele_name
          .find('img[name="{' + key + '}"]')
          .attr("src", value)
          .attr("name", key)
          .css("opacity", "1");
        ele_name.find(".bg-defult").hide();
        ele_name.find(".button-group").show();
      }
      $is_name = 1;
    } else if (ele_name.find('select[name="{' + key + '}"]').length > 0) {
      //select option
      ele_name.find('select[name="{' + key + '}"]').val(value);
      ele_name.find('select[name="{' + key + '}"]').attr("name", key);
      $is_name = 1;
    }

    if (ele_name.find('span[name="{' + key + '}"]').length > 0) {
      //span
      ele_name.find('span[name="{' + key + '}"]').html(value);
      ele_name.find('span[name="{' + key + '}"]').attr("name", key);
      $is_name = 1;
    }

    if (ele_name.find('div[name="{' + key + '}"]').length > 0) {
      //div
      ele_name.find('div[name="{' + key + '}"]').html(value);
      ele_name.find('div[name="{' + key + '}"]').attr("name", key);
      $is_name = 1;
    }
  });
}
