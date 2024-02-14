<link rel="stylesheet" href="<?php echo base_url('assets/inilabs/fb-img-grid/images-grid.css'); ?>" />
<div class="template-rightsidebar template-rightsidebar-page">
    <div class="template-rightsidebar-container">
        <div class="container container--sm" id="feeds-data">
            <?php if (($siteinfos->school_year == $this->session->userdata('defaultschoolyearID')) || ($this->session->userdata('usertypeID') == 1)) { ?>
                <?php if (permissionChecker('activities_add')) { ?>
                    <header class="pg-header">
                        <?php if ($activityCategoryID == '' || $activityCategoryID == 0) { ?>
                            <h1 class="pg-title">Activities</h1>
                            <div class="dropdown">
                                <a data-toggle="dropdown" class="btn-sm btn btn-default"><i class="fa fa-plus"></i> <?= $this->lang->line('add_title') ?></a>
                                <ul id="menu2" class="dropdown-menu" aria-labelledby="drop5">
                                    <?php foreach ($activitiescategories as $category) { ?>
                                        <li>
                                           <a href="<?= base_url('activities/add/' . $category->activitiescategoryID) ?>"><i class="fa <?= $category->fa_icon ?>"></i><?= $category->title ?></a>
                                        </li>
                                    <?php } ?>
                                </ul>
                            </div>
                        <?php } else { ?>
                            <input type="hidden" id="activity-category" value="<?= $activityCategoryID?>">
                            <h1 class="pg-title"><?php echo $activitiescategories[$activityCategoryID]->title ?></h1>
                            <div class="dropdown">
                                <a class="btn-sm btn btn-default" href="<?= base_url('activities/add/' . $activityCategoryID) ?>"><i class="fa fa-plus"></i> <?= $this->lang->line('add_title') ?></a>
                            </div>
                        <?php } ?>
                    </header>
                <?php } ?>
            <?php } ?>


            <?php if (customCompute($activities) > 0) {
                $i = 0;
                foreach ($activities as $activity) {
                    if (isset($user[$activity->usertypeID][$activity->userID])) { ?>
                        <div class="card p-0 card--media">
                            <div class="card-header">
                                <div class="media-block">
                                    <div class="avatar">
                                        <img src="<?= isset($user[$activity->usertypeID][$activity->userID]) ?  imagelink($user[$activity->usertypeID][$activity->userID]->photo,56) : imagelink('default.png') ?>" class="avatar-img" alt="" />
                                    </div>
                                    <div class="media-block-body">
                                        <ul class="list-inline list-inline--social-meta">
                                            <li class="pill-list"><span class="pill"> <?= isset($activitiescategories[$activity->activitiescategoryID]) ? $activitiescategories[$activity->activitiescategoryID]->title : '' ?> </span></li>
                                            <li><?= $user[$activity->usertypeID][$activity->userID]->name ?></li>
                                            <li class="date-list"><span class="date"><?= getRangeDateString(date_create($activity->create_date)->getTimestamp()) ?></span></li>
                                        </ul>
                                    </div>
                                    <div class="dropdown">
                                        <a href="#" class=" " data-toggle="dropdown"> ⋮</a>
                                        <ul id="menu2" class="dropdown-menu" aria-labelledby="drop5">
                                        <?php if (permissionChecker('activities_edit')) : ?>
                                            <li>
                                                <a href="<?= base_url('activities/edit/') . $activity->activitiesID ?>">Edit</a>
                                            </li>
                                           <?php endif; ?>
                                            <?php if (permissionChecker('activities_delete')) : ?>
                                            <li>
                                                <a href="<?= base_url('activities/delete/') . $activity->activitiesID ?>" onclick="return confirm('you are about to delete a record. This cannot be undone. are you sure?')">Delete</a>
                                            </li>
                                            <?php endif; ?>
                                        </ul>
                                    </div>
                                </div>
                                <div class="card-title-wrapper">
                                <?php if((isset($activitiesmedia[$activity->activitiesID]) &&  count($activitiesmedia[$activity->activitiesID]) != 1) || !isset($activitiesmedia[$activity->activitiesID])){ ?>
                           
                                    <h3 class="card-title">
                                      <?= $activity->title ?>
                                    </h3>
                                    <?php if ($activity->time_from && $activity->time_to) { ?>
                                    <div class="time-block mt-2">
                                        <i class="fa fa-clock-o text-danger"></i>
                                        <small>
                                           <b><?php echo date("h:i A", strtotime($activity->time_from)); ?> - <?php echo date("h:i A", strtotime($activity->time_to)); ?></b>
                                        </small>
                                    </div>
                                    <?php } }?>
                                </div>
                            </div>
                            
                            <?php
                            if(isset($activitiesmedia[$activity->activitiesID]) &&  count($activitiesmedia[$activity->activitiesID]) == 1 ){
                                $imageName = $activitiesmedia[$activity->activitiesID][0]->attachment;
                            ?>
                            <figure class="card-img card-img-auto">
                                <img src= "<?= base_url('uploads/activities/' . $imageName) ?>" class="myImg" alt="">
                               <figcaption data-background="">
                                    <h1 class="display-title">
                                      <?= $activity->title ?>
                                    </h1>
                                    <div class="time-block mt-2">
                                        <i class="fa fa-calendar fa-2x text-danger"></i>
                                        <b><?php echo date("h:i A", strtotime($activity->time_from)); ?> - <?php echo date("h:i A", strtotime($activity->time_to)); ?></b>
                                    </div>
                                </figcaption>
                            </figure>

                            <div class="card-body description more">
                                    <?= $activity->description ?>
                            </div>
                            <?php if(strlen($activity->description) > 500){ ?>
                            <div class="read-more"><a href="#" class=""><span>READ MORE</span></a></div>
                            <?php } ?>
                            <?php }else{ ?>
                            <div class="card-body description more">
                                    <?= $activity->description ?>
                            </div>
                            <?php if(strlen($activity->description) > 500){ ?>
                            <div class="read-more"><a href="#" class=""><span>READ MORE</span></a></div>
                            <?php } ?>
                            <?php if(count($activity->media) > 1){ ?>
                            <div id="gallery<?= $activity->activitiesID ?>" data-activity_id="<?= $activity->activitiesID ?>" class="card-gallery"></div>
                            <?php }} ?>
                            <div class="card-footer">
                                <div>
                                    <b><a href="#commentbox<?= $activity->activitiesID ?>" role="button" data-toggle="collapse">
                                            <?php
                                            if (isset($comments[$activity->activitiesID])) {
                                                if ($activity->comment_count == 1) {
                                                    echo '<span class=comment_count_' . $activity->activitiesID . '>' . $activity->comment_count . '</span> ' . $this->lang->line('activities_comment');
                                                } else {
                                                    echo '<span class=comment_count_' . $activity->activitiesID . '>' . $activity->comment_count . '</span> ' . $this->lang->line('activities_comments');
                                                }
                                            } else {
                                                echo '<span style="display:none" class=comment_' . $activity->activitiesID . '><span class=comment_count_' . $activity->activitiesID . '>0</span> ' . $this->lang->line('activities_comments') . '</span>';
                                            }
                                            ?>
                                        </a></b>
                                </div>
                            </div>
                            <div class="commentbox collapse" id="commentbox<?= $activity->activitiesID ?>">
                            <?php if ($activity->comment_count > 5) { ?>
                                <div id="viewWrapper<?= $activity->activitiesID ?>" style="text-align: left;padding-left: 22px;padding-top: 10px;padding-bottom: 8px;">
                                <a href="javascript:void(0)" id="more<?= $activity->activitiesID ?>" class="viewMoreComment" data-start = "5" data-activities-id="<?= $activity->activitiesID ?>">View More Comment</a>
                                </div> 
                            <?php } ?>
                            <?php if (customCompute($comments) && isset($comments[$activity->activitiesID])) { ?>
                                    <?php foreach ($comments[$activity->activitiesID] as $comment) {
                                        if (isset($user[$comment->usertypeID][$comment->userID])) { ?>
                                            <div class="card-header">
                                                <div class="media-block">
                                                    <div class="avatar">
                                                        <img src="<?= imagelink($user[$comment->usertypeID][$comment->userID]->photo,56) ?>" class="avatar-img" alt="" />
                                                    </div>
                                                    <div class="media-block-body">
                                                        <ul class="list-inline list-inline--social-meta">
                                                            <li>
                                                                <h4><b><?= $user[$comment->usertypeID][$comment->userID]->name ?></b></h4>
                                                            </li>
                                                            <li class="date-list"><span class="date"><?= getRangeDateString(date_create($comment->create_date)->getTimestamp()) ?></span></li>
                                                        </ul>
                                                    </div>
                                                    <?php if(($this->session->userdata('loginuserID') == $comment->userID && $this->session->userdata('usertypeID') == $comment->usertypeID) || ($this->session->userdata('loginuserID') == 1 && $this->session->userdata('usertypeID') == 1)) : ?>
                                                        <a href="javascript:void(0)" class="icon-round__trash" data-toggle="modal" data-target="#commentModal" data-comment-id="<?= $comment->activitiescommentID; ?>" data-activities-id="<?= $activity->activitiesID ?>">
                                                            <i class="fa fa-edit"></i>
                                                        </a>
                                                        <a href="javascript:void(0)" class="icon-round collapsed icon-round__trash" role="button" data-comment-id="<?= $comment->activitiescommentID; ?>" data-activity_id="<?= $activity->activitiesID ?>">
                                                            <i class="fa fa-trash"></i>
                                                        </a>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="card-title-wrapper">
                                                    <p id="<?= $activity->activitiesID ?>-<?= $comment->activitiescommentID; ?>">
                                                        <?= $comment->comment ?>
                                                    </p>
                                                </div>
                                            </div>
                                <?php }
                                    }
                                } ?>
                            </div>

                            <div class="card-header commentbox__postcomment">
                                <div class="media-block">
                                    <div class="avatar">
                                        <img src="<?= imagelink($this->session->userdata('photo'),56) ?>" class="avatar-img" alt="" />
                                    </div>
                                    <div class="media-block-body">
                                        <div class="md-form mb-0 mt-0">
                                            <textarea name="" id="comment<?= $activity->activitiesID ?>" data-activity_id="<?= $activity->activitiesID ?>" class="md-textarea form-control activity_comment"></textarea>
                                            <label for="comment<?= $activity->activitiesID ?>">Write your comment</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

            <?php $i++;
                    }
                }
            } ?>

        </div>
    </div>

    <aside class="template-rightsidebar-sidebar  right-sidebar js-affix-top affix-top" id="js-activities-sidebar">
        <div class="card card--quiz card--quiz-Sidebar box js-affix-top affix-top">
            <div class=" ">
                <div class="box-header">
                    <h3 class="box-title">
                        <i class="fa fa-fighter-jet"></i><?= $this->lang->line('panel_title') ?>
                    </h3>
                    <a href="#" class="js-activities-btn icon-round activities-close" role="button"> <i class="fa fa-close"></i></a>
                </div>
                <div class="box-body">
                    <div class="activities--actions">
                        <?php $colors = array("maroon", "green", "aqua", "blue", "olive", "navy", "purple", "black"); ?>
                        <?php foreach ($activitiescategories as $category) { ?>
                            <?php $randIndex = array_rand($colors); ?>
                            <a href="<?= base_url('activities/index?category=' . $category->activitiescategoryID) ?>" class="btn btn-app bg-<?= $colors[$randIndex]; ?>">
                                <i class="fa <?= $category->fa_icon ?>"></i> <?= $category->title ?>
                            </a>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </aside>
    <a href="#" class="btn btn-primary btn--floating js-activities-btn"> <i class="fa fa-plus"></i> Add Activities</a>
</div>

<div class="modal fade" id="commentModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Edit Comment</h5>
      </div>
      <div class="commentWrapper">
          
      </div>
    </div>
  </div>
</div>


<script type="text/javascript">

$('.viewMoreComment').click(function(){

    activitiesID = $(this).data('activities-id');
    pageValue = $(this).data('start');

    $.ajax({
        url: "<?= base_url('activities/getMoreActivityCommentData/') ?>" + pageValue,
        type: "get",
        data: {'activitiesID': activitiesID},
        success: function(response) {
            pageValue += 5;
            $("#commentbox" + activitiesID).before($('#viewWrapper'+activitiesID)).prepend(response);
            $('#more'+activitiesID).data('start',pageValue);
        },
        error: function(response) {
            $('#viewWrapper'+activitiesID).hide();
        }
    });

});

$('#commentModal').on('show.bs.modal', function(e) {

    var commentId = $(e.relatedTarget).data('comment-id');
    var activitiesId = $(e.relatedTarget).data('activities-id');

    $.ajax({
        url: "<?= base_url('activities/getComment/') ?>",
        type: "get",
        data:{'commentID':commentId,'activitiesID':activitiesId},
        success: function(response) {
            $('#commentModal').find('.commentWrapper').html(response); 
            $('#commentBtn').on('click', function(e) {
                e.preventDefault();
                $.ajax({
                    type: "POST",
                    url: "<?= base_url('activities/editComment') ?>",
                    data: $('#commentForm').serialize(),
                    success: function(res) {
                    if(res){
                        $('#'+activitiesId+'-'+commentId).html(res);
                        //   showToastSuccess('Success');
                        $('#commentModal').modal('hide');
                    }else{
                        showToastError('Something went wrong');
                    }
                        
                    },
                    error: function() {
                        alert('Error');
                    }
                });
                return false;
            });
        },
        error: function(response) {
                    
        }
    });

});

    $(document).ready(function() {
        $(".card--media .read-more a").click(function(e) {
                e.preventDefault();
                $(this).toggleClass("active");
                $(this).parent().siblings('.description').toggleClass('full-text');
        });
    });

    $(document).ready(function() {
        var pageValue = 0;
        var hasData = true;
        $('body').scroll(function() {

            if ($('body').scrollTop() + $('body').height() >= $(document).height()) {
                pageValue += 1;

                if (hasData) {
                     
                    var category= $('#activity-category').val();
                //    if(category != undefined){
                //        var url= "<?= base_url('activities/getMoreActivitiesData?category=') ?>" + category + '/' + pageValue;
                //    }else{
                //        var url= "<?= base_url('activities/getMoreActivitiesData/') ?>" + pageValue;
                //    }

                    $.ajax({
                        url: "<?= base_url('activities/getMoreActivitiesData/') ?>" + pageValue,
                        type: "get",
                        data: {
                            category: category
                        },
                        success: function(response) {
                            $('#feeds-data').append(response);
                            $(".card--media .read-more a").click(function(e) {
                                        e.preventDefault();
                                        $(this).toggleClass("active");
                                        $(this).parent().siblings('.description').toggleClass('full-text');
                            });
                        },
                        error: function(response) {

                            hasData = false;
                        }
                    });
                }
            }
        });

    });
</script>
<script type="text/javascript" src="<?php echo base_url('assets/inilabs/fb-img-grid/images-grid.js'); ?>"></script>
<script>
    $(document).ready(function() {


        $('.js-activities-btn').on('click', function(e) {
            e.preventDefault();
            $('#js-activities-sidebar').toggleClass('active');
        });

        $(window).load(function() {
            url = "<?= base_url('uploads/activities/') ?>";
            user_photo = "<?= imagelink($this->session->userdata('photo'),56) ?>";
            user_name = "<?= $this->session->userdata('name') ?>";

            $('div[id^="gallery"]').each(function(i, e) {

                    var assets_url = "<?php echo base_url('uploads/activities/') ?>";
                    var jQueryArray = <?php echo json_encode($activitiesmedia); ?>;
                    data = JSON.stringify(jQueryArray);
                    data = JSON.parse(data);
                    notice_images = data[$(e).data('activity_id')];
                    image_url = [];
                    if (typeof notice_images !== 'undefined') {
                        notice_images.forEach(function(value, i) {
                            var xhr = new XMLHttpRequest();
                            xhr.open('HEAD', assets_url+'512/'+value.attachment, false);
                            xhr.send();

                            if (xhr.status == "404") { 
                                image_url.push({
                                    src: assets_url+value.attachment, 
                                    thumbnail: assets_url+value.attachment, 
                                    caption:  value.caption 
                                });
                            }
                            else{
                                image_url.push({
                                    src: assets_url+value.attachment, 
                                    thumbnail: assets_url+'512/'+value.attachment, 
                                    caption:  value.caption 
                                });
                            }
                                
                                });
                                $("#" + $(e).attr('id')).imagesGrid({
                                images: image_url,
                            });
                    }
            });   

        });



        $(".activity_comment").keypress(function(e) {
            comt = $(this);
            comment = $(this).val();
            activity_id = $(this).data('activity_id');
            now = "<?= getRangeDateString(date_create(date("Y-m-d h:i:s"))->getTimestamp()) ?>";

            var code = (e.keyCode ? e.keyCode : e.which);
            if (code == 13) {
                $.ajax({
                    type: 'POST',
                    url: "<?= base_url('activities/comment') ?>",
                    data: {
                        'activity_id': $(this).data('activity_id'),
                        'comment': $(this).val()
                    },
                    dataType: "json",
                    success: function(data) {
                        // console.log(data);

                        $("#commentbox" + activity_id).append("<div class=\"card-header\"><div class=\"media-block\"><div class=\"avatar\"><img src=\"" + user_photo + "\" class=\"avatar-img\" alt=\"\"/></div><div class=\"media-block-body\"><ul class=\"list-inline list-inline--social-meta\"><li><h4><b>" + user_name + "</b></h4></li><li class=\"date-list\"><span class=\"date\">" + now + "</span></li></ul></div><a href=\"javascript:void(0)\" class=\"icon-round__trash\" data-toggle=\"modal\" data-target=\"#commentModal\" data-comment-id=" + data + "  data-activities-id=" + activity_id + "><i class=\"fa fa-edit\"></i></a><a href=\"javascript:void(0)\" data-comment-id=" + data + "  data-activity_id=" + activity_id + " class=\"icon-round collapsed icon-round__trash\" role=\"button\"><i class=\"fa fa-trash\"></i></a></div><div class=\"card-title-wrapper\"><p id="+activity_id+'-'+data+">" + comment + "</p></div></div>");
                        comt.val('');
                        comment_count = $(".comment_count_" + activity_id).html();
                        if (comment_count != 0) {
                            $(".comment_count_" + activity_id).html(parseInt(comment_count) + 1);
                        } else {
                            // console.log('helo');
                            $(".comment_" + activity_id).show();
                            $(".comment_count_" + activity_id).html(parseInt(comment_count) + 1);
                        }
                        $("#commentbox" + activity_id).removeClass('collapse');
                        $("#commentbox" + activity_id).addClass('in');
                        $("#commentbox" + activity_id).css("height: auto");
                    }
                });
            }
        });

    });


    $(".commentbox").on('click', '.icon-round', function(e) {
        e.preventDefault();
        var result = confirm("Are you sure to delete?");
        var classes = $(this).parent().parent('div');
        comment_id = $(this).data('comment-id');
        activity_id = $(this).data('activity_id');

        if (result) {
            $.ajax({
                type: 'POST',
                url: "<?= base_url('activities/delete_comment'); ?>",
                data: {
                    id: comment_id
                },
                dataType: "html",
                success: function(data) {

                    var response = jQuery.parseJSON(data);
                    if (response.status) {
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
                    classes.remove();

                    comment_count = $(".comment_count_" + activity_id).html();
                    if (comment_count != 0) {
                        $(".comment_count_" + activity_id).html(parseInt(comment_count) - 1);
                    } else {
                        // console.log('helo');
                        $(".comment_" + activity_id).show();
                        $(".comment_count_" + activity_id).html(parseInt(comment_count) - 1);
                    }
                    $("#commentbox" + activity_id).removeClass('collapse');
                    $("#commentbox" + activity_id).addClass('in');
                    $("#commentbox" + activity_id).css("height: auto");
                }

            });
        }



    });
</script>
</div>