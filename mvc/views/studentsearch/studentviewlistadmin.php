<div class="container container--sm">
  <header class="pg-header mt-4">
    <h1 class="pg-title">Students</h1>
  </header>

  <div class="row">
    <!-- <div class="col-md-6">
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
    </div> -->
    <div class="col-md-6 mt-3 mt-lg-0">
      <div class="md-form-block">
        <div class="md-form--select md-form">
          <select class="mdb-select" id="classesID">
            <option value="" selected>Select Classes</option>
            <?php foreach($teacherobjs as $teacherobj){
              if(isset($classesID)){?>

            <option value="<?=$teacherobj->classesID?>" <?php echo ($teacherobj->classesID==$classesID)?'selected':'';?>><?=$teacherobj->classes?></option>
            <?php }else{?>
            <option value="<?=$teacherobj->classesID?>" ><?=$teacherobj->classes?></option>
            <?php }
            }?>
          </select>
        </div>
      </div>
    </div>
  </div>
  <section class="mt-4 mb-5 pb-5" id="studentList">
  </section>
  
</div>

<script>  
  $( window ).on( "load", function() {
      var idclass = $('#classesID').val();
      if(parseInt(idclass)) {
        $.ajax({
            type: 'POST',
            url: "<?=base_url('studentsearch/classsectionview')?>",
            data: {"id":idclass},
            dataType: "html",
            success: function(data) {
               $('#studentList').html(data);
            }  
        });    
      }  
  });

  $("#classesID").change(function() {
    var id = $('#classesID').val();
    if(parseInt(id)) {
        $.ajax({
            type: 'POST',
            url: "<?=base_url('studentsearch/classsectionview')?>",
            data: {"id" : id},
            dataType: "html",
            success: function(data) {
               $('#studentList').html(data);
            }
        });
    }
});
</script>
