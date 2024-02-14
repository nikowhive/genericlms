<style>
      * {
        font-family: sans-serif;
      }
      .selections{
          height: 110px;
          overflow-y: auto;
      }
      .selected{
          height: 110px;
          overflow-y: auto;
      }
	  .checkbox--pill {
        margin: 3px;
    }
    </style>
    <link rel="stylesheet" href="<?php echo base_url('assets/inilabs/jquery.tree-multiselect.min.css'); ?>">
    <script src="<?php echo base_url('assets/jqueryUI/jqueryui.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/inilabs/jquery.tree-multiselect.js'); ?>"></script>
<?php
    $email = $this->session->userdata('email');
    $usertype=$this->session->userdata('usertype');
?>

<div class="box">
    <div class="box-body">
        <div class="row">
			<?php include_once 'sidebar.php'; ?>

			<div class="col-md-9">

				<div class="box box-primary">
                	<div class="box-header with-border">
                  		<h3 class="box-title"><?=$this->lang->line('compose_new')?></h3>
                  	</div>


                  	<div class="box-body">
                    	<form role="form" method="post" enctype="multipart/form-data">
	                      		<?php 
	                            //   if(form_error('email')) 
	                            //       echo "<div id='divemail' class='form-group has-error' >";
	                            //   else     
	                            //       echo "<div id='divemail' class='form-group' >";
	                          ?>
	                              <!-- <label for="email" class="col-sm-2 control-label">
	                                Recipient Users:  
	                              </label>
	                              <div class="col-sm-6">
	                                  <select name="email[]" id="test-select-1"></select>
	                              </div>
	                              <span class="col-sm-4 control-label">
	                                  <?php //echo form_error('email'); ?>
	                              </span>
	                            </div> -->
                      		
								<?php $this->load->view("notice/users"); ?>

							<div id="classDiv" class="form-group <?=form_error('classID') ? 'has-error' : '' ?>" style="display:none;">
		                        <select id="classID" class="Group form-control select2" name="classID">
		                            <option value=""><?=$this->lang->line('select_class')?></option>
		                        </select>
	                          	<span id="selectDiv" class="control-label">
	                              <?php echo form_error('classID'); ?>
	                          	</span>
		                    </div>


		                    <div id="stdDiv" class="form-group" style="display:none;">
		                        <select id="studentID" class="Group form-control select2" name="studentID">
		                            <option value=""><?=$this->lang->line('select_student')?></option>
		                        </select>
								
		                        <span class="has-error" id="selectDiv">
		                            <?php echo form_error('studentID'); ?>
		                        </span>
		                    </div>


		                    <div id="adminDiv" class="form-group" style="display:none;">
	                          	<select id="systemadminID" class="Group form-control select2" name="systemadminID">
	                            	<option value=""><?=$this->lang->line('select_admin')?></option>
	                          	</select>
		                    
		                        <span class="has-error" id="selectDiv">
		                            <?php echo form_error('systemadminID'); ?>
		                        </span>
		                    </div>

		                    <div id="teacherDiv" class="form-group" style="display:none;">
	                          	<select id="teacherID" class="Group form-control select2" name="teacherID">
	                            	<option value=""><?=$this->lang->line('select_teacher')?></option>
	                          	</select>
		                        <span class="has-error" id="selectDiv">
									<?php echo form_error('teacherID'); ?>
		                        </span>
		                    </div>


		                    <div id="parentDiv" class="form-group" style="display:none;">
                          		<select id="parentID" class="Group form-control select2" name="parentID">
                            		<option value=""><?=$this->lang->line('select_parent')?></option>
                          		</select>
                   
		                        <span class="has-error" id="selectDiv">
									<?php echo form_error('parentID'); ?>
		                        </span>
                      		</div>

	                      	<div id="userDiv" class="form-group" style="display:none;">
	                          	<select id="userID" class="Group form-control select2" name="userID">
	                            	<option value=""><?=$this->lang->line('select_user')?></option>
	                          	</select>
	                       
	                        	<span class="has-error" id="selectDiv">
	                           		<?php echo form_error('userID'); ?></p>
	                        	</span>
	                      	</div>

                      		<div class="form-group <?=form_error('subject') ? 'has-error' : '' ?>">
                        		<input class="form-control" name="subject" value="<?=set_value('subject')?>" placeholder="<?=$this->lang->line('subject')?>"/>

                        		<span class="control-label">
                              		<?php echo form_error('subject'); ?>
                          		</span>
                          	</div>
                      
	                      	<div class="form-group <?=form_error('message') ? 'has-error' : '' ?>">
	                        	<textarea id="message" class="form-control" name="message" rows="10" placeholder="<?=$this->lang->line('message')?>"><?=set_value('message')?></textarea>

	                        	<span class="control-label">
                              		<?php echo form_error('message'); ?>
                          		</span>
                          	</div>


                          	<div class="form-group">
		                        <div class="btn btn-info btn-file">
		                          	<i class="fa fa-paperclip"></i> <?=$this->lang->line('attachment')?>
		                          	<input type="file" id="attachment" name="attachment"/>
		                        </div>
                        		<div class="col-sm-3" style="padding-left:0;">
                            		<input class="form-control"  id="uploadFile" placeholder="<?=$this->lang->line('choosefile');?>" disabled />
                        		</div>
                        		<div class="has-error">
                            		<p class="text-danger"> <?php echo form_error('attachment'); ?></p>
                        		</div>
                      		</div>

                      		<div class="pull-right">
                        		<button type="submit" value="send" name="submit" class="btn btn-primary"><i class="fa fa-envelope-o"></i> <?=$this->lang->line('send')?></button>
                      		</div>

                      		<button type="submit" value="draft" name="submit" class="btn btn-warning"><i class="fa fa-times"></i> <?=$this->lang->line('draft')?></button>


                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>

