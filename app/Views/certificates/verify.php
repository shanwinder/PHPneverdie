<div class="card lesson-card mx-auto" style="max-width: 760px"><div class="card-body text-center">
  <h1 class="h3">Certificate Verification</h1>
  <?php if (!$certificate): ?>
    <div class="alert alert-danger">ไม่พบ certificate code นี้</div>
  <?php else: ?>
    <p><span class="badge <?= $certificate['revoked_at'] ? 'text-bg-danger' : 'text-bg-success' ?>"><?= $certificate['revoked_at'] ? 'revoked' : 'valid' ?></span></p>
    <h2 class="h4"><?= e($certificate['title']) ?></h2>
    <p><?= e($certificate['user_name']) ?> · <?= e($certificate['course_title']) ?></p>
    <p><strong><?= e($certificate['certificate_number']) ?></strong></p>
    <?php if ($certificate['revoked_at']): ?><p class="text-danger"><?= e($certificate['revoke_reason']) ?></p><?php endif; ?>
  <?php endif; ?>
</div></div>

