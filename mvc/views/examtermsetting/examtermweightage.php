<legend class="mb-1 setting-legend">Term Weightage</legend>
                    <div class="row">
                    <div class="col-md-12" >
                    <?php
                            if(customCompute($exams)) { ?>
                                 <table class="table table-striped table-bordered table-hover">
                                   <thead>
                                      <tr>
                                        <th></th>
                                        <th>Exam Type</th>
                                        <th>Marks</th>
                                      </tr>
                                   </thead>
                                   <tbody>
                                    <?php

                                      foreach ($exams as $exam) {
                                          $value = isset($checkexamtermsettingsArray[$exam->examID])?$checkexamtermsettingsArray[$exam->examID]:'';
                                          $checkbox = isset($checkexamtermsettingsArray[$exam->examID])?'checked="checked"':'';
                                          $disabled = isset($checkexamtermsettingsArray[$exam->examID])?'':'disabled="disabled"';
                                       
                                       ?>
                                           <tr>
                                                <td><?php  echo '<input class="examtermsettingCheckbox" '.$checkbox.' type="checkbox" value="'.$exam->examID.'" > &nbsp;'; ?></td>
                                                <td>
                                                 <?= $exam->exam; ?>
                                                  <?= in_array($exam->examID,$markSettings)?'':'</br><span style="font-size:10px;color:red;">Marksetting not done.</span>' ?>
                                                 </td>
                                                <td><input <?php echo  $disabled; ?> type="text" class="form-control marks" id="mark<?php echo $exam->examID ?>" name="marks[<?php echo $exam->examID; ?>]" value="<?php echo $value; ?>"/></td>
                                           </tr>
                                      <?php }
                                       $value1 = isset($checkexamtermsettingsArray[$examID])?$checkexamtermsettingsArray[$examID]:100;
                                      ?>
                                            <tr>
                                                <td><input type="checkbox" checked disabled/></td>
                                                <td><?php  echo $examName ?>
                                                <?= in_array($examID,$markSettings)?'':'</br><span style="font-size:10px;color:red;">Marksetting not done.</span>' ?>
                                                </td>
                                                <td><input type="text" readonly class="form-control" id="mark<?php echo $examID ?>" name="marks[<?php echo $examID; ?>]" value="<?php echo $value1; ?>"/></td>
                                           </tr>
                                           <tr>
                                                <td></td>
                                                <td>Total: (100)</td>
                                                <td id="total"></td>
                                           </tr>
                                   </tbody>
                                   
                                 </table>
                                 
                           <?php } ?>
                    </div>
                        
                    </div>
