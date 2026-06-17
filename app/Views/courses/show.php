<div class="mb-4">
  <a class="text-decoration-none" href="<?= e(url('/courses')) ?>">Learning Path</a>
  <h1 class="h3 mt-2"><?= e($course['title']) ?></h1>
  <p class="text-secondary"><?= e($course['description']) ?></p>
</div>

<?php foreach ($modules as $module): ?>
  <section class="mb-4">
    <h2 class="h5"><?= e($module['title']) ?></h2>
    <div class="row g-3">
      <?php foreach ($module['lessons'] as $lesson): ?>
        <?php $status = $statuses[$lesson['id']] ?? 'not_started'; ?>
        <div class="col-md-6 col-xl-4">
          <a class="card lesson-card h-100 text-decoration-none text-dark" href="<?= e(url('/lessons/' . $lesson['slug'])) ?>">
            <div class="card-body">
              <div class="d-flex justify-content-between gap-2">
                <h3 class="h6"><?= e($lesson['title']) ?></h3>
                <span class="status-dot status-<?= e($status) ?>"></span>
              </div>
              <p class="text-secondary small"><?= e($lesson['summary']) ?></p>
              <span class="badge text-bg-light"><?= e($lesson['estimated_minutes']) ?> นาที</span>
              <span class="badge text-bg-light"><?= e($lesson['difficulty']) ?></span>
            </div>
          </a>
        </div>
      <?php endforeach; ?>
    </div>
  </section>
<?php endforeach; ?>
