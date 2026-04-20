<?php
namespace EkaApp\Controllers;

use EkaCore\EkaController;
use EkaCore\EkaRequest;
use EkaCore\EkaResponse;
use EkaApp\Services\EkaCloudflareService;
use EkaCore\EkaLogger;

class EkaDnsController extends EkaController
{
    private function getApi(EkaResponse $response)
    {
        $activeTokenId = $_SESSION['active_token_id'] ?? null;
        if (!$activeTokenId) {
            $response->redirect('/dashboard');
            return null;
        }
        $cfService = new EkaCloudflareService($activeTokenId);
        $api = $cfService->getApi();
        if (!$api) {
            $response->redirect('/dashboard');
        }
        return $api;
    }

    public function index(EkaRequest $request, EkaResponse $response): void
    {
        $api = $this->getApi($response);
        if (!$api) return;

        $zoneId = $request->getParam('zoneId');
        $page = (int)$request->input('page', 1);
        
        $zoneRes = $api->getZone($zoneId);
        $zone = $zoneRes['result'] ?? null;

        if (!$zone) {
            $response->redirect('/domains');
            return;
        }

        $recordsRes = $api->getDnsRecords($zoneId, $page);
        $records = $recordsRes['result'] ?? [];
        $info = $recordsRes['result_info'] ?? ['page' => 1, 'total_pages' => 1];

        $response->render('admin', 'admin/dns/index', [
            'zone' => $zone,
            'records' => $records,
            'info' => $info
        ]);
    }

    public function create(EkaRequest $request, EkaResponse $response): void
    {
        $api = $this->getApi($response);
        if (!$api) return;

        $zoneId = $request->getParam('zoneId');
        
        $data = [
            'type' => $request->input('type'),
            'name' => $request->input('name'),
            'content' => $request->input('content'),
            'ttl' => (int)$request->input('ttl', 1),
            'proxied' => $request->input('proxied') === '1'
        ];

        if ($data['type'] === 'MX') {
            $data['priority'] = (int)$request->input('priority', 10);
        }

        $existing = $api->getDnsRecords($zoneId, 1, $data['type'], $data['name']);
        $isDuplicate = false;
        if (isset($existing['result'])) {
            foreach ($existing['result'] as $rec) {
                if ($rec['content'] === $data['content']) {
                    $isDuplicate = true;
                    break;
                }
            }
        }

        if ($isDuplicate) {
            EkaLogger::info("DNS Kaydı Atlandı (Zaten Var): {$data['name']} -> {$data['content']}");
            $_SESSION['flash_warning'] = 'Kayıt zaten mevcut olduğu için atlandı.';
        } else {
            $res = $api->createDnsRecord($zoneId, $data);
            if ($res['success']) {
                EkaLogger::info("DNS Kaydı Eklendi: {$data['name']} ({$data['type']}) -> {$data['content']}");
                $_SESSION['flash_success'] = 'Kayıt başarıyla eklendi.';
            } else {
                $_SESSION['flash_error'] = 'Kayıt eklenirken hata oluştu.';
            }
        }

        $response->redirect("/dns/{$zoneId}");
    }

    public function delete(EkaRequest $request, EkaResponse $response): void
    {
        $api = $this->getApi($response);
        if (!$api) return;

        $zoneId = $request->getParam('zoneId');
        $recordId = $request->input('record_id');

        $res = $api->deleteDnsRecord($zoneId, $recordId);
        if ($res['success']) {
            EkaLogger::info("DNS Kaydı Silindi: ID {$recordId}");
            $_SESSION['flash_success'] = 'Kayıt başarıyla silindi.';
        } else {
            $_SESSION['flash_error'] = 'Kayıt silinirken hata oluştu.';
        }

        $response->redirect("/dns/{$zoneId}");
    }

    public function bulkForm(EkaRequest $request, EkaResponse $response): void
    {
        $api = $this->getApi($response);
        if (!$api) return;

        $zoneId = $request->getParam('zoneId');
        $zoneRes = $api->getZone($zoneId);
        $zone = $zoneRes['result'] ?? null;

        if (!$zone) {
            $response->redirect('/domains');
            return;
        }

        $response->render('admin', 'admin/dns/bulk', [
            'zone' => $zone
        ]);
    }

