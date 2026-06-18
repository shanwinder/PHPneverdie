<div class="d-flex flex-wrap justify-content-between gap-2 mb-4"><h1 class="h3 mb-0">Project Reviews</h1></div>
<div class="card lesson-card"><div class="card-body table-responsive">
  <table class="table align-middle">
    <thead><tr><th>Project</th><th>User</th><th>Status</th><th>Submitted</th><th></th></tr></thead>
    <tbody>
    <?php foreach ($projects as $project): ?>
      <tr>
        <td><?= e($project['title']) ?></td><td><?= e($project['user_name']) ?></td><td><span class="badge text-bg-secondary"><?= e($project['status']) ?></span></td><td><?= e($project['submitted_at']) ?></td>
        <td class="text-end"><a class="btn btn-sm btn-outline-primary" href="<?= e(url('/admin/project-reviews/' . $project['id'])) ?>">Review</a></td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
</div></div>

