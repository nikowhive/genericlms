<div class="container container--sm">
  <header class="pg-header mt-4">
    <h1 class="pg-title">
        Student Attendance
    </h1>
  </header>

  <div class="row">
      <div class="col-md-6">
        <div class="md-form-block">
            <div class="md-form  input-with-post-icon datepicker">
                <div class="<?php echo form_error('date') ? 'form-group has-error' : 'form-group'; ?>" >
                    <input placeholder="<?=date('d-m-YY')?>" type="text" id="attdate" name="date" class="form-control datepicker" data-value="<?=date('d-m-YY')?>">
                   
                    <i class="fa fa-calendar input-prefix" tabindex=0></i>
                </div>
              </div>
 
        </div>
      </div>
      <?php if($userType!=2){ ?>
      <div class="col-md-6 mt-3 mt-lg-0">
        <div class="md-form-block">
            <div class="md-form--select md-form">
                <div class="<?php echo form_error('classesID') ? 'form-group has-error' : 'form-group'; ?>" > 
                    <select class="mdb-select" id="classesID" name="classesID">
                        <option value="" selected>Select Class</option>
                        <?php foreach($classes as $class):?>
                        <option value="<?=$class->classesID?>" selected><?=$class->classes?></option>
                        <?php endforeach;?>
                      
                    </select>
                </div>
               
            </div>
 
        </div>
      </div>
      <?php } else { ?>
        <input type="hidden" name="classesID" id="classesID" value="<?=$classesID->classesID?>">
        <?php } ?>
  </div>

  <section class="mt-4" id="sectionList">
  </section>

  <section class="mt-4" id="subjectList">
  </section>
</div>


<script>
$(function(){
    $('.datepicker').pickadate({
        format: 'dd-mm-yyyy',
        formatSubmit: 'dd-mm-yyyy',
        min: [<?=$startingtime?>],
        max: [<?=$endingtime?>],
        disable: [<?=$siteinfos->weekends?>,<?=$holidays?>],
        today: 'Today'
    });
})

 $( window ).on( "load", function() {
    var id = $('#classesID').val();
    var attdate = $('#attdate').val();
    if(parseInt(id)) {

        <?php if($setting->attendance=="subject"){ ?>
            $.ajax({
                type: 'POST',
                url: "<?=base_url('attendance/subjectall')?>",
                data: {"id" : id, "attdate" : attdate},
                dataType: "html",
                success: function(data) {
                   $('#subjectList').html(data);
                }
            });
        
        <?php } else { ?>
            $.ajax({
                type: 'POST',
                url: "<?=base_url('attendance/classsectionall')?>",
                data: {"id" : id, "attdate" : attdate},
                dataType: "html",
                success: function(data) {
                   $('#sectionList').html(data);
                }
            });
        <?php }?>    
    }
});

$("#classesID").change(function() {
    var id = $(this).val();
    var attdate = $('#attdate').val();
    if(parseInt(id)) {

        <?php if($setting->attendance=="subject"){ ?>
            $.ajax({
                type: 'POST',
                url: "<?=base_url('attendance/subjectall')?>",
                data: {"id" : id, "attdate" : attdate},
                dataType: "html",
                success: function(data) {
                   $('#subjectList').html(data);
                }
            });
        
        <?php } else { ?>
            $.ajax({
                type: 'POST',
                url: "<?=base_url('attendance/classsectionall')?>",
                data: {"id" : id, "attdate" : attdate},
                dataType: "html",
                success: function(data) {
                   $('#sectionList').html(data);
                }
            });
        <?php }?>    
    }
});

$("#attdate").change(function() {
    var id = $('#classesID').val();
    var attdate = $(this).val();
    if(parseInt(id)) {

        <?php if($setting->attendance=="subject"){ ?>
            $.ajax({
                type: 'POST',
                url: "<?=base_url('attendance/subjectall')?>",
                data: {"id" : id, "attdate" : attdate},
                dataType: "html",
                success: function(data) {
                   $('#subjectList').html(data);
                }
            });
        
        <?php } else { ?>
            $.ajax({
                type: 'POST',
                url: "<?=base_url('attendance/classsectionall')?>",
                data: {"id" : id, "attdate" : attdate},
                dataType: "html",
                success: function(data) {
                   $('#sectionList').html(data);
                }
            });
        <?php }?>    
    }
});
</script>