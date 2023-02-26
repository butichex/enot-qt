<?php

require_once '../boot.php';

$stmt = pdo()->prepare("SELECT nominal, charcode, value FROM currency");

$stmt->execute();
$result = $stmt -> fetchAll();
echo json_encode($result);

?>