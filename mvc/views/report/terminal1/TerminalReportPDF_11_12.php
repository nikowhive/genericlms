<?php if(customCompute($studentLists)) { foreach($studentLists as $x => $student) { ?>
<div style="padding-top:38px">
  <div class="mainBorder" style="padding-top:20px">
    <table class="container reportTheme-2">
      <thead>
        <tr>
          <td>
            <table class="table-fluid" >
              <tr>
                <td valign="top">
                  <img
                    src="<?=base_url("uploads/images/$siteinfos->photo")?>"
                    width="100"
                    height="100"
                    alt=""
                  />
                </td>
                <td>
                  <table>
                    <tr>
                      <td align="center">
                        <h3><?=strtoupper($siteinfos->sname)?></h3>
                        <p style="font-size:16px"><?=$siteinfos->address?></p>
                        <p style="font-size:16px">Ph: <?=$siteinfos->phone?></p>
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>
              <tr>
                <td align="center" colspan="2"  >
                  <!-- : <?=strtoupper($examName)?> -->
                  <h2>GRADE SHEET</h2>
                  <br><br>
                </td>
              </tr>
            </table>
          </td>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td class="description">
            THE FOLLOWING ARE THE GRADE(S) OBTAINED BY <b class="dot-border-bottom"><?=strtoupper($student->srname)?>&emsp; &emsp; &emsp; &emsp; &emsp;</b><br>
            DATE OF BIRTH: <b class="dot-border-bottom"><?=isset($student->dob_in_bs) ? "$student->dob_in_bs B.S. &emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;" : ''?></b> &emsp;&emsp; ( <b class="dot-border-bottom"><?=isset($student->dob) ? "$student->dob A.D. &emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;" : ''?></b>). &emsp; <br>
            REGISTRATION NO.: <b class="dot-border-bottom"><?=strtoupper($student->srroll)?>&emsp;&emsp;&emsp;&emsp;</b> &emsp;&emsp; SYMBOL NO.: <b class="dot-border-bottom"><?=strtoupper($student->remarks)?>&emsp;&emsp;&emsp;&emsp;</b> &emsp;&emsp; GRADE: <b class="dot-border-bottom"><?=isset($classes[$student->srclassesID]) && $classes[$student->srclassesID] == "Eleven" ? 'XI' : 'XII'?></b><br>
            IN THE ANNUAL EXAMINATION CONDUCTED BY SCHOOL/CAMPUS IN <b class="dot-border-bottom"><?=isset($exam->date_in_nepali) ? "&emsp;&emsp;". date('Y', strtotime($exam->date_in_nepali)) ." B.S &emsp;&emsp;" : '' ?></b><br> (<b class="dot-border-bottom" > &emsp;&emsp; <?=isset($exam->date) ? date('Y', strtotime($exam->date)): '' ?> A.D. &emsp;&emsp;</b>) ARE GIVEN BELOW:
            
          </td>
        </tr>

        <tr>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>
            <table class="table-fluid table-bordered">
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
                  <th style="vertical-align:middle" width="80">SUBJECT CODE</th>
                  <th style="vertical-align:middle" width="200">SUBJECTS</th>
                  <th style="vertical-align:middle">CREDIT HOURS</th>
                  <th  style="vertical-align:middle">GRADE POINT</th>
                  <th  style="vertical-align:middle">GRADE</th>
                  <th  style="vertical-align:middle">FINAL GRADE</th>
                  <th  style="vertical-align:middle">REMARKS</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $total_subject_mark = 0;
                $total_grade_point = 0;
                $total_credit_hours = 0;
                $credit_hours_x_grade_point = 0;
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
                    <td align="center"><?=$subject->subject_code ?></td>
                    <td><?=$subject->subject ?></td>
                    <td align="center"><?=$subject->credit ?></td>
                    <?php 
                    if($subject->credit != '') {
                        $total_credit_hours += $subject->credit; 
                    }
                    $mark_exist = false;
                    foreach($markpercentages as $markpercentageID) {
                        $f = false;
                        if(isset($uniquepercentageArr['own']) && in_array($markpercentageID, $uniquepercentageArr['own'])) {
                            $f = true;
                        } 
                        if(isset($studentPosition[$student->srstudentID]['markpercentageMark'][$subject->subjectID][$markpercentageID]) && $f) {

                            $markpercentage = isset($percentageArr[$markpercentageID]) ? $percentageArr[$markpercentageID]->markpercentagetype : '';
                            if($studentPosition[$student->srstudentID]['markpercentageMark'][$subject->subjectID][$markpercentageID] != 0) {
                                $practical_part = ($percentageArr[$markpercentageID]->percentage/100) * $fullmark;
                                $percentage = floor(($studentPosition[$student->srstudentID]['markpercentageMark'][$subject->subjectID][$markpercentageID]/$practical_part)*100);
                                if(customCompute($grades)) {
                                    foreach($grades as $grade) {
                                        if(($grade->gradefrom <= $percentage) && ($grade->gradeupto >= $percentage)) {
                                            $type = $markpercentage;
                                            $mark_exist = true;
                                            ?>
                                            <td align="center"><?=$grade->point?></td>
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
                    if($mark_exist == false) {
                        echo "<td align='center'>-</td>";
                        echo "<td align='center'>-</td>";
                    }

                        $percentageMark  = 0;
                        
                        $subjectMark = isset($studentPosition[$student->srstudentID]['subjectMark'][$subject->subjectID]) ? $studentPosition[$student->srstudentID]['subjectMark'][$subject->subjectID] : '0';                                    
                                        
                        $subjectMark = markCalculationView($subjectMark, $fullmark, $percentageMark);


                        $total_subject_mark += $subjectMark;
                        if(customCompute($grades)) {
                          foreach($grades as $grade) {
                              if(($grade->gradefrom <= $subjectMark) && ($grade->gradeupto >= $subjectMark)) {
                                  $mark_exist = true;
                                  $total_grade_point += $grade->point;
                                  if($subject->credit != '') {
                                      $credit_hours_x_grade_point += ($subject->credit * $grade->point);
                                  } else {
                                      $credit_hours_x_grade_point += 0;
                                  }
                                  // get theory subject with (th) in the end
                                  // if found store it
                                  // and find same subject with (pr) in the end
                                  // add and show
                                  if($type == 'th' || $type == 'Th' || $type == 'Theory' || $type == 'theory') {
                                      
                                  ?>
                                  <td align="center" ><?=$grade->grade?></td>
                                  <?php } else { ?>
                                      <td align="center" ></td>
                                  <?php } ?>
                                  <?php 
                              }
                          } 
                      }
                      if($mark_exist == false) {
                          echo "<td align='center'>-</td>";
                      }
                      ?>
                      <td>&nbsp;</td>
                      
                </tr>
                <?php } } ?>
              </tbody>


                <tr>
                  <td></td>
                  <td align="right" style="font-size:12px"><b>TOTAL</b></td>
                  <td></td>
                  <td colspan="4" style="font-size:12px"><b>GRADE POINT AVERAGE (GPA): 
                  <?php
                    $total_subject_mark = round($total_subject_mark / $subject_count);
                    if(isset($studentPosition[$student->srstudentID]['classPositionMark']) && $studentPosition[$student->srstudentID]['classPositionMark'] > 0 && isset($studentPosition['totalStudentMarkAverage']) && $total_subject_mark > 1) {
                        echo number_format($credit_hours_x_grade_point / $total_credit_hours, 2);
                        // echo number_format($total_grade_point / $subject_count, 2);
                    } else {
                        echo '0';
                    }
                    ?>
                    </b>
                </td>
                </tr>
              
            </table>
          </td>
        </tr>
    <tr>
      <td>&nbsp;</td>
    </tr>
        <tr>
          <td>
            <table class="table-fluid">
              <tr>
                <td colspan="3">PREPARED BY:</td>
              </tr>
              
              <tr>
                <td>
                  <table>
                    <tr>
                      <td width="200px" align="center">
                        <?="<img height='65' src=".getClassTeacherSignature($class->classes_numeric, $sections[$student->srsectionID])." />"; ?>
                      </td>
                    </tr>
                    <tr>
                      <td class="dot-border-top" align="center">
                        <b>CHECKED BY <br/>(CLASS TEACHER)</b>
                      </td>
                    </tr>
                  </table>
                </td>
                <td></td>
                <td align="right">
                  <table>
                    <tr>
                      <td width="200px" align="center">
                        <?php
                        if($siteinfos->enable_principal_signature == 'YES') {
                            if(customCompute($siteinfos->principal)) {
                                echo "<img height='65' src=".base_url('uploads/images/'.$siteinfos->principal)." />";
                            }
                        } else { ?>
                            <img width='150' height='50' src="uploads/images/site.png" style="visibility:hidden"/>
                        <?php } ?>
                      </td>
                    </tr>
                    <tr>
                      <td class="dot-border-top" align="center">
                        <b>HEAD MASTER / CAMPUS CHIEF</b>
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>
            </table>
          </td>
        </tr>
        <tr>
          <td>DATE OF ISSUE: <?=isset($exam->issue_date_in_english) ? $exam->issue_date_in_english : '.............' ?></td>
        </tr>
        <tr>
      <td>&nbsp;</td>
    </tr>
      </tbody>
      <tfoot>
        <tr>
          <td class="footer" style="border-top:1px solid black; line-height:24px">
            <h4>NOTE: ONE CREDIT HOURS EQUALS 32 CLOCK HOURS</h4>
            TH = THEORY &emsp; &emsp;&emsp; &emsp;&emsp; &emsp; &emsp; &emsp; &emsp; &emsp; &emsp; &emsp;PR = PRACTICAL &emsp; &emsp;&emsp; &emsp;&emsp; &emsp; &emsp; &emsp; &emsp; &emsp; &emsp; &emsp;XC = EXPELLED <br />
            ABS = ABSENT&emsp; &emsp;&emsp; &emsp;&emsp; &emsp; &emsp; &emsp; &emsp; &emsp; &emsp; &emsp;W = WITHHELD
          </td>
        </tr>
      </tfoot>
    </table>
  </div>
</div>
<?php if($x < count($studentLists) - 1) { ?>
    <p style="page-break-after: always;">&nbsp;</p>
<?php } ?>
<?php } } } else { ?>
    <div class="notfound">
        <?php echo $this->lang->line('terminalreport_data_not_found'); ?>
    </div>
<?php } ?>