<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h3 class="modal-title"><?= $this->data['quiz']->quiz_name ?></h3>
</div>
<div class="modal-body">
    <div class="container-fluid">
        

        <?php if(isset($this->data['chapter']->unit)): ?>
            <div class="row">
                <div class="col-md-12 ml-auto"><b>Unit</b><br><?= (isset($this->data['chapter']->unit) ? $this->data['chapter']->unit : "") ?></div>
            </div>
            <hr>
        <?php endif; ?>
        <?php if(isset($this->data['chapter']->chapter_name)): ?>
            <div class="row">
                <div class="col-md-12 ml-auto"><b>Chapter</b><br><?= (isset($this->data['chapter']->chapter_name) ? $this->data['chapter']->chapter_name : "") ?></div>
            </div>
            <hr>
        <?php endif; ?>

        <div class="row">
            <div class="col-md-12 ml-auto"><b>Coverage: </b><?= $this->data['quiz']->percentage_coverage ?>%</div>
        </div>
        <hr>

        <div>
            <ul><?php foreach ($this->data['questions'] as $question) : ?>
                    <li><?= $question->question ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>

</div>
</div>
<div class="modal-footer">
    <?php if (permissionChecker('quiz_edit')) : ?>
                   <a class="btn btn-primary" target="_blank" href="<?php echo base_url() . 'courses/new_quiz_ui/' . $quiz->id . '/' . $quiz->coursechapter_id . '?course=' . $quiz->course_id ?>">View</a>
    <?php endif; ?>
    <?php if($usertypeID == 3):?>
    <a class="btn btn-primary" target="_blank" href="<?php echo base_url(). 'courses/quiz/'.$quiz->id; ?>">Take Quiz</a>
    <?php endif; ?>
    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
</div>
</div>