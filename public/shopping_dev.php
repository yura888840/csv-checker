<?php

use App\Kernel;
use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\ErrorHandler\Debug;
use Symfony\Component\HttpFoundation\IpUtils;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

require dirname(__DIR__).'/vendor/autoload.php';

(new Dotenv())->bootEnv(dirname(__DIR__).'/.env');

umask(0000);
Debug::enable();

$request = Request::createFromGlobals();
$kernel = new Kernel('dev', true);
$kernel->boot();

// list of allowed source IPs, loaded from consul (deployer/_default/_default/dotenv/ALLOWED_DEV_IPS)
$allowedDevIps = $_SERVER['ALLOWED_DEV_IPS'] ?? $_ENV['ALLOWED_DEV_IPS'] ?? "";
$listAllowedDevIps = [];
if (!empty($allowedDevIps)) {
    $listAllowedDevIps = array_map('trim', explode(',', $allowedDevIps));
}

// Check for proper call (see also https://symfony.com/doc/current/deployment/proxies.html)
// Needs symfony/http-foundation packages installed! (composer require symfony/http-foundation)
if ($request->server->get('USER') !== 'vagrant' // Local development with vagrant
    && php_sapi_name() !== 'cli'
    && IpUtils::checkIp($request->getClientIp(), $listAllowedDevIps) === false
) {
    (new Response('You are not allowed to view this page', Response::HTTP_FORBIDDEN))->send();

    return;
}

$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);
