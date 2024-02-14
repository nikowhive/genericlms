<style>
    .modal-body .read-more a {
        color: #236d37;
        font-size: 13px;
        font-weight: bold;
        float: right;
    }
</style>
<div class="right-side--fullHeight">
    <div class="row w-100 ">
        <?php $this->load->view("components/course_menu"); ?>
            <div class="course-content">
                <div class="container container--sm" style="padding-left:30px;">
                    <header class="pg-header mt-4">
                        <h1 class="pg-title">
                            <div><small>Course</small></div>
                            Daily Report Version
                        </h1>
                    </header>
                    <div class="card card--spaced">
                        <!-- /.box-header -->
                        <!-- form start -->
                        <!-- <div class="card-body"> -->
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="template-rightsidebar template-rightsidebar-page">
                                            <!-- <div class="container container--sm"> -->
                                                <div class="card p-0 card--media">
                                                    <div class="card-header">
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
                                                                <div class="col-md-12 ml-auto"><b>Absent Students</b><br><?php echo $this->data['daily']->absent_student_count  ?>
                                                                </div>
                                                            </div>
                                                            <hr>
                                                        <?php endif; ?> 
                                                            <div class="row ">
                                                                <div class="col-md-12 ml-auto description more"><b>Activities</b><?php echo $dailys->activities  ?>
                                                                </div>
                                                                <?php if (strlen($dailys->activities) > 500) { ?>
                                                                    <div class="read-more"><a href="#" class=""><span>READ MORE</span></a></div>
                                                                <?php } ?>
                                                            </div>
                                                            <hr>
                                                            <div class="row ">
                                                                <div class="col-md-12 ml-auto description more"><b>Assignment</b><?php echo $dailys->assignments  ?>
                                                                </div>
                                                                <?php if (strlen($dailys->assignments) > 500) { ?>
                                                                    <div class="read-more"><a href="#" class=""><span>READ MORE</span></a></div>
                                                                <?php } ?>
                                                            </div>
                                                            <hr>
                                                            <div class="row ">
                                                                <div class="col-md-12 ml-auto description more"><b>Feedback</b><?php echo $dailys->feedback  ?>
                                                                </div>
                                                                <?php if (strlen($dailys->feedback) > 500) { ?>
                                                                    <div class="read-more"><a href="#" class=""><span>READ MORE</span></a></div>
                                                                <?php } ?>
                                                            </div>
                                                            <hr>
                                                            <div class="row ">
                                                                <div class="col-md-12 ml-auto description more"><b>Remark</b><?php echo $dailys->remarks  ?>
                                                                </div>
                                                                <?php if (strlen($dailys->remarks) > 500) { ?>
                                                                    <div class="read-more"><a href="#" class=""><span>READ MORE</span></a></div>
                                                                <?php } ?>
                                                            </div>
                                                        </div>
                                                        <div class="center">
                                                            <div id='pagination'>
                                                                <?php foreach ($versions as $i => $k) : ?>
                                                                    <a href="#" class="version pag-<?= $k->id ?> <?= $k->finalized_id == '1' ? 'active' : '' ?>" data-version_id="<?= $k->id ?>"><?= $i + 1 ?></a>
                                                                <?php endforeach; ?>
                                                            </div>
                                                        </div>
                                                        <div class="right">
                                                            <?php if (permissionChecker('courses_edit')) : ?>
                                                                <?php if ($usertypeID == 1) : ?>
                                                                    <label class="switch" data-toggle="tooltip" data-placement="top" data-original-title="Finalizied version">
                                                                        <input type="checkbox" class="switch__input is_finalized" value="" class="onoffswitch-small-checkbox" id="">
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
                                                    </div>

                                                    <div class="card-body">
                                                        <input type="hidden" value="<?= $dailys->id ?>" id="daily_id" name="daily_id">
                                                        <div id="gallery" class="card-gallery imgs-grid imgs-grid-5">
                                                            <div class="imgs-grid-image">
                                                                <div class="image-wrap">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="card-footer">
                                                        <div>
                                                            <b><a href="#commentbox<?= $dailys->id ?>" role="button" data-toggle="collapse" class="collapsed">
                                                                    <?php
                                                                    if (isset($daily_comments[$dailys->id])) {
                                                                        if (customCompute($daily_comments[$dailys->id]) == 1) {
                                                                            echo '<span class=comment_count_' . $dailys->id . '>' . customCompute($daily_comments[$dailys->id]) . '</span> ' . $this->lang->line('daily_comment');
                                                                        } else {
                                                                            echo '<span class=comment_count_' . $dailys->id . '>' . customCompute($daily_comments[$dailys->id]) . '</span> ' . $this->lang->line('daily_comments');
                                                                        }
                                                                    } else {
                                                                        echo '<span style="display:none" class=comment_' . $dailys->id . '><span class=comment_count_' . $dailys->id . '>0</span> ' . $this->lang->line('daily_comments') . '</span>';
                                                                    }
                                                                    ?>
                                                                </a></b>
                                                        </div>
                                                    </div>
                                                    <div class="commentbox collapse" id="commentbox<?= $dailys->id ?>" style="height: 1px;">
                                                        <?php
                                                        if (customCompute($daily_comments) && isset($daily_comments[$dailys->id])) { ?>
                                                            <?php foreach ($daily_comments[$dailys->id] as $comment) {
                                                                if (isset($user[$comment->user_type_id][$comment->user_id])) {
                                                            ?>
                                                                    <div class="card-header">
                                                                        <div class="media-block">
                                                                            <div class="avatar">
                                                                                <img src="<?= imagelink($user[$comment->user_type_id][$comment->user_id]->photo,56) ?>" class="avatar-img" alt="">
                                                                            </div>
                                                                            <div class="media-block-body">
                                                                                <ul class="list-inline list-inline--social-meta">
                                                                                    <li>
                                                                                        <h4><b><?= $user[$comment->user_type_id][$comment->user_id]->name ?></b></h4>
                                                                                    </li>
                                                                                    <li class="date-list"><span class="date"><?= getRangeDateString(date_create($comment->create_date)->getTimestamp()) ?></span></li>
                                                                                </ul>
                                                                            </div>
                                                                            <?php if ($this->session->userdata('loginuserID') == $comment->user_id && $this->session->userdata('usertypeID') == $comment->user_type_id) : ?>
                                                                                <a href="#" class="icon-round collapsed icon-round__trash" role="button" data-comment-id="<?= $comment->id; ?>" data-daily_id="<?= $dailys->id ?>">
                                                                                    <i class="fa fa-trash"></i>
                                                                                </a>
                                                                            <?php endif; ?>
                                                                        </div>
                                                                        <div class="card-title-wrapper">
                                                                            <p>
                                                                                <?= $comment->comment ?>
                                                                            </p>
                                                                        </div>
                                                                    </div>

                                                        <?php }
                                                            }
                                                        }
                                                        ?>

                                                    </div>
                                                    <div class="card-header commentbox__postcomment">
                                                        <div class="media-block">
                                                            <div class="avatar">
                                                                <img src="<?= imagelink($this->session->userdata('photo'),56) ?>" class="avatar-img" alt="">
                                                            </div>
                                                            <div class="media-block-body">
                                                                <div class="md-form mb-0 mt-0">
                                                                    <textarea name="" placeholder="press enter to post comment" id="comment<?= $dailys->id ?>" class="md-textarea form-control daily_comment" data-daily_id="<?= $dailys->id ?>" data-course="<?= $dailys->course_id ?>" autocomplete="off" spellcheck="false"></textarea>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            <!-- </div> -->
                                        
                                        <!-- <a href="#" class="btn btn-primary btn--floating js-activities-btn waves-effect waves-light"> <i class="fa fa-plus"></i> Add Activities</a> -->
                                    </div>
                                </div>
                            </div>
                        <!-- </div> -->
                    </div>
                </div>
            </div>
    </div>
    <div class="modal fade" id="myModal1" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                    <img src="" id="imagepreview" style="width: 100%;">
                </div>
            </div>
        </div>
    </div>
