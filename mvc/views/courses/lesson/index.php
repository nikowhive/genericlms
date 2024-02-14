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
                            <small>Lesson Plan</small>
                        </div>
                        <?php echo $course->classes . ' ' . $course->subject; ?>
                    </h1>
                    <?php if (permissionChecker('lesson_plan_add')) :
                    ?>
                        <a href="javascript:;" data-toggle="modal" data-target="#addAttachment" class="btn-sm btn btn-success"><i class="fa fa-plus"></i> Upload Lesson</a>
                    <?php endif; ?>
                </header>

                <div class="sortable-list">
                    <ul id="course" class="course-wrapper">
                        <?php foreach ($lessons as $x => $val) {
                            foreach ($val->version as $y => $version) {
                        ?>
                                <li style="margin-bottom:20px;">
                                    <div class="sortable-block sortable-blockunit">
                                        <div class="sortable-header">

                                            <h3 class="sortable-title" data-id="<?= $val->id ?>">

                                                <?php if ($val->unit_id != NULL && $val->unit_id != 0) : ?>
                                                    <small>
                                                        <?php echo $val->unit;
                                                        echo ($val->chapter_id != null && $val->chapter_id != 0) ? '- ' . $val->chapter_name : '' ?></small>
                                                <?php endif ?>
                                                Lesson: <?php echo $val->title; ?>
                                            </h3>
                                        </div>

                                        <div class="sortable-actions">
                                            
                                                <?php if ($usertypeID == 1) : ?>
                                                    <label class="switch" data-toggle="tooltip" data-placement="top" data-original-title="Publish/Unpublish">
                                                        <input type="checkbox" class="switch__input" onclick="changeLessonStatus('<?= $val->id ?>')" class="onoffswitch-small-checkbox" id="switch-attachment<?= $val->unit_id ?>" <?= $val->published == '1' ? "checked='checked'" : ''; ?>>
                                                        <span class="switch--unchecked">
                                                            <i class="fa fa-ban"></i>
                                                        </span>
                                                        <span class="switch--checked">
                                                            <i class="fa fa-check-circle"></i>
                                                        </span>
                                                    </label>
                                                <?php endif; ?>

                                                <?php if(
                                                        permissionChecker('lesson_plan_view')
                                                        || permissionChecker('lesson_plan_edit') 
                                                ) : ?>
                                                <div class="dropdown">
                                                    <a href="#" class=" " data-toggle="dropdown"> ⋮</a>
                                                    <ul id="menu2" class="dropdown-menu" aria-labelledby="drop5">
                                                    <?php if (permissionChecker('lesson_plan_view')) : ?>
                                                        <li>
                                                            <a href="<?php echo base_url() . 'lesson_plan/view/' . $val->id . '?course=' . $course->id   ?>">View Version</a>
                                                        </li>
                                                        <?php endif; ?>
                                                        <?php if (permissionChecker('lesson_plan_edit')) : ?>
                                                        <li>
                                                            <a href="<?php echo base_url() . 'lesson_plan/edit/' . $val->id . '?course=' . $course->id   ?>">Edit Lesson Plan</a>
                                                        </li>
                                                        <?php endif; ?>
                                                        <?php if($usertypeID == 3 || $usertypeID == 4): ?>
                                                        <li>
                                                            <a href="<?php echo base_url() . 'lesson_plan/studentview/' . $val->id . '?course=' . $course->id   ?>">View Lesson Plan</a>
                                                        </li>
                                                       <?php endif; ?>
                                                    </ul>
                                                 </div>
                                                 <?php endif; ?>
                                               
                                            
                                            <!-- <?php if($usertypeID == 3): ?>
                                                <div class="dropdown">
                                                    <a href="#" class=" " data-toggle="dropdown"> ⋮</a>
                                                    <ul id="menu2" class="dropdown-menu" aria-labelledby="drop5">

                                                        <li>
                                                            <a href="<?php echo base_url() . 'lesson_plan/studentview/' . $val->id . '?course=' . $course->id   ?>">View Lesson Plan</a>
                                                        </li>

                                                    </ul>
                                                </div>
                                            <?php endif; ?> -->
                                        </div>
                                    </div>

                                </li>
                        <?php
                            }
                        } ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- add course modal starts -->
<div class="modal fade" tabindex="-1" role="dialog" id="addAttachment">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title">Add new lesson</h3>
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
                <button type="button" id="add-attachment" class="btn btn-primary">Add Lesson</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- add course modal ends -->





<script type="text/javascript" src="<?php echo base_url('assets/lightbox2-2.11.3/dist/js/lightbox.js'); ?>"></script>
<script>
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

    $('#add-attachment').click(function(e) {
        unit_id = $('#unit_id').val();
        chapter_id = $('#chapter_id').val();
        window.location.href = "<?php echo base_url('lesson_plan/add/'); ?>" + <?php echo $course->id ?> + "?chapter=" + chapter_id + "&unit=" + unit_id;

    })
</script>


<?php if ($usertypeID == 1 || $usertypeID == 2) : ?>
    <script>
        let url = "<?= base_url('lesson_plan/ajaxChangeFileStatus/') ?>";

        function changeLessonStatus(id) {

            $.post(url + id).done(function() {
                $('#loading').hide();
                toastr["success"]("Status changed.")

            }).fail(function(error) {
                $('#loading').hide();
                toastr["error"](error.responseText)

            });
        }
    </script>

<?php endif; ?>