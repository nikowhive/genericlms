
<div class="container container--sm">

<header class="pg-header mt-4">
        <div>
            
            <h1 class="pg-title">
            
                <?=$this->lang->line('panel_title')?>
                </h1>
                <ol class="breadcrumb">
                <li><a href="<?=base_url("dashboard/index")?>"> <?=$this->lang->line('menu_dashboard')?></a></li>
                <li><a href="<?=base_url("markpercentage/index")?>"></i> <?=$this->lang->line('menu_markpercentage')?></a></li>
                <li class="active"><?=$this->lang->line('menu_add')?> <?=$this->lang->line('menu_markpercentage')?></li>
            </ol>
        </div>
</header>
    <div class="card card--spaced">
  
        <!-- form start -->
        <div class="card-body">
    
                    <form class="" role="form" method="post">
    
                        <?php
                            if(form_error('markpercentagetype'))
                                echo "<div class='form-group has-error' >";
                            else
                                echo "<div class='form-group' >";
                        ?>
                            <div class="md-form">
                                <label for="markpercentagetype" class=" control-label">
                                    <?=$this->lang->line("markpercentage_markpercentagetype")?> <span class="text-red">*</span>
                                </label>
                              
                                    <input type="text" class="form-control" id="markpercentagetype" name="markpercentagetype" value="<?=set_value('markpercentagetype')?>" >
                                 
                                <span class="text-danger error">
                                    <?php echo form_error('markpercentagetype'); ?>
                                </span>
                            </div>
                        </div>
    
                        <?php
                            if(form_error('percentage'))
                                echo "<div class='form-group has-error' >";
                            else
                                echo "<div class='form-group' >";
                        ?>
                            <div class="md-form">
                                <label for="percentage" class=" control-label">
                                    <?=$this->lang->line("markpercentage_percentage")?> <span class="text-red">*</span>
                                </label>
                                
                                    <input type="text" class="form-control" id="percentage" name="percentage" value="<?=set_value('percentage')?>" >
                                
                                <span class="text-danger error">
                                    <?php echo form_error('percentage'); ?>
                                </span>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-success"><?=$this->lang->line("add_markpercentage")?></button>
                         
    
                    </form>
    
                    <?php if ($siteinfos->note==1) { ?>
                        <div class="callout callout-danger mt-4">
                            <p><b>Note:</b> After adding a new mark percentage select this percentage in general settings to activate this new percentage on marking.</p>
                        </div>
                    <?php } ?>
    
    
               
        </div>
    </div>
</div>
