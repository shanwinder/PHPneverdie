<div class="mb-4">
  <a class="text-decoration-none" href="<?= e(url('/lessons/' . $lesson['slug'])) ?>"><?= e($lesson['title']) ?></a>
  <h1 class="h3 mt-2"><?= e($quiz['title']) ?></h1>
  <p class="text-secondary">ต้องได้อย่างน้อย <?= e($quiz['passing_score']) ?>% จึงถือว่าผ่าน</p>
</div>
<form method="post" action="<?= e(url('/lessons/' . $lesson['id'] . '/quiz')) ?>">
  <?= csrf_field() ?>
  <?php foreach ($quiz['questions'] as $index => $question): ?>
    <div class="card lesson-card mb-3">
      <div class="card-body">
        <h2 class="h6">ข้อ <?= e($index + 1) ?>. <?= e($question['question_text']) ?></h2>
        <div class="row g-2 mt-2">
          <?php foreach ($question['choices'] as $choice): ?>
            <div class="col-md-6">
              <label class="choice-card d-block">
                <input class="form-check-input me-2" type="radio" name="answers[<?= e($question['id']) ?>]" value="<?= e($choice['id']) ?>" required>
                <?= e($choice['choice_text']) ?>
              </label>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  <?php endforeach; ?>
  <button class="btn btn-primary btn-lg" type="submit">ส่งคำตอบ</button>
</form>
