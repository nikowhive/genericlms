<?php if(isset($course)) { ?>
    <a href="javascript:;" class="page-submenu-toggler btn btn-default js-page-submenu"  ><i class="fa fa-bars" ></i> <span  >Courses</span></a>


    <nav class="page-submenu ">
        <ul class="menu">
            <li class="menu-item treeview-menu-header ">
                    <a href="javascript:;" class="menu-link js-page-submenu"  title="Courses"><i class="fa fa-bars" aria-hidden="true"></i> <span>Courses</span></a>
                </li>
            <?php if($usertypeID != 3 && $usertypeID != 4) { ?>
                <li class="menu-item">
                    <a href="<?php echo base_url().'courses/show/'.$course->id ?>" data-container="body" data-toggle="tooltip" class="menu-link <?php echo htmlentities(escapeString($this->uri->segment(2))) == 'show' ? 'active': '' ?>" data-placement="right" title="Home"><i class="fa fa-home" aria-hidden="true"></i> <span>Home</span></a>
                </li>
            <?php } ?>
            <?php if($usertypeID == 3 || $usertypeID == 4) { ?>
                <li class="menu-item">
                    <a href="<?php echo base_url().'courses/student_view/'.$course->id ?>" data-container="body" data-toggle="tooltip" class="menu-link <?php echo htmlentities(escapeString($this->uri->segment(2))) == 'student_view' ? 'active': '' ?>" title="Home"  data-placement="right"><i class="fa fa-home" aria-hidden="true"></i> <span>Home</span></a>
                </li>
            <?php } ?>
            <!-- <li class="menu-item">
                <a href="<?php //echo base_url().'courses/annual/'.$course->id ?>" data-container="body" data-toggle="tooltip" class="menu-link <?php echo htmlentities(escapeString($this->uri->segment(2))) == 'annual' || htmlentities(escapeString($this->uri->segment(1))) == 'annual_plan' ? 'active': '' ?>" title="Annual"  data-placement="right"><i class="fa fa-puzzle-piece" aria-hidden="true"></i> <span>Annual Plan</span></a>
            </li>
            <li class="menu-item">
                <a href="<?php //echo base_url().'courses/lesson/'.$course->id ?>" data-container="body" data-toggle="tooltip" class="menu-link <?php echo htmlentities(escapeString($this->uri->segment(2))) == 'lesson' || htmlentities(escapeString($this->uri->segment(1))) == 'lesson_plan' ? 'active': '' ?>" title="Lesson"  data-placement="right"><i class="fa icon-subject" aria-hidden="true"></i> <span>Lesson Plan</span></a>
            </li> -->
            <?php //if($usertypeID == 1 || $usertypeID == 2) { ?>
            <!-- <li class="menu-item">
                <a href="<?php //echo base_url().'courses/daily/'.$course->id ?>" data-container="body" data-toggle="tooltip" class="menu-link <?php echo htmlentities(escapeString($this->uri->segment(2))) == 'daily' || htmlentities(escapeString($this->uri->segment(1))) == 'daily_plan' ? 'active': '' ?>" title="Daily"  data-placement="right"><i class="fa fa-calendar" aria-hidden="true"></i> <span>Daily Plan</span></a>
            </li> -->
            <?php //} ?>
            <li class="menu-item">
                <a href="<?php echo base_url().'courses/contents/'.$course->id ?>" data-container="body" data-toggle="tooltip" class="menu-link <?php echo htmlentities(escapeString($this->uri->segment(2))) == 'contents' || htmlentities(escapeString($this->uri->segment(2))) == 'addcontent' || htmlentities(escapeString($this->uri->segment(2))) == 'editcontent' ? 'active': '' ?>"  title="Contents" data-placement="right"><i class="fa fa-file-word-o" aria-hidden="true"></i> <span>Contents</span></a>
            </li>
            <li class="menu-item">
                <a href="<?php echo base_url().'courses/attachments/'.$course->id ?>" data-container="body" data-toggle="tooltip" class="menu-link <?php echo htmlentities(escapeString($this->uri->segment(2))) == 'attachments' || htmlentities(escapeString($this->uri->segment(2))) == 'addfiles' ? 'active': '' ?>" title="Attachments" data-placement="right"><i class="fa fa-paperclip" aria-hidden="true"></i> <span>Attachments</span></a>
            </li>
            <li class="menu-item">
                <a href="<?php echo base_url().'courses/links/'.$course->id ?>" data-container="body" data-toggle="tooltip" class="menu-link <?php echo htmlentities(escapeString($this->uri->segment(2))) == 'links' || htmlentities(escapeString($this->uri->segment(2))) == 'addlinks' ? 'active': '' ?>" title="Links" data-placement="right"><i class="fa fa-link" aria-hidden="true"></i> <span>Links</span></a>
            </li>
            <li class="menu-item">
                <a href="<?php echo base_url().'courses/quizzes/'.$course->id ?>" data-container="body" data-toggle="tooltip" class="menu-link <?php echo htmlentities(escapeString($this->uri->segment(2))) == 'quizzes' ? 'active': '' ?>" title="Quizzes"  data-placement="right"><i class="fa fa-puzzle-piece" aria-hidden="true"></i> <span>Quizzes</span></a>
            </li>
            <li class="menu-item">
                <a href="<?php echo base_url().'courses/assignment/'.$course->id ?>" data-container="body" data-toggle="tooltip" class="menu-link <?php echo htmlentities(escapeString($this->uri->segment(2))) == 'assignment' || htmlentities(escapeString($this->uri->segment(1))) == 'assignment' ? 'active': '' ?>" title="Assignment"  data-placement="right"><i class="fa fa-book" aria-hidden="true"></i> <span>Assignment</span></a>
            </li>
            <li class="menu-item">
                <a href="<?php echo base_url().'courses/homework/'.$course->id ?>" data-container="body" data-toggle="tooltip" class="menu-link <?php echo htmlentities(escapeString($this->uri->segment(2))) == 'homework' || htmlentities(escapeString($this->uri->segment(1))) == 'homework' ? 'active': '' ?>" title="Homework"  data-placement="right"><i class="fa fa-copy" aria-hidden="true"></i> <span>Homework</span></a>
            </li>
            <li class="menu-item">
                <a href="<?php echo base_url().'courses/classwork/'.$course->id ?>" data-container="body" data-toggle="tooltip" class="menu-link <?php echo htmlentities(escapeString($this->uri->segment(2))) == 'classwork' || htmlentities(escapeString($this->uri->segment(1))) == 'classwork' ? 'active': '' ?>" title="Classwork"  data-placement="right"><i class="fa fa-file-text-o" aria-hidden="true"></i> <span>Classwork</span></a>
            </li>
            <?php if($usertypeID == 1 || $usertypeID == 2) { ?>
                <li class="menu-item">
                    <a href="<?php echo base_url().'courses/student_view/'.$course->id ?>" data-container="body" data-toggle="tooltip" class="menu-link <?php echo htmlentities(escapeString($this->uri->segment(2))) == 'student_view' ? 'active': '' ?>" title="Student view"  data-placement="right"><i class="fa fa-user" aria-hidden="true"></i> <span>Student view</span></a>
                </li>
            <?php }?>
        </ul>
        <div class="menu-overlay js-page-submenu-overlay"></div>
    </nav>
<?php } ?>