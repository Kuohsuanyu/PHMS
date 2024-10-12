<?php
session_start();
require_once('admin_session_check.php');

// 連接資料庫
$conn = new mysqli('localhost', 'root', '12345678', 'phmsfinal');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// 刪除紀錄
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "DELETE FROM gas_sensor_records WHERE id = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
        header("Location: view_gas_sensor_records.php"); // 刪除後重定向
        exit();
    }
}
?>
