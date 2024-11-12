<?php
header('Content-Type: application/json');

try {
    $db = new PDO('mysql:dbname=hackathon;host=127.0.0.1;charset=utf8mb4', 'naladmin', 'naladmin');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $data = json_decode(file_get_contents('php://input'), true);
    $roomID = $data['roomID'];

    $stmt = $db->prepare("DELETE FROM room WHERE roomID = :roomID");
    $stmt->bindParam(':roomID', $roomID);
    $stmt->execute();

    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
