# แผนพัฒนา Phase 1: MVP สำหรับ PHP Mastery Quest / PHP Code Academy

> เอกสารนี้โฟกัสเฉพาะ **Phase 1: MVP** ของแพลตฟอร์มเรียน PHP แบบ Interactive Learning Platform โดยตัดฟีเจอร์ขั้นสูงออกก่อน เพื่อให้เริ่มพัฒนาได้จริง ใช้งานได้จริง และต่อยอดไป Phase 2–4 ได้อย่างเป็นระบบ

---

## 1. บทนำ

Phase 1 คือระยะสร้างแกนหลักของระบบให้ “เรียนได้จริง” ก่อน โดยยังไม่เน้นความอลังการ เช่น Code Playground เต็มรูปแบบ, Run Code จริง, AI Tutor, Forum, Certificate, Sandbox, Project Review หรือ Leaderboard ขั้นสูง

เป้าหมายสำคัญของ Phase 1 คือสร้างเว็บแอพที่ผู้เรียนสามารถสมัครสมาชิก เข้าสู่ระบบ ดู Dashboard เลือกบทเรียน เรียนเนื้อหา ทำ Quiz ติดตามความก้าวหน้า และแอดมินสามารถเพิ่ม/แก้ไขบทเรียนได้ผ่านระบบหลังบ้าน

Phase 1 ต้องตอบคำถามหลักให้ได้ว่า:

1. ผู้เรียนเข้ามาแล้วเริ่มเรียน PHP ได้จริงหรือไม่
2. ผู้เรียนเห็นความก้าวหน้าของตัวเองหรือไม่
3. ระบบจำได้หรือไม่ว่าผู้เรียนเรียนถึงบทไหนแล้ว
4. แอดมินเพิ่มบทเรียน/โมดูล/Quiz ได้โดยไม่ต้องแก้โค้ดหรือไม่
5. โครงสร้างระบบพร้อมต่อยอดไป Phase 2 ได้หรือไม่

---

## 2. เป้าหมายของ Phase 1

### 2.1 เป้าหมายหลัก

สร้างระบบ MVP ที่มีฟีเจอร์หลักดังนี้:

- Login / Register / Logout
- Dashboard ผู้เรียน
- Course / Module / Lesson
- Quiz เบื้องต้น
- Progress Tracking
- ระบบ XP เบื้องต้น
- Admin เพิ่ม/แก้ไข/ลบบทเรียน
- Admin เพิ่ม/แก้ไข Quiz และคำถาม
- Bootstrap UI แบบ Responsive
- บทเรียน PHP Beginner อย่างน้อย 10 บท

### 2.2 ผลลัพธ์ที่ต้องได้เมื่อจบ Phase 1

เมื่อ Phase 1 เสร็จ ระบบควรสามารถใช้งานได้ในระดับนี้:

- ผู้เรียนสมัครสมาชิกและเข้าสู่ระบบได้
- ผู้เรียนเห็น Dashboard ส่วนตัว
- ผู้เรียนเห็นเส้นทางบทเรียน PHP Beginner
- ผู้เรียนเปิดอ่านบทเรียนได้
- ผู้เรียนทำ Quiz หลังบทเรียนได้
- ระบบบันทึกสถานะว่าเรียนบทใดแล้ว
- ระบบแสดงเปอร์เซ็นต์ความก้าวหน้า
- ระบบให้ XP เมื่อเรียนจบบทหรือทำ Quiz ผ่าน
- แอดมินเพิ่ม Course, Module, Lesson, Quiz, Question, Choice ได้
- แอดมินเปิด/ปิดการเผยแพร่บทเรียนได้
- ระบบมีโครงสร้างไฟล์ที่พร้อมต่อยอดเป็น MVC

---

## 3. ขอบเขตงาน Phase 1

### 3.1 สิ่งที่อยู่ในขอบเขต Phase 1

| หมวด | รายละเอียด |
|---|---|
| Authentication | สมัครสมาชิก, เข้าสู่ระบบ, ออกจากระบบ, session login |
| User Dashboard | แสดงบทเรียนล่าสุด, progress, XP, level เบื้องต้น |
| Course Management | โครงสร้าง Course > Module > Lesson |
| Lesson Reader | หน้าอ่านบทเรียนพร้อมเนื้อหา Markdown/HTML |
| Quiz | แบบปรนัย 4 ตัวเลือก, ตรวจคำตอบ, บันทึกคะแนน |
| Progress | บันทึกบทเรียนที่เรียนแล้ว, เปอร์เซ็นต์ความก้าวหน้า |
| XP เบื้องต้น | ให้ XP เมื่อเรียนจบหรือทำ Quiz ผ่าน |
| Admin CMS | จัดการ Course, Module, Lesson, Quiz, Question, Choice |
| UI | ใช้ Bootstrap ทำ Responsive Layout |
| Seed Content | บทเรียน PHP Beginner อย่างน้อย 10 บท |

### 3.2 สิ่งที่ยังไม่ทำใน Phase 1

ฟีเจอร์เหล่านี้ให้เลื่อนไป Phase ถัดไป:

- Code Playground แบบรันโค้ดจริง
- Docker Sandbox
- SQL Playground
- AI Tutor
- Animation Learning เต็มรูปแบบ
- Badge ขั้นสูง
- Leaderboard
- Daily Quest / Weekly Challenge
- Forum / Community
- Certificate
- Project Submission
- Review Project ด้วย Rubric
- Email Notification
- Payment / Subscription
- Marketplace บทเรียน
- Multi-course เต็มรูปแบบ เช่น Laravel, JavaScript, Python

เหตุผลที่ยังไม่ทำใน Phase 1: ฟีเจอร์เหล่านี้ใช้เวลาสูง มีความเสี่ยงด้านความปลอดภัย และอาจทำให้ MVP ไม่เสร็จ ควรทำระบบเรียนพื้นฐานให้มั่นคงก่อน

---

## 4. นิยาม MVP ของระบบนี้

MVP ของระบบนี้ไม่ใช่เว็บที่มีทุกฟีเจอร์ แต่คือเว็บที่พิสูจน์ได้ว่า:

> “ผู้เรียนสามารถเรียน PHP Beginner ผ่านระบบออนไลน์ได้จริง และระบบสามารถติดตามความก้าวหน้าได้จริง”

ดังนั้น MVP ต้องมี 5 เสาหลัก:

1. **ผู้ใช้** — สมัคร/เข้าสู่ระบบได้
2. **เนื้อหา** — มีบทเรียนให้อ่านอย่างเป็นระบบ
3. **การวัดผล** — มี Quiz หลังบทเรียน
4. **ความก้าวหน้า** — บันทึกว่าเรียนถึงไหนแล้ว
5. **หลังบ้าน** — แอดมินจัดการบทเรียนได้

ถ้ามี 5 ส่วนนี้ครบ ถือว่า Phase 1 ประสบความสำเร็จ แม้จะยังไม่มี Code Editor หรือ AI Tutor

---

## 5. กลุ่มผู้ใช้ใน Phase 1

### 5.1 ผู้เรียน

