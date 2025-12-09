<?php
// includes/functions.php
require_once __DIR__.'/db.php';


function fetch_tasks($conn, $filter = null) {
if ($filter === 'active') {
$stmt = $conn->prepare("SELECT * FROM tasks WHERE completed = 0 ORDER BY priority DESC, created_at DESC");
$stmt->execute();
} elseif ($filter === 'completed') {
$stmt = $conn->prepare("SELECT * FROM tasks WHERE completed = 1 ORDER BY completed_at DESC");
$stmt->execute();
} else {
$stmt = $conn->prepare("SELECT * FROM tasks ORDER BY completed, priority DESC, created_at DESC");
$stmt->execute();
}
return $stmt->fetchAll();
}


function get_task($conn, $id) {
$stmt = $conn->prepare('SELECT * FROM tasks WHERE id = ?');
$stmt->execute([(int)$id]);
return $stmt->fetch();
}


function esc($s) {
return htmlspecialchars($s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}
?>