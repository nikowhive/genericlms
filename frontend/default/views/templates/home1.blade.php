@layout('views/layouts/master')


@section('content')
<link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css"/>
    @if(customCompute($sliders))
        <div id="main-slider" class="slider-area">
        @foreach($sliders as $slider)
            <div class="single-slide">
                <img src="{{ base_url('uploads/gallery/'.$slider->file_name) }}" alt="">
                <div class="banner-overlay">
                    <div class="container">
                        <div class="caption style-2">
                            <h2>{{ sentenceMap(htmlspecialchars_decode($slider->file_title), 17, '<span>', '</span>') }}</h2>
                            <p>{{ htmlspecialchars_decode($slider->file_description) }}</p>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
        </div>
    @endif

    {{-- <section id="about" class="about-area area-padding">
    	<div class="container">
            <div class="row">
                <div class="col-xs-12">
                    <div class="section-title text-uppercase text-center">
                        <h2>Introduction1</h2>
                        <span class="star"></span>
                        <span class="star"></span>
                        <span class="star"></span>
                    </div>
                </div>
            </div>

    		<div class="row">
        		@if(!customCompute($featured_image))
        			<div class="col-md-12">
                        <div class="about-content">
                            <p> {{ htmlspecialchars_decode($page->content) }} </p>
                        </div>
        			</div>
        		@else
                    <div class="col-md-6 col-md-push-6">
                        <div class="content-img">
                            <img src="{{ imageLinkWithDefatulImage($featured_image->file_name, 'holiday.png', 'uploads/gallery/') }}" />
                        </div>
                    </div>
        			<div class="col-md-6 col-md-pull-6">
                        <div class="about-content">
                            <p> {{ htmlspecialchars_decode($page->content) }} </p>
                        </div>
        			</div>
        		@endif
    		</div>
    	</div>
    </section> --}}

    @if(customCompute($notices))
    <section id="notice" class="notice-area area-padding gray-bg">
        <div class="container">
            <div class="row">
                <div class="col-xs-12">
                    <div class="section-title text-uppercase text-center">
                        <h2><a href="<?=base_url('/frontend/page/notice')?>">Notice</a></h2>
                        <span class="star"></span>
                        <span class="star"></span>
                        <span class="star"></span>
                    </div>
                </div>
            </div>
            <div class="row text-center">
                    <?php $i = 1; ?>
                    @foreach($notices as $notice)
                        @if($i <= 3)
                            <div class="col-md-4 col-sm-6">
                                <div class="single-notice">
                                    <div class="notice-content">
                                        <h3><a href="{{ base_url('frontend/notice/'.$notice->noticeID) }}">{{ namesorting($notice->title, 45) }}</a></h3>
                                        <div class="notice-meta">
                                            <span class="published-date">
                                                <i class="fa fa-calendar"></i>
                                                {{ date('d M Y', strtotime($notice->date)) }}
                                            </span>
                                        </div>
                                        <p>{{ namesorting($notice->notice, 140)  }}</p>
                                        <a href="{{ base_url('frontend/notice/'.$notice->noticeID) }}" class="read-more-btn">read more <i class="fa fa-long-arrow-right"></i></a>
                                    </div>
                                </div>
                            </div>
                        @endif
                        <?php $i++; ?>
                    @endforeach
            </div>
        </div>
    </section>
    @endif


    @if(customCompute($events))
    <section id="events" class="events-area area-padding">
        <div class="container">
            <div class="row">
                <div class="col-xs-12">
                    <div class="section-title text-uppercase text-center">
                        <h2><a href="<?=base_url('/frontend/page/event')?>">Event</a></h2>
                        <span class="star"></span>
                        <span class="star"></span>
                        <span class="star"></span>
                    </div>
                </div>
            </div>
                    <?php $i = 1; ?>
                    @foreach($events as $event)
                        @if($i <= 3)
                            @if($i%3 == 0)
                                <div class="row">
                            @endif
                                <div class="col-md-4 col-sm-6">

                                    <div class="single-event-list">
                                        <div class="event-img">
                                            <a href="{{ base_url('frontend/event/'.$event->eventID) }}"><img src="{{ imageLinkWithEventImage($event->photo, 'holiday.png') }}" alt=""></a>
                                        </div>
                                        <div class="event-content">
                                            <div class="event-meta">
                                                <div class="event-date first-date">
                                                    {{ date('d', strtotime($event->fdate))  }}
                                                    <span>{{ date('M', strtotime($event->fdate)) }}</span>
                                                </div>
                                                @if($event->fdate != $event->tdate)
                                                    <div class="event-date second-date">
                                                        {{ date('d', strtotime($event->tdate))  }}
                                                        <span>{{ date('M', strtotime($event->tdate)) }}</span>
                                                    </div>
                                                @endif

                                                <div class="event-info">
                                                    <h4><a href="{{ base_url('frontend/event/'.$event->eventID) }}">{{ $event->title }}</a></h4>
                                                    <div class="event-time">
                                                        <span class="event-title">Time: </span>
                                                        <span>{{ date('h:i A', strtotime($event->ftime)) }} - {{ date('h:i A', strtotime($event->ttime)) }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <a id="{{ $event->eventID }}" href="#" class="primary-btn style--two going-event">Going now</a>
                                        </div>
                                    </div>
                                </div>
                            @if($i%3 == 0)
                                </div>
                            @endif
                        @endif
                        <?php $i++; ?>
                    @endforeach
        </div>
    </section>
    @endif

    <section id="testimonials" class="area-padding">
        <div class="container">
            <div class="section-title text-uppercase text-center">
                <h2>Testimonials</h2>
            </div>

            <div class="js-testimonials">
                <div class="row">
                    <div class="col-lg-12">
                        <blockquote class="blockquote">
                            <p class="mb-0">Gradually, I improved my study and gained a very good result in SLC examination. Before the exam I got motivated by my teachers. After the SLC examination, I again got a chance to study +2 level in this institution. Live your life with a positive attitude and automatically you'll have faith in yourself to do better in your life. Keep patience and continue doing hard work.</p>
                            <div class="blockquote-footer pt-3">
                                <figure class="avatar">
                                    <span class="avatar__image">
                                        <img src="{{ base_url('assets/testimonials/0.jpg') }}" alt="Photo of John Doe" width="20">
                                    </span>
                                    <figcaption class="avatar__meta">
                                        <h4 class="avatar__title mb-2">John Doe</h4>
                                        <div class="infos">Student</div>
                                    </figcaption>
                                </figure>
                            </div>
                        </blockquote>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <blockquote class="blockquote">
                            <p class="mb-0">Life itself is an examination. We have to take different exams at various steps of life. If a student thinks that examination is evil, he cannot make his life a good one. His life will be a burden to himself. Therefore, we should take examination as an art and live a happy life.</p>
                            <div class="blockquote-footer pt-3">
                                <figure class="avatar">
                                    <span class="avatar__image">
                                        <img src="{{ base_url('assets/testimonials/0.jpg') }}" alt="Photo of John Doe" width="20">
                                    </span>
                                    <figcaption class="avatar__meta">
                                        <h4 class="avatar__title mb-2">John Doe</h4>
                                        <div class="infos">Student</div>
                                    </figcaption>
                                </figure>
                            </div>
                        </blockquote>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <blockquote class="blockquote">
                            <p class="mb-0">I am very much glad for getting opportunity to study in Sainik Awasiya Mahavidyalaya in Grade XI. Being here I have learned many things from the teachers, the friends and the staff. I cannot forget those days when I fluttered the flag of the Mahavidyalaya as the 'Sports Captain'.</p>
                            <div class="blockquote-footer pt-3">
                                <figure class="avatar">
                                    <span class="avatar__image">
                                        <img src="{{ base_url('assets/testimonials/0.jpg') }}" alt="Photo of John Doe" width="20">
                                    </span>
                                    <figcaption class="avatar__meta">
                                        <h4 class="avatar__title mb-2">Mary Jane</h4>
                                        <div class="infos">Student</div>
                                    </figcaption>
                                </figure>
                            </div>
                        </blockquote>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <iframe src="https://www.google.com/maps/d/embed?mid=13yFOC3bQ9VQJk5tIh-zbPuiTnWQ" frameborder="0" style="border:0; width: 100%; height: 400px;" allowfullscreen="" aria-hidden="false" tabindex="0"></iframe>

    @if(count($popupImages) > 0)
    @foreach ($popupImages as $key=>$popupImage)
    <div class="modal fade" id="popupImageModal{{$key}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
               <div class="modal-body">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                    <div class="popup-item">
                        <img width="100%" src="{{ imageLinkWithDefatulImage($popupImage->file_name, '', 'uploads/popupimages/') }}" />
                        <div class="popup-description mt-2 text-center">
                            <p>{{ $popupImage->title }}</p>
                        </div>
                    </div>
                </div>
           
              <div class="justify-content-center modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">
                    <span aria-hidden="true" class="fa fa-close"></span>
                     Close
                </button>
               </div>
          </div>
        </div>
      </div>
      
      @endforeach
      @endif
@endsection

@section('footerAssetPush')
<script type="text/javascript" src="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
<script type="text/javascript">
    $(document).on('click', '.going-event', function(e) {
        e.preventDefault();
        var id = $(this).attr('id');
        if(id) {
            $.ajax({
                dataType: 'json',
                type: 'POST',
                url: "<?=base_url('frontend/eventGoing')?>",
                data: { 'id':id },
                dataType: "html",
                success: function(data) {
                    var response = jQuery.parseJSON(data);
                    if(response.status == true) {
                        toastr["success"](response.message)
                        toastr.options = {
                            "closeButton": true,
                            "debug": false,
                            "newestOnTop": false,
                            "progressBar": false,
                            "positionClass": "toast-top-right",
                            "preventDuplicates": false,
                            "onclick": null,
                            "showDuration": "500",
                            "hideDuration": "500",
                            "timeOut": "5000",
                            "extendedTimeOut": "1000",
                            "showEasing": "swing",
                            "hideEasing": "linear",
                            "showMethod": "fadeIn",
                            "hideMethod": "fadeOut"
                        }

                    } else {
                        toastr["error"](response.message)
                        toastr.options = {
                            "closeButton": true,
                            "debug": false,
                            "newestOnTop": false,
                            "progressBar": false,
                            "positionClass": "toast-top-right",
                            "preventDuplicates": false,
                            "onclick": null,
                            "showDuration": "500",
                            "hideDuration": "500",
                            "timeOut": "5000",
                            "extendedTimeOut": "1000",
                            "showEasing": "swing",
                            "hideEasing": "linear",
                            "showMethod": "fadeIn",
                            "hideMethod": "fadeOut"
                        }
                    }
                }
            });
        }
    });

</script>

<script>
    $(document).ready(function(){
        var totalImages = "{{ count($popupImages) }}";
        for($i = 0;$i<totalImages;$i++){
            $("#popupImageModal"+$i).modal('show');
        }
        
    });
</script>

<script>
$(function(e){
      //testimonails
  if ($(".js-testimonials").length > 0) {
    $(".js-testimonials").slick({
      infinite: false,
      slidesToShow: 1,
      slidesToScroll: 1,
      arrows: true,
      dots: true,
      autoplay: true,
      centerMode: false,
      variableWidth: false,
      centerPadding: "60px",
      nextArrow: `<button class="carousel-arrow slick-next"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
  <path d="M6.0001 7.2L12.0001 13.2L18.0001 7.2L20.4001 8.4L12.0001 16.8L3.6001 8.4L6.0001 7.2Z"  />
  </svg></button>
  `,
      prevArrow: `<button class="carousel-arrow slick-prev"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
  <path d="M6.0001 7.2L12.0001 13.2L18.0001 7.2L20.4001 8.4L12.0001 16.8L3.6001 8.4L6.0001 7.2Z"  />
  </svg></button>
  `,

      mobileFirst: false,


    });
  }
})
</script>
@endsection