<?php
declare(strict_types=1);
if (session_status() !== PHP_SESSION_ACTIVE) session_start();

$scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/opi_eestis/index.php'));
if (substr($scriptDir, -6) === '/admin') $scriptDir = substr($scriptDir, 0, -6);
define('BASE_PATH', rtrim($scriptDir, '/'));

function url(string $path = ''): string { return (BASE_PATH ?: '') . '/' . ltrim($path, '/'); }
function e($value): string { return htmlspecialchars((string)$value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); }
function csrf_token(): string {
    if (empty($_SESSION['csrf'])) $_SESSION['csrf'] = bin2hex(random_bytes(32));
    return $_SESSION['csrf'];
}
function csrf_valid(): bool {
    return isset($_POST['csrf'], $_SESSION['csrf']) && is_string($_POST['csrf']) && hash_equals($_SESSION['csrf'], $_POST['csrf']);
}
function redirect(string $path = ''): void { header('Location: ' . url($path)); exit; }
function is_logged_in(): bool { return isset($_SESSION['user_id']); }
function is_admin(): bool { return is_logged_in() && ($_SESSION['role'] ?? '') === 'admin'; }
function require_admin(): void { if (!is_admin()) { http_response_code(403); render('error', ['title' => 'Ligipääs puudub', 'message' => 'Selle lehe nägemiseks pead olema administraator.']); exit; } }
function require_login(): void { if (!is_logged_in()) redirect('admin/'); }
function flash(string $message = ''): string {
    if ($message !== '') { $_SESSION['flash'] = $message; return ''; }
    $message = (string)($_SESSION['flash'] ?? ''); unset($_SESSION['flash']); return $message;
}
function render(string $template, array $data = []): void {
    extract($data, EXTR_SKIP);
    ob_start();
    require __DIR__ . '/../view/' . $template . '.php';
    $content = ob_get_clean();
    require __DIR__ . '/../view/layout.php';
}
function quiz_score(array $questions, array $answers): array {
    $correct = 0;
    foreach ($questions as $question) {
        $id = (int)$question['id'];
        if (isset($answers[$id]) && (int)$answers[$id] === (int)$question['correct_option_id']) $correct++;
    }
    return ['correct' => $correct, 'total' => count($questions)];
}
