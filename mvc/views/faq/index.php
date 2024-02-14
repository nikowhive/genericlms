<div class="right-side--fullHeight  ">

    <div class="row w-100 ">

        <?php $this->load->view("components/course_menu"); ?>
        <div class="course-content">
            <div class="container container--sm">
                <header class="pg-header mt-4">
                    <h1 class="pg-title">
                        FAQ
                    </h1>
                    <?php if ($siteinfos->school_year == $this->session->userdata('defaultschoolyearID') || $this->session->userdata('usertypeID') != 3) { ?>
                        <?php if (permissionChecker('faq_add')) { ?>
                            <a href="<?= base_url('faq/add')?>" class="btn-sm btn btn-success" title="Create new FAQ"><i class="fa fa-plus"></i> Create</a>
                        <?php } ?>
                    <?php } ?>
                </header>
                <div class="row">
                    <div class="col-md-4 mt-3 mt-lg-0">
                        <div class="md-form-block">
                            <div class="md-form--select md-form">
                                <select class="mdb-select" id="classesID">
                                    <option value="" selected>Select Classes</option>
                                    <?php foreach ($classes as $class) { ?>
                                    <option value="<?= $class->classesID ?>" <?php echo ($class->classesID == $classesID) ? 'selected' : ''; ?>><?= $class->classes ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                                    </br>


                <div class="sortable-list" id ="hide-table">
                    <ul id="unit" class="course-wrapper">
                        <?php if(isset($faq)&& !empty($faq)){ 
                            foreach ($faq as $f) { ?>
                            <li style="margin-bottom:20px;">
                                <div class="sortable-block sortable-blockunit">
                                    <div class="sortable-header">
                                        <div class="panned-icon"><i class="fa fa-question-circle" aria-hidden="true"></i></div>
                                        <h3 class="sortable-title">
                                            <?= $f->question  ?>
                                        </h3>
                                        </a>

                                    </div>
                                    <div class="sortable-actions">
                                        <?php if ($siteinfos->school_year == $this->session->userdata('defaultschoolyearID')) : ?>
                                            <div class="dropdown">
                                                <a href="#" class=" " data-toggle="dropdown"> ⋮</a>
                                                <ul id="menu2" class="dropdown-menu" aria-labelledby="drop5">
                                                    <?php if (permissionChecker('faq_edit')) : ?>
                                                        <li>
                                                            <a href="<?= base_url('faq/edit/' . $f->id) ?>">Edit FAQ</a>
                                                        </li>
                                                    <?php endif; ?>
                                                    <?php if (permissionChecker('faq_delete')) : ?>
                                                        <li>
                                                            <a onclick="return confirm('you are about to delete a record. This cannot be undone. are you sure?')" href="<?= base_url('faq/delete/' . $f->id) ?>">Delete FAQ</a>
                                                        </li>
                                                    <?php endif; ?>
                                                    <!-- <?php if (permissionChecker('faq_view')) : ?>
                                                        <li>
                                                            <a href="<?= base_url('faq/view/' . $f->id) ?>">View Submission</a>
                                                        </li>
                                                    <?php endif; ?> -->
                                                </ul>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </li>
                        <?php } } ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $('#classesID').change(function() {
        var classesID = $(this).val();
        if(classesID == 0) {
            $('#hide-table').show();
        } else {
            $('#hide-table').hide();
            $.ajax({
                type: 'POST',
                url: "<?=base_url('faq/faq_list')?>",
                data: "id=" + classesID,
                dataType: "html",
                success: function(data) {
                    window.location.href = data;
                }
            });
        }
    });
</script>
