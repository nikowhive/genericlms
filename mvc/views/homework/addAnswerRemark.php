<form id="studentRemarkForm">
		    <input type="hidden" id="homeworkanswerID" name="homeworkanswerID" value="<?php echo $homeworkanswer->homeworkanswerID; ?>"/> 
		    <div class="form-group">
			   <label>Comment:</label>
			   <textarea class="form-control" id="comment" require name="comment"><?php echo $homeworkanswer->remarks; ?></textarea>
			</div>
			<!-- <div class="form-group">
			   <label>Checked:</label>
 			   <input type="checkbox" name="status" id="status" require value="checked"/>
			</div> -->
</form>