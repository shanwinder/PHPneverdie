<?php

use App\Controllers\Admin\AdminDashboardController;
use App\Controllers\Admin\AnimationBlockController;
use App\Controllers\Admin\BadgeController as AdminBadgeController;
use App\Controllers\Admin\CertificateController as AdminCertificateController;
use App\Controllers\Admin\ChallengeController as AdminChallengeController;
use App\Controllers\Admin\ExecutionMonitorController;
use App\Controllers\Admin\ProjectReviewController;
use App\Controllers\Admin\RuntimeProfileController;
use App\Controllers\Admin\SqlPlaygroundController as AdminSqlPlaygroundController;
use App\Controllers\Admin\TestCaseController;
use App\Controllers\BadgeController;
use App\Controllers\CertificateController;
use App\Controllers\ChallengeController;
use App\Controllers\Admin\ContentController;
use App\Controllers\AuthController;
use App\Controllers\CourseController;
use App\Controllers\DashboardController;
use App\Controllers\ExecutionController;
use App\Controllers\HomeController;
use App\Controllers\LeaderboardController;
use App\Controllers\LessonController;
use App\Controllers\ProjectSubmissionController;
use App\Controllers\QuizController;
use App\Controllers\SqlPlaygroundController;

$router->get('/', [HomeController::class, 'index']);
$router->get('/login', [AuthController::class, 'showLogin']);
$router->post('/login', [AuthController::class, 'login']);
$router->get('/register', [AuthController::class, 'showRegister']);
$router->post('/register', [AuthController::class, 'register']);
$router->post('/logout', [AuthController::class, 'logout'], ['auth']);

$router->get('/dashboard', [DashboardController::class, 'index'], ['auth']);
$router->get('/courses', [CourseController::class, 'index'], ['auth']);
$router->get('/courses/{slug}', [CourseController::class, 'show'], ['auth']);
$router->get('/lessons/{slug}', [LessonController::class, 'show'], ['auth']);
$router->post('/lessons/{id}/start', [LessonController::class, 'start'], ['auth']);
$router->get('/lessons/{id}/quiz', [QuizController::class, 'show'], ['auth']);
$router->post('/lessons/{id}/quiz', [QuizController::class, 'submit'], ['auth']);
$router->get('/lessons/{id}/practice', [ChallengeController::class, 'lessonPractice'], ['auth']);

$router->get('/challenges', [ChallengeController::class, 'index'], ['auth']);
$router->get('/challenges/{slug}', [ChallengeController::class, 'show'], ['auth']);
$router->get('/challenges/{slug}/result', [ChallengeController::class, 'result'], ['auth']);
$router->post('/challenges/{id}/run', [ExecutionController::class, 'run'], ['auth']);
$router->post('/challenges/{id}/submit-runtime', [ExecutionController::class, 'submitRuntime'], ['auth']);
$router->post('/challenges/{id}/submit', [ChallengeController::class, 'submit'], ['auth']);
$router->get('/challenges/{id}/history', [ChallengeController::class, 'history'], ['auth']);
$router->post('/challenges/{id}/hint', [ChallengeController::class, 'hint'], ['auth']);
$router->get('/executions/{id}', [ExecutionController::class, 'show'], ['auth']);
$router->get('/executions/{id}/status', [ExecutionController::class, 'status'], ['auth']);
$router->get('/sql-playgrounds', [SqlPlaygroundController::class, 'index'], ['auth']);
$router->get('/sql-playgrounds/{slug}', [SqlPlaygroundController::class, 'show'], ['auth']);
$router->post('/sql-playgrounds/{id}/run', [SqlPlaygroundController::class, 'run'], ['auth']);
$router->post('/sql-playgrounds/{id}/submit', [SqlPlaygroundController::class, 'submit'], ['auth']);
$router->get('/projects', [ProjectSubmissionController::class, 'index'], ['auth']);
$router->get('/projects/create', [ProjectSubmissionController::class, 'create'], ['auth']);
$router->post('/projects', [ProjectSubmissionController::class, 'store'], ['auth']);
$router->get('/projects/{id}', [ProjectSubmissionController::class, 'show'], ['auth']);
$router->post('/projects/{id}/update', [ProjectSubmissionController::class, 'update'], ['auth']);
$router->post('/projects/{id}/submit', [ProjectSubmissionController::class, 'submit'], ['auth']);
$router->get('/certificates', [CertificateController::class, 'index'], ['auth']);
$router->get('/certificates/{id}', [CertificateController::class, 'show'], ['auth']);
$router->get('/verify-certificate/{code}', [CertificateController::class, 'verify']);
$router->get('/badges', [BadgeController::class, 'index'], ['auth']);
$router->get('/leaderboard', [LeaderboardController::class, 'index'], ['auth']);

