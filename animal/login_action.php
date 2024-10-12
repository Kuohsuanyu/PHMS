<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // 連接資料庫
    $conn = new mysqli("localhost", "root", "12345678", "phmsfinal");

    // 檢查連接
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // 查詢帳號與密碼是否匹配
    $sql = "SELECT id, username FROM users WHERE username = ? AND password = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // 查詢成功，設定 session 並重定向
        session_start();
        $user = $result->fetch_assoc();
        $_SESSION['user_id'] = $user['id'];  // 儲存使用者的唯一標識符
        $_SESSION['username'] = $user['username'];
        
        header("Location: user_dashboard.php");
        exit();
    } else {
        // 登入失敗，返回登入頁面並顯示錯誤訊息
        echo "<script>alert('帳號或密碼錯誤！'); window.location.href = 'user.php';</script>";
    }

    // 關閉連接
    $stmt->close();
    $conn->close();
}
?>
