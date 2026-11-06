$(document).ready(function() {
  const countform = $('.form-handsontable');

  for (let i = 0; i < countform.length; i++) {
    var id = $($('.form-handsontable')[i]).find('.handsontable').attr('id');
    createTable(id);
  }
 
  function  createTable(id_table) {
    var id = '#' + id_table;
    const container = document.querySelector(id);
    var check_edit = $(container).hasClass('no-edit');
    var data_th = jQuery.parseJSON($.base64.decode($(id).attr('data-data_th')));
    var data_td = jQuery.parseJSON($.base64.decode($(id).attr('data-data_td')));
    var type = jQuery.parseJSON($.base64.decode($(id).attr('data-type')));

    //options
    var columnSorting = $(id).attr('data-columnSorting');
    var rowHeaders = $(id).attr('data-rowHeaders');
    var autoInsertRow = $(id).attr('data-autoInsertRow');
    var manualColumnMove = $(id).attr('data-manualColumnMove');
    var manualRowMove = $(id).attr('data-manualRowMove');
    var dropdownMenu = $(id).attr('data-dropdownMenu');
    var filters = $(id).attr('data-filters');
    var height = $(id).attr('data-height');
    var height = $(id).attr('data-height');
    var fixedRowsBottom = $(id).attr('data-fixedRowsBottom');
    var fixedColumnsLeft = $(id).attr('data-fixedColumnsLeft');

    const hot = new Handsontable(container, {
      data: data_td,
      columns: type,
      colHeaders: data_th,
      rowHeaders: (rowHeaders == 1) ? true : false,
      columnSorting: columnSorting,
      fillHandle: {
        direction: 'vertical',
        autoInsertRow: autoInsertRow
      },
      formulas: {
        engine: HyperFormula,
      },
      manualColumnMove: manualColumnMove,
      manualRowMove: manualRowMove,
      dropdownMenu: dropdownMenu,
      filters: filters,
      height: (height == 'auto') ? 'auto' : height,
      fixedRowsBottom: false,
      fixedColumnsLeft: fixedColumnsLeft,
      stretchH: 'all',
      licenseKey: 'non-commercial-and-evaluation'
    });
  
    const button_string = document.querySelector('#export-string');
    const exportPlugin = hot.getPlugin('exportFile');

    button_string.addEventListener('click', () => {
      const exportedString = exportPlugin.exportAsString('csv', {
        bom: false,
        columnDelimiter: ',',
        columnHeaders: false,
        exportHiddenColumns: true,
        exportHiddenRows: true,
        rowDelimiter: '\r\n',
        rowHeaders: true
      });
      var array = exportedString.split("\r\n");
      console.log(array);
    });

    const button_file = document.querySelector('#export-file');
    button_file.addEventListener('click', () => {
      exportPlugin.downloadFile('csv', {
        bom: false,
        columnDelimiter: ',',
        columnHeaders: false,
        exportHiddenColumns: true,
        exportHiddenRows: true,
        fileExtension: 'csv',
        filename: 'Handsontable-CSV-file_[YYYY]-[MM]-[DD]',
        mimeType: 'text/csv',
        rowDelimiter: '\r\n',
        rowHeaders: true
      });
    });
  }
  
});