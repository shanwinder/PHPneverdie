<div class="d-flex flex-wrap justify-content-between gap-2 mb-4">
  <div><h1 class="h3 mb-1"><?= e($project['title']) ?></h1><p class="text-secondary mb-0">Status: <?= e($project['status']) ?></p></div>
  <a class="btn btn-outline-secondary" href="<?= e(url('/projects')) ?>">Back</a>
</div>
<div class="card lesson-card"><div class="card-body">
  <?php if ($project['github_url']): ?><p><strong>GitHub:</strong> <a href="<?= e($project['github_url']) ?>" target="_blank" rel="noopener"><?= e($project['github_url']) ?></a></p><?php endif; ?>
  <p><?= nl2br(e($project['description'])) ?></p>
  <pre class="code-block"><code><?= e($project['note']) ?></code></pre>
</div></div>
<?php if ($project['status'] !== 'approved'): ?>
  <form class="card lesson-card mt-3" method="post" action="<?= e(url('/projects/' . $project['id'] . '/update')) ?>">
    <?= csrf_field() ?>
    <div class="card-body row g-3">
      <div class="col-md-8"><label class="form-label">Title</label><input class="form-control" name="title" value="<?= e($project['title']) ?>" required></div>
      <div class="col-md-4"><label class="form-label">Type</label><select class="form-select" name="submission_type"><option value="github_url" <?= $project['submission_type'] === 'github_url' ? 'selected' : '' ?>>GitHub URL</option><option value="text_note" <?= $project['submission_type'] === 'text_note' ? 'selected' : '' ?>>Text Note</option></select></div>
      <div class="col-12"><label class="form-label">GitHub URL</label><input class="form-control" name="github_url" value="<?= e($project['github_url']) ?>"></div>
      <div class="col-12"><label class="form-label">Description</label><textarea class="form-control" name="description" rows="3"><?= e($project['description']) ?></textarea></div>
      <div class="col-12"><label class="form-label">Note</label><textarea class="form-control" name="note" rows="4"><?= e($project['note']) ?></textarea></div>
    </div>
    <div class="card-footer d-flex justify-content-end gap-2">
      <button class="btn btn-outline-primary" type="submit">Update</button>
      <button class="btn btn-primary" type="submit" formaction="<?= e(url('/projects/' . $project['id'] . '/submit')) ?>">Submit for Review</button>
    </div>
  </form>
<?php endif; ?>
<?php if ($reviews): ?>
  <div class="card lesson-card mt-3"><div class="card-body">
    <h2 class="h5">Review Feedback</h2>
    <?php foreach ($reviews as $review): ?>
      <div class="border-bottom py-2">
        <strong><?= e($review['decision']) ?> · <?= e($review['total_score']) ?>/100</strong>
        <div><?= nl2br(e($review['feedback'])) ?></div>
      </div>
    <?php endforeach; ?>
  </div></div>
<?php endif; ?>
