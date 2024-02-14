<div class="right-side--fullHeight  ">
    <div class="row w-100 ">
        <?php $this->load->view("components/course_menu"); ?>
        <div class="course-content">
            <div class="container container--sm">

                <header class="pg-header mt-4">
                    <h1 class="pg-title">
                        <div><small>Course</small></div>
                        Edit Attachment
                    </h1>
                </header>
                <div class="card card--spaced">
                    <!-- /.box-header -->
                    <!-- form start -->
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-10">
                                <form name="frm" method="post" onsubmit="return validate(this);" enctype="multipart/form-data">
                                    <input type="hidden" name="pgaction">
                                    <!--  <?php if ($GLOBALS['msg']) {
                                                echo '<center><span class="err">' . $GLOBALS['msg'] . '</span></center>';
                                            } ?> -->
                                    <h4>Upload any number of file</h4>

                                    <div id="dvFile">
                                        <div class="increment-block mb-0 row" id="director-uploads1" data-number="0" data-changed="0">
                                            <div class="col-lg-6">
                                                <div class="md-form md-form--file">
                                                    <div class="file-field">
                                                        <div class="btn btn-success btn-sm float-left">
                                                            <span>Choose file</span>
                                                            <input type="file" name="item_file" />
                                                        </div>
                                                        <div class="file-path-wrapper">
                                                            <input class="file-path validate form-control" type="text" name="file_name" placeholder="Upload your file" value="" readonly />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <?php
                                            if (form_error('file_name'))
                                                echo "<div class='form-group has-error' >";
                                            else
                                                echo "<div class='form-group' >";
                                            ?>

                                            <div class="namepanel col-lg-12 ">
                                                <div class="md-form">
                                                    <!-- <label  >File Name</label> -->
                                                    <input type="text" name="file_name" class="form-control" data-item="0" placeholder="File Name" value="<?php echo $attachments->file_name; ?>">
                                                    <span class="col-sm-12 control-label">
                                                        <?php echo form_error('file_name'); ?>
                                                    </span>
                                                </div>

                                            </div>
                                        </div>
                                    </div>


                                    <input type="submit" value="Upload File" class="btn btn-success">
                                    <a href="<?= $this->agent->referrer(); ?>" class="btn btn-default">Cancel</a>

                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script language="javascript">
    $(document).ready(function() {
        function clone() {
            var number = $('#director-uploads1').attr('data-number');
            $("#director-uploads1").attr('data-number', parseInt(number) + 1);
            $('.director-uploads-hidden').find('.app-file').attr('data-item', parseInt(number) + 1);
            var director = $('.director-uploads-hidden').html();

            $('#director-uploads1').append(director)
                .find("*")
                .each(function() {
                    var name = $(this).attr('name');
                })
                .on('click', 'button.clone', clone);
        }
        $("button#add-director").on("click", clone);

        $("html").on('change', '.app-file', function() {
            var number = $(this).attr('data-item');
            console.log(number);
        });
    });

    function remove_input(e) {
        e.parentNode.parentNode.parentNode.removeChild(e.parentNode.parentNode);
    }

    
</script>
<style type="text/css">
    .md-form--file .file-field input[type="file"] {
        position: absolute;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
        width: 100%;
        padding: 0;
        margin: 0;
        cursor: pointer;
        filter: alpha(opacity=0);
        opacity: 0;
    }

    .md-form--file .file-field .btn * {
        cursor: pointer;
    }

    .md-form input,
    .md-form textarea {
        box-sizing: border-box !important;
    }

    input[type="file"] {
        display: block;
    }

    input,
    button,
    select,
    textarea {
        font-family: inherit;
        font-size: inherit;
        line-height: inherit;
    }
</style>