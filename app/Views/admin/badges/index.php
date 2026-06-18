<?php $isEdit = (bool) $edit; ?>
<div class="d-flex flex-wrap justify-content-between gap-2 mb-4">
  <h1 class="h3 mb-0">Manage Badges</h1>
  <?php if ($isEdit): ?><a class="btn btn-outline-secondary" href="<?= e(url('/admin/badges')) ?>">Create New</a><?php endif; ?>
</div>

<div class="row g-4">
  <div class="col-lg-4">
    <form method="post" action="<?= e($isEdit ? url('/admin/badges/' . $edit['id']) : url('/admin/badges')) ?>" class="card lesson-card">
      <?= csrf_field() ?>
      <div class="card-body">
        <h2 class="h5"><?= $isEdit ? 'Edit Badge' : 'Create Badge' ?></h2>
        <label class="form-label">Code</label>
        <input class="form-control mb-3" name="code" value="<?= e($edit['code'] ?? '') ?>" required>
        <label class="form-label">Name</label>
        <input class="form-control mb-3" name="name" value="<?= e($edit['name'] ?? '') ?>" required>
        <label class="form-label">Icon</label>
        <input class="form-control mb-3" name="icon" value="<?= e($edit['icon'] ?? '*') ?>">
        <label class="form-label">Description</label>
        <textarea class="form-control mb-3" name="description" rows="3"><?= e($edit['description'] ?? '') ?></textarea>
        <label class="form-label">Rule Type</label>
        <select class="form-select mb-3" name="rule_type">
          <?php foreach (['challenge_count', 'lesson_count', 'xp_total', 'manual'] as $type): ?><option value="<?= e($type) ?>" <?= ($edit['rule_type'] ?? 'challenge_count') === $type ? 'selected' : '' ?>><?= e($type) ?></option><?php endforeach; ?>
        </select>
        <label class="form-label">Rule Value</label>
        <input class="form-control mb-3" type="number" name="rule_value" value="<?= e($edit['rule_value'] ?? 1) ?>">
        <label class="form-label">Sort</label>
        <input class="form-control mb-3" type="number" name="sort_order" value="<?= e($edit['sort_order'] ?? 0) ?>">
        <label class="form-check">
          <input class="form-check-input" type="checkbox" name="is_active" <?= ($edit['is_active'] ?? 1) ? 'checked' : '' ?>>
          <span class="form-check-label">Active</span>
        </label>
      </div>
      <div class="card-footer d-flex justify-content-between">
        <?php if ($isEdit): ?><button class="btn btn-outline-danger" form="deleteBadge" type="submit">Delete</button><?php else: ?><span></span><?php endif; ?>
        <button class="btn btn-primary" type="submit">Save Badge</button>
      </div>
    </form>
    <?php if ($isEdit): ?>
      <form id="deleteBadge" method="post" action="<?= e(url('/admin/badges/' . $edit['id'] . '/delete')) ?>" data-confirm="Delete this badge?"><?= csrf_field() ?></form>
    <?php endif; ?>
  </div>
  <div class="col-lg-8">
    <div class="card lesson-card"><div class="card-body table-responsive">
      <table class="table align-middle">
        <thead><tr><th>Name</th><th>Code</th><th>Rule</th><th>Awarded</th><th>Active</th><th></th></tr></thead>
        <tbody>
        <?php foreach ($badges as $badge): ?>
          <tr>
            <td><?= e($badge['icon'] ?: '*') ?> <?= e($badge['name']) ?></td>
            <td><code><?= e($badge['code']) ?></code></td>
            <td><?= e($badge['rule_type']) ?> <?= e($badge['rule_value']) ?></td>
            <td><?= e($badge['awarded_count'] ?? 0) ?></td>
            <td><span class="badge <?= $badge['is_active'] ? 'text-bg-success' : 'text-bg-secondary' ?>"><?= $badge['is_active'] ? 'Yes' : 'No' ?></span></td>
            <td class="text-end"><a class="btn btn-outline-dark btn-sm" href="<?= e(url('/admin/badges/' . $badge['id'] . '/edit')) ?>">Edit</a></td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    </div></div>
  </div>
</div>
