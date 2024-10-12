<?php
session_start();
require_once('admin_session_check.php');

// 連接資料庫
$conn = new mysqli('localhost', 'root', '12345678', 'phmsfinal');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// 確認傳遞了 ID
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // 刪除紀錄
    $sql = "DELETE FROM temp_humidity_records WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: view_temp_records_admin.php");  // 刪除後重定向回管理頁面
        exit();
    } else {
        echo "刪除失敗: " . $conn->error;
    }
} else {
    die("No record ID provided.");
}

$stmt->close();
$conn->close();
?>
