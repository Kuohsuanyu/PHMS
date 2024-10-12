<?php
session_start();
require_once('admin_session_check.php');

// 連接資料庫
$conn = new mysqli('localhost', 'root', '12345678', 'phmsfinal');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// 獲取記錄ID並查詢資料
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM user_page_views WHERE id = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $record = $result->fetch_assoc();
        $stmt->close();
    }
}

// 處理表單提交，更新資料
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $page_name = $_POST['page_name'];
    $view_time = $_POST['view_time'];
    $view_count = $_POST['view_count'];

    $sql = "UPDATE user_page_views SET page_name = ?, view_time = ?, view_count = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("ssii", $page_name, $view_time, $view_count, $id);
        $stmt->execute();
        $stmt->close();
        header("Location: view_user_visits.php"); // 更新後重定向
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>修改用戶造訪紀錄</title>
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
            max-width: 600px;
            width: 100%;
        }

        h2 {
            color: #7B7B7B;
            margin-bottom: 20px;
        }

        input[type="text"], input[type="number"], input[type="datetime-local"] {
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
        <h2>修改用戶造訪紀錄</h2>
        <form method="POST" action="edit_user_page_view.php?id=<?php echo $id; ?>">
            <label for="page_name">頁面名稱:</label>
            <input type="text" name="page_name" value="<?php echo htmlspecialchars($record['page_name']); ?>" required><br>
            
            <label for="view_time">造訪時間:</label>
            <input type="datetime-local" name="view_time" value="<?php echo htmlspecialchars($record['view_time']); ?>" required><br>
            
            <label for="view_count">造訪次數:</label>
            <input type="number" name="view_count" value="<?php echo htmlspecialchars($record['view_count']); ?>" required><br>

            <input type="submit" value="更新">
        </form>
    </div>
</body>
</html>
