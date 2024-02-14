<div class="modal fade in" tabindex="-1" role="dialog" style="display: block;" aria-hidden="false">
    <div class="modal-dialog modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12 ml-auto"><?php echo $assignmentanswer->content; ?></div>
                    </div>
                    <?php if (customCompute($assignment_answer_medias)) { ?>
                        <br>
                        <div class="content">
                            <div class="row">
                                <?php foreach ($assignment_answer_medias as $v) : ?>
                                    <div class="col-sm-6" id="pip-<?= $v->id; ?>" style="margin-bottom: 1em">
                                        <div class="card text-center">
                                            <div class="card-body">
                                                <div class="">
                                                    <div class="panned-icon">
                                                        <i class='fa <?= checkFileExtension($v->attachment) ?>' aria-hidden='true'></i>
                                                    </div>
                                                    <p class="card-text"><small><?= substr($v->caption, 0, 20) ?>.<?= pathinfo($v->attachment, PATHINFO_EXTENSION); ?></small></p>
                                                </div>
                                                <?php
                                                $fileType = checkFileExtension($v->attachment);
                                                if ($fileType == 'fa-picture-o') { ?>
                                                    <a class="btn btn-danger myImg1" data-link="<?php echo base_url('uploads/images/') . $v->attachment; ?>" href="javascript:void(0)">View</a>
                                                <?php } elseif ($fileType == 'fa-file-pdf-o') { ?>
                                                    <a class="btn btn-danger" target="_blank" href="<?php echo base_url('uploads/images/') . $v->attachment; ?>">Preview</a>
                                                <?php } else {
                                                    echo btn_download('assignment/assignmentdownloadFiles/' . $v->id, $this->lang->line('download'));
                                                } ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<script>
    // Get the modal
    var modal = document.getElementById("feedModal");



    // Get the image and insert it inside the modal - use its "alt" text as a caption
    var img = $('.myImg1');
    var modalImg = document.getElementById("img01");
    var captionText = document.getElementById("caption");

    img.click(function() {
        var link = $(this).data('link');
        modal.style.display = "block";
        modalImg.src = link;
        //   captionText.innerHTML = this.alt;
    });

    // Get the <span> element that closes the modal
    var span = document.getElementsByClassName("closeBtn")[0];

    // When the user clicks on <span> (x), close the modal
    span.onclick = function() {
        modal.style.display = "none";
    }
</script>