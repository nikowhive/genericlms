<style>
            input[type="file"] {
                display: block;
            }

            .imageThumb {
                max-height: 75px;
                border: 2px solid;
                padding: 1px;
                cursor: pointer;
            }

            .pip {
                display: inline-block;
                margin: 10px 10px 0 0;
            }

            .remove {
                display: block;
                background: #444;
                border: 1px solid black;
                color: white;
                text-align: center;
                cursor: pointer;
            }

            .remove:hover {
                background: white;
                color: black;
            }
        </style>
<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa fa-calendar-check-o"></i> <?=$this->lang->line('panel_title')?></h3>


        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li><a href="<?=base_url("event/index")?>"><?=$this->lang->line('menu_event')?></a></li>
            <li class="active"><?=$this->lang->line('menu_edit')?> <?=$this->lang->line('menu_event')?></li>
        </ol>
    </div><!-- /.box-header -->
    <?php
      $date = date("m/d/Y", strtotime($event->fdate))." ".date("h:i A", strtotime($event->ftime))." - ".date("m/d/Y", strtotime($event->tdate))." ".date("h:i A", strtotime($event->ttime));
    ?>
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-12">
                <form class="form-horizontal" role="form" method="post" enctype="multipart/form-data">
                
                <?php
                    if (form_error('status'))
                        echo "<div class='form-group has-error' >";
                    else
                        echo "<div class='form-group' >";
                    ?>
                    <label for="event" class="col-sm-2 control-label">
                        <?= $this->lang->line("event_status") ?>
                    </label>
                    <div class="col-sm-2">
                        <input type="checkbox" class="" id="status" name="status" value="public" <?php echo $event->status == 'public'?'checked':''; ?> />
                    </div>
                    <span class="col-sm-2 control-label">
                        <?php echo form_error('status'); ?>
                    </span>
            </div>

            <?php
              $this->load->view("notice/edit_users");
            ?>
                    <?php
                        if(form_error('title'))
                            echo "<div class='form-group has-error' >";
                        else
                            echo "<div class='form-group' >";
                    ?>
                        <label for="title" class="col-sm-2 control-label">
                            <?=$this->lang->line("event_title")?> <span class="text-red">*</span>
                        </label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" id="title" name="title" value="<?=set_value('title',$event->title)?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('title'); ?>
                        </span>
                    </div>

                    <?php
                        if(form_error('date'))
                            echo "<div class='form-group has-error' >";
                        else
                            echo "<div class='form-group' >";
                    ?>
                        <label for="date" class="col-sm-2 control-label">
                            <?=$this->lang->line("event_date")?> <span class="text-red">*</span>
                        </label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" id="date" name="date" value="<?=set_value('date',$date)?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('fdate'); ?>
                        </span>
                    </div>

                    <?php
                        if(form_error('published_date'))
                            echo "<div class='form-group has-error' >";
                        else
                            echo "<div class='form-group' >";
                    ?>
                        <label for="published_date" class="col-sm-2 control-label">
                            <?=$this->lang->line("event_published_date")?> <span class="text-red">*</span>
                        </label>
                        <div class="col-sm-4">
                            <input type="text" autocomplete="off" class="form-control" id="published_date" name="published_date" value="<?=set_value('published_date',date("d-m-Y", strtotime($event->published_date)))?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('published_date'); ?>
                        </span>
                    </div>

                    <?php
                        if(form_error('event_details'))
                            echo "<div class='form-group has-error' >";
                        else
                            echo "<div class='form-group' >";
                    ?>
                        <label for="event_details" class="col-sm-2 control-label">
                            <?=$this->lang->line("event_details")?> <span class="text-red">*</span>
                        </label>
                        <div class="col-sm-8">
                            <textarea class="form-control" id="event_details" name="event_details" ><?=set_value('event_details',$event->details)?></textarea>
                        </div>
                        <span class="col-sm-3 control-label">
                            <?php echo form_error('event_details'); ?>
                        </span>
                    </div>


                    <?php 
                        if(form_error('enable_comment')) 
                            echo "<div class='form-group has-error' >";
                        else     
                            echo "<div class='form-group' >";
                    ?>
                        <label for="event" class="col-sm-2 control-label">
                            <?=$this->lang->line("event_enable_comment")?>
                        </label>
                        <div class="col-sm-2">
                            <?php 
                             $check = $event->enable_comment == 1?'checked':'';?>
                            <input type="checkbox" class="" id="enable_comment" <?php echo $check; ?> name="enable_comment" value="1"/>
                        </div>
                        <span class="col-sm-2 control-label">
                            <?php echo form_error('enable_comment'); ?>
                        </span>
                    </div>

                    <?php
            if (form_error('photos[]'))
                echo "<div class='form-group has-error' >";
            else
                echo "<div class='form-group' >";
            ?>
            
            <span class="col-sm-8">
                <?php echo form_error('photos[]'); ?>
            </span> 
        </div>

        <div class="dvFile" id="dvFile">
            <div class="increment-block mb-0 row" id="director-uploads" data-number="0" data-changed="0">
                <div class="col-lg-2 align-self-lg-center">
                    <button type="button" class="btn btn-success" id="add-director"><i class='fa fa-plus'></i> </button>
                </div>

            </div>

        </div>
        <br>

        <div class="box-footer clearfix">
            <div id="dvPreview">
                <?php foreach ($event_media as $media) : ?>

                    <span class="pip pip-<?= $media->id; ?>">
                        <img class="imageThumb" src="<?= base_url('uploads/events/' . $media->attachment); ?>" title="undefined"><br><span class="remove" onclick="deleteImage(<?= $media->id; ?>)">Remove image</span></span>
                <?php endforeach; ?>
            </div>
        </div>
                    <div class="form-group">
                        <div class="col-sm-offset-0 col-sm-8">
                            <input type="submit" id="saveUsers" class="btn btn-success" value="<?=$this->lang->line("update_class")?>" >
                        </div>
                    </div>

                </form>

            </div>
        </div>
    </div>
