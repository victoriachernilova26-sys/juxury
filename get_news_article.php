<?php
header('Content-Type: application/json');

$host = 'localhost';
$db   = 'juxury'; 
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if (!isset($_GET['id'])) {
        echo json_encode(["error" => "Не е избрана статия."]);
        exit;
    }

    $article_id = $_GET['id'];

    // 1. ВЗИМАМЕ ГОЛЯМАТА СТАТИЯ
    $stmt = $pdo->prepare("SELECT * FROM runway_articles WHERE id = ?");
    $stmt->execute([$article_id]);
    $article = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$article) {
        echo json_encode(["error" => "Статията не е намерена."]);
        exit;
    }

    // Взимаме името на марката от голямата статия (напр. 'Balenciaga')
    $current_brand = $article['brand']; 

    // 2. ВЗИМАМЕ 3-ТЕ НОВИНИ ЗА СЪЩАТА МАРКА ОТ ТАБЛИЦАТА brand_news
    // Увери се, че имената на колоните (title, image, description) съвпадат с тези в твоята таблица brand_news!
    $news_stmt = $pdo->prepare("
        SELECT id, title, image, description 
        FROM brand_news 
        WHERE brand = ? 
        LIMIT 3
    ");
    // Търсим само новини, чиято марка съвпада с марката на голямата статия
    $news_stmt->execute([$current_brand]);
    $brand_news = $news_stmt->fetchAll(PDO::FETCH_ASSOC);

    // 3. ПРИКАЧВАМЕ ГИ КЪМ ОТГОВОРА
    $article['related'] = $brand_news;

    echo json_encode($article);

} catch (PDOException $e) {
    echo json_encode(["error" => "Грешка: " . $e->getMessage()]);
}
?>