ผู้เรียนคือผู้ใช้หลักของระบบใน Phase 1 มีความสามารถดังนี้:

- สมัครสมาชิก
- เข้าสู่ระบบ
- ดู Dashboard
- ดูรายการบทเรียน
- เปิดอ่านบทเรียน
- ทำ Quiz
- ดูคะแนน Quiz
- ดูความก้าวหน้าของตนเอง
- ดู XP และ Level เบื้องต้น

### 5.2 แอดมิน / ครูผู้ดูแลระบบ

แอดมินคือผู้จัดการเนื้อหา มีความสามารถดังนี้:

- เข้าสู่ระบบหลังบ้าน
- จัดการผู้ใช้เบื้องต้น
- เพิ่ม/แก้ไข/ลบ Course
- เพิ่ม/แก้ไข/ลบ Module
- เพิ่ม/แก้ไข/ลบ Lesson
- เพิ่ม/แก้ไข/ลบ Quiz
- เพิ่ม/แก้ไข/ลบ Question
- เพิ่ม/แก้ไข/ลบ Choice
- ดูสถิติพื้นฐานของผู้เรียน

---

## 6. User Journey ของ Phase 1

### 6.1 เส้นทางผู้เรียนครั้งแรก

1. ผู้เรียนเปิดหน้าเว็บ
2. เห็นหน้า Landing Page อธิบายว่าเว็บนี้คือเว็บฝึก PHP
3. กดสมัครสมาชิก
4. กรอกชื่อ อีเมล รหัสผ่าน
5. ระบบสร้างบัญชีและพาเข้าสู่ Dashboard
6. Dashboard แนะนำบทเรียนแรก
7. ผู้เรียนกดเริ่มเรียน
8. อ่านบทเรียนที่ 1
9. ทำ Quiz หลังบทเรียน
10. Quiz ผ่าน ระบบบันทึก progress และเพิ่ม XP
11. ผู้เรียนเห็นบทเรียนถัดไปถูกแนะนำ

### 6.2 เส้นทางผู้เรียนที่กลับมาเรียนต่อ

1. ผู้เรียน Login
2. Dashboard แสดงบทเรียนล่าสุดที่เรียนค้างไว้
3. ผู้เรียนกด Continue Learning
4. ระบบพาไปยังบทเรียนถัดไปหรือบทเรียนที่ยังไม่ผ่าน Quiz
5. ผู้เรียนเรียนต่อและทำ Quiz
6. ระบบอัปเดต progress

### 6.3 เส้นทางแอดมินเพิ่มบทเรียน

1. แอดมิน Login
2. เข้าเมนู Admin Dashboard
3. สร้าง Course หรือเลือก Course เดิม
4. สร้าง Module
5. สร้าง Lesson
6. ใส่เนื้อหา lesson
7. สร้าง Quiz สำหรับ lesson
8. เพิ่ม Question และ Choice
9. กำหนดคำตอบที่ถูก
10. กด Publish
11. ผู้เรียนเห็นบทเรียนใน Learning Path

---

## 7. โครงสร้างระบบที่แนะนำใน Phase 1

### 7.1 Stack หลัก

| ส่วน | เทคโนโลยีที่แนะนำ |
|---|---|
| Backend | PHP 8.x |
| Database | MySQL 8.x |
| Frontend | Bootstrap 5.x, HTML, CSS, JavaScript |
| Package Manager | Composer |
| Web Server | Apache หรือ Nginx |
| Local Development | XAMPP, Laragon, Docker หรือ PHP built-in server |
| Database Tool | phpMyAdmin, TablePlus, DBeaver หรือ MySQL Workbench |

### 7.2 รูปแบบสถาปัตยกรรม

Phase 1 ควรใช้ PHP ล้วนแบบกึ่ง MVC เพื่อให้เข้าใจง่ายและต่อยอดได้:

```text
public/index.php
        ↓
routes/web.php
        ↓
Controller
        ↓
Model / Service
        ↓
Database
        ↓
View
```

ไม่ควรเขียนไฟล์แบบกระจัดกระจาย เช่น `add_lesson.php`, `edit_lesson.php`, `delete_lesson.php` อยู่คนละที่โดยไม่มีโครงสร้าง เพราะจะทำให้ระบบโตต่อยาก

---

## 8. โครงสร้างไฟล์ Phase 1

```text
php-mastery/
│
├── public/
│   ├── index.php
│   └── assets/
│       ├── css/
│       │   └── app.css
│       ├── js/
│       │   └── app.js
│       └── images/
│
├── app/
│   ├── Controllers/
│   │   ├── AuthController.php
│   │   ├── DashboardController.php
│   │   ├── CourseController.php
│   │   ├── LessonController.php
│   │   ├── QuizController.php
│   │   └── Admin/
│   │       ├── AdminDashboardController.php
│   │       ├── CourseController.php
│   │       ├── ModuleController.php
│   │       ├── LessonController.php
│   │       └── QuizController.php
│   │
│   ├── Models/
│   │   ├── User.php
│   │   ├── Role.php
│   │   ├── Course.php
│   │   ├── Module.php
│   │   ├── Lesson.php
│   │   ├── Quiz.php
│   │   ├── QuizQuestion.php
│   │   ├── QuizChoice.php
│   │   ├── LessonProgress.php
│   │   └── XpLog.php
│   │
│   ├── Views/
│   │   ├── layouts/
│   │   │   ├── app.php
│   │   │   └── admin.php
│   │   ├── auth/
│   │   ├── dashboard/
│   │   ├── courses/
│   │   ├── lessons/
│   │   ├── quizzes/
│   │   └── admin/
│   │
│   ├── Core/
│   │   ├── App.php
│   │   ├── Router.php
│   │   ├── Controller.php
│   │   ├── Database.php
│   │   ├── View.php
│   │   ├── Session.php
│   │   └── Request.php
│   │
│   ├── Middleware/
│   │   ├── AuthMiddleware.php
│   │   └── AdminMiddleware.php
│   │
│   ├── Helpers/
│   │   ├── auth.php
│   │   ├── url.php
│   │   ├── csrf.php
│   │   └── view.php
│   │
│   └── Services/
│       ├── ProgressService.php
│       ├── QuizService.php
│       └── XpService.php
│
├── config/
│   ├── app.php
│   └── database.php
│
├── database/
│   ├── migrations/
│   └── seeders/
│
├── routes/
│   └── web.php
│
├── storage/
│   ├── logs/
│   └── cache/
│
├── vendor/
├── .env
├── .env.example
├── composer.json
└── README.md
```

---

## 9. Modules ที่ต้องทำใน Phase 1

## 9.1 Module: Authentication

### วัตถุประสงค์

ให้ผู้ใช้สมัครสมาชิก เข้าสู่ระบบ และออกจากระบบได้อย่างปลอดภัย

### ฟีเจอร์

- Register
- Login
- Logout
- Session-based authentication
- Password hashing
- Role-based redirect
- ป้องกันผู้ใช้ที่ยังไม่ login เข้าหน้า dashboard
- ป้องกัน user ธรรมดาเข้าหน้า admin

### หน้าที่ต้องมี

