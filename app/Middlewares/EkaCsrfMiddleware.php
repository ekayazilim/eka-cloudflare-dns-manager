<?php
namespace EkaApp\Middlewares;

use EkaCore\EkaResponse;

class EkaCsrfMiddleware
{
    public function handle(): bool
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!isset($_POST['csrf_token']) || !isset($_SESSION['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
                $response = new EkaResponse();
                http_response_code(403);
                $response->view('errors/500', ['message' => 'CSRF Token Geçersiz. Lütfen sayfayı yenileyin.']);
                return false;
            }
        }
        return true;
    }
}
