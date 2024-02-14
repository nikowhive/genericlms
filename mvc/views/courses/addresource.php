<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>

<div class="right-side--fullHeight  ">

  <div class="row w-100 ">

    <?php $this->load->view("components/course_menu"); ?>

    <div class="course-content">
      <div class="container container--sm">

        <div class="box" style="margin-top: 15px">
          <div class="box-header">
            <h3 class="box-title"><i class="fa fa-sitemap"></i> Add Content</h3>
          </div><!-- /.box-header -->
          <!-- form start -->
          <div class="box-body">
            <form class=" " role="form" method="post" enctype="multipart/form-data">
              <div id="editorContainer"></div>
              <?php
              if (form_error('content_title'))
                echo "<div class='form-group has-error' >";
              else
                echo "<div class='form-group' >";
              ?>

              <div class='form-group'>
                <div class="md-form">
                  <label for="form1">Title</label>
                  <input type="text" class="form-control" id="content_title" name="content_title" value="<?= set_value('content_title'); ?>">

                  <span class="text-danger error">
                    <?php echo form_error('content_title'); ?>
                  </span>
                </div>
              </div>
              </div>

              <?php
              if (form_error('chapter_content'))
                echo "<div class='form-group has-error' >";
              else
                echo "<div class='form-group' >";
              ?>

              <div class='form-group'>
                <label class="form-label">Chapter Content </label>
                <textarea class="form-control" id="chapter_content" name="chapter_content"><?= set_value('chapter_content') ?></textarea>
                <span class="text-danger error">
                  <?php echo form_error('chapter_content'); ?>
                </span>

              </div>
          </div>

          <?php
          if (form_error('percentage_coverage'))
            echo "<div class='form-group has-error' >";
          else
            echo "<div class='form-group' >";
          ?>
          <div class='form-group'>
            <div class="md-form">
              <input type="number" style="resize:none;" class="form-control" id="percentage_coverage" name="percentage_coverage" value="<?= set_value('percentage_coverage'); ?>">
              <label for="percentage_coverage"> Percentage coverage</label>

              <span class="text-danger error">
                <?php echo form_error('percentage_coverage'); ?>
              </span>
            </div>

          </div>


          <input type="submit" class="btn btn-success" value="Add">
          <a href="<?= $this->agent->referrer(); ?>" class="btn btn-default">Cancel</a>

        </div>
      </div>
      </form>
    </div>
  </div>
</div>
</div>
</div>
</div>
<script>
  $(".select2").select2({
    placeholder: "",
    maximumSelectionSize: 6
  });
  // CKEDITOR.replace('chapter_content');
</script>
<script type="text/javascript">

  tinymce.init({
    selector: '#chapter_content',
    width: 600,
    height: 300,
    plugins: [
      'advlist autolink link image lists charmap print preview hr anchor pagebreak',
      'searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking',
      'table emoticons template powerpaste help tiny_mce_wiris'
    ],
    toolbar: 'undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | ' +
      'bullist numlist outdent indent | link image | print preview media fullpage | ' +
      'forecolor backcolor emoticons | help tiny_mce_wiris_formulaEditor | tiny_mce_wiris_formulaEditorChemistry',
    powerpaste_allow_local_images: true,
    powerpaste_word_import: 'prompt',
    powerpaste_html_import: 'prompt',
    menu: {
      favs: {
        title: 'My Favorites',
        items: 'code visualaid | searchreplace | emoticons'
      }
    },
    automatic_uploads: true,
    relative_urls: false,
    remove_script_host: false,
    /*
    URL of our upload handler (for more details check: https://www.tiny.cloud/docs/configure/file-image-upload/#images_upload_url)
    images_upload_url: 'postAcceptor.php',
    here we add custom filepicker only to Image dialog
    */
    images_upload_url: '<?= base_url('courses/Uploadimages') ?>',
    file_picker_types: 'image',
    /* and here's our custom image picker*/
    file_picker_callback: function(cb, value, meta) {
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

      input.onchange = function() {
        var file = this.files[0];

        var reader = new FileReader();
        reader.onload = function() {
          /*
            Note: Now we need to register the blob in TinyMCEs image blob
            registry. In the next release this part hopefully won't be
            necessary, as we are looking to handle it internally.
          */
          var id = 'blobid' + (new Date()).getTime();
          var blobCache = tinymce.activeEditor.editorUpload.blobCache;
          var base64 = reader.result.split(',')[1];
          var blobInfo = blobCache.create(id, file, base64);
          blobCache.add(blobInfo);

          /* call the callback and populate the Title field with the file name */
          cb(blobInfo.blobUri(), {
            title: file.name
          });
        };
        reader.readAsDataURL(file);
      };

      input.click();
    },
    menubar: 'favs file edit view insert format tools table help',
    content_css: 'css/content.css'
  });
</script>