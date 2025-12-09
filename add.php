<?php
require_once __DIR__.'/includes/csrf.php';
require_once __DIR__.'/includes/db.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
http_response_code(405);
exit;
}
if (!csrf_validate($_POST['csrf_token'] ?? '')) {
die('Invalid CSRF token');
}
$title = trim($_POST['title'] ?? '');
desc = trim($_POST['description'] ?? '');
$priority = (int)($_POST['priority'] ?? 2);
if ($title === '' || $desc === '') {
$_SESSION['error'] = 'عنوان و توضیحات لازم است.';
header('Location: index.php');
exit;
}
$stmt = $conn->prepare('INSERT INTO tasks (title, description, priority, completed, created_at) VALUES (?, ?, ?, 0, NOW())');
$stmt->execute([$title, $desc, $priority]);
header('Location: index.php');
exit;
?>