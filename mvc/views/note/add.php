<style>
      * {
        font-family: sans-serif;
      }
      .selections{
          /* height: 110px;
          overflow-y: auto; */
      }
      .selected{
          /* height: 110px;
          overflow-y: auto; */
      }
    </style>
    <link rel="stylesheet" href="<?php echo base_url('assets/inilabs/jquery.tree-multiselect.min.css'); ?>">
    <script src="<?php echo base_url('assets/jqueryUI/jqueryui.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/datepicker/datepicker.js'); ?>"></script>
    <script src="<?php echo base_url('assets/inilabs/jquery.tree-multiselect.js'); ?>"></script>
<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa fa-calendar"></i> <?=$this->lang->line('panel_title')?></h3>

       
        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li><a href="<?=base_url("note/index")?>"><?=$this->lang->line('menu_note')?></a></li>
            <li class="active"><?=$this->lang->line('menu_add')?> <?=$this->lang->line('menu_note')?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-12">
                <form class="form-horizontal" role="form" method="post">

                    <?php 
                        if(form_error('note')) 
                            echo "<div class='form-group has-error' >";
                        else     
                            echo "<div class='form-group' >";
                    ?>
                        <label for="note" class="col-sm-1 control-label">
                            <?=$this->lang->line("note_note")?> <span class="text-red">*</span>
                        </label>
                        <div class="col-sm-8">
                            <textarea class="form-control" id="note" name="note" ><?=set_value('note')?></textarea>
                        </div>
                        <span class="col-sm-3 control-label">
                            <?php echo form_error('note'); ?>
                        </span>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-offset-1 col-sm-8">
                            <input type="submit" class="btn btn-success" value="<?=$this->lang->line("add_class")?>" >
                        </div>
                    </div>

                </form>

            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function(){
        $('input[type="checkbox"]').click(function(){
            if($(this).prop("checked") == true){
              $('#divemail').hide();
            }
            else if($(this).prop("checked") == false){
                $('#divemail').show();
            }
        });
    });
</script>


<script type="text/javascript">

$('#date').datepicker({
    dateFormat: 'dd-mm-yy',
    startDate:'<?=$schoolyearsessionobj->startingdate?>',
    endDate:'<?=$schoolyearsessionobj->endingdate?>',
});


tinymce.init({
        selector: '#note',
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

$.getJSON( "<?=base_url('mailandsms/getAlldatas')?>", function( data ) {

        var $select = $('#test-select-1');
        $.each( data, function( key, val ) {
          //console.log(val.email);
          var $option = $('<option value="'+val.id+val.usertypeID+'" data-section="'+val.category2+'/'+val.category1+'">'+val.name+'</option>');
          $select.append($option);    

        });

        $select.treeMultiselect({ enableSelectAll: true, sortable: true, searchable: true, startCollapsed: true});        
      });
</script>
