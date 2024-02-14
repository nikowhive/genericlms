<div class="right-side--fullHeight  ">

    <div class="row w-100 ">

        <?php $this->load->view("components/course_menu"); ?>
        <div class="course-content">
            <div class="container container--sm">
                <header class="pg-header mt-4">
                    <h1 class="pg-title">
                       Enrolment
                    </h1>
                    <?php if ($siteinfos->school_year == $this->session->userdata('defaultschoolyearID') || $this->session->userdata('usertypeID') != 3) { ?>
                        <?php if (permissionChecker('enrollment_add')) { ?>
                            <a href="<?= base_url('enrollment/add') ?>" class="btn-sm btn btn-success" title="Create new enrolment"><i class="fa fa-plus"></i> Create</a>
                        <?php } ?>
                    <?php } ?>
                </header>
                <div class="row">
                    <div class="col-md-4 mt-3 mt-lg-0">
                        <div class="md-form-block">
                            <div class="md-form--select md-form">
                                <select class="mdb-select" id="classesID">
                                    <option value="" selected>Select Classes</option>
                                    <?php 
                                    foreach ($classes as $class) { ?>
                                    <option value="<?= $class->classesID ?>" <?php echo ($class->classesID == $classesID) ? 'selected' : ''; ?>><?= $class->classes ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                 </div>
                </br>

                <div class="sortable-list">
                    <ul id="unit" class="course-wrapper">
                        <?php
                        if(isset($enrollment) && !empty($enrollment)){
                            foreach ($enrollment as $enroll) {
                                $a = "01";
                                $time = strtotime($a."-".$enroll->from_month);
                                $month=date("F",$time);
                                $year=date("Y",$time);
                                $time1= strtotime($a."-".$enroll->to_month);
                                $month1=date("F",$time1);
                                $year1=date("Y",$time1); ?>
                                <li style="margin-bottom:20px;">
                                    <div class="sortable-block sortable-blockunit">
                                        <div class="sortable-header">
                                            <!-- <div class="panned-icon">⋮⋮ </div> -->
                                            <div class="panned-icon"><i class="fa fa-copy" aria-hidden="true"></i></div>
                                            <h3 class="sortable-title">

                                                <small>
                                                        <?=$month." ".$year." - ".$month1." ".$year1?>
                                                </small>
                                                <?= $enroll->title  ?>
                                            </h3>
                                            </a>

                                        </div>
                                        <div class="sortable-actions">
                                            <?php if ($siteinfos->school_year == $this->session->userdata('defaultschoolyearID')) : ?>
                                                <div class="dropdown">
                                                    <a href="#" class=" " data-toggle="dropdown"> ⋮</a>
                                                    <ul id="menu2" class="dropdown-menu" aria-labelledby="drop5">
                                                        <?php if (permissionChecker('enrollment_edit')) : ?>
                                                            <li>
                                                                <a href="<?= base_url('enrollment/edit/' . $enroll->id) ?>">Edit enrolment</a>
                                                            </li>
                                                        <?php endif; ?>
                                                        <?php if (permissionChecker('enrollment_delete')) : ?>
                                                            <li>
                                                                <a onclick="return confirm('you are about to delete a record. This cannot be undone. are you sure?')" href="<?= base_url('enrollment/delete/' . $enroll->id) ?>">Delete Enrolment</a>
                                                            </li>
                                                        <?php endif; ?>
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
    $(".select2").select2();
    $('#classesID').change(function() {
        var classesID = $(this).val();
        if(classesID == 0) {
            $('#sortable-list').show();
        } else {
            $('#sortable-list').hide();
            $.ajax({
                type: 'POST',
                url: "<?=base_url('enrollment/enrollment_list')?>",
                data: "id=" + classesID,
                dataType: "html",
                success: function(data) {
                    window.location.href = data;
                }
            });
        }
    });
</script>
