<?php
require_once '../config/db.php';

$id_salon = $_GET['salon'] ?? 1;
$last_id = $_GET['last_id'] ?? 0;

$stmt = $pdo->prepare("SELECT COUNT(*) AS new_messages, MAX(id_message) AS last_id 
                      FROM messages WHERE id_salon = ? AND id_message > ?");
$stmt->execute([$id_salon, $last_id]);
$result = $stmt->fetch(PDO::FETCH_ASSOC);

echo json_encode($result);
?>
