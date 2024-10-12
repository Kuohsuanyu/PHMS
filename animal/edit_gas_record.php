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
    $sql = "SELECT * FROM gas_sensor_records WHERE id = ?";
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
    $gas_type = $_POST['gas_type'];
    $concentration_ppm = $_POST['concentration_ppm'];

    // 將 'datetime-local' 輸入格式轉換為 MySQL 可接受的格式
    $recorded_at = date('Y-m-d H:i:s', strtotime($_POST['recorded_at']));

    $sql = "UPDATE gas_sensor_records SET gas_type = ?, concentration_ppm = ?, recorded_at = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("sisi", $gas_type, $concentration_ppm, $recorded_at, $id);
        $stmt->execute();
        $stmt->close();

        // 檢查是否正確進行到重定向部分
        echo "資料庫更新成功，正在重定向...";
        
        // 使用 header() 進行重定向，並加上 exit() 以確保不再執行其他代碼
        header("Location: view_gas_sensor_records.php");
        exit();
    } else {
        echo "SQL 語句錯誤或準備失敗: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>修改氣味紀錄</title>
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
        <h2>修改氣味紀錄</h2>
        <form method="POST" action="edit_gas_record.php?id=<?php echo $id; ?>">
            <label for="gas_type">氣體類型:</label>
            <input type="text" name="gas_type" value="<?php echo htmlspecialchars($record['gas_type']); ?>" required><br>
            
            <label for="concentration_ppm">濃度 (ppm):</label>
            <input type="number" name="concentration_ppm" value="<?php echo htmlspecialchars($record['concentration_ppm']); ?>" required><br>
            
            <label for="recorded_at">記錄時間:</label>
            <input type="datetime-local" name="recorded_at" value="<?php echo date('Y-m-d\TH:i', strtotime($record['recorded_at'])); ?>" required><br>

            <input type="submit" value="更新">
        </form>
    </div>
</body>
</html>
