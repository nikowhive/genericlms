<form action="" method="POST" id="commentForm">
    <div class="modal-body">
        <input type="hidden" name="commentID" value="<?php echo $commentID; ?>"/>
        <textarea name="comment" class="form-control"><?php echo $comment; ?></textarea>   
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" id="commentBtn" class="btn btn-primary">Save changes</button>
    </div>
</form>  