| หน้า | URL แนะนำ | รายละเอียด |
|---|---|---|
| Register | `/register` | สมัครสมาชิก |
| Login | `/login` | เข้าสู่ระบบ |
| Logout | `/logout` | ออกจากระบบ |

### Field Register

| Field | Validation |
|---|---|
| name | required, min 2 |
| email | required, email, unique |
| password | required, min 8 |
| password_confirmation | required, same as password |

### Acceptance Criteria

- สมัครสมาชิกด้วยอีเมลซ้ำไม่ได้
- รหัสผ่านถูก hash ก่อนบันทึกลงฐานข้อมูล
- Login สำเร็จแล้วเข้า Dashboard
- Login ไม่สำเร็จต้องแสดง error message
- Logout แล้ว session ถูกล้าง
- User ที่ยังไม่ login เข้า `/dashboard` ไม่ได้
- User ธรรมดาเข้า `/admin` ไม่ได้

---

## 9.2 Module: Student Dashboard

### วัตถุประสงค์

ให้ผู้เรียนเห็นภาพรวมการเรียนของตนเองทันทีหลังเข้าสู่ระบบ

### ข้อมูลที่ควรแสดง

- ชื่อผู้เรียน
- Level ปัจจุบัน
- XP ปัจจุบัน
- Progress ทั้งหมดเป็นเปอร์เซ็นต์
- บทเรียนล่าสุดที่เรียน
- บทเรียนถัดไปที่แนะนำ
- จำนวนบทเรียนที่เรียนจบ
- จำนวน Quiz ที่ผ่าน

### ตัวอย่าง Layout

```text
+------------------------------------------------+
| สวัสดี Natdanai                               |
| Level 1: PHP Beginner                          |
| XP: 120 / 300                                  |
+------------------------------------------------+
| Progress: 30%                                  |
| [##########----------------------]             |
+------------------------------------------------+
| Continue Learning                              |
| บทที่ 4: เงื่อนไข if else                     |
| [เรียนต่อ]                                     |
+------------------------------------------------+
| Learning Path                                  |
| บทที่ 1 ผ่านแล้ว                               |
| บทที่ 2 ผ่านแล้ว                               |
| บทที่ 3 กำลังเรียน                             |
+------------------------------------------------+
```

### Acceptance Criteria

- Dashboard แสดงเฉพาะข้อมูลของผู้ใช้ที่ login อยู่
- Progress คำนวณจากบทเรียนที่ publish แล้ว
- ถ้ายังไม่เคยเรียน ให้แนะนำบทที่ 1
- ถ้าเรียนแล้ว ให้แนะนำบทถัดไป
- XP แสดงค่าปัจจุบันจากตาราง users หรือ xp_logs

---

## 9.3 Module: Course / Module / Lesson

### วัตถุประสงค์

สร้างโครงสร้างบทเรียนแบบเป็นระบบ โดยเริ่มจาก Course เดียวคือ PHP Beginner

### โครงสร้างข้อมูล

```text
Course: PHP Beginner
 ├── Module 1: เริ่มต้นกับ PHP
 │    ├── Lesson 1: PHP คืออะไร
 │    ├── Lesson 2: เตรียมเครื่องมือเขียน PHP
 │    └── Lesson 3: echo และ print
 │
 ├── Module 2: ตัวแปรและชนิดข้อมูล
 │    ├── Lesson 4: ตัวแปรใน PHP
 │    ├── Lesson 5: String, Number, Boolean
 │    └── Lesson 6: Operator
 │
 ├── Module 3: Control Flow
 │    ├── Lesson 7: if else
 │    ├── Lesson 8: switch
 │    └── Lesson 9: loop
 │
 └── Module 4: Function และ Array เบื้องต้น
      ├── Lesson 10: Array เบื้องต้น
      └── Lesson 11: Function เบื้องต้น
```

### Phase 1 ควรมีอย่างน้อย 10 บท

| ลำดับ | บทเรียน | เป้าหมาย |
|---:|---|---|
| 1 | PHP คืออะไร | เข้าใจบทบาทของ PHP ในเว็บ |
| 2 | ติดตั้งเครื่องมือ | รู้จัก XAMPP/Laragon/VS Code |
| 3 | ไฟล์ `.php` และ `echo` | แสดงข้อความบนเว็บได้ |
| 4 | ตัวแปร | สร้างและใช้งานตัวแปรได้ |
| 5 | ชนิดข้อมูล | เข้าใจ string, integer, float, boolean |
| 6 | Operator | ใช้ arithmetic/comparison/logical operator |
| 7 | if else | เขียนเงื่อนไขได้ |
| 8 | switch | เลือกเงื่อนไขหลายกรณีได้ |
| 9 | loop | ใช้ for/while/foreach เบื้องต้นได้ |
| 10 | array | สร้างและอ่านข้อมูล array ได้ |
| 11 | function | สร้าง function อย่างง่ายได้ |

หมายเหตุ: แม้เป้าหมายขั้นต่ำคือ 10 บท แต่แนะนำให้เตรียม 11 บทเพื่อให้ครอบคลุม function เบื้องต้น

### Lesson Structure ใน Phase 1

บทเรียนแต่ละบทควรมี field เหล่านี้:

- title
- slug
- summary
- content
- estimated_minutes
- difficulty
- sort_order
- xp_reward
- is_published

### Acceptance Criteria

- ผู้เรียนเห็นเฉพาะบทเรียนที่ `is_published = true`
- บทเรียนเรียงตาม `sort_order`
- เปิดบทเรียนด้วย slug ได้ เช่น `/lessons/php-echo-print`
- ถ้าบทเรียนไม่มีอยู่ ให้แสดง 404
- ถ้าแอดมินปิด publish ผู้เรียนจะไม่เห็นบทเรียนนั้น

---

## 9.4 Module: Lesson Reader

### วัตถุประสงค์

ให้ผู้เรียนอ่านบทเรียนได้สบาย เข้าใจง่าย และพร้อมทำ Quiz ต่อท้ายบท

### องค์ประกอบหน้า Lesson Reader

- Breadcrumb
- ชื่อบทเรียน
- ระยะเวลาโดยประมาณ
- เนื้อหาบทเรียน
- ตัวอย่างโค้ด
- กล่องสรุปท้ายบท
- ปุ่ม “ทำ Quiz”
- ปุ่ม “กลับไป Learning Path”
- ปุ่ม “บทเรียนก่อนหน้า/ถัดไป”

### รูปแบบเนื้อหา

ใน Phase 1 สามารถใช้ HTML หรือ Markdown ได้ โดยแนะนำให้เก็บใน field `content` และ render ออกมาใน View

ตัวอย่างโครงบทเรียน:

```markdown
# PHP คืออะไร

PHP คือภาษาสำหรับฝั่ง Server ที่ใช้สร้างเว็บแบบ Dynamic

## PHP ทำงานอย่างไร

1. Browser ส่ง request ไปยัง server
2. Server อ่านไฟล์ PHP
3. PHP ประมวลผล
4. Server ส่ง HTML กลับไปยัง Browser

## ตัวอย่าง

```php
<?php
  echo "Hello PHP";
