<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>

<div class="right-side--fullHeight  ">

    <div class="row w-100 ">

        <?php $this->load->view("components/course_menu"); ?>

        <div class="col-md-10" style="margin-top: 15px">

            <div class="box">
                <div class="box-header">
                    <h3 class="box-title"><i class="fa fa-sitemap"></i> Select Questions</h3>
                </div><!-- /.box-header -->
                <!-- form start -->
                <div class="box-body">
                    <div class="row">
                        <div class="col-sm-10">
                            <div class="page-header">
                                <a href="<?php echo base_url('question_bank/add/' . $quiz_id . '/' . $coursechapter_id . '?course=' . $course_id) ?>">
                                    <i class="fa fa-plus"></i>
                                    Add a Question
                                </a>
                            </div>
                            <form class="form-horizontal" role="form" method="post">
                                <table class="table table-striped table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th class="col-sm-1">Checkboxes</th>
                                            <th class="col-sm-1"><?= $this->lang->line('question_bank_level') ?></th>
                                            <th class="col-sm-3"><?= $this->lang->line('question_bank_question') ?></th>
                                            <th class="col-sm-1"><?= $this->lang->line('question_bank_group') ?></th>
                                            <th class="col-sm-1"><?= $this->lang->line('question_bank_type') ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if ($chapter_quizzes) {
                                            $i = 1;
                                            foreach ($chapter_quizzes as $quiz) { ?>
                                                <tr>
                                                    <td>
                                                        <div class="checkbox">
                                                            <label>
                                                               <input type="checkbox" name="quizzes[]" value="<?php echo $quiz->questionBankID ?>" <?php echo set_checkbox('quizzes[]', $quiz->questionBankID); ?> <?php echo (($this->coursequiz_m->get_bycoursechapter($quiz->questionBankID, $quiz_id)) ? 'checked' : ''); ?>>
                                                            </label>
                                                        </div>
                                                    </td>

                                                    <td data-title="<?= $this->lang->line('question_bank_level') ?>">
                                                        <?= isset($levels[$quiz->levelID]) ? $levels[$quiz->levelID]->name : ''; ?>
                                                    </td>
                                                    <td data-title="<?= $this->lang->line('question_bank_question') ?>">
                                                        <p>
                                                            <?php
                                                            if (strlen($quiz->question) > 60)
                                                                echo substr(strip_tags($quiz->question), 0, 60) . "...";
                                                            else
                                                                echo strip_tags($quiz->question);
                                                            ?>
                                                        </p>

                                                    </td>
                                                    <td data-title="<?= $this->lang->line('question_bank_group') ?>">
                                                        <?= isset($groups[$quiz->groupID]) ? $groups[$quiz->groupID]->title : ''; ?>
                                                    </td>
                                                    <td data-title="<?= $this->lang->line('question_bank_type') ?>">
                                                        <?= isset($types[$quiz->type_id]) ? $types[$quiz->type_id]->name : ''; ?>
                                                    </td>
                                                </tr>
                                            <?php $i++;
                                            }
                                        } else { ?>
                                            <tr>There are no question related to the quizzes</tr>
                                        <?php
                                        } ?>
                                    </tbody>
                                </table>
                                <div class="form-group" style="float: left; margin-top: 10px;">
                                    <div class=" col-sm-8">
                                        <input type="submit" class="btn btn-success" value="Add">
                                    </div>
                                </div>
                        </div>
                        </form>

                    </div>
                    <?php if ($siteinfos->note == 1) { ?>
                        <div class="callout callout-danger" style="width: fit-content;">
                            <p><b>Note:</b> Create questions before adding it to the quiz.</p>
                        </div>
                    <?php } ?>
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

    

    tinymce.init({
        selector: '#chapter_content',
        width: 600,
        height: 300,
        plugins: [
        'advlist autolink link image lists charmap print preview hr anchor pagebreak',
        'searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking',
        'table emoticons template powerpaste help tiny_mce_wiris '
        ],
        toolbar: 'undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | ' +
        'bullist numlist outdent indent | link image | print preview media fullpage | ' +
        'forecolor backcolor emoticons | help tiny_mce_wiris_formulaEditor | tiny_mce_wiris_formulaEditorChemistry',
        powerpaste_allow_local_images: true,
        powerpaste_word_import: 'prompt',
        powerpaste_html_import: 'prompt',
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

</script>