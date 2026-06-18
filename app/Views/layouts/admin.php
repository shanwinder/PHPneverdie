<?php
use App\Core\Session;
$success = Session::consumeFlash('success');
$error = Session::consumeFlash('error');
?>
<!doctype html>
<html lang="th">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= e(($title ?? 'Admin') . ' - Admin') ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="<?= e(url('/assets/css/app.css')) ?>" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="<?= e(url('/admin')) ?>">Admin CMS</a>
    <div class="d-flex gap-2 align-items-center">
      <a class="btn btn-outline-light btn-sm" href="<?= e(url('/dashboard')) ?>">Student View</a>
      <form method="post" action="<?= e(url('/logout')) ?>">
        <?= csrf_field() ?>
        <button class="btn btn-outline-light btn-sm" type="submit">Logout</button>
      </form>
    </div>
  </div>
</nav>
<main class="container-fluid app-shell">
  <div class="row">
    <aside class="col-md-3 col-lg-2 sidebar p-3">
      <nav class="nav flex-column gap-1">
        <a class="nav-link" href="<?= e(url('/admin')) ?>">Dashboard</a>
        <a class="nav-link" href="<?= e(url('/admin/courses')) ?>">Courses</a>
        <a class="nav-link" href="<?= e(url('/admin/modules')) ?>">Modules</a>
        <a class="nav-link" href="<?= e(url('/admin/lessons')) ?>">Lessons</a>
        <a class="nav-link" href="<?= e(url('/admin/quizzes')) ?>">Quizzes</a>
        <a class="nav-link" href="<?= e(url('/admin/questions')) ?>">Questions</a>
        <a class="nav-link" href="<?= e(url('/admin/challenges')) ?>">Challenges</a>
        <a class="nav-link" href="<?= e(url('/admin/runtime-profiles')) ?>">Runtime Profiles</a>
        <a class="nav-link" href="<?= e(url('/admin/executions')) ?>">Execution Monitor</a>
        <a class="nav-link" href="<?= e(url('/admin/sql-playgrounds')) ?>">SQL Playgrounds</a>
        <a class="nav-link" href="<?= e(url('/admin/project-reviews')) ?>">Project Reviews</a>
        <a class="nav-link" href="<?= e(url('/admin/certificates')) ?>">Certificates</a>
        <a class="nav-link" href="<?= e(url('/admin/badges')) ?>">Badges</a>
        <a class="nav-link" href="<?= e(url('/admin/animations')) ?>">Animations</a>
        <a class="nav-link" href="<?= e(url('/admin/users')) ?>">Users</a>
        <a class="nav-link" href="<?= e(url('/admin/reports')) ?>">Reports</a>
      </nav>
    </aside>
    <section class="col-md-9 col-lg-10 p-4">
      <?php if ($success): ?><div class="alert alert-success"><?= $success ?></div><?php endif; ?>
      <?php if ($error): ?><div class="alert alert-danger"><?= $error ?></div><?php endif; ?>
      <?= $content ?>
    </section>
  </div>
</main>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?= e(url('/assets/js/app.js')) ?>"></script>
</body>
</html>
