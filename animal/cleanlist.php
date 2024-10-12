<?php
// 連接到資料庫
$conn = new mysqli('localhost', 'root', '12345678', 'phms');
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// 查詢清潔紀錄的資料
$sql = "SELECT * FROM cleaning_records"; // 查詢 cleaning_records 資料表的所有記錄
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>清潔紀錄表</title>
  <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
  <link rel="stylesheet" href="https://www.w3schools.com/lib/w3-theme-deep-purple.css">
  <style>
    .table-container {
      background-color: rgba(255, 255, 255, 0.9);
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
      margin-top: 20px;
      width: 80%;
      margin: auto;
    }
    h1 {
      color: #7B7B7B;
      text-align: center;
    }
    .no-records {
      text-align: center;
      color: red;
      font-size: 1.2em;
      margin-top: 20px;
    }
  </style>
</head>
<body>
  <h1>清潔紀錄表</h1>
  <?php
  if ($result->num_rows > 0) {
    echo "<div class='table-container w3-card-4'><table class='w3-table-all'>";
    echo "<tr class='w3-blue'><th>清潔位置</th><th>清潔時間</th><th>備註</th></tr>";
    while ($row = $result->fetch_assoc()) {
      echo "<tr><td>" . $row["清潔位置"]. "</td><td>" . $row["清潔時間"]. "</td><td>" . $row["備註"]. "</td></tr>";
    }
    echo "</table></div>";
  } else {
    echo "<div class='no-records'>No Records Found!</div>";
  }

  $conn->close();
  ?>

  </form>
  <form action="Query.php" method="POST" class="w3-container" style="text-align: center; margin-top: 20px;">
  <input type="submit" class="w3-button w3-gray w3-round-large" style="color: white !important; font-size: 1.5em;" value="上一頁">
  </form>
  <form action="index.php" method="POST" class="w3-container" style="text-align: center; margin-top: 20px; ">
    <input type="submit" class="w3-button w3-gray w3-round-large" style="color: white !important; font-size: 1.5em;" value="回首頁">

</body>
</html>
