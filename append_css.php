<?php
$css = "
/* Menu Dropdown Styles */
.menu-dropdown { position: relative; display: inline-block; }
.menu-dropdown-content { display: none; position: absolute; top: 100%; left: 0; background-color: var(--white); min-width: 200px; box-shadow: 0 8px 16px rgba(0,0,0,0.1); z-index: 1000; border-radius: 8px; border: 1px solid var(--border-color); overflow: hidden; }
.menu-dropdown-content a { color: var(--text-dark) !important; padding: 12px 16px !important; text-decoration: none; display: block; border-bottom: 1px solid var(--border-color); font-weight: normal; }
.menu-dropdown-content a:last-child { border-bottom: none; }
.menu-dropdown-content a:hover { background-color: rgba(212, 175, 55, 0.1); color: var(--primary-color) !important; }
.menu-dropdown:hover > .menu-dropdown-content { display: block; }
.menu-dropdown .menu-dropdown { position: relative; display: block; }
.menu-dropdown .menu-dropdown-content { top: 0; left: 100%; }
";
file_put_contents('public/css/style.css', $css, FILE_APPEND);
echo "CSS appended.\n";
