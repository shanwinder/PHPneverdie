<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Database;
use App\Core\Request;
use App\Core\Session;
use App\Services\CertificateService;

class CertificateController extends Controller
{
    public function index(Request $request): void
    {
        $certificates = Database::select(
            'SELECT certificates.*, users.name AS user_name, courses.title AS course_title
             FROM certificates
             JOIN users ON users.id = certificates.user_id
             JOIN courses ON courses.id = certificates.course_id
             ORDER BY certificates.issued_at DESC LIMIT 100'
        );
        $users = Database::select('SELECT id, name, email FROM users WHERE role = "student" ORDER BY name');
        $courses = Database::select('SELECT id, title FROM courses ORDER BY id');
        $this->render('admin/certificates/index', ['title' => 'Certificates', 'certificates' => $certificates, 'users' => $users, 'courses' => $courses], 'admin');
    }

    public function issue(Request $request): void
    {
        $this->requireCsrf();
        $courseId = (int) $request->input('course_id');
        $projectId = $request->input('project_submission_id') !== '' ? (int) $request->input('project_submission_id') : null;
        $id = (new CertificateService())->issue((int) $request->params['userId'], $courseId, $projectId);
        Session::flash('success', 'ออก certificate แล้ว #' . $id);
        redirect('/admin/certificates');
    }

    public function revoke(Request $request): void
    {
        $this->requireCsrf();
        (new CertificateService())->revoke((int) $request->params['id'], (int) current_user()['id'], (string) $request->input('reason', 'Revoked by admin'));
        Session::flash('success', 'revoke certificate แล้ว');
        redirect('/admin/certificates');
    }
}

