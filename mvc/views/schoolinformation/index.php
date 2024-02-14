<div class="container container--sm">
    <div class="box">
        <div class="box-header">
            <h3 class="box-title"><i class="fa fa-gears"></i> <?=$this->lang->line('panel_title')?></h3>
            <ol class="breadcrumb">
                <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
                <li class="active"><?=$this->lang->line('menu_schoolinformation')?></li>
            </ol>
        </div><!-- /.box-header -->
        <!-- form start -->
        <style type="text/css">
            .schoolinformation-fieldset {
                border: 1px solid #DBDEE0 !important;
                padding: 15px !important;
                margin: 0 0 25px 0 !important;
                box-shadow: 0px 0px 0px 0px #000;
            }
    
            .schoolinformation-legend {
                font-size: 1.1em !important;
                font-weight: bold !important;
                text-align: left !important;
                width: auto;
                color: #428BCA;
                padding: 5px 15px;
                border: 1px solid #DBDEE0 !important;
                margin: 0px;
            }
        </style>
        <form class=" " role="form" method="post" enctype="multipart/form-data">
            <div class="box-body">
                <!-- <fieldset class="schoolinformation-fieldset">
                    <legend class="schoolinformation-legend"><?=$this->lang->line('schoolinformation_site_configaration')?></legend> -->
                    <fieldset>
                    <legend class="mb-1" >Principle Details</legend>
                    <div class="row">
                        <div class="col-sm-6">
                                <div class="form-group <?=form_error('principal_name') ? 'has-error' : ''?>">
                                    <div class="md-form">
                                        <label for="principal_name">
                                            <?=$this->lang->line("schoolinformation_school_principal_name")?></label>
                                        <input type="text" class="form-control" placeholder="Set principal name here" id="principal_name" name="principal_name" value="<?=set_value('principal_name', $schoolinformation->principal_name)?>" >
                                        <span class="text-danger error">
                                            <?=form_error('principal_name'); ?>
                                        </span>
                                    </div>
                                </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group <?=form_error('principal_image') ? 'has-error' : ''?>">

                                <div class="md-form md-form--file">
                                        <div class="file-field">
                                            <div class="btn btn-success btn-sm float-left waves-effect waves-light">
                                                <span>Choose  File</span>
                                                <input type="file" accept="image/png, image/jpeg, image/gif" name="principal_image"/>
                                            </div>
                                            <div class="file-path-wrapper">
                                                <input type="text" class="form-control image-preview-filename file-path validat" disabled="disabled" placeholder="<?=$this->lang->line("schoolinformation_school_principal_image")?>">          
                                            </div>
                                        </div>
                                
                                        <span class="text-danger error">
                                        <?=form_error('principal_image'); ?>
                                        </span>
                                </div>
                                    <!-- <div class="col-sm-12">
                                        <label for="principal_image"><?=$this->lang->line("schoolinformation_school_principal_image")?>&nbsp;<i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" title="Set principal image here"></i>
                                        </label>
                                        <div class="input-group image-preview principal_image-preview">
                                            <input type="text" class="form-control image-preview-filename principal_image-preview-filename" disabled="disabled">
                                            <span class="input-group-btn">
                                                <button type="button" class="btn btn-default image-preview-clear principal_image-preview-clear" style="display:none;">
                                                    <span class="fa fa-remove"></span>
                                                    <?=$this->lang->line('setting_clear')?>
                                                </button>
                                                <div class="btn btn-success image-preview-input principal_image-preview-input">
                                                    <span class="fa fa-repeat"></span>
                                                    <span class="image-preview-input-title principal_image-preview-input-title">
                                                    <?=$this->lang->line('setting_file_browse')?></span>
                                                    <input type="file" accept="image/png, image/jpeg, image/gif" name="principal_image"/>
                                                </div>
                                            </span>
                                        </div>
                                        <span class="control-label">
                                            <?=form_error('principal_image'); ?>
                                        </span>
                                    </div> -->
                                
                            </div>
                         </div>
                          
                        <div class="col-sm-12">
                            <div class="form-group <?=form_error('principal_message') ? 'has-error' : ''?>">
                                <div class="md-form">
                                    
                                    <textarea onkeyup="textAreaAdjust(this)" style="overflow:hidden" type="text" class="form-control md-textarea" id="principal_message" name="principal_message" placeholder="Set principal message here" ><?=set_value('principal_message', $schoolinformation->principal_message)?></textarea>
                                    <label for="principal_message"><?=$this->lang->line("schoolinformation_school_principal_message")?></label>
                                    <span class="control-label">
                                        <?=form_error('principal_message'); ?>
                                    </span>
                                </div>
                            </div>
                        </div>
    
                        
                    </div>
                    </fieldset>

                    <fieldset>
                    <legend class="mb-1" >School Details</legend>
                    <div class="row">

                         <div  class="col-sm-6">
                            <div class="form-group <?=form_error('school_name') ? 'has-error' : ''?>">
                                <div class="md-form">
                                    <label for="school_name"><?=$this->lang->line("schoolinformation_school_name")?> </label>
                                    <input type="text" class="form-control" placeholder="Set school name here" id="school_name" name="school_name" value="<?=set_value('school_name', $schoolinformation->school_name)?>" >
                                    <span class="text-danger error">
                                        <?=form_error('school_name'); ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group <?=form_error('school_phone') ? 'has-error' : ''?>">
                                <div class="md-form">
                                    <label for="school_phone"><?=$this->lang->line("schoolinformation_school_phone")?> </label>
                                    <input type="text" class="form-control" placeholder="Set school phone here" id="school_phone" name="school_phone" value="<?=set_value('school_phone', $schoolinformation->school_phone)?>" inputmode="numeric" >
                                    <span class="text-danger error">
                                        <?=form_error('school_phone'); ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group <?=form_error('school_logo') ? 'has-error' : ''?>">

                                <div class="md-form md-form--file">
                                        <div class="file-field">
                                            <div class="btn btn-success btn-sm float-left waves-effect waves-light">
                                                <span>Choose  File</span>
                                                <input type="file" accept="image/png, image/jpeg, image/gif" name="school_logo"/>
                                            </div>
                                            <div class="file-path-wrapper">
                                                <input type="text" class="form-control image-preview-filename file-path validat" disabled="disabled" placeholder="<?=$this->lang->line("schoolinformation_school_logo")?>">          
                                            </div>
                                        </div>
                                    
                                        <span class="text-danger error">
                                        <?=form_error('school_logo'); ?>
                                        </span>
                                </div>
                                <!-- <div class="col-sm-12">
                                    <label for="school_logo"><?=$this->lang->line("schoolinformation_school_logo")?>&nbsp;<i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" title="Set school logo here"></i>
                                    </label>
                                    <div class="input-group image-preview school_logo-preview">
                                        <input type="text" class="form-control image-preview-filename school_logo-preview-filename" disabled="disabled">
                                        <span class="input-group-btn">
                                            <button type="button" class="btn btn-default image-preview-clear school_logo-preview-clear" style="display:none;">
                                                <span class="fa fa-remove"></span>
                                                <?=$this->lang->line('setting_clear')?>
                                            </button>
                                            <div class="btn btn-success image-preview-input school_logo-preview-input">
                                                <span class="fa fa-repeat"></span>
                                                <span class="image-preview-input-title school_logo-preview-input-title">
                                                <?=$this->lang->line('setting_file_browse')?></span>
                                                <input type="file" accept="image/png, image/jpeg, image/gif" name="school_logo"/>
                                            </div>
                                        </span>
                                    </div>
                                    <span class="control-label">
                                        <?=form_error('school_logo'); ?>
                                    </span>
                                </div> -->
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group <?=form_error('school_banner') ? 'has-error' : ''?>">

                                <div class="md-form md-form--file">
                                        <div class="file-field">
                                            <div class="btn btn-success btn-sm float-left waves-effect waves-light">
                                                <span>Choose  File</span>
                                                <input type="file" accept="image/png, image/jpeg, image/gif" name="school_banner"/>
                                            </div>
                                            <div class="file-path-wrapper">
                                                <input type="text" class="form-control image-preview-filename file-path validat" disabled="disabled" placeholder="<?=$this->lang->line("schoolinformation_school_banner")?>">          
                                            </div>
                                            
                                        </div>
                                        <span class="text-danger error">
                                        <?=form_error('school_logo'); ?>
                                        </span>
                                </div>
                                <!-- <div class="col-sm-12">
                                    <label for="school_banner"><?=$this->lang->line("schoolinformation_school_banner")?>&nbsp;<i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" title="Set school banner here"></i>
                                    </label>
                                    <div class="input-group image-preview school_banner-preview">
                                        <input type="text" class="form-control image-preview-filename school_banner-preview-filename" disabled="disabled">
                                        <span class="input-group-btn">
                                            <button type="button" class="btn btn-default image-preview-clear school_banner-preview-clear" style="display:none;">
                                                <span class="fa fa-remove"></span>
                                                <?=$this->lang->line('setting_clear')?>
                                            </button>
                                            <div class="btn btn-success image-preview-input school_banner-preview-input">
                                                <span class="fa fa-repeat"></span>
                                                <span class="image-preview-input-title school_banner-preview-input-title">
                                                <?=$this->lang->line('setting_file_browse')?></span>
                                                <input type="file" accept="image/png, image/jpeg, image/gif" name="school_banner"/>
                                            </div>
                                        </span>
                                    </div>
                                    <span class="control-label">
                                        <?=form_error('school_banner'); ?>
                                    </span>
                                </div> -->
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group <?=form_error('school_email') ? 'has-error' : ''?>">
                                <div class="md-form">
                                    <label for="school_email"><?=$this->lang->line("schoolinformation_school_email")?> </label>
                                    <input type="text" placeholder="Set school email here" class="form-control" id="school_email" name="school_email" value="<?=set_value('school_email', $schoolinformation->school_email)?>" >
                                    <span class="text-danger error">
                                        <?=form_error('school_email'); ?>
                                    </span>
                                </div>
                            </div>
                        </div>
    
                        <div class="col-sm-6">
                            <div class="form-group <?=form_error('school_address') ? 'has-error' : ''?>">
                                <div class="md-form">
                                    <label for="school_address"><?=$this->lang->line("schoolinformation_school_address")?> </label>
                                    <input type="text" class="form-control" placeholder="Set school address here" id="school_address" name="school_address" value="<?=set_value('school_address', $schoolinformation->school_address)?>" >
                                    <span class="text-danger error">
                                        <?=form_error('school_address'); ?>
                                    </span>
                                </div>
                            </div>
                        </div>
    
                        
    
                        <div class="col-sm-12">
                            <div class="form-group <?=form_error('school_description') ? 'has-error' : ''?>">
                                <div class="md-form">
                                    
                                    <textarea onkeyup="textAreaAdjust(this)" style="overflow:hidden" class="form-control md-textarea" placeholder="Set school description here" id="school_description" name="school_description" ><?=set_value('school_description', $schoolinformation->school_description)?></textarea>
                                    <label for="school_description"><?=$this->lang->line("schoolinformation_school_description")?></label>
                                    <span class="text-danger error">
                                        <?=form_error('school_description'); ?>
                                    </span>
                                </div>
                            </div>
                        </div>
    
                    
                        
                        
    
                       
                
                       
    
                    </div>
                    </fieldset>
    
    
                <!-- </fieldset> -->
    
           
                        <button type="submit" class="btn btn-success btn-md"  ><?=$this->lang->line("update_schoolinformation")?></button>
                    
            </div>
        </form>
    </div>
