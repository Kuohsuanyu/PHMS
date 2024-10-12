<?php
session_start();
require_once('admin_session_check.php');

// 連接資料庫
$conn = new mysqli('localhost', 'root', '12345678', 'phmsfinal');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// 獲取用戶ID並查詢資料
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $stmt->close();
    }
}

// 處理表單提交，更新資料
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $age = $_POST['age'];

    $sql = "UPDATE users SET username = ?, email = ?, age = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("ssii", $username, $email, $age, $id);
        $stmt->execute();
        $stmt->close();
        header("Location: view_users.php"); // 更新後重定向
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>修改用戶資料</title>
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
            margin: 0;
        }

        .container {
            background-color: rgba(255, 255, 255, 0.9);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
            max-width: 600px;
            width: 100%;
        }

        h2 {
            color: #7B7B7B;
            margin-bottom: 20px;
        }

        input[type="text"], input[type="email"], input[type="number"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        input[type="submit"] {
            background-color: #9D9D9D;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        input[type="submit"]:hover {
            background-color: #6e6e6e;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>修改用戶資料</h2>
        <form method="POST" action="edit_user.php?id=<?php echo $id; ?>">
            <label for="username">用戶名稱:</label>
            <input type="text" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required><br>
            
            <label for="email">Email:</label>
            <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required><br>
            
            <label for="age">年齡:</label>
            <input type="number" name="age" value="<?php echo htmlspecialchars($user['age']); ?>" required><br>

            <input type="submit" value="更新">
        </form>
    </div>
</body>
</html>
