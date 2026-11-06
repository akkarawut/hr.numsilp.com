var type_calendar = '';
document.addEventListener('DOMContentLoaded', function () {

  var scope_calendar = document.querySelectorAll('.scope_calendar');
  scope_calendar.forEach((e) => {
    fullCalendar(e);
  });

  var scope_mini_calendar = document.querySelectorAll('.scope_mini_calendar');
  scope_mini_calendar.forEach((e) => {
    miniCalendar(e);
  });

});

function fullCalendar(ele) {
  var month_full = ["January", "February", "March", "April", "May", "June", "July",
    "August", "September", "October", "November", "December"];
  var month_short = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
  var thai_month_full = ["มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน", "กรกฎาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม",];
  var thai_month_short = ["ม.ค.", "ก.พ.", "มี.ค.", "เม.ย.", "พ.ค.", "มิ.ย.", "ก.ค.", "ส.ค.", "ก.ย.", "ต.ค.", "พ.ย.", "ธ.ค.",];
  var month_list = {
    'month_full': month_full,
    'month_short': month_short,
    'thai_month_full': thai_month_full,
    'thai_month_short': thai_month_short
  };

  let nextElement = ele.children[0];
  let getID = nextElement.getAttribute('id');
  let calendarEl = document.getElementById(getID);
  let value_base_64 = calendarEl.getAttribute('data-value');
  let options_base_64 = calendarEl.getAttribute('data-options');
  let value_decode = JSON.parse(atob(value_base_64))
  let options = JSON.parse(atob(options_base_64))

  let calendar = new FullCalendar.Calendar(calendarEl, {
    timeZone: 'local',
    initialView: options.initialView,
    locale: options.locale ? options.locale : 'en',
    titleFormat: {
      year: options.titleFormat.year,
      month: options.titleFormat.month,
      day: options.titleFormat.day,
    },
    // titleFormat: function (date) {
    //   if (options.titleFormat) {
    //     // let date_start_js = Date(date.start.year, date.start.month, date.start.day);
    //     // let date_end_js = Date(date.end.year, date.end.month, date.end.day);
    //     let month_type = options.month_type ? options.month_type : 'month_full';
    //     let month = month_list[month_type];
    //     console.log(date, 'date');
    //     console.log(options, 'options');
    //     console.log(options.initialView, 'options.initialView');

    //     console.log(type_calendar)

    //     if (type_calendar == 'timeGridDay') {
    //       return month[date.start.month] + ' ' + date.start.day + ' - ' + date.end.day + ' ,  ' + date.start.year;
    //     }
    //     else if (type_calendar == 'timeGridWeek') {
    //       return month[date.start.month] + ' ' + date.start.day + ' - ' + date.end.day + ' ,  ' + date.start.year;
    //     }
    //     else if (type_calendar == 'dayGridMonth' || !type_calendar) {
    //       return month[date.start.month] + ' ' + date.start.day + ' ,  ' + date.start.year;
    //     }

    //   }

    //   return date.toString() + '!!!';
    // },
    eventDidMount: function (info) {
      $(info.el).tooltip({
        title: info.event.title,
        container: 'body',
        delay: { "show": 50, "hide": 50 }
      });
    },
    initialDate: options.initialDate,
    dateClick: function (info) {
      if (options.link) {
        window.location = `${options.link}&date_calendar=${info.dateStr}`;
      }
    },

    eventClick: function (info) {
      console.log(info, 'info');
      if (options.link) {
        let currentDate = new Date(info.event.start);
        let year = currentDate.getFullYear();
        let month = currentDate.getMonth() + 1;
        let date = currentDate.getDate();
        let fullDate = `${year}-${month}-${date}`;
        window.location = `${options.link}&date_calendar=${fullDate}`;
      }
    },
    dayMaxEvents: true,
    headerToolbar: {
      left: options.headerToolbar.left,
      center: options.headerToolbar.center,
      right: options.headerToolbar.right
    },
    events: value_decode,
    eventContent: function (info) {
      if (info.event.extendedProps.count) {
        return {
          html: `<div class="d-flex justify-content-between">
                  <div>${info.event.extendedProps.count}</div>   
                  <div>${info.event.title}</div>
                </div>` };
      } else {
        return {
          html: info.event.title
        }
      }

    }
  });

  calendar.render();
  if (options.connect) {
    let inputMiniCalendar = document.querySelector(`input[name="${options.connect}"]`);
    $(`#${options.connect}`).datepicker({
      dateFormat: "yy-mm-dd",
      onSelect: function (dateText, inst) {
        var dateAsString = dateText;
        var dateAsObject = $(this).datepicker('getDate');
        inputMiniCalendar.value = dateAsString;
        calendar.gotoDate(inputMiniCalendar.value)
      },
      onChangeMonthYear: function (year, month, inst) {
        inputMiniCalendar.value = `${year}-${month < 10 ? '0' + month : month}-01`;
        calendar.gotoDate(inputMiniCalendar.value)
      }
    });

    document.querySelector(`#${getID} .fc-prev-button`).addEventListener('click', function () {
      connectMiniCalendar(calendar, options.connect);
    });

    document.querySelector(`#${getID} .fc-today-button`).addEventListener('click', function () {
      connectMiniCalendar(calendar, options.connect);
    });

    document.querySelector(`#${getID} .fc-next-button`).addEventListener('click', function () {
      connectMiniCalendar(calendar, options.connect);
    });
  }
}

function connectMiniCalendar(calendar, miniCalendar) {
  let getDate = calendar.getDate();
  let currentDate = new Date(getDate);
  let year = currentDate.getFullYear();
  let month = currentDate.getMonth() + 1;
  let date = currentDate.getDate();
  let fullDate = `${year}-${month}-${date}`;
  let inputMiniCalendar = document.querySelector(`input[name="${miniCalendar}"]`);
  inputMiniCalendar.value = fullDate;
  $(`#${miniCalendar}`).datepicker('setDate', fullDate);
}

function miniCalendar(ele) {
  let nextElement = ele.children[0];
  let getID = nextElement.getAttribute('id');
  $(`#${getID}`).datepicker({
    dateFormat: "yy-mm-dd",
  });
}

