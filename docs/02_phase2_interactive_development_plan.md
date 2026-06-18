# แผนพัฒนา Phase 2: Interactive Learning สำหรับ PHP Mastery Quest / PHP Code Academy

> ไฟล์แนะนำสำหรับวางใน GitHub: `docs/02_phase2_interactive_development_plan.md`  
> สถานะเอกสาร: Draft สำหรับใช้วางแผนพัฒนา Phase 2  
> โฟกัส: Code Editor, Challenge, Submission, Hint, Badge, Animation เบื้องต้น, Leaderboard และ Admin CMS ที่เกี่ยวข้อง  
> ข้อจำกัดสำคัญ: Phase 2 **ยังไม่รัน PHP code จริงบน server** และยังไม่ทำ Docker Sandbox / AI Tutor / SQL Playground เต็มรูปแบบ

---

## 1. บทนำ

Phase 1 ของโปรเจกต์ PHP Mastery Quest ได้สร้างแกนระบบเรียนรู้พื้นฐานแล้ว ได้แก่ authentication, dashboard, learning path, lesson reader, quiz, progress tracking, XP log, admin CMS, schema ฐานข้อมูล และ seed content สำหรับ PHP Beginner

Phase 2 มีเป้าหมายเพื่อเปลี่ยนระบบจาก “อ่านบทเรียน + ทำ Quiz” ให้กลายเป็น “เรียนแล้วฝึกเขียนโค้ดในเว็บ” โดยเพิ่มส่วน Interactive ที่ปลอดภัยและควบคุมขอบเขตได้ก่อน ได้แก่

- Code Editor สำหรับฝึกเขียนโค้ด
- Challenge หลังบทเรียน
- ระบบส่งคำตอบและบันทึก submission
- ระบบตรวจคำตอบแบบไม่รัน PHP จริง
- Hint ทีละขั้น
- Badge เบื้องต้น
- Animation Learning Block เบื้องต้น
- Leaderboard แบบจำกัด scope
- Admin CMS สำหรับสร้าง challenge, hint, badge และ animation block
- Report เพิ่มเติมเพื่อดูว่าโจทย์ไหนยาก ผู้เรียนติดตรงไหน

Phase 2 ต้องไม่รีบทำระบบ Run Code จริง เพราะการรัน code ที่ผู้เรียนส่งมาเป็นความเสี่ยงด้านความปลอดภัยสูง ควรเก็บไว้ Phase 3 ซึ่งจะออกแบบ Docker Sandbox แยกต่างหาก

---

## 2. วิเคราะห์สถานะ GitHub ปัจจุบัน

จากโครงสร้างที่มีใน repository ปัจจุบัน ระบบ Phase 1 มีฐานที่พร้อมต่อยอด Phase 2 แล้ว โดยเฉพาะ:

### 2.1 สิ่งที่มีแล้ว

