<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Database;
use App\Core\Request;
use App\Core\Session;

class AnimationBlockController extends Controller
{
    public function index(Request $request): void
    {
        $this->render('admin/animations/index', [
            'title' => 'Manage Animation Blocks',
            'lessons' => $this->lessons(),
            'blocks' => Database::select(
                'SELECT lesson_animation_blocks.*, lessons.title AS lesson_title
                 FROM lesson_animation_blocks
                 JOIN lessons ON lessons.id = lesson_animation_blocks.lesson_id
                 ORDER BY lessons.sort_order, lesson_animation_blocks.sort_order, lesson_animation_blocks.id'
            ),
            'edit' => null,
        ], 'admin');
    }

    public function edit(Request $request): void
    {
        $edit = Database::first('SELECT * FROM lesson_animation_blocks WHERE id = :id', ['id' => $request->params['id']]);
        $this->render('admin/animations/index', [
            'title' => 'Edit Animation Block',
            'lessons' => $this->lessons(),
            'blocks' => Database::select('SELECT lesson_animation_blocks.*, lessons.title AS lesson_title FROM lesson_animation_blocks JOIN lessons ON lessons.id = lesson_animation_blocks.lesson_id ORDER BY lessons.sort_order, lesson_animation_blocks.sort_order'),
            'edit' => $edit,
        ], 'admin');
    }

    public function store(Request $request): void
    {
        $this->requireCsrf();
        Database::execute(
            'INSERT INTO lesson_animation_blocks (lesson_id, title, block_type, content, sort_order, is_published, created_at, updated_at)
             VALUES (:lesson_id, :title, :block_type, :content, :sort_order, :is_published, NOW(), NOW())',
            $this->data($request)
        );
        $this->saved('/admin/animations');
    }

    public function update(Request $request): void
    {
        $this->requireCsrf();
        Database::execute(
            'UPDATE lesson_animation_blocks SET lesson_id = :lesson_id, title = :title, block_type = :block_type, content = :content,
                sort_order = :sort_order, is_published = :is_published, updated_at = NOW() WHERE id = :id',
            ['id' => $request->params['id']] + $this->data($request)
        );
        $this->saved('/admin/animations');
    }

    public function delete(Request $request): void
    {
        $this->requireCsrf();
        Database::execute('DELETE FROM lesson_animation_blocks WHERE id = :id', ['id' => $request->params['id']]);
        $this->saved('/admin/animations');
    }

    private function data(Request $request): array
    {
        $type = in_array($request->input('block_type'), ['html', 'lottie', 'diagram', 'steps'], true) ? $request->input('block_type') : 'html';
        return [
            'lesson_id' => (int) $request->input('lesson_id'),
            'title' => trim((string) $request->input('title')),
            'block_type' => $type,
            'content' => (string) $request->input('content'),
            'sort_order' => (int) $request->input('sort_order', 0),
            'is_published' => $request->boolean('is_published'),
        ];
    }

    private function lessons(): array
    {
        return Database::select('SELECT id, title FROM lessons ORDER BY sort_order, id');
    }

    private function saved(string $path): never
    {
        Session::flash('success', 'บันทึกข้อมูลเรียบร้อย');
        redirect($path);
    }
}
