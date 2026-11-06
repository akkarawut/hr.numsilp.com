w.extend({
  tiwdal: {
    getRegisterData: function (ele_name) {
      var result = w(ele_name).attr("data-register");
      result = jQuery.parseJSON($.base64.decode(result));
      return result;
    },
  },
});
