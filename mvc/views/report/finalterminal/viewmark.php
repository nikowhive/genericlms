<!DOCTYPE html>
<html lang="en">
<head> <meta charset="UTF-8"></head>
<style>
  table tr th {
      text-align: center;
  }
</style>
<body>
<div class="box">
    <div class="box-header bg-gray">
            <h3 class="box-title text-navy"><i class="fa fa-clipboard"></i>
                <?=$this->lang->line('terminalreport_report_for')?> <?=$this->lang->line('terminalreport_terminal')?> -
                <?=$examName?> Class: <?=$class->classes;?>
            </h3>
    </div>
    <div class="box-body" style="margin-bottom: 50px;">
        <div class="row">
            <div class="col-sm-12">
                <div style="width: 1100px;overflow-x:scroll;">
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                            <?php if(customCompute($rows)){
                                foreach($rows as $k=>$row){ ?>
                                    <tr>
                                        <?php foreach($row as $key=>$value){
                                            if($k == 0 && $key == 0){
                                                $col = 4;
                                            }elseif($k == 0 && $key <= $subject_count){
                                                    $col = $examCount + 2;
                                            }else{
                                                    $col = 1;
                                            }
                                            ?>
                                            <th colspan="<?php echo $col; ?>"><?php echo $value; ?></th>
                                        <?php } ?>
                                    </tr>
                            <?php }} ?>
                        </thead>
                        <tbody>
                            <?php if(customCompute($bodyrows)){
                                    foreach($bodyrows as $bodyrow){ ?>
                                        <tr>
                                            <?php foreach($bodyrow as $key=>$value){ ?>
                                                <td><?php echo $value; ?></td>
                                            <?php } ?>
                                        </tr>
                            <?php }} ?>
                        </tbody>
                            
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
