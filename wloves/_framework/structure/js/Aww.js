var Aww = {
  helper: {},
  global: {
    view: [],
    controller: [],
    cleave: {},
  },
  template: {},
  settings: {
    pace_module: true,
    toastr_module: true,
  },
};

// core function

Aww.detectIE = function () {
  /**
   * detect IE
   * returns version of IE or false, if browser is not Internet Explorer
   */
  var ua = window.navigator.userAgent;

  // Test values; Uncomment to check result …

  // IE 10
  // ua = 'Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.2; Trident/6.0)';

  // IE 11
  // ua = 'Mozilla/5.0 (Windows NT 6.3; Trident/7.0; rv:11.0) like Gecko';

  // Edge 12 (Spartan)
  // ua = 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/39.0.2171.71 Safari/537.36 Edge/12.0';

  // Edge 13
  // ua = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/46.0.2486.0 Safari/537.36 Edge/13.10586';

  var msie = ua.indexOf("MSIE ");
  if (msie > 0) {
    // IE 10 or older => return version number
    return parseInt(ua.substring(msie + 5, ua.indexOf(".", msie)), 10);
  }

  var trident = ua.indexOf("Trident/");
  if (trident > 0) {
    // IE 11 => return version number
    var rv = ua.indexOf("rv:");
    return parseInt(ua.substring(rv + 3, ua.indexOf(".", rv)), 10);
  }

  var edge = ua.indexOf("Edge/");
  if (edge > 0) {
    // Edge (IE 12+) => return version number
    return parseInt(ua.substring(edge + 5, ua.indexOf(".", edge)), 10);
  }

  // other browser
  return false;
};

Aww.handleIE = function () {
  // Get IE or Edge browser version
  var version = Aww.detectIE();
  if (version === false) {
    // other browser
  } else if (version >= 12) {
    // ms edge
  } else {
    // ie 11 below
    swal
      .fire({
        title: "ตรวจพบบราวเซอร์รุ่นเก่า",
        html: "กรุณาใช้บราวเซอร์รุ่นใหม่เพื่อประสิทธิภาพการใช้งานที่ดีกว่า",
        type: "warning",
        confirmButtonText: '<i class="fab fa-chrome"></i> Download',
        confirmButtonClass: "btn btn-primary",
        buttonsStyling: false,
      })
      .then(
        function () {
          window.open("https://www.google.com/intl/th/chrome/", "_blank");
        },
        function (dismiss) {}
      );
  }
};

// init function

Aww.initTooltip = function () {
  $(".tooltip").remove();
  $('[data-toggle="tooltip"], .toggle-tooltip').tooltip({
    container: "body",
  });
  $('[data-toggle="popover"], .toggle-popover').popover({
    container: "body",
  });
};


Aww.initCleaveJS = async function ($this) {
  if (typeof Cleave == "function") {
    if (typeof $this != "undefined") {
      if ($this.hasClass(".cleave-input")) {
        let $target_input = $this;

        let cleave_name = Aww.helper.uid();
        let set_cleave_name = "cleave_input_" + cleave_name;

        let options = {};

        if (typeof $target_input.data("prefix") != "undefined") {
          options.prefix = $target_input.data("prefix");
        }

        if (typeof $target_input.data("blocks") != "undefined") {
          options.blocks = eval($target_input.data("blocks"));
        }

        if (typeof $target_input.data("delimiter") != "undefined") {
          options.delimiter = $target_input.data("delimiter");
        }

        if (typeof $target_input.data("uppercase") != "undefined") {
          options.uppercase = $target_input.data("uppercase");
        }

        if (typeof $target_input.data("numeric") != "undefined") {
          options.numericOnly = $target_input.data("numeric");
        }

        if (typeof Aww.global.cleave[set_cleave_name] != "undefined") {
          await Aww.global.cleave[set_cleave_name].destroy();
        }

        Aww.global.cleave[set_cleave_name] = new Cleave($target_input, options);
      }
    }
  }
};



