<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h3 class="modal-title"><?= $this->data['link']->type ?></h3>
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

        <p><b>Link</b></p>
        <div class="embed-responsive embed-responsive-16by9">
            <iframe class="embed-responsive-item" scrolling="no" width="560" height="315" src="<?= $url1 ?>" allowfullscreen></iframe>
        </div>
    </div>
</div>
</div>
<div class="modal-footer">
    <a class="btn btn-primary" target="_blank" href="<?= $url1 ?>">Open Link</a>
    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
</div>
</div>