<?php
require 'db.php';
header('Content-Type: application/json');

// Взимаме данните, изпратени от JavaScript
$data = json_decode(file_get_contents("php://input"), true);

if(isset($data['title']) && isset($data['category']) && isset($data['author']) && isset($data['content'])) {
    try {
        $stmt = $pdo->prepare("INSERT INTO forum_topics (title, category, author, content) VALUES (?, ?, ?, ?)");
        if($stmt->execute([$data['title'], $data['category'], $data['author'], $data['content']])) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Неуспешен запис в базата данни.']);
        }
    } catch(PDOException $e) {
        echo json_encode(['success' => false, 'error' => 'Грешка: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Моля, попълнете всички полета.']);
}
?>