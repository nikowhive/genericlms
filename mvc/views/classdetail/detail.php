
<style>
.blockWrapper{
    padding: 10px;
    /* border: 3px solid #ccc; */
    margin-bottom: 10px;
    border-radius: 10px;
    background-color: #fff;
    box-shadow: 0px 3px 5px rgb(0 0 0 / 10%);
    position: relative;
    --padding-y: 16px;
    --padding-x: 16px;
}

.sticky {
  position: fixed;
  margin-top: 620px;
  top: 0;
  z-index: 1;
  right: 23px;
  /* --padding-x:150px; */
  
}
/* @media (max-width: 991px){
    .sticky{
        position: fixed;
        margin-top: 620px;
        top: 0;
        z-index: 1;
        right: 23px;
    }
}
 @media (min-width: 992px){
    .sticky{
        position: fixed;
        margin-top: 620px;
        top: 0;
        z-index: 1;
        right: 23px;
    }
} */
.fixed_button{
position: fixed;
bottom: 10px;
right: 23px;
/* left: calc(50% + 210px); */
z-index: 99;
 }

.box-body{
    margin-top:20px
}

.removeFaq, .deleteBlock{
    float: right;
}

.faqList ul li{
    padding: 5px;
}
#enrollmentWrapper label{
   margin: 5px; 
}

#enrollmentWrapper label a .fa {
   color: white; 
}

.faqList {
    height: 200px;
    overflow-y: scroll;
}

</style>

