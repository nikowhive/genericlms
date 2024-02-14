<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa fa-sitemap"></i> Resource List</h3>

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
                                <th class="col-lg-2">Content</th>
                                <th class="col-lg-2">Coverage</th>
                                <th class="col-lg-3">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(customCompute($coursechapterresources)) {$i = 1; foreach($coursechapterresources as $coursechapterresource) { ?>
                                <tr>
                                    <td data-title="Content">
                                        <?php echo $coursechapterresource->chapter_content; ?>
                                    </td>
                                    <td data-title="Content">
                                        <?php echo $coursechapterresource->percentage_coverage; ?>
                                    </td>
                                    
                                    <td>
                                        <?php echo btn_add('courses/addunit', "Add Resource"); ?>
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
