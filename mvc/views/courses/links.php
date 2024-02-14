<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>


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
                    <?php if (permissionChecker('link_add')) : ?>
                        <a href="javascript:;" data-toggle="modal" data-target="#addLink" class="btn-sm btn btn-success"><i class="fa fa-plus"></i> Add link</a>
                    <?php endif; ?>
                </header>

                <div class="sortable-list">
                    <ul id="course" class="course-wrapper">
                        <?php foreach ($links as $link) { ?>
                            <li style="margin-bottom:20px;">
                                <div class="sortable-block sortable-blockunit">
                                    <div class="sortable-header">

                                        <div class="panned-icon"><i class='fa <?= checkLinkType($link->type) ?>' aria-hidden='true'></i></div>
                                        <h3 class="view-link sortable-title" data-id="<?= $link->id ?>">
                                            <small>
                                                <?php echo $link->unit; ?> - <?php echo $link->chapter_name; ?></small>
                                            <?php
                                            $url = $link->courselink;
                                            $url = (strncasecmp('http://', $url, 7) && strncasecmp('https://', $url, 8) ? 'http://' : '') . $url;
                                            ?>
                                            <?php echo namesorting($url, 30); ?>
                                        </h3>
                                    </div>
                                    <div class="sortable-actions">

                                        <?php if ($usertypeID == 1 || $usertypeID == 2) : ?>
                                            <label class="switch" data-toggle="tooltip" data-placement="top" data-original-title="Publish/Unpublish">
                                                <input type="checkbox" class="switch__input" onclick="changeLinkStatus('<?= $link->id ?>')" class="onoffswitch-small-checkbox" id="switch-link<?= $link->unit_id ?>" <?= $link->published == '1' ? "checked='checked'" : ''; ?>>
                                                <span class="switch--unchecked">
                                                    <i class="fa fa-ban"></i>
                                                </span>
                                                <span class="switch--checked">
                                                    <i class="fa fa-check-circle"></i>
                                                </span>
                                            </label>
                                        <?php endif; ?>

                                        <?php
                                        $url = $link->courselink;
                                        $url = (strncasecmp('http://', $url, 7) && strncasecmp('https://', $url, 8) ? 'http://' : '') . $url;
                                        ?>



                                        <?php if(
                                                        permissionChecker('link_edit')
                                                        || permissionChecker('link_delete') 
                                                ) : ?>
                                        <div class="dropdown">
                                            <a href="#" class=" " data-toggle="dropdown"> ⋮</a>
                                            <ul id="menu2" class="dropdown-menu" aria-labelledby="drop5">
                                                <?php if (permissionChecker('link_edit')) : ?>
                                                    <li>
                                                        <a href="<?php echo base_url() . 'courses/edit_link/' . $link->id . '?course=' . $course->id . '&link=' . 'links'  ?>">Edit Link</a>
                                                    </li>
                                                <?php endif; ?>
                                                <?php if (permissionChecker('link_delete')) : ?>
                                                    <li>
                                                        <a onclick="return confirm('you are about to delete a record. This cannot be undone. are you sure?')" href="<?php echo base_url() . 'courses/deletelink/' . $link->id . '?course=' . $course->id . '&link=' . 'links' ?>">Delete Link</a>
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
<div class="modal fade" tabindex="-1" role="dialog" id="addLink">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title">Add new link</h3>
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
                <button type="button" id="add-link" class="btn btn-primary">Add Link</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- add course modal ends -->

<script type="text/javascript" src="<?php echo base_url('assets/lightbox2-2.11.3/dist/js/lightbox.js'); ?>"></script>

<script>
    $('.link-switch').click(function(e) {
        linkid = $(this).attr("linkid")
        $.ajax({
            type: 'POST',
            url: "<?= base_url('courses/ajaxChangeLinkStatus/') ?>" + linkid,
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

    $('#add-link').click(function(e) {
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
        window.location.href = "<?php echo base_url('courses/addlinks/'); ?>" + chapter_id + "?course=" + <?php echo $course->id ?> + "&link=links"
        // }
    })
</script>

<?php if ($usertypeID == 1 || $usertypeID == 2) : ?>
    <script>
        let url = "<?= base_url('courses/ajaxChangeLinkStatus/') ?>";

        function changeLinkStatus(id) {

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