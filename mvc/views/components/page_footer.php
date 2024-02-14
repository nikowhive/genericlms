</div>

<!-- add course modal starts -->
<div class="modal fade" tabindex="-1" role="dialog" id="viewmodal">
<div class="modal-dialog" role="document">
<div class="modal-content" id="view_ajax_modal_content">
    
</div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- add course modal ends -->

<div id="feedModal" class="modal">
<span class="closeBtn">&times;</span>
<img class="modal-content" id="img01">
<div id="caption"></div>
</div>



<script>
// Get the modal
var modal = document.getElementById("feedModal");

// Get the image and insert it inside the modal - use its "alt" text as a caption
var img = $('.myImg');
var modalImg = document.getElementById("img01");
var captionText = document.getElementById("caption");

img.click(function(){
modal.style.display = "block";
modalImg.src = $(this).data('img');
captionText.innerHTML = this.alt;
});

// Get the <span> element that closes the modal
var span = document.getElementsByClassName("closeBtn")[0];

// When the user clicks on <span> (x), close the modal
span.onclick = function() { 
modal.style.display = "none";
}
</script>

<script type="text/javascript" src="<?php echo base_url('main.js'); ?>"></script>
<!-- <script type="text/javascript" src="<?php echo base_url('assets/inilabs/mdb-free.js'); ?>"></script> -->
<script type="text/javascript" src="<?php echo base_url('assets/bootstrap/bootstrap.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/inilabs/mdb-old/mdb.js'); ?>"></script>
<!-- <script type="text/javascript" src="<?php echo base_url('assets/inilabs/mdb-445/js/mdb.min.js'); ?>"></script> -->
<!-- <script type="text/javascript" src="<?php echo base_url('assets/inilabs/forms-free.min.js'); ?>"></script> -->
<!-- <script type="text/javascript" src="<?php echo base_url('assets/inilabs/material-select.min.js'); ?>"></script> -->
<!-- <script type="text/javascript" src="<?php echo base_url('assets/inilabs/dropdown.min.js'); ?>"></script> -->
<!-- <script type="text/javascript" src="<?php echo base_url('assets/inilabs/material-select-view-renderer.min.js'); ?>"></script> -->
<script type="text/javascript" src="<?php echo base_url('assets/inilabs/easyautocomplete/jquery.easy-autocomplete.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/inilabs/style.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/datatables/tools/jquery.dataTables.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/datatables/tools/dataTables.buttons.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/datatables/tools/jszip.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/datatables/tools/pdfmake.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/datatables/tools/vfs_fonts.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/datatables/tools/buttons.html5.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/datatables/dataTables.bootstrap.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/inilabs/inilabs.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/app.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/courses.js'); ?>"></script>

<?php

$cont = $this->uri->segment(1);
$method = $this->uri->segment(2);

if(($cont == 'event' AND ($method == '' OR $method == 'index')) OR ($cont == 'holiday' AND ($method == '' OR $method == 'index')) OR ($cont == 'notice' AND ($method == '' OR $method == 'index'))OR ($cont == 'feed' AND ($method == '' OR $method == 'index'))){ ?>
    <script type="text/javascript" src="<?php echo base_url('assets/inilabs/fb-img-grid/images-grid.js'); ?>"></script>

<?php }

?>

<script type="text/javascript">

