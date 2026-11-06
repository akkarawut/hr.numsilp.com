w.extend({
  global: {
    view: [],
    controller: [],
    cleave: {},
  },
  date: (date) => {
    if (date) {
      return new Date(date);
    } else {
      return new Date();
    }
  },
  formatDate: (date, format) => {
    var date = w.date(date);
    if (format) {
      let d = date.getDate();
      let m = date.getMonth() + 1;
      let y = date.getFullYear();
      let h = date.getHours();
      let i = date.getMinutes();
      let s = date.getSeconds();

      if (d < 10) {
        d = "0" + d;
      }

      if (m < 10) {
        m = "0" + m;
      }

      if (h < 10) {
        h = "0" + h;
      }

      if (i < 10) {
        i = "0" + i;
      }

      if (s < 10) {
        s = "0" + s;
      }

      let month = [
        "Jan",
        "Feb",
        "Mar",
        "Apr",
        "May",
        "Jun",
        "Jul",
        "Aug",
        "Sep",
        "Oct",
        "Nov",
        "Dec",
      ];
      let fullMonth = [
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

      let monthName = month[m - 1];
      let fullMonthName = fullMonth[m - 1];

      format = format.replace("d", d);
      format = format.replace("m", m);
      format = format.replace("Y", y);
      format = format.replace("H", h);
      format = format.replace("i", i);
      format = format.replace("s", s);

      format = format.replace("F", fullMonthName);
      format = format.replace("M", monthName);

      return format;
    } else {
      return w.date(date);
    }
  },
  formatNumber: (number, decimal = 2, separator = ",", currency) => {
    number = parseFloat(number);
    if (decimal) {
      number = number.toFixed(decimal);
    } else {
      number = number.toFixed(0);
    }
    if (separator) {
      let split = number.toString().split(".");

      number = split[0].replace(/\B(?=(\d{3})+(?!\d))/g, separator);
      decimalNumber = split[1] === undefined ? 0 : split[1];
      decimalNumber = decimalNumber ? "." + decimalNumber : "";
      number = number + decimalNumber;
    }

    if (currency) {
      number = currency + number;
    }

    return number;
  },
  formatPercent: (number, decimal = 2, separator = "%") => {
    number = parseFloat(number);

    if (decimal) {
      number = number.toFixed(decimal);
    } else {
      number = number.toFixed(0);
    }

    if (separator) {
      let split = number.toString().split(".");
      number = split[0].replace(/\B(?=(\d{3})+(?!\d))/g, separator);
      decimalNumber = split[1] === undefined ? 0 : split[1];
      decimalNumber = decimalNumber ? "." + decimalNumber : "";
      number = number + decimalNumber;
    }

    number = number + "%";

    return number;
  },
  formatPhone: (number, format) => {
    if (number) {
      number = number.toString();
      if (format === "3-3-4") {
        return number.replace(/(\d{3})(\d{3})(\d{4})/, "$1-$2-$3");
      }

      if (format === "(3) 3-4") {
        return number.replace(/(\d{3})(\d{3})(\d{4})/, "($1) $2-$3");
      }

      if (format === "3.3.4") {
        return number.replace(/(\d{3})(\d{3})(\d{4})/, "$1.$2.$3");
      }

      if (format === "3 3 4") {
        return number.replace(/(\d{3})(\d{3})(\d{4})/, "$1 $2 $3");
      }
    } else {
      return "";
    }
  },
  file: (input) => {
    if (input.files && input.files[0]) {
      return input.files[0];
    } else {
      return "File not found";
    }
  },
  clipBoard: (text) => {
    let textArea = document.createElement("textarea");
    textArea.value = text;
    document.body.appendChild(textArea);
    textArea.select();
    document.execCommand("Copy");
    textArea.remove();
  },
  uid: function (prefix) {
    if (typeof prefix == "undefined") {
      prefix = "";
    }
    return (
      prefix +
      "xxxxxxxxxxxxx".replace(/[x]/g, function (c) {
        let r = (Math.random() * 16) | 0,
          v = c == "x" ? r : (r & 0x3) | 0x8;
        return v.toString(16);
      })
    );
  },
  notification: function (type, message, options, id = 0) {
    let html = "";
    let check_class = "";

    if (type == "success") {
      check_class = "Success";
    } else if (type == "error") {
      check_class = "Error";
    } else if (type == "info") {
      check_class = "";
    }
    id = w.uid();
    html = w.toastTemplate(id, type, check_class, message);

    if (type == "info") {
      if (options == "closed") {
        if (id) {
          toastClosed(id);
        }
      } else if (options == "open") {
        if (id) {
          w(".toast-noti").append(html);
          w(".toast." + type).addClass("show");
          toastOpen(id);
        }
      }
    } else {
      w(".toast-noti").append(html);
      w(".toast." + type).addClass("show");
      let toast_list = w(".toast");
      var parent;
      if (type == "success") {
        for (let i = 0; i < toast_list.length; i++) {
          setTimeout(function () {
            // $('.toast.'+type).toast('hide');
            parent = w(w(toast_list)[i]).parents(".remove-toast.event_" + id);
            parent.remove();
          }, 2000);
        }
      }
    }

    w.on("click", ".toast-close", function () {
      let parent = w(this).parents(".toast-container");
      if (parent && parent.length > 0) {
        parent.remove();
      } else {
        w(this).parents(".remove-toast").remove();
      }
    });

    function toastOpen(id) {
      w(".toast-close").addClass("event_" + id);
    }

    function toastClosed(id) {
      let click = w(".toast-close.event_" + id).click();
      let parent = click.parents(".remove-toast.event_" + id);
      parent.remove();
    }
  },
  toastTemplate: function (id = 0, type, check_class, message) {
    let html = "";
    html += `
      <div class="row remove-toast event_${id}">
        <div class="col">
          <div role="alert" aria-live="assertive" aria-atomic="true" class="toast ${type}" data-autohide="false">
            <div class="toast-icon">
              <div class="icon-dot"></div>
            </div>
            <div class="toast-content">
  
              <p class="toast-type">${check_class}</p>
              <div class="d-flex justify-content-between">
                <p class="toast-message">${message}</p>
              </div>
            </div>
            <div class="toast-close">
              <svg version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 15.642 15.642" xmlns:xlink="http://www.w3.org/1999/xlink" enable-background="new 0 0 15.642 15.642">
                <path fill-rule="evenodd" d="M8.882,7.821l6.541-6.541c0.293-0.293,0.293-0.768,0-1.061  c-0.293-0.293-0.768-0.293-1.061,0L7.821,6.76L1.28,0.22c-0.293-0.293-0.768-0.293-1.061,0c-0.293,0.293-0.293,0.768,0,1.061  l6.541,6.541L0.22,14.362c-0.293,0.293-0.293,0.768,0,1.061c0.147,0.146,0.338,0.22,0.53,0.22s0.384-0.073,0.53-0.22l6.541-6.541  l6.541,6.541c0.147,0.146,0.338,0.22,0.53,0.22c0.192,0,0.384-0.073,0.53-0.22c0.293-0.293,0.293-0.768,0-1.061L8.882,7.821z"></path>
              </svg>
            </div>
          </div>
        </div>
      </div>
    `;
    return html;
  },
  timeStamp: function (str) {
    return new Date(str.replace(/^(\d{2}\-)(\d{2}\-)(\d{4})$/, "$2$1$3"))
      .getTime()
      .toString()
      .substring(0, 10);
  },
  calVat: function (price, vat = 7, vat_type = "exclude") {
    var result = [];

    if (vat_type == "exclude") {
      result["vat"] = (price * vat) / 100;
      result["total_price"] = price + (price * vat) / (100 + vat);
    } else {
      result["vat"] = (price * vat) / 107;
      result["total_price"] = price;
    }

    return result;
  },
  shortText: function (text, text_length = 200) {
    let raw_text = "";

    if (typeof text == "string") {
      raw_text = text.trim();
    } else if (typeof text == "object") {
      raw_text = w(text).text().trim();
    }
    if (raw_text.length > text_length) {
      return raw_text.substring(0, text_length) + "...";
    } else {
      return raw_text;
    }
  },
  preventMultipleSubmit: function () {
    $("form").submit(function (e) {
      var btn = $(this).find('button[type="submit"]');
      btn.prop("disabled", true);
    });
  },
  getLink: function (path) {
    var url = window.location.href;
    var arr = url.split("/");
    var newUrl = arr[0] + "//" + arr[2] + "/" + arr[3] + "/" + path;
    return newUrl;
  },
  loadingPage: function () {
    w(document).on("click", "a[href]", function (e) {
      let hashtag = this.href;
      hashtag = hashtag ? hashtag.indexOf("#") : (hashtag = -1);
      let is_not_loading = e.target.closest(".__event_non_redirect");
      if (is_not_loading == null && !this.classList.contains('none-loading')) {
        let hashtag = this.href;
        hashtag = hashtag ? hashtag.indexOf("#") : (hashtag = -1);
        if (!this.hasAttribute("target") && hashtag == -1) {
          let loading = document.createElement("div");
          loading.id = "loading";
          loading.innerHTML = `
          <div class="loading__page">
            <div class="loading__div">
              <div class="loading__icon"></div>
              <div class="loading-text">Loading...</div>
            </div>
          </div>
        `;
          if (!e.ctrlKey && !e.metaKey) {
            w("body")[0].append(loading);
          }
        }
      }
    });
  },
  setDecimal: function (e, count) {
    if (e.target.value.indexOf(".") == -1) {
      return;
    }
    if (e.target.value.length - e.target.value.indexOf(".") > count) {
      e.target.value = parseFloat(e.target.value).toFixed(count);
    }
  },
  checkValidity: function (form_ele) {
    let scope_form = w(form_ele);
    if (scope_form) {
      return true;
    } else {
      return false;
    }
  },
  resize: function (callback) {
    addEventListener("resize", (event) => {
      return callback(event);
    });
  },
  countNavX: function () {
    let uri = window.location.href;
    uri = uri.split("/");
    let index_framework = uri.indexOf("_framework");
    if (index_framework != -1) {
      url = "../../../_framework/resources/layout/ajax/ajax_count_sidenav.php";
    } else {
      url = "../../_framework/resources/layout/ajax/ajax_count_sidenav.php";
    }
    var is_count = w(".nav-x").hasClass("show-count");
    if (is_count) {
      wx.post(url, "", (res) => {
        let data = JSON.parse(res);

        for (const key in data) {
          const code = data[key].code;
          const count_nav = data[key].count_nav;

          for (const key_ in count_nav) {
            if (Object.hasOwnProperty.call(count_nav, key_)) {
              if (initial_data.includes(key_)) {
                let html_count = "";
                if (typeof count_nav[key_] != "object") {
                  if (count_nav[key_] && count_nav[key_] > 0) {
                    html_count = `<div class="__nav_x_count_item">${count_nav[key_]}</div>`;
                  }
                } else {
                  for (const key_count_nav in count_nav[key_]) {
                    if (
                      Object.hasOwnProperty.call(count_nav[key_], key_count_nav)
                    ) {
                      const element = count_nav[key_][key_count_nav];
                      if (element.count && element.count > 0) {
                        html_count += `<div class="__nav_x_count_item" style="background-color:${element.color}">${element.count}</div>`;
                      }
                    }
                  }
                }
                w(".__nav_x_count_" + key_).html(html_count);
              }
            }
          }
        }
      });
    }
  },
  serializeJSON: function (form) {
    const data = new FormData(form);
    const obj = {};

    data.forEach((value, key) => {
      key = key.replace(/\[([^\]]+)\]/g, ".$1");
      key = key.replace(/^\./, "");
      key = key.split(".");
      let current = obj;
      for (let i = 0; i < key.length - 1; i++) {
        if (!current[key[i]]) {
          current[key[i]] = {};
        }
        current = current[key[i]];
      }
      if (current[key[key.length - 1]]) {
        if (!Array.isArray(current[key[key.length - 1]])) {
          current[key[key.length - 1]] = [current[key[key.length - 1]]];
        }
        current[key[key.length - 1]].push(value);
        return;
      } else {
        current[key[key.length - 1]] = value;
      }
    });

    return JSON.stringify(obj);
  },
  setCookie: function (cname, cvalue, exdays) {
    const d = new Date();
    d.setTime(d.getTime() + exdays * 24 * 60 * 60 * 1000);
    let expires = "expires=" + d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
  },
  getCookie: function (cname) {
    let name = cname + "=";
    let decodedCookie = decodeURIComponent(document.cookie);
    let ca = decodedCookie.split(";");
    for (let i = 0; i < ca.length; i++) {
      let c = ca[i];
      while (c.charAt(0) == " ") {
        c = c.substring(1);
      }
      if (c.indexOf(name) == 0) {
        return c.substring(name.length, c.length);
      }
    }
    return "";
  },
  makeid: function (length) {
    var result = "";
    var characters =
      "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
    var charactersLength = characters.length;
    for (var i = 0; i < length; i++) {
      result += characters.charAt(Math.floor(Math.random() * charactersLength));
    }
    return "__" + result;
  }
});
