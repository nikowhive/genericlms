<style>
.chapter-wrapper {
    padding-bottom: 10px;
    margin-top: -14px;
}

.content-details {
    color: #000;
    margin: 0px;
}

.contentTitle{
	font-size: 15px;
}

.contentlist .contentchildlist {
  border-bottom: 1px solid #CCC;
}

.contentlist .contentchildlist:last-child {
  border: none;
}

</style>
<?php if(customCompute($course_units)) {
	?>
<div class="mt-4 mb-4 pb-3">
    <div class="leftContent" style="float: left;">
    </div>
    <div class="rightContent" style="float: right;">
        <a class="btn btn-sm btn-default waves-effect waves-light" style="color:#040303!important" target="_blank"
            href="<?php echo base_url(); ?>Coursesreport/exportExcel?classesID=<?php echo $classesID ?>&subjectID=<?php echo $subjectID ?>&courseID=<?php echo $courseID ?>&studentID=<?php echo $studentID ?>">
			<i class="fa fa-file-excel-o"></i> XLSX
		</a>
    </div>
</div>
<?php } ?>

<?php if(customCompute($course_units)) { ?>
<div class="sortable-list">
    <ul id="coursereport" class="coursereport-wrapper">
     <?php $i = 1; foreach($course_units as $course_unit)  { 
	    foreach($course_unit->chapters as $chapter) { ?>
        <li>
            <div class="sortable-block sortable-blockunit">
                <div class="sortable-header collapsed" role="button" data-toggle="collapse"
                    href="#chapter<?php echo $chapter->id?>"
                    onclick="storeSortableData('chapter<?php echo $chapter->id?>')" aria-expanded="true">
                    <a class="btn btn-sm btn-link collapsed" role="button" data-toggle="collapse"
                        href="#chapter<?php echo $chapter->id?>" aria-expanded="true">
                        <i class="fa fa-angle-down"></i>
                    </a>
                    <h3 class="sortable-title">
                        <small><?php echo $chapter->unit ?> : <?php echo $chapter->chapter_name ?></small>
                        <a class="" role="button" data-toggle="collapse" href="#chapter<?php echo $chapter->id?>"
                            aria-expanded="true">
                            <p class="chapterTitle">
                                Total Coverage: 110 &nbsp;
                                Covered: 0
                            </p>
                        </a>
                    </h3>
                </div>
            </div>
            <ul id="chapter<?php echo $chapter->id?>" class="collapse chapter-wrapper">

                <?php if($chapter->content_count > 0): ?>
                <li class="contentlist">
                    <div class="sortable-block in sortable-block--shown" style="padding: 0px 16px 0px 16px;background-color: var(--themeColorLight);">
                        <div class="sortable-header in sortable-block--shown">
                            <h3 class="sortable-title sort-type">
                                <p class="content-details">
									<i class="fa fa-file-word-o" aria-hidden="true"></i> Contents - <?php echo $chapter->content_count; ?>
								</p>
                            </h3>
                        </div>
                    </div>
                    <?php foreach($chapter->contents as $content): ?>
						<div class="sortable-block in sortable-block--shown contentchildlist" style="padding: 10px 16px 10px 16px;">
							<div class="sortable-header in sortable-block--shown">
								<div class="sortable-title sort-type">
									<div class="contentTitle">
										<?php echo $content['content_title']; ?>
										<span style="float:right;font-size:14px">
											<?php echo $content['exists'] . ' out of ' . $content['percentage_coverage']; ?>
										</span>
									</div>
								</div>
							</div>
						</div>
                    <?php endforeach; ?>
                </li>
                <?php endif; ?>

                <?php if($chapter->quizz_count > 0): ?>
                <li class="contentlist">
                    <div class="sortable-block in sortable-block--shown" style="padding: 0px 16px 0px 16px;background-color: var(--themeColorLight);">
                        <div class="sortable-header in sortable-block--shown">
                            <h3 class="sortable-title sort-type">
                                <p class="content-details">
									<i class="fa fa-puzzle-piece" aria-hidden="true"></i> Quizzes - <?php echo $chapter->quizz_count; ?>
								</p>
                            </h3>
                        </div>
                    </div>


                    <?php foreach($chapter->quizzes as $quiz): ?>
						<div class="sortable-block in sortable-block--shown contentchildlist" style="padding: 10px 16px 10px 16px;">
							<div class="sortable-header in sortable-block--shown">
								<div class="sortable-title sort-type">
									<div class="contentTitle">
										<?php echo $quiz['quiz_name']; ?> 
										<span style="float:right;font-size:14px;text-align:right;">
										    <?php echo $quiz['percentage_coverage'].'%<br>'.$quiz['scored']; ?>
										</span>
									</div>
								</div>
							</div>
						</div>
                    <?php endforeach; ?>
                </li>
                <?php endif; ?>

                <?php if($chapter->homework_count > 0): ?>
                <li class="contentlist">
                    <div class="sortable-block in sortable-block--shown" style="padding: 0px 16px 0px 16px;background-color: var(--themeColorLight);">
                        <div class="sortable-header in sortable-block--shown">
                            <h3 class="sortable-title sort-type">
                                <p class="content-details">
									 <i class="fa fa-copy" aria-hidden="true"></i> Homeworks - <?php echo $chapter->homework_count; ?>
                                </p>
                            </h3>
                        </div>
                    </div>

                    <?php foreach($chapter->homeworks as $homework): ?>
						<div class="sortable-block in sortable-block--shown contentchildlist" style="padding: 10px 16px 10px 16px;">
							<div class="sortable-header in sortable-block--shown">
								<div class="sortable-title sort-type">
									<div class="contentTitle">
										<div style="float:left;">
											<?php echo $homework->title; ?><br>
												<small>
													<span class="label <?php echo $homework->answer_status_label; ?>">
														<?php echo $homework->answer_status; ?>
													</span>
												</small>
										</div>
										<div style="float:right;font-size:14px;font-size: 12px;text-align: right;">
											<span>
												<?php echo $homework->date; ?>
											</span>
										</div>
									</div>
								</div>
							</div>
						</div>
                    <?php endforeach; ?>
                </li>
                <?php endif; ?>

                <?php if($chapter->classwork_count > 0): ?>
                <li class="contentlist">
                    <div class="sortable-block in sortable-block--shown" style="padding: 0px 16px 0px 16px;background-color: var(--themeColorLight);">
                        <div class="sortable-header in sortable-block--shown">
                            <h3 class="sortable-title sort-type">
                                <p class="content-details">
									 <i class="fa fa-file-text-o" aria-hidden="true"></i> Classworks - <?php echo $chapter->classwork_count; ?>
								</p>
                            </h3>
                        </div>
                    </div>
                    <?php foreach($chapter->classworks as $classwork): ?>
                    <div class="sortable-block in sortable-block--shown contentchildlist" style="padding: 10px 16px 10px 16px;">
                        <div class="sortable-header in sortable-block--shown">
                            <div class="sortable-title sort-type">
							<div class="contentTitle">
									<div style="float:left;">
										<?php echo $classwork->title; ?><br>
										    <small>
												<span class="label <?php echo $classwork->answer_status_label; ?>">
													<?php echo $classwork->answer_status; ?>
												</span>
					                        </small>
					                </div>
									<div style="float:right;font-size:14px;font-size: 12px;text-align: right;">
										<span>
											<?php echo $classwork->date; ?>
										</span>
					                </div>
								</div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </li>
                <?php endif; ?>

                <?php if($chapter->assignment_count > 0): ?>
                <li class="contentlist">
                    <div class="sortable-block in sortable-block--shown" style="padding: 0px 16px 0px 16px;background-color: var(--themeColorLight);">
                        <div class="sortable-header in sortable-block--shown">
                            <h3 class="sortable-title sort-type">
                                <p class="content-details"> 
									<i class="fa fa-book" aria-hidden="true"></i> Assignments - <?php echo $chapter->assignment_count; ?>
								</p>
                            </h3>
                        </div>
                    </div>
                    <?php foreach($chapter->assignments as $assignment): ?>
                    <div class="sortable-block in sortable-block--shown contentchildlist" style="padding: 10px 16px 10px 16px;">
                        <div class="sortable-header in sortable-block--shown">
                            <div class="sortable-title sort-type">
							<div class="contentTitle">
									<div style="float:left;">
										<?php echo $assignment->title; ?><br>
										    <small>
												<span class="label <?php echo $assignment->answer_status_label; ?>">
													<?php echo $assignment->answer_status; ?>
												</span>
					                        </small>
					                </div>
								    <div style="float:right;font-size:14px;font-size: 12px;text-align: right;">
										<span>
											<?php echo $assignment->date; ?>
										</span>
					                </div>
								</div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
        </li>
                <?php endif; ?>
        </ul>

            <?php }} ?>
    </ul>
</div>
<?php }else{ ?>
<div class="callout callout-danger">
    <p><b class="text-info"><?=$this->lang->line('onlineexamreport_data_not_found')?></b></p>
</div>
<?php } ?>