        <div class="box">
            <div class="box-header">
                <h3 class="box-title"><i class="fa fa-fighter-jet"></i> <?= $this->lang->line('panel_title') ?></h3>
                <ol class="breadcrumb">
                    <li><a href="<?= base_url("dashboard/index") ?>"><i class="fa fa-laptop"></i> <?= $this->lang->line('menu_dashboard') ?></a></li>
                    <li><a href="<?= base_url('activities') ?>"><?= $this->lang->line('menu_activities') ?></a></li>
                    <li class="active"><?= $this->lang->line('add_activities') ?></li>
                </ol>
            </div><!-- /.box-header -->
            <div class="box-body">
                <div class="row">
                    <div class="col-sm-10">
                        <form class="form-horizontal" role="form" method="post" enctype="multipart/form-data" id="myform">
                            <!-- <div class="row">
                                <div class="col-sm-8">
                                    <div class="alert alert-danger" role="alert" id="validation-error">
                                    </div>
                                </div>
                            </div>
                            <br> -->

                            <?php
                            if (form_error('title'))
                                echo "<div class='form-group has-error' >";
                            else
                                echo "<div class='form-group' >";
                            ?>

                            <div class='form-group'>
                                <label for="title" class="col-sm-2 control-label">
                                    <?= $this->lang->line("activities_title") ?>
                                    <span class="text-red">*</span>
                                </label>
                                <div class="col-sm-6">

                                    <input class="form-control" name="title" id="title" cols="30" rows="3" />
                                </div>
                                <span class="col-sm-4 control-label">
                                    <?php echo form_error('title'); ?>
                                </span>
                            </div>
                    </div>

                    <?php
                    if (form_error('description'))
                        echo "<div class='form-group has-error' >";
                    else
                        echo "<div class='form-group' >";
                    ?>
                    <div class='form-group'>
                        <label for="description" class="col-sm-2 control-label">
                            <?= $this->lang->line("activities_description") ?>
                            <span class="text-red">*</span>
                        </label>
                        <div class="col-sm-6">
                            <textarea class="form-control" name="description" id="description" cols="30" rows="3"></textarea>
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('description'); ?>
                        </span>
                    </div>
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
                    <input type="text" class="form-control" id="time_from" name="time_from" value="<?= set_value('time_from') ?>">
                </div>
                <div class="col-sm-3">
                    <input type="text" class="form-control" id="time_to" name="time_to" value="<?= set_value('time_to') ?>">
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
                <input type="text" class="form-control" id="time_at" name="time_at" value="<?= set_value('time_at') ?>">
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
                <div class="col-sm-4">
                    <div class="md-form md-form--file">
                        <div class="file-field">
                            <div class="btn btn-success btn-sm float-left">
                                <span>Choose file</span>
                                <input type="file" name="attachment[]" />
                            </div>
                            <div class="file-path-wrapper">
                                <input class="file-path validate form-control" type="text" name="attachment1[]" placeholder="Upload your file" readonly />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-3 ">
                    <div class="md-form">
                        <input type="text" name="caption[]" class="form-control" data-item="0" id="file-name0" placeholder="Caption">
                    </div>
                </div>

                <div class="col-lg-2 align-self-lg-center">
                    <button type="button" class="btn btn-success" id="add-director"><i class='fa fa-plus'></i> </button>
                </div>

            </div>

        </div>

        <div class="form-group">
            <div class="col-sm-offset-0 col-sm-8">
                <input type="submit" class="btn btn-success" value="<?= $this->lang->line("add_activities") ?>">
            </div>
        </div>
        </form>
        </div>
        </div>
        </div>
        <div class="box-footer clearfix">
            <div id="dvPreview"></div>
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