?>
```

## สรุป

PHP ใช้สร้างเว็บที่มีการประมวลผลฝั่ง Server
```

### Acceptance Criteria

- หน้า lesson อ่านง่ายบนมือถือและคอมพิวเตอร์
- โค้ดตัวอย่างแสดงในกล่อง code block
- มีปุ่มนำทางไปบทก่อนหน้า/ถัดไป
- เมื่อผู้เรียนกดทำ Quiz ระบบพาไป Quiz ของบทนั้น

---

## 9.5 Module: Quiz

### วัตถุประสงค์

ตรวจความเข้าใจหลังเรียนบทเรียน

### ประเภท Quiz ใน Phase 1

ใช้เฉพาะแบบปรนัย 4 ตัวเลือกก่อน

### โครงสร้าง Quiz

```text
Lesson
 └── Quiz
      ├── Question 1
      │    ├── Choice A
      │    ├── Choice B
      │    ├── Choice C
      │    └── Choice D
      ├── Question 2
      └── Question 3
```

### กติกา Quiz

- 1 lesson มี 1 quiz
- 1 quiz มี 3–5 คำถาม
- ผู้เรียนต้องได้คะแนนอย่างน้อย 70% จึงถือว่าผ่าน
- เมื่อผ่าน Quiz ระบบ mark lesson เป็น completed
- เมื่อผ่านครั้งแรก ระบบให้ XP
- ถ้าทำซ้ำแล้วผ่านอีก ไม่ควรได้ XP ซ้ำ

### ตัวอย่าง Quiz

**คำถาม:** คำสั่งใดใช้แสดงข้อความใน PHP?

- A. printText
- B. echo
- C. show
- D. display

คำตอบที่ถูก: B

### Acceptance Criteria

- ผู้เรียนเลือกคำตอบและ submit ได้
- ระบบตรวจคะแนนทันที
- ระบบบอกว่าผ่าน/ไม่ผ่าน
- ถ้าไม่ผ่าน ให้ทำใหม่ได้
- ถ้าผ่าน ให้บันทึก progress
- XP ได้เฉพาะครั้งแรกที่ผ่าน
- แอดมินสามารถเพิ่มคำถามและตัวเลือกได้

---

## 9.6 Module: Progress Tracking

### วัตถุประสงค์

บันทึกความก้าวหน้าของผู้เรียนแต่ละคน

### สถานะบทเรียน

| Status | ความหมาย |
|---|---|
| not_started | ยังไม่เริ่ม |
| in_progress | เปิดเรียนแล้ว แต่ยังไม่ผ่าน Quiz |
| completed | ผ่าน Quiz แล้ว |

### การคำนวณ Progress

```text
progress_percent = (จำนวนบทเรียน completed / จำนวนบทเรียน published ทั้งหมด) * 100
```

### เหตุการณ์ที่ต้องบันทึก

- ผู้เรียนเปิดบทเรียนครั้งแรก → status = in_progress
- ผู้เรียนทำ Quiz ผ่าน → status = completed
- ผู้เรียนทำ Quiz ไม่ผ่าน → status ยังเป็น in_progress
- ผู้เรียนกลับมาเรียนซ้ำ → ไม่เปลี่ยน completed เป็น in_progress

### Acceptance Criteria

- ระบบรู้ว่าผู้เรียนเรียนบทไหนแล้ว
- Dashboard แสดง progress ถูกต้อง
- Learning Path แสดงสถานะของแต่ละบทได้
- Progress แยกตาม user ไม่ปนกัน

---

## 9.7 Module: XP และ Level เบื้องต้น

### วัตถุประสงค์

เพิ่มแรงจูงใจให้ผู้เรียน โดยยังไม่ทำระบบเกมเต็มรูปแบบ

### กติกา XP ใน Phase 1

| กิจกรรม | XP |
|---|---:|
| เปิดเรียนบทแรก | +5 |
| ผ่าน Quiz ครั้งแรก | +20 |
| เรียนจบบทเรียน | +30 |
| เรียนจบ Module | +50 |

หมายเหตุ: กติกาสามารถปรับได้ แต่ต้องบันทึกใน `xp_logs` เพื่อป้องกันการได้คะแนนซ้ำโดยไม่ตั้งใจ

### Level เบื้องต้น

| Level | XP ที่ต้องมี | ชื่อ Level |
|---:|---:|---|
| 1 | 0 | PHP Newbie |
| 2 | 100 | PHP Apprentice |
| 3 | 250 | PHP Explorer |
| 4 | 500 | PHP Builder |
| 5 | 1,000 | PHP Developer |

### Acceptance Criteria

- XP เพิ่มเมื่อผ่านเงื่อนไข
- XP ไม่เพิ่มซ้ำจาก event เดิม
- Dashboard แสดง XP และ Level
- ระบบมี log ว่า XP มาจากกิจกรรมใด

---

## 9.8 Module: Admin CMS

### วัตถุประสงค์

ให้แอดมินจัดการเนื้อหาได้เองโดยไม่ต้องแก้โค้ด

### เมนู Admin Phase 1

1. Admin Dashboard
2. Manage Courses
3. Manage Modules
4. Manage Lessons
5. Manage Quizzes
6. Manage Questions
7. Manage Users
8. Basic Reports

### ความสามารถหลัก

| Feature | Create | Read | Update | Delete | Publish |
|---|---:|---:|---:|---:|---:|
| Course | ✅ | ✅ | ✅ | ✅ | ✅ |
| Module | ✅ | ✅ | ✅ | ✅ | ✅ |
| Lesson | ✅ | ✅ | ✅ | ✅ | ✅ |
| Quiz | ✅ | ✅ | ✅ | ✅ | ✅ |
| Question | ✅ | ✅ | ✅ | ✅ | - |
| Choice | ✅ | ✅ | ✅ | ✅ | - |
| User | - | ✅ | ✅ role/status | - | - |

### Admin Dashboard ควรแสดง

- จำนวนผู้ใช้ทั้งหมด
- จำนวนบทเรียนทั้งหมด
- จำนวนบทเรียนที่ publish แล้ว
- จำนวน Quiz ทั้งหมด
- จำนวนผู้เรียนที่ active ล่าสุด
- Progress เฉลี่ยเบื้องต้น

### Acceptance Criteria

- เฉพาะ admin เข้าเมนู admin ได้
- Admin เพิ่มบทเรียนใหม่แล้วผู้เรียนเห็นเมื่อ publish
- Admin ปิด publish แล้วผู้เรียนไม่เห็น
- Admin เพิ่ม Quiz และคำถามได้ครบ
- Admin แก้ไขเนื้อหาได้โดยไม่ต้องแก้ไฟล์โค้ด

---

## 10. ฐานข้อมูล Phase 1

## 10.1 ตารางหลัก

Phase 1 ควรมีตารางขั้นต่ำดังนี้:

1. roles
2. users
3. courses
4. modules
5. lessons
6. quizzes
7. quiz_questions
8. quiz_choices
9. quiz_attempts
10. quiz_answers
11. lesson_progress
12. xp_logs

---

## 10.2 รายละเอียดตาราง

