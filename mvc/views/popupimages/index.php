<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa fa-pencil"></i> <?=$this->lang->line('panel_title')?></h3>
        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li class="active"><?=$this->lang->line('popup_image_menu')?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-12">

                <?php if(permissionChecker('popupimages_add')) { ?>
                    <h5 class="page-header">
                        <a href="<?php echo base_url('popupimages/add') ?>" data-toggle="modal" data-target="#imageModal">
                            <i class="fa fa-plus"></i> 
                            <?=$this->lang->line('add_title')?>
                        </a>
                    </h5>
                <?php } ?>

                <div id="hide-table">
                    <table id="example1" class="table table-striped table-bordered table-hover dataTable no-footer">
                        <thead>
                            <tr>
                                <th class="col-lg-1"><?=$this->lang->line('slno')?></th>
                                <th class="col-lg-2"><?=$this->lang->line('popup_image_title')?></th>
                                <th class="col-lg-2"><?=$this->lang->line('popup_image_file')?></th>
                                <th class="col-lg-2"><?=$this->lang->line('popup_image_disabled')?></th>
                                <?php if(permissionChecker('popupimages_delete')) { ?>
                                <th class="col-lg-2"><?=$this->lang->line('action')?></th>
                                <?php } ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(customCompute($images)) {$i = 1; foreach($images as $image) { ?>
                                <tr>
                                    <td data-title="<?=$this->lang->line('slno')?>">
                                        <?php echo $i;
                                       ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('popup_image_title')?>">
                                       <?php echo $image->title;?> 
                                    </td>
                                    <td data-title="<?=$this->lang->line('popup_image_file')?>">
                                        <img src="<?=base_url("uploads/popupimages/".$image->file_name);?>" width="80"/>
                                    </td>
                                    <td>
                                       <a href="<?php echo  base_url('popupimages/updateView/'.$image->imageID) ?>" onclick="return confirm('you are about to update a record?')" title="Update" data-id="<?php echo $image->imageID;?>">
                                          <span class="label label-<?php echo $image->disabled == 0 ?'primary':'success' ?>">
                                            <?php echo $image->disabled == 1 ?'Yes':'No' ?>
                                          </span>
                                       </a>
                                        </td>
                                    <?php if(permissionChecker('popupimages_delete')) { ?>
                                    <td data-title="<?=$this->lang->line('action')?>">
                                        <?php  
                                            echo btn_delete('popupimages/delete/'.$image->imageID, $this->lang->line('delete'));
                                         ?>
                                    </td>
                                    <?php } ?>
                                </tr>
                            <?php $i++; }} ?>
                        </tbody>
                    </table>
                </div>


            </div> <!-- col-sm-12 -->
        </div><!-- row -->
    </div><!-- Body -->
</div><!-- /.box -->

<!-- Modal -->
<div class="modal fade" id="imageModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Upload Popup Image</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="<?=base_url("popupimages/upload")?>" class="form-horizontal" role="form" method="post" enctype="multipart/form-data">
      <div class="modal-body">

                <div class='form-group' >
                    <label for="photo" class="col-sm-3 control-label col-xs-8 col-md-2">
                        Title <span class="text-red">*</span>
                    </label>
                    <div class="col-sm-9">
                    <input type="text" name="title" class="form-control" require/>
                    </div>
                </div>    
                <div class='form-group' >
                    <label for="photo" class="col-sm-3 control-label col-xs-8 col-md-2">
                        Image <span class="text-red">*</span>
                    </label>

                    <div class="col-sm-9">
                        <div class="input-group image-preview">
                            <input type="text" class="form-control image-preview-filename" disabled="disabled">
                            <span class="input-group-btn">
                                <button type="button" class="btn btn-default image-preview-clear" style="display:none;">
                                    <span class="fa fa-remove"></span>
                                    Clear
                                </button>
                                <div class="btn btn-success image-preview-input">
                                    <span class="fa fa-repeat"></span>
                                    <span class="image-preview-input-title">
                                    File Browse</span>
                                    <input type="file" require accept="image/png, image/jpeg, image/gif" name="file"/>
                                </div>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Upload</button>
      </div>
      </form>
    </div>
  </div>
</div>

<script type="text/javascript">
    
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
            $(".image-preview-input-title").text("<?=$this->lang->line('media_file_browse')?>"); 
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
                $(".image-preview-input-title").text("<?=$this->lang->line('media_file_browse')?>");
                $(".image-preview-clear").show();
                $(".image-preview-filename").val(file.name);
            }        
            reader.readAsDataURL(file);
        });  
    });    
</script>