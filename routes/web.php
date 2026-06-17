<?php

use App\Controllers\Admin\AdminDashboardController;
use App\Controllers\Admin\ContentController;
use App\Controllers\AuthController;
use App\Controllers\CourseController;
use App\Controllers\DashboardController;
use App\Controllers\HomeController;
use App\Controllers\LessonController;
use App\Controllers\QuizController;

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
