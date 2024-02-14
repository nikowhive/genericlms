<style>
    .modal-body .read-more a {
        color: #236d37;
        font-size: 13px;
        font-weight: bold;
        float: right;
    }
</style>
<div class="modal fade in" tabindex="-1" role="dialog" style="display: block;" aria-hidden="false">
    <div class="modal-dialog modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h3 class="modal-title"><?php echo $this->data['daily']->title; ?></h3>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                  
                    <?php if(isset($this->data['chapter']->unit)): ?>
                        <div class="row ">
                            <div class="col-md-12 ml-auto"><b>Unit</b><br><?php echo $this->data['chapter']->unit  ?>
                            </div>
                        </div>
                        <hr>
                    <?php endif; ?> 


                    <?php if(isset($this->data['chapter']->chapter_name)): ?> 
                        <div class="row ">
                            <div class="col-md-12 ml-auto"><b>Chapter</b><br><?php echo $this->data['chapter']->chapter_name  ?>
                            </div>
                        </div>
                        <hr>
                    <?php endif; ?> 

                    <?php if(isset($this->data['daily']->absent_student_count)): ?>
                        <div class="row ">
                            <div class="col-md-12 ml-auto"><b>Absent Students: </b><?php echo $this->data['daily']->absent_student_count  ?>
                            </div>
                        </div>
                        <hr>
                    <?php endif; ?> 

                    <div class="row ">
                        <div class="col-md-12 ml-auto description more"><b>Activities</b><?php echo $this->data['daily']->activities  ?>
                        </div>
                        <?php if (strlen($this->data['daily']->activities) > 500) { ?>
                            <div class="read-more"><a href="#" class=""><span>READ MORE</span></a></div>
                        <?php } ?>
                    </div>
                    <hr>
                    <div class="row ">
                        <div class="col-md-12 ml-auto description more"><b>Assignment</b><?php echo $this->data['daily']->assignments  ?>
                        </div>
                        <?php if (strlen($this->data['daily']->assignments) > 500) { ?>
                            <div class="read-more"><a href="#" class=""><span>READ MORE</span></a></div>
                        <?php } ?>
                    </div>
                    <hr>
                    <div class="row ">
                        <div class="col-md-12 ml-auto description more"><b>Feedback</b><?php echo $this->data['daily']->feedback  ?>
                        </div>
                        <?php if (strlen($this->data['daily']->feedback) > 500) { ?>
                            <div class="read-more"><a href="#" class=""><span>READ MORE</span></a></div>
                        <?php } ?>
                    </div>
                    <hr>
                    <div class="row ">
                        <div class="col-md-12 ml-auto description more"><b>Remark</b><?php echo $this->data['daily']->remarks  ?>
                        </div>
                        <?php if (strlen($this->data['daily']->remarks) > 500) { ?>
                            <div class="read-more"><a href="#" class=""><span>READ MORE</span></a></div>
                        <?php } ?>
                    </div>

                   
                    <?php if (customCompute($daily_medias)) { ?>
                        <br>
                        <div class="content">
                            <div class="row">
                                <?php foreach ($daily_medias as $v) : ?>
                                    <div class="col-sm-6" id="pip-<?= $v->id; ?>" style="margin-bottom: 1em">
                                        <div class="card text-center">
                                            <div class="card-body">
                                                <div class="">
                                                    <div class="panned-icon">
                                                        <i class='fa <?= checkFileExtension($v->file) ?>' aria-hidden='true'></i>
                                                    </div>
                                                    <p class="card-text"><small><?= substr($v->caption, 0, 20) ?>.<?= pathinfo($v->file, PATHINFO_EXTENSION); ?></small></p>
                                                </div>
                                                <?php
                                                $fileType = checkFileExtension($v->file);
                                                if ($fileType == 'fa-picture-o') { ?>
                                                    <a class="btn btn-danger myImg1" data-link="<?php echo base_url('uploads/images/') . $v->file; ?>" href="javascript:void(0)">View</a>
                                                <?php } elseif ($fileType == 'fa-file-pdf-o') { ?>
                                                    <a class="btn btn-danger" target="_blank" href="<?php echo base_url('uploads/images/') . $v->file; ?>">Preview</a>
                                                <?php } else { ?>
                                                   <a class="btn btn-info" href="<?php echo base_url('uploads/images/') . $v->file; ?>"> Download  </a>
                                               <?php } ?>

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

    $(document).ready(function() {
    $(".modal-body .read-more a").click(function(e) {
            e.preventDefault();
            $(this).toggleClass("active");
            $(this).parent().siblings('.description').toggleClass('full-text');
    });
});
</script>