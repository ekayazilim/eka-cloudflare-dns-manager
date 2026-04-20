<?php
namespace EkaApp\Controllers;

use EkaCore\EkaController;
use EkaCore\EkaRequest;
use EkaCore\EkaResponse;
use EkaCore\EkaAuth;
use EkaApp\Models\EkaUser;
use EkaCore\EkaLogger;

class EkaAuthController extends EkaController
{
    public function showLogin(EkaRequest $request, EkaResponse $response): void
    {
        $response->render('auth', 'auth/login');
    }

    public function login(EkaRequest $request, EkaResponse $response): void
    {
        $email = $request->input('email');
        $password = $request->input('password');

        if (empty($email) || empty($password)) {
            $response->render('auth', 'auth/login', ['error' => 'Lütfen tüm alanları doldurun.']);
            return;
        }

        $userModel = new EkaUser();
        $user = $userModel->login($email, $password);

        if ($user) {
            EkaAuth::login($user['id']);
            EkaLogger::info("Kullanıcı giriş yaptı: {$email}");
            $response->redirect('/dashboard');
            return;
        }

        EkaLogger::warning("Hatalı giriş denemesi: {$email}");
        $response->render('auth', 'auth/login', ['error' => 'E-posta veya şifre hatalı.']);
    }

    public function logout(EkaRequest $request, EkaResponse $response): void
    {
        $userId = EkaAuth::id();
        EkaAuth::logout();
        EkaLogger::info("Kullanıcı çıkış yaptı (ID: {$userId})");
        $response->redirect('/login');
    }
}
