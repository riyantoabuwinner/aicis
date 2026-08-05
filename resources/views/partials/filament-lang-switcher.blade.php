<style>
/* Custom Language Dropdown for Filament */
.custom-lang-dropdown {
    position: relative;
    z-index: 1000;
}
.current-lang {
    display: flex;
    align-items: center;
    gap: 8px;
    cursor: pointer;
    padding: 6px 12px;
    border: 1px solid var(--gray-300, rgba(255,255,255,0.3));
    border-radius: 8px;
    background: var(--white, #fff);
    color: var(--text-dark, #333);
}
.dark .current-lang {
    background: rgba(255,255,255,0.1);
    border-color: rgba(255,255,255,0.1);
    color: #fff;
}
.lang-options {
    display: none;
    position: absolute;
    right: 0;
    top: 100%;
    margin-top: 5px;
    background: var(--white, #fff);
    border-radius: 8px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    padding: 8px 0;
    min-width: 140px;
    z-index: 1050;
    border: 1px solid var(--gray-200, #eee);
}
.dark .lang-options {
    background: #1f2937;
    border-color: #374151;
}
.lang-switch-btn {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 8px 15px;
    color: var(--text-dark, #333);
    text-decoration: none;
    transition: background 0.2s;
}
.dark .lang-switch-btn {
    color: #f3f4f6;
}
.lang-switch-btn:hover {
    background: #f3f4f6;
}
.dark .lang-switch-btn:hover {
    background: #374151;
}
</style>

<div style="display: flex; align-items: center; gap: 15px; margin-right: 15px;">
    <!-- Dark Mode Toggle -->
    <div class="dark-mode-toggle" id="customDarkModeToggle" title="Toggle Dark/Light Mode" style="cursor: pointer; display: flex; align-items: center; justify-content: center; width: 34px; height: 34px; border-radius: 8px; border: 1px solid var(--gray-300, rgba(255,255,255,0.3)); background: var(--white, #fff); color: var(--text-dark, #333); transition: all 0.3s;">
        <i class="fas fa-moon"></i>
    </div>

    <!-- Custom Language Dropdown -->
    <div class="custom-lang-dropdown">
        <div class="current-lang" id="currentLangBtn">
            <img src="https://flagcdn.com/w20/gb.png" alt="English" id="currentLangFlag" style="width: 20px; border-radius: 2px;">
            <i class="fas fa-chevron-down" style="font-size: 0.7rem;"></i>
        </div>
        <div class="lang-options" id="langOptions">
            <a href="#" class="lang-switch-btn" data-lang="en">
                <img src="https://flagcdn.com/w20/gb.png" alt="English" style="width: 20px;"> English
            </a>
            <a href="#" class="lang-switch-btn" data-lang="id">
                <img src="https://flagcdn.com/w20/id.png" alt="Indonesia" style="width: 20px;"> Indonesia
            </a>
            <a href="#" class="lang-switch-btn" data-lang="ar">
                <img src="https://flagcdn.com/w20/sa.png" alt="Arabic" style="width: 20px;"> العربية
            </a>
        </div>
    </div>
</div>

<!-- Google Translate Script -->
<div id="google_translate_element" style="display: none;"></div>
<script type="text/javascript">
    function googleTranslateElementInit() {
        new google.translate.TranslateElement({pageLanguage: 'en', autoDisplay: false}, 'google_translate_element');
    }
</script>
<script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        // --- Dark Mode Logic for Filament ---
        const darkModeToggle = document.getElementById('customDarkModeToggle');
        const html = document.documentElement;
        
        // Ensure initial icon state matches Filament's state
        if (html.classList.contains('dark') || localStorage.getItem('theme') === 'dark') {
            if (darkModeToggle) {
                darkModeToggle.innerHTML = '<i class="fas fa-sun"></i>';
                darkModeToggle.style.background = 'rgba(255,255,255,0.1)';
                darkModeToggle.style.borderColor = 'rgba(255,255,255,0.1)';
                darkModeToggle.style.color = '#fff';
            }
        }

        if (darkModeToggle) {
            darkModeToggle.addEventListener('click', () => {
                // Dispatch Alpine JS event for Filament to handle it properly, 
                // or just toggle the class manually if Alpine isn't hooked there.
                html.classList.toggle('dark');
                
                if (html.classList.contains('dark')) {
                    localStorage.setItem('theme', 'dark');
                    darkModeToggle.innerHTML = '<i class="fas fa-sun"></i>';
                    darkModeToggle.style.background = 'rgba(255,255,255,0.1)';
                    darkModeToggle.style.borderColor = 'rgba(255,255,255,0.1)';
                    darkModeToggle.style.color = '#fff';
                    // Optional: force trigger alpine dark mode
                    window.dispatchEvent(new CustomEvent('theme-changed', { detail: 'dark' }));
                } else {
                    localStorage.setItem('theme', 'light');
                    darkModeToggle.innerHTML = '<i class="fas fa-moon"></i>';
                    darkModeToggle.style.background = '#fff';
                    darkModeToggle.style.borderColor = 'rgba(255,255,255,0.3)';
                    darkModeToggle.style.color = '#333';
                    window.dispatchEvent(new CustomEvent('theme-changed', { detail: 'light' }));
                }
            });
        }
        // --- Google Translate Logic ---
        const langButtons = document.querySelectorAll('.lang-switch-btn');
        const currentLangBtn = document.getElementById('currentLangBtn');
        const langOptions = document.getElementById('langOptions');
        const currentLangFlag = document.getElementById('currentLangFlag');

        if (langButtons.length > 0 && currentLangBtn && langOptions) {
            
            // Toggle Dropdown
            currentLangBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                langOptions.style.display = langOptions.style.display === 'block' ? 'none' : 'block';
            });

            // Close dropdown when clicking outside
            document.addEventListener('click', () => {
                langOptions.style.display = 'none';
            });

            function getCookie(name) {
                let matches = document.cookie.match(new RegExp("(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"));
                return matches ? decodeURIComponent(matches[1]) : undefined;
            }

            let currentLang = getCookie('googtrans');
            let activeLangCode = 'en'; // default

            if (currentLang) {
                activeLangCode = currentLang.split('/').pop();
            }

            // Update main button flag
            const activeBtn = document.querySelector(`.lang-switch-btn[data-lang="${activeLangCode}"]`);
            if (activeBtn) {
                currentLangFlag.src = activeBtn.querySelector('img').src;
                activeBtn.style.backgroundColor = document.documentElement.classList.contains('dark') ? '#374151' : '#f0f0f0'; 
            }

            langButtons.forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    let lang = this.dataset.lang;
                    if (lang === 'en') {
                        document.cookie = "googtrans=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
                        document.cookie = "googtrans=; expires=Thu, 01 Jan 1970 00:00:00 UTC; domain=." + location.hostname + "; path=/;";
                        document.cookie = "googtrans=; expires=Thu, 01 Jan 1970 00:00:00 UTC; domain=" + location.hostname + "; path=/;";
                    } else {
                        let cookieValue = '/en/' + lang;
                        document.cookie = "googtrans=" + cookieValue + "; path=/";
                        document.cookie = "googtrans=" + cookieValue + "; domain=" + location.hostname + "; path=/";
                    }
                    window.location.reload();
                });
            });
        }
    });
</script>
