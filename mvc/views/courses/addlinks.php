<div class="right-side--fullHeight  ">
    <div class="row w-100 ">
        <?php $this->load->view("components/course_menu"); ?>
        <div class="course-content">
            <div class="container container--sm">
                <header class="pg-header mt-4">
                    <h1 class="pg-title">
                        <div><small>Course</small></div>
                        Add Links
                    </h1>
                </header>

                <div class="card card--spaced">
                    <!-- /.box-header -->
                    <!-- form start -->
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <form name="frm" method="post" enctype="multipart/form-data" id="myform">
                                    <input type="hidden" name="pgaction">
                                    <div class='form-group has-error'>
                                        <span id="item-link-error" class="text-danger"></span>

                                    </div>
                                    <?php if ($GLOBALS['msglink']) {
                                        echo '<p class="err">' . $GLOBALS['msglink'] . '</p><br>';
                                    } ?>
                                    <h4>Add Number of links</h4>
                                    <div id="dvFile1 ">

                                        <div class="clone">
                                            <div class="increment-block row mb-0 increment-block--url ">

                                                <div class="col-lg-6">
                                                    <div class="md-form">
                                                        <label>URL</label>
                                                        <input type="text" class="form-control" placeholder="https://" name="item_link[]" id="item_link">

                                                    </div>

                                                </div>
                                                <div class="namepanel1 col-lg-4">
                                                    <div class="md-form--select md-form">
                                                        <select name="type[]" class="mdb-select" required id="type">
                                                            <option value="" disabled="">Choose types</option>
                                                            <option value="Youtube">Youtube</option>
                                                            <option value="Image">Image</option>
                                                            <option value="Drive">Drive</option>
                                                            <option value="Pdf">Pdf</option>
                                                            <option value="Others">Others</option>
                                                        </select>
                                                        <label class="mdb-main-label">Choose Types</label>
                                                    </div>

                                                </div>
                                                <div class="col-lg-2 align-self-lg-center">
                                                    <button type="button" class="mb-xs mr-xs btn btn-success addmore" id="addmore"><i class="fa fa-plus"></i></button>

                                                    <button type="button" class="mb-xs mr-xs btn btn-danger deleteChild " onclick="remove_input(this);" value="Remove"><i class="fa fa-minus"></i></button>

                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <input type="submit" class="btn btn-success" value="Add link">
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

<script type="text/javascript">
    $('form#myform').submit(function(e) {
        e.preventDefault();
        $.ajax({
            url: '<?= base_url('courses/check_link') ?>',
            type: 'POST',
            data: $("#myform").serialize(),
            dataType: 'json',
            success: function(data) {
                console.log(data);
                if (data.error) {
                    if (data.item_link_error != '') {
                        $('#item-link-error').html(data.item_link_error);
                    } else {
                        $('#item-link-error').remove();
                    }


                } else {

                    $('#myform')[0].submit();
                }

            }
        });


    });
</script>

<script language="javascript">
    $(".deleteChild").addClass('hidden');
    $(".addmore").click(function(e) {
        $(".clone:last").after($(".clone:first").clone(true));
        $(".clone:last .deleteChild").removeClass('hidden');
        $(".clone:last #addmore").remove();
        $(".clone:last #item_link").val("");

        $('.mdb-select').material_select('destroy');
        $('.mdb-select').material_select();

    });


    function remove_input(e) {
       var thisId = e.closest('.clone').remove();
        console.log(thisId);

    }

    function _add_morelink() {
        var txt = "<div class=\"increment-block row increment-block--url\"><div class=\"uploadpanel1 col-lg-4\"><input type=\"text\" name=\"item_link[]\" class=\"form-control\" placeholder=\"https://\"></div><div class=\"namepanel1 col-lg-2\"><select name=\"type[]\" required class=\"form-control\"><option value=\"\">Choose types</option><option value=\"Youtube\">Youtube</option><option value=\"Image\">Image<option value=\"Drive\">Drive</option><option value=\"Pdf\">Pdf</option><option value=\"Others\">Others</option></select></div><div class=\"col-lg-2\"><button type=\"button\" class=\"remove btn btn-danger\" value=\"-\" onclick=\"remove_inputlink(this);\"/><i class=\"fa fa-times\"></i></div></div>";
        document.getElementById("dvFile1").innerHTML += txt;


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