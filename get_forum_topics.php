<?php
require 'db.php'; // Свързваме се с базата данни
header('Content-Type: application/json');

try {
    // Взимаме всички теми, подредени от най-новите към най-старите
    $stmt = $pdo->query("SELECT * FROM forum_topics ORDER BY created_at DESC");
    $topics = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode($topics);
} catch(PDOException $e) {
    echo json_encode(['error' => 'Грешка: ' . $e->getMessage()]);
}
?>