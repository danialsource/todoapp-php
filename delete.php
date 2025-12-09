<?php
require_once __DIR__.'/includes/csrf.php';
require_once __DIR__.'/includes/db.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { http_response_code(405); exit; }
if (!csrf_validate($_POST['csrf_token'] ?? '')) { die('Invalid CSRF token'); }
$id = (int)($_POST['id'] ?? 0);
$stmt = $conn->prepare('DELETE FROM tasks WHERE id = ?');
$stmt->execute([$id]);
header('Location: index.php'); exit;
?>