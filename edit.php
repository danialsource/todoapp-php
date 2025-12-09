<?php
require_once __DIR__.'/includes/csrf.php';
require_once __DIR__.'/includes/functions.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
require_once __DIR__.'/includes/db.php';
if (!csrf_validate($_POST['csrf_token'] ?? '')) { die('Invalid CSRF token'); }
$id = (int)($_POST['id'] ?? 0);
$title = trim($_POST['title'] ?? '');
$desc = trim($_POST['description'] ?? '');
$priority = (int)($_POST['priority'] ?? 2);
if ($title === '' || $desc === '') { $_SESSION['error'] = 'مقادیر نامعتبر'; header('Location: edit.php?id='.$id); exit; }
$stmt = $conn->prepare('UPDATE tasks SET title = ?, description = ?, priority = ? WHERE id = ?');
$stmt->execute([$title, $desc, $priority, $id]);
header('Location: index.php'); exit;
}
$id = (int)($_GET['id'] ?? 0);
$task = get_task($conn, $id);
include __DIR__.'/includes/header.php';
?>
<div class="card shadow-sm mx-auto" style="max-width:720px;">
<div class="card-body">
<h5 class="card-title">ویرایش کار</h5>
<form method="post" action="edit.php" class="needs-validation" novalidate>
<input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
<input type="hidden" name="id" value="<?= (int)$task['id'] ?>">
<div class="mb-3">
<label class="form-label">عنوان</label>
<input name="title" class="form-control" required value="<?= esc($task['title']) ?>">
</div>
<div class="mb-3">
<label class="form-label">توضیحات</label>
<textarea name="description" rows="4" class="form-control" required><?= esc($task['description']) ?></textarea>
</div>
<div class="mb-3">
<label class="form-label">اولویت</label>
<select name="priority" class="form-select">
<option value="1" <?php if($task['priority']==1) echo 'selected'; ?>>1</option>
<option value="2" <?php if($task['priority']==2) echo 'selected'; ?>>2</option>
<option value="3" <?php if($task['priority']==3) echo 'selected'; ?>>3</option>
</select>
</div>
<button class="btn btn-success">ذخیره</button>
<a href="index.php" class="btn btn-outline-secondary">انصراف</a>
</form>
</div>
</div>
<?php include __DIR__.'/includes/footer.php'; ?>