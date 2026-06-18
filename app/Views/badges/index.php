<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
  <div>
    <h1 class="h3 mb-1">My Badges</h1>
    <p class="text-secondary mb-0">รางวัลจากการเรียนและฝึกเขียน code</p>
  </div>
  <a class="btn btn-outline-primary" href="<?= e(url('/challenges')) ?>">กลับไปฝึก Challenge</a>
</div>

<div class="row g-3">
  <?php foreach ($badges as $badge): ?>
    <div class="col-md-6 col-xl-4">
      <div class="card lesson-card h-100 <?= $badge['awarded_at'] ? '' : 'badge-locked' ?>">
        <div class="card-body">
          <div class="badge-icon mb-2"><?= e($badge['icon'] ?: '*') ?></div>
          <h2 class="h5"><?= e($badge['name']) ?></h2>
          <p class="text-secondary"><?= e($badge['description']) ?></p>
          <?php if ($badge['awarded_at']): ?>
            <span class="badge text-bg-success">ได้รับแล้ว <?= e($badge['awarded_at']) ?></span>
          <?php else: ?>
            <span class="badge text-bg-light">ยังล็อกอยู่</span>
          <?php endif; ?>
        </div>
      </div>
    </div>
  <?php endforeach; ?>
</div>
