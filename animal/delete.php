<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>刪除動物資料</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@700&display=swap" rel="stylesheet">
  <style>
    body {
      background: url('6.jpg') no-repeat center center fixed;
      background-size: cover;
      color: #fff;
      font-family: 'Roboto', sans-serif;
    }
    .container-custom {
      background-color: white;
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.5);
      margin-top: 50px;
    }
    h1 {
      margin-top: 20px;
      font-size: 2.5em;
      font-weight: 700;
      color: #7B7B7B;
    }
    .form-select {
      background-color: #f0f0f0;
      color: #7B7B7B;
      font-weight: 700;
      border-radius: 10px;
      border: 1px solid #ddd;
      padding: 10px;
    }
    .btn-custom {
      font-size: 1.2em;
      font-weight: 700;
      padding: 10px 20px;
      border-radius: 10px;
      color: white;
    }
    .btn-primary-custom {
      background-color: #8E8E8E;
      border-color: #8E8E8E;
    }
    .btn-success-custom {
      background-color: #6C6C6C;
      border-color: #6C6C6C;
    }
    .table-container {
      background-color: rgba(255, 255, 255, 0.9);
      padding: 20px;
      border-radius: 10px;
      margin-top: 20px;
      color: #7B7B7B;
    }
    .table thead th {
      background-color: #8E8E8E;
      color: white;
    }

    /* 手機自適應樣式 */
    @media (max-width: 768px) {
      .container-custom {
        margin-top: 20px;
      }
      h1 {
        font-size: 2em;
      }
      .form-select, .btn-custom {
        font-size: 1em;
        padding: 8px;
      }
      .table-container {
        padding: 15px;
      }
    }

    /* 超小螢幕（如手機）自適應樣式 */
    @media (max-width: 576px) {
      h1 {
        font-size: 1.8em;
      }
      .form-select, .btn-custom {
        font-size: 0.9em;
        padding: 6px;
      }
    }
  </style>
</head>
<body>
  <div class="container container-custom text-center">
    <h1>刪除動物資料</h1>
    <form action="delete.php" method="GET" class="mb-3">
      <div class="mb-3">
        <?php
          $conn = new mysqli('localhost', 'root', '12345678', 'animal');
          if ($conn->connect_error) {
            die("連接失敗：" . $conn->connect_error);
          }
          $sql = "SELECT name FROM basic_data";
          $result = $conn->query($sql);

          echo "<label for='orderid' class='form-label'>動物名字:</label>";
          echo "<select id='orderid' name='orderid' class='form-select'>";
          if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
              echo "<option value='" . $row['name'] . "'>" . $row['name'] . "</option>";
            }
          } else {
            echo "<option value=''>沒有資料</option>";
          }
          echo "</select>";

          $conn->close();
        ?>
      </div>
      <input type="submit" class="btn btn-primary btn-custom btn-primary-custom" value="刪除">
    </form>

    <hr class="text-white">

    <div class="table-container">
      <?php
        if (!empty($_GET['orderid'])) {
          $conn = new mysqli('localhost', 'root', '12345678', 'animal');
          if ($conn->connect_error) {
            die("連接失敗：" . $conn->connect_error);
          }

          // 簡單的刪除語句
          $sql = "DELETE FROM basic_data WHERE name = '" . $_GET['orderid'] . "'";
          $conn->query($sql);

          // 查詢更新後的資料
          $sql = "SELECT * FROM basic_data";
          $result = $conn->query($sql);

          if ($result->num_rows > 0) {
            echo "<h4>Table: basic_data<br>No. of Records: " . $result->num_rows . "</h4>";
            echo "<table class='table table-striped table-bordered'>";
            $row = $result->fetch_assoc();
            echo "<thead class='thead-dark'><tr>";
            foreach (array_keys($row) as $col) {
              echo "<th scope='col'>" . $col . "</th>";
            }
            echo "</tr></thead><tbody>";
            do {
              echo "<tr>";
              foreach ($row as $val) {
                echo "<td>" . $val . "</td>";
              }
              echo "</tr>";
            } while ($row = $result->fetch_assoc());
            echo "</tbody></table>";
          } else {
            echo "<h4>No Records Found!</h4>";
          }

          $conn->close();
        }
      ?>
    </div>

    <form action="index3.php" method="POST" class="mt-3">
      <input type="submit" class="btn btn-success btn-custom btn-success-custom" value="上一頁">
    </form>
    <form action="index.php" method="POST" class="mt-3">
      <input type="submit" class="btn btn-success btn-custom btn-success-custom" value="回首頁">
    </form>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
