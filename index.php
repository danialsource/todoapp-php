<?php
require_once __DIR__ . '/includes/csrf.php';
require_once __DIR__ . '/includes/functions.php';

$filter = $_GET['filter'] ?? null;
$tasks = fetch_tasks($conn, $filter);
$token = csrf_token();

include __DIR__ . '/includes/header.php';
?>

<div class="row g-4">

  <!-- فرم افزودن کار جدید -->
  <div class="col-md-6">
    <div class="card shadow-sm">
      <div class="card-body">
        <h5 class="card-title">افزودن یادداشت جدید</h5>
        <form method="post" action="add.php" class="needs-validation" novalidate>
          <input type="hidden" name="csrf_token" value="<?= $token ?>">
          
          <div class="mb-3">
            <label for="title" class="form-label">عنوان</label>
            <input id="title" name="title" class="form-control" required maxlength="255">
            <div class="invalid-feedback">عنوان را وارد کنید.</div>
          </div>
          
          <div class="mb-3">
            <label for="description" class="form-label">توضیحات</label>
            <textarea id="description" name="description" rows="4" class="form-control" required></textarea>
            <div class="invalid-feedback">توضیحات را وارد کنید.</div>
          </div>
          
          <div class="mb-3">
            <label for="priority" class="form-label">اولویت</label>
            <select id="priority" name="priority" class="form-select">
              <option value="1">1 (کم)</option>
              <option value="2" selected>2</option>
              <option value="3">3 (زیاد)</option>
            </select>
          </div>
          
          <button class="btn btn-primary btn-lg shadow-sm" type="submit">افزودن</button>
        </form>
      </div>
    </div>
  </div>

  <!-- لیست کارها -->
  <div class="col-md-6">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h5 class="m-0">لیست کارها</h5>
      <div>
        <a href="?filter=all" class="btn btn-sm btn-outline-secondary">همه</a>
        <a href="?filter=active" class="btn btn-sm btn-outline-secondary">فعال</a>
        <a href="?filter=completed" class="btn btn-sm btn-outline-secondary">انجام‌شده</a>
      </div>
    </div>

    <div class="list-group">
      <?php if (empty($tasks)): ?>
        <div class="text-center text-muted py-4">هیچ کاری وجود ندارد.</div>
      <?php endif; ?>

      <?php foreach ($tasks as $task): ?>
        <div class="list-group-item list-group-item-action d-flex align-items-center justify-content-between task-item <?php if ($task['completed']) echo 'completed'; ?>">
          <div class="d-flex align-items-center">
            
            <form method="post" action="toggle.php" class="me-3">
              <input type="hidden" name="csrf_token" value="<?= $token ?>">
              <input type="hidden" name="id" value="<?= (int)$task['id'] ?>">
              <button class="btn btn-sm btn-outline-0 toggle-btn" title="تکمیل / بازگردانی">
                <?php if ($task['completed']): ?>
                  ✔
                <?php else: ?>
                  ○
                <?php endif; ?>
              </button>
            </form>

            <div>
              <div class="fw-semibold"><?= esc($task['title']) ?></div>
              <small class="text-muted"><?= esc(mb_substr($task['description'],0,80)) ?><?php if (mb_strlen($task['description'])>80) echo '...'; ?></small>
            </div>
          </div>

          <div class="d-flex align-items-center gap-2">
            <span class="badge rounded-pill priority p-2">P<?= (int)$task['priority'] ?></span>
            <a href="edit.php?id=<?= (int)$task['id'] ?>" class="btn btn-sm btn-outline-primary">ویرایش</a>
            <form method="post" action="delete.php" onsubmit="return confirm('آیا از حذف مطمئن هستید؟');">
              <input type="hidden" name="csrf_token" value="<?= $token ?>">
              <input type="hidden" name="id" value="<?= (int)$task['id'] ?>">
              <button class="btn btn-sm btn-outline-danger">حذف</button>
            </form>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>

</div>

<?php include __DIR__.'/includes/footer.php'; ?>
