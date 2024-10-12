<?php
session_start();

// 清除所有的 session 資料
session_unset();

// 銷毀 session
session_destroy();

// 重定向回到登入頁面
header("Location: user.php");
exit();
?>
