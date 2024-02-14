
<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa icon-lbooks"></i> <?=$this->lang->line('panel_title')?></h3>

       
        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li><a href="<?=base_url("book/index")?>"><?=$this->lang->line('menu_books')?></a></li>
            <li class="active"><?=$this->lang->line('menu_add')?> <?=$this->lang->line('menu_books')?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-10">
                 <form class="form-horizontal" role="form" method="post" enctype="multipart/form-data">
                   <?php 
                        if(form_error('book')) 
                            echo "<div class='form-group has-error' >";
                        else     
                            echo "<div class='form-group' >";
                    ?>
                        <label for="book" class="col-sm-2 control-label">
                            <?=$this->lang->line("book_name")?> <span class="text-red">*</span>
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="book" name="book" value="<?=set_value('book')?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('book'); ?>
                        </span>
                    </div>

                    <?php 
                        if(form_error('author')) 
                            echo "<div class='form-group has-error' >";
                        else     
                            echo "<div class='form-group' >";
                    ?>
                        <label for="author" class="col-sm-2 control-label">
                            <?=$this->lang->line("book_author")?> 
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="author" name="author" value="<?=set_value('author')?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('author'); ?>
                        </span>
                    </div>

                    <?php 
                        if(form_error('subject_code')) 
                            echo "<div class='form-group has-error' >";
                        else     
                            echo "<div class='form-group' >";
                    ?>
                        <label for="subject_code" class="col-sm-2 control-label">
                            <?=$this->lang->line("book_subject_code")?> <span class="text-red">*</span>
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="subject_code" name="subject_code" value="<?=set_value('subject_code')?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('subject_code'); ?>
                        </span>
                    </div>

                   

                    <?php 
                        if(form_error('price')) 
                            echo "<div class='form-group has-error' >";
                        else     
                            echo "<div class='form-group' >";
                    ?>
                        <label for="price" class="col-sm-2 control-label">
                            <?=$this->lang->line("book_price")?> 
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="price" name="price" value="<?=set_value('price')?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('price'); ?>
                        </span>
                    </div>

                    <?php 
                        if(form_error('quantity')) 
                            echo "<div class='form-group has-error' >";
                        else     
                            echo "<div class='form-group' >";
                    ?>
                        <label for="quantity" class="col-sm-2 control-label">
                            <?=$this->lang->line("book_quantity")?> 
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="quantity" name="quantity" value="<?=set_value('quantity')?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('quantity'); ?>
                        </span>
                    </div>

                    <?php 
                        if(form_error('rack')) 
                            echo "<div class='form-group has-error' >";
                        else     
                            echo "<div class='form-group' >";
                    ?>
                        <label for="rack" class="col-sm-2 control-label">
                            <?=$this->lang->line("book_rack_no")?> 
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="rack" name="rack" value="<?=set_value('rack')?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('rack'); ?>
                        </span>
                    </div>
                    
                    <?php 
                        if(form_error('isbn')) 
                            echo "<div class='form-group has-error' >";
                        else     
                            echo "<div class='form-group' >";
                    ?>
                        <label for="isbn" class="col-sm-2 control-label">
                            <?=$this->lang->line("book_isbn")?>
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="isbn" name="isbn" value="<?=set_value('isbn')?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('isbn'); ?>
                        </span>
                    </div>

                    <?php 
                        if(form_error('call')) 
                            echo "<div class='form-group has-error' >";
                        else     
                            echo "<div class='form-group' >";
                     ?>
                        <label for="call" class="col-sm-2 control-label">
                            <?=$this->lang->line("book_call")?> <span class="text-red">*</span>
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="call" name="call" value="<?=set_value('call')?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('call'); ?>
                        </span>
                    </div>

                    <?php 
                        if(form_error('keyword')) 
                            echo "<div class='form-group has-error' >";
                        else     
                            echo "<div class='form-group' >";
                     ?>
                        <label for="keywords" class="col-sm-2 control-label">
                            <?=$this->lang->line("book_keyword")?>
                        </label>
                        <div class="col-sm-6">
                            <input type="text" data-role="tagsinput" class="form-control" id="keyword" name="keyword" value="<?=set_value('keyword')?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('keyword'); ?>
                        </span>
                    </div>

                    <?php 
                                if(form_error('publisher')) 
                                    echo "<div class='form-group has-error' >";
                                else     
                                    echo "<div class='form-group' >";
                            ?>
                                <label for="call" class="col-sm-2 control-label">
                                    <?=$this->lang->line("book_publisher")?> <span class="text-red">*</span>
                                </label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" id="publisher" name="publisher" value="<?=set_value('publisher')?>" >
                                </div>
                                <span class="col-sm-4 control-label">
                                    <?php echo form_error('publisher'); ?>
                                </span>
                            </div>

                    <div class='form-group' >
                    <label for="addtional_field" class="col-sm-2 control-label">
                            <?=$this->lang->line("book_is_additional_detail")?>
                        </label>
                        <div class="col-sm-6">
                            <input type="checkbox" id="addtional_field" name="addtional_field" value="<?=set_value('addtional_field',1)?>" >
                        </div>
                    </div>

                    <div class="additionalWrapper">
                           
                            <?php 
                                if(form_error('published_year')) 
                                    echo "<div class='form-group has-error' >";
                                else     
                                    echo "<div class='form-group' >";
                            ?>
                                <label for="call" class="col-sm-2 control-label">
                                    <?=$this->lang->line("book_published_year")?>
                                </label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" id="published_year" name="published_year" value="<?=set_value('published_year')?>" >
                                </div>
                                <span class="col-sm-4 control-label">
                                    <?php echo form_error('published_year'); ?>
                                </span>
                            </div>
                            <?php 
                                if(form_error('place_of_publication')) 
                                    echo "<div class='form-group has-error' >";
                                else     
                                    echo "<div class='form-group' >";
                            ?>
                                <label for="call" class="col-sm-2 control-label">
                                    <?=$this->lang->line("book_place_of_publication")?>
                                </label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" id="place_of_publication" name="place_of_publication" value="<?=set_value('place_of_publication')?>" >
                                </div>
                                <span class="col-sm-4 control-label">
                                    <?php echo form_error('place_of_publication'); ?>
                                </span>
                            </div>
                            <?php 
                                if(form_error('pages')) 
                                    echo "<div class='form-group has-error' >";
                                else     
                                    echo "<div class='form-group' >";
                            ?>
                                <label for="call" class="col-sm-2 control-label">
                                    <?=$this->lang->line("book_pages")?>
                                </label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" id="pages" name="pages" value="<?=set_value('pages')?>" >
                                </div>
                                <span class="col-sm-4 control-label">
                                    <?php echo form_error('pages'); ?>
                                </span>
                            </div>
                            <?php 
                                if(form_error('edition')) 
                                    echo "<div class='form-group has-error' >";
                                else     
                                    echo "<div class='form-group' >";
                            ?>
                                <label for="call" class="col-sm-2 control-label">
                                    <?=$this->lang->line("book_edition")?>
                                </label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" id="edition" name="edition" value="<?=set_value('edition')?>" >
                                </div>
                                <span class="col-sm-4 control-label">
                                    <?php echo form_error('edition'); ?>
                                </span>
                            </div>
                            <?php 
                                if(form_error('second_author')) 
                                    echo "<div class='form-group has-error' >";
                                else     
                                    echo "<div class='form-group' >";
                            ?>
                                <label for="call" class="col-sm-2 control-label">
                                    <?=$this->lang->line("book_second_author")?>
                                </label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" id="second_author" name="second_author" value="<?=set_value('second_author')?>" >
                                </div>
                                <span class="col-sm-4 control-label">
                                    <?php echo form_error('second_author'); ?>
                                </span>
                            </div>
                            <?php 
                                if(form_error('third_author')) 
                                    echo "<div class='form-group has-error' >";
                                else     
                                    echo "<div class='form-group' >";
                            ?>
                                <label for="call" class="col-sm-2 control-label">
                                    <?=$this->lang->line("book_third_author")?>
                                </label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" id="third_author" name="third_author" value="<?=set_value('third_author')?>" >
                                </div>
                                <span class="col-sm-4 control-label">
                                    <?php echo form_error('third_author'); ?>
                                </span>
                            </div>
                            <?php 
                                if(form_error('language')) 
                                    echo "<div class='form-group has-error' >";
                                else     
                                    echo "<div class='form-group' >";
                            ?>
                                <label for="call" class="col-sm-2 control-label">
                                    <?=$this->lang->line("book_language")?>
                                </label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" id="language" name="language" value="<?=set_value('language')?>" >
                                </div>
                                <span class="col-sm-4 control-label">
                                    <?php echo form_error('language'); ?>
                                </span>
                            </div>

                            <?php 
                                if(form_error('volume')) 
                                    echo "<div class='form-group has-error' >";
                                else     
                                    echo "<div class='form-group' >";
                            ?>
                                <label for="call" class="col-sm-2 control-label">
                                    <?=$this->lang->line("book_volume")?>
                                </label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" id="volume" name="volume" value="<?=set_value('volume')?>" >
                                </div>
                                <span class="col-sm-4 control-label">
                                    <?php echo form_error('volume'); ?>
                                </span>
                            </div>

                            <?php 
                                if(form_error('source')) 
                                    echo "<div class='form-group has-error' >";
                                else     
                                    echo "<div class='form-group' >";
                            ?>
                                <label for="call" class="col-sm-2 control-label">
                                    <?=$this->lang->line("book_source")?>
                                </label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" id="source" name="source" value="<?=set_value('source')?>" >
                                </div>
                                <span class="col-sm-4 control-label">
                                    <?php echo form_error('source'); ?>
                                </span>
                            </div>

                            <?php 
                                if(form_error('form')) 
                                    echo "<div class='form-group has-error' >";
                                else     
                                    echo "<div class='form-group' >";
                            ?>
                                <label for="call" class="col-sm-2 control-label">
                                    <?=$this->lang->line("book_form")?>
                                </label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" id="form" name="form" value="<?=set_value('form')?>" >
                                </div>
                                <span class="col-sm-4 control-label">
                                    <?php echo form_error('form'); ?>
                                </span>
                            </div>

                            <?php 
                                if(form_error('barcode')) 
                                    echo "<div class='form-group has-error' >";
                                else     
                                    echo "<div class='form-group' >";
                            ?>
                                <label for="call" class="col-sm-2 control-label">
                                    <?=$this->lang->line("book_barcode")?>
                                </label>
                                <div class="col-sm-6">
                                <input type="file" class="form-control" id="barcode" name="barcode"/>
                                    <!-- <input type="text" class="form-control" id="barcode" name="barcode" value="<?=set_value('barcode')?>" > -->
                                </div>
                                <span class="col-sm-4 control-label">
                                    <?php echo form_error('barcode'); ?>
                                </span>
                            </div>

                            <?php
                        if(form_error('book_photo'))
                            echo "<div class='form-group has-error' >";
                        else
                            echo "<div class='form-group' >";
                    ?>
                        <label for="book_photo" class="col-sm-2 control-label">
                            <?=$this->lang->line("book_photo")?> 
                        </label>
                        <div class="col-sm-6">
                            <input type="file" class="form-control" id="book_photo" name="book_photo"/>
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('book_photo'); ?>
                        </span>
                    </div>

                    </div>


                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-8">
                            <input type="submit" class="btn btn-success" value="<?=$this->lang->line("add_book")?>" >
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

<link href="<?php echo base_url('assets/tags/bootstrap-tagsinput.css'); ?>" rel="stylesheet">
<script type="text/javascript" src="<?php echo base_url('assets/tags/bootstrap-tagsinput.js'); ?>"></script>

<script>

  $('#addtional_field').change(function(){
      if($(this).prop('checked') == true){
          $('.additionalWrapper').show();
      }else{
        $('.additionalWrapper').hide();
      } 
  });
  $('#addtional_field').trigger('change');
</script>
