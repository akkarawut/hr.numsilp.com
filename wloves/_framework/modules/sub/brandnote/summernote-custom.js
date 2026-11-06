$(document).ready(function () {
  startBrandnoteEvent();
});

function startBrandnoteEvent() {
  var countClass = $('.theme-summernote');
  for (let i = 0; i < countClass.length; i++) {
    var textarea_id = $($('.theme-summernote')[i]).find('textarea').attr('id');
    var h = $('#' + textarea_id).data('h');
    $('#' + textarea_id).summernote({
      placeholder: 'Enter ...',
      height: h,
      fontNames: ['sans-serif', 'Arial', 'Arial Black', 'Comic Sans MS', 'Courier New', 'Helvetica', 'Impact', 'Tahoma', 'Times New Roman', 'Verdana', 'Roboto'],
      toolbar: [
        ['style', ['style']],
        ['fontname', ['fontname']],
        ['fontsize', ['fontsize']],
        ['style', ['bold', 'italic', 'underline', 'clear']],
        ['color', ['color']],
        ['height', ['height']],
        ['table', ['table']],
        ['para', ['ul', 'ol', 'paragraph']],
        // ['insert', ['link', 'picture', 'video', 'hr']],
        ['insert', ['link']],
        ['view', ['fullscreen', 'codeview', 'undo', 'redo', 'help']]
      ],
      // dialogsInBody: true
      // callbacks: {
      //   onPaste: function(e) {
      //     var bufferText = ((e.originalEvent || e).clipboardData || window.clipboardData).getData('text/html');
      //     e.preventDefault();
      //     var div = $('<div />');
      //     div.append(bufferText);
      //     div.find('*').removeAttr('style');
      //     setTimeout(function() {
      //       document.execCommand('insertHtml', false, div.html());
      //     }, 10);
      //   }
      // }
    });

    $('#' + textarea_id).on('summernote.change', function (we, contents, $editable) {
      $('#' + textarea_id).trigger('keyup');
    });
  }
}
