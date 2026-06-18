<div class="d-flex flex-wrap justify-content-between gap-2 mb-4">
  <h1 class="h3 mb-0">Manage Challenges</h1>
  <a class="btn btn-primary" href="<?= e(url('/admin/challenges/create')) ?>">Create Challenge</a>
</div>

<div class="card lesson-card"><div class="card-body table-responsive">
  <table class="table align-middle">
    <thead><tr><th>Title</th><th>Lesson</th><th>Mode</th><th>XP</th><th>Published</th><th>Pass Rate</th><th></th></tr></thead>
    <tbody>
    <?php foreach ($challenges as $challenge): ?>
      <?php $rate = (int) $challenge['submissions'] > 0 ? round(((int) $challenge['passed_submissions'] / (int) $challenge['submissions']) * 100) : 0; ?>
      <tr>
        <td><?= e($challenge['title']) ?></td>
        <td><?= e($challenge['lesson_title']) ?></td>
        <td>
          <?= e($challenge['checking_mode']) ?>
          <?php if ($challenge['runtime_enabled'] ?? 0): ?><span class="badge text-bg-info ms-1"><?= e($challenge['runtime_mode']) ?></span><?php endif; ?>
        </td>
        <td><?= e($challenge['xp_reward']) ?></td>
        <td><span class="badge <?= $challenge['is_published'] ? 'text-bg-success' : 'text-bg-secondary' ?>"><?= $challenge['is_published'] ? 'Yes' : 'No' ?></span></td>
        <td><?= e($rate) ?>% (<?= e($challenge['submissions']) ?>)</td>
        <td class="text-end">
          <div class="btn-group btn-group-sm">
            <?php if ($challenge['is_published']): ?><a class="btn btn-outline-secondary" href="<?= e(url('/challenges/' . $challenge['slug'])) ?>">Preview</a><?php endif; ?>
            <a class="btn btn-outline-primary" href="<?= e(url('/admin/challenges/' . $challenge['id'] . '/submissions')) ?>">Submissions</a>
            <a class="btn btn-outline-success" href="<?= e(url('/admin/challenges/' . $challenge['id'] . '/test-cases')) ?>">Tests</a>
            <a class="btn btn-outline-dark" href="<?= e(url('/admin/challenges/' . $challenge['id'] . '/edit')) ?>">Edit</a>
          </div>
        </td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
</div></div>
