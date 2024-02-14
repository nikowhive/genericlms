<?php $this->load->view("feed_components/page_header"); ?>
<?php $this->load->view("feed_components/page_topbar"); ?>
<?php $this->load->view("feed_components/page_menu"); ?>
<link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css"/>
<script type="text/javascript" src="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
        <div class="right-side">
            <section class="content">
                <div class="row">
                    <div class="col-xs-12">
                        <?php $this->load->view($subview); ?>
                    </div>
                </div>
            </section>
             <footer class="main-footer"  >
                <div class="pull-right hidden-xs">
                    <a target="_blank" href="<?=base_url('frontend/index')?>" class="dropdown-toggle" data-toggle="tooltip" title="<?=$this->lang->line('menu_visit_site')?>" data-placement="top">
                            <i class="fa fa-globe"></i>
                    </a>

                    <b>v</b> <?=config_item('ini_version')?>
                </div>
                <strong><?=$siteinfos->footer?></strong>
             </footer>
        </div>

        <aside class="aside-sidebar">
            
                <div class="aside-section aside-sidebar-section aside-sidebar-section--opened "  id="mytasksbar">
                    <header class="aside-sidebar-header">
                        <h4 class="aside-sidebar-title">My Tasks</h4>
                        <a href="#mytasksbar" class="icon-round close-btn  js-close-aside-sidebar" role="button"> <i class="fa fa-times"></i> </a>
                    </header>
                    <div class="aside-sidebar-body">
                        <div class="aside-section-block aside-section-block--reminder">
                            <header class="aside-header">
                                <h5 class="aside-title" data-toggle="collapse" role="button" data-target="#reminderBlock"> <span class="mr-2 h4 icon-arrow" ><i class="fa fa-angle-down"></i></span>Reminders</h5>
                                <a href="javascript:;" class="icon-round aside-header-btn " role="button" data-toggle="tooltip" data-placement="left" title="Add Reminder"> <i class="fa fa-plus"></i> </a>
                            </header>
                            <div class="aside-body collapse in" id="reminderBlock">
                                <div class="aside-item">
                                    <div class="content-box">
                                        <div class="content-box-text">
                                            Lorem ipsum dolor sit amet consectetur adipisicing elit. Rem, aliquid!
                                        </div>
                                        <div class="dropdown content-box-btn aside-item-btn  ">
                                            <a href="#" class=" icon-round dropdown-dotted " role="button" data-toggle="dropdown"> ⋮</a>
                                            <ul  class="dropdown-menu right">
                                                <li>
                                                    <a href="#">Action</a>
                                                </li>
                                                <li>
                                                    <a href="#">Another action</a>
                                                </li>
                        
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="aside-item">
                                    <div class="content-box">
                                        <div class="content-box-text">
                                            Lorem ipsum dolor sit amet consectetur adipisicing elit. Rem, aliquid!
                                        </div>
                                        <div class="dropdown content-box-btn aside-item-btn">
                                            <a href="#" class=" icon-round dropdown-dotted " role="button" data-toggle="dropdown"> ⋮</a>
                                            <ul  class="dropdown-menu right">
                                                <li>
                                                    <a href="#">Action</a>
                                                </li>
                                                <li>
                                                    <a href="#">Another action</a>
                                                </li>
                        
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="aside-item">
                                    <div class="content-box">
                                        <div class="content-box-text">
                                            Lorem ipsum dolor sit amet consectetur adipisicing elit. Rem, aliquid!
                                        </div>
                                        <div class="dropdown content-box-btn aside-item-btn">
                                            <a href="#" class=" icon-round dropdown-dotted " role="button" data-toggle="dropdown"> ⋮</a>
                                            <ul  class="dropdown-menu right">
                                                <li>
                                                    <a href="#">Action</a>
                                                </li>
                                                <li>
                                                    <a href="#">Another action</a>
                                                </li>
                        
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="aside-section-block aside-section-block--student-work">
                            <header class="aside-header">
                            <h5 class="aside-title" data-toggle="collapse" role="button" data-target="#studendHomeworkBlock"> <span class="mr-2 h4 icon-arrow" ><i class="fa fa-angle-down"></i></span>Student Homework</h5>
                            <a href="javascript:;" class="icon-round aside-header-btn " role="button" data-toggle="tooltip" data-placement="left" title="Add Homework"> <i class="fa fa-plus"></i> </a>
                                 
                            </header>
                            <div class="aside-body collapse in" id="studendHomeworkBlock">
                                <div class="aside-item">
                                      <div class="media-block">
                                         <div class="media-block-figure">
                                            <div class="avatar">
                                            <i class="fa fa-book" ></i>
                                                <!-- <img src="https://randomuser.me/api/portraits/men/0.jpg" class="avatar-img" alt=""> -->
                                            </div>
                                         </div>
                                        <div class="media-block-body">
                                                <div class="media-block-subtitle">Class, Section, Subject, Unit, chapter</div>
                                                <h6 class="media-block-title">Write an essay about spirtituality</h6>
                                                <ul class="list-inline-new mt-2">
                                                    <li class="list-inline-new-item">
                                                        <a href="#" class="pill pill--sm pill--flat pill-light" role="button">21 March</a>
                                                    </li>
                                                    <li class="list-inline-new-item">
                                                        <a href="#" class="pill pill--sm pill--flat" role="button"> 5/34 Submitted</a>
                                                    </li>
                                                </ul>
                                        </div>
                                        <div class="content-box-btn aside-item-btn align-self-center">
                                            <a href="#" class=" icon-round " role="button" > <i class="fa fa-angle-right"></i></a>
                        
                                        </div>
                                      </div>
                                </div>
                                <div class="aside-item">
                                      <div class="media-block">
                                         <div class="media-block-figure">
                                            <div class="avatar">
                                            <i class="fa fa-book" ></i>
                                                <!-- <img src="https://randomuser.me/api/portraits/men/0.jpg" class="avatar-img" alt=""> -->
                                            </div>
                                         </div>
                                        <div class="media-block-body">
                                                <div class="media-block-subtitle">Class, Section, Subject, Unit, chapter</div>
                                                <h6 class="media-block-title">Write an essay about spirtituality</h6>
                                                <ul class="list-inline-new mt-2">
                                                    <li class="list-inline-new-item">
                                                        <a href="#" class="pill pill--sm pill--flat pill-light" role="button">21 March</a>
                                                    </li>
                                                    <li class="list-inline-new-item">
                                                        <a href="#" class="pill pill--sm pill--flat" role="button"> 5/34 Submitted</a>
                                                    </li>
                                                </ul>
                                        </div>
                                        <div class="content-box-btn aside-item-btn align-self-center">
                                            <a href="#" class=" icon-round " role="button" > <i class="fa fa-angle-right"></i></a>
                        
                                        </div>
                                      </div>
                                </div>
                                <div class="aside-item">
                                      <div class="media-block">
                                         <div class="media-block-figure">
                                            <div class="avatar">
                                            <i class="fa fa-book" ></i>
                                                <!-- <img src="https://randomuser.me/api/portraits/men/0.jpg" class="avatar-img" alt=""> -->
                                            </div>
                                         </div>
                                        <div class="media-block-body">
                                                <div class="media-block-subtitle">Class, Section, Subject, Unit, chapter</div>
                                                <h6 class="media-block-title">Write an essay about spirtituality</h6>
                                                <ul class="list-inline-new mt-2">
                                                    <li class="list-inline-new-item">
                                                        <a href="#" class="pill pill--sm pill--flat pill-light" role="button">21 March</a>
                                                    </li>
                                                    <li class="list-inline-new-item">
                                                        <a href="#" class="pill pill--sm pill--flat bg-danger" role="button"> Offline</a>
                                                    </li>
                                                </ul>
                                        </div>
                                        <div class="content-box-btn aside-item-btn align-self-center">
                                            <a href="#" class=" icon-round " role="button" > <i class="fa fa-angle-right"></i></a>
                        
                                        </div>
                                      </div>
                                </div>
                        
                        
                            </div>
                        </div>
                        <div class="aside-section-block aside-section-block--student-work">
                            <header class="aside-header">
                            <h5 class="aside-title" data-toggle="collapse" role="button" data-target="#studendassignmentBlock"> <span class="mr-2 h4 icon-arrow" ><i class="fa fa-angle-down"></i></span>Student Assignments</h5>
                            <a href="javascript:;" class="icon-round aside-header-btn " role="button" data-toggle="tooltip" data-placement="left" title="Add Assignment"> <i class="fa fa-plus"></i> </a>
                                
                            </header>
                            <div class="aside-body collapse in" id="studendassignmentBlock">
                                <div class="aside-item">
                                      <div class="media-block">
                                         <div class="media-block-figure">
                                            <div class="avatar">
                                            <i class="fa fa-book" ></i>
                                                <!-- <img src="https://randomuser.me/api/portraits/men/0.jpg" class="avatar-img" alt=""> -->
                                            </div>
                                         </div>
                                        <div class="media-block-body">
                                                <div class="media-block-subtitle">Class, Section, Subject, Unit, chapter</div>
                                                <h6 class="media-block-title">Write an essay about spirtituality</h6>
                                                <ul class="list-inline-new mt-2">
                                                    <li class="list-inline-new-item">
                                                        <a href="#" class="pill pill--sm pill--flat pill-light" role="button">21 March</a>
                                                    </li>
                                                    <li class="list-inline-new-item">
                                                        <a href="#" class="pill pill--sm pill--flat" role="button"> 5/34 Submitted</a>
                                                    </li>
                                                </ul>
                                        </div>
                                        <div class="content-box-btn aside-item-btn align-self-center">
                                            <a href="#" class=" icon-round " role="button" > <i class="fa fa-angle-right"></i></a>
                        
                                        </div>
                                      </div>
                                </div>
                                <div class="aside-item">
                                      <div class="media-block">
                                         <div class="media-block-figure">
                                            <div class="avatar">
                                            <i class="fa fa-book" ></i>
                                                <!-- <img src="https://randomuser.me/api/portraits/men/0.jpg" class="avatar-img" alt=""> -->
                                            </div>
                                         </div>
                                        <div class="media-block-body">
                                                <div class="media-block-subtitle">Class, Section, Subject, Unit, chapter</div>
                                                <h6 class="media-block-title">Write an essay about spirtituality</h6>
                                                <ul class="list-inline-new mt-2">
                                                    <li class="list-inline-new-item">
                                                        <a href="#" class="pill pill--sm pill--flat pill-light" role="button">21 March</a>
                                                    </li>
                                                    <li class="list-inline-new-item">
                                                        <a href="#" class="pill pill--sm pill--flat" role="button"> 5/34 Submitted</a>
                                                    </li>
                                                </ul>
                                        </div>
                                        <div class="content-box-btn aside-item-btn align-self-center">
                                            <a href="#" class=" icon-round " role="button" > <i class="fa fa-angle-right"></i></a>
                        
                                        </div>
                                      </div>
                                </div>
                                <div class="aside-item">
                                      <div class="media-block">
                                         <div class="media-block-figure">
                                            <div class="avatar">
                                            <i class="fa fa-book" ></i>
                                                <!-- <img src="https://randomuser.me/api/portraits/men/0.jpg" class="avatar-img" alt=""> -->
                                            </div>
                                         </div>
                                        <div class="media-block-body">
                                                <div class="media-block-subtitle">Class, Section, Subject, Unit, chapter</div>
                                                <h6 class="media-block-title">Write an essay about spirtituality</h6>
                                                <ul class="list-inline-new mt-2">
                                                    <li class="list-inline-new-item">
                                                        <a href="#" class="pill pill--sm pill--flat pill-light" role="button">21 March</a>
                                                    </li>
                                                    <li class="list-inline-new-item">
                                                        <a href="#" class="pill pill--sm pill--flat bg-danger" role="button"> Offline</a>
                                                    </li>
                                                </ul>
                                        </div>
                                        <div class="content-box-btn aside-item-btn align-self-center">
                                            <a href="#" class=" icon-round " role="button" > <i class="fa fa-angle-right"></i></a>
                        
                                        </div>
                                      </div>
                                </div>
                        
                        
                            </div>
                        </div>
                    </div>
                </div>
                <div class="aside-section aside-sidebar-section "  id="activitiesbar">
                     <header class="aside-sidebar-header">
                        <h4 class="aside-sidebar-title">Activities</h4>
                        <a href="#activitiesbar" class="icon-round close-btn  js-close-aside-sidebar" role="button"> <i class="fa fa-times"></i> </a>
                    </header>
                    <div class="aside-sidebar-body">
                        <div class="aside-section-block aside-section-block--activities">
                            <header class="aside-header">
                                <h5 class="aside-title" data-toggle="collapse" role="button" data-target="#activitiesrBlock"> <span class="mr-2 h4 icon-arrow" ><i class="fa fa-angle-down"></i></span>School Activities</h5>
                                <a href="javascript:;" class="icon-round aside-header-btn " role="button" data-toggle="tooltip" data-placement="left" title="Add School activities"> <i class="fa fa-plus"></i> </a>
                            </header>
                            <div class="aside-body collapse in" id="activitiesrBlock">
                                <div class="aside-item">
                                    <div class="activities--actions">
                                        <a
                                            href="http://localhost/erp/activities/add/1"
                                            class="btn btn-app bg-green "
                                        >
                                            <i class="fa fa-picture-o"></i> Photos
                                        </a>
                                        <a
                                            href="http://localhost/erp/activities/add/2"
                                            class="btn btn-app bg-aqua "
                                        >
                                            <i class="fa fa-cutlery"></i> Food
                                        </a>
                                        <a
                                            href="http://localhost/erp/activities/add/3"
                                            class="btn btn-app bg-blue "
                                        >
                                            <i class="fa fa-bed"></i> Sleep
                                        </a>
                                        <a
                                            href="http://localhost/erp/activities/add/4"
                                            class="btn btn-app bg-navy "
                                        >
                                            <i class="fa fa-trophy"></i> Sports
                                        </a>
                                        <a
                                            href="http://localhost/erp/activities/add/5"
                                            class="btn btn-app bg-purple "
                                        >
                                            <i class="fa fa-puzzle-piece"></i> Activities
                                        </a>
                                        <a
                                            href="http://localhost/erp/activities/add/6"
                                            class="btn btn-app bg-blue "
                                        >
                                            <i class="fa fa-edit"></i> Note
                                        </a>
                                        <a
                                            href="http://localhost/erp/activities/add/7"
                                            class="btn btn-app bg-aqua "
                                        >
                                            <i class="fa fa-times"></i> Incident
                                        </a>
                                        <a
                                            href="http://localhost/erp/activities/add/8"
                                            class="btn btn-app bg-navy "
                                        >
                                            <i class="fa fa-medkit"></i> Meds
                                        </a>
                                        <a
                                            href="http://localhost/erp/activities/add/9"
                                            class="btn btn-app bg-purple "
                                        >
                                            <i class="fa fa-paint-brush"></i> Art
                                        </a>
                                    </div>
                                </div>
                              
                            </div>
                        </div>
                    </div>
                </div>

                <div class="aside-section aside-sidebar-section aside-sidebar-section-calendar "  id="calendarbar">
                     <header class="aside-sidebar-header">
                        <h4 class="aside-sidebar-title">Calendar 
                      
                                <div class="lang-date">
                                    <a href="#" class="icon-round ml-2" role="button" title="English Date" data-toggle="tooltip" data-placement="bottom"> <i class="fa fa-globe"></i> </a>
                                    <a href="#" class="icon-round ml-2" role="button" title="Nepali Date" data-toggle="tooltip" data-placement="bottom"> <img src="<?= base_url("assets/images/nepaliflag.svg") ?>" alt="Nepali Flag"> </a>
                                </div>
                             
                        </h4>
                        <a href="#calendarbar" class="icon-round close-btn  js-close-aside-sidebar" role="button"> <i class="fa fa-times"></i> </a>
                    </header>
                    <div class="aside-sidebar-body">
                        <div class="aside-section-block aside-section-block--calendar">
 
                            <div class="aside-body  " >
                                <div class="p-3">
                                    <div class="calendar">
                                        <div class="calendar-header">
                                            <div class="calendar-month-switcher">
                                                <a href="#" class="calendar-month-prev js-week-switcher-prev">
                                                    <i class="fa fa-angle-left"></i>
                                                </a>
                                                <select name="" id=""> 
                                                    <option value="">March 2021</option>
                                                </select>
                                                <a href="#" class="calendar-month-next js-week-switcher-next">
                                                    <i class="fa fa-angle-right"></i>
                                                </a>
                                            </div>
                                            <a href="javascript:;" class="icon-round aside-header-btn " role="button" data-toggle="tooltip" data-placement="left" title="Add Reminder"> <i class="fa fa-plus"></i> </a>
                                        </div>
                                        <div class="calendar-week js-week-switcher">
                                            <div class="slick-item">
                                                <div class="calendar-day">
                                                    <span class="calendar-day-name">SUN</span>
                                                    <input type="radio" class="calendar-day-select" name="date" id="day5">
                                                    <label for="day5" class="calendar-day-date">5</label>
                                                </div>
                                            </div>
                                            <div class="slick-item">
                                                <div class="calendar-day">
                                                    <span class="calendar-day-name">mon</span>
                                                    <input type="radio" class="calendar-day-select" name="date" id="day6">
                                                    <label for="day6" class="calendar-day-date">6</label>
                                                </div>
                                            </div>
                                            <div class="slick-item">
                                                <div class="calendar-day">
                                                    <span class="calendar-day-name">tue</span>
                                                    <input type="radio" class="calendar-day-select" name="date" id="day7">
                                                    <label for="day7" class="calendar-day-date">7</label>
                                                </div>
                                            </div>
                                            <div class="slick-item">
                                                <div class="calendar-day">
                                                    <span class="calendar-day-name">wed</span>
                                                    <input type="radio" class="calendar-day-select" name="date" id="day8">
                                                    <label for="day8" class="calendar-day-date">8</label>
                                                </div>
                                            </div>
                                            <div class="slick-item">
                                                <div class="calendar-day">
                                                    <span class="calendar-day-name">thu</span>
                                                    <input type="radio" class="calendar-day-select" name="date" id="day9">
                                                    <label for="day9" class="calendar-day-date">9</label>
                                                </div>
                                            </div>
                                            <div class="slick-item">
                                                <div class="calendar-day">
                                                    <span class="calendar-day-name">fri</span>
                                                    <input type="radio" class="calendar-day-select" name="date" id="day10">
                                                    <label for="day10" class="calendar-day-date">10</label>
                                                </div>
                                            </div>
                                            <div class="slick-item">
                                                <div class="calendar-day">
                                                    <span class="calendar-day-name">sat</span>
                                                    <input type="radio" class="calendar-day-select" name="date" id="day11">
                                                    <label for="day11" class="calendar-day-date">11</label>
                                                </div>
                                            </div>

                                            <div class="slick-item">
                                                <div class="calendar-day">
                                                    <span class="calendar-day-name">SUN</span>
                                                    <input type="radio" class="calendar-day-select" name="date" id="day12">
                                                    <label for="day12" class="calendar-day-date">12</label>
                                                </div>
                                            </div>
                                            <div class="slick-item">
                                                <div class="calendar-day">
                                                    <span class="calendar-day-name">mon</span>
                                                    <input type="radio" class="calendar-day-select" name="date" id="day13">
                                                    <label for="day13" class="calendar-day-date">13</label>
                                                </div>
                                            </div>
                                            <div class="slick-item">
                                                <div class="calendar-day">
                                                    <span class="calendar-day-name">tue</span>
                                                    <input type="radio" class="calendar-day-select" name="date" id="day14">
                                                    <label for="day14" class="calendar-day-date">14</label>
                                                </div>
                                            </div>
                                            <div class="slick-item">
                                                <div class="calendar-day">
                                                    <span class="calendar-day-name">wed</span>
                                                    <input type="radio" class="calendar-day-select" name="date" id="day15">
                                                    <label for="day15" class="calendar-day-date">15</label>
                                                </div>
                                            </div>
                                            <div class="slick-item">
                                                <div class="calendar-day">
                                                    <span class="calendar-day-name">thu</span>
                                                    <input type="radio" class="calendar-day-select" name="date" id="day16">
                                                    <label for="day16" class="calendar-day-date">16</label>
                                                </div>
                                            </div>
                                            <div class="slick-item">
                                                <div class="calendar-day">
                                                    <span class="calendar-day-name">fri</span>
                                                    <input type="radio" class="calendar-day-select" name="date" id="day17">
                                                    <label for="day17" class="calendar-day-date">17</label>
                                                </div>
                                            </div>
                                            <div class="slick-item">
                                                <div class="calendar-day">
                                                    <span class="calendar-day-name">sat</span>
                                                    <input type="radio" class="calendar-day-select" name="date" id="day18">
                                                    <label for="day18" class="calendar-day-date">18</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>                              
                            </div>
                        </div>

                        <div class="aside-section-block aside-section-block--calendar-reminder">
                        <header class="aside-header">
                                <h5 class="aside-title"  > Today - Monday, 6 March</h5>
                                 
                            </header>
                            <div class="aside-body  " >
                                <div class="aside-item">
                                    <div class="content-box content-box--blockquote">
                                        <div class="content-box-text">
                                            <a href="#" class="pill pill--sm pill-light">CLASS</a>
                                        </div>                                        
                                    </div>
                                </div>
                                <div class="aside-item">
                                    <div class="content-box content-box--blockquote">
                                        <div class="content-box-text">
                                            <a href="#" class="pill pill--sm pill-light">CLASS</a>
                                        </div>                                        
                                    </div>
                                </div>                                
                            </div>
                        </div>
                    </div>
                </div>
                <div class="aside-section aside-sidebar-section "  id="messagesbar">
                     <header class="aside-sidebar-header">
                        <h4 class="aside-sidebar-title">Messages</h4>
                        <a href="#messagesbar" class="icon-round close-btn  js-close-aside-sidebar" role="button"> <i class="fa fa-times"></i> </a>
                    </header>
                    <div class="aside-sidebar-body">
                        <div class="aside-section-block aside-section-block--messages">
                           
                            <div class="aside-body  " >
                                <div class="aside-item"  >
                                    <div class="media-block">
                                        <div class="media-block-figure">
                                            <div class="avatar">
                                            
                                                <img src="https://randomuser.me/api/portraits/men/0.jpg" class="avatar-img" alt="">
                                            </div>
                                        </div>
                                        <div class="media-block-body">
                                                <div class="media-block-subtitle">May 14, 2021 9:45 AM</div>
                                                <a href="#" class="media-block-title stretched-link " onClick="handleMessage(this);">Online Conference</a>
                                                    <p>Lorem ipsum dolor sit amet.</p>
                                        </div>
                                        <div class="dropdown content-box-btn aside-item-btn  ">
                                            <a href="#" class=" icon-round dropdown-dotted " role="button" data-toggle="dropdown"> ⋮</a>
                                            <ul  class="dropdown-menu right">
                                                <li>
                                                    <a href="#">Action</a>
                                                </li>
                                                <li>
                                                    <a href="#">Another action</a>
                                                </li>
                        
                                            </ul>
                                        </div>                                       
                                    </div>
                                </div>

                                <div class="aside-item">
                                    <div class="media-block">
                                        <div class="media-block-figure">
                                            <div class="avatar">
                                            L
                                                <!-- <img src="https://randomuser.me/api/portraits/men/0.jpg" class="avatar-img" alt=""> -->
                                            </div>
                                        </div>
                                        <div class="media-block-body">
                                                <div class="media-block-subtitle">May 14, 2021 9:45 AM</div>
                                                <a href="#" class="media-block-title stretched-link" onClick="handleMessage(this);">Online Conference</a>
                                                    <p>Lorem ipsum dolor sit amet.</p>
                                        </div>
                                        <div class="dropdown content-box-btn aside-item-btn  ">
                                            <a href="#" class=" icon-round dropdown-dotted " role="button" data-toggle="dropdown"> ⋮</a>
                                            <ul  class="dropdown-menu right">
                                                <li>
                                                    <a href="#">Action</a>
                                                </li>
                                                <li>
                                                    <a href="#">Another action</a>
                                                </li>
                        
                                            </ul>
                                        </div>                                       
                                    </div>
                                </div>
                                
                                <a href="#" role="button" class="btn btn-floating"> <i class="fa fa-edit"></i> </a>
                              
                            </div>
                        </div>
                    </div>
                </div>

                <div class="messageDetail">
                    <header class="messageDetail-header aside-sidebar-header">
                        <div class="avatar">
                            <figure class="avatar__figure">
                                <div class="avatar__image">
                                <img src="https://randomuser.me/api/portraits/men/0.jpg" class="" alt="">
                                </div>

                            </figure>
                            <div class="avatar__meta">
                                <h5>EduWise</h5>
                            </div>
                        </div>
                    <a href="#messagesbar" onclick="handleMessageDetail();" class="icon-round close-btn  js-close-messageDetail" role="button"> <i class="fa fa-times"></i> </a>
                    </header>
                    <div class="messageDetail-body">
                        <div class="media-block">
                            <div class="media-block-figure">
                                <div class="avatar">
                                
                                    <img src="https://randomuser.me/api/portraits/men/0.jpg" class="avatar-img" alt="">
                                </div>
                            </div>
                            <div class="media-block-body">
                                    <div class="media-block-subtitle">May 14, 2021 9:45 AM</div>
                                    <div class="media-block-content">
                                        <div class="media-block-title stretched-link "  >Online Conference</div>
                                        <p>Lorem ipsum dolor sit amet.</p>
                                    </div>
                            </div>
                                                                    
                        </div>
                        <div class="media-block">
                            <div class="media-block-figure">
                                <div class="avatar">
                                
                                    <img src="https://randomuser.me/api/portraits/men/0.jpg" class="avatar-img" alt="">
                                </div>
                            </div>
                            <div class="media-block-body">
                                    <div class="media-block-subtitle">May 14, 2021 9:45 AM</div>
                                    <div class="media-block-content">
                                        <div class="media-block-title stretched-link "  >Online Conference</div>
                                        <p>Lorem ipsum dolor sit amet.</p>
                                    </div>
                            </div>
                                                                    
                        </div>
                        <div class="media-block">
                            <div class="media-block-figure">
                                <div class="avatar">
                                
                                    <img src="https://randomuser.me/api/portraits/men/0.jpg" class="avatar-img" alt="">
                                </div>
                            </div>
                            <div class="media-block-body">
                                    <div class="media-block-subtitle">May 14, 2021 9:45 AM</div>
                                    <div class="media-block-content">
                                        <div class="media-block-title stretched-link "  >Online Conference</div>
                                        <p>Lorem ipsum dolor sit amet.</p>
                                    </div>
                            </div>
                                                                    
                        </div>
                        <div class="media-block">
                            <div class="media-block-figure">
                                <div class="avatar">
                                
                                    <img src="https://randomuser.me/api/portraits/men/0.jpg" class="avatar-img" alt="">
                                </div>
                            </div>
                            <div class="media-block-body">
                                    <div class="media-block-subtitle">May 14, 2021 9:45 AM</div>
                                    <div class="media-block-content">
                                        <div class="media-block-title stretched-link "  >Online Conference</div>
                                        <p>Lorem ipsum dolor sit amet.</p>
                                    </div>
                            </div>
                                                                    
                        </div>
                        <div class="media-block media-block--self">
                            <div class="media-block-figure">
                                <div class="avatar">
                                
                                    <img src="https://randomuser.me/api/portraits/men/0.jpg" class="avatar-img" alt="">
                                </div>
                            </div>
                            <div class="media-block-body">
                                    <div class="media-block-subtitle">May 14, 2021 9:45 AM</div>
                                    <div class="media-block-content">
                                        <div class="media-block-title stretched-link " onclick="handleMessage('12');">Online Conference</div>
                                        <p>Lorem ipsum dolor sit amet.</p>
                                    </div>
                            </div>
                                                                    
                        </div>
                    </div>
                    <div class="messageDetail-footer">
                        <div class="md-form mb-0">
                            <textarea name="" id="" class="md-textarea form-control" rows="3"></textarea>
                            <label for="form7" class="">Message</label>
                            <a href="#" role="button" class="btn btn-floating "> <i class="fa fa-paper-plane"></i> </a>
                        </div>
                    </div>
                </div>


             
        </aside>