Aww.initClipboard = function ($scope) {
  if (typeof $scope == "undefined") {
    $scope = $(document);
  }

  if (typeof ClipboardJS != "undefined") {
    $(".btn-clipboard").tooltip({
      trigger: "click",
      placement: "top",
    });

    function setTooltip(btn, message) {
      $(btn)
        .tooltip("hide")
        .attr("data-original-title", message)
        .tooltip("show");
    }

    function hideTooltip(btn) {
      setTimeout(function () {
        $(btn).tooltip("hide");
      }, 1000);
    }

    var clipboard = new ClipboardJS(".btn-clipboard");

    clipboard.on("success", function (e) {
      setTooltip(e.trigger, "Copied!");
      hideTooltip(e.trigger);
    });

    clipboard.on("error", function (e) {
      setTooltip(e.trigger, "Failed!");
      hideTooltip(e.trigger);
    });
  }
};

Aww.initDatePicker = function ($scope) {
  if (typeof $scope == "undefined") {
    $scope = $(document);
  }

  let $input_date_list = $scope.find(".toggle-datepicker");

  if ($input_date_list.length > 0) {
    $input_date_list.each(async function (idx, ele) {
      let $this = $(ele);
      let input_type = $this.attr("type");

      if (input_type == "date") {
        $this.attr("type", "text");
      }

      let opt_separator = $this.data("separator");
      let opt_view_format = $this.data("view-format");
      let opt_time = $this.data("time");
      let opt_range = $this.data("range");
      let opt_multiple = $this.data("multiple");
      let opt_date_format = $this.data("date-format");
      let opt_time_format = $this.data("time-format");
      let opt_min_date = $this.data("min-date");
      let opt_max_date = $this.data("max-date");
      let opt_min_hours = $this.data("min-hours");
      let opt_max_hours = $this.data("max-hours");
      let opt_min_minutes = $this.data("min-minutes");
      let opt_max_minutes = $this.data("max-minutes");
      let opt_minutes_step = $this.data("step-minutes");
      let opt_hours_step = $this.data("step-hours");
      let opt_disabledDate = $this.data("disabled-date");

      let options = {
        language: "en",
      };

      if (typeof opt_separator != "undefined") {
        options["multipleDatesSeparator"] = opt_separator;
      }

      if (typeof opt_view_format != "undefined") {
        if (opt_view_format == "months") {
          options["view"] = "months";
          options["minView"] = "months";
          options["dateFormat"] = "MM";
        } else if (opt_view_format == "years") {
          options["view"] = "years";
          options["minView"] = "years";
          options["dateFormat"] = "yyyy";
        }
      }

      if (typeof opt_multiple != "undefined") {
        options["multipleDates"] = opt_multiple;
        options["multipleDatesSeparator"] = ",";
      }

      if (typeof opt_range != "undefined") {
        options["multipleDates"] = opt_range;
        options["multipleDatesSeparator"] = "-";
      }

      if (typeof opt_time != "undefined") {
        options["timepicker"] = opt_time;
        options["timeFormat"] = "hh:ii";
      }

      if (typeof opt_date_format != "undefined") {
        options["dateFormat"] = opt_date_format;
      }

      if (typeof opt_time_format != "undefined") {
        options["timeFormat"] = opt_time_format;
      }

      if (typeof opt_min_date != "undefined") {
        // let date_format_pattern = /^([0-9]{4})-([0-1][0-9])-([0-3][0-9]))$/;

        let search = date_format_pattern.test(opt_min_date);

        if (search) {
          options["minDate"] = new Date(opt_min_date);
        }
      }

      if (typeof opt_max_date != "undefined") {
        // let date_format_pattern = /^([0-9]{4})-([0-1][0-9])-([0-3][0-9]))$/;

        let search = date_format_pattern.test(opt_max_date);

        if (search) {
          options["maxDate"] = new Date(opt_max_date);
        }
      }

      if (typeof opt_min_hours != "undefined") {
        options["minHours"] = opt_min_hours;
      }

      if (typeof opt_max_hours != "undefined") {
        options["maxHours"] = opt_max_hours;
      }

      if (typeof opt_min_minutes != "undefined") {
        options["minMinutes"] = opt_min_minutes;
      }

      if (typeof opt_max_minutes != "undefined") {
        options["maxMinutes"] = opt_max_minutes;
      }

      if (typeof opt_hours_step != "undefined") {
        options["hoursStep"] = opt_hours_step;
      }

      if (typeof opt_minutes_step != "undefined") {
        options["minutesStep"] = opt_minutes_step;
      }

      if (typeof opt_disabledDate != "undefined") {
        options["onRenderCell"] = (date, cellType) => {
          if (cellType == "day") {
            let day = date.getDay();
            let isDisabled = opt_disabledDate.indexOf(day) != -1;

            return {
              disabled: isDisabled,
            };
          }
        };
      }

      $this.datepicker(options);
    });
  }
};

