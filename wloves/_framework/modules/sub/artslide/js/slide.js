var artslide = {
  slideStart: function (id) {
    var html = w("#" + id)
      .find(".swiper-wrapper")
      .find("div.items");
    var options = w("#" + id).attr("options-encode");

    var count = 0;
    html.each(function (index, ele) {
      ele.classList.add("swiper-slide");
      count++;
      if (count === html.length) {
        artslide.renderSlide(id, options);
      }
    });
  },
  renderSlide: function (id, options) {
    options = w.tiwform.decode(options);
    var items_page = options.items_page ? options.items_page : 1;
    var space_gap = options.space_gap ? options.space_gap : 30;
    var loop = typeof options.loop !== "undefined" ? options.loop : false;
    var start_center =
      typeof options.start_center !== "undefined"
        ? options.start_center
        : false;
    var bullet_dynamic =
      typeof options.bullet_dynamic !== "undefined"
        ? options.bullet_dynamic
        : false;
    var autoplay =
      typeof options.autoplay !== "undefined" ? options.autoplay : false;
    var breakpoints =
      typeof options.breakpoints !== "undefined" ? options.breakpoints : false;
    var config = {
      slidesPerView: items_page,
      spaceBetween: space_gap,
      navigation: {
        nextEl: ".next_" + id,
        prevEl: ".prev_" + id,
      },
      pagination: {
        el: ".pagination_" + id,
        clickable: true, //clickable pagination bullets
        dynamicBullets: bullet_dynamic, //dynamic pagination bullets
      },
      loop: loop,
      centeredSlides: start_center,
      on: {
        init: function () {
          artslide.showSlide(id);
        },
      },
    };

    if (autoplay) {
      config.autoplay = {
        delay: autoplay,
        disableOnInteraction: false,
        pauseOnMouseEnter: true,
      };
    }

    if (breakpoints) {
      config.breakpoints = breakpoints;
    }

    new Swiper("#" + id + "_target", config);
  },
  showSlide: function (id) {
    w("#" + id).removeClass("d-none");
    w("#preload_" + id).remove();
  },
};

w.extend({ artslide });
