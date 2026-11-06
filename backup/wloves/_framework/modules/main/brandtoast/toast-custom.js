  document.addEventListener("DOMContentLoaded", () => {
    let class_toast = w('.toast-container');
    // w('.toast.'+class_toast).toast('show');

    if (class_toast && class_toast.length > 0) {
      class_toast = class_toast.attr('data-class');
      let toast_list = w('.toast');
    
      if(class_toast == 'success') {
        for (let i = 0; i < toast_list.length; i++) {
          setTimeout(function() {
            // w('.toast.'+class_toast).toast('hide');
            let parent = w(toast_list).parents(".remove-toast");
            parent.remove();
          }, 6000);
        }
      }
  
      if (class_toast) {
        w.on('click', '.toast-close', function () {
          let parent = w(this).parents(".toast-container");
          if (parent) {
            parent.remove();
          } else {
            w(this).parents(".remove-toast").remove();
          }
        });
      }
    }
  });