w.ready(function () {
  startDateRangeEvent();
  setCheckPasswordEvent();
  var __available_ele = "";

  function makeid(length) {
    var result = "";
    var characters =
      "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
    var charactersLength = characters.length;
    for (var i = 0; i < length; i++) {
      result += characters.charAt(Math.floor(Math.random() * charactersLength));
    }
    return "__" + result;
  }

  //file
  w(document).on("change", ".file_upload_event", function (e) {
    var ele = w(this).parents(".form-file");
    var name = w(this).val();
    name = name.replace(/.*(\/|\\)/, "");
    if (name) {
      ele.addClass("active");
      ele.find(".file_name_evnet").html(name);
    }
    ele.find(".event_input_delete_file").remove();
  });

  w(document).on("click", ".delete_normal_file_event", function (e) {
    var ele = w(this).parents(".form-file");
    var input_file = ele.find(".file_upload_event");
    var is_required = ele.attr("required");
    ele.removeClass("active");
    ele.prepend(
      '<input type="hidden" class="event_input_delete_file" name="delete_' +
        input_file.attr("name") +
        '" value="1">'
    );
    ele.find(".btn-upload-file span").html("Upload");
    if (is_required) {
      ele.find(".file_upload_event").attr("required", true);
    }

    input_file.val("");
    input_file.trigger("change");
  });

  //select
  w(document).on("click", ".select_img_event", function (e) {
    // e.stopImmediatePropagation();
    var ele = w(this).parents(".form-select-img");
    var id = w(this).attr("data-id");
    var data = w(this).attr("data-list");
    var select_html = w(this).html();
    ele.find(".select_img_event").removeClass("selected");
    w(this).addClass("selected");
    ele.find(".select-img-group").html(select_html);
    var input_scope = ele.find("input[select_value]");
    input_scope.val(id);
    if (data) {
      input_scope.attr("data-list", data);
    }
    input_scope.trigger("change");
  });

  w(document).on("keyup", ".search_event", function (e) {
    var scope = w(this).parents(".form-select-img");
    var value = w(this).val().toLowerCase();
    scope.find(".select_img_event").filter(function () {
      if (w(this).text().toLowerCase().indexOf(value) > -1) {
        w(this).removeClass("hidden");
      } else {
        w(this).addClass("hidden");
      }
    });

    var len_all = scope.find(".select_img_event").length;
    var len = scope.find(".select_img_event.hidden").length;
    if (len_all == len) {
      scope.find(".no-result").show();
    } else {
      scope.find(".no-result").hide();
    }
  });

  $(".__dropdown_search_event").on("show.bs.dropdown", function () {
    $(".form-select-img").find(".dropdown-menu").removeClass("show");
  });

  w(document).on("click", ".__dropdown_search_event", function (e) {
    e.stopPropagation();
    var scope = w(this).parents(".form-select-img");
    changeASelectImgWidthEvent(scope);
    scope.find(".search_event").val("");
    scope.find(".search_event").focus();
    scope.find(".search_event").trigger("keyup");

    scope.find(".__search_for_ajax").val("");
    scope.find(".__search_for_ajax").focus();
    scope.find(".__search_for_ajax").trigger("keyup");

    $(".__dropdown_search_event").dropdown("update");
  });

  w(document).on(
    "click",
    ".form-select-img .dropdown-toggle[data-bs-toggle]",
    function (e) {
      changeSelectImgWidthEvent();
    }
  );

  //select ajax
  w(document).on("input", ".__search_for_ajax", function (e) {
    var scope = w(this).parents(".form-select-img");
    var data = w.tiwform.decode(scope.attr("data-options"));
    var search = w(this).val();
    if (search) {
      var url =
        "../../_framework/modules/main/tiwform/ajax/ajax_select_lazyload.php";
      var params = {
        search: search,
        data: data,
        value: scope.find("input[select_value]").val(),
      };
      $.post(url, params).done(function (data) {
        scope.find(".select-group").html(data);
      });
    } else {
      scope.find(".select-group").html("");
    }
  });

  changeSelectImgWidthEvent();
  w.resize(function () {
    changeSelectImgWidthEvent();
  });

  //upload img
  w(document).on("click", ".upload_img_area_event", function (e) {
    e.preventDefault();
    var ele = w(this).parents(".live-once-img-area");
    ele.find(".upload_img_event").click();
  });

  w(document).on("change", ".upload_img_event", function (e) {
    e.preventDefault();
    var ele = w(this).parents(".live-once-img-area");
    var is_required = ele.attr("required");
    renderImg(ele.find(".preview"), this);
    setTimeout(() => {
      if (ele.find(".preview").attr("src")) {
        ele.find(".live-once-img-group").addClass("no-bg");
        ele.find(".preview").show();
        ele.find(".button-group").show();
        ele.addClass("active");
        ele.find(".event_input_delete_img").remove();
        if (is_required) {
          ele.find(".upload_img_event").removeAttr("required");
        }
      } else {
        ele.find(".preview").hide();
        ele.find(".button-group").hide();
        ele.removeClass("active");
      }
    }, 100);
  });

  w(document).on("click", ".preview_upload_img_event", function (e) {
    e.stopImmediatePropagation();
    var ele = $(this).parents(".live-once-img-area");
    var url = $(this).attr("data-url");
    var img = ele.find(".preview").attr("src");
    var w = window.open("");
    w.document.write(
      '<html style="height: 100%;"><head><meta name="viewport" content="width=device-width, minimum-scale=0.1"><title>Preview Image</title></head><body style="margin: 0px; background: #0e0e0e; height: 100%; display: flex; align-items: center; justify-content: center;"><img style="-webkit-user-select: none;margin: auto;background-color: hsl(0, 0%, 90%);transition: background-color 300ms; max-width: 100%; max-height: 100%; object-fit: cover;" src="' +
        img +
        '"></body></html>'
    );
  });

  w(document).on("click", ".delete_upload_img_event", function (e) {
    e.stopImmediatePropagation();
    var ele = w(this).parents(".live-once-img-area");
    var is_bg = ele.attr("is-bg");
    var is_required = ele.attr("required");
    ele.removeClass("active");
    ele.find(".preview").attr("src", "");
    ele.find(".preview").hide();
    ele.find(".button-group").hide();
    ele.find(".bg-defult").show();
    var file_key = ele.find(".upload_img_event").attr("name");
    ele.prepend(
      '<input type="hidden" class="event_input_delete_img" name="delete_' +
        file_key +
        '" value="1">'
    );
    if (is_bg == "1") {
      ele.find(".live-once-img-group").addClass("no-bg");
    } else {
      ele.find(".live-once-img-group").removeClass("no-bg");
    }
    if (is_required) {
      ele.find(".upload_img_event").attr("required", true);
    }
    ele.find(".upload_img_event").val("");
    ele.find(".upload_img_event").trigger("change");
  });

  //telephone
  w(".telephone-form-group .form-flags").each(function () {
    var key = this.childElementCount;
    window.intlTelInput(w(".telephone-form-group .form-flags")[key], {
      preferredCountries: ["th"],
      separateDialCode: true,
      autoPlaceholder: "polite",
    });
  });

  changeTelTrigger();
  $(document).on(
    "countrychange",
    ".telephone-form-group .form-flags",
    function () {
      changeTelTrigger();
    }
  );

  //available form
  w(document).on("change", "[data-available]", function (e) {
    var url = "../../_framework/modules/tiwform/ajax/ajax_call_api.php";
    var data_key = w(this).attr("name");
    var data_edit = w(this).val();
    var params = w(this).attr("data-available");
    __available_ele = w(this);
    if (params) {
      params = jQuery.parseJSON($.base64.decode(params));
    } else {
      params = [];
    }
    var all_params = {
      params: params,
      data_key: data_key,
      data_edit: data_edit,
    };
    if (data_edit) {
      // $.post(url, all_params)
      //   .done(function (data) {
      //     let result = JSON.parse(data);
      //     var data_id = __available_ele.attr("available-scope");
      //     $("#" + data_id).remove();
      //     if (result.response_status == true) {
      //       $("#" + data_id).remove();
      //     } else {
      //       $(
      //         '<label class="available-message" id="' +
      //           data_id +
      //           '">' +
      //           result.response_message +
      //           "</label>"
      //       ).insertAfter(__available_ele);
      //     }
      //   })
      //   .fail(function (xhr, status, error) {
      //     console.log(error);
      //   });

      wx.post(url, all_params, function (data) {
        let result = JSON.parse(data);
        var data_id = __available_ele.attr("available-scope");
        w("#" + data_id).remove();
        if (result.response_status == true) {
          w("#" + data_id).remove();
        } else {
          w(
            '<label class="available-message" id="' +
              data_id +
              '">' +
              result.response_message +
              "</label>"
          ).insertAfter(__available_ele);
        }
      });
    } else {
      w("#" + $(this).attr("available-scope")).remove();
    }
  });

  //drag and drop form
  checkDragDropLengthEvent();
  $(document).on(
    "dragover dragleave drop",
    ".drag-drop-file-container",
    function (e) {
      e.preventDefault();
      e.stopPropagation();
      if (e.type == "drop") {
        var files = e.originalEvent.dataTransfer.files;
        var scope = $(this).find(".drop-file-group");
        var name = $(this).attr("name") ? $(this).attr("name") : "";

        for (var i = 0; i < files.length; i++) {
          var this_file = files[i];
          var ele = $("<div>").attr("class", "temp-area");
          var file_id = makeid(20);

          var html = '<div class="drop-file-list">';
          html +=
            '<input type="file" class="list-file ' +
            file_id +
            '" name="' +
            name +
            '">';
          html +=
            '<div class="name-file-drag-drop">' + this_file.name + "</div>";
          html += '<div class="btn-delete-file-drag-drop"><i></i></div>';
          html += "</div>";

          ele.html(html);
          const dt = new DataTransfer();
          dt.items.add(this_file);
          scope.append(ele.html());
          $("." + file_id).prop("files", dt.files);

          scope
            .parents(".drag-drop-file-area")
            .find(".drop_on_area_event")
            .attr("data-list-id", file_id);
          scope
            .parents(".drag-drop-file-area")
            .find(".drop_on_area_event")
            .attr("data-status", "add")
            .trigger("change");
        }
        checkDragDropLengthEvent();
      }
    }
  );
  $(document).on("click", ".drag-drop-file-container", function (e) {
    var scope = $(this).find(".drop-file-group");
    var name = $(this).attr("name") ? $(this).attr("name") : "";
    var file_id = makeid(20);

    var html = '<div class="drop-file-list ' + file_id + ' d-none">';
    html += '<div class="name-file-drag-drop"></div>';
    html += '<div class="btn-delete-file-drag-drop"><i></i></div>';
    html +=
      '<input type="file" class="__drag_drop_file_event list-file' +
      file_id +
      '">';
    html += "</div>";
    scope.append(html);

    $(".list-file" + file_id).click();

    $(document).on("change", ".list-file" + file_id, function (e) {
      var file_name = $(this).val().split("\\");
      $("." + file_id)
        .removeClass("d-none")
        .find(".name-file-drag-drop")
        .html(file_name[file_name.length - 1]);
      $(this).attr("name", name);

      checkDragDropLengthEvent();
    });
  });
  $(document).on("click", ".__drag_drop_file_event", function (e) {
    e.stopPropagation();
  });
  $(document).on("click", ".btn-delete-file-drag-drop", function (e) {
    e.preventDefault();
    e.stopPropagation();
    var main_scope = $(this).parents(".drag-drop-file-area");
    var scope = $(this).parents(".drop-file-list");
    scope.remove();
    main_scope
      .find(".drop_on_area_event")
      .attr("data-status", "delete")
      .trigger("change");
    checkDragDropLengthEvent();
  });
  $(document).on("click", ".__btn_delete_file_uploaded", function (e) {
    var datas = $("#__delete_uploaded_modal").attr("data-register");
    datas = datas ? jQuery.parseJSON($.base64.decode(datas)) : {};
    localStorage.setItem("image_code", datas.code);
    var url = "../../_framework/modules/tiwform/ajax/ajax_delete_img.php";
    var params = {
      code: datas.code,
    };
    $.post(url, params)
      .done(function (data) {
        var result = JSON.parse(data);
        if (result.response_status) {
          w.notification("success", result.response_message);
          $(".__" + localStorage.getItem("image_code")).remove();
        } else {
          w.notification("error", result.response_message);
        }
        $("#__delete_uploaded_modal").modal("hide");
      })
      .fail(function (xhr, status, error) {
        console.log(error);
      });
  });

  //add tag and scan form
  w(document).on("keyup", ".__input_tag_event", function (e) {
    if (e.which === 13) {
      var scope = $(this).parents(".add-scan-tag-container");
      w.tiwform.setTag(
        w(this).parents(".add-scan-tag-container"),
        w(this).val()
      );
      checkScanTagLangthEvent(scope);
    }
  });
  w(document).on("click", ".__add_tag_event", function (e) {
    var scope = $(this).parents(".add-scan-tag-container");
    var tag = scope.find(".__input_tag_event").val();
    if (!tag) {
      scope.find(".tag-msg").remove();
      scope
        .find(".add-scan-tag-group")
        .prepend(
          '<div class="font-14px text-danger tag-msg w-100 mt--10px mb-5px pl-5px">Please enter tag name !</div>'
        );
      setTimeout(() => {
        scope.find(".tag-msg").remove();
      }, 3000);
    } else {
      w.tiwform.setTag(w(this).parents(".add-scan-tag-container"), tag);
      checkScanTagLangthEvent(scope);
    }
  });
  $(document).on("click", ".__delete_tag_scan_event", function (e) {
    var scope = $(this).parents(".add-scan-tag-container");
    $(this).parents(".add-scan-tag-list").remove();
    checkScanTagLangthEvent(scope);
  });

  //upload sound
  w(document).on("click", ".__sound-blank", function (e) {
    var scope = w(this).parents(".__form-upload-sound");
    scope.find(".__sound-file").click();
  });
  w(document).on("change", ".__sound-file", function (e) {
    var scope = w(this).parents(".__form-upload-sound");
    var file = this.files[0];
    var accept_file = scope.find(".__sound-file").attr("accept");
    accept_file = accept_file ? accept_file.split(",") : [];
    for (var i = 0; i < accept_file.length; i++) {
      accept_file[i] = accept_file[i].trim();
    }
    var file_type = file.name.split(".");
    file_type = "." + file_type[file_type.length - 1];

    if (
      (!accept_file && file.type == "audio/mpeg") ||
      (accept_file.length > 0 && accept_file.indexOf(file_type) > -1)
    ) {
      scope.find(".__sound-name").html(file.name);
      scope.find("audio").remove();
      var audio =
        '<audio src="' +
        URL.createObjectURL(scope.find(".__sound-file")[0].files[0]) +
        '"></audio>';
      scope.prepend(audio);
      scope.find(".__sound-uploaded").attr("data-status", "pause");
      scope.attr("is-upload", 1);
    } else {
      $(this).val("");

      if (accept_file.length > 0) {
        var text_json = JSON.stringify(accept_file);
        text_json = text_json.replace(/"/g, "");
        text_json = text_json.replace(/,/g, ", ");
        text_json = text_json.replace(/\[/g, "");
        text_json = text_json.replace(/\]/g, "");

        w.notification("error", "Please upload " + text_json + " file only !");
      } else {
        w.notification("error", "Please upload audio file only !");
      }
    }
  });
  w(document).on("click", ".__sound-delete", function (e) {
    var scope = w(this).parents(".__form-upload-sound");
    var name = scope.find(".__sound-file").attr("name");
    scope.find(".__sound-file").val("");
    scope.find(".__sound-name").html("");
    scope.find("audio").remove();
    scope.attr("is-upload", 0);
    scope.prepend(
      '<input type="hidden" name="delete_' + name + '" value="1" />'
    );
    if (scope.attr("required") != null) {
      scope.find(".__sound-file").attr("required", true);
    }
  });
  w(document).on("click", ".__sound-play", function (e) {
    var scope = w(this).parents(".__form-upload-sound");
    scope.find("audio")[0].currentTime = 0;
    scope.find("audio")[0].play();
  });
  w(document).on("pause", ".__form-upload-sound audio", function () {
    var scope = w(this).parents(".__form-upload-sound");
    scope.find(".__sound-uploaded").attr("data-status", "pause");
  });
  w(document).on("play", ".__form-upload-sound audio", function () {
    var scope = w(this).parents(".__form-upload-sound");
    scope.find(".__sound-uploaded").attr("data-status", "play");
  });
  w(document).on("click", ".__sound-pause", function (e) {
    var scope = w(this).parents(".__form-upload-sound");
    scope.find("audio")[0].pause();
    scope.find(".__sound-uploaded").attr("data-status", "pause");
  });
});

function changeASelectImgWidthEvent(scope) {
  var width = scope.outerWidth();
  scope.find(".dropdown-menu").width(width);
}

function changeSelectImgWidthEvent() {
  var scopes = w(".form-select-img");
  for (var i = 0; i < scopes.length; i++) {
    var scope = w(scopes[i]);
    var width = scope.outerWidth();
    scope.find(".dropdown-menu").width(width);
  }
}

function changeTelTrigger() {
  var key = 0;
  $(".telephone-form-group").each(function () {
    let ele = $($(".telephone-form-group")[key]);
    let country_code = ele.find(".country_code");
    let country_name = ele.find(".country_name");
    let iti = window.intlTelInputGlobals.getInstance(
      ele.find(".form-flags")[0]
    );
    let countryData = iti.getSelectedCountryData();
    country_code.val(countryData.dialCode);
    country_code.trigger("countrychange");
    country_name.val(countryData.name);
    country_name.trigger("countrychange");
    key++;
  });
}

function setTelEvent(scope, tel) {
  let container = $(scope);
  container.find(".form-flags").intlTelInput("setNumber", tel);
}

function setTelScopeEvent(scope, tel) {
  scope.find(".form-flags").intlTelInput("setNumber", tel);
}

function selectImgEvent(ele, value) {
  var select_html = ele
    .find(".select_list_" + value.toString().replace("#", "").replace(" ", "_"))
    .html();
  ele.find(".select-img-group").html(select_html);
  var input_scope = ele.find("input[select_value]");
  input_scope.val(value);
  input_scope.trigger("change");
}

function selectAjaxEvent(ele, value, name) {
  ele.find(".select-img-group").html(name);
  var input_scope = ele.find("input[select_value]");
  input_scope.val(value);
  input_scope.trigger("change");
}

function selectImgBlankEvent(ele) {
  var placeholder = ele.find("input[select_value]").attr("placeholder");
  ele.find("input[select_value]").val("");
  ele
    .find(".select-img-group")
    .html('<div class="placeholder">' + placeholder + "</div>");
  ele.find(".select_img_event").removeClass("selected");
}

function startDateRangeEvent(ele_name = "") {
  if (ele_name) {
    flatpickr(ele_name + " input", {
      mode: "range",
      dateFormat: "Y-m-d",
      onReady: function (selectedDates, dateStr, instance) {
        var date_form = $.datepicker.formatDate("dd/mm/yy", selectedDates[0]);
        var date_to = $.datepicker.formatDate("dd/mm/yy", selectedDates[1]);
        if (date_form && date_to) {
          $("#" + instance._input.id).val(date_form + " - " + date_to);
        }
      },
      onChange: function (selectedDates, dateStr, instance) {
        var is_range = dateStr.toLowerCase().indexOf("to");
        if (is_range > -1) {
          var date_form = $.datepicker.formatDate("dd/mm/yy", selectedDates[0]);
          var date_to = $.datepicker.formatDate("dd/mm/yy", selectedDates[1]);
          $("#" + instance._input.id).val(date_form + " - " + date_to);
        }
      },
    });
  } else {
    flatpickr(".form-date-range input", {
      mode: "range",
      dateFormat: "Y-m-d",
      onReady: function (selectedDates, dateStr, instance) {
        var date_form = $.datepicker.formatDate("dd/mm/yy", selectedDates[0]);
        var date_to = $.datepicker.formatDate("dd/mm/yy", selectedDates[1]);
        if (date_form && date_to) {
          $("#" + instance._input.id).val(date_form + " - " + date_to);
        }
      },
      onChange: function (selectedDates, dateStr, instance) {
        var is_range = dateStr.toLowerCase().indexOf("to");
        if (is_range > -1) {
          var date_form = $.datepicker.formatDate("dd/mm/yy", selectedDates[0]);
          var date_to = $.datepicker.formatDate("dd/mm/yy", selectedDates[1]);
          $("#" + instance._input.id).val(date_form + " - " + date_to);
        }
      },
    });
  }
}

//check password
function setCheckPasswordEvent() {
  var scope = $(".check-password-group");
  if (scope.length > 0) {
    for (var i = 0; i < scope.length; i++) {
      var scope_password = $(scope[i]).attr("check-password-class");
      var scope_btn = "." + $(scope[i]).attr("check-btn-class");
      var no_match_message = $(scope[i]).attr("is-match");
      $(scope_btn).attr("disabled", true);
      keyupPasswordEvent(
        $(scope[i]),
        scope_password,
        scope_btn,
        no_match_message
      );
    }
  }
}

function keyupPasswordEvent(
  scope,
  scope_password,
  scope_btn,
  no_match_message
) {
  $(document).on("keyup", "." + scope_password, function (e) {
    var check_all = 0;
    var check_pass = 0;
    var password = $($("." + scope_password)[0]).val();
    var char_number = parseInt(
      scope.find(".conditon_length_event").attr("char_number")
    );
    var scope_1 = scope.find(".conditon_length_event");
    if (scope_1.length > 0) {
      check_all++;
      if (password.length >= char_number) {
        scope_1.addClass("active");
        check_pass++;
      } else {
        scope_1.removeClass("active");
      }
    }
    var scope_2 = scope.find(".conditon_word_event");
    if (scope_2.length > 0) {
      check_all++;
      if (password.search(/[a-z]/g) !== -1 && password.length > 0) {
        //lower case
        if (password.search(/[A-Z]/g) !== -1) {
          //upper case
          scope_2.addClass("active");
          check_pass++;
        } else {
          scope_2.removeClass("active");
        }
      } else {
        scope_2.removeClass("active");
      }
    }
    var scope_3 = scope.find(".conditon_number_event");
    if (scope_3.length > 0) {
      check_all++;
      if (
        (password.search(/[0-9]/g) !== -1 ||
          password.search(/[0-9a-zA-Z]/g) === -1) &&
        password.length > 0
      ) {
        scope_3.addClass("active");
        check_pass++;
      } else {
        scope_3.removeClass("active");
      }
    }
    if (check_pass == check_all) {
      $(scope_btn).attr("disabled", false);
    } else {
      $(scope_btn).attr("disabled", true);
    }

    if (
      no_match_message &&
      (check_all == 0 || (check_all > 0 && check_pass == check_all))
    ) {
      checkMatchValueEvent(scope_password, no_match_message, scope_btn);
    } else {
      $("." + scope_password).html("");
    }
  });
}

function checkMatchValueEvent(scope_password, no_match_message, scope_btn) {
  var scope = $("." + scope_password);
  if (!$(scope)[2]) {
    $(
      '<label class="value-not-match ' +
        scope_password +
        '">' +
        no_match_message +
        "</label>"
    ).insertAfter($(scope)[1]);
  }
  if (
    ($(scope[0]).val().length > 0 || $(scope[1]).val().length > 0) &&
    $(scope[0]).val() != $(scope[1]).val()
  ) {
    $($(scope)[2]).fadeIn(100);
    $(scope_btn).attr("disabled", true);
  } else {
    $($(scope)[2]).fadeOut(100);
    $(scope_btn).attr("disabled", false);
  }
}

//drag and drop function
function checkDragDropLengthEvent() {
  var scope = $(".drag-drop-file-container");
  for (var i = 0; i < scope.length; i++) {
    var l = $(scope[i]).find(".drop-file-list").length;
    if (l > 0) {
      $(scope[i]).addClass("active");
      $(scope[i]).find(".file-required").remove();
    } else {
      $(scope[i]).removeClass("active");
      //check required form
      var required = $(scope[i]).attr("required") ? true : false;
      if (required) {
        $(scope[i]).append(
          '<input type="file" class="file-required" required>'
        );
      }
    }
  }

  try {
    dragAndDropCallback();
  } catch (err) {}
}

function checkScanTagLangthEvent(scope) {
  var len = scope.find(".add-scan-tag-group .add-scan-tag-list").length;
  var scope_group = scope.find(".add-scan-tag-group");
  if (len > 0) {
    scope_group.find(".input-required").remove();
  } else {
    scope_group.append('<input type="input" class="input-required" required>');
  }
}
