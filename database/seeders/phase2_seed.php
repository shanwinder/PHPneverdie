<?php

declare(strict_types=1);

require dirname(__DIR__, 2) . '/app/bootstrap.php';

use App\Core\Database;

$pdo = Database::connection();
$pdo->beginTransaction();

try {
    $lessons = Database::select('SELECT id, slug FROM lessons');
    $lessonIds = array_column($lessons, 'id', 'slug');

    $badges = [
        ['first_challenge', 'First Code Step', 'ผ่าน challenge แรก', '1', 'challenge_count', 1, 1],
        ['five_challenges', 'Practice Starter', 'ผ่าน challenge 5 ข้อ', '5', 'challenge_count', 5, 2],
        ['ten_challenges', 'PHP Practice Builder', 'ผ่าน challenge 10 ข้อ', '10', 'challenge_count', 10, 3],
        ['lesson_runner', 'Lesson Runner', 'เรียนจบอย่างน้อย 5 บท', 'L', 'lesson_count', 5, 4],
        ['xp_collector', 'XP Collector', 'สะสม XP อย่างน้อย 300', 'XP', 'xp_total', 300, 5],
    ];

    foreach ($badges as [$code, $name, $description, $icon, $ruleType, $ruleValue, $sort]) {
        Database::execute(
            'INSERT INTO badges (code, name, description, icon, rule_type, rule_value, sort_order, is_active, created_at, updated_at)
             VALUES (:code, :name, :description, :icon, :rule_type, :rule_value, :sort_order, 1, NOW(), NOW())
             ON DUPLICATE KEY UPDATE name = VALUES(name), description = VALUES(description), icon = VALUES(icon), rule_type = VALUES(rule_type),
                rule_value = VALUES(rule_value), sort_order = VALUES(sort_order), is_active = 1, updated_at = NOW()',
            [
                'code' => $code,
                'name' => $name,
                'description' => $description,
                'icon' => $icon,
                'rule_type' => $ruleType,
                'rule_value' => $ruleValue,
                'sort_order' => $sort,
            ]
        );
    }

    $defaultForbidden = ['eval', 'exec', 'shell_exec', 'system'];
    $challenges = [
        ['php-echo-print', 'hello-php-echo', 'แสดงข้อความแรกด้วย echo', 'เขียนคำสั่ง echo เพื่อแสดงข้อความ Hello PHP', "ใช้ echo หรือ print เพื่อแสดงข้อความตามโจทย์", "<?php\n// เขียนคำสั่ง echo เพื่อแสดงข้อความ Hello PHP\n", 'Hello PHP', '', 'keyword', ['echo'], $defaultForbidden, ['ดูตัวอย่าง echo ในบทเรียน', 'ข้อความต้องอยู่ในเครื่องหมาย quote', 'รูปแบบเช่น echo "ข้อความ";'], 40, 1],
        ['php-echo-print', 'print-html-heading', 'แสดงหัวข้อ HTML', 'ใช้ PHP แสดง h1 ที่มีข้อความ Welcome', "ให้ output มี tag h1 และคำว่า Welcome", "<?php\n// แสดง <h1>Welcome</h1>\n", 'Welcome', '', 'keyword', ['echo', '<h1>'], $defaultForbidden, ['PHP สามารถ echo HTML ได้', 'ลองใส่ tag h1 ใน string'], 40, 2],
        ['php-echo-print', 'two-line-output', 'แสดงข้อความสองบรรทัด', 'แสดงคำว่า PHP และ Practice ใน code เดียวกัน', "จะใช้ echo สองครั้งหรือ string เดียวก็ได้", "<?php\n// แสดงคำว่า PHP และ Practice\n", 'Practice', '', 'keyword', ['echo', 'PHP'], $defaultForbidden, ['ต้องมีคำว่า PHP', 'ต้องมีคำว่า Practice'], 40, 3],
        ['php-variables', 'create-name-variable', 'สร้างตัวแปร $name', 'สร้างตัวแปร $name และกำหนดค่าเป็นชื่อของคุณ', "ตัวแปร PHP ต้องขึ้นต้นด้วย $", "<?php\n// สร้างตัวแปร \$name\n", '$name', '', 'keyword', ['$name'], $defaultForbidden, ['ตัวแปรใน PHP ใช้ $ นำหน้า', 'กำหนดค่าด้วยเครื่องหมาย ='], 40, 4],
        ['php-variables', 'echo-name-variable', 'แสดงค่าจากตัวแปร', 'สร้าง $name แล้ว echo ค่านั้นออกมา', "ต้องมีทั้งตัวแปรและคำสั่งแสดงผล", "<?php\n\$name = \"Somchai\";\n// แสดงค่าใน \$name\n", '$name', '', 'keyword', ['$name', 'echo'], $defaultForbidden, ['เริ่มจากตัวแปร $name', 'ใช้ echo $name;'], 40, 5],
        ['php-variables', 'score-variable', 'เก็บคะแนนในตัวแปร', 'สร้าง $score และกำหนดเป็นตัวเลข', "ลองใช้ number โดยไม่ต้องใส่ quote", "<?php\n// สร้าง \$score และกำหนดคะแนน\n", '$score', '', 'keyword', ['$score', '='], $defaultForbidden, ['ตัวเลขไม่จำเป็นต้องมี quote', 'เช่น $score = 80;'], 40, 6],
        ['php-data-types', 'boolean-active', 'สร้าง boolean', 'สร้างตัวแปร $isActive เป็น true', "ฝึกใช้ boolean true/false", "<?php\n// สร้าง \$isActive เป็น true\n", 'true', '', 'keyword', ['$isActive', 'true'], $defaultForbidden, ['boolean ใช้ true หรือ false', 'ชื่อ variable ต้องตรงกับโจทย์'], 40, 7],
        ['php-data-types', 'string-and-number', 'แยก string กับ number', 'สร้าง $name เป็น string และ $age เป็น number', "ใช้ชนิดข้อมูลให้เหมาะกับค่า", "<?php\n// สร้าง \$name และ \$age\n", '$age', '', 'keyword', ['$name', '$age'], $defaultForbidden, ['ชื่อเป็น string', 'อายุเป็น number'], 40, 8],
        ['php-operators', 'calculate-total', 'คำนวณราคารวม', 'ใช้ $price และ $qty คำนวณ $total', "total ควรมาจากการคูณราคาและจำนวน", "<?php\n\$price = 120;\n\$qty = 2;\n// คำนวณ \$total\n", '$total', '', 'keyword', ['$price', '$qty', '$total', '*'], $defaultForbidden, ['ใช้ operator *', 'เก็บผลลัพธ์ใน $total'], 45, 9],
        ['php-operators', 'compare-score', 'เปรียบเทียบคะแนน', 'สร้างเงื่อนไขเปรียบเทียบว่า $score มากกว่าหรือเท่ากับ 50', "ใช้ comparison operator", "<?php\n\$score = 70;\n// เปรียบเทียบคะแนน\n", '>=', '', 'keyword', ['$score', '>='], $defaultForbidden, ['operator มากกว่าหรือเท่ากับคือ >=', 'ใช้กับ $score'], 45, 10],
        ['php-if-else', 'pass-or-fail', 'ตรวจสอบผ่านหรือไม่ผ่าน', 'ใช้ if else เพื่อตรวจ $score >= 50', "ถ้าผ่านให้มีคำว่า pass ถ้าไม่ผ่านให้มี fail", "<?php\n\$score = 45;\n// เขียน if else\n", 'fail', '', 'keyword', ['if', 'else', '$score'], array_merge($defaultForbidden, ['switch']), ['ใช้ if ก่อน', 'เพิ่ม else สำหรับกรณีไม่ผ่าน'], 50, 11],
        ['php-if-else', 'adult-check', 'ตรวจอายุผู้ใช้', 'ใช้ if else ตรวจว่า $age >= 18', "ให้ code มีเงื่อนไขอายุ", "<?php\n\$age = 20;\n// ตรวจอายุ\n", '$age', '', 'keyword', ['if', 'else', '$age', '18'], $defaultForbidden, ['ใช้ตัวแปร $age', 'เทียบกับ 18'], 50, 12],
        ['php-if-else', 'grade-check', 'ตรวจเกรดอย่างง่าย', 'ใช้ if เพื่อตรวจคะแนนระดับ A', "คะแนนตั้งแต่ 80 ให้มีตัวอักษร A", "<?php\n\$score = 88;\n// ตรวจเกรด A\n", 'A', '', 'keyword', ['if', '$score', '80'], $defaultForbidden, ['เริ่มจาก if ($score >= 80)', 'ให้ output หรือค่ามี A'], 50, 13],
        ['php-switch', 'role-switch', 'เลือกข้อความตาม role', 'ใช้ switch กับ $role', "ต้องมี case สำหรับ admin", "<?php\n\$role = \"admin\";\n// เขียน switch\n", 'admin', '', 'keyword', ['switch', 'case', '$role'], $defaultForbidden, ['switch เหมาะกับค่าเดียวหลายกรณี', 'อย่าลืม case'], 45, 14],
        ['php-loops', 'foreach-students', 'แสดงรายชื่อนักเรียนด้วย foreach', 'ใช้ foreach วน array $students', "แสดงชื่อใน array ทีละคน", "<?php\n\$students = [\"Somchai\", \"Suda\", \"Mana\"];\n// ใช้ foreach เพื่อแสดงชื่อทุกคน\n", 'Somchai', '', 'keyword', ['foreach', '$students'], $defaultForbidden, ['ข้อมูลหลายรายการอยู่ใน array', 'ใช้ foreach ($students as $student)'], 50, 15],
        ['php-loops', 'for-one-to-five', 'วนเลข 1 ถึง 5', 'ใช้ for loop เพื่อวนเลข', "ต้องมี for และตัวเลข 5", "<?php\n// ใช้ for loop วน 1 ถึง 5\n", '5', '', 'keyword', ['for', '5'], $defaultForbidden, ['for มีค่าเริ่มต้น เงื่อนไข และการเพิ่มค่า', 'ลองใช้ $i++'], 50, 16],
        ['php-loops', 'while-countdown', 'นับถอยหลังด้วย while', 'ใช้ while loop กับตัวแปร $count', "ฝึก while และการลดค่า", "<?php\n\$count = 3;\n// ใช้ while เพื่อนับถอยหลัง\n", '$count', '', 'keyword', ['while', '$count'], $defaultForbidden, ['while ทำซ้ำตราบเท่าที่เงื่อนไข true', 'อย่าลืมเปลี่ยนค่า $count'], 50, 17],
        ['php-arrays', 'create-colors-array', 'สร้าง array สี', 'สร้าง $colors ที่มี red, green, blue', "ฝึก indexed array", "<?php\n// สร้าง array \$colors\n", 'green', '', 'keyword', ['$colors', '[', 'green'], $defaultForbidden, ['array แบบสั้นใช้ []', 'ใส่ green เป็นหนึ่งในสมาชิก'], 45, 18],
        ['php-arrays', 'associative-user', 'สร้าง associative array', 'สร้าง $user ที่มี key name', "ฝึก array key => value", "<?php\n// สร้าง \$user ที่มี name\n", 'name', '', 'keyword', ['$user', '=>', 'name'], $defaultForbidden, ['associative array ใช้ key => value', 'key name ต้องอยู่ใน array'], 45, 19],
        ['php-functions', 'greet-function', 'สร้าง function greet', 'สร้าง function greet($name) และ return ข้อความทักทาย', "ฝึกประกาศ function พร้อม parameter", "<?php\n// สร้าง function greet(\$name)\n", 'greet', '/function\s+greet\s*\(/i', 'pattern', ['function', 'greet', 'return'], $defaultForbidden, ['function ต้องมีชื่อ greet', 'รับ parameter $name', 'ใช้ return ส่งค่ากลับ'], 60, 20],
    ];

    foreach ($challenges as [$lessonSlug, $slug, $title, $description, $instructions, $starter, $expectedText, $expectedOutput, $mode, $required, $forbidden, $hints, $xp, $sort]) {
        if (!isset($lessonIds[$lessonSlug])) {
            continue;
        }
        Database::execute(
            'INSERT INTO challenges (lesson_id, title, slug, description, instructions, starter_code, expected_text, expected_output, checking_mode, difficulty, xp_reward, sort_order, is_published, created_at, updated_at)
             VALUES (:lesson_id, :title, :slug, :description, :instructions, :starter_code, :expected_text, :expected_output, :checking_mode, "beginner", :xp_reward, :sort_order, 1, NOW(), NOW())
             ON DUPLICATE KEY UPDATE lesson_id = VALUES(lesson_id), title = VALUES(title), description = VALUES(description), instructions = VALUES(instructions),
                starter_code = VALUES(starter_code), expected_text = VALUES(expected_text), expected_output = VALUES(expected_output), checking_mode = VALUES(checking_mode),
                xp_reward = VALUES(xp_reward), sort_order = VALUES(sort_order), is_published = 1, updated_at = NOW()',
            [
                'lesson_id' => $lessonIds[$lessonSlug],
                'title' => $title,
                'slug' => $slug,
                'description' => $description,
                'instructions' => $instructions,
                'starter_code' => $starter,
                'expected_text' => $expectedText,
                'expected_output' => $expectedOutput,
                'checking_mode' => $mode,
                'xp_reward' => $xp,
                'sort_order' => $sort,
            ]
        );
        $challengeId = (int) Database::first('SELECT id FROM challenges WHERE slug = :slug', ['slug' => $slug])['id'];
        Database::execute('DELETE FROM challenge_required_keywords WHERE challenge_id = :id', ['id' => $challengeId]);
        Database::execute('DELETE FROM challenge_forbidden_keywords WHERE challenge_id = :id', ['id' => $challengeId]);
        Database::execute('DELETE FROM challenge_hints WHERE challenge_id = :id', ['id' => $challengeId]);

        foreach ($required as $index => $keyword) {
            Database::execute('INSERT INTO challenge_required_keywords (challenge_id, keyword, sort_order, created_at, updated_at) VALUES (:id, :keyword, :sort, NOW(), NOW())', ['id' => $challengeId, 'keyword' => $keyword, 'sort' => $index + 1]);
        }
        foreach ($forbidden as $index => $keyword) {
            Database::execute('INSERT INTO challenge_forbidden_keywords (challenge_id, keyword, sort_order, created_at, updated_at) VALUES (:id, :keyword, :sort, NOW(), NOW())', ['id' => $challengeId, 'keyword' => $keyword, 'sort' => $index + 1]);
        }
        foreach ($hints as $index => $hint) {
            Database::execute('INSERT INTO challenge_hints (challenge_id, hint_text, sort_order, created_at, updated_at) VALUES (:id, :hint, :sort, NOW(), NOW())', ['id' => $challengeId, 'hint' => $hint, 'sort' => $index + 1]);
        }
    }

    $animations = [
        ['what-is-php', 'Request / Response', 'steps', json_encode(['Browser ส่ง request', 'Server รับ request', 'PHP ประมวลผล', 'Server ส่ง HTML กลับ', 'Browser แสดงผล'], JSON_UNESCAPED_UNICODE), 1],
        ['php-variables', 'Variable Memory', 'html', '<div class="d-flex flex-wrap gap-2"><code>$name =&gt; "Somchai"</code><code>$age =&gt; 12</code><code>$score =&gt; 85</code></div>', 1],
        ['php-if-else', 'If / Else Decision', 'steps', json_encode(['ตรวจ score >= 50', 'ถ้า true แสดงผ่าน', 'ถ้า false แสดงไม่ผ่าน'], JSON_UNESCAPED_UNICODE), 1],
        ['php-loops', 'Loop Process', 'steps', json_encode(['รอบที่ 1', 'รอบที่ 2', 'รอบที่ 3', 'จบ loop เมื่อเงื่อนไข false'], JSON_UNESCAPED_UNICODE), 1],
        ['php-functions', 'Function Input / Output', 'steps', json_encode(['รับ input $name', 'เรียก greet($name)', 'return ข้อความทักทาย'], JSON_UNESCAPED_UNICODE), 1],
    ];

    foreach ($animations as [$lessonSlug, $title, $type, $content, $sort]) {
        if (!isset($lessonIds[$lessonSlug])) {
            continue;
        }
        Database::execute(
            'DELETE FROM lesson_animation_blocks WHERE lesson_id = :lesson_id AND title = :title',
            ['lesson_id' => $lessonIds[$lessonSlug], 'title' => $title]
        );
        Database::execute(
            'INSERT INTO lesson_animation_blocks (lesson_id, title, block_type, content, sort_order, is_published, created_at, updated_at)
             VALUES (:lesson_id, :title, :block_type, :content, :sort_order, 1, NOW(), NOW())',
            ['lesson_id' => $lessonIds[$lessonSlug], 'title' => $title, 'block_type' => $type, 'content' => $content, 'sort_order' => $sort]
        );
    }

    $pdo->commit();
    echo "Phase 2 seed completed: 20 challenges, 5 badges, 5 animation blocks.\n";
} catch (Throwable $exception) {
    $pdo->rollBack();
    throw $exception;
}
