//open external browser in line
if (
  navigator.userAgent.includes("Line") &&
  !navigator.userAgent.includes("LIFF")
) {
  var e = new URL(window.location.href);
  e.searchParams.append("openExternalBrowser", "1"),
    (window.location.href = e),
    setTimeout(function () {
      window.open("about:blank", "_self");
    }, 3e3);
}

$(document).ready(function () {
  $(".sidenav-filter").on("keyup", function () {
    let scope = $(this).parents(".filter-menu-container");
    let value = $(this).val().toLowerCase();

    scope.find(".filter-menu-items").filter(function () {
      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
    });
  });

  $(".avatar-trigger").on("touchstart click", function () {
    let $scope = $(this).parents(".avatar-box");
    let $file_select = $scope.find(".img-upload-file");
    $file_select.trigger("click");
  });

  $(".img-upload-file").on("change", function () {
    let $this = $(this);
    let $scope = $this.parents(".avatar-box");
    let $img_preview_box = $scope.find(".avatar-preview-box");
    let $img_preview = $img_preview_box.find(".avatar-preview-img");

    $img_preview_box.removeClass("has-img");
    $img_preview.attr("src", "");

    let status = Aww.readImage(this, async (file_event) => {
      await $img_preview.attr("src", file_event.target.result);
      await $img_preview_box.addClass("has-img");
    });

    if (status == "success") {
      $this.parents("form").submit();
    }
  });

  $(document).on("click", ".tr-link", function (e) {
    var link = $(this).data("link");
    if (link !== undefined && link !== "") {
      if (!$(".disabled-link, .disabled-link *").is(e.target)) {
        window.location = $(this).data("link");
      }
    }
  });

  $(document).on("click", "[data-tr]", function (e) {
    var url = $(this).attr("data-tr");
    window.location = url;
  });

  $(".file-input").change(function (e) {
    var input_this = $(this);
    var scope = $(this).parents(".manage_image");
    var has_class = scope.hasClass("has-form");
    if (has_class) {
      scope.find(".upload_image").submit();
    } else {
      if (input_this[0].files && input_this[0].files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
          let html = '<img src="' + e.target.result + '">';
          scope.find(".preview-image").html("");
          scope.find(".preview-image").html(html);
          scope.find(".file-delete-btn").css("display", "block");
        };
        reader.readAsDataURL(input_this[0].files[0]);
      }
    }
  });

  $(".file-delete-btn").click(function (e) {
    var scope = $(this).parents(".manage_image");
    var has_class = scope.hasClass("has-form");
    var bg_image = scope.find('input[name="bg-image"]').val();
    if (has_class) {
      scope.find(".delete_image").submit();
    } else {
      let html = '<img src="' + bg_image + '">';
      scope.find(".file-input").val("");
      scope.find(".preview-image").html("");
      scope.find(".preview-image").html(html);
      scope.find(".file-delete-btn").css("display", "none");
    }
  });

  $(document).on("change", 'input[name="check_all_event"]', function (e) {
    if ($(this).is(":checked")) {
      $('input[name="check_list_event[]"]').prop("checked", true);
    } else {
      $('input[name="check_list_event[]"]').prop("checked", false);
    }
  });

  $(document).on("change", 'input[name="check_list_event[]"]', function (e) {
    var check_list = $('input[name="check_list_event[]');
    var check_all = $('input[name="check_all_event');
    var cout_check = check_list.filter(":checked").length;

    if (cout_check == check_list.length) {
      check_all.prop("checked", true);
    } else {
      check_all.prop("checked", false);
    }
  });

  startCopyToClipboard();
  $(document).on("click", ".btn_copy_event", function (e) {
    copyToClipboard($(this).parents(".code-container"));
    w.notification("success", "Copied");
  });

  //permission
  countCheckedInModuleEvent();
  $(document).on("click", ".permission-hide-module-detail", function (e) {
    var scope = $(this).parents(".permission-module-group");
    $(this).toggleClass("hide");
    if ($(this).hasClass("hide")) {
      scope.find(".table-permission").hide();
    } else {
      scope.find(".table-permission").show();
    }
  });
  $(document).on("click", ".__check_all_permission_event", function (e) {
    var scope = $(this).parents(".permission-container");
    scope.find(".__check_list_permission_event input").prop("checked", true);
  });
  $(document).on("click", "._clear_all_permission_event", function (e) {
    var scope = $(this).parents(".permission-container");
    scope.find(".__check_list_permission_event input").prop("checked", false);
  });
  $(document).on("click", ".__check_permission_group_event", function (e) {
    var scope = $(this).parents(".permission-module-group");
    scope.find(".__check_list_permission_event input").prop("checked", true);
  });
  $(document).on("click", ".__clear_permission_group_event", function (e) {
    var scope = $(this).parents(".permission-module-group");
    scope.find(".__check_list_permission_event input").prop("checked", false);
  });
  $(document).on("click", ".__show_permission_box_all", function (e) {
    var scope = $(this).parents(".permission-container");
    scope.find(".table-permission").show();
  });
  $(document).on("click", ".__hide_permission_box_all", function (e) {
    var scope = $(this).parents(".permission-container");
    scope.find(".table-permission").hide();
  });
  $(document).on(
    "change",
    ".__check_list_permission_event input",
    function (e) {
      countCheckedInModuleEvent();
    }
  );

  $(".boat-nav-dinner").mousewheel(function (event, delta) {
    this.scrollLeft -= delta * 30;
    event.preventDefault();
  });
  $(".boat-nav-topbar").mousewheel(function (event, delta) {
    this.scrollLeft -= delta * 30;
    event.preventDefault();
  });
  $(".boat-nav-topbar-style-2").mousewheel(function (event, delta) {
    this.scrollLeft -= delta * 30;
    event.preventDefault();
  });
});

