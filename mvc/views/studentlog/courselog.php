<?php if(customCompute($logs) > 0 ) { ?>

    <div class="mt-4 mb-4 pb-3">
        <div class="leftContent" style="float: left;">
            <h4><b>Student Logs</b></h4>
        </div>
        <div class="rightContent" style="float: right;">
            <a class="btn btn-sm btn-default waves-effect waves-light" target="_blank" href="<?php echo base_url(); ?>studentlog/exportExcel?studentID=<?php echo $filters['studentID']; ?>&eventID=<?php echo $filters['eventID']; ?>&startDate=<?php echo $filters['startDate']; ?>&endDate=<?php echo $filters['endDate']; ?>" title="Excel"><i class="fa fa-file-excel-o"></i> XLSX</a>
            <a class="btn btn-sm btn-default waves-effect waves-light" target="_blank" href="<?php echo base_url(); ?>studentlog/pdf?studentID=<?php echo $filters['studentID']; ?>&eventID=<?php echo $filters['eventID']; ?>&startDate=<?php echo $filters['startDate']; ?>&endDate=<?php echo $filters['endDate']; ?>" title="PDF"><i class="fa fa-file-pdf-o"> PDF Preview</i></a>
        </div>
	</div>
    <div class="card mt-3 card--attendance">
        <div class="card-header">
            <div class="row row-md-flex">
                <div class="col-md-5">
					<div class="media-block media-block-alignCenter">
						<figure class="avatar__figure">
                            <span class="avatar__image">
                            <?php     
                                if($student->photo != 'default.png'){
                                     $image = base_url() . 'uploads/images/56/' . $student->photo;
                                     $alt = 'a';
                                }else{
                                    $image = base_url() . 'uploads/images/default.png' ;
                                    $alt = 'b';
                                }
                            ?>    
                            <img src="<?php echo $image; ?>" alt="<?php echo $alt; ?>">
                            </span>
						</figure>
						<div class="media-block-body">
                            <h3 class="card-title mb-3 mb-lg-0">
                            <?php echo $class->classes; ?> <span class="pill pill--flat pill--sm"><?php echo $section->section; ?></span></h3>
                            <div class="mt-2 "><?php echo $student->name; ?></div>
						</div>
					</div>
                        
                </div>
                <div class="col-md-4 attendance-stats">
                <?php
                    $totaltimespent = 0;
                    foreach($logs as $log) {
                        $totaltimespent = $totaltimespent + $log->second_spent;
                    }
                ?>
                    <div>Total time spent:
                         <span id="totalTimeSpenthtml">
                            <?php echo secondConversion($totaltimespent); ?>
                        </span>
                        <input type="hidden" id="totalTimeSpent" value="<?php echo $totaltimespent;?>"/>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="attendee-lists" id="logcontentwrapper">
                <?php foreach($logs as $log) {
                    ?>
                    <div class="attendee-lists-item">
                        <div class="media-block">
                            <div class="media-block-body">
                                <div class="media-content">
                                    <h4 class="title">
                                        <?php echo $log->event; ?>
                                        <br>
                                        <em class="rollnumber"><?php echo $log->remarks; ?></em>
                                    </h4>
                                </div>
                                       
                                <div class="sortable-actions">
                                    <p class="timerange" style="text-align:right;">
                                        <b>Start:</b> <?php echo $log->start_datetime; ?> 
                                        <?php if($log->end_datetime){ 
                                            echo '<br>';
                                            echo '<b>End:</b> '.$log->end_datetime;
                                            echo '<br>';
                                            echo $log->time_spent;
                                        } ?>
                                       
                                    </p>
                                </div>
					        </div>
                        </div>
                    </div>
                <?php 
               
                } ?>
            </div>
        </div>
    </div>
<?php }else{
    echo '<p>Data not found.</p>';
}?>