tinymce.init({
        selector: '#description',
        width: 600,
        height: 300,
        plugins: [
        'advlist autolink link image lists charmap print preview hr anchor pagebreak',
        'searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking',
        'table emoticons template paste help tiny_mce_wiris '
        ],
        toolbar: 'undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | ' +
        'bullist numlist outdent indent | link image | print preview media fullpage | ' +
        'forecolor backcolor emoticons | help tiny_mce_wiris_formulaEditor | tiny_mce_wiris_formulaEditorChemistry',
        menu: {
        favs: {title: 'My Favorites', items: 'code visualaid | searchreplace | emoticons'}
        },
        automatic_uploads: true,
        relative_urls: false,
        remove_script_host: false,
          /*
            URL of our upload handler (for more details check: https://www.tiny.cloud/docs/configure/file-image-upload/#images_upload_url)
            images_upload_url: 'postAcceptor.php',
            here we add custom filepicker only to Image dialog
          */
          file_picker_types: 'image',
          /* and here's our custom image picker*/
          file_picker_callback: function (cb, value, meta) {
            var input = document.createElement('input');
            input.setAttribute('type', 'file');
            input.setAttribute('accept', 'image/*');

            /*
              Note: In modern browsers input[type="file"] is functional without
              even adding it to the DOM, but that might not be the case in some older
              or quirky browsers like IE, so you might want to add it to the DOM
              just in case, and visually hide it. And do not forget do remove it
              once you do not need it anymore.
            */

            input.onchange = function () {
              var file = this.files[0];

              var reader = new FileReader();
              reader.onload = function () {
                /*
                  Note: Now we need to register the blob in TinyMCEs image blob
                  registry. In the next release this part hopefully won't be
                  necessary, as we are looking to handle it internally.
                */
                var id = 'blobid' + (new Date()).getTime();
                var blobCache =  tinymce.activeEditor.editorUpload.blobCache;
                var base64 = reader.result.split(',')[1];
                var blobInfo = blobCache.create(id, file, base64);
                blobCache.add(blobInfo);

                /* call the callback and populate the Title field with the file name */
                cb(blobInfo.blobUri(), { title: file.name });
              };
              reader.readAsDataURL(file);
            };

            input.click();
          },
        menubar: 'favs file edit view insert format tools table help',
        content_css: 'css/content.css'
    });

  $(document).ready(function () {
    $(document).ajaxStart(function () {
      $("#loading").show();
    }).ajaxStop(function () {
      $("#loading").hide();
    });
  });

  $(document).ready(function () {
    $('#example3, #example1, #example2').DataTable({
      dom : 'Bfrtip',
      buttons : [
        'copyHtml5',
        'excelHtml5',
        'csvHtml5',
        'pdfHtml5'
      ],
      search : false
    });
  });
</script>

<script type="text/javascript">
  $(function () {
    $("#withoutBtn").dataTable();
  });
</script>

<?php if ($this->session->flashdata('success')): ?>
    <script type="text/javascript">
      toastr[ "success" ]("<?=$this->session->flashdata('success');?>");
      toastr.options = {
        "closeButton" : true,
        "debug" : false,
        "newestOnTop" : false,
        "progressBar" : false,
        "positionClass" : "toast-top-right",
        "preventDuplicates" : false,
        "onclick" : null,
        "showDuration" : "500",
        "hideDuration" : "500",
        "timeOut" : "5000",
        "extendedTimeOut" : "1000",
        "showEasing" : "swing",
        "hideEasing" : "linear",
        "showMethod" : "fadeIn",
        "hideMethod" : "fadeOut"
      }
    </script>
<?php endif ?>
<?php if ($this->session->flashdata('error')): ?>
    <script type="text/javascript">
      toastr[ "error" ]("<?=$this->session->flashdata('error');?>");
      toastr.options = {
        "closeButton" : true,
        "debug" : false,
        "newestOnTop" : false,
        "progressBar" : false,
        "positionClass" : "toast-top-right",
        "preventDuplicates" : false,
        "onclick" : null,
        "showDuration" : "500",
        "hideDuration" : "500",
        "timeOut" : "5000",
        "extendedTimeOut" : "1000",
        "showEasing" : "swing",
        "hideEasing" : "linear",
        "showMethod" : "fadeIn",
        "hideMethod" : "fadeOut"
      }
    </script>
<?php endif ?>

