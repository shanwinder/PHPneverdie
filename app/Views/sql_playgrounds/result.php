<div class="d-flex flex-wrap justify-content-between gap-2 mb-4">
  <div>
    <h1 class="h3 mb-1">SQL Result</h1>
    <p class="text-secondary mb-0"><?= e($playground['title']) ?></p>
  </div>
  <a class="btn btn-primary" href="<?= e(url('/sql-playgrounds/' . $playground['slug'])) ?>">ลองอีกครั้ง</a>
</div>
<?php require base_path('app/Views/sql_playgrounds/partials/result_table.php'); ?>

