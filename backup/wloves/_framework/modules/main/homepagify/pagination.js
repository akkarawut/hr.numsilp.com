(function ($) {
  var pagify = {
    items: 0,
    container: null,
    totalPages: 1,
    keep_count: 0,
    perPage: 5,
    currentPage: 0,
    count_total: 0,
    size_option: [10, 15, 20, 0],
    data_sort: {},
    search_text: "",
    send_param: {},
    checked_item: {},
    column_index: {},
    sort_option: ["Show", "Newest", "Lastest update", "Oldest"],
    sort_value: ["", "newest", "lastest_update", "oldest"],
    sortData: "",
    loop: 0,
    count_filter_new: 0,
    createNavigation: function () {
      let scope = this.container;
      let fix_page = scope.data("fix_page") ? scope.data("fix_page") : false;
      if (fix_page) {
        this.perPage = fix_page;
      }
      if (this.perPage == 0) {
        this.totalPages = Math.ceil(this.items / this.items);
      } else {
        this.totalPages = Math.ceil(this.items / this.perPage);
      }
      let page_size = this.perPage;
      let sort_data = this.sortData;
      let start = 0;
      let max_button = 2;
      let end = this.totalPages;
      let total = this.totalPages;
      let page = this.currentPage;
      let next = page + 1;
      let items = this.items;
      let checked_item = this.checked_item;
      let column_index = this.column_index;
      let data_sort = this.data_sort;
      let id = scope.attr("id");
      let checked_list = checked_item[id] ? checked_item[id] : {};
      let checked_column = column_index[id] ? column_index[id] : {};
      let search_text = this.search_text;
      let send_param = this.send_param;
      // let count_total = scope.data("header_name");

      scope.find(".pagination").html("");
      scope.find(".select-size").html("");
      scope.find(".custom-button").html("");

      let select_size = "";
      let size;
      let sort_name;
      let selected;
      let header_name = scope.data("header_name");
      let count_filter = scope.find("th[data-filter]").length;
      let count_text = $("#" + id).find(".tag-search-text").length;
      count_filter_new = count_text;
      // data-not_all
      let not_all = scope.data("not_all") ? scope.data("not_all") : false;
      not_all = not_all ? true : false;
      if (not_all) {
        this.size_option = [10, 15, 20];
      }
      if (fix_page) {
        // if(jQuery.inArray(fix_page, this.size_option) !== -1){
        // }else{
        //   this.size_option = [10, 15, 20];
        //   this.size_option.push(fix_page);
        //   this.size_option.sort(function(a, b) {
        //     return a - b;
        //   });
        //   this.size_option.push(0);
        // }
        this.size_option = [fix_page];
        page_size = fix_page;
      }
      for (option = 0; option < this.size_option.length; option++) {
        size = this.size_option[option] == 0 ? "All" : this.size_option[option];
        selected = this.size_option[option] == page_size ? "selected" : "";
        select_size +=
          '<option value="' +
          this.size_option[option] +
          '" ' +
          selected +
          ">" +
          size +
          " /page </option>";
      }
      //สร้าง select pape footer
      // scope.find('.footer-pagination .select-size').append('Show <select class="page-size ml-5px">' + select_size + '</select>');
      //ถ้า ไม่มีจำนวนข้อมูล จะแสดงเป็น 0
      let count_items = 0;
      if (items != undefined) {
        count_items = items;
      }
      //ถ้าไม่มี data-filter จะไม่แสดงปุ่ม search
      let filter_sort = "";
      for (option = 0; option < this.sort_option.length; option++) {
        selected = this.sort_value[option] == sort_data ? "selected" : "";
        filter_sort +=
          '<option value="' +
          this.sort_value[option] +
          '" ' +
          selected +
          ">" +
          this.sort_option[option] +
          "</option>";
      }

      if (count_filter <= 0) {
        scope
          .find(".header-pagination .select-size")
          .append(
            '<select class="filter-sort">' +
            filter_sort +
            '</select><select class="page-size">' +
            select_size +
            "</select>"
          );
      } else {
        if (count_text > 0) {
          scope
            .find(".header-pagination .select-size")
            .append(
              '<select class="filter-sort">' +
              filter_sort +
              '</select><select class="page-size">' +
              select_size +
              "</select>"
            );
        } else {
          scope
            .find(".header-pagination .select-size")
            .append(
              '<select class="filter-sort">' +
              filter_sort +
              '</select><select class="page-size">' +
              select_size +
              "</select>"
            );
        }
      }
      //แสดง tag คำค้นหา
      scope
        .find(".filter-card .found-text")
        .html(
          "Found " +
          count_items +
          " from " +
          this.count_total +
          '<span class="px-5px">|</span>'
        );

      let has_excel = scope.find("table").hasClass("table-excel"); //เช็คเรียก ปุ่ม excel
      let has_column = scope.find("table").hasClass("table-colvis"); //เช็คเรียก ปุ่ม colvis (ซ่อน column)
      let table_btn = "";
      if (has_excel) {
        table_btn +=
          '<a class="btn btn-export pagify-export px-15px"> EXPORT </a>';
      }
      if (has_column) {
        table_btn +=
          '<button class="btn ml-5px btn-export btn-column"></button>';
      }
      scope.find(".header-pagination .custom-button").append(table_btn); //วาดปุ่ม excel/colvis
      //วาดท้ายตาราง
      let pagination = scope
        .find(".pagination")
        .append('<div class="my-pagination"></div>');

      //วาดจำนวนข้อมูลที่จะแสดง scope_table_data_count
      if ($('.scope_table_data_count')) {
        table_count_with_id = $('.scope_table_data_count' + "[table-id='" + id + "']");
        if (table_count_with_id) {
          table_count_with_id.html(count_items);
        }
        $('.scope_table_data_count:not([table-id]').html(count_items);
      }

      //วาดตัวเลือกจำนวนข้อมูลที่จะแสดง
      var current_page_size = scope.find(".select-size .page-size").val();
      var current_page = page + 1;

      var show_page = current_page_size * current_page;
      var show_size_page = show_page - count_items;
      if (show_size_page > 0) {
        show_page = show_page - show_size_page;
      }
      var current_item_no = parseInt(current_page_size) * page + 1;
      let show_item_no = current_item_no + " - " + show_page;
      if (count_items == 0) {
        show_item_no = 0;
      }
      var uri = window.location.href;
      var uri = uri.split("/");
      var index_framework = uri.indexOf("_framework");
      if (index_framework != -1) {
        path_link = "../../../";
      } else {
        path_link = "../../";
      }
      pagination.append(
        '<div class="">' + show_item_no + " of " + count_items + "</div>"
      );
      pagination.append(
        '<a class="nav prev page-item" data-next="' +
        (next - 1) +
        '"><img class="pagination-left" src="' +
        path_link +
        '_framework/modules/main/homepagify/image/angle-left.svg"></a>'
      );
      //วาดปุ่มย้อนหลัง
      pagination.append(
        '<a class="nav next page-item" data-next="' +
        (next + 1) +
        '"><img class="pagination-right" src="' +
        path_link +
        '_framework/modules/main/homepagify/image/angle-right.svg"></a>'
      );
      pagination.append(
        '<a class="page-fist d-none" data-page="' +
        (page + 1) +
        '"><img class="pagination-right" src="' +
        path_link +
        '_framework/modules/main/homepagify/image/angle-right.svg"></a>'
      );
      //append ปุ่มทั้งหมด
      scope.find(".pagination").append(pagination);

      let that = this;
      //ส่งจำนวนข้อมูลที่ต้องการแสดง
      this.pageNavigator = scope.find(".page-size").on("change", function () {
        var value = $(this).find("option:selected").val();
        scope.pagify(value, items, scope, search_text, send_param);
      });
      this.pageNavigator = scope.find(".filter-sort").on("change", function () {
        let sort = {};
        var filter_selected = $(this).find("option:selected").val();
        if (filter_selected == "newest") {
          sort = {
            insert_date_time: "DESC",
          };
        } else if (filter_selected == "oldest") {
          sort = {
            insert_date_time: "ASC",
          };
        } else if (filter_selected == "lastest_update") {
          sort = {
            update_date_time: "DESC",
          };
        } else {
          sort = {};
        }
        that.sortData = filter_selected;
        that.sortTable(
          sort,
          scope,
          page_size,
          page,
          items,
          send_param,
          column_index
        );
      });
      //checkebox
      let checked = scope.find("tbody");
      checked.find(".table-checked input").off("click");
      this.pageNavigator = checked
        .find(".table-checked input")
        .on("click", function () {
          let this_check = $(this);
          let check_id = this_check.attr("id");
          let check_val = this_check.val();
          if ($(this).is(":checked")) {
            checked_list[check_id] = check_val;
          } else {
            delete checked_list[check_id]; //ถ้าเช็คออกค่าจะถูกลบ
          }
          checked_item[id] = checked_list; //เก็บแยกของแต่ละตาราง key = table id
          // scope.find('input[name="checkboxAll"]').prop('checked', true);
          that.countItem(scope); //เรียกฟังชั่น count จำนวน checkbox ของตาราง
          that.checkBoxTable(checked_item, scope, page, items); //ส่งเข้าฟังก์ชั่นเพื่อไปอัพเดทค่า
        });
      //checked all
      scope.find(".table-checked-all input").off("click");
      this.pageNavigator = scope
        .find(".table-checked-all input")
        .on("click", function () {
          if ($(this).is(":checked")) {
            checked.find(".table-checked input").prop("checked", true);
            checked.find(".table-checked input").each(function (index) {
              let check_id = $(this).attr("id");
              let check_val = $(this).val();
              checked_list[check_id] = check_val;
            });
          } else {
            checked.find(".table-checked input").prop("checked", false);
            checked.find(".table-checked input").each(function (index) {
              let check_id = $(this).attr("id");
              delete checked_list[check_id];
            });
          }
          checked_item[id] = checked_list;
          that.checkBoxTable(checked_item, scope, page, items); //ส่งเข้าฟังก์ชั่นเพื่อไปอัพเดทค่า
        });

      //ส่งเลขหน้าที่ต้องการแสดง
      this.pageNavigator = scope.find(".select-page").on("change", function () {
        let value = $(this).find("option:selected").val();
        that.goToPage(value, scope, items, page_size, send_param, column_index); //ส่งเข้าฟังก์ชั่นเพื่อไปอัพเดทค่า
      });

      scope.find(".page-fist").off("click");
      this.pageNavigator = scope.find(".page-fist").on("click", function () {
        var el = $(this);
        that.goToPage(
          el.data("page"),
          scope,
          items,
          page_size,
          send_param,
          column_index
        ); //ส่งเข้าฟังก์ชั่นเพื่อไปอัพเดทค่า
      });

      //ส่งหน้าถัดไปหรือย้อนหลัง
      scope.find(".nav").off("click");
      this.pageNavigator = scope.find(".nav").on("click", function () {
        let el = $(this);
        that.goToPage(
          el.data("next"),
          scope,
          items,
          page_size,
          send_param,
          column_index
        ); //ส่งเข้าฟังก์ชั่นเพื่อไปอัพเดทค่า
      });

      //กดเลือก Sort
      scope.find(".table-sort thead tr th").off("click");
      let sort = {};
      this.pageNavigator = scope
        .find(".table-sort thead tr th")
        .on("click", function () {
          let has_class = $(this).hasClass("no-sort");
          //ถ้ามี no sort จะข้าม
          if (!has_class) {
            let has_asc = $(this).hasClass("ASC");
            let has_desc = $(this).hasClass("DESC");
            let order_by = "";
            let name = $(this).data("sort");
            //ล้าง class ใน th
            scope.find(".table-sort  thead tr th").removeClass("DESC");
            scope.find(".table-sort  thead tr th").removeClass("ASC");
            if (name !== undefined) {
              if (!has_asc && !has_desc) {
                $(this).addClass("ASC");
                order_by = "ASC";
              } else {
                if (has_asc) {
                  $(this).removeClass("ASC");
                  $(this).addClass("DESC");
                  order_by = "DESC";
                } else if (has_desc) {
                  $(this).removeClass("DESC");
                  $(this).addClass("ASC");
                  order_by = "ASC";
                }
              }
              if (!has_class) {
                sort[name] = order_by;
                that.sortTable(
                  sort,
                  scope,
                  page_size,
                  page,
                  items,
                  send_param,
                  column_index
                );
              }
            }
          }
        });

      //ซ่อน column
      scope.find(".table-checkColumn").off("click");
      this.pageNavigator = scope
        .find(".table-checkColumn")
        .on("click", function () {
          let column_check = $(this).val();
          if ($(this).is(":checked")) {
            delete checked_column[column_check];
            // scope.find('.'+column_check).removeClass('d-none')
          } else {
            checked_column[column_check] = column_check;
            // scope.find('.'+column_check).addClass('d-none')
          }

          column_index[id] = checked_column;
          that.checkColumIndex(
            column_index,
            scope,
            page_size,
            page,
            items,
            data_sort,
            send_param
          );
        });

      //modal ค้นหา
      scope.find(".btn-pagify-search").off("click");
      this.pageNavigator = scope
        .find(".btn-pagify-search")
        .on("click", function () {
          var id = scope.attr("id");
          var thead_tr = scope.find("thead tr th");
          var input_filter = {};
          var array_filter = [];
          var state_filter = $("#" + id).data("state_filter");
          if (state_filter) {
            state_filter = jQuery.parseJSON($.base64.decode(state_filter));
          }
          for (var i = 0; i < thead_tr.length; i++) {
            var filter = thead_tr.eq(i).data("filter");
            if (filter != undefined) {
              var filter = filter.split(",");
              var hasclass_help = thead_tr.eq(i).hasClass("help");
              var title = "";
              if (hasclass_help) {
                title = thead_tr.eq(i).find("div").eq(0).html();
              } else {
                title = thead_tr.eq(i).html();
              }

              var name = jQuery.parseJSON($.base64.decode(filter[0]));
              input_filter = {
                title: title,
                name: name,
                type: filter[1],
              };

              if (filter[1] == "select") {
                input_filter["option"] = jQuery.parseJSON(
                  $.base64.decode(filter[2])
                );
              }
              array_filter.push(input_filter);
            }
          }

          var data_filter = {};
          data_filter["filter_list"] = Object.assign({}, array_filter);
          data_filter["this_table"] = id;
          var url_link =
            "../../_framework/modules/homepagify/ajax/ajax_search_modal.php";
          $.post(url_link, data_filter).done(async function (response) {
            $("#pagify_search_modal")
              .find(".create-modal-search")
              .html(response);
            var value_input = send_param;
            $.each(value_input, function (key, value) {
              if (jQuery.isArray(value)) {
                if (value[0] != "") {
                  $("#pagify_search_modal")
                    .find(
                      'select[name="' +
                      key +
                      '"] option[value="' +
                      value[0] +
                      '"]'
                    )
                    .attr("selected", "selected");
                }
              } else if (value != "") {
                $("#pagify_search_modal")
                  .find('input[name="' + key + '"]')
                  .val(value);
              }
            });

            $("#pagify_search_modal").modal("show"); // $('body').append(response);
          });
        });

      if (this.loop == 2) {
        // scope.find('.page-fist[data-page="' + (page + 1) + '"]').click();
      }
    },

    sortTable: function (
      sort,
      scope,
      page_size,
      page,
      items,
      send_param,
      column_index,
      count_filter_new
    ) {
      this.container = scope;
      this.data_sort = sort;
      this.send_param = send_param;
      this.items = items;
      this.perPage = page_size;
      this.currentPage = page;
      this.column_index = column_index;

      this.showItems();
    },

    countItem: function (scope) {
      let table = scope;
      let count_checked = table.find(".table-checked input:checked").length;
      let count = table.find(".table-checked input").length;
      if (count_checked == count) {
        table.find('input[name="checkboxAll"]').prop("checked", true);
      } else {
        table.find('input[name="checkboxAll"]').prop("checked", false);
      }
      let button = $('button[data-is_check_table="' + table.attr("id") + '"]');
      button.attr("disabled", true);
      // $('.is-check-table').attr("disabled", true);
      if (count_checked > 0) {
        // $('.is-check-table').attr("disabled", false);
        button.attr("disabled", false);
      }
    },

    checkColumIndex: function (
      colum,
      scope,
      page_size,
      page,
      items,
      data_sort,
      send_param
    ) {
      this.container = scope;
      this.column_index = colum;
      this.perPage = page_size;
      this.currentPage = page;
      this.items = items;
      this.data_sort = data_sort;
      this.send_param = send_param;
      this.showItems();
    },

    checkBoxTable: function (checked, scope, page, items) {
      this.container = scope;
      this.checked_item = checked;
      this.currentPage = page;
      this.items = items;
      this.updateNavigation();
    },

    updateNavigation: function () {
      this.currentPage;
      this.createNavigation();
      let checked_box = this.checked_item;
      let scope = this.container;
      let scope_id = scope.attr("id");
      let colum_index = this.column_index[scope_id];
      let checked_array = [];

      $.each(checked_box[scope_id], function (key, value) {
        checked_array.push(value);
      });
      let convertValue = JSON.stringify(checked_array);
      let tbody = scope.find("tbody");
      let link = tbody.data("export");
      let array_link = "";
      if (link) {
        array_link = link.split("<array>");
      }
      let thead_tr = scope.find("thead tr th");
      let tbody_tr = tbody.find("tr").not(".title-group");
      for (let i = 0; i < tbody_tr.length; i++) {
        for (let j = 0; j < thead_tr.length; j++) {
          tbody_tr
            .eq(i)
            .find("td")
            .eq(j)
            .addClass("table-column-" + j);
          tbody_tr.eq(i).find("td").eq(j).removeClass("d-none");
        }
      }
      // if((this.loop == 2) && (Object.keys(send_param).length == 0)) {
      //   this.count_total = this.items;

      // }

      thead_tr.removeClass("d-none");
      for (const key in colum_index) {
        scope.find("." + key).addClass("d-none");
      }
      /* เพื่อใช้ปิดเปิดการทำงาน checked
      if(scope.find('table').hasClass('input-checked')) {
        var convertValue = JSON.stringify(checked_box[scope_id]);
        scope.find('input[data-checkbox_table="' + (scope_id) + '"]').val(convertValue);
      }
      */
      scope
        .find('input[data-checkbox_table="' + scope_id + '"]')
        .val(convertValue)
        .trigger("change");
      if (array_link[1] == 0) {
        scope.find(".pagify-export").attr("href", array_link[0]);
      } else if (array_link[1] != 0) {
        scope
          .find(".pagify-export")
          .attr("href", array_link[1] + "?data=" + array_link[0]);
      }

      for (const key in checked_box[scope_id]) {
        scope.find("#" + key).prop("checked", true);
      }

      /* เพื่อใช้ปิดเปิดการทำงาน group
      let has_group = scope.find('table').hasClass('table-group');
      if(!has_group) {
        scope.find('.title-group').addClass('d-none')
      } */

      if (this.currentPage == 0) {
        this.container.find(".pagination .nav.prev").addClass("disabled");
      } else {
        this.container
          .find(".pagination .nav.prev, .pagination .first")
          .removeClass("disabled");
      }

      if (this.currentPage == this.totalPages - 1) {
        this.container.find(".pagination .nav.next").addClass("disabled");
      } else {
        this.container.find(".pagination .nav.next").removeClass("disabled");
      }
      this.countItem(this.container);
      var pages = this.container.find(".pagination .page");
      pages.removeClass("current");
      this.container
        .find('.pagination .page[data-page="' + (this.currentPage + 1) + '"]')
        .attr("selected", true);
    },

    goToPage: function (
      page,
      scope,
      items,
      page_size,
      send_param,
      column_index,
      count_filter_new
    ) {
      this.container = scope;
      this.items = items;
      this.currentPage = page - 1;
      this.perPage = page_size;
      this.send_param = send_param;
      this.column_index = column_index;
      this.showItems();
    },

    drawnData: function (
      scope,
      count,
      perPage,
      currentPage,
      data_sort,
      send_param,
      column_index,
      count_param = 0
    ) {
      this.items = count;
      this.container = scope;
      this.perPage = perPage;
      this.currentPage = currentPage;
      this.data_sort = data_sort;
      this.send_param = send_param;
      this.column_index = column_index;
      if (count_param == 0) {
        this.count_total = count;
      }
      this.updateNavigation();
    },

    savingState: function (scope, data) {
      var uri = window.location.href;
      var uri = uri.split("/");
      var index_framework = uri.indexOf("_framework");
      if (index_framework != -1) {
        url_link =
          "../../../_framework/modules/main/homepagify/ajax/ajax_saving_state.php";
      } else {
        url_link =
          "../../_framework/modules/main/homepagify/ajax/ajax_saving_state.php";
      }
      var pathname = window.location.pathname;

      var data = data;
      var code = scope.data("code");

      if (!data["item_pagify_count"]) {
        data["item_pagify_count"] = this.count_total;
      }

      data["pathname"] = pathname;
      data["code"] = code;
      data["id"] = scope.attr("id");

      $.post(url_link, data).done(async function (response) { });
    },

    showItems: function () {
      let scope = this.container;
      let url_link = scope.data("url");
      let active = scope.data("active");
      let data_sort = this.data_sort;
      let search_text = this.search_text;
      let perPage = this.perPage;
      let currentPage = this.currentPage;
      let send_param = this.send_param;
      let column_index = this.column_index;
      let count_filter = count_filter_new;
      var $this = this;
      this.perPage = scope.find(".page-size option").filter(":selected").val();
      let test = scope.find(".select-page option");
      // console.log(this.loop)

      // this.loop++;
      let keep_count = this.keep_count;
      var item_per_page = Math.ceil(keep_count / this.perPage);
      if (keep_count != 0 && item_per_page <= currentPage) {
        this.currentPage = 0;
        this.perPage = 1;
      }

      // console.log(this.loop)
      var data = {
        page_no: this.currentPage + 1,
        page_size: this.perPage,
        search_text: search_text,
        data_sort: data_sort,
        active: active,
      };
      var save_data = {
        page_no: this.currentPage + 1,
        page_size: this.perPage,
        search_text: search_text,
        data_sort: data_sort,
        active: active,
      };
      var count_param = 0;
      for (const key in this.send_param) {
        let value_param = "";
        if (jQuery.isArray(this.send_param[key])) {
          value_param = this.send_param[key][0];
          if (value_param != "all") {
            count_param++;
          }
        } else {
          value_param = this.send_param[key];
          if (value_param != "") {
            count_param++;
          }
        }
        data[key] = value_param;
        save_data[key] = this.send_param[key];
      }
      var total_count = 0;
      var count_array = 0;
      url_link = url_link + "&count_filter=" + count_filter;
      if (url_link) {
        scope.find("tbody").remove();
        $.post(url_link, data).done(async function (response) {
          var thead_td = scope.find("thead tr th").length;
          var default_table = `<tbody><tr><td class="text-center" colspan="${thead_td}"> No data </td></tr></tbody>`;

          if (response == "") {
            scope.find("thead").after(default_table);
          } else {
            scope.find("thead").after(response);
          }
          // console.log(response);
          var tbody_td = scope.find("tbody tr").length;
          if (tbody_td <= 0) {
            scope.find("thead").after(default_table);
          }
          var count = scope.find("table tbody").attr("data-total_count");
          if (count !== undefined) {
            this.keep_count = count;
          }
          $this.drawnData(
            scope,
            count,
            perPage,
            currentPage,
            data_sort,
            send_param,
            column_index,
            count_param
          );
          if (count_param == 0) {
            save_data["item_pagify_count"] = count;
            count_array = count;
            // $this.count_total = count;
            // console.log(save_data);
          }
          let myObject = Object.assign({}, column_index[scope.attr("id")]);
          save_data["colum_index"] = myObject;
          $this.savingState(scope, save_data);
        });
      }
      // console.log(count_array);
      // console.log(save_data);
    },

    init: function (
      container,
      items,
      perPage,
      search_text,
      param,
      currentPage,
      count_filter_new
    ) {
      this.container = container;
      // console.log(this.container.attr('id'));
      var state_sort = container.data("state_sort");
      var id = container.attr("id");
      var count_total = $("#" + id).data("item_pagify_count");
      // console.log(this.container);

      var has_column = container.find("table").hasClass("table-colvis");
      var check_column = {};
      if (has_column) {
        var state_column = container.data("state_column");
        if (state_column) {
          state_column = jQuery.parseJSON($.base64.decode(state_column));
        }
        check_column[container.attr("id")] = state_column;
        $.each(state_column, function (key, value) {
          container.find('input[value="' + value + '"]').prop("checked", false);
        });
      }
      if (state_sort) {
        state_sort = jQuery.parseJSON($.base64.decode(state_sort));
      }

      $.each(state_sort, function (key, value) {
        container.find(`[data-sort="${key}"]`).addClass(value);
      });

      (this.currentPage = currentPage), (this.totalPages = 0);
      this.perPage = perPage;
      this.items = items;
      this.search_text = search_text;
      this.send_param = param;
      // console.log(param)
      this.data_sort = state_sort;
      this.column_index = check_column;
      this.loop = 0;
      if (this.loop == 0) {
        // console.log(count_total)
        this.count_total = count_total;
      }
      this.createNavigation();
      this.showItems();
    },
  };
  $.fn.pagify = function (
    perPage,
    itemSelector,
    table,
    search_text,
    param = {},
    currentPage = 0
  ) {
    var el = $(this);
    var items = itemSelector;
    var param = param;
    delete param["search_text"];
    delete param["active"];
    var col = el.data("column");
    if (col) {
      col = jQuery.parseJSON($.base64.decode(col));
    } else {
      col = [];
    }
    var thead_tr = el.find("thead tr th");
    for (var i = 0; i < thead_tr.length; i++) {
      var name = thead_tr.eq(i).data("sort");
      if (name == undefined || !name) {
        thead_tr.eq(i).addClass("no-sort");
      }
    }

    var param_input = $('.homepage-form[data-homepagify="' + table + '"]');
    // var fitter_param = param_input.find('.filter-param');
    var param_send = {};
    let fitter_param = el.find("th[data-filter]");
    fitter_param.each(function () {
      var $this = $(this).data("filter");
      var filter = $this.split(",");
      var name = jQuery.parseJSON($.base64.decode(filter[0]));
      if (filter[1] == "select") {
        param_send[name] = ["all", "All"];
      } else {
        if (jQuery.isArray(name)) {
          name.forEach(function (e, key) {
            param_send[e] = "";
          });
        } else {
          param_send[name] = "";
        }
      }
    });
    if (Object.keys(param).length == 0) {
      param = param_send;
    }
    // console.log(param);

    if (isNaN(perPage) || perPage === undefined) {
      perPage = 5;
    }
    pagify.init(el, items, perPage, search_text, param, currentPage);
  };

  // $(document).on('click', ".pagify-export", function (e) {
  //   var params = $(this).data('api');
  //   if (params) {
  //     params = jQuery.parseJSON($.base64.decode(params));
  //   } else {
  //     params = [];
  //   }
  //   var url_link = '../../_framework/modules/homepagify/ajax/ajax_export.php';
  //   $.post(url_link, {data:params}).done(async function(response) {
  //     console.log(response);

  //   });

  // });

  $(document).on("click", ".btn-filter-clear", function (e) {
    var this_form = $(this).parents(".homepage-form");
    var id = this_form.data("homepagify");
    this_form.find("input").val("");
    this_form.find("select").val("all");
    $("#" + id)
      .find("input.input-pagify-search")
      .val("");
    $("#" + id)
      .find("select.input-pagify-search")
      .val("");
    this_form.submit();
  });

  String.prototype.replaceBetween = function (opentag, closetag, replacement) {
    var read_index = 0;
    var open_index = 0;
    var close_index = 0;
    var output = "";

    while ((open_index = this.indexOf(opentag, read_index)) != -1) {
      output += this.slice(read_index, open_index) + opentag;
      read_index = open_index + opentag.length;

      if ((close_index = this.indexOf(closetag, read_index)) != -1) {
        if (typeof replacement === "function") {
          output +=
            replacement(
              this.substring(open_index + opentag.length, close_index - 1)
            ) + closetag;
        } else {
          output += replacement + closetag;
        }
        read_index = close_index + closetag.length;
      }
    }

    output += this.slice(read_index);

    return output;
  };

  /*$(document).on('click', '.btn-pagify-search', function (e) {
    var scope = $(this).parents('.container-pagination');
    var id = scope.attr('id');
    var thead_tr = $('#'+id).find('thead tr th');
    var input_filter = {};
    var array_filter = [];
    var state_filter = $('#'+id).data("state_filter");
    if(state_filter) {
      state_filter = jQuery.parseJSON($.base64.decode(state_filter));
    }
    for( var i = 0; i < thead_tr.length; i++){
      var filter = thead_tr.eq(i).data("filter");
      if(filter != undefined ) {
        var filter =  filter.split(",");
        var hasclass_help = thead_tr.eq(i).hasClass('help')
        var title =  '';
        if(hasclass_help) {
          title = thead_tr.eq(i).find('div').eq(0).html();
        } else {
          title = thead_tr.eq(i).html();
        }
  
        var name  = jQuery.parseJSON($.base64.decode(filter[0]));
        input_filter = {
          'title' : title,
          'name' : name,
          'type' : filter[1],
        };

        if(filter[1] == 'select') {
          input_filter['option'] = jQuery.parseJSON($.base64.decode(filter[2]));
        }
        array_filter.push(input_filter);
      }
    }

    // console.log(pagify.send_param)
    var  data_filter = {};
    // console.log(array_filter)
    data_filter['filter_list'] = Object.assign({}, array_filter);
    data_filter['this_table'] = id;
    var url_link = '../../_framework/modules/homepagify/ajax/ajax_search_modal.php';
    $.post(url_link, data_filter).done(async function(response) {
      $('#pagify_search_modal').find('.create-modal-search').html(response);
      var value_input =  pagify.send_param
      $.each(value_input, function(key, value) {
        if(jQuery.isArray(value)) {
          if(value[0] != '') {
           $('#pagify_search_modal').find('select[name="' + key + '"] option[value="'+value[0]+'"]').attr('selected','selected');
          }
        }else if(value != '') {
          $('#pagify_search_modal').find('input[name="' + key + '"]').val(value);
        }
      });
  
      $('#pagify_search_modal').modal('show')// $('body').append(response);

    });
  });*/

  $(document).on("hidden.bs.modal", "#pagify_search_modal", function (e) {
    $("#pagify_search_modal").find(".create-modal-search").html("");
  });

  $(document).on("click", ".cancel-text", function (e) {
    var form = $(this).parents(".container-pagination");
    var id = form.attr("id");
    var filter = $(this).parent(".tag-search-text").data("pagify");
    var param = pagify.send_param;
    $(this).parent(".tag-search-text").remove();
    if (jQuery.isArray(param[filter])) {
      $("#" + id)
        .find('select[name="' + filter + '"] option[value="all"]')
        .attr("selected", "selected");
      param[filter] = ["all", "All"];
    } else {
      param[filter] = "";
      $("#" + id)
        .find('input[name="' + filter + '"]')
        .val("");
    }
    var count_text = $("#" + id).find(".tag-search-text").length;
    $("#" + id)
      .find(".count-filter")
      .html(count_text + " Filter");
    if (count_text == 0) {
      $("#" + id)
        .find(".filter-card")
        .removeClass("d-flex")
        .addClass("d-none");
    }
    $("#" + id).pagify(pagify.perPage, 0, id, "", param, 0);
  });

  $(document).on("click", ".clear-all", function (e) {
    var form = $(this).parents(".container-pagination");
    var id = form.attr("id");
    $("#" + id)
      .find(".filter-card")
      .removeClass("d-flex")
      .addClass("d-none");
    var param_send = {};
    let fitter_param = $("#" + id).find("th[data-filter]");
    fitter_param.each(function () {
      var $this = $(this).data("filter");
      var filter = $this.split(",");
      var name = jQuery.parseJSON($.base64.decode(filter[0]));
      if (filter[1] == "select") {
        param_send[name] = ["all", "All"];
      } else {
        if (jQuery.isArray(name)) {
          name.forEach(function (e, key) {
            param_send[e] = "";
          });
        } else {
          param_send[name] = "";
        }
      }
    });
    $("#" + id)
      .find("input.input-pagify-search")
      .val("");
    $("#" + id)
      .find("select.input-pagify-search")
      .val("");
    $("#" + id)
      .find(".tag-search-text")
      .remove();
    $("#" + id)
      .find(".filter-card")
      .removeClass("d-flex")
      .addClass("d-none");
    $("#" + id).pagify(pagify.perPage, 0, id, "", param_send, 0);
  });

  $(document).on("change", ".input-pagify-search", function (e) {
    var form = $(this).parents(".container-pagination");
    var id = form.attr("id");
    var name = $(this).attr("name");
    var value_input = pagify.send_param;
    var value;
    if ($(this).is("select")) {
      value = [
        $(this).find("option:selected").val(),
        $(this).find("option:selected").text(),
      ];
    } else {
      value = $(this).val();
    }
    value_input[name] = value;
    var text_search = "";
    var text = "";
    var count = 0;
    $.each(value_input, function (key, value) {
      if (jQuery.isArray(value)) {
        if (value[0] != "all") {
          text +=
            '<div class="tag-search-text" data-pagify="' +
            key +
            '">' +
            value[1] +
            '<div class="cancel-text">  </div> </div>';
          count++;
        }
      } else if (value != "") {
        text +=
          '<div class="tag-search-text" data-pagify="' +
          key +
          '">' +
          value +
          '<div class="cancel-text">  </div> </div>';
        count++;
      }
    });
    $("#" + id)
      .find(".count-filter, .tag-search-text, .clear-all")
      .remove();
    if (count > 0) {
      if (text != "") {
        $("#" + id)
          .find(".filter-card")
          .removeClass("d-none")
          .addClass("d-flex");
        text_search +=
          '<div class="count-filter mr-5px">' +
          count +
          " Filter</div>" +
          text +
          '<div class="clear-all">Clear all</div>';
        $("#" + id)
          .find(".filter-card .found-text")
          .after(text_search);
      }
    } else {
      $("#" + id)
        .find(".filter-card")
        .removeClass("d-flex")
        .addClass("d-none");
    }

    $("#" + id).pagify(pagify.perPage, 0, id, "", value_input, 0);
  });

  $(document).on("submit", ".homepage-form", function (e) {
    e.preventDefault();
    var id = $(this).data("homepagify");
    var param = {};

    $(this)
      .find(".filter-param")
      .each(function () {
        var value = $(this).val();
        var name = $(this).attr("name");
        if ($(this).is("select")) {
          value = [
            $(this).find("option:selected").val(),
            $(this).find("option:selected").text(),
          ];
        } else {
          value = $(this).val();
        }
        param[name] = value;
      });
    $("#pagify_search_modal").modal("hide");
    $("#" + id).pagify(pagify.perPage, 0, id, "", param, 0);
    var text_search = "";
    var text = "";
    var count = 0;

    $.each(pagify.send_param, function (key, value) {
      if (jQuery.isArray(value)) {
        if (value[0] != "all" && value[0] != undefined) {
          $("#" + id)
            .find('select[name="' + key + '"] option[value="' + value[0] + '"]')
            .attr("selected", "selected");
          text +=
            '<div class="tag-search-text" data-pagify="' +
            key +
            '">' +
            value[1] +
            '<div class="cancel-text">  </div> </div>';
          count++;
        }
      } else if (value != "") {
        $("#" + id)
          .find('input[name="' + key + '"]')
          .val(value);
        text +=
          '<div class="tag-search-text" data-pagify="' +
          key +
          '">' +
          value +
          '<div class="cancel-text">  </div> </div>';
        count++;
      }
    });

    $("#" + id)
      .find(".count-filter, .tag-search-text, .clear-all")
      .remove();
    if (count > 0) {
      if (text != "") {
        $("#" + id)
          .find(".filter-card")
          .removeClass("d-none")
          .addClass("d-flex");
        text_search +=
          '<div class="count-filter mr-5px">' +
          count +
          " Filter</div>" +
          text +
          '<div class="clear-all">Clear all</div>';
        $("#" + id)
          .find(".filter-card .found-text")
          .after(text_search);
      }
    } else {
      $("#" + id)
        .find(".filter-card")
        .removeClass("d-flex")
        .addClass("d-none");
    }
  });
})(jQuery);

