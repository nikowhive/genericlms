@layout('views/layouts/coursedetail')

@section('headerAssetPush')
    
@endsection

@section('content')
<style>

.prerequisitesDiv{
   padding-left: 10px;
}

.prerequisitesDiv p{
   margin: auto;
}


</style>
<section class="hero hero-fixed">
        <div class="container">
                    <header class="hero-header">
                        <h1 class="hero-title">
                        <?php if(isset($class)){echo $class->classes; } ?>
                        </h1>
                        <div class="hero-lead">{{$extraInformations->description}}</div>
                    </header>
               
        </div>
        <img src="<?= base_url("uploads/images/".$class->photo) ?>" class="hero-image" alt="feature image" srcset="">
</section>

<section class="hero hero-static">
        <div class="container">
                    <header class="hero-header">
                        <h1 class="hero-title">
                        <?php if(isset($class)){echo $class->classes; } ?>
                        </h1>
                        <div class="hero-lead">{{$extraInformations->description}}</div>
                    </header>
               
        </div>
        <?php if($class->photo){ ?>
            <img src="<?= base_url("uploads/images/".$class->photo) ?>" class="hero-image" alt="feature image" srcset="">
        <?php } ?>
</section>

<main class="main-content main-content-course-detail">
   <div class="container-fluid">
      <div class="row">
         <div class="col-lg-3">
            <div  class="card course-menu">
               <div class="card-header" data-bs-toggle="collapse" data-bs-target="#courseMenu" role="button" aria-expanded="false" aria-controls="collapseExample">
                  <h4 class="card-title text-truncate mb-0">Find on this course</h4>
                  <i class="fa fa-angle-down"></i>
               </div>
               <div class="card-body collapse p-0" id="courseMenu">
                  <div class="list-group " id="list-example" >
                     <!-- <a href="#enrollments"  class="list-group-item list-group-item-action active">Course overview</a>       -->
                     @if(customCompute($newContentBlocks))
                        @foreach($newContentBlocks as $key => $blocks)
                           @if($blocks['type_id'] == '2' && !empty($courses))
                                     
                           <a href="#coursecontent"  class="list-group-item list-group-item-action active">Course Contents</a>                  
                           @endif 
                           
                           @if($blocks['type_id'] == '1')  
                           <a href="#aboutauther<?=$key?>"  class="list-group-item list-group-item-action">{{$blocks['title']}}</a> 
                           @endif    
                           
                           @if($blocks['type_id'] == '3')               
                           <a href="#faq"  class="list-group-item list-group-item-action">Frequently asked questions</a>    
                           @endif 
                        @endforeach
                     @endif                
                  </div>
               </div>
            </div>
         </div>
         <div class="col-lg-6 course-main-content">
        
               <section id="enrollments" class="course-main-content-section section-enrollments mb-4">
                  <h3 class="section-title">Enrolments</h3>
                  <div class="chip-groups">
                     @if(customCompute($enrollments))
                        @foreach($enrollments as $enroll)
                        <?php $a = "01";
                           $time = strtotime($a."-".$enroll->from_month);
                           $month=substr(date("F",$time),0,3);
                           //$year=date("Y",$time);
                           $time1= strtotime($a."-".$enroll->to_month);
                           $month1=substr(date("F",$time1),0,3);
                           //$year1=date("Y",$time1);  ?>
                        <!-- <a href="#" class="chip">{{$month."-".$month1}}</a> -->
                        <a href="#" class="chip">{{ $enroll->title }}</a>
                        @endforeach
                     @endif
                  </div>
               </section>
               @if(customCompute($newContentBlocks))
                  @foreach($newContentBlocks as $key => $blocks)
                     @if($blocks['type_id'] == '2' && !empty($courses))
                        <section id="coursecontent" class="course-main-content-section section-course-content mb-4">
                           <h4 class="section-title mb-3">Course Contents</h4>
                           <div class="sortable-list">
                              <ul class="unit-wrapper">
                                 <?php $i = 0; ?>
                                 @foreach($courses as $course)
                                 <li>
                                    <div class="sortable-block sortable-blockunit"  data-bs-toggle="collapse" href="#collapseExample<?=$i?>" role="button" aria-expanded="false">
                                       <div class="sortable-header" >
                                          <a class="btn btn-sm btn-link " data-toggle role="button"  >
                                          <!-- <i class="fa fa-angle-down"></i> -->
                                          </a>
                        
                                          <div class="my-2">
                                             <div class="d-flex align-items-center mb-2">
                                                <small class="mb-0 pb-0 me-2 lh-1">Course</small>
                                                <span class="pill pill--xs"><?php if($course->type == 1){ echo "Core";} else { echo "Elective";}?></span>
                                             </div>
                                             <h4 class="sortable-title my-0">
                                              
                                             <b>{{$course->subject_code}}&nbsp; {{ $course->subject}}</b>
                                             </h4>
                                             <div class="list-inline-new mt-1">
                                                <div class="list-inline-new-item" data-bs-toggle="tooltip" data-bs-placement="top"
                                                data-bs-offset="0,-10" title="Units">{{$course->unit}} <i class="fa fa-hourglass-start"></i></div>
                                                <div class="list-inline-new-item" data-bs-toggle="tooltip" data-bs-placement="top"
                                                data-bs-offset="0,-10" title="Chapters"> {{$course->chapter}} <i class="fa fa-question-circle"></i></div>
                                                <div class="list-inline-new-item" data-bs-toggle="tooltip" data-bs-placement="top"
                                                data-bs-offset="0,-10" title="Contents"> {{$course->content}} <i class="fa fa-briefcase"></i></div>
                                             </div>
                                             @if($course->prerequisites)
                                             <div class="d-flex align-items-center mt-2">
                                                 <span class="pill pill--xs">Prerequisites</span>
                                             </div>
                                             <div class="prerequisitesDiv">
                                                {{ $course->prerequisites}}
                                             </div>
                                             @endif
                                          </div>
                        
                        
                                       </div>
                                       <div class="sortable-right-column align-self-center text-sm-end text-muted ">
                                       <span>{{$course->duration}}</span>   <span>{{$course->hour_per_week}}</span>
                                       </div>
                                    </div>
                                    <div class="collapse "  id="collapseExample<?=$i?>">
                                       <div class="sortable-block chapter-wrapper sortable-block--parent px-4 py-4">
                                          {{$course->description}}
                                       </div>
                        
                                    </div>
                                 </li>
                                 <?php $i++; ?>
                                 @endforeach
                              </ul>
                           </div>
                        </section>
                     @endif  

                     @if($blocks['type_id'] == '1' && $blocks['image']=='')  
                     <article id="aboutauther<?=$key?>" class="course-main-content-section card card--media mb-4" style="--oveflow-mask-height:162px;">
                        <div class="card-body">
                           <h4 class="card-title mb-2">{{$blocks['title']}}</h4>
                           <div class="card-text overflow-mask"> 
                                 {{$blocks['description']}}
                              @if(strlen(strip_tags($blocks['description']))> 250)
                                 <span  class="readmore-link">
                                    <span class="readmore-link-text"></span>
                                       <i class="fa fa-angle-down"></i>
                                 </span>
                              @endif
                           </div>
                        </div>
                     </article>
                     @endif 
                     @if($blocks['type_id'] == '1' && $blocks['image']!='')  
                        <article id="aboutauther<?=$key?>" class="course-main-content-section card card--media">
                           <div class="card-body">
                              <div class="row">
                                 <div class="col-md-8">
                                    <h4 class="card-title mb-2">{{$blocks['title']}}</h4>
                                       {{$blocks['description']}}
                                 </div>
                                 <div class="col-md-4 mt-3 mt-md-0">
                                    <img src="<?= base_url("uploads/class-detail/".$blocks['image']) ?>"  class="img-fluid rounded-3" alt="">
                                 </div>
                              </div>
                           </div>
                        </article>
                     @endif 
                     
                     
                     @if($blocks['type_id'] == '3')  <?php $i=0; ?>
                        <section id="faq" class="course-main-content-section section-faq-content">
                        <h4 class="section-title mb-3">Frequently asked questions</h4>
                        <div class="accordion" id="accordionExample">
                           @foreach($blocks['faq'] as $faq )
                              <div class="accordion-item">
                                 <h2 class="accordion-header" id="heading<?=$i?>">
                                    <button class="accordion-button accordion-button-icon-left" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?=$i?>" aria-expanded="true" aria-controls="collapse<?=$i?>">
                                    {{$faq->question}}
                                    </button>
                                 </h2>
                                 <div id="collapse<?=$i?>" class="accordion-collapse collapse <?php echo $i==0?'show':'' ?>" aria-labelledby="heading<?=$i?>" data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                       <strong>{{$faq->answer}}</strong>
                                    </div>
                                 </div>
                              </div>
                              <?php $i++; ?>
                           @endforeach
                           </div>
                        </section>
                     @endif   
                     
                  @endforeach   
               @endif
               
            
            <button  class="btn btn-floating btn-floating-fixed js-course-info"> <i class="fa fa-info"></i> </button>
         </div>
         @if(isset($extraInformations) && !empty($extraInformations))
         <h3 class="col-lg-3">
            <div class="card course-meta" id="courseMeta">
            <button class="icon-round close-btn  js-course-info"  > <i class="fa fa-times"></i> </button>
                  <ul class="list-group list-group-flush">
                     <li class="list-group-item">
                        <div class="list-group-item-label">Student</div>
                        <div class="list-group-item-value">{{ $extraInformations->student }} </div>
                     </li>
                     <li class="list-group-item">
                        <div class="list-group-item-label">Study mode</div>
                        <div class="list-group-item-value">{{ $extraInformations->study_mode }}</div>
                     </li>
                     <li class="list-group-item">
                        <div class="list-group-item-label">Campus locations</div>
                        <div class="list-group-item-value">{{ $extraInformations->campus_location }}</div>
                     </li>
                     <li class="list-group-item">
                        <div class="list-group-item-label">Duration</div>
                        <div class="list-group-item-value">{{ $extraInformations->duration }}</div>
                     </li>
                     <li class="list-group-item">
                        <div class="list-group-item-label">Total hours</div>
                        <div class="list-group-item-value">{{ $extraInformations->total_hours }}</div>
                     </li>
                     <li class="list-group-item">
                        <div class="list-group-item-label">Fees</div>
                        <div class="list-group-item-value">	
                          @if($extraInformations->fees)    
                          <p style="text-decoration: line-through red;">{{ $extraInformations->fees }}</p>
                          @endif     
                          <p> {{ $extraInformations->discounted_fees }}</p>
                        </div>
                     </li>
                     <li class="list-group-item">
                        <div class="list-group-item-label">Material fees</div>
                        <div class="list-group-item-value">{{ $extraInformations->material_fees }}</div>
                     </li>
                     <li class="list-group-item">
                        <div class="list-group-item-label">Enrolment fees</div>
                        <div class="list-group-item-value">{{ $extraInformations->enrollment_fees }}</div>
                     </li>
                     @if($extraInformations->covid_scholarship )            
                     <li class="list-group-item">
                        <div class="list-group-item-label">Covid scholarship</div>
                        <div class="list-group-item-value">{{ $extraInformations->covid_scholarship }}</div>
                     </li>
                     @endif         
                     <li class="list-group-item">
                        <div class="list-group-item-label">Cricos code</div>
                        <div class="list-group-item-value">{{ $extraInformations->cricos }}</div>
                     </li>
                     <li class="list-group-item">
                        <div class="list-group-item-label">Start date</div>
                        <div class="list-group-item-value">
                        @if(customCompute($enrollments))
                           @foreach($enrollments as $enroll)
                           {{ $enroll->title }} <br>
                           @endforeach
                        @endif   
                           <!-- View all key dates -->
                        </div>
                     </li>
                  </ul>
                  <div class="card-footer"><a href="#" class="btn btn-success" role="button">Enquiry Now <i class="fa fa-angle-right"></i> </a></div>
                
            </div>
         </h3>
         @endif
      </div>
   </div>
</main>


@endsection

