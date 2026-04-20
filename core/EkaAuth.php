<?php
namespace EkaCore;

class EkaAuth
{
    public static function login(int $userId): void
    {
        $_SESSION['eka_user_id'] = $userId;
    }

    public static function check(): bool
    {
        return isset($_SESSION['eka_user_id']);
    }

    public static function logout(): void
    {
        unset($_SESSION['eka_user_id']);
        session_destroy();
    }

    public static function id(): ?int
    {
        return $_SESSION['eka_user_id'] ?? null;
    }
}
