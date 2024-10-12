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
    $sql = "SELECT * FROM feeding_records WHERE id = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $record = $result->fetch_assoc();
        $stmt->close();
    }
}

// 處理表單提交，更新紀錄
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $feeding_time = $_POST['feeding_time'];
    $food_type = $_POST['food_type'];
    $quantity = $_POST['quantity'];
    $notes = $_POST['notes'];

    $sql = "UPDATE feeding_records SET feeding_time = ?, food_type = ?, quantity = ?, notes = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("ssiis", $feeding_time, $food_type, $quantity, $notes, $id);
        $stmt->execute();
        $stmt->close();
        header("Location: view_feeding_records_admin.php"); // 更新後重定向回查看頁面
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>修改餵食紀錄</title>
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
            margin-bottom: 20px;
        }

        form {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        label {
            margin-top: 10px;
            color: #7B7B7B;
        }

        input[type="text"], input[type="datetime-local"], input[type="number"] {
            width: 100%;
            padding: 10px;
            margin-top: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        .btn-container {
            margin-top: 20px;
        }

        input[type="submit"] {
            background-color: #9D9D9D;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1.2em;
        }

        input[type="submit"]:hover {
            background-color: #6e6e6e;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>修改餵食紀錄</h2>
        <form method="POST" action="edit_feeding_record.php?id=<?php echo $id; ?>">
            <label for="feeding_time">餵食時間:</label>
            <input type="datetime-local" name="feeding_time" value="<?php echo htmlspecialchars($record['feeding_time']); ?>" required><br>

            <label for="food_type">食物類型:</label>
            <input type="text" name="food_type" value="<?php echo htmlspecialchars($record['food_type']); ?>" required><br>

            <label for="quantity">餵食量:</label>
            <input type="number" name="quantity" value="<?php echo htmlspecialchars($record['quantity']); ?>" required><br>

            <label for="notes">備註:</label>
            <input type="text" name="notes" value="<?php echo htmlspecialchars($record['notes']); ?>"><br>

            <div class="btn-container">
                <input type="submit" value="更新">
            </div>
        </form>
    </div>
</body>
</html>
