<div class="form-group" id="userContainer" >
    <!-- <label for="users" class="col-sm-2 control-label">
                    Recipient Users:
    </label> -->
    <div class="col-md-12">
        <!-- <div class="container"> -->
            <div class="mt-3 mb-3">
                <div class="user-selection">
                    <h4 class="ml-3">Select Employee Type</h4>
                    <div class="user-selection-header">
                        <input type="hidden" id="bulkEmployeeType" value="<?php echo $bulkEmployeeTypeValue; ?>" name="bulkEmployeeType"/>
                        <input type="hidden" id="bulkClass" value="<?php echo $bulkClassValue; ?>" name="bulkClass"/>
                        <input type="hidden" id="bulkEmployee" value="<?php echo $bulkEmployeeValue; ?>" name="bulkEmployee"/>
                        <div class="list-inline">
                            <?php foreach($usersData as $userData){ ?>
                                <div class="checkbox--pill ">
                                    <input type="checkbox" class="types" <?php echo in_array($userData['type'],$bulkEmployeeType)?'checked':'' ?> id="types-<?php echo $userData['type']; ?>" 
                                    name="types" data-count="<?php echo $userData['count'];?>"
                                     value="<?php echo $userData['type']; ?>"
                                      <?php echo ($bulkEmployeeTypeValue == 0 && $userData['type'] != 0)?'disabled':''; ?>/>
                                    <label for="types-<?php echo $userData['type']; ?>" class="pill"><?php echo $userData['name'].' | '.$userData['count']; ?></label>
                                </div>
                            <?php } ?>
                        </div>
                        <div class="switch-wrapper">
                            <div class="onoffswitch-small">
                                <input type="checkbox" name="send_to_parents" class="onoffswitch-small-checkbox" id="course1" <?php echo $send_to_parents == 'on'?'checked':''; ?>>
                                <label class="onoffswitch-small-label" for="course1">
                                <span class="onoffswitch-small-inner"></span>
                                <span class="onoffswitch-small-switch"></span>
                                </label>
                            </div>
                            <label for="course1" class="switch-label">Send to Parents</label>
                        </div>
                    </div>
                
                    <div class="user-selection-body">
                        <div id="classwrapper">
                            <p><label for="">Classes</label></p>
                            <div class="list-inline">
                                <div class="checkbox--pill">
                                        <input type="checkbox" data-count="<?php echo $totalStudent; ?>"id="class0" <?php echo in_array(0,$bulkClass)?'checked':'' ?> value="0" name="classes" class="classes"/>
                                        <label for="class0" class="pill">All|<?php echo $totalStudent; ?></label>
                                </div>
                                <?php foreach($classes as $classe){ ?>
                                    <div class="checkbox--pill ">
                                        <input type="checkbox" name="classes" data-count="<?php echo $classe->no;?>"
                                         class="classes" id="class<?php echo $classe->classesID; ?>"
                                          value="<?php echo $classe->classesID; ?>"
                                           <?php echo in_array($classe->classesID,$bulkClass)?'checked':'' ?>
                                           <?php echo ($bulkClassValue == 0)?'disabled':''; ?> />
                                        <label for="class<?php echo $classe->classesID; ?>" class="pill"><?php echo $classe->classes.'|'.$classe->no; ?></label>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                
                        <div id="employeewrapper">
                            <br>
                            <p><label for="">Employee</label></p>
                            <div class="list-inline">
                                <?php foreach($employees as $employee){ 
                                    if($employee['no'] != 0){?>
                                    <div class="checkbox--pill ">
                                        <input type="checkbox" class="employees" data-count="<?php echo $employee['no']; ?>" name="employees" 
                                        id="employee<?php echo $employee['usertypeID']; ?>"
                                         value="<?php echo $employee['usertypeID']; ?>"
                                          <?php echo in_array($employee['usertypeID'],$bulkEmployee)?'checked':'' ?> 
                                          <?php echo ($bulkEmployeeValue == 0 && $employee['usertypeID'] != 0)?'disabled':''; ?>
                                          />
                                        <label for="employee<?php echo $employee['usertypeID']; ?>" class="pill"><?php echo $employee['usertype'].'|'.$employee['no']; ?></label>
                                    </div>
                                <?php }} ?>
                            </div>
                        </div>

                        <div class="user-selection-search mt-3 mb-3">
                            <div class="row row-flex align-items-center">
                                <div class="col-md-8">
                                    <div class="search-box" id="filterUsers">
                                        <input type="search" placeholder="Type person's name to search" name="search" id="searchuser2" class="form-control" value="<?php echo $search;?>" >
                                        <button type="submit" id="searchUser" class="btn btn-success">Search</button>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                </div>
                            </div>
                        </div>
                        <div class="user-selection-search mt-3 mb-3">
                            <div class="row row-flex align-items-center">
                                <div class="col-md-12">
                                    <h4 class="mt-4" id="totalSelectedUsers"></h4>
                                </div>
                            </div>
                        </div>
                        <div class="usersWrapper"> </div>
                    </div>
                </div>
            </div>
        <!-- </div> -->
    </div>
</div>

