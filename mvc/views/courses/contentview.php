<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h3 class="modal-title"><?= $this->data['content']->content_title ?></h3>
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
            <div class="col-md-12 ml-auto"><b>Coverage: </b><?= $this->data['content']->percentage_coverage ?>%</div>
        </div>
        <hr>
        <div>
            <div class="row">
                <div class="col-md-12 ml-auto"><?= $this->data['content']->chapter_content ?></div>
            </div>
        </div>
    </div>
</div>

<div class="modal-footer">
    <?= isset($view_url)?$view_url:''; ?>
    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
</div>
</div>