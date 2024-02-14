            <div class="box">
                <div class="box-header">
                    <h3 class="box-title"><i class="fa icon-activities_category"></i> <?= $this->lang->line('panel_title') ?></h3>

                    <ol class="breadcrumb">
                        <li><a href="<?= base_url("dashboard/index") ?>"><i class="fa fa-laptop"></i> <?= $this->lang->line('menu_dashboard') ?></a></li>
                        <li><a href="<?= base_url("activities/index") ?>"><?= $this->lang->line('menu_activities_category') ?></a></li>
                        <li class="active"><?= $this->lang->line('menu_edit') ?> <?= $this->lang->line('menu_activities_category') ?></li>
                    </ol>
                </div><!-- /.box-header -->
                <!-- form start -->
                <div class="box-body">
                    <div class="row">
                        <div class="col-sm-10">
                            <form class="form-horizontal" role="form" method="post" id="myform" enctype="multipart/form-data">

                                <?php
                                if (form_error('title'))
                                    echo "<div class='form-group has-error' >";
                                else
                                    echo "<div class='form-group' >";
                                ?>
                                <label for="title" class="col-sm-2 control-label">
                                    <?= $this->lang->line("activities_title") ?>
                                    <span class="text-red">*</span>
                                </label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" id="title" name="title" value="<?= set_value('title', $activity->title) ?>">
                                </div>
                                <span class="col-sm-4 control-label">
                                    <?php echo form_error('title'); ?>
                                </span>
                        </div>

                        <?php
                        if (form_error('description'))
                            echo "<div class='form-group has-error' >";
                        else
                            echo "<div class='form-group' >";
                        ?>
                        <label for="description" class="col-sm-2 control-label">
                            <?= $this->lang->line("activities_description") ?>
                            <span class="text-red">*</span>
                        </label>
                        <div class="col-sm-6">

                            <textarea class="form-control" name="description" id="description" cols="30" rows="3"><?= $activity->description ?></textarea>
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('description'); ?>
                        </span>
                    </div>

                    <?php
                    if (form_error('time_from') || form_error('time_to'))
                        echo "<div class='form-group has-error' >";
                    else
                        echo "<div class='form-group' >";
                    ?>
                    <label for="time_from" class="col-sm-2 control-label">
                        <?= $this->lang->line("activities_time_frame") ?>
                    </label>
                    <div class="col-sm-3">
                        <input type="text" class="form-control" id="time_from" name="time_from" value="<?= set_value('time_from', $activity->time_from) ?>">
                    </div>
                    <div class="col-sm-3">
                        <input type="text" class="form-control" id="time_to" name="time_to" value="<?= set_value('time_to', $activity->time_to) ?>">
                    </div>
                    <span class="col-sm-4 control-label">
                        <?php echo form_error('time_from'); ?>
                        <?php echo form_error('time_to'); ?>
                    </span>
                </div>

                <?php
                if (form_error('time_at'))
                    echo "<div class='form-group has-error' >";
                else
                    echo "<div class='form-group' >";
                ?>
                <label for="time_at" class="col-sm-2 control-label">
                    <?= $this->lang->line("activities_time_at") ?>
                </label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" id="time_at" name="time_at" value="<?= set_value('time_at', $activity->time_at) ?>">
                </div>
                <span class="col-sm-4 control-label">
                    <?php echo form_error('time_at'); ?>
                </span>
            </div>

            <?php
            if (form_error('attachment1[]'))
                echo "<div class='form-group has-error' >";
            else
                echo "<div class='form-group' >";
            ?>
            <span class="col-sm-4">
                <?php echo form_error('attachment1[]'); ?>
            </span>
            </div>

            <div class="dvFile" id="dvFile">
                <div class="increment-block mb-0 row" id="director-uploads" data-number="0" data-changed="0">
                    <div class="col-lg-2 align-self-lg-center">
                        <button type="button" class="btn btn-success" id="add-director"><i class='fa fa-plus'></i> </button>
                    </div>

                </div>

            </div>
            <br>

            <div class="box-footer clearfix">
                <div id="dvPreview">
                    <?php foreach ($activities_media as $media) : ?>

                        <span class="pip pip-<?= $media->activitiesmediaID; ?>">
                            <img class="imageThumb" src="<?= base_url('uploads/activities/' . $media->attachment); ?>" title="undefined"><br><span class="remove" onclick="deleteImage(<?= $media->activitiesmediaID; ?>)">Remove image</span></span>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-offset-0 col-sm-8">
                    <input type="submit" class="btn btn-success" value="<?= $this->lang->line("update_activities") ?>">
                </div>
            </div>
            </form>
            </div>
            </div>
            </div>

            </div>


            <script language="javascript">
                $(document).ready(function() {
                    var i = 1;

                    function clone() {
                        var html = "<div class=\"increment-block mb-0 row\" data-changed=\"0\"><div class=\"col-sm-4\"><div class=\"md-form md-form--file\"><div class=\"file-field\"><div class=\"btn btn-success btn-sm float-left\"><span>Choose file</span><input type=\"file\" name=\"attachment[]\"  /></div><div class=\"file-path-wrapper\"><input class=\"file-path validate form-control\" type=\"text\" name=\"attachment1[]\" placeholder=\"Upload your file\" readonly/></div></div></div></div><div class=\"col-sm-3\"><div class=\"md-form\"><input type=\"text\" name=\"caption[]\" id=\"file-name" + i + "\" class=\"form-control\" data-item=\"0\"  placeholder=\"Caption\" > <span class=\"text-danger error\"><p id=\"file-name-error" + i + "\"></p></span></div></div><div class=\"col-lg-2 align-self-lg-center\"><button type=\"button\" class=\"btn btn-danger\" onclick=\"remove_input(this)\" title=\"Remove\" id=\"remove-clone\"><i class='fa fa-minus'></i></button></div></div>";
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

            <script>
                $(document).ready(function() {
                    if (window.File && window.FileList && window.FileReader) {
                        $("#attachments").on("change", function(e) {
                            var files = e.target.files,
                                filesLength = files.length;
                            for (var i = 0; i < filesLength; i++) {
                                var f = files[i]
                                var fileReader = new FileReader();
                                fileReader.onload = (function(e) {
                                    var file = e.target;
                                    $("<span class=\"pip\">" +
                                        "<img class=\"imageThumb\" src=\"" + e.target.result + "\" title=\"" + file.name + "\"/>" +
                                        "<br/><span class=\"remove\">Remove image</span>" +
                                        "</span>").insertAfter("#dvPreview");
                                    $("body").on('click', '.remove', function() {
                                        $(this).parent(".pip").remove();
                                    });


                                });
                                fileReader.readAsDataURL(f);
                            }
                        });
                    } else {
                        alert("Your browser doesn't support to File API")
                    }
                });


                function deleteImage(id) {
                    var result = confirm("Are you sure to delete?");
                    if (result) {
                        $.post("<?php echo base_url('activities/deleteImage'); ?>", {
                            id: id
                        }, function(data) {
                            var response = jQuery.parseJSON(data);
                            if (response.status) {
                                console.log(response);
                                $('.pip-' + id).remove();
                                toastr["success"](response.message)
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
                            }
                        });
                    }
                }


                $(function() {
                    $("#fileupload").change(function() {
                        if (typeof(FileReader) != "undefined") {
                            var dvPreview = $("#dvPreview");
                            dvPreview.html("");
                            var regex = /^([a-zA-Z0-9\s_\\.\-:])+(.jpg|.jpeg|.gif|.png|.bmp)$/;
                            $($(this)[0].files).each(function() {
                                var file = $(this);
                                if (regex.test(file[0].name.toLowerCase())) {
                                    var reader = new FileReader();
                                    reader.onload = function(e) {
                                        var img = $("<img />");
                                        var span = $("<span></span>");

                                        img.attr("style", "");
                                        img.attr("class", "act-attach");
                                        img.attr("src", e.target.result);

                                        span.attr("class", "thumbnail-attach");

                                        span.append(img);
                                        dvPreview.append(span);
                                    }
                                    reader.readAsDataURL(file[0]);
                                } else {
                                    alert(file[0].name + " is not a valid image file.");
                                    dvPreview.html("");
                                    return false;
                                }
                            });
                        } else {
                            alert("This browser does not support HTML5 FileReader.");
                        }
                    });
                });

                $('.act-image img').click(function() {
                    var id = $(this).attr('id');
                    if ($(this).hasClass('selected')) {
                        $(this).removeClass('selected');
                        $('input[type=hidden]').each(function() {
                            if ($(this).val() === id) {
                                $(this).remove();
                            }
                        });
                    } else {
                        $(this).addClass('selected'); // adds the class to the clicked image
                        $("#students").after(
                            "<input id='students' type='hidden' name='students[]' value=" + id + " />"
                        );
                    }
                });
                $('#time_from, #time_to, #time_at').timepicker({
                    minuteStep: 5,
                });
            </script>

            <style>
                input[type="file"] {
                    display: block;
                }

                .imageThumb {
                    max-height: 75px;
                    border: 2px solid;
                    padding: 1px;
                    cursor: pointer;
                }

                .pip {
                    display: inline-block;
                    margin: 10px 10px 0 0;
                }

                .remove {
                    display: block;
                    background: #444;
                    border: 1px solid black;
                    color: white;
                    text-align: center;
                    cursor: pointer;
                }

                .remove:hover {
                    background: white;
                    color: black;
                }
            </style>