</div>

<script>

<?php if($bulkEmployeeValue == ''){ ?>
    $('#employeewrapper').hide();
<?php } ?> 
<?php if($bulkClassValue == ''){ ?>     
    $('#classwrapper').hide();
<?php } ?>  
<?php if($bulkEmployeeValue == '' && $bulkClassValue == ''){ ?>
    $('#filterUsers').hide();
<?php } ?>

<?php if($event->status == 'private'){ ?>
   renderTemplatesEdit();
<?php } ?>   

$('#saveUsers').click(function(){

   if($('#status').is(':checked') == false){
    
    var employeeType = $('#bulkEmployeeType').val();
    var classes = $('#bulkClass').val();
    var employees = $('#bulkEmployee').val();

    if(employeeType == ''){
        showToastError('Please select employee type.');
        return false;
    }
    if(employeeType.includes(3) || employeeType.includes(4)){
       if(classes == ''){
         showToastError('Please select class.');
         return false;
       }
    }
    if(employeeType.includes(11)){
       if(employees == ''){
            showToastError('Please select employee.');
            return false;
       }
    }

    var ids = [];
    if((employeeType !=0 && classes != 0) || (employeeType !=0 && employees != 0)){
        var length = $("input[name='users[]']:checked").length;
        if(length == 0){
            showToastError("Users are not selected yet.");
            return false;
        }
    }
}
});

  
$('.types').click(function(){

   var ids = [];
   var id = $(this).attr('id');
   if(id == 'types-0'){
        if($(this).is(':checked')){
              $('.types').not(this).attr('disabled', 'disabled'); 
        }else{
            $('.types').not(this).removeAttr('disabled');
        }
        $('.types').not(this).removeAttr('checked');
        $.each($("input[name='types']:checked"), function(){
            ids.push($(this).val());
        });
    }else{
        $.each($("input[name='types']:checked"), function(){
            ids.push($(this).val());
        });
    }
    var users = ids.join(",");

    if(users.includes(0)){
        $('#bulkClass').val('');
        $('#bulkEmployee').val('');
    }

    if(users.includes(3) || users.includes(4)){
        $('#classwrapper').show(); 
    }else{
        $('#classwrapper').hide(); 
        $('.classes').removeAttr('checked');
        $('.classes').removeAttr('disabled');
        $('#bulkClass').val('');
    }

    if(users.includes(11)){
        $('#employeewrapper').show();
    }else{
        $('#employeewrapper').hide(); 
        $('.employees').removeAttr('checked');
        $('.employees').removeAttr('disabled');
        $('#bulkEmployee').val('');
    }

    $('#bulkEmployeeType').val(users);

    renderTemplates();
});

$('.classes').click(function(){
   var ids = [];
   var id = $(this).attr('id');

    if(id == 'class0'){
        if($(this).is(':checked')){
            $('.classes').not(this).attr('disabled', 'disabled'); 
        }else{
            $('.classes').not(this).removeAttr('disabled');
        }
        $('.classes').not(this).removeAttr('checked');
        $.each($("input[name='classes']:checked"), function(){
            ids.push($(this).val());
        });
    }else{
        $.each($("input[name='classes']:checked"), function(){
            ids.push($(this).val());
        });
    }
    var classes = ids.join(",");
    $('#bulkClass').val(classes);
    renderTemplates();
});

