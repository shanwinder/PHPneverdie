<h1 class="h3 mb-4">Quizzes</h1>
<div class="row g-4">
  <div class="col-lg-4"><div class="card lesson-card"><div class="card-body">
    <h2 class="h5"><?= $edit ? 'Edit Quiz' : 'Create Quiz' ?></h2>
    <form method="post" action="<?= e($edit ? url('/admin/quizzes/' . $edit['id']) : url('/admin/quizzes')) ?>">
      <?= csrf_field() ?>
      <div class="mb-3"><label class="form-label">Lesson</label><select class="form-select" name="lesson_id" required><?php foreach ($lessons as $lesson): ?><option value="<?= e($lesson['id']) ?>" <?= (int)($edit['lesson_id'] ?? 0) === (int)$lesson['id'] ? 'selected' : '' ?>><?= e($lesson['title']) ?></option><?php endforeach; ?></select></div>
      <div class="mb-3"><label class="form-label">Title</label><input class="form-control" name="title" value="<?= e($edit['title'] ?? '') ?>" required></div>
      <div class="row"><div class="col-6 mb-3"><label class="form-label">Passing %</label><input class="form-control" type="number" name="passing_score" value="<?= e($edit['passing_score'] ?? 70) ?>"></div><div class="col-6 mb-3"><label class="form-label">Max attempts</label><input class="form-control" type="number" name="max_attempts" value="<?= e($edit['max_attempts'] ?? '') ?>"></div></div>
      <div class="form-check mb-3"><input class="form-check-input" type="checkbox" name="is_published" <?= !empty($edit['is_published']) || !$edit ? 'checked' : '' ?>><label class="form-check-label">Published</label></div>
      <button class="btn btn-primary" type="submit">Save</button>
    </form>
  </div></div></div>
  <div class="col-lg-8"><div class="card lesson-card"><div class="card-body table-responsive">
    <table class="table align-middle"><thead><tr><th>Quiz</th><th>Lesson</th><th>Passing</th><th>Status</th><th></th></tr></thead><tbody>
    <?php foreach ($quizzes as $quiz): ?><tr>
      <td><?= e($quiz['title']) ?></td><td><?= e($quiz['lesson_title']) ?></td><td><?= e($quiz['passing_score']) ?>%</td><td><span class="badge <?= $quiz['is_published'] ? 'text-bg-success' : 'text-bg-secondary' ?>"><?= $quiz['is_published'] ? 'Published' : 'Draft' ?></span></td>
      <td class="text-end"><a class="btn btn-sm btn-outline-primary" href="<?= e(url('/admin/quizzes/' . $quiz['id'] . '/edit')) ?>">Edit</a> <form class="d-inline" method="post" action="<?= e(url('/admin/quizzes/' . $quiz['id'] . '/delete')) ?>" data-confirm="Delete this quiz?"><?= csrf_field() ?><button class="btn btn-sm btn-outline-danger">Delete</button></form></td>
    </tr><?php endforeach; ?>
    </tbody></table>
  </div></div></div>
</div>