$router->get('/admin', [AdminDashboardController::class, 'index'], ['admin']);
$router->get('/admin/courses', [ContentController::class, 'courses'], ['admin']);
$router->get('/admin/courses/{id}/edit', [ContentController::class, 'courses'], ['admin']);
$router->post('/admin/courses', [ContentController::class, 'storeCourse'], ['admin']);
$router->post('/admin/courses/{id}', [ContentController::class, 'updateCourse'], ['admin']);
$router->post('/admin/courses/{id}/delete', [ContentController::class, 'deleteCourse'], ['admin']);

$router->get('/admin/modules', [ContentController::class, 'modules'], ['admin']);
$router->get('/admin/modules/{id}/edit', [ContentController::class, 'modules'], ['admin']);
$router->post('/admin/modules', [ContentController::class, 'storeModule'], ['admin']);
$router->post('/admin/modules/{id}', [ContentController::class, 'updateModule'], ['admin']);
$router->post('/admin/modules/{id}/delete', [ContentController::class, 'deleteModule'], ['admin']);

$router->get('/admin/lessons', [ContentController::class, 'lessons'], ['admin']);
$router->get('/admin/lessons/{id}/edit', [ContentController::class, 'lessons'], ['admin']);
$router->post('/admin/lessons', [ContentController::class, 'storeLesson'], ['admin']);
$router->post('/admin/lessons/{id}', [ContentController::class, 'updateLesson'], ['admin']);
$router->post('/admin/lessons/{id}/delete', [ContentController::class, 'deleteLesson'], ['admin']);

$router->get('/admin/quizzes', [ContentController::class, 'quizzes'], ['admin']);
$router->get('/admin/quizzes/{id}/edit', [ContentController::class, 'quizzes'], ['admin']);
$router->post('/admin/quizzes', [ContentController::class, 'storeQuiz'], ['admin']);
$router->post('/admin/quizzes/{id}', [ContentController::class, 'updateQuiz'], ['admin']);
$router->post('/admin/quizzes/{id}/delete', [ContentController::class, 'deleteQuiz'], ['admin']);

$router->get('/admin/questions', [ContentController::class, 'questions'], ['admin']);
$router->post('/admin/questions', [ContentController::class, 'storeQuestion'], ['admin']);
$router->post('/admin/questions/{id}', [ContentController::class, 'updateQuestion'], ['admin']);
$router->post('/admin/questions/{id}/delete', [ContentController::class, 'deleteQuestion'], ['admin']);
$router->post('/admin/choices', [ContentController::class, 'storeChoice'], ['admin']);
$router->post('/admin/choices/{id}', [ContentController::class, 'updateChoice'], ['admin']);
$router->post('/admin/choices/{id}/delete', [ContentController::class, 'deleteChoice'], ['admin']);

$router->get('/admin/users', [ContentController::class, 'users'], ['admin']);
$router->post('/admin/users/{id}', [ContentController::class, 'updateUser'], ['admin']);
$router->get('/admin/reports', [ContentController::class, 'reports'], ['admin']);

