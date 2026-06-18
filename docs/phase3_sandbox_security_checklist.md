# Phase 3 Sandbox Security Checklist

- `SANDBOX_ENABLED` ต้องเป็น `false` ในค่าเริ่มต้น และเปิดเฉพาะ environment ที่เตรียม Docker แล้ว
- Worker เท่านั้นที่เรียก sandbox; web controller สร้าง queue job และอ่านผลลัพธ์เท่านั้น
- ห้ามใช้ `eval()` หรือ include code ผู้เรียนใน web process
- Docker ต้องรันด้วย `--network none`, memory limit, CPU limit, read-only filesystem และ non-root user
- จำกัด `timeout_ms`, `memory_mb`, `max_output_bytes` และ `max_code_bytes` ใน `runtime_profiles`
- ลบ workspace ชั่วคราวหลังรันทุกครั้ง
- Escape stdout, stderr, code และ expected output ทุกครั้งก่อนแสดงบนหน้าเว็บ
- ใช้ CSRF กับทุก form ที่สร้าง job, review, revoke หรือแก้ runtime profile
- ใช้ rate limit จาก `execution_jobs` ต่อ user/job type
- ตรวจ admin monitor หลัง deploy เพื่อดู timeout/error ที่ผิดปกติ

