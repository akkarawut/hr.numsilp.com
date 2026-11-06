function changeConditionOT(t) {
  let v = t.value;
  let url = `ajax/ajax_change_condition_ot.php?id=${v}`;
  wx.get(url, function (res) {
    let data = JSON.parse(res);
    let scope_hours = document.querySelector('.scope_hours');
    let scope_condition_ot = document.querySelector('.scope_condition_ot');
    let scope_price = document.querySelector('.scope_price');
    let hours = parseInt(scope_hours.innerHTML);
    scope_condition_ot.innerHTML = w.formatNumber(data.price, 2) + ' บาท : ชั่วโมง';
    scope_price.innerHTML = w.formatNumber((hours * data.price), 2) + ' บาท';
  });
}