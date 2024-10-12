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

// 更新用戶查看頁面次數
$user_id = $_SESSION['user_id'];
$page_name = "user_dashboard";  // 當前頁面名稱

// 檢查是否有此頁面的紀錄
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

// 查詢用戶相關的寵物資料
$sql = "SELECT * FROM pets WHERE user_id = ?";
$stmt = $conn->prepare($sql);
if ($stmt) {
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $pets = $stmt->get_result();
} else {
    die("SQL Error: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="zh-TW">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>用戶 Dashboard</title>
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

    h1, h2 {
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
      vertical-align: middle; /* 垂直置中 */
      padding: 10px; /* 保持適當的內間距 */
      line-height: 1.6; /* 調整行高使文字垂直居中 */
    }

    th {
      background-color: #9D9D9D; 
      color: white;
      font-size: 1.2em;
      font-weight: bold;
      border-bottom: 3px solid #ffffff; /* 添加下邊框 */
    }

    td {
     background-color: white;
     color: #333;
     border: 1px solid #ddd;
     padding: 10px;
     text-align: center; /* 水平置中 */
     vertical-align: middle; /* 垂直置中 */
     line-height: 1.5em; /* 確保行距適中 */
     height: 60px; /* 控制表格的高度以防止過大 */
     }

    .w3-button {
      background-color: #9D9D9D;
      color: white;
      font-size: 1.1em;
      width: auto;
      margin-top: 10px;
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

    .btn-container {
      display: flex;
      flex-direction: column;
      align-items: center;
      margin-top: 30px;
    }

    /* 自適應設計 */
    @media (max-width: 768px) {
      .container {
        width: 95%;
      }
    }

    @media (max-width: 576px) {
      h1 {
        font-size: 1.8em;
      }
      .w3-button {
        font-size: 1.1em;
      }
    }
  </style>
</head>
<body>
  <div class="container">
    <h1>歡迎 <?php echo htmlspecialchars($_SESSION['username']); ?></h1>
    <h2>你的寵物資料:</h2>

    <div class="table-container">
      <?php if ($pets->num_rows > 0): ?>
        <table class="w3-table-all">
          <thead>
            <tr>
              <th>寵物名稱</th>
              <th>種類</th>
              <th>年齡</th>
              <th>品種</th>
              <th>管理</th>
            </tr>
          </thead>
          <tbody>
            <?php while($pet = $pets->fetch_assoc()): ?>
              <tr>
                <td><?php echo htmlspecialchars($pet['pet_name']); ?></td>
                <td><?php echo htmlspecialchars($pet['pet_type']); ?></td>
                <td><?php echo htmlspecialchars($pet['pet_age']); ?></td>
                <td><?php echo htmlspecialchars($pet['pet_breed']); ?></td>
                <td>
                  <a href="view_cleaning_records.php?pet_id=<?php echo $pet['id']; ?>" class="w3-button w3-small w3-round-large" style="font-size: 1em!important;">清潔紀錄</a>
                  <a href="view_feeding_records.php?pet_id=<?php echo $pet['id']; ?>" class="w3-button w3-small w3-round-large" style="font-size: 1em!important;">餵食紀錄</a>
                  <a href="view_temp_humidity.php" class="w3-button w3-small w3-round-large" style="font-size: 1em!important;">溫濕度紀錄</a>
                </td>
              </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      <?php else: ?>
        <p class="no-records">目前沒有寵物記錄。</p>
      <?php endif; ?>
    </div>

    <!-- 添加回首頁與登出按鈕 -->
    <div class="btn-container">
      <form action="index.php" method="POST">
        <input type="submit" style="color:white !important;" class="w3-button w3-gray w3-round-large" value="回首頁">
      </form>
      <form action="logout.php" method="POST">
        <input type="submit" style="color:white !important;" class="w3-button w3-gray w3-round-large" value="登出">
      </form>
    </div>
  </div>
</body>
</html>

<?php
// 關閉資料庫連接
$stmt_check->close();
$stmt->close();
$conn->close();
?>
