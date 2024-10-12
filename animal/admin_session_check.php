<?php
// 檢查 session 是否已啟動
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// 檢查是否有管理員登入
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}
?>
