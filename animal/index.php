<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>智慧代養系統</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@900&family=Open+Sans:wght@400&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css">
  <style>
    body {
      background: url('3.jpg') no-repeat center center fixed;
      background-size: cover;
      color: #fff;
      font-family: 'PingFang TC', sans-serif;
      scroll-behavior: smooth;
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      padding: 0 20px;
    }

    .container {
      width: 100%;
      max-width: 600px;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
    }

    h1 {
      margin-bottom: 50px;
      margin-top: 50px;
      font-family: 'Roboto', sans-serif;
      font-weight: 900;
      font-size: 3.5em;
      color: #8E8E8E;
      letter-spacing: 3px;
      text-align: center;
    }

    .btn-primary {
      background-color: #8E8E8E;
      border-color: #FFFFFF;
      font-size: 1.5em;
      font-weight: 700;
      padding: 15px 50px;
      scroll-behavior: smooth;
    }

    .row {
      width: 100%;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      margin-top: 50px;
    }

    .btn-block {
      width: auto;
    }

    form {
      display: flex;
      justify-content: center;
      margin-bottom: 80px;
    }

    .custom-btn {
      background-color: #8E8E8E;
      border-color: #FFFFFF;
      color: white;
      font-size: 1.3em;
      font-weight: 700;
      padding: 12px 40px;
      scroll-behavior: smooth;
      border-radius: 10px;
    }

    .custom-btn:hover,
    .custom-btn:active {
      background-color: #6e6e6e;
      border-color: #dddddd;
    }

    #visitResult {
      margin-top: 30px;
      margin-bottom: 30px;
      font-size: 1.3em;
      color: black;
    }

    @media (max-width: 768px) {
      h1 {
        font-size: 2em;
        margin-bottom: 50px;
      }
      .custom-btn {
        font-size: 1.2em;
        padding: 10px 30px;
      }
      #visitResult {
        font-size: 1.2em;
      }
    }

    @media (max-width: 576px) {
      h1 {
        font-size: 1.8em;
      }
      .custom-btn {
        font-size: 1em;
        padding: 8px 20px;
      }
      #visitResult {
        font-size: 1em;
      }
    }
  </style>
</head>
<body>
  <div class="container">
    <h1 data-aos="fade-down">智慧代養系統</h1>
    <div class="row">
      <div class="col-md-12">
        <div class="mb-4" data-aos="fade-up">
          <div>
            <!-- 修改 action 指向 user.php，這是登入頁面 -->
            <form action="user.php" method="POST">
              <button type="submit" class="custom-btn">使用者專區</button>
            </form>
          </div>
        </div>
        <div class="mb-4" data-aos="fade-up" data-aos-delay="100">
          <div>
            <!-- 管理員區域保持不變 -->
            <form action="admin_login.php" method="POST">
              <button type="submit" class="custom-btn">管理員專區</button>
            </form>
          </div>
        </div>
      </div>
    </div>
    <div id="visitResult">總造訪次數：載入中...</div>
  </div>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
  <script>
    AOS.init({
      duration: 1200, // 動畫持續時間
    });

    // 獲取總造訪次數並顯示
    window.onload = function() {
      fetch('visittotal.php')  // 使用基於 URL 的路徑
        .then(response => response.json())
        .then(data => {
          document.getElementById("visitResult").textContent = "總造訪次數：" + data.total_visits;
        })
        .catch(error => console.error("Error:", error));
    }
  </script>
  
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
