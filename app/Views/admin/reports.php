<h1 class="h3 mb-4">Reports</h1>
<div class="card lesson-card"><div class="card-body table-responsive">
  <table class="table align-middle">
    <thead><tr><th>Learner</th><th>Email</th><th>XP</th><th>Level</th><th>Completed Lessons</th><th>Passed Quizzes</th><th>Passed Challenges</th><th>Badges</th></tr></thead>
    <tbody>
    <?php foreach ($rows as $row): ?>
      <tr>
        <td><?= e($row['name']) ?></td>
        <td><?= e($row['email']) ?></td>
        <td><?= e($row['xp']) ?></td>
        <td><?= e($row['level']) ?></td>
        <td><?= e($row['completed_lessons']) ?></td>
        <td><?= e($row['passed_quizzes']) ?></td>
        <td><?= e($row['passed_challenges']) ?></td>
        <td><?= e($row['badge_count']) ?></td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
</div></div>

<div class="row g-4 mt-1">
  <div class="col-lg-8">
    <div class="card lesson-card"><div class="card-body table-responsive">
      <h2 class="h5">Challenge Pass Rate</h2>
      <table class="table align-middle">
        <thead><tr><th>Challenge</th><th>Submissions</th><th>Passed</th><th>Pass Rate</th><th>Avg Hints</th></tr></thead>
        <tbody>
        <?php foreach ($challengeReports as $row): ?>
          <?php $rate = (int) $row['submissions'] > 0 ? round(((int) $row['passed'] / (int) $row['submissions']) * 100) : 0; ?>
          <tr>
            <td><?= e($row['title']) ?></td>
            <td><?= e($row['submissions']) ?></td>
            <td><?= e($row['passed']) ?></td>
            <td><?= e($rate) ?>%</td>
            <td><?= e($row['avg_hints'] ?? 0) ?></td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    </div></div>
  </div>
  <div class="col-lg-4">
    <div class="card lesson-card"><div class="card-body table-responsive">
      <h2 class="h5">Badge Distribution</h2>
      <table class="table align-middle">
        <thead><tr><th>Badge</th><th>Awarded</th></tr></thead>
        <tbody>
        <?php foreach ($badgeReports as $row): ?>
          <tr><td><?= e($row['name']) ?></td><td><?= e($row['awarded']) ?></td></tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    </div></div>
  </div>
</div>
