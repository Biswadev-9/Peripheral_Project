<?php
require_once __DIR__ . '/../includes/functions.php';
header('Content-Type: application/json');
echo json_encode(['status' => 'ok', 'data' => get_categories()], JSON_PRETTY_PRINT);
