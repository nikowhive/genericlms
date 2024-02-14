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
<div class="modal fade" tabindex="-1" role="dialog" id="addNote">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button
            type="button"
            class="close"
            data-dismiss="modal"
            aria-label="Close"
          >
            <span aria-hidden="true">&times;</span>
          </button>
          <h3 class="modal-title">Write Note</h3>
        </div>
        <div class="modal-body">
          <form action="">
            <div class="md-form">                                    
                <textarea class="md-textarea form-control"  id="notes" name="notes" rows="4"></textarea>
                <label for="notes" class="active">Absent with Note </label>
                <input type="hidden" name="modelid" id="modelid">
                <input type="hidden" name="datevalue" id="datevalue">
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">
            Close
          </button>
          <button type="button" class="btn btn-primary" onclick="addnote()" data-dismiss="modal">Add Note</button>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
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
      var idclass = $('#classesID').val();
      var idsection = $('#sectionID').val();
      var attdate = $('#attdate').val();
      if(parseInt(idclass) && parseInt(idsection) && attdate) {
        $.ajax({
            type: 'POST',
            url: "<?=base_url('attendance/take_attendance')?>",
            data: {"id" : idclass, "attdate" : attdate, "idsection":idsection},
            dataType: "html",
            success: function(data) {
               $('#studentList').html(data);
               var studentno = $('#hiddenchron').attr('data-value');
               for(var j=0;j<parseInt(studentno);j++){
                  var value = $('#hiddendiv'+j).attr("data-value");
                  var id = $('#hiddendiv'+j).attr("attendance-id");
                  //console.log(id);
              
                  if (value == "P" || value == "") {
                      $("#p" + id).show();
                      $("#l" + id).hide();
                      $("#a" + id).hide();
                      $("#cid" + id).show();
                      $("#ucid" + id).hide();
                      $("#absentnote" + id).hide();
                      $('#attendances'+id).hide();
                  } else if(value == "L"){
                      $("#p" + id).hide();
                      $("#l" + id).show();
                      $("#a" + id).hide();
                      $("#cid" + id).show();
                      $("#ucid" + id).hide();
                      $("#absentnote" + id).hide();
                      $('#attendances'+id).hide();
                  } else if(value == 'A'){
                      $("#p" + id).hide();
                      $("#l" + id).hide();
                      $("#a" + id).show();
                      $("#cid" + id).hide();
                      $("#ucid" + id).show();
                      $("#absentnote" + id).show();
                      $('#attendances'+id).show();
                      
                  }
              } 
            }  
        });    
      }  
  });
  
  $("#classesID").change(function() {
    var id = $(this).val();
    var attdate = $('#attdate').val();
    window.location.replace("<?php echo base_url('attendance/index')?>"+"/"+id+"/"+attdate);
});


$("#attdate").change(function() {
    var id = $('#classesID').val();
    var attdate = $(this).val();
    var idsection = $('#sectionID').val();
    if(parseInt(id) && parseInt(idsection) && attdate) {
        $.ajax({
            type: 'POST',
            url: "<?=base_url('attendance/take_attendance')?>",
            data: {"id" : id, "idsection" : idsection, "attdate" : attdate},
            dataType: "html",
            success: function(data) {
               $('#studentList').html(data);
               var studentno = $('#hiddenchron').attr('data-value');
               for(var j=0;j<parseInt(studentno);j++){
                  var value = $('#hiddendiv'+j).attr("data-value");
                  var id = $('#hiddendiv'+j).attr("attendance-id");
                  //console.log(id);
              
                  if (value == "P" || value == "") {
                      $("#p" + id).show();
                      $("#l" + id).hide();
                      $("#a" + id).hide();
                      $("#cid" + id).show();
                      $("#ucid" + id).hide();
                      $("#absentnote" + id).hide();
                      $('#attendances'+id).hide();
                  } else if(value == "L"){
                      $("#p" + id).hide();
                      $("#l" + id).show();
                      $("#a" + id).hide();
                      $("#cid" + id).show();
                      $("#ucid" + id).hide();
                      $("#absentnote" + id).hide();
                      $('#attendances'+id).hide();
                  } else if(value == 'A'){
                      $("#p" + id).hide();
                      $("#l" + id).hide();
                      $("#a" + id).show();
                      $("#cid" + id).hide();
                      $("#ucid" + id).show();
                      $("#absentnote" + id).show();
                      $('#attendances'+id).show();
                  }
              }
            
            }
        });  
    }
});
  
   function togglestatus(param){
      var tempScrollTop = $(window).scrollTop();
      var idvalue = $(param).attr("data-key");
      var value = $(param).attr("data-value");
      var datevalue = $(param).attr("date-value");
      if (value == "P" || value == "") {
          $("#p" + idvalue).show();
          $("#l" + idvalue).hide();
          $("#a" + idvalue).hide();
          $("#absentnote" + idvalue).hide();
          $('#attendances'+idvalue).hide();
      } else if(value == 'A'){
          $("#modelid").val(idvalue);
          $("#datevalue").val(datevalue);
          $('#addNote').modal('show');
          $("#p" + idvalue).hide();
          $("#l" + idvalue).hide();
          $("#a" + idvalue).show();
          $("#absentnote" + idvalue).show();
          $('#attendances'+idvalue).show();
      }
      $(window).scrollTop(tempScrollTop);
  } 

  function showModal(param) {
    $('#addNote').modal('show');
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
          $('#attendances'+idvalue).hide();
      } else{
          $("#modelid").val(idvalue);
          $("#datevalue").val(datevalue);
          // $('#addNote').modal('show');
          $("#p" + idvalue).hide();
          $("#l" + idvalue).hide();
          $("#a" + idvalue).show();
          $("#absentnote" + idvalue).show();
          $('#attendances'+idvalue).show();
      }
      $(window).scrollTop(tempScrollTop);
  } 



  function checkStatus(elem) {
    var tempScrollTop = $(window).scrollTop();
    var id = $(elem).attr("id");
    var idvalue = $(elem).attr("data-key");
    var value = $(elem).attr("data-value");
    var datevalue = $(elem).attr("date-value");
    alert(idvalue);
        if (value == "P" || value == "") {
          $("#p" + idvalue).show();
          $("#l" + idvalue).hide();
          $("#a" + idvalue).hide();
          $("#cid" + idvalue).show();
          $("#ucid" + idvalue).hide();
          $("#absentnote" + idvalue).hide();
          $("#attendances" + idvalue).hide();
            
        } else if(value == "L"){
            $("#p" + idvalue).hide();
            $("#l" + idvalue).show();
            $("#a" + idvalue).hide();
            $("#cid" + idvalue).show();
            $("#ucid" + idvalue).hide();
            $("#absentnote" + idvalue).hide();
            $("#attendances" + idvalue).hide();
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
            $("#attendances" + idvalue).show();
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
          data: JSON.stringify({"datevalue" : attdate, "statuses" : idarray, "attid" : attid,"studentid" : studentid,"studentpid" : studentpid,"studentpemail" : studentpemail,"attdate" : attdate, "student_name" : studentname,"classesid":classesid}),
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
          toastr["success"](response.message);
          }
      });  
  }
</script>
