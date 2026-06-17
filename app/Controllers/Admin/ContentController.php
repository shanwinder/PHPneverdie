<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Database;
use App\Core\Request;
use App\Core\Session;

class ContentController extends Controller
{
    public function courses(Request $request): void
    {
        $this->render('admin/courses', [
            'title' => 'Manage Courses',
            'courses' => Database::select('SELECT * FROM courses ORDER BY sort_order, id'),
            'edit' => isset($request->params['id']) ? Database::first('SELECT * FROM courses WHERE id = :id', ['id' => $request->params['id']]) : null,
        ], 'admin');
    }

    public function storeCourse(Request $request): void
    {
        $this->requireCsrf();
        Database::execute(
            'INSERT INTO courses (title, slug, description, sort_order, is_published, created_at, updated_at)
             VALUES (:title, :slug, :description, :sort_order, :is_published, NOW(), NOW())',
            [
                'title' => trim((string) $request->input('title')),
                'slug' => $this->slug($request->input('slug') ?: $request->input('title')),
                'description' => trim((string) $request->input('description')),
                'sort_order' => (int) $request->input('sort_order', 0),
                'is_published' => $request->boolean('is_published'),
            ]
        );
        $this->saved('/admin/courses');
    }

    public function updateCourse(Request $request): void
    {
        $this->requireCsrf();
        Database::execute(
            'UPDATE courses SET title = :title, slug = :slug, description = :description, sort_order = :sort_order, is_published = :is_published, updated_at = NOW() WHERE id = :id',
            [
                'id' => $request->params['id'],
                'title' => trim((string) $request->input('title')),
                'slug' => $this->slug($request->input('slug') ?: $request->input('title')),
                'description' => trim((string) $request->input('description')),
                'sort_order' => (int) $request->input('sort_order', 0),
                'is_published' => $request->boolean('is_published'),
            ]
        );
        $this->saved('/admin/courses');
    }

    public function deleteCourse(Request $request): void
    {
        $this->delete('courses', (int) $request->params['id'], '/admin/courses');
    }

    public function modules(Request $request): void
    {
        $this->render('admin/modules', [
            'title' => 'Manage Modules',
            'courses' => Database::select('SELECT id, title FROM courses ORDER BY sort_order, id'),
            'modules' => Database::select(
                'SELECT modules.*, courses.title AS course_title FROM modules JOIN courses ON courses.id = modules.course_id ORDER BY courses.sort_order, modules.sort_order'
            ),
            'edit' => isset($request->params['id']) ? Database::first('SELECT * FROM modules WHERE id = :id', ['id' => $request->params['id']]) : null,
        ], 'admin');
    }

    public function storeModule(Request $request): void
    {
        $this->requireCsrf();
        Database::execute(
            'INSERT INTO modules (course_id, title, slug, description, sort_order, is_published, created_at, updated_at)
             VALUES (:course_id, :title, :slug, :description, :sort_order, :is_published, NOW(), NOW())',
            [
                'course_id' => (int) $request->input('course_id'),
                'title' => trim((string) $request->input('title')),
                'slug' => $this->slug($request->input('slug') ?: $request->input('title')),
                'description' => trim((string) $request->input('description')),
                'sort_order' => (int) $request->input('sort_order', 0),
                'is_published' => $request->boolean('is_published'),
            ]
        );
        $this->saved('/admin/modules');
    }

    public function updateModule(Request $request): void
    {
        $this->requireCsrf();
        Database::execute(
            'UPDATE modules SET course_id = :course_id, title = :title, slug = :slug, description = :description, sort_order = :sort_order, is_published = :is_published, updated_at = NOW() WHERE id = :id',
            [
                'id' => $request->params['id'],
                'course_id' => (int) $request->input('course_id'),
                'title' => trim((string) $request->input('title')),
                'slug' => $this->slug($request->input('slug') ?: $request->input('title')),
                'description' => trim((string) $request->input('description')),
                'sort_order' => (int) $request->input('sort_order', 0),
                'is_published' => $request->boolean('is_published'),
            ]
        );
        $this->saved('/admin/modules');
    }

    public function deleteModule(Request $request): void
    {
        $this->delete('modules', (int) $request->params['id'], '/admin/modules');
    }

    public function lessons(Request $request): void
    {
        $this->render('admin/lessons', [
            'title' => 'Manage Lessons',
            'modules' => Database::select('SELECT modules.id, modules.title, courses.title AS course_title FROM modules JOIN courses ON courses.id = modules.course_id ORDER BY courses.sort_order, modules.sort_order'),
            'lessons' => Database::select(
                'SELECT lessons.*, modules.title AS module_title FROM lessons JOIN modules ON modules.id = lessons.module_id ORDER BY modules.sort_order, lessons.sort_order'
            ),
            'edit' => isset($request->params['id']) ? Database::first('SELECT * FROM lessons WHERE id = :id', ['id' => $request->params['id']]) : null,
        ], 'admin');
    }

