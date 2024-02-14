
<legend class="mb-1 setting-legend">&nbsp;&nbsp;<?=$this->lang->line("marksetting_mark_percentage")?></legend>
<div class="col-md-12">
<?php
                            if(customCompute($markpercentages)) { ?>
                                 <table class="table table-striped table-bordered table-hover">
                                   <thead>
                                      <tr>
                                        <th></th>
                                        <th>Mark Distribution Type</th>
                                        <th>Mark Value</th>
                                      </tr>
                                   </thead>
                                   <tbody>
                                    <?php
                                      $classpercentageArr = count($classpercentageArr) ? $classpercentageArr : [];
                                      foreach ($markpercentages as $markpercentage) {
                                          $checkbox = (in_array($markpercentage->markpercentageID, $classpercentageArr)) ? 'checked="checked"' : ''; ?>
                                           <tr>
                                                <td>
                                                    <?php
                                                     echo '<input class="classwisemarkpercentage" type="checkbox" '.$checkbox.' value="1_'.$classesID.'_'.$markpercentage->markpercentageID.'" name="markpercentages[]"> &nbsp;';
                                                ?>
                                                </td>   
                                                 <td><?php  echo $markpercentage->markpercentagetype ?></td>
                                                <td><?php  echo $markpercentage->percentage ?></td>
                                           </tr>
                                    <?php } ?>
                                   </tbody>
                                 </table>
                           <?php } ?>
</div>






