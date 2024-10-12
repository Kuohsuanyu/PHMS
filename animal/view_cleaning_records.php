<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// 連接資料庫
$conn = new mysqli('localhost', 'root', '12345678', 'phmsfinal');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// 紀錄用戶檢視此頁面的次數
$user_id = $_SESSION['user_id'];
$page_name = 'cleaning_records';
$view_time = date("Y-m-d H:i:s");

// 檢查用戶是否已經有這個頁面的記錄
$check_sql = "SELECT * FROM user_page_views WHERE user_id = ? AND page_name = ?";
$stmt_check = $conn->prepare($check_sql);
$stmt_check->bind_param("is", $user_id, $page_name);
$stmt_check->execute();
$result_check = $stmt_check->get_result();

if ($result_check->num_rows > 0) {
    // 更新檢視次數
    $update_sql = "UPDATE user_page_views SET view_count = view_count + 1, view_time = ? WHERE user_id = ? AND page_name = ?";
    $stmt_update = $conn->prepare($update_sql);
    $stmt_update->bind_param("sis", $view_time, $user_id, $page_name);
    $stmt_update->execute();
    $stmt_update->close();
} else {
    // 新增記錄
    $insert_sql = "INSERT INTO user_page_views (user_id, page_name, view_time, view_count) VALUES (?, ?, ?, 1)";
    $stmt_insert = $conn->prepare($insert_sql);
    $stmt_insert->bind_param("iss", $user_id, $page_name, $view_time);
    $stmt_insert->execute();
    $stmt_insert->close();
}

$stmt_check->close();

// 檢查是否有傳遞 `pet_id`
if (!isset($_GET['pet_id'])) {
    die("No pet ID provided.");
}

$pet_id = $_GET['pet_id'];

// 處理時間篩選表單提交
$start_date = isset($_GET['start_date']) ? $_GET['start_date'] : null;
$end_date = isset($_GET['end_date']) ? $_GET['end_date'] : null;

// 查詢與特定寵物的清潔紀錄，根據時間段篩選
if ($start_date && $end_date) {
    $sql = "SELECT clean_location, clean_time, cleanliness_level, notes FROM cleaning_records WHERE pet_id = ? AND clean_time BETWEEN ? AND ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iss", $pet_id, $start_date, $end_date);
} else {
    $sql = "SELECT clean_location, clean_time, cleanliness_level, notes FROM cleaning_records WHERE pet_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $pet_id);
}

// 檢查 SQL 是否正確準備
if (!$stmt) {
    die("SQL Error: " . $conn->error);
}

$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>清潔紀錄</title>
    <link href="https://www.w3schools.com/w3css/4/w3.css" rel="stylesheet">
    <link rel="stylesheet" href="https://www.w3schools.com/lib/w3-theme-deep-purple.css">
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
            max-width: 800px;
            width: 100%;
            text-align: center;
        }

        h2 {
            color: #7B7B7B;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            background-color: white;
            margin-top: 20px;
        }

        th {
            background-color: #9D9D9D;
            color: white;
            padding: 10px;
        }

        td {
            color: #333;
            padding: 10px;
        }

        .btn-container {
            margin-top: 30px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .btn-container .btn {
            background-color: #9D9D9D;
            color: white;
            border-radius: 5px;
            font-size: 1.2em;
            padding: 10px 30px;
            margin-bottom: 15px;
        }

        .btn-container .btn:hover {
            background-color: #6e6e6e;
        }

        .no-data {
            text-align: center;
            color: red;
            font-size: 1.2em;
        }

        .filter-form {
            margin-bottom: 20px;
        }

        .filter-form input {
            padding: 10px;
            margin-right: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>清潔紀錄</h2>

        <!-- 篩選表單 -->
        <form method="GET" action="view_cleaning_records.php" class="filter-form">
            <input type="hidden" name="pet_id" value="<?php echo htmlspecialchars($pet_id); ?>">
            <label for="start_date">開始日期：</label>
            <input type="date" name="start_date" value="<?php echo htmlspecialchars($start_date); ?>" required>
            <label for="end_date">結束日期：</label>
            <input type="date" name="end_date" value="<?php echo htmlspecialchars($end_date); ?>" required>
            <input type="submit" value="篩選">
        </form>

        <?php if ($result->num_rows > 0): ?>
            <table class="w3-table-all">
                <thead>
                    <tr>
                        <th>清潔地點</th>
                        <th>清潔時間</th>
                        <th>整潔度</th>
                        <th>備註</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['clean_location']); ?></td>
                            <td><?php echo htmlspecialchars($row['clean_time']); ?></td>
                            <td><?php echo htmlspecialchars($row['cleanliness_level']); ?></td>
                            <td><?php echo htmlspecialchars($row['notes']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="no-data">目前沒有清潔紀錄。</p>
        <?php endif; ?>

        <div class="btn-container">
            <a href="user_dashboard.php" class="btn w3-button">回到資料頁</a>
            <a href="index.php" class="btn w3-button">回首頁</a>
        </div>
    </div>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
