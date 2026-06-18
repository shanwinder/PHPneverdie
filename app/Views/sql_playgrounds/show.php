<div class="d-flex flex-wrap justify-content-between gap-2 mb-4">
  <div>
    <h1 class="h3 mb-1"><?= e($playground['title']) ?></h1>
    <p class="text-secondary mb-0"><?= e($playground['description']) ?></p>
  </div>
  <a class="btn btn-outline-secondary" href="<?= e(url('/sql-playgrounds')) ?>">Back</a>
</div>

<div class="row g-3">
  <div class="col-lg-5">
    <div class="card lesson-card"><div class="card-body">
      <h2 class="h5">Schema</h2>
      <pre class="code-block"><code><?= e($playground['schema_sql']) ?></code></pre>
      <h2 class="h5">Seed</h2>
      <pre class="code-block"><code><?= e($playground['seed_sql']) ?></code></pre>
    </div></div>
  </div>
  <div class="col-lg-7">
    <form class="card lesson-card" method="post" action="<?= e(url('/sql-playgrounds/' . $playground['id'] . '/run')) ?>">
      <?= csrf_field() ?>
      <div class="card-body">
        <label class="form-label">SELECT Query</label>
        <textarea class="form-control code-editor" name="query_text" rows="10" required><?= e($query ?: 'SELECT * FROM students') ?></textarea>
        <div class="d-flex gap-2 mt-3">
          <button class="btn btn-outline-success" type="submit">Run</button>
          <button class="btn btn-success" type="submit" formaction="<?= e(url('/sql-playgrounds/' . $playground['id'] . '/submit')) ?>">Submit</button>
        </div>
      </div>
    </form>
    <?php if ($result): ?>
      <?php require base_path('app/Views/sql_playgrounds/partials/result_table.php'); ?>
    <?php endif; ?>
  </div>
</div>

