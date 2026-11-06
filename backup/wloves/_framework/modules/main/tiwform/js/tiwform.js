w.extend({
  tiwform: {
    getSelectData: function (ele_name = "") {
      var select_scope = w(ele_name);
      var select_id = select_scope.find("input[select_value]").val();
      var select_data = select_scope
        .find("input[select_value]")
        .attr("data-list");
      select_data = select_data
        ? jQuery.parseJSON($.base64.decode(select_data))
        : [];
      return select_data;
    },
    setTagData: function (ele_name = "", select_ids = "", callback) {
      var ele_name = $(ele_name);
      ele_name.find(".selected-area .selected-list").remove();
      ele_name
        .find(".select-list-group .select-group .select-list")
        .removeClass("active");
      $.each(select_ids, function (key, value) {
        var name = value.toString().replace(/\ /g, "_");
        name = name.replace(/\(/g, "");
        name = name.replace(/\)/g, "");
        name = name.replace(/\,/g, "");

        ele_name
          .find(".select-list-group .select-group .list_" + name)
          .addClass("active");
      });
      setTagAutoEvent();
      if (callback) {
        callback();
      }
    },
    getTagData: function (ele_name = "") {
      var ele_name = $(ele_name);
      var select_ids = ele_name.find("input[value-select]");
      var selected_ids = [];
      $.each(select_ids, function (key, value) {
        selected_ids.push($(value).val());
      });
      selected_ids.shift();
      return selected_ids;
    },
    encode: function (data_array = []) {
      return $.base64.btoa(JSON.stringify(data_array), true);
    },
    decode: function (data_array = []) {
      return JSON.parse(decodeURIComponent(escape(atob(data_array, true))));
      // return JSON.parse($.base64.decode(data_array, true));
    },
    setTel: function (ele_name = "", tel = "") {
      // w(w(ele_name)[0]).intlTelInput("setNumber", tel);
      var instance = window.intlTelInput(w(ele_name)[0]);
      instance.setNumber(tel);
    },
    setTag: function (ele_name = "", tag = [], callback) {
      var scope = w(ele_name);
      var name = scope.attr("name") ? scope.attr("name") : "";
      var html = '<div class="add-scan-tag-list">';
      html += '<input type="hidden" name="' + name + '" value="' + tag + '">';
      html +=
        '<div class="add-scan-tag-name" title="' + tag + '">' + tag + "</div>";
      html +=
        '<div class="add-scan-tag-delete __delete_tag_scan_event"><i></i></div>';
      html += "</div>";

      scope.find(".add-scan-tag-group").append(html);
      scope.find(".__input_tag_event").val("");
      if (callback) {
        callback();
      }
    },
    randomName: function (length = 10) {
      var result = "";
      var timestamp = Date.now();
      var characters =
        "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
      var charactersLength = characters.length;
      for (var i = 0; i < length; i++) {
        result += characters.charAt(
          Math.floor(Math.random() * charactersLength)
        );
      }
      return "__" + result;
    },
    //set value input type file audio
    setAudioData: function (ele_name = "", url = "") {
      var scope = w(ele_name);
      var name = url.split("/").pop();
      scope.find(".__sound-name").html(name);
      scope.find("audio").remove();
      var audio = '<audio src="' + url + '"></audio>';
      scope.prepend(audio);
      scope.find(".__sound-uploaded").attr("data-status", "pause");
      scope.attr("is-upload", 1);
    },
    setSelect(ele_name = "", value = "") {
      var scope = w(ele_name);
      if (value != "") {
        var select_html = scope
          .find(
            ".select_list_" +
            value.toString().replace("#", "").replace(" ", "_")
          )
          .html();
        scope.find(".select-img-group").html(select_html);
        var input_scope = scope.find("input[select_value]");
        input_scope.val(value);
        scope.find(".select_img_event").removeClass("selected");
        scope
          .find('.select_img_event[data-id="' + value + '"]')
          .addClass("selected");
        input_scope.trigger("change");
      } else {
        var placeholder = scope.find("input[select_value]").attr("placeholder");
        scope.find(".dropdown-menu .dropdown-item").removeClass("selected");
        scope.find("input[select_value]").val("");
        scope.find(".select_img_event").removeClass("selected");
        if (placeholder) {
          scope
            .find(".select-img-group")
            .html('<div class="select-placeholder">' + placeholder + "</div>");
        }
      }
    },
  },
});
