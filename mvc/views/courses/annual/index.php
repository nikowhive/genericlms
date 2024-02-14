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
                            <small>Annual Plan</small>
                        </div>
                        <?php echo $course->classes . ' ' . $course->subject; ?>
                    </h1>
                    <div class="switch-wrapper">
                        <?php if ($usertypeID == 1) :
                            if (isset($annuals->medias) && !empty($annuals->medias)) :
                        ?>
                                <label class="switch" data-toggle="tooltip" data-placement="top" data-original-title="Publish/Unpublish">
                                    <input type="checkbox" class="switch__input" onclick="changeAnnualStatus('<?= $annuals->id ?>')" class="onoffswitch-small-checkbox" id="switch-course<?= $annuals->id ?>" <?= $annuals->published == '1' ? "checked='checked'" : ''; ?>>
                                    <span class="switch--unchecked">
                                        <i class="fa fa-ban"></i>
                                    </span>
                                    <span class="switch--checked">
                                        <i class="fa fa-check-circle"></i>
                                    </span>
                                </label>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                    <?php if (permissionChecker('annual_plan_add') || permissionChecker('annual_plan_edit')) :
                        if (isset($annuals) && !empty($annuals)) :
                    ?>
                            <a href="<?php echo base_url('annual_plan/edit/' . $annuals->id . '?course=' . $course->id) ?>" class="btn-sm btn btn-success"><i class="fa fa-plus"></i> Edit</a>
                        <?php else : ?>

                            <a href="<?php echo base_url('annual_plan/add?course=' . $course->id) ?>" class="btn-sm btn btn-success"><i class="fa fa-plus"></i> Add</a>
                        <?php endif; ?>
                    <?php endif; ?>
                </header>

                <div class="content">
                    <div class="col">
                        <!--Accordion wrapper-->
                        <div class="accordion md-accordion" id="accordionEx" role="tablist" aria-multiselectable="true">
                            <?php if (isset($annuals) && !empty($annuals)) : ?>

                                <?php foreach ($annuals->medias as $val) : ?>
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="sortable-header">
                                                <!-- <div class="panned-icon">⋮⋮</div> -->
                                                <div class="panned-icon">
                                                    <i class='fa <?= checkFileExtension($val->file) ?>' aria-hidden='true'></i>
                                                </div>
                                                <h3 class="sortable-title" data-id="<?= $val->id ?>">

                                                    <?php echo $val->caption; ?>
                                                    <!-- <?php echo $val->file; ?> -->
                                                </h3>
                                                <!-- <iframe src="<?= base_url('uploads/images/' . $val->file) ?>" width="550px" height="400px" style="border: none;"></iframe> -->
                                            </div>
                                        </div>
                                        <!-- <i class='fa <?= checkFileExtension($val->file) ?>' float="right"></i>
                                    <h5 class="card-title"> <?php echo $val->caption; ?></h5>
                                </div> -->
                                        <?php
                                        $allowed = array('gif', 'png', 'jpg');
                                        $txt_ext = array('pdf', 'xlsx', 'docx', 'csv', 'doc', 'xls');
                                        $vdo_extension = array('mp4', 'mov', 'flv', 'avi');

                                        $ext = pathinfo($val->file, PATHINFO_EXTENSION);
                                        if (in_array($ext, $allowed)) {
                                            echo '<div class="annual_preview"><img class="img-preview" width="100%" src=' . base_url('uploads/images/') . $val->file . ' /></div>';
                                        } elseif (in_array($ext, $txt_ext)) {
                                            echo '<a  type="button" class="btn btn-sm" role="button" target="_blank" rel="noopener noreferrer"  href="' . base_url('uploads/images/') . $val->file . '">Download this <b>' . $ext . ' </b>for preview</a>';
                                            // echo '<embed src="' . base_url('uploads/images/') . $file . '" width="600px" height="350px" />';
                                        } else {
                                            echo '';
                                        }
                                        ?>

                                    </div>
                                <?php endforeach; ?>

                            <?php endif; ?>

                        </div>
                        <!-- Accordion wrapper -->
                    </div>
                </div>


            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="myModal1" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <img src="" id="imagepreview" style="width: 100%;">
            </div>
        </div>
    </div>
</div>



<script type="text/javascript" src="<?php echo base_url('assets/lightbox2-2.11.3/dist/js/lightbox.js'); ?>"></script>
<script>
    $('.annual_preview').on('click', function(e) {
        var link = $(this).find('img').attr('src');
        modal.style.display = "block";
        modalImg.src = link;
    });
</script>

<?php if ($usertypeID == 1 || $usertypeID == 2) : ?>
    <script>
        let url = "<?= base_url('annual_plan/ajaxChangeAnnualStatus/') ?>";

        function changeAnnualStatus(id) {

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