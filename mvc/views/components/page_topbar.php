<header class="main-header header">
    <nav class="navbar navbar-static-top" role="navigation">
        <div class="logo-wrapper">
            <a href="#" class="navbar-btn sidebar-toggle" data-toggle="offcanvas" role="button" id="sidebar-toggle">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </a>
                    <a href="javascript:;" class="close-icon-mobile js-search-box-toggler">
                    <img src="<?php echo base_url('assets/images/close-24px.svg'); ?>" alt="" >
                    </a>
                    <a href="<?php echo base_url('feed'); ?>" class="logo">
                    <?php if(customCompute($siteinfos)) { echo namesorting($siteinfos->sname, 50); } ?>
                                </a>
                </div>

                <div class="navbar-right">
                    <ul class="nav navbar-nav">
                        <!-- <li class="dropdown notifications-menu">
                            <a target="_blank" href="<?=base_url('frontend/index')?>" class="dropdown-toggle" data-toggle="tooltip" title="<?=$this->lang->line('menu_visit_site')?>" data-placement="bottom">
                                <i class="fa fa-globe"></i>
                            </a>
                        </li>-->
                        <?php if($userTypeID == 3){ ?>
                            <!-- <li>
                                <p class="logintimer">
                                    <i class="fa fa-clock-o"></i> 
                                    Timer: <span id="minutes">00</span>:<span id="seconds">00</span>
                                    <span id="stopTimer" class="btn btn-xs btn-danger" onclick="myStopFunction()">Stop</span> 
                                    <span id="startTimer" class="btn btn-xs btn-danger" onclick="timerstart1()" <?php echo $loginendtime?'':'disabled'; ?>>Start</span> 
                                </p>
                            </li>  -->
                        <?php } ?>

                        <!-- <div class="quiz-time">
                            <div class="timer">
                                <span id="minutes">00</span>:<span id="seconds">00</span>
                            </div>

                            <button href="#" id="show-explanation" class="button1" onclick="myStopFunction()">Stop</button>
                            <button href="#" id="show-explanation" class="button1" onclick="timerstart()">Start Again</button>
                            <button href="#" id="show-explanation" class="button1" onclick="myClearFunction();">Clear</button>
                        </div> -->
                      
                       <li class="navbar-nav--search">
                          <div class="search-box">
                                <input type="search" id="basics" 
                                placeholder="Type teacher or student or teacher or parents or admin or user by category or name or designation or registerNO to search..."
                                title="Type teacher or student or teacher or parents or admin or user by category or name or designation or registerNO to search..."
                                 name="serach" class="form-control" value="<?php echo isset($_REQUEST['text'])?$_REQUEST['text']:''; ?>">
                                <button type="submit" id="serachUser" class="btn btn-success">Search</button>
                            </div>
                            <a href="javascript:;" class="search-icon-mobile js-search-box-toggler"><i class="fa fa-search"></i></a>
                        </li>

                        <?php if(permissionChecker('schoolyear')) { funtopbarschoolyear($siteinfos, $topbarschoolyears); } ?>

                        <!-- <li class="dropdown messages-menu my-push-message">
                            <a href="#" class="dropdown-toggle my-push-message-a" data-toggle="dropdown" >
                                <i class="fa fa-bell-o" ></i>
                            </a>
                            <ul class="dropdown-menu my-push-message-ul" style="display:none">
                                <li class='header my-push-message-number'>
                                </li>
                                <li>
                                    <ul class="menu my-push-message-list">
                                    </ul>
                                </li>
                            </ul>
                        </li> -->
                        <li class="dropdown messages-menu my-push-conversation">
                            <a href="#" class="dropdown-toggle my-push-conversation-a" id="msgnotification" data-toggle="dropdown" >
                                <i class="fa fa-envelope"></i>
                            </a>
                            <ul class="dropdown-menu my-push-conversation-ul" style="display:none">
                                <li class='header my-push-conversation-number'>
                                </li>
                                <li>
                                    <ul class="menu my-push-conversation-list">
                                    </ul>
                                </li>
                            </ul>
                        </li>

                        <!-- <?php //if(isset($siteinfos->language_status) && $siteinfos->language_status == 0) { ?>
                            <li class="dropdown notifications-menu">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                    <img class="language-img" src="<?php 
                                   // $image = $this->session->userdata('lang'); 
                                   // echo base_url('uploads/language_image/'.$image.'.png');
                                     ?>" 
                                    /> 
                                    <span class="label label-warning">15</span>
                                </a>
                                <ul class="dropdown-menu">
                                    <li class="header"> <?//=$this->lang->line("language")?></li>
                                    <li>
                                        <ul class="menu">
                                            <li class="language" id="arabic">
                                                <a href="<?php //echo base_url('language/index/arabic')?>">
                                                    <div class="pull-left">
                                                        <img src="<?php //echo base_url('uploads/language_image/arabic.png'); ?>"/>
                                                    </div>
                                                    <h4>
                                                        Arabic
                                                        <?php //if($image == 'arabic') echo " <i class='glyphicon glyphicon-ok'></i>";  ?>
                                                    </h4>
                                                </a>
                                            </li>
                                            <li class="language" id="bengali">
                                                <a href="<?php //echo base_url('language/index/bengali')?>">
                                                    <div class="pull-left">
                                                        <img src="<?php //echo base_url('uploads/language_image/bengali.png'); ?>"/>
                                                    </div>
                                                    <h4>
                                                        Bengali
                                                        <?php //if($image == 'bengali') echo " <i class='glyphicon glyphicon-ok'></i>";  ?>
                                                    </h4>
                                                </a>
                                            </li>
                                            <li class="language" id="chinese">
                                                <a href="<?php //echo base_url('language/index/chinese')?>">
                                                    <div class="pull-left">
                                                        <img src="<?php //echo base_url('uploads/language_image/chinese.png'); ?>"/>
                                                    </div>
                                                    <h4>
                                                        Chinese
                                                        <?php //if($image == 'chinese') echo " <i class='glyphicon glyphicon-ok'></i>";  ?>
                                                    </h4>
                                                </a>
                                            </li>
                                            <li class="language" id="english">
                                                <a href="<?php //echo base_url('language/index/english')?>">
                                                    <div class="pull-left">
                                                        <img src="<?php //echo base_url('uploads/language_image/english.png'); ?>"/>
                                                    </div>
                                                    <h4>
                                                        English
                                                        <?php //if($image == 'english') echo " <i class='glyphicon glyphicon-ok'></i>";  ?>
                                                    </h4>
                                                </a>
                                            </li>
                                            <li class="language" id="french">
                                                <a href="<?php //echo base_url('language/index/french')?>">
                                                    <div class="pull-left">
                                                        <img src="<?php //echo base_url('uploads/language_image/french.png'); ?>"/>
                                                    </div>
                                                    <h4>
                                                        French
                                                        <?php //if($image == 'french') echo " <i class='glyphicon glyphicon-ok'></i>";  ?>
                                                    </h4>
                                                </a>
                                            </li>
                                            <li class="language" id="german">
                                                <a href="<?php //echo base_url('language/index/german')?>">
                                                    <div class="pull-left">
                                                        <img src="<?php //echo base_url('uploads/language_image/german.png'); ?>"/>
                                                    </div>
                                                    <h4>
                                                        German
                                                        <?php i//f($image == 'german') echo " <i class='glyphicon glyphicon-ok'></i>";  ?>
                                                    </h4>
                                                </a>
                                            </li>
                                            <li class="language" id="hindi">
                                                <a href="<?php //echo base_url('language/index/hindi')?>">
                                                    <div class="pull-left">
                                                        <img src="<?php //echo base_url('uploads/language_image/hindi.png'); ?>"/>
                                                    </div>
                                                    <h4>
                                                        Hindi
                                                        <?php //if($image == 'hindi') echo " <i class='glyphicon glyphicon-ok'></i>";  ?>
                                                    </h4>
                                                </a>
                                            </li>
                                            <li class="language" id="indonesian">
                                                <a href="<?php //echo base_url('language/index/indonesian')?>">
                                                    <div class="pull-left">
                                                        <img src="<?php //echo base_url('uploads/language_image/indonesian.png'); ?>"/>
                                                    </div>
                                                    <h4>
                                                        Indonesian
                                                        <?php //if($image == 'indonesian') echo " <i class='glyphicon glyphicon-ok'></i>";  ?>
                                                    </h4>
                                                </a>
                                            </li>
                                            <li class="language" id="italian">
                                                <a href="<?php //echo base_url('language/index/italian')?>">
                                                    <div class="pull-left">
                                                        <img src="<?php //echo base_url('uploads/language_image/italian.png'); ?>"/>
                                                    </div>
                                                    <h4>
                                                        Italian
                                                        <?php //if($image == 'italian') echo " <i class='glyphicon glyphicon-ok'></i>";  ?>
                                                    </h4>
                                                </a>
                                            </li>
                                            <li class="language" id="portuguese">
                                                <a href="<?php //echo base_url('language/index/portuguese')?>">
                                                    <div class="pull-left">
                                                        <img src="<?php //echo base_url('uploads/language_image/portuguese.png'); ?>"/>
                                                    </div>
                                                    <h4>
                                                        Portuguese
                                                        <?php //if($image == 'portuguese') echo " <i class='glyphicon glyphicon-ok'></i>";  ?>
                                                    </h4>
                                                </a>
                                            </li>
                                            <li class="language" id="romanian">
                                                <a href="<?php //echo base_url('language/index/romanian')?>">
                                                    <div class="pull-left">
                                                        <img src="<?php //echo base_url('uploads/language_image/romanian.png'); ?>"/>
                                                    </div>
                                                    <h4>
                                                        Romanian
                                                        <?php //if($image == 'romanian') echo " <i class='glyphicon glyphicon-ok'></i>";  ?>
                                                    </h4>
                                                </a>
                                            </li>
                                            <li class="language" id="russian">
                                                <a href="<?php //echo base_url('language/index/russian')?>">
                                                    <div class="pull-left">
                                                        <img src="<?php //echo base_url('uploads/language_image/russian.png'); ?>"/>
                                                    </div>
                                                    <h4>
                                                        Russian
                                                        <?php //if($image == 'russian') echo " <i class='glyphicon glyphicon-ok'></i>";  ?>
                                                    </h4>
                                                </a>
                                            </li>
                                            <li class="language" id="spanish">
                                                <a href="<?php //echo base_url('language/index/spanish')?>">
                                                    <div class="pull-left">
                                                        <img src="<?php //echo base_url('uploads/language_image/spanish.png'); ?>"/>
                                                    </div>
                                                    <h4>
                                                        Spanish
                                                        <?php //if($image == 'spanish') echo " <i class='glyphicon glyphicon-ok'></i>";  ?>
                                                    </h4>
                                                </a>
                                            </li>
                                            <li class="language" id="thai">
                                                <a href="<?php //echo base_url('language/index/thai')?>">
                                                    <div class="pull-left">
                                                        <img src="<?php //echo base_url('uploads/language_image/thai.png'); ?>"/>
                                                    </div>
                                                    <h4>
                                                        Thai
                                                        <?php //if($image == 'thai') echo " <i class='glyphicon glyphicon-ok'></i>";  ?>
                                                    </h4>
                                                </a>
                                            </li>
                                            <li class="language" id="turkish">
                                                <a href="<?php //echo base_url('language/index/turkish')?>">
                                                    <div class="pull-left">
                                                        <img src="<?php //echo base_url('uploads/language_image/turkish.png'); ?>"/>
                                                    </div>
                                                    <h4>
                                                        Turkish
                                                        <?php //if($image == 'turkish') echo " <i class='glyphicon glyphicon-ok'></i>";  ?>
                                                    </h4>
                                                </a>
                                            </li>
                                        </ul>
                                    </li>
                                    <li class="footer"></li>
                                </ul>
                            </li>
                        <?php //} ?> -->

                        <li class="dropdown user user-menu">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <img src="<?=imagelink($this->session->userdata('photo'),56) 
                                ?>" class="user-logo" alt="" />
                                <span class="user-menu--name">
                                    <?=(strlen($this->session->userdata('name')) > 10) ? substr($this->session->userdata('name'), 0, 10) : $this->session->userdata('name')?>
                                    
                                </span>   
                                <i class="caret"></i>
                            </a>

                            <ul class="dropdown-menu">
                                <li class="user-body">
                                    <div class="col-xs-6 text-center">
                                        <a href="<?=base_url("profile/index")?>">
                                            <div><i class="fa fa-briefcase"></i></div>
                                            <?=$this->lang->line("profile")?> 
                                        </a>
                                    </div>
                                    <div class="col-xs-6 text-center">
                                        <a href="<?=base_url("signin/cpassword")?>">
                                            <div><i class="fa fa-lock"></i></div>
                                            <?=$this->lang->line("change_password")?> 
                                        </a>
                                    </div>
                                </li>
                                <li class="user-footer">
                                    <div class="text-center">
                                        <a href="<?=base_url("signin/signout")?>" id="logoutme">
                                            <div><i class="fa fa-power-off"></i></div>
                                            <?=$this->lang->line("logout")?> 
                                        </a>
                                    </div>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav>
        </header>

        <script>

            $(document).ready(function(){
                $("#serachUser").on("click", function() {
                    var text = $('#basics').val();
                    var url = "<?=base_url() ?>search/index?text="+text;
                      window.location.href = url;

                });
            });

            $(function(){
                $('.js-search-box-toggler').on('click', function(){
                  $(this).parents('.navbar').toggleClass('searchbox-shown');
                    
                })
                var options = {     

                    url: function(phrase) {
                         return "<?=base_url('search/searchUsers')?>";
                    },

                    getValue: function(element) {
                    return element.name;
                    },

                    template: {
                                    type: "custom",
                                    method: function(value, item) {
                                      var html =   "<figure class='avatar'><img src='" + item.icon + "' /></figure>"+
                                        "<a target='_blank' href='"+item.url+"' style='color:black'>"+
                                        "<span class='name'>"+ value+"</span>";
                                       if(item.designation){
                                           html +=  "<br><span class='designation' style='font-size:12px;'>"+ item.designation+"</span></a>"  ;
                                       } 
                                       if(item.registerNO){
                                           html +=  "<br><span class='studentDetails' style='font-size:12px'>Regno:"+ item.registerNO+"&nbsp;Class:"+item.class+"&nbsp;sec:"+item.section+"</span></a>"  ;
                                       } 

                                       return html;
                                        
                                    }
                    },

                    ajaxSettings: {
                    dataType: "json",
                    method: "POST",
                    data: {
                        dataType: "json"
                    }
                    },

                    preparePostData: function(data) {
                        data.phrase = $("#basics").val();
                        return data;
                    },

                    requestDelay: 400
                };

                $("#basics").easyAutocomplete(options);

                function searchBoxSizing(){
                    var winWidth=$(window).outerWidth();
                    // console.log(winWidth);
                    if(winWidth>600){
                        // console.log('sfsfsdf')
                        $('.navbar').removeClass('searchbox-shown');
                    }
                }
                searchBoxSizing();
                $(window).resize(function() {
                
                    // $('.navbar').toggleClass('searchbox-shown');
                    searchBoxSizing();
                });
            })
        </script>

