<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AICIS 2026 - UIN Siber Syekh Nurjati Cirebon</title>
    <meta name="description" content="The Annual International Conference on Islamic Studies (AICIS) 2026 at UIN Siber Syekh Nurjati Cirebon.">
    @php
        $siteSettings = \App\Models\Setting::first();
        $faviconUrl = $siteSettings && $siteSettings->favicon ? Storage::url($siteSettings->favicon) : asset('favicon.ico');
        $logoUrl = $siteSettings && $siteSettings->logo ? Storage::url($siteSettings->logo) : asset('images/logo.png');
        $siteTitle = $siteSettings && $siteSettings->site_title ? $siteSettings->site_title : 'AICIS 2026';
        $siteSubtitle = $siteSettings && $siteSettings->site_subtitle ? $siteSettings->site_subtitle : 'UIN SIBER SYEKH NURJATI';
    @endphp
    
    <link rel="icon" href="{{ $faviconUrl }}" type="image/x-icon">
    
    <!-- Google Fonts: Montserrat & Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- FontAwesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Custom Styles -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}?v={{ time() }}">
    
    <!-- Swiper JS CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
</head>
<body>
    
    <!-- Top Bar -->
    <div class="top-bar">
        <div class="container top-bar-container">
            <div class="top-bar-left" style="display: flex; align-items: center;">
                <span><i class="fas fa-phone-alt"></i> {{ $siteSettings?->phone ?? '+62 851-1702-2042' }}</span>
                <span style="margin-left: 15px;"><i class="fas fa-envelope"></i> {{ $siteSettings?->email ?? 'aicis2026@syekhnurjati.ac.id' }}</span>
                <!-- Top Menu -->
                @php
                    $topMenu = \Datlechin\FilamentMenuBuilder\Models\Menu::where('name', 'Top Menu')->first();
                    $topMenuItems = $topMenu ? $topMenu->menuItems()->whereNull('parent_id')->orderBy('order')->get() : [];
                @endphp
                @if(count($topMenuItems) > 0)
                    <div style="display: flex; gap: 15px; margin-left: 20px; font-size: 0.85rem; border-left: 1px solid rgba(255,255,255,0.3); padding-left: 15px;">
                        @foreach($topMenuItems as $item)
                            <a href="{{ $item->url ? url($item->url) : '#' }}" style="color: #fff; text-decoration: none; transition: color 0.3s;" onmouseover="this.style.color='#dfb162'" onmouseout="this.style.color='#fff'">{{ $item->title }}</a>
                        @endforeach
                    </div>
                @endif
            </div>
            <div class="top-bar-right" style="display: flex; align-items: center; gap: 15px;">
                <a href="{{ url('/contact') }}" style="color: #fff; margin-right: 10px;"><i class="fas fa-map-marker-alt"></i> {{ \Illuminate\Support\Str::limit(strip_tags($siteSettings?->address ?? 'UIN Siber Syekh Nurjati Cirebon'), 40) }}</a>
                
                <!-- Custom Language Dropdown -->
                <div class="custom-lang-dropdown" style="position: relative;">
                    <div class="current-lang" id="currentLangBtn" style="color: #fff; display: flex; align-items: center; gap: 8px; cursor: pointer; padding: 4px 8px; border: 1px solid rgba(255,255,255,0.3); border-radius: 4px;">
                        <img src="https://flagcdn.com/w20/gb.png" alt="English" id="currentLangFlag" style="width: 20px; border-radius: 2px;">
                        <i class="fas fa-chevron-down" style="font-size: 0.7rem;"></i>
                    </div>
                    <div class="lang-options" id="langOptions" style="display: none; position: absolute; right: 0; top: 100%; background: var(--white); border-radius: 4px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); padding: 5px 0; min-width: 120px; z-index: 1000;">
                        <a href="#" class="lang-switch-btn" data-lang="en" style="display: flex; align-items: center; gap: 10px; padding: 8px 15px; color: var(--text-dark); text-decoration: none;">
                            <img src="https://flagcdn.com/w20/gb.png" alt="English" style="width: 20px;"> English
                        </a>
                        <a href="#" class="lang-switch-btn" data-lang="id" style="display: flex; align-items: center; gap: 10px; padding: 8px 15px; color: var(--text-dark); text-decoration: none;">
                            <img src="https://flagcdn.com/w20/id.png" alt="Indonesia" style="width: 20px;"> Indonesia
                        </a>
                        <a href="#" class="lang-switch-btn" data-lang="ar" style="display: flex; align-items: center; gap: 10px; padding: 8px 15px; color: var(--text-dark); text-decoration: none;">
                            <img src="https://flagcdn.com/w20/sa.png" alt="Arabic" style="width: 20px;"> العربية
                        </a>
                    </div>
                </div>
                
                <!-- Dark Mode Toggle -->
                <div class="dark-mode-toggle" id="darkModeToggle" title="Toggle Dark/Light Mode" style="color: #fff; width: 28px; height: 28px;">
                    <i class="fas fa-moon"></i>
                </div>

                <!-- Login / Dashboard -->
                @auth
                    <a href="{{ url('/admin') }}" class="btn btn-primary nav-btn" style="padding: 4px 12px; font-size: 0.75rem; border-color: rgba(255,255,255,0.3);">Dashboard</a>
                @else
                    <a href="{{ url('/admin/login') }}" class="btn btn-primary nav-btn" style="padding: 4px 12px; font-size: 0.75rem; border-color: rgba(255,255,255,0.3);">Login / Register</a>
                @endauth
            </div>
        </div>
    </div>

    <!-- Header / Navbar -->
    <header class="header">
        <div class="container nav-container">
            <a href="{{ url('/') }}" class="logo">
                <div style="display: flex; align-items: center; gap: 10px;">
                    <img src="{{ $logoUrl }}" alt="AICIS Logo">
                    <div class="logo-text">
                        {{ $siteTitle }}
                        <span>{{ $siteSubtitle }}</span>
                    </div>
                </div>
            </a>
            
            <nav class="main-menu">
                @php
                    $mainMenu = \Datlechin\FilamentMenuBuilder\Models\Menu::where('name', 'Main Menu')->first();
                    $mainMenuItems = $mainMenu ? $mainMenu->menuItems()->with('children')->whereNull('parent_id')->orderBy('order')->get() : [];
                @endphp
                @if(count($mainMenuItems) > 0)
                    @include('components.menu-items', ['items' => $mainMenuItems])
                @else
                    <a href="{{ url('/') }}" class="{{ request()->is('/') ? 'active' : '' }}">Home</a>
                @endif
            </nav>

            <div class="nav-right">
                <i class="fas fa-search search-icon"></i>
            </div>
            
            <div class="mobile-menu-toggle">
                <i class="fas fa-bars"></i>
            </div>
        </div>
    </header>

    <!-- Announcements Ticker -->
    @php
        $announcements = \App\Models\Announcement::where('is_active', true)
            ->orderBy('sort_order')
            ->get();
    @endphp
    
    <div class="announcement-ticker" style="background-color: #063A27; color: #dfb162; padding: 8px 0; border-bottom: 1px solid rgba(223, 177, 98, 0.2); position: relative; z-index: 999; font-size: 0.9rem;">
        <div class="container" style="display: flex; align-items: center;">
            <div style="font-weight: 600; padding-right: 15px; border-right: 1px solid rgba(223, 177, 98, 0.3); z-index: 10; background-color: #063A27; display: flex; align-items: center; gap: 8px; flex-shrink: 0; text-transform: uppercase; font-size: 0.8rem; letter-spacing: 1px;">
                <i class="fas fa-bullhorn"></i> Announcement
            </div>
            <div style="flex-grow: 1; overflow: hidden; white-space: nowrap; padding-left: 15px; position: relative; z-index: 1;">
                <div class="ticker-content" style="display: inline-block; padding-left: 100%; animation: ticker 25s linear infinite;">
                    @if($announcements->count() > 0)
                        @foreach($announcements as $announcement)
                            @php
                                $color = '#dfb162';
                                $icon = 'fa-circle';
                                if ($announcement->urgency === 'important') {
                                    $color = '#f59e0b';
                                    $icon = 'fa-exclamation-triangle';
                                } elseif ($announcement->urgency === 'urgent') {
                                    $color = '#ef4444';
                                    $icon = 'fa-exclamation-circle';
                                }
                            @endphp
                            <span style="margin-right: 50px;">
                                <i class="fas {{ $icon }}" style="font-size: 0.7rem; margin-right: 8px; vertical-align: middle; color: {{ $color }}; opacity: 0.8;"></i>
                                @if($announcement->link)
                                    <a href="{{ $announcement->link }}" style="color: {{ $color }}; text-decoration: none; transition: filter 0.3s;" onmouseover="this.style.filter='brightness(1.5)'" onmouseout="this.style.filter='none'">{{ $announcement->text }}</a>
                                @else
                                    <span style="color: {{ $color }}">{{ $announcement->text }}</span>
                                @endif
                            </span>
                        @endforeach
                    @else
                        <span style="color: #dfb162">No active announcements at this moment.</span>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <style>
        .ticker-content:hover {
            animation-play-state: paused !important;
        }
        @keyframes ticker {
            0% { transform: translateX(0); }
            100% { transform: translateX(-100%); }
        }
    </style>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Secondary Menu -->
    @php
        $secondaryMenu = \Datlechin\FilamentMenuBuilder\Models\Menu::where('name', 'Secondary Menu')->first();
        $secondaryMenuItems = $secondaryMenu ? $secondaryMenu->menuItems()->whereNull('parent_id')->orderBy('order')->get() : [];
    @endphp
    @if(count($secondaryMenuItems) > 0)
    <div style="background-color: #f8f9fa; border-top: 1px solid #eaeaea; border-bottom: 1px solid #eaeaea; padding: 15px 0;">
        <div class="container" style="display: flex; justify-content: center; gap: 25px; flex-wrap: wrap;">
            @foreach($secondaryMenuItems as $item)
                <a href="{{ $item->url ? url($item->url) : '#' }}" style="color: var(--text-dark); font-weight: 500; font-size: 0.95rem; transition: color 0.3s;" onmouseover="this.style.color='var(--primary-color)'" onmouseout="this.style.color='var(--text-dark)'">{{ $item->title }}</a>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Footer -->
    <footer class="footer" style="background: linear-gradient(135deg, #021a10 0%, #063A27 100%); color: #cbd5e1; padding: 100px 0 0 0; position: relative; overflow: hidden; border-top: 4px solid #dfb162; border-radius: 50% 50% 0 0 / 60px 60px 0 0; margin-top: 40px;">
        <!-- Background Pattern / Glow -->
        <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; opacity: 0.05; pointer-events: none; background-image: radial-gradient(circle at 50% 150%, #dfb162 0%, transparent 70%);"></div>
        
        @if($siteSettings?->dark_logo)
        <!-- Silhouette Logo -->
        <div style="position: absolute; bottom: 0; right: 0; width: 100%; height: 100%; pointer-events: none; z-index: 1; overflow: hidden;">
            <img src="{{ Storage::url($siteSettings->dark_logo) }}" alt="Silhouette Logo" style="position: absolute; bottom: 60px; right: 0px; height: auto; width: 450px; max-width: 50vw; opacity: 0.08; object-fit: contain;">
        </div>
        @endif
        
        <div class="container footer-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 50px; position: relative; z-index: 2; padding-bottom: 60px;">
            <div class="footer-widget">
                <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 25px;">
                    <img src="{{ $logoUrl }}" alt="AICIS Logo" style="height: 45px; background: rgba(255,255,255,0.1); padding: 5px; border-radius: 8px; backdrop-filter: blur(5px);">
                    <div style="font-family: var(--font-heading); font-weight: 700; font-size: 1.15rem; color: #fff; line-height: 1.2;">
                        {{ $siteTitle }}<br>
                        <span style="color: #dfb162; font-size: 0.75rem; font-weight: 500; letter-spacing: 1px;">{{ $siteSubtitle }}</span>
                    </div>
                </div>
                <p style="margin-bottom: 25px; line-height: 1.8; color: #94a3b8; font-size: 0.85rem;">The Annual International Conference on Islamic Studies (AICIS) is a premier academic event for scholars to discuss and present their latest findings in Islamic Studies, fostering global academic dialogue and innovation.</p>
                <div style="display: flex; gap: 15px;">
                    @if($siteSettings?->facebook_url)
                    <a href="{{ $siteSettings->facebook_url }}" target="_blank" style="display: flex; align-items: center; justify-content: center; width: 36px; height: 36px; border-radius: 50%; background: rgba(255,255,255,0.05); color: #cbd5e1; font-size: 1rem; transition: all 0.3s ease; border: 1px solid rgba(255,255,255,0.1);" onmouseover="this.style.background='#dfb162'; this.style.color='#021a10'; this.style.transform='translateY(-5px)'" onmouseout="this.style.background='rgba(255,255,255,0.05)'; this.style.color='#cbd5e1'; this.style.transform='translateY(0)'"><i class="fab fa-facebook-f"></i></a>
                    @endif
                    @if($siteSettings?->twitter_url)
                    <a href="{{ $siteSettings->twitter_url }}" target="_blank" style="display: flex; align-items: center; justify-content: center; width: 36px; height: 36px; border-radius: 50%; background: rgba(255,255,255,0.05); color: #cbd5e1; font-size: 1rem; transition: all 0.3s ease; border: 1px solid rgba(255,255,255,0.1);" onmouseover="this.style.background='#dfb162'; this.style.color='#021a10'; this.style.transform='translateY(-5px)'" onmouseout="this.style.background='rgba(255,255,255,0.05)'; this.style.color='#cbd5e1'; this.style.transform='translateY(0)'"><i class="fab fa-twitter"></i></a>
                    @endif
                    @if($siteSettings?->instagram_url)
                    <a href="{{ $siteSettings->instagram_url }}" target="_blank" style="display: flex; align-items: center; justify-content: center; width: 36px; height: 36px; border-radius: 50%; background: rgba(255,255,255,0.05); color: #cbd5e1; font-size: 1rem; transition: all 0.3s ease; border: 1px solid rgba(255,255,255,0.1);" onmouseover="this.style.background='#dfb162'; this.style.color='#021a10'; this.style.transform='translateY(-5px)'" onmouseout="this.style.background='rgba(255,255,255,0.05)'; this.style.color='#cbd5e1'; this.style.transform='translateY(0)'"><i class="fab fa-instagram"></i></a>
                    @endif
                    @if($siteSettings?->youtube_url)
                    <a href="{{ $siteSettings->youtube_url }}" target="_blank" style="display: flex; align-items: center; justify-content: center; width: 36px; height: 36px; border-radius: 50%; background: rgba(255,255,255,0.05); color: #cbd5e1; font-size: 1rem; transition: all 0.3s ease; border: 1px solid rgba(255,255,255,0.1);" onmouseover="this.style.background='#dfb162'; this.style.color='#021a10'; this.style.transform='translateY(-5px)'" onmouseout="this.style.background='rgba(255,255,255,0.05)'; this.style.color='#cbd5e1'; this.style.transform='translateY(0)'"><i class="fab fa-youtube"></i></a>
                    @endif
                </div>
            </div>
            
            <div class="footer-widget">
                <h4 style="color: #fff; font-family: var(--font-heading); font-size: 1.1rem; margin-bottom: 25px; position: relative; padding-bottom: 12px; font-weight: 600;">
                    Quick Links
                    <span style="position: absolute; bottom: 0; left: 0; width: 40px; height: 3px; background: #dfb162; border-radius: 2px;"></span>
                </h4>
                <ul style="list-style: none; padding: 0; margin: 0;">
                    @php
                        $footerMenu = \Datlechin\FilamentMenuBuilder\Models\Menu::where('name', 'Footer Menu')->first();
                        $footerMenuItems = $footerMenu ? $footerMenu->menuItems()->orderBy('order')->get() : [];
                    @endphp
                    @if(count($footerMenuItems) > 0)
                        @foreach($footerMenuItems as $item)
                        <li style="margin-bottom: 10px;"><a href="{{ $item->url ? url($item->url) : '#' }}" style="color: #94a3b8; text-decoration: none; transition: all 0.3s ease; display: inline-flex; align-items: center; gap: 8px; font-size: 0.85rem;" onmouseover="this.style.color='#dfb162'; this.style.transform='translateX(8px)'" onmouseout="this.style.color='#94a3b8'; this.style.transform='translateX(0)'"><i class="fas fa-chevron-right" style="font-size: 0.6rem; color: #dfb162;"></i> {{ $item->title }}</a></li>
                        @endforeach
                    @else
                        <li style="margin-bottom: 10px;"><a href="{{ url('/') }}" style="color: #94a3b8; text-decoration: none; transition: all 0.3s ease; display: inline-flex; align-items: center; gap: 8px; font-size: 0.85rem;" onmouseover="this.style.color='#dfb162'; this.style.transform='translateX(8px)'" onmouseout="this.style.color='#94a3b8'; this.style.transform='translateX(0)'"><i class="fas fa-chevron-right" style="font-size: 0.6rem; color: #dfb162;"></i> Home</a></li>
                    @endif
                </ul>
            </div>

            <div class="footer-widget">
                <h4 style="color: #fff; font-family: var(--font-heading); font-size: 1.1rem; margin-bottom: 25px; position: relative; padding-bottom: 12px; font-weight: 600;">
                    Contact Info
                    <span style="position: absolute; bottom: 0; left: 0; width: 40px; height: 3px; background: #dfb162; border-radius: 2px;"></span>
                </h4>
                <ul class="footer-contact" style="list-style: none; padding: 0; margin: 0;">
                    <li style="display: flex; gap: 15px; margin-bottom: 18px;">
                        <div style="width: 36px; height: 36px; border-radius: 8px; background: rgba(223, 177, 98, 0.1); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                            <i class="fas fa-map-marker-alt" style="color: #dfb162; font-size: 1rem;"></i>
                        </div>
                        <span style="color: #94a3b8; line-height: 1.6; font-size: 0.85rem; margin-top: 4px;">{!! nl2br(e($siteSettings?->address ?? "UIN Siber Syekh Nurjati\nJl. Perjuangan By Pass Sunyaragi\nCirebon, West Java")) !!}</span>
                    </li>
                    <li style="display: flex; gap: 15px; margin-bottom: 18px;">
                        <div style="width: 36px; height: 36px; border-radius: 8px; background: rgba(223, 177, 98, 0.1); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                            <i class="fas fa-phone-alt" style="color: #dfb162; font-size: 1rem;"></i>
                        </div>
                        <span style="color: #94a3b8; font-size: 0.85rem; margin-top: 8px;">{{ $siteSettings?->phone ?? '+62 851-1702-2042' }}</span>
                    </li>
                    <li style="display: flex; gap: 15px; margin-bottom: 18px;">
                        <div style="width: 36px; height: 36px; border-radius: 8px; background: rgba(223, 177, 98, 0.1); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                            <i class="fas fa-envelope" style="color: #dfb162; font-size: 1rem;"></i>
                        </div>
                        <span style="color: #94a3b8; font-size: 0.85rem; margin-top: 8px;">{{ $siteSettings?->email ?? 'aicis2026@syekhnurjati.ac.id' }}</span>
                    </li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom" style="background: rgba(0,0,0,0.4); padding: 20px 0; border-top: 1px solid rgba(255,255,255,0.05); position: relative; z-index: 2;">
            <div class="container" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px;">
                <div style="color: #64748b; font-size: 0.8rem;">
                    &copy; 2026 <strong style="color: #dfb162;">AICIS</strong> - UIN Siber Syekh Nurjati Cirebon. All Rights Reserved.
                </div>
                <div style="color: #64748b; font-size: 0.75rem; display: flex; gap: 20px;">
                    <a href="#" style="color: #64748b; text-decoration: none; transition: color 0.3s ease;" onmouseover="this.style.color='#dfb162'" onmouseout="this.style.color='#64748b'">Privacy Policy</a>
                    <a href="#" style="color: #64748b; text-decoration: none; transition: color 0.3s ease;" onmouseover="this.style.color='#dfb162'" onmouseout="this.style.color='#64748b'">Terms of Service</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Google Translate Script -->
    <div id="google_translate_element" style="display: none;"></div>
    <script type="text/javascript">
        function googleTranslateElementInit() {
            new google.translate.TranslateElement({pageLanguage: 'en', autoDisplay: false}, 'google_translate_element');
        }
    </script>
    <script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>

    <!-- Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

    <script src="{{ asset('js/script.js') }}?v={{ time() }}"></script>

    <!-- Global Accessibility Floating Bubble -->
    @include('partials.floating-bubble')
</body>
</html>
