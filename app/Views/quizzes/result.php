<div class="card lesson-card">
  <div class="card-body p-4">
    <span class="badge <?= $result['passed'] ? 'text-bg-success' : 'text-bg-warning' ?> mb-3"><?= $result['passed'] ? 'ผ่านแล้ว' : 'ยังไม่ผ่าน' ?></span>
    <h1 class="h3">คะแนน <?= e($result['score']) ?>%</h1>
    <p class="text-secondary">ตอบถูก <?= e($result['correct']) ?> จาก <?= e($result['total']) ?> ข้อ · เกณฑ์ผ่าน <?= e($result['passing_score']) ?>%</p>
    <div class="d-flex flex-wrap gap-2">
      <?php if ($result['passed']): ?>
        <a class="btn btn-primary" href="<?= e(url('/dashboard')) ?>">กลับ Dashboard</a>
        <a class="btn btn-outline-dark" href="<?= e(url('/courses/' . $lesson['course_slug'])) ?>">ดู Learning Path</a>
      <?php else: ?>
        <a class="btn btn-primary" href="<?= e(url('/lessons/' . $lesson['id'] . '/quiz')) ?>">ลองทำใหม่</a>
        <a class="btn btn-outline-dark" href="<?= e(url('/lessons/' . $lesson['slug'])) ?>">กลับไปอ่านบทเรียน</a>
      <?php endif; ?>
    </div>
  </div>
</div>
