var dom_table = "t<'d-flex align-items-center justify-content-center nowrap flex-wrap justify-content-md-end mt-3'<'select-l'l><'mx-3'i>p>";
$.extend(true, $.fn.dataTable.defaults, {
  "dom": dom_table,
  "autoWidth": false,
  "searching": false,
  "responsive": true,
  "processing": true,
  "serverSide": true,
  "serverSide": true,
  "ordering": false,
  "paging": true,
  'lengthMenu': [
    [10, 25, 50, -1],
    ['10 / หน้า', '25 / หน้า', '50 / หน้า', 'ทั้งหมด']
  ],
  "language": {
    "lengthMenu": "การแสดงผลต่อหน้า _MENU_",
    "infoEmpty": "0 - 0 จาก 0",
    "info": "_START_-_END_ จาก _TOTAL_",
    "paginate": {
      "first": '<<',
      "previous": "<",
      "next": ">",
      "last": ">>"
    },
    "emptyTable": "ไม่พบข้อมูล",
  },
  fnInfoCallback: function (oSettings, iStart, iEnd, iMax, iTotal, sPre) {
    var api = this.api();
    let filter_page_size = document.querySelector('.filter_page_size');
    let dataTables_length = document.querySelector('.dataTables_length');
    let select_length = dataTables_length.querySelector('select');

    if (filter_page_size) {
      api.page.len(filter_page_size.value);
    }
    select_length.addEventListener('change', function () {
      api.page.len(select_length.value);
      if (filter_page_size) {
        filter_page_size.value = api.page.len();
      }
    });
    var len = api.page.len();
    var page = api.page.info().page;
    return (((page) * len) + 1) + " - " + (api.page.info().end ? api.page.info().end : iTotal) + " จาก " + iTotal + " ";
  },
  drawCallback: function () {
    var api = this.api();
    let filter_page_size = document.querySelector('.filter_page_size');
    let dataTables_length = document.querySelector('.dataTables_length');
    let select_length = dataTables_length.querySelector('select');
    var len = api.page.len();

    if (filter_page_size) {
      api.page.len(filter_page_size.value);
    }

    select_length.addEventListener('change', function () {
      api.page.len(select_length.value);
      if (filter_page_size) {
        filter_page_size.value = api.page.len();
      }
    });

  }
});


