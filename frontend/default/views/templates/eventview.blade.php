@layout('views/layouts/master')

@section('content')

    <section id="events" class="events-area area-padding">
        <div class="container">
            <div class="row">
                <div class="col-md-9">

                    @if (customCompute($eventView))
                        @if (count($eventView->medias) == 1)
                            <div class="single-event-details">
                                <div class="eventView-img">
                                    <a href="#"><img
                                            src="{{ imageLinkWithEventImage($eventView->photo, 'holiday.png') }}"
                                            alt=""></a>
                                </div>
                                <div class="event-content">
                                    <div class="event-meta">
                                        <div class="event-date first-date">
                                            {{ date('d', strtotime($eventView->fdate)) }}
                                            <span>{{ date('M', strtotime($eventView->fdate)) }}</span>
                                        </div>
                                        @if ($eventView->fdate != $eventView->tdate)
                                            <div class="event-date second-date">
                                                {{ date('d', strtotime($eventView->tdate)) }}
                                                <span>{{ date('M', strtotime($eventView->tdate)) }}</span>
                                            </div>
                                        @endif
                                    </div>
                                    
                                </div>
                            </div>
                        @else
                            <div id="main-slider" class="slider-area">
                                @foreach ($eventView->medias as $slider)
                                    <div class="single-slide">
                                        <div class="event-date first-date">
                                            {{ date('d', strtotime($eventView->fdate)) }}
                                            <span>{{ date('M', strtotime($eventView->fdate)) }}</span>
                                        </div>
                                        @if ($eventView->fdate != $eventView->tdate)
                                            <div class="event-date second-date">
                                                {{ date('d', strtotime($eventView->tdate)) }}
                                                <span>{{ date('M', strtotime($eventView->tdate)) }}</span>
                                            </div>
                                        @endif

                                        <img src="{{ imageLinkWithEventImage($slider->attachment, 'holiday.png') }}"
                                            alt="">
                                        <div class="banner-overlay">
                                            <div class="container">
                                                <div class="caption style-2">
                                                    <h2>
                                                    </h2>
                                                    <p>{{ $eventView->details }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                @endforeach

                            </div>
                        @endif

                        <div class="event-info">
                            <h4>{{ $eventView->title }}</h4>
                            <div class="event-time">
                                <span class="event-title">Time: </span>
                                <span>{{ date('h:i A', strtotime($eventView->ftime)) }} -
                                    {{ date('h:i A', strtotime($eventView->ttime)) }}</span>
                            </div>
                        </div>
                        <p>{{ $eventView->details }}</p>
                    @endif




                </div>
                <div class="col-md-3">
                    <h4 class="recent-event-title text-capitalize">Recent events</h4>

                    @if (customCompute($events))
                        <?php $i = 1; ?>
                        @foreach ($events as $event)
                            @if ($i <= 9)
                                <div class="recent-events-list">
                                    <div class="eventView-img">
                                        <a href="{{ base_url('frontend/event/' . $event->eventID) }}"><img
                                                src="{{ imageLinkWithEventImage($event->photo, 'holiday.png') }}"
                                                alt=""></a>
                                    </div>

                                    <div class="event-content">
                                        <div class="event-meta">
                                            <div class="event-info">
                                                <h4><a
                                                        href="{{ base_url('frontend/event/' . $event->eventID) }}">{{ $event->title }}</a>
                                                </h4>
                                                <div class="event-time">
                                                    <span class="event-title">Time: </span>
                                                    <span>{{ date('h:i A', strtotime($event->ftime)) }} -
                                                        {{ date('h:i A', strtotime($event->ttime)) }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </section>

@endsection
