<h1 class="h3 mb-4">Modules</h1>
<div class="row g-4">
  <div class="col-lg-4">
    <div class="card lesson-card"><div class="card-body">
      <h2 class="h5"><?= $edit ? 'Edit Module' : 'Create Module' ?></h2>
      <form method="post" action="<?= e($edit ? url('/admin/modules/' . $edit['id']) : url('/admin/modules')) ?>">
        <?= csrf_field() ?>
        <div class="mb-3"><label class="form-label">Course</label><select class="form-select" name="course_id" required><?php foreach ($courses as $course): ?><option value="<?= e($course['id']) ?>" <?= (int)($edit['course_id'] ?? 0) === (int)$course['id'] ? 'selected' : '' ?>><?= e($course['title']) ?></option><?php endforeach; ?></select></div>
        <div class="mb-3"><label class="form-label">Title</label><input class="form-control" name="title" value="<?= e($edit['title'] ?? '') ?>" required></div>
        <div class="mb-3"><label class="form-label">Slug</label><input class="form-control" name="slug" value="<?= e($edit['slug'] ?? '') ?>"></div>
        <div class="mb-3"><label class="form-label">Description</label><textarea class="form-control" name="description" rows="3"><?= e($edit['description'] ?? '') ?></textarea></div>
        <div class="mb-3"><label class="form-label">Sort order</label><input class="form-control" type="number" name="sort_order" value="<?= e($edit['sort_order'] ?? 0) ?>"></div>
        <div class="form-check mb-3"><input class="form-check-input" type="checkbox" name="is_published" <?= !empty($edit['is_published']) || !$edit ? 'checked' : '' ?>><label class="form-check-label">Published</label></div>
        <button class="btn btn-primary" type="submit">Save</button>
      </form>
    </div></div>
  </div>
  <div class="col-lg-8">
    <div class="card lesson-card"><div class="card-body table-responsive">
      <table class="table align-middle"><thead><tr><th>Course</th><th>Title</th><th>Sort</th><th>Status</th><th></th></tr></thead><tbody>
      <?php foreach ($modules as $module): ?><tr>
        <td><?= e($module['course_title']) ?></td><td><?= e($module['title']) ?></td><td><?= e($module['sort_order']) ?></td>
        <td><span class="badge <?= $module['is_published'] ? 'text-bg-success' : 'text-bg-secondary' ?>"><?= $module['is_published'] ? 'Published' : 'Draft' ?></span></td>
        <td class="text-end"><a class="btn btn-sm btn-outline-primary" href="<?= e(url('/admin/modules/' . $module['id'] . '/edit')) ?>">Edit</a> <form class="d-inline" method="post" action="<?= e(url('/admin/modules/' . $module['id'] . '/delete')) ?>" data-confirm="Delete this module?"><?= csrf_field() ?><button class="btn btn-sm btn-outline-danger">Delete</button></form></td>
      </tr><?php endforeach; ?>
      </tbody></table>
    </div></div>
  </div>
</div>
