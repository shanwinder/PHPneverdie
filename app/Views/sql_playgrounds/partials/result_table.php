<div class="card lesson-card mt-3"><div class="card-body">
  <h2 class="h5">Result</h2>
  <p><span class="badge <?= ($result['passed'] ?? $result['ok'] ?? false) ? 'text-bg-success' : 'text-bg-warning' ?>"><?= ($result['passed'] ?? false) ? 'passed' : (($result['ok'] ?? false) ? 'ok' : 'error') ?></span> <?= e($result['feedback'] ?? '') ?></p>
  <?php if (!empty($result['rows'])): ?>
    <div class="table-responsive">
      <table class="table table-sm">
        <thead><tr><?php foreach (array_keys($result['rows'][0]) as $column): ?><th><?= e($column) ?></th><?php endforeach; ?></tr></thead>
        <tbody>
        <?php foreach ($result['rows'] as $row): ?>
          <tr><?php foreach ($row as $value): ?><td><?= e($value) ?></td><?php endforeach; ?></tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  <?php else: ?>
    <p class="text-secondary mb-0">ไม่มี rows ให้แสดง</p>
  <?php endif; ?>
</div></div>

