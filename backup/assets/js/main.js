
const nav_hamburger = document.getElementById('nav-hamburger');
const ele_sidebar = document.getElementById('hr-sidebar');
const ele_content = document.getElementById('hr-content');
nav_hamburger.addEventListener('click', function () {
  nav_hamburger.classList.toggle('active');
  if (nav_hamburger.classList.contains('active')) {
    ele_sidebar.setAttribute('data-collapse', true);
    ele_content.classList.add('is-half');
  } else {
    ele_sidebar.setAttribute('data-collapse', false);
    ele_content.classList.remove('is-half');
  }
});

w.ready(function () {
  //input text
  w(document).on("input", ".hr-form-control .input", function () {
    var scope = w(this).parents(".hr-form-control");
    var text = w(this).val();
    if (text.length > 0) {
      scope.find(".input-delete button").css("display", "flex");
    } else {
      scope.find(".input-delete button").css("display", "none");
    }
  });

  //delete text
  w(document).on(
    "click",
    ".hr-form-control .input-delete button",
    function () {
      var scope = w(this).parents(".hr-form-control");
      scope.find(".input").val("");
      w(this).css("display", "none");
      scope.find(".input").focus();
    }
  );

  //show hide password
  w(document).on(
    "click",
    ".hr-form-control .btn-password-manage",
    function () {
      var scope = w(this).parents(".hr-form-control");
      var status = w(this).attr("data-status");
      if (status == "show") {
        w(this).attr("data-status", "hide");
        scope.find(".input").attr("type", "password");
      } else {
        w(this).attr("data-status", "show");
        scope.find(".input").attr("type", "text");
      }
    }
  );

  w(document).on("change", ".event_change_txt_on_off input",
    function () {
      let scope = w(this).parents(".scope_box_event");
      let txt = scope.find(".label_text");
      if (w(this).is(':checked')) {
        txt.html('<span class="text-primary ml-5px">เปิดใช้งาน</span>');
      } else {
        txt.html('<span class="text-secondary ml-5px">ปิดใช้งาน</span>');
      }
    }
  );

  w(document).on("change", ".event_change_txt_can_login input",
    function () {
      let scope = w(this).parents(".scope_box_event");
      let txt = scope.find(".label_text");
      if (w(this).is(':checked')) {
        txt.html('<span class="text-primary ml-5px">เข้าใช้ได้</span>');
      } else {
        txt.html('<span class="text-secondary ml-5px">ปิดเข้าใช้งาน</span>');
      }
    }
  );
});


