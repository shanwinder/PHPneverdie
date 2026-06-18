# แผนพัฒนา Phase 3: Secure Code Execution, Sandbox, SQL Playground, Project Submission และ Certificate

> ไฟล์แนะนำสำหรับวางใน GitHub: `docs/03_phase3_secure_code_execution_plan.md`  
> สถานะเอกสาร: Draft สำหรับใช้วางแผนพัฒนา Phase 3  
> โฟกัสหลัก: Run Code จริงอย่างปลอดภัย, Docker Sandbox, Execution Queue, Test Cases, SQL Playground แบบจำกัด, Project Submission, Project Review และ Certificate เบื้องต้น  
> ข้อจำกัดสำคัญ: Phase 3 ยังไม่ใช่ระบบ Online Judge ระดับใหญ่ และยังไม่ควรเปิด public production ถ้ายังไม่ได้ hardening sandbox, rate limit, monitoring และ infrastructure แยกชัดเจน

---

## 1. บทนำ

Phase 1 ทำให้ระบบ “เรียนได้จริง” ผ่านบทเรียน, Quiz, Progress, XP และ Admin CMS

Phase 2 ทำให้ระบบ “ฝึกเขียนได้” ผ่าน Challenge, Code Editor, Rule-based Checker, Submission History, Hint, Badge, Animation Block และ Leaderboard

Phase 3 คือจุดเปลี่ยนสำคัญที่สุดของแพลตฟอร์ม เพราะระบบจะเริ่ม “รันโค้ดจริง” ของผู้เรียน ดังนั้นต้องออกแบบด้วยแนวคิด Security-first ตั้งแต่ต้น

เป้าหมายของ Phase 3 ไม่ใช่แค่เพิ่มปุ่ม Run Code แต่คือการสร้างระบบย่อยที่ปลอดภัยพอสำหรับรองรับโค้ดที่ไม่น่าเชื่อถือจากผู้ใช้ โดยแยกการทำงานออกจาก web application หลัก มี job queue, worker, sandbox, runtime profile, test case และระบบบันทึกผลการรันอย่างเป็นระบบ

สรุปเป้าหมาย Phase 3:

```text
Code Editor เดิม
    ↓
ส่งคำตอบ / กด Run
    ↓
สร้าง Execution Job
    ↓
Worker ดึง job
    ↓
รันใน Docker Sandbox แบบจำกัดสิทธิ์
    ↓
เก็บ stdout / stderr / exit code / duration / memory
    ↓
ตรวจ test cases
    ↓
อัปเดต submission / challenge progress / XP / badge
```

---

## 2. วิเคราะห์สถานะ GitHub ปัจจุบัน

จากการตรวจ repository ปัจจุบัน พบว่า Phase 1 และ Phase 2 ถูก implement แล้วในระดับที่พร้อมต่อยอด Phase 3

### 2.1 สิ่งที่มีแล้วจาก Phase 1

- Authentication: login, register, logout
- Role: admin และ student
- Student dashboard
- Course > Module > Lesson
- Lesson reader
- Quiz และ quiz attempt
- Progress tracking
- XP log
- Admin CMS
- MySQL schema หลัก
- Seed content PHP Beginner

### 2.2 สิ่งที่มีแล้วจาก Phase 2

- Student Challenges
- Code editor แบบ textarea fallback
- Rule-based checker โดยไม่ execute PHP code
- Submission history
- Feedback และ hint ทีละขั้น
- XP reward จาก challenge ผ่านครั้งแรก
- Badge system
- Lesson animation blocks
- Leaderboard
- Admin CMS สำหรับ challenge, badge และ animation
- Challenge reports
- Seed content Phase 2: 20 challenges, 5 badges, 5 animation blocks

### 2.3 จุดสำคัญที่ Phase 3 ต้องต่อยอด

Phase 2 มีตารางและ service ที่เป็นฐานของ Phase 3 แล้ว:

- `challenges`
- `challenge_submissions`
- `challenge_submission_checks`
- `ChallengeCheckerService`
- `ChallengeSubmissionService`
- `XpService`
- `BadgeService`
- `LeaderboardService`

แต่ Phase 2 ยังตรวจแบบ rule-based เท่านั้น ยังไม่รัน PHP จริง ดังนั้น Phase 3 ต้องเพิ่มระบบเหล่านี้:

- execution jobs
- execution results
- test cases
- runtime profiles
- sandbox worker
- runner adapter
- SQL playground jobs
- project submissions
- project review rubric
- certificates
- audit logs
- admin safety controls

---

## 3. เป้าหมายของ Phase 3

### 3.1 เป้าหมายหลัก

ทำให้ผู้เรียนสามารถกด Run Code และ Submit Code ที่ถูกตรวจด้วย test cases ได้จริง โดย code ต้องถูกรันใน sandbox ที่แยกจากระบบหลัก

เมื่อจบ Phase 3 ผู้เรียนควรสามารถ:

- เปิด challenge ที่รองรับ runtime execution
- กด Run Code เพื่อดู output
- กด Submit เพื่อตรวจกับ test cases
- เห็นผลลัพธ์ stdout, stderr, exit code, runtime duration และ feedback
- เห็นผลแต่ละ test case ว่าผ่านหรือไม่ผ่าน
- ได้ XP เมื่อผ่าน test cases ครั้งแรก
- ใช้ SQL Playground แบบจำกัด เช่น SELECT query กับ dataset ที่กำหนด
- ส่ง Project Submission ได้
- เห็นสถานะ project ว่า submitted, under_review, approved หรือ rejected
- ได้ certificate เมื่อผ่านเงื่อนไขที่กำหนด

Admin ควรสามารถ:

- เปิด/ปิด runtime execution ต่อ challenge
- สร้าง test cases สำหรับ challenge
- ตั้งค่า runtime profile เช่น timeout, memory, network policy
- ดู execution jobs และ logs
- ดู runtime errors
- ดู suspicious submissions
- จัดการ SQL playground dataset/test cases
- ตรวจ project submissions ด้วย rubric
- อนุมัติ/ปฏิเสธ project
- ออก certificate
- ตรวจสอบ certificate ด้วย verification code

---

## 4. นิยามความสำเร็จของ Phase 3

Phase 3 ถือว่าสำเร็จเมื่อ:

1. ระบบสามารถรัน PHP code ของผู้เรียนใน sandbox แยกจาก web process ได้
2. การรัน code ไม่ใช้ `eval()` และไม่ include/require code ของผู้เรียนใน app หลัก
3. มี execution queue และ worker แยกต่างหาก
4. มี runtime timeout ขั้นต่ำ เช่น 3–5 วินาทีต่อ job
5. มี memory limit ต่อ job
6. ปิด network ใน sandbox โดย default
7. ลบไฟล์ชั่วคราวหลังรัน
8. เก็บ stdout/stderr แบบจำกัดขนาด
9. challenge อย่างน้อย 10 ข้อรองรับ runtime test cases
10. SQL Playground แบบ read-only SELECT ใช้งานได้กับ dataset ที่กำหนด
11. Project Submission ใช้งานได้อย่างน้อยแบบ manual review
12. Certificate เบื้องต้นออกให้ผู้เรียนที่ผ่านเงื่อนไขได้
13. Admin มีหน้า monitor execution jobs
14. มี README อัปเดตวิธีติดตั้ง sandbox/worker
15. มีเอกสาร security checklist สำหรับ sandbox

---

## 5. Scope ของ Phase 3

### 5.1 อยู่ในขอบเขต

| หมวด | รายละเอียด |
|---|---|
| Runtime Execution | รัน PHP code จริงใน sandbox |
| Execution Queue | สร้าง job และให้ worker ประมวลผล |
| Docker Sandbox | ใช้ container แยกสำหรับรัน code |
| Runtime Profile | ตั้งค่า timeout, memory, network, output limit |
| Test Cases | ตรวจ output จาก input หลายชุด |
| Execution Result | เก็บ stdout, stderr, exit code, duration, status |
| Challenge Runtime Mode | ให้ challenge บางข้อใช้ runtime checker ได้ |
| SQL Playground | รัน SQL แบบจำกัดกับ dataset ที่เตรียมไว้ |
| Project Submission | ส่ง project เป็น GitHub URL หรือ ZIP |
| Project Review | Admin ตรวจ project ด้วย rubric |
| Certificate | ออก certificate เมื่อเรียน/quiz/challenge/project ผ่าน |
| Admin Monitoring | ดู queue, job, error, suspicious submissions |
| Security Audit Log | บันทึกเหตุการณ์สำคัญ |

### 5.2 ไม่อยู่ในขอบเขต Phase 3

