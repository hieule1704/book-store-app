// session_config.php
ini_set('session.cookie_httponly', 1); // Prevents JavaScript from stealing cookies
ini_set('session.use_only_cookies', 1); // Prevents ID passing in URL
ini_set('session.cookie_secure', 1); // Only if you use HTTPS (Turn off for localhost)
session_start();
session_regenerate_id(true); // Generates new ID on every refresh to prevent fixation