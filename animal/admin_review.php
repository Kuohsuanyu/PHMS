<?php
session_start();

// 確保只有管理員可以訪問此頁面
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// 連接資料庫
$conn = new mysqli('localhost', 'root', '12345678', 'phmsfinal');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// 處理審核通過或拒絕的請求
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!empty($_POST['user_id']) && isset($_POST['action'])) {
        $user_id = $_POST['user_id'];
        if ($_POST['action'] == 'approve') {
            // 審核通過，將用戶從申請表移動到正式的 users 表
            $sql = "INSERT INTO users (username, password, email, age, created_at)
                    SELECT username, password, email, age, created_at FROM user_applications WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $user_id);
            $stmt->execute();

            // 刪除申請表中的用戶
            $delete_sql = "DELETE FROM user_applications WHERE id = ?";
            $delete_stmt = $conn->prepare($delete_sql);
            $delete_stmt->bind_param("i", $user_id);
            $delete_stmt->execute();
        } elseif ($_POST['action'] == 'reject') {
            // 拒絕申請，直接刪除記錄
            $sql = "DELETE FROM user_applications WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
        }
    }
}

// 查詢未審核的用戶申請
$sql = "SELECT * FROM user_applications";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>審核用戶申請</title>
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
            background-color: rgba(255, 255, 255, 0.9);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
            max-width: 800px;
            width: 100%;
            color: #333;
        }
        .btn-approve, .btn-reject, .w3-button {
            background-color: #9D9D9D;
            color: white;
            padding: 10px 20px;
            margin: 5px;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }
        .btn-approve:hover, .btn-reject:hover, .w3-button:hover {
            background-color: #6e6e6e;
        }
        .btn-approve {
            background-color: #4CAF50;
        }
        .btn-reject {
            background-color: #f44336;
        }
        h2 {
            color: #333;
            text-align: center;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            background-color: #fff;
            color: #333;
        }
        th, td {
            padding: 15px;
            text-align: left;
        }
        .btn-container {
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>審核用戶申請</h2>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>用戶名</th>
                    <th>郵箱</th>
                    <th>年齡</th>
                    <th>申請時間</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['username']); ?></td>
                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                        <td><?php echo htmlspecialchars($row['age']); ?></td>
                        <td><?php echo htmlspecialchars($row['created_at']); ?></td>
                        <td>
                            <form method="POST" class="d-inline">
                                <input type="hidden" name="user_id" value="<?php echo $row['id']; ?>">
                                <button type="submit" name="action" value="approve" class="btn btn-approve">審核通過</button>
                            </form>
                            <form method="POST" class="d-inline">
                                <input type="hidden" name="user_id" value="<?php echo $row['id']; ?>">
                                <button type="submit" name="action" value="reject" class="btn btn-reject">拒絕</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <div class="btn-container">
         <a href="admin_dashboard.php" class="w3-button">返回控制台</a>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
