<?php
session_start();
require_once('admin_session_check.php');

// 連接資料庫
$conn = new mysqli('localhost', 'root', '12345678', 'phmsfinal');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// 獲取 ID 並查詢紀錄
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM cleaning_records WHERE id = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result(); // 使用 get_result() 來取得結果
        $record = $result->fetch_assoc(); // 使用 fetch_assoc() 提取資料
        $stmt->close();
    }
}

// 處理表單提交，更新紀錄
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $clean_location = $_POST['clean_location'];
    $clean_time = $_POST['clean_time'];
    $cleanliness_level = $_POST['cleanliness_level'];
    $notes = $_POST['notes'];

    $sql = "UPDATE cleaning_records SET clean_location = ?, clean_time = ?, cleanliness_level = ?, notes = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("sssii", $clean_location, $clean_time, $cleanliness_level, $notes, $id);
        $stmt->execute();
        $stmt->close();
        header("Location: view_cleaning_records_admin.php"); // 更新後重定向回查看頁面
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>修改清潔紀錄</title>
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
            text-align: center;
        }

        h2 {
            color: #7B7B7B;
            font-weight: bold;
            margin-bottom: 20px;
        }

        label {
            color: #333;
            font-size: 1.2em;
            margin-top: 10px;
            text-align: left;
            display: block;
        }

        input[type="text"], input[type="datetime-local"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0 20px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        input[type="submit"] {
            background-color: #9D9D9D;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            font-size: 1.2em;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        input[type="submit"]:hover {
            background-color: #6e6e6e;
        }

        .btn-container {
            margin-top: 20px;
        }

        .back-btn {
            background-color: #9D9D9D;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
        }

        .back-btn:hover {
            background-color: #6e6e6e;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>修改清潔紀錄</h2>
        <form method="POST" action="edit_cleaning_record.php?id=<?php echo $id; ?>">
            <label for="clean_location">清潔地點:</label>
            <input type="text" name="clean_location" value="<?php echo htmlspecialchars($record['clean_location']); ?>" required>

            <label for="clean_time">清潔時間:</label>
            <input type="datetime-local" name="clean_time" value="<?php echo htmlspecialchars($record['clean_time']); ?>" required>

            <label for="cleanliness_level">整潔度:</label>
            <input type="text" name="cleanliness_level" value="<?php echo htmlspecialchars($record['cleanliness_level']); ?>" required>

            <label for="notes">備註:</label>
            <input type="text" name="notes" value="<?php echo htmlspecialchars($record['notes']); ?>">

            <input type="submit" value="更新">
        </form>

        <div class="btn-container">
            <a href="view_cleaning_records_admin.php" class="back-btn">返回清潔紀錄</a>
        </div>
    </div>
</body>
</html>
