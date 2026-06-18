<?php $isEdit = (bool) $edit; ?>
<div class="d-flex flex-wrap justify-content-between gap-2 mb-4">
  <h1 class="h3 mb-0">Manage Animation Blocks</h1>
  <?php if ($isEdit): ?><a class="btn btn-outline-secondary" href="<?= e(url('/admin/animations')) ?>">Create New</a><?php endif; ?>
</div>

<div class="row g-4">
  <div class="col-lg-5">
    <form method="post" action="<?= e($isEdit ? url('/admin/animations/' . $edit['id']) : url('/admin/animations')) ?>" class="card lesson-card">
      <?= csrf_field() ?>
      <div class="card-body">
        <h2 class="h5"><?= $isEdit ? 'Edit Block' : 'Create Block' ?></h2>
        <label class="form-label">Lesson</label>
        <select class="form-select mb-3" name="lesson_id" required>
          <?php foreach ($lessons as $lesson): ?>
            <option value="<?= e($lesson['id']) ?>" <?= $edit && (int) $edit['lesson_id'] === (int) $lesson['id'] ? 'selected' : '' ?>><?= e($lesson['title']) ?></option>
          <?php endforeach; ?>
        </select>
        <label class="form-label">Title</label>
        <input class="form-control mb-3" name="title" value="<?= e($edit['title'] ?? '') ?>" required>
        <label class="form-label">Type</label>
        <select class="form-select mb-3" name="block_type">
          <?php foreach (['html', 'steps', 'diagram', 'lottie'] as $type): ?><option value="<?= e($type) ?>" <?= ($edit['block_type'] ?? 'steps') === $type ? 'selected' : '' ?>><?= e($type) ?></option><?php endforeach; ?>
        </select>
        <label class="form-label">Content</label>
        <textarea class="form-control mb-3" name="content" rows="10"><?= e($edit['content'] ?? '["Step 1","Step 2","Step 3"]') ?></textarea>
        <label class="form-label">Sort</label>
        <input class="form-control mb-3" type="number" name="sort_order" value="<?= e($edit['sort_order'] ?? 0) ?>">
        <label class="form-check">
          <input class="form-check-input" type="checkbox" name="is_published" <?= ($edit['is_published'] ?? 1) ? 'checked' : '' ?>>
          <span class="form-check-label">Published</span>
        </label>
      </div>
      <div class="card-footer d-flex justify-content-between">
        <?php if ($isEdit): ?><button class="btn btn-outline-danger" form="deleteAnimation" type="submit">Delete</button><?php else: ?><span></span><?php endif; ?>
        <button class="btn btn-primary" type="submit">Save Block</button>
      </div>
    </form>
    <?php if ($isEdit): ?>
      <form id="deleteAnimation" method="post" action="<?= e(url('/admin/animations/' . $edit['id'] . '/delete')) ?>" data-confirm="Delete this block?"><?= csrf_field() ?></form>
    <?php endif; ?>
  </div>
  <div class="col-lg-7">
    <div class="card lesson-card"><div class="card-body table-responsive">
      <table class="table align-middle">
        <thead><tr><th>Title</th><th>Lesson</th><th>Type</th><th>Published</th><th></th></tr></thead>
        <tbody>
        <?php foreach ($blocks as $block): ?>
          <tr>
            <td><?= e($block['title']) ?></td>
            <td><?= e($block['lesson_title']) ?></td>
            <td><?= e($block['block_type']) ?></td>
            <td><span class="badge <?= $block['is_published'] ? 'text-bg-success' : 'text-bg-secondary' ?>"><?= $block['is_published'] ? 'Yes' : 'No' ?></span></td>
            <td class="text-end"><a class="btn btn-outline-dark btn-sm" href="<?= e(url('/admin/animations/' . $block['id'] . '/edit')) ?>">Edit</a></td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    </div></div>
  </div>
</div>
