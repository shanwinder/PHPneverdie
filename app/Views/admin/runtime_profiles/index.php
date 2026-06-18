<?php $isEdit = (bool) $profile; ?>
<div class="d-flex flex-wrap justify-content-between gap-2 mb-4"><h1 class="h3 mb-0">Runtime Profiles</h1></div>
<div class="row g-3">
  <div class="col-lg-5">
    <form class="card lesson-card" method="post" action="<?= e($isEdit ? url('/admin/runtime-profiles/' . $profile['id']) : url('/admin/runtime-profiles')) ?>">
      <?= csrf_field() ?>
      <div class="card-body row g-3">
        <div class="col-12"><label class="form-label">Name</label><input class="form-control" name="name" value="<?= e($profile['name'] ?? '') ?>" required></div>
        <div class="col-md-6"><label class="form-label">PHP Version</label><input class="form-control" name="version" value="<?= e($profile['version'] ?? '8.3') ?>"></div>
        <div class="col-md-6"><label class="form-label">Docker Image</label><input class="form-control" name="docker_image" value="<?= e($profile['docker_image'] ?? 'php-mastery-sandbox-php:8.3') ?>"></div>
        <div class="col-md-6"><label class="form-label">Timeout ms</label><input class="form-control" type="number" name="timeout_ms" value="<?= e($profile['timeout_ms'] ?? 3000) ?>"></div>
        <div class="col-md-6"><label class="form-label">Memory MB</label><input class="form-control" type="number" name="memory_mb" value="<?= e($profile['memory_mb'] ?? 64) ?>"></div>
        <div class="col-md-4"><label class="form-label">CPU</label><input class="form-control" name="cpu_quota" value="<?= e($profile['cpu_quota'] ?? '0.5') ?>"></div>
        <div class="col-md-4"><label class="form-label">Output bytes</label><input class="form-control" type="number" name="max_output_bytes" value="<?= e($profile['max_output_bytes'] ?? 20000) ?>"></div>
        <div class="col-md-4"><label class="form-label">Code bytes</label><input class="form-control" type="number" name="max_code_bytes" value="<?= e($profile['max_code_bytes'] ?? 50000) ?>"></div>
        <div class="col-12">
          <label class="form-check"><input class="form-check-input" type="checkbox" name="network_enabled" <?= ($profile['network_enabled'] ?? 0) ? 'checked' : '' ?>> <span class="form-check-label">Network enabled</span></label>
          <label class="form-check"><input class="form-check-input" type="checkbox" name="is_active" <?= ($profile['is_active'] ?? 1) ? 'checked' : '' ?>> <span class="form-check-label">Active</span></label>
        </div>
      </div>
      <div class="card-footer text-end"><button class="btn btn-primary" type="submit"><?= $isEdit ? 'Update' : 'Create' ?></button></div>
    </form>
  </div>
  <div class="col-lg-7">
    <div class="card lesson-card"><div class="card-body table-responsive">
      <table class="table align-middle"><thead><tr><th>Name</th><th>Timeout</th><th>Memory</th><th>Network</th><th></th></tr></thead><tbody>
      <?php foreach ($profiles as $row): ?>
        <tr>
          <td><?= e($row['name']) ?></td><td><?= e($row['timeout_ms']) ?>ms</td><td><?= e($row['memory_mb']) ?>MB</td><td><?= $row['network_enabled'] ? 'on' : 'off' ?></td>
          <td class="text-end"><a class="btn btn-sm btn-outline-primary" href="<?= e(url('/admin/runtime-profiles/' . $row['id'] . '/edit')) ?>">Edit</a></td>
        </tr>
      <?php endforeach; ?>
      </tbody></table>
    </div></div>
  </div>
</div>

