<header class="pg-header">
    <h1 class="pg-title"><?=$this->lang->line('panel_title')?></h1>
</header>
<?php 
// dd($feeds);
foreach ($feeds as $feed) { 
    $type = strtolower($feed->type);
    $typeid = 'itemID'; 
    if ($feed->type == 'event') { ?>
        <div class="card p-0 card--media" data-type="event">
            <div class="card-header">
                <div class="media-block">
                    <div class="avatar">
                        <img src="<?= imagelink($feed->user_image,56) ?>" class="avatar-img" alt="" />
                    </div> 
                    <div class="media-block-body">
                        <ul class="list-inline list-inline--social-meta">
                            <li class="pill-list"><span class="pill"> <?= ucfirst($feed->type) ?> </span></li>
                            <li><?= $feed->created_by ?></li>
                            <li class="date-list"><span class="date"><?= getRangeDateString(date_create($feed->create_date)->getTimestamp()) ?></span></li>
                        </ul>
                    </div>
                </div>
                <div class="card-title-wrapper">
                    <?php if(count($feed->media) > 1 || (count($feed->media) != 1 && $feed->feedphoto == '')){ ?>    
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
            <?php
            if(count($feed->media) == 1 || $feed->feedphoto != ''){
            $media1 = ($feed->media) ? $feed->media[0] : '';
            $photo = ($media1) ? $media1 : $feed->feedphoto;
            $photourl = '';
            if ($media1) {
                $photourl = base_url("uploads/events/" . $photo);
                //$thumb_photourl = base_url("uploads/events/256/" . $photo);
                $thumb_photourl = imagelink1($photo,768,"uploads/events");
            }else{
                $photourl = base_url("uploads/images/" . $photo);
                //$thumb_photourl = base_url("uploads/images/256/" . $photo);
                $thumb_photourl = imagelink1($photo,768,"uploads/images");

            }
            ?>
            <figure class="card-img card-img-auto">
                <?php if($photourl != ""){ ?>
                    <img src="<?php echo $thumb_photourl ?>" data-img="<?php echo $photourl ?>" class="img-absolute-full myImg" alt="" />
                <?php } ?>
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
                <div id="galleryEvent<?=$feed->itemID ?>" data-feed_id="<?=$feed->itemID ?>" class="card-gallery"></div>
            <?php }} ?>
            <div class="card-footer">
                <div id="overall-event-info-<?= $feed->unique_id ?>"><b><?= $feed->going ?></b> Going -
                        <b><?= $feed->not_going ?></b> Not Going
                </div>
                <?php if ($userType != 1) { ?>
                <div class="list-inline">
                    <?php if (isset($feed->is_going)) {
                        if ($feed->is_going) { ?>
                            <a href="#" class="btn btn-default btn-sm" id="going-btn<?= $feed->unique_id ?>" style="display: none"
                                onclick="handleEvent('<?=$feed->unique_id?>', '<?= $feed->itemID ?>', 1)">
                                <i class="fa fa-check"></i> Going
                            </a>
                            <a href="#" class="btn btn-primary disabled btn-sm"
                                id="disabled-going-btn<?= $feed->unique_id ?>">
                                <i class="fa fa-check"></i> You are going
                            </a>
                            <a href="#" class="btn btn-default btn-sm"
                                id="not-going-btn<?= $feed->unique_id ?>"
                                onclick="handleEvent('<?=$feed->unique_id?>', '<?= $feed->itemID ?>', 0)"> <i
                                class="fa fa-close"></i>Not Going
                            </a>
                            <a href="#" class="btn btn-danger disabled btn-sm"
                                id="disabled-not-going-btn<?= $feed->unique_id ?>"
                                style="display: none">
                                <i class="fa fa-close"></i> You are not going
                            </a>
                        <?php } else { ?>
                            <a href="#" class="btn btn-default btn-sm" id="going-btn<?= $feed->unique_id ?>"
                                onclick="handleEvent('<?=$feed->unique_id?>', '<?= $feed->itemID ?>', 1)">
                                <i class="fa fa-check"></i> Going
                            </a>
                            <a href="#" class="btn btn-primary disabled btn-sm" id="disabled-going-btn<?= $feed->unique_id ?>" style="display: none">
                                <i class="fa fa-check"></i> You are going
                            </a>
                            <a href="#" class="btn btn-danger disabled btn-sm" id="disabled-not-going-btn<?= $feed->unique_id ?>">
                                <i class="fa fa-close"></i> You are not going
                            </a>
                            <a href="#" class="btn btn-default btn-sm" id="not-going-btn<?= $feed->unique_id ?>" style="display: none"
                                onclick="handleEvent('<?=$feed->unique_id?>', '<?= $feed->itemID ?>', 0)"> <i
                                class="fa fa-close"></i>Not Going
                            </a>
                        <?php } ?>
                    <?php } else { ?>
                            <a href="#" class="btn btn-primary disabled btn-sm" id="disabled-going-btn<?= $feed->unique_id ?>" style="display: none">
                                <i class="fa fa-check"></i> You are going
                            </a>
                            <a href="#" class="btn btn-default btn-sm" id="going-btn<?= $feed->unique_id ?>"
                                onclick="handleEvent('<?=$feed->unique_id?>', '<?= $feed->itemID ?>', 1)">
                                <i class="fa fa-check"></i> Going
                            </a>
                            <a href="#" class="btn btn-default btn-sm"
                                id="not-going-btn<?= $feed->unique_id ?>"
                                onclick="handleEvent('<?=$feed->unique_id?>', '<?= $feed->itemID ?>', 0)"> <i
                                class="fa fa-close"></i>Not Going
                            </a>
                            <a href="#" class="btn btn-danger disabled btn-sm" id="disabled-not-going-btn<?= $feed->unique_id ?>" style="display: none">
                                <i class="fa fa-check"></i> You are not going
                            </a>
                    <?php } ?>
                </div>
                <?php } ?>
            </div>

            <!-- comment start -->
            <?php 
            if($feed->enable_comment == 1): ?>
                <div class="card-footer">
                    <div>
                        <b><a href="#<?= $type; ?>commentbox<?= $feed->$typeid ?>" role="button" data-toggle="collapse">
                            <?php
                            if (isset($comments[$type][$feed->$typeid])) {
                                if ($feed->comment_count == 1) {
                                    echo '<span class='.$type.'comment_count_' . $feed->$typeid . '>' . $feed->comment_count . '</span> ' . 'Comment';
                                } else {
                                    echo '<span class='.$type.'comment_count_' . $feed->$typeid . '>' . $feed->comment_count . '</span> ' . 'Comments';
                                }
                            } else {
                                echo '<span style="display:none" class='.$type.'comment_' . $feed->$typeid . '><span class=comment_count_' . $feed->$typeid . '>0</span> ' . 'Comments' . '</span>';
                            }
                            ?>
                        </a></b>
                    </div>
                </div>

                <div class="<?= $type; ?>commentbox collapse" id="<?= $type; ?>commentbox<?= $feed->$typeid ?>">
                <?php if ($feed->comment_count > 5) { ?>
                        <div id="<?= $type; ?>ViewWrapper<?= $feed->$typeid ?>" style="text-align: left;padding-left: 22px;padding-top: 10px;padding-bottom: 8px;">
                          <a href="javascript:void(0)" id="<?= $type; ?>More<?= $feed->$typeid ?>" class="viewMoreComment" data-start = "5" data-feed-id="<?= $feed->$typeid ?>" data-type="<?= $type; ?>">View More Comment</a>
                        </div> 
                <?php } ?>    
                <?php if (customCompute($comments[$type]) && isset($comments[$type][$feed->$typeid])) { ?>
                        <?php foreach ($comments[$type][$feed->$typeid] as $comment) {
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
                                            <?php if( $this->session->userdata('loginuserID') == $comment->userID && $this->session->userdata('usertypeID') == $comment->usertypeID ) : ?>
                                                <a href="javascript:void(0)" data-toggle="modal" data-target="#commentModal" data-comment-id="<?= $comment->commentID; ?>" data-feed-id="<?= $feed->$typeid ?>" data-type="<?= $type ?>">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                                <a href="javascript:void(0)" class="icon-round collapsed icon-round__trash" role="button" data-comment-id="<?= $comment->commentID; ?>" data-feed_id="<?= $feed->$typeid ?>">
                                                    <i class="fa fa-trash"></i>
                                                </a>
                                            <?php endif; ?>
                                    </div>
                                    <div class="card-title-wrapper">
                                        <p id="<?= $type ?>-<?= $feed->$typeid ?>-<?= $comment->commentID; ?>">
                                            <?= $comment->comment ?>
                                        </p>
                                    </div>
                                </div>    
                            <?php }}
                        } ?>

                </div>

                <div class="card-header commentbox__postcomment">
                    <div class="media-block">
                        <div class="avatar">
                            <img src="<?= imagelink($this->session->userdata('photo'),56) ?>" class="avatar-img" alt="" />
                        </div>
                        <div class="media-block-body">
                            <div class="md-form mb-0 mt-0">
                                <textarea name=""  id="<?= $type; ?>comment<?= $feed->$typeid ?>" data-feed_id="<?= $feed->$typeid ?>" class="md-textarea form-control <?php echo $type; ?>_comment"></textarea>
                                <label for="<?= $type; ?>comment<?= $feed->$typeid ?>">Write your comment</label>
                            </div>
                        </div>
                    </div>
                </div>
        
            <?php endif; ?>
            <!-- comment end -->
        </div>
    <?php } else { ?>
        <div class="card p-0 card--media">
            <div class="card-header">
                <div class="media-block">
                    <div class="avatar">
                        <img src="<?= imagelink($feed->user_image,56) ?>" class="avatar-img" alt="" />
                    </div>
                    <div class="media-block-body">
                        <ul class="list-inline list-inline--social-meta">
                            <li class="pill-list"><span class="pill"> <?= ucfirst($feed->type) ?> </span></li>
                            <li><?= $feed->created_by ?></li>
                            <li class="date-list">
                                <span class="date"><?= getRangeDateString(date_create($feed->create_date)->getTimestamp()) ?></span>
                            </li>
                        </ul>
                    </div>
                </div>
                <?php if($feed->type == 'notice'){ ?>
                    <div class="card-title-wrapper">
                        <?php if(count($feed->media) > 1 ||  count($feed->media) == 0){ ?>
                            <h3 class="card-title"> <?= $feed->title ?></h3>
                        <?php } ?>
                    </div>
                <?php } ?>

                <?php if($feed->type == 'holiday'){ ?>
                    <div class="card-title-wrapper">
                        <?php if(count($feed->media) > 1 || (count($feed->media) != 1 && $feed->feedphoto == '')){ ?>
                            <h3 class="card-title"><?= $feed->title ?></h3>
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
                <?php } ?> 

                <?php if($feed->type == 'activity'){ ?>
                    <?php if(count($feed->media) > 1 ||  count($feed->media) == 0){ ?>
                        <div class="card-title-wrapper">
                            <h3 class="card-title"><?= $feed->title ?></h3>
                            <?php if ($feed->time_from && $feed->time_to) { ?>
                                <div class="time-block mt-2">
                                    <i class="fa fa-clock-o text-danger"></i>
                                    <small>
                                        <b><?php echo date("h:i A", strtotime($feed->time_from)); ?> - <?php echo date("h:i A", strtotime($feed->time_to)); ?></b>
                                    </small>
                                </div>
                            <?php } ?>
                        </div>
                    <?php
                }} ?>    
            </div>

            <!-- figure start -->
            <?php if($feed->type == "notice") { 
                if(count($feed->media) == 1){
                    $photourl = base_url("uploads/notice/" . $feed->media[0]);
                    //$thumb_photourl = base_url("uploads/notice/256/" . $feed->media[0]);
                    $thumb_photourl = imagelink1($feed->media[0],768,"uploads/notice");

            ?>
            <figure class="card-img card-img-auto">
                <img src="<?php echo $thumb_photourl ?>" data-img="<?php echo $photourl ?>" class="img-absolute-full myImg" alt="" />
                    <figcaption data-background>
                        <h1 class="display-title"><?=$feed->title?></h1>
                    </figcaption>
            </figure>

            <div class="card-body description more">
                <?= $feed->details ?>
            </div>
            <?php if(strlen($feed->details) > 500){ ?>
                <div class="read-more"><a href="#" class=""><span>READ MORE</span></a></div>
            <?php } ?>
            <?php }else{ ?>
                <div class="card-body description more">
                    <?= $feed->details ?>
                </div>  
                <?php if(strlen($feed->details) > 500){ ?>
                    <div class="read-more"><a href="#" class=""><span>READ MORE</span></a></div>
                <?php } ?>
                <?php if(count($feed->media) > 1){ ?>
                <div id="galleryNotice<?= $feed->itemID ?>" data-feed_id="<?= $feed->itemID ?>" class="card-gallery"></div>
            <?php }} ?>
           <?php } ?>  
                        
        <?php if($feed->type == "activity") { 
            if(count($feed->media) == 1){
                $photourl = base_url("uploads/activities/" . $feed->media[0]);
                //$thumb_photourl = base_url("uploads/activities/256/" . $feed->media[0]);
                $thumb_photourl = imagelink1($feed->media[0],768,"uploads/activities");

            ?>
                <figure class="card-img card-img-auto">
                    <img src="<?php echo $thumb_photourl ?>" data-img="<?php echo $photourl ?>" class="img-absolute-full myImg" alt="" />
                    <figcaption data-background="">
                        <h1 class="display-title"><?= $feed->title ?></h1>
                        <div class="time-block mt-2">
                            <i class="fa fa-calendar fa-2x text-danger"></i>
                            <b><?php echo date("h:i A", strtotime($feed->time_from)); ?> - <?php echo date("h:i A", strtotime($feed->time_to)); ?></b>
                        </div>
                    </figcaption>
                </figure>

                <div class="card-body description more">
                    <?= $feed->details ?>
                </div>
                <?php if(strlen($feed->details) > 500){ ?>
                    <div class="read-more"><a href="#" class=""><span>READ MORE</span></a></div>
                <?php } ?>

            <?php }else{ ?>
                <div class="card-body description more">
                    <?= $feed->details ?>
                </div> 
                <?php if(strlen($feed->details) > 500){ ?>
                    <div class="read-more"><a href="#" class=""><span>READ MORE</span></a></div>
                <?php } ?>
                <?php if(count($feed->media) > 1){ ?>
                <div id="galleryActivity<?= $feed->itemID ?>" data-feed_id="<?= $feed->itemID ?>" class="card-gallery"></div>
            <?php }} ?>
        <?php } ?>

        <?php if($feed->type == "holiday") {
            if(count($feed->media) == 1 || $feed->feedphoto != ''){
                $media1 = ($feed->media) ? $feed->media[0] : '';
                $photo = ($media1) ? $media1 : $feed->feedphoto;
                $photourl = '';
                if ($media1) {
                    $photourl = base_url("uploads/holiday/" . $photo);
                    //$thumb_photourl = base_url("uploads/holiday/256/" . $photo);
                    $thumb_photourl = imagelink1($photo,768,"uploads/holiday");

                }else{
                    $photourl = base_url("uploads/images/" . $photo);
                    //$thumb_photourl = base_url("uploads/holiday/256/" . $photo);
                    $thumb_photourl = imagelink1($photo,768,"uploads/images");

                }
        ?>
        <figure class="card-img card-img-auto">
            <img src="<?php echo $thumb_photourl ?>" data-img="<?php echo $photourl ?>" class="img-absolute-full myImg" alt="" />
            <figcaption data-background>
                <h1 class="display-title"><?=$feed->title?></h1>
                <div class="time-block mt-2">
                    <i class="fa fa-calendar fa-2x text-danger"></i>
                    <b>
                        <?= date('F j, Y',strtotime($feed->fdate)) ?> - <?= date('F j, Y',strtotime($feed->tdate)) ?>
                    </b>
                </div>
            </figcaption>
        </figure>
        <div class="card-body description more">
            <?=$feed->details?>
        </div>
        <?php if(strlen($feed->details) > 500){ ?>
            <div class="read-more"><a href="#" class=""><span>READ MORE</span></a></div>
        <?php } }else{ ?>
        <div class="card-body description more">
            <?=$feed->details?>
        </div>
        <?php if(strlen($feed->details) > 500){ ?>
            <div class="read-more"><a href="#" class=""><span>READ MORE</span></a></div>
        <?php } ?>
        <?php if(count($feed->media) > 1){ ?>
            <div id="galleryHoliday<?=$feed->itemID ?>" data-feed_id="<?=$feed->itemID ?>" class="card-gallery"></div>
        <?php }} ?>
        <?php } ?>

        <!-- comment start -->
        <?php if($feed->enable_comment == 1): ?>
            <div class="card-footer">
                <div>
                    <b><a href="#<?= $type; ?>commentbox<?= $feed->$typeid ?>" role="button" data-toggle="collapse">
                    <?php
                    if (isset($comments[$type][$feed->$typeid])) {
                        if ($feed->comment_count == 1) {
                            echo '<span class='.$type.'comment_count_' . $feed->$typeid . '>' . $feed->comment_count . '</span> ' . 'Comment';
                        } else {
                            echo '<span class='.$type.'comment_count_' . $feed->$typeid . '>' . $feed->comment_count . '</span> ' . 'Comments';
                        }
                    } else {
                            echo '<span style="display:none" class='.$type.'comment_' . $feed->$typeid . '><span class=comment_count_' . $feed->$typeid . '>0</span> ' . 'Comments' . '</span>';
                    }
                    ?>
                    </a></b>
                </div>
            </div>

            <div class="<?= $type; ?>commentbox collapse" id="<?= $type; ?>commentbox<?= $feed->$typeid ?>">
            <?php if ($feed->comment_count > 5) { ?>
                        <div id="<?= $type; ?>ViewWrapper<?= $feed->$typeid ?>" style="text-align: left;padding-left: 22px;padding-top: 10px;padding-bottom: 8px;">
                          <a href="javascript:void(0)" id="<?= $type; ?>More<?= $feed->$typeid ?>" class="viewMoreComment" data-start = "5" data-feed-id="<?= $feed->$typeid ?>" data-type="<?= $type; ?>">View More Comment</a>
                        </div> 
                <?php } ?>      
            <?php if (customCompute($comments[$type]) && isset($comments[$type][$feed->$typeid])) { ?>
                    <?php foreach ($comments[$type][$feed->$typeid] as $comment) {
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
                                    <?php
                                    if($feed->type == "activity"){
                                        $commentID = 'activitiescommentID';
                                    }else{
                                        $commentID = 'commentID';
                                    }
                                    ?>
                                    <?php if( $this->session->userdata('loginuserID') == $comment->userID && $this->session->userdata('usertypeID') == $comment->usertypeID ) : ?>
                                        <a href="javascript:void(0)" data-toggle="modal" data-target="#commentModal" data-comment-id="<?= $comment->$commentID; ?>" data-feed-id="<?= $feed->$typeid ?>" data-type="<?= $type ?>">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        <a href="javascript:void(0)" class="icon-round collapsed icon-round__trash" role="button" data-comment-id="<?= $comment->$commentID; ?>" data-feed_id="<?= $feed->$typeid ?>">
                                            <i class="fa fa-trash"></i>
                                        </a>
                                    <?php endif; ?>
                                </div>
                                <div class="card-title-wrapper">
                                    <p id="<?= $type ?>-<?= $feed->$typeid ?>-<?= $comment->$commentID; ?>">
                                        <?= $comment->comment ?>
                                    </p>
                                </div>
                            </div>
                        <?php } }} ?>
            </div>

            <div class="card-header commentbox__postcomment">
                <div class="media-block">
                    <div class="avatar">
                        <img src="<?= imagelink($this->session->userdata('photo'),56) ?>" class="avatar-img" alt="" />
                    </div>
                    <div class="media-block-body">
                        <div class="md-form mb-0 mt-0">
                            <textarea name=""  id="<?= $type; ?>comment<?= $feed->$typeid ?>" data-feed_id="<?= $feed->$typeid ?>" class="md-textarea form-control <?php echo $type; ?>_comment"></textarea>
                            <label for="<?= $type; ?>comment<?= $feed->$typeid ?>">Write your comment</label>
                        </div>
                    </div>
                </div>
            </div>
    <?php endif; ?>
    <!-- comment end -->
</div>
    <?php }
} ?>

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

