<div class="header-top-area hidden-xs">
        <div class="container">
            <div class="row">
                <div class="col-sm-6">
                    <?php if($this->setting_m->get_setting_where('phone') && $this->setting_m->get_setting_where('phone')->value != '') { ?>
                        <span><i class="fa fa-phone"></i><?php echo $this->setting_m->get_setting_where('phone')->value; ?></span>
                    <?php } ?>
                    <?php if($this->setting_m->get_setting_where('email') && $this->setting_m->get_setting_where('email')->value != '') { ?>
                        <a href="#"><i class="fa fa-envelope-o"></i><?php echo $this->setting_m->get_setting_where('email')->value; ?></a>
                    <?php } ?>
                </div>
                <div class="col-sm-6 text-right">
                    <ul>
                        <!-- <li style="padding-right: 5px;"><a style="border: solid; padding: 4px 10px 4px 10px; border-width: 1px; border-radius: 4px;" href="{{ base_url('frontend/page/admission') }}">Admission</a></li>
                        <li style="padding-right: 5px;">
                                <a style="border: solid; padding: 4px 10px 4px 10px; border-width: 1px; border-radius: 4px;" href="{{ base_url('entrance/index') }}">
                                    Online Entrance
                                </a>
                        </li> -->
                        <li><a href="{{ frontendData::get_frontend('facebook') }}"><i class="fa fa-facebook"></i></a></li>
                        <li><a href="{{ frontendData::get_frontend('twitter') }}"><i class="fa fa-twitter"></i></a></li>
                        <li><a href="{{ frontendData::get_frontend('linkedin') }}"><i class="fa fa-linkedin"></i></a></li>
                        <li><a href="{{ frontendData::get_frontend('youtube') }}"><i class="fa fa-youtube"></i></a></li>
                        <li><a href="{{ frontendData::get_frontend('google') }}"><i class="fa fa-google-plus"></i></a></li>
                        <li style="padding-right: 5px;"><a style="border: solid; padding: 4px 10px 4px 10px; border-width: 1px; border-radius: 4px;" href="{{ base_url('signin/index') }}">Login</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>