Aww.handleScrollTop = function () {
  // Back To Top
  var offset = 450;
  var duration = 500;
  var upToTop = $(".btn-to-top");

  if (upToTop) {
    $(window).on("scroll", function () {
      if (upToTop) {
        if ($(this).scrollTop() > offset) {
          upToTop.fadeIn(duration);
        } else {
          upToTop.fadeOut(duration);
        }
      }
    });

    upToTop.on("click", function (event) {
      event.preventDefault();
      $("html, body").animate(
        {
          scrollTop: 0,
        },
        duration
      );
      return false;
    });
  }
};

Aww.preventMultipleSubmit = function () {
  $("form").submit(function (e) {
    var btn = $(this).find('button[type="submit"]');
    btn.prop("disabled", true);
  });
};

Aww.handleFocusModalInput = function () {
  $(document).on("click", ".toggle-modal", function (e) {
    e.preventDefault();
    var $this = $(this);
    var $target = $($this.attr("data-target"));
    var model =
      typeof $this.data("model") != "undefined" ? $this.data("model") : null;
    $target.modal();

    // auto fill modal input
    var $params = $this.find(".modal-param");

    for (var i = 0; i < $params.length; i++) {
      var $param = $params.eq(i);
      var trigger =
        typeof $param.data("trigger") != "undefined"
          ? $param.data("trigger")
          : "";
      var input_type = $param.attr("type");
      if (input_type == "radio") {
        if (!$param.prop("checked")) {
          continue;
        }
      }
      var key = $param.attr("name");
      var value = $param.val();
      var $target_input = $target.find('[name="' + key + '"]');
      var target_type = $target_input.attr("type");
      if (target_type == "radio") {
        $target_input.each(function (idx, ele) {
          if ($target_input.eq(idx).val() == value) {
            $target_input.eq(idx).prop("checked", true);
          }
        });
      } else {
        $target_input.val(value);
      }
      if (trigger != "") {
        $target_input.trigger(trigger);
      }
    }

    var $param = [];
    if (model != null && typeof eval("$_MODEL." + model) != "undefined") {
      $param = eval("$_MODEL." + model);
    }
    var trigger = [];

    for (var key in $param) {
      var value = $param[key];
      var $target_input = $target.find('[name="' + key + '"]');
      trigger.push($target_input);
      var target_type = $target_input.attr("type");
      if (target_type == "radio") {
        $target_input.each(function (idx, ele) {
          if ($target_input.eq(idx).val() == value) {
            $target_input.eq(idx).prop("checked", true);
          }
        });
      } else if (target_type == "checkbox") {
        if ($target_input.val() == value) {
          $target_input.prop("checked", true);
        }
      } else {
        $target_input.val(value);
      }
    }

    for (var i = 0; i < trigger.length; i++) {
      if (typeof trigger[i].data("trigger") != "undefined") {
        trigger[i].trigger(trigger[i].data("trigger"));
      }
    }
  });

  $(document).on("shown.bs.modal", function (e) {
    var $modal = $(e.target);
    if (!$($modal.find("input")[0]).hasClass("no-focus")) {
      $($modal.find("input")[0]).focus();
    }
  });
};

