<div class="d-flex flex-wrap justify-content-between gap-2 mb-4">
  <div>
    <h1 class="h3 mb-1">Execution Result</h1>
    <p class="text-secondary mb-0"><?= e($job['challenge_title'] ?? 'Execution job') ?></p>
  </div>
  <?php if (!empty($job['challenge_slug'])): ?><a class="btn btn-primary" href="<?= e(url('/challenges/' . $job['challenge_slug'])) ?>">กลับไปโจทย์</a><?php endif; ?>
</div>

<div class="row g-3">
  <div class="col-lg-4">
    <div class="card lesson-card"><div class="card-body">
      <h2 class="h5">Status</h2>
      <p><span class="badge <?= $job['status'] === 'passed' ? 'text-bg-success' : ($job['status'] === 'failed' ? 'text-bg-warning' : 'text-bg-danger') ?>"><?= e($job['status']) ?></span></p>
      <dl class="row mb-0">
        <dt class="col-5">Job</dt><dd class="col-7">#<?= e($job['id']) ?></dd>
        <dt class="col-5">Type</dt><dd class="col-7"><?= e($job['job_type']) ?></dd>
        <dt class="col-5">Duration</dt><dd class="col-7"><?= e($result['duration_ms'] ?? '-') ?> ms</dd>
        <dt class="col-5">Exit</dt><dd class="col-7"><?= e($result['exit_code'] ?? '-') ?></dd>
      </dl>
    </div></div>
  </div>
  <div class="col-lg-8">
    <div class="card lesson-card"><div class="card-body">
      <h2 class="h5">Summary</h2>
      <p><?= e($result['result_summary'] ?? $job['error_message'] ?? 'ไม่มี result') ?></p>
      <?php if (!empty($result['output_truncated'])): ?><div class="alert alert-warning">Output ถูกตัดตาม limit ของ runtime profile</div><?php endif; ?>
      <h3 class="h6">STDOUT</h3>
      <pre class="code-block"><code><?= e($result['stdout'] ?? '') ?></code></pre>
      <h3 class="h6">STDERR</h3>
      <pre class="code-block"><code><?= e($result['stderr'] ?? '') ?></code></pre>
    </div></div>
  </div>
</div>

<?php if ($cases): ?>
  <div class="card lesson-card mt-3"><div class="card-body table-responsive">
    <h2 class="h5">Test Cases</h2>
    <table class="table align-middle">
      <thead><tr><th>Case</th><th>Status</th><th>Expected</th><th>Actual</th><th>Message</th></tr></thead>
      <tbody>
      <?php foreach ($cases as $case): ?>
        <tr>
          <td><?= e($case['test_name']) ?></td>
          <td><span class="badge <?= $case['passed'] ? 'text-bg-success' : 'text-bg-warning' ?>"><?= $case['passed'] ? 'ผ่าน' : 'ยังไม่ผ่าน' ?></span></td>
          <td><code><?= e($case['expected_output'] ?? 'hidden') ?></code></td>
          <td><code><?= e($case['actual_output'] ?? 'hidden') ?></code></td>
          <td><?= e($case['message']) ?></td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div></div>
<?php endif; ?>

