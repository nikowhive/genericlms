<div class="right-side--fullHeight  ">
    <div class="row w-100 ">
        <?php $this->load->view("components/course_menu"); ?>
        <div class="course-content">
            <div class="container container--sm">
                <header class="pg-header mt-4">
                    <h1 class="pg-title">
                        <div><small>Course</small></div>
                        Edit Links
                    </h1>
                </header>
                <div class="card card--spaced">
                    <!-- /.box-header -->
                    <!-- form start -->
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <form name="frm" method="post" onsubmit="return validatelink(this);" enctype="multipart/form-data">
                                    <input type="hidden" name="pgaction">
                                    <!--  <?php if ($GLOBALS['msglink']) {
                                                echo '<p class="err">' . $GLOBALS['msglink'] . '</p><br>';
                                            } ?> -->
                                    <h4>Edit links</h4>
                                    <div id="dvFile1 ">
                                        <div id="clone" class="">
                                            <div class="increment-block row mb-0 increment-block--url ">

                                                <div class="col-lg-6">
                                                    <div class="md-form">
                                                        <label>URL</label>
                                                        <input type="text" class="form-control" placeholder="https://" name="item_link" value="<?php echo $link->courselink; ?>" required>
                                                    </div>
                                                </div>
                                                <div class="namepanel1 col-lg-4">
                                                    <div class="md-form--select md-form">
                                                        <select name="type" class="mdb-select">

                                                            <option <?= ($link->type == "Youtube" ? 'selected=""' : '') ?> value="Youtube">Youtube</option>
                                                            <option <?= ($link->type == "Image" ? 'selected=""' : '') ?>value="Image">Image</option>
                                                            <option <?= ($link->type == "Drive" ? 'selected=""' : '') ?> value="Drive">Drive</option>
                                                            <option <?= ($link->type == "Pdf" ? 'selected=""' : '') ?>value="Pdf">Pdf</option>
                                                            <option <?= ($link->type == "Others" ? 'selected=""' : '') ?> value="Others">Others</option>
                                                        </select>
                                                        <label class="mdb-main-label">Choose Types</label>
                                                    </div>

                                                </div>
                                                <div class="col-lg-2 align-self-lg-center">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <input type="submit" class="btn btn-success" value="Update link">
                                    <a href="<?= $this->agent->referrer(); ?>" class="btn btn-default">Cancel</a>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script language="javascript">
    function _add_morelink() {
        var txt = "<div class=\"increment-block row increment-block--url\"><div class=\"uploadpanel1 col-lg-4\"><input type=\"text\" name=\"item_link[]\" class=\"form-control\" placeholder=\"https://\"></div><div class=\"namepanel1 col-lg-2\"><select name=\"type[]\" required class=\"form-control\"><option value=\"\">Choose types</option><option value=\"Youtube\">Youtube</option><option value=\"Image\">Image<option value=\"Drive\">Drive</option><option value=\"Pdf\">Pdf</option><option value=\"Others\">Others</option></select></div><div class=\"col-lg-2\"><button type=\"button\" class=\"remove btn btn-danger\" value=\"-\" onclick=\"remove_inputlink(this);\"/><i class=\"fa fa-times\"></i></div></div>";
        document.getElementById("dvFile1").innerHTML += txt;
    }

    function remove_inputlink(e) {
        e.parentNode.parentNode.parentNode.removeChild(e.parentNode.parentNode);
    }

    function validatelink(f) {
        var chkFlg = false;
        for (var i = 0; i < f.length; i++) {
            if (f.elements[i].type == "text" && f.elements[i].value != "") {
                chkFlg = true;
            }

        }
        if (!chkFlg) {
            alert('Please Add at least one link');
            return false;
        }
        f.pgaction.value = 'addlink';
        return true;
    }
</script>
<script language="javascript">
    function remove_input(e) {
        e.parentNode.parentNode.parentNode.removeChild(e.parentNode.parentNode);
    }

    function _add_more() {
        var txt = "<div class=\"increment-block row\"><div class=\"uploadpanel col-lg-4\"><input type=\"file\" name=\"item_file[]\" class=\"form-control\"></div><div class=\"namepanel col-lg-2\"><input type=\"text\" name=\"file_name[]\" class=\"form-control\" required></div><div class=\"col-lg-2\"><button type=\"button\" class=\"remove btn btn-danger\"  onclick=\"remove_input(this);\"><i class=\"fa fa-times\"></i></button></div></div>";
        document.getElementById("dvFile").innerHTML += txt;
    }

    function validate(f) {
        var chkFlg = false;
        for (var i = 0; i < f.length; i++) {
            if (f.elements[i].type == "file" && f.elements[i].value != "") {
                chkFlg = true;
            }

        }
        if (!chkFlg) {
            alert('Please browse/choose at least one file');
            return false;
        }
        f.pgaction.value = 'upload';
        return true;
    }
</script>