Aww.handleConfirm = function () {
  $(document).on(
    "click",
    '.toggle-confirm, [data-toggle="confirm"]',
    function (e) {
      let $this = $(this);
      let submit_text = $(this).data("submit_text");
      let cancel_text = $(this).data("cancel_text");
      let icon = $(this).data("icon");
      let target =
        typeof $this.attr("data-target") != "undefined"
          ? $this.attr("data-target")
          : false;
      let $form = $this.parents("form");

      if (typeof icon == "undefined") {
        icon = "question";
      }
      if (typeof submit_text == "undefined") {
        submit_text = '<i class="fa fa-check"></i> Confirm';
      }
      if (typeof cancel_text == "undefined") {
        cancel_text = '<i class="fa fa-times"></i> Cancel';
      }

      if (target != false && $(target).length > 0) {
        let $target = $(target);
        // auto fill target input
        let $params = $form.find(".transfer-param");
        for (let i = 0; i < $params.length; i++) {
          let $param = $params.eq(i);
          let trigger =
            typeof $param.data("trigger") != "undefined"
              ? $param.data("trigger")
              : "";
          let key = $param.attr("name");
          let value = $param.val();
          let $target_input = $target.find('[name="' + key + '"]');
          $target_input.val(value);
          if (trigger != "") {
            $target_input.trigger(trigger);
          }
        }
        if ($target[0].checkValidity()) {
          e.preventDefault();
          Aww.confirm(
            $this.attr("data-message"),
            "",
            function () {
              $target.submit();
            },
            function () {},
            submit_text,
            cancel_text,
            icon
          );
        } else {
          $target[0].reportValidity();
        }
      } else if ($form.length > 0) {
        if ($form[0].checkValidity()) {
          e.preventDefault();
          Aww.confirm(
            $this.attr("data-message"),
            "",
            function () {
              $form.submit();
            },
            function () {},
            submit_text,
            cancel_text,
            icon
          );
        } else {
          $form[0].reportValidity();
        }
      } else {
        console.error("Toggle Confirm : Parent <form> not found.");
      }
    }
  );
};

// call function
Aww.notification = function (type, message, options, id = 0) {
  var html = "";
  var check_class = "";

  if (type == "success") {
    check_class = "Success";
  } else if (type == "error") {
    check_class = "Error";
  } else if (type == "info") {
    check_class = "";
  }

  html += '<div class="row remove-toast event_' + id + '">';
  html += '<div class="col">';
  html +=
    '<div role="alert" aria-live="assertive" aria-atomic="true" class="toast ' +
    type +
    '" data-autohide="false">';
  html += '<div class="toast-icon">';
  html += '<div class="icon-dot"></div>';
  html += "</div>";
  html += '<div class="toast-content">';
  html += '<p class="toast-type">' + check_class + "</p>";
  html += '<div class="d-flex justify-content-between">';
  html += '<p class="toast-message">' + message + "</p>";
  // html += '<a href="#" target="_blank" class="toast-link">';
  // html +=
  //   '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="15.469" viewBox="0 0 20 15.469"><g id="viewer" transform="translate(0 -58)"><g id="Group_57666" data-name="Group 57666" transform="translate(0 58)"><g id="Group_57665" data-name="Group 57665" transform="translate(0)"><path id="Path_78661" data-name="Path 78661" d="M169.539,274a3.536,3.536,0,1,0,3.536,3.536A3.54,3.54,0,0,0,169.539,274Zm0,5.5a1.964,1.964,0,1,1,1.964-1.964A1.966,1.966,0,0,1,169.539,279.5Z" transform="translate(-159.539 -265.603)" fill="#778498"/><path id="Path_78662" data-name="Path 78662" d="M19.916,68.081a10.31,10.31,0,0,0-3.95-4.174l2.115-2.11a.78.78,0,1,0-1.1-1.1L14.5,63.17a11.715,11.715,0,0,0-3.714-.857V58.78a.781.781,0,0,0-1.562,0v3.533a11.715,11.715,0,0,0-3.714.857L3.024,60.694a.78.78,0,1,0-1.1,1.1l2.115,2.11a10.31,10.31,0,0,0-3.95,4.174.781.781,0,0,0,1.395.7A9.461,9.461,0,0,1,10,63.847a9.461,9.461,0,0,1,8.521,4.937.781.781,0,0,0,1.395-.7Z" transform="translate(0 -58)" fill="#778498"/></g></g></g></svg>';
  // html += "</a>";
  html += "</div>";
  html += "</div>";
  html += '<div class="toast-close">';
  html +=
    '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 15.642 15.642" xmlns:xlink="http://www.w3.org/1999/xlink" enable-background="new 0 0 15.642 15.642"><path fill-rule="evenodd" d="M8.882,7.821l6.541-6.541c0.293-0.293,0.293-0.768,0-1.061  c-0.293-0.293-0.768-0.293-1.061,0L7.821,6.76L1.28,0.22c-0.293-0.293-0.768-0.293-1.061,0c-0.293,0.293-0.293,0.768,0,1.061  l6.541,6.541L0.22,14.362c-0.293,0.293-0.293,0.768,0,1.061c0.147,0.146,0.338,0.22,0.53,0.22s0.384-0.073,0.53-0.22l6.541-6.541  l6.541,6.541c0.147,0.146,0.338,0.22,0.53,0.22c0.192,0,0.384-0.073,0.53-0.22c0.293-0.293,0.293-0.768,0-1.061L8.882,7.821z"></path></svg>';
  html += "</div>";
  html += "</div>";
  html += "</div>";
  html += "</div>";

  if (type == "info") {
    if (options == "closed") {
      if (id) {
        toastClosed(id);
      }
    } else if (options == "open") {
      if (id) {
        $(".toast-noti").append(html);
        $(".toast." + type).toast("show");
        toastOpen(id);
      }
    }
  } else {
    $(".toast-noti").append(html);
    $(".toast." + type).toast("show");
    var toast_list = $(".toast");

    if (type == "success") {
      for (let i = 0; i < toast_list.length; i++) {
        setTimeout(function () {
          // $('.toast.'+type).toast('hide');
          var parent = $(toast_list).parents(".remove-toast");
          parent.remove();
        }, 2000);
      }
    }
  }

  $(".toast-close").click(function () {
    var parent = $(this).parents(".remove-toast");
    parent.remove();
  });

  function toastOpen(id) {
    $(".toast-close").addClass("event_" + id);
  }

  function toastClosed(id) {
    var click = $(".toast-close.event_" + id).click();
    var parent = click.parents(".remove-toast.event_" + id);
    parent.remove();
  }
};


