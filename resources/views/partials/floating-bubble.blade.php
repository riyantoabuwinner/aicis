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

