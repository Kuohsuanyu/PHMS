<?php
session_start();

// 檢查是否有設置 user_id 的 session 變數
if (!isset($_SESSION['user_id'])) {
    // 如果用戶未登入，重定向至登入頁面
    header("Location: user.php");
    exit();
}
?>