| ไม่ทำใน Phase 3 | เหตุผล |
|---|---|
| AI Tutor เต็มรูปแบบ | ควรรอข้อมูล submission มากขึ้น |
| Marketplace บทเรียน | ยังไม่เกี่ยวกับ runtime core |
| Multi-language sandbox | เริ่มจาก PHP ก่อน |
| Real-time collaborative editor | ซับซ้อนเกิน phase นี้ |
| Auto-grade project ทั้งหมด | เริ่มจาก manual rubric ก่อน |
| Payment/subscription | ยังไม่ใช่เป้าหมาย product learning |
| Kubernetes scale | ยังไม่จำเป็นสำหรับ MVP sandbox |
| Public open judge ขนาดใหญ่ | ต้องมี infrastructure และ security หนักกว่านี้ |

---

## 6. หลักคิดด้านสถาปัตยกรรม

Phase 3 ต้องยึดหลัก 7 ข้อ:

### 6.1 ห้ามรัน code ใน web process

Web app มีหน้าที่รับ request, สร้าง job และแสดงผลเท่านั้น ห้ามให้ controller เรียก PHP code ของผู้เรียนโดยตรง

### 6.2 Worker ต้องแยกจาก Web App

Worker เป็น process แยก เช่น:

```bash
php bin/workers/execution_worker.php
```

หรือในอนาคตอาจแยกเป็น container/service ต่างหาก

### 6.3 Code ผู้เรียนต้องเป็น untrusted input เสมอ

แม้เป็นผู้ใช้ที่ login แล้ว ก็ต้องถือว่า code ที่ส่งมาไม่น่าเชื่อถือทั้งหมด

### 6.4 Sandbox ต้องจำกัดสิทธิ์หลายชั้น

อย่าพึ่งพาแค่ `disable_functions` หรือ regex forbidden keyword เพราะไม่พอ ต้องใช้หลายชั้นร่วมกัน:

- container isolation
- timeout
- memory limit
- no network
- read-only filesystem เท่าที่ทำได้
- temporary workspace
- output size limit
- non-root user
- cleanup หลังรัน

### 6.5 Runtime ต้องเปิดเป็นราย challenge

ไม่ควรเปลี่ยนทุก challenge เป็น runtime ทันที ให้ admin เลือกได้ว่า challenge ใดใช้:

- rule-based
- runtime-output
- runtime-testcase
- manual

### 6.6 ผลลัพธ์ต้องตรวจสอบย้อนหลังได้

ทุก execution job ต้องมีประวัติ เช่น:

- ใครรัน
- รัน challenge ไหน
- code snapshot คืออะไร
- runtime profile ใด
- ใช้เวลาเท่าไร
- exit code อะไร
- stdout/stderr อะไร
- ผ่าน test case ใดบ้าง

### 6.7 Fail-safe by default

ถ้า worker, Docker, runtime profile หรือ security config ผิดพลาด ระบบควร fail เป็น `error` หรือ `sandbox_unavailable` ไม่ใช่รัน code ด้วยวิธีอื่นแทน

---

## 7. ภาพรวม Architecture Phase 3

```text
Browser
  |
  | POST /challenges/{id}/run
  v
Web App Controller
  |
  | validate + CSRF + auth
  v
ExecutionService
  |
  | INSERT execution_jobs(status=queued)
  v
Database Queue
  |
  | worker polling
  v
Execution Worker
  |
  | creates temp workspace
  | writes submitted code
  | starts Docker container
  | enforces limits
  v
Sandbox Container
  |
  | runs php /workspace/main.php
  v
Worker collects result
  |
  | stdout/stderr/exit_code/duration
  v
execution_results + test_case_results
  |
  | update challenge_submissions
  v
Student Result Page
```

---

## 8. Runtime Modes ที่ควรรองรับ

เพิ่ม runtime mode ให้ challenge

| Mode | ความหมาย | ใช้เมื่อไร |
|---|---|---|
| rule | ใช้ checker เดิมจาก Phase 2 | โจทย์พื้นฐานที่ตรวจ keyword ได้ |
| output | รัน code แล้วเทียบ output เดียว | โจทย์ echo/loop/simple function |
| testcase | รันหลาย test case | function, logic, grade calculator |
| manual | รับ submission รอ admin ตรวจ | project-like challenge |
| hybrid | rule + runtime | ต้องมี keyword และ output ถูก |

Phase 3 MVP ควรรองรับก่อน 3 แบบ:

1. `rule`
2. `output`
3. `testcase`

`hybrid` ทำต่อหลัง runtime stable แล้ว

---

## 9. Database Design สำหรับ Phase 3

ให้เพิ่ม migration ใหม่:

```text
database/migrations/003_create_phase3_secure_execution_schema.sql
```

---

### 9.1 แก้ตาราง `challenges`

เพิ่ม field สำหรับ runtime

```sql
ALTER TABLE challenges
  ADD COLUMN runtime_enabled TINYINT(1) NOT NULL DEFAULT 0 AFTER checking_mode,
  ADD COLUMN runtime_mode ENUM('rule','output','testcase','manual','hybrid') NOT NULL DEFAULT 'rule' AFTER runtime_enabled,
  ADD COLUMN runtime_profile_id INT UNSIGNED NULL AFTER runtime_mode,
  ADD COLUMN run_button_enabled TINYINT(1) NOT NULL DEFAULT 0 AFTER runtime_profile_id,
  ADD COLUMN submit_runtime_enabled TINYINT(1) NOT NULL DEFAULT 0 AFTER run_button_enabled;
```

หมายเหตุ: ถ้า MySQL ไม่รองรับตำแหน่ง `AFTER` ตาม environment ให้ตัด `AFTER` ออกได้

---

### 9.2 ตาราง `runtime_profiles`

กำหนดข้อจำกัดของ sandbox

