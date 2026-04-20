<?php
use EkaApp\Middlewares\EkaAuthMiddleware;
use EkaApp\Middlewares\EkaGuestMiddleware;
use EkaApp\Middlewares\EkaCsrfMiddleware;

$router->get('/login', ['EkaApp\Controllers\EkaAuthController', 'showLogin'], [EkaGuestMiddleware::class]);
$router->post('/login', ['EkaApp\Controllers\EkaAuthController', 'login'], [EkaGuestMiddleware::class, EkaCsrfMiddleware::class]);
$router->get('/logout', ['EkaApp\Controllers\EkaAuthController', 'logout']);

$auth = [EkaAuthMiddleware::class];
$authCsrf = [EkaAuthMiddleware::class, EkaCsrfMiddleware::class];

$router->get('/', ['EkaApp\Controllers\EkaDashboardController', 'index'], $auth);
$router->get('/dashboard', ['EkaApp\Controllers\EkaDashboardController', 'index'], $auth);

$router->get('/domains', ['EkaApp\Controllers\EkaDomainController', 'index'], $auth);

$router->get('/dns/{zoneId}', ['EkaApp\Controllers\EkaDnsController', 'index'], $auth);
$router->post('/dns/{zoneId}/create', ['EkaApp\Controllers\EkaDnsController', 'create'], $authCsrf);
$router->post('/dns/{zoneId}/delete', ['EkaApp\Controllers\EkaDnsController', 'delete'], $authCsrf);

$router->get('/dns/{zoneId}/bulk', ['EkaApp\Controllers\EkaDnsController', 'bulkForm'], $auth);
$router->post('/dns/{zoneId}/bulk', ['EkaApp\Controllers\EkaDnsController', 'bulkAdd'], $authCsrf);

$router->get('/dns/{zoneId}/missing', ['EkaApp\Controllers\EkaDnsController', 'missingForm'], $auth);
$router->post('/dns/{zoneId}/missing/scan', ['EkaApp\Controllers\EkaDnsController', 'missingForm'], $authCsrf);
$router->post('/dns/{zoneId}/missing/create', ['EkaApp\Controllers\EkaDnsController', 'missingCreate'], $authCsrf);

$router->get('/settings/tokens', ['EkaApp\Controllers\EkaSettingsController', 'tokens'], $auth);
$router->post('/settings/tokens', ['EkaApp\Controllers\EkaSettingsController', 'addToken'], $authCsrf);
$router->post('/settings/tokens/delete', ['EkaApp\Controllers\EkaSettingsController', 'deleteToken'], $authCsrf);

$router->get('/settings/logs', ['EkaApp\Controllers\EkaLogsController', 'index'], $auth);
$router->post('/settings/logs/clear', ['EkaApp\Controllers\EkaLogsController', 'clear'], $authCsrf);