### roles

| Field | Type | Note |
|---|---|---|
| id | INT PK | |
| name | VARCHAR(50) | เช่น admin, student |
| created_at | DATETIME | |
| updated_at | DATETIME | |

### users

| Field | Type | Note |
|---|---|---|
| id | INT PK | |
| role_id | INT FK | อ้างถึง roles |
| name | VARCHAR(150) | |
| email | VARCHAR(190) UNIQUE | |
| password | VARCHAR(255) | hash แล้ว |
| avatar | VARCHAR(255) NULL | |
| xp | INT DEFAULT 0 | |
| level | INT DEFAULT 1 | |
| status | ENUM | active, inactive |
| last_login_at | DATETIME NULL | |
| created_at | DATETIME | |
| updated_at | DATETIME | |

### courses

| Field | Type | Note |
|---|---|---|
| id | INT PK | |
| title | VARCHAR(255) | |
| slug | VARCHAR(255) UNIQUE | |
| description | TEXT NULL | |
| sort_order | INT | |
| is_published | BOOLEAN | |
| created_at | DATETIME | |
| updated_at | DATETIME | |

### modules

| Field | Type | Note |
|---|---|---|
| id | INT PK | |
| course_id | INT FK | |
| title | VARCHAR(255) | |
| slug | VARCHAR(255) | |
| description | TEXT NULL | |
| sort_order | INT | |
| is_published | BOOLEAN | |
| created_at | DATETIME | |
| updated_at | DATETIME | |

### lessons

| Field | Type | Note |
|---|---|---|
| id | INT PK | |
| module_id | INT FK | |
| title | VARCHAR(255) | |
| slug | VARCHAR(255) UNIQUE | |
| summary | TEXT NULL | |
| content | LONGTEXT | |
| difficulty | ENUM | beginner, intermediate, advanced |
| estimated_minutes | INT | |
| xp_reward | INT DEFAULT 30 | |
| sort_order | INT | |
| is_published | BOOLEAN | |
| created_at | DATETIME | |
| updated_at | DATETIME | |

### quizzes

| Field | Type | Note |
|---|---|---|
| id | INT PK | |
| lesson_id | INT FK | |
| title | VARCHAR(255) | |
| passing_score | INT DEFAULT 70 | |
| max_attempts | INT NULL | NULL = ไม่จำกัด |
| is_published | BOOLEAN | |
| created_at | DATETIME | |
| updated_at | DATETIME | |

### quiz_questions

| Field | Type | Note |
|---|---|---|
| id | INT PK | |
| quiz_id | INT FK | |
| question_text | TEXT | |
| explanation | TEXT NULL | อธิบายหลังตอบ |
| sort_order | INT | |
| created_at | DATETIME | |
| updated_at | DATETIME | |

### quiz_choices

| Field | Type | Note |
|---|---|---|
| id | INT PK | |
| question_id | INT FK | |
| choice_text | TEXT | |
| is_correct | BOOLEAN | |
| sort_order | INT | |
| created_at | DATETIME | |
| updated_at | DATETIME | |

### quiz_attempts

| Field | Type | Note |
|---|---|---|
| id | INT PK | |
| user_id | INT FK | |
| quiz_id | INT FK | |
| score | DECIMAL(5,2) | |
| total_questions | INT | |
| correct_answers | INT | |
| passed | BOOLEAN | |
| started_at | DATETIME | |
| submitted_at | DATETIME | |

### quiz_answers

| Field | Type | Note |
|---|---|---|
| id | INT PK | |
| attempt_id | INT FK | |
| question_id | INT FK | |
| choice_id | INT FK | |
| is_correct | BOOLEAN | |
| created_at | DATETIME | |

### lesson_progress

| Field | Type | Note |
|---|---|---|
| id | INT PK | |
| user_id | INT FK | |
| lesson_id | INT FK | |
| status | ENUM | not_started, in_progress, completed |
| started_at | DATETIME NULL | |
| completed_at | DATETIME NULL | |
| last_accessed_at | DATETIME NULL | |
| created_at | DATETIME | |
| updated_at | DATETIME | |

ข้อกำหนดสำคัญ:

```text
UNIQUE(user_id, lesson_id)
```

เพื่อให้ 1 user มี progress ต่อ 1 lesson เพียงแถวเดียว

### xp_logs

| Field | Type | Note |
|---|---|---|
| id | INT PK | |
| user_id | INT FK | |
| event_type | VARCHAR(100) | เช่น lesson_completed |
| event_id | INT NULL | เช่น lesson_id |
| xp_amount | INT | |
| description | VARCHAR(255) | |
| created_at | DATETIME | |

ข้อกำหนดสำคัญ:

```text
UNIQUE(user_id, event_type, event_id)
```

เพื่อป้องกันการรับ XP ซ้ำจาก event เดิม

---

## 11. Routes ที่ควรมีใน Phase 1

### Public Routes

| Method | URL | Controller | Action |
|---|---|---|---|
| GET | `/` | HomeController | index |
| GET | `/login` | AuthController | showLogin |
| POST | `/login` | AuthController | login |
| GET | `/register` | AuthController | showRegister |
| POST | `/register` | AuthController | register |
| POST | `/logout` | AuthController | logout |

### Student Routes

| Method | URL | Controller | Action |
|---|---|---|---|
| GET | `/dashboard` | DashboardController | index |
| GET | `/courses` | CourseController | index |
| GET | `/courses/{slug}` | CourseController | show |
| GET | `/lessons/{slug}` | LessonController | show |
| POST | `/lessons/{id}/start` | LessonController | start |
| GET | `/lessons/{id}/quiz` | QuizController | show |
| POST | `/lessons/{id}/quiz` | QuizController | submit |

### Admin Routes

| Method | URL | Controller | Action |
|---|---|---|---|
| GET | `/admin` | AdminDashboardController | index |
| GET | `/admin/courses` | Admin/CourseController | index |
| GET | `/admin/courses/create` | Admin/CourseController | create |
| POST | `/admin/courses` | Admin/CourseController | store |
| GET | `/admin/courses/{id}/edit` | Admin/CourseController | edit |
| POST | `/admin/courses/{id}` | Admin/CourseController | update |
| POST | `/admin/courses/{id}/delete` | Admin/CourseController | delete |
| GET | `/admin/modules` | Admin/ModuleController | index |
| POST | `/admin/modules` | Admin/ModuleController | store |
| GET | `/admin/lessons` | Admin/LessonController | index |
| POST | `/admin/lessons` | Admin/LessonController | store |
| GET | `/admin/quizzes` | Admin/QuizController | index |
| POST | `/admin/quizzes` | Admin/QuizController | store |

---

## 12. หน้าจอที่ต้องทำใน Phase 1

## 12.1 ฝั่งผู้เรียน

| ลำดับ | หน้า | ความสำคัญ |
|---:|---|---|
| 1 | Landing Page | สูง |
| 2 | Register | สูง |
| 3 | Login | สูง |
| 4 | Dashboard | สูง |
| 5 | Learning Path / Course Detail | สูง |
| 6 | Lesson Reader | สูง |
| 7 | Quiz Page | สูง |
| 8 | Quiz Result | สูง |
| 9 | Profile แบบง่าย | กลาง |

