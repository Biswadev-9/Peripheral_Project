<?php
require_once __DIR__ . '/../includes/functions.php';
header('Content-Type: application/json');

$category = trim($_GET['category'] ?? '');
$search = trim($_GET['search'] ?? '');
$where = [];
$params = [];
if ($category !== '') {
    $where[] = 'c.slug = ?';
    $params[] = $category;
}
if ($search !== '') {
    $where[] = '(p.name LIKE ? OR p.brand LIKE ? OR p.model LIKE ?)';
    $params[] = "%$search%";
    $params[] = "%$search%";
    $params[] = "%$search%";
}
$whereSql = $where ? 'WHERE ' . implode(' AND ', $where) : '';
$stmt = db()->prepare("SELECT p.*, c.name AS category_name, c.slug AS category_slug FROM products p JOIN categories c ON c.id = p.category_id $whereSql ORDER BY p.created_at DESC LIMIT 50");
$stmt->execute($params);
echo json_encode(['status' => 'ok', 'data' => $stmt->fetchAll()], JSON_PRETTY_PRINT);