function valueChecklist(scope) {
  var id = scope;
  var check_list = $('input[data-checkbox_table="' + id + '"]').val();
  return check_list;
}

var class_pagination = $(".container-pagination");
class_pagination.each(function () {
  var element = $(this);
  var id = element.attr("id");
  var url = element.data("url");
  var page_no = element.data("page_no");
  var page_size = element.data("page_size");
  var fix_page = element.data("fix_page") ? element.data("fix_page") : false;
  if (fix_page && fix_page != page_size) {
    page_no = 1;
  }
  var state_filter = element.data("state_filter");
  if (state_filter) {
    state_filter = jQuery.parseJSON($.base64.decode(state_filter));
  }
  page_no = page_no - 1;
  if (page_no < 0) {
    page_no = 0;
  }
  var total_count = 0;
  var search_text = "";
  var has_search = $("#" + id)
    .find("table")
    .hasClass("table-search");
  var has_column = $("#" + id)
    .find("table")
    .hasClass("table-colvis");
  var filter_key = {};

  if (url) {
    $(this)
      .find(".table-responsive")
      .after(
        `<div class="footer-pagination"><div class="select-size"></div></div>`
      );
    $(this)
      .find(".table-responsive")
      .before(
        `<div class="header-pagination d-flex"><div class="column-list d-none"> </div> <input type="hidden" class="get-checked-list"  data-checkbox_table="${id}"> <div class="select-size"></div><div class="pagination"></div><div class="custom-button d-flex"></div></div>`
      );
    var has_no_sort = $("#" + id)
      .find("table")
      .hasClass("table-no-sort");
    var has_sort = $("#" + id)
      .find("table")
      .hasClass("table-sort");
    var custom_filter = "";
    var thead_tr = element.find("thead tr th");
    var count_filter = 0;
    var column_list = "";
    for (var i = 0; i < thead_tr.length; i++) {
      var filter = thead_tr.eq(i).data("filter");
      // console.log(filter);
      var title = thead_tr.eq(i).text();
      thead_tr.eq(i).addClass("table-column-" + i);

      if (title.trim() !== "") {
        column_list +=
          '<input type="checkbox" checked name="checkColumn" class="table-checkColumn" data-value="table-column-' +
          i +
          '" value="table-column-' +
          i +
          '"><label class="ml-5px font-14px">' +
          title +
          "</label><br>";
      }

      if (filter != undefined) {
        count_filter++;
        var filter = filter.split(",");
        var name = jQuery.parseJSON($.base64.decode(filter[0]));
        if (
          filter[1] == "text" ||
          filter[1] == "number" ||
          filter[1] == "date" ||
          filter[1] == "time"
        ) {
          filter_key[name] = "";
          if (!jQuery.isArray(name)) {
            custom_filter += `<th class="th-search table-column-${i}"><input type="${filter[1]}" class="form-control filter-param input-pagify-search" autocomplete="off" name="${name}"  placeholder="Search..." value=""></th>`;
          }
        } else if (filter[1] == "number_between") {
          filter_key[name] = "";
          custom_filter += `<th class="th-search table-column-${i}"></th>`;
          //   if(jQuery.isArray(name)) {
          //   custom_filter += `<div class="col-md-4 col-lg-2">
          //                       <div class="form-group">
          //                         <label>${title}</label>
          //                         <div class="form-row d-flex align-items-center">`;
          //                           name.forEach(function(e,key) {
          //                             custom_filter += `<div class="col"><input type="number" class="form-control filter-param" name="${e}"></div>`;
          //                             if(key < name.length - 1) {
          //                               custom_filter +=  '-';
          //                             }
          //                           });
          // custom_filter += `  </div>
          //                   </div>
          //                   </div>`;
          //   }
        } else if (filter[1] == "select") {
          filter_key[name] = "select";
          var option = jQuery.parseJSON($.base64.decode(filter[2]));
          custom_filter += `<th class="th-search table-column-${i} "><select class="form-control filter-param input-pagify-search" name="${name}">
                              <option value="">All</option>`;
          option.forEach(function (e, key) {
            custom_filter += `<option value="${e.value}">${e.text ? e.text : e.value
              }</option>`;
          });
          custom_filter += `</select></th>`;
        }
      } else {
        custom_filter += `<th class="th-search table-column-${i}"></th>`;
      }
    }

    if (has_column) {
      element.find(".column-list").html(column_list);
    }

    //วาดfilter
    if (count_filter > 0) {
      $(this).find(".header-pagination").after(`<div class="filter-card">
      <div class="found-text">
      </div>
    </div>
    `);
      var modal_search = `<div class="modal fade" id="pagify_search_modal" data-backdrop="static">
     <div class="modal-dialog" role="document">
       <div class="modal-content">
         <div class="modal-header">
           <h5 class="modal-title">FILTER DATA</h5>
           <button type="button" class="close" data-dismiss="modal" aria-label="Close">
             <span aria-hidden="true">×</span>
           </button>
         </div>
         <div class="create-modal-search">
         
         </div>
       </div>
     </div>
   </div>`;
      // $(this).find(".filter-custom").append(button)
      // element.find(thead).append(custom_filter);
      element.find("thead").append("<tr>" + custom_filter + "</tr>");
      $("body").append(modal_search);
    }
    //input value
    var text_search = "";
    var text = "";
    var count = 0;
    $.each(state_filter, function (key, value) {
      if (jQuery.isArray(value)) {
        if (value[0] != "all") {
          $("#" + id)
            .find('select[name="' + key + '"] option[value="' + value[0] + '"]')
            .attr("selected", "selected");
          text +=
            '<div class="tag-search-text" data-pagify="' +
            key +
            '">' +
            value[1] +
            '<div class="cancel-text">  </div> </div>';
          count++;
        }
      } else if (value != "") {
        $("#" + id)
          .find('input[name="' + key + '"]')
          .val(value);
        text +=
          '<div class="tag-search-text" data-pagify="' +
          key +
          '">' +
          value +
          '<div class="cancel-text">  </div> </div>';
        count++;
      }
    });

    var send_param = {};
    $.each(filter_key, function (key, value) {
      if (state_filter[key] != undefined) {
        send_param[key] = state_filter[key];
      } else {
        if (value == "select") {
          send_param[key] = ["all", "All"];
        } else {
          send_param[key] = "";
        }
      }
    });
    send_param["search_text"] = "";

    $("#" + id)
      .find(".filter-card")
      .removeClass("d-flex")
      .addClass("d-none");
    if (count > 0) {
      $("#" + id)
        .find(".filter-card")
        .removeClass("d-none")
        .addClass("d-flex");
      if (text != "") {
        text_search +=
          '<div class="count-filter mr-5px">' +
          count +
          " Filter</div>" +
          text +
          '<div class="clear-all">Clear all</div>';
      }
      $("#" + id)
        .find(".filter-card .found-text")
        .after(text_search);
    }

    if (!has_no_sort && !has_sort) {
      element.find("table").addClass("table-sort");
    }
    $("#" + id).pagify(page_size, 0, id, search_text, send_param, page_no);
  } else {
    $(this)
      .find(".table-responsive")
      .before(
        `<input type="hidden" class="get-checked-list"  data-checkbox_table="${id}">`
      );
    $("#" + id).pagify(0, 0, id, "", {}, 0);
    element.find("table").addClass("table-no-sort");
  }
});

