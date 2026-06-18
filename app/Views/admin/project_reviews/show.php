<div class="d-flex flex-wrap justify-content-between gap-2 mb-4">
  <div><h1 class="h3 mb-1"><?= e($project['title']) ?></h1><p class="text-secondary mb-0"><?= e($project['user_name']) ?> · <?= e($project['status']) ?></p></div>
  <a class="btn btn-outline-secondary" href="<?= e(url('/admin/project-reviews')) ?>">Back</a>
</div>
<div class="row g-3">
  <div class="col-lg-5">
    <div class="card lesson-card"><div class="card-body">
      <?php if ($project['github_url']): ?><p><strong>GitHub:</strong> <a href="<?= e($project['github_url']) ?>" target="_blank" rel="noopener"><?= e($project['github_url']) ?></a></p><?php endif; ?>
      <p><?= nl2br(e($project['description'])) ?></p>
      <pre class="code-block"><code><?= e($project['note']) ?></code></pre>
    </div></div>
  </div>
  <div class="col-lg-7">
    <form class="card lesson-card" method="post" action="<?= e(url('/admin/project-reviews/' . $project['id'] . '/review')) ?>">
      <?= csrf_field() ?>
      <div class="card-body row g-3">
        <?php foreach (['database_score' => 'Database', 'security_score' => 'Security', 'ui_score' => 'UI/UX', 'code_structure_score' => 'Code Structure', 'feature_score' => 'Features'] as $name => $label): ?>
          <div class="col-md-4"><label class="form-label"><?= e($label) ?></label><input class="form-control" type="number" min="0" max="20" name="<?= e($name) ?>" value="0"></div>
        <?php endforeach; ?>
        <div class="col-md-4"><label class="form-label">Decision</label><select class="form-select" name="decision"><option value="approved">approved</option><option value="revision_requested">revision_requested</option><option value="rejected">rejected</option></select></div>
        <div class="col-12"><label class="form-label">Feedback</label><textarea class="form-control" name="feedback" rows="5"></textarea></div>
      </div>
      <div class="card-footer text-end"><button class="btn btn-primary" type="submit">Save Review</button></div>
    </form>
  </div>
</div>
<?php if ($reviews): ?>
  <div class="card lesson-card mt-3"><div class="card-body">
    <h2 class="h5">Previous Reviews</h2>
    <?php foreach ($reviews as $review): ?><div class="border-bottom py-2"><strong><?= e($review['decision']) ?> · <?= e($review['total_score']) ?>/100</strong><div><?= nl2br(e($review['feedback'])) ?></div></div><?php endforeach; ?>
  </div></div>
<?php endif; ?>

