<?php if(customCompute($logs) > 0 ) { ?>
    <div class="mt-4 mb-4 pb-3">
        <div class="leftContent" style="float: left;">
            <h4><b>All Student Logs</b></h4>
        </div>
        <div class="rightContent" style="float: right;">
            <a class="btn btn-sm btn-default waves-effect waves-light" target="_blank" href="<?php echo base_url(); ?>studentlog/exportExcel?studentID=<?php echo $filters['studentID']; ?>&eventID=<?php echo $filters['eventID']; ?>&startDate=<?php echo $filters['startDate']; ?>&endDate=<?php echo $filters['endDate']; ?>" title="Excel"><i class="fa fa-file-excel-o"></i> XLSX</a>
            <a class="btn btn-sm btn-default waves-effect waves-light" target="_blank" href="<?php echo base_url(); ?>studentlog/pdf?studentID=<?php echo $filters['studentID']; ?>&eventID=<?php echo $filters['eventID']; ?>&startDate=<?php echo $filters['startDate']; ?>&endDate=<?php echo $filters['endDate']; ?>" title="PDF"><i class="fa fa-file-pdf-o"> PDF Preview</i></a>
        </div>
	</div>

    <div class="sortable-list" style="margin-top:40px;">
        <ul id="logcontentwrapper" class="unit-wrapper">
            <?php
            $i = 1; 
            foreach($logs as $studentlogs){ ?>
                <li>
                    <div class="sortable-block sortable-blockunit">
                        <div class="sortable-header" role="button" data-toggle="collapse" href="#log<?php echo $i; ?>" aria-expanded="true">
                            <a class="btn btn-sm btn-link waves-effect waves-light" role="button" data-toggle="collapse" href="#log<?php echo $i; ?>" aria-expanded="true">
                                <i class="fa fa-angle-down"></i>
                            </a>
                                              
                            <div class="media-block media-block-alignCenter">
                                <figure class="avatar__figure">
                                    <span class="avatar__image">
                                        <?php     
                                            if($studentlogs[0]->photo != 'default.png'){
                                                $image = base_url() . 'uploads/images/56/' . $studentlogs[0]->photo;
                                        }else{
                                                $image = base_url() . 'uploads/images/default.png' ;
                                        }?>    
                                        <img src="<?php echo $image; ?>">
                                    </span>
                                </figure>
                                <div class="media-block-body">
                                    <a class="collapsed" role="button" data-toggle="collapse" href="#log<?php echo $i; ?>" aria-expanded="true">
                                        <h3 class="card-title mb-3 mb-lg-0"><?php echo $studentlogs[0]->classes; ?></h3>
                                        <div class="mt-2 "><?php echo $studentlogs[0]->name; ?></div>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="sortable-actions">
                            <?php
                                $totaltimespent = 0;
                                foreach($studentlogs as $log) {
                                    $totaltimespent = $totaltimespent + $log->second_spent;
                                }
                            ?>
                            <div>
                                Total time spent: <span id="totalTimeSpent1">
                                    <?php echo secondConversion($totaltimespent); ?></span> 
                            </div>
                        </div>

                    </div>  
                    <ul id="log<?php echo $i; ?>" class="chapter-wrapper <?php echo $i == 1?'in':'collapse' ?>" style="height: auto;">
                        <li>
                            <div class="attendee-lists">
                                <?php foreach($studentlogs as $log) { ?>
                                    <div class="attendee-lists-item">
                                        <div class="media-block">
                                            <div class="media-block-body">
                                                <div class="media-content">
                                                    <h4 class="title"><?php echo $log->event; ?><br></h4>
                                                </div>
                                                <div class="sortable-actions">
                                                    <p class="timerange" style="text-align:right;">
                                                        <?php echo secondConversion($log->second_spent); ?>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        </li>
                    </ul>    
                </li>
            <?php
             $i = $i + 1;
           } ?>
        </ul>
    </div>
<?php }else{
    echo '<p>Data not found.</p>';
}?>
