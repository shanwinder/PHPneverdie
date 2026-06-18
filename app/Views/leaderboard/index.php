<h1 class="h3 mb-4">Leaderboard</h1>
<div class="card lesson-card"><div class="card-body table-responsive">
  <table class="table align-middle">
    <thead><tr><th>#</th><th>Learner</th><th>Level</th><th>XP</th><th>Challenges</th><th>Quizzes</th><th>Badges</th></tr></thead>
    <tbody>
    <?php foreach ($rows as $index => $row): ?>
      <tr class="<?= (int) $row['id'] === $currentUserId ? 'table-primary' : '' ?>">
        <td><?= e($index + 1) ?></td>
        <td><?= e($row['name']) ?><?= (int) $row['id'] === $currentUserId ? ' (คุณ)' : '' ?></td>
        <td><?= e($row['level']) ?></td>
        <td><?= e($row['xp']) ?></td>
        <td><?= e($row['passed_challenges']) ?></td>
        <td><?= e($row['passed_quizzes']) ?></td>
        <td><?= e($row['badge_count']) ?></td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
</div></div>
