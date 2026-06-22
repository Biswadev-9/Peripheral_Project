<?php
require_once __DIR__ . '/../includes/functions.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $items = cart_items();
    echo json_encode(['status' => 'ok', 'items' => $items, 'totals' => cart_totals($items)], JSON_PRETTY_PRINT);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $payload = json_decode(file_get_contents('php://input'), true) ?: $_POST;
    $productId = (int) ($payload['product_id'] ?? 0);
    $quantity = max(1, (int) ($payload['quantity'] ?? 1));
    if (!$productId) {
        http_response_code(422);
        echo json_encode(['status' => 'error', 'message' => 'product_id is required']);
        exit;
    }
    add_to_cart($productId, $quantity);
    echo json_encode(['status' => 'ok', 'message' => 'Product added to cart', 'count' => cart_count()]);
}
