<div class="d-flex flex-wrap justify-content-between gap-2 mb-4">
  <div><h1 class="h3 mb-1">Execution #<?= e($job['id']) ?></h1><p class="text-secondary mb-0"><?= e($job['user_name']) ?> · <?= e($job['challenge_title'] ?? '-') ?></p></div>
  <a class="btn btn-outline-secondary" href="<?= e(url('/admin/executions')) ?>">Back</a>
</div>
<div class="row g-3">
  <div class="col-lg-4">
    <div class="card lesson-card"><div class="card-body">
      <p><span class="badge text-bg-secondary"><?= e($job['status']) ?></span></p>
      <p class="mb-1"><strong>Type:</strong> <?= e($job['job_type']) ?></p>
      <p class="mb-1"><strong>Worker:</strong> <?= e($job['worker_id'] ?? '-') ?></p>
      <p class="mb-3"><strong>Error:</strong> <?= e($job['error_message'] ?? '-') ?></p>
      <?php if (in_array($job['status'], ['queued', 'running'], true)): ?>
        <form method="post" action="<?= e(url('/admin/executions/' . $job['id'] . '/cancel')) ?>"><?= csrf_field() ?><button class="btn btn-outline-danger" type="submit">Cancel</button></form>
      <?php endif; ?>
    </div></div>
  </div>
  <div class="col-lg-8">
    <div class="card lesson-card"><div class="card-body">
      <h2 class="h5">Output</h2>
      <pre class="code-block"><code><?= e($result['stdout'] ?? '') ?></code></pre>
      <h2 class="h5">Error</h2>
      <pre class="code-block"><code><?= e($result['stderr'] ?? '') ?></code></pre>
    </div></div>
  </div>
</div>
<?php if ($cases): ?>
  <div class="card lesson-card mt-3"><div class="card-body table-responsive">
    <table class="table"><thead><tr><th>Case</th><th>Status</th><th>Message</th></tr></thead><tbody>
    <?php foreach ($cases as $case): ?><tr><td><?= e($case['test_name']) ?></td><td><?= $case['passed'] ? 'passed' : 'failed' ?></td><td><?= e($case['message']) ?></td></tr><?php endforeach; ?>
    </tbody></table>
  </div></div>
<?php endif; ?>

