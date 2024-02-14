<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
            <?php if(customCompute($results)){
                // dd($selectedUsers);

            $count = 1;    
            foreach($results as $key=>$result){ 
                $keycheck = in_array($key,$keyselectedUsers)?'checked':'';
              ?>
            <div class="panel panel-default">
                <div class="panel-heading" role="tab" id="heading<?php echo $key; ?>" >
                        <div class="form-check  ">
                            <input class="form-check-input allcheck"  <?php echo $keycheck; ?> type="checkbox" value="<?php echo $key; ?>" id="flexCheck<?php echo $key; ?>">
                            <label class="form-check-label mb-0" for="flexCheck<?php echo $key; ?>">
                                <b><?php echo $key; ?></b>
                            </label>
                        </div>
                        <div data-toggle="collapse" role="button" data-parent="#accordion" data-target="#collapse<?php echo $key; ?>" aria-expanded="true" aria-controls="collapse<?php echo $key; ?>">
                            <span class="mr-2">Total:<?php echo count($result); ?></span>
                            <a href="javascript:;" class="icon-round" role="button" data-parent="#accordion" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                            <i class="fa fa-angle-down"></i>
                            </a>
                        </div>
                </div>
                <div id="collapse<?php echo $key; ?>" class="panel-collapse collapse <?php echo $count == 1?'in':''; ?>" role="tabpanel" aria-labelledby="heading<?php echo $key; ?>">
                    <div class="panel-body">
                            <?php 
                            foreach($result as $r): 
                            $check = in_array($r['ID'].$r['usertypeID'],$selectedUsers)?'checked':'';
                            ?>
                            <div class="user-selection-list">
                                <div class="user-selection-name">
                                <div class="form-check  ">
                                    <input class="form-check-input flexCheck<?php echo $key; ?> chkbox" type="checkbox" name="users[]"  <?php echo $check; ?> value="<?php echo $r['ID'].$r['usertypeID'] ?>" id="user<?php echo $r['ID'].$r['usertypeID'] ?>">
                                    <label class="form-check-label mb-0" for="user<?php echo $r['ID'].$r['usertypeID'] ?>">
                                        <b><?php echo $r['name']; ?></b>
                                    </label>
                                </div>
                                </div>
                                
                                <div class="user-selection-rollnreg">
                                <?php if($r['usertypeID'] == 3){ 
                                    echo $r['roll'];
                                    echo '<span class="reg">'.$r['registerNO']?'|'.$r['registerNO']:$r['registerNO'].'</span>';
                                 }elseif($key == 'Teacher'){
                                    echo $r['designation'];
                                 } ?>
                                </div>
                                <div class="user-selection-other">
                                    <em>
                                    <?php if($r['usertypeID'] == 3){ 
                                      echo $r['parentName'];
                                      echo $r['parentPhone']?' - '.$r['parentPhone']:$r['parentPhone'];
                                    }else{
                                        if($r['usertypeID'] == 4){
                                            echo $r['studentName'];
                                        }
                                        echo '&nbsp;&nbsp;'.$r['email'];
                                        echo '<span class="reg">'.$r['phone']?'|'.$r['phone']:$r['phone'].'</span>';
                                    } ?>
                                    </em>
                                </div>
                            </div>
                            <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <?php $count++; } } ?>
        </div>

<script>
 $('.allcheck').click(function(){
        var id = $(this).attr('id');
        if($(this).is(':checked')){
            $('.'+id).prop("checked", true);
        }else{
            $('.'+id).prop("checked", false);
        }
        totalUsers();
 });

 $('.chkbox').click(function(){
    totalUsers();
 });    
</script>
        