</div>

<script type='text/javascript'>
    $(document).ready(function() {
            $(".read-more a").click(function(e) {
                    e.preventDefault();
                    $(this).toggleClass("active");
                    $(this).parent().siblings('.description').toggleClass('full-text');
            });
    });
        $(document).ready(function() {
            $(window).load(function() {
                var daily_id = $("#daily_id").val();
                var version = $('#pagination').find('a.active').data('version_id');
                load_version(version);

                function load_version(version) {
                    $.ajax({
                        url: '<?= base_url() ?>daily_plan/get_more_version',
                        type: 'get',
                        data: {
                            'daily': daily_id,
                            'version': version
                        },
                        dataType: 'json',
                        success: function(response) {
                            check_version = response.version;

                            if (check_version.finalized_id == 1) {
                                $('.is_finalized').prop('checked', true);

                            }
                            result = response.result;

                            $('#gallery ').empty();
                            $('.version').removeClass('active');
                            var img = '';
                            $.each(result, function(key, value) {
                                var pic = value.file;
                                console.log(value);
                                var fextension = pic.substring(pic.lastIndexOf('.') + 1);
                                var imgExt = ["jpg", "jpeg", "gif", "png", "PNG"];
                                var docExtensions = ["doc", "pdf", "docx", "xls", "xlsx", "ppt", "pptx", "txt", "zip", "rar", "gzip"];

                                if ($.inArray(fextension, imgExt) == -1) {
                                    img += "<a href='<?php echo base_url("uploads/images/") ?>" + pic + "'> Download this " + fextension + " file for preview. </a>";

                                } else if ($.inArray(fextension, docExtensions) == -1) {
                                    img += "<div class='daily-img'> <img width='100%' height='100%' id='media_img_" + value.id + "' data-media_id=" + value.id + " src='<?php echo base_url("uploads/images/") ?>" + pic + "'></div>";
                                }

                            })

                            $('.pag-' + version).addClass('active');
                            $('.is_finalized').val(check_version.id);
                            $('#gallery').append(img);

                        }
                    });
                }

                $('.center .version').on('click', function() {
                    var version = $(this).data('version_id');
                    $('.is_finalized').prop('checked', false);
                    load_version(version);

                });

                $('#gallery').on('click', '.daily-img', function(e) {
                    var link = $(this).find('img').attr('src');
                    modal.style.display = "block";
                    modalImg.src = link;
                });


                $('.is_finalized').on('click', function(e) {
                    e.preventDefault();
                    $('.is_finalized').prop('checked', false);


                    let url = "<?= base_url('daily_plan/ajaxChangeVersionStatus/') ?>";
                    var id = $(this).val();

                    if (id == '') {
                        toastr["error"]('No version detect');
                        return false;

                    }
                    $.post(url + id).done(function() {
                        $('#loading').hide();
                        $('.is_finalized').prop('checked', true);
                        toastr["success"]("Status changed.")

                    }).fail(function(error) {
                        $('#loading').hide();
                        toastr["error"](error.responseText)

                    });

                });




            });
        });


        user_photo = "<?= imagelink($this->session->userdata('photo'),56) ?>";
        user_name = "<?= $this->session->userdata('name') ?>";
        $(document).ready(function() {
            $(".daily_comment").keypress(function(e) {
                comt = $(this);
                course = $(this).data('course');
                comment = $(this).val();
                daily_id = $(this).data('daily_id');
                now = "<?= getRangeDateString(date_create(date("Y-m-d h:i:s"))->getTimestamp()) ?>";

                var code = (e.keyCode ? e.keyCode : e.which);
                if (code == 13) {
                    $.ajax({
                        type: 'POST',
                        url: "<?= base_url('daily_plan/comment') ?>",
                        data: {
                            'daily_id': $(this).data('daily_id'),
                            'course': $(this).data('course'),
                            'comment': $(this).val()
                        },
                        dataType: "json",
                        success: function(data) {
                            console.log(data);

                            $("#commentbox" + daily_id).append("<div class=\"card-header\"><div class=\"media-block\"><div class=\"avatar\"><img src=\"" + user_photo + "\" class=\"avatar-img\" alt=\"\"/></div><div class=\"media-block-body\"><ul class=\"list-inline list-inline--social-meta\"><li><h4><b>" + user_name + "</b></h4></li><li class=\"date-list\"><span class=\"date\">" + now + "</span></li></ul></div><a href=\"javascript:void(0)\" data-comment-id=" + data + "  data-daily_id=" + daily_id + " class=\"icon-round collapsed icon-round__trash\" role=\"button\"><i class=\"fa fa-trash\"></i></a></div><div class=\"card-title-wrapper\"><p>" + comment + "</p></div></div>");
                            comt.val('');
                            comment_count = $(".comment_count_" + daily_id).html();
                            if (comment_count != 0) {
                                $(".comment_count_" + daily_id).html(parseInt(comment_count) + 1);
                            } else {
                                // console.log('helo');
                                $(".comment_" + daily_id).show();
                                $(".comment_count_" + daily_id).html(parseInt(comment_count) + 1);
                            }
                            $("#commentbox" + daily_id).removeClass('collapse');
                            $("#commentbox" + daily_id).addClass('in');
                            $("#commentbox" + daily_id).css("height: auto");
                        }
                    });
                }
            });

            $(".commentbox").on('click', '.icon-round', function(e) {
                e.preventDefault();
                var result = confirm("Are you sure to delete?");
                var classes = $(this).parent().parent('div');
                comment_id = $(this).data('comment-id');
                daily_id = $(this).data('daily_id');

                if (result) {
                    $.ajax({
                        type: 'POST',
                        url: "<?= base_url('daily_plan/delete_comment'); ?>",
                        data: {
                            id: comment_id
                        },
                        dataType: "html",
                        success: function(data) {

                            var response = jQuery.parseJSON(data);
                            if (response.status) {
                                toastr["success"](response.message)

                            }
                            classes.remove();

                            comment_count = $(".comment_count_" + daily_id).html();
                            if (comment_count != 0) {
                                $(".comment_count_" + daily_id).html(parseInt(comment_count) - 1);
                            } else {
                                // console.log('helo');
                                $(".comment_" + daily_id).show();
                                $(".comment_count_" + daily_id).html(parseInt(comment_count) - 1);
                            }
                            $("#commentbox" + daily_id).removeClass('collapse');
                            $("#commentbox" + daily_id).addClass('in');
                            $("#commentbox" + daily_id).css("height: auto");
                        }

                    });
                }



            });

        });
    </script>

    <style>
        .center {
            text-align: center;
        }

        .right {
            text-align: right;
        }

        #pagination {
            display: inline-block;
        }

        #pagination a {
            color: black;
            float: center;
            font-size: 10px;
            padding: 8px 16px;
            text-decoration: none;
            transition: background-color .3s;
            border: 1px solid #ddd;
            margin: 0 4px;
        }

        #pagination a.active {
            background-color: #4CAF50;
            color: white;
            border: 1px solid #4CAF50;
        }

        #pagination a:hover:not(.active) {
            background-color: #ddd;
        }

        #pagination a:first-child {
            border-top-left-radius: 5px;
            border-bottom-left-radius: 5px;
        }

        #pagination a:last-child {
            border-top-right-radius: 5px;
            border-bottom-right-radius: 5px;
        }
        
    </style>