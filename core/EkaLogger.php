<?php
namespace EkaCore;

class EkaLogger
{
    private static string $logFile = '';

    public static function init(string $file): void
    {
        self::$logFile = $file;
    }

    public static function info(string $message): void
    {
        self::write('INFO', $message);
    }

    public static function error(string $message): void
    {
        self::write('ERROR', $message);
    }

    public static function warning(string $message): void
    {
        self::write('WARNING', $message);
    }

    private static function write(string $level, string $message): void
    {
        if (empty(self::$logFile)) {
            return;
        }

        $date = date('Y-m-d H:i:s');
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'UNKNOWN';
        $logLine = "[{$date}] [{$ip}] [{$level}] {$message}" . PHP_EOL;
        
        file_put_contents(self::$logFile, $logLine, FILE_APPEND | LOCK_EX);
    }
}
