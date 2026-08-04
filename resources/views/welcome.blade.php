@extends('layouts.app')

@section('content')
@php
    $sliders = \App\Models\Slider::where('is_active', true)->orderBy('sort_order')->get();
    $counter = \App\Models\Counter::where('is_active', true)->orderBy('sort_order')->first();
    
    // Dynamic Event Management Stats
    $totalConferences = \App\Models\Conference::count();
    $totalSubmissions = \App\Models\PaperSubmission::count();
    $totalSessions = \App\Models\PresentationSession::count();
    $totalOfficialParticipants = \App\Models\User::whereHas('paperSubmissions')->whereNotNull('institution')->distinct('institution')->count('institution');

    // New Data Fetching for Homepage
    $timelines = \App\Models\Timeline::where('is_active', true)->orderBy('sort_order')->get();
    $themes = \App\Models\Theme::where('is_active', true)->orderBy('sort_order')->get();
    $posts = \App\Models\Post::latest()->take(3)->get();
    $galleries = \App\Models\Gallery::latest()->get();
    $partners = \App\Models\OfficialPartner::where('is_active', true)->orderBy('sort_order')->get();
    $faqs = \App\Models\Faq::where('is_active', true)->orderBy('sort_order')->get();
    $videoProfiles = \App\Models\VideoProfile::where('is_active', true)->orderBy('sort_order')->get();
    $aboutSettings = \App\Models\Setting::find(1);
@endphp

<style>
    .mySwiper {
        width: 100%;
        height: 80vh;
        position: relative;
    }
    .swiper-slide {
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }
    .slider-media {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 1;
        object-fit: cover;
    }
    .slider-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: transparent; /* Overlay removed as requested */
        z-index: 2;
    }
    .slider-content {
        position: relative;
        z-index: 3;
        text-align: center;
        color: #ffffff;
        padding: 0 20px;
        max-width: 900px;
    }
    .slider-title {
        font-size: 3.5rem;
        font-weight: 700;
        margin-bottom: 20px;
        letter-spacing: -0.5px;
        font-family: var(--font-heading);
        text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
    }
    .slider-subtitle {
        font-size: 1.3rem;
        margin-bottom: 35px;
        font-weight: 300;
        font-family: var(--font-body);
        text-shadow: 1px 1px 2px rgba(0,0,0,0.5);
        color: rgba(255,255,255,0.9);
        line-height: 1.6;
    }
    
    .swiper-button-next, .swiper-button-prev {
        color: #fff !important;
        text-shadow: 0px 0px 5px rgba(0,0,0,0.8);
    }
    .swiper-pagination-bullet {
        background: #fff !important;
        opacity: 0.7;
    }
    .swiper-pagination-bullet-active {
        background: var(--primary-color) !important;
        opacity: 1;
    }
    
    @media (max-width: 768px) {
        .slider-title {
            font-size: 2.5rem;
        }
        .slider-subtitle {
            font-size: 1.1rem;
        }
        .mySwiper {
            height: 60vh;
        }
    }
</style>

@if($sliders->count() > 0)
    <!-- Dynamic Swiper Slider Section -->
    <section class="swiper mySwiper">
        <div class="swiper-wrapper">
            @foreach($sliders as $slider)
            <div class="swiper-slide">
                @if($slider->media_type === 'video')
                    <video autoplay loop muted playsinline class="slider-media">
                        <source src="{{ Storage::url($slider->media_path) }}" type="video/mp4">
                    </video>
                @else
                    <div class="slider-media" style="background-image: url('{{ Storage::url($slider->media_path) }}'); background-size: cover; background-position: center;"></div>
                @endif
                <div class="slider-overlay"></div>
                <div class="slider-content">
                    @if($slider->title)
                        <h1 class="slider-title">{{ $slider->title }}</h1>
                    @endif
                    @if($slider->subtitle)
                        <p class="slider-subtitle">{{ $slider->subtitle }}</p>
                    @endif
                    @if($slider->button_text && $slider->button_url)
                        <a href="{{ $slider->button_url }}" class="btn btn-primary slider-btn">{{ $slider->button_text }}</a>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
        <!-- Add Pagination -->
        <div class="swiper-pagination"></div>
        <!-- Add Navigation -->
        <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div>
    </section>
@else
    <!-- Fallback Hero Section -->
    <section class="hero">
        <div class="container">
            <h1 class="hero-title">Welcome to AICIS 2026</h1>
            <p class="hero-subtitle">Annual International Conference on Islamic Studies, proudly hosted by UIN Siber Syekh Nurjati Cirebon. Shaping the future of global Islamic scholarship through dialogue and innovation.</p>
            
            <a href="#about" class="btn btn-primary">Discover More</a>
        </div>
    </section>
@endif

@if($counter || true)
<!-- Countdown & Statistics Section -->
<section class="countdown-section" style="padding: 60px 0; background: url('{{ asset('images/cyber-bg.png') }}') no-repeat center center; background-size: cover; background-attachment: fixed; position: relative;">
    <!-- Dark green overlay to restore original color while showing cyber background -->
    <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(27, 94, 32, 0.85); z-index: 1;"></div>
    
    <div class="container" style="position: relative; z-index: 2;">
        <div style="display: flex; flex-wrap: wrap; margin: -15px; align-items: center;">
            
            <!-- Countdown Side -->
            <div style="flex: 1 1 50%; min-width: 300px; padding: 15px;">
                @if($counter)
                <div style="text-align: center; margin-bottom: 25px; color: #fff;">
                    <h3 style="margin:0; font-size: 1.2rem; font-weight: normal; letter-spacing: 2px; text-transform: uppercase; color: #dfb162;">
                        {{ $counter->name }} - {{ \Carbon\Carbon::parse($counter->target_datetime)->format('d F Y') }}
                    </h3>
                </div>
                <div class="countdown-wrapper" id="countdown-timer" data-target="{{ \Carbon\Carbon::parse($counter->target_datetime)->timestamp * 1000 }}">
                    <div class="countdown-box">
                        <span class="countdown-num" id="days" style="display: block; font-size: 2.5rem; font-weight: 800; color: #dfb162; line-height: 1;">00</span>
                        <span class="countdown-label" style="display: block; color: #fff; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 2px; margin-top: 10px;">Days</span>
                    </div>
                    <div class="countdown-box">
                        <span class="countdown-num" id="hours" style="display: block; font-size: 2.5rem; font-weight: 800; color: #dfb162; line-height: 1;">00</span>
                        <span class="countdown-label" style="display: block; color: #fff; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 2px; margin-top: 10px;">Hours</span>
                    </div>
                    <div class="countdown-box">
                        <span class="countdown-num" id="minutes" style="display: block; font-size: 2.5rem; font-weight: 800; color: #dfb162; line-height: 1;">00</span>
                        <span class="countdown-label" style="display: block; color: #fff; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 2px; margin-top: 10px;">Minutes</span>
                    </div>
                    <div class="countdown-box">
                        <span class="countdown-num" id="seconds" style="display: block; font-size: 2.5rem; font-weight: 800; color: #dfb162; line-height: 1;">00</span>
                        <span class="countdown-label" style="display: block; color: #fff; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 2px; margin-top: 10px;">Seconds</span>
                    </div>
                </div>
                @endif
            </div>

            <!-- Statistics Side -->
            <div style="flex: 1 1 50%; min-width: 300px; padding: 15px;">
                <div style="text-align: center; margin-bottom: 25px; color: #fff;">
                    <h3 style="margin:0; font-size: 1.2rem; font-weight: normal; letter-spacing: 2px; text-transform: uppercase; color: #dfb162;">
                        Event Infographics
                    </h3>
                </div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; max-width: 500px; margin: 0 auto;">
                    <div style="background: rgba(255, 255, 255, 0.1); padding: 1.5rem 1rem; text-align: center; border-radius: 8px;">
                        <div style="font-size: 2rem; font-weight: 800; color: #dfb162; line-height: 1; margin-bottom: 0.5rem;">{{ number_format($totalConferences) }}</div>
                        <div style="color: #fff; font-size: 0.8rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">Conferences</div>
                    </div>
                    <div style="background: rgba(255, 255, 255, 0.1); padding: 1.5rem 1rem; text-align: center; border-radius: 8px;">
                        <div style="font-size: 2rem; font-weight: 800; color: #dfb162; line-height: 1; margin-bottom: 0.5rem;">{{ number_format($totalSubmissions) }}</div>
                        <div style="color: #fff; font-size: 0.8rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">Paper Submissions</div>
                    </div>
                    <div style="background: rgba(255, 255, 255, 0.1); padding: 1.5rem 1rem; text-align: center; border-radius: 8px;">
                        <div style="font-size: 2rem; font-weight: 800; color: #dfb162; line-height: 1; margin-bottom: 0.5rem;">{{ number_format($totalSessions) }}</div>
                        <div style="color: #fff; font-size: 0.8rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">Presentation Sessions</div>
                    </div>
                    <div style="background: rgba(255, 255, 255, 0.1); padding: 1.5rem 1rem; text-align: center; border-radius: 8px;">
                        <div style="font-size: 2rem; font-weight: 800; color: #dfb162; line-height: 1; margin-bottom: 0.5rem;">{{ number_format($totalOfficialParticipants) }}</div>
                        <div style="color: #fff; font-size: 0.8rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">Official Participants</div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>
@endif

<!-- About Section -->
<section id="about" class="section" style="padding: 80px 0;">
    <div class="container">
        <div style="display: flex; flex-wrap: wrap; margin: -15px; align-items: center;">
            <div style="flex: 1 1 50%; min-width: 300px; padding: 15px;">
                <h2 class="section-title" style="text-align: left; margin-bottom: 25px;">{{ $aboutSettings?->about_title ?? 'About AICIS' }}</h2>
                <div style="width: 40px; height: 3px; background-color: var(--accent-color); margin-bottom: 30px;"></div>
                <div style="font-size: 0.95rem; line-height: 1.8; color: var(--text-light); margin-bottom: 35px; text-align: justify;">
                    @if($aboutSettings && $aboutSettings->about_content)
                        {!! nl2br(e(Str::limit(strip_tags($aboutSettings->about_content), 500))) !!}
                    @else
                        <p>In an era defined by urgent global challenges—environmental crises, armed conflicts, economic disparities, and public health concerns—multidisciplinary collaboration is vital to crafting solutions rooted in justice and sustainability.</p><p>Hosted by UIN Siber Syekh Nurjati Cirebon, and proudly sponsored by the Directorate General of Islamic Education, Ministry of Religious Affairs of the Republic of Indonesia, the AICIS invites scholars, practitioners, and innovators to explore the intersections of ecotheology, technological advancements, and Islamic scholarship.</p>
                    @endif
                </div>
                <a href="{{ $aboutSettings?->about_button_url ?? url('/about') }}" class="btn btn-primary" style="display: inline-flex; align-items: center; gap: 10px;">
                    READ MORE <i class="fas fa-angle-right"></i>
                </a>
            </div>
            <div style="flex: 1 1 50%; min-width: 300px; padding: 15px;">
                @if($videoProfiles->count() > 0)
                    <div class="swiper videoSwiper" style="border-radius: 10px; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
                        <div class="swiper-wrapper">
                            @foreach($videoProfiles as $video)
                                <div class="swiper-slide" style="background: #000; width: 100%;">
                                    <div style="position: relative; width: 100%; padding-bottom: 56.25%; height: 0; overflow: hidden; background: #000;">
                                        @php
                                            preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/i', $video->youtube_url, $matches);
                                            $youtubeId = $matches[1] ?? '';
                                        @endphp
                                        @if($youtubeId)
                                            <!-- Pointer events none prevents iframe from stealing swipe gestures on mobile -->
                                            <iframe id="yt-player-{{ $loop->index }}" class="yt-bg-player" data-index="{{ $loop->index }}" src="https://www.youtube.com/embed/{{ $youtubeId }}?autoplay=1&mute=1&controls=0&showinfo=0&rel=0&enablejsapi=1&origin={{ urlencode(request()->getSchemeAndHttpHost()) }}" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; border: 0; pointer-events: none;" allow="autoplay; fullscreen"></iframe>
                                            <div class="video-overlay" onclick="openVideoModal('{{ $youtubeId }}')" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; cursor: pointer; z-index: 10;"></div>
                                        @else
                                            <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; color: #fff;">Invalid Video URL</div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        @if($videoProfiles->count() > 1)
                        <div class="swiper-pagination"></div>
                        @endif
                    </div>
                @else
                    <div style="background-image: url('{{ asset('images/about-placeholder.jpg') }}'); background-size: cover; background-position: center; height: 400px; border-radius: 10px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); background-color: #eee; display: flex; align-items: center; justify-content: center; color: #999;">
                        <i class="fas fa-university" style="font-size: 5rem; opacity: 0.5;"></i>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>

