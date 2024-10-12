<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once('admin_session_check.php');

// 檢查是否有管理者登入
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>管理員控制台</title>
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
            margin: 0;
        }

        .container {
            background-color: rgba(255, 255, 255, 0.9);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
            max-width: 600px;
            width: 100%;
            text-align: center;
        }

        h2 {
            color: #7B7B7B;
            margin-bottom: 20px;
        }

        .btn-container {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .btn-container a {
            background-color: #9D9D9D;
            color: white;
            padding: 15px 25px;
            margin: 10px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 1.2em;
            width: 100%;
            text-align: center;
            transition: background-color 0.3s ease;
        }

        .btn-container a:hover {
            background-color: #6e6e6e;
        }

        .logout-btn {
            margin-top: 20px;
            background-color: #ff6666;
        }

        .logout-btn:hover {
            background-color: #e65c5c;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>管理員控制台</h2>
        <div class="btn-container">
            <a href="view_cleaning_records_admin.php" class="w3-button">查看清潔紀錄</a>
            <a href="view_feeding_records_admin.php" class="w3-button">查看餵食紀錄</a>
            <a href="view_user_visits.php" class="w3-button">查看用戶造訪次數</a>
            <a href="view_users.php" class="w3-button">查看用戶資料</a>
	    <a href="view_gas_sensor_records.php" class="w3-button">查看氣味資料</a>
	    <a href="view_temp_records_admin.php" class="w3-button">查看溫溼度資料</a>
	    <a href="admin_review.php" class="w3-button">審核帳號申請資料</a>
        </div>

        <div class="btn-container">
            <form action="admin_logout.php" method="POST">
                <input type="submit" style="border-radius: 10px !important;" value="登出" class="w3-button logout-btn">
            </form>
        </div>
        <div class="btn-container">
            <form action="index.php" method="POST">
                <input type="submit" style="border-radius: 10px ; background-color: #4CAF50 !important;" value="回首頁" class="w3-button logout-btn">
            </form>
        </div>
    </div>
</body>
</html>
