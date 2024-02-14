<?php if (customCompute($comments)) { ?>
    <?php foreach ($comments as $comment) { ?>
        <div class="card-header">
            <div class="media-block">
                <div class="avatar">
                    <img src="<?= imagelink($comment->photo,56) ?>" class="avatar-img" alt="" />
                </div>
                                                    <div class="media-block-body">
                                                        <ul class="list-inline list-inline--social-meta">
                                                            <li>
                                                                <h4><b><?= $comment->name ?></b></h4>
                                                            </li>
                                                            <li class="date-list"><span class="date"><?= getRangeDateString(date_create($comment->create_date)->getTimestamp()) ?></span></li>
                                                        </ul>
                                                    </div>
                                                    <?php if(($this->session->userdata('loginuserID') == $comment->userID && $this->session->userdata('usertypeID') == $comment->usertypeID) || ($this->session->userdata('loginuserID') == 1 && $this->session->userdata('usertypeID') == 1)) : ?>
                                                        <a href="javascript:void(0)" class="icon-round__trash" data-toggle="modal" data-target="#commentModal" data-comment-id="<?= $comment->activitiescommentID; ?>" data-activities-id="<?= $comment->activitiesID ?>">
                                                            <i class="fa fa-edit"></i>
                                                        </a>
                                                        <a href="javascript:void(0)" class="icon-round collapsed icon-round__trash" role="button" data-comment-id="<?= $comment->activitiescommentID; ?>" data-activity_id="<?= $comment->activitiesID ?>">
                                                            <i class="fa fa-trash"></i>
                                                        </a>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="card-title-wrapper">
                                                    <p id="<?= $comment->activitiesID ?>-<?= $comment->activitiescommentID; ?>">
                                                        <?= $comment->comment ?>
                                                    </p>
                                                </div>
                                            </div>
                                <?php 
                                    }
                                } ?>