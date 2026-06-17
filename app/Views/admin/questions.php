<h1 class="h3 mb-4">Questions & Choices</h1>
<div class="card lesson-card mb-4"><div class="card-body">
  <h2 class="h5">Add Question</h2>
  <form class="row g-3" method="post" action="<?= e(url('/admin/questions')) ?>">
    <?= csrf_field() ?>
    <div class="col-md-4"><label class="form-label">Quiz</label><select class="form-select" name="quiz_id" required><?php foreach ($quizzes as $quiz): ?><option value="<?= e($quiz['id']) ?>"><?= e($quiz['lesson_title'] . ' / ' . $quiz['title']) ?></option><?php endforeach; ?></select></div>
    <div class="col-md-4"><label class="form-label">Question</label><input class="form-control" name="question_text" required></div>
    <div class="col-md-3"><label class="form-label">Explanation</label><input class="form-control" name="explanation"></div>
    <div class="col-md-1"><label class="form-label">Sort</label><input class="form-control" type="number" name="sort_order" value="1"></div>
    <div class="col-12"><button class="btn btn-primary">Add Question</button></div>
  </form>
</div></div>

<?php foreach ($questions as $question): ?>
  <div class="card lesson-card mb-3"><div class="card-body">
    <div class="d-flex justify-content-between gap-3">
      <div class="flex-grow-1">
        <span class="badge text-bg-light"><?= e($question['quiz_title']) ?></span>
        <form class="row g-2 mt-2" method="post" action="<?= e(url('/admin/questions/' . $question['id'])) ?>">
          <?= csrf_field() ?>
          <div class="col-md-6"><input class="form-control" name="question_text" value="<?= e($question['question_text']) ?>" required></div>
          <div class="col-md-4"><input class="form-control" name="explanation" value="<?= e($question['explanation']) ?>"></div>
          <div class="col-md-1"><input class="form-control" type="number" name="sort_order" value="<?= e($question['sort_order']) ?>"></div>
          <div class="col-md-1"><button class="btn btn-outline-primary w-100">Save</button></div>
        </form>
      </div>
      <form method="post" action="<?= e(url('/admin/questions/' . $question['id'] . '/delete')) ?>" data-confirm="Delete this question?"><?= csrf_field() ?><button class="btn btn-sm btn-outline-danger">Delete</button></form>
    </div>
    <div class="row g-2 my-2">
      <?php foreach ($question['choices'] as $choice): ?>
        <div class="col-md-6">
          <div class="border rounded p-2">
            <form class="row g-2 align-items-center" method="post" action="<?= e(url('/admin/choices/' . $choice['id'])) ?>">
              <?= csrf_field() ?>
              <div class="col-md-7"><input class="form-control form-control-sm" name="choice_text" value="<?= e($choice['choice_text']) ?>"></div>
              <div class="col-md-2"><input class="form-control form-control-sm" type="number" name="sort_order" value="<?= e($choice['sort_order']) ?>"></div>
              <div class="col-md-1 form-check"><input class="form-check-input" type="checkbox" name="is_correct" <?= $choice['is_correct'] ? 'checked' : '' ?>></div>
              <div class="col-md-2"><button class="btn btn-sm btn-outline-primary w-100">Save</button></div>
            </form>
            <form class="mt-1" method="post" action="<?= e(url('/admin/choices/' . $choice['id'] . '/delete')) ?>">
              <?= csrf_field() ?>
              <button class="btn btn-sm btn-link text-danger p-0">Delete choice</button>
            </form>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
    <form class="row g-2 align-items-end" method="post" action="<?= e(url('/admin/choices')) ?>">
      <?= csrf_field() ?>
      <input type="hidden" name="question_id" value="<?= e($question['id']) ?>">
      <div class="col-md-7"><label class="form-label">Choice text</label><input class="form-control" name="choice_text" required></div>
      <div class="col-md-2"><label class="form-label">Sort</label><input class="form-control" type="number" name="sort_order" value="1"></div>
      <div class="col-md-2 form-check pb-2"><input class="form-check-input" type="checkbox" name="is_correct"><label class="form-check-label">Correct</label></div>
      <div class="col-md-1"><button class="btn btn-outline-primary w-100">Add</button></div>
    </form>
  </div></div>
<?php endforeach; ?>
