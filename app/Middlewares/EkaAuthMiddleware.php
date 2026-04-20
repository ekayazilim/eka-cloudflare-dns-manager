<?php
namespace EkaApp\Middlewares;

use EkaCore\EkaAuth;
use EkaCore\EkaResponse;

class EkaAuthMiddleware
{
    public function handle(): bool
    {
        if (!EkaAuth::check()) {
            $response = new EkaResponse();
            $response->redirect('/login');
            return false;
        }
        return true;
    }
}