$(document).on("click", ".btn-toggle", function (e) {
  let this_tr = $(this).parents(".title-group");
  let group = this_tr.data("group");
  let scope = this_tr.parents(".container-pagination");
  $(this).toggleClass("transform-img");
  scope.find("." + group).toggleClass("d-none");
});

$(document).on("click", ".btn-toggle-tr", function (e) {
  let this_tr = $(this);
  let group = this_tr.data("group");
  let scope = this_tr.parents(".container-pagination");
  $(this).toggleClass("transform-img");
  scope.find("." + group).toggleClass("d-none");
});

$(document).on("click", ".btn-column", function (e) {
  let scope = $(this).parents(".container-pagination");
  scope.find(".column-list").toggleClass("d-none");
});

$(document).on("click", ".filter-card .btn-hide-filter", function (e) {
  $(this).toggleClass("active");
  var scope = $(this).parents(".filter-card");
  var has_class = $(this).hasClass("active");

  if (has_class) {
    scope.find(".homepage-form").addClass("d-none");
  } else {
    scope.find(".homepage-form").removeClass("d-none");
  }
});

$(document).on("click", ".container-pagination .tr-link", function (e) {
  var link = $(this).data("link");
  if (link !== undefined && link !== "") {
    if (!$(".disabled-link, .disabled-link *").is(e.target)) {
      window.location = $(this).data("link");
    }
  }
});
