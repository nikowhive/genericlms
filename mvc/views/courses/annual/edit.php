<div class="right-side--fullHeight  ">
    <div class="row w-100 ">
        <?php $this->load->view("components/course_menu"); ?>
        <div class="course-content">
            <div class="container container--sm">

                <header class="pg-header mt-4">
                    <h1 class="pg-title">
                        <div><small>Course</small></div>
                        Edit Annual Plan
                    </h1>
                </header>
                <div class="card card--spaced">

                    <!-- /.box-header -->
                    <!-- form start -->
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-12">

                                <form name="frm" method="post" enctype="multipart/form-data" id="myform">
                                    <div class="row">
                                        <div class="col-sm-10">
                                            <?php echo validation_errors('<div class="alert-danger">', '</div>'); ?>
                                        </div>
                                    </div>

                                    <h4>Upload any number of file</h4>

                                    <div class="dvFile" id="dvFile">
                                        <div class="increment-block mb-0 row" id="director-uploads" data-number="0" data-changed="0">
                                            <div class="col-lg-2 align-self-lg-center">
                                                <button type="button" class="btn btn-success" id="add-director"><i class='fa fa-plus'></i> </button>
                                            </div>

                                        </div>

                                    </div>
                                    <br>

                                    <div class="content">

                                        <div class="row">
                                            <?php foreach ($annual_medias as $v) : ?>
                                                <div class="col-sm-4">
                                                    <div class="card">
                                                        <div class="card-body">
                                                            <div class="">
                                                                <!-- <div class="panned-icon">⋮⋮</div> -->
                                                                <div class="panned-icon">
                                                                    <i class='fa <?= checkFileExtension($v->file) ?>' aria-hidden='true'></i>
                                                                </div>
                                                                <h5 class="card-title"><?= $v->caption ?></h5>
                                                            </div>
                                                            <!-- <img src="..." class="card-img-top" alt="..."> -->
                                                            <a onclick="return confirm('You are about to delete a record. This cannot be undone. are you sure?')" href="<?php echo base_url() . 'annual_plan/delete/' . $v->id . '?annual=' . $annual . '&course=' . $course->id ?>" class="btn btn-danger">Remove</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                    <br>
                                   

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
        var i = 1;

        function clone() {
            var html = "<div class=\"increment-block mb-0 row\" data-changed=\"0\"><div class=\"col-lg-6\"><div class=\"md-form md-form--file\"><div class=\"file-field\"><div class=\"btn btn-success btn-sm float-left\"><span>Choose file</span><input type=\"file\" name=\"upload_Files[]\"  /></div><div class=\"file-path-wrapper\"><input class=\"file-path validate form-control\" type=\"text\" name=\"file_name1[]\" placeholder=\"Upload your file\" readonly/></div></div></div></div><div class=\"namepanel col-lg-4\"><div class=\"md-form\"><input type=\"text\" name=\"caption[]\" id=\"file-name" + i + "\" class=\"form-control\" data-item=\"0\"  placeholder=\"Caption\" > <span class=\"text-danger error\"><p id=\"file-name-error" + i + "\"></p></span></div></div><div class=\"col-lg-2 align-self-lg-center\"><button type=\"button\" class=\"btn btn-danger\" onclick=\"remove_input(this)\" title=\"Remove\" id=\"remove-clone\"><i class='fa fa-minus'></i></button></div></div>";
            i++;
            $('#dvFile').append(html).find("*").each(function() {
                var name = $(this).attr('name');
            }).on('click', 'button.clone', clone);
        }
        $("button#add-director").on("click", clone);
        $("html").on('change', '.app-file', function() {
            var number = $(this).attr('data-item');
        });
    });

    function remove_input(e) {
        var thisId = e.closest('.increment-block').remove();
        console.log(thisId);
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