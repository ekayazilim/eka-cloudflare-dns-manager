<?php
namespace EkaCore;

class EkaCloudflare
{
    private string $token;
    private const API_URL = 'https://api.cloudflare.com/client/v4';

    public function __construct(string $token)
    {
        $this->token = $token;
    }

    private function request(string $method, string $endpoint, array $data = []): array
    {
        $url = self::API_URL . $endpoint;
        
        $ch = curl_init();
        
        $headers = [
            'Authorization: Bearer ' . $this->token,
            'Content-Type: application/json'
        ];

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);

        if (!empty($data) && in_array($method, ['POST', 'PUT', 'PATCH'])) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        }

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        
        curl_close($ch);

        if ($error) {
            EkaLogger::error("Cloudflare API Curl Hatası: {$error}");
            return ['success' => false, 'errors' => [['message' => $error]], 'http_code' => $httpCode];
        }

        $responseData = json_decode($response, true);
        
        if ($httpCode === 429) {
            EkaLogger::warning("Cloudflare API Limit Aşıldı (Rate Limit)");
            return ['success' => false, 'errors' => [['message' => 'API limit aşıldı, lütfen bekleyin.']], 'http_code' => 429];
        }

        if (!$responseData || !isset($responseData['success'])) {
            EkaLogger::error("Cloudflare API Geçersiz Yanıt (HTTP {$httpCode}): {$response}");
            return ['success' => false, 'errors' => [['message' => 'Geçersiz API yanıtı']], 'http_code' => $httpCode];
        }

        if (!$responseData['success']) {
            $errMsgs = array_map(fn($e) => $e['message'], $responseData['errors'] ?? []);
            EkaLogger::error("Cloudflare API Hatası (HTTP {$httpCode}): " . implode(', ', $errMsgs));
        }

        return $responseData;
    }

    public function getZones(int $page = 1, string $search = ''): array
    {
        $qs = "?page={$page}&per_page=50";
        if ($search) {
            $qs .= "&name=" . urlencode($search);
        }
        return $this->request('GET', '/zones' . $qs);
    }

    public function getZone(string $zoneId): array
    {
        return $this->request('GET', "/zones/{$zoneId}");
    }

    public function getDnsRecords(string $zoneId, int $page = 1, string $type = '', string $name = ''): array
    {
        $qs = "?page={$page}&per_page=100";
        if ($type) {
            $qs .= "&type=" . urlencode($type);
        }
        if ($name) {
            $qs .= "&name=" . urlencode($name);
        }
        return $this->request('GET', "/zones/{$zoneId}/dns_records" . $qs);
    }

    public function createDnsRecord(string $zoneId, array $data): array
    {
        return $this->request('POST', "/zones/{$zoneId}/dns_records", $data);
    }

    public function updateDnsRecord(string $zoneId, string $recordId, array $data): array
    {
        return $this->request('PUT', "/zones/{$zoneId}/dns_records/{$recordId}", $data);
    }

    public function deleteDnsRecord(string $zoneId, string $recordId): array
    {
        return $this->request('DELETE', "/zones/{$zoneId}/dns_records/{$recordId}");
    }
}