<?php
    if ( isset($footerassets) ) {
        foreach ( $footerassets as $assetstype => $footerasset ) {
            if ( $assetstype == 'css' ) {
                if ( customCompute($footerasset) ) {
                    foreach ( $footerasset as $keycss => $css ) {
                        echo '<link rel="stylesheet" href="' . base_url($css) . '">' . "\n";
                    }
                }
            } elseif ( $assetstype == 'js' ) {
                if ( customCompute($footerasset) ) {
                    foreach ( $footerasset as $keyjs => $js ) {
                        echo '<script type="text/javascript" src="' . base_url($js) . '"></script>' . "\n";
                    }
                }
            }
        }
    }
?>

<script type="text/javascript">
    $("ul.sidebar-menu li").each(function() {
        if($(this).attr('class') === 'active') {
            $(this).parents('li').addClass('active');
        }
    });

    $(document).ready(function () {

       $('#msgnotification').click(function(){
           
          $.ajax({
            type : 'GET',
            dataType : "html",
            async : false,
            url : "<?=base_url('alert/conversation')?>",
            success : function (data) {
              $(".my-push-conversation-list").html(data);
              var alertNumber = 0;
              $('.my-push-conversation-list li').each(function () {
                alertNumber++;
              });
              if (alertNumber > 0) {
                $('.my-push-conversation-ul').removeAttr('style');
                $('.my-push-conversation-a').append('<span class="label label-danger"><lable class="alert-image">' + alertNumber + '</lable> </span>');
                $('.my-push-conversation-number').html('<?=$this->lang->line("la_fs") . " "?>' + alertNumber + ' messages');
              } else {
                $('.my-push-conversation-ul').remove();
              }
            }
          });

       });
      setTimeout(function () {
        $.ajax({
          type : 'GET',
          dataType : "html",
          async : false,
          url : "<?=base_url('alert/conversation_count')?>",
          success : function (alertNumber) {
              $('.my-push-conversation-a').append('<span class="label label-danger"><lable class="alert-image">' + alertNumber + '</lable> </span>');
          }
        });
      }, 5000);
    });
</script>
</body>
</html>


<script>
$( document ).ready(function() {
if(localStorage.menu == 1) {
if( screen.width > 768 ) {
    $(".sidebar-offcanvas").addClass("collapse-left");
    $(".right-side").addClass("strech");
    $(document.body).addClass('menu-toggle');
}
}

if(localStorage.submenu == 1) {
$(".treeview-menu").addClass("collapse-submenu");
}

if( screen.width > 768 ) {
$("#result").hide();
} else {
$("#result").show();
}

// permission update

$.ajax({
    type: 'GET',
    url: "<?=base_url('permission/updateUserPermissions/')?>",
    dataType: "html",
    success: function (data) {
      console.log(data);
    }
});

})

$(document).ready(function(){


 // doUnload();
  // function doUnload(){
  //    if (window.event.clientX < 0 && window.event.clientY < 0){
  //     $.ajax({
  //             url: "<?//=base_url('signin/ajaxSignout/')?>",
  //             type: 'POST',
  //             datatype: 'json',
  //             success: function(data) {
  //               alert("Successful");
  //             }
  //           });
  //    }else{
  //      alert("Window refreshed");
  //    }
  //  }

  // $(window).bind('unload', function(){
  //   $.ajax({
  //       url: "<?//=base_url('signin/ajaxSignout/')?>",
  //       type: 'POST',
  //       datatype: 'json',
  //       success: function(data) {
  //         alert("Successful");
  //       }
  //     });
  //     return "Do you really want to close?";
  //   };



    // window.addEventListener('beforeunload', function (e) {
    //         e.preventDefault();
    //         if (window.event.clientX < 0 && window.event.clientY < 0){
    //         $.ajax({
    //           url: "<?//=base_url('signin/ajaxSignout/')?>",
    //           type: 'POST',
    //           datatype: 'json',
    //           success: function(data) {
    //             alert("Successful");
    //           }
    //         });

    //   return "Do you really want to close?";
    //       }
    // });
 

});


</script>

<!-- Global site tag (gtag.js) - Google Analytics -->

<script async src="https://www.googletagmanager.com/gtag/js?id=G-HSYBCK42ZW"></script>
<script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', 'G-Y0Q1QEWLK1');
</script>



