<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

// 連接資料庫
$conn = new mysqli('localhost', 'root', '12345678', 'phmsfinal');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// 初始化錯誤訊息為空
$error = "";

// 檢查是否是表單提交
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 確保用戶提交了 username 和 password
    if (!empty($_POST['username']) && !empty($_POST['password'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];

        // 查詢管理員帳號與密碼
        $sql = "SELECT * FROM admin_users WHERE username = ? AND password = ?";
        $stmt = $conn->prepare($sql);

        // 檢查 SQL 查詢是否正確準備
        if (!$stmt) {
            die("SQL Error: " . $conn->error);
        }

        // 綁定參數並執行查詢
        $stmt->bind_param("ss", $username, $password);
        $stmt->execute();
        $result = $stmt->get_result();

        // 檢查是否查詢到匹配的帳號
        if ($result->num_rows > 0) {
            // 設置 session 並重定向到管理者頁面
            $admin = $result->fetch_assoc();
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_username'] = $admin['username'];

            header("Location: admin_dashboard.php");
            exit();
        } else {
            // 如果帳號或密碼不正確，顯示錯誤訊息
            $error = "帳號或密碼錯誤，請重新嘗試";
        }

        $stmt->close();
    } else {
        // 表單提交但缺少帳號或密碼
        $error = "請輸入帳號和密碼";
    }
}

$conn->close();
?>

<!-- 顯示 HTML 表單 -->
<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>管理員登入</title>
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <style>
        body {
            background: url('6.jpg') no-repeat center center fixed;
            background-size: cover;
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background-color: rgba(255, 255, 255, 0.9);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
            max-width: 400px;
            width: 100%;
            text-align: center;
        }

        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .btn {
            background-color: #9D9D9D;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1.2em;
        }

        .btn:hover {
            background-color: #6e6e6e;
        }

        .error {
            color: red;
            margin-top: 15px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>管理員登入</h2>
        <!-- 只有當有錯誤時顯示 -->
        <?php if (!empty($error)): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        <!-- 顯示登入表單 -->
        <form action="admin_login.php" method="POST">
            <input type="text" name="username" placeholder="輸入帳號" required>
            <input type="password" name="password" placeholder="輸入密碼" required>
            <input type="submit" value="登入" class="btn">
        </form>
         <a href="index.php" class="w3-button">回首頁</a>
    </div>
</body>
</html>