- `README.md` ระบุว่า Phase 1 ทำระบบหลักแล้ว เช่น login, register, logout, role, dashboard, learning path, lesson reader, quiz, progress, XP log, admin CMS, schema และ seed content
- `composer.json` ใช้ autoload แบบ PSR-4 ที่ namespace `App\` ชี้ไปยังโฟลเดอร์ `app/`
- `routes/web.php` มี route หลักสำหรับหน้าเรียน, dashboard, course, lesson, quiz และ admin CMS
- `database/migrations/001_create_phase1_schema.sql` มี schema หลัก 12 ตาราง เช่น users, roles, courses, modules, lessons, quizzes, quiz_questions, quiz_choices, quiz_attempts, quiz_answers, lesson_progress และ xp_logs
- `ProgressService` มี logic สำหรับ mark in progress, complete lesson, progress summary, latest lesson และ next lesson
- `XpService` มี logic สำหรับให้ XP แบบไม่ให้ซ้ำใน event เดิม ผ่าน `addOnce()`
- `phase1_seed.php` มี seed content สำหรับ PHP Beginner 11 บท พร้อม quiz เบื้องต้น

### 2.2 ความหมายต่อ Phase 2

Phase 2 ไม่ต้องเริ่มจากศูนย์ แต่ควรต่อยอดจาก 5 ส่วนนี้:

1. **Lesson เดิม** → เพิ่ม practice/challenge ต่อท้ายบทเรียน
2. **Quiz เดิม** → ใช้เป็น gating หรือ prerequisite ก่อนทำ challenge
3. **Progress เดิม** → เพิ่มสถานะฝึกปฏิบัติ เช่น challenge passed
4. **XP เดิม** → เพิ่ม event type สำหรับ challenge, badge และ streak
5. **Admin CMS เดิม** → เพิ่มหน้าจัดการ challenge, hint, animation และ badge

### 2.3 สิ่งที่ยังไม่มีและต้องเพิ่มใน Phase 2

- ตาราง `challenges`
- ตาราง `challenge_hints`
- ตาราง `challenge_required_keywords`
- ตาราง `challenge_forbidden_keywords`
- ตาราง `challenge_submissions`
- ตาราง `challenge_submission_checks`
- ตาราง `badges`
- ตาราง `user_badges`
- ตาราง `lesson_animation_blocks`
- หน้า Code Editor
- หน้า Challenge List
- หน้า Challenge Detail
- หน้า Submission Result
- หน้า My Badges
- หน้า Leaderboard
- Admin CMS สำหรับ Challenge
- Admin CMS สำหรับ Badge
- Admin CMS สำหรับ Animation Block
- Service สำหรับตรวจคำตอบแบบ rule-based
- Service สำหรับให้ badge
- Service สำหรับคำนวณ leaderboard

---

## 3. เป้าหมายของ Phase 2

### 3.1 เป้าหมายหลัก

ทำให้ผู้เรียนสามารถฝึกเขียนโค้ดหลังเรียนแต่ละบทได้จริง โดยยังไม่ต้องรันโค้ด PHP บน server

เมื่อจบ Phase 2 ผู้เรียนควรสามารถ:

- เปิดบทเรียนแล้วเห็นปุ่ม “ฝึกเขียนโค้ด”
- เข้าหน้า Challenge ได้
- อ่านโจทย์และ starter code
- เขียนหรือแก้ code ใน editor บนเว็บ
- กดส่งคำตอบ
- ระบบตรวจคำตอบแบบ rule-based
- ระบบแจ้ง feedback ว่าผ่าน/ไม่ผ่าน
- ขอ hint ได้ทีละขั้น
- ดูประวัติ submission ของตนเอง
- ได้ XP เมื่อผ่าน challenge
- ได้ badge เมื่อผ่านเงื่อนไข
- ดูอันดับ leaderboard เบื้องต้นได้
- เห็น animation block บางบทเรียนเพื่อช่วยเข้าใจแนวคิด

### 3.2 นิยามความสำเร็จของ Phase 2

Phase 2 ถือว่าสำเร็จเมื่อ:

1. มี challenge อย่างน้อย 20 โจทย์ ผูกกับบทเรียน PHP Beginner เดิม
2. ผู้เรียนส่ง code answer ได้อย่างน้อย 1 submission ต่อ challenge
3. ระบบตรวจคำตอบได้โดยไม่ใช้ `eval()` และไม่ execute PHP code จริง
4. ระบบบันทึก submission history ได้
5. ระบบให้ XP จาก challenge ผ่าน `XpService::addOnce()` ได้
6. ระบบมอบ badge อย่างน้อย 5 แบบได้
7. Admin สร้าง/แก้ไข/ปิดเผยแพร่ challenge ได้
8. Admin เพิ่ม hint และ required keyword ได้
9. มี animation block อย่างน้อย 5 จุดในบทเรียนสำคัญ
10. มี leaderboard เบื้องต้นจาก XP และ challenge pass count
11. ทุก form ที่เปลี่ยนข้อมูลมี CSRF
12. Output ที่แสดง code ของผู้เรียนต้อง escape ทุกครั้ง

---

## 4. Scope ของ Phase 2

### 4.1 สิ่งที่อยู่ในขอบเขต

| หมวด | รายละเอียด |
|---|---|
| Code Editor | ฝัง editor บนหน้าเว็บสำหรับเขียน code |
| Challenge | โจทย์ฝึกเขียนโค้ดผูกกับ lesson |
| Starter Code | โค้ดตั้งต้นให้ผู้เรียนแก้ |
| Rule-based Checking | ตรวจคำตอบด้วย expected text, required keyword, forbidden keyword และ manual pattern |
| Submission | บันทึก code, status, score, feedback |
| Hint | คำใบ้เป็น step |
| XP | เพิ่ม event จาก challenge started / challenge passed |
| Badge | มอบ badge ตามเงื่อนไขพื้นฐาน |
| Animation Block | แสดง animation/explanation block ในบทเรียน |
| Leaderboard | จัดอันดับจาก XP และจำนวน challenge ที่ผ่าน |
| Admin CMS | จัดการ challenge, hint, badge, animation block |
| Report | ดู challenge pass rate และ submission count |

### 4.2 สิ่งที่ยังไม่ทำใน Phase 2

| ยังไม่ทำ | เหตุผล |
|---|---|
| Run PHP จริงบน server | เสี่ยงด้านความปลอดภัย ต้องรอ Docker Sandbox Phase 3 |
| Docker Sandbox | ซับซ้อน ต้องออกแบบ resource limit, timeout, network isolation |
| SQL Playground | ควรทำหลังมี sandbox หรือ isolated database |
| AI Tutor | ต้องมีข้อมูล submission มากพอและต้องควบคุมไม่ให้เฉลยทันที |
| Forum / Community | ไม่ใช่แกนของ interactive learning |
| Certificate | ควรรอ Capstone หรือ Project Submission |
| Project Review | ควรอยู่ Phase 3 หรือ 4 |
| Payment / Subscription | ยังไม่เกี่ยวกับ product validation |
| Multi-course เต็มรูปแบบ | Phase 2 ควรโฟกัส PHP Beginner ก่อน |

---

## 5. แนวคิดสำคัญของ Phase 2

Phase 2 ต้องตอบคำถามนี้:

> “ผู้เรียนได้ฝึกคิดและเขียนโค้ดเองมากขึ้นหรือไม่ โดยที่ระบบยังปลอดภัยและพัฒนาได้ทัน?”

ดังนั้น Challenge ใน Phase 2 ควรเป็น **Simulation-based / Rule-based Challenge** ไม่ใช่ runtime-based challenge

ตัวอย่าง:

### 5.1 ตรวจด้วย expected text

โจทย์: ให้เขียนคำสั่งแสดงข้อความ `Hello PHP`

ระบบตรวจว่า code มีข้อความ `Hello PHP` หรือไม่ และมีคำสั่ง `echo` หรือ `print` หรือไม่

### 5.2 ตรวจด้วย required keyword

โจทย์: ให้ใช้ `foreach` วน array

ระบบตรวจว่า code มีคำว่า `foreach` และมีตัวแปร array อย่างน้อยหนึ่งตัว

### 5.3 ตรวจด้วย forbidden keyword

โจทย์: ให้แก้ปัญหาโดยใช้ `if else` ห้ามใช้ `switch`

ระบบตรวจว่า code ไม่มีคำว่า `switch`

### 5.4 ตรวจด้วย pattern

โจทย์: ให้สร้าง function ชื่อ `calculateGrade`

ระบบตรวจว่า code มี `function calculateGrade` และมี `return`

### 5.5 ตรวจแบบ manual review flag

โจทย์ที่ซับซ้อนเกิน rule-based ให้ระบบรับ submission แล้วขึ้นสถานะ `needs_review` เพื่อให้ admin ตรวจภายหลัง

---

## 6. User Journey ของ Phase 2

### 6.1 ผู้เรียนทำ Challenge หลังบทเรียน

1. ผู้เรียน login
2. เข้าหน้า Dashboard
3. กด Continue Learning
4. เปิดบทเรียน
5. อ่านบทเรียนและดู animation block
6. ทำ quiz ให้ผ่าน หรือถ้าผ่านแล้วเห็นปุ่ม “ฝึกเขียนโค้ด”
7. เปิด Challenge
8. อ่านโจทย์, เป้าหมาย, เงื่อนไข, starter code
9. แก้ code ใน editor
10. กด Submit
11. ระบบตรวจคำตอบแบบ rule-based
12. ถ้าผ่าน:
    - บันทึก submission เป็น `passed`
    - เพิ่ม XP
    - ตรวจ badge
    - แสดง feedback
13. ถ้าไม่ผ่าน:
    - บันทึก submission เป็น `failed`
    - แสดง feedback
    - เสนอ hint ถัดไป
14. ผู้เรียนสามารถกลับมาแก้และส่งใหม่ได้

### 6.2 ผู้เรียนขอ Hint

1. ผู้เรียนอยู่หน้า Challenge
2. กดปุ่ม “ขอคำใบ้”
3. ระบบแสดง hint ลำดับที่ 1
4. ถ้ายังไม่ผ่าน สามารถกด hint ถัดไป
5. ระบบบันทึกจำนวน hint ที่ใช้
6. ถ้าผู้เรียนใช้ hint เยอะ อาจได้ XP น้อยลงในอนาคต แต่ Phase 2 อาจยังไม่ต้องหัก XP

### 6.3 ผู้เรียนดู Badge

1. ผู้เรียนเข้าเมนู My Badges
2. เห็น badge ที่ได้รับแล้ว
3. เห็น badge ที่ยังล็อกอยู่แบบไม่เปิดเผยคำตอบทั้งหมด
4. กดดูเงื่อนไข badge เบื้องต้น เช่น “ผ่าน challenge 5 ข้อแรก”

### 6.4 ผู้เรียนดู Leaderboard

1. ผู้เรียนเข้าเมนู Leaderboard
2. เห็นอันดับจาก XP รวม
3. เห็นจำนวน challenge ที่ผ่าน
4. เห็น level และ badge count
5. ระบบควรแสดงเฉพาะผู้ใช้ active และ role student

### 6.5 Admin สร้าง Challenge

1. Admin login
2. เข้า `/admin/challenges`
3. กด Create Challenge
4. เลือก lesson ที่ต้องการผูก
5. ใส่ title, description, instructions
6. ใส่ starter code
7. ใส่ expected output หรือ expected text
8. กำหนด required keywords
9. กำหนด forbidden keywords
10. เพิ่ม hint 1–3 ขั้น
11. กำหนด XP reward
12. กำหนด difficulty
13. กด publish
14. ผู้เรียนเห็น challenge ในบทเรียน

### 6.6 Admin ดู Report

1. Admin เข้า `/admin/reports/challenges`
2. เห็นโจทย์ทั้งหมด
3. เห็นจำนวน submission
4. เห็น pass rate
5. เห็น average attempts
6. เห็นโจทย์ที่ผู้เรียน failed มากที่สุด
7. ใช้ข้อมูลไปปรับโจทย์หรือเพิ่ม hint

---

## 7. Database Design สำหรับ Phase 2

ให้เพิ่ม migration ใหม่ เช่น:

```text
database/migrations/002_create_phase2_interactive_schema.sql
```

### 7.1 ตาราง `challenges`

ใช้เก็บโจทย์ฝึกเขียน code ที่ผูกกับบทเรียน

```sql
CREATE TABLE IF NOT EXISTS challenges (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  lesson_id INT UNSIGNED NOT NULL,
  title VARCHAR(255) NOT NULL,
  slug VARCHAR(255) NOT NULL UNIQUE,
  description TEXT NOT NULL,
  instructions LONGTEXT NULL,
  starter_code LONGTEXT NULL,
  expected_text TEXT NULL,
  expected_output TEXT NULL,
  checking_mode ENUM('text','keyword','pattern','manual') NOT NULL DEFAULT 'keyword',
  difficulty ENUM('beginner','intermediate','advanced') NOT NULL DEFAULT 'beginner',
  xp_reward INT NOT NULL DEFAULT 40,
  sort_order INT NOT NULL DEFAULT 0,
  is_published TINYINT(1) NOT NULL DEFAULT 0,
  created_at DATETIME NOT NULL,
  updated_at DATETIME NOT NULL,
  CONSTRAINT fk_challenges_lesson FOREIGN KEY (lesson_id) REFERENCES lessons(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

### 7.2 ตาราง `challenge_required_keywords`

ใช้บังคับว่าคำตอบต้องมี keyword บางคำ เช่น `echo`, `foreach`, `function`

```sql
CREATE TABLE IF NOT EXISTS challenge_required_keywords (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  challenge_id INT UNSIGNED NOT NULL,
  keyword VARCHAR(190) NOT NULL,
  message VARCHAR(255) NULL,
  sort_order INT NOT NULL DEFAULT 0,
  created_at DATETIME NOT NULL,
  updated_at DATETIME NOT NULL,
  CONSTRAINT fk_required_keywords_challenge FOREIGN KEY (challenge_id) REFERENCES challenges(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

### 7.3 ตาราง `challenge_forbidden_keywords`

ใช้ห้าม keyword บางคำ เช่น `eval`, `exec`, `shell_exec`, `system`

```sql
CREATE TABLE IF NOT EXISTS challenge_forbidden_keywords (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  challenge_id INT UNSIGNED NOT NULL,
  keyword VARCHAR(190) NOT NULL,
  message VARCHAR(255) NULL,
  sort_order INT NOT NULL DEFAULT 0,
  created_at DATETIME NOT NULL,
  updated_at DATETIME NOT NULL,
  CONSTRAINT fk_forbidden_keywords_challenge FOREIGN KEY (challenge_id) REFERENCES challenges(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

### 7.4 ตาราง `challenge_hints`

ใช้เก็บ hint ทีละขั้น

```sql
CREATE TABLE IF NOT EXISTS challenge_hints (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  challenge_id INT UNSIGNED NOT NULL,
  hint_text TEXT NOT NULL,
  sort_order INT NOT NULL DEFAULT 0,
  created_at DATETIME NOT NULL,
  updated_at DATETIME NOT NULL,
  CONSTRAINT fk_hints_challenge FOREIGN KEY (challenge_id) REFERENCES challenges(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

### 7.5 ตาราง `challenge_submissions`

ใช้บันทึก code ที่ผู้เรียนส่ง

```sql
CREATE TABLE IF NOT EXISTS challenge_submissions (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id INT UNSIGNED NOT NULL,
  challenge_id INT UNSIGNED NOT NULL,
  code LONGTEXT NOT NULL,
  status ENUM('passed','failed','needs_review') NOT NULL DEFAULT 'failed',
  score DECIMAL(5,2) NOT NULL DEFAULT 0,
  feedback TEXT NULL,
  hints_used INT NOT NULL DEFAULT 0,
  submitted_at DATETIME NOT NULL,
  CONSTRAINT fk_submissions_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  CONSTRAINT fk_submissions_challenge FOREIGN KEY (challenge_id) REFERENCES challenges(id) ON DELETE CASCADE,
  INDEX idx_submission_user_challenge (user_id, challenge_id),
  INDEX idx_submission_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

### 7.6 ตาราง `challenge_submission_checks`

ใช้เก็บผลตรวจแต่ละข้อ เช่น ผ่าน required keyword หรือไม่

```sql
CREATE TABLE IF NOT EXISTS challenge_submission_checks (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  submission_id INT UNSIGNED NOT NULL,
  check_type ENUM('expected_text','required_keyword','forbidden_keyword','pattern') NOT NULL,
  check_value VARCHAR(255) NOT NULL,
  passed TINYINT(1) NOT NULL DEFAULT 0,
  message VARCHAR(255) NULL,
  created_at DATETIME NOT NULL,
  CONSTRAINT fk_submission_checks_submission FOREIGN KEY (submission_id) REFERENCES challenge_submissions(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

### 7.7 ตาราง `badges`

ใช้เก็บ badge ที่ระบบมี

```sql
CREATE TABLE IF NOT EXISTS badges (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  code VARCHAR(100) NOT NULL UNIQUE,
  name VARCHAR(255) NOT NULL,
  description TEXT NULL,
  icon VARCHAR(255) NULL,
  rule_type ENUM('challenge_count','lesson_count','module_complete','xp_total','manual') NOT NULL DEFAULT 'manual',
  rule_value INT UNSIGNED NULL,
  sort_order INT NOT NULL DEFAULT 0,
  is_active TINYINT(1) NOT NULL DEFAULT 1,
  created_at DATETIME NOT NULL,
  updated_at DATETIME NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

### 7.8 ตาราง `user_badges`

ใช้เก็บ badge ที่ผู้เรียนได้รับ

```sql
CREATE TABLE IF NOT EXISTS user_badges (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id INT UNSIGNED NOT NULL,
  badge_id INT UNSIGNED NOT NULL,
  awarded_at DATETIME NOT NULL,
  UNIQUE KEY uq_user_badge (user_id, badge_id),
  CONSTRAINT fk_user_badges_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  CONSTRAINT fk_user_badges_badge FOREIGN KEY (badge_id) REFERENCES badges(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

### 7.9 ตาราง `lesson_animation_blocks`

ใช้เก็บ animation/explanation block ที่ผูกกับบทเรียน

```sql
CREATE TABLE IF NOT EXISTS lesson_animation_blocks (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  lesson_id INT UNSIGNED NOT NULL,
  title VARCHAR(255) NOT NULL,
  block_type ENUM('html','lottie','diagram','steps') NOT NULL DEFAULT 'html',
  content LONGTEXT NOT NULL,
  sort_order INT NOT NULL DEFAULT 0,
  is_published TINYINT(1) NOT NULL DEFAULT 1,
  created_at DATETIME NOT NULL,
  updated_at DATETIME NOT NULL,
  CONSTRAINT fk_animation_blocks_lesson FOREIGN KEY (lesson_id) REFERENCES lessons(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

---

## 8. File Structure ที่ควรเพิ่มใน Phase 2

ต่อจากโครงสร้าง Phase 1 ให้เพิ่มไฟล์ประมาณนี้:

```text
app/
├── Controllers/
│   ├── ChallengeController.php
│   ├── BadgeController.php
│   ├── LeaderboardController.php
│   └── Admin/
│       ├── ChallengeController.php
│       ├── BadgeController.php
│       └── AnimationBlockController.php
│
├── Models/
│   ├── Challenge.php
│   ├── ChallengeHint.php
│   ├── ChallengeSubmission.php
│   ├── Badge.php
│   ├── UserBadge.php
│   └── LessonAnimationBlock.php
│
├── Services/
│   ├── ChallengeCheckerService.php
│   ├── ChallengeSubmissionService.php
│   ├── BadgeService.php
│   └── LeaderboardService.php
│
└── Views/
    ├── challenges/
    │   ├── index.php
    │   ├── show.php
    │   ├── result.php
    │   └── history.php
    │
    ├── badges/
    │   └── index.php
    │
    ├── leaderboard/
    │   └── index.php
    │
    └── admin/
        ├── challenges/
        │   ├── index.php
        │   ├── form.php
        │   ├── hints.php
        │   └── submissions.php
        │
        ├── badges/
        │   ├── index.php
        │   └── form.php
        │
        └── animations/
            ├── index.php
            └── form.php

public/
└── assets/
    ├── js/
    │   ├── challenge-editor.js
    │   ├── animation-blocks.js
    │   └── leaderboard.js
    └── css/
        └── phase2.css

database/
├── migrations/
│   └── 002_create_phase2_interactive_schema.sql
└── seeders/
    └── phase2_seed.php
```

---

## 9. Routes ที่ควรเพิ่ม

เพิ่มใน `routes/web.php`

```php
use App\Controllers\ChallengeController;
use App\Controllers\BadgeController;
use App\Controllers\LeaderboardController;
use App\Controllers\Admin\ChallengeController as AdminChallengeController;
use App\Controllers\Admin\BadgeController as AdminBadgeController;
use App\Controllers\Admin\AnimationBlockController;

// Student challenge routes
$router->get('/challenges', [ChallengeController::class, 'index'], ['auth']);
$router->get('/challenges/{slug}', [ChallengeController::class, 'show'], ['auth']);
$router->post('/challenges/{id}/submit', [ChallengeController::class, 'submit'], ['auth']);
$router->get('/challenges/{id}/history', [ChallengeController::class, 'history'], ['auth']);
$router->post('/challenges/{id}/hint', [ChallengeController::class, 'hint'], ['auth']);

// Lesson practice shortcut
$router->get('/lessons/{id}/practice', [ChallengeController::class, 'lessonPractice'], ['auth']);

// Badges and leaderboard
$router->get('/badges', [BadgeController::class, 'index'], ['auth']);
$router->get('/leaderboard', [LeaderboardController::class, 'index'], ['auth']);

// Admin challenge routes
$router->get('/admin/challenges', [AdminChallengeController::class, 'index'], ['admin']);
$router->get('/admin/challenges/create', [AdminChallengeController::class, 'create'], ['admin']);
$router->post('/admin/challenges', [AdminChallengeController::class, 'store'], ['admin']);
$router->get('/admin/challenges/{id}/edit', [AdminChallengeController::class, 'edit'], ['admin']);
$router->post('/admin/challenges/{id}', [AdminChallengeController::class, 'update'], ['admin']);
$router->post('/admin/challenges/{id}/delete', [AdminChallengeController::class, 'delete'], ['admin']);
$router->get('/admin/challenges/{id}/submissions', [AdminChallengeController::class, 'submissions'], ['admin']);
$router->post('/admin/challenges/{id}/hints', [AdminChallengeController::class, 'storeHint'], ['admin']);
$router->post('/admin/challenge-hints/{id}/delete', [AdminChallengeController::class, 'deleteHint'], ['admin']);

// Admin badge routes
$router->get('/admin/badges', [AdminBadgeController::class, 'index'], ['admin']);
$router->post('/admin/badges', [AdminBadgeController::class, 'store'], ['admin']);
$router->get('/admin/badges/{id}/edit', [AdminBadgeController::class, 'edit'], ['admin']);
$router->post('/admin/badges/{id}', [AdminBadgeController::class, 'update'], ['admin']);
$router->post('/admin/badges/{id}/delete', [AdminBadgeController::class, 'delete'], ['admin']);

// Admin animation block routes
$router->get('/admin/animations', [AnimationBlockController::class, 'index'], ['admin']);
$router->post('/admin/animations', [AnimationBlockController::class, 'store'], ['admin']);
$router->get('/admin/animations/{id}/edit', [AnimationBlockController::class, 'edit'], ['admin']);
$router->post('/admin/animations/{id}', [AnimationBlockController::class, 'update'], ['admin']);
$router->post('/admin/animations/{id}/delete', [AnimationBlockController::class, 'delete'], ['admin']);
```

---

## 10. Module รายละเอียด

## 10.1 Module: Code Editor

### เป้าหมาย

ให้ผู้เรียนเขียนหรือแก้ code ในหน้าเว็บได้ โดยยังไม่ execute code จริง

### UI ที่ต้องมี

- กล่องโจทย์
- เงื่อนไขการผ่าน
- starter code
- editor
- ปุ่ม Reset
- ปุ่ม Submit
- ปุ่ม Hint
- แถบ feedback
- submission history ล่าสุด 5 รายการ

### Technical Note

ใน Phase 2 ใช้ editor ฝั่ง frontend เท่านั้น เช่น textarea ที่ปรับปรุงด้วย JavaScript หรือใช้ CodeMirror ภายหลังได้ แต่ควรทำให้ระบบยังใช้งานได้แม้ JavaScript มีปัญหา โดยมี `<textarea name="code">` เป็น fallback

### Validation

- code ต้องไม่ว่าง
- จำกัดความยาว เช่น ไม่เกิน 20,000 characters
- ห้ามส่ง binary หรือ control characters ที่ผิดปกติ
- escape code ทุกครั้งที่แสดงผล
- ห้าม execute code

---

## 10.2 Module: Challenge

### เป้าหมาย

ทำให้ทุก lesson สามารถมี challenge อย่างน้อย 1 ข้อ

### Field สำคัญ

- lesson_id
- title
- slug
- description
- instructions
- starter_code
- expected_text
- expected_output
- checking_mode
- difficulty
- xp_reward
- sort_order
- is_published

### Challenge Type ใน Phase 2

| Type | รายละเอียด | ตัวอย่าง |
|---|---|---|
| text | ตรวจว่ามีข้อความที่คาดหวัง | ต้องมี `Hello PHP` |
| keyword | ตรวจ keyword ที่ต้องมี/ต้องห้าม | ต้องมี `foreach` |
| pattern | ตรวจ pattern แบบง่าย | ต้องมี `function calculateGrade` |
| manual | ระบบรับงานไว้และให้ admin ตรวจ | งานที่ซับซ้อน |

---

## 10.3 Module: Challenge Checker

### Service ที่ควรสร้าง

```text
app/Services/ChallengeCheckerService.php
```

### Method ที่ควรมี

```php
public function check(array $challenge, string $code): array
```

ผลลัพธ์ควรเป็น:

```php
[
    'passed' => true,
    'score' => 100,
    'feedback' => 'ผ่านครบทุกเงื่อนไข',
    'checks' => [
        [
            'type' => 'required_keyword',
            'value' => 'echo',
            'passed' => true,
            'message' => 'พบคำสั่ง echo แล้ว'
        ]
    ]
]
```

### Logic เบื้องต้น

1. ตรวจ code ไม่ว่าง
2. ตรวจ expected_text ถ้ามี
3. ตรวจ required keywords
4. ตรวจ forbidden keywords
5. ตรวจ pattern ถ้าตั้งค่าไว้
6. รวมคะแนน
7. ส่ง feedback กลับ

### ตัวอย่าง scoring

| เงื่อนไข | คะแนน |
|---|---:|
| expected_text ผ่าน | 40 |
| required keywords ผ่านทั้งหมด | 40 |
| ไม่มี forbidden keywords | 20 |
| รวม | 100 |

ถ้า checking_mode เป็น `manual` ให้ status เป็น `needs_review`

---

## 10.4 Module: Submission

### เป้าหมาย

บันทึกคำตอบทุกครั้ง เพื่อให้ผู้เรียนย้อนดูได้ และ admin วิเคราะห์ได้ว่าโจทย์ไหนยาก

### Status

| Status | ความหมาย |
|---|---|
| passed | ผ่านเงื่อนไข |
| failed | ยังไม่ผ่าน |
| needs_review | รอ admin ตรวจ |

### Rule สำคัญ

- ไม่ให้ XP ซ้ำจาก challenge เดิมที่เคยผ่านแล้ว
- อนุญาตให้ส่งซ้ำได้เพื่อฝึก
- เก็บ submission ทุกครั้ง
- แสดงประวัติของผู้เรียนเฉพาะของตัวเอง
- Admin ดู submissions รวมได้

---

## 10.5 Module: Hint System

### เป้าหมาย

ช่วยผู้เรียนโดยไม่เฉลยทันที

### รูปแบบ Hint

ควรมี 3 ระดับ:

1. แนวคิด
2. โครงสร้าง
3. จุดที่ควรแก้

ตัวอย่างโจทย์ `foreach`:

- Hint 1: “ลองคิดว่าข้อมูลหลายรายการควรเก็บใน array”
- Hint 2: “ใช้ foreach เพื่อวนอ่านสมาชิกใน array ทีละตัว”
- Hint 3: “รูปแบบคือ foreach ($items as $item) { ... }”

### Data ที่ควรบันทึก

ใน Phase 2 บันทึก `hints_used` ใน submission ได้ก่อน ยังไม่จำเป็นต้องมีตาราง hint usage แยก

ถ้าจะละเอียดขึ้นในอนาคต เพิ่มตาราง:

```sql
challenge_hint_usages (
  id,
  user_id,
  challenge_id,
  hint_id,
  viewed_at
)
```

---

## 10.6 Module: Badge

### Badge เริ่มต้นที่ควรมี

| Code | ชื่อ | เงื่อนไข |
|---|---|---|
| first_challenge | First Code Step | ผ่าน challenge แรก |
| five_challenges | Practice Starter | ผ่าน challenge 5 ข้อ |
| ten_challenges | PHP Practice Builder | ผ่าน challenge 10 ข้อ |
| form_ready | Form Apprentice | ผ่าน challenge ที่เกี่ยวกับ form ครบ |
| loop_master | Loop Walker | ผ่าน challenge ที่เกี่ยวกับ loop ครบ |
| quiz_and_code | Balanced Learner | ผ่าน quiz และ challenge อย่างน้อย 5 บท |

### Service ที่ควรสร้าง

```text
app/Services/BadgeService.php
```

### Method สำคัญ

```php
public static function evaluateForUser(int $userId): array
```

หน้าที่:

1. นับจำนวน challenge ที่ผ่าน
2. นับจำนวน lesson ที่ completed
3. นับ XP รวม
4. ตรวจ badge rule
5. insert เข้า user_badges ถ้ายังไม่เคยได้
6. return badge ใหม่ที่เพิ่งได้รับ

### UX

หลัง submit challenge แล้วถ้าได้ badge ใหม่ ให้แสดง popup หรือ alert:

> ยินดีด้วย! คุณได้รับ Badge: First Code Step

---

## 10.7 Module: Animation Learning Block

### เป้าหมาย

เพิ่ม visual explanation ให้บทเรียนเข้าใจง่ายขึ้น โดยยังไม่ต้องทำ animation ซับซ้อน

### Block Type

| Type | ใช้ทำอะไร |
|---|---|
| html | HTML/CSS block ธรรมดา |
| steps | แสดง process ทีละขั้น |
| diagram | แสดง diagram ด้วย HTML |
| lottie | รองรับไฟล์ Lottie ในอนาคต |

### จุดที่ควรเริ่มทำ animation

1. Request / Response
2. Variable Memory
3. If / Else Decision
4. Loop
5. Function Input / Output

### ตัวอย่าง content แบบ steps

```json
[
  "ผู้ใช้กรอกฟอร์ม",
  "Browser ส่ง request",
  "PHP รับข้อมูล",
  "Server ประมวลผล",
  "ส่ง HTML กลับไปยัง Browser"
]
```

ใน Phase 2 สามารถเก็บเป็น JSON string ใน field `content` ได้ แล้ว render ด้วย JavaScript

---

## 10.8 Module: Leaderboard

### เป้าหมาย

เพิ่มแรงจูงใจโดยไม่ทำให้ระบบซับซ้อน

### Ranking Criteria

เริ่มจากเรียงตาม:

1. XP รวม
2. จำนวน challenge ที่ผ่าน
3. จำนวน quiz ที่ผ่าน
4. วันที่ active ล่าสุด

### Query ตัวอย่าง

```sql
SELECT
  users.id,
  users.name,
  users.xp,
  users.level,
  COUNT(DISTINCT CASE WHEN challenge_submissions.status = 'passed' THEN challenge_submissions.challenge_id END) AS passed_challenges,
  COUNT(DISTINCT CASE WHEN quiz_attempts.passed = 1 THEN quiz_attempts.quiz_id END) AS passed_quizzes
FROM users
JOIN roles ON roles.id = users.role_id
LEFT JOIN challenge_submissions ON challenge_submissions.user_id = users.id
LEFT JOIN quiz_attempts ON quiz_attempts.user_id = users.id
WHERE roles.name = 'student'
  AND users.status = 'active'
GROUP BY users.id, users.name, users.xp, users.level
ORDER BY users.xp DESC, passed_challenges DESC, passed_quizzes DESC
LIMIT 50;
```

### ข้อควรระวัง

- ห้ามแสดง email ผู้เรียนใน leaderboard
- แสดงเฉพาะชื่อ, level, XP, badge count, challenge count
- ควรมี option ปิด leaderboard ในอนาคต ถ้าใช้กับห้องเรียนจริง

---

## 10.9 Module: Admin Challenge CMS

### หน้าจอที่ต้องมี

| หน้า | รายละเอียด |
|---|---|
| `/admin/challenges` | รายการ challenge |
| `/admin/challenges/create` | สร้าง challenge |
| `/admin/challenges/{id}/edit` | แก้ไข challenge |
| `/admin/challenges/{id}/hints` | จัดการ hint |
| `/admin/challenges/{id}/submissions` | ดูคำตอบผู้เรียน |

### Field ใน form

- Lesson
- Title
- Slug
- Description
- Instructions
- Starter Code
- Expected Text
- Expected Output
- Checking Mode
- Required Keywords
- Forbidden Keywords
- Difficulty
- XP Reward
- Sort Order
- Published

### Admin UX

- ควรมีปุ่ม preview หน้า challenge
- ควรมีปุ่ม duplicate challenge
- ควรแสดง pass rate ถ้ามี submission แล้ว
- ควรเตือนก่อนลบ เพราะจะลบ submission ที่ผูกอยู่ถ้าใช้ cascade

---

## 10.10 Module: Admin Reports เพิ่มเติม

### Report ที่ควรมี

| Report | รายละเอียด |
|---|---|
| Challenge Overview | จำนวน challenge ทั้งหมด published/unpublished |
| Challenge Pass Rate | โจทย์ที่ผ่านง่าย/ยาก |
| Failed Submissions | submissions ที่ failed มากที่สุด |
| Hint Usage | โจทย์ที่ผู้เรียนใช้ hint เยอะ |
| Badge Distribution | badge ไหนมีคนได้มาก/น้อย |
| Active Learners | ผู้เรียน active จาก submission ล่าสุด |

---

## 11. Seed Content สำหรับ Phase 2

ให้สร้าง:

```text
database/seeders/phase2_seed.php
```

### 11.1 Challenge อย่างน้อย 20 ข้อ

แบ่งตามบทเรียนเดิม:

| หมวด | จำนวน Challenge |
|---|---:|
| PHP คืออะไร / echo | 3 |
| variable | 3 |
| data types | 2 |
| operators | 2 |
| if else | 3 |
| switch | 1 |
| loop | 3 |
| array | 2 |
| function | 1 |
| รวม | 20 |

### 11.2 ตัวอย่าง Challenge

#### Challenge 1: Hello PHP

- Lesson: `php-echo-print`
- Title: แสดงข้อความแรกด้วย echo
- Starter Code:

```php
<?php
// เขียนคำสั่ง echo เพื่อแสดงข้อความ Hello PHP
```

- Required Keywords:
  - `echo`
- Expected Text:
  - `Hello PHP`
- XP Reward:
  - 40

#### Challenge 2: ตัวแปรชื่อผู้เรียน

- Lesson: `php-variables`
- Title: สร้างตัวแปร $name
- Starter Code:

```php
<?php
// สร้างตัวแปร $name และกำหนดค่าเป็นชื่อของคุณ
```

- Required Keywords:
  - `$name`
- Forbidden Keywords:
  - `eval`
  - `exec`
  - `shell_exec`
- XP Reward:
  - 40

#### Challenge 3: วน array ด้วย foreach

- Lesson: `php-loops`
- Title: แสดงรายชื่อนักเรียนด้วย foreach
- Starter Code:

```php
<?php
$students = ["Somchai", "Suda", "Mana"];

// ใช้ foreach เพื่อแสดงชื่อทุกคน
```

- Required Keywords:
  - `foreach`
  - `$students`
- Expected Text:
  - `Somchai`
- XP Reward:
  - 50

---

## 12. XP Design สำหรับ Phase 2

ต่อยอดจาก `XpService::addOnce()` ที่มีอยู่แล้ว

### Event Type ใหม่

| Event Type | XP | เงื่อนไข |
|---|---:|---|
| challenge_started | 5 | เปิด challenge ครั้งแรก |
| challenge_passed | ตาม challenge | ผ่าน challenge ครั้งแรก |
| first_challenge_passed | 20 | ผ่าน challenge แรก |
| badge_awarded | 10 | ได้ badge ใหม่ |
| animation_viewed | 2 | ดู animation block ครั้งแรก อาจทำหรือไม่ทำก็ได้ |

### กติกา XP

- ใช้ `addOnce()` เพื่อป้องกัน XP ซ้ำ
- การส่ง challenge ซ้ำหลังผ่านแล้วไม่ควรได้ XP เพิ่ม
- ถ้า challenge ถูก unpublish แล้ว ไม่ควรนับ XP ใหม่
- Admin submission ไม่ควรได้ XP
- badge_awarded ควรให้ XP ได้ครั้งเดียวต่อ badge

---

## 13. Security Requirements

Phase 2 ต้องระวังเป็นพิเศษ เพราะเริ่มรับ code จากผู้เรียน

### 13.1 ห้ามทำเด็ดขาดใน Phase 2

- ห้ามใช้ `eval()`
- ห้ามใช้ `exec()`
- ห้ามใช้ `shell_exec()`
- ห้ามใช้ `system()`
- ห้ามใช้ `passthru()`
- ห้ามใช้ `proc_open()`
- ห้ามใช้ `popen()`
- ห้ามเขียน code ผู้เรียนเป็นไฟล์ `.php` ใน public folder
- ห้าม include/require code ของผู้เรียน
- ห้าม render code ผู้เรียนเป็น HTML โดยไม่ escape

### 13.2 สิ่งที่ต้องทำ

- ทุก form ต้องมี CSRF token
- ตรวจ role ทุก admin route
- จำกัดความยาว code
- Escape code ด้วย `htmlspecialchars()`
- ใช้ PDO prepared statement
- บันทึก submission เป็น text เท่านั้น
- ตรวจ forbidden keyword ขั้นต่ำทุก challenge
- Admin content ที่เป็น HTML ต้องถือว่า trusted เฉพาะ admin เท่านั้น
- ป้องกันการ spam submit เช่น จำกัด submit ถี่เกินไปในอนาคต

### 13.3 Forbidden Keywords Default

ควรมี default forbidden keywords ในระบบ:

```text
eval
exec
shell_exec
system
passthru
proc_open
popen
unlink
file_put_contents
fopen
include
require
include_once
require_once
```

หมายเหตุ: ใน Phase 2 เราไม่ได้รัน code จริง แต่การเตือน keyword เหล่านี้ช่วยสร้างนิสัยด้าน security และเตรียมระบบสำหรับ Phase 3

---

## 14. UI/UX สำหรับ Phase 2

### 14.1 Student UI

เมนูฝั่งผู้เรียนควรเพิ่ม:

- Challenges
- My Badges
- Leaderboard

หน้า Dashboard ควรเพิ่ม card:

- Challenge ที่แนะนำ
- Challenge ที่ยังไม่ผ่าน
- Badge ล่าสุด
- Rank เบื้องต้น
- Practice progress

### 14.2 Lesson Page

ในหน้า lesson ควรเพิ่ม section:

```text
[Animation Block]
[บทเรียน]
[Quiz]
[Practice Challenge]
[Related Challenges]
```

ถ้าผู้เรียนยังไม่ผ่าน quiz อาจแสดงปุ่ม challenge ได้ แต่มีข้อความแนะนำว่า “ควรทำ Quiz ก่อน”

### 14.3 Challenge Page Layout

```text
┌─────────────────────────────────────────────┐
│ Challenge Title + Difficulty + XP           │
├─────────────────────────────────────────────┤
│ คำอธิบายโจทย์ / เงื่อนไขผ่าน              │
├─────────────────────┬───────────────────────┤
│ Code Editor          │ Feedback / Hint       │
│                     │ Submission History     │
├─────────────────────┴───────────────────────┤
│ [Reset] [Show Hint] [Submit]                 │
└─────────────────────────────────────────────┘
```

### 14.4 Feedback UX

Feedback ควรเป็นมิตร เช่น:

- “ยังไม่พบคำสั่ง echo ลองเพิ่ม echo เพื่อแสดงข้อความ”
- “พบ foreach แล้ว ดีมาก แต่ยังไม่พบตัวแปร $students”
- “ผ่านแล้ว! คุณได้รับ 40 XP”

ไม่ควรแสดงข้อความ error แบบเทคนิคเกินไปสำหรับผู้เริ่มต้น

---

## 15. Content Plan สำหรับ Animation Block

### Animation 1: Request / Response

ใช้กับบท “PHP คืออะไร”

ขั้นตอน:

1. Browser ส่ง request
2. Server รับ request
3. PHP ประมวลผล
4. Server ส่ง HTML กลับ
5. Browser แสดงผล

### Animation 2: Variable Memory

ใช้กับบท “ตัวแปรใน PHP”

ภาพแนวคิด:

```text
$name  →  "Somchai"
$age   →  12
$score →  85
```

### Animation 3: If / Else Decision

ใช้กับบท “if else”

ภาพแนวคิด:

```text
score >= 50 ?
    YES → ผ่าน
    NO  → ไม่ผ่าน
```

### Animation 4: Loop

ใช้กับบท “loop”

แสดงการวนซ้ำรอบที่ 1, 2, 3, 4, 5

### Animation 5: Function Input / Output

ใช้กับบท “function เบื้องต้น”

ภาพแนวคิด:

```text
input: "Somchai"
        ↓
greet($name)
        ↓
output: "Hello Somchai"
```

---

## 16. Admin UX สำหรับ Phase 2

Admin ควรทำงานได้โดยไม่ต้องแก้ code โดยตรง

### เมนู Admin ที่เพิ่ม

- Manage Challenges
- Manage Badges
- Manage Animation Blocks
- Challenge Reports

### Admin Challenge Form ควรมี helper

- ปุ่มเพิ่ม required keyword หลายรายการ
- ปุ่มเพิ่ม forbidden keyword หลายรายการ
- ปุ่มเพิ่ม hint หลายขั้น
- preview starter code
- preview challenge page
- auto-generate slug จาก title

### Admin Report ควรแสดง

- Challenge ทั้งหมด
- Published challenge
- Submission ทั้งหมด
- Pass rate
- Average attempts
- Top failed challenge
- Top active learner

---

## 17. Acceptance Criteria ราย Feature

### 17.1 Challenge List

- ผู้เรียนเห็น challenge ที่ publish แล้วเท่านั้น
- แสดง difficulty, XP, lesson, status ของตัวเอง
- Challenge ที่ผ่านแล้วมีเครื่องหมาย completed
- Challenge ที่ยังไม่ผ่านมีปุ่ม Start

### 17.2 Challenge Detail

- แสดงโจทย์และ starter code ถูกต้อง
- มี editor หรือ textarea ให้แก้ code
- กด Reset แล้วกลับเป็น starter code
- กด Submit แล้วบันทึก submission
- ถ้าผ่าน แสดง success feedback
- ถ้าไม่ผ่าน แสดง feedback ที่ชัดเจน
- ไม่ execute PHP code จริง

### 17.3 Submission

- บันทึก user_id, challenge_id, code, status, score, feedback, hints_used, submitted_at
- ผู้เรียนดูเฉพาะ submission ของตัวเอง
- Admin ดู submission ทุกคนได้
- ส่งซ้ำได้
- XP ได้ครั้งเดียวเมื่อผ่านครั้งแรก

### 17.4 Hint

- แสดง hint ทีละลำดับ
- ถ้าไม่มี hint แล้ว แสดงข้อความว่าไม่มีคำใบ้เพิ่มเติม
- Hint ไม่ควรเฉลย code เต็มทันที

### 17.5 Badge

- ระบบมอบ badge เมื่อผ่านเงื่อนไข
- badge เดิมไม่ถูกมอบซ้ำ
- ผู้เรียนเห็น badge ที่ได้รับแล้ว
- Admin เพิ่ม/แก้ badge ได้

### 17.6 Animation Block

- บทเรียนสามารถมี animation block ได้มากกว่า 1 block
- แสดงเฉพาะ block ที่ publish
- เรียงตาม sort_order
- ถ้า content เป็น HTML ต้องแสดงเฉพาะจาก admin trusted content

### 17.7 Leaderboard

- แสดงเฉพาะ student active
- ไม่แสดง email
- เรียงตาม XP และ challenge passed
- จำกัด 50 อันดับแรก
- ผู้เรียนเห็นอันดับของตนเองถ้าอยู่ในรายการ

---

## 18. Sprint Plan สำหรับ Phase 2

แนะนำทำเป็น 7 sprint สั้น ๆ

---

### Sprint 0: Audit และเตรียมฐาน Phase 1

**เป้าหมาย:** ตรวจ Phase 1 ให้พร้อมก่อนต่อยอด

งานที่ต้องทำ:

- ตรวจว่า migration Phase 1 ใช้งานได้
- ตรวจว่า seed Phase 1 run ได้
- ตรวจ auth/admin/student flow
- ตรวจ quiz/progress/XP
- ตรวจ routes และ controller ที่มีอยู่
- เพิ่มเอกสาร `docs/02_phase2_interactive_development_plan.md`
- สร้าง branch เช่น `phase2-interactive-learning`

ผลลัพธ์:

- ระบบ Phase 1 run ได้
- มีแผน Phase 2 ใน repo
- พร้อมเริ่มเพิ่ม schema ใหม่

Acceptance Criteria:

- Login ได้ทั้ง admin/student
- เปิด lesson ได้
- ทำ quiz ได้
- progress และ XP เปลี่ยนถูกต้อง
- ไม่มี fatal error ใน flow หลัก

---

### Sprint 1: Database Migration และ Model พื้นฐาน

**เป้าหมาย:** เพิ่ม schema สำหรับ challenge, submission, badge, animation

งานที่ต้องทำ:

- สร้าง `002_create_phase2_interactive_schema.sql`
- เพิ่มตาราง:
  - challenges
  - challenge_required_keywords
  - challenge_forbidden_keywords
  - challenge_hints
  - challenge_submissions
  - challenge_submission_checks
  - badges
  - user_badges
  - lesson_animation_blocks
- สร้าง model:
  - Challenge
  - ChallengeSubmission
  - ChallengeHint
  - Badge
  - UserBadge
  - LessonAnimationBlock
- สร้าง seeder เบื้องต้น `phase2_seed.php`

ผลลัพธ์:

- migration run ได้
- seed challenge/badge/animation ได้
- model query ข้อมูลได้

Acceptance Criteria:

- ตารางถูกสร้างครบ
- foreign key ทำงาน
- seed challenge อย่างน้อย 5 ข้อแรกได้
- badge เริ่มต้นอย่างน้อย 5 badge ถูกสร้างได้

---

### Sprint 2: Student Challenge UI + Code Editor

**เป้าหมาย:** ผู้เรียนเปิด challenge และส่ง code ได้

งานที่ต้องทำ:

- เพิ่ม route student challenge
- สร้าง `ChallengeController`
- สร้าง view:
  - `challenges/index.php`
  - `challenges/show.php`
  - `challenges/result.php`
  - `challenges/history.php`
- เพิ่ม `challenge-editor.js`
- เพิ่ม textarea fallback
- เพิ่มปุ่ม Reset / Submit / Hint
- แสดง challenge status ของผู้เรียน

ผลลัพธ์:

- ผู้เรียนเห็น challenge list
- เปิด challenge detail ได้
- เขียน code ใน editor ได้
- ส่ง submission ได้

Acceptance Criteria:

- เปิด `/challenges` ได้
- เปิด `/challenges/{slug}` ได้
- submit code แล้วบันทึกลงฐานข้อมูล
- code ที่แสดงกลับถูก escape
- ไม่มีการ execute code

---

### Sprint 3: Challenge Checker + Feedback

**เป้าหมาย:** ระบบตรวจคำตอบแบบ rule-based ได้

งานที่ต้องทำ:

- สร้าง `ChallengeCheckerService`
- ตรวจ expected_text
- ตรวจ required keyword
- ตรวจ forbidden keyword
- ตรวจ pattern อย่างง่าย
- สร้าง `ChallengeSubmissionService`
- บันทึก submission checks
- สร้าง feedback ภาษาไทย
- เชื่อม XP เมื่อผ่าน challenge ครั้งแรก

ผลลัพธ์:

- ระบบบอกผ่าน/ไม่ผ่านได้
- feedback เป็นมิตร
- XP เพิ่มเมื่อผ่านครั้งแรก

Acceptance Criteria:

- Challenge แบบ keyword ตรวจได้ถูกต้อง
- Forbidden keyword ทำให้ไม่ผ่าน
- Submission มี score
- Submission มี feedback
- XP ไม่ซ้ำเมื่อ submit ผ่าน challenge เดิมหลายครั้ง

---

### Sprint 4: Admin Challenge CMS

**เป้าหมาย:** Admin สร้างและแก้ไข challenge ได้เอง

งานที่ต้องทำ:

- สร้าง `Admin\ChallengeController`
- สร้างหน้า:
  - list challenge
  - create challenge
  - edit challenge
  - manage hints
  - view submissions
- เพิ่ม form required keywords
- เพิ่ม form forbidden keywords
- เพิ่ม form hints
- เพิ่ม publish/unpublish
- เพิ่ม preview link

ผลลัพธ์:

- Admin จัดการ challenge ได้ครบ
- ไม่ต้องแก้ code เพื่อเพิ่มโจทย์ใหม่

Acceptance Criteria:

- Admin สร้าง challenge ใหม่ได้
- Admin แก้ challenge ได้
- Admin เพิ่ม hint ได้
- Admin เพิ่ม required/forbidden keyword ได้
- Admin publish/unpublish ได้
- ผู้เรียนเห็นเฉพาะ published challenge

---

### Sprint 5: Badge System

**เป้าหมาย:** ระบบมอบ badge ตามเงื่อนไขพื้นฐานได้

งานที่ต้องทำ:

- สร้าง `BadgeService`
- สร้าง `BadgeController`
- สร้าง `Admin\BadgeController`
- เพิ่มหน้า `/badges`
- เพิ่มหน้า `/admin/badges`
- ตรวจ badge หลัง challenge passed
- แสดง badge ใหม่หลัง submit

ผลลัพธ์:

- ผู้เรียนได้รับ badge
- ผู้เรียนเห็น badge ของตัวเอง
- Admin จัดการ badge ได้

Acceptance Criteria:

- ผ่าน challenge แรกแล้วได้ badge `first_challenge`
- ผ่าน challenge 5 ข้อแล้วได้ badge `five_challenges`
- ไม่ได้รับ badge ซ้ำ
- Admin เปิด/ปิด badge ได้

---

### Sprint 6: Animation Blocks

**เป้าหมาย:** เพิ่ม visual explanation ในบทเรียน

งานที่ต้องทำ:

- สร้าง `AnimationBlockController` ฝั่ง admin
- สร้าง model `LessonAnimationBlock`
- เพิ่มการ render block ในหน้า lesson
- เพิ่ม `animation-blocks.js`
- Seed animation block อย่างน้อย 5 อัน
- รองรับ block type `html`, `steps`, `diagram`

ผลลัพธ์:

- บทเรียนสำคัญมี animation block
- Admin เพิ่ม animation block ได้

Acceptance Criteria:

- หน้า lesson แสดง animation block ตาม sort_order
- แสดงเฉพาะ published block
- block type `steps` render ได้
- content จาก admin ไม่ทำให้ layout พังง่าย

---

### Sprint 7: Leaderboard + Reports + QA

**เป้าหมาย:** ปิด Phase 2 ด้วย leaderboard, report และทดสอบครบ flow

งานที่ต้องทำ:

- สร้าง `LeaderboardController`
- สร้าง `LeaderboardService`
- เพิ่มหน้า `/leaderboard`
- เพิ่ม admin report:
  - challenge pass rate
  - failed submissions
  - top active learners
  - badge distribution
- เพิ่ม seed challenge ให้ครบ 20 ข้อ
- QA flow ผู้เรียน
- QA flow admin
- Update README
- Update docs

ผลลัพธ์:

- มี leaderboard เบื้องต้น
- admin เห็นข้อมูล challenge
- seed content พร้อมใช้งานจริง
- Phase 2 พร้อม demo

Acceptance Criteria:

- `/leaderboard` แสดงข้อมูลถูกต้อง
- ไม่แสดง email
- admin report แสดง pass rate
- challenge seed ครบอย่างน้อย 20 ข้อ
- ไม่มี fatal error ใน flow หลัก
- README มีวิธี run Phase 2 migration/seeder

---

## 19. Definition of Done ของ Phase 2

Phase 2 จะถือว่าเสร็จเมื่อครบเงื่อนไขทั้งหมดนี้:

### 19.1 Functional

- ผู้เรียนเปิด challenge ได้
- ผู้เรียน submit code ได้
- ระบบตรวจ rule-based ได้
- ระบบให้ feedback ได้
- ระบบบันทึก submission ได้
- ระบบให้ XP เมื่อผ่าน challenge ได้
- ระบบให้ badge ได้
- ผู้เรียนดู badge ได้
- ผู้เรียนดู leaderboard ได้
- Admin จัดการ challenge ได้
- Admin จัดการ hint ได้
- Admin จัดการ badge ได้
- Admin จัดการ animation block ได้
- Admin ดู report challenge ได้

### 19.2 Content

- มี challenge อย่างน้อย 20 ข้อ
- มี hint อย่างน้อย 2 hint ต่อ challenge หลัก
- มี badge อย่างน้อย 5 badge
- มี animation block อย่างน้อย 5 อัน
- README อัปเดตวิธีใช้งาน Phase 2
- เอกสาร Phase 2 อยู่ใน `docs/`

### 19.3 Security

- ไม่มีการ execute code ผู้เรียน
- ไม่มี `eval()`
- ไม่มีการ include code ผู้เรียน
- ทุก form มี CSRF
- code output ถูก escape
- admin route ตรวจ role
- ใช้ prepared statement
- จำกัดความยาว code submission

### 19.4 UX

- feedback อ่านเข้าใจง่าย
- hint ไม่เฉลยทันที
- challenge status ชัดเจน
- badge reward มีความรู้สึกเป็นรางวัล
- leaderboard ไม่เปิดเผยข้อมูลส่วนตัวเกินจำเป็น
- mobile responsive ระดับใช้งานได้

---

## 20. Risk และวิธีลดความเสี่ยง

| ความเสี่ยง | ผลกระทบ | วิธีลดความเสี่ยง |
|---|---|---|
| Scope บานไปถึง sandbox | ทำไม่จบ | ยืนยันว่า Phase 2 ไม่รัน code จริง |
| Rule-based ตรวจไม่แม่น | ผู้เรียนบางคนผ่านทั้งที่ code ไม่ดี | ใช้ feedback + manual mode สำหรับโจทย์ซับซ้อน |
| Admin form ซับซ้อน | ใช้งานยาก | เริ่ม form ง่ายก่อน แล้วค่อยเพิ่ม UX |
| Badge ทำให้ระบบงง | logic ซ้ำซ้อน | จำกัด rule type ไม่กี่แบบ |
| Leaderboard กดดันผู้เรียน | UX ด้านลบ | แสดงแบบ friendly และไม่แสดงข้อมูลส่วนตัว |
| Code submission มี XSS | เสี่ยงสูง | escape ทุก output เสมอ |
| Content challenge ไม่พอ | ระบบดูว่าง | seed อย่างน้อย 20 ข้อก่อน demo |

---

## 21. Priority Matrix

### Must Have

- Challenge database
- Challenge list/detail
- Code editor/textarea
- Submission
- Rule-based checker
- Feedback
- XP reward
- Admin challenge CMS
- Seed challenge 20 ข้อ
- Security escape/CSRF

### Should Have

- Hint system
- Badge system
- My Badges page
- Animation block
- Challenge reports

### Could Have

- Leaderboard
- Preview challenge
- Duplicate challenge
- Submission filter
- Animation block type lottie

### Won't Have in Phase 2

- Docker sandbox
- Real PHP execution
- AI tutor
- SQL playground
- Certificate
- Forum
- Payment

---

## 22. แผน GitHub Branch และ Commit

### Branch

```bash
git checkout -b phase2-interactive-learning
```

### Commit แนะนำ

1. `docs: add phase 2 interactive learning plan`
2. `db: add phase 2 challenge and badge schema`
3. `feat: add challenge models and seed content`
4. `feat: add student challenge pages`
5. `feat: add rule based challenge checker`
6. `feat: add admin challenge cms`
7. `feat: add badge system`
8. `feat: add lesson animation blocks`
9. `feat: add leaderboard and challenge reports`
10. `docs: update readme for phase 2`

---

## 23. Checklist ก่อนเริ่มเขียน Phase 2

- [ ] Phase 1 run ได้บนเครื่อง local
- [ ] Database Phase 1 migrate แล้ว
- [ ] Seeder Phase 1 ทำงาน
- [ ] Admin login ได้
- [ ] Student login ได้
- [ ] Quiz ผ่านแล้ว progress เปลี่ยน
- [ ] XP log ทำงาน
- [ ] สร้าง branch Phase 2
- [ ] เพิ่มเอกสารนี้ลง `docs/`
- [ ] ตัดสินใจว่าจะใช้ textarea ก่อน หรือเพิ่ม CodeMirror ตั้งแต่ Sprint 2
- [ ] กำหนดชื่อ badge และ challenge seed ชุดแรก
- [ ] สำรองฐานข้อมูลก่อน run migration ใหม่

---

## 24. สรุป Phase 2

Phase 2 คือช่วงเปลี่ยนระบบจาก “เว็บเรียน PHP แบบมี Quiz” ไปเป็น “เว็บฝึกเขียน PHP แบบ Interactive” โดยยังรักษาความปลอดภัยและขอบเขตงานให้ทำจบได้

หัวใจของ Phase 2 คือ:

> Challenge + Code Editor + Rule-based Checking + Submission + Hint + Badge + Animation เบื้องต้น

สิ่งที่สำคัญที่สุดคือ **ยังไม่รัน code จริง** แต่ให้ผู้เรียนได้ฝึกเขียน code ใน editor และให้ระบบตรวจคำตอบด้วยกฎที่ควบคุมได้ก่อน เมื่อระบบนี้นิ่งแล้ว Phase 3 จึงค่อยต่อยอดไป Docker Sandbox และการรัน PHP จริงอย่างปลอดภัย

ผลลัพธ์เมื่อ Phase 2 เสร็จคือระบบจะเริ่มมีความรู้สึกเหมือนแพลตฟอร์มฝึกเขียนโปรแกรมจริง ไม่ใช่แค่เว็บอ่านบทเรียน และจะพร้อมต่อยอดไปสู่ระบบ Run Code, AI Tutor, Project Submission และ Certificate ใน Phase ต่อไป