<style>
.add-button {
  position: absolute;
  top: 1px;
  left: 1px;
}

</style>
<?php $this->load->view("feed_components/page_footer"); ?>
 <script>
     $(function(){

         function handleAsideSidebar(){
            var body = $('body');
            var asideOpenClass='aside-sidebar--opened';
            var asideSectionOpenClass='aside-sidebar-section--opened';
    
             $('.js-close-aside-sidebar').on("click",function(){
                var targetDiv =$(this).parents('.aside-sidebar-section').attr('id'); 
                 var MenuDiv=$('a[href=#'+targetDiv+']');
                 console.log(MenuDiv);
                 handleMessageDetail();
                if(MenuDiv.hasClass('active')) {
                    MenuDiv.removeClass('active')
                }
                if(body.hasClass(asideOpenClass)) {
                    body.removeClass(asideOpenClass);
                    setTimeout(function() {
                        $('#'+targetDiv).removeClass(asideSectionOpenClass);
                    }, 800);
                   
                }
            })

            $('.js-trigger-aside-sidebar').on("click",function(e){
               e.preventDefault();
               handleMessageDetail();
               var targetDiv =$(this).attr('href');

               if(targetDiv=="#calendarbar"){
                // $('.js-week-switcher')[0].slick.refresh();
                 $('.js-week-switcher').slick("refresh"); 
                }     
                else  if ( $('.js-week-switcher').hasClass('slick-initialized')) {
                     $('.js-week-switcher').slick('destroy');
                }        
                // if(!body.hasClass(asideOpenClass)) {
                    $('.js-trigger-aside-sidebar').removeClass('active');
                    $(this).addClass('active');
                    body.addClass(asideOpenClass);
                    $('.aside-sidebar-section').removeClass(asideSectionOpenClass);
                    $(targetDiv).addClass(asideSectionOpenClass);
                    
                // }
            })
         }

         function responsiveAsideSidebar(){
            if (window.matchMedia('(max-width: 1199px)').matches) {
                if($('.navbar-nav li a').hasClass('active')) {
                    $('.navbar-nav li a').removeClass('active')
                }
                if($('body').hasClass('aside-sidebar--opened')) {
                    $('body').removeClass('aside-sidebar--opened')
                }
                if($('.aside-sidebar-section').hasClass('aside-sidebar-section--opened')) {
                    $('.aside-sidebar-section').removeClass('aside-sidebar-section--opened')
                }
            }
         }
         responsiveAsideSidebar();
         $(window).resize(function() {
            responsiveAsideSidebar();
         });
         handleAsideSidebar();

         

        function calenderWeekCall(){
            $('.js-week-switcher').slick({
                slidesToShow: 7,
                slidesToScroll: 7,
                autoplaySpeed: 2000,
                infinite:false,
                arrows: false,
            });
            // Custom carousel nav
            $('.js-week-switcher-prev').click(function(e){ 
                e.preventDefault(); 
                
                $('.js-week-switcher').slick('slickPrev');
            } );
            
            $('.js-week-switcher-next').click(function(){
            
                $('.js-week-switcher').slick('slickNext');
            } );
        }

        
        
        calenderWeekCall();

        
         
     });
     function handleMessageDetail(){
        if( $('.aside-sidebar').hasClass('aside-sidebar--messages')) {
                    $('.aside-sidebar').removeClass('aside-sidebar--messages');
                    $('.aside-sidebar .aside-item').removeClass('active')
                }
     }
     function handleMessage(e){
         var parent=$(e).parents(".aside-item");
         
        //  console.log($(e).parents(".aside-item"));
        parent.addClass('active');
            $('.aside-sidebar').addClass('aside-sidebar--messages');
        }
 </script>


