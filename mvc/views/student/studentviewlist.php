<div class="container container--sm">
  <header class="pg-header mt-4">
    <h1 class="pg-title">Students</h1>

    
      <h5 class="page-header">
        <?php if (($siteinfos->school_year == $this->session->userdata('defaultschoolyearID')) || ($this->session->userdata('usertypeID') == 1)) { ?>
          <?php if (permissionChecker('student_add')) { ?>
            <a href="<?php echo base_url('student/add') ?>" class="btn btn-sm btn-default waves-effect waves-light">
              <i class="fa fa-plus"></i>
              <?= $this->lang->line('add_title') ?>
            </a>
          <?php } ?>
      <?php }?>

  </header>




  <div class="row">
    <div class="col-md-6">
      <div class="md-form-block">
        <div class="md-form">
          <input type="search" id="form-autocomplete" placeholder="Search Student" class="form-control mdb-autocomplete" />
          <button class="mdb-autocomplete-clear">
            <svg fill="#000000" height="24" viewBox="0 0 24 24" width="24" xmlns="https://www.w3.org/2000/svg">
              <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z" />
              <path d="M0 0h24v24H0z" fill="none" />
            </svg>
          </button>
        </div>
      </div>
    </div>
    <div class="col-md-6 mt-3 mt-lg-0">
      <div class="md-form-block">
        <div class="md-form--select md-form">
          <select class="mdb-select" id="classesID">
            <option value="" selected>Select Classes</option>
            <?php foreach ($teacherobjs as $teacherobj) { ?>
              <option value="<?= $teacherobj->classesID ?>" <?php echo ($teacherobj->classesID == $classesID) ? 'selected' : ''; ?>><?= $teacherobj->classes ?></option>
            <?php } ?>
          </select>
          <?php if ($userType == 2) { ?>
            <input type="hidden" value="<?= $sectionobj->sectionID ?>" id="sectionID">
          <?php } else { ?>
            <input type="hidden" value="<?= $sectionID ?>" id="sectionID">
          <?php } ?>
        </div>
      </div>
    </div>
  </div>
  <section class="mt-4 mb-5 pb-5" id="studentList">
  </section>
</div>


<script>
$(document).ready(function(){
$('body').on('click','.delete',function ()
{
  var x = confirm("Are you sure you want to delete?");

  if (x)
      return true;
  else
    return false;
});
})

  $("#form-autocomplete").on('keyup', function() {
    var value = $(this).val().toLowerCase();

    $(".attendee-lists .attendee-lists-item").each(function() {
      if ($(this).text().toLowerCase().search(value) > -1) {
        $(this).show();
      } else {
        $(this).hide();
      }
    });
  })

  <?php if ($userType == 2) { ?>
    document.getElementById("classesID").disabled = true;
  <?php } ?>

  $(window).on("load", function() {
    var idclass = $('#classesID').val();
    var idsection = $('#sectionID').val();
    if (parseInt(idclass) && parseInt(idsection)) {
      $.ajax({
        type: 'POST',
        url: "<?= base_url('student/take_studentview') ?>",
        data: {
          "id": idclass,
          "idsection": idsection
        },
        dataType: "html",
        success: function(data) {
          $('#studentList').html(data);
          var status = '';
          var id = 0;
          $('.onoffswitch-small-checkbox').click(function() {
              if($(this).prop('checked')) {
                  status = 'chacked';
                  id = $(this).parent().attr("id");
              } else {
                  status = 'unchacked';
                  id = $(this).parent().attr("id");
              }

              if((status != '' || status != null) && (id !='')) {
                  $.ajax({
                      type: 'POST',
                      url: "<?=base_url('student/active')?>",
                      data: "id=" + id + "&status=" + status,
                      dataType: "html",
                      success: function(data) {
                          if(data == 'Success') {
                              toastr["success"]("Success")
                              toastr.options = {
                                "closeButton": true,
                                "debug": false,
                                "newestOnTop": false,
                                "progressBar": false,
                                "positionClass": "toast-top-right",
                                "preventDuplicates": false,
                                "onclick": null,
                                "showDuration": "500",
                                "hideDuration": "500",
                                "timeOut": "5000",
                                "extendedTimeOut": "1000",
                                "showEasing": "swing",
                                "hideEasing": "linear",
                                "showMethod": "fadeIn",
                                "hideMethod": "fadeOut"
                              }
                          } else {
                              toastr["error"]("Error")
                              toastr.options = {
                                "closeButton": true,
                                "debug": false,
                                "newestOnTop": false,
                                "progressBar": false,
                                "positionClass": "toast-top-right",
                                "preventDuplicates": false,
                                "onclick": null,
                                "showDuration": "500",
                                "hideDuration": "500",
                                "timeOut": "5000",
                                "extendedTimeOut": "1000",
                                "showEasing": "swing",
                                "hideEasing": "linear",
                                "showMethod": "fadeIn",
                                "hideMethod": "fadeOut"
                              }
                          }
                      }
                  });
              }
          });  
        }
      });
    }
  });
  <?php if ($userType == 1) { ?>
    $("#classesID").change(function() {
      var id = $(this).val();
      //var attdate = $('#attdate').val();
      window.location.replace("<?php echo base_url('student/index') ?>" + "/" + id);
    });
  <?php } ?>
</script>

