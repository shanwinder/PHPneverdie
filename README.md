# PHP Mastery Quest

แพลตฟอร์มเรียน PHP แบบ interactive ตามแผน Phase 1 และ Phase 2 ใน `docs/`

## สิ่งที่ทำแล้วใน Phase 1

- Login, register, logout พร้อม session, password hash และ CSRF
- Role `admin` และ `student`
- Student dashboard แสดง XP, level, progress, lesson ล่าสุด และบทเรียนถัดไป
- Course > Module > Lesson พร้อมหน้า Learning Path
- Lesson reader พร้อม code block และปุ่มทำ Quiz
- Quiz ปรนัย 4 ตัวเลือก ตรวจคะแนนทันที บันทึก attempt และ answer
- Progress tracking แยกตาม user
- XP log กันการรับ XP ซ้ำจาก event เดิม
- Admin CMS สำหรับ courses, modules, lessons, quizzes, questions, choices, users และ reports
- MySQL schema 12 ตารางตามแผน
- Seed content: PHP Beginner 11 บท และ quiz 3 คำถามต่อบท

## สิ่งที่เพิ่มใน Phase 2

- Student Challenges พร้อม code editor แบบ textarea fallback
- Rule-based checker สำหรับ expected text, required keyword, forbidden keyword และ pattern โดยไม่ execute PHP code ของผู้เรียน
- Submission history, feedback และ hint ทีละขั้น
- XP reward สำหรับ challenge ผ่านครั้งแรกผ่าน `XpService::addOnce()`
- Badge system และหน้า My Badges
- Lesson animation blocks สำหรับ visual explanation
- Leaderboard ที่ไม่แสดง email
- Admin CMS สำหรับ challenges, badges และ animation blocks
- Challenge reports, pass rate, hint usage และ badge distribution
- Seed content Phase 2: challenge 20 ข้อ, badge 5 แบบ และ animation block 5 จุด

## สิ่งที่เพิ่มใน Phase 3

- Secure PHP code execution ผ่าน execution queue, worker และ Docker sandbox adapter
- Runtime profiles, runtime test cases และ admin execution monitor
- ปุ่ม Run Code / Submit for Test สำหรับ challenge ที่เปิด runtime
- SQL Playground แบบ SELECT-only พร้อม expected result check
- Project submission ด้วย GitHub URL หรือ note และ admin rubric review
- Certificate HTML พร้อม public verification URL และ revoke flow
- Audit log และ rate limit ขั้นต่ำสำหรับ execution jobs

## Requirements

- PHP 8.1 ขึ้นไป
- MySQL 8 หรือ MariaDB ที่รองรับ InnoDB และ utf8mb4
- Apache/MAMP หรือ PHP built-in server

## ติดตั้งบน MAMP

1. สร้างฐานข้อมูลชื่อ `php_mastery_quest` ใน phpMyAdmin
2. ตรวจ `.env` ให้ตรงกับเครื่องของคุณ ค่าเริ่มต้นถูกตั้งไว้สำหรับ MAMP:

```env
DB_HOST=127.0.0.1
DB_PORT=8889
DB_DATABASE=php_mastery_quest
DB_USERNAME=root
DB_PASSWORD=root
```

3. รัน migration:

```bash
/Applications/MAMP/Library/bin/mysql80/bin/mysql -h 127.0.0.1 -P 8889 -u root -proot php_mastery_quest < database/migrations/001_create_phase1_schema.sql
/Applications/MAMP/Library/bin/mysql80/bin/mysql -h 127.0.0.1 -P 8889 -u root -proot php_mastery_quest < database/migrations/002_create_phase2_interactive_schema.sql
/Applications/MAMP/Library/bin/mysql80/bin/mysql -h 127.0.0.1 -P 8889 -u root -proot php_mastery_quest < database/migrations/003_create_phase3_secure_execution_schema.sql
```

4. รัน seeder:

```bash
php database/seeders/phase1_seed.php
php database/seeders/phase2_seed.php
php database/seeders/phase3_seed.php
```

5. ชี้ web root ไปที่โฟลเดอร์ `public/` หรือใช้ PHP built-in server:

```bash
php -S localhost:8000 -t public
```

## บัญชีเริ่มต้น

- Admin: `admin@example.com` / `password123`
- Student: `student@example.com` / `password123`

## Flow ทดสอบเร็ว

1. เปิด `/register` แล้วสมัครผู้เรียนใหม่
2. เข้า `/dashboard` แล้วกดบทเรียนแรก
3. อ่านบทเรียนและกดทำ Quiz
4. ส่งคำตอบให้ผ่าน ระบบจะบันทึก progress และเพิ่ม XP
5. เปิด `/challenges` หรือกด Practice Challenge จากหน้า lesson
6. ส่ง code ใน editor แล้วดู feedback, hint และ submission history
7. เปิด `/badges` และ `/leaderboard`
8. เปิด `/sql-playgrounds`, `/projects` และ `/certificates` เพื่อทดสอบ Phase 3
9. Login เป็น admin แล้วเข้า `/admin`
10. เพิ่ม/แก้ไข lesson, quiz, challenge, runtime profile, test case, SQL playground, badge หรือ animation แล้วเปิด publish เพื่อให้ผู้เรียนเห็น

## Phase 3 Sandbox Setup

ค่า default ใน `.env.example` ตั้ง `SANDBOX_ENABLED=false` และ `SANDBOX_DRIVER=mock` เพื่อความปลอดภัยในเครื่อง dev ที่ยังไม่ได้เตรียม Docker

Build sandbox image:

```bash
docker build -t php-mastery-sandbox-php:8.3 docker/sandbox/php
```

เปิด Docker sandbox ใน `.env`:

```env
SANDBOX_ENABLED=true
SANDBOX_DRIVER=docker
SANDBOX_PHP_IMAGE=php-mastery-sandbox-php:8.3
```

Run worker:

```bash
php bin/workers/execution_worker.php
```

ระหว่าง dev สามารถใช้ mock worker ได้โดยตั้ง:

```env
SANDBOX_ENABLED=false
SANDBOX_DRIVER=mock
```

แล้วรัน worker คำสั่งเดิมเพื่อให้ job เปลี่ยนจาก `queued` เป็น result โดยไม่ execute PHP code จริง

## โครงสร้างหลัก

```text
public/index.php      Front controller
routes/web.php        Route definitions
app/Core              Router, Database, Session, Request, Controller
app/Controllers       Student/auth controllers
app/Controllers/Admin Admin CMS controllers
app/Services          Progress, Quiz, XP services
app/Views             Layouts and screens
bin/workers           Execution worker
docker/sandbox        Sandbox image definitions
database/migrations   MySQL schema
database/seeders      Phase seed content
```

## หมายเหตุด้านความปลอดภัย

- ทุก query ใช้ PDO prepared statements
- รหัสผ่านใช้ `password_hash()` และตรวจด้วย `password_verify()`
- Form ที่เปลี่ยนข้อมูลมี CSRF token
- Output ทั่วไป escape ด้วย `htmlspecialchars()`
- เนื้อหา lesson ใน admin รองรับ HTML และถือว่า admin เป็น trusted content editor
- Phase 2 ไม่ใช้ `eval()`, ไม่ include code ผู้เรียน และไม่รัน PHP code ที่ส่งเข้ามา
- Code submission จำกัดความยาว 20,000 characters และแสดงผลด้วย escape ทุกครั้ง
- Phase 3 ยังคงไม่รัน code ใน web process: controller สร้าง job เท่านั้น และ worker เป็นผู้เรียก sandbox
- Docker sandbox ปิด network โดย default, จำกัด memory/CPU/timeout/output และลบ workspace ชั่วคราวหลังรัน
- อ่าน checklist เพิ่มเติมได้ที่ `docs/phase3_sandbox_security_checklist.md`
