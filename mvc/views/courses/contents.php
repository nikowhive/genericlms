<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>

<style>
 strong{
        font-weight: bold;
    }
</style>

<div class="right-side--fullHeight  ">
    <div class="row w-100 ">
        <?php $this->load->view("components/course_menu"); ?>
        <div class="course-content">
            <div class="container container--sm">
                <header class="pg-header mt-4">
                    <h1 class="pg-title">
                        <div>
                            <small>Course</small>
                        </div>
                        <?php echo $course->classes . ' ' . $course->subject; ?>
                    </h1>
                    <?php if (permissionChecker('courses_add')) : ?>
                        <a href="javascript:;" data-toggle="modal" data-target="#addContent" class="btn-sm btn btn-success"><i class="fa fa-plus"></i> Add Content</a>
                    <?php endif; ?>
                </header>

                <div class="sortable-list">
                    <ul id="course" class="course-wrapper">
                       
                                <?php foreach ($contents as $content) { ?>
                                    <li style="margin-bottom:20px;">
                                        <div class="sortable-block sortable-blockunit">
                                            <div class="sortable-header">
                                                <!-- <div class="panned-icon">⋮⋮</div> -->
                                                <div class="panned-icon"><i class="fa fa-book" aria-hidden="true"></i></div>
                                                <h3 class="sortable-title">
                                                    <small>
                                                        <?php echo $content->unit; ?> - <?php echo $content->chapter_name; ?></small>
                                                    <a href="#viewContent" class="view_content" data-toggle="modal" data-id="<?php echo $content->id; ?>"><?php echo $content->content_title; ?></a>
                                                </h3>
                                            </div>
                                            <div class="sortable-actions">
                                                
                                                    <?php if ($usertypeID == 1 || $usertypeID == 2) : ?>
                                                        <label class="switch" data-toggle="tooltip" data-placement="top" data-original-title="Publish/Unpublish">
                                                            <input type="checkbox" class="switch__input" onclick="changeContentStatus('<?= $content->id ?>')" class="onoffswitch-small-checkbox" id="switch-content<?= $content->unit_id ?>" <?= $content->published == '1' ? "checked='checked'" : ''; ?>>
                                                            <span class="switch--unchecked">
                                                                <i class="fa fa-ban"></i>
                                                            </span>
                                                            <span class="switch--checked">
                                                                <i class="fa fa-check-circle"></i>
                                                            </span>
                                                        </label>
                                                    <?php endif; ?>


                                                    <?php if(
                                                        permissionChecker('content_edit')
                                                        || permissionChecker('content_delete') 
                                                ) : ?>
                                                    <div class="dropdown">
                                                        <a href="#" class=" " data-toggle="dropdown"> ⋮</a>
                                                        <ul id="menu2" class="dropdown-menu" aria-labelledby="drop5">
                                                        <?php if (permissionChecker('content_edit')) : ?>
                                                            <li>
                                                                <a href="<?php echo base_url() . 'courses/editcontent/' . $content->id . '?course=' . $course->id . '&link=' . 'contents' ?>">Edit Content</a>
                                                            </li>
                                                            <?php endif; ?>
                                                            <?php if (permissionChecker('content_delete')) : ?>
                                                            <li>
                                                                <a onclick="return confirm('you are about to delete a record. This cannot be undone. are you sure?')" href="<?php echo base_url() . 'courses/deletecontent/' . $content->id . '?course=' . $course->id . '&link=' . 'contents' ?>">Delete Content</a>
                                                            </li>
                                                            <?php endif; ?>
                                                        </ul>
                                                    </div>
                                                    <?php endif; ?>

                                               
                                            </div>
                                        </div>
                                    </li>
                        <?php 
                        
                        } ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- add course modal starts -->
