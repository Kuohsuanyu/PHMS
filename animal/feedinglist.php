<?php
// 連接到資料庫
$conn = new mysqli('localhost', 'root', '12345678', 'phms');
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// 查詢餵食紀錄的資料
$sql = "SELECT * FROM feeding_records"; // 正確指定查詢的資料表名稱 feeding_records
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>餵食紀錄表</title>
  <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
  <link rel="stylesheet" href="https://www.w3schools.com/lib/w3-theme-deep-purple.css">
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f4f4f4;
    }

    .table-container {
      background-color: rgba(255, 255, 255, 0.9);
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
      margin-top: 20px;
      width: 80%;
      margin: auto;
      overflow-x: auto; /* 使表格在小螢幕上可水平滾動 */
    }
    
    h1 {
      color: #7B7B7B;
      text-align: center;
      font-size: 2em;
    }

    .no-records {
      text-align: center;
      color: red;
      font-size: 1.2em;
      margin-top: 20px;
    }

    /* 自適應設計：針對較小螢幕進行樣式調整 */
    @media (max-width: 768px) {
      h1 {
        font-size: 1.8em;
      }
      .table-container {
        width: 95%; /* 增加表格在小螢幕上的寬度 */
      }
      .w3-button {
        font-size: 1.2em;
      }
    }

    @media (max-width: 576px) {
      h1 {
        font-size: 1.5em;
      }
      .table-container {
        width: 100%; /* 在手機上表格寬度佔滿 */
      }
      .w3-button {
        font-size: 1em;
        padding: 10px 20px;
      }
    }
  </style>
</head>
<body>
  <h1>餵食紀錄表</h1>
  <?php
  if ($result->num_rows > 0) {
    echo "<div class='table-container w3-card-4'><table class='w3-table-all'>";
    echo "<tr class='w3-blue'><th>餵食位置</th><th>餵食時間</th><th>備註</th></tr>"; // 更新表頭
    while ($row = $result->fetch_assoc()) {
      echo "<tr><td>" . $row["餵食位置"]. "</td><td>" . $row["餵食時間"]. "</td><td>" . $row["備註"]. "</td></tr>";
    }
    echo "</table></div>";
  } else {
    echo "<div class='no-records'>No Records Found!</div>";
  }

  $conn->close();
  ?>

  <form action="Query.php" method="POST" class="w3-container" style="text-align: center; margin-top: 20px;">
    <input type="submit" class="w3-button w3-gray w3-round-large" style="color: white !important; font-size: 1.5em;" value="上一頁">
  </form>

  <form action="index.php" method="POST" class="w3-container" style="text-align: center; margin-top: 20px;">
    <input type="submit" class="w3-button w3-gray w3-round-large" style="color: white !important; font-size: 1.5em;" value="回首頁">
  </form>
</body>
</html>
