<div class="container container--sm">
  <header class="pg-header mt-4">
    <h1 class="pg-title">Students</h1>
  </header>

  <div class="row">
    <div class="col-md-6">
      <div class="md-form-block">
        <div class="md-form">
          <input
            type="search"
            id="form-autocomplete"
            placeholder="Search Student"
            class="form-control mdb-autocomplete"
          />
          <button class="mdb-autocomplete-clear">
            <svg
              fill="#000000"
              height="24"
              viewBox="0 0 24 24"
              width="24"
              xmlns="https://www.w3.org/2000/svg"
            >
              <path
                d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"
              />
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
            <?php foreach($teacherobjs as $teacherobj){?>
            <option value="<?=$teacherobj->classesID?>" <?php echo ($teacherobj->classesID==$classesID)?'selected':'';?>><?=$teacherobj->classes?></option>
            <?php }?>
          </select>
          <?php if($userType==2){?>
          <input type="hidden" value="<?=$sectionobj->sectionID?>" id="sectionID">
          <?php }else{ ?>
               <input type="hidden" value="<?=$sectionID?>" id="sectionID">
          <?php }?>
        </div>
      </div>
    </div>
  </div>
  <section class="mt-4 mb-5 pb-5" id="studentList">
  </section>
</div>


<script>
  $(document).ready(function(){
      $('#form-autocomplete').keyup(function(){
     
      // Search text
      var text = $(this).val();
     
      // Hide all content class element
      $('.attendee-lists-item').hide();

      // Search and show
      $('.attendee-lists-item:contains("'+text+'")').show();
     
      });
  });
  <?php if($userType == 2){?>
  document.getElementById("classesID").disabled = true;
<?php }?>

  $( window ).on( "load", function() {
      var idclass = $('#classesID').val();
      var idsection = $('#sectionID').val();
      if(parseInt(idclass) && parseInt(idsection)) {
        $.ajax({
            type: 'POST',
            url: "<?=base_url('attendance/take_studentview')?>",
            data: {"id" : idclass, "idsection":idsection},
            dataType: "html",
            success: function(data) {
               $('#studentList').html(data);
            }  
        });    
      }  
  });
  <?php if($userType == 1){?>
  $("#classesID").change(function() {
    var id = $(this).val();
    //var attdate = $('#attdate').val();
    window.location.replace("<?php echo base_url('attendance/studentviewlist')?>"+"/"+id);
});
<?php }?> 
</script>
