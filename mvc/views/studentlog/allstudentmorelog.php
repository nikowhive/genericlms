<?php if(customCompute($logs) > 0 ) { ?>
    <!-- <div class="sortable-list" style="margin-top:40px;">
        <ul id="unit" class="unit-wrapper"> -->
            <?php
            $i = 1; 
            foreach($logs as $studentlogs){ ?>
                <li>
                    <div class="sortable-block sortable-blockunit">
                        <div class="sortable-header" role="button" data-toggle="collapse" href="#morelog<?php echo $page.$i; ?>" aria-expanded="true">
                            <a class="btn btn-sm btn-link waves-effect waves-light" role="button" data-toggle="collapse" href="#morelog<?php echo $page.$i; ?>" aria-expanded="true">
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
                                    <h3 class="card-title mb-3 mb-lg-0"><?php echo $studentlogs[0]->classes; ?></h3>
                                    <div class="mt-2 "><?php echo $studentlogs[0]->name; ?></div>
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
                                    <?php echo secondConversion($totaltimespent); ?>
                            </div>
                        </div>
                    </div>  
                    <ul id="morelog<?php echo $page.$i; ?>" class="chapter-wrapper collapse" style="height: auto;">
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
        <!-- </ul>
    </div> -->
<?php }?>
