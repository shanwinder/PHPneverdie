<h1 class="h3 mb-4">Lessons</h1>
<div class="row g-4">
  <div class="col-xl-5">
    <div class="card lesson-card"><div class="card-body">
      <h2 class="h5"><?= $edit ? 'Edit Lesson' : 'Create Lesson' ?></h2>
      <form method="post" action="<?= e($edit ? url('/admin/lessons/' . $edit['id']) : url('/admin/lessons')) ?>">
        <?= csrf_field() ?>
        <div class="mb-3"><label class="form-label">Module</label><select class="form-select" name="module_id" required><?php foreach ($modules as $module): ?><option value="<?= e($module['id']) ?>" <?= (int)($edit['module_id'] ?? 0) === (int)$module['id'] ? 'selected' : '' ?>><?= e($module['course_title'] . ' / ' . $module['title']) ?></option><?php endforeach; ?></select></div>
        <div class="row"><div class="col-md-8 mb-3"><label class="form-label">Title</label><input class="form-control" name="title" value="<?= e($edit['title'] ?? '') ?>" required></div><div class="col-md-4 mb-3"><label class="form-label">Sort</label><input class="form-control" type="number" name="sort_order" value="<?= e($edit['sort_order'] ?? 0) ?>"></div></div>
        <div class="mb-3"><label class="form-label">Slug</label><input class="form-control" name="slug" value="<?= e($edit['slug'] ?? '') ?>"></div>
        <div class="mb-3"><label class="form-label">Summary</label><textarea class="form-control" name="summary" rows="2"><?= e($edit['summary'] ?? '') ?></textarea></div>
        <div class="row"><div class="col-md-4 mb-3"><label class="form-label">Difficulty</label><select class="form-select" name="difficulty"><?php foreach (['beginner','intermediate','advanced'] as $level): ?><option <?= ($edit['difficulty'] ?? 'beginner') === $level ? 'selected' : '' ?>><?= e($level) ?></option><?php endforeach; ?></select></div><div class="col-md-4 mb-3"><label class="form-label">Minutes</label><input class="form-control" type="number" name="estimated_minutes" value="<?= e($edit['estimated_minutes'] ?? 10) ?>"></div><div class="col-md-4 mb-3"><label class="form-label">XP</label><input class="form-control" type="number" name="xp_reward" value="<?= e($edit['xp_reward'] ?? 30) ?>"></div></div>
        <div class="mb-3"><label class="form-label">Content HTML</label><textarea class="form-control font-monospace" name="content" rows="12"><?= e($edit['content'] ?? '') ?></textarea></div>
        <div class="form-check mb-3"><input class="form-check-input" type="checkbox" name="is_published" <?= !empty($edit['is_published']) || !$edit ? 'checked' : '' ?>><label class="form-check-label">Published</label></div>
        <button class="btn btn-primary" type="submit">Save</button>
      </form>
    </div></div>
  </div>
  <div class="col-xl-7">
    <div class="card lesson-card"><div class="card-body table-responsive">
      <table class="table align-middle"><thead><tr><th>Lesson</th><th>Module</th><th>Sort</th><th>Status</th><th></th></tr></thead><tbody>
      <?php foreach ($lessons as $lesson): ?><tr>
        <td><?= e($lesson['title']) ?></td><td><?= e($lesson['module_title']) ?></td><td><?= e($lesson['sort_order']) ?></td><td><span class="badge <?= $lesson['is_published'] ? 'text-bg-success' : 'text-bg-secondary' ?>"><?= $lesson['is_published'] ? 'Published' : 'Draft' ?></span></td>
        <td class="text-end"><a class="btn btn-sm btn-outline-primary" href="<?= e(url('/admin/lessons/' . $lesson['id'] . '/edit')) ?>">Edit</a> <form class="d-inline" method="post" action="<?= e(url('/admin/lessons/' . $lesson['id'] . '/delete')) ?>" data-confirm="Delete this lesson?"><?= csrf_field() ?><button class="btn btn-sm btn-outline-danger">Delete</button></form></td>
      </tr><?php endforeach; ?>
      </tbody></table>
    </div></div>
  </div>
</div>
