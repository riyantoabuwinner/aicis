@extends('layouts.app')

@section('content')
@php
    $sliders = \App\Models\Slider::where('is_active', true)->orderBy('sort_order')->get();
    $counter = \App\Models\Counter::where('is_active', true)->orderBy('sort_order')->first();
    
    // Dynamic Event Management Stats
    $totalConferences = \App\Models\Conference::count();
    $totalSubmissions = \App\Models\PaperSubmission::count();
    $totalSessions = \App\Models\PresentationSession::count();
    $totalOfficialParticipants = \App\Models\User::whereHas('paperSubmissions')
        ->whereNotNull('institution')
        ->where('institution', '!=', '')
        ->distinct()
        ->pluck('institution')
        ->count();

    // New Data Fetching for Homepage
    $timelines = \App\Models\Timeline::where('is_active', true)->orderBy('sort_order')->get();
    $themes = \App\Models\Theme::where('is_active', true)->orderBy('sort_order')->get();
    $eventTheme = \App\Models\EventTheme::where('is_active', true)->orderBy('sort_order')->first();
    $posts = \App\Models\Post::orderByRaw('COALESCE(published_at, created_at) DESC')->take(3)->get();
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
        
        @if(isset($eventTheme))
        <div style="text-align: center; margin-bottom: 50px;">
            <a href="{{ route('theme.show') }}" style="text-decoration: none; display: block; transition: transform 0.3s ease;" onmouseover="this.style.transform='scale(1.02)'" onmouseout="this.style.transform='scale(1)'">
                <h2 style="font-size: 1.6rem; font-weight: 700; color: #dfb162; margin-bottom: 10px; text-transform: uppercase; letter-spacing: 1px;">
                    {{ $eventTheme->title }}
                </h2>
                @if($eventTheme->description)
                <div style="font-size: 0.95rem; line-height: 1.6; max-width: 800px; margin: 0 auto; color: rgba(255,255,255,0.85);">
                    {{ \Illuminate\Support\Str::limit(strip_tags($eventTheme->description), 200) }}
                </div>
                @endif
            </a>
        </div>
        @endif

        <div style="display: flex; flex-wrap: wrap; margin: -15px; align-items: center;">
            
            <!-- Countdown Side -->
            <div style="flex: 1 1 50%; min-width: 300px; padding: 15px;">
                @if($counter)
                <div style="text-align: center; margin-bottom: 25px; color: #fff;">
                    <h3 style="margin:0; font-size: 1.2rem; font-weight: normal; letter-spacing: 2px; text-transform: uppercase; color: #dfb162;">
                        Conference Date : <br>
                        <span style="font-size: 1rem; color: #fff; display: inline-block; margin-top: 5px;">{{ \Carbon\Carbon::parse($counter->target_datetime)->format('d F Y') }}@if($counter->until_date) - {{ \Carbon\Carbon::parse($counter->until_date)->format('d F Y') }}@endif</span>
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
                <h2 style="font-size: 1.6rem; color: #1e3a5f; font-weight: 500; text-align: left; margin-bottom: 25px;">{{ $aboutSettings?->about_title ?? 'About AICIS' }}</h2>
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
<style>
    .grid-column-flow {
        display: grid;
        grid-template-columns: 1fr;
        gap: 20px;
    }
    @media (min-width: 600px) {
        .grid-column-flow.themes {
            grid-template-columns: 1fr 1fr;
        }
    }
    @media (min-width: 1024px) {
        .grid-column-flow.themes {
            grid-template-columns: repeat(5, 1fr);
        }
    }
    @media (min-width: 800px) {
        .grid-column-flow.timelines {
            grid-template-columns: 1fr 1fr;
            grid-auto-flow: column;
            grid-template-rows: repeat({{ ceil($timelines->count() / 2) }}, 1fr);
            gap: 20px;
        }
    }
