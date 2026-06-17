# คู่มือแอดมินเบื้องต้น

## เข้าสู่ระบบ

1. เปิด `/login`
2. ใช้บัญชี `admin@example.com` / `password123`
3. ไปที่เมนู `/admin`

## จัดการ Course, Module, Lesson

- เข้า `Admin > Courses` เพื่อเพิ่มหรือแก้ไข course
- เข้า `Admin > Modules` เพื่อเพิ่ม module ใต้ course
- เข้า `Admin > Lessons` เพื่อเพิ่มบทเรียน ใส่ summary, content HTML, sort order, XP และ publish status
- ถ้า lesson ไม่ publish ผู้เรียนจะไม่เห็นใน Learning Path

## จัดการ Quiz

1. เข้า `Admin > Quizzes`
2. สร้าง quiz ให้ผูกกับ lesson
3. ตั้ง passing score เช่น 70
4. เข้า `Admin > Questions`
5. เพิ่มคำถาม แล้วเพิ่ม choice อย่างน้อย 4 ตัวเลือก
6. ติ๊ก `Correct` ให้ choice ที่ถูก

## จัดการผู้ใช้และรายงาน

- `Admin > Users` ใช้ปรับ role และ status
- `Admin > Reports` ดู XP, level, จำนวน lesson ที่จบ และ quiz ที่ผ่าน

## Checklist ก่อน publish บทเรียน

- Course และ Module publish แล้ว
- Lesson publish แล้ว
- Lesson มี content อ่านได้
- Quiz publish แล้ว
- Quiz มีคำถามอย่างน้อย 3 ข้อ
- แต่ละคำถามมีตัวเลือก และมีคำตอบที่ถูก
