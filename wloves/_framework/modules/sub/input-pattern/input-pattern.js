$(document).ready(function () {
  $(document).on("submit", ".aww-regex-form", function (e) {
    var count_validated = 0;
    e.preventDefault();
    e.stopPropagation();
    var btn_submit = $(this)
      .find('button[type="submit"]')
      .attr("disabled", false);
    var regex_box = $(this).find(".aww-regex-box");
    $.each(regex_box, function (idx, ele) {
      var input = $(ele).find('input[type="text"], input[type="password"]');
      var value = input.val();
      var regex_warning_box = $(ele).find(".aww-regex-warning-box");

      $.each(regex_warning_box, function (regex_idx, regex_ele) {
        var get_pattern = $(regex_ele)
          .find(".aww-regex-warning-text")
          .data("pattern");
        if (get_pattern === false) {
          var is_match = true;
        } else {
          var pattern = new RegExp(get_pattern);
          var is_match = value.match(pattern);
        }

        if (!is_match) {
          count_validated++;
          $(ele).addClass("notvalidate");
          setTimeout(function () {
            $(ele).removeClass("notvalidate");
          }, 2000);
        }
      });
    });
    if (count_validated == 0) {
      $(this)[0].submit();
    }
  });

  $(document).on("keyup", ".aww-regex-input", function () {
    var scope = $(this).parents(".aww-regex-box");
    var value = $(this).val();
    var regex_box = scope.find(".aww-regex-warning-box");

    $.each(regex_box, function (idx, ele) {
      var input = $(ele).find('input[type="text"]');
      var get_pattern = $(ele).find(".aww-regex-warning-text").data("pattern");
      var pattern = new RegExp(get_pattern);
      var is_match = value.match(pattern);

      $(ele).removeClass("success");
      $(ele).addClass("error");

      if (is_match) {
        $(ele).removeClass("error");
        $(ele).addClass("success");
      }
    });
  });

  $(document).on("keyup", ".aww-confirm-password", function () {
    var input = $(".aww-confirm-password");
    var scope = $(this).parents(".aww-regex-form");
    let warning = scope.find(".aww-confirm-password-warning");
    let warning_parent = warning.parents(".aww-regex-warning-box");
    let btn = scope.find('button[type="submit"]');
    let previous = "";
    let check = false;
    let checknotempty = true;
    $.each(input, function (idx, ele) {
      let val = $(ele).val();
      if (previous.length > 0) {
        if (val.length > 0 && previous == val) {
          check = true;
        } else {
          check = false;
        }
        previous = val;
      } else {
        if (val.length > 0) {
          previous = val;
        }
      }
      if (val.length <= 0) {
        checknotempty = false;
      }
    });
    if (check && checknotempty) {
      btn.prop("disabled", false);
      warning_parent.addClass("d-none");
    } else if (checknotempty) {
      btn.prop("disabled", true);
      warning_parent.removeClass("d-none");
    }
  });
});
