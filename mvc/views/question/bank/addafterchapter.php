<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa fa-qrcode"></i> <?=$this->lang->line('panel_title')?></h3>
        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li><a href="<?=base_url("question_bank/index")?>"><?=$this->lang->line('menu_question_bank')?></a></li>
            <li class="active"><?=$this->lang->line('menu_add')?> <?=$this->lang->line('menu_question_bank')?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-12">
                <form class="form-horizontal" role="form" method="post" id="question_bank" enctype="multipart/form-data">
                    <?php
                    if(form_error('class_id'))
                        echo "<div class='form-group has-error' >";
                    else
                        echo "<div class='form-group' >";
                    ?>
                        <label for="group" class="col-sm-2 control-label">
                            <?=$this->lang->line("question_bank_class")?> <span class='text-red'>*</span>
                        </label>
                        <div class="col-sm-6">
                            <?php
                            $array = array(0 => $this->lang->line("question_bank_select"));
                            foreach ($classes as $group) {
                                $array[$group->classesID] = $group->classes;
                            }
                            echo form_dropdown("class_id", $array, $set_class, "id='class_id' class='form-control select2'");
                            ?>
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('class_id'); ?>
                        </span>
                    </div>

                    <?php
                    if(form_error('subject_id'))
                        echo "<div class='form-group has-error' >";
                    else
                        echo "<div class='form-group' >";
                    ?>
                        <label for="group" class="col-sm-2 control-label">
                            <?=$this->lang->line("question_bank_subject")?> <span class='text-red'>*</span>
                        </label>
                        <div class="col-sm-6" id="ajax-get-subjects">
                            <?php
                            $array = array(0 => $this->lang->line("question_bank_select"));
                            foreach ($subjects as $group) {
                                $array[$group->subjectID] = $group->subject;
                            }
                            //print_r($array);die();
                            echo form_dropdown("subject_id", $array, set_value("subject_id",$set_subject), "id='subject_id' class='form-control select2'");
                            ?>
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('subject_id'); ?>
                        </span>
                    </div>

                    <?php
                    if(form_error('chapter_id'))
                        echo "<div class='form-group has-error' >";
                    else
                        echo "<div class='form-group' >";
                    ?>
                        <label for="group" class="col-sm-2 control-label">
                            <?=$this->lang->line("question_bank_chapter")?> <span class='text-red'>*</span>
                        </label>
                        <div class="col-sm-6" id="ajax-get-chapters">
                            <?php
                            $array = array(0 => $this->lang->line("question_bank_select"));
                            foreach ($chapters as $group) {
                                $array[$group->id] = $group->chapter_name;
                            }
                            echo form_dropdown("chapter_id", $array, $set_chapter, "id='chapter_id' class='form-control select2'");
                            ?>
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('chapter_id'); ?>
                        </span>
                    </div>

                    <?php
                    if(form_error('group'))
                        echo "<div class='form-group has-error' >";
                    else
                        echo "<div class='form-group' >";
                    ?>
                        <label for="group" class="col-sm-2 control-label">
                            <?=$this->lang->line("question_bank_group")?> <span class='text-red'>*</span>
                        </label>
                        <div class="col-sm-6">
                            <?php
                            $array = array(0 => $this->lang->line("question_bank_select"));
                            foreach ($groups as $group) {
                                $array[$group->questionGroupID] = $group->title;
                            }
                            echo form_dropdown("group", $array, set_value("group"), "id='group' class='form-control select2'");
                            ?>
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('group'); ?>
                        </span>
                    </div>
                    <?php
                    if(form_error('level'))
                        echo "<div class='form-group has-error' >";
                    else
                        echo "<div class='form-group' >";
                    ?>
                        <label for="level" class="col-sm-2 control-label">
                            <?=$this->lang->line("question_bank_level")?> <span class='text-red'>*</span>
                        </label>
                        <div class="col-sm-6">
                            <?php
                            $array = array(0 => $this->lang->line("question_bank_select"));
                            foreach ($levels as $level) {
                                $array[$level->questionLevelID] = $level->name;
                            }
                            echo form_dropdown("level", $array, set_value("level"), "id='level' class='form-control select2'");
                            ?>
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('level'); ?>
                        </span>
                    </div>

                    <?php
                    if(form_error('question'))
                        echo "<div class='form-group has-error' >";
                    else
                        echo "<div class='form-group' >";
                    ?>
                        <label for="question" class="col-sm-2 control-label">
                            <?=$this->lang->line("question_bank_question")?> <span class='text-red'>*</span>
                        </label>
                        <div class="col-sm-6">
                            <textarea class="form-control" id="question" name="question" ><?=set_value('question')?></textarea>
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('question'); ?>
                        </span>
                    </div>
                    
                    <?php
                    if(form_error('explanation'))
                        echo "<div class='form-group has-error' >";
                    else
                        echo "<div class='form-group' >";
                    ?>
                        <label for="explanation" class="col-sm-2 control-label">
                            <?=$this->lang->line("question_bank_explanation")?>
                        </label>
                        <div class="col-sm-6">
                            <textarea class="form-control" id="explanation" name="explanation" ><?=set_value('explanation')?></textarea>
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('explanation'); ?>
                        </span>
                    </div>

                    <?php
                        if(form_error('photo'))
                            echo "<div class='form-group has-error' >";
                        else
                            echo "<div class='form-group' >";
                    ?>
                        <label for="photo" class="col-sm-2 control-label">
                            <?=$this->lang->line("question_bank_image")?>
                        </label>
                        <div class="col-sm-6">
                            <div class="input-group image-preview">
                                <input type="text" class="form-control image-preview-filename" disabled="disabled">
                                <span class="input-group-btn">
                                    <button type="button" class="btn btn-default image-preview-clear" style="display:none;">
                                        <span class="fa fa-remove"></span>
                                        <?=$this->lang->line('question_bank_clear')?>
                                    </button>
                                    <div class="btn btn-success image-preview-input">
                                        <span class="fa fa-repeat"></span>
                                        <span class="image-preview-input-title">
                                        <?=$this->lang->line('question_bank_file_browse')?></span>
                                        <input type="file" accept="image/png, image/jpeg, image/gif" name="photo"/>
                                    </div>
                                </span>
                            </div>
                        </div>

                        <span class="col-sm-4">
                            <?php echo form_error('photo'); ?>
                        </span>
                    </div>

                    <?php
                    if(form_error('hints'))
                        echo "<div class='form-group has-error' >";
                    else
                        echo "<div class='form-group' >";
                    ?>
                        <label for="hints" class="col-sm-2 control-label">
                            <?=$this->lang->line("question_bank_hints")?>
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="hints" name="hints" value="<?=set_value('hints')?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('hints'); ?>
                        </span>
                    </div>

                    <?php
                    if(form_error('mark'))
                        echo "<div class='form-group has-error' >";
                    else
                        echo "<div class='form-group' >";
                    ?>
                        <label for="mark" class="col-sm-2 control-label">
                            <?=$this->lang->line("question_bank_mark")?> <span class='text-red'>*</span>
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="mark" name="mark" value="<?=set_value('mark')?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('mark'); ?>
                        </span>
                    </div>

                    <?php
                    if(form_error('type'))
                        echo "<div class='form-group has-error' >";
                    else
                        echo "<div class='form-group' >";
                    ?>
                        <label for="type" class="col-sm-2 control-label">
                            <?=$this->lang->line("question_bank_type")?> <span class='text-red'>*</span>
                        </label>
                        <div class="col-sm-6">
                            <?php
                            $array = array(0 => $this->lang->line("question_bank_select"));
                            foreach ($types as $type) {
                                $array[$type->questionTypeID] = $type->name;
                            }

                            echo form_dropdown("type_id", $array, set_value("questionTypeID"), "id='type_id' class='form-control select2'");
                            ?>
                            <input type="hidden" id="type" name="type" value=""/>
                        </div>
                        
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('type'); ?>
                        </span>
                    </div>

                    <?php
                    if(form_error('totalOption'))
                        echo "<div class='form-group has-error' id='totalOptionDiv'>";
                    else
                        echo "<div class='form-group' id='totalOptionDiv'>";
                    ?>
                        <label for="totalOption" class="col-sm-2 control-label" >
                            <?=$this->lang->line("question_bank_totalOption")?>
                        </label>
                        <div class="col-sm-6">
                            <select name="totalOption" id="totalOption" class="form-control ">
                               <option value="0" class="js-sub">Please Select</option> 
                               <option value="short" class="js-sub">Short</option>
                               <option value="long" class="js-sub">Long</option>
                            <?php
                            //$array = array(0 => $this->lang->line("question_bank_select"));
                            foreach (range(0,10) as $i) {
                                //$array[$i] = $i;
                                echo '<option value="'.$i.'" class="js-nonsub">'.$i.'</option>';
                            }
                            //echo form_dropdown("totalOption", $array, set_value("totalOption"), "id='totalOption' class='form-control select2'");
                            ?>
                            </select>
                        </div>
                       
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('totalOption'); ?>
                        </span>
                    </div>

                    <div id="in"></div>

                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-8">
                            <input type="submit" class="btn btn-success" value="<?=$this->lang->line("add_class")?>" >
                        </div>
                    </div>

                    
                </form>
                
                <input type="hidden" id="ajax-get-subjects-url" value="<?php echo base_url() ?>subject/ajaxGetSubjectsFromClassId">
                <input type="hidden" id="ajax-get-chapters-url" value="<?php echo base_url() ?>subject/ajaxGetChaptersFromSubjectId">
                <input type="hidden" id="ajax-get-question-type-from-question-type-id" value="<?php echo base_url() ?>question_bank/ajaxGetQuestionTypeNumberFromQuestionTypeId">
                
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $('.select2').select2();
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
            $(".image-preview-input-title").text("<?=$this->lang->line('question_bank_file_browse')?>");
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
                $(".image-preview-input-title").text("<?=$this->lang->line('question_bank_file_browse')?>");
                $(".image-preview-clear").show();
                $(".image-preview-filename").val(file.name);
                img.attr('src', e.target.result);
                $(".image-preview").attr("data-content",$(img)[0].outerHTML).popover("show");
                $('.content').css('padding-bottom', '100px');
            }
            reader.readAsDataURL(file);
        });
    });

    // $('#question').jqte();
    // $('#explanation').jqte();
    // CKEDITOR.replace('question');
    // CKEDITOR.replace('explanation');
    tinymce.init({
        selector: '#question,#explanation',
        width: 600,
        height: 300,
        plugins: [
        'advlist autolink link image lists charmap print preview hr anchor pagebreak',
        'searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking',
        'table emoticons template powerpaste help tiny_mce_wiris'
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
    
    $(function () {
        $('#totalOptionDiv').hide();
    });

    $(document).ready(function() {
        var totalOptionID = '<?=$totalOptionID?>';
        if(totalOptionID > 0) {
            $('#totalOptionDiv').show();

            if(totalOptionID == 4)
            {
                $('.js-sub').show();
                $('.js-nonsub').hide();
            }
            else
            {
                $('.js-sub').hide();
                $('.js-nonsub').show();
            }
        }
    });

    $('#type_id').change(function() {
        let question_type_id = $(this).val();
        let url = $('#ajax-get-question-type-from-question-type-id').val()
        $.ajax({
          url: url + "?question_type_id=" + question_type_id,
        }).done(function( data ) {
            $('#type').val(data);
            $('#type').trigger('change');
        });
    })

    $('#type').change(function() {
        $('#in').children().remove();
        var type = $(this).val();
        var type_id = parseInt($('#type_id').val());
        
        if(type == 0) {
            $('#totalOptionDiv').hide();
        } else if(type_id == 4) {
            $('#totalOption').val(2)
            $('#totalOption').trigger('change');
            $('#totalOptionDiv').show();
        }
        else {
            $('#totalOption').val(0);
            $('#totalOption').trigger('change');
            $('#totalOptionDiv').show();
        }

        if(type==4)
            {   
                $('.js-sub').show();
                $('.js-nonsub').hide();
            }
            else
            {   
                $('.js-sub').hide();
                $('.js-nonsub').show();
            }

    });

    $('#totalOption').change(function(event) {
        var valTotalOption = parseInt($(this).val());
        var type = $('#type').val();
        var type_id = parseInt($('#type_id').val());

        if(type_id == 4 && valTotalOption != 2) {
            alert('True False must have 2 options');
            $('#type_id').trigger('change');
            return;
        }
        
        if(parseInt(valTotalOption) !=0) {
            var opt = type_id == 4 ? ['', 'True', 'False'] : [];
            var ans = [];
            var count =  $('.coption').size();
            for(j=1; j<=count; j++) {
                if(type == 3) {
                    opt[j] = $('#answer'+j).val();
                } else {
                    opt[j] = $('#option'+j).val();
                    if($('#ans'+j).prop('checked')) {
                        ans[j] = 'checked="checked"';
                    }
                }
            }
                        
            $('#in').children().remove();
            for(i=1; i<=valTotalOption; i++) {
                if($('#in').size())
                    $('#in').append(formHtmlData(i, type, opt[i], ans[i]));
                else
                    $('#in').append(formHtmlData(i, type));
            }
        } else {
             $('#in').children().remove();
        }

    });

    function formHtmlData(id, type, value='', checked='') {
        var required = 'required';
        
        if(type == 1) {
            type = 'radio';
        } else if(type == 2) {
            type = 'checkbox';
            required = '';
        } else if(type == 3) {
            var html = '<div class="form-group coption"><label for="answer'+id+'" class="col-sm-2 control-label"><?=$this->lang->line("question_bank_answer")?> '+ id +'</label><div class="col-sm-4"><input type="text" class="form-control" id="answer'+id+'" name="answer[]" value="'+value+'" placeholder="<?=$this->lang->line("question_bank_answer")?> '+id+'" ></div><div class="col-sm-1"></div><span class="col-sm-4 control-label text-red" id="anserror'+id+'"><?php if(isset($form_validation['answer1'])) { echo $form_validation['answer1']; } ?></span></div>';
            return html;
        }

        
        var html = '<div class="form-group coption"><label for="option'+id+'" class="col-sm-2 control-label"><?=$this->lang->line("question_bank_option")?> '+ id +'</label><div class="col-sm-4" style="display:inline-table"><input type="text" class="form-control" id="option'+id+'" name="option[]" value="'+value+'" placeholder="<?=$this->lang->line("question_bank_option")?> '+id+'" ><span class="input-group-addon"><input class="answer" id="ans'+id+'" '+checked+' type="'+type+'" name="answer[]" value="'+id+'" data-toggle="tooltip" data-placement="top" title="Correct Answer" '+ required +' /></span></div><div class="col-sm-3" style="display:inline-table"><input type="file" name="image'+id+'" id="image'+id+'"></div><span class="col-sm-3 control-label text-red" id="anserror'+id+'"><?php if(isset($form_validation['answer1'])) { echo $form_validation['answer1']; } ?></span></div>';    
        
        
        return html;
    }   
</script>

<?php if(customCompute($options) || customCompute($answers)) {
        if($typeID == 3) {
            $options =  $answers;
        } else {
            $options =  $options;
        }
        foreach ($options as $optionKey => $optionValue) { ?>
            <script type="text/javascript">
                var optID = '<?=$optionKey+1?>';
                var optTypeID = '<?=$typeID?>';
                var optVal = '<?=$optionValue?>';
                var optAns = '';
                <?php if($answers) { ?> var optAns = '<?=(in_array($optionKey+1, $answers)) ? 'checked="checked"' : '' ?>'; <?php } ?>
                $('#in').append(formHtmlData(optID, optTypeID, optVal, optAns));               
            </script>
<?php } } ?>

<script>
$(document).on('change', '#class_id', function() {
    let class_id = $(this).val();
    let url = $('#ajax-get-subjects-url').val()
    $('#subject_id').trigger('change');
    $.ajax({
      url: url + "?class_id=" + class_id,
    }).done(function( data ) {
        data = JSON.parse(data);
        $('#ajax-get-subjects').html(data.form);
    });
})

$(document).on('change', '#subject_id', function() {
    let subject_id = $(this).val();
    let url = $('#ajax-get-chapters-url').val()
    $.ajax({
      url: url + "?subject_id=" + subject_id,
    }).done(function( data ) {
        $('#ajax-get-chapters').html(data);
    });
})
</script>