<script type="text/javascript">

    $('.viewMoreComment').click(function(){

        feedID = $(this).data('feed-id');
        pageValue = $(this).data('start');
        feedType = $(this).data('type');

        if(feedType == 'notice'){
            id = 'noticeID';
            url = "<?= base_url('notice/getMoreNoticeCommentData/') ?>" + pageValue+'?noticeID='+feedID;
        }
        if(feedType == 'event'){
            id = 'eventID';
            url = "<?= base_url('event/getMoreEventCommentData/') ?>" + pageValue+'?eventID='+feedID;
        }
        if(feedType == 'holiday'){
            id = 'holidayID';
            url = "<?= base_url('holiday/getMoreHolidayCommentData/') ?>" + pageValue+'?holidayID='+feedID;
        }
        if(feedType == 'activity'){
            id = 'activitiesID';
            url = "<?= base_url('activities/getMoreActivityCommentData/') ?>" + pageValue+'?activitiesID='+feedID;
        }

        $.ajax({
            url: url,
            type: "get",
            success: function(response) {
                pageValue += 5;
                $('#'+feedType+'commentbox' + feedID).before($('#'+feedType+'ViewWrapper'+feedID)).prepend(response);
                $('#'+feedType+'More'+feedID).data('start',pageValue);
            },
            error: function(response) {
                $('#'+feedType+'ViewWrapper'+feedID).hide();
            }
        });

    });

    $('#commentModal').on('show.bs.modal', function(e) {

        var commentId = $(e.relatedTarget).data('comment-id');
        var feedId = $(e.relatedTarget).data('feed-id');
        var type = $(e.relatedTarget).data('type');

        if(type == 'notice'){
            $.ajax({
                url: "<?= base_url('notice/getComment/') ?>",
                type: "get",
                data:{
                    'commentID':commentId,
                    'noticeID':feedId
                    },
                success: function(response) {
                    $('#commentModal').find('.commentWrapper').html(response); 
                    $('#commentBtn').on('click', function(e) {
                        e.preventDefault();
                        $.ajax({
                            type: "POST",
                            url: "<?= base_url('notice/editComment') ?>",
                            data: $('#commentForm').serialize(),
                            success: function(res) {
                            if(res){
                                $('#'+type+'-'+feedId+'-'+commentId).html(res);
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
        }

        if(type == 'event'){
            $.ajax({
                url: "<?= base_url('event/getComment/') ?>",
                type: "get",
                data:{'commentID':commentId,'eventID':feedId},
                success: function(response) {
                    $('#commentModal').find('.commentWrapper').html(response); 
                    $('#commentBtn').on('click', function(e) {
                        e.preventDefault();
                        $.ajax({
                            type: "POST",
                            url: "<?= base_url('event/editComment') ?>",
                            data: $('#commentForm').serialize(),
                            success: function(res) {
                            if(res){
                                $('#'+type+'-'+feedId+'-'+commentId).html(res);
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
        }

        if(type == 'holiday'){
            $.ajax({
                url: "<?= base_url('holiday/getComment/') ?>",
                type: "get",
                data:{'commentID':commentId,'holidayID':feedId},
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
                                $('#'+type+'-'+feedId+'-'+commentId).html(res);
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
        }

        if(type == 'activity'){
            $.ajax({
                url: "<?= base_url('activities/getComment/') ?>",
                type: "get",
                data:{'commentID':commentId,'activitiesID':feedId},
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
                                $('#'+type+'-'+feedId+'-'+commentId).html(res);
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
        }

    });

    $(document).ready(function() {
        $(".card--media .read-more a").click(function(e) {
            e.preventDefault();
            $(this).toggleClass("active");
            $(this).parent().siblings('.description').toggleClass('full-text');
        });
    });

    function handleEvent(uniqueId, eventId, isGoing) {
        const eventObject = {
            id: eventId,
            status: isGoing
        }
        $.ajax({
            url: "<?=base_url('event/eventcounter/')?>",
            type: "post",
            data: eventObject,
            success: function (response) {
                response = JSON.parse(response);
                if(isGoing) {
                    $('#going-btn' + uniqueId).hide();
                    $('#disabled-going-btn' + uniqueId).show();
                    $('#not-going-btn' + uniqueId).show();
                    $('#disabled-not-going-btn' + uniqueId).hide();
                } else {
                    $('#not-going-btn' + uniqueId).hide();
                    $('#disabled-not-going-btn' + uniqueId).show();
                    $('#going-btn' + uniqueId).show();
                    $('#disabled-going-btn' + uniqueId).hide();
                }
                const overAllInfo = '<b>' + response.going + '</b> Going - <b>' + response.not_going + '</b> Not Going';
                $('#overall-event-info-' + uniqueId).html(overAllInfo);
                showSuccessToast();
            }
        });
    }
    
    var pageValue = 0;
    var hasData = true;
   
    $('body').scroll(function () {
        if ($('body').scrollTop() + $('body').height() >= $(document).height()) {
            pageValue += 20;
            if(hasData) {
                $.ajax({
                    url: "<?=base_url('feed/getMoreFeedData/')?>" + pageValue,
                    type: "get",
                    success: function (response) {
                        $('#feeds-data').append(response);
                        $(".card--media .read-more a").click(function(e) {
                                e.preventDefault();
                                $(this).toggleClass("active");
                                $(this).parent().siblings('.description').toggleClass('full-text');
                        });
                    },
                    error: function (response) {
                        hasData = false;
                    }
                });
            }
        }
    });
</script>

<script>

    $(document).ready(function(){
   
        user_photo = "<?= imagelink($this->session->userdata('photo'),56) ?>";
        user_name = "<?= $this->session->userdata('name') ?>";

        $('div[id^="galleryNotice"]').each(function(i, e) {

            var assets_url = "<?php echo base_url('uploads/notice/') ?>";
            var jQueryArray = <?php echo json_encode($noticesMedia); ?>;
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

                    // $.get(assets_url+'256/'+value.attachment)
                    // .done(function() { 
                    //     //Do something now you know the image exists.
                    //     image_url.push({
                    //         src: assets_url+value.attachment, 
                    //         thumbnail: assets_url+'256/'+value.attachment, 
                    //         caption:  value.caption 
                    //     });        

                    // }).fail(function() { 
                    //     //alert(assets_url+'256/'+value.attachment);
                    //     //Image doesn't exist - do something else.
                    //     image_url.push({
                    //         src: assets_url+value.attachment, 
                    //         thumbnail: assets_url+value.attachment, 
                    //         caption:  value.caption 
                    //     });   
                    // })
                        
                        });
                        $("#" + $(e).attr('id')).imagesGrid({
                        images: image_url,
                    });
            }
        });

        $('div[id^="galleryEvent"]').each(function(i, e) {

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

        $('div[id^="galleryHoliday"]').each(function(i, e) {

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

        $('div[id^="galleryActivity"]').each(function(i, e) {

            var assets_url = "<?php echo base_url('uploads/activities/') ?>";
            var jQueryArray = <?php echo json_encode($activitiesMedia); ?>;
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
    $(function() {
        comment();
    });

    function comment(){

        user_photo = "<?= imagelink($this->session->userdata('photo'),56) ?>";
        user_name = "<?= $this->session->userdata('name') ?>";

        $(".notice_comment").keypress(function(e) {
            comt = $(this);
            comment = $(this).val();
            feed_id = $(this).data('feed_id');
            now = "<?= getRangeDateString(date_create(date("Y-m-d h:i:s"))->getTimestamp()) ?>";

            var code = (e.keyCode ? e.keyCode : e.which);
            if (code == 13) {
                $.ajax({
                    type: 'POST',
                    url: "<?= base_url('notice/comment') ?>",
                    data: {
                        'activity_id': feed_id,
                        'comment': $(this).val()
                    },
                    dataType: "json",
                    success: function(data) {
                        var type = 'notice';
                        $("#noticecommentbox" + feed_id).append("<div class=\"card-header\"><div class=\"media-block\"><div class=\"avatar\"><img src=\"" + user_photo + "\" class=\"avatar-img\" alt=\"\"/></div><div class=\"media-block-body\"><ul class=\"list-inline list-inline--social-meta\"><li><h4><b>" + user_name + "</b></h4></li><li class=\"date-list\"><span class=\"date\">" + now + "</span></li></ul></div><a href=\"javascript:void(0)\" data-toggle=\"modal\" data-target=\"#commentModal\" data-comment-id=" + data + "  data-feed-id=" + feed_id + " data-type=\"notice\"><i class=\"fa fa-edit\"></i></a><a href=\"javascript:void(0)\" data-comment-id=" + data + "  data-feed_id=" + feed_id + " class=\"icon-round collapsed icon-round__trash\" role=\"button\"><i class=\"fa fa-trash\"></i></a></div><div class=\"card-title-wrapper\"><p id="+type+'-'+feed_id+'-'+data+">" + comment + "</p></div></div>");
                        comt.val('');
                        comment_count = $(".noticecomment_count_" + feed_id).html();
                        if (comment_count != 0) {
                            $(".noticecomment_count_" + feed_id).html(parseInt(comment_count) + 1);
                        } else {
                           
                            $(".noticecomment_" + feed_id).show();
                            $(".noticecomment_count_" + feed_id).html(parseInt(comment_count) + 1);
                        }
                        $("#noticecommentbox" + feed_id).removeClass('collapse');
                        $("#noticecommentbox" + feed_id).addClass('in');
                        $("#noticecommentbox" + feed_id).css("height: auto");
                    }
                });
            }
        });

        $(".event_comment").keypress(function(e) {
            comt = $(this);
            comment = $(this).val();
            feed_id = $(this).data('feed_id');
            now = "<?= getRangeDateString(date_create(date("Y-m-d h:i:s"))->getTimestamp()) ?>";

            var code = (e.keyCode ? e.keyCode : e.which);
            if (code == 13) {
                $.ajax({
                    type: 'POST',
                    url: "<?= base_url('event/comment') ?>",
                    data: {
                        'activity_id': feed_id,
                        'comment': $(this).val()
                    },
                    dataType: "json",
                    success: function(data) {
                        var type = 'event';
                        $("#eventcommentbox" + feed_id).append("<div class=\"card-header\"><div class=\"media-block\"><div class=\"avatar\"><img src=\"" + user_photo + "\" class=\"avatar-img\" alt=\"\"/></div><div class=\"media-block-body\"><ul class=\"list-inline list-inline--social-meta\"><li><h4><b>" + user_name + "</b></h4></li><li class=\"date-list\"><span class=\"date\">" + now + "</span></li></ul></div><a href=\"javascript:void(0)\" data-toggle=\"modal\" data-target=\"#commentModal\" data-comment-id=" + data + "  data-feed-id=" + feed_id + " data-type=\"event\"><i class=\"fa fa-edit\"></i></a><a href=\"javascript:void(0)\" data-comment-id=" + data + "  data-feed_id=" + feed_id + " class=\"icon-round collapsed icon-round__trash\" role=\"button\"><i class=\"fa fa-trash\"></i></a></div><div class=\"card-title-wrapper\"><p id="+type+'-'+feed_id+'-'+data+">" + comment + "</p></div></div>");
                        comt.val('');
                        comment_count = $(".eventcomment_count_" + feed_id).html();
                        if (comment_count != 0) {
                            $(".eventcomment_count_" + feed_id).html(parseInt(comment_count) + 1);
                        } else {
                           
                            $(".eventcomment_" + feed_id).show();
                            $(".eventcomment_count_" + feed_id).html(parseInt(comment_count) + 1);
                        }
                        $("#eventcommentbox" + feed_id).removeClass('collapse');
                        $("#eventcommentbox" + feed_id).addClass('in');
                        $("#eventcommentbox" + feed_id).css("height: auto");
                    }
                });
            }
        });

        $(".holiday_comment").keypress(function(e) {
            comt = $(this);
            comment = $(this).val();
            feed_id = $(this).data('feed_id');
            now = "<?= getRangeDateString(date_create(date("Y-m-d h:i:s"))->getTimestamp()) ?>";

            var code = (e.keyCode ? e.keyCode : e.which);
            if (code == 13) {
                $.ajax({
                    type: 'POST',
                    url: "<?= base_url('holiday/comment') ?>",
                    data: {
                        'activity_id': feed_id,
                        'comment': $(this).val()
                    },
                    dataType: "json",
                    success: function(data) {
                        var type = 'holiday';
                        $("#holidaycommentbox" + feed_id).append("<div class=\"card-header\"><div class=\"media-block\"><div class=\"avatar\"><img src=\"" + user_photo + "\" class=\"avatar-img\" alt=\"\"/></div><div class=\"media-block-body\"><ul class=\"list-inline list-inline--social-meta\"><li><h4><b>" + user_name + "</b></h4></li><li class=\"date-list\"><span class=\"date\">" + now + "</span></li></ul></div><a href=\"javascript:void(0)\" data-toggle=\"modal\" data-target=\"#commentModal\" data-comment-id=" + data + "  data-feed-id=" + feed_id + " data-type=\"holiday\"><i class=\"fa fa-edit\"></i></a><a href=\"javascript:void(0)\" data-comment-id=" + data + "  data-feed_id=" + feed_id + " class=\"icon-round collapsed icon-round__trash\" role=\"button\"><i class=\"fa fa-trash\"></i></a></div><div class=\"card-title-wrapper\"><p id="+type+'-'+feed_id+'-'+data+">" + comment + "</p></div></div>");
                        comt.val('');
                        comment_count = $(".holidaycomment_count_" + feed_id).html();
                        if (comment_count != 0) {
                            $(".holidaycomment_count_" + feed_id).html(parseInt(comment_count) + 1);
                        } else {
                            $(".holidaycomment_" + feed_id).show();
                            $(".holidaycomment_count_" + feed_id).html(parseInt(comment_count) + 1);
                        }
                        $("#holidaycommentbox" + feed_id).removeClass('collapse');
                        $("#holidaycommentbox" + feed_id).addClass('in');
                        $("#holidaycommentbox" + feed_id).css("height: auto");
                    }
                });
            }
        });

        $(".activity_comment").keypress(function(e) {
            comt = $(this);
            comment = $(this).val();
            feed_id = $(this).data('feed_id');
            now = "<?= getRangeDateString(date_create(date("Y-m-d h:i:s"))->getTimestamp()) ?>";

            var code = (e.keyCode ? e.keyCode : e.which);
            if (code == 13) {
                $.ajax({
                    type: 'POST',
                    url: "<?= base_url('activities/comment') ?>",
                    data: {
                        'activity_id': feed_id,
                        'comment': $(this).val()
                    },
                    dataType: "json",
                    success: function(data) {
                        var type = 'activity';
                        $("#activitycommentbox" + feed_id).append("<div class=\"card-header\"><div class=\"media-block\"><div class=\"avatar\"><img src=\"" + user_photo + "\" class=\"avatar-img\" alt=\"\"/></div><div class=\"media-block-body\"><ul class=\"list-inline list-inline--social-meta\"><li><h4><b>" + user_name + "</b></h4></li><li class=\"date-list\"><span class=\"date\">" + now + "</span></li></ul></div><a href=\"javascript:void(0)\" data-toggle=\"modal\" data-target=\"#commentModal\" data-comment-id=" + data + "  data-feed-id=" + feed_id + " data-type=\"activity\"><i class=\"fa fa-edit\"></i></a><a href=\"javascript:void(0)\" data-comment-id=" + data + "  data-feed_id=" + feed_id + " class=\"icon-round collapsed icon-round__trash\" role=\"button\"><i class=\"fa fa-trash\"></i></a></div><div class=\"card-title-wrapper\"><p id="+type+'-'+feed_id+'-'+data+">" + comment + "</p></div></div>");
                        comt.val('');
                        comment_count = $(".activitycomment_count_" + feed_id).html();
                        if (comment_count != 0) {
                            $(".activitycomment_count_" + feed_id).html(parseInt(comment_count) + 1);
                        } else {
                            $(".activitycomment_" + feed_id).show();
                            $(".activitycomment_count_" + feed_id).html(parseInt(comment_count) + 1);
                        }
                        $("#activitycommentbox" + feed_id).removeClass('collapse');
                        $("#activitycommentbox" + feed_id).addClass('in');
                        $("#activitycommentbox" + feed_id).css("height: auto");
                    }
                });
            }
        });


        $(".noticecommentbox").on('click', '.icon-round', function(e) {
            e.preventDefault();

            var classes = $(this).parent().parent('div');
            comment_id = $(this).data('comment-id');
            feed_id = $(this).data('feed_id');
            $.ajax({
                type: 'POST',
                url: "<?= base_url('notice/delete_comment'); ?>",
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

                    comment_count = $(".noticecomment_count_" + feed_id).html();
                    if (comment_count != 0) {
                        $(".noticecomment_count_" + feed_id).html(parseInt(comment_count) - 1);
                    } else {
                        $(".noticecomment_" + feed_id).show();
                        $(".noticecomment_count_" + feed_id).html(parseInt(comment_count) - 1);
                    }
                    $("#noticecommentbox" + feed_id).removeClass('collapse');
                    $("#noticecommentbox" + feed_id).addClass('in');
                    $("#noticecommentbox" + feed_id).css("height: auto");
                }

            });

        });

        $(".eventcommentbox").on('click', '.icon-round', function(e) {
            e.preventDefault();

            var classes = $(this).parent().parent('div');
            comment_id = $(this).data('comment-id');
            feed_id = $(this).data('feed_id');
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

                    comment_count = $(".eventcomment_count_" + feed_id).html();
                    if (comment_count != 0) {
                        $(".eventcomment_count_" + feed_id).html(parseInt(comment_count) - 1);
                    } else {
                        $(".eventcomment_" + feed_id).show();
                        $(".eventcomment_count_" + feed_id).html(parseInt(comment_count) - 1);
                    }
                    $("#eventcommentbox" + feed_id).removeClass('collapse');
                    $("#eventcommentbox" + feed_id).addClass('in');
                    $("#eventcommentbox" + feed_id).css("height: auto");
                }

            });

        });

        $(".holidaycommentbox").on('click', '.icon-round', function(e) {
            e.preventDefault();

            var classes = $(this).parent().parent('div');
            comment_id = $(this).data('comment-id');
            feed_id = $(this).data('feed_id');
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

                    comment_count = $(".holidaycomment_count_" + feed_id).html();
                    if (comment_count != 0) {
                        $(".holidaycomment_count_" + feed_id).html(parseInt(comment_count) - 1);
                    } else {
                        $(".holidaycomment_" + feed_id).show();
                        $(".holidaycomment_count_" + feed_id).html(parseInt(comment_count) - 1);
                    }
                    $("#holidaycommentbox" + feed_id).removeClass('collapse');
                    $("#holidaycommentbox" + feed_id).addClass('in');
                    $("#holidaycommentbox" + feed_id).css("height: auto");
                }

            });

        });

        $(".activitycommentbox").on('click', '.icon-round', function(e) {
            e.preventDefault();

            var classes = $(this).parent().parent('div');
            comment_id = $(this).data('comment-id');
            feed_id = $(this).data('feed_id');
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

                    comment_count = $(".activitycomment_count_" + feed_id).html();
                    if (comment_count != 0) {
                        $(".activitycomment_count_" + feed_id).html(parseInt(comment_count) - 1);
                    } else {
                        $(".activitycomment_" + feed_id).show();
                        $(".activitycomment_count_" + feed_id).html(parseInt(comment_count) - 1);
                    }
                    $("#activitycommentbox" + feed_id).removeClass('collapse');
                    $("#activitycommentbox" + feed_id).addClass('in');
                    $("#activitycommentbox" + feed_id).css("height: auto");
                }

            });

        });
}
</script>