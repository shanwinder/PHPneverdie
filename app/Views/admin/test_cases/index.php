<div class="d-flex flex-wrap justify-content-between gap-2 mb-4">
  <div><h1 class="h3 mb-1">Test Cases</h1><p class="text-secondary mb-0"><?= e($challenge['title']) ?></p></div>
  <a class="btn btn-outline-secondary" href="<?= e(url('/admin/challenges/' . $challenge['id'] . '/edit')) ?>">Back</a>
</div>
<form class="card lesson-card mb-3" method="post" action="<?= e(url('/admin/challenges/' . $challenge['id'] . '/test-cases')) ?>">
  <?= csrf_field() ?>
  <div class="card-body row g-3">
    <div class="col-md-4"><label class="form-label">Name</label><input class="form-control" name="name" required></div>
    <div class="col-md-3"><label class="form-label">Mode</label><select class="form-select" name="comparison_mode"><?php foreach (['trimmed','exact','contains','regex'] as $mode): ?><option value="<?= e($mode) ?>"><?= e($mode) ?></option><?php endforeach; ?></select></div>
    <div class="col-md-2"><label class="form-label">Weight</label><input class="form-control" type="number" name="weight" value="1"></div>
    <div class="col-md-2"><label class="form-label">Sort</label><input class="form-control" type="number" name="sort_order" value="0"></div>
    <div class="col-md-1"><label class="form-check mt-4"><input class="form-check-input" type="checkbox" name="is_hidden"> Hidden</label></div>
    <div class="col-md-4"><label class="form-label">Input</label><textarea class="form-control" name="input_data" rows="4"></textarea></div>
    <div class="col-md-4"><label class="form-label">Expected Output</label><textarea class="form-control" name="expected_output" rows="4"></textarea></div>
    <div class="col-md-4"><label class="form-label">Expected Pattern</label><textarea class="form-control" name="expected_pattern" rows="4"></textarea></div>
  </div>
  <div class="card-footer text-end"><button class="btn btn-primary" type="submit">Add Test Case</button></div>
</form>
<div class="card lesson-card"><div class="card-body">
  <?php foreach ($cases as $case): ?>
    <form class="border-bottom py-3" method="post" action="<?= e(url('/admin/test-cases/' . $case['id'])) ?>">
      <?= csrf_field() ?>
      <div class="row g-2">
        <div class="col-md-3"><input class="form-control" name="name" value="<?= e($case['name']) ?>"></div>
        <div class="col-md-2"><select class="form-select" name="comparison_mode"><?php foreach (['trimmed','exact','contains','regex'] as $mode): ?><option value="<?= e($mode) ?>" <?= $case['comparison_mode'] === $mode ? 'selected' : '' ?>><?= e($mode) ?></option><?php endforeach; ?></select></div>
        <div class="col-md-1"><input class="form-control" type="number" name="weight" value="<?= e($case['weight']) ?>"></div>
        <div class="col-md-1"><input class="form-control" type="number" name="sort_order" value="<?= e($case['sort_order']) ?>"></div>
        <div class="col-md-1"><label class="form-check"><input class="form-check-input" type="checkbox" name="is_hidden" <?= $case['is_hidden'] ? 'checked' : '' ?>> Hidden</label></div>
        <div class="col-md-4 text-end"><button class="btn btn-sm btn-outline-primary" type="submit">Save</button> <button class="btn btn-sm btn-outline-danger" type="submit" formaction="<?= e(url('/admin/test-cases/' . $case['id'] . '/delete')) ?>">Delete</button></div>
        <div class="col-md-4"><textarea class="form-control" name="input_data" rows="3"><?= e($case['input_data']) ?></textarea></div>
        <div class="col-md-4"><textarea class="form-control" name="expected_output" rows="3"><?= e($case['expected_output']) ?></textarea></div>
        <div class="col-md-4"><textarea class="form-control" name="expected_pattern" rows="3"><?= e($case['expected_pattern']) ?></textarea></div>
      </div>
    </form>
  <?php endforeach; ?>
  <?php if (!$cases): ?><p class="text-secondary mb-0">ยังไม่มี test case</p><?php endif; ?>
</div></div>

