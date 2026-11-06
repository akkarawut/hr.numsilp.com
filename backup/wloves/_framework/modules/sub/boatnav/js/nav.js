$(".boat-nav-milkclub .list-frist").click(function (e) {
  var scope = $(this).parents(".list");
  if (scope.hasClass("active")) {
    scope.removeClass("active");
  } else {
    // $(this).parents('.it-nav-milkclub').find('.list').removeClass('active');
    scope.addClass("active");
  }
});
$('.nav-search input[type="search"]').on("keyup", function () {
  // let scope = $(this).parents(".it-nav-milkclub");
  let scope = $(this).parents(".boat-nav-milkclub");
  let value = $(this).val().toLowerCase();

  scope.find(".list").filter(function () {
    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
    if ($(this).text().toLowerCase().indexOf(value) > 0) {
      $(this).removeClass("active");
      $(this).addClass("active");
    } else {
      $(this).removeClass("active");
    }
  });
});

function searchNav(e) {
  var value = e.target.value.toLowerCase();
  var scope = e.target.closest(".boat-nav-search");
  var list = scope.querySelectorAll(".nav_search_list");
  var count = 0;
  var html_count = scope.querySelector(".count_nav");
  list.forEach(function (item) {
    if (item.innerText.toLowerCase().indexOf(value) > -1) {
      item.style.display = "flex";
      count++;
    } else {
      item.style.display = "none";
    }
  });
  html_count.innerHTML = "(" + count + ")";
}

function disabledLink(e) {
  var tag_a = e.target.closest(".nav_search_list");
  tag_a.preventDefault();
}

var boat_nav_dinner = document.querySelectorAll(".boat-nav-dinner");
boat_nav_dinner.forEach(function (item) {
  // item event onload
  var scrollLeft = item.scrollLeft;
  var scrollWidth = item.scrollWidth;
  var clientWidth = item.clientWidth;
  var scrollRight = scrollWidth - scrollLeft - clientWidth;
  if (scrollLeft > 0) {
    item.querySelector(".arrow-scroll-left").classList.add("active");
  }
  if (scrollRight > 0) {
    item.querySelector(".arrow-scroll-right").classList.add("active");
  }

  // item event scroll
  item.addEventListener("scroll", function (e) {
    //scroll left and right
    var scrollLeft = e.target.scrollLeft;
    var scrollWidth = e.target.scrollWidth;
    var clientWidth = e.target.clientWidth;
    var scrollRight = scrollWidth - scrollLeft - clientWidth;
    if (scrollLeft > 0) {
      item.querySelector(".arrow-scroll-left").classList.add("active");
    } else {
      item.querySelector(".arrow-scroll-left").classList.remove("active");
    }
    if (scrollRight > 0) {
      item.querySelector(".arrow-scroll-right").classList.add("active");
    } else {
      item.querySelector(".arrow-scroll-right").classList.remove("active");
    }
  });
  // item resize window
  window.addEventListener("resize", function (e) {
    var scrollLeft = item.scrollLeft;
    var scrollWidth = item.scrollWidth;
    var clientWidth = item.clientWidth;
    var scrollRight = scrollWidth - scrollLeft - clientWidth;
    if (scrollLeft > 0) {
      item.querySelector(".arrow-scroll-left").classList.add("active");
    } else {
      item.querySelector(".arrow-scroll-left").classList.remove("active");
    }
    if (scrollRight > 0) {
      item.querySelector(".arrow-scroll-right").classList.add("active");
    } else {
      item.querySelector(".arrow-scroll-right").classList.remove("active");
    }
  });
});
