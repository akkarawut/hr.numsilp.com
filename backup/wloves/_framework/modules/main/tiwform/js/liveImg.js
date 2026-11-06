$(function () {
  //once img
  $(document).on("click", ".live_img_event", function (e) {
    e.preventDefault();
    var ele = $(this).parents(".live-once-img-area");
    ele.find(".once_img_event").click();
  });

  $(document).on("change", ".once_img_event", function (e) {
    e.preventDefault();
    var ele = $(this).parents(".live-once-img-area");
    renderImg(ele.find(".preview"), this);
    ele.find(".preview").show();
    setTimeout(() => {
      ele.addClass("active");
    }, 100);
    ele.find(".button-group").show();

    callAPIAddImg(ele, function (data) {
      ele.find(".once_img_event").val("");
    });
  });

  $(document).on("click", ".delete_img_event", function (e) {
    e.stopImmediatePropagation();
    var ele = $(this).parents(".live-once-img-area");
    callAPIDeleteImg(ele, function (data) {
      if (data == true) {
        ele.removeClass("active");
        ele.find(".preview").hide();
        ele.find(".preview").attr("src", "");
        ele.find(".button-group").hide();
      }
      ele.find(".once_img_event").val("");
    });
  });

  $(document).on("click", ".preview_img_event", function (e) {
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

  function callAPIAddImg(ele, callback) {
    var url = "../../_framework/modules/tiwform/ajax/ajax_add_img.php";
    let myForm = document.getElementById(ele.find("form").attr("id"));
    let params = new FormData(myForm);
    $.ajaxSetup({
      cache: false,
      contentType: false,
      processData: false,
    });
    $.post(url, params)
      .done(function (data) {
        $.ajaxSetup({
          cache: true,
          contentType: "application/x-www-form-urlencoded",
          processData: true,
        });
        let result = JSON.parse(data);
        if (result.response_status == true) {
          w.notification("success", result.response_message);
        } else {
          w.notification("error", result.response_message);
        }
        callback(result.response_status);
      })
      .fail(function (xhr, status, error) {
        console.log(error);
      });
  }

  function callAPIDeleteImg(ele, callback) {
    var url = "../../_framework/modules/tiwform/ajax/ajax_delete_img.php";
    var params = {
      code: ele.find("input[name=code]").val(),
    };
    $.post(url, params)
      .done(function (data) {
        var result = JSON.parse(data);
        if (result.response_status) {
          w.notification("success", result.response_message);
          callback(true);
        } else {
          w.notification("error", result.response_message);
          callback(false);
        }
      })
      .fail(function (xhr, status, error) {
        console.log(error);
      });
  }
});

function renderImg(ele, file) {
  if (file.files && file.files[0]) {
    var reader = new FileReader();
    reader.onload = function (e) {
      ele.attr("src", e.target.result);
    };
    reader.readAsDataURL(file.files[0]);
  }
}
