<?php
namespace EkaCore;

use PDO;
use PDOException;

class EkaDatabase
{
    private static ?PDO $connection = null;

    public static function init(array $config): void
    {
        if (self::$connection === null) {
            try {
                $dsn = "mysql:host={$config['host']};dbname={$config['dbname']};charset={$config['charset']}";
                self::$connection = new PDO($dsn, $config['username'], $config['password'], [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ]);
            } catch (PDOException $e) {
                EkaLogger::error("Veritabanı bağlantı hatası: " . $e->getMessage());
                die("Veritabanı bağlantısı sağlanamadı. Detaylar log kayıtlarındadır.");
            }
        }
    }

    public static function getConnection(): PDO
    {
        return self::$connection;
    }
}