## 12.2 ฝั่งแอดมิน

| ลำดับ | หน้า | ความสำคัญ |
|---:|---|---|
| 1 | Admin Dashboard | สูง |
| 2 | Course List | สูง |
| 3 | Course Create/Edit | สูง |
| 4 | Module List | สูง |
| 5 | Module Create/Edit | สูง |
| 6 | Lesson List | สูง |
| 7 | Lesson Create/Edit | สูง |
| 8 | Quiz List | สูง |
| 9 | Quiz Create/Edit | สูง |
| 10 | Question/Choice Manager | สูง |
| 11 | User List | กลาง |
| 12 | Basic Report | กลาง |

---

## 13. Design Guideline Phase 1

### 13.1 โทน UI

ใช้แนว Modern Coding Academy แต่ไม่ต้องซับซ้อนมากใน Phase 1

ลักษณะที่ควรมี:

- ใช้ Bootstrap Card
- มี Sidebar สำหรับ Dashboard
- มี Top Navbar
- มี Progress Bar
- มี Badge แสดง Level
- ใช้ Code Block ให้อ่านง่าย
- Responsive บนมือถือ
- ใช้สีหลักโทนน้ำเงิน/ม่วงหรือเข้มแบบ Coding Platform

### 13.2 Layout ผู้เรียน

```text
+------------------------------------------------+
| Navbar                                         |
+----------------------+-------------------------+
| Sidebar              | Content                 |
| - Dashboard          |                         |
| - Learning Path      |                         |
| - My Progress        |                         |
| - Profile            |                         |
+----------------------+-------------------------+
```

### 13.3 Layout Admin

```text
+------------------------------------------------+
| Admin Navbar                                    |
+----------------------+-------------------------+
| Admin Sidebar        | Admin Content           |
| - Dashboard          |                         |
| - Courses            |                         |
| - Modules            |                         |
| - Lessons            |                         |
| - Quizzes            |                         |
| - Users              |                         |
+----------------------+-------------------------+
```

---

## 14. Sprint Plan สำหรับ Phase 1

แนะนำแบ่งงาน Phase 1 เป็น 6 Sprint

---

## Sprint 0: Project Setup

### เป้าหมาย

เตรียมโครงสร้างโปรเจกต์ ฐานข้อมูล และระบบพื้นฐานให้พร้อมพัฒนา

### งานที่ต้องทำ

- สร้าง repository
- กำหนดโครงสร้าง folder
- ตั้งค่า Composer
- สร้าง `.env` และ config database
- สร้าง Core Router
- สร้าง Database Connection ด้วย PDO
- สร้าง Base Controller
- สร้างระบบ View Layout
- สร้าง migration เบื้องต้น
- สร้าง seed roles: admin, student
- สร้าง admin user เริ่มต้น

### Deliverables

- โครงสร้างโปรเจกต์พร้อมใช้งาน
- เชื่อมฐานข้อมูลได้
- หน้าแรกเปิดได้
- Route ทำงานได้
- Layout พื้นฐานพร้อม Bootstrap

### Acceptance Criteria

- เปิด `/` แล้วเห็นหน้าเว็บ
- เชื่อมต่อ MySQL ได้
- มีตาราง roles และ users
- มี admin user ในระบบ
- โครงสร้างไฟล์เป็นระเบียบตามกึ่ง MVC

---

## Sprint 1: Authentication + Layout

### เป้าหมาย

ทำระบบผู้ใช้และ layout หลักให้เสร็จ

### งานที่ต้องทำ

- หน้า Register
- ระบบ Register
- หน้า Login
- ระบบ Login
- ระบบ Logout
- Session helper
- Auth middleware
- Admin middleware
- Layout ฝั่งผู้เรียน
- Layout ฝั่งแอดมิน
- Flash message
- CSRF token สำหรับ form

### Deliverables

- สมัครสมาชิกได้
- Login ได้
- Logout ได้
- แยก role admin/student ได้
- หน้า dashboard เปล่าเปิดได้หลัง login

### Acceptance Criteria

- สมัครสมาชิกด้วย email ซ้ำไม่ได้
- password ถูก hash
- login ผิดแสดง error
- user ไม่ login เข้า dashboard ไม่ได้
- student เข้า admin ไม่ได้
- admin เข้า admin ได้

---

## Sprint 2: Course / Module / Lesson Management

### เป้าหมาย

ทำระบบจัดการเนื้อหาแกนหลัก

### งานที่ต้องทำ

- สร้างตาราง courses
- สร้างตาราง modules
- สร้างตาราง lessons
- Admin CRUD Course
- Admin CRUD Module
- Admin CRUD Lesson
- ระบบ publish/unpublish
- ระบบ sort_order
- หน้า Learning Path ฝั่งผู้เรียน
- หน้า Lesson Reader ฝั่งผู้เรียน

### Deliverables

- Admin เพิ่ม Course ได้
- Admin เพิ่ม Module ได้
- Admin เพิ่ม Lesson ได้
- ผู้เรียนเห็นบทเรียนที่ publish
- ผู้เรียนเปิดอ่านบทเรียนได้

### Acceptance Criteria

- Course > Module > Lesson เชื่อมกันถูกต้อง
- Lesson ไม่ publish ผู้เรียนไม่เห็น
- Lesson เรียงตาม sort_order
- เปิด lesson ด้วย slug ได้
- Admin แก้ไข lesson แล้วข้อมูลเปลี่ยนจริง

---

## Sprint 3: Quiz System

### เป้าหมาย

ทำระบบ Quiz ปรนัยหลังบทเรียน

### งานที่ต้องทำ

- สร้างตาราง quizzes
- สร้างตาราง quiz_questions
- สร้างตาราง quiz_choices
- สร้างตาราง quiz_attempts
- สร้างตาราง quiz_answers
- Admin CRUD Quiz
- Admin CRUD Question
- Admin CRUD Choice
- หน้า Quiz ฝั่งผู้เรียน
- ระบบ submit คำตอบ
- ระบบตรวจคะแนน
- หน้า Quiz Result

### Deliverables

- แอดมินสร้าง Quiz ได้
- ผู้เรียนทำ Quiz ได้
- ระบบตรวจคะแนนได้
- ระบบบันทึก attempt ได้

### Acceptance Criteria

- 1 lesson มี quiz ได้
- 1 question มีหลาย choice ได้
- ต้องมี choice ที่ถูกอย่างน้อย 1 ข้อ
- submit แล้วระบบคำนวณคะแนนถูกต้อง
- แสดงผลผ่าน/ไม่ผ่านตาม passing_score
- บันทึกประวัติการทำ quiz

---

## Sprint 4: Progress + XP

### เป้าหมาย

ทำระบบบันทึกความก้าวหน้าและ XP เบื้องต้น

### งานที่ต้องทำ

