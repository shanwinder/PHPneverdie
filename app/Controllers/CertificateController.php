<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;
use App\Core\Request;

class CertificateController extends Controller
{
    public function index(Request $request): void
    {
        $certificates = Database::select(
            'SELECT certificates.*, courses.title AS course_title
             FROM certificates JOIN courses ON courses.id = certificates.course_id
             WHERE certificates.user_id = :user_id ORDER BY issued_at DESC',
            ['user_id' => current_user()['id']]
        );
        $this->render('certificates/index', ['title' => 'Certificates', 'certificates' => $certificates]);
    }

    public function show(Request $request): void
    {
        $certificate = Database::first(
            'SELECT certificates.*, users.name AS user_name, courses.title AS course_title
             FROM certificates
             JOIN users ON users.id = certificates.user_id
             JOIN courses ON courses.id = certificates.course_id
             WHERE certificates.id = :id AND certificates.user_id = :user_id',
            ['id' => $request->params['id'], 'user_id' => current_user()['id']]
        );
        if (!$certificate) {
            http_response_code(404);
            view('errors/404', ['title' => 'Certificate not found']);
            return;
        }
        $this->render('certificates/show', ['title' => 'Certificate', 'certificate' => $certificate]);
    }

    public function verify(Request $request): void
    {
        $certificate = Database::first(
            'SELECT certificates.*, users.name AS user_name, courses.title AS course_title
             FROM certificates
             JOIN users ON users.id = certificates.user_id
             JOIN courses ON courses.id = certificates.course_id
             WHERE certificates.verification_code = :code',
            ['code' => $request->params['code']]
        );
        $this->render('certificates/verify', ['title' => 'Verify Certificate', 'certificate' => $certificate]);
    }
}

