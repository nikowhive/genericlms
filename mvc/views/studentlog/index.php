<style>
  .onoffswitch-small-inner:before{
    content : "" !important;
    background-color: #00C851 !important;
  }
  .onoffswitch-small-inner:after{
    content : "" !important;
    background-color: #ff3547 !important;
  }

  .container--sm {
    max-width: 100%!important;
    width: 100%;
  }

    em.rollnumber {
        font-size: 12px;
    }

    .timerange{
        font-size: 13px;
    }

    /* @media (min-width: 992px) */
    .attendee-lists-item:before {
        content: ''!important;
        flex: 0;
        margin-right: 16px;
        align-self: flex-start;
        flex: 0 0 auto;
    }

</style> 

<div class="row row--quiz">
    <div class="col-lg-4 order-lg-2 mb-3 mb-lg-0">
        <div class="card card--quiz card--quiz-Sidebar js-affix-top affix-top">
            <div class="card-body">
                <a class="quiz-sidebar-title " role="button" data-toggle="collapse" href="#quizFilters" aria-expanded="false">
                    <span>Student Log</span>
                    <i class="fa fa-caret-down"></i>
                </a>
                <h3 class="card-title">Student Log</h3>
                <div id="quizFilters" class="collapse">
                    All Student: &nbsp;
                    <input type="checkbox" id="allstudents" value="1" class=""/>
                    <input type="hidden" id="all" name="allstudent" value="1"/>
                    <input type="hidden" id="pageValue" value="0"/>
                    <div class="form1">
                        <div class="md-form--select md-form">
                            <select class="mdb-select" id="classesID" require name="classesID">
                                <option value="0" selected>Select Class</option>
                                <?php foreach($classes as $class){?>
                                    <option value="<?=$class->classesID?>"><?=$class->classes?></option>
                                <?php }?>
                            </select>
                            <label class="mdb-main-label">Class <span class="text-red">*</span> </label>
                        </div>

                        <div class="md-form--select md-form">
                            <select class="mdb-select" id="studentID" require name="studentID">
                                <option value="0" selected>Select Student</option>
                            </select>
                            <label class="mdb-main-label">Student <span class="text-red">*</span></label>
                        </div>
                    </div>
                    <div class="form2">
                        <div class="md-form--select md-form">
                            <select class="mdb-select" id="eventID" require name="eventID">
                                <option value="0" selected>Select Event</option>
                                <option value="1"  >Attendance Log</option>
                                <option value="2"  >Course Log</option>
                            </select>
                            <label class="mdb-main-label">Event</label>
                        </div>

                        <div class="md-form--select md-form">
                            <input placeholder="Select startdate" type="text" id="startDate" name="startDate" class="form-control"
                            value="<?php echo date('d-m-Y'); ?>" require />
                            <label class="mdb-main-label">Start Date</label>
                        </div>

                        <div class="md-form--select md-form">
                            <input placeholder="Select enddate" type="text" id="endDate" name="endDate" class="form-control"
                            value="<?php echo date('d-m-Y'); ?>" require />
                            <label class="mdb-main-label">End Date</label>
                        </div>
                    </div>    
                    <input type="submit" class="btn btn-primary" value="Filter" id="filterLog">
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-8 order-lg-1 ">
        <div class="card card--quiz">
            <div class="card-body">
                <section class="mt-4 mb-5 pb-5" id="logList">
                    Please filter to view student logs.
                </section>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">

$(document).ready(function(){

    $("#allstudents").click(function(){
        if ($(this).is(':checked')) {
             $('#all').val(0);
             $('#classesID').val(0);
             $('#studentID').val(0);
             $('.form1').hide();
             $('#logList').html('');
        }else{
            $('#all').val(1);
            $('.form1').show();
            $('#logList').html('');
            
        }  
    });    
});


