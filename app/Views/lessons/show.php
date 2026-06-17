<nav class="small mb-3">
  <a href="<?= e(url('/courses/' . $lesson['course_slug'])) ?>"><?= e($lesson['course_title']) ?></a>
  <span class="text-secondary">/ <?= e($lesson['module_title']) ?></span>
</nav>
<article class="card lesson-card">
  <div class="card-body p-lg-5">
    <div class="d-flex flex-wrap justify-content-between gap-2 mb-3">
      <h1 class="h2 mb-0"><?= e($lesson['title']) ?></h1>
      <span class="badge text-bg-light align-self-start"><?= e($lesson['estimated_minutes']) ?> นาที · <?= e($lesson['xp_reward']) ?> XP</span>
    </div>
    <p class="lead text-secondary"><?= e($lesson['summary']) ?></p>
    <div class="lesson-content"><?= $lesson['content'] ?></div>
    <hr>
    <div class="d-flex flex-wrap justify-content-between gap-2">
      <div class="d-flex gap-2">
        <?php if ($prev): ?><a class="btn btn-outline-secondary" href="<?= e(url('/lessons/' . $prev['slug'])) ?>">บทก่อนหน้า</a><?php endif; ?>
        <?php if ($next): ?><a class="btn btn-outline-secondary" href="<?= e(url('/lessons/' . $next['slug'])) ?>">บทถัดไป</a><?php endif; ?>
      </div>
      <div class="d-flex gap-2">
        <a class="btn btn-outline-dark" href="<?= e(url('/courses/' . $lesson['course_slug'])) ?>">กลับ Learning Path</a>
        <?php if ($quiz): ?><a class="btn btn-primary" href="<?= e(url('/lessons/' . $lesson['id'] . '/quiz')) ?>">ทำ Quiz</a><?php endif; ?>
      </div>
    </div>
  </div>
</article>
