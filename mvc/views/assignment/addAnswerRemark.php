<form id="studentRemarkForm">
		    <input type="hidden" id="assignmentanswerID" name="assignmentanswerID" value="<?php echo $assignmentanswer->assignmentanswerID; ?>"/> 
		    <div class="form-group">
			   <label>Comment:</label>
			   <textarea class="form-control" id="comment" require name="comment"><?php echo $assignmentanswer->remarks; ?></textarea>
			</div>
			<!-- <div class="form-group">
			   <label>Checked:</label>
 			   <input type="checkbox" name="status" id="status" require value="checked"/>
			</div> -->
</form>