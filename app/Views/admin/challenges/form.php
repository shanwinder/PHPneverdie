<?php
$isEdit = (bool) $challenge;
$required = $challenge ? implode("\n", array_column($challenge['required_keywords'], 'keyword')) : '';
$forbidden = $challenge ? implode("\n", array_column($challenge['forbidden_keywords'], 'keyword')) : "eval\nexec\nshell_exec\nsystem";
$hints = $challenge ? implode("\n", array_column($challenge['hints'], 'hint_text')) : '';
?>
<div class="d-flex flex-wrap justify-content-between gap-2 mb-4">
  <h1 class="h3 mb-0"><?= $isEdit ? 'Edit Challenge' : 'Create Challenge' ?></h1>
  <a class="btn btn-outline-secondary" href="<?= e(url('/admin/challenges')) ?>">Back</a>
</div>

<form method="post" action="<?= e($isEdit ? url('/admin/challenges/' . $challenge['id']) : url('/admin/challenges')) ?>" class="card lesson-card">
  <?= csrf_field() ?>
  <div class="card-body">
    <div class="row g-3">
      <div class="col-md-6">
        <label class="form-label">Lesson</label>
        <select class="form-select" name="lesson_id" required>
          <?php foreach ($lessons as $lesson): ?>
            <option value="<?= e($lesson['id']) ?>" <?= $challenge && (int) $challenge['lesson_id'] === (int) $lesson['id'] ? 'selected' : '' ?>><?= e($lesson['module_title'] . ' / ' . $lesson['title']) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="col-md-6">
        <label class="form-label">Title</label>
        <input class="form-control" name="title" value="<?= e($challenge['title'] ?? '') ?>" required>
      </div>
      <div class="col-md-6">
        <label class="form-label">Slug</label>
        <input class="form-control" name="slug" value="<?= e($challenge['slug'] ?? '') ?>">
      </div>
      <div class="col-md-3">
        <label class="form-label">Mode</label>
        <select class="form-select" name="checking_mode">
          <?php foreach (['keyword', 'text', 'pattern', 'manual'] as $mode): ?><option value="<?= e($mode) ?>" <?= ($challenge['checking_mode'] ?? 'keyword') === $mode ? 'selected' : '' ?>><?= e($mode) ?></option><?php endforeach; ?>
        </select>
      </div>
      <div class="col-md-3">
        <label class="form-label">Difficulty</label>
        <select class="form-select" name="difficulty">
          <?php foreach (['beginner', 'intermediate', 'advanced'] as $difficulty): ?><option value="<?= e($difficulty) ?>" <?= ($challenge['difficulty'] ?? 'beginner') === $difficulty ? 'selected' : '' ?>><?= e($difficulty) ?></option><?php endforeach; ?>
        </select>
      </div>
      <div class="col-md-3">
        <label class="form-label">XP</label>
        <input class="form-control" type="number" name="xp_reward" value="<?= e($challenge['xp_reward'] ?? 40) ?>">
      </div>
      <div class="col-md-3">
        <label class="form-label">Sort</label>
        <input class="form-control" type="number" name="sort_order" value="<?= e($challenge['sort_order'] ?? 0) ?>">
      </div>
      <div class="col-12">
        <label class="form-label">Description</label>
        <textarea class="form-control" name="description" rows="3" required><?= e($challenge['description'] ?? '') ?></textarea>
      </div>
      <div class="col-12">
        <label class="form-label">Instructions</label>
        <textarea class="form-control" name="instructions" rows="4"><?= e($challenge['instructions'] ?? '') ?></textarea>
      </div>
      <div class="col-md-6">
        <label class="form-label">Starter Code</label>
        <textarea class="form-control code-editor" name="starter_code" rows="12"><?= e($challenge['starter_code'] ?? "<?php\n") ?></textarea>
      </div>
      <div class="col-md-6">
        <label class="form-label">Expected Text</label>
        <input class="form-control mb-3" name="expected_text" value="<?= e($challenge['expected_text'] ?? '') ?>">
        <label class="form-label">Pattern / Expected Output</label>
        <input class="form-control mb-3" name="expected_output" value="<?= e($challenge['expected_output'] ?? '') ?>">
        <label class="form-label">Required Keywords (one per line)</label>
        <textarea class="form-control mb-3" name="required_keywords" rows="4"><?= e($required) ?></textarea>
        <label class="form-label">Forbidden Keywords (one per line)</label>
        <textarea class="form-control mb-3" name="forbidden_keywords" rows="4"><?= e($forbidden) ?></textarea>
        <label class="form-label">Hints (one per line)</label>
        <textarea class="form-control" name="hints" rows="4"><?= e($hints) ?></textarea>
      </div>
      <div class="col-12"><hr></div>
      <div class="col-md-3">
        <label class="form-check mt-4">
          <input class="form-check-input" type="checkbox" name="runtime_enabled" <?= ($challenge['runtime_enabled'] ?? 0) ? 'checked' : '' ?>>
          <span class="form-check-label">Runtime enabled</span>
        </label>
      </div>
      <div class="col-md-3">
        <label class="form-label">Runtime Mode</label>
        <select class="form-select" name="runtime_mode">
          <?php foreach (['rule', 'output', 'testcase', 'manual', 'hybrid'] as $mode): ?><option value="<?= e($mode) ?>" <?= ($challenge['runtime_mode'] ?? 'rule') === $mode ? 'selected' : '' ?>><?= e($mode) ?></option><?php endforeach; ?>
        </select>
      </div>
      <div class="col-md-3">
        <label class="form-label">Runtime Profile</label>
        <select class="form-select" name="runtime_profile_id">
          <option value="">Default active profile</option>
          <?php foreach ($runtimeProfiles as $profile): ?>
            <option value="<?= e($profile['id']) ?>" <?= (int) ($challenge['runtime_profile_id'] ?? 0) === (int) $profile['id'] ? 'selected' : '' ?>><?= e($profile['name']) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="col-md-3">
        <label class="form-check mt-4">
          <input class="form-check-input" type="checkbox" name="run_button_enabled" <?= ($challenge['run_button_enabled'] ?? 0) ? 'checked' : '' ?>>
          <span class="form-check-label">Show Run Code</span>
        </label>
        <label class="form-check">
          <input class="form-check-input" type="checkbox" name="submit_runtime_enabled" <?= ($challenge['submit_runtime_enabled'] ?? 0) ? 'checked' : '' ?>>
          <span class="form-check-label">Submit via runtime</span>
        </label>
      </div>
      <div class="col-12">
        <label class="form-check">
          <input class="form-check-input" type="checkbox" name="is_published" <?= ($challenge['is_published'] ?? 1) ? 'checked' : '' ?>>
          <span class="form-check-label">Published</span>
        </label>
      </div>
    </div>
  </div>
  <div class="card-footer d-flex justify-content-between">
    <?php if ($isEdit): ?>
      <div class="d-flex gap-2">
        <button class="btn btn-outline-danger" form="deleteChallenge" type="submit">Delete</button>
        <a class="btn btn-outline-primary" href="<?= e(url('/admin/challenges/' . $challenge['id'] . '/test-cases')) ?>">Test Cases</a>
      </div>
    <?php else: ?><span></span><?php endif; ?>
    <button class="btn btn-primary" type="submit">Save Challenge</button>
  </div>
</form>
<?php if ($isEdit): ?>
  <form id="deleteChallenge" method="post" action="<?= e(url('/admin/challenges/' . $challenge['id'] . '/delete')) ?>" data-confirm="Delete this challenge and related submissions?"><?= csrf_field() ?></form>
<?php endif; ?>
