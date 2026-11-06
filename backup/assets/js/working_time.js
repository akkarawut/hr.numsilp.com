
function eventUseDay(t) {
  let scope = t.closest('.scope_row_working');
  let input = scope.querySelectorAll('.box-disabled input');
  if (t.checked == true) {
    input.forEach(element => {
      element.removeAttribute('readonly');
      element.setAttribute('required', true);
    });
    DiffHours(scope, 'data-time', 'data-end_time', '.cal_time');
  } else {
    input.forEach(element => {
      element.setAttribute('readonly', true);
      element.removeAttribute('required');
      element.value = '';
    });
    scope.setAttribute('data-time', 0);
    scope.setAttribute('data-end_time', 0);
    DiffHours(scope, 'data-time', 'data-end_time', '.cal_time');
    scope.querySelector('.cal_time').innerHTML = `<span class="text-primary">ไม่นับเวลาทำงาน</span>`;
  }
}

function changeTime(t, type) {
  let scope = t.closest('.scope_row_working');
  let cal_time = scope.querySelector('.cal_time');
  if (type == 'start_time') {
    scope.setAttribute('data-time', t.value);
  } else if (type == 'end_time') {
    scope.setAttribute('data-end_time', t.value);
  }
  DiffHours(scope, 'data-time', 'data-end_time', '.cal_time');

}

function DiffHours(scope, data_start, data_end, ele_txt) {
  let start_time = scope.getAttribute(data_start);
  let end_time = scope.getAttribute(data_end);
  let arr_start_time = start_time.split(':');
  let arr_end_time = end_time.split(':');
  let startDate = new Date(0, 0, 0, arr_start_time[0], arr_start_time[1], 0);
  let endDate = new Date(0, 0, 0, arr_end_time[0], arr_end_time[1], 0);
  let diff = (endDate.getTime() ? endDate.getTime() : startDate.getTime()) - (startDate.getTime() ? startDate.getTime() : endDate.getTime());
  var hours = Math.floor(diff / 1000 / 60 / 60);
  diff -= hours * 1000 * 60 * 60;
  var minutes = Math.floor(diff / 1000 / 60);

  let html = ``;
  html = `
  <span class="txt_hrs">
    ${hours ? hours : 0}
  </span> ชั่วโมง
  <span class="txt_min ml-5px">${minutes ? minutes : 0}</span> นาที
  `;
  scope.querySelector(ele_txt).innerHTML = html;
  sumHours('table_working_time');
}

function sumHours(ele) {
  let scope = document.getElementById(ele);
  let hrs = scope.querySelectorAll('.txt_hrs');
  let min = scope.querySelectorAll('.txt_min');
  let sum_hrs = 0;
  let sum_min = 0;
  hrs.forEach(e => {
    let value_hrs = e.innerHTML;
    let int_hrs = parseInt(value_hrs);
    sum_hrs += int_hrs;

  });
  min.forEach(e => {
    let value_min = e.innerHTML;
    let int_min = parseInt(value_min);
    sum_min += int_min;
  });
  let check_min = sum_min >= 60 ? Math.floor(sum_min / 60) : 0;
  console.log(check_min);
  let minute = sum_min % 60;
  document.querySelector('.sum_hrs').innerHTML = sum_hrs + (check_min ? check_min : 0);
  document.querySelector('.sum_min').innerHTML = minute;
}

w(document).on('change', '.event_checkbox_condtion', function () {
  let scope = w(this).parents('.scope_condition');
  if (w(this).is(':checked')) {
    scope.find('.event_input').attr('required', true);
  } else {
    scope.find('.event_input').removeAttr('required');
  }
});