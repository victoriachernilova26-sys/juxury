<?php
require 'db.php';
header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);

if(isset($data['topic_id']) && isset($data['author']) && isset($data['content'])) {
    try {
        $stmt = $pdo->prepare("INSERT INTO forum_comments (topic_id, author, content) VALUES (?, ?, ?)");
        if($stmt->execute([$data['topic_id'], $data['author'], $data['content']])) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Неуспешен запис на коментара.']);
        }
    } catch(PDOException $e) {
        echo json_encode(['success' => false, 'error' => 'Грешка: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Моля, напишете коментар.']);
}
?>