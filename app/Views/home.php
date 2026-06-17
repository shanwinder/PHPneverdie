<section class="row align-items-center g-5">
  <div class="col-lg-7">
    <span class="badge text-bg-primary mb-3">Interactive PHP Beginner</span>
    <h1 class="display-5 fw-bold">PHP Mastery Quest</h1>
    <p class="lead text-secondary">เรียน PHP จากพื้นฐานด้วยบทเรียนสั้น ๆ Quiz หลังบท และ Dashboard ที่จำความก้าวหน้าของคุณได้จริง</p>
    <div class="d-flex flex-wrap gap-2">
      <?php if (current_user()): ?>
        <a class="btn btn-primary btn-lg" href="<?= e(url('/dashboard')) ?>">ไปที่ Dashboard</a>
      <?php else: ?>
        <a class="btn btn-primary btn-lg" href="<?= e(url('/register')) ?>">เริ่มเรียนฟรี</a>
        <a class="btn btn-outline-dark btn-lg" href="<?= e(url('/login')) ?>">เข้าสู่ระบบ</a>
      <?php endif; ?>
    </div>
  </div>
  <div class="col-lg-5">
    <div class="card metric-card">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <h2 class="h5 mb-0"><?= e($course['title'] ?? 'PHP Beginner') ?></h2>
          <span class="badge text-bg-success"><?= e($course['lessons_count'] ?? 11) ?> บท</span>
        </div>
        <pre class="code-block mb-0"><code>&lt;?php
$name = "Developer";
echo "Hello, " . $name;
?&gt;</code></pre>
      </div>
    </div>
  </div>
</section>
