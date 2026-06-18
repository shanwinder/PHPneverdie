<div class="certificate-sheet text-center">
  <p class="text-uppercase text-secondary">PHP Mastery Quest</p>
  <h1><?= e($certificate['title']) ?></h1>
  <p class="lead">มอบให้</p>
  <h2><?= e($certificate['user_name']) ?></h2>
  <p>สำหรับการผ่านหลักสูตร <?= e($certificate['course_title']) ?></p>
  <p><strong><?= e($certificate['certificate_number']) ?></strong></p>
  <p class="small">Verify: <?= e(url('/verify-certificate/' . $certificate['verification_code'])) ?></p>
  <?php if ($certificate['revoked_at']): ?><div class="alert alert-danger">Certificate นี้ถูก revoke แล้ว</div><?php endif; ?>
</div>

