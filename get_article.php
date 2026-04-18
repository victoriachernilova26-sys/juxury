<?php
// get_article.php
header('Content-Type: application/json');
require 'db.php';

$brand = isset($_GET['brand']) ? $_GET['brand'] : 'balenciaga';

$stmt = $pdo->prepare("SELECT * FROM runway_articles WHERE brand_slug = ? LIMIT 1");
$stmt->execute([$brand]);
$article = $stmt->fetch(PDO::FETCH_ASSOC);

if ($article) {
    echo json_encode($article);
} else {
    echo json_encode(['error' => 'Статията не е намерена']);
}
?>