</style>
        <div style="display: flex; flex-direction: column; gap: 60px;">
            
            @if($themes->count() > 0)
            <!-- Sub Themes Column -->
            <div style="width: 100%;">
                <h2 style="text-align: left; margin-bottom: 10px; color: #1e3a5f; font-size: 1.6rem; font-family: var(--font-heading); font-weight: 500;">Sub Themes</h2>
                <div style="width: 40px; height: 2px; background-color: var(--accent-color); margin-bottom: 30px;"></div>
                
                <div class="grid-column-flow themes">
                    @foreach($themes as $index => $theme)
                    @php
                        $isEven = $index % 2 === 0;
                        
                        // Dark Blue card (0, 2, 4...)
                        $bgStyleEven = "background: #243c5a; box-shadow: 0 10px 20px rgba(36, 60, 90, 0.15);";
                        $numberColorEven = "#ffffff"; 
                        $iconColorEven = "#dfb162"; 
                        $titleColorEven = "#ffffff"; 
                        $descColorEven = "#e2e8f0";
                        
                        // Light Gray card (1, 3, 5...)
                        $bgStyleOdd = "background: linear-gradient(135deg, #d1d5db 0%, #f3f4f6 100%); box-shadow: 0 10px 20px rgba(0,0,0,0.05);";
                        $numberColorOdd = "#243c5a"; 
                        $iconColorOdd = "#243c5a"; 
                        $titleColorOdd = "#243c5a"; 
                        $descColorOdd = "#4b5563";

                        $bgStyle = $isEven ? $bgStyleEven : $bgStyleOdd;
                        $numberColor = $isEven ? $numberColorEven : $numberColorOdd;
                        $iconColor = $isEven ? $iconColorEven : $iconColorOdd;
                        $titleColor = $isEven ? $titleColorEven : $titleColorOdd;
                        $descColor = $isEven ? $descColorEven : $descColorOdd;

                        // Pick an icon based on index
                        $icons = ['fa-seedling', 'fa-cogs', 'fa-users', 'fa-recycle'];
                        $iconClass = $icons[$index % count($icons)];
                    @endphp
                    <div class="theme-card-clickable" data-title="{{ $theme->name }}" data-description="{{ $theme->description }}" style="{{ $bgStyle }} padding: 30px 20px; border-radius: 15px; position: relative; display: flex; flex-direction: column; transition: transform 0.3s ease, box-shadow 0.3s ease; cursor: pointer; height: 100%; box-sizing: border-box;" onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 15px 30px rgba(0,0,0,0.15)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='{{ $isEven ? "0 10px 20px rgba(36, 60, 90, 0.15)" : "0 10px 20px rgba(0,0,0,0.05)" }}';">
                        
                        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 25px;">
                            <!-- Icon -->
                            <div style="font-size: 2.5rem; color: {{ $iconColor }}; line-height: 1;">
                                <i class="fas {{ $iconClass }}"></i>
                            </div>
                            <!-- Number -->
                            <div style="font-size: 1.8rem; font-weight: 700; color: {{ $numberColor }}; line-height: 1; font-family: var(--font-heading);">
                                {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}.
                            </div>
                        </div>
                        
                        <!-- Content -->
                        <div>
                            <h5 style="font-size: 1.1rem; font-weight: 600; margin-bottom: 15px; color: {{ $titleColor }}; line-height: 1.3;">{{ $theme->name }}</h5>
                            <p style="color: {{ $descColor }}; font-size: 0.85rem; line-height: 1.6; margin: 0; font-weight: 400;">
                                {{ \Illuminate\Support\Str::limit($theme->description, 120) }}
                            </p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            @if($timelines->count() > 0)
            <!-- Timeline Column -->
            <div style="width: 100%;">
                <h2 style="text-align: left; margin-bottom: 10px; color: #1e3a5f; font-size: 1.6rem; font-family: var(--font-heading); font-weight: 500;">Timeline</h2>
                <div style="width: 40px; height: 2px; background-color: var(--accent-color); margin-bottom: 30px;"></div>
                
                <div class="grid-column-flow timelines">
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

