<div class="row justify-content-center">
  <div class="col-md-8 col-lg-6">
    <div class="card auth-card">
      <div class="card-body p-4">
        <h1 class="h3 mb-3">สมัครสมาชิก</h1>
        <form method="post" action="<?= e(url('/register')) ?>">
          <?= csrf_field() ?>
          <div class="mb-3">
            <label class="form-label">ชื่อ</label>
            <input class="form-control" name="name" value="<?= e(old('name')) ?>" required minlength="2">
          </div>
          <div class="mb-3">
            <label class="form-label">อีเมล</label>
            <input class="form-control" type="email" name="email" value="<?= e(old('email')) ?>" required>
          </div>
          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-label">รหัสผ่าน</label>
              <input class="form-control" type="password" name="password" required minlength="8">
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label">ยืนยันรหัสผ่าน</label>
              <input class="form-control" type="password" name="password_confirmation" required minlength="8">
            </div>
          </div>
          <button class="btn btn-primary w-100" type="submit">Create Account</button>
        </form>
      </div>
    </div>
  </div>
</div>
