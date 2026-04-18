<?php
// admin_add.php
require 'db.php';
$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $stmt = $pdo->prepare("INSERT INTO runway_articles (brand_slug, brand_name, title, meta, author, content, main_images_count, detail_images_count) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    
    try {
        $stmt->execute([
            $_POST['brand_slug'], $_POST['brand_name'], $_POST['title'], 
            $_POST['meta'], $_POST['author'], $_POST['content'], 
            $_POST['main_count'], $_POST['detail_count']
        ]);
        $message = "Успешно добавена статия!";
    } catch (Exception $e) {
        $message = "Грешка: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Добави Runway Статия</title>
    <style>
        body { font-family: sans-serif; padding: 20px; max-width: 600px; margin: 0 auto; }
        input, textarea { width: 100%; padding: 10px; margin-bottom: 15px; }
        button { padding: 10px 20px; background: #111; color: #fff; cursor: pointer; }
    </style>
</head>
<body>
    <h2>Добави нова колекция</h2>
    <?php if($message) echo "<p style='color:green;'>$message</p>"; ?>
    
    <form method="POST">
        <label>URL Slug (напр. prada, chanel - само малки букви, без интервали):</label>
        <input type="text" name="brand_slug" required>

        <label>Име на марката (напр. Prada):</label>
        <input type="text" name="brand_name" required>

        <label>Заглавие (напр. Prada Spring 2026):</label>
        <input type="text" name="title" required>

        <label>Мета (напр. Милано / От Миуча Прада):</label>
        <input type="text" name="meta" required>

        <label>Автор (напр. Текст: Никол):</label>
        <input type="text" name="author" required>

        <label>Текст на статията (можеш да ползваш &lt;p&gt; тагове):</label>
        <textarea name="content" rows="6" required></textarea>

        <label>Брой снимки в главната галерия:</label>
        <input type="number" name="main_count" value="40" required>

        <label>Брой снимки в детайлите:</label>
        <input type="number" name="detail_count" value="15" required>

        <button type="submit">Публикувай</button>
    </form>
</body>
</html>