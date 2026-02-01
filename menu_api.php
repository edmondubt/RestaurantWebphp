<?php
require_once "db.php"; // class Database
header("Content-Type: application/json; charset=utf-8");

$category = $_GET["category"] ?? "";
$allowed = ["pije","dessert","ushqim"];
if (!in_array($category, $allowed, true)) {
    echo json_encode([]);
    exit;
}

$db = new Database();
$conn = $db->connect();

$sql = "SELECT mi.title, mi.price, mi.description, mi.image_path
        FROM menu_items mi
        JOIN menu_categories mc ON mc.id = mi.category_id
        WHERE mc.slug = :slug AND mi.is_active = 1
        ORDER BY mi.id DESC";

$stmt = $conn->prepare($sql);
$stmt->execute([":slug" => $category]);
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($items);
