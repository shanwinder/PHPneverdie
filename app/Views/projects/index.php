<div class="d-flex flex-wrap justify-content-between gap-2 mb-4">
  <h1 class="h3 mb-0">My Projects</h1>
  <a class="btn btn-primary" href="<?= e(url('/projects/create')) ?>">Submit Project</a>
</div>
<div class="card lesson-card"><div class="card-body table-responsive">
  <table class="table align-middle">
    <thead><tr><th>Title</th><th>Status</th><th>Submitted</th><th></th></tr></thead>
    <tbody>
    <?php foreach ($projects as $project): ?>
      <tr>
        <td><?= e($project['title']) ?></td>
        <td><span class="badge text-bg-secondary"><?= e($project['status']) ?></span></td>
        <td><?= e($project['submitted_at']) ?></td>
        <td class="text-end"><a class="btn btn-sm btn-outline-primary" href="<?= e(url('/projects/' . $project['id'])) ?>">View</a></td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
  <?php if (!$projects): ?><p class="text-secondary mb-0">ยังไม่มี project submission</p><?php endif; ?>
</div></div>