//permission
function countCheckedInModuleEvent() {
  var scope = $(".permission-module-group");
  for (var i = 0; i < scope.length; i++) {
    var scope_box = $($(scope)[i]);
    var checked_len = scope_box.find(
      ".__check_list_permission_event input:checked"
    ).length;
    var checked_len_view = scope_box.find(
      ".__check_list_permission_event.__is_checked"
    ).length;
    var all_len = scope_box.find(".__check_list_permission_event").length;
    if (checked_len) {
      scope_box
        .find(".__amount_checked_permission_per_module")
        .html(w.formatNumber(checked_len, 0));
    } else {
      scope_box
        .find(".__amount_checked_permission_per_module")
        .html(w.formatNumber(checked_len_view, 0));
    }

    scope_box
      .find(".__amount_all_permission_per_module")
      .html("/" + w.formatNumber(all_len, 0));
  }
}

function startCopyToClipboard() {
  $(".code-container .btn_copy_event").remove();
  $(".code-container").append('<div class="btn_copy_event"></div>');
}

function copyToClipboard(element) {
  var $temp = $("<input>");
  $("body").append($temp);

  // Exclude class "gutter" from the text
  var text = $(element).clone().find(".gutter").remove().end().text().trim();

  // Remove the first symbol if it is not a letter or number
  var firstSymbol = text.charAt(0);
  if (!firstSymbol.match(/[A-Za-z0-9]/)) {
    text = text.substring(1);
  }

  $temp.val(text).select();

  // $temp.val($(element).text().trim()).select();
  document.execCommand("copy");
  $temp.remove();
}

function readURL(input) {
  if (input.id) {
    var scope = $("#" + input.id).parents(".custom-file-wlove");
    if (input.files && input.files[0]) {
      var reader = new FileReader();

      reader.onload = function (e) {
        let html = '<img src="' + e.target.result + '">';
        scope.find(".preview-image").html("");
        scope.find(".preview-image").html(html);
      };

      reader.readAsDataURL(input.files[0]);
    }
  } else {
    if (input.files && input.files[0]) {
      var reader = new FileReader();

      reader.onload = function (e) {
        let html = '<img src="' + e.target.result + '">';
        $(".preview-image").html("");
        $(".preview-image").html(html);
      };

      reader.readAsDataURL(input.files[0]);
    }
  }
}

$(document).on("submit", ".form-loading", function (e) {
  var btn = $(this).find("button[type='submit']");
  btn.html(
    '<div class="spinner-border text-light spinner-border-sm" role="status"><span class="visually-hidden">Loading...</span></div>'
  );
  setTimeout(() => {
    btn.prop("disabled", true);
  }, 100);
});
