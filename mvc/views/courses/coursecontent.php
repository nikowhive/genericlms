
<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa fa-sitemap"></i> Content Listing</h3>

        <ol class="breadcrumb">
            
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-12">
                <h5 class="page-header">
                    <a href="<?php echo base_url('courses/addcontent/'.$coursechapter_id) ?>">
                        <i class="fa fa-plus"></i> 
                        Add Content
                    </a>
                </h5>
                <div id="hide-table">
                    <table id="example1" class="table table-striped table-bordered table-hover dataTable no-footer">
                        <thead>
                            <tr>
                                
                                
                                <th class="col-lg-2">Content</th>
                                <th class="col-lg-2">Coverage</th>
                                
                                <th class="col-lg-3">Status</th>
                                <th class="col-lg-3">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(customCompute($coursecontent)) {$i = 1; foreach($coursecontent as $content) { ?>
                                <tr>
                                    
                                    
                                    <td data-title="<?=$this->lang->line('classes_name')?>">
                                        <?php echo $content->chapter_content; ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('classes_name')?>">
                                        <?php echo $content->percentage_coverage; ?>
                                    </td>
                                    
                                    <td data-title="Course Published">
                                        
                                            <form method="post" action="<?php echo base_url() ?>courses/postChangeContentStatus/<?php echo $content->id; ?>">
                                                <div class="onoffswitch-small">
                                                    <input type="checkbox"  class="onoffswitch-small-checkbox" name="published" <?php if($content->published == '1') { ?> checked='checked' <?php } if($content->published == '1')  echo "value='2'";  else echo "value='1'"; ?>>
                                                    <label for="myonoffswitch" class="onoffswitch-small-label">
                                                        <span class="onoffswitch-small-inner"></span>
                                                        <span class="onoffswitch-small-switch"></span>
                                                    </label>
                                                </div>
                                            </form>

                                        
                                    </td>
                                    <td>
                                        <?php echo anchor('courses/editcontent/'.$content->id, "<i class='fa fa-edit'></i>", "class='btn btn-primary btn-xs mrg' data-placement='top' data-toggle='tooltip' data-original-title='Edit'"); ?>
                                        <?php echo anchor('courses/deletecontent/'.$content->id, "<i class='fa fa-trash-o'></i>", "class='btn btn-primary btn-xs mrg' data-placement='top' data-toggle='tooltip' data-original-title='Delete'"); ?>
                                        
                                    </td>
                            
                                    
                                </tr>
                            <?php $i++; }} ?>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>
<script>
    $('.onoffswitch-small').click(function(e) {
        $(this).parent().submit();  
    })
</script>