- สร้างตาราง lesson_progress
- สร้างตาราง xp_logs
- ProgressService
- XpService
- บันทึก in_progress เมื่อเปิดบทเรียน
- บันทึก completed เมื่อผ่าน Quiz
- ให้ XP เมื่อผ่าน Quiz ครั้งแรก
- คำนวณ progress_percent
- แสดง progress ใน Dashboard
- แสดง XP และ Level ใน Dashboard
- แสดงสถานะบทเรียนใน Learning Path

### Deliverables

- ระบบรู้ว่าผู้เรียนเรียนถึงไหน
- Dashboard แสดง progress ได้
- ระบบให้ XP ได้
- Learning Path แสดงสถานะบทเรียน

### Acceptance Criteria

- เปิดบทเรียนครั้งแรกแล้ว status เป็น in_progress
- ผ่าน Quiz แล้ว status เป็น completed
- ได้ XP เฉพาะครั้งแรกที่ผ่าน
- progress_percent คำนวณถูกต้อง
- Level เปลี่ยนตาม XP

---

## Sprint 5: Seed Content + Polish UI

### เป้าหมาย

เติมเนื้อหา PHP Beginner และปรับ UI ให้ใช้งานได้จริง

### งานที่ต้องทำ

- เขียนบทเรียน PHP Beginner อย่างน้อย 10 บท
- สร้าง Quiz อย่างน้อยบทละ 3 คำถาม
- เพิ่ม code block ในบทเรียน
- ปรับหน้า Dashboard
- ปรับหน้า Learning Path
- ปรับหน้า Lesson Reader
- ปรับหน้า Quiz Result
- ปรับ Responsive บนมือถือ
- เพิ่ม Empty State
- เพิ่ม Error Page 404/403
- เพิ่มข้อความแจ้งเตือนแบบ user-friendly

### Deliverables

- มีบทเรียนพร้อมใช้งาน 10 บทขึ้นไป
- มี Quiz ครบตามบทเรียน
- UI อ่านง่ายและใช้งานได้จริง

### Acceptance Criteria

- ผู้เรียนใหม่สามารถเรียนตั้งแต่บทที่ 1 ถึงบทที่ 10 ได้
- ทุกบทมี Quiz
- ทำ Quiz ผ่านแล้ว progress เพิ่ม
- UI ไม่พังบนมือถือ
- ไม่มีหน้าที่แสดง error ดิบของ PHP ให้ผู้ใช้เห็น

---

## Sprint 6: Testing + Deployment Preparation

### เป้าหมาย

ตรวจสอบระบบให้พร้อมใช้งานจริงในวงทดลอง

### งานที่ต้องทำ

- Manual test ทุก flow
- ทดสอบ Register/Login/Logout
- ทดสอบ Admin CRUD
- ทดสอบ Lesson Reader
- ทดสอบ Quiz
- ทดสอบ Progress
- ทดสอบ XP
- ทดสอบ permission
- ตรวจ SQL error
- ตรวจ XSS เบื้องต้น
- ตรวจ CSRF form สำคัญ
- เตรียม README
- เตรียมคู่มือแอดมิน
- เตรียมคู่มือผู้เรียน
- เตรียมไฟล์ SQL/migration สำหรับติดตั้ง

### Deliverables

- ระบบพร้อมทดลองใช้
- README พร้อมติดตั้ง
- คู่มือแอดมินเบื้องต้น
- คู่มือผู้เรียนเบื้องต้น

### Acceptance Criteria

- Flow ผู้เรียนตั้งแต่สมัครจนเรียนจบบทที่ 1 ทำงานครบ
- Flow แอดมินตั้งแต่เพิ่มบทเรียนจน publish ทำงานครบ
- ไม่มี error ร้ายแรงในหน้าเว็บหลัก
- มีเอกสารติดตั้งระบบ

---

## 15. Checklist รวม Phase 1

### 15.1 Core System

- [ ] ตั้งค่า project structure
- [ ] ตั้งค่า Composer
- [ ] ตั้งค่า `.env`
- [ ] เชื่อมต่อฐานข้อมูลด้วย PDO
- [ ] สร้าง Router
- [ ] สร้าง Controller base
- [ ] สร้าง View layout
- [ ] สร้าง Session helper
- [ ] สร้าง CSRF helper

### 15.2 Authentication

- [ ] Register page
- [ ] Register logic
- [ ] Login page
- [ ] Login logic
- [ ] Logout logic
- [ ] Password hashing
- [ ] Auth middleware
- [ ] Admin middleware

### 15.3 Student

- [ ] Student dashboard
- [ ] Learning path
- [ ] Lesson reader
- [ ] Quiz page
- [ ] Quiz result
- [ ] Progress display
- [ ] XP display

### 15.4 Admin

- [ ] Admin dashboard
- [ ] Manage courses
- [ ] Manage modules
- [ ] Manage lessons
- [ ] Manage quizzes
- [ ] Manage questions
- [ ] Manage choices
- [ ] Manage users เบื้องต้น

### 15.5 Database

- [ ] roles
- [ ] users
- [ ] courses
- [ ] modules
- [ ] lessons
- [ ] quizzes
- [ ] quiz_questions
- [ ] quiz_choices
- [ ] quiz_attempts
- [ ] quiz_answers
- [ ] lesson_progress
- [ ] xp_logs

### 15.6 Content

- [ ] บทที่ 1 PHP คืออะไร
- [ ] บทที่ 2 ติดตั้งเครื่องมือ
- [ ] บทที่ 3 echo และ print
- [ ] บทที่ 4 ตัวแปร
- [ ] บทที่ 5 ชนิดข้อมูล
- [ ] บทที่ 6 Operator
- [ ] บทที่ 7 if else
- [ ] บทที่ 8 switch
- [ ] บทที่ 9 loop
- [ ] บทที่ 10 array
- [ ] บทที่ 11 function เบื้องต้น
- [ ] Quiz อย่างน้อยบทละ 3 คำถาม

---

## 16. Security ขั้นต่ำใน Phase 1

แม้ Phase 1 ยังไม่รันโค้ดผู้เรียนจริง แต่ยังต้องมี security ขั้นต่ำ

### สิ่งที่ต้องทำ

- ใช้ `password_hash()` สำหรับรหัสผ่าน
- ใช้ `password_verify()` ตอน login
- ใช้ PDO prepared statement ทุก query
- Escape output ด้วย `htmlspecialchars()`
- ใช้ CSRF token ใน form ที่เปลี่ยนข้อมูล
- ตรวจสิทธิ์ admin ทุก route หลังบ้าน
- Validate input ฝั่ง server ทุกครั้ง
- ไม่แสดง error ดิบของ PHP ใน production
- ตั้งค่า session ให้เหมาะสม
- regenerate session id หลัง login

### สิ่งที่ยังไม่จำเป็นใน Phase 1

- Two-factor authentication
- OAuth login
- Rate limit ขั้นสูง
- Web Application Firewall
- Docker sandbox
- Static code analysis ขั้นสูง

---

## 17. Testing Plan

### 17.1 Manual Test: ผู้เรียน

