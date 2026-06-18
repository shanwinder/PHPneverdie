<?php $isEdit = (bool) $playground; ?>
<div class="d-flex flex-wrap justify-content-between gap-2 mb-4"><h1 class="h3 mb-0">SQL Playgrounds</h1></div>
<div class="row g-3">
  <div class="col-lg-5">
    <form class="card lesson-card" method="post" action="<?= e($isEdit ? url('/admin/sql-playgrounds/' . $playground['id']) : url('/admin/sql-playgrounds')) ?>">
      <?= csrf_field() ?>
      <div class="card-body row g-3">
        <div class="col-md-8"><label class="form-label">Title</label><input class="form-control" name="title" value="<?= e($playground['title'] ?? '') ?>" required></div>
        <div class="col-md-4"><label class="form-label">XP</label><input class="form-control" type="number" name="xp_reward" value="<?= e($playground['xp_reward'] ?? 50) ?>"></div>
        <div class="col-md-6"><label class="form-label">Slug</label><input class="form-control" name="slug" value="<?= e($playground['slug'] ?? '') ?>"></div>
        <div class="col-md-6"><label class="form-label">Lesson</label><select class="form-select" name="lesson_id"><option value="">None</option><?php foreach ($lessons as $lesson): ?><option value="<?= e($lesson['id']) ?>" <?= (int) ($playground['lesson_id'] ?? 0) === (int) $lesson['id'] ? 'selected' : '' ?>><?= e($lesson['title']) ?></option><?php endforeach; ?></select></div>
        <div class="col-12"><label class="form-label">Description</label><textarea class="form-control" name="description" rows="3"><?= e($playground['description'] ?? '') ?></textarea></div>
        <div class="col-12"><label class="form-label">Schema SQL</label><textarea class="form-control code-editor" name="schema_sql" rows="5"><?= e($playground['schema_sql'] ?? '') ?></textarea></div>
        <div class="col-12"><label class="form-label">Seed SQL</label><textarea class="form-control code-editor" name="seed_sql" rows="5"><?= e($playground['seed_sql'] ?? '') ?></textarea></div>
        <div class="col-12"><label class="form-label">Expected Result JSON</label><textarea class="form-control" name="expected_result_json" rows="4"><?= e($playground['expected_result_json'] ?? '') ?></textarea></div>
        <div class="col-12"><label class="form-check"><input class="form-check-input" type="checkbox" name="is_published" <?= ($playground['is_published'] ?? 1) ? 'checked' : '' ?>> Published</label></div>
      </div>
      <div class="card-footer text-end"><button class="btn btn-primary" type="submit"><?= $isEdit ? 'Update' : 'Create' ?></button></div>
    </form>
  </div>
  <div class="col-lg-7">
    <div class="card lesson-card"><div class="card-body table-responsive">
      <table class="table align-middle"><thead><tr><th>Title</th><th>Slug</th><th>Published</th><th></th></tr></thead><tbody>
      <?php foreach ($playgrounds as $row): ?>
        <tr><td><?= e($row['title']) ?></td><td><?= e($row['slug']) ?></td><td><?= $row['is_published'] ? 'yes' : 'no' ?></td><td class="text-end">
          <a class="btn btn-sm btn-outline-primary" href="<?= e(url('/admin/sql-playgrounds/' . $row['id'] . '/edit')) ?>">Edit</a>
          <form class="d-inline" method="post" action="<?= e(url('/admin/sql-playgrounds/' . $row['id'] . '/delete')) ?>" data-confirm="Delete SQL playground?"><?= csrf_field() ?><button class="btn btn-sm btn-outline-danger" type="submit">Delete</button></form>
        </td></tr>
      <?php endforeach; ?>
      </tbody></table>
    </div></div>
  </div>
</div>

