<div class="d-flex flex-wrap justify-content-between gap-2 mb-4">
  <div>
    <h1 class="h3 mb-1">Submission History</h1>
    <p class="text-secondary mb-0"><?= e($challenge['title']) ?></p>
  </div>
  <a class="btn btn-primary" href="<?= e(url('/challenges/' . $challenge['slug'])) ?>">กลับไปทำโจทย์</a>
</div>

<div class="card lesson-card"><div class="card-body table-responsive">
  <table class="table align-middle">
    <thead><tr><th>Submitted</th><th>Status</th><th>Score</th><th>Hints</th><th>Feedback</th><th>Code</th></tr></thead>
    <tbody>
    <?php foreach ($history as $row): ?>
      <tr>
        <td><?= e($row['submitted_at']) ?></td>
        <td><span class="badge <?= $row['status'] === 'passed' ? 'text-bg-success' : ($row['status'] === 'needs_review' ? 'text-bg-info' : 'text-bg-warning') ?>"><?= e($row['status']) ?></span></td>
        <td><?= e($row['score']) ?>%</td>
        <td><?= e($row['hints_used']) ?></td>
        <td><?= nl2br(e($row['feedback'])) ?></td>
        <td><pre class="code-block submission-code"><code><?= e($row['code']) ?></code></pre></td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
</div></div>
