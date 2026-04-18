<?php
require 'db.php';
header('Content-Type: application/json');

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

try {
    // 1. Търсим темата
    $stmt = $pdo->prepare("SELECT * FROM forum_topics WHERE id = ?");
    $stmt->execute([$id]);
    $topic = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($topic) {
        // 2. Ако има тема, взимаме и нейните коментари
        $stmt_comments = $pdo->prepare("SELECT * FROM forum_comments WHERE topic_id = ? ORDER BY created_at ASC");
        $stmt_comments->execute([$id]);
        $topic['comments'] = $stmt_comments->fetchAll(PDO::FETCH_ASSOC);
        
        echo json_encode($topic);
    } else {
        echo json_encode(['error' => 'Темата не е намерена.']);
    }
} catch(PDOException $e) {
    echo json_encode(['error' => 'Грешка: ' . $e->getMessage()]);
}
?>