    public function storeLesson(Request $request): void
    {
        $this->requireCsrf();
        Database::execute(
            'INSERT INTO lessons (module_id, title, slug, summary, content, difficulty, estimated_minutes, xp_reward, sort_order, is_published, created_at, updated_at)
             VALUES (:module_id, :title, :slug, :summary, :content, :difficulty, :estimated_minutes, :xp_reward, :sort_order, :is_published, NOW(), NOW())',
            $this->lessonData($request)
        );
        $this->saved('/admin/lessons');
    }

    public function updateLesson(Request $request): void
    {
        $this->requireCsrf();
        Database::execute(
            'UPDATE lessons SET module_id = :module_id, title = :title, slug = :slug, summary = :summary, content = :content, difficulty = :difficulty, estimated_minutes = :estimated_minutes, xp_reward = :xp_reward, sort_order = :sort_order, is_published = :is_published, updated_at = NOW() WHERE id = :id',
            ['id' => $request->params['id']] + $this->lessonData($request)
        );
        $this->saved('/admin/lessons');
    }

    public function deleteLesson(Request $request): void
    {
        $this->delete('lessons', (int) $request->params['id'], '/admin/lessons');
    }

    public function quizzes(Request $request): void
    {
        $this->render('admin/quizzes', [
            'title' => 'Manage Quizzes',
            'lessons' => Database::select('SELECT id, title FROM lessons ORDER BY sort_order, id'),
            'quizzes' => Database::select(
                'SELECT quizzes.*, lessons.title AS lesson_title FROM quizzes JOIN lessons ON lessons.id = quizzes.lesson_id ORDER BY lessons.sort_order'
            ),
            'edit' => isset($request->params['id']) ? Database::first('SELECT * FROM quizzes WHERE id = :id', ['id' => $request->params['id']]) : null,
        ], 'admin');
    }

    public function storeQuiz(Request $request): void
    {
        $this->requireCsrf();
        Database::execute(
            'INSERT INTO quizzes (lesson_id, title, passing_score, max_attempts, is_published, created_at, updated_at)
             VALUES (:lesson_id, :title, :passing_score, :max_attempts, :is_published, NOW(), NOW())',
            $this->quizData($request)
        );
        $this->saved('/admin/quizzes');
    }

    public function updateQuiz(Request $request): void
    {
        $this->requireCsrf();
        Database::execute(
            'UPDATE quizzes SET lesson_id = :lesson_id, title = :title, passing_score = :passing_score, max_attempts = :max_attempts, is_published = :is_published, updated_at = NOW() WHERE id = :id',
            ['id' => $request->params['id']] + $this->quizData($request)
        );
        $this->saved('/admin/quizzes');
    }

    public function deleteQuiz(Request $request): void
    {
        $this->delete('quizzes', (int) $request->params['id'], '/admin/quizzes');
    }

    public function questions(Request $request): void
    {
        $quizzes = Database::select(
            'SELECT quizzes.id, quizzes.title, lessons.title AS lesson_title FROM quizzes JOIN lessons ON lessons.id = quizzes.lesson_id ORDER BY lessons.sort_order'
        );
        $questions = Database::select(
            'SELECT quiz_questions.*, quizzes.title AS quiz_title FROM quiz_questions JOIN quizzes ON quizzes.id = quiz_questions.quiz_id ORDER BY quizzes.id, quiz_questions.sort_order'
        );
        foreach ($questions as &$question) {
            $question['choices'] = Database::select('SELECT * FROM quiz_choices WHERE question_id = :id ORDER BY sort_order, id', ['id' => $question['id']]);
        }

        $this->render('admin/questions', ['title' => 'Manage Questions', 'quizzes' => $quizzes, 'questions' => $questions], 'admin');
    }

    public function storeQuestion(Request $request): void
    {
        $this->requireCsrf();
        Database::execute(
            'INSERT INTO quiz_questions (quiz_id, question_text, explanation, sort_order, created_at, updated_at)
             VALUES (:quiz_id, :question_text, :explanation, :sort_order, NOW(), NOW())',
            [
                'quiz_id' => (int) $request->input('quiz_id'),
                'question_text' => trim((string) $request->input('question_text')),
                'explanation' => trim((string) $request->input('explanation')),
                'sort_order' => (int) $request->input('sort_order', 0),
            ]
        );
        $this->saved('/admin/questions');
    }

    public function deleteQuestion(Request $request): void
    {
        $this->delete('quiz_questions', (int) $request->params['id'], '/admin/questions');
    }

    public function updateQuestion(Request $request): void
    {
        $this->requireCsrf();
        Database::execute(
            'UPDATE quiz_questions SET question_text = :question_text, explanation = :explanation, sort_order = :sort_order, updated_at = NOW() WHERE id = :id',
            [
                'id' => $request->params['id'],
                'question_text' => trim((string) $request->input('question_text')),
                'explanation' => trim((string) $request->input('explanation')),
                'sort_order' => (int) $request->input('sort_order', 0),
            ]
        );
        $this->saved('/admin/questions');
    }

