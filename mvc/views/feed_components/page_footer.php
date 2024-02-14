        </div>

        <!-- add course modal starts -->
<div class="modal fade" tabindex="-1" role="dialog" id="viewmodal">
    <div class="modal-dialog" role="document">
        <div class="modal-content" id="view_ajax_modal_content">
            
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- add course modal ends -->

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
              setTimeout(function () {
                $.ajax({
                  type : 'GET',
                  dataType : "html",
                  async : false,
                  url : "<?=base_url('alert/alert')?>",
                  success : function (data) {
                    $(".my-push-message-list").html(data);
                    var alertNumber = 0;
                    $('.my-push-message-list li').each(function () {
                      alertNumber++;
                    });
                    if (alertNumber > 0) {
                      $('.my-push-message-ul').removeAttr('style');
                      $('.my-push-message-a').append('<span class="label label-danger"><lable class="alert-image">' + alertNumber + '</lable> </span>');
                      $('.my-push-message-number').html('<?=$this->lang->line("la_fs") . " "?>' + alertNumber + '<?=" " . $this->lang->line("la_ls")?>');
                    } else {
                      $('.my-push-message-ul').remove();
                    }
                  }
                });
              }, 5000);
              setTimeout(function () {
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
                      $('.my-push-conversation-number').html('<?=$this->lang->line("la_fs") . " "?>' + alertNumber + 'messages');
                    } else {
                      $('.my-push-conversation-ul').remove();
                    }
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
  })

</script>

<!-- Global site tag (gtag.js) - Google Analytics -->

<script async src="https://www.googletagmanager.com/gtag/js?id=G-HSYBCK42ZW"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-HSYBCK42ZW');
</script>