$('.employees').click(function(){

    var ids = [];
    var id = $(this).attr('id');

    if(id == 'employee0'){
        if($(this).is(':checked')){
            $('.employees').not(this).attr('disabled', 'disabled'); 
        }else{
            $('.employees').not(this).removeAttr('disabled');
        }
        $('.employees').not(this).removeAttr('checked');
        $.each($("input[name='employees']:checked"), function(){
            ids.push($(this).val());
        });
    }else{
        $.each($("input[name='employees']:checked"), function(){
            ids.push($(this).val());
        });
    }

    var employees = ids.join(",");
    $('#bulkEmployee').val(employees);
    renderTemplates();
});

function renderTemplates(){

    var selectedUsers  = [];
    $.each($("input[name='users[]']:checked"), function(){
        selectedUsers.push($(this).val());
    });

    var keyselectedUsers  = [];
    $.each($(".allcheck:checked"), function(){
        keyselectedUsers.push($(this).val());
    });

    $('.user-selection-body .usersWrapper').html('');
    $('#filterUsers').hide();
    $('#searchuser2').val('');

    var employeeType = $('#bulkEmployeeType').val();
    var classes = $('#bulkClass').val();
    var employees = $('#bulkEmployee').val();


    if(employeeType == 0){
        $('.user-selection-body .usersWrapper').html('This message will be sent to all users.');
         totalUsers();
    }
    if(employeeType == ""){
        $('.user-selection-body .usersWrapper').html('');
        totalUsers();
    }

    if(classes == 0 || employees == 0){
        totalUsers();
    }
    
    if(employeeType != 0){
        if(classes != 0 || employees != 0){
            $.ajax({
            type: 'GET',
            url: "<?= base_url('notice/renderUsersTemplate') ?>",
            data: {
                'employeeType': employeeType,
                'classes': classes,
                'employees': employees,
                'selectedUsers':selectedUsers,
                'keyselectedUsers':keyselectedUsers
            },
            dataType: "html",
            success: function(data) {
                $('.user-selection-body .usersWrapper').html(data);
                $('#filterUsers').show();
                totalUsers();
            }
        });
        }
    }

}

function renderTemplatesEdit(){

var eventID = "<?= $event->eventID; ?>" ;   
var selectedUsers  = [];
var keyselectedUsers  = [];

$('.user-selection-body .usersWrapper').html('');
$('#filterUsers').hide();
$('#searchuser2').val('');

var employeeType = $('#bulkEmployeeType').val();
var classes = $('#bulkClass').val();
var employees = $('#bulkEmployee').val();


if(employeeType == 0){
    $('.user-selection-body .usersWrapper').html('This message will be sent to all users.');
     totalUsers();
}
if(employeeType == ""){
    $('.user-selection-body .usersWrapper').html('');
    totalUsers();
}

if(classes == 0 || employees == 0){
    totalUsers();
}

if(employeeType != 0){
    if(classes != 0 || employees != 0){
        $.ajax({
        type: 'GET',
        url: "<?= base_url('notice/renderUsersTemplate') ?>",
        data: {
            'employeeType': employeeType,
            'classes': classes,
            'employees': employees,
            'selectedUsers':selectedUsers,
            'keyselectedUsers':keyselectedUsers,
            'eventID' : eventID
        },
        dataType: "html",
        success: function(data) {
            $('.user-selection-body .usersWrapper').html(data);
            $('#filterUsers').show();
            totalUsers();
        }
    });
    }
}

}


$('#searchUser').click(function(e){
   e.preventDefault();  
   var name = $('#searchuser2').val(); 
//    if(name != ''){
    var employeeType = $('#bulkEmployeeType').val();
    var classes = $('#bulkClass').val();
    var employees = $('#bulkEmployee').val();

    if(employeeType != 0){
        if(classes != 0 || employees != 0){
            $.ajax({
            type: 'GET',
            url: "<?= base_url('notice/renderUsersTemplate') ?>",
            data: {
                'employeeType' : employeeType,
                'classes'      : classes,
                'employees'    : employees,
                'name'         : name
            },
            dataType: "html",
            success: function(data) {
                $('.user-selection-body .usersWrapper').html(data);
                totalUsers();
            }
        });
        }
    }
//    }
});

function totalUsers(){

    var employeeType = $('#bulkEmployeeType').val();
    var classes = $('#bulkClass').val();
    var employees = $('#bulkEmployee').val();

    if(employeeType == 0){
        var total = $('#types-0').data('count');
        $('#totalSelectedUsers').html('Selected - <b>'+total+'</b> Users');
    }

    if(employeeType == ''){
        total = 0;
        $('#totalSelectedUsers').html('Selected - <b>'+total+'</b> Users');
    }

    if(employeeType != 0){
        total = 0;
        if(classes == 0 && classes != ''){
            var numbers = employeeType.split(',');
            for(var i = 0; i < numbers.length; i++)
            {
                if(numbers[i] != 11){
                    var id = $('#types-'+numbers[i]).data('count');
                    total = total + id;
                }
            }
        }
        if(employees == 0 && employees != ''){
                var id = $('#types-11').data('count');
                total = total + id;
        }

        var u = $("input[name='users[]']:checked").length;

        var total = total + u;

        $('#totalSelectedUsers').html('Selected - <b>'+total+'</b> Users');
    }
}

