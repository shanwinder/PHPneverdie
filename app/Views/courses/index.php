<div class="d-flex justify-content-between align-items-center mb-4">
  <h1 class="h3 mb-0">Learning Path</h1>
</div>
<div class="row g-3">
  <?php foreach ($courses as $course): ?>
    <div class="col-md-6 col-xl-4">
      <div class="card lesson-card h-100">
        <div class="card-body">
          <h2 class="h5"><?= e($course['title']) ?></h2>
          <p class="text-secondary"><?= e($course['description']) ?></p>
          <span class="badge text-bg-light mb-3"><?= e($course['lessons_count']) ?> บทเรียน</span><br>
          <a class="btn btn-primary" href="<?= e(url('/courses/' . $course['slug'])) ?>">เปิดเส้นทางเรียน</a>
        </div>
      </div>
    </div>
  <?php endforeach; ?>
</div>
