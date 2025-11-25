<?php
// session_config.php
ini_set('session.cookie_httponly', 1); // Prevents JavaScript from stealing cookies
ini_set('session.use_only_cookies', 1); // Prevents ID passing in URL
// enable secure cookies only when HTTPS is active (keep off for local http)
$secure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 1 : 0;
ini_set('session.cookie_secure', $secure);
// set SameSite policy if supported (PHP 7.3+)
if (PHP_VERSION_ID >= 70300) {
    ini_set('session.cookie_samesite', 'Lax');
}
session_start();
session_regenerate_id(true); // Generates new ID on every refresh to prevent fixation