@if($timelines->count() > 0 || $themes->count() > 0)
<!-- Programs & Timeline Section -->
<section id="programs-timeline" class="section" style="padding: 80px 0; background: url('{{ asset('images/pattern-bg.png') }}'); background-color: #f8f9fa;">
    <div class="container">
        <div style="display: flex; flex-wrap: wrap; margin: -15px;">
            
            @if($themes->count() > 0)
            <!-- Grand Theme Column -->
            <div style="flex: 1 1 50%; min-width: 300px; padding: 15px;">
                <h2 style="text-align: left; margin-bottom: 10px; color: #1e3a5f; font-size: 1.6rem; font-family: var(--font-heading); font-weight: 500;">Grand Theme</h2>
                <div style="width: 40px; height: 2px; background-color: var(--accent-color); margin-bottom: 30px;"></div>
                
                <div style="display: grid; grid-template-columns: 1fr; gap: 12px;">
                    @foreach($themes as $index => $theme)
                    @php
                        $isEven = $index % 2 === 0;
                        
                        // Clean Elegant Green
                        $bgStyleEven = "background: linear-gradient(135deg, #0f3014 0%, #16421a 100%); box-shadow: 0 5px 15px rgba(15, 48, 20, 0.1); border: 1px solid rgba(223, 177, 98, 0.1);";
                        $numberColorEven = "rgba(255, 255, 255, 0.03)"; 
                        $iconBgEven = "rgba(223, 177, 98, 0.1)";
                        $iconColorEven = "#dfb162"; 
                        $titleColorEven = "#dfb162"; 
                        $descColorEven = "rgba(255, 255, 255, 0.75)";
                        
                        // Clean Elegant Gold
                        $bgStyleOdd = "background: linear-gradient(135deg, #dfb162 0%, #c49947 100%); box-shadow: 0 5px 15px rgba(223, 177, 98, 0.15); border: 1px solid rgba(255, 255, 255, 0.2);";
                        $numberColorOdd = "rgba(15, 48, 20, 0.05)"; 
                        $iconBgOdd = "rgba(255, 255, 255, 0.3)";
                        $iconColorOdd = "#0f3014"; 
                        $titleColorOdd = "#0f3014"; 
                        $descColorOdd = "rgba(15, 48, 20, 0.85)";

                        $bgStyle = $isEven ? $bgStyleEven : $bgStyleOdd;
                        $numberColor = $isEven ? $numberColorEven : $numberColorOdd;
                        $iconBg = $isEven ? $iconBgEven : $iconBgOdd;
                        $iconColor = $isEven ? $iconColorEven : $iconColorOdd;
                        $titleColor = $isEven ? $titleColorEven : $titleColorOdd;
                        $descColor = $isEven ? $descColorEven : $descColorOdd;
                    @endphp
                    <div style="{{ $bgStyle }} padding: 12px 18px; border-radius: 8px; position: relative; overflow: hidden; display: flex; align-items: flex-start; gap: 12px; transition: transform 0.3s ease;">
                        <!-- Number Watermark -->
                        <div style="font-size: 2.5rem; font-weight: 700; color: {{ $numberColor }}; position: absolute; right: 15px; top: 5px; line-height: 1; font-family: var(--font-heading);">
                            {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}
                        </div>
                        
                        <!-- Icon -->
                        <div style="width: 40px; height: 40px; flex-shrink: 0; background: {{ $iconBg }}; color: {{ $iconColor }}; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1rem;">
                            <i class="fas fa-leaf"></i>
                        </div>
                        
                        <!-- Content -->
                        <div style="position: relative; z-index: 1;">
                            <h5 style="font-size: 1.05rem; font-weight: 500; margin-bottom: 2px; color: {{ $titleColor }};">{{ $theme->name }}</h5>
                            <p style="color: {{ $descColor }}; font-size: 0.85rem; line-height: 1.4; margin: 0; font-weight: 300;">{{ $theme->description }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            @if($timelines->count() > 0)
            <!-- Timeline Column -->
            <div style="flex: 1 1 50%; min-width: 300px; padding: 15px;">
                <h2 style="text-align: left; margin-bottom: 10px; color: #1e3a5f; font-size: 1.6rem; font-family: var(--font-heading); font-weight: 500;">Timeline</h2>
                <div style="width: 40px; height: 2px; background-color: var(--accent-color); margin-bottom: 30px;"></div>
                
                <div style="display: flex; flex-direction: column; gap: 20px;">
                    @foreach($timelines as $timeline)
                    @php
                        $from = \Carbon\Carbon::parse($timeline->date_from);
                        $until = $timeline->date_until ? \Carbon\Carbon::parse($timeline->date_until) : null;
                        
                        // Formatting day string
                        if ($until && $from->format('Y-m-d') !== $until->format('Y-m-d')) {
                            $dayString = $from->format('d') . ' - ' . $until->format('d');
                        } else {
                            $dayString = $from->format('d');
                        }
                        
                        // Formatting month/year string
                        if ($until && $from->format('Y-m') !== $until->format('Y-m')) {
                            if ($from->format('Y') !== $until->format('Y')) {
                                $monthString = $from->format('F Y') . ' – ' . $until->format('F Y');
                            } else {
                                $monthString = $from->format('F') . ' – ' . $until->format('F Y');
                            }
                        } else {
                            $monthString = $from->format('F Y');
                        }
                    @endphp
                    <div style="display: flex; box-shadow: 0 4px 10px rgba(0,0,0,0.02); border-radius: 8px; overflow: hidden; min-height: 80px;">
                        <div style="background-color: #1b5e20; color: #fff; width: 35%; display: flex; flex-direction: column; justify-content: center; align-items: center; padding: 15px; text-align: center; border-radius: 8px 0 0 8px;">
                            <div style="font-size: 1.1rem; font-weight: 500; margin-bottom: 5px; line-height: 1.2; font-family: var(--font-heading);">{{ $dayString }}</div>
                            <div style="font-size: 0.75rem; font-weight: 400; color: rgba(223, 177, 98, 0.9);">{{ $monthString }}</div>
                        </div>
                        <div style="background-color: #fff; border: 1px solid rgba(27, 94, 32, 0.1); border-left: none; width: 65%; display: flex; align-items: center; padding: 15px 20px; border-radius: 0 8px 8px 0;">
                            <h4 style="font-size: 0.95rem; margin: 0; line-height: 1.5; color: #333; font-weight: 500;">{{ $timeline->title }}</h4>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

        </div>
    </div>
</section>
</section>
@endif

<!-- Call for Papers Process Section -->
<section id="cfp-process" class="section" style="padding: 80px 0; background-color: #fff;">
    <div class="container">
        <div style="text-align: center; margin-bottom: 50px;">
            <h2 style="color: #1e3a5f; font-size: 2.2rem; font-family: var(--font-heading); font-weight: 700; margin-bottom: 15px;">Call for Papers Process</h2>
            <div style="width: 60px; height: 3px; background-color: var(--accent-color); margin: 0 auto 20px;"></div>
            <p style="color: #666; max-width: 600px; margin: 0 auto;">Follow our comprehensive and rigorous double-blind peer review process to present your research at AICIS 2026.</p>
        </div>

        <div style="max-width: 900px; margin: 0 auto; position: relative;">
            <!-- Vertical Line -->
            <div style="position: absolute; left: 50%; top: 0; bottom: 0; width: 4px; background: #e2e8f0; transform: translateX(-50%); border-radius: 4px; z-index: 0;"></div>

            <style>
                .cfp-step { display: flex; align-items: center; margin-bottom: 30px; position: relative; z-index: 1; }
                .cfp-step:nth-child(even) { flex-direction: row-reverse; }
                .cfp-content { width: 45%; padding: 20px; background: #f8fafc; border-radius: 12px; border: 1px solid #e2e8f0; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05); transition: transform 0.3s, box-shadow 0.3s; }
                .cfp-step:hover .cfp-content { transform: translateY(-5px); box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1); border-color: #cbd5e1; }
                .cfp-step:nth-child(odd) .cfp-content { text-align: right; }
                .cfp-step:nth-child(even) .cfp-content { text-align: left; }
                .cfp-icon { width: 60px; height: 60px; background: linear-gradient(135deg, #1b5e20 0%, #2e7d32 100%); color: #fff; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; border: 4px solid #fff; box-shadow: 0 0 0 4px #e2e8f0; z-index: 2; flex-shrink: 0; margin: 0 5%; transition: all 0.3s; }
                .cfp-step:hover .cfp-icon { background: linear-gradient(135deg, #dfb162 0%, #c49947 100%); box-shadow: 0 0 0 4px #dfb162; color: #1b5e20; transform: scale(1.1); }
                .cfp-title { font-size: 1.1rem; font-weight: 700; color: #1e293b; margin-bottom: 8px; font-family: var(--font-heading); }
                .cfp-desc { font-size: 0.85rem; color: #64748b; line-height: 1.5; margin: 0; }
                
                @media (max-width: 768px) {
                    .cfp-step, .cfp-step:nth-child(even) { flex-direction: column; align-items: center; text-align: center; }
                    .cfp-content, .cfp-step:nth-child(odd) .cfp-content, .cfp-step:nth-child(even) .cfp-content { width: 90%; text-align: center; margin-top: 15px; }
                    .cfp-icon { margin: 0; }
                    div[style*="left: 50%"] { display: none; }
                }
            </style>

            <div class="cfp-step">
                <div class="cfp-content">
                    <div class="cfp-title">1. Announcement & Registration</div>
                    <p class="cfp-desc">Account registration for prospective presenters and official participants.</p>
                </div>
                <div class="cfp-icon"><i class="fas fa-bullhorn"></i></div>
                <div style="width: 45%;"></div>
            </div>

            <div class="cfp-step">
                <div class="cfp-content">
                    <div class="cfp-title">2. Submission</div>
                    <p class="cfp-desc">Submission of abstracts and full papers via the dashboard.</p>
                </div>
                <div class="cfp-icon"><i class="fas fa-file-upload"></i></div>
                <div style="width: 45%;"></div>
            </div>

            <div class="cfp-step">
                <div class="cfp-content">
                    <div class="cfp-title">3. Administrative Check</div>
                    <p class="cfp-desc">Checking template conformity, plagiarism level, and conference scope.</p>
                </div>
                <div class="cfp-icon"><i class="fas fa-clipboard-check"></i></div>
                <div style="width: 45%;"></div>
            </div>

            <div class="cfp-step">
                <div class="cfp-content">
                    <div class="cfp-title">4. Double Blind Peer Review</div>
                    <p class="cfp-desc">Anonymous review by 2-3 experts to maintain objectivity.</p>
                </div>
                <div class="cfp-icon"><i class="fas fa-user-secret"></i></div>
                <div style="width: 45%;"></div>
            </div>

            <!-- Fade Out Button -->
            <div style="position: relative; z-index: 3; text-align: center; margin-top: -30px; padding-top: 80px; background: linear-gradient(to bottom, rgba(255,255,255,0) 0%, rgba(255,255,255,1) 80%);">
                <button onclick="openCfpModal()" class="btn btn-primary" style="box-shadow: 0 4px 15px rgba(212, 175, 55, 0.4); padding: 12px 30px; border-radius: 30px; font-weight: 600; cursor: pointer;">
                    <i class="fas fa-expand-alt" style="margin-right: 8px;"></i> See More Details
                </button>
            </div>
        </div>
    </div>
</section>

<!-- CFP Modal -->
<div id="cfp-modal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(4, 37, 25, 0.85); z-index: 9999; backdrop-filter: blur(12px); justify-content: center; align-items: center; transition: opacity 0.3s ease;">
    <div id="cfp-modal-content" style="background: #fff; width: 90%; max-width: 1000px; max-height: 90vh; border-radius: 24px; box-shadow: 0 30px 60px rgba(0,0,0,0.4), 0 0 40px rgba(212, 175, 55, 0.2); border: 1px solid rgba(212, 175, 55, 0.4); display: flex; flex-direction: column; overflow: hidden; transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275); transform: scale(0.95); opacity: 0;">
        
        <div style="padding: 20px 30px; background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(10px); color: #1e3a5f; display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid rgba(212, 175, 55, 0.3); z-index: 10;">
            <h3 style="margin: 0; font-family: var(--font-heading); font-size: 1.5rem;"><i class="fas fa-project-diagram" style="margin-right: 10px; color: #dfb162;"></i> Call for Papers Process</h3>
            <div style="display: flex; gap: 10px;">
                <button onclick="maximizeCfpModal()" style="background: #f1f5f9; border: none; color: #64748b; font-size: 1rem; cursor: pointer; width: 40px; height: 40px; border-radius: 50%; display: flex; justify-content: center; align-items: center; transition: all 0.2s;" onmouseover="this.style.background='#e2e8f0'; this.style.color='#1e3a5f'" onmouseout="this.style.background='#f1f5f9'; this.style.color='#64748b'" title="Maximize">
                    <i class="fas fa-expand" id="cfp-maximize-icon"></i>
                </button>
                <button onclick="closeCfpModal()" style="background: #fee2e2; border: none; color: #ef4444; font-size: 1.1rem; cursor: pointer; width: 40px; height: 40px; border-radius: 50%; display: flex; justify-content: center; align-items: center; transition: all 0.2s;" onmouseover="this.style.background='#fecaca'; this.style.color='#dc2626'" onmouseout="this.style.background='#fee2e2'; this.style.color='#ef4444'" title="Close">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
        
        <div style="padding: 40px 20px; overflow-y: auto; background-color: #fafafa; flex-grow: 1; position: relative;">
            <style>
                /* Sleek scrollbar for the modal */
                #cfp-modal-content ::-webkit-scrollbar { width: 8px; }
                #cfp-modal-content ::-webkit-scrollbar-track { background: #f1f1f1; border-radius: 4px; }
                #cfp-modal-content ::-webkit-scrollbar-thumb { background: #dfb162; border-radius: 4px; }
                #cfp-modal-content ::-webkit-scrollbar-thumb:hover { background: #c49947; }
            </style>
            <div style="max-width: 900px; margin: 0 auto; position: relative;">
                <div style="position: absolute; left: 50%; top: 0; bottom: 0; width: 4px; background: #e2e8f0; transform: translateX(-50%); border-radius: 4px; z-index: 0;"></div>
                
                <!-- Duplicate Steps 1-4 for Modal -->
                <div class="cfp-step">
                    <div class="cfp-content">
                        <div class="cfp-title">1. Announcement & Registration</div>
                        <p class="cfp-desc">Account registration for prospective presenters and official participants.</p>
                    </div>
                    <div class="cfp-icon"><i class="fas fa-bullhorn"></i></div>
                    <div style="width: 45%;"></div>
                </div>
                <div class="cfp-step">
                    <div class="cfp-content">
                        <div class="cfp-title">2. Submission</div>
                        <p class="cfp-desc">Submission of abstracts and full papers via the dashboard.</p>
                    </div>
                    <div class="cfp-icon"><i class="fas fa-file-upload"></i></div>
                    <div style="width: 45%;"></div>
                </div>
                <div class="cfp-step">
                    <div class="cfp-content">
                        <div class="cfp-title">3. Administrative Check</div>
                        <p class="cfp-desc">Checking template conformity, plagiarism level, and conference scope.</p>
                    </div>
                    <div class="cfp-icon"><i class="fas fa-clipboard-check"></i></div>
                    <div style="width: 45%;"></div>
                </div>
                <div class="cfp-step">
                    <div class="cfp-content">
                        <div class="cfp-title">4. Double Blind Peer Review</div>
                        <p class="cfp-desc">Anonymous review by 2-3 experts to maintain objectivity.</p>
                    </div>
                    <div class="cfp-icon"><i class="fas fa-user-secret"></i></div>
                    <div style="width: 45%;"></div>
                </div>

            <div class="cfp-step">
                <div class="cfp-content">
                    <div class="cfp-title">5. Review Results</div>
                    <p class="cfp-desc">Final assessment: Accepted, Accepted with Revision, or Rejected.</p>
                </div>
                <div class="cfp-icon"><i class="fas fa-star-half-stroke"></i></div>
                <div style="width: 45%;"></div>
            </div>

            <div class="cfp-step">
                <div class="cfp-content">
                    <div class="cfp-title">6. Author Revision</div>
                    <p class="cfp-desc">Authors re-upload the revised paper according to feedback.</p>
                </div>
                <div class="cfp-icon"><i class="fas fa-pen-to-square"></i></div>
                <div style="width: 45%;"></div>
            </div>

            <div class="cfp-step">
                <div class="cfp-content">
                    <div class="cfp-title">7. Final Acceptance (LoA)</div>
                    <p class="cfp-desc">Issuance of Letter of Acceptance for accepted papers.</p>
                </div>
                <div class="cfp-icon"><i class="fas fa-certificate"></i></div>
                <div style="width: 45%;"></div>
            </div>

            <div class="cfp-step">
                <div class="cfp-content">
                    <div class="cfp-title">8. Registration & Payment</div>
                    <p class="cfp-desc">Financial administration process for presenters.</p>
                </div>
                <div class="cfp-icon"><i class="fas fa-credit-card"></i></div>
                <div style="width: 45%;"></div>
            </div>

            <div class="cfp-step">
                <div class="cfp-content">
                    <div class="cfp-title">9. Presentasi</div>
                    <p class="cfp-desc">Presentation session in Plenary or Parallel Session.</p>
                </div>
                <div class="cfp-icon"><i class="fas fa-chalkboard-user"></i></div>
                <div style="width: 45%;"></div>
            </div>

            <div class="cfp-step">
                <div class="cfp-content">
                    <div class="cfp-title">10. Best Paper Selection</div>
                    <p class="cfp-desc">Selection and awarding of the best papers.</p>
                </div>
                <div class="cfp-icon"><i class="fas fa-trophy"></i></div>
                <div style="width: 45%;"></div>
            </div>

            <div class="cfp-step">
                <div class="cfp-content">
                    <div class="cfp-title">11. Proceedings</div>
                    <p class="cfp-desc">Publication of all selected papers in Conference Proceedings.</p>
                </div>
                <div class="cfp-icon"><i class="fas fa-book-journal-whills"></i></div>
                <div style="width: 45%;"></div>
            </div>

            <div class="cfp-step">
                <div class="cfp-content">
                    <div class="cfp-title">12. Selected Papers Publication</div>
                    <p class="cfp-desc">Recommendation for publication in Scopus, SINTA, or International Publisher Journals.</p>
                </div>
                <div class="cfp-icon"><i class="fas fa-globe"></i></div>
                <div style="width: 45%;"></div>
            </div>
            
        </div>
            </div>
        </div>
    </div>
</div>

<script>
    function openCfpModal() {
        const modal = document.getElementById('cfp-modal');
        const content = document.getElementById('cfp-modal-content');
        modal.style.display = 'flex';
        // Trigger reflow for animation
        void modal.offsetWidth;
        content.style.transform = 'scale(1)';
        content.style.opacity = '1';
        document.body.style.overflow = 'hidden';
    }
    
    function closeCfpModal() {
        const modal = document.getElementById('cfp-modal');
        const content = document.getElementById('cfp-modal-content');
        content.style.transform = 'scale(0.95)';
        content.style.opacity = '0';
        setTimeout(() => {
            modal.style.display = 'none';
            document.body.style.overflow = '';
        }, 300);
    }
    
    let isCfpMaximized = false;
    function maximizeCfpModal() {
        const content = document.getElementById('cfp-modal-content');
        const icon = document.getElementById('cfp-maximize-icon');
        if (!isCfpMaximized) {
            content.style.width = '100%';
            content.style.maxWidth = '100%';
            content.style.height = '100vh';
            content.style.maxHeight = '100vh';
            content.style.borderRadius = '0';
            icon.className = 'fas fa-compress';
        } else {
            content.style.width = '90%';
            content.style.maxWidth = '1000px';
            content.style.height = 'auto';
            content.style.maxHeight = '90vh';
            content.style.borderRadius = '24px';
            icon.className = 'fas fa-expand';
        }
        isCfpMaximized = !isCfpMaximized;
    }

    // Close modal when clicking outside of the content
    document.getElementById('cfp-modal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeCfpModal();
        }
    });
</script>

<!-- Features/Venue Section -->
<section class="section" style="background-color: var(--bg-light); padding: 80px 0;">
    <div class="container">
        <h2 class="section-title">Official Host 2026</h2>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 30px;">
            <div class="feature-card">
                <i class="fas fa-university feature-icon"></i>
                <h3>UIN Siber Syekh Nurjati</h3>
                <p>Proudly hosting AICIS 2026. Experience a deep academic environment and rich culture in Cirebon.</p>
                <a href="https://syekhnurjati.ac.id/" target="_blank" style="margin-top: 15px; display: inline-block; font-weight: bold;">Visit Website <i class="fas fa-angle-right"></i></a>
            </div>
            
            <div class="feature-card">
                <i class="fas fa-globe-asia feature-icon"></i>
                <h3>Global Dialogue</h3>
                <p>Connecting scholars, researchers, and practitioners from around the globe to discuss Islamic Studies.</p>
                <a href="{{ url('/about') }}" style="margin-top: 15px; display: inline-block; font-weight: bold;">Learn More <i class="fas fa-angle-right"></i></a>
            </div>
            
            <div class="feature-card">
                <i class="fas fa-book-open feature-icon"></i>
                <h3>Call for Papers</h3>
                <p>Submit your research and be part of shaping the future of global Islamic scholarship.</p>
                <a href="{{ url('/admin/login') }}" style="margin-top: 15px; display: inline-block; font-weight: bold;">Login to Submit <i class="fas fa-angle-right"></i></a>
            </div>
        </div>
    </div>
</section>

@if($faqs->count() > 0)
<!-- FAQ Section -->
<section id="faq" class="section" style="padding: 100px 0; background: linear-gradient(135deg, #1b5e20 0%, #063A27 100%); position: relative; overflow: hidden;">
    @if($aboutSettings && $aboutSettings->dark_logo)
    <!-- Silhouette Container -->
    <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; pointer-events: none; z-index: 0; overflow: hidden;">
        <!-- Top Right Accent -->
        <img src="{{ Storage::url($aboutSettings->dark_logo) }}" alt="Background Logo" style="position: absolute; top: 20px; right: 20px; height: auto; width: 400px; max-width: 45vw; opacity: 0.15; object-fit: contain;">
    </div>
    @endif
    <div class="container" style="position: relative; z-index: 1;">
        <div style="text-align: center; margin-bottom: 50px;">
            <h2 class="section-title" style="color: #fff; margin-bottom: 15px;">FAQ</h2>
            <div style="width: 60px; height: 3px; background-color: #dfb162; margin: 0 auto 20px;"></div>
            <p style="color: #e2e8f0; max-width: 600px; margin: 0 auto; line-height: 1.6;">Find answers to the most frequently asked questions about the conference schedule, registration process, and guidelines for abstract submissions.</p>
        </div>
        <div style="max-width: 1100px; margin: 0 auto; display: grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: 20px; align-items: start;">
            @foreach($faqs->take(10) as $index => $faq)
            <div class="faq-item" style="background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(212, 175, 55, 0.4); border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); overflow: hidden; backdrop-filter: blur(10px); transition: all 0.3s ease;">
                <button class="faq-toggle" style="width: 100%; text-align: left; background: transparent; border: none; padding: 20px 25px; font-size: 1.05rem; font-weight: 600; color: #fff; cursor: pointer; display: flex; justify-content: space-between; align-items: center; transition: background 0.3s ease;" onclick="const content = this.nextElementSibling; const icon = this.querySelector('i'); if(content.style.display === 'block') { content.style.display = 'none'; icon.style.transform = 'rotate(0deg)'; this.parentElement.style.boxShadow = '0 4px 15px rgba(0,0,0,0.1)'; this.parentElement.style.borderColor = 'rgba(212, 175, 55, 0.4)'; this.parentElement.style.background = 'rgba(255, 255, 255, 0.05)'; } else { content.style.display = 'block'; icon.style.transform = 'rotate(180deg)'; this.parentElement.style.boxShadow = '0 10px 30px rgba(0,0,0,0.3)'; this.parentElement.style.borderColor = 'rgba(212, 175, 55, 0.8)'; this.parentElement.style.background = 'rgba(255, 255, 255, 0.1)'; }" onmouseover="if(this.nextElementSibling.style.display !== 'block') this.parentElement.style.background='rgba(255, 255, 255, 0.08)'" onmouseout="if(this.nextElementSibling.style.display !== 'block') this.parentElement.style.background='rgba(255, 255, 255, 0.05)'">
                    <span style="padding-right: 15px; font-family: var(--font-heading);">{{ $faq->question }}</span>
                    <i class="fas fa-chevron-down" style="transition: transform 0.3s ease; color: #dfb162; font-size: 1.1rem; flex-shrink: 0;"></i>
                </button>
                <div class="faq-content" style="display: none; padding: 0 25px 25px 25px; background: transparent; color: #e2e8f0; line-height: 1.6; font-size: 0.95rem;">
                    {!! $faq->answer !!}
                </div>
            </div>
            @endforeach
        </div>

        @if($faqs->count() > 10)
        <!-- Button Only, No Fade Box -->
        <div style="position: relative; z-index: 3; text-align: center; margin-top: 40px;">
            <button onclick="openFaqModal()" style="background: #dfb162; color: #042519; border: none; padding: 12px 30px; border-radius: 30px; font-weight: 600; cursor: pointer; transition: all 0.3s ease; box-shadow: 0 4px 15px rgba(223, 177, 98, 0.4);" onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 6px 20px rgba(223, 177, 98, 0.6)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 15px rgba(223, 177, 98, 0.4)';">
                See More Details
            </button>
        </div>
        @endif
    </div>

    @if($faqs->count() > 10)
    <!-- FAQ Modal -->
    <div id="faq-modal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(4, 37, 25, 0.85); z-index: 9999; backdrop-filter: blur(12px); opacity: 0; transition: opacity 0.4s ease; align-items: center; justify-content: center; overflow: hidden;">
        <!-- Modal Content -->
        <div id="faq-modal-content" style="background: linear-gradient(145deg, #134616 0%, #063A27 100%); border: 1px solid rgba(212, 175, 55, 0.3); border-radius: 20px; width: 90%; max-width: 1000px; max-height: 85vh; padding: 40px; box-shadow: 0 30px 60px rgba(0,0,0,0.5), inset 0 1px 0 rgba(255,255,255,0.1); position: relative; overflow: hidden; transform: scale(0.95); transition: transform 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275); display: flex; flex-direction: column;">
            
            <!-- Decoration -->
            <div style="position: absolute; top: -50px; right: -50px; width: 200px; height: 200px; background: radial-gradient(circle, rgba(212,175,55,0.15) 0%, rgba(212,175,55,0) 70%); border-radius: 50%; pointer-events: none;"></div>
            
            <!-- Header Controls -->
            <div style="display: flex; justify-content: flex-end; align-items: center; margin-bottom: 25px; position: relative; z-index: 2;">
                <div style="display: flex; gap: 15px; background: rgba(0,0,0,0.2); padding: 8px 15px; border-radius: 30px; border: 1px solid rgba(255,255,255,0.05);">
                    <button onclick="toggleMaximizeFaqModal()" id="faq-maximize-btn" style="background: transparent; border: none; font-size: 1.1rem; cursor: pointer; color: #a0aec0; transition: color 0.2s ease; display: flex; align-items: center; justify-content: center;" onmouseover="this.style.color='#dfb162'" onmouseout="this.style.color='#a0aec0'" title="Maximize">
                        <i class="fas fa-expand"></i>
                    </button>
                    <div style="width: 1px; height: 18px; background: rgba(255,255,255,0.1);"></div>
                    <button onclick="closeFaqModal()" style="background: transparent; border: none; font-size: 1.3rem; cursor: pointer; color: #a0aec0; transition: color 0.2s ease; display: flex; align-items: center; justify-content: center;" onmouseover="this.style.color='#e53e3e'" onmouseout="this.style.color='#a0aec0'" title="Close">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>

            <h3 style="color: #fff; font-size: 1.8rem; margin-top: 0; margin-bottom: 30px; text-align: center; font-weight: 700; text-shadow: 0 2px 4px rgba(0,0,0,0.3); position: relative; z-index: 2;">
                <span style="color: #dfb162;">All</span> FAQs
            </h3>

            <!-- Scrollable Steps Area -->
            <div id="faq-scroll-area" style="overflow-y: auto; padding-right: 15px; position: relative; z-index: 2; flex-grow: 1; margin-bottom: 10px;">
                <style>
                    #faq-scroll-area::-webkit-scrollbar { width: 6px; }
                    #faq-scroll-area::-webkit-scrollbar-track { background: rgba(0,0,0,0.1); border-radius: 10px; }
                    #faq-scroll-area::-webkit-scrollbar-thumb { background: rgba(212,175,55,0.5); border-radius: 10px; }
                    #faq-scroll-area::-webkit-scrollbar-thumb:hover { background: rgba(212,175,55,0.8); }
                </style>
                
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: 20px; align-items: start; padding-bottom: 20px;">
                    @foreach($faqs as $index => $faq)
                    <div class="faq-item" style="background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(212, 175, 55, 0.4); border-radius: 12px; overflow: hidden; backdrop-filter: blur(5px); transition: all 0.3s ease;">
                        <button class="faq-toggle" style="width: 100%; text-align: left; background: transparent; border: none; padding: 15px 20px; font-size: 1rem; font-weight: 600; color: #fff; cursor: pointer; display: flex; justify-content: space-between; align-items: center; transition: background 0.3s ease;" onclick="const content = this.nextElementSibling; const icon = this.querySelector('i'); if(content.style.display === 'block') { content.style.display = 'none'; icon.style.transform = 'rotate(0deg)'; } else { content.style.display = 'block'; icon.style.transform = 'rotate(180deg)'; }" onmouseover="if(this.nextElementSibling.style.display !== 'block') this.style.background='rgba(255, 255, 255, 0.08)'" onmouseout="if(this.nextElementSibling.style.display !== 'block') this.style.background='transparent'">
                            <span style="padding-right: 15px; font-family: var(--font-heading);">{{ $faq->question }}</span>
                            <i class="fas fa-chevron-down" style="transition: transform 0.3s ease; color: #dfb162; font-size: 1rem; flex-shrink: 0;"></i>
                        </button>
                        <div class="faq-content" style="display: none; padding: 0 20px 20px 20px; background: transparent; color: #e2e8f0; line-height: 1.6; font-size: 0.9rem;">
                            {!! $faq->answer !!}
                        </div>
                    </div>
                    @endforeach
                </div>
                
            </div>
        </div>
    </div>

    <script>
        let isFaqMaximized = false;
        
        function openFaqModal() {
            const modal = document.getElementById('faq-modal');
            const content = document.getElementById('faq-modal-content');
            modal.style.display = 'flex';
            // Trigger reflow
            void modal.offsetWidth;
            modal.style.opacity = '1';
            content.style.transform = 'scale(1)';
            document.body.style.overflow = 'hidden'; // Prevent background scrolling
        }

        function closeFaqModal() {
            const modal = document.getElementById('faq-modal');
            const content = document.getElementById('faq-modal-content');
            modal.style.opacity = '0';
            content.style.transform = 'scale(0.95)';
            setTimeout(() => {
                modal.style.display = 'none';
                document.body.style.overflow = ''; // Restore scrolling
            }, 400); // Wait for transition
        }

        function toggleMaximizeFaqModal() {
            const content = document.getElementById('faq-modal-content');
            const icon = document.querySelector('#faq-maximize-btn i');
            
            if (!isFaqMaximized) {
                content.style.width = '100%';
                content.style.maxWidth = '100%';
                content.style.height = '100vh';
                content.style.maxHeight = '100vh';
                content.style.borderRadius = '0';
                content.style.border = 'none';
                icon.className = 'fas fa-compress';
            } else {
                content.style.width = '90%';
                content.style.maxWidth = '1000px';
                content.style.height = 'auto';
                content.style.maxHeight = '85vh';
                content.style.borderRadius = '20px';
                content.style.border = '1px solid rgba(212, 175, 55, 0.3)';
                icon.className = 'fas fa-expand';
            }
            isFaqMaximized = !isFaqMaximized;
        }

        // Close modal when clicking outside of the content
        document.getElementById('faq-modal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeFaqModal();
            }
        });
    </script>
    @endif
</section>
@endif

@if($posts->count() > 0)
<!-- News Section -->
<section id="news" class="section" style="padding: 80px 0; background-color: #f9f9f9;">
    <div class="container">
        <div style="text-align: center; margin-bottom: 50px;">
            <h2 class="section-title" style="margin-bottom: 15px;">News & Updates</h2>
            <div style="width: 60px; height: 3px; background-color: var(--primary-color); margin: 0 auto 20px;"></div>
            <p style="color: #64748b; font-size: 1.05rem; max-width: 650px; margin: 0 auto; line-height: 1.7;">Stay informed with the latest announcements, articles, and insights from the AICIS conference. Read our newest updates below.</p>
        </div>
        <div class="news-grid">
            @foreach($posts as $post)
            <div class="news-card" style="background: #fff; border-radius: 16px; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.03); border: 1px solid rgba(0,0,0,0.04); transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1); display: flex; flex-direction: column; height: 100%;" onmouseover="this.style.transform='translateY(-8px)'; this.style.boxShadow='0 20px 40px rgba(27,94,32,0.08)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 10px 30px rgba(0,0,0,0.03)';">
                <a href="{{ url('/post/' . $post->slug) }}" style="display: block; height: 220px; overflow: hidden; position: relative;">
                    @if($post->featured_image)
                        @php
                            $imgSrc = \Illuminate\Support\Str::startsWith($post->featured_image, ['http://', 'https://']) 
                                ? $post->featured_image 
                                : Storage::url($post->featured_image);
                        @endphp
                        <img src="{{ $imgSrc }}" alt="{{ $post->title }}" style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.6s ease;" onmouseover="this.style.transform='scale(1.08)'" onmouseout="this.style.transform='scale(1)'">
                    @else
                        <div style="width: 100%; height: 100%; background: linear-gradient(135deg, #f5f7fa 0%, #e4e8f0 100%); display: flex; align-items: center; justify-content: center; color: #b0b8c4;">
                            <i class="fas fa-image" style="font-size: 3rem;"></i>
                        </div>
                    @endif
                    <!-- Elegant Date Pill -->
                    <div style="position: absolute; top: 20px; left: 20px; background: rgba(255, 255, 255, 0.95); color: var(--primary-color); padding: 8px 16px; border-radius: 30px; font-size: 0.75rem; font-weight: 600; letter-spacing: 0.5px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); backdrop-filter: blur(5px);">
                        {{ $post->published_at ? \Carbon\Carbon::parse($post->published_at)->format('M d, Y') : $post->created_at->format('M d, Y') }}
                    </div>
                </a>
                <div style="padding: 30px; display: flex; flex-direction: column; flex-grow: 1;">
                    <div style="margin-bottom: 12px;">
                        <span style="color: var(--accent-color); font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 1px;">
                            {{ $post->category ? $post->category->name : 'Uncategorized' }}
                        </span>
                    </div>
                    <h4 style="font-size: 1.15rem; font-weight: 600; margin-bottom: 15px; line-height: 1.5; font-family: var(--font-heading);">
                        <a href="{{ url('/post/' . $post->slug) }}" style="color: #1a2530; text-decoration: none; transition: color 0.3s;" onmouseover="this.style.color='var(--primary-color)'" onmouseout="this.style.color='#1a2530'">{{ $post->title }}</a>
                    </h4>
                    <p style="color: #666; font-size: 0.9rem; line-height: 1.7; font-weight: 300; margin-bottom: 25px; flex-grow: 1;">
                        {{ Str::limit(strip_tags($post->content), 120) }}
                    </p>
                    <a href="{{ url('/post/' . $post->slug) }}" style="display: inline-flex; align-items: center; gap: 8px; color: var(--primary-color); font-weight: 500; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px; text-decoration: none;" onmouseover="this.querySelector('i').style.transform='translateX(5px)'" onmouseout="this.querySelector('i').style.transform='translateX(0)'">
                        Read More <i class="fas fa-arrow-right" style="font-size: 0.75rem; transition: transform 0.3s;"></i>
                    </a>
                </div>
            </div>
            @endforeach
        </div>
        
        <div style="text-align: center; margin-top: 50px;">
            <a href="{{ url('/posts') }}" class="btn btn-primary" style="background: transparent; color: var(--primary-color); border: 2px solid var(--primary-color);">View All News</a>
        </div>
    </div>
</section>
@endif

@if($galleries->count() > 0)
<!-- Gallery Section -->
<section id="gallery" class="section" style="padding: 80px 0;">
    <div class="container">
        <h2 class="section-title">Gallery</h2>
        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 15px;">
            @foreach($galleries->take(6) as $gallery)
            <div style="position: relative; overflow: hidden; border-radius: 8px; height: 250px; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
                @php
                    $galSrc = \Illuminate\Support\Str::startsWith($gallery->file_path, ['http://', 'https://']) 
                        ? $gallery->file_path 
                        : Storage::url($gallery->file_path);
                @endphp
                <img src="{{ $galSrc }}" alt="{{ $gallery->caption }}" style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s ease;" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'">
                @if($gallery->caption)
                <div style="position: absolute; bottom: 0; left: 0; width: 100%; background: linear-gradient(transparent, rgba(0,0,0,0.9)); color: #fff; padding: 30px 20px 15px;">
                    <h5 style="margin: 0; font-size: 1.05rem; font-family: var(--font-heading);">{{ $gallery->caption }}</h5>
                </div>
                @endif
            </div>
            @endforeach
        </div>
        
        @if($galleries->count() > 0)
        <div style="text-align: center; margin-top: 40px;">
            <button onclick="openGalleryModal()" style="background: #dfb162; color: #042519; border: none; padding: 12px 35px; border-radius: 30px; font-weight: 600; cursor: pointer; transition: all 0.3s ease; box-shadow: 0 4px 15px rgba(223, 177, 98, 0.4); text-transform: uppercase; letter-spacing: 1px;" onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 6px 20px rgba(223, 177, 98, 0.6)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 15px rgba(223, 177, 98, 0.4)';">
                See More Details
            </button>
        </div>
        @endif
    </div>

    @if($galleries->count() > 0)
    <!-- Gallery Modal -->
    <div id="gallery-modal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(4, 37, 25, 0.9); z-index: 9999; backdrop-filter: blur(15px); opacity: 0; transition: opacity 0.4s ease; align-items: center; justify-content: center; overflow: hidden;">
        <!-- Modal Content -->
        <div id="gallery-modal-content" style="background: #111827; border: 1px solid rgba(212, 175, 55, 0.3); border-radius: 20px; width: 90%; max-width: 1200px; max-height: 90vh; padding: 40px; box-shadow: 0 30px 60px rgba(0,0,0,0.5), inset 0 1px 0 rgba(255,255,255,0.1); position: relative; overflow: hidden; transform: scale(0.95); transition: transform 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275); display: flex; flex-direction: column;">
            
            <!-- Header Controls -->
            <div style="display: flex; justify-content: flex-end; align-items: center; margin-bottom: 25px; position: relative; z-index: 2;">
                <div style="display: flex; gap: 15px; background: rgba(255,255,255,0.1); padding: 8px 15px; border-radius: 30px; border: 1px solid rgba(255,255,255,0.1);">
                    <button onclick="toggleMaximizeGalleryModal()" id="gallery-maximize-btn" style="background: transparent; border: none; font-size: 1.1rem; cursor: pointer; color: #a0aec0; transition: color 0.2s ease; display: flex; align-items: center; justify-content: center;" onmouseover="this.style.color='#dfb162'" onmouseout="this.style.color='#a0aec0'" title="Maximize">
                        <i class="fas fa-expand"></i>
                    </button>
                    <div style="width: 1px; height: 18px; background: rgba(255,255,255,0.2);"></div>
                    <button onclick="closeGalleryModal()" style="background: transparent; border: none; font-size: 1.3rem; cursor: pointer; color: #a0aec0; transition: color 0.2s ease; display: flex; align-items: center; justify-content: center;" onmouseover="this.style.color='#e53e3e'" onmouseout="this.style.color='#a0aec0'" title="Close">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>

            <h3 style="color: #fff; font-size: 2rem; margin-top: 0; margin-bottom: 30px; text-align: center; font-weight: 700; font-family: var(--font-heading); text-shadow: 0 2px 4px rgba(0,0,0,0.5);">
                <span style="color: #dfb162;">All</span> Galleries
            </h3>

            <!-- Scrollable Gallery Area -->
            <div id="gallery-scroll-area" style="overflow-y: auto; padding-right: 15px; position: relative; z-index: 2; flex-grow: 1; margin-bottom: 10px;">
                <style>
                    #gallery-scroll-area::-webkit-scrollbar { width: 8px; }
                    #gallery-scroll-area::-webkit-scrollbar-track { background: rgba(255,255,255,0.05); border-radius: 10px; }
                    #gallery-scroll-area::-webkit-scrollbar-thumb { background: rgba(212,175,55,0.5); border-radius: 10px; }
                    #gallery-scroll-area::-webkit-scrollbar-thumb:hover { background: rgba(212,175,55,0.8); }
                    @media (max-width: 992px) {
                        #gallery .container > div { grid-template-columns: repeat(2, 1fr) !important; }
                    }
                    @media (max-width: 576px) {
                        #gallery .container > div { grid-template-columns: 1fr !important; }
                        #gallery-scroll-area > div { grid-template-columns: 1fr !important; }
                    }
                </style>
                
                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 20px; align-items: start; padding-bottom: 20px;">
                    @foreach($galleries as $gallery)
                    <div style="position: relative; overflow: hidden; border-radius: 10px; height: 250px; border: 1px solid rgba(255,255,255,0.1); box-shadow: 0 10px 20px rgba(0,0,0,0.3);">
                        @php
                            $galSrc = \Illuminate\Support\Str::startsWith($gallery->file_path, ['http://', 'https://']) 
                                ? $gallery->file_path 
                                : Storage::url($gallery->file_path);
                        @endphp
                        <img src="{{ $galSrc }}" alt="{{ $gallery->caption }}" style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s ease;" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'">
                        @if($gallery->caption)
                        <div style="position: absolute; bottom: 0; left: 0; width: 100%; background: linear-gradient(transparent, rgba(0,0,0,0.9)); color: #fff; padding: 30px 20px 15px;">
                            <h5 style="margin: 0; font-size: 1.05rem; font-family: var(--font-heading); text-shadow: 0 2px 4px rgba(0,0,0,0.8);">{{ $gallery->caption }}</h5>
                        </div>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <script>
        let isGalleryMaximized = false;
        
        function openGalleryModal() {
            const modal = document.getElementById('gallery-modal');
            const content = document.getElementById('gallery-modal-content');
            modal.style.display = 'flex';
            // Trigger reflow
            void modal.offsetWidth;
            modal.style.opacity = '1';
            content.style.transform = 'scale(1)';
            document.body.style.overflow = 'hidden';
        }

        function closeGalleryModal() {
            const modal = document.getElementById('gallery-modal');
            const content = document.getElementById('gallery-modal-content');
            modal.style.opacity = '0';
            content.style.transform = 'scale(0.95)';
            setTimeout(() => {
                modal.style.display = 'none';
                document.body.style.overflow = '';
            }, 400);
        }

        function toggleMaximizeGalleryModal() {
            const content = document.getElementById('gallery-modal-content');
            const icon = document.querySelector('#gallery-maximize-btn i');
            
            if (!isGalleryMaximized) {
                content.style.width = '100%';
                content.style.maxWidth = '100%';
                content.style.height = '100vh';
                content.style.maxHeight = '100vh';
                content.style.borderRadius = '0';
                content.style.border = 'none';
                icon.className = 'fas fa-compress';
            } else {
                content.style.width = '90%';
                content.style.maxWidth = '1200px';
                content.style.height = 'auto';
                content.style.maxHeight = '90vh';
                content.style.borderRadius = '20px';
                content.style.border = '1px solid rgba(212, 175, 55, 0.3)';
                icon.className = 'fas fa-expand';
            }
            isGalleryMaximized = !isGalleryMaximized;
        }

        document.getElementById('gallery-modal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeGalleryModal();
            }
        });
    </script>
    @endif
</section>
@endif

@if($partners->count() > 0)
<!-- Official Partners Section -->
<section id="partners" class="section" style="padding: 60px 0; background-color: #fff; border-top: 1px solid #eee;">
    <div class="container">
        <h2 class="section-title" style="margin-bottom: 40px; font-size: 1.8rem;">Official Partners</h2>
        <style>
            .partnerSwiper {
                width: 100%;
                padding: 60px 0; /* Increased padding to allow tooltip space */
                overflow: visible !important; /* Force visibility */
            }
            .partnerSwiper .swiper-slide {
                display: flex;
                align-items: center;
                justify-content: center;
                height: 100px;
                opacity: 0.4;
                transform: scale(0.7);
                transition: all 0.5s ease;
                position: relative;
            }
            .partnerSwiper .swiper-slide-active {
                opacity: 1;
                transform: scale(1.4);
                z-index: 2;
            }
            .partnerSwiper .swiper-slide:hover {
                opacity: 1;
                z-index: 5;
            }
            .partnerSwiper .swiper-slide-active img, .partnerSwiper .swiper-slide:hover img {
                filter: grayscale(0%) !important;
            }
            .partnerSwiper .swiper-slide img {
                max-width: 150px;
                max-height: 80px;
                object-fit: contain;
                filter: grayscale(100%);
                transition: filter 0.5s ease;
            }
            .partner-tooltip {
                position: absolute;
                bottom: -20px;
                left: 50%;
                transform: translateX(-50%);
                background: #063A27;
                color: #dfb162;
                padding: 8px 15px;
                border-radius: 6px;
                font-size: 0.8rem;
                font-weight: 700;
                white-space: nowrap;
                opacity: 0;
                visibility: hidden;
                transition: all 0.3s ease;
                z-index: 50;
                box-shadow: 0 4px 15px rgba(0,0,0,0.2);
                pointer-events: none;
            }
            .partnerSwiper .swiper-slide:hover .partner-tooltip {
                opacity: 1;
                visibility: visible;
                bottom: -35px;
            }
        </style>
        <div class="swiper partnerSwiper">
            <div class="swiper-wrapper">
                @foreach($partners as $partner)
                <div class="swiper-slide">
                    @if($partner->url)
                    <a href="{{ $partner->url }}" target="_blank" style="display: flex; width: 100%; height: 100%; align-items: center; justify-content: center;" title="{{ $partner->name }}">
                    @endif
                        @php
                            $logoSrc = $partner->logo_path ? Storage::url($partner->logo_path) : ($partner->logo_url ? $partner->logo_url : ($aboutSettings->logo ? Storage::url($aboutSettings->logo) : ''));
                        @endphp
                        <img src="{{ $logoSrc }}" alt="{{ $partner->name }}">
                    @if($partner->url)
                    </a>
                    @endif
                    <div class="partner-tooltip">{{ $partner->name }}</div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
@endif

<!-- Video Modal -->
<div id="videoModal" style="display:none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.9); z-index: 9999; align-items: center; justify-content: center;">
    <div style="position: absolute; top: 20px; right: 30px; color: #fff; font-size: 2.5rem; cursor: pointer; z-index: 10001;" onclick="closeVideoModal()">&times;</div>
    <div style="width: 90%; max-width: 1000px; position: relative; padding-bottom: 50.625%; height: 0;">
        <iframe id="modalIframe" src="" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; border: 0;" allow="autoplay; fullscreen"></iframe>
    </div>
</div>

<script>
    // YouTube API Logic
    var tag = document.createElement('script');
    tag.src = "https://www.youtube.com/iframe_api";
    var firstScriptTag = document.getElementsByTagName('script')[0];
    firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

    var ytPlayers = [];
    var videoSwiper;

    function onYouTubeIframeAPIReady() {
        var iframes = document.querySelectorAll('.yt-bg-player');
        iframes.forEach(function(iframe) {
            var player = new YT.Player(iframe.id, {
                events: {
                    'onReady': onPlayerReady,
                    'onStateChange': onPlayerStateChange
                }
            });
            ytPlayers.push(player);
        });
    }

    function onPlayerReady(event) {
        // Video must be muted to autoplay in modern browsers
        event.target.mute();
        
        // If this is the active slide, play it
        var iframe = event.target.getIframe();
        if (iframe && iframe.closest('.swiper-slide-active')) {
            event.target.playVideo();
        } else {
            event.target.pauseVideo();
        }
    }

    function onPlayerStateChange(event) {
        if (event.data == YT.PlayerState.ENDED) {
            if (videoSwiper && document.querySelectorAll('.yt-bg-player').length > 1) {
                if (videoSwiper.isEnd) {
                    videoSwiper.slideTo(0);
                } else {
                    videoSwiper.slideNext();
                }
            } else {
                event.target.seekTo(0);
                event.target.playVideo();
            }
        }
    }

    function openVideoModal(ytId) {
        document.getElementById('videoModal').style.display = 'flex';
        // Auto play with sound
        document.getElementById('modalIframe').src = "https://www.youtube.com/embed/" + ytId + "?autoplay=1&mute=0&rel=0";
    }

    function closeVideoModal() {
        document.getElementById('videoModal').style.display = 'none';
        document.getElementById('modalIframe').src = "";
    }

    document.addEventListener('DOMContentLoaded', function () {
        if (typeof Swiper !== 'undefined') {
            var partnerSwiper = new Swiper(".partnerSwiper", {
                slidesPerView: 3,
                spaceBetween: 20,
                centeredSlides: true,
                loop: true,
                speed: 800,
                autoplay: {
                    delay: 2000,
                    disableOnInteraction: false,
                },
                breakpoints: {
                    640: { slidesPerView: 3, spaceBetween: 30 },
                    768: { slidesPerView: 5, spaceBetween: 40 },
                    1024: { slidesPerView: 7, spaceBetween: 50 },
                }
            });

            var swiper = new Swiper(".mySwiper", {
                loop: true,
                autoplay: {
                    delay: 5000,
                    disableOnInteraction: false,
                },
                pagination: {
                    el: ".swiper-pagination",
                    clickable: true,
                },
                navigation: {
                    nextEl: ".swiper-button-next",
                    prevEl: ".swiper-button-prev",
                },
                effect: 'fade',
                fadeEffect: {
                    crossFade: true
                }
            });

            videoSwiper = new Swiper(".videoSwiper", {
                loop: false, // Turn off loop to prevent cloning iframes and ID conflicts
                autoplay: false, // Turn off swiper's autoplay, let YT API handle it
                pagination: {
                    el: ".swiper-pagination",
                    clickable: true,
                },
                on: {
                    slideChangeTransitionEnd: function () {
                        // Pause all videos
                        ytPlayers.forEach(function(p) {
                            if (p && typeof p.pauseVideo === 'function') {
                                p.pauseVideo();
                            }
                        });
                        // Play the active video
                        var activeSlide = this.slides[this.activeIndex];
                        var iframe = activeSlide.querySelector('.yt-bg-player');
                        if (iframe) {
                            var playerIndex = parseInt(iframe.getAttribute('data-index'));
                            if (ytPlayers[playerIndex] && typeof ytPlayers[playerIndex].playVideo === 'function') {
                                ytPlayers[playerIndex].seekTo(0);
                                ytPlayers[playerIndex].playVideo();
                            }
                        }
                    }
                }
            });
        }
        
        const countdownTimer = document.getElementById('countdown-timer');
        if (countdownTimer) {
            const targetDate = parseInt(countdownTimer.dataset.target);
            
            const updateCountdown = () => {
                const now = new Date().getTime();
                const distance = targetDate - now;
                
                if (distance < 0) {
                    document.getElementById('days').innerText = "00";
                    document.getElementById('hours').innerText = "00";
                    document.getElementById('minutes').innerText = "00";
                    document.getElementById('seconds').innerText = "00";
                    return;
                }
                
                const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);
                
                document.getElementById('days').innerText = days.toString().padStart(2, '0');
                document.getElementById('hours').innerText = hours.toString().padStart(2, '0');
                document.getElementById('minutes').innerText = minutes.toString().padStart(2, '0');
                document.getElementById('seconds').innerText = seconds.toString().padStart(2, '0');
            };
            
            updateCountdown();
            setInterval(updateCountdown, 1000);
        }
    });
</script>

<!-- Floating Bubble Menu -->
<div id="floating-bubble-menu" class="floating-bubble-container">
    <div class="bubble-menu-card" id="bubble-menu-card">
        <div class="bubble-grid">
            <a href="#" class="bubble-item" onclick="event.preventDefault(); toggleAccessibilityPanel(); document.getElementById('floating-bubble-menu').classList.remove('open');">
                <div class="bubble-icon"><i class="fas fa-universal-access"></i></div>
                <span>Accessibility</span>
            </a>
            <a href="#" class="bubble-item" onclick="event.preventDefault();">
                <div class="bubble-icon"><i class="fas fa-calendar-check"></i></div>
                <span>Conference Agenda</span>
            </a>
            <a href="#" class="bubble-item" onclick="event.preventDefault();">
                <div class="bubble-icon"><i class="fas fa-microphone"></i></div>
                <span>Keynote Speakers</span>
            </a>
            <a href="#" class="bubble-item" onclick="event.preventDefault();">
                <div class="bubble-icon"><i class="fas fa-chart-line"></i></div>
                <span>Statistics</span>
            </a>
            <a href="#" class="bubble-item" onclick="event.preventDefault();">
                <div class="bubble-icon"><i class="fas fa-calendar-alt"></i></div>
                <span>Calendar</span>
            </a>
            <a href="#" class="bubble-item" onclick="event.preventDefault();">
                <div class="bubble-icon"><i class="fas fa-map-location-dot"></i></div>
                <span>Venue & Maps</span>
            </a>
            <a href="{{ url('/admin/login') }}" class="bubble-item">
                <div class="bubble-icon"><i class="fas fa-file-pdf"></i></div>
                <span>Call for Papers</span>
            </a>
            <a href="#" class="bubble-item" onclick="event.preventDefault();">
                <div class="bubble-icon"><i class="fas fa-robot"></i></div>
                <span>AI Chat</span>
            </a>
            <a href="#" class="bubble-item" onclick="event.preventDefault();">
                <div class="bubble-icon"><i class="fas fa-headset"></i></div>
                <span>Help Center</span>
            </a>
            <a href="#" class="bubble-item" onclick="event.preventDefault(); openMiniBrowser('https://uinssc.ac.id', 'UINSSC'); document.getElementById('bubble-menu-card').classList.remove('show');">
                <div class="bubble-icon"><i class="fas fa-university"></i></div>
                <span>UINSSC</span>
            </a>
            <a href="#" class="bubble-item" onclick="event.preventDefault(); openMiniBrowser('https://info.uinssc.ac.id', 'Campus News'); document.getElementById('bubble-menu-card').classList.remove('show');">
                <div class="bubble-icon"><i class="fas fa-newspaper"></i></div>
                <span>Campus News</span>
            </a>
            <a href="#" class="bubble-item" onclick="event.preventDefault(); openMiniBrowser('https://ppid.uinssc.ac.id', 'UINSSC PPID'); document.getElementById('bubble-menu-card').classList.remove('show');">
                <div class="bubble-icon"><i class="fas fa-info-circle"></i></div>
                <span>UINSSC PPID</span>
            </a>
        </div>
    </div>
    <button class="bubble-button" id="bubble-toggle-btn" onclick="toggleBubbleMenu()">
        <i class="fas fa-layer-group" id="bubble-icon-open"></i>
        <i class="fas fa-times" id="bubble-icon-close" style="display: none;"></i>
    </button>
    
    <!-- Accessibility Panel -->
    <div class="a11y-panel" id="a11y-panel">
        <div class="a11y-header">
            <h4 style="margin: 0; font-size: 1.05rem; color: white; font-weight: 600;"><i class="fas fa-universal-access" style="margin-right: 8px;"></i> Aksesibilitas</h4>
            <button onclick="toggleAccessibilityPanel()" class="a11y-close">&times;</button>
        </div>
        <div class="a11y-body" style="max-height: 500px; overflow-y: auto;">
            
            <!-- Hambatan Penglihatan -->
            <div class="a11y-section-title"><i class="fas fa-eye-slash"></i> Hambatan Penglihatan (Low Vision & Tunanetra)</div>
            <div class="a11y-section-grid">
                <button class="a11y-btn" id="btn-a11y-high-contrast" onclick="toggleA11yClass('a11y-high-contrast')">
                    <i class="fas fa-adjust"></i><span>Kontras Tinggi</span>
                </button>
                <button class="a11y-btn" id="btn-a11y-large-text" onclick="toggleA11yClass('a11y-large-text')">
                    <i class="fas fa-search-plus"></i><span>Perbesar Teks</span>
                </button>
                <button class="a11y-btn" id="btn-a11y-large-cursor" onclick="toggleA11yClass('a11y-large-cursor')">
                    <i class="fas fa-mouse-pointer"></i><span>Kursor Besar</span>
                </button>
                <button class="a11y-btn" id="btn-a11y-invert" onclick="toggleA11yClass('a11y-invert')">
                    <i class="fas fa-moon"></i><span>Warna Gelap</span>
                </button>
                <button class="a11y-btn" id="btn-a11y-tts" onclick="toggleTTS()" style="grid-column: span 2;">
                    <i class="fas fa-volume-up"></i><span>Pembaca Suara (TTS)</span>
                </button>
            </div>
            
            <!-- Hambatan Kognitif -->
            <div class="a11y-section-title"><i class="fas fa-brain"></i> Hambatan Kognitif (Disleksia)</div>
            <div class="a11y-section-grid">
                <button class="a11y-btn" id="btn-a11y-readable-font" onclick="toggleA11yClass('a11y-readable-font')">
                    <i class="fas fa-font"></i><span>Font Disleksia</span>
                </button>
                <button class="a11y-btn" id="btn-a11y-text-spacing" onclick="toggleA11yClass('a11y-text-spacing')">
                    <i class="fas fa-text-width"></i><span>Spasi Lebar</span>
                </button>
                <button class="a11y-btn" id="btn-a11y-reading-guide" onclick="toggleReadingGuide()">
                    <i class="fas fa-ruler-horizontal"></i><span>Garis Baca</span>
                </button>
                <button class="a11y-btn" id="btn-a11y-highlight-headings" onclick="toggleA11yClass('a11y-highlight-headings')">
                    <i class="fas fa-heading"></i><span>Sorot Judul</span>
                </button>
            </div>
            
            <!-- Hambatan Motorik -->
            <div class="a11y-section-title"><i class="fas fa-wheelchair"></i> Hambatan Motorik (Tunadaksa)</div>
            <div class="a11y-section-grid">
                <button class="a11y-btn" id="btn-a11y-highlight-links" onclick="toggleA11yClass('a11y-highlight-links')">
                    <i class="fas fa-link"></i><span>Sorot Tautan</span>
                </button>
                <button class="a11y-btn" id="btn-a11y-smart-focus" onclick="toggleA11yClass('a11y-smart-focus')">
                    <i class="fas fa-keyboard"></i><span>Sorot Fokus</span>
                </button>
            </div>
            
            <!-- Ramah Epilepsi & Sensori -->
            <div class="a11y-section-title"><i class="fas fa-bolt"></i> Ramah Epilepsi & Sensori</div>
            <div class="a11y-section-grid">
                <button class="a11y-btn" id="btn-a11y-stop-animations" onclick="toggleA11yClass('a11y-stop-animations')">
                    <i class="fas fa-pause-circle"></i><span>Hentikan Animasi</span>
                </button>
                <button class="a11y-btn" id="btn-a11y-grayscale" onclick="toggleA11yClass('a11y-grayscale')">
                    <i class="fas fa-tint-slash"></i><span>Skala Abu-abu</span>
                </button>
                <button class="a11y-btn" id="btn-a11y-low-saturation" onclick="toggleA11yClass('a11y-low-saturation')" style="grid-column: span 2;">
                    <i class="fas fa-eye-slash"></i><span>Redupkan Saturasi Warna</span>
                </button>
            </div>

            <button class="a11y-btn a11y-reset" onclick="resetA11y()" style="width: 100%; flex-direction: row; justify-content: center; background: #fff5f5; color: #e53e3e; border: 1px solid #fed7d7; padding: 12px; margin-top: 10px;">
                <i class="fas fa-undo" style="color: #e53e3e; font-size: 1rem; margin-bottom: 0;"></i> <span style="font-size: 0.85rem; font-weight: 600;">Reset Semua Pengaturan</span>
            </button>
    </div>
</div>

<!-- Mini Browser Modal -->
<div id="mini-browser-modal" class="mini-browser-modal">
    <div class="mini-browser-window">
        <div class="mini-browser-header">
            <div class="mini-browser-title"><i class="fas fa-globe"></i> <span id="mini-browser-title-text">Browser</span></div>
            <div class="mini-browser-controls">
                <button onclick="maximizeMiniBrowser()" id="mini-browser-max-btn" title="Maximize"><i class="fas fa-expand"></i></button>
                <button onclick="closeMiniBrowser()" class="close-btn" title="Close"><i class="fas fa-times"></i></button>
            </div>
        </div>
        <div class="mini-browser-content">
            <iframe id="mini-browser-iframe" src="" frameborder="0"></iframe>
        </div>
    </div>
</div>



<style>
    .floating-bubble-container {
        position: fixed;
        bottom: 30px;
        right: 30px;
        z-index: 9999;
        font-family: var(--font-body), sans-serif;
    }
    .bubble-button {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: linear-gradient(135deg, #1b5e20 0%, #2e7d32 100%);
        color: white;
        border: none;
        box-shadow: 0 8px 25px rgba(27, 94, 32, 0.4);
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.4rem;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        float: right;
        position: relative;
        z-index: 2;
    }
    .bubble-button::after {
        content: '';
        position: absolute;
        top: -3px; left: -3px; right: -3px; bottom: -3px;
        border-radius: 50%;
        background: linear-gradient(135deg, #dfb162, transparent);
        z-index: -1;
        opacity: 0;
        transition: opacity 0.4s ease;
    }
    .bubble-button:hover {
        transform: translateY(-5px) scale(1.05);
        box-shadow: 0 15px 35px rgba(27, 94, 32, 0.5);
    }
    .bubble-button:hover::after {
        opacity: 1;
    }
    .bubble-menu-card {
        position: absolute;
        bottom: 75px;
        right: 0;
        background: white;
        border-radius: 16px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        width: 320px;
        display: none;
        flex-direction: column;
        overflow: hidden;
        animation: bubbleFadeIn 0.3s ease;
    }
    .bubble-menu-card.show {
        display: flex;
    }
    .bubble-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 20px 10px;
        padding: 25px 15px 15px;
    }
    .bubble-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-decoration: none;
        color: #555;
        font-size: 0.75rem;
        font-weight: 600;
        text-align: center;
        gap: 10px;
        transition: transform 0.2s ease;
        line-height: 1.2;
    }
    .bubble-item:hover {
        transform: translateY(-3px);
    }
    .bubble-icon {
        width: 55px;
        height: 55px;
        border-radius: 50%;
        background-color: #f4f6f8;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.3rem;
    }
    
    .bubble-item:nth-child(1) .bubble-icon { color: #9b59b6; }
    .bubble-item:nth-child(2) .bubble-icon { color: #1abc9c; }
    .bubble-item:nth-child(3) .bubble-icon { color: #27ae60; }
    .bubble-item:nth-child(4) .bubble-icon { color: #2ecc71; }
    .bubble-item:nth-child(5) .bubble-icon { color: #3498db; }
    .bubble-item:nth-child(6) .bubble-icon { color: #8e44ad; }
    .bubble-item:nth-child(7) .bubble-icon { color: #16a085; }
    .bubble-item:nth-child(8) .bubble-icon { color: #d35400; }
    .bubble-item:nth-child(9) .bubble-icon { color: #2980b9; }
    .bubble-item:nth-child(10) .bubble-icon { color: #c0392b; }
    .bubble-item:nth-child(11) .bubble-icon { color: #e67e22; }
    .bubble-item:nth-child(12) .bubble-icon { color: #34495e; }
    @keyframes bubbleFadeIn {
        from { opacity: 0; transform: translateY(15px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    /* Accessibility Styles */
    .a11y-panel {
        position: absolute;
        bottom: 75px;
        right: 340px;
        background: white;
        border-radius: 20px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.15);
        width: 360px;
        display: none;
        flex-direction: column;
        overflow: hidden;
        animation: bubbleFadeIn 0.3s ease;
        z-index: 999;
    }
    .a11y-panel.show {
        display: flex;
    }
    .a11y-header {
        padding: 15px 20px;
        background: linear-gradient(135deg, #1b5e20 0%, #2e7d32 100%);
        color: white;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .a11y-close {
        background: transparent;
        border: none;
        color: rgba(255,255,255,0.8);
        font-size: 1.8rem;
        cursor: pointer;
        line-height: 1;
        transition: color 0.2s;
    }
    .a11y-close:hover {
        color: white;
    }
    .a11y-body {
        padding: 20px;
        display: flex;
        flex-direction: column;
    }
    .a11y-section-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
        margin-bottom: 20px;
    }
    .a11y-btn {
        background: #f8f9fa;
        border: 1px solid #e9ecef;
        border-radius: 12px;
        padding: 15px 10px;
        text-align: center;
        cursor: pointer;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 8px;
        transition: all 0.25s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        color: #4a5568;
    }
    .a11y-btn:hover {
        background: #fff;
        border-color: #cbd5e0;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        transform: translateY(-2px);
    }
    .a11y-btn.active {
        background: #f0fdf4;
        border-color: #2e7d32;
        color: #1b5e20;
        box-shadow: 0 4px 12px rgba(46, 125, 50, 0.15);
    }
    .a11y-btn i {
        font-size: 1.5rem;
        color: #2e7d32;
        transition: color 0.2s;
    }
    .a11y-btn.active i {
        color: #1b5e20;
    }
    .a11y-btn span {
        font-size: 0.75rem;
        font-weight: 600;
        line-height: 1.3;
    }
    .a11y-reset:hover {
        background: #fed7d7 !important;
    }

    .a11y-section-title {
        font-size: 0.75rem;
        font-weight: 700;
        color: #888;
        margin-bottom: 12px;
        margin-top: 5px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        display: flex;
        align-items: center;
        border-bottom: 1px solid #eee;
        padding-bottom: 8px;
    }
    .a11y-section-title i {
        font-size: 1rem;
        color: #2e7d32;
        margin-right: 8px;
    }

    /* Global Classes applied to body/html */
    body.a11y-high-contrast { filter: contrast(150%) saturate(130%); }
    body.a11y-grayscale { filter: grayscale(100%); }
    body.a11y-invert { filter: invert(100%) hue-rotate(180deg); background-color: #000; }
    body.a11y-invert img { filter: invert(100%) hue-rotate(180deg); }
    body.a11y-low-saturation { filter: saturate(30%) brightness(90%); }
    
    html.a11y-large-text { font-size: 115% !important; }
    
    body.a11y-readable-font * {
        font-family: 'Arial', 'Helvetica', sans-serif !important;
        letter-spacing: 1px !important;
        line-height: 1.8 !important;
    }
    
    body.a11y-text-spacing * {
        letter-spacing: 2px !important;
        word-spacing: 5px !important;
        line-height: 1.8 !important;
    }
    
    body.a11y-smart-focus *:focus, body.a11y-smart-focus a:hover, body.a11y-smart-focus button:hover {
        outline: 4px solid #f39c12 !important;
        outline-offset: 2px !important;
    }
    
    body.a11y-stop-animations, body.a11y-stop-animations * {
        animation: none !important;
        transition: none !important;
        scroll-behavior: auto !important;
    }
    
    body.a11y-highlight-links a {
        background-color: #ffeb3b !important;
        color: #000 !important;
        text-decoration: underline !important;
        border: 2px solid #000 !important;
        padding: 0 2px !important;
    }
    
    body.a11y-highlight-headings h1, body.a11y-highlight-headings h2, 
    body.a11y-highlight-headings h3, body.a11y-highlight-headings h4, 
    body.a11y-highlight-headings h5, body.a11y-highlight-headings h6 {
        border: 2px dashed #e74c3c !important;
        padding: 4px !important;
    }
    
    body.a11y-large-cursor, body.a11y-large-cursor * {
        cursor: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32"><polygon points="2,2 10,30 16,20 28,26 32,20 20,14 30,8" fill="white" stroke="black" stroke-width="2"/></svg>'), auto !important;
    }

    .a11y-reading-guide-line {
        position: fixed;
        left: 0; right: 0; height: 100px;
        background: transparent;
        box-shadow: 0 0 0 9999px rgba(0, 0, 0, 0.4);
        pointer-events: none;
        z-index: 9998;
        display: none;
        transform: translateY(-50%);
    }
    body.a11y-show-guide .a11y-reading-guide-line { display: block; }
    
    .tts-highlighting {
        background-color: #a0d468 !important;
        color: #000 !important;
        outline: 2px solid #27ae60 !important;
    }
    
    .mini-browser-modal {
        display: none;
        position: fixed;
        top: 0; left: 0; width: 100%; height: 100%;
        background: rgba(0,0,0,0.6);
        z-index: 10000;
        align-items: center;
        justify-content: center;
        backdrop-filter: blur(5px);
    }
    .mini-browser-window {
        width: 85%;
        height: 85%;
        background: white;
        border-radius: 12px;
        display: flex;
        flex-direction: column;
        overflow: hidden;
        box-shadow: 0 20px 50px rgba(0,0,0,0.3);
        transition: all 0.3s ease;
    }
    .mini-browser-window.maximized {
        width: 100%;
        height: 100%;
        border-radius: 0;
    }
    .mini-browser-header {
        background: #f1f5f9;
        padding: 12px 18px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px solid #e2e8f0;
    }
    .mini-browser-title {
        font-weight: 600;
        color: #334155;
        font-size: 0.95rem;
    }
    .mini-browser-title i {
        color: #0ea5e9;
        margin-right: 8px;
    }
    .mini-browser-controls button {
        background: none;
        border: none;
        cursor: pointer;
        margin-left: 15px;
        color: #64748b;
        font-size: 1.1rem;
        transition: color 0.2s;
    }
    .mini-browser-controls button:hover {
        color: #0f172a;
    }
    .mini-browser-controls button.close-btn:hover {
        color: #ef4444;
    }
    .mini-browser-content {
        flex: 1;
        background: #fff;
    }
    .mini-browser-content iframe {
        width: 100%;
        height: 100%;
    }



    @media (max-width: 480px) {
        .bubble-menu-card {
            width: 280px;
        }
        .a11y-panel {
            right: 0;
            bottom: 420px;
            width: 300px;
        }
        .bubble-grid {
            gap: 15px 5px;
            padding: 20px 10px 10px;
        }
        .bubble-icon {
            width: 45px;
            height: 45px;
            font-size: 1.1rem;
        }
    }
</style>
<script>
    function toggleBubbleMenu() {
        const menu = document.getElementById('bubble-menu-card');
        const iconOpen = document.getElementById('bubble-icon-open');
        const iconClose = document.getElementById('bubble-icon-close');
        
        menu.classList.toggle('show');
        
        if (menu.classList.contains('show')) {
            iconOpen.style.display = 'none';
            iconClose.style.display = 'block';
        } else {
            iconOpen.style.display = 'block';
            iconClose.style.display = 'none';
        }
    }

    // Close menu when clicking outside
    document.addEventListener('click', function(event) {
        const container = document.getElementById('floating-bubble-menu');
        const menu = document.getElementById('bubble-menu-card');
        const iconOpen = document.getElementById('bubble-icon-open');
        const iconClose = document.getElementById('bubble-icon-close');
        const a11yPanel = document.getElementById('a11y-panel');
        
        if (container && !container.contains(event.target)) {
            if (menu.classList.contains('show')) {
                menu.classList.remove('show');
                iconOpen.style.display = 'block';
                iconClose.style.display = 'none';
            }
            if (a11yPanel && a11yPanel.classList.contains('show')) {
                a11yPanel.classList.remove('show');
            }
        }
    });

    // Accessibility Functions
    function toggleAccessibilityPanel() {
        const panel = document.getElementById('a11y-panel');
        panel.classList.toggle('show');
    }

    function toggleA11yClass(className) {
        const target = (className === 'a11y-large-text') ? document.documentElement : document.body;
        const btn = document.getElementById('btn-' + className);
        
        target.classList.toggle(className);
        if (btn) btn.classList.toggle('active');
        localStorage.setItem(className, target.classList.contains(className));
    }

    // Reading Guide
    let readingGuideActive = false;
    const guideEl = document.createElement('div');
    guideEl.className = 'a11y-reading-guide-line';
    document.body.appendChild(guideEl);

    document.addEventListener('mousemove', function(e) {
        if (readingGuideActive) guideEl.style.top = e.clientY + 'px';
    });

    function toggleReadingGuide() {
        readingGuideActive = !readingGuideActive;
        const btn = document.getElementById('btn-a11y-reading-guide');
        if (readingGuideActive) {
            document.body.classList.add('a11y-show-guide');
            if(btn) btn.classList.add('active');
            localStorage.setItem('a11y-reading-guide', 'true');
        } else {
            document.body.classList.remove('a11y-show-guide');
            if(btn) btn.classList.remove('active');
            localStorage.removeItem('a11y-reading-guide');
        }
    }

    // Text to Speech
    let ttsEnabled = false;

    function toggleTTS() {
        ttsEnabled = !ttsEnabled;
        const btn = document.getElementById('btn-a11y-tts');
        if (ttsEnabled) {
            btn.classList.add('active');
            localStorage.setItem('a11y-tts-enabled', 'true');
        } else {
            btn.classList.remove('active');
            if (window.currentA11yAudio) {
                window.currentA11yAudio.pause();
            }
            window.speechSynthesis.cancel();
            document.querySelectorAll('.tts-highlighting').forEach(el => el.classList.remove('tts-highlighting'));
            localStorage.removeItem('a11y-tts-enabled');
        }
    }

    function getTTSNode(node) {
        while (node && node !== document.body) {
            if (['P', 'H1', 'H2', 'H3', 'H4', 'H5', 'H6', 'A', 'BUTTON', 'LI', 'SPAN', 'DIV'].includes(node.tagName) && node.innerText.trim() !== '') {
                // Ensure it has actual text, not just empty children
                if (node.children.length === 0 || node.textContent.trim().length > 0) return node;
            }
            node = node.parentNode;
        }
        return null;
    }

    function speakText(text) {
        if (window.currentA11yAudio) {
            window.currentA11yAudio.pause();
        }
        window.speechSynthesis.cancel();
        
        text = text.trim();
        if (!text) return;

        // Get current language from HTML tag or Laravel locale
        let currentLang = document.documentElement.lang || '{{ app()->getLocale() }}' || 'id';
        let langCode = currentLang.toLowerCase().startsWith('en') ? 'en' : (currentLang.toLowerCase().startsWith('ar') ? 'ar' : 'id');
        
        // Use Google Translate TTS (High Quality AI Voice)
        // Note: For long texts, we truncate to 200 chars to avoid API limits on this endpoint
        const safeText = text.substring(0, 200);
        const url = `https://translate.google.com/translate_tts?ie=UTF-8&client=tw-ob&tl=${langCode}&q=${encodeURIComponent(safeText)}`;
        
        const audio = new Audio(url);
        window.currentA11yAudio = audio;
        
        audio.onended = function() {
            document.querySelectorAll('.tts-highlighting').forEach(el => el.classList.remove('tts-highlighting'));
        };
        
        audio.play().catch(e => {
            console.log("Audio fallback to Web Speech API...", e);
            fallbackWebSpeech(text, langCode === 'id' ? 'id-ID' : (langCode === 'en' ? 'en-US' : 'ar-SA'));
        });
    }
    
    function fallbackWebSpeech(text, fullLangCode) {
        const msg = new SpeechSynthesisUtterance(text);
        msg.lang = fullLangCode;
        msg.rate = 0.9;
        
        const voices = window.speechSynthesis.getVoices();
        if(voices.length > 0) {
            let baseLang = fullLangCode.split('-')[0];
            let voice = voices.find(v => v.lang.replace('_', '-') === fullLangCode || v.lang.toLowerCase().startsWith(baseLang) || (baseLang === 'id' && (v.name.toLowerCase().includes('indonesia') || v.lang.toLowerCase().startsWith('in'))));
            if(voice) msg.voice = voice;
        }
        
        msg.onend = function() {
            document.querySelectorAll('.tts-highlighting').forEach(el => el.classList.remove('tts-highlighting'));
        };
        window.speechSynthesis.speak(msg);
    }

    document.addEventListener('click', function(e) {
        if (!ttsEnabled) return;
        if (e.target.closest('.a11y-panel') || e.target.closest('#floating-bubble-menu')) return;
        
        const node = getTTSNode(e.target);
        if (node) {
            e.preventDefault();
            e.stopPropagation();
            
            document.querySelectorAll('.tts-highlighting').forEach(el => el.classList.remove('tts-highlighting'));
            node.classList.add('tts-highlighting');
            
            speakText(node.innerText);
        }
    }, true);

    function resetA11y() {
        const classes = [
            'a11y-high-contrast', 'a11y-grayscale', 'a11y-invert', 'a11y-readable-font', 
            'a11y-highlight-links', 'a11y-highlight-headings', 'a11y-large-cursor', 
            'a11y-text-spacing', 'a11y-smart-focus', 'a11y-stop-animations', 'a11y-low-saturation'
        ];
        document.body.classList.remove(...classes);
        document.documentElement.classList.remove('a11y-large-text');
        
        const btns = document.querySelectorAll('.a11y-btn');
        btns.forEach(b => b.classList.remove('active'));
        
        classes.push('a11y-large-text');
        classes.forEach(c => localStorage.removeItem(c));
        
        if (readingGuideActive) toggleReadingGuide();
        if (ttsEnabled) toggleTTS();
    }

    // Load saved preferences on page load
    document.addEventListener('DOMContentLoaded', function() {
        const classes = [
            'a11y-high-contrast', 'a11y-grayscale', 'a11y-invert', 'a11y-large-text', 
            'a11y-readable-font', 'a11y-highlight-links', 'a11y-highlight-headings', 
            'a11y-large-cursor', 'a11y-text-spacing', 'a11y-smart-focus', 
            'a11y-stop-animations', 'a11y-low-saturation'
        ];
        classes.forEach(cls => {
            if (localStorage.getItem(cls) === 'true') {
                const target = (cls === 'a11y-large-text') ? document.documentElement : document.body;
                target.classList.add(cls);
                const btn = document.getElementById('btn-' + cls);
                if (btn) btn.classList.add('active');
            }
        });
        
        if (localStorage.getItem('a11y-reading-guide') === 'true') toggleReadingGuide();
        if (localStorage.getItem('a11y-tts-enabled') === 'true') toggleTTS();
        
        // Ensure voices are loaded for Safari/Chrome
        window.speechSynthesis.onvoiceschanged = function() {};
    });
    // Mini Browser Functions
    function openMiniBrowser(url, title) {
        const iframe = document.getElementById('mini-browser-iframe');
        // Show loading state by resetting src
        iframe.src = 'about:blank';
        setTimeout(() => {
            iframe.src = url;
        }, 50);
        
        document.getElementById('mini-browser-title-text').innerText = title;
        document.getElementById('mini-browser-modal').style.display = 'flex';
        
        // Hide bubble menu if open
        const menu = document.getElementById('bubble-menu-card');
        const iconOpen = document.getElementById('bubble-icon-open');
        const iconClose = document.getElementById('bubble-icon-close');
        if (menu && menu.classList.contains('show')) {
            menu.classList.remove('show');
            if(iconOpen) iconOpen.style.display = 'block';
            if(iconClose) iconClose.style.display = 'none';
        }
    }
    
    function closeMiniBrowser() {
        document.getElementById('mini-browser-modal').style.display = 'none';
        document.getElementById('mini-browser-iframe').src = '';
    }
    
    function maximizeMiniBrowser() {
        const win = document.querySelector('.mini-browser-window');
        const btn = document.getElementById('mini-browser-max-btn');
        win.classList.toggle('maximized');
        if (win.classList.contains('maximized')) {
            btn.innerHTML = '<i class="fas fa-compress"></i>';
            btn.title = "Restore";
        } else {
            btn.innerHTML = '<i class="fas fa-expand"></i>';
            btn.title = "Maximize";
        }
    }


</script>
@endsection

