document.addEventListener('DOMContentLoaded', () => {
    // Mobile menu toggle
    const mobileMenuToggle = document.querySelector('.mobile-menu-toggle');
    const mainMenu = document.querySelector('.main-menu');
    const navRight = document.querySelector('.nav-right');
    
    if (mobileMenuToggle && mainMenu) {
        mobileMenuToggle.addEventListener('click', () => {
            mainMenu.style.display = mainMenu.style.display === 'flex' ? 'none' : 'flex';
            mainMenu.style.flexDirection = 'column';
            mainMenu.style.position = 'absolute';
            mainMenu.style.top = '100%';
            mainMenu.style.left = '0';
            mainMenu.style.width = '100%';
            mainMenu.style.backgroundColor = 'var(--white)';
            mainMenu.style.padding = '20px';
            mainMenu.style.boxShadow = '0 5px 10px rgba(0,0,0,0.1)';
            
            if (navRight) {
                navRight.classList.toggle('show-mobile');
            }
        });
    }

    // --- Dark Mode Logic ---
    const darkModeToggle = document.getElementById('darkModeToggle');
    const body = document.body;
    
    // Check local storage for theme
    if (localStorage.getItem('theme') === 'dark') {
        body.classList.add('dark-mode');
        if (darkModeToggle) darkModeToggle.innerHTML = '<i class="fas fa-sun"></i>';
    }

    if (darkModeToggle) {
        darkModeToggle.addEventListener('click', () => {
            body.classList.toggle('dark-mode');
            if (body.classList.contains('dark-mode')) {
                localStorage.setItem('theme', 'dark');
                darkModeToggle.innerHTML = '<i class="fas fa-sun"></i>';
            } else {
                localStorage.setItem('theme', 'light');
                darkModeToggle.innerHTML = '<i class="fas fa-moon"></i>';
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
            activeBtn.style.backgroundColor = '#f0f0f0'; // highlight selected option
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
