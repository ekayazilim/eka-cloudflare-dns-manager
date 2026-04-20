<?php
namespace EkaApp\Controllers;

use EkaCore\EkaController;
use EkaCore\EkaRequest;
use EkaCore\EkaResponse;
use EkaApp\Services\EkaCloudflareService;

class EkaDomainController extends EkaController
{
    public function index(EkaRequest $request, EkaResponse $response): void
    {
        $activeTokenId = $_SESSION['active_token_id'] ?? null;
        if (!$activeTokenId) {
            $response->redirect('/dashboard');
            return;
        }

        $page = (int)$request->input('page', 1);
        $search = trim($request->input('search', ''));
        
        $cfService = new EkaCloudflareService($activeTokenId);
        $api = $cfService->getApi();
        
        if (!$api) {
            $response->redirect('/dashboard');
            return;
        }

        $zonesRes = $api->getZones($page, $search);
        $zones = $zonesRes['result'] ?? [];
        $info = $zonesRes['result_info'] ?? ['page' => 1, 'total_pages' => 1];

        $response->render('admin', 'admin/domains/index', [
            'zones' => $zones,
            'info' => $info,
            'search' => $search
        ]);
    }
}
