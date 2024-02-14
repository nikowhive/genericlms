<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa iniicon-ebook"></i> <?=$this->lang->line('panel_title')?></h3>
        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li><a href="<?=base_url("book")?>"></i><?=$this->lang->line('menu_book')?></a></li>
            <li class="active"><?=$this->lang->line('menu_view')?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-12">
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <li class="active" style="display: list-item;"><a href="#detail" data-toggle="tab">Detail</a></li>
                    </ul>
                    <div class="tab-content">
                        <div class="active tab-pane" id="detail" style="display: list-item;">
                            <div class="panel-body profile-view-dis">
                                <div class="row">
                                    <div class="profile-view-tab">
                                        <p><span>Book </span>: 
                                        <?php echo  $book->book; ?></p>
                                    </div>
                                    <div class="profile-view-tab">
                                        <p><span>Author </span>: <?php echo  $book->author; ?></p>
                                    </div>
                                    <div class="profile-view-tab">
                                        <p><span>Subject Code </span>: <?php echo  $book->subject_code; ?></p>
                                    </div>
                                    <div class="profile-view-tab">
                                        <p><span>Price </span>: <?php echo  $book->price; ?></p>
                                    </div>
                                    <div class="profile-view-tab">
                                        <p><span>Quantity </span>: <?php echo  $book->quantity; ?></p>
                                    </div>
                                    <div class="profile-view-tab">
                                        <p><span>Rack No. </span>: <?php echo  $book->rack; ?></p>
                                    </div>
                                    <div class="profile-view-tab">
                                        <p><span>ISBN </span>: <?php echo  $book->isbn; ?></p>
                                    </div>
                                    <div class="profile-view-tab">
                                        <p><span>Call </span>: <?php echo  $book->call; ?></p>
                                    </div>
                                    <?php if($keywords){ ?>
                                        <div class="profile-view-tab">
                                            <p><span>Keywords </span>: <?php echo  $keywords; ?></p>
                                        </div>
                                    <?php } ?>
                                 
                                </div>
                                <?php 
                                if($enable == 1){
                                if($additional_fields){ ?>
                               
                                    <div class="row">

                                        <div class="profile-view-tab">
                                            <p><span>Publisher </span>: 
                                            <?php echo  $additional_fields->publisher; ?></p>
                                        </div>
                                        <div class="profile-view-tab">
                                            <p><span>Published Year </span>: <?php echo  $additional_fields->published_year; ?></p>
                                        </div>
                                        <div class="profile-view-tab">
                                            <p><span>Place of Publication </span>: <?php echo  $additional_fields->place_of_publication; ?></p>
                                        </div>
                                        <div class="profile-view-tab">
                                            <p><span>Pages </span>: <?php echo  $additional_fields->pages; ?></p>
                                        </div>
                                        <div class="profile-view-tab">
                                            <p><span>Edition </span>: <?php echo  $additional_fields->edition; ?></p>
                                        </div>
                                        <div class="profile-view-tab">
                                            <p><span>Second Author </span>: <?php echo  $additional_fields->second_author; ?></p>
                                        </div>
                                        <div class="profile-view-tab">
                                            <p><span>Third Author </span>: <?php echo  $additional_fields->third_author; ?></p>
                                        </div>
                                        <div class="profile-view-tab">
                                            <p><span>Language </span>: <?php echo  $additional_fields->language; ?></p>
                                        </div>
                                       <div class="profile-view-tab">
                                            <p><span>Source </span>: <?php echo  $additional_fields->source; ?></p>
                                        </div>
                                        <div class="profile-view-tab">
                                            <p><span>Form </span>: <?php echo  $additional_fields->form; ?></p>
                                        </div>
                                        <?php if($additional_fields->book_photo){ ?>
                                            <div class="profile-view-tab">
                                            <p><span>Book Photo </span>: <img width="25%" src=" <?=base_url('uploads/books/'.$additional_fields->book_photo)?>"/></p>
                                              </div>
                                           
                                       <?php } ?>
                                       <?php if($additional_fields->barcode){ ?>
                                            <div class="profile-view-tab">
                                            <p><span>Bar code </span>: <img width="25%" src=" <?=base_url('uploads/books/'.$additional_fields->barcode)?>"/></p>
                                              </div>
                                           
                                       <?php } ?>
                                    
                                    
                                    </div>
                                <?php }} ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- row -->
    </div><!-- Body -->
</div><!-- /.box -->

