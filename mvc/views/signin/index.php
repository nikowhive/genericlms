
<div class="card" id="login-box">
    <div class="card-header">
        <h3 class="card-title"><?=$this->lang->line('signin')?></h3>
    </div>
     <div class="card-body">

   
    <form method="post">

        <!-- style="margin-top:40px;" -->

        <div class="body white-bg">
        <?php
            if($form_validation == "No"){
            } else {
                if(customCompute($form_validation)) {
                    echo "<div class=\"alert alert-danger alert-dismissable\">
                        <i class=\"fa fa-ban\"></i>
                        <button aria-hidden=\"true\" data-dismiss=\"alert\" class=\"close\" type=\"button\">×</button>
                        $form_validation
                    </div>";
                }
            }
            if($this->session->flashdata('reset_success')) {
                $message = $this->session->flashdata('reset_success');
                echo "<div class=\"alert alert-success alert-dismissable\">
                    <i class=\"fa fa-ban\"></i>
                    <button aria-hidden=\"true\" data-dismiss=\"alert\" class=\"close\" type=\"button\">×</button>
                    $message
                </div>";
            }
        ?>
            <div class="md-form mt-2">
                <label for="username">Username</label>
                <input class="form-control" id="username"  name="username" type="text" autofocus value="<?=set_value('username')?>">
            </div>
            <div class="md-form">
                <label for="">Password</label>
                <input class="form-control"   name="password" type="password">
            </div>
            <div class="md-form md-form--select">
                <?php
                    $array = array(
                         ''       => 'Select User Type',
                        'systemadmin'   => 'Admin',
                        'teacher' => 'Teacher',
                        'student' => 'Student',
                        'parents' => 'Parent',
                        'user'  => 'Other'
                    );
                     echo form_dropdown("userType", $array, set_value("userType"), "id='userType' class='mdb-select'");
                ?>
                <!-- <label class="mdb-main-label">User Type</label> -->
            </div>
            <div class="auth-sideinfos mb-3">
            <div class="form-check ">
                
                    <input type="checkbox" class="form-check-input" value="Remember Me" name="remember" id="rememberme">
                    <label class="form-check-label" for="rememberme">  Remember Me  </label>
               
            </div>
            <a href="<?=base_url('reset/index')?>"> Forgot Password?</a>
            </div>

            <?php if(isset($siteinfos->captcha_status) && $siteinfos->captcha_status == 0) { ?>
                <div class="form-group">
                    <?php echo $recaptcha['widget']; echo $recaptcha['script']; ?>
                </div>
            <?php } ?>
            
            <button type="submit" class="btn btn-lg btn-success btn-block"  > SIGN IN </button>
            
            </br>
            <div class="alert alert-info ml-0  " role="alert">
                If you face any issues while logging in. Please Contact at <?= $siteinfos->email ?>
            </div>
        </div>
    </form>
    </div>
</div>

 