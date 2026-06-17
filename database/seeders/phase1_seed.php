<?php

declare(strict_types=1);

require dirname(__DIR__, 2) . '/app/bootstrap.php';

use App\Core\Database;

$pdo = Database::connection();
$pdo->beginTransaction();

try {
    Database::execute('INSERT IGNORE INTO roles (name, created_at, updated_at) VALUES ("admin", NOW(), NOW()), ("student", NOW(), NOW())');
    $adminRole = Database::first('SELECT id FROM roles WHERE name = "admin"');
    $studentRole = Database::first('SELECT id FROM roles WHERE name = "student"');

    Database::execute(
        'INSERT INTO users (role_id, name, email, password, xp, level, status, created_at, updated_at)
         VALUES (:role_id, "Admin", "admin@example.com", :password, 0, 1, "active", NOW(), NOW())
         ON DUPLICATE KEY UPDATE role_id = VALUES(role_id), status = "active", updated_at = NOW()',
        ['role_id' => $adminRole['id'], 'password' => password_hash('password123', PASSWORD_DEFAULT)]
    );
    Database::execute(
        'INSERT INTO users (role_id, name, email, password, xp, level, status, created_at, updated_at)
         VALUES (:role_id, "Demo Student", "student@example.com", :password, 0, 1, "active", NOW(), NOW())
         ON DUPLICATE KEY UPDATE role_id = VALUES(role_id), status = "active", updated_at = NOW()',
        ['role_id' => $studentRole['id'], 'password' => password_hash('password123', PASSWORD_DEFAULT)]
    );

    Database::execute('DELETE FROM courses WHERE slug = "php-beginner"');
    $courseId = Database::insert(
        'INSERT INTO courses (title, slug, description, sort_order, is_published, created_at, updated_at)
         VALUES ("PHP Beginner", "php-beginner", "เส้นทางเรียน PHP สำหรับผู้เริ่มต้น ตั้งแต่แนวคิดฝั่ง server จนถึง array และ function", 1, 1, NOW(), NOW())'
    );

    $modules = [
        ['เริ่มต้นกับ PHP', 'getting-started', 1],
        ['ตัวแปรและชนิดข้อมูล', 'variables-and-types', 2],
        ['Control Flow', 'control-flow', 3],
        ['Function และ Array เบื้องต้น', 'functions-and-arrays', 4],
    ];
    $moduleIds = [];
    foreach ($modules as [$title, $slug, $sort]) {
        $moduleIds[$slug] = Database::insert(
            'INSERT INTO modules (course_id, title, slug, description, sort_order, is_published, created_at, updated_at)
             VALUES (:course_id, :title, :slug, "", :sort_order, 1, NOW(), NOW())',
            ['course_id' => $courseId, 'title' => $title, 'slug' => $slug, 'sort_order' => $sort]
        );
    }

    $lessons = [
        ['getting-started', 'PHP คืออะไร', 'what-is-php', 'เข้าใจบทบาทของ PHP ในเว็บแบบ dynamic', 'PHP ทำงานบน server เพื่อประมวลผล request แล้วส่ง HTML กลับไปยัง browser', 'echo "Hello PHP";'],
        ['getting-started', 'ติดตั้งเครื่องมือ', 'setup-php-tools', 'เตรียม MAMP, XAMPP หรือ Laragon และ VS Code', 'เครื่องมือที่ดีช่วยให้เริ่มเขียน PHP ได้เร็วและตรวจ error ได้ง่าย', 'php -v'],
        ['getting-started', 'ไฟล์ .php และ echo', 'php-echo-print', 'สร้างไฟล์ PHP และแสดงข้อความด้วย echo/print', 'ไฟล์ PHP สามารถแทรกโค้ด PHP เพื่อสร้าง output HTML ได้', 'echo "<h1>Hello</h1>";'],
        ['variables-and-types', 'ตัวแปรใน PHP', 'php-variables', 'สร้างและใช้งานตัวแปรด้วย $', 'ตัวแปรใน PHP เริ่มด้วยเครื่องหมาย $ และเก็บค่าได้หลายชนิด', '$name = "Nat";'],
        ['variables-and-types', 'String, Number, Boolean', 'php-data-types', 'เข้าใจชนิดข้อมูลพื้นฐานที่ใช้บ่อย', 'ชนิดข้อมูลช่วยให้เลือก operator และการเปรียบเทียบได้ถูกต้อง', '$active = true;'],
        ['variables-and-types', 'Operator', 'php-operators', 'ใช้ arithmetic, comparison และ logical operator', 'Operator คือเครื่องมือคำนวณ เปรียบเทียบ และรวมเงื่อนไข', '$total = $price * $qty;'],
        ['control-flow', 'if else', 'php-if-else', 'เขียนเงื่อนไขแบบเลือกทางเดิน', 'if else ทำให้โปรแกรมตัดสินใจตามเงื่อนไขที่เป็น true หรือ false', 'if ($score >= 70) { echo "pass"; }'],
        ['control-flow', 'switch', 'php-switch', 'เลือกเงื่อนไขหลายกรณีให้อ่านง่าย', 'switch เหมาะกับการเทียบค่าเดียวกับหลายกรณี', 'switch ($role) { case "admin": break; }'],
        ['control-flow', 'loop', 'php-loops', 'ใช้ for, while และ foreach เบื้องต้น', 'Loop ช่วยทำงานซ้ำกับข้อมูลหลายรายการโดยไม่เขียนโค้ดซ้ำ', 'foreach ($items as $item) { echo $item; }'],
        ['functions-and-arrays', 'array เบื้องต้น', 'php-arrays', 'สร้างและอ่านข้อมูล array', 'Array เก็บข้อมูลหลายค่าในตัวแปรเดียว ทั้งแบบ index และ associative', '$user = ["name" => "Ada"];'],
        ['functions-and-arrays', 'function เบื้องต้น', 'php-functions', 'สร้าง function เพื่อจัดกลุ่ม logic', 'Function ช่วยให้โค้ดใช้ซ้ำ อ่านง่าย และทดสอบแยกส่วนได้', 'function greet($name) { return "Hi ".$name; }'],
    ];

    foreach ($lessons as $index => [$moduleSlug, $title, $slug, $summary, $body, $code]) {
        $content = '<h2>เป้าหมาย</h2><p>' . htmlspecialchars($summary, ENT_QUOTES, 'UTF-8') . '</p>'
            . '<h2>แนวคิดสำคัญ</h2><p>' . htmlspecialchars($body, ENT_QUOTES, 'UTF-8') . '</p>'
            . '<h2>ตัวอย่างโค้ด</h2><pre><code>&lt;?php' . "\n" . htmlspecialchars($code, ENT_QUOTES, 'UTF-8') . "\n" . '?&gt;</code></pre>'
            . '<h2>สรุปท้ายบท</h2><p>อ่านแนวคิดให้เข้าใจ แล้วทำ Quiz เพื่อบันทึกความก้าวหน้าและรับ XP</p>';
        $lessonId = Database::insert(
            'INSERT INTO lessons (module_id, title, slug, summary, content, difficulty, estimated_minutes, xp_reward, sort_order, is_published, created_at, updated_at)
             VALUES (:module_id, :title, :slug, :summary, :content, "beginner", 10, 30, :sort_order, 1, NOW(), NOW())',
            [
                'module_id' => $moduleIds[$moduleSlug],
                'title' => $title,
                'slug' => $slug,
                'summary' => $summary,
                'content' => $content,
                'sort_order' => $index + 1,
            ]
        );

        $quizId = Database::insert(
            'INSERT INTO quizzes (lesson_id, title, passing_score, max_attempts, is_published, created_at, updated_at)
             VALUES (:lesson_id, :title, 70, NULL, 1, NOW(), NOW())',
            ['lesson_id' => $lessonId, 'title' => 'Quiz: ' . $title]
        );

        $questions = [
            ['จุดประสงค์หลักของบทนี้คืออะไร?', [$summary, 'ตั้งค่า payment gateway', 'สร้างระบบ certificate', 'เขียน Docker sandbox'], 0],
            ['ข้อใดเป็นตัวอย่าง PHP ที่เกี่ยวข้องกับบทนี้?', [$code, 'console.log("Hi")', 'SELECT * FROM users', '<style>body{}</style>'], 0],
            ['เมื่อเรียนจบบทและผ่าน Quiz ระบบควรทำอะไร?', ['บันทึก progress และให้ XP ตามเงื่อนไข', 'ลบบัญชีผู้เรียน', 'ปิด publish course', 'เปลี่ยนรหัส admin'], 0],
        ];

        foreach ($questions as $qIndex => [$text, $choices, $correctIndex]) {
            $questionId = Database::insert(
                'INSERT INTO quiz_questions (quiz_id, question_text, explanation, sort_order, created_at, updated_at)
                 VALUES (:quiz_id, :question_text, :explanation, :sort_order, NOW(), NOW())',
                [
                    'quiz_id' => $quizId,
                    'question_text' => $text,
                    'explanation' => 'อ่านสรุปบทเรียนแล้วเลือกคำตอบที่สัมพันธ์กับหัวข้อ',
                    'sort_order' => $qIndex + 1,
                ]
            );
            foreach ($choices as $choiceIndex => $choiceText) {
                Database::execute(
                    'INSERT INTO quiz_choices (question_id, choice_text, is_correct, sort_order, created_at, updated_at)
                     VALUES (:question_id, :choice_text, :is_correct, :sort_order, NOW(), NOW())',
                    [
                        'question_id' => $questionId,
                        'choice_text' => $choiceText,
                        'is_correct' => $choiceIndex === $correctIndex ? 1 : 0,
                        'sort_order' => $choiceIndex + 1,
                    ]
                );
            }
        }
    }

    $pdo->commit();
    echo "Phase 1 seed completed.\nAdmin: admin@example.com / password123\nStudent: student@example.com / password123\n";
} catch (Throwable $exception) {
    $pdo->rollBack();
    throw $exception;
}
