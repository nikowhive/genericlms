@layout('views/layouts/course')

@section('headerAssetPush')
    
@endsection

@section('content')
    <section class="hero">
        <div class="container">
                    <header class="hero-header">
                        <h1 class="hero-title">
                        Machine Learning A-Z™: Hands-On Python & R In Data Science
                        </h1>
                        <div class="hero-lead">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Id risus nunc eget turpis id fringilla nulla ac.</div>
                    </header>
               
        </div>
        <img src="https://source.unsplash.com/random/1400x400" class="hero-image" alt="feature image" srcset="">
    </section>
    @if (customCompute($featured_image))
        <div class="featured-slider">
            <img src="{{ base_url('uploads/gallery/' . $featured_image->file_name) }}"
                alt="{{ $featured_image->file_alt_text }}">
        </div>
    @endif
 


    @if (customCompute($classgroups))
        @foreach ($classgroups as $i => $value)
            @if (customCompute($value->classes))
                <?php $i++; ?>
                <section id="classgroup<?= $i ?>" class="section section-stripe">
        <div class="container">
             <header class="header section-header">
                <h2 class="section-title">
                {{ $value->group }}
                </h2>
            </header>
         

           
            <div class="cards">
                @foreach ($value->classes as $i => $k)
                
                <div class="card">
                    <div class="ratio ratio-16x9">
                        <?php $image = $k->photo == '' ? 'assets/images/no-image-preview.png' : 'uploads/images/' . $k->photo; ?>
                                <img class="ratio-img" src="<?= base_url($image) ?>">
                    </div>
                    <div class="card-body">
                        <a href="{{ base_url('frontend/page/course-detail/' . $k->classesID) }}" class="card-title stretched-link"> -->
                            {{ $k->classes }} 
                        </a>
                        <p class="card-text">
                            {{ $k->note }}
                        </p>
                        <!-- <p>
                            @foreach($k->courses as $kay)
                            <a href="{{ base_url('frontend/page/course-detail/'.$k->classesID.'/'.$kay->id) }}">
                            {{ $kay->coursename }} 
                            </a>
                            @endforeach
                        </p> -->
                        <div class="card-footer">
                            {{$k->extra->total_hours}}
                            Total hours... 
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

        </div>
    </section>
    @endif
    @endforeach
    @endif
@endsection
