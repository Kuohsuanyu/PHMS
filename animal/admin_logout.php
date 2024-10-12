<?php
session_start();

// 銷毀所有 session 資料
session_unset(); // 清除所有的 session 變數
session_destroy(); // 終結 session

// 重定向至管理員登入頁面
header("Location: admin_login.php");
exit();
?>
