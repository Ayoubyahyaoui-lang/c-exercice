<?php
function e($value): string { return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8'); }

function is_logged_in(): bool { return isset($_SESSION['user']); }
function current_user_id(): ?int { return is_logged_in() ? (int)$_SESSION['user']['id'] : null; }
function is_admin(): bool { return is_logged_in() && ($_SESSION['user']['role'] ?? 'user') === 'admin'; }

function flash(string $key, ?string $message = null): ?string {
    if ($message !== null) { $_SESSION['flash'][$key] = $message; return null; }
    $message = $_SESSION['flash'][$key] ?? null;
    unset($_SESSION['flash'][$key]);
    return $message;
}

function redirect(string $url): never { header('Location: ' . $url); exit; }

function require_login(): void {
    if (!is_logged_in()) redirect('account.php?auth=login&next=' . urlencode($_SERVER['REQUEST_URI'] ?? 'account.php'));
}

function require_admin(): void {
    if (!is_admin()) redirect('account.php?auth=login');
}

function csrf_token(): string {
    if (empty($_SESSION['csrf'])) $_SESSION['csrf'] = bin2hex(random_bytes(32));
    return $_SESSION['csrf'];
}

function verify_csrf(): void {
    if (!hash_equals($_SESSION['csrf'] ?? '', $_POST['csrf'] ?? '')) {
        http_response_code(403);
        exit('Security validation failed.');
    }
}

function money(float $amount): string { return number_format($amount, 2); }