    public function storeChoice(Request $request): void
    {
        $this->requireCsrf();
        Database::execute(
            'INSERT INTO quiz_choices (question_id, choice_text, is_correct, sort_order, created_at, updated_at)
             VALUES (:question_id, :choice_text, :is_correct, :sort_order, NOW(), NOW())',
            [
                'question_id' => (int) $request->input('question_id'),
                'choice_text' => trim((string) $request->input('choice_text')),
                'is_correct' => $request->boolean('is_correct'),
                'sort_order' => (int) $request->input('sort_order', 0),
            ]
        );
        $this->saved('/admin/questions');
    }

    public function deleteChoice(Request $request): void
    {
        $this->delete('quiz_choices', (int) $request->params['id'], '/admin/questions');
    }

    public function updateChoice(Request $request): void
    {
        $this->requireCsrf();
        Database::execute(
            'UPDATE quiz_choices SET choice_text = :choice_text, is_correct = :is_correct, sort_order = :sort_order, updated_at = NOW() WHERE id = :id',
            [
                'id' => $request->params['id'],
                'choice_text' => trim((string) $request->input('choice_text')),
                'is_correct' => $request->boolean('is_correct'),
                'sort_order' => (int) $request->input('sort_order', 0),
            ]
        );
        $this->saved('/admin/questions');
    }

    public function users(Request $request): void
    {
        $this->render('admin/users', [
            'title' => 'Manage Users',
            'roles' => Database::select('SELECT * FROM roles ORDER BY id'),
            'users' => Database::select('SELECT users.*, roles.name AS role FROM users JOIN roles ON roles.id = users.role_id ORDER BY users.created_at DESC'),
        ], 'admin');
    }

    public function updateUser(Request $request): void
    {
        $this->requireCsrf();
        Database::execute(
            'UPDATE users SET role_id = :role_id, status = :status, updated_at = NOW() WHERE id = :id',
            [
                'id' => $request->params['id'],
                'role_id' => (int) $request->input('role_id'),
                'status' => $request->input('status') === 'inactive' ? 'inactive' : 'active',
            ]
        );
        $this->saved('/admin/users');
    }

    public function reports(Request $request): void
    {
        $rows = Database::select(
            'SELECT users.name, users.email, users.xp, users.level,
                COUNT(DISTINCT CASE WHEN lesson_progress.status = "completed" THEN lesson_progress.lesson_id END) AS completed_lessons,
                COUNT(DISTINCT CASE WHEN quiz_attempts.passed = 1 THEN quiz_attempts.quiz_id END) AS passed_quizzes
             FROM users
             LEFT JOIN lesson_progress ON lesson_progress.user_id = users.id
             LEFT JOIN quiz_attempts ON quiz_attempts.user_id = users.id
             GROUP BY users.id
             ORDER BY users.xp DESC, users.name'
        );
        $this->render('admin/reports', ['title' => 'Basic Reports', 'rows' => $rows], 'admin');
    }

    private function lessonData(Request $request): array
    {
        return [
            'module_id' => (int) $request->input('module_id'),
            'title' => trim((string) $request->input('title')),
            'slug' => $this->slug($request->input('slug') ?: $request->input('title')),
            'summary' => trim((string) $request->input('summary')),
            'content' => (string) $request->input('content'),
            'difficulty' => in_array($request->input('difficulty'), ['beginner', 'intermediate', 'advanced'], true) ? $request->input('difficulty') : 'beginner',
            'estimated_minutes' => (int) $request->input('estimated_minutes', 10),
            'xp_reward' => (int) $request->input('xp_reward', 30),
            'sort_order' => (int) $request->input('sort_order', 0),
            'is_published' => $request->boolean('is_published'),
        ];
    }

    private function quizData(Request $request): array
    {
        $maxAttempts = trim((string) $request->input('max_attempts'));
        return [
            'lesson_id' => (int) $request->input('lesson_id'),
            'title' => trim((string) $request->input('title')),
            'passing_score' => (int) $request->input('passing_score', 70),
            'max_attempts' => $maxAttempts === '' ? null : (int) $maxAttempts,
            'is_published' => $request->boolean('is_published'),
        ];
    }

    private function saved(string $path): never
    {
        Session::flash('success', 'บันทึกข้อมูลเรียบร้อย');
        redirect($path);
    }

    private function delete(string $table, int $id, string $path): void
    {
        $this->requireCsrf();
        Database::execute("DELETE FROM {$table} WHERE id = :id", ['id' => $id]);
        $this->saved($path);
    }

    private function slug(mixed $value): string
    {
        $slug = strtolower(trim((string) $value));
        $slug = preg_replace('/[^a-z0-9ก-๙]+/iu', '-', $slug) ?: 'item';
        return trim($slug, '-');
    }
}
