<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h3 class="modal-title"><?= $this->data['attachments']->file_name ?></h3>
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

       
        <p><b>Attachment</b></p>
        <?php
        $allowed = array('gif', 'png', 'jpg');
        $file_extension = array('pdf', 'xlsx', 'docx', 'csv', 'doc', 'xls');
        $vdo_extension = array('mp4', 'mov', 'flv', 'avi');

        $filename = $this->data['attachments']->attachment;
        $ext = pathinfo($filename, PATHINFO_EXTENSION);

        if (in_array($ext, $allowed)) {
            echo '<img width="100% " height="300" src=' . base_url('uploads/images/') . $this->data['attachments']->attachment . '>';
        } elseif (in_array($ext, $file_extension)) {
            echo 'Please download this <b>' . $ext . '</b> file for preview.';
        } elseif (in_array($ext, $vdo_extension)) {
            echo '<video width="550" height="400" id="attachment_video" controls>
            <source src="' . base_url('uploads/images/') . $this->data['attachments']->attachment . '" type="video/mp4">
            </source>
        </video>';
        }
        ?>
    </div>
</div>
</div>
<div class="modal-footer">
    <a class="btn btn-primary" target="_blank" rel="noopener noreferrer" href="<?php echo base_url('uploads/images/') . $this->data['attachments']->attachment ?>"><?= in_array($ext, $file_extension) ? 'Download' : 'View'; ?></a>
    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
</div>
</div>