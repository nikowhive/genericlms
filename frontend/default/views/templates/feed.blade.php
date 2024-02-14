

<section id="events" class="events-area area-padding">
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <div class="section-title text-uppercase text-center">
                    <h2><a href="#">What's ON</a></h2>
                </div>
            </div>
        </div>

        <div class="row row-flex">
            <div class="col-xs-12">
                <div class="container container--sm" id="feeds-data">
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
                                            <img src="<?= base_url('uploads/images/' . $feed->user_image) ?>" class="avatar-img" alt=""/>
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
                                }else{
                                    $photourl = base_url("uploads/images/" . $photo);
                                }
                                ?>
                                <figure class="card-img card-img-auto">
                                    <?php if($photourl != ""){ ?>
                                        <img src="<?php echo $photourl ?>" class="img-absolute-full myImg" alt="" />
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
                               
                                <!-- comment end -->
                            </div>
                        <?php } else { ?>
                            <div class="card p-0 card--media">
                                <div class="card-header">
                                    <div class="media-block">
                                        <div class="avatar">
                                            <img src="<?= base_url('uploads/images/' . $feed->user_image) ?>" class="avatar-img" alt=""/>
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
                                ?>
                                <figure class="card-img card-img-auto">
                                    <img src="<?php echo $photourl ?>" class="img-absolute-full myImg" alt="" />
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
                                ?>
                                    <figure class="card-img card-img-auto">
                                        <img src="<?= $photourl ?>" alt="" class="myImg">
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
                                    }else{
                                        $photourl = base_url("uploads/images/" . $photo);
                                    }
                            ?>
                            <figure class="card-img card-img-auto">
                                <img src="<?php echo $photourl ?>" class="img-absolute-full myImg" alt="" />
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
                           
                        <!-- comment end -->
                    </div>
                        <?php }
                    } ?>


                </div>
            </div>

        </div>
       

    </div>
</section>

@section('footerAssetPush')
<script type="text/javascript" src="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
<script type="text/javascript">
    $(document).on('click', '.going-event', function(e) {
        e.preventDefault();
        var id = $(this).attr('id');
        if (id) {
            $.ajax({
                dataType: 'json',
                type: 'POST',
                url: "<?= base_url('frontend/eventGoing') ?>",
                data: {
                    'id': id
                },
                dataType: "html",
                success: function(data) {
                    var response = jQuery.parseJSON(data);
                    if (response.status == true) {
                        toastr["success"](response.message)

                    } else {
                        toastr["error"](response.message)
                    }
                }
            });
        }
    });
</script>

<script>
    $(document).ready(function() {
        var totalImages = "{{ count($popupImages) }}";
        for ($i = 0; $i < totalImages; $i++) {
            $("#popupImageModal" + $i).modal('show');
        }

    });
</script>



<script type="text/javascript">
    

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
            url: "<?= base_url('event/eventcounter/') ?>",
            type: "post",
            data: eventObject,
            success: function(response) {
                response = JSON.parse(response);
                if (isGoing) {
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

    $(document).ready(function() {

        user_photo = "<?= imagelink($this->session->userdata('photo')) ?>";
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
                    image_url.push({
                        src: assets_url + value.attachment,
                        caption: value.caption
                    });

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

                    image_url.push({
                        src: assets_url + value.attachment,
                        caption: value.caption
                    });

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
                    image_url.push({
                        src: assets_url + value.attachment,
                        caption: value.caption
                    });

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
                    image_url.push({
                        src: assets_url + value.attachment,
                        caption: value.caption
                    });

                });
                $("#" + $(e).attr('id')).imagesGrid({
                    images: image_url,
                });
            }
        });
    });

</script>

@endsection