    public function bulkAdd(EkaRequest $request, EkaResponse $response): void
    {
        $api = $this->getApi($response);
        if (!$api) return;

        $zoneId = $request->getParam('zoneId');
        
        $zoneRes = $api->getZone($zoneId);
        $domainName = $zoneRes['result']['name'] ?? '';
        
        $subdomains = explode("\n", str_replace("\r", "", trim($request->input('subdomains'))));
        $type = $request->input('type');
        $content = $request->input('content');
        $proxied = $request->input('proxied') === '1';
        
        $added = 0;
        $skipped = 0;
        $failed = 0;

        foreach ($subdomains as $sub) {
            $sub = trim($sub);
            if (empty($sub)) continue;

            $fullName = $sub === '@' ? $domainName : "{$sub}.{$domainName}";

            $existing = $api->getDnsRecords($zoneId, 1, $type, $fullName);
            $isDuplicate = false;
            
            if (isset($existing['result'])) {
                foreach ($existing['result'] as $rec) {
                    if ($rec['content'] === $content) {
                        $isDuplicate = true;
                        break;
                    }
                }
            }

            if ($isDuplicate) {
                $skipped++;
                continue;
            }

            $res = $api->createDnsRecord($zoneId, [
                'type' => $type,
                'name' => $fullName,
                'content' => $content,
                'ttl' => 1,
                'proxied' => $proxied
            ]);

            if ($res['success']) {
                $added++;
            } else {
                $failed++;
            }
        }

        EkaLogger::info("Toplu İşlem: {$added} eklendi, {$skipped} atlandı, {$failed} hata.");
        $_SESSION['flash_success'] = "{$added} kayıt eklendi, {$skipped} atlandı, {$failed} hata.";
        $response->redirect("/dns/{$zoneId}");
    }

    public function missingForm(EkaRequest $request, EkaResponse $response): void
    {
        $api = $this->getApi($response);
        if (!$api) return;

        $zoneId = $request->getParam('zoneId');
        $zoneRes = $api->getZone($zoneId);
        $zone = $zoneRes['result'] ?? null;

        if (!$zone) {
            $response->redirect('/domains');
            return;
        }

        $allRecordsRes = $api->getDnsRecords($zoneId, 1, 'A');
        $existingRecords = $allRecordsRes['result'] ?? [];
        $existingNames = array_map(fn($r) => $r['name'], $existingRecords);

        $checkList = explode("\n", str_replace("\r", "", trim($request->input('check_list', ''))));
        if (empty(trim($request->input('check_list', '')))) {
            $checkList = ['@', 'www', 'mail', 'ftp', 'cpanel'];
        }

        $missing = [];
        $domainName = $zone['name'];

        if ($request->input('action') === 'scan') {
            foreach ($checkList as $sub) {
                $sub = trim($sub);
                if (empty($sub)) continue;
                
                $fullName = ($sub === '@' || $sub === $domainName) ? $domainName : "{$sub}.{$domainName}";
                
                if (!in_array($fullName, $existingNames)) {
                    $missing[] = $fullName;
                }
            }
        }

        $response->render('admin', 'admin/dns/missing', [
            'zone' => $zone,
            'missing' => $missing,
            'checkList' => implode("\n", $checkList)
        ]);
    }

    public function missingCreate(EkaRequest $request, EkaResponse $response): void
    {
        $api = $this->getApi($response);
        if (!$api) return;

        $zoneId = $request->getParam('zoneId');
        $missing = $request->input('missing_records', []);
        $targetIp = $request->input('target_ip');

        if (empty($missing) || empty($targetIp)) {
            $_SESSION['flash_error'] = 'Kayıtlar ve hedef IP zorunludur.';
            $response->redirect("/dns/{$zoneId}/missing");
            return;
        }

        $added = 0;
        foreach ($missing as $name) {
            $res = $api->createDnsRecord($zoneId, [
                'type' => 'A',
                'name' => $name,
                'content' => $targetIp,
                'ttl' => 1,
                'proxied' => true
            ]);
            
            if ($res['success']) {
                $added++;
            }
        }

        EkaLogger::info("Eksik Kayıtlar Eklendi: {$added} adet tanımlandı.");
        $_SESSION['flash_success'] = "{$added} eksik kayıt tamamlandı.";
        $response->redirect("/dns/{$zoneId}");
    }
}