<!-- Theme Modal -->
<div id="themeModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 9999; background: rgba(0, 0, 0, 0.7); backdrop-filter: blur(5px); opacity: 0; transition: opacity 0.3s ease; align-items: center; justify-content: center;">
    <div id="themeModalContent" style="background: #fff; width: 90%; max-width: 600px; max-height: 90vh; border-radius: 12px; overflow: hidden; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5); transform: translateY(20px); transition: transform 0.3s ease, width 0.3s ease, max-width 0.3s ease, height 0.3s ease, max-height 0.3s ease; display: flex; flex-direction: column;">
        
        <!-- Header -->
        <div style="padding: 20px 25px; border-bottom: 1px solid #eaeaea; display: flex; justify-content: space-between; align-items: center; background: linear-gradient(135deg, #0f3014 0%, #16421a 100%);">
            <h3 id="themeModalTitle" style="margin: 0; color: #dfb162; font-size: 1.3rem; font-weight: 600; font-family: var(--font-heading);">Theme Title</h3>
            <div style="display: flex; gap: 10px;">
                <button id="themeModalFullscreenBtn" style="background: rgba(255, 255, 255, 0.1); border: none; color: #fff; cursor: pointer; border-radius: 50%; width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; transition: background 0.2s;" onmouseover="this.style.background='rgba(255, 255, 255, 0.2)'" onmouseout="this.style.background='rgba(255, 255, 255, 0.1)'"><i class="fas fa-expand"></i></button>
                <button id="themeModalCloseBtn" style="background: rgba(255, 255, 255, 0.1); border: none; color: #fff; cursor: pointer; border-radius: 50%; width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; transition: background 0.2s;" onmouseover="this.style.background='rgba(255, 255, 255, 0.2)'" onmouseout="this.style.background='rgba(255, 255, 255, 0.1)'"><i class="fas fa-times"></i></button>
            </div>
        </div>
        
        <!-- Body -->
        <div style="padding: 30px 25px; overflow-y: auto; flex-grow: 1; background: #fafafa;">
            <p id="themeModalDescription" style="margin: 0; color: #444; font-size: 1.05rem; line-height: 1.8; text-align: justify; white-space: pre-wrap; font-family: var(--font-body);"></p>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('themeModal');
        const modalContent = document.getElementById('themeModalContent');
        const modalTitle = document.getElementById('themeModalTitle');
        const modalDesc = document.getElementById('themeModalDescription');
        const closeBtn = document.getElementById('themeModalCloseBtn');
        const fullscreenBtn = document.getElementById('themeModalFullscreenBtn');
        let isFullscreen = false;

        document.querySelectorAll('.theme-card-clickable').forEach(card => {
            card.addEventListener('click', function() {
                modalTitle.innerText = this.getAttribute('data-title');
                modalDesc.textContent = this.getAttribute('data-description');
                
                modal.style.display = 'flex';
                // Trigger reflow
                void modal.offsetWidth;
                modal.style.opacity = '1';
                modalContent.style.transform = 'translateY(0)';
            });
        });

        const closeModal = () => {
            modal.style.opacity = '0';
            modalContent.style.transform = 'translateY(20px)';
            setTimeout(() => {
                modal.style.display = 'none';
                if (isFullscreen) toggleFullscreen();
            }, 300);
        };

        const toggleFullscreen = () => {
            isFullscreen = !isFullscreen;
            if (isFullscreen) {
                modalContent.style.width = '100%';
                modalContent.style.maxWidth = '100%';
                modalContent.style.height = '100%';
                modalContent.style.maxHeight = '100%';
                modalContent.style.borderRadius = '0';
                fullscreenBtn.innerHTML = '<i class="fas fa-compress"></i>';
            } else {
                modalContent.style.width = '90%';
                modalContent.style.maxWidth = '600px';
                modalContent.style.height = 'auto';
                modalContent.style.maxHeight = '90vh';
                modalContent.style.borderRadius = '12px';
                fullscreenBtn.innerHTML = '<i class="fas fa-expand"></i>';
            }
        };

        closeBtn.addEventListener('click', closeModal);
        fullscreenBtn.addEventListener('click', toggleFullscreen);

        // Close when clicking outside
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                closeModal();
            }
        });
    });
</script>

@endsection
