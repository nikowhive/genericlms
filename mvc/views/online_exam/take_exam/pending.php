<section class="panel">
    <div class="panel-body">
        <div id="printablediv">
            <div class="row">
                <div class="col-sm-3">
                    <div class="box box-primary">
                        <div class="box-body box-profile">
                            <?=profileviewimage($student->photo)?>
                            <h3 class="profile-username text-center"><?=$student->name?></h3>
                            <ul class="list-group list-group-unbordered">
                                <li class="list-group-item" style="background-color: #FFF">
                                    <b><?=$this->lang->line('take_exam_registerNO')?></b> <a class="pull-right"><?=$student->registerNO?></a>
                                </li>
                                <li class="list-group-item" style="background-color: #FFF">
                                    <b><?=$this->lang->line('take_exam_roll')?></b> <a class="pull-right"><?=$student->roll?></a>
                                </li>
                                <li class="list-group-item" style="background-color: #FFF">
                                    <b><?=$this->lang->line('take_exam_class')?></b> <a class="pull-right"><?=customCompute($class) ? $class->classes : ''?></a>
                                </li>
                                <li class="list-group-item" style="background-color: #FFF">
                                    <b><?=$this->lang->line('take_exam_section')?></b> <a class="pull-right"><?=customCompute($section) ? $section->section : ''?></a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="col-sm-9">
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#profile" data-toggle="tab"><?=$this->lang->line('take_exam_exam_info')?></a></li>
                        </ul>
                        <div class="tab-content">
                            <div class="active tab-pane" id="profile">
                                <div class="panel-body profile-view-dis">
                                    <div class="row" style="min-height: 250px">
                                        <h2>
                                            Thank you for appearing our online entrance examination. 
                                        </h2>
                                        <p>We will publish your result and notify you soon. We wish you best of luck for your result.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>