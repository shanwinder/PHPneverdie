<h1 class="h3 mb-4">Admin Dashboard</h1>
<div class="row g-3 mb-4">
  <?php foreach ([
    'Users' => $stats['users'],
    'Lessons' => $stats['lessons'],
    'Published' => $stats['published_lessons'],
    'Quizzes' => $stats['quizzes'],
    'Active 30d' => $stats['active_users'],
    'Avg Progress' => $stats['average_progress'] . '%',
  ] as $label => $value): ?>
    <div class="col-md-4 col-xl-2">
      <div class="card metric-card"><div class="card-body">
        <div class="text-secondary small"><?= e($label) ?></div>
        <div class="h3 mb-0"><?= e($value) ?></div>
      </div></div>
    </div>
  <?php endforeach; ?>
</div>

<div class="card lesson-card">
  <div class="card-body">
    <h2 class="h5">Recent Quiz Attempts</h2>
    <div class="table-responsive">
      <table class="table align-middle">
        <thead><tr><th>User</th><th>Quiz</th><th>Score</th><th>Status</th><th>Submitted</th></tr></thead>
        <tbody>
        <?php foreach ($recentAttempts as $attempt): ?>
          <tr>
            <td><?= e($attempt['user_name']) ?></td>
            <td><?= e($attempt['quiz_title']) ?></td>
            <td><?= e($attempt['score']) ?>%</td>
            <td><span class="badge <?= $attempt['passed'] ? 'text-bg-success' : 'text-bg-warning' ?>"><?= $attempt['passed'] ? 'Passed' : 'Retry' ?></span></td>
            <td><?= e($attempt['submitted_at']) ?></td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
