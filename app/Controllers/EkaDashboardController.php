<?php
namespace EkaApp\Controllers;

use EkaCore\EkaController;
use EkaCore\EkaRequest;
use EkaCore\EkaResponse;
use EkaApp\Services\EkaCloudflareService;
use EkaApp\Models\EkaApiToken;

class EkaDashboardController extends EkaController
{
    public function index(EkaRequest $request, EkaResponse $response): void
    {
        $tokenModel = new EkaApiToken();
        $tokens = $tokenModel->all();
        
        $zonesCount = 0;
        $activeTokenId = $request->input('token_id', $_SESSION['active_token_id'] ?? null);
        
        if (!$activeTokenId && !empty($tokens)) {
            $activeTokenId = $tokens[0]['id'];
        }
        
        if ($activeTokenId) {
            $_SESSION['active_token_id'] = $activeTokenId;
            $cfService = new EkaCloudflareService($activeTokenId);
            $api = $cfService->getApi();
            
            if ($api) {
                $zonesRes = $api->getZones(1);
                if ($zonesRes['success']) {
                    $zonesCount = $zonesRes['result_info']['total_count'] ?? count($zonesRes['result']);
                }
            }
        }

        $response->render('admin', 'admin/dashboard', [
            'tokens' => $tokens,
            'activeTokenId' => $activeTokenId,
            'zonesCount' => $zonesCount
        ]);
    }
}
