<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>智慧代養系統</title>
  <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
  <link rel="stylesheet" href="https://www.w3schools.com/lib/w3-theme-deep-purple.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
  <style>
    body {
      background: url('6.jpg') no-repeat center center fixed;
      background-size: cover;
      font-family: Arial, sans-serif;
    }
    .container {
      font-size: 1.3em;
      margin-top: 50px;
    }
    .form-container {
      background-color: rgba(255, 255, 255, 0.8);
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
      max-width: 500px;
      margin: auto;
    }
    h1 {
      color: #8E8E8E;
      text-align: center;
      font-weight: bold;
      margin-bottom: 30px;
    }
    .w3-button {
      width: 30%;
      font-size: 1.em;
      margin-top: 20px;
      background-color: #8E8E8E; 
      text-align: center; 
      border-radius: 10px;
      color : white !important;
        
    }
    table {
      margin: auto;
      width: 90%;
    }
    .table-container {
      background-color: rgba(255, 255, 255, 0.9);
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
      margin-top: 20px;
    }
    .no-records {
      text-align: center;
      color: red;
      font-size: 1.2em;
      margin-top: 20px;
    }
    .error {
      color: red;
      font-size: 0.9em;
    }
    .w3-text-indigo {
     color: #7B7B7B !important;
    }

  </style>
</head>
<body>
  <div class="container">
    <div class="form-container w3-card-4">
      <h1>智慧代養系統-添加</h1>
      <form action="" method="GET" class="w3-container" >
        <div class="mb-3">
          <label for="orderid" class="w3-text-indigo"><b>動物名稱:</b></label>
          <input type="text" id="orderid" name="orderid" class="w3-input w3-border w3-round-large" required>
        </div>
        <div class="mb-3">
          <label for="payment_method" class="w3-text-indigo"><b>動物種類:</b></label>
          <input type="text" id="payment_method" name="payment_method" class="w3-input w3-border w3-round-large" required>
        </div>
        <div class="mb-3">
          <label for="total_amount" class="w3-text-indigo"><b>動物年齡:</b></label>
          <input type="number" id="total_amount" name="total_amount" class="w3-input w3-border w3-round-large" required>
        </div>
        <div class="mb-3">
          <label for="ID" class="w3-text-indigo"><b>ID:</b></label>
          <input type="text" id="ID" name="ID" class="w3-input w3-border w3-round-large" required>
        </div>
        <div class="mb-3">
          <label for="temp" class="w3-text-indigo"><b>溫度 (Temp):</b></label>
          <input type="number" step="0.1" id="temp" name="temp" class="w3-input w3-border w3-round-large" required>
        </div>
        <div class="mb-3">
          <label for="humidity" class="w3-text-indigo"><b>濕度 (Humidity):</b></label>
          <input type="number" step="0.1" id="humidity" name="humidity" class="w3-input w3-border w3-round-large" required>
        </div>
        <div class="mb-3">
          <label for="feed_weight" class="w3-text-indigo"><b>餵食重量 (Feed Weight):</b></label>
          <input type="number" step="0.1" id="feed_weight" name="feed_weight" class="w3-input w3-border w3-round-large" required>
        </div>
        <div class="mb-3">
          <label for="clean_number" class="w3-text-indigo"><b>清潔次數 (Clean Number):</b></label>
          <input type="number" id="clean_number" name="clean_number" class="w3-input w3-border w3-round-large" required>
        </div>
        <div class="mb-3">
          <label for="last_cleaning_time" class="w3-text-indigo"><b>最後清潔時間:</b></label>
          <input type="time" id="last_cleaning_time" name="last_cleaning_time" class="w3-input w3-border w3-round-large" required>
        </div >
	<div style="text-align: center;">
          <input type="submit" class="w3-button w3-round-large w3-gray" value="提交" >
	</div>
        
      </form>
    </div>
    <hr>
    <?php
    if(!empty($_GET)) {
      // 後端驗證數據是否為空，並顯示錯誤
      $errors = [];
      if (empty($_GET['orderid'])) $errors[] = "動物名稱未填寫";
      if (empty($_GET['payment_method'])) $errors[] = "動物種類未填寫";
      if (empty($_GET['total_amount'])) $errors[] = "動物年齡未填寫";
      if (empty($_GET['ID'])) $errors[] = "ID 未填寫";
      if (empty($_GET['temp'])) $errors[] = "溫度未填寫";
      if (empty($_GET['humidity'])) $errors[] = "濕度未填寫";
      if (empty($_GET['feed_weight'])) $errors[] = "餵食重量未填寫";
      if (empty($_GET['clean_number'])) $errors[] = "清潔次數未填寫";
      if (empty($_GET['last_cleaning_time'])) $errors[] = "最後清潔時間未選擇";

      if (!empty($errors)) {
        echo "<div class='alert alert-danger text-center'><ul>";
        foreach ($errors as $error) {
          echo "<li>$error</li>";
        }
        echo "</ul></div>";
      } else {
        // 連接資料庫
        $conn = new mysqli('localhost', 'root', '', 'animal');
        
        // 檢查連接
        if ($conn->connect_error) {
          die("Connection failed: " . $conn->connect_error);
        }

        // 插入數據到資料庫
        $sql = "INSERT INTO basic_data (name, species, age, id, temp, humidity, `feed weight`, `clean number`, `last cleaning time`) 
                VALUES ('" . $_GET['orderid'] . "','" . $_GET['payment_method'] . "','" . $_GET['total_amount'] . "','" . $_GET['ID'] . "','" .
                $_GET['temp'] . "','" . $_GET['humidity'] . "','" . $_GET['feed_weight'] . "','" . $_GET['clean_number'] . "','" . $_GET['last_cleaning_time'] . "')";

        if ($conn->query($sql) === TRUE) {
          echo "<div class='alert alert-success text-center'>新增記錄成功！</div>";
        } else {
          echo "<div class='alert alert-danger text-center'>錯誤: " . $conn->error . "</div>";
        }

        // 查詢資料庫中的所有數據
        $sql = "SELECT * FROM basic_data";
        $result = $conn->query($sql);

        // 如果有結果，顯示資料表
        if($result->num_rows > 0) {
          echo "<div class='table-container w3-card-4'><table class='w3-table-all'>";
          echo "<tr class='w3-blue'>";
          $data = $result->fetch_assoc();
          foreach(array_keys($data) as $col)
            echo "<th>" . $col . "</th>";
          echo "</tr>";
          do {
            echo "<tr>";
            foreach($data as $val)
              echo "<td>" . $val . "</td>";
            echo "</tr>";
          } while($data = $result->fetch_assoc());
          echo "</table></div>";
        } else {
          echo "<div class='no-records'>沒有找到記錄！</div>";
        }

        // 關閉資料庫連接
        $conn->close();
      }
    }
    ?>
    <br>
    <form action="index3.php" method="POST" class="w3-container" style="text-align: center;">
      <input type="submit" class="w3-button "  value="上一頁" style="color: white; background-color: #8E8E8E;" >
    </form>
    <form action="index.php" method="POST" class="w3-container" style="text-align: center;">
      <input type="submit" class="w3-button "  value="回首頁" style="color: white; background-color: #8E8E8E;" >
    </form>
  </div>
</body>
</html>
