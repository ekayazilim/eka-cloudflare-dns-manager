<?php
namespace EkaCore;

class EkaResponse
{
    public function json(array $data, int $statusCode = 200): void
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    public function view(string $viewPath, array $data = []): void
    {
        extract($data);
        require __DIR__ . '/../app/Views/' . $viewPath . '.php';
    }

    public function render(string $layout, string $viewPath, array $data = []): void
    {
        extract($data);
        ob_start();
        require __DIR__ . '/../app/Views/' . $viewPath . '.php';
        $content = ob_get_clean();
        require __DIR__ . '/../app/Views/layouts/' . $layout . '.php';
    }

    public function redirect(string $url): void
    {
        $appConfig = require __DIR__ . '/../config/app.php';
        $fullUrl = strpos($url, 'http') === 0 ? $url : $appConfig['url'] . $url;
        header("Location: $fullUrl");
        exit;
    }

    public function back(): void
    {
        $url = $_SERVER['HTTP_REFERER'] ?? '/';
        $this->redirect($url);
    }
}
