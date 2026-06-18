<div class="d-flex flex-wrap justify-content-between gap-2 mb-4"><h1 class="h3 mb-0">Certificates</h1></div>
<form class="card lesson-card mb-3" method="post" action="<?= e(url('/admin/certificates/issue/' . ($users[0]['id'] ?? 0))) ?>" id="issueCertificateForm">
  <?= csrf_field() ?>
  <div class="card-body row g-3">
    <div class="col-md-4"><label class="form-label">Student</label><select class="form-select" id="certificateUser"><?php foreach ($users as $user): ?><option value="<?= e($user['id']) ?>"><?= e($user['name'] . ' <' . $user['email'] . '>') ?></option><?php endforeach; ?></select></div>
    <div class="col-md-4"><label class="form-label">Course</label><select class="form-select" name="course_id"><?php foreach ($courses as $course): ?><option value="<?= e($course['id']) ?>"><?= e($course['title']) ?></option><?php endforeach; ?></select></div>
    <div class="col-md-3"><label class="form-label">Project ID</label><input class="form-control" name="project_submission_id" placeholder="optional"></div>
    <div class="col-md-1 d-flex align-items-end"><button class="btn btn-primary" type="submit">Issue</button></div>
  </div>
</form>
<div class="card lesson-card"><div class="card-body table-responsive">
  <table class="table align-middle"><thead><tr><th>Number</th><th>User</th><th>Course</th><th>Status</th><th>Verify</th><th></th></tr></thead><tbody>
  <?php foreach ($certificates as $certificate): ?>
    <tr>
      <td><?= e($certificate['certificate_number']) ?></td><td><?= e($certificate['user_name']) ?></td><td><?= e($certificate['course_title']) ?></td><td><?= $certificate['revoked_at'] ? 'revoked' : 'valid' ?></td>
      <td><a href="<?= e(url('/verify-certificate/' . $certificate['verification_code'])) ?>" target="_blank" rel="noopener">verify</a></td>
      <td class="text-end"><?php if (!$certificate['revoked_at']): ?><form class="d-inline" method="post" action="<?= e(url('/admin/certificates/' . $certificate['id'] . '/revoke')) ?>"><?= csrf_field() ?><input type="hidden" name="reason" value="Revoked by admin"><button class="btn btn-sm btn-outline-danger" type="submit">Revoke</button></form><?php endif; ?></td>
    </tr>
  <?php endforeach; ?>
  </tbody></table>
</div></div>
<script>
document.getElementById('issueCertificateForm')?.addEventListener('submit', (event) => {
  const userId = document.getElementById('certificateUser').value;
  event.currentTarget.action = '<?= e(url('/admin/certificates/issue')) ?>/' + encodeURIComponent(userId);
});
</script>

