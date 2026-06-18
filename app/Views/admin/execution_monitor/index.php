<div class="d-flex flex-wrap justify-content-between gap-2 mb-4"><h1 class="h3 mb-0">Execution Monitor</h1></div>
<div class="d-flex flex-wrap gap-2 mb-3">
  <?php foreach ($stats as $stat): ?><span class="badge text-bg-secondary"><?= e($stat['status']) ?>: <?= e($stat['total']) ?></span><?php endforeach; ?>
</div>
<div class="card lesson-card"><div class="card-body table-responsive">
  <table class="table align-middle">
    <thead><tr><th>ID</th><th>User</th><th>Challenge</th><th>Type</th><th>Status</th><th>Queued</th><th></th></tr></thead>
    <tbody>
    <?php foreach ($jobs as $job): ?>
      <tr>
        <td>#<?= e($job['id']) ?></td><td><?= e($job['user_name']) ?></td><td><?= e($job['challenge_title'] ?? '-') ?></td><td><?= e($job['job_type']) ?></td>
        <td><span class="badge text-bg-secondary"><?= e($job['status']) ?></span></td><td><?= e($job['queued_at']) ?></td>
        <td class="text-end"><a class="btn btn-sm btn-outline-primary" href="<?= e(url('/admin/executions/' . $job['id'])) ?>">View</a></td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
</div></div>