$('#employeewrapper').hide();
$('#classwrapper').hide();
$('#filterUsers').hide();

$('#saveUsers').click(function(){

   if($('#status').is(':checked') == false){
    
    var employeeType = $('#bulkEmployeeType').val();
    var classes = $('#bulkClass').val();
    var employees = $('#bulkEmployee').val();

    if(employeeType == ''){
        showToastError('Please select employee type.');
        return false;
    }
    if(employeeType.includes(3) || employeeType.includes(4)){
       if(classes == ''){
         showToastError('Please select class.');
         return false;
       }
    }
    if(employeeType.includes(11)){
       if(employees == ''){
            showToastError('Please select employee.');
            return false;
       }
    }

    var ids = [];
    if((employeeType !=0 && classes != 0) || (employeeType !=0 && employees != 0)){
        var length = $("input[name='users[]']:checked").length;
        if(length == 0){
            showToastError("Users are not selected yet.");
            return false;
        }
    }
}
});

  
$('.types').click(function(){

   var ids = [];
   var id = $(this).attr('id');
   if(id == 'types-0'){
        if($(this).is(':checked')){
              $('.types').not(this).attr('disabled', 'disabled'); 
        }else{
            $('.types').not(this).removeAttr('disabled');
        }
        $('.types').not(this).removeAttr('checked');
        $.each($("input[name='types']:checked"), function(){
            ids.push($(this).val());
        });
    }else{
        $.each($("input[name='types']:checked"), function(){
            ids.push($(this).val());
        });
    }
    var users = ids.join(",");

    if(users.includes(0)){
        $('#bulkClass').val('');
        $('#bulkEmployee').val('');
    }

    if(users.includes(3) || users.includes(4)){
        $('#classwrapper').show(); 
    }else{
        $('#classwrapper').hide(); 
        $('.classes').removeAttr('checked');
        $('.classes').removeAttr('disabled');
        $('#bulkClass').val('');
    }

    if(users.includes(11)){
        $('#employeewrapper').show();
    }else{
        $('#employeewrapper').hide(); 
        $('.employees').removeAttr('checked');
        $('.employees').removeAttr('disabled');
        $('#bulkEmployee').val('');
    }

    $('#bulkEmployeeType').val(users);

    renderTemplates();
});

$('.classes').click(function(){
   var ids = [];
   var id = $(this).attr('id');

    if(id == 'class0'){
        if($(this).is(':checked')){
            $('.classes').not(this).attr('disabled', 'disabled'); 
        }else{
            $('.classes').not(this).removeAttr('disabled');
        }
        $('.classes').not(this).removeAttr('checked');
        $.each($("input[name='classes']:checked"), function(){
            ids.push($(this).val());
        });
    }else{
        $.each($("input[name='classes']:checked"), function(){
            ids.push($(this).val());
        });
    }
    var classes = ids.join(",");
    $('#bulkClass').val(classes);
    renderTemplates();
});

$('.employees').click(function(){

    var ids = [];
    var id = $(this).attr('id');

    if(id == 'employee0'){
        if($(this).is(':checked')){
            $('.employees').not(this).attr('disabled', 'disabled'); 
        }else{
            $('.employees').not(this).removeAttr('disabled');
        }
        $('.employees').not(this).removeAttr('checked');
        $.each($("input[name='employees']:checked"), function(){
            ids.push($(this).val());
        });
    }else{
        $.each($("input[name='employees']:checked"), function(){
            ids.push($(this).val());
        });
    }

    var employees = ids.join(",");
    $('#bulkEmployee').val(employees);
    renderTemplates();
});

