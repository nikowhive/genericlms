<div class="row row-flex">
    <div class="col-md-9">
        <div class="container container--sm">
            <header class="pg-header">
                <h1 class="pg-title"><?= $feeds ? $this->lang->line('panel_title') : $this->lang->line('no_notes') ?></h1>
            </header>
            <?php
            // if (permissionChecker('note_add')) { 

                ?>
                <div>
                    <h5 class="page-header">
                        <a style="float: right;" href="<?php echo base_url('note/add') ?>">
                            <i class="fa fa-plus"></i>
                            <?= $this->lang->line('add_title') ?>
                        </a>
                    </h5>
                </div>
            <?php //} ?>
            <?php foreach ($feeds as $feed) { ?>
                <div class="card p-0 card--media">
                    <div class="card-header">
                        <div class="media-block">
                            
                            <?php if (permissionChecker('note_edit') || permissionChecker('note_delete')) { ?>
                                <div class="dropdown">
                                    <a href="#" class=" " data-toggle="dropdown"> ⋮</a>
                                    <ul id="menu2" class="dropdown-menu" aria-labelledby="drop5">
                                        <?php if (permissionChecker('note_edit')) { ?>
                                            <li>
                                                <a href="<?= base_url('note/edit/' . $feed->noteID) ?>"><i
                                                            class="fa fa-pencil"></i> Edit</a>
                                            </li>
                                        <?php } ?>
                                        <?php if (permissionChecker('note_delete')) { ?>
                                            <li>
                                                <a href="<?= base_url('note/delete/' . $feed->noteID) ?>"
                                                   onclick="return confirm('Are you sure you want to delete this note?');"><i class="fa fa-trash"></i> Delete</a>
                                            </li>
                                        <?php } ?>
                                    </ul>
                                </div>
                            <?php } ?>
                        </div>
                       
                    </div>
                    <div class="card-body">
                        <p>
                            <?= $feed->note ?>
                        </p>
                    </div>
                </div>
            <?php } ?>
            <?=$links?>
        </div>
    </div>
    <div class="col-auto"></div>
</div>