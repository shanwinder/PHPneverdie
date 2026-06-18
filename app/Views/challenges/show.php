<nav class="small mb-3">
  <a href="<?= e(url('/challenges')) ?>">Challenges</a>
  <span class="text-secondary">/ <?= e($challenge['lesson_title']) ?></span>
</nav>

<div class="challenge-layout">
  <section class="card lesson-card">
    <div class="card-body">
      <div class="d-flex flex-wrap justify-content-between gap-2 mb-3">
        <h1 class="h3 mb-0"><?= e($challenge['title']) ?></h1>
        <span class="badge text-bg-primary align-self-start"><?= e($challenge['difficulty']) ?> · <?= e($challenge['xp_reward']) ?> XP</span>
      </div>
      <p class="text-secondary"><?= e($challenge['description']) ?></p>
      <?php if ($challenge['instructions']): ?>
        <div class="challenge-instructions"><?= nl2br(e($challenge['instructions'])) ?></div>
      <?php endif; ?>

      <div class="rule-list my-3">
        <?php if ($challenge['expected_text']): ?><span class="badge text-bg-light">ต้องมีข้อความ: <?= e($challenge['expected_text']) ?></span><?php endif; ?>
        <?php foreach ($challenge['required_keywords'] as $keyword): ?><span class="badge text-bg-light">ต้องมี <?= e($keyword['keyword']) ?></span><?php endforeach; ?>
        <?php foreach ($challenge['forbidden_keywords'] as $keyword): ?><span class="badge text-bg-warning">ห้ามใช้ <?= e($keyword['keyword']) ?></span><?php endforeach; ?>
      </div>

      <form method="post" action="<?= e(url('/challenges/' . $challenge['id'] . '/submit')) ?>">
        <?= csrf_field() ?>
        <textarea class="form-control code-editor" name="code" rows="18" maxlength="20000" data-starter="<?= e($challenge['starter_code']) ?>" required><?= e(old('code', $challenge['starter_code'])) ?></textarea>
        <?php if (($challenge['runtime_enabled'] ?? 0) && ($challenge['run_button_enabled'] ?? 0)): ?>
          <label class="form-label mt-3">Custom input สำหรับ Run Code</label>
          <textarea class="form-control" name="input_data" rows="3" placeholder="ใส่ input ที่ต้องการส่งเข้า STDIN"><?= e(old('input_data', '')) ?></textarea>
        <?php endif; ?>
        <div class="d-flex flex-wrap justify-content-between gap-2 mt-3">
          <button class="btn btn-outline-secondary" type="button" data-reset-editor>Reset</button>
          <div class="d-flex gap-2">
            <a class="btn btn-outline-dark" href="<?= e(url('/challenges/' . $challenge['id'] . '/history')) ?>">History</a>
            <?php if (($challenge['runtime_enabled'] ?? 0) && ($challenge['run_button_enabled'] ?? 0)): ?>
              <button class="btn btn-outline-success" type="submit" formaction="<?= e(url('/challenges/' . $challenge['id'] . '/run')) ?>">Run Code</button>
            <?php endif; ?>
            <?php if (($challenge['runtime_enabled'] ?? 0) && ($challenge['submit_runtime_enabled'] ?? 0)): ?>
              <button class="btn btn-success" type="submit" formaction="<?= e(url('/challenges/' . $challenge['id'] . '/submit-runtime')) ?>">Submit for Test</button>
            <?php else: ?>
              <button class="btn btn-success" type="submit">Submit</button>
            <?php endif; ?>
          </div>
        </div>
      </form>
    </div>
  </section>

  <aside class="challenge-side">
    <div class="card lesson-card mb-3">
      <div class="card-body">
        <h2 class="h5">Hints</h2>
        <?php if ($hints): ?>
          <ol class="mb-3">
            <?php foreach ($hints as $hint): ?><li><?= e($hint['hint_text']) ?></li><?php endforeach; ?>
          </ol>
        <?php else: ?>
          <p class="text-secondary">ยังไม่ได้เปิดคำใบ้</p>
        <?php endif; ?>
        <form method="post" action="<?= e(url('/challenges/' . $challenge['id'] . '/hint')) ?>">
          <?= csrf_field() ?>
          <button class="btn btn-outline-primary btn-sm" type="submit">ขอคำใบ้</button>
        </form>
      </div>
    </div>

    <div class="card lesson-card">
      <div class="card-body">
        <h2 class="h5">Recent Submissions</h2>
        <?php foreach ($history as $row): ?>
          <div class="submission-row">
            <span class="badge <?= $row['status'] === 'passed' ? 'text-bg-success' : ($row['status'] === 'needs_review' ? 'text-bg-info' : 'text-bg-warning') ?>"><?= e($row['status']) ?></span>
            <span><?= e($row['score']) ?>%</span>
            <span class="text-secondary small"><?= e($row['submitted_at']) ?></span>
          </div>
        <?php endforeach; ?>
        <?php if (!$history): ?><p class="text-secondary mb-0">ยังไม่มี submission</p><?php endif; ?>
      </div>
    </div>
  </aside>
</div>
