<?php
header('Content-Type: application/json');

try {
    $db = new PDO('mysql:dbname=hackathon;host=127.0.0.1;charset=utf8mb4', 'naladmin', 'naladmin');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $roomID = $_GET['roomID'];

    $stmt = $db->prepare("SELECT participant FROM room WHERE roomID = :roomID");
    $stmt->bindParam(':roomID', $roomID);
    $stmt->execute();
    $room = $stmt->fetch(PDO::FETCH_ASSOC);

    $participants = [];
    if ($room && $room['participant']) {
        $participants[] = $room['participant'];
    }

    echo json_encode(['participants' => $participants]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
