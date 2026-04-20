<?php
namespace EkaApp\Controllers;

use EkaCore\EkaController;
use EkaCore\EkaRequest;
use EkaCore\EkaResponse;
use EkaApp\Models\EkaApiToken;
use EkaCore\EkaLogger;

class EkaSettingsController extends EkaController
{
    public function tokens(EkaRequest $request, EkaResponse $response): void
    {
        $tokenModel = new EkaApiToken();
        $tokens = $tokenModel->all();
        $response->render('admin', 'admin/settings/tokens', ['tokens' => $tokens]);
    }

    public function addToken(EkaRequest $request, EkaResponse $response): void
    {
        $name = trim($request->input('name'));
        $token = trim($request->input('token'));

        if (empty($name) || empty($token)) {
            $response->back();
            return;
        }

        $tokenModel = new EkaApiToken();
        $tokenModel->insert([
            'name' => $name,
            'token' => $token,
            'created_at' => date('Y-m-d H:i:s')
        ]);
        
        EkaLogger::info("Yeni API Token eklendi: {$name}");
        $response->redirect('/settings/tokens');
    }

    public function deleteToken(EkaRequest $request, EkaResponse $response): void
    {
        $id = (int)$request->input('id');
        if ($id) {
            $tokenModel = new EkaApiToken();
            $tokenModel->delete($id);
            EkaLogger::info("API Token silindi (ID: {$id})");
            
            if (isset($_SESSION['active_token_id']) && $_SESSION['active_token_id'] == $id) {
                unset($_SESSION['active_token_id']);
            }
        }
        $response->redirect('/settings/tokens');
    }
}
