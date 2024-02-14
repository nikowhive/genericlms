@layout('views/layouts/master')

@section('headerAssetPush')
<style>
    .card {
        border-radius: 10px;
        background-color: #fff;
        box-shadow: 0px 3px 5px rgb(0 0 0 / 10%);
        padding: 0;
        position: relative;
        --padding-y: 16px;
        --padding-x: 16px;
    }

    .card-img-top {
        display: inline-block;
        border-radius: 10px 10px 0px 0px;
    }


    .section-title.text-center p {
        font-size: 20px;
        line-height: 24px;
    }

    .read-more a {
        color: #23292f;
        font-size: 13px;
        font-weight: bold;
        float: right;
    }
</style>

@endsection


@section('content')
<link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css" />
@if (customCompute($sliders))
<div id="main-slider" class="slider-area">
    @foreach ($sliders as $slider)
    <div class="single-slide">
        <img src="{{ base_url('uploads/gallery/' . $slider->file_name) }}" alt="">
    </div>
    @endforeach
</div>
@endif

<section id="about" class="about-area area-padding">
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <div class="section-title text-center">
                    {{ htmlspecialchars_decode($page->content) }}
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <div class="section-title text-uppercase text-center">
                    <h2>Explore our courses</h2>
                </div>
            </div>
        </div>
        <div class="row">
            @foreach ($classgroups as $value)
            <div class="col-sm-4">
                <div class="card">
                    <?php
                    $image = $value->photo == '' ? 'assets/images/no-image-preview.png' : 'uploads/images/' . $value->photo;
                    ?>
                    <img class="card-img-top" src="<?= base_url($image) ?>" alt="Card image cap">
                    <input type="hidden" class="classgroupID" value="<?= $value->classgroupID ?>">
                    <div class="card-body">
                        <a href="{{ base_url('frontend/page/courses/') }}" class=""> <h2 class="card-title">{{ $value->group }}</h2></a>
                        @foreach ($value->classes as $i => $k)
                        <li class="card-text"> <a href="{{ base_url('frontend/page/course-detail/'.$k->classesID) }}" class="">{{ $k->classes }} </a></li>
                        @endforeach

                        @if(count($value->classes) >= 2)
                        <div class="read-more desc"><a href="{{ base_url('frontend/page/courses/') }}" class=""><?= $value->count_classes>2?($value->count_classes-2)." "."more..":'' ?> </a></div>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

@include('views/templates/feed')

@endsection

