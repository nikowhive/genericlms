<?php if($chapter_quizzes) { $i = 1; foreach($chapter_quizzes as $index => $quiz) {
$question = isset($questions[$quiz->questionBankID]) ? $questions[$quiz->questionBankID] : '';
$questionOptions = isset($options[$quiz->questionBankID]) ? $options[$quiz->questionBankID] : [];
$questionAnswers = isset($answers[$quiz->questionBankID]) ? $answers[$quiz->questionBankID] : [];    
?>

<?php

if($question != ''){
    if($this->coursequiz_m->get_bycoursechapter($quiz->questionBankID, $quiz_id)) {
    
    if($question->typeNumber == 1 || $question->typeNumber == 2) {
        $questionAnswers = pluck($questionAnswers, 'optionID');
    }
    if($question != '') {
        ?>
        <div class="card-list card-list--item" id="question-list-<?=$quiz->questionBankID ?>">
          
            <div style="display: flex;">

                <!-- <input type="checkbox" style ="margin-right: 10px;"class="checkSingle checkbox-<?php echo $quiz->questionBankID ?>" name="quizzes[]" value="<?php echo $quiz->questionBankID ?>" <?php echo set_checkbox('quizzes[]', $quiz->questionBankID); ?> <?php echo (($this->coursequiz_m->get_bycoursechapter($quiz->questionBankID, $quiz_id)) ? 'checked' : ''); ?>> -->
                <a class="icon-round collapsed" role="button" data-toggle="collapse" href="#question<?php echo $quiz->questionBankID ?>" aria-expanded="false"><i class="fa fa-caret-down"></i>
                </a>
            </div>
        
            <div class="quiz-display">
                    <div class="quiz-display-header">
                        <div class="quiz-display-section">
                            <div class="quiz-display-type"><?php echo isset($types[$quiz->type_id]) ? ' ('.$types[$quiz->type_id]->name.')' : ''; ?></div>
                            <h4 class="quiz-display-title">
                                <?php 
                                if(strlen($quiz->question) > 60) {
                                        echo substr(strip_tags($quiz->question), 0, 60)."...";
                                    } else {
                                        echo strip_tags($quiz->question);
                                        
                                    }
                                ?>
                            </h4>
                        </div>
                        <div class="dropdown">
                            <a
                                href="#"
                                class="icon-round"
                                role="button"
                                data-toggle="dropdown"
                                >
                            ⋮</a
                                >
                            <ul
                                id="menu2"
                                class="dropdown-menu right"
                                aria-labelledby="drop5"
                                >
                                <li>
                                <a class="add-question <?php echo (($this->coursequiz_m->get_bycoursechapter($quiz->questionBankID, $quiz_id)) ? 'hide-q' : ''); ?>" id="add-question-<?=$quiz->questionBankID ?>" qid="<?=$quiz->questionBankID ?>"><i class="fa fa-plus" aria-hidden="true" style="font-size:24px"></i> Add</a>
                                </li>
                                <li>
                                <a class="remove-question <?php echo (($this->coursequiz_m->get_bycoursechapter($quiz->questionBankID, $quiz_id)) ? '' : 'hide-q'); ?>" id="remove-question-<?=$quiz->questionBankID ?>" qid="<?=$quiz->questionBankID ?>"><i class="fa fa-times-circle" aria-hidden="true" style="font-size:24px"></i> Remove</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div id="question<?php echo $quiz->questionBankID ?>" class="collapse">
                        <ol type="A" class="quiz-display-answers">
                        <?php
                            $tdCount = 0;
                            foreach ($questionOptions as $option) {
                                $checked = false;
                                if(in_array($option->optionID, $questionAnswers)) {
                                    $checked = true;
                                }
                                ?>
                                <li>
                                    <?=$checked ? '<b>'.$option->name.'</b> (correct)': $option->name ?>
                                </li>
                                <?php
                                $tdCount++;
                                if($tdCount == 2) {
                                    $tdCount = 0;
                                    echo "</tr><tr>";
                                }
                            }
                                    
                            echo "</ol>";
                            if($question->typeNumber == 3) {
                                foreach ($questionAnswers as $answerKey => $answer) {
                                    ?>
                                    <div class="md-form--select md-form">
                                        <div class="select-wrapper">
                                            <?=$answer->text ?>
                                        </div>
                                    </div>
                                    <?php
                                }
                            }
                        ?>
                        
                    </div>
                </div>
        </div>
            <?php
        }
    }
} ?>

<?php } } ?>

<script>
    $(function(){
        $('.hide-q').hide();
    });

</script>