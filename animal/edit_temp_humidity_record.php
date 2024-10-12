<?php
session_start();
require_once('admin_session_check.php');

// 連接資料庫
$conn = new mysqli('localhost', 'root', '12345678', 'phmsfinal');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// 獲取記錄 ID 並查詢資料
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM temp_humidity_records WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $record = $result->fetch_assoc();
} else {
    die("No record ID provided.");
}

// 處理表單提交，更新紀錄
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $temperature = $_POST['temperature'];
    $humidity = $_POST['humidity'];
    $recorded_at = $_POST['recorded_at'];

    $sql = "UPDATE temp_humidity_records SET temperature = ?, humidity = ?, recorded_at = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("dssi", $temperature, $humidity, $recorded_at, $id);

    if ($stmt->execute()) {
        header("Location: view_temp_records_admin.php");  // 更新後重定向回管理頁面
        exit();
    } else {
        echo "更新失敗: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>修改溫濕度紀錄</title>
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
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            max-width: 500px;
            width: 100%;
        }

        h2 {
            color: #7B7B7B;
            margin-bottom: 20px;
            text-align: center;
        }

        label {
            display: block;
            margin-bottom: 10px;
            font-weight: bold;
            color: #333;
        }

        input[type="number"], input[type="datetime-local"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
            border: 1px solid #ccc;
            font-size: 16px;
            box-sizing: border-box;
        }

        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            font-size: 16px;
            width: 100%;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }

        .back-btn {
            margin-top: 10px;
            display: block;
            background-color: #9D9D9D;
            color: white;
            text-align: center;
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
        <h2>修改溫濕度紀錄</h2>
        <form method="POST" action="edit_temp_humidity_record.php?id=<?php echo $id; ?>">
            <label for="temperature">溫度 (°C):</label>
            <input type="number" step="0.1" name="temperature" value="<?php echo htmlspecialchars($record['temperature']); ?>" required>

            <label for="humidity">濕度 (%):</label>
            <input type="number" step="0.1" name="humidity" value="<?php echo htmlspecialchars($record['humidity']); ?>" required>

            <label for="recorded_at">記錄時間:</label>
            <input type="datetime-local" name="recorded_at" value="<?php echo htmlspecialchars(date('Y-m-d\TH:i:s', strtotime($record['recorded_at']))); ?>" required>

            <input type="submit" value="更新">
        </form>
        <a href="view_temp_records_admin.php" class="back-btn">返回列表</a>
    </div>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
