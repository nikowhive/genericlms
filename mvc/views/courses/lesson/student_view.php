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
                </header>

                <div class="content">
                    <div class="col">
                        <!--Accordion wrapper-->
                        <div class="accordion md-accordion" id="accordionEx" role="tablist" aria-multiselectable="true">
                            <?php foreach ($versions as $v) : ?>
                                <?php foreach ($v->media as $val) : ?>
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

                                        <?php
                                        $allowed = array('gif', 'png', 'jpg');
                                        $txt_ext = array('pdf', 'xlsx', 'docx', 'csv', 'doc', 'xls');
                                        $vdo_extension = array('mp4', 'mov', 'flv', 'avi');

                                        $ext = pathinfo($val->file, PATHINFO_EXTENSION);
                                        if (in_array($ext, $allowed)) {
                                            echo '<div class="lesson_preview"><img width="100%" src=' . base_url('uploads/images/') . $val->file . ' /></div>';
                                        } elseif (in_array($ext, $txt_ext)) {
                                            echo '<a  type="button" class="btn btn-sm" role="button" target="_blank" rel="noopener noreferrer"  href="' . base_url('uploads/images/') . $val->file . '">Download this <b>' . $ext . ' </b>for preview</a>';
                                            // echo '<embed src="' . base_url('uploads/images/') . $file . '" width="600px" height="350px" />';
                                        } else {
                                            echo '';
                                        }
                                        ?>

                                    </div>

                                <?php endforeach; ?>
                            <?php endforeach; ?>

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

<script>
    $('.lesson_preview').on('click', function(e) {
        var link = $(this).find('img').attr('src');
        modal.style.display = "block";
        modalImg.src = link;
    });
</script>