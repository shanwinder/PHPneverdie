<?php

declare(strict_types=1);

require dirname(__DIR__, 2) . '/app/bootstrap.php';

use App\Core\Database;

$pdo = Database::connection();
$pdo->beginTransaction();

try {
    $profiles = [
        ['php-cli-safe-small', '8.3', 'php-mastery-sandbox-php:8.3', 3000, 64, '0.5', 0, 20000, 50000],
        ['php-cli-safe-medium', '8.3', 'php-mastery-sandbox-php:8.3', 5000, 128, '0.5', 0, 50000, 70000],
    ];

    foreach ($profiles as [$name, $version, $image, $timeout, $memory, $cpu, $network, $output, $code]) {
        Database::execute(
            'INSERT INTO runtime_profiles (name, language, version, docker_image, timeout_ms, memory_mb, cpu_quota, network_enabled, max_output_bytes, max_code_bytes, is_active, created_at, updated_at)
             VALUES (:name, "php", :version, :docker_image, :timeout_ms, :memory_mb, :cpu_quota, :network_enabled, :max_output_bytes, :max_code_bytes, 1, NOW(), NOW())
             ON DUPLICATE KEY UPDATE version = VALUES(version), docker_image = VALUES(docker_image), timeout_ms = VALUES(timeout_ms), memory_mb = VALUES(memory_mb),
                cpu_quota = VALUES(cpu_quota), network_enabled = VALUES(network_enabled), max_output_bytes = VALUES(max_output_bytes), max_code_bytes = VALUES(max_code_bytes), is_active = 1, updated_at = NOW()',
            [
                'name' => $name,
                'version' => $version,
                'docker_image' => $image,
                'timeout_ms' => $timeout,
                'memory_mb' => $memory,
                'cpu_quota' => $cpu,
                'network_enabled' => $network,
                'max_output_bytes' => $output,
                'max_code_bytes' => $code,
            ]
        );
    }

    $profileId = (int) Database::first('SELECT id FROM runtime_profiles WHERE name = "php-cli-safe-small"')['id'];
    $runtimeChallenges = [
        ['hello-php-echo', 'output', "<?php\necho \"Hello PHP\";\n", 'Hello PHP', []],
        ['echo-name-variable', 'output', "<?php\n\$name = \"Somchai\";\necho \$name;\n", 'Somchai', []],
        ['calculate-total', 'testcase', "<?php\n\$price = (int) trim(fgets(STDIN));\n\$qty = (int) trim(fgets(STDIN));\necho \$price * \$qty;\n", '', [['2 x 120', "120\n2\n", '240'], ['5 x 35', "35\n5\n", '175']]],
        ['pass-or-fail', 'testcase', "<?php\n\$score = (int) trim(fgets(STDIN));\necho \$score >= 50 ? \"pass\" : \"fail\";\n", '', [['Pass case', "80\n", 'pass'], ['Fail case', "40\n", 'fail']]],
        ['role-switch', 'testcase', "<?php\n\$day = trim(fgets(STDIN));\nswitch (\$day) {\n  case \"mon\": echo \"Monday\"; break;\n  case \"fri\": echo \"Friday\"; break;\n  default: echo \"Unknown\";\n}\n", '', [['Monday', "mon\n", 'Monday'], ['Friday', "fri\n", 'Friday'], ['Unknown', "sun\n", 'Unknown']]],
        ['for-one-to-five', 'output', "<?php\nfor (\$i = 1; \$i <= 5; \$i++) {\n  echo \$i;\n}\n", '12345', []],
        ['foreach-students', 'output', "<?php\n\$students = [\"Somchai\", \"Suda\", \"Mana\"];\nforeach (\$students as \$student) {\n  echo \$student . \"\\n\";\n}\n", "Somchai\nSuda\nMana", []],
        ['create-colors-array', 'testcase', "<?php\n\$colors = [\"red\", \"green\", \"blue\"];\necho count(\$colors);\n", '', [['Count colors', '', '3']]],
        ['greet-function', 'testcase', "<?php\nfunction greet(\$name) {\n  return \"Hello \" . \$name;\n}\necho greet(trim(fgets(STDIN)));\n", '', [['Greet Somchai', "Somchai\n", 'Hello Somchai'], ['Greet Suda', "Suda\n", 'Hello Suda']]],
        ['grade-check', 'testcase', "<?php\n\$score = (int) trim(fgets(STDIN));\nif (\$score >= 80) echo \"A\";\nelseif (\$score >= 70) echo \"B\";\nelseif (\$score >= 60) echo \"C\";\nelseif (\$score >= 50) echo \"D\";\nelse echo \"F\";\n", '', [['A', "85\n", 'A'], ['C', "63\n", 'C'], ['F hidden', "40\n", 'F', 1]]],
    ];

    foreach ($runtimeChallenges as [$slug, $mode, $starter, $expected, $cases]) {
        $challenge = Database::first('SELECT id FROM challenges WHERE slug = :slug', ['slug' => $slug]);
        if (!$challenge) {
            continue;
        }
        Database::execute(
            'UPDATE challenges SET starter_code = :starter_code, expected_output = :expected_output, runtime_enabled = 1, runtime_mode = :runtime_mode,
                runtime_profile_id = :runtime_profile_id, run_button_enabled = 1, submit_runtime_enabled = 1, updated_at = NOW()
             WHERE id = :id',
            [
                'id' => $challenge['id'],
                'starter_code' => $starter,
                'expected_output' => $expected,
                'runtime_mode' => $mode,
                'runtime_profile_id' => $profileId,
            ]
        );
        Database::execute('DELETE FROM challenge_test_cases WHERE challenge_id = :id', ['id' => $challenge['id']]);
        foreach ($cases as $index => $case) {
            Database::execute(
                'INSERT INTO challenge_test_cases (challenge_id, name, input_data, expected_output, comparison_mode, is_hidden, weight, sort_order, created_at, updated_at)
                 VALUES (:challenge_id, :name, :input_data, :expected_output, "trimmed", :is_hidden, 1, :sort_order, NOW(), NOW())',
                [
                    'challenge_id' => $challenge['id'],
                    'name' => $case[0],
                    'input_data' => $case[1],
                    'expected_output' => $case[2],
                    'is_hidden' => (int) ($case[3] ?? 0),
                    'sort_order' => $index + 1,
                ]
            );
        }
    }

    $lesson = Database::first('SELECT id FROM lessons ORDER BY id LIMIT 1');
    $lessonId = $lesson ? (int) $lesson['id'] : null;
    $schema = 'CREATE TABLE classrooms (id INTEGER PRIMARY KEY, name TEXT);
CREATE TABLE students (id INTEGER PRIMARY KEY, name TEXT, grade TEXT, score INTEGER, classroom_id INTEGER);';
    $seed = 'INSERT INTO classrooms (id, name) VALUES (1, "ป.5"), (2, "ป.6");
INSERT INTO students (id, name, grade, score, classroom_id) VALUES
(1, "Somchai", "ป.6", 88, 2),
(2, "Suda", "ป.6", 95, 2),
(3, "Mana", "ป.5", 72, 1),
(4, "Anong", "ป.6", 67, 2),
(5, "Krit", "ป.5", 81, 1),
(6, "Nida", "ป.6", 90, 2);';
    $sqlPlaygrounds = [
        ['sql-select-students', 'แสดงรายชื่อนักเรียนทั้งหมด', 'ฝึก SELECT พื้นฐาน', 'SELECT name FROM students', '[{"name":"Somchai"},{"name":"Suda"},{"name":"Mana"},{"name":"Anong"},{"name":"Krit"},{"name":"Nida"}]'],
        ['sql-where-grade-six', 'ค้นหานักเรียนชั้น ป.6', 'ใช้ WHERE เพื่อกรองข้อมูล', 'SELECT name FROM students WHERE grade = "ป.6"', '[{"name":"Somchai"},{"name":"Suda"},{"name":"Anong"},{"name":"Nida"}]'],
        ['sql-order-score', 'เรียงคะแนนจากมากไปน้อย', 'ใช้ ORDER BY กับคะแนน', 'SELECT name, score FROM students ORDER BY score DESC', '[{"name":"Suda","score":95},{"name":"Nida","score":90},{"name":"Somchai","score":88},{"name":"Krit","score":81},{"name":"Mana","score":72},{"name":"Anong","score":67}]'],
        ['sql-top-five', 'แสดง 5 อันดับแรก', 'ใช้ LIMIT จำกัดจำนวนแถว', 'SELECT name, score FROM students ORDER BY score DESC LIMIT 5', '[{"name":"Suda","score":95},{"name":"Nida","score":90},{"name":"Somchai","score":88},{"name":"Krit","score":81},{"name":"Mana","score":72}]'],
        ['sql-join-classrooms', 'JOIN students กับ classrooms', 'เชื่อมข้อมูลนักเรียนกับห้องเรียน', 'SELECT students.name, classrooms.name AS classroom FROM students JOIN classrooms ON classrooms.id = students.classroom_id WHERE classrooms.name = "ป.6"', '[{"name":"Somchai","classroom":"ป.6"},{"name":"Suda","classroom":"ป.6"},{"name":"Anong","classroom":"ป.6"},{"name":"Nida","classroom":"ป.6"}]'],
    ];

    foreach ($sqlPlaygrounds as [$slug, $title, $description, $starterQuery, $expected]) {
        Database::execute(
            'INSERT INTO sql_playgrounds (lesson_id, title, slug, description, schema_sql, seed_sql, expected_result_json, allowed_statement, xp_reward, is_published, created_at, updated_at)
             VALUES (:lesson_id, :title, :slug, :description, :schema_sql, :seed_sql, :expected_result_json, "select_only", 50, 1, NOW(), NOW())
             ON DUPLICATE KEY UPDATE title = VALUES(title), description = VALUES(description), schema_sql = VALUES(schema_sql), seed_sql = VALUES(seed_sql),
                expected_result_json = VALUES(expected_result_json), is_published = 1, updated_at = NOW()',
            [
                'lesson_id' => $lessonId,
                'title' => $title,
                'slug' => $slug,
                'description' => $description . "\nStarter: " . $starterQuery,
                'schema_sql' => $schema,
                'seed_sql' => $seed,
                'expected_result_json' => $expected,
            ]
        );
    }

    $pdo->commit();
    echo "Phase 3 seed completed: runtime profiles, 10 runtime challenges, 5 SQL playgrounds.\n";
} catch (Throwable $exception) {
    $pdo->rollBack();
    throw $exception;
}

