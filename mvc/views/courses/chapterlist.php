
<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa fa-sitemap"></i> Chapter List</h3>

        <ol class="breadcrumb">
            
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-12">
                
                <div id="hide-table">
                    <table id="example1" class="table table-striped table-bordered table-hover dataTable no-footer">
                        <thead>
                            <tr>
                                
                                
                                <th class="col-lg-2">Chapter Name</th>
                                <th class="col-lg-2">Status</th>
                                
                                <th class="col-lg-3">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(customCompute($courseunitchapters)) {$i = 1; foreach($courseunitchapters as $courseunitchapter) { ?>
                                <tr>
                                    
                                    
                                    <td data-title="Unit Name">
                                        <?php echo $courseunitchapter->chapter_name; ?>
                                    </td>
                                    <td>
                                        <form method="post" action="<?php echo base_url() ?>courses/postChangeChapterStatus/<?php echo $courseunitchapter->id; ?>">
                                            <div class="onoffswitch-small">
                                                <input type="checkbox"  class="onoffswitch-small-checkbox" name="published" <?php if($courseunitchapter->published == '1') { ?> checked='checked' <?php } if($courseunitchapter->published == '1')  echo "value='2'";  else echo "value='1'"; ?>>
                                                <label for="myonoffswitch" class="onoffswitch-small-label">
                                                    <span class="onoffswitch-small-inner"></span>
                                                    <span class="onoffswitch-small-switch"></span>
                                                </label>
                                            </div>
                                        </form>
                                    </td>
                                    <td>
                                        <?php echo anchor('courses/addcontent/'.$courseunitchapter->id, "Add Content</i>", "class='btn btn-primary btn-xs mrg' data-placement='top' data-toggle='tooltip' data-original-title='Add Content'"); ?>
                                        <?php echo anchor('courses/addfiles/'.$courseunitchapter->id, "Add Files", "class='btn btn-primary btn-xs mrg' data-placement='top' data-toggle='tooltip' data-original-title='Add Files'"); ?>
                                        <?php echo anchor('courses/addquizzes/'.$courseunitchapter->id, "Add Quizzes</i>", "class='btn btn-primary btn-xs mrg' data-placement='top' data-toggle='tooltip' data-original-title='Add Question'"); ?>
                                        <?php echo anchor('courses/contentlist/'.$courseunitchapter->id, "List Content</i>", "class='btn btn-primary btn-xs mrg' data-placement='top' data-toggle='tooltip' data-original-title='List Content'"); ?>
                                        <?php echo anchor('courses/chapterdetails/'.$courseunitchapter->id, "View Details</i>", "class='btn btn-primary btn-xs mrg' data-placement='top' data-toggle='tooltip' data-original-title='View Details'"); ?>
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
