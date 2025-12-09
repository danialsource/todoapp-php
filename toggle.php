<?php
require_once __DIR__.'/includes/csrf.php';
require_once __DIR__.'/includes/db.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { http_response_code(405); exit; }
if (!csrf_validate($_POST['csrf_token'] ?? '')) { die('Invalid CSRF token'); }
$id = (int)($_POST['id'] ?? 0);
// get current
$stmt = $conn->prepare('SELECT completed FROM tasks WHERE id = ?');
$stmt->execute([$id]);
$task = $stmt->fetch();
if (!$task) { header('Location: index.php'); exit; }
$new = $task['completed'] ? 0 : 1;
if ($new) {
$u = $conn->prepare('UPDATE tasks SET completed = 1, completed_at = NOW() WHERE id = ?');
$u->execute([$id]);
} else {
$u = $conn->prepare('UPDATE tasks SET completed = 0, completed_at = NULL WHERE id = ?');
$u->execute([$id]);
}
header('Location: index.php'); exit;
?>