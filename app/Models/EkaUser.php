<?php
namespace EkaApp\Models;

use EkaCore\EkaModel;

class EkaUser extends EkaModel
{
    protected string $table = 'users';

    public function login(string $email, string $password): ?array
    {
        $user = $this->firstWhere('email', '=', $email);
        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        return null;
    }
}