Aww.confirm = function (title, text, onOK, onCancel, submit_text, cancel_text) {
  if (typeof swal != "undefined") {
    swal
      .fire({
        title: title,
        icon: "question",
        text: text,
        showCancelButton: true,
        confirmButtonText: submit_text,
        confirmButtonClass: "btn btn-primary mx-5px",
        cancelButtonText: cancel_text,
        cancelButtonClass: "btn btn-light mx-5px",
        buttonsStyling: false,
      })
      .then(function (result) {
        if (result.isConfirmed) {
          if (typeof onOK === "function") onOK();
        } else {
          if (typeof onCancel === "function") onCancel(result);
        }
      });
  }
};

Aww.alert = function (title, text, onOK) {
  if (typeof swal != "undefined") {
    swal
      .fire({
        title: title,
        text: text,
        type: "warning",
        confirmButtonText: '<i class="fa fa-check"></i> ตกลง',
        confirmButtonClass: "btn btn-primary",
        buttonsStyling: false,
      })
      .then(function () {
        if (typeof onOK === "function") onOK();
      });
  } else {
    alert(title + "\n" + text);
    if (typeof onOK === "function") onOK();
  }
};

Aww.initTemplate = function ($scope) {
  if (typeof $scope == "undefined") {
    $scope = $(document);
  }
  $scope.find(".z-template").each(function (index, el) {
    var $template = $(el);
    var id = $template.attr("id");
    if (id) {
      Aww.template[id] = $template.html();
      $template.remove();
    }
  });
};

Aww.compileTemplate = function (templateID, data) {
  var $template = $("#" + templateID);
  if ($template.length > 0) {
    Aww.template[templateID] = $template.html();
    $template.remove();
  }

  if (typeof Aww.template[templateID] == "undefined") {
    console.error("Undefined Template ID: " + templateID);
    var html = "Undefined Template ID: " + templateID;
    return html;
  }
  var html = Aww.template[templateID];
  for (var key in data) {
    if (typeof data[key] != "undefined") {
      var re = new RegExp("{{" + key + "}}", "g");
      html = html.replace(re, data[key]);
    }
  }
  return html;
};

Aww.helper.uid = function (prefix) {
  if (typeof prefix == "undefined") {
    prefix = "";
  }
  return (
    prefix +
    "xxxxxxxxxxxxx".replace(/[x]/g, function (c) {
      var r = (Math.random() * 16) | 0,
        v = c == "x" ? r : (r & 0x3) | 0x8;
      return v.toString(16);
    })
  );
};

Aww.helper.short_text = function (text, text_length = 200) {
  let raw_text = "";

  if (typeof text == "string") {
    raw_text = text.trim();
  } else if (typeof text == "object") {
    raw_text = $(text).text().trim();
  }
  if (raw_text.length > text_length) {
    return raw_text.substring(0, text_length) + "...";
  } else {
    return raw_text;
  }
};

