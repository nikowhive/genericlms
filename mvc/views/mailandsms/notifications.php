<style>
      * {
        font-family: sans-serif;
      }
      .selections{
          /* height: 110px; */
          /* overflow-y: auto; */
      }
      .selected{
          /* height: 110px; */
          /* overflow-y: auto; */
      }
    </style>
    <link rel="stylesheet" href="<?php echo base_url('assets/inilabs/jquery.tree-multiselect.min.css'); ?>">
    <script src="<?php echo base_url('assets/jqueryUI/jqueryui.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/inilabs/jquery.tree-multiselect.js'); ?>"></script>
    <div class="box">
        <div class="box-header">
            <h3 class="box-title"><i class="fa icon-mailandsms"></i> <?=$this->lang->line('panel_title')?></h3>
            <ol class="breadcrumb">
                <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
                <li><a href="<?=base_url("mailandsms/index")?>"> <?=$this->lang->line('menu_mailandsms')?></a></li>
                <li class="active"> <?=$this->lang->line('menu_add')?> <?=$this->lang->line('menu_mailandsms')?></li>
            </ol>
        </div>
        <div class="box-body">
            <div class="row">
                <div class="col-sm-12">
                  <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <li class="active"><a data-toggle="tab" href="#email" aria-expanded="true"><?=$this->lang->line('mailandsms_email')?></a></li>
                        <li><a data-toggle="tab" href="#sms" aria-expanded="true"><?=$this->lang->line('mailandsms_sms')?></a></li>
                        <!-- <li><a data-toggle="tab" href="#announcement" aria-expanded="true">Notice</a></li>
                        <li><a data-toggle="tab" href="#events" aria-expanded="true">Events</a></li> -->
  
                    </ul>
                    <div class="tab-content">
                        <div id="email" class="tab-pane active">
                            <br>
                            <div class="row">
                                <div class="col-sm-10">

                    <form class="form-horizontal" role="form" method="post">
                          <?php echo form_hidden('type', 'email'); ?> 
                          <?php 
                              if(form_error('email')) 
                                  echo "<div id='divemail' class='form-group has-error' >";
                              else     
                                  echo "<div id='divemail' class='form-group' >";
                          ?>
                              <label for="email" class="col-sm-2 control-label">
                                Recipient Users:  
                              </label>
                              <div class="col-sm-10">
                                  <?php
                                      echo form_dropdown("email[]", "", "", "id='test-select-1'");
                                  ?>
                              </div>
                              <span class="col-sm-4 control-label">
                                  <?php echo form_error('email'); ?>
                              </span>
                            </div>
                          <?php 
                                if(form_error('email_template')) 
                                    echo "<div class='form-group has-error' >";
                                else     
                                    echo "<div class='form-group' >";
                            ?>
                                <label for="email_template" class="col-sm-2 control-label">
                                    <?=$this->lang->line("mailandsms_template")?>
                                </label>
                                <div class="col-sm-6" >
                                    
                                    <?php
                                        $array = array(
                                            'select' => $this->lang->line('mailandsms_select_template'),
                                        );
                                            
                                        echo form_dropdown("email_template", $array, set_value("email_template"), "id='email_template' class='form-control select2'");
                                    ?>
                                </div>
                                <span class="col-sm-4 control-label">
                                    <?php echo form_error('email_template'); ?>
                                </span>
                            </div>

                            <?php 
                                if(form_error('email_subject')) 
                                    echo "<div class='form-group has-error' id='subject_section' >";
                                else     
                                    echo "<div class='form-group' id='subject_section' >";
                            ?>
                                <label for="email_subject" class="col-sm-2 control-label">
                                    <?=$this->lang->line("mailandsms_subject")?> <span class="text-red">*</span>
                                </label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" id="email_subject" name="email_subject" value="<?=set_value('email_subject')?>" >
                                </div>
                                <span class="col-sm-4 control-label">
                                    <?php echo form_error('email_subject'); ?>
                                </span>
                            </div>

                            <?php 
                                if(form_error('email_message')) 
                                    echo "<div class='form-group has-error' >";
                                else     
                                    echo "<div class='form-group' >";
                            ?>
                                <label for="email_message" class="col-sm-2 control-label">
                                    <?=$this->lang->line("mailandsms_message")?> <span class="text-red">*</span>
                                </label>
                                <div class="col-sm-10">
                                    <textarea class="form-control" id="email_message1" name="email_message" ><?=set_value('email_message')?></textarea>
                                </div>
                                <span class="col-xs-12 col-sm-10 col-sm-offset-2 control-label">
                                    <?php echo form_error('email_message'); ?>
                                </span>
                            </div>    
                
                          <div class="form-group">
                              <div class="col-sm-offset-2 col-sm-8">
                                  <input type="submit" class="btn btn-success" value="<?=$this->lang->line("send")?>" >
                              </div>
                          </div>
                    </form>
                </div>
            </div>
        </div>
        <div id="sms" class="tab-pane">
            <br>
                <div class="row">
                    <div class="col-sm-10">

                    <form class="form-horizontal" role="form" method="post">
                          <?php 
                              if(form_error('email')) 
                                  echo "<div id='divemail' class='form-group has-error' >";
                              else     
                                  echo "<div id='divemail' class='form-group' >";
                          ?>
                              <label for="email" class="col-sm-2 control-label">
                                Recipient Users:  
                              </label>
                              <div class="col-sm-10">
                                  <?php
                                      echo form_dropdown("email", "", "", "id='test-select-2'");
                                  ?>
                              </div>
                              <span class="col-sm-4 control-label">
                                  <?php echo form_error('email'); ?>
                              </span>
                            </div>
                          <?php 
                              if(form_error('email_template')) 
                                  echo "<div class='form-group has-error' >";
                              else     
                                  echo "<div class='form-group' >";
                          ?>
                              <label for="email_template" class="col-sm-2 control-label">
                                  <?=$this->lang->line("mailandsms_template")?>
                              </label>
                              <div class="col-sm-6" >
                                  
                                  <?php
                                      $array = array(
                                          'select' => $this->lang->line('mailandsms_select_template'),
                                      );
                                          
                                      echo form_dropdown("email_template", $array, set_value("email_template"), "id='email_template' class='form-control select2'");
                                  ?>
                              </div>
                              <span class="col-sm-4 control-label">
                                  <?php echo form_error('email_template'); ?>
                              </span>
                          </div>

                          <?php 
                              if(form_error('email_subject')) 
                                  echo "<div class='form-group has-error' id='subject_section' >";
                              else     
                                  echo "<div class='form-group' id='subject_section' >";
                          ?>
                              <label for="email_subject" class="col-sm-2 control-label">
                                  <?=$this->lang->line("mailandsms_subject")?> <span class="text-red">*</span>
                              </label>
                              <div class="col-sm-6">
                                  <input type="text" class="form-control" id="email_subject" name="email_subject" value="<?=set_value('email_subject')?>" >
                              </div>
                              <span class="col-sm-4 control-label">
                                  <?php echo form_error('email_subject'); ?>
                              </span>
                          </div>

                          <?php 
                              if(form_error('email_message')) 
                                  echo "<div class='form-group has-error' >";
                              else     
                                  echo "<div class='form-group' >";
                          ?>
                              <label for="email_message" class="col-sm-2 control-label">
                                  <?=$this->lang->line("mailandsms_message")?> <span class="text-red">*</span>
                              </label>
                              <div class="col-sm-10">
                                  <textarea class="form-control" id="email_message2" name="email_message" ><?=set_value('email_message')?></textarea>
                              </div>
                              <span class="col-xs-12 col-sm-10 col-sm-offset-2 control-label">
                                  <?php echo form_error('email_message'); ?>
                              </span>
                          </div>    
                
                          <div class="form-group">
                              <div class="col-sm-offset-2 col-sm-8">
                                  <input type="submit" class="btn btn-success" value="<?=$this->lang->line("send")?>" >
                              </div>
                          </div>
                    </form>
                </div>
            </div>
        </div>
                       
    </div>
    </div>
    <script type="text/javascript" src="<?php echo base_url('assets/editor/jquery-te-1.4.0.min.js'); ?>"></script>
    <script type="text/javascript">
      $(document).ready(function() {
          $('#email_message1').jqte();
          $('#email_message2').jqte();
          $('#email_message3').jqte();
          $('#email_message4').jqte();
      });    
      //$('#otheremail_message').jqte();
      $.getJSON( "<?=base_url('mailandsms/getAlldatas')?>", function( data ) {

        var $select = $('#test-select-1');
        $.each( data, function( key, val ) {
          //console.log(val.email);
          var $option = $('<option value="'+val.email+'" data-section="'+val.category2+'/'+val.category1+'">'+val.name+'</option>');
          $select.append($option);    

        });

        $select.treeMultiselect({ enableSelectAll: true, sortable: true, searchable: true, startCollapsed: true});

        var $select2 = $('#test-select-2');
        $.each( data, function( key, val ) {
              var $option = $('<option value="'+val.email+'" data-section="'+val.category2+'/'+val.category1+'">'+val.name+'</option>');
          $select2.append($option);    
        });

        $select2.treeMultiselect({ enableSelectAll: true, sortable: true, searchable: true, startCollapsed: true});

        // var $select3 = $('#test-select-3');
        // $.each( data, function( key, val ) {
  
        //   var $option = $('<option value="'+val.email+'" data-section="'+val.category2+'/'+val.category1+'">'+val.name+'</option>');
        //   $select3.append($option);    

        // });

        // $select3.treeMultiselect({ enableSelectAll: true, sortable: true, searchable: true, startCollapsed: true});

        // var $select4 = $('#test-select-4');
        // $.each( data, function( key, val ) {
          
        //   var $option = $('<option value="'+val.email+'" data-section="'+val.category2+'/'+val.category1+'">'+val.name+'</option>');
        //   $select4.append($option);    

        // });

        // $select4.treeMultiselect({ enableSelectAll: true, sortable: true, searchable: true, startCollapsed: true});
       
        
      });

      

      $('#date').daterangepicker({
        timePicker: true,
        timePickerIncrement: 5,
        maxDate: '<?=date('m/d/Y', strtotime($schoolyearsessionobj->endingdate))?>',
        minDate: '<?=date('m/d/Y', strtotime($schoolyearsessionobj->startingdate))?>',
        locale: {
            format: 'MM/DD/YYYY h:mm A'
        },
    });
      
      
    </script>