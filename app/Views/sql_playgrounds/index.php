<div class="d-flex flex-wrap justify-content-between gap-2 mb-4">
  <h1 class="h3 mb-0">SQL Playgrounds</h1>
</div>

<div class="row g-3">
  <?php foreach ($playgrounds as $playground): ?>
    <div class="col-md-6 col-xl-4">
      <div class="card lesson-card h-100"><div class="card-body">
        <h2 class="h5"><?= e($playground['title']) ?></h2>
        <p class="text-secondary"><?= e($playground['description']) ?></p>
        <a class="btn btn-primary" href="<?= e(url('/sql-playgrounds/' . $playground['slug'])) ?>">Open</a>
      </div></div>
    </div>
  <?php endforeach; ?>
  <?php if (!$playgrounds): ?><p class="text-secondary">ยังไม่มี SQL playground ที่เผยแพร่</p><?php endif; ?>
</div>

