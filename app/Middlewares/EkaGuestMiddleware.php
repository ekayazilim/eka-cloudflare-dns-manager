<?php
namespace EkaApp\Middlewares;

use EkaCore\EkaAuth;
use EkaCore\EkaResponse;

class EkaGuestMiddleware
{
    public function handle(): bool
    {
        if (EkaAuth::check()) {
            $response = new EkaResponse();
            $response->redirect('/dashboard');
            return false;
        }
        return true;
    }
}
