<?php
namespace EkaApp\Services;

use EkaCore\EkaCloudflare;
use EkaApp\Models\EkaApiToken;

class EkaCloudflareService
{
    private ?EkaCloudflare $cf = null;
    private ?int $tokenId = null;

    public function __construct(?int $tokenId = null)
    {
        if ($tokenId) {
            $this->setToken($tokenId);
        } else {
            $tokenModel = new EkaApiToken();
            $defaultTokens = $tokenModel->all();
            if (!empty($defaultTokens)) {
                $this->setToken($defaultTokens[0]['id']);
            }
        }
    }

    public function setToken(int $tokenId): void
    {
        $tokenModel = new EkaApiToken();
        $token = $tokenModel->find($tokenId);
        if ($token) {
            $this->tokenId = $token['id'];
            $this->cf = new EkaCloudflare($token['token']);
        }
    }

    public function getTokenId(): ?int
    {
        return $this->tokenId;
    }

    public function getApi(): ?EkaCloudflare
    {
        return $this->cf;
    }
}
