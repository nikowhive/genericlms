$(document).ready(function () {
  $(".edit_unit").on("click", function (params) {
    var unit_id = $(this).data("id");
    var course = $(this).data("course");
    $("#view_ajax_modal_content").empty();
   
    $.ajax({
      type: "POST",
      url: BASE_URL + "unit/getUnitByAjax",
      dataType: "html",
      data: { id: unit_id, course: course },
      success: function (data) {
        console.log(data);
        $("#viewmodal").modal("show");
        $("#view_ajax_modal_content").append(data);
      },
    });
  });

  $(".edit_chapter").on("click", function (params) {
    var chapter_id = $(this).data("id");
    var course = $(this).data("course");
    $("#view_ajax_modal_content").empty();
    
    $.ajax({
      type: "POST",
      url: BASE_URL + "chapter/editChapterByAjax",
      dataType: "html",
      data: { id: chapter_id, course: course },
      success: function (data) {
        console.log(data);
        $("#viewmodal").modal("show");
        $("#view_ajax_modal_content").append(data);
      },
    });
  });

  $(".add_chapter").on("click", function (params) {
    var unit_id = $(this).data("unit-id");
    var course = $(this).data("course");
    $("#view_ajax_modal_content").empty();
   
    $.ajax({
      type: "POST",
      url: BASE_URL + "chapter/addChapterWithUnit",
      dataType: "html",
      data: { unit_id: unit_id, course: course },
      success: function (data) {
        console.log(data);
        $("#viewmodal").modal("show");
        $("#view_ajax_modal_content").append(data);
      },
    });
  });

  $(".view-attachment").on("click", function (params) {
    var unit_id = $(this).data("id");
    var course = $(this).data("course");
    $("#view_ajax_modal_content").empty();
   
    $.ajax({
      type: "POST",
      url: BASE_URL + "courses/getAttachmentByAjax",
      dataType: "html",
      data: { id: unit_id, course: course },
      success: function (data) {
        console.log(data);
        $("#viewmodal").modal("show");
        $("#view_ajax_modal_content").append(data);
      },
    });
  });

  $(".view-link").on("click", function (params) {
    var unit_id = $(this).data("id");
    var course = $(this).data("course");
    $("#view_ajax_modal_content").empty();
    
    $.ajax({
      type: "POST",
      url: BASE_URL + "courses/getLinkByAjax",
      dataType: "html",
      data: { id: unit_id, course: course },
      success: function (data) {
        console.log(data);
        $("#viewmodal").modal("show");
        $("#view_ajax_modal_content").append(data);
      },
    });
  });

  $(".quiz-title").on("click", function () {
    var id = $(this).data("id");
    var course = $(this).data("course");
    $("#view_ajax_modal_content").empty();
    $.ajax({
      type: "POST",
      url: BASE_URL + "courses/getQuizByAjax",
      dataType: "html",
      data: { id: id, course: course },
      success: function (data) {
        console.log(data);
        $("#viewmodal").modal("show");
        $("#view_ajax_modal_content").append(data);
      },
    });
  });

  $(".viewAtt").on("click", function () {
    contentid = $(this).data("id");
    course = $(this).data("course");
    set = $(this).data("set");
    $("#view_ajax_modal_content").empty();

    $.ajax({
      type: "POST",
      url: BASE_URL + "courses/getAssignmentByAjax",
      dataType: "html",
      data: { id: contentid, course: course, set: set },
      success: function (data) {
        console.log(data);
        $("#viewmodal").modal("show");
        $("#view_ajax_modal_content").html(data);
      },
    });
  });

  


  $(".viewHomework").on("click", function () {
    contentid = $(this).data("id");
    course = $(this).data("course");
    set = $(this).data("set");
    $("#view_ajax_modal_content").empty();
    
    $.ajax({
      type: "POST",
      url: BASE_URL + "courses/getHomeworkByAjax",
      dataType: "html",
      data: { id: contentid, course: course, set: set },
      success: function (data) {
        
        console.log(data);
        $("#viewmodal").modal("show");
        $("#view_ajax_modal_content").html(data);
      },
    });
  });

  $(".viewClasswork").on("click", function () {
    contentid = $(this).data("id");
    course = $(this).data("course");
    set = $(this).data("set");
    $("#view_ajax_modal_content").empty();
    
    $.ajax({
      type: "POST",
      url: BASE_URL + "courses/getClassworkByAjax",
      dataType: "html",
      data: { id: contentid, course: course, set: set },
      success: function (data) {
        
        console.log(data);
        $("#viewmodal").modal("show");
        $("#view_ajax_modal_content").html(data);
      },
    });
  });
});

$(document).ready(function ($) {
    let $elements = $(".treeview-animated-element");

  $(".closed").click(function () {
    $this = $(this);
    $target = $this.siblings(".nested");
    $pointer = $this.children(".fa-angle-right");

    $this.toggleClass("open");
    $pointer.toggleClass("down");

    !$target.hasClass("active")
      ? $target.addClass("active").slideDown()
      : $target.removeClass("active").slideUp();

    return false;
  });

  $elements.click(function () {
    $this = $(this);

    $this.hasClass("opened")
      ? $this.removeClass("opened")
      : ($elements.removeClass("opened"), $this.addClass("opened"));
  });
});

$('#viewmodal').on('hidden.bs.modal', function (e) {
  var $iframes = $(e.target).find('iframe');
  $iframes.each(function(index, iframe){
  $(iframe).attr("src", $(iframe).attr('src'));
  });
})

$('#viewmodal').on('hidden.bs.modal', function (e) {
  $("#myModal video").attr("src", $("#myModal video").attr("src"));
 
})