Aww.formatMoney = function (
  amount,
  decimalCount = 2,
  decimal = ".",
  thousands = ","
) {
  try {
    decimalCount = Math.abs(decimalCount);
    decimalCount = isNaN(decimalCount) ? 2 : decimalCount;

    const negativeSign = amount < 0 ? "-" : "";

    let i = parseInt(
      (amount = Math.abs(Number(amount) || 0).toFixed(decimalCount))
    ).toString();
    let j = i.length > 3 ? i.length % 3 : 0;

    return (
      negativeSign +
      (j ? i.substr(0, j) + thousands : "") +
      i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + thousands) +
      (decimalCount
        ? decimal +
          Math.abs(amount - i)
            .toFixed(decimalCount)
            .slice(2)
        : "")
    );
  } catch (e) {
    console.log(e);
  }
};

Aww.calVat = function (price, vat_type = "exclude") {
  var result = [];

  if (vat_type == "exclude") {
    result["vat"] = (price * 7) / 100;
    result["total_price"] = price + (price * 7) / 107;
  } else {
    result["vat"] = (price * 7) / 107;
    result["total_price"] = price;
  }

  return result;
};

Aww.readImage = function (fileInput, onSuccess) {
  if (!fileInput.files || !fileInput.files[0]) return false;
  var imgPath = $(fileInput)[0].value;
  var extn = imgPath.substring(imgPath.lastIndexOf(".") + 1).toLowerCase();
  if (extn == "gif" || extn == "png" || extn == "jpg" || extn == "jpeg") {
    if (typeof FileReader != "undefined") {
      var reader = new FileReader();
      reader.onload = function (e) {
        // get loaded data and render thumbnail.
        if (typeof onSuccess === "function") onSuccess(e);
      };
      // read the image file as a data URL.
      reader.readAsDataURL(fileInput.files[0]);
      return "success";
    } else {
      return "not support";
    }
  } else {
    return "invalid type";
  }
};
Aww.selectMultiValue = function () {
  let $select_multi_value = $(".select-multi-value");

  $select_multi_value.each(function () {
    let options_data = $(this).children("option:selected").data();
    let $select_multi_container = $(this).parents(".select-multi-container");
    $select_multi_container.find(`.select-multi-target`).val("");
    if (typeof options_data != "undefined") {
      for (key in options_data) {
        $select_multi_container
          .find(`.select-multi-target[data-target=${key}]`)
          .val(options_data[key]);
        $select_multi_container
          .find(`.select-multi-target.target-html[data-target=${key}]`)
          .html(options_data[key]);
      }
    }
  });
};

Aww.timeStamp = function (str) {
  return new Date(str.replace(/^(\d{2}\-)(\d{2}\-)(\d{4})$/, "$2$1$3"))
    .getTime()
    .toString()
    .substring(0, 10);
};

