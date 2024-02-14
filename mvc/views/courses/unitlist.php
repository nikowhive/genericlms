
<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa fa-sitemap"></i> Unit List</h3>

        <ol class="breadcrumb">
            
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-12">
                
                <div id="hide-table">
                    <table id="example1" class="table table-striped table-bordered table-hover dataTable no-footer">
                        <thead>
                            <tr>
                                <?php if(permissionChecker('courses_edit')) { ?>
                                    <th class="col-lg-2">Unit Name</th>
                                    <th class="col-lg-2">Status</th>
                                    <th class="col-lg-3">Action</th>
                                <?php } elseif($usertypeID == '3') { ?>
                                    <th>Units</th>
                                <?php } ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(customCompute($course_units)) {$i = 1; foreach($course_units as $course_unit) { ?>
                                <tr>
                                <?php if(permissionChecker('courses_edit')) { ?>
                                    <td data-title="Course Name">
                                        <?php echo $course_unit->unit_name; ?>
                                    </td>
                                    <td data-title="Course Published">
                                        <form method="post" action="<?php echo base_url() ?>courses/postChangeUnitStatus/<?php echo $course_unit->id.'/'.$course_unitid; ?>">
                                            <div class="onoffswitch-small">
                                                <input type="checkbox"  class="onoffswitch-small-checkbox" name="published" <?php if($course_unit->published == '1') { ?> checked='checked' <?php } if($course_unit->published == '1')  echo "value='2'";  else echo "value='1'"; ?>>
                                                <label for="myonoffswitch" class="onoffswitch-small-label">
                                                    <span class="onoffswitch-small-inner"></span>
                                                    <span class="onoffswitch-small-switch"></span>
                                                </label>
                                            </div>
                                        </form>
                                    </td>
                                    
                                    <td>
                                        <?php echo anchor('courses/chapterlist/'.$course_unit->id, "Add Chapter", "class='btn btn-primary btn-xs mrg' data-placement='top' data-toggle='tooltip' data-original-title='Add Chapter'"); ?>
                                    </td>
                                <?php } ?>
                                </tr>
                                <?php if(permissionChecker('courses_view') && $usertypeID != '1' && $usertypeID != '2') {  ?>
                                <tr>
                                    <td>
                                    <p style="margin-top: 10px;">Unit: <b><?= $course_unit->unit_name ?></b></p>
                                    <hr/>
                                        <?php if(customCompute($course_unit->chapters)) { $i = 1; foreach($course_unit->chapters as $index =>$chapters) { ?>
                                            <div style="margin-left: 30px;">
                                                <?= $index + 1 .")  <b>".$chapters->chapter_name. "</b>"; ?>
                                                <?php if($chapters->content_exists) { ?>
                                                    <?php if($usertypeID == '3') { ?>
                                                        <span style="float: right; margin-right: 30px;"><?php echo anchor('courses/content/'.$chapters->id, "View Content", "class='btn btn-primary btn-xs mrg' data-placement='top' data-toggle='tooltip' data-original-title='View Content'"); ?></span>
                                                    <?php } elseif ($usertypeID == '4') {?>
                                                        <input style="float: right; margin-right: 30px;" class="btn btn-success btn-sm displaycontent" style="margin-bottom: 10px" type="button" value="View Contents" data-chapterid="<?php echo $chapters->id?>" data-toggle="modal" data-target="#displaycontent">
												        <input style="float: right; margin-right: 30px;" class="btn btn-success btn-sm displayquiz" style="margin-bottom: 10px" type="button" value="View Quizzes" data-chapterid="<?php echo $chapters->id?>" data-toggle="modal" data-target="#displayquiz">

                                                        <!-- <span style="float: right; margin-right: 30px;" class='btn btn-primary btn-xs mrg'>View Contents</span>
                                                        <span style="float: right; margin-right: 30px;" class='btn btn-primary btn-xs mrg'>View Quizzes</span> -->
                                                    <?php } ?>
                                                    <span style="float: right; margin-right: 30px; color: green"><?= $chapters->covered ?> of <?= $chapters->total_coverage ?> completed</span>
                                                <?php } ?>
                                            </div>
                                            <?php echo count($course_unit->chapters) - 1 == $index ? '' : "<hr/>" ?>
                                        <?php } ?>
                                    </td>
                                </tr>
                                <?php }} ?>
                            <?php $i++; }} ?>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="displaycontent">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<h4 class="modal-title">View Contents</h4>
			</div>
			<div class="modal-body">
				<table class="table table-striped table-bordered table-hover dataTable no-footer">
					<tbody id="content_id"></tbody>
				</table>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="displayquiz">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<h4 class="modal-title">View Quizzes</h4>
			</div>
			<div class="modal-body">
			<table class="table table-striped table-bordered table-hover dataTable no-footer">
				<tbody id="quiz_id"></tbody>
			</table>
			</div>
		</div>
	</div>
</div>

<script>
    $('.onoffswitch-small').click(function(e) {
        $(this).parent().submit();  
    })
</script>
<script>
$(document).on("click", ".displaycontent", function () {
     var chapter_id = $(this).data('chapterid');
	 var student_id = "<?php echo htmlentities(escapeString($this->uri->segment(4))); ?>"
	$.ajax({
		type: 'POST',
		url: "<?=base_url('courses/getContent')?>",
		data: {'chapter_id' : chapter_id, 'student_id': student_id},
		dataType: "html",
		success: function(data) {
			$('#content_id').html(data);
		}
	});

});


$(document).on("click", ".displayquiz", function () {
     var chapter_id = $(this).data('chapterid');
	 var student_id = "<?php echo htmlentities(escapeString($this->uri->segment(4))); ?>"

	$.ajax({
		type: 'POST',
		url: "<?=base_url('courses/getQuiz')?>",
		data: {'chapter_id' : chapter_id, 'student_id': student_id},
		dataType: "html",
		success: function(data) {
			$('#quiz_id').html(data);
		}
	});

});
</script>