<style>
  .onoffswitch-small-inner:before{
    content : "" !important;
    background-color: #00C851 !important;
  }
  .onoffswitch-small-inner:after{
    content : "" !important;
    background-color: #ff3547 !important;
  }
</style> 
<div class="container container--sm">
  <header class="pg-header mt-4">
    <h1 class="pg-title">Student Attendance</h1>
  </header>

  <div class="row">
    <div class="col-md-6">
      <div class="md-form-block">
        <div class="md-form input-with-post-icon">
          <input
            placeholder="Select date"
            type="text"
            id="attdate"
            class="form-control"
            value="<?php echo isset($attdate)?$attdate:date('d-m-Y');?>"
          />

          <i class="fa fa-calendar input-prefix" tabindex="0"></i>
        </div>
      </div>
    </div>

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
  $(function () {
  $("#attdate").pickadate({
      format: 'dd-mm-yyyy',
      formatSubmit: 'dd-mm-yyyy',
      min: [<?=$startingtime?>],
      max: [<?=$endingtime?>],
      disable: [<?=$weekarray?>,<?=$holidays?>],
      today: 'Today'
  })
  });

  $( window ).on( "load", function() {
      var id = $('#classesID').val();
      //var idsection = $('#sectionID').val();
      var attdate = $('#attdate').val();
      if(parseInt(id) && attdate) {
        $.ajax({
            type: 'POST',
            url: "<?=base_url('attendance/classsectionall')?>",
            data: {"id" : id, "attdate" : attdate},
            dataType: "html",
            success: function(data) {
               $('#studentList').html(data);
            }
        });  
      }  
  });

  $("#classesID").change(function() {
    var id = $(this).val();
    var attdate = $('#attdate').val();
    if(parseInt(id) && attdate) {
        $.ajax({
            type: 'POST',
            url: "<?=base_url('attendance/classsectionall')?>",
            data: {"id" : id, "attdate" : attdate},
            dataType: "html",
            success: function(data) {
               $('#studentList').html(data);
            }
        });
    }
});

$("#attdate").change(function() {
    var id = $('#classesID').val();
    var attdate = $(this).val();
    if(parseInt(id) && attdate) {
        $.ajax({
            type: 'POST',
            url: "<?=base_url('attendance/classsectionall')?>",
            data: {"id" : id, "attdate" : attdate},
            dataType: "html",
            success: function(data) {
               $('#studentList').html(data);
            }
        });  
    }
});

function togglestatus(param){
    var tempScrollTop = $(window).scrollTop();
    var idvalue = $(param).attr("data-key");
    var value = $(param).attr("data-value");
    var datevalue = $(param).attr("date-value");
    if (value == "P") {
        $("#p" + idvalue).show();
        $("#l" + idvalue).hide();
        $("#a" + idvalue).hide();
        $("#cid" + idvalue).show();
        $("#ucid" + idvalue).hide();
        $("#absentnote" + idvalue).hide();
    } else if(value == 'A'){
        $("#modelid").val(idvalue);
        $("#datevalue").val(datevalue);
        $('#addNote').modal('show');
        $("#p" + idvalue).hide();
        $("#l" + idvalue).hide();
        $("#a" + idvalue).show();
        $("#cid" + idvalue).hide();
        $("#ucid" + idvalue).show();
        $("#absentnote" + idvalue).show();
    }
    $(window).scrollTop(tempScrollTop);
} 