Aww.formatDate = function (format, timestamp) {
  //   example 1: date('H:m:s \\m \\i\\s \\m\\o\\n\\t\\h', 1062402400);
  //   returns 1: '09:09:40 m is month'
  //   example 2: date('F j, Y, g:i a', 1062462400);
  //   returns 2: 'September 2, 2003, 2:26 am'
  //   example 3: date('Y W o', 1062462400);
  //   returns 3: '2003 36 2003'
  //   example 4: x = date('Y m d', (new Date()).getTime()/1000);
  //   example 4: (x+'').length == 10 // 2009 01 09
  //   returns 4: true
  //   example 5: date('W', 1104534000);
  //   returns 5: '53'
  //   example 6: date('B t', 1104534000);
  //   returns 6: '999 31'
  //   example 7: date('W U', 1293750000.82); // 2010-12-31
  //   returns 7: '52 1293750000'
  //   example 8: date('W', 1293836400); // 2011-01-01
  //   returns 8: '52'
  //   example 9: date('W Y-m-d', 1293974054); // 2011-01-02
  //   returns 9: '52 2011-01-02'

  var that = this;
  var jsdate, f;
  // Keep this here (works, but for code commented-out below for file size reasons)
  // var tal= [];
  var txt_words = [
    "Sun",
    "Mon",
    "Tues",
    "Wednes",
    "Thurs",
    "Fri",
    "Satur",
    "January",
    "February",
    "March",
    "April",
    "May",
    "June",
    "July",
    "August",
    "September",
    "October",
    "November",
    "December",
  ];
  // trailing backslash -> (dropped)
  // a backslash followed by any character (including backslash) -> the character
  // empty string -> empty string
  var formatChr = /\\?(.?)/gi;
  var formatChrCb = function (t, s) {
    return f[t] ? f[t]() : s;
  };
  var _pad = function (n, c) {
    n = String(n);
    while (n.length < c) {
      n = "0" + n;
    }
    return n;
  };
  f = {
    // Day
    d: function () {
      // Day of month w/leading 0; 01..31
      return _pad(f.j(), 2);
    },
    D: function () {
      // Shorthand day name; Mon...Sun
      return f.l().slice(0, 3);
    },
    j: function () {
      // Day of month; 1..31
      return jsdate.getDate();
    },
    l: function () {
      // Full day name; Monday...Sunday
      return txt_words[f.w()] + "day";
    },
    N: function () {
      // ISO-8601 day of week; 1[Mon]..7[Sun]
      return f.w() || 7;
    },
    S: function () {
      // Ordinal suffix for day of month; st, nd, rd, th
      var j = f.j();
      var i = j % 10;
      if (i <= 3 && parseInt((j % 100) / 10, 10) == 1) {
        i = 0;
      }
      return ["st", "nd", "rd"][i - 1] || "th";
    },
    w: function () {
      // Day of week; 0[Sun]..6[Sat]
      return jsdate.getDay();
    },
    z: function () {
      // Day of year; 0..365
      var a = new Date(f.Y(), f.n() - 1, f.j());
      var b = new Date(f.Y(), 0, 1);
      return Math.round((a - b) / 864e5);
    },

    // Week
    W: function () {
      // ISO-8601 week number
      var a = new Date(f.Y(), f.n() - 1, f.j() - f.N() + 3);
      var b = new Date(a.getFullYear(), 0, 4);
      return _pad(1 + Math.round((a - b) / 864e5 / 7), 2);
    },

    // Month
    F: function () {
      // Full month name; January...December
      return txt_words[6 + f.n()];
    },
    m: function () {
      // Month w/leading 0; 01...12
      return _pad(f.n(), 2);
    },
    M: function () {
      // Shorthand month name; Jan...Dec
      return f.F().slice(0, 3);
    },
    n: function () {
      // Month; 1...12
      return jsdate.getMonth() + 1;
    },
    t: function () {
      // Days in month; 28...31
      return new Date(f.Y(), f.n(), 0).getDate();
    },

    // Year
    L: function () {
      // Is leap year?; 0 or 1
      var j = f.Y();
      return ((j % 4 === 0) & (j % 100 !== 0)) | (j % 400 === 0);
    },
    o: function () {
      // ISO-8601 year
      var n = f.n();
      var W = f.W();
      var Y = f.Y();
      return Y + (n === 12 && W < 9 ? 1 : n === 1 && W > 9 ? -1 : 0);
    },
    Y: function () {
      // Full year; e.g. 1980...2010
      return jsdate.getFullYear();
    },
    y: function () {
      // Last two digits of year; 00...99
      return f.Y().toString().slice(-2);
    },

    // Time
    a: function () {
      // am or pm
      return jsdate.getHours() > 11 ? "pm" : "am";
    },
    A: function () {
      // AM or PM
      return f.a().toUpperCase();
    },
    B: function () {
      // Swatch Internet time; 000..999
      var H = jsdate.getUTCHours() * 36e2;
      // Hours
      var i = jsdate.getUTCMinutes() * 60;
      // Minutes
      var s = jsdate.getUTCSeconds(); // Seconds
      return _pad(Math.floor((H + i + s + 36e2) / 86.4) % 1e3, 3);
    },
    g: function () {
      // 12-Hours; 1..12
      return f.G() % 12 || 12;
    },
    G: function () {
      // 24-Hours; 0..23
      return jsdate.getHours();
    },
    h: function () {
      // 12-Hours w/leading 0; 01..12
      return _pad(f.g(), 2);
    },
    H: function () {
      // 24-Hours w/leading 0; 00..23
      return _pad(f.G(), 2);
    },
    i: function () {
      // Minutes w/leading 0; 00..59
      return _pad(jsdate.getMinutes(), 2);
    },
    s: function () {
      // Seconds w/leading 0; 00..59
      return _pad(jsdate.getSeconds(), 2);
    },
    u: function () {
      // Microseconds; 000000-999000
      return _pad(jsdate.getMilliseconds() * 1000, 6);
    },

    // Timezone
    e: function () {
      // Timezone identifier; e.g. Atlantic/Azores, ...
      // The following works, but requires inclusion of the very large
      // timezone_abbreviations_list() function.
      /*              return that.date_default_timezone_get();
       */
      throw "Not supported (see source code of date() for timezone on how to add support)";
    },
    I: function () {
      // DST observed?; 0 or 1
      // Compares Jan 1 minus Jan 1 UTC to Jul 1 minus Jul 1 UTC.
      // If they are not equal, then DST is observed.
      var a = new Date(f.Y(), 0);
      // Jan 1
      var c = Date.UTC(f.Y(), 0);
      // Jan 1 UTC
      var b = new Date(f.Y(), 6);
      // Jul 1
      var d = Date.UTC(f.Y(), 6); // Jul 1 UTC
      return a - c !== b - d ? 1 : 0;
    },
    O: function () {
      // Difference to GMT in hour format; e.g. +0200
      var tzo = jsdate.getTimezoneOffset();
      var a = Math.abs(tzo);
      return (
        (tzo > 0 ? "-" : "+") + _pad(Math.floor(a / 60) * 100 + (a % 60), 4)
      );
    },
    P: function () {
      // Difference to GMT w/colon; e.g. +02:00
      var O = f.O();
      return O.substr(0, 3) + ":" + O.substr(3, 2);
    },
    T: function () {
      // Timezone abbreviation; e.g. EST, MDT, ...
      // The following works, but requires inclusion of the very
      // large timezone_abbreviations_list() function.
      /*              var abbr, i, os, _default;
      if (!tal.length) {
        tal = that.timezone_abbreviations_list();
      }
      if (that.php_js && that.php_js.default_timezone) {
        _default = that.php_js.default_timezone;
        for (abbr in tal) {
          for (i = 0; i < tal[abbr].length; i++) {
            if (tal[abbr][i].timezone_id === _default) {
              return abbr.toUpperCase();
            }
          }
        }
      }
      for (abbr in tal) {
        for (i = 0; i < tal[abbr].length; i++) {
          os = -jsdate.getTimezoneOffset() * 60;
          if (tal[abbr][i].offset === os) {
            return abbr.toUpperCase();
          }
        }
      }
      */
      return "UTC";
    },
    Z: function () {
      // Timezone offset in seconds (-43200...50400)
      return -jsdate.getTimezoneOffset() * 60;
    },

    // Full Date/Time
    c: function () {
      // ISO-8601 date.
      return "Y-m-d\\TH:i:sP".replace(formatChr, formatChrCb);
    },
    r: function () {
      // RFC 2822
      return "D, d M Y H:i:s O".replace(formatChr, formatChrCb);
    },
    U: function () {
      // Seconds since UNIX epoch
      return (jsdate / 1000) | 0;
    },
  };
  this.formatDate = function (format, timestamp) {
    that = this;
    jsdate =
      timestamp === undefined
        ? new Date() // Not provided
        : timestamp instanceof Date
        ? new Date(timestamp) // JS Date()
        : new Date(timestamp * 1000); // UNIX timestamp (auto-convert to int)
    return format.replace(formatChr, formatChrCb);
  };
  return this.formatDate(format, timestamp);
};

Aww.checkValidity = function (form_ele) {
  var scope_form = $(form_ele);
  if (scope_form[0].checkValidity()) {
    return true;
  } else {
    scope_form[0].reportValidity();
    return false;
  }
};

$(document).ready(function () {
  Aww.handleIE();
  Aww.initClipboard();
  Aww.initTooltip();
  Aww.initDatePicker();
  Aww.handleScrollTop();
  Aww.handleFocusModalInput();
  Aww.handleConfirm();
  Aww.selectMultiValue();

  $(document).off("change", ".select-multi-value");
  $(document).on("change", ".select-multi-value", function () {
    Aww.selectMultiValue();
  });
});
