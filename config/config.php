<?php
define('APP_NAME', 'BETZONE216');
define('BASE_URL', '/');

function env_value(string $key, $default = null) {
    $value = getenv($key);
    return $value === false ? $default : $value;
}

function db_config(): array {
    return [
        'host' => env_value('DB_HOST', '127.0.0.1'),
        'port' => env_value('DB_PORT', '3306'),
        'name' => env_value('DB_NAME', 'betzone216'),
        'user' => env_value('DB_USER', 'root'),
        'pass' => env_value('DB_PASS', ''),
    ];
}

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_set_cookie_params(['httponly' => true, 'samesite' => 'Lax', 'secure' => !empty($_SERVER['HTTPS'])]);
    session_start();
}
