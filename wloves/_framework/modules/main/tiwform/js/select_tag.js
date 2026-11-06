$(function () {
  $(document).on(
    "keyup",
    ".form-select-tag .search-tag-event",
    async function (e) {
      e.preventDefault();
      var ele = $(this).parents(".form-select-tag");
      var value = $(this).val().toLowerCase();
      ele.find(".select-group").filter(function () {
        if (
          $(this).find(".select-title").text().toLowerCase().indexOf(value) > -1
        ) {
          $(this).find(".select-title").removeClass("hidden");
          $(this).find(".select-list").removeClass("hidden");
        } else {
          $(this).find(".select-title").addClass("hidden");
          $(this).find(".select-list").addClass("hidden");
          $(this)
            .find(".select-list")
            .filter(function () {
              if ($(this).text().toLowerCase().indexOf(value) > -1) {
                $(this).removeClass("hidden");
                $(this)
                  .parents(".select-group")
                  .find(".select-title")
                  .removeClass("hidden");
              }
            });
        }
      });

      var list_hidden = ele.find(".search-list.hidden").length;
      var list_all = ele.find(".search-list").length;
      if (list_hidden == list_all) {
        ele.find(".no-result").show();
      } else {
        ele.find(".no-result").hide();
      }
    }
  );

  $(document).on("click", ".form-select-tag .select-dropdown", function (e) {
    e.preventDefault();
    var ele = $(this).parents(".form-select-tag");
    if (ele.find(".select-list-group").hasClass("show")) {
      ele.find(".select-list-group").removeClass("show");
    } else {
      ele.find(".select-list-group").addClass("show");
    }
  });

  $(document).on("focusout", ".form-select-tag .select-dropdown", function (e) {
    setTimeout(() => {
      var ele = $(this).parents(".form-select-tag");
      ele.find(".select-list-group").removeClass("show");
      $(".search-tag-event").val("");
      $(".search-list").removeClass("hidden");
      $(".no-result").hide();
    }, 200);
  });

  $(document).on("click", ".form-select-tag .remove-tag", function (e) {
    e.preventDefault();
    var ele = $(this).parents(".form-select-tag");
    var is_required = ele.attr("required");
    var class_list = $(this).parents(".selected-list").attr("data-class");
    ele.find(".list_" + class_list).removeClass("active");
    ele.find(".selected_" + class_list).remove();
    ele.find(".trigger_tag_event").trigger("change");

    if (is_required && ele.find(".selected-list").length == 0) {
      ele.prepend(
        '<input type="text" class="hidden_value_for_required" required>'
      );
    }
  });

  $(document).on("click", ".form-select-tag .select-list", function (e) {
    e.preventDefault();
    var ele = $(this).parents(".form-select-tag");
    var is_required = ele.attr("required");
    $(this).toggleClass("active");
    if ($(this).hasClass("active")) {
      addSelectTagEvent(this);
    } else {
      ele.find(".selected_" + $(this).attr("data-class")).remove();
      if (is_required && ele.find(".selected-list").length == 0) {
        ele.prepend(
          '<input type="text" class="hidden_value_for_required" required>'
        );
      }
    }
    ele.find(".search-tag-event").val("");
    ele.find(".trigger_tag_event").trigger("change");
  });

  setTagAutoEvent();
});

function setTagAutoEvent() {
  $(".selected-area .selected-list").remove();
  var len = $(".form-select-tag").length;
  for (var i = 0; i < len; i++) {
    var ele_main = $($(".form-select-tag")[i]);
    var len_list = ele_main.find(".select-list").length;
    for (var f = 0; f < len_list; f++) {
      var ele = ele_main.find(".select-list")[f];
      if ($(ele).hasClass("active")) {
        addSelectTagEvent(ele);
      }
    }
  }
}

function addSelectTagEvent(t) {
  var ele = $(t).parents(".form-select-tag");
  var text = $(t).html().trim();
  var value = $(t).attr("data-value");
  var image = $(t).attr("data-image");
  var data_input = $(t).attr("data-input")
    ? $.base64.decode($(t).attr("data-input"))
    : "";
  var class_list = $(t).attr("data-class");
  var html =
    '<div class="selected-list selected_' +
    class_list +
    '" data-image="' +
    image +
    '" data-value="' +
    value +
    '" data-class="' +
    class_list +
    '">';
  html += ele.find(".selected-template").html();
  html += "</div>";
  ele.find(".selected-area").append(html);
  var new_ele = $(ele.find(".selected_" + class_list));
  new_ele.find(".name-area").html(text);
  new_ele.find(".name-area").append(data_input);
  new_ele.find("input[value-select]").val(value);
  var name = new_ele.find("input[value-select]").attr("name");
  new_ele.find("input[value-select]").attr("name", name + "[]");

  var tag_min = ele.attr("min") ? parseInt(ele.attr("min")) : 0;
  var tag_count = ele.find(".selected-area .selected-list").length;
  if (tag_min == 0) {
    ele.find(".hidden_value_for_required").remove();
  } else if (tag_count >= tag_min) {
    ele.find(".hidden_value_for_required").remove();
  }
}