</div>

<script>

function textAreaAdjust(element) {
  element.style.height = "1px";
  element.style.height = (25+element.scrollHeight)+"px";
}
$(document).on('click', '#close-preview', function(){ 
        $('.liaison_photo-preview').popover('hide');
        // Hover befor close the preview
        $('.liaison_photo-preview').hover(
            function () {
               $('.liaison_photo-preview').popover('show');
               $('.content').css('padding-bottom', '120px');
            }, 
             function () {
               $('.liaison_photo-preview').popover('hide');
               $('.content').css('padding-bottom', '20px');
            }
        );    

        $('.principal_image-preview').popover('hide');
        // Hover befor close the preview
        $('.principal_image-preview').hover(
            function () {
               $('.principal_image-preview').popover('show');
               $('.content').css('padding-bottom', '120px');
            }, 
             function () {
               $('.principal_image-preview').popover('hide');
               $('.content').css('padding-bottom', '20px');
            }
        );   

        $('.school_banner-preview').popover('hide');
        // Hover befor close the preview
        $('.school_banner-preview').hover(
            function () {
               $('.school_banner-preview').popover('show');
               $('.content').css('padding-bottom', '120px');
            }, 
             function () {
               $('.school_banner-preview').popover('hide');
               $('.content').css('padding-bottom', '20px');
            }
        );   

        $('.school_logo-preview').popover('hide');
        // Hover befor close the preview
        $('.school_logo-preview').hover(
            function () {
               $('.school_logo-preview').popover('show');
               $('.content').css('padding-bottom', '120px');
            }, 
             function () {
               $('.school_logo-preview').popover('hide');
               $('.content').css('padding-bottom', '20px');
            }
        );   
    });

    $(function() {
        // Create the close button
        var closebtn = $('<button/>', {
            type:"button",
            text: 'x',
            id: 'close-preview',
            style: 'font-size: initial;',
        });
        closebtn.attr("class","close pull-right");
        // Set the popover default content
        $('.liaison_photo-preview').popover({
            trigger:'manual',
            html:true,
            title: "<strong>Preview</strong>"+$(closebtn)[0].outerHTML,
            content: "There's no image",
            placement:'bottom'
        });
        $('.principal_image-preview').popover({
            trigger:'manual',
            html:true,
            title: "<strong>Preview</strong>"+$(closebtn)[0].outerHTML,
            content: "There's no image",
            placement:'bottom'
        });
        $('.school_banner-preview').popover({
            trigger:'manual',
            html:true,
            title: "<strong>Preview</strong>"+$(closebtn)[0].outerHTML,
            content: "There's no image",
            placement:'bottom'
        });
        $('.school_logo-preview').popover({
            trigger:'manual',
            html:true,
            title: "<strong>Preview</strong>"+$(closebtn)[0].outerHTML,
            content: "There's no image",
            placement:'bottom'
        });

        // Clear event
        $('.liaison_photo-preview-clear').click(function(){
            $('.liaison_photo-preview').attr("data-content","").popover('hide');
            $('.liaison_photo-preview-filename').val("");
            $('.liaison_photo-preview-clear').hide();
            $('.liaison_photo-preview-input input:file').val("");
            $(".liaison_photo-preview-input-title").text("<?=$this->lang->line('setting_file_browse')?>"); 
        }); 
        $('.principal_image-preview-clear').click(function(){
            $('.principal_image-preview').attr("data-content","").popover('hide');
            $('.principal_image-preview-filename').val("");
            $('.principal_image-preview-clear').hide();
            $('.principal_image-preview-input input:file').val("");
            $(".principal_image-preview-input-title").text("<?=$this->lang->line('setting_file_browse')?>"); 
        }); 
        $('.school_banner-preview-clear').click(function(){
            $('.school_banner-preview').attr("data-content","").popover('hide');
            $('.school_banner-preview-filename').val("");
            $('.school_banner-preview-clear').hide();
            $('.school_banner-preview-input input:file').val("");
            $(".school_banner-preview-input-title").text("<?=$this->lang->line('setting_file_browse')?>"); 
        }); 
        $('.school_logo-preview-clear').click(function(){
            $('.school_logo-preview').attr("data-content","").popover('hide');
            $('.school_logo-preview-filename').val("");
            $('.school_logo-preview-clear').hide();
            $('.school_logo-preview-input input:file').val("");
            $(".school_logo-preview-input-title").text("<?=$this->lang->line('setting_file_browse')?>"); 
        }); 
        
        // Create the preview image
        $(".liaison_photo-preview-input input:file").change(function (){     
            var img = $('<img/>', {
                id: 'dynamic',
                width:250,
                height:200,
                overflow:'hidden'
            });      
            var file = this.files[0];
            var reader = new FileReader();
            // Set preview image into the popover data-content
            reader.onload = function (e) {
                $(".liaison_photo-preview-input-title").text("<?=$this->lang->line('setting_clear')?>");
                $(".liaison_photo-preview-clear").show();
                $(".liaison_photo-preview-filename").val(file.name);            
                img.attr('src', e.target.result);
                $(".liaison_photo-preview").attr("data-content",$(img)[0].outerHTML).popover("show");
                $('.content').css('padding-bottom', '120px');
            }        
            reader.readAsDataURL(file);
        });  
        $(".principal_image-preview-input input:file").change(function (){     
            var img = $('<img/>', {
                id: 'dynamic',
                width:250,
                height:200,
                overflow:'hidden'
            });      
            var file = this.files[0];
            var reader = new FileReader();
            // Set preview image into the popover data-content
            reader.onload = function (e) {
                $(".principal_image-preview-input-title").text("<?=$this->lang->line('setting_clear')?>");
                $(".principal_image-preview-clear").show();
                $(".principal_image-preview-filename").val(file.name);            
                img.attr('src', e.target.result);
                $(".principal_image-preview").attr("data-content",$(img)[0].outerHTML).popover("show");
                $('.content').css('padding-bottom', '120px');
            }        
            reader.readAsDataURL(file);
        });  
        $(".school_banner-preview-input input:file").change(function (){     
            var img = $('<img/>', {
                id: 'dynamic',
                width:250,
                height:200,
                overflow:'hidden'
            });      
            var file = this.files[0];
            var reader = new FileReader();
            // Set preview image into the popover data-content
            reader.onload = function (e) {
                $(".school_banner-preview-input-title").text("<?=$this->lang->line('setting_clear')?>");
                $(".school_banner-preview-clear").show();
                $(".school_banner-preview-filename").val(file.name);            
                img.attr('src', e.target.result);
                $(".school_banner-preview").attr("data-content",$(img)[0].outerHTML).popover("show");
                $('.content').css('padding-bottom', '120px');
            }        
            reader.readAsDataURL(file);
        });  
        $(".school_logo-preview-input input:file").change(function (){     
            var img = $('<img/>', {
                id: 'dynamic',
                width:250,
                height:200,
                overflow:'hidden'
            });      
            var file = this.files[0];
            var reader = new FileReader();
            // Set preview image into the popover data-content
            reader.onload = function (e) {
                $(".school_logo-preview-input-title").text("<?=$this->lang->line('setting_clear')?>");
                $(".school_logo-preview-clear").show();
                $(".school_logo-preview-filename").val(file.name);            
                img.attr('src', e.target.result);
                $(".school_logo-preview").attr("data-content",$(img)[0].outerHTML).popover("show");
                $('.content').css('padding-bottom', '120px');
            }        
            reader.readAsDataURL(file);
        });  
    });
</script>