$router->get('/admin/challenges', [AdminChallengeController::class, 'index'], ['admin']);
$router->get('/admin/challenges/create', [AdminChallengeController::class, 'create'], ['admin']);
$router->post('/admin/challenges', [AdminChallengeController::class, 'store'], ['admin']);
$router->get('/admin/challenges/{id}/edit', [AdminChallengeController::class, 'edit'], ['admin']);
$router->post('/admin/challenges/{id}', [AdminChallengeController::class, 'update'], ['admin']);
$router->post('/admin/challenges/{id}/delete', [AdminChallengeController::class, 'delete'], ['admin']);
$router->get('/admin/challenges/{id}/submissions', [AdminChallengeController::class, 'submissions'], ['admin']);
$router->get('/admin/challenges/{id}/test-cases', [TestCaseController::class, 'index'], ['admin']);
$router->post('/admin/challenges/{id}/test-cases', [TestCaseController::class, 'store'], ['admin']);
$router->post('/admin/test-cases/{id}', [TestCaseController::class, 'update'], ['admin']);
$router->post('/admin/test-cases/{id}/delete', [TestCaseController::class, 'delete'], ['admin']);

$router->get('/admin/runtime-profiles', [RuntimeProfileController::class, 'index'], ['admin']);
$router->post('/admin/runtime-profiles', [RuntimeProfileController::class, 'store'], ['admin']);
$router->get('/admin/runtime-profiles/{id}/edit', [RuntimeProfileController::class, 'edit'], ['admin']);
$router->post('/admin/runtime-profiles/{id}', [RuntimeProfileController::class, 'update'], ['admin']);
$router->get('/admin/executions', [ExecutionMonitorController::class, 'index'], ['admin']);
$router->get('/admin/executions/{id}', [ExecutionMonitorController::class, 'show'], ['admin']);
$router->post('/admin/executions/{id}/cancel', [ExecutionMonitorController::class, 'cancel'], ['admin']);
$router->get('/admin/sql-playgrounds', [AdminSqlPlaygroundController::class, 'index'], ['admin']);
$router->post('/admin/sql-playgrounds', [AdminSqlPlaygroundController::class, 'store'], ['admin']);
$router->get('/admin/sql-playgrounds/{id}/edit', [AdminSqlPlaygroundController::class, 'edit'], ['admin']);
$router->post('/admin/sql-playgrounds/{id}', [AdminSqlPlaygroundController::class, 'update'], ['admin']);
$router->post('/admin/sql-playgrounds/{id}/delete', [AdminSqlPlaygroundController::class, 'delete'], ['admin']);
$router->get('/admin/project-reviews', [ProjectReviewController::class, 'index'], ['admin']);
$router->get('/admin/project-reviews/{id}', [ProjectReviewController::class, 'show'], ['admin']);
$router->post('/admin/project-reviews/{id}/review', [ProjectReviewController::class, 'review'], ['admin']);
$router->get('/admin/certificates', [AdminCertificateController::class, 'index'], ['admin']);
$router->post('/admin/certificates/issue/{userId}', [AdminCertificateController::class, 'issue'], ['admin']);
$router->post('/admin/certificates/{id}/revoke', [AdminCertificateController::class, 'revoke'], ['admin']);

$router->get('/admin/badges', [AdminBadgeController::class, 'index'], ['admin']);
$router->post('/admin/badges', [AdminBadgeController::class, 'store'], ['admin']);
$router->get('/admin/badges/{id}/edit', [AdminBadgeController::class, 'edit'], ['admin']);
$router->post('/admin/badges/{id}', [AdminBadgeController::class, 'update'], ['admin']);
$router->post('/admin/badges/{id}/delete', [AdminBadgeController::class, 'delete'], ['admin']);

$router->get('/admin/animations', [AnimationBlockController::class, 'index'], ['admin']);
$router->post('/admin/animations', [AnimationBlockController::class, 'store'], ['admin']);
$router->get('/admin/animations/{id}/edit', [AnimationBlockController::class, 'edit'], ['admin']);
$router->post('/admin/animations/{id}', [AnimationBlockController::class, 'update'], ['admin']);
$router->post('/admin/animations/{id}/delete', [AnimationBlockController::class, 'delete'], ['admin']);
