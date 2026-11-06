(function ($) {
  var pagify = {
    items: 0,
    container: null,
    totalPages: 1,
    perPage: 5,
    currentPage: 0,
    size_option: [10, 15, 20, 0],
    data_sort: {},
    search_text: '',
    send_param: {},
    checked_item: {},
    column_index: {},
    createNavigation: function () {
      if (this.perPage == 0) {
        this.totalPages = Math.ceil(this.items / this.items);
      } else {
        this.totalPages = Math.ceil(this.items / this.perPage);
      }
      let scope = this.container;
      let page_size = this.perPage;
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
      let id = scope.attr('id');
      let checked_list = (checked_item[id]) ? checked_item[id] : {};
      let checked_column = (column_index[id]) ? column_index[id] : {};
      let search_text = this.search_text;
      let send_param = this.send_param;
      scope.find('.pagination').html('');
      scope.find('.select-size').html('');
      scope.find('.custom-button').html('');
      let select_size = '';
      let size
      let selected
      let header_name = scope.data("header_name");
      for (option = 0; option < this.size_option.length; option++) {
        size = (this.size_option[option] == 0) ? 'All' : this.size_option[option];
        selected = (this.size_option[option] == page_size) ? 'selected' : '';
        select_size += '<option value="' + this.size_option[option] + '" ' + selected + '>' + size + ' /page </option>';
      }
      scope.find('.footer-pagination .select-size').append('Show <select class="page-size ml-5px">' + select_size + '</select>');
      scope.find('.header-pagination .select-size').append(header_name + ' ' + items + ' list<select class="page-size ml-5px">' + select_size + '</select>');

      let has_excel = scope.find('table').hasClass('table-excel');
      let has_column = scope.find('table').hasClass('table-colvis');

      let table_btn = '';
      if (has_excel) {
        table_btn += '<a class="btn btn-export pagify-export px-15px"> EXPORT </a>';
      }
      if (has_column) {
        table_btn += '<button class="btn ml-5px btn-export btn-column"></button>';
      }
      scope.find('.header-pagination .custom-button').append(table_btn);
      //วาดท้ายตาราง
      let pagination = scope.find('.pagination').append('<div class="my-pagination"></div>');
      pagination.append('<a class="nav prev page-item fas fa-angle-left" data-next="' + (next - 1) + '"></a>')
      //วาดตัวเลือกจำนวนข้อมูลที่จะแสดง
      let select_page = '';
      for (i = 0; i < this.totalPages; i++) {
        select_page += '<option value="' + (i + 1) + '" class="page" data-page="' + (i + 1) + '">' + (i + 1) + '</option>';
      }
      pagination.append('<select class="select-page">' + select_page + '</select>');
      //วาดปุ่มย้อนหลัง 
      pagination.append('<a class="nav next page-item fas fa-angle-right" data-next="' + (next + 1) + '"> </a>');
      scope.find('.pagination').append(pagination);

      let that = this;
      //ส่งจำนวนข้อมูลที่ต้องการแสดง
      this.pageNavigator = scope.find(".page-size").on("change", function () {
        var value = $(this).find('option:selected').val();
        scope.pagify(value, items, scope, search_text, send_param);
      });
      //checkebox
      let checked = scope.find('tbody')
      checked.find(".table-checked input").off("click");

      this.pageNavigator = checked.find(".table-checked input").on("click", function () {
        let this_check = $(this)
        let check_id = this_check.attr("id");
        let check_val = this_check.val();
        if ($(this).is(":checked")) {
          checked_list[check_id] = check_val;
        } else {
          delete checked_list[check_id];
        }
        checked_item[id] = checked_list;
        // scope.find('input[name="checkboxAll"]').prop('checked', true);
        that.countItem(scope);
        that.checkBoxTable(checked_item, scope, page, items);
      });

      scope.find(".table-checked-all input").off("click");
      this.pageNavigator = scope.find(".table-checked-all input").on("click", function () {
        if ($(this).is(":checked")) {
          checked.find(".table-checked input").prop("checked", true)
          checked.find(".table-checked input").each(function (index) {
            let check_id = $(this).attr("id");
            let check_val = $(this).val();
            checked_list[check_id] = check_val;
          });
        } else {
          checked.find(".table-checked input").prop("checked", false)
          checked.find(".table-checked input").each(function (index) {
            let check_id = $(this).attr("id");
            delete checked_list[check_id];
          });
        }
        checked_item[id] = checked_list;
        that.checkBoxTable(checked_item, scope, page, items);
      });

      //ส่งเลขหน้าที่ต้องการแสดง
      this.pageNavigator = scope.find(".select-page").on("change", function () {
        let value = $(this).find('option:selected').val();
        that.goToPage(value, scope, items, page_size, send_param, column_index);
      });

      //ส่งหน้าถัดไปหรือย้อนหลัง
      scope.find(".nav").off("click");
      this.pageNavigator = scope.find(".nav").on("click", function () {
        let el = $(this);
        that.goToPage(el.data("next"), scope, items, page_size, send_param, column_index);
      });

      //กดเลือก Sort
      scope.find(".table-sort thead tr th").off("click");
      let sort = {};
      this.pageNavigator = scope.find(".table-sort thead tr th").on('click', function () {
        let has_class = $(this).hasClass('no-sort');
        if (!has_class) {
          let has_asc = $(this).hasClass('ASC');
          let has_desc = $(this).hasClass('DESC');
          let order_by = '';
          let name = $(this).data('sort');
          scope.find(".table-sort  thead tr th").removeClass('DESC');
          scope.find(".table-sort  thead tr th").removeClass('ASC');
          if (name !== undefined) {
            if (!has_asc && !has_desc) {
              $(this).addClass('ASC');
              order_by = 'ASC';
            } else {
              if (has_asc) {
                $(this).removeClass('ASC');
                $(this).addClass('DESC');
                order_by = 'DESC';
              } else if (has_desc) {
                $(this).removeClass('DESC');
                $(this).addClass('ASC');
                order_by = 'ASC';
              }
            }
            if (!has_class) {
              sort[name] = order_by;
              that.sortTable(sort, scope, page_size, page, items, send_param, column_index);
            }
          }
        }
      });

      //ซ่อน column 
      scope.find(".table-checkColumn").off("click");
      this.pageNavigator = scope.find(".table-checkColumn").on('click', function () {
        let column_check = $(this).val();
        if ($(this).is(":checked")) {
          delete checked_column[column_check];
          // scope.find('.'+column_check).removeClass('d-none')
        } else {
          checked_column[column_check] = column_check;
          // scope.find('.'+column_check).addClass('d-none')
        }

        column_index[id] = checked_column;
        that.checkColumIndex(column_index, scope, page_size, page, items, data_sort, send_param);
      });
    },

    sortTable: function (sort, scope, page_size, page, items, send_param, column_index) {
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
      let count_checked = table.find('.table-checked input:checked').length;
      let count = table.find('.table-checked input').length;
      if (count_checked == count) {
        table.find('input[name="checkboxAll"]').prop('checked', true);
      } else {
        table.find('input[name="checkboxAll"]').prop('checked', false);
      }
      let button = $('button[data-is_check_table="' + (table.attr('id')) + '"]');
      button.attr("disabled", true);
      // $('.is-check-table').attr("disabled", true);
      if (count_checked > 0) {
        // $('.is-check-table').attr("disabled", false);
        button.attr("disabled", false);
      }
    },

    checkColumIndex: function (colum, scope, page_size, page, items, data_sort, send_param) {
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
      let colum_index = this.column_index[scope_id]
      let checked_array = [];

      $.each(checked_box[scope_id], function (key, value) {
        checked_array.push(value);
      });
      let convertValue = JSON.stringify(checked_array);
      let tbody = scope.find('tbody');
      let link = tbody.data('export');
      let thead_tr = scope.find('thead tr th');
      let tbody_tr = tbody.find('tr').not(".title-group");
      for (let i = 0; i < tbody_tr.length; i++) {
        for (let j = 0; j < thead_tr.length; j++) {
          tbody_tr.eq(i).find('td').eq(j).addClass('table-column-' + j)
          tbody_tr.eq(i).find('td').eq(j).removeClass('d-none')
        }
      }


      thead_tr.removeClass('d-none')
      for (const key in colum_index) {
        scope.find('.' + key).addClass('d-none');
      }
      /* เพื่อใช้ปิดเปิดการทำงาน checked
      if(scope.find('table').hasClass('input-checked')) {
        var convertValue = JSON.stringify(checked_box[scope_id]);
        scope.find('input[data-checkbox_table="' + (scope_id) + '"]').val(convertValue);
      }
      */
      scope.find('input[data-checkbox_table="' + (scope_id) + '"]').val(convertValue).trigger('change');
      scope.find(".pagify-export").attr('href', link);

      for (const key in checked_box[scope_id]) {
        scope.find('#' + key).prop('checked', true);
      }

      /* เพื่อใช้ปิดเปิดการทำงาน group
      let has_group = scope.find('table').hasClass('table-group');
      if(!has_group) {
        scope.find('.title-group').addClass('d-none')
      } */

      if (this.currentPage == 0) {
        this.container.find(".pagination .nav.prev").addClass("disabled");
      } else {
        this.container.find(".pagination .nav.prev, .pagination .first").removeClass("disabled");
      }

      if (this.currentPage == this.totalPages - 1) {
        this.container.find(".pagination .nav.next").addClass("disabled");
      } else {
        this.container.find(".pagination .nav.next").removeClass("disabled");
      }
      this.countItem(this.container);
      var pages = this.container.find(".pagination .page");
      pages.removeClass("current");
      this.container.find('.pagination .page[data-page="' + (this.currentPage + 1) + '"]').attr('selected', true)

    },

    goToPage: function (page, scope, items, page_size, send_param, column_index) {
      this.container = scope;
      this.items = items;
      this.currentPage = page - 1;
      this.perPage = page_size;
      this.send_param = send_param;
      this.column_index = column_index;

      this.showItems();
    },

    drawnData: function (scope, count, perPage, currentPage, data_sort, send_param, column_index) {
      this.items = count;
      this.container = scope;
      this.perPage = perPage;
      this.currentPage = currentPage;
      this.data_sort = data_sort;
      this.send_param = send_param;
      this.column_index = column_index;
      this.updateNavigation();

      // scope.pagify(perPage, count, scope, '', send_param,currentPage);
      // $("#"+id).pagify(page_size, 10, id, search_text,state_filter,page_no);
      // scope.pagify(perPage, 0, id, '',param, 0);
    },

    savingState: function (scope, data) {
      var url_link = '../../_framework/modules/homepagify/ajax/ajax_saving_state.php';
      var pathname = window.location.pathname;
      var data = data;
      var code = scope.data('code');
      // console.log(data);
      data['pathname'] = pathname;
      data['code'] = code;
      data['id'] = scope.attr('id');
      $.post(url_link, data).done(async function (response) {});
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
      let $this = this;
      this.perPage = scope.find('.page-size option').filter(':selected').val()
      let test = scope.find('.select-page option')
      var base = this.perPage * this.currentPage;

      // if(this.items <= this.perPage) {
      //   this.currentPage = 0;
      // }



      var data = {
        'page_no': this.currentPage + 1,
        'page_size': this.perPage,
        'search_text': search_text,
        'data_sort': data_sort,
        'active': active
      };

      for (const key in this.send_param) {
        data[key] = this.send_param[key]
      }

      if (url_link) {
        scope.find('tbody').remove();
        $.post(url_link, data).done(async function (response) {
          var thead_td = scope.find('thead tr th').length;
          var default_table = `<tbody><tr><td class="text-center" colspan="${thead_td}"> No data </td></tr></tbody>`

          if (response == '') {
            scope.find('thead').after(default_table);
          } else {
            scope.find('thead').after(response);
          }

          var tbody_td = scope.find('tbody tr').length;
          if (tbody_td <= 0) {
            scope.find('thead').after(default_table);
          }

          var count = scope.find("table tbody").attr("data-total_count")

          $this.drawnData(scope, count, perPage, currentPage, data_sort, send_param, column_index);
        });
      }
      var save_data = {}

      save_data = data
      let myObject = Object.assign({}, column_index[scope.attr('id')]);
      save_data['colum_index'] = myObject;

      this.savingState(scope, save_data);
    },

    init: function (container, items, perPage, search_text, param, currentPage) {
      this.container = container;
      var state_sort = container.data("state_sort");
      var has_column = container.find('table').hasClass('table-colvis');
      var check_column = {};
      if (has_column) {
        var state_column = container.data("state_column");
        if (state_column) {
          state_column = jQuery.parseJSON($.base64.decode(state_column));
        }
        check_column[container.attr('id')] = state_column
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

      this.currentPage = currentPage,
        this.totalPages = 0;
      this.perPage = perPage;
      this.items = items;
      this.search_text = search_text;
      this.send_param = param;
      this.data_sort = state_sort;
      this.column_index = check_column;
      this.createNavigation();
      this.showItems();

    }
  };

  $.fn.pagify = function (perPage, itemSelector, table, search_text, param = {}, currentPage = 0) {
    var el = $(this);
    var items = itemSelector;
    var param = param
    var col = el.data("column");
    if (col) {
      col = jQuery.parseJSON($.base64.decode(col));
    } else {
      col = [];
    }

    var thead_tr = el.find('thead tr th');
    for (var i = 0; i < thead_tr.length; i++) {
      var name = thead_tr.eq(i).data("sort");
      if (name == undefined || !name) {
        thead_tr.eq(i).addClass("no-sort")
      }
    }

    var param_input = $('.homepage-form[data-homepagify="' + table + '"]');
    var fitter_param = param_input.find('.filter-param');
    var param_send = {};
    fitter_param.each(function () {
      var value = $(this).val();
      var name = $(this).attr("name");
      param_send[name] = value;
    });

    if (param == '') {
      param = param_send
    }

    if (isNaN(perPage) || perPage === undefined) {
      perPage = 5;
    }
    pagify.init(el, items, perPage, search_text, param, currentPage);

  };

  $(document).on('click', ".homepage-form .btn-filter-clear", function (e) {
    var this_form = $(this).parents('.container-pagination');
    var id = this_form.attr('id');
    var form = $('#' + id).find('.homepage-form');
    form.find("input").val('');
    form.find("select").val('all');
    form.submit();
  });

  $(document).on('submit', '.homepage-form', function (e) {
    e.preventDefault();
    var form = $(this).parents('.container-pagination');
    var id = form.attr('id');
    var param = {};
    // var send_parm = {};
    // var get_array = $('#'+id).data("get");

    // get_array = jQuery.parseJSON($.base64.decode(get_array));
    // var url_link = '?';
    // for (const key in get_array) {
    //   send_parm[key] =get_array[key];
    // }

    $('#' + id).find('.filter-param').each(function () {
      var value = $(this).val();
      var name = $(this).attr("name");
      param[name] = value;
    });

    $("#" + id).pagify(pagify.perPage, 0, id, '', param, 0);
  });

})(jQuery);

function valueChecklist(scope) {
  var id = scope;
  var check_list = $('input[data-checkbox_table="' + (id) + '"]').val();
  return check_list;
};

var class_pagination = $(".container-pagination");
class_pagination.each(function () {
  var element = $(this);
  var id = element.attr("id");
  var url = element.data("url");
  var page_no = element.data("page_no");
  var page_size = element.data("page_size");
  var state_filter = element.data("state_filter");
  if (state_filter) {
    state_filter = jQuery.parseJSON($.base64.decode(state_filter));
  }
  page_no = page_no - 1;
  if (page_no < 0) {
    page_no = 0;
  }
  var total_count = 0;
  var search_text = '';
  var has_search = $('#' + id).find('table').hasClass('table-search');
  var has_column = $('#' + id).find('table').hasClass('table-colvis');

  if (url) {
    $(this).find(".table-responsive").after(`<div class="footer-pagination"><div class="select-size"></div><div class="pagination"></div></div>`);
    $(this).find(".table-responsive").before(`<div class="header-pagination d-flex justify-content-between"> <div class="column-list d-none">  </div> <input type="hidden" class="get-checked-list"  data-checkbox_table="${id}"> <div class="select-size"></div><div class="custom-button d-flex"></div></div>`);

    var has_no_sort = $("#" + id).find("table").hasClass("table-no-sort");
    var has_sort = $("#" + id).find("table").hasClass("table-sort");
    var custom_filter = '';
    var thead_tr = element.find('thead tr th');
    var count_filter = 0;
    var column_list = '';
    for (var i = 0; i < thead_tr.length; i++) {
      var filter = thead_tr.eq(i).data("filter");
      var title = thead_tr.eq(i).text();
      thead_tr.eq(i).addClass('table-column-' + i)

      if (title.trim() !== '') {
        column_list += '<input type="checkbox" checked name="checkColumn" class="table-checkColumn" data-value="table-column-' + i + '" value="table-column-' + i + '"><label class="ml-5px font-14px">' + title + '</label><br>';
      }

      if (filter != undefined) {
        count_filter++;
        var filter = filter.split(",");
        var name = jQuery.parseJSON($.base64.decode(filter[0]));
        if (filter[1] == 'text' || filter[1] == 'number' || filter[1] == 'date' || filter[1] == 'time') {
          if (!jQuery.isArray(name)) {
            custom_filter += `<div class="col-md-4 col-lg-2">
                              <div class="form-group">
                                <label>${title}</label>
                                <input type="${filter[1]}" class="form-control filter-param" name="${name}"  value="">
                              </div>
                            </div>`;
          }
        } else if (filter[1] == 'number_between') {
          if (jQuery.isArray(name)) {
            custom_filter += `<div class="col-md-4 col-lg-2">
                              <div class="form-group">
                                <label>${title}</label>
                                <div class="form-row d-flex align-items-center">`;
            name.forEach(function (e, key) {
              custom_filter += `<div class="col"><input type="number" class="form-control filter-param" name="${e}"></div>`;
              if (key < name.length - 1) {
                custom_filter += '-';
              }
            });
            custom_filter += `  </div>
                          </div>
                          </div>`;
          }

        } else if (filter[1] == 'select') {
          var option = jQuery.parseJSON($.base64.decode(filter[2]));
          custom_filter += `<div class="col-md-4 col-lg-2">
                              <div class="form-group">
                                <label>${title}</label>
                                <select class="form-control filter-param" name="${name}">
                                  <option value="all">All</option>`;
          option.forEach(function (e, key) {
            custom_filter += `<option value="${e.value}">${((e.text) ? e.text : e.value)}</option>`
          });
          custom_filter += `</select>
                                </div>
                              </div>`;


        }
      }
    }

    if (has_column) {
      element.find('.column-list').html(column_list)
    }

    //วาดfilter
    if (count_filter > 0) {
      $(this).find(".header-pagination").before(`<div class="filter-card">
    <div class="d-flex align-items-center justify-content-between px-15px py-10px">
      <div class="filter-title">
        <p class="mb-0">Filter</p>
      </div>
      <div>
        <div class="btn-hide-filter">
          <span class="mr-10px">Hide filter</span>
        </div>
      </div>
    </div>
    <form method="post" class="homepage-form" data-homepagify="${id}" data-send_get="0">
      <div class="col-12">
        <div class="form-row filter-custom">
        </div>
      </div>
    </form>
    </div>
    `);
      custom_filter += `<div class="col-auto d-flex align-self-end pb-10px">
                        <button type="submit" class="btn  btn-filter-search mr-5px">Search</button>
                        <button type="button" class="btn  btn-filter-clear">Clear all</button>
                      </div>`;
      $(this).find(".filter-custom").append(custom_filter)
    }

    $.each(state_filter, function (key, value) {
      element.find('input[name="' + key + '"]').val(value);
      element.find('select[name="' + key + '"] option[value="' + value + '"]').attr('selected', 'selected');
    });

    if (!has_no_sort && !has_sort) {
      element.find("table").addClass("table-sort");
    }

    $("#" + id).pagify(page_size, 0, id, search_text, state_filter, page_no);
  } else {
    $(this).find(".table-responsive").before(`<input type="hidden" class="get-checked-list"  data-checkbox_table="${id}">`);
    $("#" + id).pagify(0, 0, id, '', {}, 0);
    element.find('table').addClass('table-no-sort');
  }
});

$(document).on('click', '.btn-toggle', function (e) {
  let this_tr = $(this).parents('.title-group');
  let group = this_tr.data('group');
  let scope = this_tr.parents('.container-pagination');
  $(this).toggleClass('transform-img');
  scope.find('.' + group).toggleClass('d-none');
});

$(document).on('click', '.btn-column', function (e) {
  let scope = $(this).parents('.container-pagination');
  scope.find('.column-list').toggleClass('d-none');
});

$(document).on('click', ".filter-card .btn-hide-filter", function (e) {
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
  if (link !== undefined && link !== '') {
    if (!$(".disabled-link, .disabled-link *").is(e.target)) {
      window.location = $(this).data("link");
    }
  }
});