<?php

use App\Kernel;

require_once dirname(__DIR__).'/vendor/autoload_runtime.php';

return function (array $context) {
  $allowedOrigins = [
    'http://localhost:5173', // Replace with your frontend domain
  ];

  $origin = $_SERVER['HTTP_ORIGIN'] ?? '';
  if (in_array($origin, $allowedOrigins)) {
    header('Access-Control-Allow-Origin: ' . $origin);
    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Allow-Headers: X-API-KEY, Origin, Authorization, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method');
    header('Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE');
  }

  $method = $_SERVER['REQUEST_METHOD'];
  if ($method == "OPTIONS") {
    die();
  }

  return new Kernel($context['APP_ENV'], (bool) $context['APP_DEBUG']);
};
