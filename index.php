<?php

use App\Api;

require_once "vendor/autoload.php";

try {
    $api = new Api();
    echo $api->run();
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage(),
        'data' => []
    ]);
}