<?php
namespace EkaApp\Controllers;

use EkaCore\EkaController;
use EkaCore\EkaRequest;
use EkaCore\EkaResponse;

class EkaLogsController extends EkaController
{
    public function index(EkaRequest $request, EkaResponse $response): void
    {
        $logFile = __DIR__ . '/../../storage/logs/app.log';
        $logs = [];
        
        if (file_exists($logFile)) {
            $lines = file($logFile);
            $lines = array_reverse($lines);
            $logs = array_slice($lines, 0, 500); 
        }

        $response->render('admin', 'admin/logs/index', [
            'logs' => $logs
        ]);
    }

    public function clear(EkaRequest $request, EkaResponse $response): void
    {
        $logFile = __DIR__ . '/../../storage/logs/app.log';
        if (file_exists($logFile)) {
            file_put_contents($logFile, '');
        }
        
        $response->redirect('/settings/logs');
    }
}
