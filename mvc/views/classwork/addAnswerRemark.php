<form id="studentRemarkForm">
		    <input type="hidden" id="classworkanswerID" name="classworkanswerID" value="<?php echo $classworkanswer->classworkanswerID; ?>"/> 
		    <div class="form-group">
			   <label>Comment:</label>
			   <textarea class="form-control" id="comment" require name="comment"><?php echo $classworkanswer->remarks; ?></textarea>
			</div>
			<!-- <div class="form-group">
			   <label>Checked:</label>
 			   <input type="checkbox" name="status" id="status" require value="checked"/>
			</div> -->
</form>