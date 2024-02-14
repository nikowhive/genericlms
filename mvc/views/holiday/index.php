<div class="row row-flex">
    <div class="col-md-9">
        <div class="container container--sm" id="feeds-data">
            <header class="pg-header">
                <h1 class="pg-title"><?= $feeds ? $this->lang->line('panel_title') : $this->lang->line('no_holiday') ?></h1>
            <?php
            if (permissionChecker( 'holiday_add')) { ?>
                <div>
                        <a  href="<?php echo base_url( 'holiday/add') ?>" class="btn btn-success">
                            <i class="fa fa-plus"></i>
                            <?= $this->lang->line('add_title') ?>
                        </a>
                </div>
            <?php }?>
            </header>
            <?php foreach ($feeds as $feed) { ?>
                <div class="card p-0 card--media">
                    <div class="card-header">
                        <div class="media-block">
                            <div class="avatar">
                            <img src="<?= imagelink($feed->user_image,56) ?>" class="avatar-img" alt="" />
                            </div>
                            <div class="media-block-body">
                                <ul class="list-inline list-inline--social-meta">
                                    <li class="pill-list"><span class="pill"><?= $feed->type ?> </span></li>
                                    <li><?= $feed->created_by ?></li>
                                    <li class="date-list"><span class="date"><?= getRangeDateString(date_create($feed->create_date)->getTimestamp()) ?></span>
                                    </li>
                                    <li>
                                    <?php if (permissionChecker('holiday_edit')) { ?>
                                        <div class="onoffswitch-small">
                                            <input type="checkbox" name="course" class="onoffswitch-small-checkbox" id="holiday<?= $feed->holidayID ?>" <?php if ($feed->published == '1') { ?> checked='checked' <?php }
                                                    if ($feed->published == '1') echo "value='2'"; else echo "value='1'"; ?>>
                                                    <label class="onoffswitch-small-label switch"
                                                           for="holiday<?= $feed->holidayID ?>"
                                                           holidayid="<?php echo $feed->holidayID; ?>">
                                                        <span class="onoffswitch-small-inner"></span>
                                                        <span class="onoffswitch-small-switch"></span>
                                                    </label>
                                        </div>
                                    <?php } ?>
                                    </li>
                                </ul>
                                                               
                            </div>
                            <?php if (permissionChecker('holiday_edit') || permissionChecker('holiday_delete')) { ?>
                                <div class="dropdown">
                                    <a href="#" class=" " data-toggle="dropdown"> ⋮</a>
                                    <ul id="menu2" class="dropdown-menu" aria-labelledby="drop5">
                                        <?php if (permissionChecker( 'holiday_view')) { ?>
                                            <li>
                                                <a href="<?=base_url('holiday/view/'.$feed->holidayID)?>"><i class="fa fa-eye"></i> View</a>
                                            </li>
                                        <?php } ?>
                                        <?php if (permissionChecker( 'holiday_edit')) { ?>
                                            <li>
                                                <a href="<?=base_url('holiday/edit/'.$feed->holidayID)?>"><i class="fa fa-pencil"></i> Edit</a>
                                            </li>
                                        <?php } ?>
                                        <?php if (permissionChecker( 'holiday_delete')) { ?>
                                            <li>
                                                <a href="<?= base_url('holiday/delete/' . $feed->holidayID) ?>"
                                                   onclick="return confirm('Are you sure you want to delete this holiday?');"><i class="fa fa-trash"></i> Delete</a>
                                            </li>
                                        <?php } ?>
                                    </ul>
                                </div>
                            <?php } ?>
                        </div>

                        <div class="card-title-wrapper">
                                <?php if(count($feed->media) > 1 || (count($feed->media) != 1 && $feed->holidayPhoto == '')){ ?>
                                    <h3 class="card-title">
                                      <?= $feed->title ?>
                                    </h3>
                                    <div class="time-block mt-2">
                                    <i class="fa fa-clock-o text-danger"></i>
                                        <small>
                                        <b>
                                           <?= date('F j, Y',strtotime($feed->fdate)) ?> - <?= date('F j, Y',strtotime($feed->tdate)) ?>
                                        </b>
                                        </small>
                                    </div>
                                    <?php } ?>
                        </div>
                        
                    </div>

                    <?php
                    if(count($feed->media) == 1 || $feed->holidayPhoto != ''){
                        $media1 = ($feed->media) ? $feed->media[0] : '';
                        $photo = ($media1) ? $media1 : $feed->holidayPhoto;
                        $photourl = '';
                        if ($media1) {
                            $photourl = base_url("uploads/holiday/" . $photo);
                            //$thumb_photourl = base_url("uploads/holiday/256/" . $photo);
                            $thumb_photourl = imagelink1($photo,512,"uploads/holiday");

                        }else{
                            $photourl = base_url("uploads/images/" . $photo);
                            //$thumb_photourl = base_url("uploads/holiday/256/" . $photo);
                            $thumb_photourl = imagelink1($photo,512,"uploads/images");

                        }
                    ?>
                        <figure class="card-img card-img-auto">
                            <img src="<?php echo $thumb_photourl ?>" data-img="<?php echo $photourl ?>" class="img-absolute-full myImg" alt="" />
                            <figcaption data-background>
                                <h1 class="display-title"><?= $feed->title ?></h1>
                                <div class="time-block mt-2">
                                    <i class="fa fa-calendar fa-2x text-danger"></i>
                                    <b>
                                        <?= date_format(date_create($feed->fdate), "M d Y") ?>
                                       -
                                        <?= date_format(date_create($feed->tdate), "M d Y") ?>
                                    </b>
                                </div>
                            </figcaption>
                        </figure>

                    <div class="card-body description more">
                        <?=$feed->details?>
                    </div>
                    <?php if(strlen($feed->details) > 500){ ?>
                    <div class="read-more"><a href="#" class=""><span>READ MORE</span></a></div>
                    <?php } ?>
                    <?php }else{ ?>

                    <div class="card-body description more">
                        <?=$feed->details?>
                    </div>
                    <?php if(strlen($feed->details) > 500){ ?>
                    <div class="read-more"><a href="#" class=""><span>READ MORE</span></a></div>
                    <?php } ?>
                    <?php if(count($feed->media) > 1){ ?>
                    <div id="gallery<?=$feed->holidayID ?>" data-feed_id="<?=$feed->holidayID ?>" class="card-gallery"></div>
                   <?php }} ?>
                  
                    <?php if($feed->enable_comment == 1): ?>
                    <div class="card-footer">
                                <div>
                                    <b><a href="#commentbox<?= $feed->holidayID ?>" role="button" data-toggle="collapse">
                                            <?php
                                            if (isset($comments[$feed->holidayID])) {
                                                if ($feed->comment_count == 1) {
                                                    echo '<span class=comment_count_' . $feed->holidayID . '>' . $feed->comment_count . '</span> ' . $this->lang->line('holiday_comment');
                                                } else {
                                                    echo '<span class=comment_count_' . $feed->holidayID . '>' . $feed->comment_count . '</span> ' . $this->lang->line('holiday_comments');
                                                }
                                            } else {
                                                echo '<span style="display:none" class=comment_' . $feed->holidayID . '><span class=comment_count_' . $feed->holidayID . '>0</span> ' . $this->lang->line('holiday_comments') . '</span>';
                                            }
                                            ?>
                                        </a></b>
                                </div>
                    </div>

                    <div class="commentbox collapse" id="commentbox<?= $feed->holidayID ?>">
                                <?php if ($feed->comment_count > 5) { ?>
                                    <div id="viewWrapper<?= $feed->holidayID ?>" style="text-align: left;padding-left: 22px;padding-top: 10px;padding-bottom: 8px;">
                                    <a href="javascript:void(0)" id="more<?= $feed->holidayID ?>" class="viewMoreComment" data-start = "5" data-holiday-id="<?= $feed->holidayID ?>">View More Comment</a>
                                    </div> 
                                <?php } ?>
                                <?php if (customCompute($comments) && isset($comments[$feed->holidayID])) { ?>
                                    <?php foreach ($comments[$feed->holidayID] as $comment) {
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
                                                        <a href="javascript:void(0)" class="icon-round__trash" data-toggle="modal" data-target="#commentModal" data-comment-id="<?= $comment->commentID; ?>" data-holiday-id="<?= $feed->holidayID ?>">
                                                            <i class="fa fa-edit"></i>
                                                        </a>
                                                        <a href="javascript:void(0)" class="icon-round collapsed icon-round__trash" role="button" data-comment-id="<?= $comment->commentID; ?>" data-activity_id="<?= $feed->holidayID ?>">
                                                            <i class="fa fa-trash"></i>
                                                        </a>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="card-title-wrapper">
                                                    <p id="<?= $feed->holidayID ?>-<?= $comment->commentID; ?>">
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
                                            <textarea name=""  id="comment<?= $feed->holidayID ?>" data-activity_id="<?= $feed->holidayID ?>" class="md-textarea form-control holiday_comment"></textarea>
                                            <label for="comment<?= $feed->holidayID ?>">Write your comment</label>
                                        </div>
                                    </div>
                                </div>
                    </div>
                    <?php endif; ?>
                </div>
            <?php } ?>
            <!-- <?//=$links?> -->
        </div>
    </div>
    <div class="col-auto"></div>
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

<script type="text/javascript" src="<?php echo base_url('assets/inilabs/fb-img-grid/images-grid.js'); ?>"></script>

<script type="application/javascript">

$('.viewMoreComment').click(function(){

holidayID = $(this).data('holiday-id');
pageValue = $(this).data('start');

$.ajax({
    url: "<?= base_url('holiday/getMoreHolidayCommentData/') ?>" + pageValue,
    type: "get",
    data: {'holidayID': holidayID},
    success: function(response) {
        pageValue += 5;
        $("#commentbox" + holidayID).before($('#viewWrapper'+holidayID)).prepend(response);
        $('#more'+holidayID).data('start',pageValue);
    },
    error: function(response) {
        $('#viewWrapper'+holidayID).hide();
    }
});

});

$('#commentModal').on('show.bs.modal', function(e) {

var commentId = $(e.relatedTarget).data('comment-id');
var holidayId = $(e.relatedTarget).data('holiday-id');

$.ajax({
    url: "<?= base_url('holiday/getComment/') ?>",
    type: "get",
    data:{'commentID':commentId,'holidayID':holidayId},
    success: function(response) {
        $('#commentModal').find('.commentWrapper').html(response); 
        $('#commentBtn').on('click', function(e) {
            e.preventDefault();
            $.ajax({
                type: "POST",
                url: "<?= base_url('holiday/editComment') ?>",
                data: $('#commentForm').serialize(),
                success: function(res) {
                   if(res){
                      $('#'+holidayId+'-'+commentId).html(res);
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

    $(document).ready(function(){
        
    var pageValue = 0;
    var hasData = true;
    $('body').scroll(function () {
        if ($('body').scrollTop() + $('body').height() >= $(document).height()) {
            pageValue += 20;
          
            if(hasData) {
                $.ajax({
                    url: "<?=base_url('holiday/getMoreHolidayData/')?>" + pageValue,
                    type: "get",
                    success: function (response) {
                        console.log(response);
                        $('#feeds-data').append(response);
                        $(".card--media .read-more a").click(function(e) {
                                e.preventDefault();
                                $(this).toggleClass("active");
                                $(this).parent().siblings('.description').toggleClass('full-text');
                        });
                    },
                    error: function (response) {
                        console.log(response);
                        hasData = false;
                    }
                });
            }
        }
    });

    });
    
    $(window).load(function() {
        
        $('div[id^="gallery"]').each(function(i, e) {
            var assets_url = "<?php echo base_url('uploads/holiday/') ?>";
            var jQueryArray = <?php echo json_encode($holidaysMedia); ?>;
            data = JSON.stringify(jQueryArray);
            data = JSON.parse(data);
            notice_images = data[$(e).data('feed_id')];
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
    
 
</script>

<script>

    $('.switch').click(function (e) {
       
        holidayid = $(this).attr("holidayid");
           $.ajax({
               type: 'POST',
               url: "<?=base_url('holiday/postChangeHolidayStatus/')?>" + holidayid,
               dataType: "html",
               success: function (data) {
                   showSuccessToast();
               }
           });
       })
</script>

<script>
    $(function() {

        user_photo = "<?= imagelink($this->session->userdata('photo'),56) ?>";
        user_name = "<?= $this->session->userdata('name') ?>";

        $(".holiday_comment").keypress(function(e) {
            comt = $(this);
            comment = $(this).val();
            activity_id = $(this).data('activity_id');
            now = "<?= getRangeDateString(date_create(date("Y-m-d h:i:s"))->getTimestamp()) ?>";

            var code = (e.keyCode ? e.keyCode : e.which);
            if (code == 13) {
                $.ajax({
                    type: 'POST',
                    url: "<?= base_url('holiday/comment') ?>",
                    data: {
                        'activity_id': $(this).data('activity_id'),
                        'comment': $(this).val()
                    },
                    dataType: "json",
                    success: function(data) {
                        // console.log(data);

                        $("#commentbox" + activity_id).append("<div class=\"card-header\"><div class=\"media-block\"><div class=\"avatar\"><img src=\"" + user_photo + "\" class=\"avatar-img\" alt=\"\"/></div><div class=\"media-block-body\"><ul class=\"list-inline list-inline--social-meta\"><li><h4><b>" + user_name + "</b></h4></li><li class=\"date-list\"><span class=\"date\">" + now + "</span></li></ul></div><a href=\"javascript:void(0)\" class=\"icon-round__trash\" data-toggle=\"modal\" data-target=\"#commentModal\" data-comment-id=" + data + "  data-holiday-id=" + activity_id + "><i class=\"fa fa-edit\"></i></a><a href=\"javascript:void(0)\" data-comment-id=" + data + "  data-activity_id=" + activity_id + " class=\"icon-round collapsed icon-round__trash\" role=\"button\"><i class=\"fa fa-trash\"></i></a></div><div class=\"card-title-wrapper\"><p id="+activity_id+'-'+data+">" + comment + "</p></div></div>");
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

        var classes = $(this).parent().parent('div');
        comment_id = $(this).data('comment-id');
        activity_id = $(this).data('activity_id');
        $.ajax({
            type: 'POST',
            url: "<?= base_url('holiday/delete_comment'); ?>",
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

    });
</script>