| Test Case | Expected Result |
|---|---|
| สมัครสมาชิกด้วยข้อมูลถูกต้อง | สมัครสำเร็จและเข้าสู่ dashboard |
| สมัครด้วย email ซ้ำ | ระบบแจ้งว่า email ถูกใช้แล้ว |
| login ด้วยรหัสผิด | ระบบแจ้ง error |
| เปิดบทเรียนแรก | status เป็น in_progress |
| ทำ Quiz ผ่าน | status เป็น completed และได้ XP |
| ทำ Quiz ไม่ผ่าน | status ยังไม่ completed |
| กลับมา login ใหม่ | dashboard แสดง progress เดิม |

### 17.2 Manual Test: แอดมิน

| Test Case | Expected Result |
|---|---|
| admin login | เข้า admin dashboard ได้ |
| student เข้า `/admin` | ถูกปฏิเสธ |
| เพิ่ม course | course ถูกสร้าง |
| เพิ่ม module | module อยู่ใต้ course ที่เลือก |
| เพิ่ม lesson | lesson อยู่ใต้ module ที่เลือก |
| publish lesson | ผู้เรียนเห็น lesson |
| unpublish lesson | ผู้เรียนไม่เห็น lesson |
| เพิ่ม quiz | quiz แสดงใน lesson |
| เพิ่ม question/choice | ผู้เรียนตอบ quiz ได้ |

### 17.3 Security Test เบื้องต้น

| Test Case | Expected Result |
|---|---|
| ใส่ `<script>` ในชื่อผู้ใช้ | หน้าเว็บไม่ execute script |
| ยิง URL admin ด้วย user ธรรมดา | เข้าไม่ได้ |
| ส่ง form ไม่มี CSRF token | ระบบปฏิเสธ |
| SQL injection ใน login | login ไม่สำเร็จและ database ไม่เสียหาย |

---

## 18. Definition of Done ของ Phase 1

Phase 1 ถือว่าเสร็จเมื่อครบเงื่อนไขต่อไปนี้:

1. ผู้เรียนสมัครสมาชิกและ login ได้
2. ผู้เรียนเห็น Dashboard ส่วนตัว
3. มี Course PHP Beginner
4. มีบทเรียนอย่างน้อย 10 บทที่ publish แล้ว
5. ผู้เรียนเปิดอ่านบทเรียนได้
6. ทุกบทมี Quiz อย่างน้อย 3 คำถาม
7. ผู้เรียนทำ Quiz และเห็นผลคะแนนได้
8. ระบบบันทึก progress ได้ถูกต้อง
9. ระบบให้ XP ได้ถูกต้องและไม่ซ้ำ
10. แอดมินเพิ่ม/แก้ไข/ลบ Course, Module, Lesson, Quiz ได้
11. มี role admin/student
12. student เข้า admin ไม่ได้
13. UI ใช้งานได้บนคอมพิวเตอร์และมือถือ
14. ไม่มี error ดิบแสดงให้ผู้ใช้เห็นในหน้าหลัก
15. มี README สำหรับติดตั้งและใช้งานเบื้องต้น

---

## 19. ความเสี่ยงของ Phase 1 และแนวทางลดความเสี่ยง

| ความเสี่ยง | ผลกระทบ | วิธีลดความเสี่ยง |
|---|---|---|
| Scope บาน | ทำไม่เสร็จ | ห้ามเพิ่ม Code Runner/AI/Forum ใน Phase 1 |
| บทเรียนเขียนไม่ทัน | ระบบมีแต่โครง ไม่มีเนื้อหา | เริ่มจาก 10 บทสั้น ๆ ก่อน |
| Admin CMS ซับซ้อนเกิน | พัฒนาใช้เวลานาน | ทำ CRUD พื้นฐานก่อน ไม่ต้องมี editor ขั้นสูง |
| Quiz logic ผิด | Progress ผิดตาม | เขียน test case ให้ชัด |
| XP ได้ซ้ำ | คะแนนเพี้ยน | ใช้ xp_logs พร้อม unique event |
| UI ใช้งานยาก | ผู้เรียนไม่อยากใช้ | ใช้ Bootstrap component ง่าย ๆ ก่อน |
| Security ต่ำ | ระบบเสี่ยง | ใช้ prepared statement, CSRF, escape output ตั้งแต่แรก |

---

## 20. Backlog ที่เลื่อนไป Phase 2

เมื่อ Phase 1 เสร็จแล้ว งานต่อไปที่ควรทำใน Phase 2 คือ:

- Code Editor ด้วย CodeMirror หรือ Monaco
- Challenge แบบตรวจ keyword/output ง่าย ๆ
- Hint ทีละขั้น
- Badge เบื้องต้น
- Animation เบื้องต้นในบทเรียน
- Leaderboard แบบ optional
- Daily Quest อย่างง่าย
- Improve Dashboard ด้วย Chart.js
- ระบบ submission เบื้องต้น

---

## 21. สรุปแผน Phase 1

Phase 1 ควรโฟกัสที่การสร้าง “แกนระบบเรียน” ให้สมบูรณ์ก่อน ไม่ควรรีบทำฟีเจอร์ใหญ่ เช่น Run Code, AI Tutor หรือ Sandbox เพราะจะทำให้โปรเจกต์บานและเสี่ยงไม่เสร็จ

MVP ที่เหมาะสมที่สุดคือ:

> **Login/Register + Dashboard + Course/Module/Lesson + Quiz + Progress + XP เบื้องต้น + Admin จัดการบทเรียน + บทเรียน PHP Beginner 10 บท**

เมื่อทำ Phase 1 สำเร็จ ระบบจะยังไม่ใช่แพลตฟอร์มเต็มรูปแบบ แต่จะเป็นฐานที่มั่นคงพอให้ต่อยอดไป Phase 2 ได้ทันที โดยไม่ต้องรื้อโครงสร้างใหม่

---

## 22. Output ที่ควรส่งมอบเมื่อจบ Phase 1

รายการไฟล์/สิ่งส่งมอบที่ควรมี:

1. Source code ระบบ MVP
2. Database schema หรือ migration files
3. Seeder สำหรับ admin user และบทเรียนเริ่มต้น
4. บทเรียน PHP Beginner อย่างน้อย 10 บท
5. Quiz อย่างน้อยบทละ 3 คำถาม
6. README สำหรับติดตั้งระบบ
7. คู่มือแอดมินเบื้องต้น
8. คู่มือผู้เรียนเบื้องต้น
9. Checklist การทดสอบ
10. รายการ Backlog สำหรับ Phase 2

---

## 23. Roadmap ย่อหลังจบ Phase 1

```text
Phase 1: MVP
- ระบบเรียนพื้นฐาน
- Quiz
- Progress
- Admin CMS

Phase 2: Interactive
- Code Editor
- Challenge
- Badge
- Animation เบื้องต้น

Phase 3: Professional Learning
- SQL Playground
- Project Submission
- Certificate
- Admin Report

Phase 4: Advanced Platform
- AI Tutor
- Sandbox จริง
- Community
- Learning Analytics
```

Phase 1 จึงเป็นฐานรากของระบบทั้งหมด หากออกแบบดีตั้งแต่ตอนนี้ Phase ถัดไปจะต่อยอดได้ง่ายและลดการเขียนระบบใหม่ซ้ำ
