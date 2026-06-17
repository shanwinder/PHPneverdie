<div class="row justify-content-center">
  <div class="col-md-7 col-lg-5">
    <div class="card auth-card">
      <div class="card-body p-4">
        <h1 class="h3 mb-3">เข้าสู่ระบบ</h1>
        <form method="post" action="<?= e(url('/login')) ?>">
          <?= csrf_field() ?>
          <div class="mb-3">
            <label class="form-label">อีเมล</label>
            <input class="form-control" type="email" name="email" value="<?= e(old('email')) ?>" required>
          </div>
          <div class="mb-3">
            <label class="form-label">รหัสผ่าน</label>
            <input class="form-control" type="password" name="password" required>
          </div>
          <button class="btn btn-primary w-100" type="submit">Login</button>
        </form>
        <p class="text-secondary small mt-3 mb-0">ยังไม่มีบัญชี? <a href="<?= e(url('/register')) ?>">สมัครสมาชิก</a></p>
      </div>
    </div>
  </div>
</div>
