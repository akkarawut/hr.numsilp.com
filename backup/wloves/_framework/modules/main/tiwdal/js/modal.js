$(function () {
  var __modal_lv = 0;
  $(".modal")
    .on("show.bs.modal", function (e) {
      w.requestEvent();
      $(".modal-backdrop:last").css("z-index", 1051 + __modal_lv);
      $(e.currentTarget).css("z-index", 1052 + __modal_lv);
      __modal_lv++;

      var is_enter = $(this).find("button[is_enter]");
      if (is_enter.length) {
        is_enter.focus();
        is_enter.on("keypress", function (e) {
          var keycode = e.keyCode || e.which;
          if (keycode == 13) {
            is_enter.click();
          }
        });
      }
    })
    .on("hidden.bs.modal", function (e) {
      __modal_lv--;
      $(".modal-backdrop:last").css("z-index", 1051 - __modal_lv);
    });

  $(document).on(
    "click",
    "button[toggle-edit-modal], div[toggle-edit-modal], span[toggle-edit-modal]",
    function (e) {
      var t = w(this);
      var required_form = $(this).attr("required_form");

      if (required_form) {
        if (w(required_form)[0].checkValidity()) {
          toggleEditFunction(t);
        } else {
          w(required_form)[0].reportValidity();
        }
      } else {
        toggleEditFunction(t);
      }
    }
  );

  $(document).on("click", "tr[toggle-edit-modal] td", function (e) {
    if (!w(this).hasClass("disabled-event")) {
      var t = w(this).parents("tr");
      toggleEditFunction(t);
    }
  });

  function toggleEditFunction(t) {
    var modal_name = t.attr("toggle-edit-modal");
    var auto_form = 1;
    if (t.attr("auto_form") == 0) {
      auto_form = 0;
    }
    var params_encode = t.attr("data-register");
    $(modal_name).attr("data-register", params_encode);
    if (params_encode) {
      params = w.tiwform.decode(params_encode);
    } else {
      params = [];
    }
    if (auto_form) {
      autoFormEvent(params, $(modal_name));
    }
    w(modal_name).modal("show");
  }

  $(document).on(
    "click",
    "button[toggle-ajax-modal], div[toggle-ajax-modal]",
    function (e) {
      var t = $(this);
      toggleAjaxFunction(t);
    }
  );

  $(document).on(
    "click",
    "tr[toggle-edit-modal] td, tr[toggle-ajax-modal] td",
    function (e) {
      if (!w(this).hasClass("disabled-event")) {
        var t = w(this).parents("tr");
        toggleAjaxFunction(t);
      }
    }
  );

  function toggleAjaxFunction(t) {
    var modal_name = t.attr("toggle-ajax-modal");
    // var auto_form = t.attr("auto_form") == 0 ? 1 : 0;
    var prefix = t.attr("prefix") ? $.base64.decode(t.attr("prefix")) : "";
    prefix =
      t.attr("modal_prefix") != undefined
        ? $.base64.decode(t.attr("modal_prefix"))
        : prefix;
    var code = t.attr("code") ? "?c=" + $.base64.decode(t.attr("code")) : "";
    var url =
      prefix + "ajax/modal_" + modal_name.replace("#", "") + ".php" + code;
    var params = t.attr("data-register");
    if (params) {
      params = w.tiwform.decode(t.attr("data-register"));
    } else {
      params = [];
    }
    $.post(url, params)
      .done(async function (data) {
        await $(modal_name).find(".modal-content").html(data);
        w.requestEvent();

        // if (auto_form) {
        //   autoFormEvent(params, $(modal_name));
        // }
        w(modal_name).modal("show");
      })
      .fail(function (xhr, status, error) {
        console.log(error);
      });
  }
});
