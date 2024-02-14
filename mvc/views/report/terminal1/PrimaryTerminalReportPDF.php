<?php

if(customCompute($studentLists)) { foreach($studentLists as $x => $student) { ?>

<table class="container pdfTable-lg pdfTable--bgmsm">
  <tbody>
    <tr>
      <td>
        <table class="header">
          <tr>
            <td align="center">
              <h1 style="font-size:24px;"><?=strtoupper($siteinfos->sname)?></h1>
            </td>
          </tr>
          <tr>
            <td>
              <table class="table-fluid head-mid">
                <tr>
                  <td width="120">
                    <img
                      src="<?=base_url("uploads/images/$siteinfos->photo")?>"
                      width="100"
                      height="100"
                      alt=""
                    />
                  </td>
                  <td  width="600"  style="padding-left:26px;">
                    <table>
                      <tr>
                        <td align="center">
                          <p style="font-size:14pt;">
                            <?=$siteinfos->address?>, Tel: <?=$siteinfos->phone?>
                            <br />
                            E-mail: <?=$siteinfos->email?>
                          </p>
                          <br />
                          <h2><?=strtoupper($examName)?></h2>
                          <br />
                          <h3>GRADE-SHEET</h3>
                        </td>
                      </tr>
                    </table>
                  </td>
                  <td>&nbsp;</td>
             
                </tr>
              </table>
            </td>
          </tr>
          <tr>
            <td>
              <table class="table-fluid student-details">
                <tr>
                  <td><b>Name: <?=$student->srname?></b></td>
                  <td><b>Class: <?=isset($classes[$student->srclassesID]) ? $classes[$student->srclassesID] : ''?></b></td>
                  <td><b>Section: <?=isset($sections[$student->srsectionID]) ? $sections[$student->srsectionID] : ''?></b> </td>
                </tr>
                <tr>
                  <td><b>Roll NO: <?=$student->srroll?></b></td>
                  <td><b>DOB: <?=isset($student->dob) ? $student->dob : ''?></b></td>
                  <td><b>Registration No.: <?=$student->srregisterNO?></b></td>
                  <!-- <td><b>House: Laligurans</b></td> -->
                </tr>
              </table>
            </td>
          </tr>
        </table>
      </td>
    </tr>
    <tr>
      <td>
        <table class="reports table-fluid">
          <thead>
            <tr>
              <?php 
                reset($markpercentagesArr);
                $firstindex          = key($markpercentagesArr);
                $uniquepercentageArr = isset($markpercentagesArr[$firstindex]) ? $markpercentagesArr[$firstindex] : [];
                $markpercentages     = $uniquepercentageArr[(($settingmarktypeID==4) || ($settingmarktypeID==6)) ? 'unique' : 'own'];
                
                reset($markpercentagesArr1);
                $firstindex1          = key($markpercentagesArr1);
                $uniquepercentageArr1 = isset($markpercentagesArr1[$firstindex]) ? $markpercentagesArr1[$firstindex] : [];
                $markpercentages1     = $uniquepercentageArr1[(($settingmarktypeID==4) || ($settingmarktypeID==6)) ? 'unique' : 'own'];
                  
              ?>
              <th rowspan="2" align="center" width="30" style="vertical-align: middle;">S.No.</th>
              <th rowspan="2"  align="center" width="300" style="vertical-align: middle;">Subject</th>
              <th rowspan="2" width="60" align="center"  style="vertical-align: middle;">Credit Hours</th>
              <!-- <th rowspan="2" width="120">Obtained Mark</th> -->
              <th colspan="3"  width="60" align="center" >Obtained Grade</th>
              <th rowspan="2" width="60" align="center" style="vertical-align: middle;">Grade Point</th>
              <th rowspan="2" width="60" align="center" style="vertical-align: middle;">Highest Grade Point</th>
            </tr>

            <tr>
              <th align="center" style="vertical-align: middle;">Continuous Assessment System</th>
              <th  align="center" style="vertical-align: middle;">Periodic Assessment</th>
              <th width="60" align="center" style="vertical-align: middle;">Final</th>
            </tr>
          </thead>
          <tbody>
          <?php
          $total_subject_mark = 0;
          $total_grade_point = 0;
          $subject_count = 0;
          $after_first = false;
          $i = 0;
          if(customCompute($subjects)) { foreach($subjects as $index => $subject) {
            $f = true;
            $mark_exist = false;
              if(isset($studentPosition[$student->srstudentID]['subjectMark'][$subject->subjectID])) {
              $subject_count = $subject_count + 1;

              $fullmark = $subject->finalmark;
              if(isset($subject_marks[$subject->subjectID]) && $subject_marks[$subject->subjectID] != '') {
                  $fullmark = $subject_marks[$subject->subjectID];
              }

              $uniquepercentageArr =  isset($markpercentagesArr[$subject->subjectID]) ? $markpercentagesArr[$subject->subjectID] : [];
            
             ?>
          <tr>
              <td align="center"><?=$i+1; $i++; ?></td>
              <td><?=$subject->subject ?></td>
              <td align="center"><?=$fullmark  == 100 ? '4': '2' ?></td>
              <?php
              $percentageMark  = 0;
              ?>
              
              <?php foreach($markpercentages as $markpercentageID) {
                $f = false;
                if(isset($uniquepercentageArr['own']) && in_array($markpercentageID, $uniquepercentageArr['own'])) {
                    $f = true;
                } 
                if(isset($studentPosition[$student->srstudentID]['markpercentageMark'][$subject->subjectID][$markpercentageID]) && $f) {

                  $markpercentage = isset($percentageArr[$markpercentageID]) ? $percentageArr[$markpercentageID]->markpercentagetype : '';
                  if($markpercentage == 'Continuous Assessment System' || $markpercentage == 'continuous assessment system') {
                    if($studentPosition[$student->srstudentID]['markpercentageMark'][$subject->subjectID][$markpercentageID] != 0) {
                      $theory_part = ($percentageArr[$markpercentageID]->percentage/100) * $fullmark;
                      $percentage = floor(($studentPosition[$student->srstudentID]['markpercentageMark'][$subject->subjectID][$markpercentageID]/$theory_part)*100);
                      if(customCompute($grades)) {
                          foreach($grades as $grade) {
                              if(($grade->gradefrom <= $percentage) && ($grade->gradeupto >= $percentage)) {
                                  $mark_exist = true;
                                  ?>
                                  <td align="center"><?=$grade->grade?></td>
                                  <?php 
                              }
                          } 
                      } 
                    } else {
                        $mark_exist = false;
                    }
                  }
                }
              } 
              
              if($mark_exist == false) {
                  echo "<td align='center'>-</td>";
              }
              foreach($markpercentages as $markpercentageID) {
                $f = false;
                if(isset($uniquepercentageArr['own']) && in_array($markpercentageID, $uniquepercentageArr['own'])) {
                    $f = true;
                } 
                if(isset($studentPosition[$student->srstudentID]['markpercentageMark'][$subject->subjectID][$markpercentageID]) && $f) {

                $markpercentage = isset($percentageArr[$markpercentageID]) ? $percentageArr[$markpercentageID]->markpercentagetype : '';
                if($markpercentage == 'Periodic Assessment' || $markpercentage == 'periodic assessment') {
                    if(isset($studentPosition[$student->srstudentID]['markpercentageMark'][$subject->subjectID][$markpercentageID])) {
                      if($studentPosition[$student->srstudentID]['markpercentageMark'][$subject->subjectID][$markpercentageID] != 0) {
                        $practical_part = ($percentageArr[$markpercentageID]->percentage/100) * $fullmark;
                        $percentage = floor(($studentPosition[$student->srstudentID]['markpercentageMark'][$subject->subjectID][$markpercentageID]/$practical_part)*100);
                        if(customCompute($grades)) {
                            foreach($grades as $grade) {
                              if(($grade->gradefrom <= $percentage) && ($grade->gradeupto >= $percentage)) {
                                  $mark_exist = true;
                                  ?>
                                  <td align="center"><?=$grade->grade?></td>
                                  <?php
                              }
                            } 
                        }
                      } else {
                          $mark_exist = false;
                      }
                    }
                  }
                }
            }
            
              if($mark_exist == false) {
                  echo "<td align='center>-</td>";
              }
              $subjectMark = isset($studentPosition[$student->srstudentID]['subjectMark'][$subject->subjectID]) ? $studentPosition[$student->srstudentID]['subjectMark'][$subject->subjectID] : '0';
              $subjectMark = markCalculationView($subjectMark, $fullmark, $percentageMark);

              $total_subject_mark += $subjectMark;
              if(customCompute($grades)) {
                  foreach($grades as $grade) {
                      if(($grade->gradefrom <= $subjectMark) && ($grade->gradeupto >= $subjectMark)) {
                          $mark_exist = true;
                          $total_grade_point += $grade->point;
                          ?>
                          <td align="center"><?=$grade->grade?></td>
                          <td align="center"><?=$grade->point?></td>
                          <?php 
                      }
                  } 
              }
              if($mark_exist == false) {
                echo "<td align='center'>-</td>";
                echo "<td align='center'>-</td>";
              }
              $highest = 0;
              foreach($markpercentages as $index => $markpercentageID) {
                  $f = false;
                  if(isset($uniquepercentageArr['own']) && in_array($markpercentageID, $uniquepercentageArr['own'])) {
                      $f = true;
                  }  
                  if(isset($highestmarks[$examID][$subject->subjectID][$markpercentageID]) && ($highestmarks[$examID][$subject->subjectID][$markpercentageID] != -1) && $f) {
                      $highest += $highestmarks[$examID][$subject->subjectID][$markpercentageID];
                  }
              }
              if($fullmark == 50) {
                $highest = floor($highest * 2);
              }
              if(customCompute($grades)) {
                foreach($grades as $grade) {
                    if(($grade->gradefrom <= $highest) && ($grade->gradeupto >= $highest)) {
                        ?>
                        <td align="center"><?=$grade->point?></td>
                        <?php 
                      }
                  } 
              } 
              if($highest == 0) {
                echo '<td align="center">0</td>';
              }
              ?>
          </tr>
          <?php } } ?>
          </tbody>
          <tfoot>
            <tr>
            <td colspan="7" align="right" style="margin-right:10px;">
                <b>GRADE POINTS AVERAGE (GPA): <?php 
                    $total_subject_mark = round($total_subject_mark / $subject_count);
                    if(isset($studentPosition[$student->srstudentID]['classPositionMark']) && $studentPosition[$student->srstudentID]['classPositionMark'] > 0 && isset($studentPosition['totalStudentMarkAverage']) && $total_subject_mark > 1) {
                        echo number_format($total_grade_point / $subject_count, 2);
                    } else {
                        echo '0';
                    }
                ?></b></td>
                <td></td>
            </tr>
          </tfoot>
        </table>
      </td>
    </tr>

    <tr>
      <td  >
        <table class="  table-fluid">
          <tr   > 
            <td style="padding-left:0px; width:65%;">
              <table class="grade-table table-fluid">
                <thead>
                    <tr>
                        <th colspan="5">Grade Descriptions</th>
                    </tr>
                    <tr>
                        <th style="vertical-align: middle;" width="40">S.No.</th>
                        <th style="vertical-align: middle;" width="150">Interval in Percent</th>
                        <th style="vertical-align: middle;" >Grade</th>
                        <th style="vertical-align: middle;" width="150"  >Description</th>
                        <th style="vertical-align: middle;" >Grade Point</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="font-size: 8pt;" align="center">1</td>
                        <td style="font-size: 8pt;">90 to 100</td>
                        <td style="font-size: 8pt;" align="center">A+ </tda>
                        <td style="font-size: 8pt;">Outstanding </td>
                        <td style="font-size: 8pt;" align="center">4.0</td>
                        </tr>
                        <tr>
                        <td style="font-size: 8pt;" align="center">2</td>
                        <td style="font-size: 8pt;">80 to Below 90</td>
                        <td style="font-size: 8pt;" align="center">A </td>
                        <td style="font-size: 8pt;">Excellent</td>
                        <td style="font-size: 8pt;" align="center">3.6</td>
                    </tr>
                    <tr>
                        <td style="font-size: 8pt;" align="center">3</td>
                        <td style="font-size: 8pt;">70 to Below 80</td>
                        <td style="font-size: 8pt;" align="center">B+ </td>
                        <td style="font-size: 8pt;">Very Good</td>
                        <td style="font-size: 8pt;" align="center">3.2</td>
                        </tr>
                        <tr>
                        <td style="font-size: 8pt;" align="center">4</td>
                        <td style="font-size: 8pt;">60 to Below 70</td>
                        <td style="font-size: 8pt;" align="center">B </td>
                        <td style="font-size: 8pt;">Good</td>
                        <td style="font-size: 8pt;" align="center">2.8</td>
                    </tr>
                    <tr>
                        <td style="font-size: 8pt;" align="center">5</td>
                        <td style="font-size: 8pt;">50 to Below 60</td>
                        <td style="font-size: 8pt;" align="center">C+ </td>
                        <td style="font-size: 8pt;">Above Average</td>
                        <td style="font-size: 8pt;" align="center">2.4</td>
                        </tr>
                        <tr>
                        <td style="font-size: 8pt;" align="center">6</td>
                        <td style="font-size: 8pt;">40 to Below 50</td>
                        <td style="font-size: 8pt;" align="center">C </td>
                        <td style="font-size: 8pt;">Average</td>
                        <td style="font-size: 8pt;" align="center">2.0</td>
                    </tr>
                    <tr>
                        <td style="font-size: 8pt;" align="center">7</td>
                        <td style="font-size: 8pt;">30 to Below 40</td>
                        <td style="font-size: 8pt;" align="center">D+ </td>
                        <td style="font-size: 8pt;">Partially Acceptable</td>
                        <td style="font-size: 8pt;" align="center">1.6</td>
                      </tr>
                      <tr>
                        <td style="font-size: 8pt;" align="center">8</td>
                        <td style="font-size: 8pt;">20 to Below 30</td>
                        <td style="font-size: 8pt;" align="center">D </td>
                        <td style="font-size: 8pt;">Insufficient </td>
                        <td style="font-size: 8pt;" align="center">1.2</td>
                    </tr>
                    <tr>
                        <td style="font-size: 8pt;" align="center">9</td>
                        <td style="font-size: 8pt;">1 to Below 20</td>
                        <td style="font-size: 8pt;" align="center">E </td>
                        <td style="font-size: 8pt;">Very Insufficient</td>
                        <td style="font-size: 8pt;" align="center">0.8</td>
                    </tr>
                </tbody>
            </table>
            </td>
            <td  style="padding-right:0px;" >
              <table class="grade-table table-fluid">
                <thead>
                  <tr>
                    <th colspan="2">Deportments</th>
                  </tr>
                </thead>
                <tbody>
                <?php
                 foreach($coscholasticSubjects as $index => $subject) {
                  ?>
                  <tr>
                      <?php
                          $percentageMark1  = 0;

                          if(isset($studentPosition1[$student->srstudentID]['subjectMark'][$subject->subjectID])) {
                              $uniquepercentageArr1 =  isset($markpercentagesArr1[$subject->subjectID]) ? $markpercentagesArr1[$subject->subjectID] : [];
                          if(customCompute($markpercentages1)) {
                              foreach($markpercentages1 as $markpercentageID) {

                                  $f = false;
                                  if(isset($uniquepercentageArr1['own']) && in_array($markpercentageID, $uniquepercentageArr1['own'])) {
                                      $f = true;
                                      $percentageMark1   += isset($percentageArr1[$markpercentageID]) ? $percentageArr1[$markpercentageID]->percentage : 0;
                                  } 
                                  $markpercentage1 = isset($percentageArr1[$markpercentageID]) ? $percentageArr1[$markpercentageID]->markpercentagetype : '';
                                  if($markpercentage1 == 'Deportments'|| $markpercentage1 == 'Deportments') { ?>
                                      <td  width="100" ><?=$subject->subject ?></td>
                                      <td style="text-align:center"  width="60" align="center">
                                      <?php 
                                          if(isset($studentPosition1[$student->srstudentID]['markpercentageMark'][$subject->subjectID][$markpercentageID]) && $f && $studentPosition1[$student->srstudentID]['markpercentageMark'][$subject->subjectID][$markpercentageID] != 0) {
                                              foreach($grades as $grade) {
                                                  if($grade->gradefrom <= $studentPosition1[$student->srstudentID]['markpercentageMark'][$subject->subjectID][$markpercentageID] && $grade->gradeupto >= $studentPosition1[$student->srstudentID]['markpercentageMark'][$subject->subjectID][$markpercentageID]) {
                                                      echo $grade->grade;
                                                  }
                                              }
                                          } else {
                                              echo '-';
                                          }
                                      ?>
                                      </td>
                                  <?php 
                                  }
                              }
                          } } 
                          ?>
                      </tr>
                  <?php } ?>
                </tbody>
              </table>
              
              <br>
              <table class="grade-table table-fluid"  >
                <thead>
                  <tr>
                    <th width="50">S.No.</th>
                    <th colspan="2">Attendance</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                      <td align="center">1</td>
                      <td>School Days</td>
                      <td width="100" align="center"><?=getSchoolDays($class->classes_numeric); ?></td>
                      </tr>
                      <tr>
                      <td align="center">2</td>
                      <td>Present Days</td>
                      <td width="100" align="center"><?=isset($student->presentdays) ? $student->presentdays : 0 ?></td>
                      </tr>
                      <tr>
                      <td align="center">3</td>
                      <td>Absent Days</td>
                      <td width="100" align="center"><?=isset($student->presentdays) ? getSchoolDays($class->classes_numeric) - $student->presentdays : getSchoolDays($class->classes_numeric); ?></td>
                  </tr>
                </tbody>
              </table>
              
            </td>
          </tr>
      
        </table>
      </td>
    </tr>
    <tr>
      <td style="padding-left: 8px;">
        <table class="grade-table table-fluid"  >
          <tr>
            <td>
              <h4>Remarks:</h4>
            </td>
          </tr>
          <tr>
            <td>
              <?php 
                if(isset($remarks[$student->studentID])) { ?>
                  <p><?=$remarks[$student->studentID] ?></p>
                  <p >&nbsp;</p>
                <?php } else { ?>
                  <p >&nbsp;</p>
                  <p >&nbsp;</p>
                <?php } ?>
            </td>
          </tr>
        </table>
      </td>
    </tr>
    <br>
    <br>
    <tr>
        <td valign="top">
          <table class="table-fluid">
            <tr>

              <td align="center">
                <table class="table-fluid" width="150">
                  <tr><td height="70"  style="vertical-align: bottom;"><?=isset($exam->issue_date) ? $exam->issue_date : '' ?></td></tr>
                  <tr><td  align="center" class="dot-border-top">Issue Date</td></tr>
                </table>
                
              </td>


              <td align="center">
                <table width="150">
                    <tr>
                        <td>
                            <?="<img height='60' src=".getClassTeacherSignature($class->classes_numeric, $sections[$student->srsectionID])." />"; ?>
                        </td>
                    </tr>
                    <tr><td class="dot-border-top"><?=isset($class_teacher[$student->srsectionID]) ? $class_teacher[$student->srsectionID] : ''?></td></tr> 
                    <tr><td  align="center">Class Teacher</td></tr>
                </table>
        
            </td>



            <td align="center">
                <table width="150">
                    <tr>
                        <td>
                            <?="<img height='60' src=".getVerifiedSignature($class->classes_numeric)." />"; ?>
                        </td>
                    </tr>
                    <?=getVerifiedBy($class->classes_numeric) ?>
                    <tr><td  align="center">Co-ordinator</td></tr>
                </table>
        
            </td>
              
            
              <td align="center">
                <table class="table-fluid" width="150">
                  <tr>
                    <td>
                    <?php
                        if($siteinfos->enable_principal_signature == 'YES') {
                            if(customCompute($siteinfos->principal)) {
                                echo "<center><img height='60' src=".base_url('uploads/images/'.$siteinfos->principal)." /></center>";
                            }
                        } else { ?>
                            <img height='60' src="uploads/images/site.png" style="visibility:hidden"/>
                        <?php }
                    ?>
                  </td>
                </tr>
                <tr><td  class="dot-border-top"><?= customCompute($siteinfos->principal_name) ? $siteinfos->principal_name : '' ?></td></tr> 
                  <tr>
                    <td  align="center">Principal</td>
                  </tr>
                </table>
              </td>
            </tr>
          </table>
        </td>
    </tr>
  </tbody>
</table>

<?php if($x < count($studentLists) - 1) { ?>
        <p style="page-break-after: always;">&nbsp;</p>
    <?php } ?>
    <?php } } } else { ?>
        <div class="notfound">
            <?php echo $this->lang->line('terminalreport_data_not_found'); ?>
        </div>
    <?php } ?>