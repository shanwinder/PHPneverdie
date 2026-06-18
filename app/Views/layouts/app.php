<?php
use App\Core\Session;
$user = current_user();
$success = Session::consumeFlash('success');
$error = Session::consumeFlash('error');
?>
<!doctype html>
<html lang="th">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= e(($title ?? 'App') . ' - ' . config('app.name')) ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="<?= e(url('/assets/css/app.css')) ?>" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="<?= e(url('/')) ?>">PHP Mastery Quest</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="mainNav">
      <ul class="navbar-nav me-auto">
        <?php if ($user): ?>
          <li class="nav-item"><a class="nav-link" href="<?= e(url('/dashboard')) ?>">Dashboard</a></li>
          <li class="nav-item"><a class="nav-link" href="<?= e(url('/courses')) ?>">Learning Path</a></li>
          <li class="nav-item"><a class="nav-link" href="<?= e(url('/challenges')) ?>">Challenges</a></li>
          <li class="nav-item"><a class="nav-link" href="<?= e(url('/leaderboard')) ?>">Leaderboard</a></li>
          <?php if (user_is_admin()): ?><li class="nav-item"><a class="nav-link" href="<?= e(url('/admin')) ?>">Admin</a></li><?php endif; ?>
        <?php endif; ?>
      </ul>
      <div class="d-flex gap-2 align-items-center">
        <?php if ($user): ?>
          <span class="navbar-text small"><?= e($user['name']) ?></span>
          <form method="post" action="<?= e(url('/logout')) ?>">
            <?= csrf_field() ?>
            <button class="btn btn-outline-light btn-sm" type="submit">Logout</button>
          </form>
        <?php else: ?>
          <a class="btn btn-outline-light btn-sm" href="<?= e(url('/login')) ?>">Login</a>
          <a class="btn btn-primary btn-sm" href="<?= e(url('/register')) ?>">Register</a>
        <?php endif; ?>
      </div>
    </div>
  </div>
</nav>
<main class="<?= $user ? 'container-fluid app-shell' : 'container py-5' ?>">
  <?php if ($user): ?>
    <div class="row">
      <aside class="col-md-3 col-lg-2 sidebar p-3">
        <nav class="nav flex-column gap-1">
          <a class="nav-link" href="<?= e(url('/dashboard')) ?>">Dashboard</a>
          <a class="nav-link" href="<?= e(url('/courses')) ?>">Learning Path</a>
          <a class="nav-link" href="<?= e(url('/challenges')) ?>">Challenges</a>
          <a class="nav-link" href="<?= e(url('/badges')) ?>">My Badges</a>
          <a class="nav-link" href="<?= e(url('/leaderboard')) ?>">Leaderboard</a>
          <a class="nav-link" href="<?= e(url('/dashboard')) ?>#progress">My Progress</a>
          <a class="nav-link" href="<?= e(url('/dashboard')) ?>#profile">Profile</a>
        </nav>
      </aside>
      <section class="col-md-9 col-lg-10 p-4">
  <?php endif; ?>
        <?php if ($success): ?><div class="alert alert-success"><?= $success ?></div><?php endif; ?>
        <?php if ($error): ?><div class="alert alert-danger"><?= $error ?></div><?php endif; ?>
        <?= $content ?>
  <?php if ($user): ?>
      </section>
    </div>
  <?php endif; ?>
</main>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?= e(url('/assets/js/app.js')) ?>"></script>
<script src="<?= e(url('/assets/js/challenge-editor.js')) ?>"></script>
<script src="<?= e(url('/assets/js/animation-blocks.js')) ?>"></script>
</body>
</html>
