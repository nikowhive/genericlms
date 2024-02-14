<?php foreach ($feeds as $feed) { ?>
                <div class="card p-0 card--media" data-type="event">
                    <div class="card-header">
                        <div class="media-block">
                            <div class="avatar">
                                <img
                                        src="<?= base_url('uploads/images/' . $feed->user_image) ?>"
                                        class="avatar-img"
                                        alt=""
                                />
                            </div>
                            <div class="media-block-body">
                                <ul class="list-inline list-inline--social-meta">
                                    <li class="pill-list"><span class="pill"> <?= $feed->type ?> </span></li>
                                    <li class="pill-list"><span class="pill"> <?= ucfirst($feed->status) ?> </span></li>
                                    <li><?= $feed->created_by ?></li>
                                    <li class="date-list">
                                        <span class="date"><?= getRangeDateString(date_create($feed->create_date)->getTimestamp()) ?></span>
                                    </li>
                                    <li>
                                    <?php if (permissionChecker('event_edit')) { ?>
                                                <div class="onoffswitch-small">
                                                    <input type="checkbox" name="course"
                                                           class="onoffswitch-small-checkbox"
                                                           id="event<?= $feed->eventID ?>" <?php if ($feed->published == '1') { ?> checked='checked' <?php }
                                                    if ($feed->published == '1') echo "value='2'"; else echo "value='1'"; ?>>
                                                    <label class="onoffswitch-small-label switch"
                                                           for="event<?= $feed->eventID ?>"
                                                           eventid="<?php echo $feed->eventID; ?>">
                                                        <span class="onoffswitch-small-inner"></span>
                                                        <span class="onoffswitch-small-switch"></span>
                                                    </label>
                                                </div>
                                            <?php } ?>
                                    </li>
                                </ul>
                            </div>
                            <?php if (permissionChecker('event_edit') || permissionChecker('event_delete')) { ?>
                                <div class="dropdown">
                                    <a href="#" class=" " data-toggle="dropdown"> ⋮</a>
                                    <ul id="menu2" class="dropdown-menu" aria-labelledby="drop5">
                                            <?php if (permissionChecker('event_view')) { ?>
                                              <li>
                                                <a href="<?= base_url('event/view/' . $feed->eventID) ?>"><i
                                                            class="fa fa-eye"></i> View</a>
                                            </li>
                                            <?php }?>
                                        <?php if (permissionChecker('event_edit')) { ?>
                                            <li>
                                                <a href="<?= base_url('event/edit/' . $feed->eventID) ?>"><i
                                                            class="fa fa-pencil"></i> Edit</a>
                                            </li>
                                        <?php } ?>
                                        <?php if (permissionChecker('event_delete')) { ?>
                                            <li>
                                                <a href="<?= base_url('event/delete/' . $feed->eventID) ?>"
                                                onclick="return confirm('Are you sure you want to delete this event?');"><i class="fa fa-trash"></i> Delete</a>
                                            </li>
                                        <?php } ?>
                                    </ul>
                                </div>
                            <?php } ?>
                        </div>

                        <div class="card-title-wrapper">
                           <?php if(count($feed->media) > 1 || (count($feed->media) != 1 && $feed->eventphoto == '')){ ?>    
                                      <h3 class="card-title">
                                      <?= $feed->title ?>
                                    </h3>
                                    <div class="time-block mt-2">
                                        <i class="fa fa-clock-o text-danger"></i>
                                        <small>
                                            <b>
                                                <?= date_format(date_create($feed->fdate), "M d Y") ?>
                                                <?= date_format(date_create($feed->ftime), "g:i a") ?> -
                                                <?= date_format(date_create($feed->tdate), "M d Y") ?>
                                                <?= date_format(date_create($feed->ttime), "g:i a") ?>
                                            </b>
                                        </small>
                                    </div>
                                    <?php } ?>
                        </div>

                    </div>
                    <?php if(count($feed->media) == 1){
                        $photourl = base_url("uploads/events/" . $feed->media[0]);
                        $thumb_photourl = base_url("uploads/events/256" . $feed->media[0]);
                    ?>
                    <figure class="card-img card-img-auto">
                        <img src="<?php echo $thumb_photourl ?>" data-img="<?php echo $photourl ?>" class="img-absolute-full myImg" alt="" />
                        <figcaption data-background>
                            <h1 class="display-title"><?=$feed->title?></h1>
                            <div class="time-block mt-2">
                                    <i class="fa fa-calendar fa-2x text-danger"></i>
                                    <b>
                                        <?= date_format(date_create($feed->fdate), "M d Y") ?>
                                        <?= date_format(date_create($feed->ftime), "g:i a") ?> -
                                        <?= date_format(date_create($feed->tdate), "M d Y") ?>
                                        <?= date_format(date_create($feed->ttime), "g:i a") ?>
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
                    <div id="gallery<?=$feed->eventID ?>" data-feed_id="<?=$feed->eventID ?>" class="card-gallery"></div>
                    <?php }} ?>
                    <div class="card-footer">
                        <div id="overall-event-info-<?=$feed->eventID?>"><b><?= $feed->going ?></b> Going - <b><?= $feed->not_going ?></b> Not Going</div>
                        <?php if ($userType != 1) { ?>
                            <div class="list-inline">
                                <?php if (isset($feed->is_going)) {
                                    if ($feed->is_going) { ?>
                                        <a href="#" class="btn btn-default btn-sm" id="going-btn<?=$feed->eventID?>"
                                           style="display: none"
                                           onclick="handleEvent('<?= $feed->eventID ?>', 1)">
                                            <i class="fa fa-check"></i> Going
                                        </a>
                                        <a href="#" class="btn btn-primary disabled btn-sm" id="disabled-going-btn<?=$feed->eventID?>">
                                            <i class="fa fa-check"></i> You are going</a
                                        >
                                        <a href="#" class="btn btn-default btn-sm" id="not-going-btn<?=$feed->eventID?>"
                                           onclick="handleEvent('<?= $feed->eventID ?>', 0)"> <i class="fa fa-close"></i>
                                            Not Going</a>
                                        <a href="#" class="btn btn-danger disabled btn-sm" id="disabled-not-going-btn<?=$feed->eventID?>"
                                           style="display: none">
                                            <i class="fa fa-close"></i> You are not going</a
                                        >
                                    <?php } else { ?>
                                        <a href="#" class="btn btn-default btn-sm" id="going-btn<?=$feed->eventID?>"
                                           onclick="handleEvent('<?= $feed->eventID ?>', 1)">
                                            <i class="fa fa-check"></i> Going
                                        </a>
                                        <a href="#" class="btn btn-primary disabled btn-sm" id="disabled-going-btn<?=$feed->eventID?>" style="display: none">
                                            <i class="fa fa-check"></i> You are going</a
                                        >
                                        <a href="#" class="btn btn-danger disabled btn-sm" id="disabled-not-going-btn<?=$feed->eventID?>">
                                            <i class="fa fa-close"></i> You are not going</a
                                        >
                                        <a href="#" class="btn btn-default btn-sm" id="not-going-btn<?=$feed->eventID?>" style="display: none"
                                           onclick="handleEvent('<?= $feed->eventID ?>', 0)"> <i class="fa fa-close"></i>
                                            Not Going</a>
                                    <?php } ?>
                                <?php } else { ?>
                                    <a href="#" class="btn btn-primary disabled btn-sm" id="disabled-going-btn<?=$feed->eventID?>" style="display: none">
                                        <i class="fa fa-check"></i> You are going</a
                                    >
                                    <a href="#" class="btn btn-default btn-sm" id="going-btn<?=$feed->eventID?>"
                                       onclick="handleEvent('<?= $feed->eventID ?>', 1)">
                                        <i class="fa fa-check"></i> Going
                                    </a>
                                    <a href="#" class="btn btn-default btn-sm" id="not-going-btn<?=$feed->eventID?>"
                                       onclick="handleEvent('<?= $feed->eventID ?>', 0)"> <i class="fa fa-close"></i>
                                        Not Going</a>
                                    <a href="#" class="btn btn-danger disabled btn-sm" id="disabled-not-going-btn<?=$feed->eventID?>" style="display: none">
                                        <i class="fa fa-check"></i> You are not going</a
                                    >

                                <?php } ?>
                            </div>
                        <?php } ?>
                    </div>
                    <?php if($feed->enable_comment == 1): ?>
                    <div class="card-footer">
                                <div>
                                <b><a href="#commentbox<?= $feed->eventID ?>" role="button" data-toggle="collapse">
                                        <?php
                                        if (isset($comments[$feed->eventID])) {
                                            if ($feed->comment_count == 1) {
                                                echo '<span class=comment_count_' . $feed->eventID . '>' . $feed->comment_count . '</span> ' . $this->lang->line('event_comment');
                                            } else {
                                                echo '<span class=comment_count_' . $feed->eventID . '>' . $feed->comment_count . '</span> ' . $this->lang->line('event_comments');
                                            }
                                        } else {
                                            echo '<span style="display:none" class=comment_' . $feed->eventID . '><span class=comment_count_' . $feed->eventID . '>0</span> ' . $this->lang->line('event_comments') . '</span>';
                                        }
                                        ?>
                                    </a>
                                </b>
                                </div>
                    </div>

                    <div class="commentbox collapse" id="commentbox<?= $feed->eventID ?>">
                    <?php if ($feed->comment_count > 5) { ?>
                            <div id="viewWrapper<?= $feed->eventID ?>" style="text-align: left;padding-left: 22px;padding-top: 10px;padding-bottom: 8px;">
                                <a href="javascript:void(0)" id="more<?= $feed->eventID ?>" class="viewMoreComment" data-start = "5" data-event-id="<?= $feed->eventID ?>">View More Comment</a>
                            </div> 
                        <?php } ?> 
                               <?php if (customCompute($comments) && isset($comments[$feed->eventID])) { ?>
                                    <?php foreach ($comments[$feed->eventID] as $comment) {
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
                                                        <a href="javascript:void(0)" class="icon-round__trash" data-toggle="modal" data-target="#commentModal" data-comment-id="<?= $comment->commentID; ?>" data-event-id="<?= $feed->eventID ?>">
                                                            <i class="fa fa-edit"></i>
                                                        </a>   
                                                        <a href="javascript:void(0)" class="icon-round collapsed icon-round__trash" role="button" data-comment-id="<?= $comment->commentID; ?>" data-activity_id="<?= $feed->eventID ?>">
                                                            <i class="fa fa-trash"></i>
                                                        </a>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="card-title-wrapper">
                                                    <p id="<?= $feed->eventID ?>-<?= $comment->commentID; ?>">
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
                                            <textarea name=""  id="comment<?= $feed->eventID ?>" data-activity_id="<?= $feed->eventID ?>" class="md-textarea form-control notice_comment"></textarea>
                                            <label for="comment<?= $feed->eventID ?>">Write your comment</label>
                                        </div>
                                    </div>
                                </div>
                    </div>
                    <?php endif; ?>


                </div>

            <?php } ?>

    <script>
// Get the modal
var modal = document.getElementById("feedModal");

// Get the image and insert it inside the modal - use its "alt" text as a caption
var img = $('.myImg');
var modalImg = document.getElementById("img01");
var captionText = document.getElementById("caption");

img.click(function(){
    modal.style.display = "block";
  modalImg.src = this.src;
  captionText.innerHTML = this.alt;
});

// Get the <span> element that closes the modal
var span = document.getElementsByClassName("closeBtn")[0];

// When the user clicks on <span> (x), close the modal
span.onclick = function() { 
  modal.style.display = "none";
}
</script> 
<script>


$('.viewMoreComment').click(function(){

eventID = $(this).data('event-id');
pageValue = $(this).data('start');

$.ajax({
    url: "<?= base_url('event/getMoreEventCommentData/') ?>" + pageValue,
    type: "get",
    data: {'eventID': eventID},
    success: function(response) {
        pageValue += 5;
        $("#commentbox" + eventID).before($('#viewWrapper'+eventID)).prepend(response);
        $('#more'+eventID).data('start',pageValue);
    },
    error: function(response) {
        $('#viewWrapper'+eventID).hide();
    }
});

});


$('div[id^="gallery"]').each(function(i, e) {

        var assets_url = "<?php echo base_url('uploads/events/') ?>";
        var jQueryArray = <?php echo json_encode($eventsMedia); ?>;
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
//  $('.image-wrap').find('img').bttrlazyloading();
       
</script>

<script>
    $(function() {

        user_photo = "<?= imagelink($this->session->userdata('photo'),56) ?>";
        user_name = "<?= $this->session->userdata('name') ?>";

        $(".notice_comment").keypress(function(e) {
            comt = $(this);
            comment = $(this).val();
            activity_id = $(this).data('activity_id');
            now = "<?= getRangeDateString(date_create(date("Y-m-d h:i:s"))->getTimestamp()) ?>";

            var code = (e.keyCode ? e.keyCode : e.which);
            if (code == 13) {
                $.ajax({
                    type: 'POST',
                    url: "<?= base_url('event/comment') ?>",
                    data: {
                        'activity_id': $(this).data('activity_id'),
                        'comment': $(this).val()
                    },
                    dataType: "json",
                    success: function(data) {
                      $("#commentbox" + activity_id).append("<div class=\"card-header\"><div class=\"media-block\"><div class=\"avatar\"><img src=\"" + user_photo + "\" class=\"avatar-img\" alt=\"\"/></div><div class=\"media-block-body\"><ul class=\"list-inline list-inline--social-meta\"><li><h4><b>" + user_name + "</b></h4></li><li class=\"date-list\"><span class=\"date\">" + now + "</span></li></ul></div><a href=\"javascript:void(0)\" class=\"icon-round__trash\" data-toggle=\"modal\" data-target=\"#commentModal\" data-comment-id=" + data + "  data-event-id=" + activity_id + "><i class=\"fa fa-edit\"></i></a><a href=\"javascript:void(0)\" data-comment-id=" + data + "  data-activity_id=" + activity_id + " class=\"icon-round collapsed icon-round__trash\" role=\"button\"><i class=\"fa fa-trash\"></i></a></div><div class=\"card-title-wrapper\"><p id="+activity_id+'-'+data+">" + comment + "</p></div></div>");
                        comt.val('');
                        comment_count = $(".comment_count_" + activity_id).html();
                        if (comment_count != 0) {
                            $(".comment_count_" + activity_id).html(parseInt(comment_count) + 1);
                        } else {
                           
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
            url: "<?= base_url('event/delete_comment'); ?>",
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

<script>
    $('.switch').click(function (e) {
       
        eventid = $(this).attr("eventid")
            $.ajax({
                type: 'POST',
                url: "<?=base_url('event/postChangeEventStatus/')?>" + eventid,
                dataType: "html",
                success: function (data) {
                    showSuccessToast();
                }
            });
        })
</script>
