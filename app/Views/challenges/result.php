<div class="d-flex flex-wrap justify-content-between gap-2 mb-4">
  <div>
    <h1 class="h3 mb-1">Submission Result</h1>
    <p class="text-secondary mb-0"><?= e($submission['challenge_title']) ?></p>
  </div>
  <a class="btn btn-primary" href="<?= e(url('/challenges/' . $submission['challenge_slug'])) ?>">กลับไปแก้โจทย์</a>
</div>

<?php if (!empty($newBadges)): ?>
  <div class="alert alert-success">
    <?php foreach ($newBadges as $badge): ?>
      <div>ยินดีด้วย! คุณได้รับ Badge: <strong><?= e($badge['name']) ?></strong></div>
    <?php endforeach; ?>
  </div>
<?php endif; ?>

<div class="row g-3">
  <div class="col-lg-5">
    <div class="card lesson-card"><div class="card-body">
      <h2 class="h5">Feedback</h2>
      <p class="mb-2"><span class="badge <?= $submission['status'] === 'passed' ? 'text-bg-success' : ($submission['status'] === 'needs_review' ? 'text-bg-info' : 'text-bg-warning') ?>"><?= e($submission['status']) ?></span> <?= e($submission['score']) ?>%</p>
      <p class="mb-0"><?= nl2br(e($submission['feedback'])) ?></p>
    </div></div>
  </div>
  <div class="col-lg-7">
    <div class="card lesson-card"><div class="card-body">
      <h2 class="h5">Checks</h2>
      <div class="table-responsive">
        <table class="table align-middle">
          <thead><tr><th>Type</th><th>Value</th><th>Status</th><th>Message</th></tr></thead>
          <tbody>
          <?php foreach ($checks as $check): ?>
            <tr>
              <td><?= e($check['check_type']) ?></td>
              <td><code><?= e($check['check_value']) ?></code></td>
              <td><span class="badge <?= $check['passed'] ? 'text-bg-success' : 'text-bg-warning' ?>"><?= $check['passed'] ? 'ผ่าน' : 'ยังไม่ผ่าน' ?></span></td>
              <td><?= e($check['message']) ?></td>
            </tr>
          <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div></div>
  </div>
</div>

<div class="card lesson-card mt-3"><div class="card-body">
  <h2 class="h5">Submitted Code</h2>
  <pre class="code-block"><code><?= e($submission['code']) ?></code></pre>
</div></div>