$(function () {

    // $("#startDate").pickadate({
    //     format: 'dd-mm-yyyy',
    //     formatSubmit: 'dd-mm-yyyy',
    //     today: 'Today'
    // })

    // $("#endDate").pickadate({
    //     format: 'dd-mm-yyyy',
    //     formatSubmit: 'dd-mm-yyyy',
    //     today: 'Today'
    // })

    $('#startDate,#endDate').datepicker({
        dateFormat: 'dd-mm-yy'
    });


$('#filterLog').click(function(e){

        var error     = false;
        var message   = '';
        var all       = $('#all').val();
        var classesID = $('#classesID').val();
        var studentID = $('#studentID').val();
        var eventID   = $('#eventID').val();
        var startDate = $('#startDate').val();
        var endDate   = $('#endDate').val();

        var field = {
            'classesID'   : $('#classesID').val(), 
            'studentID'   : $('#studentID').val(), 
            'eventID'     : $('#eventID').val(), 
            'startDate'   : $('#startDate').val(),
            'endDate'     : $('#endDate').val(),
        };

        var data = new FormData();
        $.each(field,function(key,input){
            data.append(key,input);
        });
        
        if(all == 0){
            error = false;
        }else{
           
            if(classesID == 0){
                error = true;
                message += '<p>Please select class.</p>';
            }
            if(studentID == 0){
                error = true;
                message += '<p>Please select student.</p>';
            }
        }

        if(error){
            showToastError(message);
        }else{
            ajaxCall(data,all);
        }
        
    })

  });


  function ajaxCall(data,all){

        if(all == 1){
            var url = "<?=base_url('studentlog/index')?>";
        }else{
            var url = "<?=base_url('studentlog/allStudentLogs')?>";
        }
       
        $.ajax({
                type: 'POST',
                url: url,
                data: data,
                dataType: "html",
                cache: false,
                contentType: false,
                processData: false,
                success: function(data) {
                    var response = JSON.parse(data);
                    $('#logList').html(response.template);
                    $('#pageValue').val(0)
                }
        });
  }

$("#classesID").change(function() {
    var id = $(this).val();

    if(parseInt(id)) {
        var studentID = "";
        $.ajax({
            type: 'POST',
            url: "<?=base_url('studentlog/getStudents')?>",
            data: {'classesID':id,'studentID':studentID},
            dataType: "html",
            success: function(data) {
               $('#studentID').html(data);
               $('#studentID').material_select();
            }
        });
    }
});


$(document).ready(function() {
       
        var hasData = true;
        var pageValue = 0;
       
        $('body').on('scroll', function() {
            if ($('body').scrollTop() + $('body').height() >= $(document).height()) {
                var pageValue = parseInt($('#pageValue').val());
                pageValue += 20;
                
                var classesID = $('#classesID').val();
                var studentID = $('#studentID').val();
                var eventID   = $('#eventID').val();
                var startDate = $('#startDate').val();
                var endDate   = $('#endDate').val();
                var all       = $('#all').val();
                         
                if (hasData) {

                    if(all == 1){
                        var url = "<?=base_url('studentlog/getMoreStudentLogs')?>";
                    }else{
                        var url = "<?=base_url('studentlog/getMoreAllStudentLogs')?>";
                    }

                    $.ajax({
                        url: url +'/' + pageValue,
                        type: "get",
                        data: {'classesID':classesID,'studentID':studentID,'eventID':eventID,'startDate':startDate,'endDate':endDate},
                        success: function(data) {
                            var response = JSON.parse(data);
                            $('#logcontentwrapper').append(response.template);
                            var a = $('#totalTimeSpent').val();
                            var b = $('#moreTotal'+pageValue).val();
                            var totalTime = parseInt(a) + parseInt(b);
                            $('#totalTimeSpenthtml').html(secondsToHms(totalTime))
                            $('#totalTimeSpent').val(totalTime)
                            $('#pageValue').val(pageValue)
                           
                        },
                        error: function(response) {
                            hasData = false;
                        }
                    });
                }
            }
        });

});

function secondsToHms(seconds) {

    let d = Number(seconds);

    if(d <= 0){
       return '00:00:00'
    }else{
        let h = Math.floor(d / 3600);
        let m = Math.floor(d % 3600 / 60);
        let s = Math.floor(d % 3600 % 60);

        let hDisplay = h <= 9 ? '0'+ h+':' : h+ ":";
        let mDisplay = m <= 9 ? '0'+ m+':' : m+ ":";
        let sDisplay = s <= 9 ? '0'+ s : s;

        return hDisplay+':'+mDisplay+':'+ sDisplay; 

    }
}

</script>





