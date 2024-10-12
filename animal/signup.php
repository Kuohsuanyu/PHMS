<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// 連接資料庫
$conn = new mysqli('localhost', 'root', '12345678', 'phmsfinal');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// 初始化錯誤訊息為空
$error = "";

// 檢查是否是表單提交
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 確保所有必填字段都已填寫
    if (!empty($_POST['username']) && !empty($_POST['password']) && !empty($_POST['email']) && !empty($_POST['age'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $email = $_POST['email'];
        $age = $_POST['age'];

        // 檢查是否已有相同的用戶名或郵箱
        $check_sql = "SELECT * FROM users WHERE username = ? OR email = ?";
        $stmt = $conn->prepare($check_sql);
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $error = "用戶名或郵箱已存在";
        } else {
            // 插入新用戶的數據到審核表
            $sql = "INSERT INTO user_applications (username, password, email, age, created_at) VALUES (?, ?, ?, ?, NOW())";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssi", $username, $password, $email, $age);

            if ($stmt->execute()) {
                $success = "註冊成功，等待管理員審核";
            } else {
                $error = "註冊失敗，請稍後重試";
            }
        }
    } else {
        $error = "所有字段都是必填的";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>申請帳號</title>
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

        input[type="text"], input[type="password"], input[type="email"], input[type="number"] {
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

        .success {
            color: green;
            margin-top: 15px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>申請帳號</h2>
        <!-- 只有當有錯誤時顯示 -->
        <?php if (!empty($error)): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        <?php if (!empty($success)): ?>
            <div class="success"><?php echo $success; ?></div>
        <?php endif; ?>
        <!-- 顯示註冊表單 -->
        <form action="signup.php" method="POST">
            <input type="text" name="username" placeholder="輸入用戶名" required>
            <input type="password" name="password" placeholder="輸入密碼" required>
            <input type="email" name="email" placeholder="輸入郵箱" required>
            <input type="number" name="age" placeholder="輸入年齡" required>
            <input type="submit" value="註冊" class="btn">
        </form>
        <a href="index.php" class="w3-button">回首頁</a>
    </div>
</body>
</html>
