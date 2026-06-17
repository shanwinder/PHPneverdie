<h1 class="h3 mb-4">Basic Reports</h1>
<div class="card lesson-card"><div class="card-body table-responsive">
  <table class="table align-middle">
    <thead><tr><th>Learner</th><th>Email</th><th>XP</th><th>Level</th><th>Completed Lessons</th><th>Passed Quizzes</th></tr></thead>
    <tbody>
    <?php foreach ($rows as $row): ?>
      <tr>
        <td><?= e($row['name']) ?></td>
        <td><?= e($row['email']) ?></td>
        <td><?= e($row['xp']) ?></td>
        <td><?= e($row['level']) ?></td>
        <td><?= e($row['completed_lessons']) ?></td>
        <td><?= e($row['passed_quizzes']) ?></td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
</div></div>
