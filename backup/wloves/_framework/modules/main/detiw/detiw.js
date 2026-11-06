$(function () {
  $("#btn-debug-code-container").draggable({
    containment: "body",
    axis: "y",
  });

  $("#debug-code-modal").draggable({
    containment: "body",
    handle: ".debug-code-header",
  });
  $("#debug-code-modal").resizable();

  $(document).on("click", ".__event_debug_mode", function (e) {
    e.preventDefault();
    $("#debug-code-modal").toggleClass("is_show");
  });

  $(document).on("click", ".debug-code-close", function (e) {
    e.preventDefault();
    $("#debug-code-modal").removeClass("is_show");
  });

  $(document).on("click", ".code-group", function (e) {
    e.preventDefault();
    $(this).toggleClass("active");
    $(".code-" + $(this).attr("data-id")).toggleClass("active");
  });

  $(document).on("click", "#debug-code-modal .expand", function (e) {
    e.preventDefault();
    $("#debug-code-modal .code-group").addClass("active");
    $("#debug-code-modal .code-response").addClass("active");
  });

  $(document).on("click", "#debug-code-modal .unexpand", function (e) {
    e.preventDefault();
    $("#debug-code-modal .code-group").removeClass("active");
    $("#debug-code-modal .code-response").removeClass("active");
  });
});
