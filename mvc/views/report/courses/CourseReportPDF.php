<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
</head>
<body>
    <div class="col-sm-12">
        <?=reportheader($siteinfos, $schoolyearsessionobj, true)?>
    </div>
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-12">
            <?php if(customCompute($course_units)) { ?>
	                    <div id="hide-table">
                        <div class="box-header bg-gray">
                            <p style="font-size:16px;margin-bottom:10px;"><b>Name:</b> <?php echo $student->name; ?> <b>class:</b> <?php echo $class->classes; ?><b> Section:</b> <?php echo $section->section; ?></p>
                        </div>
	                        <table class="table table-striped table-bordered table-hover dataTable no-footer">
	                            <thead>
	                                <tr>
	                                    <th>#</th>
	                                    <th>Unit Name</th>
	                                    <th>Chapter Name</th>
	                                    <th>Total Coverage</th>
	                                    <th>Covered</th>
                                        <th>Contents</th>
                                        <th>Quizzes</th>
	                                </tr>
	                            </thead>

	                            <?php $i = 1; foreach($course_units as $course_unit)  { ?>
									<?php foreach($course_unit->chapters as $chapter) { ?>
										<tr>
											<td data-title="#"><?=$i?></td>
											<td><?php echo $chapter->unit ?></td>
											<td><?php echo $chapter->chapter_name ?></td>
											<td><?php echo isset($chapter->total_coverage) ? $chapter->total_coverage: 0 ?></td>
											<td><?php echo isset($chapter->covered) ? $chapter->covered: 0 ?></td>
                                            <td>
                                                <?php 
                                                if(isset($chapter->contents)){ ?>
                                                    <ul>    
                                                        <?php foreach($chapter->contents as $content){ ?>
                                                            <li><?php echo $content['content_title'].' - '.$content['exists'] . ' out of ' . $content['percentage_coverage'] ?><br></li>
                                                        <?php } ?>
                                                    </ul>
                                              <?php } ?>    
                                            </td>
                                            <td>
                                                <?php 
                                                if(isset($chapter->quizzes)){ ?>
                                                    <ul>    
                                                        <?php foreach($chapter->quizzes as $quizz){ ?>
                                                            <li><?php echo $quizz['quiz_name'].' - '.$quizz['percentage_coverage'].'% - '.$quizz['scored'] ?></li>
                                                        <?php } ?>
                                                    </ul>
                                              <?php } ?>    
                                            </td>
										</tr>
									<?php $i++; } ?>
								<?php } ?>
                                        
	                        </table>
	                    </div>
                    <?php } ?>
            </div>
        </div><!-- row -->
    </div><!-- Body -->
   
    <div class="col-sm-12">
        <?=reportfooter($siteinfos, $schoolyearsessionobj, true)?>
    </div>
       
</body>
</html>