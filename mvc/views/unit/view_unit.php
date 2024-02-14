<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h3 class="modal-title">"<?= $this->data['unit']->unit_name ?>"</h3>
</div>
<form class="" role="form" method="post" id="edit-unit" action="<?= base_url('unit/ajaxEditUnit/').$unit->id.'?course='.$this->data['course_id'] ;?>">
    <div class="modal-body">


        <div class="form-group ">
            <div class="md-form md-form--select">
                <input type="text" class="form-control" id="classesID" name="classes_name" value="<?= $this->data["classes"]->classes ?>" readOnly>
                <input type="hidden" name="classesID" value="<?= $this->data["classes"]->classesID ?>">
                <label for="" class="mdb-main-label">Select Class</label>
                <span class="text-danger error">
                    <p id="class-error"></p>
                </span>
            </div>
        </div>


        <div class="form-group">
            <div class="md-form md-form--select">
                <input type="text" id="" name="subject_name" value="<?=$this->data['subjects']->subject ?>" readOnly class="form-control">
                <input type="hidden" name="subject_id" id="subject_id" value="<?= $this->data['subjects']->subjectID ?>">
                <label for="" class="mdb-main-label">Select Subject</label>
                <span class="text-danger error">
                    <p id="chapter-error"></p>
                </span>
            </div>
        </div>

        <div class="form-group">
            <div class="md-form">
                <label for="unit_name" class="active">Unit name</label>
                <input type="text" class="form-control" id="unit_name" name="unit_name" value="<?= $this->data['unit']->unit_name  ?>">

                <span class="text-danger error">
                    <p id="unit-error3"></p>
                </span>
            </div>
        </div>

        <div class="form-group">
            <div class="md-form">
                <label for="unit_code" class="active">Unit code</label>
                <input type="text" class="form-control" id="unit_code" name="unit_code" value="<?= $this->data['unit']->unit_code  ?>">

                <span class="text-danger error">
                    <p id="unit-code-error3"></p>
                </span>
            </div>
        </div>


        <input type="hidden" id="ajax-get-chapter-url" value="<?php echo base_url() ?>chapter/ajaxGetChaptersFromUnitId">
    </div>
    <div class="modal-footer">
        <input type="submit" id="" class="btn btn-primary" value="Update">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
    </div>
</form>
</div>

<script>
$('body').on('submit', '#edit-unit', function(e) {
        e.preventDefault();
        var frm = $('#edit-unit');
        $('#unit-error3').text('');
        $('#unit-code-error3').text('');
        var actionurl = e.currentTarget.action;
       
        $.ajax({
            url: actionurl,
            method: 'post',
            data: frm.serialize(),
            dataType: 'html',
            success: function(res) {
                var response = jQuery.parseJSON(res);
                console.log(response);
                if (response.status) {
                    if (response.render) {
                        toastr["success"](response.message)
                        location.reload();

                    } else {
                        $('#unit-error3').html(response.unit_error);
                        $('#unit-code-error3').html(response.unit_code_error);
                    }
                   
                }

            }
        });


    });
</script>