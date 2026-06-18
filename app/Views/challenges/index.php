<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
  <div>
    <h1 class="h3 mb-1">Challenges</h1>
    <p class="text-secondary mb-0">ฝึกเขียน PHP แบบปลอดภัยด้วย rule-based checker</p>
  </div>
  <a class="btn btn-outline-primary" href="<?= e(url('/badges')) ?>">ดู Badge ของฉัน</a>
</div>

<div class="row g-3">
  <?php foreach ($challenges as $challenge): ?>
    <div class="col-md-6 col-xl-4">
      <div class="card lesson-card h-100">
        <div class="card-body d-flex flex-column">
          <div class="d-flex justify-content-between gap-2 mb-2">
            <span class="badge text-bg-light"><?= e($challenge['lesson_title']) ?></span>
            <span class="badge <?= $challenge['passed'] ? 'text-bg-success' : 'text-bg-secondary' ?>"><?= $challenge['passed'] ? 'Completed' : e($challenge['difficulty']) ?></span>
          </div>
          <h2 class="h5"><?= e($challenge['title']) ?></h2>
          <p class="text-secondary flex-grow-1"><?= e($challenge['description']) ?></p>
          <div class="d-flex justify-content-between align-items-center">
            <span class="small text-secondary"><?= e($challenge['xp_reward']) ?> XP · <?= e($challenge['attempts']) ?> attempts</span>
            <a class="btn btn-primary btn-sm" href="<?= e(url('/challenges/' . $challenge['slug'])) ?>"><?= $challenge['passed'] ? 'ฝึกซ้ำ' : 'Start' ?></a>
          </div>
        </div>
      </div>
    </div>
  <?php endforeach; ?>
  <?php if (!$challenges): ?>
    <div class="col-12"><div class="alert alert-info">ยังไม่มี challenge ที่เผยแพร่</div></div>
  <?php endif; ?>
</div>
