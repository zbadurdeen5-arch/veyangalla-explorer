<?php

session_start();

require_once "../config/database.php";


if (!isset($_SESSION['admin_id'])) {

    header("Location: login.php");
    exit;
}


$id = isset($_GET['id']) ? intval($_GET['id']) : 0;


if ($id > 0) {

    $stmt = $conn->prepare("
        DELETE FROM attractions
        WHERE Attraction_ID = ?
    ");

    $stmt->bind_param("i", $id);

    $stmt->execute();

    $stmt->close();
}


header("Location: dashboard.php");
exit;

?>