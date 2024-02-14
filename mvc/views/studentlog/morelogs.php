<?php if(customCompute($logs) > 0 ) { ?>
    <!-- <div class="card mt-3 card--attendance">
        <div class="card-body p-0">
            <div class="attendee-lists"> -->
                <?php 
                $total = 0;
                foreach($logs as $log) {
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
                $total = $total + $log->second_spent;
                } ?>
                <input type="hidden" value="<?php echo $total; ?>" id="moreTotal<?php echo $page; ?>"/>
            <!-- </div>
        </div>
    </div> -->
<?php }else{ ?>
    <input type="hidden" value="0" id="moreTotal<?php echo $page; ?>"/>
<?php } ?>
