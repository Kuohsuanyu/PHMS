<?php
session_start();
if (!isset($_SESSION['user_id']) || !isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// 連接資料庫
$conn = new mysqli('localhost', 'root', '12345678', 'phmsfinal');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// 記錄用戶查看次數
$user_id = $_SESSION['user_id'];
$page_name = "temp_humidity_records";  // 當前頁面名稱

// 準備查詢用戶查看次數的 SQL
$sql_check = "SELECT * FROM user_page_views WHERE user_id = ? AND page_name = ?";
$stmt_check = $conn->prepare($sql_check);
if (!$stmt_check) {
    die("SQL preparation failed: " . $conn->error);
}
$stmt_check->bind_param("is", $user_id, $page_name);
$stmt_check->execute();
$result_check = $stmt_check->get_result();

if ($result_check->num_rows > 0) {
    // 更新 view_count 和 view_time
    $sql_update = "UPDATE user_page_views SET view_count = view_count + 1, view_time = NOW() WHERE user_id = ? AND page_name = ?";
    $stmt_update = $conn->prepare($sql_update);
    if (!$stmt_update) {
        die("SQL preparation failed: " . $conn->error);
    }
    $stmt_update->bind_param("is", $user_id, $page_name);
    $stmt_update->execute();
    $stmt_update->close();
} else {
    // 插入新紀錄
    $sql_insert = "INSERT INTO user_page_views (user_id, page_name, view_time, view_count) VALUES (?, ?, NOW(), 1)";
    $stmt_insert = $conn->prepare($sql_insert);
    if (!$stmt_insert) {
        die("SQL preparation failed: " . $conn->error);
    }
    $stmt_insert->bind_param("is", $user_id, $page_name);
    $stmt_insert->execute();
    $stmt_insert->close();
}

// 查詢溫濕度記錄
$start_date = isset($_GET['start_date']) ? $_GET['start_date'] : '';
$end_date = isset($_GET['end_date']) ? $_GET['end_date'] : '';

if ($start_date && $end_date) {
    $sql = "SELECT id, temperature, humidity, recorded_at FROM temp_humidity_records WHERE recorded_at BETWEEN ? AND ? ORDER BY recorded_at DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $start_date, $end_date);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $sql = "SELECT id, temperature, humidity, recorded_at FROM temp_humidity_records ORDER BY recorded_at DESC";
    $result = $conn->query($sql);
}
?>

<!DOCTYPE html>
<html lang="zh-TW">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>溫濕度紀錄</title>
  <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
  <style>
    body {
      background: url('6.jpg') no-repeat center center fixed;
      background-size: cover;
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
    }

    .container {
      background-color: rgba(255, 255, 255, 0.8);
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
      width: 80%;
      max-width: 800px;
    }

    h1 {
      text-align: center;
      color: #7B7B7B;
      font-weight: bold;
    }

    table {
      width: 100%;
      margin-top: 20px;
      border-collapse: collapse;
    }

    th, td {
      text-align: center;
      vertical-align: middle; 
      padding: 10px;
      line-height: 1.6;
    }

    th {
      background-color: #9D9D9D; 
      color: white;
      font-size: 1.2em;
      font-weight: bold;
      border-bottom: 3px solid #ffffff;
    }

    td {
      background-color: white;
      color: #333;
      border: 1px solid #ddd;
      padding: 10px;
      line-height: 1.5em;
    }

    .w3-button {
      background-color: #9D9D9D;
      color: white;
      font-size: 1.1em;
      margin-top: 20px;
      padding: 10px 20px;
      transition: background-color 0.3s, color 0.3s;
    }

    .w3-button:hover {
      background-color: #6C6C6C;
    }

    .no-records {
      text-align: center;
      color: red;
      font-size: 1.2em;
    }

    .search-form {
      margin-bottom: 20px;
      text-align: center;
    }

    .search-form input[type="date"] {
      padding: 8px;
      font-size: 16px;
      border: 1px solid #ccc;
      border-radius: 5px;
      margin-right: 10px;
    }

    .search-form button {
      padding: 8px 12px;
      font-size: 16px;
      background-color: #9D9D9D;
      color: white;
      border: none;
      border-radius: 5px;
      cursor: pointer;
    }

    .search-form button:hover {
      background-color: #6e6e6e;
    }
  </style>
</head>
<body>
  <div class="container">
    <h1>溫濕度紀錄</h1>

    <!-- 搜尋表單 -->
    <form class="search-form" method="GET" action="">
      <input type="date" name="start_date" value="<?php echo htmlspecialchars($start_date); ?>" required>
      <input type="date" name="end_date" value="<?php echo htmlspecialchars($end_date); ?>" required>
      <button type="submit">搜尋</button>
    </form>
    
    <?php if ($result->num_rows > 0): ?>
      <table class="w3-table-all">
        <thead>
          <tr>
            <th>ID</th>
            <th>溫度 (°C)</th>
            <th>濕度 (%)</th>
            <th>記錄時間</th>
          </tr>
        </thead>
        <tbody>
          <?php while($row = $result->fetch_assoc()): ?>
            <tr>
              <td><?php echo htmlspecialchars($row['id']); ?></td>
              <td><?php echo htmlspecialchars($row['temperature']); ?></td>
              <td><?php echo htmlspecialchars($row['humidity']); ?></td>
              <td><?php echo htmlspecialchars($row['recorded_at']); ?></td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    <?php else: ?>
      <p class="no-records">目前沒有溫濕度紀錄。</p>
    <?php endif; ?>

    <!-- 返回 Dashboard 按鈕 -->
    <div style="text-align: center; margin-top: 20px;">
      <form action="user_dashboard.php" method="POST">
        <input type="submit" class="w3-button w3-gray w3-round-large" value="回到資料頁">
      </form>
    </div>
  </div>
</body>
</html>

<?php
$stmt_check->close();
if (isset($stmt)) {
    $stmt->close();
}
$conn->close();
?>
