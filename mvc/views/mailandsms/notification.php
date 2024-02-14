<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa icon-mailandsms"></i> <?=$this->lang->line('panel_title')?></h3>
        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li><a href="<?=base_url("mailandsms/index")?>"> <?=$this->lang->line('menu_mailandsms')?></a></li>
            <li class="active"> <?=$this->lang->line('menu_add')?> <?=$this->lang->line('menu_mailandsms')?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-12">
                <div class="row">
                    <div class="col-sm-10">
                    <p>Please select recipient type:</p>
                      <input type="radio" id="student" name="recipient" value="student">
                      <label for="student">Student</label>
                      <input type="radio" id="staff" name="recipient" value="staff">
                      <label for="staff">Staff</label>
                      <input type="radio" id="parent" name="recipient" value="parent">
                      <label for="parent">Parent</label>
                    </div>
                    </div>
                    <div class="row" id="studentlist">
                    <div class="col-sm-6">
                        <select id="demo1" data-max="" multiple="multiple" style="" class="demo1">
                            <?php foreach($classes as $class){?>
                             <option value="<?php echo $class->classesID?>" id="<?php echo 'class'.$class->classesID?>" ><?php echo $class->classes?></option>
    
                            <?php }?>
                         </select>
                    </div>
                    <div class="col-sm-6" id="studentlistId">

                    </div>
                    
                    </div>
                    <div class="row" id="stafflist">
                    <div class="col-sm-6">
                        <select id="demo2" data-max="" multiple="multiple" style="" class="demo2">
                            <?php foreach($usertypes as $usertype){?>
                             <option value="<?php echo $usertype->usertypeID?>"><?php echo $usertype->usertype?></option>
    
                            <?php }?>
                         </select>
                    </div>
                    <div class="col-sm-6" id="stafflistId">

                    </div>
                
                    </div>
                    <div class="row" id="parentlist">
                    <div class="col-sm-6">
                        <select id="demo3" data-max="" multiple="multiple" style="">
                            <?php foreach($classes as $class){?>
                             <option value="<?php echo $class->classesID?>"><?php echo $class->classes?></option>
    
                            <?php }?>
                         </select>
                    </div>
                    <div class="col-sm-6" id="parentlistId">

                    </div>
                
                    </div>
                </div>      
            </div>
        </div>
    </div>

</div><!-- /.box -->
<link href="https://www.jqueryscript.net/css/jquerysctipttop.css" rel="stylesheet" type="text/css">
<link href="<?php echo base_url('assets/easySelect/easySelectStyle.css'); ?>" rel="stylesheet">
<style>
 .box .box-body{
    height: 700px;
 }        
</style>
<script type="text/javascript" src="<?php echo base_url('assets/easySelect/easySelect.js'); ?>"></script>
<script type="text/javascript">
    $('#stafflist').hide();
    $('#studentlist').hide();
    $('#parentlist').hide();

    $('#student').click(function(){
        $('#stafflist').hide();
        $('#parentlist').hide();
        $('#studentlist').show();
    });
    $('#staff').click(function(){
        $('#stafflist').show();
        $('#studentlist').hide();
        $('#parentlist').hide();
    });

    $('#parent').click(function(){
        $('#stafflist').hide();
        $('#studentlist').hide();
        $('#parentlist').show();
    });

    $("#demo1").easySelect({
         buttons: true,
         search: true,
         placeholder: 'Choose class',
         placeholderColor: 'green',
         selectColor: 'lila',
         itemTitle: 'Color selected',
         showEachItem: true,
         width: '50%',
         dropdownMaxHeight: '450px',
         id: 'demo1',
     })
    $("#demo2").easySelect({
         buttons: true,
         search: true,
         placeholder: 'Choose usertype',
         placeholderColor: 'green',
         selectColor: 'lila',
         itemTitle: 'Color selected',
         showEachItem: true,
         width: '50%',
         dropdownMaxHeight: '450px',
         id: 'demo2',
     })
    $("#demo3").easySelect({
         buttons: true,
         search: true,
         placeholder: 'Choose class',
         placeholderColor: 'green',
         selectColor: 'lila',
         itemTitle: 'Color selected',
         showEachItem: true,
         width: '50%',
         dropdownMaxHeight: '450px',
         id: 'demo3',
     })

    $(".mulpitply_checkbox_style1").click(function(){
        $.ajax({
            type: 'POST',
            url: "<?=base_url('mailandsms/getStudent')?>",
            data: "classesID=" + $(this).val(),
            dataType: "html",
            success: function(data) {
               $('#studentlistId').html(data);
            }
        });
    })

    $(".mulpitply_checkbox_style2").click(function(){
        $.ajax({
            type: 'POST',
            url: "<?=base_url('mailandsms/getStaff')?>",
            data: "roleID=" + $(this).val(),
            dataType: "html",
            success: function(data) {
               $('#stafflistId').html(data);
            }
        });
    })

    $(".mulpitply_checkbox_style3").click(function(){
        $.ajax({
            type: 'POST',
            url: "<?=base_url('mailandsms/getStudent')?>",
            data: "classesID=" + $(this).val(),
            dataType: "html",
            success: function(data) {
               $('#parentlistId').html(data);
            }
        });
    })
</script>