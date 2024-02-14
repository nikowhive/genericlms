
<div class="container container--sm">

<header class="pg-header mt-4">
        <div>
            
            <h1 class="pg-title">
            
                <?=$this->lang->line('panel_title')?>
                </h1>
                <ol class="breadcrumb">
                <li><a href="<?=base_url("dashboard/index")?>"> <?=$this->lang->line('menu_dashboard')?></a></li>
                <li><a href="<?=base_url("grade/index")?>"><?=$this->lang->line('menu_grade')?></a></li>
                <li class="active"><?=$this->lang->line('menu_add')?> <?=$this->lang->line('menu_grade')?></li>
            </ol>
        </div>
</header>
    <div class="card card--spaced">
 
        <div class="card-body">
 
                    <form class=" " role="form" method="post">
    
                        <?php
                            if(form_error('grade'))
                                echo "<div class='form-group has-error' >";
                            else
                                echo "<div class='form-group' >";
                        ?>
                            <div class="md-form">
                            <label for="grade" class="control-label">
                                <?=$this->lang->line("grade_name")?> <span class="text-red">*</span>
                            </label>
                                <input type="text" class="form-control" id="grade" name="grade" value="<?=set_value('grade')?>" >
                            <span class="text-danger error">
                                <?php echo form_error('grade'); ?>
                            </span>
                            </div>
                        </div>
    
                        <?php
                            if(form_error('point'))
                                echo "<div class='form-group has-error' >";
                            else
                                echo "<div class='form-group' >";
                        ?>
                            <div class="md-form">
                            <label for="point" class="control-label">
                                <?=$this->lang->line("grade_point")?> <span class="text-red">*</span>
                            </label>
                                <input type="text" class="form-control" id="point" name="point" value="<?=set_value('point')?>" >
                            <span class="text-danger error">
                                <?php echo form_error('point'); ?>
                            </span>
                            </div>
                        </div>
    
                        <?php
                            if(form_error('gradefrom'))
                                echo "<div class='form-group has-error' >";
                            else
                                echo "<div class='form-group' >";
                        ?>
                            <div class="md-form">
                            <label for="gradefrom" class="control-label">
                                <?=$this->lang->line("grade_gradefrom")?> <span class="text-red">*</span>
                            </label>
                                <input type="text" class="form-control" id="gradefrom" name="gradefrom" value="<?=set_value('gradefrom')?>" >
                            <span class="text-danger error">
                                <?php echo form_error('gradefrom'); ?>
                            </span>
                            </div>
                        </div>
    
                        <?php
                            if(form_error('gradeupto'))
                                echo "<div class='form-group has-error' >";
                            else
                                echo "<div class='form-group' >";
                        ?>
                            <div class="md-form">
                            <label for="gradeupto" class="control-label">
                                <?=$this->lang->line("grade_gradeupto")?> <span class="text-red">*</span>
                            </label>
                                <input type="text" class="form-control" id="gradeupto" name="gradeupto" value="<?=set_value('gradeupto')?>" >
                            <span class="text-danger error">
                                <?php echo form_error('gradeupto'); ?>
                            </span>
                            </div>
                        </div>
    
                        <?php
                            if(form_error('note'))
                                echo "<div class='form-group has-error' >";
                            else
                                echo "<div class='form-group' >";
                        ?>
                            <div class="md-form">
                            
                                <textarea style="resize:none;" class="form-control md-textarea" id="note" name="note"><?=set_value('note')?></textarea>
                                <label for="note" class="control-label">
                                <?=$this->lang->line("grade_note")?>
                            </label>
                            <span class="text-danger error">
                                <?php echo form_error('note'); ?>
                            </span>
                            </div>
                        </div>
                        <?php
                            if(form_error('remarks'))
                                echo "<div class='form-group has-error' >";
                            else
                                echo "<div class='form-group' >";
                        ?>
                            <div class="md-form">
                            
                                <textarea style="resize:none;" class="form-control md-textarea" id="remarks" name="remarks"><?=set_value('remarks')?></textarea>
                                <label for="note" class="control-label">
                                <?=$this->lang->line("grade_remarks")?>
                            </label>
                            <span class="text-danger error">
                                <?php echo form_error('remarks'); ?>
                            </span>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-success"><?=$this->lang->line("add_grade")?></button>
                         
                    </form>
                 
        </div>
    </div>
</div>
