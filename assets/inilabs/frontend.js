$(function () {
  $(".readmore-link").click(function (e) {
    // record if our text is expanded
    var isExpanded = $(e.target).hasClass("expand");

    //close all open paragraphs
    $(".overflow-mask.expand").removeClass("expand");
    $(".readmore-link.expand").removeClass("expand");

    // if target wasn't expand, then expand it
    if (!isExpanded) {
      $(e.target).parent(".overflow-mask").addClass("expand");
      $(e.target).addClass("expand");
    }
  });

  $(".js-course-info").on("click", function () {
    // alert("llo");
    $("#courseMeta").toggleClass("open");
  });

  $(window).scroll(function () {
    if ($(window).scrollTop() >= 350) {
      $("body").addClass("fixed-hero");
    } else {
      $("body").removeClass("fixed-hero");
    }
  });
  window.addEventListener("resize", courseMeta, false);
  function courseMeta() {
    if (window.matchMedia("(max-width: 991px)").matches) {
      $(window).on("scroll", function () {
        if (
          $(window).scrollTop() >=
          $("#footer").offset().top +
            $("#footer").outerHeight() -
            window.innerHeight -
            $("#footer").outerHeight()
        ) {
          $(".btn-floating").hide();
        } else {
          $(".btn-floating").show();
        }
      });
      $(".btn-floating").addClass("mobile");
    } else {
      $(".btn-floating").removeClass("mobile");
    }
  }
  courseMeta();
});
function calcHeight() {
  var heaaderHeight = $(".site-header").outerHeight();
  var heroHeight;
  if (window.matchMedia("(max-width: 991px)").matches) {
    heroHeight = $(".hero-static").outerHeight();
  } else {
    heroHeight = $(".hero-fixed").outerHeight();
  }
  totalHeight = heaaderHeight + heroHeight;
  return totalHeight;
  // console.log(heroHeight);
}
$(window).load(function () {
  document.documentElement.style.setProperty(
    "--topHeight",
    calcHeight() + "px"
  );
  // calcHeight();
});

$(".list-group-item-action").on("click", function (e) {
  if (window.matchMedia("(max-width: 991px)").matches) {
    console.log(e);
    text = e.target.innerText;
    $(".course-menu .card-title").text(text);
    $(e.currentTarget).parents("#courseMenu").removeClass("show");
    $(e.currentTarget)
      .parents("#courseMenu")
      .siblings(".card-header")
      .attr("aria-expanded", "false");
  } else {
    $(".course-menu .card-title").text("Find on this course");
  }
});

function courseMenuMobile() {
  var text = "Find on this course";
  if (window.matchMedia("(max-width: 991px)").matches) {
    if ($(window).scrollTop() >= calcHeight()) {
      $("body").addClass("course-menu-sticked");
      if ($(".list-group-item-action").hasClass("active")) {
        text = $(".list-group-item-action.active").text();
      }
    } else {
      $("body").removeClass("course-menu-sticked");
    }
  }
  $(".course-menu .card-title").text(text);
}
courseMenuMobile();
$(window).on("scroll", function () {
  courseMenuMobile();
});
$(window).on("resize", function () {
  calcHeight();
  courseMenuMobile();
  if (window.matchMedia("(min-width: 992px)").matches) {
    $("body").removeClass("course-menu-sticked");
  }
});
document.addEventListener("DOMContentLoaded", function (event) {
  var tooltipTriggerList = [].slice.call(
    document.querySelectorAll('[data-bs-toggle="tooltip"]')
  );
  var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl);
  });
});
