<?php
header('Content-Type: application/json');

try {
    $db = new PDO('mysql:dbname=hackathon;host=127.0.0.1;charset=utf8mb4', 'naladmin', 'naladmin');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $db->prepare("SELECT roomID, creater FROM room");
    $stmt->execute();
    $rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(['rooms' => $rooms]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
