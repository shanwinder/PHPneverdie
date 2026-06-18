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
    <?php if (!empty($animationBlocks)): ?>
      <section class="animation-stack mb-4">
        <?php foreach ($animationBlocks as $block): ?>
          <div class="animation-block" data-animation-block data-type="<?= e($block['block_type']) ?>">
            <h2 class="h5"><?= e($block['title']) ?></h2>
            <?php if ($block['block_type'] === 'steps'): ?>
              <ol class="animation-steps" data-steps='<?= e($block['content']) ?>'></ol>
            <?php elseif ($block['block_type'] === 'html' || $block['block_type'] === 'diagram'): ?>
              <div class="animation-content"><?= $block['content'] ?></div>
            <?php else: ?>
              <pre class="code-block mb-0"><code><?= e($block['content']) ?></code></pre>
            <?php endif; ?>
          </div>
        <?php endforeach; ?>
      </section>
    <?php endif; ?>
    <div class="lesson-content"><?= $lesson['content'] ?></div>
    <hr>
    <?php if (!empty($challenges)): ?>
      <section class="practice-panel mb-4">
        <div>
          <h2 class="h5">Practice Challenge</h2>
          <p class="text-secondary mb-0">ฝึกเขียน code จากบทนี้ด้วยโจทย์แบบ rule-based ที่ไม่รัน code บน server</p>
        </div>
        <div class="d-flex flex-wrap gap-2">
          <?php foreach ($challenges as $challenge): ?>
            <a class="btn btn-success" href="<?= e(url('/challenges/' . $challenge['slug'])) ?>"><?= e($challenge['title']) ?></a>
          <?php endforeach; ?>
        </div>
      </section>
    <?php endif; ?>
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
