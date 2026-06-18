<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;
use App\Core\Request;
use App\Core\Session;
use App\Services\SqlPlaygroundService;

class SqlPlaygroundController extends Controller
{
    public function index(Request $request): void
    {
        $rows = Database::select('SELECT * FROM sql_playgrounds WHERE is_published = 1 ORDER BY id');
        $this->render('sql_playgrounds/index', ['title' => 'SQL Playgrounds', 'playgrounds' => $rows]);
    }

    public function show(Request $request): void
    {
        $playground = Database::first('SELECT * FROM sql_playgrounds WHERE slug = :slug AND is_published = 1', ['slug' => $request->params['slug']]);
        if (!$playground) {
            http_response_code(404);
            view('errors/404', ['title' => 'SQL Playground not found']);
            return;
        }
        $this->render('sql_playgrounds/show', ['title' => $playground['title'], 'playground' => $playground, 'result' => null, 'query' => '']);
    }

    public function run(Request $request): void
    {
        $this->requireCsrf();
        $playground = $this->playground((int) $request->params['id']);
        $query = (string) $request->input('query_text', '');
        $result = (new SqlPlaygroundService())->run($playground, $query);
        $this->render('sql_playgrounds/show', ['title' => $playground['title'], 'playground' => $playground, 'result' => $result, 'query' => $query]);
    }

    public function submit(Request $request): void
    {
        $this->requireCsrf();
        $playground = $this->playground((int) $request->params['id']);
        $query = (string) $request->input('query_text', '');
        $result = (new SqlPlaygroundService())->submit((int) current_user()['id'], $playground, $query);
        Session::flash($result['passed'] ? 'success' : 'error', $result['passed'] ? 'ผ่าน SQL Playground' : 'ยังไม่ผ่าน ลองปรับ query อีกครั้ง');
        $this->render('sql_playgrounds/result', ['title' => 'SQL Result', 'playground' => $playground, 'result' => $result, 'query' => $query]);
    }

    private function playground(int $id): array
    {
        $playground = Database::first('SELECT * FROM sql_playgrounds WHERE id = :id AND is_published = 1', ['id' => $id]);
        if (!$playground) {
            http_response_code(404);
            view('errors/404', ['title' => 'SQL Playground not found']);
            exit;
        }
        return $playground;
    }
}