</script>

<script>
    $(document).ready(function() {
        <?php if($event->status == 'public'){ ?>
            $('#userContainer').hide();
        <?php } ?>    
        $('#status').click(function() {
            if ($(this).prop("checked") == true) {
                $('#userContainer').hide();
            } else if ($(this).prop("checked") == false) {
                $('#userContainer').show();
            }
        });
    });
</script>

<script language="javascript">
            $(document).ready(function() {

                var i = 1;

                function clone() {
                    var html = "<div class=\"increment-block mb-0 row\" data-changed=\"0\"><div class=\"col-sm-4\"><div class=\"md-form md-form--file\"><div class=\"file-field\"><div class=\"btn btn-success btn-sm float-left\"><span>Choose file</span><input type=\"file\" name=\"photos[]\"  /></div><div class=\"file-path-wrapper\"><input class=\"file-path validate form-control\" type=\"text\" name=\"attachment1[]\" placeholder=\"Upload your file\" readonly/></div></div></div></div><div class=\"col-sm-3\"><div class=\"md-form\"><input type=\"text\" name=\"caption[]\" id=\"file-name" + i + "\" class=\"form-control\" data-item=\"0\"  placeholder=\"Caption\" > <span class=\"text-danger error\"><p id=\"file-name-error" + i + "\"></p></span></div></div><div class=\"col-lg-2 align-self-lg-center\"><button type=\"button\" class=\"btn btn-danger\" onclick=\"remove_input(this)\" title=\"Remove\" id=\"remove-clone\"><i class='fa fa-minus'></i></button></div></div>";
                    i++;
                    $('#dvFile').append(html).find("*").each(function() {
                        var name = $(this).attr('name');

                    }).on('click', 'button.clone', clone);
                }

                $("button#add-director").on("click", clone);

                $("html").on('change', '.app-file', function() {
                    var number = $(this).attr('data-item');
                });

            });

            function remove_input(e) {
                var thisId = e.closest('.increment-block').remove();
                console.log(thisId);
            }
        </script>

<script type="text/javascript"> 

tinymce.init({
        selector: '#event_details',
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

$('#date').daterangepicker({
    timePicker: true,
    timePickerIncrement: 5,
    maxDate: '<?=date('m/d/Y', strtotime($schoolyearsessionobj->endingdate))?>',
    minDate: '<?=date('m/d/Y', strtotime($schoolyearsessionobj->startingdate))?>',
    locale: {
        format: 'MM/DD/YYYY h:mm A'
    }
});

$('#published_date').datepicker({
    startDate:'<?=$schoolyearsessionobj->startingdate?>',
    endDate:'<?=$schoolyearsessionobj->endingdate?>',
});

$(document).on('click', '#close-preview', function(){ 
    $('.image-preview').popover('hide');
    // Hover befor close the preview
    $('.image-preview').hover(
        function () {
           $('.image-preview').popover('show');
           $('.content').css('padding-bottom', '100px');
        }, 
         function () {
           $('.image-preview').popover('hide');
           $('.content').css('padding-bottom', '20px');
        }
    );    
});

function deleteImage(id) {
                var result = confirm("Are you sure to delete?");
                if (result) {
                    $.post("<?php echo base_url('event/deleteImage'); ?>", {
                        id: id
                    }, function(data) {
                        var response = jQuery.parseJSON(data);
                        if (response.status) {
                            console.log(response);
                            $('.pip-' + id).remove();
                            toastr["success"](response.message)
                        }
                    });
                }
            }

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
    $('.image-preview').popover({
        trigger:'manual',
        html:true,
        title: "<strong>Preview</strong>"+$(closebtn)[0].outerHTML,
        content: "There's no image",
        placement:'bottom'
    });
    // Clear event
    $('.image-preview-clear').click(function(){
        $('.image-preview').attr("data-content","").popover('hide');
        $('.image-preview-filename').val("");
        $('.image-preview-clear').hide();
        $('.image-preview-input input:file').val("");
        $(".image-preview-input-title").text("<?=$this->lang->line('event_file_browse')?>"); 
    }); 
    // Create the preview image
    $(".image-preview-input input:file").change(function (){     
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
            $(".image-preview-input-title").text("<?=$this->lang->line('event_file_browse')?>");
            $(".image-preview-clear").show();
            $(".image-preview-filename").val(file.name);            
            img.attr('src', e.target.result);
            $(".image-preview").attr("data-content",$(img)[0].outerHTML).popover("show");
            $('.content').css('padding-bottom', '100px');
        }        
        reader.readAsDataURL(file);
    });  
});

</script>