function changestatus(param){
    var tempScrollTop = $(window).scrollTop();
    var idvalue = $(param).attr("data-key");
    var value = $(param).attr("data-value");
    var datevalue = $(param).attr("date-value");
    //console.log($('#inner_switch'+idvalue).css('margin-left'));
    if($('#inner_switch'+idvalue).css('margin-left') != '0px' ){
        $("#p" + idvalue).show();
        $("#l" + idvalue).hide();
        $("#a" + idvalue).hide();
        $("#absentnote" + idvalue).hide(); 
    } else{
        $("#modelid").val(idvalue);
        $("#datevalue").val(datevalue);
        $('#addNote').modal('show');
        $("#p" + idvalue).hide();
        $("#l" + idvalue).hide();
        $("#a" + idvalue).show();
        $("#absentnote" + idvalue).show();
    }
    $(window).scrollTop(tempScrollTop);
} 


  function checkStatus(elem) {
    var tempScrollTop = $(window).scrollTop();
    var id = $(elem).attr("id");
    var idvalue = $(elem).attr("data-key");
    var value = $(elem).attr("data-value");
    var datevalue = $(elem).attr("date-value");

            if (value == "P" || value == "") {
              $("#p" + idvalue).show();
              // $("#le" + id).hide();
              $("#l" + idvalue).hide();
              $("#a" + idvalue).hide();
              $("#cid" + idvalue).show();
              $("#ucid" + idvalue).hide();
              $("#absentnote" + idvalue).hide();
                
            } else if(value == "L"){
                $("#p" + idvalue).hide();
                $("#l" + idvalue).show();
                $("#a" + idvalue).hide();
                $("#cid" + idvalue).show();
                $("#ucid" + idvalue).hide();
                $("#absentnote" + idvalue).hide(); 
            } else if(value == 'A'){
                $("#modelid").val(idvalue);
                $("#datevalue").val(datevalue);
                $('#addNote').modal('show');
                $("#p" + idvalue).hide();
                $("#l" + idvalue).hide();
                $("#a" + idvalue).show();
                $("#cid" + idvalue).hide();
                $("#ucid" + idvalue).show();
                $("#absentnote" + idvalue).show();
            }
            
      $(window).scrollTop(tempScrollTop);
  }

  function addnote(){
      var attID = $("#modelid").val();
      var datevalue = $("#datevalue").val();
      var notes = $("#notes").val();
      $.ajax({
            type: 'POST',
            url: "<?=base_url('attendance/addnote')?>",
            data: {"attID" : attID, "datevalue" : datevalue, "notes" : notes},
            dataType: "html",
            success: function(data) {
                $('#absentnote'+attID).html(data);
                $("#notes").val('');
            }                      
            
      });
  }

  $(document).off("click", '.save_attendance', saveattendance).on("click", '.save_attendance', saveattendance) 

  function saveattendance(elem){

      var idarray = new Array();
      var attid = new Array();
      var studentid = new Array();
      var studentpemail = new Array();
      var studentpid = new Array();
      var studentname = new Array();
      var classesid = $('#classesID').val();
      var sectionid = $('#sectionID').val();
      var attdate = $('#attdate').val();
      $('.infofication').each(function(i){
          var id = $(this).attr('id');
          //var attid = $(this).attr('data-key');
          if($('#'+id).css('display') !='none' ){
              idarray.push($(this).attr('data-value'));
              attid.push($(this).attr('data-key'));
              if($('#'+id).attr('data-value') =='A' ){
                  studentid.push($(this).attr('student_id'));
                  studentpemail.push($(this).attr('student_pemail'));
                  studentname.push($(this).attr('student_name'));
                  studentpid.push($(this).attr('student_pid'));
              }
          }
      });
      $.ajax({
          type: 'POST',
          url: "<?=base_url('attendance/ajaxBulkattend')?>",
          data: JSON.stringify({"datevalue" : attdate, "statuses" : idarray, "attid" : attid,"studentid" : studentid,"studentpid" : studentpid,"studentpemail" : studentpemail,"attdate" : attdate, "student_name" : studentname}),
          dataType: 'json', // what type of data do we expect back from the server
          contentType: 'application/json',
          success: function(response) {
            // console.log('asdfsadf');
              $.ajax({
                type: 'POST',
                url: "<?=base_url('attendance/getpresent')?>",
                data: {"id" : classesid, "sectionid" : sectionid, "attdate" : attdate},
                dataType: "html",
                success: function(data) {
                    $('#successno').html(data);
                }                      
                
          }); 

          $.ajax({
                type: 'POST',
                url: "<?=base_url('attendance/getlate')?>",
                data: {"id" : classesid, "sectionid" : sectionid, "attdate" : attdate},
                dataType: "html",
                success: function(data) {
                    $('#warningno').html(data);
                }                      
                
          });

          $.ajax({
                type: 'POST',
                url: "<?=base_url('attendance/getabsent')?>",
                data: {"id" : classesid, "sectionid" : sectionid, "attdate" : attdate},
                dataType: "html",
                success: function(data) {
                    $('#dangerno').html(data);
                }                      
                
          });
          }
      });    
  }
</script>
