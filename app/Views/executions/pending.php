<div class="d-flex flex-wrap justify-content-between gap-2 mb-4">
  <div>
    <h1 class="h3 mb-1">กำลังรัน code ใน sandbox</h1>
    <p class="text-secondary mb-0"><?= e($job['challenge_title'] ?? 'Execution job') ?> · status: <span data-execution-status><?= e($job['status']) ?></span></p>
  </div>
  <?php if (!empty($job['challenge_slug'])): ?><a class="btn btn-outline-secondary" href="<?= e(url('/challenges/' . $job['challenge_slug'])) ?>">กลับไปโจทย์</a><?php endif; ?>
</div>

<div class="card lesson-card">
  <div class="card-body">
    <p class="mb-3">Worker จะประมวลผล job นี้นอก web request ถ้ายังไม่เปลี่ยนสถานะ ให้รันคำสั่ง <code>php bin/workers/execution_worker.php</code></p>
    <div class="progress" role="progressbar"><div class="progress-bar progress-bar-striped progress-bar-animated" style="width: 100%"></div></div>
  </div>
</div>

<script>
const statusUrl = '<?= e(url('/executions/' . $job['id'] . '/status')) ?>';
const poll = setInterval(async () => {
  const response = await fetch(statusUrl, {headers: {'Accept': 'application/json'}});
  const data = await response.json();
  document.querySelector('[data-execution-status]').textContent = data.status || 'unknown';
  if (data.finished) {
    clearInterval(poll);
    window.location.reload();
  }
}, 1800);
</script>

