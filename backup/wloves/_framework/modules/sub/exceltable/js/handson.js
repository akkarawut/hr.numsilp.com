let hot = {};
let container = {};

$(document).ready(function () {
  const countform = $(".form-handsontable");

  for (let i = 0; i < countform.length; i++) {
    let id = $($(".form-handsontable")[i])
      .find(".custom-handsontable")
      .attr("id");
    createTable(id);
  }

  function checkedCheckbox(
    instance,
    td,
    row,
    col,
    prop,
    value,
    cellProperties,
    visualRow
  ) {
    if (row != 0) {
      if (value == false || value == 2) {
        return (td.innerHTML = `<input type="checkbox" class="checkbox event_check_handson"  data-row="${row}" data-col="${col}" value="${value}" ${
          value == 2 ? "checked" : ""
        } >`);
      }
    } else {
      td.innerHTML = "";
      td.className = "handson_search";
    }
  }
  function createTable(id_table) {
    let id = "#" + id_table;
    // handson["container_" + id_table] = document.querySelector(id);
    container = document.querySelector(id);
    let data_th =
      $(id).attr("data-data_th") &&
      JSON.parse(atob($(id).attr("data-data_th")));
    let data_td =
      $(id).attr("data-data_td") &&
      JSON.parse(atob($(id).attr("data-data_td")));
    let columns =
      $(id).attr("data-columns") &&
      JSON.parse(atob($(id).attr("data-columns")));
    let api_update =
      $(id).attr("data-api_update") &&
      JSON.parse(atob($(id).attr("data-api_update")));
    let api_select =
      $(id).attr("data-api_select") &&
      JSON.parse(atob($(id).attr("data-api_select")));
    let path = $(id).attr("data-path");
    if (data_td != undefined) {
      columns.forEach((val, idx) => {
        if (val.renderer) {
          val.renderer = window[val.renderer];
        }
      });

      let search_th = {};

      columns.forEach((val, idx) => {
        search_th[val.data] = "";
      });

      data_td.unshift(search_th);

      //options
      let custom_handson_class = $(id).attr("data-custom_handson_class");
      let fixed_columns_start = $(id).attr("data-fixed_columns_start");
      let row_headers = $(id).attr("data-row_headers");
      let is_checkbox_all = $(id).attr("data-is_checkbox_all");
      let total_count = $(id).attr("data-total_count");

      // add checkbox all
      if (is_checkbox_all) {
        custom_handson_class = "handson-filter-checkbox";
        data_th.splice(
          1,
          0,
          `<input type="checkbox" name="checkbox" class="event_check_all_handson"  />`
        );

        var data_th_checked = data_th.map((val, idx) => {
          if (idx == 1) {
            return `<input type="checkbox" name="checkbox" checked class="event_check_all_handson " checked />`;
          } else {
            return val;
          }
        });

        data_td.forEach((val, idx) => {
          data_td[idx].checkbox = false;
        });

        temp_colums = [];

        columns.forEach((val, idx) => {
          if (idx == 1) {
            temp_colums.push({
              data: "checkbox",
              renderer: checkedCheckbox,
              readOnly: true,
              width: 20,
            });
          }
          temp_colums.push(val);
        });

        columns = temp_colums;
      }

      // handson["hot_" + id_table] = new Handsontable(container, {
      hot = new Handsontable(container, {
        colHeaders: data_th,
        data: data_td,
        columns: columns,
        fixedColumnsStart: fixed_columns_start,
        rowHeaders: row_headers,
        className: custom_handson_class,
        contextMenu: ["cut", "copy"],
        hiddenColumns: {
          columns: [0],
        },
        stretchH: "all",
        comments: true,
        fixedRowsTop: 1,
        licenseKey: "non-commercial-and-evaluation",
      });

      let isSearch = false;

      hot.updateSettings({
        afterChange: function (change, source) {
          if (source === "loadData") {
            return;
          }
          change?.forEach(([row, prop, oldValue, newValue], idx) => {
            let id = hot.getDataAtCell(row, 0);
            let key = prop;
            let value = newValue;
            isSearch = true;

            // $(".scope_loading_page").removeClass("d-none");

            hot.validateCells(function (isValid) {
              if (isValid) {
                if (row == 0) {
                  temp_search.forEach((item, index) => {
                    if (item.key == key) {
                      temp_search.splice(index, 1);
                      item.value = value;
                    }
                  });
                  if (value != "" && value != null) {
                    temp_search.push({
                      key: key,
                      value: value,
                    });
                  } else {
                    delete temp_search.key;
                  }
                  loadDataHandson(currentPage, limit, key, temp_search);
                } else {
                  $.ajax({
                    url: `${path}/ajax/ajax_update_handson.php?c=${code}`,
                    type: "POST",
                    data: {
                      id: id,
                      key: key,
                      value: value,
                      api_update: api_update,
                    },
                    success: function (res) {
                      // let result = JSON.parse(res);
                      // console.log(result);
                      // if (result) {
                      //   loadDataHandson(currentPage, limit, key, temp_search);
                      // }
                    },
                  });
                }
              }
            });
          });
        },
      });

      // hot.updateSettings({
      //   afterValidate(isValid, value, row, prop, source) {
      //     let id = hot.getDataAtCell(row, 0);
      //     let key = prop;

      //     if (isValid) {
      //       $.ajax({
      //         url: `ajax/ajax_update_handson.php?c=${code}`,
      //         type: "POST",
      //         data: {
      //           id: id,
      //           key: key,
      //           value: value,
      //         },
      //         success: function (res) {
      //           let result = JSON.parse(res);
      //           if (result) {
      //             loadDataHandson(currentPage, limit, key, temp_search);
      //           }
      //         },
      //       });
      //     }
      //   },
      // });

      //Event Check all
      let handson_ids = [];
      $(document).on("change", ".event_check_all_handson", function () {
        let check_length = 0;
        if ($(this).is(":checked")) {
          data_td.forEach(function (item, index) {
            if (!item.checkbox && index != 0) {
              item.checkbox = 2;
              check_length++;
              handson_ids.push(item.id);
            }
          });
          hot.loadData(data_td);

          hot.updateSettings({
            colHeaders: data_th_checked,
          });
          $(".event_check_handson").prop("checked", true);
          $(".scope_ids_handson").val(handson_ids);

          if (check_length == 0) {
            $(".scope_btn_check_handson").attr("disabled", true);
          } else {
            $(".scope_btn_check_handson").attr("disabled", false);
          }
          $(".scope_check_list_handson").text(check_length);
        } else {
          data_td.forEach(function (item, index) {
            if (item.checkbox == 2 && index != 0) {
              item.checkbox = false;
            }
          });
          hot.loadData(data_td);
          hot.updateSettings({
            colHeaders: data_th,
          });
          $(".event_check_handson").prop("checked", false);
          handson_ids = [];
          $(".scope_ids_handson").val("");
          $(".scope_check_list_handson").text(0);
          $(".scope_btn_check_handson").attr("disabled", true);
        }
      });
      $(document).on("change", ".event_check_handson", function () {
        if ($(this).is(":checked")) {
          let row = $(this).data("row");
          let ids = hot.getDataAtCell(row, 0);

          data_td.forEach(function (item, index) {
            if (ids == item.id) {
              item.checkbox = 2;
            }
          });
          hot.loadData(data_td);
          handson_ids = handson_ids.concat(ids);
          $(".scope_ids_handson").val(handson_ids);
          $(".scope_check_list_handson").text(handson_ids.length);
        } else {
          let row = $(this).data("row");
          let ids = hot.getDataAtCell(row, 0);
          handson_ids = handson_ids.filter(function (item) {
            return item !== ids;
          });

          data_td.forEach(function (item, index) {
            if (ids == item.id && index != 0) {
              item.checkbox = false;
            }
          });
          hot.loadData(data_td);
          let check = handson_ids.length;
          $(".scope_ids_handson").val(handson_ids);
          $(".scope_check_list_handson").text(check);
        }

        if ($(".scope_ids_handson").val()) {
          $(".scope_btn_check_handson").attr("disabled", false);
        } else {
          $(".scope_btn_check_handson").attr("disabled", true);
        }

        let check_all_length = 0;
        let check = handson_ids.length;

        data_td.forEach((val, idx) => {
          if (val.checkbox != true && idx != 0) {
            check_all_length++;
          }
        });

        if (check_all_length == check) {
          $(".event_check_all_handson").prop("checked", true);
          hot.updateSettings({
            colHeaders: data_th_checked,
          });
        } else {
          $(".event_check_all_handson").prop("checked", false);
          hot.updateSettings({
            colHeaders: data_th,
          });
        }
      });

      // Pagination
      let currentPage = 1;
      let limit = 100;
      let total_page = Math.ceil(parseInt(total_count) / limit);

      $(".scope_page").text(currentPage + " of " + total_page);

      function refreshData() {
        $(".scope_loading_page").removeClass("d-none");
        let isConfirm = true;

        if (currentPage <= total_page) {
          let offset = (currentPage - 1) * limit;

          loadDataHandson(currentPage, limit, "", temp_search);

          // $(".scope_page").text(currentPage + " of " + total_page);
        }

        if (currentPage == 1) {
          $(".event_previous_handson").addClass("disabled");
          $(".event_next_handson").removeClass("disabled");
        } else {
          $(".event_next_handson").removeClass("disabled");
          $(".event_previous_handson").removeClass("disabled");
        }

        if (currentPage == total_page) {
          $(".event_next_handson").addClass("disabled");
          $(".event_previous_handson").removeClass("disabled");
          if (total_page == 1) {
            $(".event_previous_handson").addClass("disabled");
            $(".event_next_handson").addClass("disabled");
          }
        }
      }

      //load Data
      function isJsonString(str) {
        try {
          JSON.parse(str);
        } catch (e) {
          return false;
        }
        return true;
      }
      const queryString = window.location.search;
      const urlParams = new URLSearchParams(queryString);
      let code = urlParams.get("c") ?? "";
      let param_code = code ? `?c=${code}` : "";

      function loadDataHandson(page_no, page_size, key, temp_search) {
        if (temp_search.length > 0 && isSearch) {
          page_no = 1;
          currentPage = 1;
        }

        $.ajax({
          url: `${path}/ajax/ajax_load_handson.php?c=${code}`,
          type: "POST",
          data: {
            page_no: page_no,
            page_size: page_size,
            temp_search: temp_search,
            api_select: api_select,
          },
          success: function (res) {
            let search_th = {};

            columns.forEach((val, idx) => {
              search_th[val.data] = "";
            });

            if (temp_search) {
              temp_search.forEach((val, idx) => {
                search_th[val.key] = val.value;
              });
            }

            if (isJsonString(res) && res !== "null") {
              let result = JSON.parse(res);

              result.list.unshift(search_th);

              result.list.forEach((new_val, new_idx) => {
                data_td.forEach((old_val, old_idx) => {
                  if (new_val.id == old_val.id) {
                    new_val.checkbox = old_val.checkbox;
                  }
                });
              });

              data_td = result.list;
              hot.loadData(data_td);

              // Reload pagination
              $(".scope_loading_page").addClass("d-none");
              if (!isSearch) {
                $(".scope_count_product").text(
                  w.formatNumber(result.total_count, 0)
                );
              }

              let total_page = Math.ceil(parseInt(result.total_count) / limit);
              total_page == Infinity || result.list.length <= 1
                ? (total_page = 1)
                : total_page;
              // console.log(currentPage);
              $(".scope_page").text(currentPage + " of " + total_page);
              if (currentPage == 1) {
                $(".event_previous_handson").addClass("disabled");
                $(".event_next_handson").removeClass("disabled");
              } else {
                $(".event_next_handson").removeClass("disabled");
                $(".event_previous_handson").removeClass("disabled");
              }
              if (currentPage == total_page) {
                $(".event_next_handson").addClass("disabled");
                $(".event_previous_handson").removeClass("disabled");
                if (total_page == 1) {
                  $(".event_previous_handson").addClass("disabled");
                  $(".event_next_handson").addClass("disabled");
                }
              }
              let html = ``;
              html += `<select class="form-select event_change_page">`;
              for (let i = 1; i <= total_page; i++) {
                html += `<option value="${i}" ${
                  currentPage == i ? "selected" : ""
                } > ${i} </option>`;
              }
              html += `</select>`;
              $(".event_change_page").html(html);
            } else {
              $(".scope_loading_page").addClass("d-none");
              alert("ไม่สามารถดึงข้อมูลได้ กรุณาลองใหม่อีกครั้ง");
              setTimeout(() => {
                location.reload();
              }, 100);
            }
            isSearch = false;
          },
        });
      }

      // Search Data
      let temp_search = [];

      $(document).on("change", ".event_change_page", function (e) {
        if (is_checkbox_all) {
          let isChecked = false;
          let isConfirm = true;

          data_td.forEach((val, idx) => {
            if (val.checkbox == 2) {
              isChecked = true;
              return;
            }
          });

          //Reset checkbox
          if (isChecked) {
            if (
              confirm(
                "ถ้าคุณต้องการยืนยันที่จะเปลี่ยนหน้า รายการที่คุณเลือกไว้จะหายไป"
              ) == true
            ) {
              isConfirm = true;
            } else {
              isConfirm = false;
              $(".event_change_page").val(currentPage);
              $(".scope_loading_page").addClass("d-none");
            }
            $(".scope_check_list_handson").text(0);
            $(".scope_ids_handson").val("");
          }

          if (isConfirm) {
            currentPage = parseInt($(this).val());
            refreshData();
            hot.updateSettings({
              colHeaders: data_th,
            });
            // $(".scope_product_noti").prop("disabled", true);
          }
        } else {
          currentPage = parseInt($(this).val());
          refreshData();
        }
      });

      $(document).on("change", ".event_change_all_page", function (e) {
        if (is_checkbox_all) {
          let isChecked = false;
          let isConfirm = true;

          data_td.forEach((val, idx) => {
            if (val.checkbox == 2) {
              isChecked = true;
              return;
            }
          });

          //Reset checkbox
          if (isChecked) {
            if (
              confirm(
                "ถ้าคุณต้องการยืนยันที่จะเปลี่ยนหน้า รายการที่คุณเลือกไว้จะหายไป"
              ) == true
            ) {
              isConfirm = true;
              data_td.forEach((val, idx) => {
                if (val.checkbox == 2) {
                  val.checkbox = false;
                }
              });
              tracking_ids = [];
            } else {
              isConfirm = false;
              $(this).val(limit);
              $(".event_change_page").val(currentPage);
              $(".scope_loading_page").addClass("d-none");
            }
            $(".scope_check_list_handson").text(0);
            $(".scope_ids_handson").val("");
          }

          if (isConfirm) {
            let value = $(this).val();

            currentPage = 1;
            limit = value;
            total_page =
              limit == 0 ? 1 : Math.ceil(parseInt(total_count) / limit);

            let html = ``;
            html += `<select class="form-select event_change_page">`;
            for (let i = 1; i <= total_page; i++) {
              html += `<option value="${i}" > ${i} </option>`;
            }
            html += `</select>`;
            $(".event_change_page").html(html);
            refreshData();
            hot.updateSettings({
              colHeaders: data_th,
            });
            // $('.scope_product_noti').prop('disabled', true)
          }
        } else {
          let value = $(this).val();

          currentPage = 1;
          limit = value;
          total_page =
            limit == 0 ? 1 : Math.ceil(parseInt(total_count) / limit);

          let html = ``;
          html += `<select class="form-select event_change_page">`;
          for (let i = 1; i <= total_page; i++) {
            html += `<option value="${i}" > ${i} </option>`;
          }
          html += `
        </select>
        `;
          $(".event_change_page").html(html);
          refreshData();
        }
      });

      $(document).on("click", ".event_previous_handson", function (e) {
        if (is_checkbox_all) {
          let isChecked = false;
          let isConfirm = true;

          data_td.forEach((val, idx) => {
            if (val.checkbox == 2) {
              isChecked = true;
              return;
            }
          });

          //Reset checkbox
          if (isChecked) {
            if (
              confirm(
                "ถ้าคุณต้องการยืนยันที่จะเปลี่ยนหน้า รายการที่คุณเลือกไว้จะหายไป"
              ) == true
            ) {
              isConfirm = true;
            } else {
              isConfirm = false;
              $(".scope_loading_page").addClass("d-none");
            }
            $(".scope_check_list_handson").text(0);
            $(".scope_ids_handson").val("");
          }

          if (currentPage > 1 && isConfirm) {
            currentPage--;
            $(".event_change_page").val(currentPage);
            refreshData();
            hot.updateSettings({
              colHeaders: data_th,
            });
            // $(".scope_product_noti").prop("disabled", true);
          }
        } else {
          if (currentPage > 1) {
            currentPage--;
            $(".event_change_page").val(currentPage);
            refreshData();
          }
        }
      });

      $(document).on("click", ".event_next_handson", function (e) {
        if (is_checkbox_all) {
          let isChecked = false;
          let isConfirm = true;

          data_td.forEach((val, idx) => {
            if (val.checkbox == 2) {
              isChecked = true;
              return;
            }
          });

          //Reset checkbox
          if (isChecked) {
            if (
              confirm(
                "ถ้าคุณต้องการยืนยันที่จะเปลี่ยนหน้า รายการที่คุณเลือกไว้จะหายไป"
              ) == true
            ) {
              isConfirm = true;
            } else {
              isConfirm = false;
              $(".scope_loading_page").addClass("d-none");
            }
            $(".scope_check_list_handson").text(0);
            $(".scope_ids_handson").val("");
          }

          if (isConfirm) {
            currentPage++;
            $(".event_change_page").val(currentPage);
            refreshData();
            hot.updateSettings({
              colHeaders: data_th,
            });
            // $(".scope_product_noti").prop("disabled", true);
          }
        } else {
          currentPage++;
          $(".event_change_page").val(currentPage);
          refreshData();
        }
      });

      hot.updateSettings({
        cells: function (row, col, prop) {
          let cellProperties = {};

          if (row == 0) {
            cellProperties.className = "handson_search";
            cellProperties.placeholder = "ค้นหา...";
            cellProperties.readOnly = false;
          }
          return cellProperties;
        },
      });
    }
  }
});

// w.extend({ excelTable });
