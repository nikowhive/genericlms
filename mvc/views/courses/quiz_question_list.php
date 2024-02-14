<?php if ($chapter_quizzes) {

    $i = 1;
    foreach ($chapter_quizzes as $index => $quiz) {
       $question = isset($questions[$quiz->questionBankID]) ? $questions[$quiz->questionBankID] : '';
        $questionOptions = isset($options[$quiz->questionBankID]) ? $options[$quiz->questionBankID] : [];
        $questionAnswers = isset($answers[$quiz->questionBankID]) ? $answers[$quiz->questionBankID] : [];
        // dd($question);
?>
        <?php
        if ($question != '') {
            if ($question->typeNumber == 1 || $question->typeNumber == 2) {
                $questionAnswers = pluck($questionAnswers, 'optionID');
            }
            if ($question != '') {
        ?>

                <div class="card-list card-list--questions <?php echo (($this->coursequiz_m->get_bycoursechapter($quiz->questionBankID, $quiz_id)) ? 'added' : ''); ?>">
                    <div class="form-check">
                        <input class="form-check-input checkSingle checkbox-<?php echo $quiz->questionBankID ?>" type="checkbox" name="quizzes[]" value="<?php echo $quiz->questionBankID ?>" <?php echo set_checkbox('quizzes[]', $quiz->questionBankID); ?> <?php echo (($this->coursequiz_m->get_bycoursechapter($quiz->questionBankID, $quiz_id)) ? 'checked' : ''); ?> id="qsn-<?php echo $quiz->questionBankID ?>">
                        <label class="form-check-label" for="qsn-<?php echo $quiz->questionBankID ?>"> </label>
                    </div>
                    <div class="quiz-display">
                        <div class="quiz-display-header">
                            <div class="quiz-display-section">
                                <div class="quiz-display-type"><?php echo isset($types[$quiz->type_id]) ? ' '.$types[$quiz->type_id]->name.' ' : ''; ?></div>
                                <h4 class="quiz-display-title" data-prefix="Q">
                                    <?php
                                    echo strip_tags($quiz->question);
                                    ?>
                                </h4>

                                <ol type="A" class="quiz-display-answers" data-prefix="A">
                                    <?php
                                    $tdCount = 0;
                                    foreach ($questionOptions as $option) {
                                        $checked = false;
                                        if (in_array($option->optionID, $questionAnswers)) {
                                            $checked = true;
                                        }
                                    ?>
                                        <li>
                                            <?= $checked ? '<b>' . $option->name . '</b> (correct)' : $option->name ?>
                                        </li>
                                        <?php
                                        $tdCount++;
                                        if ($tdCount == 2) {
                                            $tdCount = 0;
                                            echo "</tr><tr>";
                                        }
                                    }

                                    echo "</ol>";
                                    if ($question->typeNumber == 3) {
                                        foreach ($questionAnswers as $answerKey => $answer) {
                                        ?>
                                            <div class="md-form--select md-form">
                                                <div class="select-wrapper">
                                                    <?= $answer->text ?>
                                                </div>
                                            </div>
                                    <?php
                                        }
                                    }
                                    ?>
                            </div>
                            <?php if ($this->coursequiz_m->get_bycoursechapter($quiz->questionBankID, $quiz_id)) { ?>
                                <button type="button" class="btn btn-success remove-question <?php echo (($this->coursequiz_m->get_bycoursechapter($quiz->questionBankID, $quiz_id)) ? '' : 'hide-q'); ?>" id="remove-question-<?=$quiz->questionBankID ?>" qid="<?=$quiz->questionBankID ?>">
                                    <i class="fa fa-times-circle"></i> Remove
                                </button>
                            <?php } else { ?>
                                <button type="button" class="btn btn-success add-question " id="add-question-<?= $quiz->questionBankID ?>" qid="<?= $quiz->questionBankID ?>"> <i class="fa fa-check"></i>Add</button>
                            <?php } ?>
                        </div>
                        <div></div>
                    </div>
                </div>
        <?php
            }
        } ?>

<?php }
} ?>

<script>
    $(function() {
        $('.hide-q').hide();
    });
   
</script>