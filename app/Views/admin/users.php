<h1 class="h3 mb-4">Users</h1>
<div class="card lesson-card"><div class="card-body table-responsive">
  <table class="table align-middle"><thead><tr><th>Name</th><th>Email</th><th>XP</th><th>Role/Status</th><th></th></tr></thead><tbody>
  <?php foreach ($users as $user): ?><tr>
    <td><?= e($user['name']) ?></td><td><?= e($user['email']) ?></td><td><?= e($user['xp']) ?></td>
    <td>
      <form class="row g-2" method="post" action="<?= e(url('/admin/users/' . $user['id'])) ?>">
        <?= csrf_field() ?>
        <div class="col-md-6"><select class="form-select form-select-sm" name="role_id"><?php foreach ($roles as $role): ?><option value="<?= e($role['id']) ?>" <?= (int)$user['role_id'] === (int)$role['id'] ? 'selected' : '' ?>><?= e($role['name']) ?></option><?php endforeach; ?></select></div>
        <div class="col-md-4"><select class="form-select form-select-sm" name="status"><option <?= $user['status'] === 'active' ? 'selected' : '' ?>>active</option><option <?= $user['status'] === 'inactive' ? 'selected' : '' ?>>inactive</option></select></div>
        <div class="col-md-2"><button class="btn btn-sm btn-primary">Save</button></div>
      </form>
    </td>
    <td class="text-secondary small"><?= e($user['last_login_at'] ?? 'never') ?></td>
  </tr><?php endforeach; ?>
  </tbody></table>
</div></div>
