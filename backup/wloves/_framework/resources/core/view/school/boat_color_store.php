<div class="content-header-container">
  <h3 class="header-title text-info">สี EVERY ชนิด</h3>
</div>
<div class="content-body-container">
  <p class="font-weight-bold text-info"> คลังสี </p>
  <div class="row">
    <div class="col-12 mb-3">
      <?= TiwForm::normal('text', '', ['name' => 'color', 'placeholder' => 'ค้นหาสีที่จะใช้นะครับ']); ?>
    </div>
    <div class="col-12">
      <div class="show-color text-info ">
      </div>
    </div>
  </div>
</div>

<?php W_Main::loadAsset("../../structure/js/color_data.js") ?>
<script>
  $(document).ready(function() {


    $('input[name="color"]').on('keyup', function() {
      var search_color = $(this).val();
      search_color = search_color.replace("#", "");
      search_color = search_color.toLowerCase();
      let search_colors = search_color.split("");

      if (search_colors.length < 3) {
        $('.show-color').html('ใส่ค่าสีมาให้เกิน 3 ตัวหน่อยครับ ไม่รวม # นะ ');
        return false;
      }

      var search_results = [];

      var highlight_word = ['', '', '', '', '', ''];
      $.each(data_color, function(data_color_index, data_color_val) {
        var original_color = data_color_val.name;
        original_color = original_color.replace("#", "");
        original_color = original_color.toLowerCase();
        let original_colors = original_color.split("");

        var point = 0;
        $.each(search_colors, function(search_color_index, search_color_val) {
          var found_laew = false;
          $.each(original_colors, function(original_color_index, original_color_val) {
            if ((original_color_val == search_color_val) && (original_color_index == search_color_index)) {
              point += 2;

              found_laew = true;
              original_colors[original_color_index] = ' ';
              highlight_word = original_colors;
              return false;
            }
          });

          if (!found_laew) {
            $.each(original_colors, function(original_color_index, original_color_val) {
              if (original_color_val == search_color_val) {
                point += 1;

                original_colors[original_color_index] = ' ';
                highlight_word = original_colors;

                return false;
              }
            });
          }
        });

        if (3 <= point) {
          data_color_val.point = point;
          data_color_val.highlight = highlight_word;
          search_results.push(data_color_val)
        }
      });

      if (!search_results.length) {
        $('.show-color').html('ไม่มีสีที่หาในระบบ');
        return false;
      }
      search_results.sort(function(a, b) {
        if (a.point > b.point) {
          return -1
        }
        if (a.point < b.point) {
          return 1
        }
        return 0;
      });
      console.log(search_results);
      var html = '';
      $.each(search_results, function(key, data) {
        html += '<p class="text-info"ตัวอย่าง</p>';
        html += '<p class="color"  style="background-color:' + data.name + ';color:' + data.color_text + '; border:1px solid ' + data.color_text + '">#';
        var data_color = data.name;
        data_color = data_color.replace("#", "");
        let data_colors = data_color.split("");
        $.each(data_colors, function(data_colors_index, data_colors_val) {
          if (data.highlight[data_colors_index] == ' ') {
            html += '<span class="highlight">' + data_colors_val + '</span>';
          } else {
            html += '<span>' + data_colors_val + '</span>';
          }
        });
        html += '</p>';
        html += '<p class="font-17px text-info" >ตัวแปลที่ใช้ใน CSS : <span class="code-container custom-copy  text-danger">' + data.css + '</span></p>';
        if (data.class_color.length !== 0) {
          html += '<p>Class : ' + data.class_color + '</p>';
        }
        if (data.class_bg.length !== 0) {
          html += '<p>Class : ' + data.class_bg + ' </p>';
        }
        html += '<p>' + data.description + '</p>';
        html += '<hr>';
        $('.show-color').fadeIn();
        $('.show-color').html(html);
        startCopyToClipboard();
        // if (data.point == 12) {
        //   return false;
        // }
      });
    });
  });
</script>