function renderTemplates(){

    var selectedUsers  = [];
    $.each($("input[name='users[]']:checked"), function(){
        selectedUsers.push($(this).val());
    });

    var keyselectedUsers  = [];
    $.each($(".allcheck:checked"), function(){
        keyselectedUsers.push($(this).val());
    });

    $('.user-selection-body .usersWrapper').html('');
    $('#filterUsers').hide();
    $('#searchuser2').val('');

    var employeeType = $('#bulkEmployeeType').val();
    var classes = $('#bulkClass').val();
    var employees = $('#bulkEmployee').val();


    if(employeeType == 0){
        $('.user-selection-body .usersWrapper').html('This message will be sent to all users.');
         totalUsers();
    }
    if(employeeType == ""){
        $('.user-selection-body .usersWrapper').html('');
        totalUsers();
    }

    if(classes == 0 || employees == 0){
        totalUsers();
    }
    
    if(employeeType != 0){
        if(classes != 0 || employees != 0){
            $.ajax({
            type: 'GET',
            url: "<?= base_url('notice/renderUsersTemplate') ?>",
            data: {
                'employeeType': employeeType,
                'classes': classes,
                'employees': employees,
                'selectedUsers':selectedUsers,
                'keyselectedUsers':keyselectedUsers
            },
            dataType: "html",
            success: function(data) {
                $('.user-selection-body .usersWrapper').html(data);
                $('#filterUsers').show();
                totalUsers();
            }
        });
        }
    }

}


$('#searchUser').click(function(e){
   e.preventDefault();  
   var name = $('#searchuser2').val(); 
//    if(name != ''){
    var employeeType = $('#bulkEmployeeType').val();
    var classes = $('#bulkClass').val();
    var employees = $('#bulkEmployee').val();

    if(employeeType != 0){
        if(classes != 0 || employees != 0){
            $.ajax({
            type: 'GET',
            url: "<?= base_url('notice/renderUsersTemplate') ?>",
            data: {
                'employeeType' : employeeType,
                'classes'      : classes,
                'employees'    : employees,
                'name'         : name
            },
            dataType: "html",
            success: function(data) {
                $('.user-selection-body .usersWrapper').html(data);
                totalUsers();
            }
        });
        }
    }
//    }
});

function totalUsers(){

    var employeeType = $('#bulkEmployeeType').val();
    var classes = $('#bulkClass').val();
    var employees = $('#bulkEmployee').val();

    if(employeeType == 0){
        var total = $('#types-0').data('count');
        $('#totalSelectedUsers').html('Selected - <b>'+total+'</b> Users');
    }

    if(employeeType == ''){
        total = 0;
        $('#totalSelectedUsers').html('Selected - <b>'+total+'</b> Users');
    }

    if(employeeType != 0){
        total = 0;
        if(classes == 0 && classes != ''){
            var numbers = employeeType.split(',');
            for(var i = 0; i < numbers.length; i++)
            {
                if(numbers[i] != 11){
                    var id = $('#types-'+numbers[i]).data('count');
                    total = total + id;
                }
            }
        }
        if(employees == 0 && employees != ''){
                var id = $('#types-11').data('count');
                total = total + id;
        }

        var u = $("input[name='users[]']:checked").length;

        var total = total + u;

        $('#totalSelectedUsers').html('Selected - <b>'+total+'</b> Users');
    }
}

</script>


