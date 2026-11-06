// set color mode
const toggleSwitch = document.querySelector('.switch-color-mode .event-switch-color-mode input');
// const currentTheme = sessionStorage.getItem('theme') ? sessionStorage.getItem('theme') : null;
// if (currentTheme) {
//   document.documentElement.setAttribute('data-theme', currentTheme);
//   if (currentTheme === 'dark') {
//     toggleSwitch.checked = true;
//     document.querySelector(".icon-mode").innerHTML = "Dark mode";
//   } else {
//     document.querySelector(".icon-mode").innerHTML = "Light mode";
//   }
// }
// console.log(currentTheme);


function switchTheme(e) {
  if (e.target.checked) {
    updateThemeColor('dark');
    document.querySelector(".icon-mode").innerHTML = "Dark mode";
  } else {
    updateThemeColor('light');
    document.querySelector(".icon-mode").innerHTML = "Light mode";
  }
}
if (toggleSwitch) {
  toggleSwitch.addEventListener('change', switchTheme, false);
}

function updateThemeColor(mode) {

  const myRequest = new XMLHttpRequest();
  //send data
  myRequest.onload = () => {
    // if and status code is OK
    if (myRequest.status === 200) {
      var result = JSON.parse(myRequest.responseText);
      // console.log(result);
      window.location.reload();
    } else {
      console.log('Something went wrong')
    }
  }

  var uri = window.location.href;
  var uri = uri.split('/');
  var index_framework = uri.indexOf('_framework');
  if (index_framework != -1) {
    link = '../../../_framework/resources/core/view/ajax/ajax_update_theme.php';
  } else {
    link = '../../_framework/resources/core/view/ajax/ajax_update_theme.php';
  }

  myRequest.open('POST', link, true);
  myRequest.setRequestHeader('Content-Type', 'application/json;charset=UTF-8');
  myRequest.send(JSON.stringify({
    mode: mode,
  }));
}

