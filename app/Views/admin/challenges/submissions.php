<div class="d-flex flex-wrap justify-content-between gap-2 mb-4">
  <div>
    <h1 class="h3 mb-1">Submissions</h1>
    <p class="text-secondary mb-0"><?= e($challenge['title']) ?></p>
  </div>
  <a class="btn btn-outline-secondary" href="<?= e(url('/admin/challenges')) ?>">Back</a>
</div>

<div class="card lesson-card"><div class="card-body table-responsive">
  <table class="table align-middle">
    <thead><tr><th>User</th><th>Status</th><th>Score</th><th>Hints</th><th>Submitted</th><th>Feedback</th><th>Code</th></tr></thead>
    <tbody>
    <?php foreach ($submissions as $row): ?>
      <tr>
        <td><?= e($row['user_name']) ?></td>
        <td><span class="badge <?= $row['status'] === 'passed' ? 'text-bg-success' : ($row['status'] === 'needs_review' ? 'text-bg-info' : 'text-bg-warning') ?>"><?= e($row['status']) ?></span></td>
        <td><?= e($row['score']) ?>%</td>
        <td><?= e($row['hints_used']) ?></td>
        <td><?= e($row['submitted_at']) ?></td>
        <td><?= nl2br(e($row['feedback'])) ?></td>
        <td><pre class="code-block submission-code"><code><?= e($row['code']) ?></code></pre></td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
</div></div>