<div class="box-body">
    <form  id="classDetailForm" action="" method="post" enctype="multipart/form-data">
        <input type="hidden" name="classes_id" value="<?php echo $class->classesID; ?>"/>    
        <div class="row">
            <div class="col-sm-8" id="mainBlockWrapper">
                <?php if(customCompute($newContentBlocks)){
                    foreach($newContentBlocks as $key=>$newContentBlock){
                        
                        // content block
                        if($newContentBlock['type_id'] == 1){?>
                            <div class="blockWrapper">
                                <div class="card card--spaced" style= "box-shadow: none">
                                    <div class="card-body">
                                        <h4 align="center"><b>Content Block</b>
                                           <a href="javascript:void(0)" class="btn btn-xs btn-danger deleteBlock" data-id="<?php echo $newContentBlock['blockID']; ?>" title="Remove Block"><i class="fa fa-times"></i></a>    
                                        </h4>
                                        <div class="md-form">
                                            <label for="title" class="active">Title</label>
                                            <input type="text" id="title<?php echo $key; ?>" value="<?php echo $newContentBlock['title']; ?>" name="contents[<?php echo $key; ?>][title]" class="form-control" placeholder="Enter title here">
                                        </div>
                                        <label for="description" class="active">Description</label>   
                                        <div class="md-form">             
                                            <textarea class="md-textarea form-control description"  id="desc<?php echo $key; ?>" name="contents[<?php echo $key; ?>][description]" rows="4"><?php echo $newContentBlock['description']; ?></textarea>
                                        </div>      

                                        <div class="md-form md-form--file">
                                            <div class="file-field">
                                                <div class="btn btn-success btn-sm float-left">
                                                    <span>Choose file</span>
                                                        <input type="file" name="contents[photo][]" />
                                                </div>
                                                <div class="file-path-wrapper">
                                                    <input class="file-path validate form-control" value="<?php echo $newContentBlock['image']; ?>" type="text" name="contents[<?php echo $key; ?>][attachment]" placeholder="Upload your file" readonly />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="md-form">  
                                            <label for="content_order" class="active">Order</label> 
                                            <input type="number" min="1" id="content_order<?php echo $key; ?>" name="contents[<?php echo $key; ?>][content_order]" class="form-control" value="<?php echo $newContentBlock['order']; ?>"/>
                                            <input type="hidden" name="contents[<?php echo $key; ?>][block_type]" value="1" class="form-control"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php }

                        // course block 
                        if($newContentBlock['type_id'] == 2){ ?>
                            <div class="blockWrapper" id="courseBlock">
                                <div class="card card--spaced" style= "box-shadow: none">
                                    <div class="card-body">
                                        <h4 align="center"><b>Course Block</b>
                                        <a href="javascript:void(0)" class="btn btn-xs btn-danger deleteBlock" data-id="<?php echo $newContentBlock['blockID']; ?>" title="Remove Block"><i class="fa fa-times"></i></a>    
                                       </h4>
                                        <p align="center">Courses block is here.</p>
                                        <div class="md-form">
                                            <label for="content_order" class="active">Order</label>
                                            <input type="number" min="1" id="content_order<?php echo $key; ?>" name="contents[<?php echo $key; ?>][content_order]" class="form-control" value="<?php echo $newContentBlock['order']; ?>">
                                            <input type="hidden" name="contents[<?php echo $key; ?>][block_type]" value="2" class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>
                       <?php  }

                        // faq block 
                        if($newContentBlock['type_id'] == 3){ ?>
                            <div class="blockWrapper" id="faqBlock">
                                <div class="card card--spaced" style= "box-shadow: none">
                                    <div class="card-body">
                                        <h4 align="center"><b>FAQ Block</b>
                                          <a href="javascript:void(0)" class="btn btn-xs btn-danger deleteBlock" data-id="<?php echo $newContentBlock['blockID']; ?>" title="Remove Block"><i class="fa fa-times"></i></a>    
                                        </h4>
                                        <div class="md-form">
                                            <label for="content_order" class="active">Order</label>
                                            <input type="number" min="1" id="content_order<?php echo $key; ?>" name="contents[<?php echo $key; ?>][content_order]" class="form-control" value="<?php echo $newContentBlock['order']; ?>">
                                            <input type="hidden" name="contents[<?php echo $key; ?>][block_type]" value="3" class="form-control">
                                        </div>
                                        <div class="faqSelection">
                                            <div class="col-md-9">
                                                <label>Select FAQ to add</label>
                                                <div class="md-form--select md-form">
                                                    <?php
                                                        $array = [];
                                                        if(customCompute($otherfaqs)) {
                                                            foreach ($otherfaqs as $otherfaq) {
                                                                $array[$otherfaq->id] = $otherfaq->question;
                                                            }
                                                        }
                                                        echo form_multiselect("faqID[]", $array, set_value("faqID"), "id='faqID' class='mdb-select'");
                                                    ?>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <a href="#" class="btn btn-xs btn-primary" data-toggle="modal" data-target="#faqModal" style="float: right;">Add New FAQ</a>
                                                <br><br><br>
                                                <button class="btn btn-xs btn-info" id="importfaq">Import FAQ</button>
                                            </div>
                                        </div>
                                        <div class="faqList">
                                            <?php
                                               if(customCompute($faqs)){ ?>
                                                <ul> 
                                                   <?php foreach($faqs as $faq){ ?>
                                                    <li>
                                                        <?php echo $faq->question; ?>
                                                        <a href="javascript:void(0)" class="removeFaq btn btn-xs btn-default" data-id="<?php echo $faq->id; ?>" title="Remove FAQ">
                                                           <i class="fa fa-times"></i>
                                                        </a>
                                                    </li>
                                                    <?php } ?>
                                                </ul>    
                                               <?php } ?>
                                        </div>

                                    </div>
                                </div>
                            </div>
                       <?php }
                        ?>
                <?php }}else{ ?>  
                    <div class="blockWrapper">
                        <div class="card card--spaced" style= "box-shadow: none">
                            <div class="card-body">
                                <h4 align="center"><b>Content Block</b>
                                    <a href="javascript:void(0)" class="btn btn-xs btn-danger deleteBlock" data-id="" title="Remove Block"><i class="fa fa-times"></i></a>    
                                </h4>
                                <div class="md-form">
                                    <label for="title" class="active">Title</label>
                                    <input type="text" id="title0" value="" name="contents[0][title]" class="form-control" placeholder="Enter title here">
                                </div>
                                <label for="description" class="active">Description</label>   
                                <div class="md-form">             
                                    <textarea class="md-textarea form-control description"  id="desc0" name="contents[0][description]" rows="4"></textarea>
                                </div>      

                                <div class="md-form md-form--file">
                                    <div class="file-field">
                                        <div class="btn btn-success btn-sm float-left">
                                            <span>Choose file</span>
                                                <input type="file" name="contents[photo][]" />
                                        </div>
                                        <div class="file-path-wrapper">
                                            <input class="file-path validate form-control" type="text" name="contents[0][attachment]" placeholder="Upload your file" readonly />
                                        </div>
                                    </div>
                                </div>
                                <div class="md-form">  
                                    <label for="content_order" class="active">Order</label> 
                                    <input type="number" min="1" id="content_order0" name="contents[0][content_order]" class="form-control"/>
                                    <input type="hidden" name="contents[0][block_type]" value="1" class="form-control"/>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="blockWrapper" id="courseBlock">
                        <div class="card card--spaced">
                            <div class="card-body">
                                <h4 align="center"><b>Course Block</b>
                                    <a href="javascript:void(0)" class="btn btn-xs btn-danger deleteBlock" data-id="" title="Remove Block"><i class="fa fa-times"></i></a>    
                                </h4>
                                <p align="center">Courses block is here.</p>
                                <div class="md-form">
                                    <label for="content_order" class="active">Order</label>
                                    <input type="number" min="1" id="content_order1" name="contents[1][content_order]" class="form-control" value="">
                                    <input type="hidden" name="contents[1][block_type]" value="2" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?>    
            </div>
            <div class="col-sm-4 ">
                <div class="card card--spaced fixed_button" >
                    <div class="card-body">
                        <div class="md-form md-form--submit" >
                            <input type="submit" id="submit1"  name="save" value="Save" data-type="saved" class="btn btn-primary submit"/>    
                            <input type="submit" id="submit2"  name="publish" value="<?php echo $class->status == 'published'?'Update':'Publish' ?>" data-type="published" class="btn btn-success submit"/>    
                        </div>
                    </div>
                </div>

                <div class="card card--spaced " >
                    <div class="card-body ">
                        <div id="statusWrapper">
                            <label class="label label-info" id="statusLabel" style="width: 25%;float:left;" ><?php echo $class->status; ?></label>
                            <?php 
                               $disabled = $class->status == 'pending'?'disabled="disabled"':'';
                            ?>  
                            <?php if($class->status != 'pending'){ ?>
                                <a type="button" id="switchtopending" style="float:left; margin-left:5px;" class="btn btn-xs btn-primary">Switch to pending</a>
                            <?php } ?>  
                            <a id="previewBtn" style="float:right;padding-right:10px;" <?php echo $disabled; ?>target="_blank" class="btn btn-xs btn-primary" href="<?php echo base_url('frontend/page/course-detail/' . $class->classesID.'?preview=true') ?>" >Preview</a>
                        </div>
                        <h4 class="mr-5"><?php echo $class->classes; ?></h4>
                        <div class="md-form md-form--select">
                            <?php
                                $array = array("0" => $this->lang->line("classdetail_please_select"));
                                foreach ($blockTypes as $blockType) {
                                    $array[$blockType->id] = $blockType->type;
                                }
                                echo form_dropdown("content_block_type", $array, set_value("content_block_type"), "id='block_type' class='mdb-select block_type'");
                            ?>
                            <label for="classesID" class="mdb-main-label">
                                <?=$this->lang->line('classdetail_block_type')?>
                            </label>
                        </div>
                    </div>
                </div>
                <br>
                <div class="card card--spaced">
                    <div class="card-body">
                        <h4 class="mr-5">Select Enrolments</h4>
                        <div class="md-form md-form--select">
                            <?php
                                $array = [];
                                foreach ($otherenrollments as $otherenrollment) {
                                    $array[$otherenrollment->id] = $otherenrollment->title;
                                }
                                echo form_multiselect("enrollID[]", $array, set_value("enrollID"), "id='enrollID' class='mdb-select'");
                            ?>
                        </div>
                        <div id="enrollmentWrapper">
                            <?php if(customCompute($enrollments)){
                                   foreach($enrollments as $enrollment){
                            ?>
                            <label class="label label-success">
                                <?php echo $enrollment->title; ?>
                                <a href="javascript:void(0)" class="removeEnrollment" data-id="<?php echo $enrollment->id; ?>" title="Remove">&nbsp;&nbsp;<i class="fa fa-times"></i></a>
                            </label>  
                            <?php }} ?>
                        </div>
                        <div class="md-form md-form--submit">
                            <button class="btn btn-info" id="importEnrollment">Import</button>
                            <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#enrollmentModal">Add New Enrollment</a>
                        </div>
                    </div>
                </div>
                <br>
                <div class="card card--spaced">
                    <div class="card-body">
                        <h4 class="mr-5">Extra informations</h4>
                        
                        <div class= "md-form">
                            <label class="active">Student</label> 
                            <input type="text" class="form-control" name="extraDetails[student]" value="<?php echo $extraInformations?$extraInformations->student:''; ?>"/>              
                        </div>
                        <div class="md-form">  
                            <label class="active">Study Mode</label> 
                            <input type="text" class="form-control" name="extraDetails[study_mode]" value="<?php echo $extraInformations?$extraInformations->study_mode:''; ?>"/>
                        </div>
                        <div class="md-form">
                            <label class="active">Campus locations</label>
                            <input type="text" class="form-control" name="extraDetails[campus_location]" value="<?php echo $extraInformations?$extraInformations->campus_location:''; ?>"/>
                        </div>
                        <div class="md-form">
                            <label class="active">Duration</label>
                            <input type="text" class="form-control" name="extraDetails[duration]" value="<?php echo $extraInformations?$extraInformations->duration:''; ?>"/>
                        </div>
                        <div class="md-form">
                            <label class="active">Total hours</label>
                            <input type="text" class="form-control" name="extraDetails[total_hours]" value="<?php echo $extraInformations?$extraInformations->total_hours:''; ?>"/>    
                        </div>
                        <div class="md-form">
                            <label class="active">Class description</label>
                            <textarea name="extraDetails[class_description]" class="md-textarea form-control" rows="4" cols="5">
                                <?php echo $extraInformations?$extraInformations->description:''; ?>
                            </textarea>
                        </div>
                        <div class="md-form">
                            <label class="active">Tution Fees OffShore(with COE & VISA)</label>
                            <input type="text" class="form-control" name="extraDetails[fees]" value="<?php echo $extraInformations?$extraInformations->fees:''; ?>"/>    
                        </div>
                        <div class="md-form">
                            <label class="active">Tution Fees Onshore</label>
                            <input type="text" class="form-control" name="extraDetails[tution_fees_onshore]" value="<?php echo $extraInformations?$extraInformations->tution_fees_onshore:''; ?>"/>    
                        </div>
                        <div class="md-form">
                            <label class="active">Tution Fees OffShore(without COE & VISA)</label>
                            <input type="text" class="form-control" name="extraDetails[tution_fees_offshore_no_coe_visa]" value="<?php echo $extraInformations?$extraInformations->tution_fees_offshore_no_coe_visa:''; ?>"/>    
                        </div>
                        <div class="md-form">
                            <label class="active">Domestic VET*</label>
                            <input type="text" class="form-control" name="extraDetails[domestic_vet]" value="<?php echo $extraInformations?$extraInformations->domestic_vet:''; ?>"/>    
                        </div>
                        <div class="md-form">
                            <label class="active">Discounted fees</label>
                            <input type="text" class="form-control" name="extraDetails[discounted_fees]" value="<?php echo $extraInformations?$extraInformations->discounted_fees:''; ?>"/>    
                        </div>
                        <div class="md-form">
                            <label class="active">Material fees</label>
                            <input type="text" class="form-control" name="extraDetails[material_fees]" value="<?php echo $extraInformations?$extraInformations->material_fees:''; ?>"/>    
                        </div>
                        <div class="md-form">
                            <label class="active">Enrolment fees</label>
                            <input type="text" class="form-control" name="extraDetails[enrollment_fees]" value="<?php echo $extraInformations?$extraInformations->enrollment_fees:''; ?>"/>    
                        </div>
                        <div class="md-form">
                            <label class="active">Covid scholarship</label>
                            <input type="text" class="form-control" name="extraDetails[covid_scholarship]" value="<?php echo $extraInformations?$extraInformations->covid_scholarship:''; ?>"/>    
                        </div>
                        <div class="md-form">
                            <label class="active">Cricos code</label>
                            <input type="text" class="form-control" name="extraDetails[cricos]" value="<?php echo $extraInformations?$extraInformations->cricos:''; ?>"/>    
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- row -->
    </form>
</div><!-- Body -->

<div class="modal fade" id="faqModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">ADD FAQ</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
       
        <form id="faqForm"> 
            <input type="hidden" name="classes_id" value="<?php echo $class->classesID; ?>"/>
            <div class="modal-body">
                <div class="md-form">
                    <label for="title" class="active">Question</label>
                    <input type="text" id="question" value="" name="question" class="form-control" placeholder="Enter question here">
                </div>
                <label for="description" class="active">Description</label>   
                <div class="md-form">             
                    <textarea class="md-textarea form-control description"  id="answer" name="answer" rows="4"></textarea>
                </div>   
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <input type="submit" id="add_faq" class="btn btn-primary" name="add" value="Add"/>
            </div>
        </form>
    </div>
  </div>
</div>

<div class="modal fade" id="enrollmentModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">ADD Enrolment</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <form id="enrollmentForm"> 
      <input type="hidden" name="classes_id" value="<?php echo $class->classesID; ?>"/>
            <div class="md-form">
                <label for="title"> Title <span class="text-red">*</span></label>
                <input type="text"  class="form-control" id="title" name="title" value="" >
            </div>
            <div class="md-form">                                     
                <input type="text" autocomplete="off" class="form-control" id="from_month" name="from_month" value="" >
                <label for="from_month"> 
                    Month From <span class="text-red">*</span>
                </label>
            </div>
            <div class="md-form">                                     
                <input type="text" autocomplete="off" class="form-control" id="to_month" name="to_month" value="" >
                <label for="from_month"> 
                    Month To <span class="text-red">*</span>
                </label>
            </div>
            <div class="md-form">                                     
                <input type="text" autocomplete="off" class="form-control" id="start_date" name="start_date" value="" >
                <label for="start_date"> 
                    Starting Date <span class="text-red">*</span>
                </label>
            </div>
             <input type="submit" id="add_enrollment" class="btn btn-success" value="Add">
       </form>
</div>
    
    </div>
  </div>
</div>

<link rel="stylesheet" href="<?php echo base_url('assets/datepicker/datepicker.css'); ?>">
<script type="text/javascript" src="<?php echo base_url('assets/datepicker/datepicker.js'); ?>"></script>

<script type="text/javascript">

$("#from_month").datepicker( {
        format: "mm-yyyy",
        startView: "months", 
        minViewMode: "months"
    });
    $("#to_month").datepicker( {
        format: "mm-yyyy",
        startView: "months", 
        minViewMode: "months"
    });
    $('#start_date').datepicker({
        dateFormat: 'dd-mm-yy',
        
    });

$('.mdb-select').material_select('destroy');
$('.mdb-select').material_select();

$(document).ready(function(){


    $('#switchtopending').click(function(){

        var classesID = "<?php echo $class->classesID; ?>";
        $.ajax({
            type: 'GET',
            url: "<?=base_url('classdetail/switchToPending')?>",
            data: {'classesID':classesID},
            success: function(data) {
                showToast('Switch to pending successfully.');
                $("#previewBtn").attr('disabled',true);
                $('#statusLabel').html('pending');
                                   
            }
        });
    })

    $('#block_type').change(function(){

        var result = confirm("Are you sure you want to add new block?");
        if(result){
            var type = $(this).val();
            if(type == 0){
                showToastError('Please select type.');
                return false;
            }
            var count = $('.blockWrapper').length;
            var faqBlock = $('#faqBlock').length;
            var courseBlock = $('#courseBlock').length;
            if(type == 1){
                
                    var html = '<div class="blockWrapper"><div class="card card--spaced" style= "box-shadow: none" ><div class="card-body"><h4 align="center"><b>Content Block</b>'+
                            '<a href="javascript:void(0)" class="btn btn-xs btn-danger deleteBlock" data-id="" title="Remove Block"><i class="fa fa-times"></i></a>'+   
                             '</h4>'+
                            '<div class="md-form">'+
                                '<label for="title" class="active">Title</label>'+
                                '<input type="text" id="title'+count+'" value="" name="contents['+count+'][title]" class="form-control" placeholder="Enter title here">'+
                            '</div>'+
                            '<label for="description" class="active">Description</label>'+ 
                            '<div class="md-form">'+             
                                '<textarea class="md-textarea form-control description" id="desc'+count+'" name="contents['+count+'][description]" rows="4"></textarea>'+
                            '</div>'+       
                            '<div class="md-form md-form--file">'+
                                '<div class="file-field">'+
                                    '<div class="btn btn-success btn-sm float-left">'+
                                        '<span>Choose file</span>'+
                                            '<input type="file" name="contents[photo][]" />'+
                                    '</div>'+
                                    '<div class="file-path-wrapper">'+
                                        '<input class="file-path validate form-control" type="text" name="contents['+count+'][attachment]" placeholder="Upload your file" readonly />'+
                                    '</div>'+
                                '</div>'+
                            '</div>'+
                            '<div class="md-form"> '+
                            '<label for="content_order" class="active">Order</label>'+ 
                            '<input type="number" min="1" name="contents['+count+'][content_order]" id="content_order'+count+'" class="form-control"/>'+
                            '<input type="hidden" name="contents['+count+'][block_type]" value="1" class="form-control"/>'+
                            '</div></div></div>'+
                        '</div>';

                        
            }else if(type == 2){

                if(courseBlock > 0){
                    showToastError('Course block is already added.');
                    return false;
                }

                var html = '<div class="blockWrapper" id="courseBlock"><div class="card card--spaced" style= "box-shadow: none"><div class="card-body">'+
                                '<h4 align="center"><b>Course Block</b>'+
                                '<a href="javascript:void(0)" class="btn btn-xs btn-danger deleteBlock" data-id="" title="Remove Block"><i class="fa fa-times"></i></a>'+    
                               '</h4>'+
                                '<p align="center">Courses block is here.</p>'+
                                '<div class="md-form">'+  
                                    '<label for="content_order" class="active">Order</label>'+ 
                                    '<input type="number" min="1" id="content_order'+count+'"  name="contents['+count+'][content_order]" class="form-control"/>'+
                                    '<input type="hidden" name="contents['+count+'][block_type]" value="2" class="form-control"/>'+
                                '</div>'+
                                '</div></div></div>';
            }else{
                if(faqBlock > 0){
                    showToastError('FAQ block is already added.');
                    return false;
                }
               
                var html = ' <div class="blockWrapper" id="faqBlock"><div class="card card--spaced" style= "box-shadow: none"><div class="card-body">'+
                                '<h4 align="center"><b>FAQ Block</b>'+
                                '<a href="javascript:void(0)" class="btn btn-xs btn-danger deleteBlock" data-id="" title="Remove Block"><i class="fa fa-times"></i></a>'+ 
                               '</h4>'+
                                '<p align="center">FAQ block is here.</p>'+
                                '<div class="md-form">'+  
                                    '<label for="content_order" class="active">Order</label>'+ 
                                    '<input type="number" min="1" id="content_order'+count+'" name="contents['+count+'][content_order]" class="form-control"/>'+
                                    '<input type="hidden" name="contents['+count+'][block_type]" value="3" class="form-control"/>'+
                                '</div>'+
                                '<div class="faqSelection">'+
                                     '<div class="col-md-9">'+
                                        '<label>Select FAQ to add</label>'+
                                        '<div class="md-form--select md-form">'+
                                            '<select name="faqID[]" id="faqID" class="mdb-select" multiple="multiple">';

                                var otherfaqs = <?php echo json_encode($otherfaqs) ?>;
                                $.each(otherfaqs, function (i, elem) {
                                    html = html + '<option value="'+elem.id+'">'+elem.question+'</option>';
                                });
                                            
                                html = html + '</select>'+
                                        '</div>'+
                                      '</div>'+
                                    '<div class="col-md-3">'+
                                        '<a href="#" class="btn btn-xs btn-primary" data-toggle="modal" data-target="#faqModal" style="float: right;">Add New FAQ</a>'+
                                         '<br><br><br>'+
                                        '<button class="btn btn-xs btn-info" id="importfaq">Import FAQ</button>'+
                                    '</div>'+
                                '</div>'+
                                '<div class="faqList"><ul>';


                                var arrayFromPHP = <?php echo json_encode($faqs) ?>;
                                $.each(arrayFromPHP, function (i, elem) {
                                    html = html + '<li id="f'+elem.id+'">'+elem.question+' <a href="javascript:void(0)" class="removeFaq btn btn-xs btn-default" data-id="'+elem.id+'" title="Remove FAQ"><i class="fa fa-times"></i></a></li>';
                                });
                                html = html + '</ul></div></div></div></div>';

            }

            $('#mainBlockWrapper').append(html);
            $('.mdb-select').material_select('destroy');
            $('.mdb-select').material_select();
            tinyEditor();
            $('.deleteBlock').click(function(){
               deleteBlock($(this));
            });
            showToast('Block successfully added.');

            // import faq
            $('#importfaq').click(function(e){
                e.preventDefault();
                var ids = $('#faqID').val();
                var classesID = "<?php echo $class->classesID;?>";
                if(ids == null){
                    showToastError('Please select FAQ.');
                    return false;
                }
                $.ajax({
                    type: 'GET',
                    url: "<?=base_url('classdetail/importFaq')?>",
                    data: {'ids': ids,'classesID': classesID},
                    dataType: 'html',
                    success: function(data) {
                        var response = jQuery.parseJSON(data);
                        if(response.status) {
                            showToast(response.message);
                            $('.faqList ul').append(response.template);
                            // remove FAQ
                            $('.removeFaq').click(function(e){
                                e.preventDefault();
                                removeFaq($(this));    
                            });
                        }else{
                            showToastError(response.message);
                        } 
                    }
                });         
            });

            // remove FAQ
            $('.removeFaq').click(function(e){
                e.preventDefault();
                removeFaq($(this));    
            });
            function removeFaq(obj){
                var id = obj.data('id');
                var classesID = "<?php echo $class->classesID; ?>";
                var result = confirm("Are you sure you want to remove FAQ?");
                if(result){
                    $.ajax({
                        type: 'GET',
                        url: "<?=base_url('classdetail/removeFAQ')?>",
                        data: {'id':id,'classesId':classesID},
                        success: function(data) {
                            $('#f'+id).remove();
                            showToast('FAQ removed.');
                        }
                    });  
                }     
            }
        }
    
    });
  
    $('.deleteBlock').click(function(){
          deleteBlock($(this));
    });

    function deleteBlock(obj){
        var result = confirm('Are you sure you want to delete block ?');
          if(result){
                blockID = obj.data('id');
                if(blockID == ''){
                    obj.closest('.blockWrapper').remove();
                }else{
                    $.ajax({
                                type: 'GET',
                                url: "<?=base_url('classdetail/deleteCotentBlocks')?>",
                                data: {'blockID':blockID},
                                success: function(data){
                                    var response = jQuery.parseJSON(data);
                                    if(response.status) {
                                        showToast(response.message);
                                        obj.closest('.blockWrapper').remove();
                                    } 
                                }
                            });
                }
          }
    }

});

tinyEditor();

function tinyEditor(){
    tinymce.remove();
    tinymce.init({
            selector: '.description',
            width: 600,
            height: 300,
            plugins: [
            'advlist autolink link image lists charmap print preview hr anchor pagebreak',
            'searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking',
            'table emoticons template powerpaste help tiny_mce_wiris '
            ],
            toolbar: 'undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | ' +
            'bullist numlist outdent indent | link image | print preview media fullpage | ' +
            'forecolor backcolor emoticons | help tiny_mce_wiris_formulaEditor | tiny_mce_wiris_formulaEditorChemistry',
            powerpaste_allow_local_images: true,
            powerpaste_word_import: 'prompt',
            powerpaste_html_import: 'prompt',
            menu: {
            favs: {title: 'My Favorites', items: 'code visualaid | searchreplace | emoticons'}
            },
            automatic_uploads: true,
            relative_urls: false,
            remove_script_host: false,
            /*
                URL of our upload handler (for more details check: https://www.tiny.cloud/docs/configure/file-image-upload/#images_upload_url)
                images_upload_url: 'postAcceptor.php',
                here we add custom filepicker only to Image dialog
            */
            file_picker_types: 'image',
            /* and here's our custom image picker*/
            file_picker_callback: function (cb, value, meta) {
                var input = document.createElement('input');
                input.setAttribute('type', 'file');
                input.setAttribute('accept', 'image/*');

                /*
                Note: In modern browsers input[type="file"] is functional without
                even adding it to the DOM, but that might not be the case in some older
                or quirky browsers like IE, so you might want to add it to the DOM
                just in case, and visually hide it. And do not forget do remove it
                once you do not need it anymore.
                */

                input.onchange = function () {
                var file = this.files[0];

                var reader = new FileReader();
                reader.onload = function () {
                    /*
                    Note: Now we need to register the blob in TinyMCEs image blob
                    registry. In the next release this part hopefully won't be
                    necessary, as we are looking to handle it internally.
                    */
                    var id = 'blobid' + (new Date()).getTime();
                    var blobCache =  tinymce.activeEditor.editorUpload.blobCache;
                    var base64 = reader.result.split(',')[1];
                    var blobInfo = blobCache.create(id, file, base64);
                    blobCache.add(blobInfo);

                    /* call the callback and populate the Title field with the file name */
                    cb(blobInfo.blobUri(), { title: file.name });
                };
                reader.readAsDataURL(file);
                };

                input.click();
            },
            menubar: 'favs file edit view insert format tools table help',
            content_css: 'css/content.css'
        });
}

</script>

<script type="text/javascript">
var flag = false;
localStorage.setItem('selected', flag);
        $(function(){
            $(document).on('change', "#classID", function() {
                if(flag != false){
                    var result = confirm("Are you sure you want to select class?");
                } 
                //localStorage.setItem('selected', false);
            });
            "use strict";
            (() => {
            const modified_inputs = new Set;
            const defaultValue = "defaultValue";
            // store default values
            addEventListener("beforeinput", (evt) => {
                const target = evt.target;
                if (!(defaultValue in target || defaultValue in target.dataset)) {
                    target.dataset[defaultValue] = ("" + (target.value || target.textContent)).trim();
                }
            });
            // detect input modifications
            addEventListener("input", (evt) => {
                //$("#classID").prop('disabled',true);
                flag = true;
                localStorage.setItem('selected', flag);
                const target = evt.target;
                let original;
                if (defaultValue in target) {
                    original = target[defaultValue];
                } else {
                    original = target.dataset[defaultValue];
                }
                if (original !== ("" + (target.value || target.textContent)).trim()) {
                    if (!modified_inputs.has(target)) {
                        modified_inputs.add(target);     
                                           
                        //$("#classID").prop('disabled',false);
                    }
                } else if (modified_inputs.has(target)) {
                    modified_inputs.delete(target);
                }
                
            });
            
            // clear modified inputs upon form submission
            // addEventListener("submit", (evt) => {
            //     modified_inputs.clear();
            //     // to prevent the warning from happening, it is advisable
            //     // that you clear your form controls back to their default
            //     // state with evt.target.reset() or form.reset() after submission
            // });
                $(".submit").click(function(){
                    modified_inputs.clear();
                    flag = false;
                    localStorage.setItem('selected', flag);
                    //$("#classID").prop('disabled',false);
                });
            // warn before closing if any inputs are modified
            addEventListener("beforeunload", (evt) => {
                if (modified_inputs.size) {
                    const unsaved_changes_warning = "Changes you made may not be saved.";
                    evt.returnValue = unsaved_changes_warning;
                    return unsaved_changes_warning;
                }
            });
            
            })();
            

            $('#importfaq').click(function(e){
                e.preventDefault();
                var ids = $('#faqID').val();
                var classesID = "<?php echo $class->classesID;?>";
                if(ids == null){
                    showToastError('Please select FAQ.');
                    return false;
                }
                $.ajax({
                    type: 'GET',
                    url: "<?=base_url('classdetail/importFaq')?>",
                    data: {'ids': ids,'classesID': classesID},
                    dataType: 'html',
                    success: function(data) {
                        var response = jQuery.parseJSON(data);
                        if(response.status) {
                            showToast(response.message);
                            $('.faqList ul').append(response.template);
                            $('.removeFaq').click(function(e){
                                e.preventDefault();
                                removeFaq($(this));    
                            });
                        }else{
                            showToastError(response.message);
                        } 
                    }
                });    
            });

            $('#add_faq').click(function(e){
                 e.preventDefault();
                 tinyMCE.triggerSave();
                 var question = $('#question').val();
                 var answer = $('#answer').val();
                 if(question == '' || answer == '' ){
                      showToastError('All fields are require.');
                      return false;
                 }

                var formData = $("#faqForm").serialize();

                $.ajax({
                    type: 'POST',
                    url: "<?=base_url('faq/ajaxSavefaq')?>",
                    data: formData,
                    dataType: 'html',
                    success: function(data) {
                        var response = jQuery.parseJSON(data);
                        if(response.status){
                            $('#faqModal').modal('hide');
                            showToast('FAQ added.');
                            $('.faqList ul').append('<li>'+question+' <a href="javascript:void(0)" class="removeFaq btn btn-xs btn-default" data-id="'+response.id+'" title="Remove FAQ"><i class="fa fa-times"></i></a></li>');
                            $('.removeFaq').click(function(e){
                                e.preventDefault();
                                removeFaq($(this));    
                            });
                        }else{
                            showToastError('Unable to add FAQ');
                        }
                    }
                });                

            });

            // publish data
            $('.submit').click(function(e){
                e.preventDefault();

                // validation starts
                var errorCount = 0;
                var text = '';
                var description = '';
                var order = '';
                $('#mainBlockWrapper [id^="title"]').each(function(){
                    var titleID = $(this).attr('id');
                    var tid = titleID.replace('title',' ');
                    tid = parseInt(tid) + 1;
                    if ($(this).val() == ''){
                         text = text + '<p>Block '+tid+ ' title is empty</p>';
                         errorCount++;
                    }
                });

                
                $('#mainBlockWrapper [id^="desc"]').each(function(){
                    var descID = $(this).attr('id');
                    var did = descID.replace('desc',' ');
                    var rid  = parseInt(did);
                    did = parseInt(did) + 1;
                    tinyMCE.triggerSave();
                    
                    if(descID != 'desc'+rid+'_ifr'){
                        if ($('#'+descID).val() == ''){
                            description = description + '<p>Block '+did+ ' description is empty</p>';
                            errorCount++;
                        }
                    }
                   
                });

               
                $('#mainBlockWrapper [id^="content_order"]').each(function(){
                    var orderID = $(this).attr('id');
                    var oid = orderID.replace('content_order',' ');
                    oid = parseInt(oid) + 1;
                    if ($(this).val() == ''){
                        order = order + '<p>Block '+oid+ ' order is empty</p>';
                        errorCount++;
                    }
                  
                });
                // validation end

                if(errorCount > 0){
                    showToastError(text+description+order);
                    return false;
                }
                // var formData = $('#classDetailForm').serialize();
                
                var status = $(this).data('type');
                var statusLabel = $('#statusLabel').html();

                var form = $("#classDetailForm");
                var formData = new FormData(form[0]);
                formData.append( 'status', status );
                $.ajax({
                                type: 'POST',
                                url: "<?=base_url('classdetail/saveContent')?>",
                                data: formData,
                                cache: false,
                                contentType: false,
                                processData: false,
                                success: function(data) {
                                    var response = jQuery.parseJSON(data);
                                    if(response.status) {
                                        showToast(response.message);
                                        if(statusLabel == 'pending'){
                                            $("#previewBtn").removeAttr('disabled');
                                        }
                                        $('#statusLabel').html(status);
                                    } else {
                                       showToastError(response.error);
                                    }
                                }
                            });
            
                
            });

            $('.removeFaq').click(function(e){
                 e.preventDefault();
                 removeFaq($(this));
            });

            function removeFaq(obj){
                var id = obj.data('id');
                var classesID = "<?php echo $class->classesID; ?>";
                var result = confirm("Are you sure you want to remove FAQ?");
                if(result){
                    $.ajax({
                        type: 'GET',
                        url: "<?=base_url('classdetail/removeFAQ')?>",
                        data: {'id':id,'classesId':classesID},
                        success: function(data) {
                            obj.parent().remove();
                            showToast('FAQ removed.');
                            $.ajax({
                                type: 'GET',
                                url: "<?= base_url('classdetail/removedFaqs') ?>",
                                data: "classesId=" + classesID,
                                dataType: "html",
                                success: function(data) {
                                    $('.mdb-select').material_select('destroy'); 
                                    $('#faqID').html(data);
                                    $('.mdb-select').material_select();
                                }
                            });  
                        }
                    });  
                }     
            }

            // enrollment
            $('#add_enrollment').click(function(e){
                 e.preventDefault();
                 var title = $('#title').val();
                 var from_month = $('#from_month').val();
                 var to_month = $('#to_month').val();
                 if(title == '' || from_month == '' || to_month == '' ){
                      showToastError('All fields are require.');
                      return false;
                 }

                var formData = $("#enrollmentForm").serialize();


                $.ajax({
                    type: 'POST',
                    url: "<?=base_url('enrollment/ajaxSaveEnrollment')?>",
                    data: formData,
                    dataType: 'html',
                    success: function(data) {
                        var response = jQuery.parseJSON(data);
                        if(response.status){
                            $('#enrollmentModal').modal('hide');
                            showToast('Enrollment added.');
                            var html = '<label class="label label-success">'+title+'<a href="javascript:void(0)" class="removeEnrollment" data-id="'+response.id+'" title="Remove">&nbsp;&nbsp;<i class="fa fa-times"></i></a></label>';
                            $('#enrollmentWrapper').append(html);
                            $('.removeEnrollment').click(function(e){
                                e.preventDefault();
                                removeEnrollment($(this));
                            });
                        }else{
                            showToastError('Unable to add enrollment');
                        }
                    }
                });                

            });

            $('#importEnrollment').click(function(e){
                e.preventDefault();
                var ids = $('#enrollID').val();
                var classesID = "<?php echo $class->classesID;?>";
                if(ids == null){
                    showToastError('Please select Enrolment.');
                    return false;
                }
                $.ajax({
                    type: 'GET',
                    url: "<?=base_url('classdetail/importEnrollment')?>",
                    data: {'ids': ids,'classesID': classesID},
                    dataType: 'html',
                    success: function(data) {
                        var response = jQuery.parseJSON(data);
                        if(response.status) {
                            showToast(response.message);
                            $('#enrollmentWrapper').append(response.template);
                            $('.removeEnrollment').click(function(e){
                                e.preventDefault();
                                removeEnrollment($(this));
                            });
                        }else{
                            showToastError(response.message);
                        } 
                    }
                });    
            });

            $('.removeEnrollment').click(function(e){
                 e.preventDefault();
                 removeEnrollment($(this));
            });

            function removeEnrollment(obj){
                var id = obj.data('id');
                var classesID = "<?php echo $class->classesID; ?>";
                var result = confirm("Are you sure you want to remove enrolment?");
                if(result){
                    $.ajax({
                        type: 'GET',
                        url: "<?=base_url('classdetail/removeEnrollment')?>",
                        data: {'id':id,'classesId':classesID},
                        success: function(data) {
                            obj.parent().remove();
                            showToast('Enrolment removed.');
                            $.ajax({
                                type: 'GET',
                                url: "<?= base_url('classdetail/removedEnrollments') ?>",
                                data: "classesId=" + classesID,
                                dataType: "html",
                                success: function(data) {
                                    $('.mdb-select').material_select('destroy'); 
                                    // var response = jQuery.parseJSON(data);
                                    // console.log(response);
                                    $('#enrollID').html(data);
                                    $('.mdb-select').material_select();
                                }
                            });  
                        }
                    });  
                }     
            }

        });
</script>