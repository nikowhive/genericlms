$('document').ready(function() {
	'use strict';
	$('.select2').select2();

	$('.datetimepicker').datetimepicker({
        format: 'DD-MM-YYYY hh:mm A'
    });

	let projectUrl = function() {
		let re = new RegExp(/^.*\//);
	    let url = re.exec(window.location.href);
	    url = url[0].replace('edit/', '');
	    return url;
	}

	$('#classesId').on('change', function() {
		let classesId = $(this).val();
	    if(classesId === '0') {
	        $('#subjectId').val(0);
	        $('#sectionId').val(0);
	    } else {
	    	$.ajax({
	            type: 'POST',
	            url: projectUrl() + 'sectioncall',
	            data: "id=" + classesId,
	            dataType: "html",
	            success: function(data) {
	               $('#sectionId').html(data);
	            }
	        });
	    	
	        $.ajax({
	            type: 'POST',
	            url: projectUrl() + 'subjectcall',
	            data: "id=" + classesId,
	            dataType: "html",
	            success: function(data) {
	               $('#subjectId').html(data);
	            }
	        });	        
	    }
	});
});