<div class="modal fade" tabindex="-1" role="dialog" id="addContent">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title">Add new content</h3>
            </div>
            <div class="modal-body">
                <form method="post">

                    <div class="form-group">
                        <div class="md-form md-form--select">
                            <?php
                            $array = array();
                            $array[0] = $this->lang->line("select_unit");
                            foreach ($units as $unit) {
                                $array[$unit->id] = $unit->unit_name;
                            }
                            echo form_dropdown("unit_id", $array, set_value("unit_id"), "id='unit_id' class='mdb-select'");
                            ?>
                            <label for="" class="mdb-main-label">Select Unit</label>
                            <span class="text-danger error">
                                <p id="unit-error"></p>
                            </span>
                        </div>
                    </div>


                    <div class="form-group">
                        <div class="md-form md-form--select">
                            <?php
                            $array = array();
                            $array[0] = $this->lang->line("select_chapter");
                            foreach ($chapters as $chapter) {
                                $array[$chapter->id] = $chapter->chapter_name;
                            }
                            echo form_dropdown("chapter_id", $array, set_value("chapter_id"), "id='chapter_id' class='mdb-select'");
                            ?>
                            <label for="" class="mdb-main-label">Select Chapter</label>
                            <span class="text-danger error">
                                <p id="chapter-error"></p>
                            </span>
                        </div>
                    </div>
                </form>
                <input type="hidden" id="ajax-get-chapter-url" value="<?php echo base_url() ?>chapter/ajaxGetChaptersFromUnitId">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" id="add-content" class="btn btn-primary">Add Content</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- add course modal ends -->

<!-- add content modal starts -->
<div class="modal fade" tabindex="-1" role="dialog" id="viewContent">
    <div class="modal-dialog modal-dialog-scrollable" role="document">
        <div class="modal-content" id="view_ajax_content">

        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- add content modal ends -->

<script>
    $('.view_content').on('click', function(e) {
        e.preventDefault();
        $('#viewContent').hide();
        contentid = $(this).data("id");
        $('#view_ajax_content').empty();
        $.ajax({
            type: 'POST',
            url: "<?= base_url('courses/getContentByAjax') ?>",
            dataType: "html",
            data: {
                id: contentid,
                course: <?php echo $course->id ?>
            },
            success: function(data) {
                $('#view_ajax_content').append(data);

            }
        });
    });

    $('.content-switch').click(function(e) {
        contentid = $(this).attr("contentid")
        $.ajax({
            type: 'POST',
            url: "<?= base_url('courses/ajaxChangeContentStatus/') ?>" + contentid,
            dataType: "html",
            success: function(data) {
                console.log('updated');
            }
        });
    });

    $(document).on('change', '#unit_id', function() {
        let unit_id = $(this).val();
        let url = $('#ajax-get-chapter-url').val()

        $.ajax({
            url: url + "?unit_id=" + unit_id,
        }).done(function(data) {
            $('#chapter_id').html(data);
            $('.mdb-select').material_select('destroy');
            $('.mdb-select').material_select();
        });
    })

    $('#add-content').click(function(e) {
        unit_id = $('#unit_id').val();
        chapter_id = $('#chapter_id').val();

        // $('#unit-error').text('');
        // $('#chapter-error').text('');
        // if (unit_id == 0) {
        //     $('#unit-error').text('Unit is empty.');
        // }
        // if (chapter_id == 0) {
        //     $('#chapter-error').text('Chapter is empty.');
        // }
        // if (unit_id != 0 && chapter_id != 0) {
            window.location.href = "<?php echo base_url('courses/addcontent/'); ?>" + chapter_id + "?course=" + <?php echo $course->id ?> + "&link=contents"
        // }
    })
</script>

<?php if ($usertypeID == 1 || $usertypeID == 2) : ?>
    <script>
        let url = "<?= base_url('courses/ajaxChangeContentStatus/') ?>";

        function changeContentStatus(id) {

            $.post(url + id).done(function() {
                $('#loading').hide();
                toastr["success"]("Status changed.")
                toastr.options = {
                    "closeButton": true,
                    "debug": false,
                    "newestOnTop": false,
                    "progressBar": false,
                    "positionClass": "toast-top-right",
                    "preventDuplicates": false,
                    "onclick": null,
                    "showDuration": "500",
                    "hideDuration": "500",
                    "timeOut": "5000",
                    "extendedTimeOut": "1000",
                    "showEasing": "swing",
                    "hideEasing": "linear",
                    "showMethod": "fadeIn",
                    "hideMethod": "fadeOut"
                }
            }).fail(function(error) {
                $('#loading').hide();
                toastr["error"](error.responseText)
                toastr.options = {
                    "closeButton": true,
                    "debug": false,
                    "newestOnTop": false,
                    "progressBar": false,
                    "positionClass": "toast-top-right",
                    "preventDuplicates": false,
                    "onclick": null,
                    "showDuration": "500",
                    "hideDuration": "500",
                    "timeOut": "5000",
                    "extendedTimeOut": "1000",
                    "showEasing": "swing",
                    "hideEasing": "linear",
                    "showMethod": "fadeIn",
                    "hideMethod": "fadeOut"
                }
            });
        }
    </script>
<?php endif; ?>