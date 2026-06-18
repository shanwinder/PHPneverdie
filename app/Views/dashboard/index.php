<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4" id="profile">
  <div>
    <h1 class="h3 mb-1">สวัสดี <?= e(current_user()['name']) ?></h1>
    <p class="text-secondary mb-0"><?= e($levelName) ?> · Level <?= e(current_user()['level']) ?></p>
  </div>
  <span class="badge text-bg-primary fs-6">XP <?= e(current_user()['xp']) ?><?= $xpToNext ? ' · อีก ' . e($xpToNext) . ' XP ถึงเลเวลถัดไป' : '' ?></span>
</div>

<div class="row g-3 mb-4" id="progress">
  <div class="col-md-3">
    <div class="card metric-card h-100"><div class="card-body">
      <div class="text-secondary small">Progress</div>
      <div class="display-6 fw-bold"><?= e($summary['percent']) ?>%</div>
      <div class="progress mt-2"><div class="progress-bar" style="width: <?= e($summary['percent']) ?>%"></div></div>
    </div></div>
  </div>
  <div class="col-md-3">
    <div class="card metric-card h-100"><div class="card-body">
      <div class="text-secondary small">Completed Lessons</div>
      <div class="display-6 fw-bold"><?= e($summary['completed_lessons']) ?>/<?= e($summary['total_lessons']) ?></div>
    </div></div>
  </div>
  <div class="col-md-3">
    <div class="card metric-card h-100"><div class="card-body">
      <div class="text-secondary small">Passed Quizzes</div>
      <div class="display-6 fw-bold"><?= e($summary['passed_quizzes']) ?></div>
    </div></div>
  </div>
  <div class="col-md-3">
    <div class="card metric-card h-100"><div class="card-body">
      <div class="text-secondary small">Passed Challenges</div>
      <div class="display-6 fw-bold"><?= e($challengeSummary['passed_challenges'] ?? 0) ?>/<?= e($challengeSummary['total_challenges'] ?? 0) ?></div>
    </div></div>
  </div>
</div>

<div class="row g-4">
  <div class="col-lg-5">
    <div class="card lesson-card h-100">
      <div class="card-body">
        <h2 class="h5">Continue Learning</h2>
        <?php $continue = $nextLesson ?: $latestLesson; ?>
        <?php if ($continue): ?>
          <p class="mb-1 fw-semibold"><?= e($continue['title']) ?></p>
          <p class="text-secondary small"><?= e($continue['summary'] ?? '') ?></p>
          <a class="btn btn-primary" href="<?= e(url('/lessons/' . $continue['slug'])) ?>">เรียนต่อ</a>
        <?php else: ?>
          <p class="text-secondary mb-0">ยังไม่มีบทเรียนที่เผยแพร่</p>
        <?php endif; ?>
      </div>
    </div>
  </div>
  <div class="col-lg-3">
    <div class="card lesson-card h-100">
      <div class="card-body">
        <h2 class="h5">Recommended Challenge</h2>
        <?php if ($recommendedChallenge): ?>
          <p class="mb-1 fw-semibold"><?= e($recommendedChallenge['title']) ?></p>
          <p class="text-secondary small"><?= e($recommendedChallenge['lesson_title']) ?> · <?= e($recommendedChallenge['xp_reward']) ?> XP</p>
          <a class="btn btn-success" href="<?= e(url('/challenges/' . $recommendedChallenge['slug'])) ?>">ฝึกเขียน code</a>
        <?php else: ?>
          <p class="text-secondary mb-0">ผ่าน challenge ที่เผยแพร่ครบแล้ว</p>
        <?php endif; ?>
      </div>
    </div>
  </div>
  <div class="col-lg-4">
    <div class="card lesson-card">
      <div class="card-body">
        <h2 class="h5">Learning Path</h2>
        <div class="list-group list-group-flush">
          <?php foreach ($lessons as $lesson): ?>
            <a class="list-group-item list-group-item-action d-flex justify-content-between align-items-center" href="<?= e(url('/lessons/' . $lesson['slug'])) ?>">
              <span><span class="status-dot status-<?= e($lesson['status']) ?> me-2"></span><?= e($lesson['title']) ?></span>
              <span class="badge text-bg-light"><?= e($lesson['module_title']) ?></span>
            </a>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </div>
</div>

<?php if ($latestBadges): ?>
  <div class="card lesson-card mt-4">
    <div class="card-body">
      <h2 class="h5">Latest Badges</h2>
      <div class="d-flex flex-wrap gap-2">
        <?php foreach ($latestBadges as $badge): ?>
          <span class="badge text-bg-light"><?= e($badge['icon'] ?: '*') ?> <?= e($badge['name']) ?></span>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
<?php endif; ?>