<!-- student login timer script start -->
<script type="text/javascript">

      let loginendtime = "<?php echo $loginendtime; ?>"; 
      let usertypeID = "<?php echo $userTypeID; ?>"; 
      let loginuserID = "<?php echo $loginuserID; ?>"; 

      var sec =  localStorage.getItem('totaltime'+loginuserID+usertypeID);
      function pad(val) 
      { 
        return val > 9 ? val : "0" + val; 
      }

      var func;
      function timerstart(){
            func = setInterval( function(){
                    $("#seconds").html(pad(++sec%60));
                    $("#minutes").html(pad(parseInt(sec/60,10)));
                    localStorage.setItem('totaltime'+loginuserID+usertypeID, sec);
                  
            }, 1000);
      }


    var func1;
    function timerstart1(){
            loginRestart();
            func = setInterval( function(){
                    $("#seconds").html(pad(++sec%60));
                    $("#minutes").html(pad(parseInt(sec/60,10)));
                    localStorage.setItem('totaltime'+loginuserID+usertypeID, sec);
                   
                  
            }, 1000);
      }
    
    timerstart();

    if(loginendtime != '' && usertypeID == 3){
        myClearFunction1();
    }
    

    function myStopFunction() {
        if(confirm('Are you sure you want to stop timer?')){
            clearInterval(func);
            window.localStorage.removeItem('totaltime'+loginuserID+usertypeID);
            loginTimeRecords();
        }
    }

    function myClearFunction(){
        myStopFunction();
        $("#seconds").html(pad(00));
        $("#minutes").html(pad(00));
        sec = 0;
    }

    function myClearFunction1(){
        clearInterval(func);
        $("#seconds").html(pad(00));
        $("#minutes").html(pad(00));
        $('#stopTimer').attr('disabled',true);
        $('#startTimer').attr('disabled',false);
        sec = 0;
    }

    function loginTimeRecords(){
        $.ajax({
              url: "<?=base_url('signin/endLogin/')?>",
              type: 'POST',
              datatype: 'json',
              success: function(data) {
                $('#stopTimer').attr('disabled',true);
                $('#startTimer').attr('disabled',false);
              }
        });
    }


    function loginRestart(){
        $.ajax({
              url: "<?=base_url('signin/startLogin/')?>",
              type: 'POST',
              datatype: 'json',
              success: function(data) {
                $('#stopTimer').attr('disabled',false);
                $('#startTimer').attr('disabled',true);
              }
        });
    }



    $('#logoutme').click(function(){
        clearInterval(func);
        window.localStorage.removeItem('totaltime'+loginuserID+usertypeID);
    });
    
</script>
<!-- student login timer script end -->