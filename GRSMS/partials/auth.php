<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$currentScript = basename($_SERVER['PHP_SELF'] ?? '');
$publicPages = ['login.php', 'signup.php', 'logout.php'];

if (!in_array($currentScript, $publicPages, true) && empty($_SESSION['staff'])) {
    header('Location: login.php');
    exit;
}

function requireRole(array $allowedRoles, string $fallbackUrl = 'index.php?from_login=1'): void {
    $role = $_SESSION['staff']['role'] ?? '';
    if (!in_array($role, $allowedRoles, true)) {
        header('Location: ' . $fallbackUrl);
        exit;
    }
}
