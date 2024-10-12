<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>登入系統</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: url('6.jpg') no-repeat center center fixed;
      background-size: cover;
      color: #fff;
      height: 100vh;
      margin: 0;
      display: flex;
      justify-content: center;
      align-items: center;
    }
    .container {
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100%;
    }
    .card {
      background-color: white;
      color: #7B7B7B;
      border: none;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
      padding: 50px;
      width: 100%;
      max-width: 500px;
    }
    .btn-primary {
      background-color: #9D9D9D;
      border-color: white;
      font-size: 1.5em;
      padding: 15px 30px;
      width: 100%;
    }
    h1 {
      text-align: center;
      color: #7B7B7B;
      font-size: 2.5em;
      font-weight: 900;
    }
    .form-control {
      font-size: 1.3em;
      padding: 15px;
      margin-bottom: 20px;
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="card mb-4">
      <div class="card-body">
        <h1>登入系統</h1>
        <form action="login_action.php" method="POST">
          <div class="mb-3">
            <label for="username" class="form-label">帳號:</label>
            <input type="text" id="username" name="username" class="form-control" placeholder="請輸入帳號" required>
          </div>
          <div class="mb-3">
            <label for="password" class="form-label">密碼:</label>
            <input type="password" id="password" name="password" class="form-control" placeholder="請輸入密碼" required>
          </div>
          <button type="submit" class="btn btn-primary">登入</button>
        </form>
        <a href="index.php" class="btn w3-button" style="justify-content: center;display: flex;">回首頁</a>
	<a href="signup.php" class="btn w3-button" style="justify-content: center;display: flex;">註冊新帳號</a>
      </div>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