```sql
CREATE TABLE IF NOT EXISTS runtime_profiles (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL UNIQUE,
  language VARCHAR(50) NOT NULL DEFAULT 'php',
  version VARCHAR(50) NULL,
  docker_image VARCHAR(190) NOT NULL,
  timeout_ms INT UNSIGNED NOT NULL DEFAULT 3000,
  memory_mb INT UNSIGNED NOT NULL DEFAULT 64,
  cpu_quota VARCHAR(50) NULL,
  network_enabled TINYINT(1) NOT NULL DEFAULT 0,
  max_output_bytes INT UNSIGNED NOT NULL DEFAULT 20000,
  max_code_bytes INT UNSIGNED NOT NULL DEFAULT 50000,
  is_active TINYINT(1) NOT NULL DEFAULT 1,
  created_at DATETIME NOT NULL,
  updated_at DATETIME NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

Runtime profile เริ่มต้น:

| name | timeout | memory | network | output |
|---|---:|---:|---|---:|
| php-cli-safe-small | 3000ms | 64MB | off | 20KB |
| php-cli-safe-medium | 5000ms | 128MB | off | 50KB |

---

### 9.3 ตาราง `challenge_test_cases`

เก็บ test cases สำหรับ challenge

```sql
CREATE TABLE IF NOT EXISTS challenge_test_cases (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  challenge_id INT UNSIGNED NOT NULL,
  name VARCHAR(255) NOT NULL,
  input_data LONGTEXT NULL,
  expected_output LONGTEXT NULL,
  expected_pattern TEXT NULL,
  comparison_mode ENUM('exact','trimmed','contains','regex') NOT NULL DEFAULT 'trimmed',
  is_hidden TINYINT(1) NOT NULL DEFAULT 0,
  weight INT UNSIGNED NOT NULL DEFAULT 1,
  sort_order INT NOT NULL DEFAULT 0,
  created_at DATETIME NOT NULL,
  updated_at DATETIME NOT NULL,
  CONSTRAINT fk_test_cases_challenge FOREIGN KEY (challenge_id) REFERENCES challenges(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

Comparison mode:

| Mode | วิธีตรวจ |
|---|---|
| exact | stdout ต้องตรงทุกตัวอักษร |
| trimmed | trim stdout และ expected ก่อนเทียบ |
| contains | stdout ต้องมี expected text |
| regex | stdout ต้อง match pattern |

---

### 9.4 ตาราง `execution_jobs`

เก็บ queue ของงานรัน code

```sql
CREATE TABLE IF NOT EXISTS execution_jobs (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id INT UNSIGNED NOT NULL,
  challenge_id INT UNSIGNED NULL,
  submission_id INT UNSIGNED NULL,
  runtime_profile_id INT UNSIGNED NOT NULL,
  job_type ENUM('run','submit','sql_run','sql_submit') NOT NULL DEFAULT 'run',
  status ENUM('queued','running','passed','failed','error','timeout','cancelled') NOT NULL DEFAULT 'queued',
  code LONGTEXT NOT NULL,
  input_data LONGTEXT NULL,
  queued_at DATETIME NOT NULL,
  started_at DATETIME NULL,
  finished_at DATETIME NULL,
  worker_id VARCHAR(100) NULL,
  error_message TEXT NULL,
  created_at DATETIME NOT NULL,
  updated_at DATETIME NOT NULL,
  INDEX idx_execution_status (status),
  INDEX idx_execution_user (user_id),
  INDEX idx_execution_challenge (challenge_id),
  CONSTRAINT fk_execution_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  CONSTRAINT fk_execution_challenge FOREIGN KEY (challenge_id) REFERENCES challenges(id) ON DELETE SET NULL,
  CONSTRAINT fk_execution_submission FOREIGN KEY (submission_id) REFERENCES challenge_submissions(id) ON DELETE SET NULL,
  CONSTRAINT fk_execution_profile FOREIGN KEY (runtime_profile_id) REFERENCES runtime_profiles(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

---

### 9.5 ตาราง `execution_results`

เก็บผลการรันรวม

```sql
CREATE TABLE IF NOT EXISTS execution_results (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  execution_job_id BIGINT UNSIGNED NOT NULL,
  stdout MEDIUMTEXT NULL,
  stderr MEDIUMTEXT NULL,
  exit_code INT NULL,
  duration_ms INT UNSIGNED NULL,
  memory_peak_kb INT UNSIGNED NULL,
  output_truncated TINYINT(1) NOT NULL DEFAULT 0,
  passed TINYINT(1) NOT NULL DEFAULT 0,
  result_summary TEXT NULL,
  created_at DATETIME NOT NULL,
  CONSTRAINT fk_execution_results_job FOREIGN KEY (execution_job_id) REFERENCES execution_jobs(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

---

### 9.6 ตาราง `execution_test_case_results`

เก็บผลแต่ละ test case

```sql
CREATE TABLE IF NOT EXISTS execution_test_case_results (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  execution_job_id BIGINT UNSIGNED NOT NULL,
  test_case_id INT UNSIGNED NULL,
  test_name VARCHAR(255) NOT NULL,
  expected_output MEDIUMTEXT NULL,
  actual_output MEDIUMTEXT NULL,
  stderr MEDIUMTEXT NULL,
  exit_code INT NULL,
  passed TINYINT(1) NOT NULL DEFAULT 0,
  duration_ms INT UNSIGNED NULL,
  message TEXT NULL,
  created_at DATETIME NOT NULL,
  CONSTRAINT fk_case_results_job FOREIGN KEY (execution_job_id) REFERENCES execution_jobs(id) ON DELETE CASCADE,
  CONSTRAINT fk_case_results_case FOREIGN KEY (test_case_id) REFERENCES challenge_test_cases(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

---

### 9.7 ตาราง `sql_playgrounds`

เก็บโจทย์ SQL playground

```sql
CREATE TABLE IF NOT EXISTS sql_playgrounds (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  lesson_id INT UNSIGNED NULL,
  title VARCHAR(255) NOT NULL,
  slug VARCHAR(255) NOT NULL UNIQUE,
  description TEXT NOT NULL,
  schema_sql LONGTEXT NOT NULL,
  seed_sql LONGTEXT NOT NULL,
  expected_result_json LONGTEXT NULL,
  allowed_statement ENUM('select_only','insert_update_select','full_sandbox') NOT NULL DEFAULT 'select_only',
  xp_reward INT NOT NULL DEFAULT 50,
  is_published TINYINT(1) NOT NULL DEFAULT 0,
  created_at DATETIME NOT NULL,
  updated_at DATETIME NOT NULL,
  CONSTRAINT fk_sql_playgrounds_lesson FOREIGN KEY (lesson_id) REFERENCES lessons(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

Phase 3 MVP ควรใช้ `select_only` ก่อน

---

### 9.8 ตาราง `sql_submissions`

```sql
CREATE TABLE IF NOT EXISTS sql_submissions (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id INT UNSIGNED NOT NULL,
  sql_playground_id INT UNSIGNED NOT NULL,
  query_text LONGTEXT NOT NULL,
  status ENUM('passed','failed','error') NOT NULL DEFAULT 'failed',
  result_preview MEDIUMTEXT NULL,
  feedback TEXT NULL,
  submitted_at DATETIME NOT NULL,
  CONSTRAINT fk_sql_submissions_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  CONSTRAINT fk_sql_submissions_playground FOREIGN KEY (sql_playground_id) REFERENCES sql_playgrounds(id) ON DELETE CASCADE,
  INDEX idx_sql_submission_user_playground (user_id, sql_playground_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

---

### 9.9 ตาราง `project_submissions`

```sql
CREATE TABLE IF NOT EXISTS project_submissions (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id INT UNSIGNED NOT NULL,
  title VARCHAR(255) NOT NULL,
  description TEXT NULL,
  submission_type ENUM('github_url','zip_upload','text_note') NOT NULL DEFAULT 'github_url',
  github_url VARCHAR(500) NULL,
  zip_path VARCHAR(500) NULL,
  note LONGTEXT NULL,
  status ENUM('draft','submitted','under_review','approved','rejected','revision_requested') NOT NULL DEFAULT 'submitted',
  submitted_at DATETIME NULL,
  reviewed_at DATETIME NULL,
  reviewed_by INT UNSIGNED NULL,
  created_at DATETIME NOT NULL,
  updated_at DATETIME NOT NULL,
  CONSTRAINT fk_project_submissions_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  CONSTRAINT fk_project_submissions_reviewer FOREIGN KEY (reviewed_by) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

---

### 9.10 ตาราง `project_reviews`

```sql
CREATE TABLE IF NOT EXISTS project_reviews (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  project_submission_id BIGINT UNSIGNED NOT NULL,
  reviewer_id INT UNSIGNED NOT NULL,
  database_score INT UNSIGNED NOT NULL DEFAULT 0,
  security_score INT UNSIGNED NOT NULL DEFAULT 0,
  ui_score INT UNSIGNED NOT NULL DEFAULT 0,
  code_structure_score INT UNSIGNED NOT NULL DEFAULT 0,
  feature_score INT UNSIGNED NOT NULL DEFAULT 0,
  total_score INT UNSIGNED NOT NULL DEFAULT 0,
  feedback LONGTEXT NULL,
  decision ENUM('approved','rejected','revision_requested') NOT NULL,
  reviewed_at DATETIME NOT NULL,
  CONSTRAINT fk_project_reviews_submission FOREIGN KEY (project_submission_id) REFERENCES project_submissions(id) ON DELETE CASCADE,
  CONSTRAINT fk_project_reviews_reviewer FOREIGN KEY (reviewer_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

---

### 9.11 ตาราง `certificates`

```sql
CREATE TABLE IF NOT EXISTS certificates (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id INT UNSIGNED NOT NULL,
  course_id INT UNSIGNED NOT NULL,
  project_submission_id BIGINT UNSIGNED NULL,
  certificate_number VARCHAR(100) NOT NULL UNIQUE,
  verification_code VARCHAR(100) NOT NULL UNIQUE,
  title VARCHAR(255) NOT NULL,
  issued_at DATETIME NOT NULL,
  revoked_at DATETIME NULL,
  revoke_reason TEXT NULL,
  created_at DATETIME NOT NULL,
  updated_at DATETIME NOT NULL,
  CONSTRAINT fk_certificates_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  CONSTRAINT fk_certificates_course FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE,
  CONSTRAINT fk_certificates_project FOREIGN KEY (project_submission_id) REFERENCES project_submissions(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

---

### 9.12 ตาราง `audit_logs`

```sql
CREATE TABLE IF NOT EXISTS audit_logs (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id INT UNSIGNED NULL,
  action VARCHAR(150) NOT NULL,
  entity_type VARCHAR(100) NULL,
  entity_id VARCHAR(100) NULL,
  ip_address VARCHAR(100) NULL,
  user_agent VARCHAR(500) NULL,
  metadata_json LONGTEXT NULL,
  created_at DATETIME NOT NULL,
  INDEX idx_audit_action (action),
  INDEX idx_audit_user (user_id),
  CONSTRAINT fk_audit_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

---

## 10. File Structure ที่ควรเพิ่ม

```text
app/
├── Controllers/
│   ├── ExecutionController.php
│   ├── SqlPlaygroundController.php
│   ├── ProjectSubmissionController.php
│   ├── CertificateController.php
│   └── Admin/
│       ├── RuntimeProfileController.php
│       ├── ExecutionMonitorController.php
│       ├── TestCaseController.php
│       ├── SqlPlaygroundController.php
│       ├── ProjectReviewController.php
│       └── CertificateController.php
│
├── Models/
│   ├── RuntimeProfile.php
│   ├── ChallengeTestCase.php
│   ├── ExecutionJob.php
│   ├── ExecutionResult.php
│   ├── ExecutionTestCaseResult.php
│   ├── SqlPlayground.php
│   ├── SqlSubmission.php
│   ├── ProjectSubmission.php
│   ├── ProjectReview.php
│   ├── Certificate.php
│   └── AuditLog.php
│
├── Services/
│   ├── ExecutionQueueService.php
│   ├── CodeExecutionService.php
│   ├── SandboxRunnerService.php
│   ├── DockerSandboxAdapter.php
│   ├── RuntimeProfileService.php
│   ├── TestCaseEvaluationService.php
│   ├── SqlPlaygroundService.php
│   ├── ProjectSubmissionService.php
│   ├── ProjectReviewService.php
│   ├── CertificateService.php
│   ├── AuditLogService.php
│   └── RateLimitService.php
│
├── Views/
│   ├── executions/
│   │   ├── result.php
│   │   └── pending.php
│   ├── sql_playgrounds/
│   │   ├── index.php
│   │   ├── show.php
│   │   └── result.php
│   ├── projects/
│   │   ├── index.php
│   │   ├── create.php
│   │   ├── show.php
│   │   └── my_submissions.php
│   ├── certificates/
│   │   ├── index.php
│   │   ├── show.php
│   │   └── verify.php
│   └── admin/
│       ├── runtime_profiles/
│       ├── execution_monitor/
│       ├── test_cases/
│       ├── sql_playgrounds/
│       ├── project_reviews/
│       └── certificates/
│
bin/
└── workers/
    └── execution_worker.php

docker/
└── sandbox/
    ├── php/
    │   ├── Dockerfile
    │   ├── php.ini
    │   └── runner.php
    └── sql/
        ├── Dockerfile
        └── runner.sh

storage/
├── sandbox/
│   ├── jobs/
│   └── logs/
├── project_submissions/
└── certificates/

public/
└── assets/
    ├── js/
    │   ├── execution-runner.js
    │   ├── sql-playground.js
    │   └── project-submission.js
    └── css/
        └── phase3.css

database/
├── migrations/
│   └── 003_create_phase3_secure_execution_schema.sql
└── seeders/
    └── phase3_seed.php
```

---

## 11. Routes ที่ควรเพิ่ม

เพิ่มใน `routes/web.php`

```php
use App\Controllers\ExecutionController;
use App\Controllers\SqlPlaygroundController;
use App\Controllers\ProjectSubmissionController;
use App\Controllers\CertificateController;
use App\Controllers\Admin\RuntimeProfileController;
use App\Controllers\Admin\ExecutionMonitorController;
use App\Controllers\Admin\TestCaseController;
use App\Controllers\Admin\SqlPlaygroundController as AdminSqlPlaygroundController;
use App\Controllers\Admin\ProjectReviewController;
use App\Controllers\Admin\CertificateController as AdminCertificateController;

// Runtime execution
$router->post('/challenges/{id}/run', [ExecutionController::class, 'run'], ['auth']);
$router->post('/challenges/{id}/submit-runtime', [ExecutionController::class, 'submitRuntime'], ['auth']);
$router->get('/executions/{id}', [ExecutionController::class, 'show'], ['auth']);
$router->get('/executions/{id}/status', [ExecutionController::class, 'status'], ['auth']);

// SQL Playground
$router->get('/sql-playgrounds', [SqlPlaygroundController::class, 'index'], ['auth']);
$router->get('/sql-playgrounds/{slug}', [SqlPlaygroundController::class, 'show'], ['auth']);
$router->post('/sql-playgrounds/{id}/run', [SqlPlaygroundController::class, 'run'], ['auth']);
$router->post('/sql-playgrounds/{id}/submit', [SqlPlaygroundController::class, 'submit'], ['auth']);

// Project submissions
$router->get('/projects', [ProjectSubmissionController::class, 'index'], ['auth']);
$router->get('/projects/create', [ProjectSubmissionController::class, 'create'], ['auth']);
$router->post('/projects', [ProjectSubmissionController::class, 'store'], ['auth']);
$router->get('/projects/{id}', [ProjectSubmissionController::class, 'show'], ['auth']);
$router->post('/projects/{id}/update', [ProjectSubmissionController::class, 'update'], ['auth']);
$router->post('/projects/{id}/submit', [ProjectSubmissionController::class, 'submit'], ['auth']);

// Certificates
$router->get('/certificates', [CertificateController::class, 'index'], ['auth']);
$router->get('/certificates/{id}', [CertificateController::class, 'show'], ['auth']);
$router->get('/verify-certificate/{code}', [CertificateController::class, 'verify']);

// Admin runtime profiles
$router->get('/admin/runtime-profiles', [RuntimeProfileController::class, 'index'], ['admin']);
$router->post('/admin/runtime-profiles', [RuntimeProfileController::class, 'store'], ['admin']);
$router->get('/admin/runtime-profiles/{id}/edit', [RuntimeProfileController::class, 'edit'], ['admin']);
$router->post('/admin/runtime-profiles/{id}', [RuntimeProfileController::class, 'update'], ['admin']);

// Admin execution monitoring
$router->get('/admin/executions', [ExecutionMonitorController::class, 'index'], ['admin']);
$router->get('/admin/executions/{id}', [ExecutionMonitorController::class, 'show'], ['admin']);
$router->post('/admin/executions/{id}/cancel', [ExecutionMonitorController::class, 'cancel'], ['admin']);

// Admin challenge test cases
$router->get('/admin/challenges/{id}/test-cases', [TestCaseController::class, 'index'], ['admin']);
$router->post('/admin/challenges/{id}/test-cases', [TestCaseController::class, 'store'], ['admin']);
$router->post('/admin/test-cases/{id}', [TestCaseController::class, 'update'], ['admin']);
$router->post('/admin/test-cases/{id}/delete', [TestCaseController::class, 'delete'], ['admin']);

// Admin SQL playground
$router->get('/admin/sql-playgrounds', [AdminSqlPlaygroundController::class, 'index'], ['admin']);
$router->post('/admin/sql-playgrounds', [AdminSqlPlaygroundController::class, 'store'], ['admin']);
$router->get('/admin/sql-playgrounds/{id}/edit', [AdminSqlPlaygroundController::class, 'edit'], ['admin']);
$router->post('/admin/sql-playgrounds/{id}', [AdminSqlPlaygroundController::class, 'update'], ['admin']);
$router->post('/admin/sql-playgrounds/{id}/delete', [AdminSqlPlaygroundController::class, 'delete'], ['admin']);

// Admin project reviews
$router->get('/admin/project-reviews', [ProjectReviewController::class, 'index'], ['admin']);
$router->get('/admin/project-reviews/{id}', [ProjectReviewController::class, 'show'], ['admin']);
$router->post('/admin/project-reviews/{id}/review', [ProjectReviewController::class, 'review'], ['admin']);

// Admin certificates
$router->get('/admin/certificates', [AdminCertificateController::class, 'index'], ['admin']);
$router->post('/admin/certificates/issue/{userId}', [AdminCertificateController::class, 'issue'], ['admin']);
$router->post('/admin/certificates/{id}/revoke', [AdminCertificateController::class, 'revoke'], ['admin']);
```

---

## 12. Module รายละเอียด

---

# 12.1 Module: Execution Queue

## เป้าหมาย

แยกการรัน code ออกจาก request/response ปกติของเว็บ

## Flow

```text
Student clicks Run
  ↓
ExecutionController validates request
  ↓
ExecutionQueueService creates job
  ↓
Return job id + pending status
  ↓
Frontend polls /executions/{id}/status
  ↓
Worker processes job
  ↓
Frontend receives result
```

## Service ที่ควรสร้าง

```text
app/Services/ExecutionQueueService.php
```

## Method ที่ควรมี

```php
public function createRunJob(int $userId, int $challengeId, string $code): int;
public function createSubmitJob(int $userId, int $challengeId, int $submissionId, string $code): int;
public function markRunning(int $jobId, string $workerId): void;
public function markFinished(int $jobId, array $result): void;
public function markError(int $jobId, string $message): void;
public function nextQueuedJob(): ?array;
```

## Acceptance Criteria

- สร้าง job status `queued` ได้
- worker เปลี่ยนเป็น `running` ได้
- worker เปลี่ยนเป็น `passed`, `failed`, `timeout`, `error` ได้
- ผู้เรียนดูเฉพาะ job ของตัวเองได้
- admin ดูทุก job ได้

---

# 12.2 Module: Execution Worker

## เป้าหมาย

ประมวลผล execution jobs นอก web request

## ไฟล์

```text
bin/workers/execution_worker.php
```

## ตัวอย่าง command

```bash
php bin/workers/execution_worker.php
```

## Worker Loop

```text
1. ดึง queued job 1 งาน
2. lock job หรือ update status เป็น running
3. โหลด runtime profile
4. สร้าง temp workspace
5. เขียน code ลงไฟล์ main.php
6. เรียก DockerSandboxAdapter
7. เก็บ stdout/stderr/exit_code/duration
8. ตรวจ test cases ถ้าเป็น submit
9. บันทึก execution_results
10. อัปเดต submission status
11. cleanup temp workspace
12. loop ต่อ
```

## ข้อควรระวัง

- ต้องป้องกัน worker หลายตัวหยิบ job เดียวกัน
- ถ้า worker ตายกลางทาง job ต้องถูก mark stale ได้
- ต้องมี max jobs per worker cycle เพื่อ restart เป็นระยะ
- log error ต้องเก็บพอ debug แต่ไม่เก็บ secret

---

# 12.3 Module: Docker Sandbox Adapter

## เป้าหมาย

รัน code ใน container ที่จำกัดสิทธิ์

## ไฟล์

```text
app/Services/DockerSandboxAdapter.php
docker/sandbox/php/Dockerfile
docker/sandbox/php/php.ini
docker/sandbox/php/runner.php
```

## แนวทาง Docker Command

ตัวอย่างแนวคิด:

```bash
docker run --rm \
  --network none \
  --memory 64m \
  --cpus 0.5 \
  --read-only \
  --tmpfs /tmp:rw,noexec,nosuid,size=16m \
  --user 1000:1000 \
  -v /path/to/job:/workspace:ro \
  php-mastery-sandbox-php:8.3 \
  php /sandbox/runner.php /workspace/main.php
```

หมายเหตุ: command จริงอาจต้องปรับตาม OS และ Docker Desktop โดยเฉพาะ macOS/MAMP

## php.ini สำหรับ sandbox

```ini
memory_limit=64M
max_execution_time=3
display_errors=1
log_errors=0
allow_url_fopen=0
allow_url_include=0
disable_functions=exec,shell_exec,system,passthru,proc_open,popen,curl_exec,curl_multi_exec,parse_ini_file,show_source
open_basedir=/workspace:/tmp
```

## สำคัญมาก

`disable_functions` เป็นเพียงชั้นเสริม ไม่ใช่ sandbox หลัก ต้องใช้ container isolation และ resource limits ร่วมด้วย

## Acceptance Criteria

- container รัน code PHP ธรรมดาได้
- network ถูกปิดโดย default
- code ที่ infinite loop ถูก timeout
- output ใหญ่เกิน limit ถูกตัด
- file ชั่วคราวถูกลบหลังรัน
- worker ไม่รัน code ถ้า Docker unavailable

---

# 12.4 Module: Runtime Test Cases

## เป้าหมาย

ตรวจคำตอบจากผลลัพธ์จริง แทนการตรวจ keyword อย่างเดียว

## ประเภท test case

| ประเภท | ตัวอย่าง |
|---|---|
| output exact | output ต้องเท่ากับ `Hello PHP` |
| output trimmed | trim แล้วต้องเท่ากัน |
| output contains | output ต้องมีคำว่า `Somchai` |
| regex | output match `/Grade: A/` |
| hidden | ซ่อน expected จากผู้เรียน |

## ตัวอย่างโจทย์

Challenge: คำนวณเกรด

Starter code:

```php
<?php
$score = (int) trim(fgets(STDIN));

// เขียน logic แสดงเกรด
```

Test cases:

| input | expected |
|---|---|
| 85 | A |
| 72 | B |
| 63 | C |
| 51 | D |
| 40 | F |

## Acceptance Criteria

- Admin เพิ่ม test cases ได้
- Worker รันทุก test case ได้
- แสดงผล public test case ให้ผู้เรียนเห็น
- hidden test case ไม่แสดง expected output
- คำนวณคะแนนรวมจาก weight ได้

---

# 12.5 Module: Challenge Runtime Integration

## เป้าหมาย

เชื่อม challenge เดิมจาก Phase 2 กับ runtime execution

## การเปลี่ยนหน้า Challenge

หน้า challenge ควรมี 2 ปุ่ม:

```text
[Run Code]     [Submit for Test]
```

Run Code:

- รันด้วย input default หรือ input ที่ผู้เรียนกรอก
- ไม่ให้ XP
- ใช้สำหรับทดลอง

Submit for Test:

- รัน test cases
- บันทึก submission
- ถ้าผ่านทั้งหมด ให้ XP และ badge

## UI ผลลัพธ์

```text
Status: Passed / Failed / Timeout / Error
Duration: 120ms
Exit Code: 0

Output:
Hello PHP

Test Cases:
✓ Public Case 1
✓ Public Case 2
✗ Hidden Case 3
```

## Acceptance Criteria

- challenge ที่ runtime_enabled = 0 ยังใช้ rule-based เหมือนเดิม
- challenge ที่ runtime_enabled = 1 ใช้ runtime flow
- หาก worker ยังไม่ตอบ ระบบแสดง pending
- หาก timeout แสดง feedback ชัดเจน
- หาก passed ให้ XP ครั้งเดียว

---

# 12.6 Module: SQL Playground

## เป้าหมาย

ให้ผู้เรียนฝึก SQL กับ dataset ที่กำหนด โดยเริ่มจาก SELECT-only เพื่อความปลอดภัย

## Phase 3 SQL MVP

รองรับเฉพาะ:

- SELECT
- WHERE
- ORDER BY
- LIMIT
- JOIN แบบพื้นฐาน

ยังไม่ควรเปิด:

- DROP
- ALTER
- CREATE
- DELETE
- UPDATE
- INSERT
- LOAD DATA
- FILE operations

## Flow

```text
Student opens SQL playground
  ↓
เห็น schema + sample tables
  ↓
เขียน SELECT query
  ↓
Run query
  ↓
ระบบสร้าง temporary DB หรือ sandbox DB
  ↓
รัน schema_sql + seed_sql
  ↓
รัน query ผู้เรียน
  ↓
แสดง result table
  ↓
Submit เพื่อตรวจ expected result
```

## SQL Security

- เริ่มจาก parser ง่าย ๆ ที่ allow เฉพาะ query ขึ้นต้นด้วย `SELECT`
- reject ถ้ามี semicolon มากกว่า 1 statement
- reject keyword อันตราย เช่น DROP, DELETE, UPDATE, INSERT, ALTER, CREATE, GRANT, FILE
- ใช้ database sandbox แยก
- จำกัด rows เช่น 100 rows
- จำกัด timeout
- ล้าง database ชั่วคราวหลังรัน

## Acceptance Criteria

- SQL Playground เปิดได้
- Run SELECT ได้
- Query อันตรายถูก reject
- Submit แล้วตรวจผลกับ expected result ได้
- เก็บ sql_submissions ได้

---

# 12.7 Module: Project Submission

## เป้าหมาย

ให้ผู้เรียนส่งโปรเจกต์จริงหลังเรียนและฝึก challenge มาระดับหนึ่ง

## Submission Type

| Type | รายละเอียด |
|---|---|
| github_url | ส่ง GitHub repository URL |
| zip_upload | อัปโหลด zip project |
| text_note | ส่งคำอธิบาย/ลิงก์ภายนอก |

## เงื่อนไขก่อนส่ง Project

แนะนำให้กำหนด:

- เรียนจบบทเรียนอย่างน้อย 80%
- ผ่าน quiz อย่างน้อย 80%
- ผ่าน challenge อย่างน้อย 15 ข้อ
- มี XP ขั้นต่ำ เช่น 800 XP

## Rubric

| หมวด | คะแนนเต็ม |
|---|---:|
| Database Design | 20 |
| Security | 20 |
| UI/UX | 20 |
| Code Structure | 20 |
| Feature Completeness | 20 |
| รวม | 100 |

## สถานะ Project

| Status | ความหมาย |
|---|---|
| draft | ยังไม่ส่งจริง |
| submitted | ส่งแล้ว |
| under_review | admin กำลังตรวจ |
| approved | ผ่าน |
| rejected | ไม่ผ่าน |
| revision_requested | ขอแก้ไข |

## Acceptance Criteria

- ผู้เรียนสร้าง project submission ได้
- ผู้เรียนส่ง GitHub URL ได้
- ผู้เรียนอัปโหลด zip ได้ถ้าเปิดใช้
- Admin ดูรายการ project ได้
- Admin ให้คะแนน rubric ได้
- Admin ขอแก้ไข/อนุมัติ/ปฏิเสธได้
- ผู้เรียนเห็น feedback ได้

---

# 12.8 Module: Certificate

## เป้าหมาย

ออก certificate ให้ผู้เรียนเมื่อผ่านเงื่อนไขครบ

## เงื่อนไขออก certificate เบื้องต้น

- Course progress = 100%
- Quiz pass rate >= 80%
- Challenge pass count >= 20 หรือผ่าน challenge ที่กำหนดครบ
- Project submission approved

## Certificate Data

- ชื่อผู้เรียน
- ชื่อ course
- วันที่ออก
- certificate number
- verification code
- QR/URL สำหรับตรวจสอบ

## Certificate Number Format

ตัวอย่าง:

```text
PMQ-PHPB-2026-000001
```

## Verification URL

```text
/verify-certificate/{verification_code}
```

## PDF

Phase 3 สามารถเริ่มจาก HTML certificate + print browser ก่อน ยังไม่จำเป็นต้องสร้าง PDF จริง ถ้าต้องการ PDF ให้ใช้ library ใน Phase 4

## Acceptance Criteria

- ระบบออก certificate ได้เมื่อครบเงื่อนไข
- ผู้เรียนดู certificate ของตัวเองได้
- verification URL เปิดแบบ public ได้
- certificate ที่ถูก revoke ต้องแสดงว่าไม่ valid
- Admin revoke certificate ได้

---

# 12.9 Module: Admin Execution Monitor

## เป้าหมาย

ให้ admin ดูสุขภาพระบบ sandbox และตรวจปัญหาได้

## หน้า Admin

| หน้า | รายละเอียด |
|---|---|
| `/admin/executions` | รายการ execution jobs |
| `/admin/executions/{id}` | รายละเอียด job |
| `/admin/runtime-profiles` | ตั้งค่า profile |
| `/admin/challenges/{id}/test-cases` | จัดการ test cases |
| `/admin/reports/runtime` | รายงาน runtime |

## Metrics ที่ควรแสดง

- queued jobs
- running jobs
- passed/failed/error/timeout count
- average duration
- top timeout challenges
- top error messages
- suspicious forbidden code attempts
- worker last heartbeat

## Acceptance Criteria

- Admin เห็น queue status ได้
- Admin เปิดดู stdout/stderr ได้แบบ escape
- Admin cancel job ได้ถ้ายัง queued/running
- Admin เห็น runtime error ที่เกิดบ่อยได้

---

# 12.10 Module: Audit Log

## เป้าหมาย

บันทึกเหตุการณ์สำคัญ โดยเฉพาะที่เกี่ยวกับ sandbox และ certificate

## Action ที่ควร log

| Action | ตัวอย่าง |
|---|---|
| execution.job.created | ผู้เรียนสร้าง job |
| execution.job.timeout | job timeout |
| execution.job.error | job error |
| sandbox.forbidden.detected | พบ keyword เสี่ยง |
| runtime_profile.updated | admin แก้ profile |
| project.reviewed | admin ตรวจ project |
| certificate.issued | ออก certificate |
| certificate.revoked | ยกเลิก certificate |

## Acceptance Criteria

- บันทึก audit log เมื่อมี event สำคัญ
- Admin ดู log ล่าสุดได้
- ไม่เก็บข้อมูลลับ เช่น password, env, DB password

---

## 13. UX/UI Phase 3

### 13.1 Student Challenge Runtime Page

```text
┌──────────────────────────────────────────────┐
│ Challenge: คำนวณเกรด                         │
│ Difficulty: Beginner | XP: 60                │
├──────────────────────────────────────────────┤
│ Instructions                                  │
├──────────────────────┬───────────────────────┤
│ Code Editor           │ Test Results          │
│                       │ Output                │
│                       │ Error                 │
├──────────────────────┴───────────────────────┤
│ [Run Code] [Submit for Test] [Reset] [Hint]   │
└──────────────────────────────────────────────┘
```

### 13.2 Pending UX

เมื่อ submit แล้ว job ยังไม่เสร็จ:

```text
กำลังรัน code ใน sandbox...
สถานะ: queued/running
```

ใช้ polling ทุก 1–2 วินาทีผ่าน `/executions/{id}/status`

### 13.3 Error UX

ข้อความควรเป็นมิตร:

| Runtime Status | Feedback |
|---|---|
| timeout | “โค้ดใช้เวลานานเกินกำหนด ลองตรวจ loop หรือเงื่อนไขหยุดการทำงาน” |
| memory exceeded | “โค้ดใช้หน่วยความจำมากเกินไป ลองลดข้อมูลหรือ loop ที่สร้าง array ขนาดใหญ่” |
| syntax error | “PHP พบ syntax error ลองตรวจเครื่องหมาย ;, วงเล็บ หรือ quote” |
| forbidden | “พบคำสั่งที่ระบบไม่อนุญาตเพื่อความปลอดภัย” |
| sandbox unavailable | “ระบบรันโค้ดชั่วคราวไม่พร้อมใช้งาน กรุณาลองใหม่ภายหลัง” |

---

## 14. Security Requirements ระดับบังคับ

### 14.1 ห้ามทำเด็ดขาด

- ห้ามใช้ `eval()`
- ห้าม include/require code ผู้เรียนใน app หลัก
- ห้ามรัน code ผู้เรียนใน controller
- ห้ามรัน code ผู้เรียนด้วย PHP process เดียวกับเว็บ
- ห้าม mount project root เข้า container แบบ writable
- ห้ามเปิด network ใน container โดย default
- ห้ามแสดง stderr/stdout โดยไม่ escape
- ห้ามเก็บไฟล์ code ผู้เรียนใน public directory
- ห้ามให้ container run เป็น root ถ้าเลี่ยงได้

### 14.2 ต้องทำ

- CSRF ทุก form
- Auth ทุก runtime endpoint
- Rate limit ต่อ user
- จำกัด code size
- จำกัด output size
- จำกัด timeout
- จำกัด memory
- cleanup workspace
- escape stdout/stderr/code
- prepared statement
- admin-only runtime profile management
- audit log
- environment variable สำหรับเปิด/ปิด runtime execution ทั้งระบบ

### 14.3 Environment Variables

เพิ่ม `.env.example`

```env
SANDBOX_ENABLED=false
SANDBOX_DRIVER=docker
SANDBOX_WORK_DIR=storage/sandbox/jobs
SANDBOX_PHP_IMAGE=php-mastery-sandbox-php:8.3
SANDBOX_DEFAULT_TIMEOUT_MS=3000
SANDBOX_DEFAULT_MEMORY_MB=64
SANDBOX_MAX_OUTPUT_BYTES=20000
SANDBOX_NETWORK_ENABLED=false
SANDBOX_MAX_JOBS_PER_MINUTE=10
WORKER_ID=local-worker-1
```

ค่า default ควรเป็น `SANDBOX_ENABLED=false` เพื่อความปลอดภัย

---

## 15. Rate Limiting

### 15.1 เป้าหมาย

ป้องกันผู้ใช้กด Run/Submit รัวจน server หนัก

### 15.2 Rule เริ่มต้น

| Action | Limit |
|---|---:|
| Run Code | 10 ครั้ง / นาที / user |
| Submit Runtime | 5 ครั้ง / นาที / user |
| SQL Run | 10 ครั้ง / นาที / user |
| SQL Submit | 5 ครั้ง / นาที / user |
| Project Upload | 3 ครั้ง / ชั่วโมง / user |

### 15.3 วิธีทำแบบง่ายใน Phase 3

ใช้ฐานข้อมูลนับจาก execution_jobs ในช่วงเวลาล่าสุดก่อนสร้าง job ใหม่

```sql
SELECT COUNT(*) AS total
FROM execution_jobs
WHERE user_id = :user_id
  AND job_type = :job_type
  AND queued_at >= DATE_SUB(NOW(), INTERVAL 1 MINUTE)
```

ถ้าเกิน limit ให้ reject ด้วย feedback friendly

---

## 16. Seed Content Phase 3

สร้างไฟล์:

```text
database/seeders/phase3_seed.php
```

### 16.1 Runtime Profiles

- php-cli-safe-small
- php-cli-safe-medium

### 16.2 Runtime Challenges อย่างน้อย 10 ข้อ

| บทเรียน | Challenge | Runtime Mode |
|---|---|---|
| echo | แสดง Hello PHP | output |
| variables | แสดงค่าตัวแปร name | output |
| operators | คำนวณ total price | testcase |
| if else | ตรวจผ่าน/ไม่ผ่าน | testcase |
| switch | แสดงชื่อวัน | testcase |
| loop | แสดงเลข 1–5 | output |
| foreach | แสดงรายชื่อใน array | output |
| array | นับจำนวนสมาชิก | testcase |
| function | สร้าง greet function | testcase |
| function | calculateGrade | testcase |

### 16.3 SQL Playground อย่างน้อย 5 โจทย์

| โจทย์ | หัวข้อ |
|---|---|
| แสดงรายชื่อนักเรียนทั้งหมด | SELECT |
| ค้นหานักเรียนชั้น ป.6 | WHERE |
| เรียงคะแนนจากมากไปน้อย | ORDER BY |
| แสดง 5 อันดับแรก | LIMIT |
| JOIN students กับ classrooms | JOIN |

### 16.4 Project Submission Template

โปรเจกต์แนะนำ:

- ระบบจัดการนักเรียนแบบ CRUD
- ระบบบันทึกคะแนน
- ระบบห้องเช่าเบื้องต้น
- ระบบแจ้งซ่อม
- ระบบจัดการบทเรียนขนาดเล็ก

### 16.5 Certificate Template

- PHP Beginner Completion Certificate
- PHP Interactive Practice Certificate

---

## 17. Sprint Plan สำหรับ Phase 3

แนะนำแบ่งเป็น 10 sprint

---

### Sprint 0: Security Audit และเตรียมฐาน

**เป้าหมาย:** ตรวจ Phase 1/2 ก่อนเพิ่ม runtime

งานที่ต้องทำ:

- ตรวจ flow login/student/admin
- ตรวจ challenge rule-based flow
- ตรวจ submission history
- ตรวจ XP/badge/leaderboard
- ตรวจ CSRF ทุก form
- ตรวจ escape code/stdout/stderr ในหน้าเดิม
- เพิ่มเอกสาร Phase 3 ใน `docs/`
- สร้าง branch `phase3-secure-code-execution`

Acceptance Criteria:

- Phase 2 run ได้ครบ
- ไม่มี fatal error ใน challenge flow
- rule-based checker ยังทำงาน
- README ปัจจุบันชัดเจนพอสำหรับต่อ migration

---

### Sprint 1: Migration และ Models

**เป้าหมาย:** เพิ่ม schema Phase 3

งานที่ต้องทำ:

- สร้าง `003_create_phase3_secure_execution_schema.sql`
- เพิ่ม runtime fields ให้ `challenges`
- สร้างตาราง runtime_profiles
- สร้างตาราง challenge_test_cases
- สร้าง execution_jobs/results/test_case_results
- สร้าง sql_playgrounds/sql_submissions
- สร้าง project_submissions/project_reviews
- สร้าง certificates
- สร้าง audit_logs
- เพิ่ม Models ที่เกี่ยวข้อง

Acceptance Criteria:

- migration run ได้จากฐาน Phase 1+2
- foreign keys ถูกต้อง
- rollback manual plan เขียนไว้ใน docs
- seed runtime profile ได้

---

### Sprint 2: Execution Queue พื้นฐาน

**เป้าหมาย:** สร้าง job queue โดยยังไม่ต้องรัน Docker จริง

งานที่ต้องทำ:

- สร้าง ExecutionQueueService
- สร้าง ExecutionController
- เพิ่ม routes run/status
- เพิ่ม execution job create
- เพิ่มหน้า pending/result
- สร้าง worker mock mode
- เพิ่ม env `SANDBOX_ENABLED=false`

Acceptance Criteria:

- กด Run แล้วเกิด job queued
- status endpoint คืนสถานะได้
- mock worker เปลี่ยน job เป็น finished ได้
- ผู้เรียนดู job ตัวเองได้เท่านั้น
- admin ดูทุก job ได้

---

### Sprint 3: Docker Sandbox Prototype

**เป้าหมาย:** รัน PHP code ใน container ได้แบบ local

งานที่ต้องทำ:

- สร้าง Dockerfile sandbox PHP
- สร้าง php.ini sandbox
- สร้าง runner.php
- สร้าง DockerSandboxAdapter
- worker เรียก docker run ได้
- จำกัด timeout/memory/output เบื้องต้น
- cleanup workspace

Acceptance Criteria:

- echo hello รันได้
- syntax error แสดง stderr ได้
- infinite loop timeout ได้
- output ใหญ่ถูก truncate
- network ถูกปิด
- temp workspace ถูกลบหลังรัน

---

### Sprint 4: Runtime Test Cases

**เป้าหมาย:** รัน code กับ test cases ได้

งานที่ต้องทำ:

- สร้าง ChallengeTestCase model
- สร้าง TestCaseEvaluationService
- เพิ่ม admin test case CRUD
- worker รัน test cases ทีละชุด
- เก็บ execution_test_case_results
- แสดงผล test case ในหน้า result

Acceptance Criteria:

- Admin เพิ่ม test case ได้
- Public test case แสดง expected ได้
- Hidden test case ซ่อน expected ได้
- Passed ทุก test case จึงผ่าน
- คำนวณ score จาก weight ได้

---

### Sprint 5: Challenge Runtime Integration

**เป้าหมาย:** เชื่อม runtime กับ challenge/submission/XP/badge

งานที่ต้องทำ:

- เพิ่ม setting runtime ใน admin challenge form
- เพิ่ม Run Code / Submit for Test ใน challenge page
- Submit runtime สร้าง challenge_submission
- เมื่อ test cases ผ่าน อัปเดต submission เป็น passed
- ให้ XP ด้วย XpService
- เรียก BadgeService
- เพิ่ม execution history ในหน้า challenge

Acceptance Criteria:

- challenge เดิมยัง rule-based ได้
- challenge runtime run ได้
- submit runtime ได้
- XP ไม่ซ้ำ
- badge ยังทำงาน
- result UX อ่านง่าย

---

### Sprint 6: SQL Playground MVP

**เป้าหมาย:** เพิ่ม SQL playground แบบ SELECT-only

งานที่ต้องทำ:

- สร้าง SQL playground migration/model/controller/service
- เพิ่ม admin SQL playground CRUD
- สร้าง SQL sandbox strategy
- ตรวจ query allow เฉพาะ SELECT
- reject dangerous keywords
- แสดง result table
- บันทึก sql_submissions
- seed SQL playground 5 ข้อ

Acceptance Criteria:

- ผู้เรียนเปิด SQL playground ได้
- SELECT ทำงานได้
- DROP/DELETE/UPDATE ถูก reject
- แสดง result table ได้
- Submit แล้วตรวจ expected result ได้

---

### Sprint 7: Project Submission + Review

**เป้าหมาย:** ให้ผู้เรียนส่ง project และ admin ตรวจได้

งานที่ต้องทำ:

- สร้าง ProjectSubmissionController
- สร้าง ProjectReviewController admin
- สร้าง project submission views
- รองรับ GitHub URL
- รองรับ ZIP upload แบบจำกัดขนาด ถ้าพร้อม
- สร้าง rubric form
- อัปเดต status
- แสดง feedback ให้ผู้เรียน

Acceptance Criteria:

- ผู้เรียนส่ง GitHub URL ได้
- Admin ตรวจและให้คะแนนได้
- Admin approved/rejected/revision_requested ได้
- ผู้เรียนเห็น feedback ได้
- upload ถ้ามี ต้องจำกัด size/type และเก็บนอก public

---

### Sprint 8: Certificate

**เป้าหมาย:** ออก certificate หลังผ่านเงื่อนไข

งานที่ต้องทำ:

- สร้าง CertificateService
- สร้าง CertificateController
- สร้าง AdminCertificateController
- สร้าง verification code
- สร้าง certificate number
- สร้างหน้า certificate HTML
- สร้าง public verify page
- เชื่อมกับ project approved

Acceptance Criteria:

- ผู้เรียนที่ผ่านเงื่อนไขได้ certificate
- certificate number unique
- verification code unique
- หน้า verify ใช้งานได้โดยไม่ login
- revoke certificate ได้

---

### Sprint 9: Monitoring, Rate Limit, Audit Log

**เป้าหมาย:** ทำให้ระบบ runtime พร้อม demo อย่างปลอดภัยขึ้น

งานที่ต้องทำ:

- Admin execution monitor
- Worker heartbeat แบบง่าย
- AuditLogService
- RateLimitService
- suspicious submission report
- dashboard runtime stats
- README sandbox setup
- QA ทั้งระบบ

Acceptance Criteria:

- Admin เห็น queued/running/error/timeout jobs
- rate limit ทำงาน
- audit log ถูกสร้าง
- worker error ไม่ทำให้เว็บล่ม
- README มีคำสั่ง build/run worker

---

## 18. Acceptance Criteria ราย Feature

### 18.1 Runtime Execution

- กด Run แล้วสร้าง execution job
- worker รับ job แล้วรันใน sandbox
- แสดง stdout/stderr/exit code/duration
- timeout ทำงาน
- memory limit ทำงานในระดับที่ตรวจได้
- output ถูกจำกัดขนาด
- ไม่รัน code ใน web process

### 18.2 Test Cases

- Admin เพิ่ม/แก้/ลบ test case ได้
- test case มี public/hidden
- comparison mode ทำงาน
- result แยกแต่ละ test case
- hidden case ไม่เผย expected output

### 18.3 SQL Playground

- ใช้ SELECT ได้
- query อันตรายถูก reject
- จำกัด rows
- มี expected result check
- เก็บ submission history

### 18.4 Project Submission

- ผู้เรียนส่ง project ได้
- Admin review ได้
- Rubric score ถูกบันทึก
- Feedback แสดงให้ผู้เรียน
- Project approved ใช้เป็นเงื่อนไข certificate ได้

### 18.5 Certificate

- ออก certificate ได้
- verify public ได้
- revoke ได้
- certificate ของผู้เรียนอื่นเข้าถึงไม่ได้ถ้าไม่ใช่ public verify

---

## 19. Definition of Done ของ Phase 3

Phase 3 เสร็จเมื่อครบทั้งหมดนี้:

### Functional

- Runtime execution ใช้งานได้
- Worker ทำงานได้
- Docker sandbox ใช้งานได้ใน local/dev
- Test cases ทำงาน
- SQL Playground MVP ทำงาน
- Project Submission ทำงาน
- Project Review ทำงาน
- Certificate ทำงาน
- Admin monitor ทำงาน

### Security

- ไม่มี eval/include code ผู้เรียนใน app หลัก
- sandbox disabled by default
- runtime ต้องเปิดด้วย env
- network off by default
- timeout/memory/output limits มีจริง
- stdout/stderr/code escape ทุกจุด
- rate limit มีขั้นต่ำ
- audit log มี event สำคัญ

### Content

- Runtime challenge อย่างน้อย 10 ข้อ
- SQL Playground อย่างน้อย 5 ข้อ
- Project template อย่างน้อย 3 แบบ
- Certificate template อย่างน้อย 1 แบบ

### Documentation

- README อัปเดต migration/seeder Phase 3
- README มีคำสั่ง build sandbox image
- README มีคำสั่ง run worker
- docs มี security checklist
- docs มี troubleshooting

---

## 20. Risk และวิธีลดความเสี่ยง

| Risk | ผลกระทบ | วิธีลดความเสี่ยง |
|---|---|---|
| Sandbox ไม่ปลอดภัยพอ | เสี่ยงสูงต่อ server | ปิด by default, ใช้ Docker, no network, limit resource |
| Worker ค้าง | job ไม่จบ | timeout, stale job recovery, monitor |
| Infinite loop | CPU เต็ม | timeout + CPU limit |
| Output ใหญ่มาก | DB/storage โต | max output bytes + truncate |
| ผู้เรียน spam run | server หนัก | rate limit |
| Docker ใช้ยากบน MAMP | dev setup ยาก | แยก mock mode กับ docker mode |
| SQL injection ใน playground | DB เสี่ยง | sandbox DB, SELECT-only, reject dangerous keyword |
| ZIP upload เสี่ยง | malware/path traversal | เก็บนอก public, จำกัด size/type, ไม่ extract อัตโนมัติใน Phase 3 |
| Certificate ถูกปลอม | ความน่าเชื่อถือลด | verification code unique + public verify |

---

## 21. Priority Matrix

### Must Have

- execution_jobs/results schema
- runtime_profiles
- Docker sandbox prototype
- execution worker
- run code endpoint
- test cases
- runtime challenge integration
- admin execution monitor
- security limits
- README setup

### Should Have

- SQL Playground SELECT-only
- project submission GitHub URL
- project review rubric
- certificate HTML + verify URL
- rate limit
- audit log

### Could Have

- ZIP upload
- PDF certificate
- worker heartbeat dashboard
- hidden test cases
- runtime analytics chart

### Won’t Have in Phase 3

- AI Tutor
- Kubernetes scaling
- multi-language execution
- collaborative coding
- marketplace
- auto-grade full project

---

## 22. GitHub Branch และ Commit Plan

### Branch

```bash
git checkout -b phase3-secure-code-execution
```

### Commit แนะนำ

1. `docs: add phase 3 secure code execution plan`
2. `db: add phase 3 runtime execution schema`
3. `feat: add execution queue and job models`
4. `feat: add execution worker with mock runner`
5. `feat: add docker php sandbox adapter`
6. `feat: add challenge runtime test cases`
7. `feat: integrate runtime execution with challenges`
8. `feat: add sql playground mvp`
9. `feat: add project submissions and reviews`
10. `feat: add certificate issuing and verification`
11. `feat: add runtime monitoring rate limit and audit logs`
12. `docs: update readme for phase 3 sandbox setup`

---

## 23. README ที่ควรอัปเดตหลังทำ Phase 3

เพิ่มหัวข้อ:

```markdown
## สิ่งที่เพิ่มใน Phase 3

- Secure PHP code execution ผ่าน Docker sandbox
- Execution queue และ worker
- Runtime test cases
- SQL Playground แบบ SELECT-only
- Project submission และ admin review
- Certificate พร้อม verification code
- Admin runtime monitor

## Build Sandbox Image

```bash
docker build -t php-mastery-sandbox-php:8.3 docker/sandbox/php
```

## Run Worker

```bash
php bin/workers/execution_worker.php
```

## Environment

```env
SANDBOX_ENABLED=true
SANDBOX_DRIVER=docker
```
```

---

## 24. Checklist ก่อนเริ่ม Phase 3

- [ ] Phase 1 migration run ได้
- [ ] Phase 2 migration run ได้
- [ ] phase1_seed.php run ได้
- [ ] phase2_seed.php run ได้
- [ ] Login admin/student ได้
- [ ] Rule-based challenge ยังทำงาน
- [ ] XP/badge ทำงาน
- [ ] Docker ติดตั้งบนเครื่อง dev
- [ ] ตกลงว่าจะใช้ Docker Desktop หรือ Linux server
- [ ] สร้าง branch Phase 3
- [ ] เพิ่มไฟล์เอกสารนี้ใน `docs/`
- [ ] กำหนด runtime challenges ชุดแรก
- [ ] กำหนด SQL playground dataset ชุดแรก
- [ ] สำรองฐานข้อมูลก่อน migration 003

---

## 25. สรุป Phase 3

Phase 3 คือระยะที่สำคัญและเสี่ยงที่สุดของ PHP Mastery Quest เพราะระบบจะเริ่มรัน code จริงของผู้เรียน จากเดิม Phase 2 ที่ตรวจแบบ rule-based และปลอดภัยกว่า

หลักการสำคัญคือ:

> อย่าเพิ่มปุ่ม Run Code แบบตรง ๆ แต่ต้องสร้างระบบ Secure Execution Pipeline

Pipeline ที่ต้องมีคือ:

```text
Web App → Execution Job → Worker → Docker Sandbox → Result → Test Case Evaluation → XP/Badge/Progress
```

เมื่อ Phase 3 เสร็จ ระบบจะยกระดับจากเว็บฝึกเขียนแบบตรวจ keyword ไปเป็นแพลตฟอร์ม Interactive Coding จริง ผู้เรียนสามารถลองรันโค้ด เห็น output ตรวจ test cases ฝึก SQL ส่ง project และได้รับ certificate ได้

แต่ต้องจำไว้ว่า Sandbox ไม่ใช่ฟีเจอร์ตกแต่ง เป็นแกนความปลอดภัยของระบบทั้งหมด ดังนั้น Phase 3 ต้องทำแบบค่อยเป็นค่อยไป เริ่มจาก local/dev, ปิด by default, ใช้ Docker, จำกัด resource, มี queue, มี monitor และมี audit log ก่อนนำไปใช้งานจริงกับผู้เรียนจำนวนมาก