<!-- <script>
    $('#message').jqte();
  	$('.select2').select2();
  	document.getElementById("attachment").onchange = function() {
      	document.getElementById("uploadFile").value = this.value;
  	};

  	$.getJSON( "<?//=base_url('conversation/getAlldatas')?>", function( data ) {

        var $select = $('#test-select-1');
        $.each( data, function( key, val ) {
          //console.log(val.email);
          var $option = $('<option value="'+val.id+'/'+val.usertypeID+'" data-section="'+val.category2+'/'+val.category1+'">'+val.name+'</option>');
          $select.append($option);    

        });

        $select.treeMultiselect({ enableSelectAll: true, sortable: true, searchable: true, startCollapsed: true});
    });    

  	$( "#userGroup" ).change(function() {
  		if($(this).val() == 1) {
		    $("#classDiv").hide();
		    $("#stdDiv").hide();
		    $("#teacherDiv").hide();
		    $("#parentDiv").hide();
		    $("#userDiv").hide();
		    $("#adminDiv").show();
		    $.ajax({
				type: 'POST',
				url: "<?//=base_url('conversation/adminCall')?>",
				dataType: "html",
				success: function(data) {
					$('#systemadminID').html(data);
				}
		    });
  		} else if($(this).val() == 2) {
			$("#classDiv").hide();
			$("#stdDiv").hide();
			$("#adminDiv").hide();
			$("#parentDiv").hide();
			$("#userDiv").hide();
			$("#teacherDiv").show();
			$.ajax({
				type: 'POST',
				url: "<?//=base_url('conversation/teacherCall')?>",
				dataType: "html",
				success: function(data) {
					$('#teacherID').html(data);
				}
			});
		} else if($(this).val() == 3) {
			$("#classDiv").show();
			$("#stdDiv").show();
			$("#adminDiv").hide();
			$("#teacherDiv").hide();
			$("#userDiv").hide();
			$("#parentDiv").hide();
			$.ajax({
				type: 'POST',
				url: "<?//=base_url('conversation/classCall')?>",
				dataType: "html",
				success: function(data) {
					$('#classID').html(data);
				}
			});
		} else if($(this).val() == 4) {
			$("#classDiv").hide();
			$("#stdDiv").hide();
			$("#adminDiv").hide();
			$("#parentDiv").hide();
			$("#teacherDiv").hide();
			$("#userDiv").hide();
			$("#parentDiv").show();
			$.ajax({
				type: 'POST',
				url: "<?//=base_url('conversation/parentCall')?>",
				dataType: "html",
				success: function(data) {
					$('#parentID').html(data);
				}
			});
  		} else {
			var id = $(this).val();
			$("#classDiv").hide();
			$("#stdDiv").hide();
			$("#adminDiv").hide();
			$("#parentDiv").hide();
			$("#teacherDiv").hide();
			$("#parentDiv").hide();
			$("#userDiv").show();
			$.ajax({
				type: 'POST',
				url: "<?//=base_url('conversation/userCall')?>",
				data : {id : id},
				dataType: "html",
				success: function(data) {
					$('#userID').html(data);
				}
			});
		}
	});

	$('#classID').change(function(event) {
	    var classID = $(this).val();
	    if(classID === '0') {
	        $('#studentID').val(0);
	    } else {
	        $.ajax({
	            type: 'POST',
	            url: "<?//=base_url('conversation/studentCall')?>",
	            data: "id=" + classID,
	            dataType: "html",
	            success: function(data) {
	               $('#studentID').html(data);
	            }
	        });
	    }
	});
</script>

<?php //if($GroupID != 0) { ?>
<script>

	var GroupID = "<?//=$GroupID?>";

  	if(GroupID == 1) {
	    $("#classDiv").hide();
	    $("#stdDiv").hide();
	    $("#teacherDiv").hide();
	    $("#parentDiv").hide();
	    $("#userDiv").hide();
	    $("#adminDiv").show();
	    $.ajax({
			type: 'POST',
			url: "<?//=base_url('conversation/adminCall')?>",
			dataType: "html",
			success: function(data) {
				$('#systemadminID').html(data);
			}
	    });
  	} else if(GroupID == 2) {
		$("#classDiv").hide();
		$("#stdDiv").hide();
		$("#adminDiv").hide();
		$("#parentDiv").hide();
		$("#userDiv").hide();
		$("#teacherDiv").show();
		$.ajax({
			type: 'POST',
			url: "<?//=base_url('conversation/teacherCall')?>",
			dataType: "html",
			success: function(data) {
				$('#teacherID').html(data);
			}
		});
	} else if(GroupID == 3) {
		$("#classDiv").show();
		$("#stdDiv").show();
		$("#adminDiv").hide();
		$("#teacherDiv").hide();
		$("#userDiv").hide();
		$("#parentDiv").hide();
		$.ajax({
			type: 'POST',
			url: "<?//=base_url('conversation/classCall')?>",
			dataType: "html",
			success: function(data) {
				$('#classID').html(data);
			}
		});
	} else if(GroupID == 4) {
		$("#classDiv").hide();
		$("#stdDiv").hide();
		$("#adminDiv").hide();
		$("#parentDiv").hide();
		$("#teacherDiv").hide();
		$("#userDiv").hide();
		$("#parentDiv").show();
		$.ajax({
			type: 'POST',
			url: "<?//=base_url('conversation/parentCall')?>",
			dataType: "html",
			success: function(data) {
				$('#parentID').html(data);
			}
		});
  	} else {
		var id = $(this).val();
		$("#classDiv").hide();
		$("#stdDiv").hide();
		$("#adminDiv").hide();
		$("#parentDiv").hide();
		$("#teacherDiv").hide();
		$("#parentDiv").hide();
		$("#userDiv").show();
		$.ajax({
			type: 'POST',
			url: "<?//=base_url('conversation/userCall')?>",
			data : {id : id},
			dataType: "html",
			success: function(data) {
				$('#userID').html(data);
			}
		});
	}

</script>
<?php //} ?> -->

