<?php
session_start();
require_once('admin_session_check.php');

// 連接資料庫
$conn = new mysqli('localhost', 'root', '12345678', 'phmsfinal');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// 處理查詢條件
$search = "";
$search_email = "";
$age_min = "";
$age_max = "";

if (isset($_GET['search'])) {
    $search = $_GET['search'];
}
if (isset($_GET['search_email'])) {
    $search_email = $_GET['search_email'];
}
if (isset($_GET['age_min'])) {
    $age_min = $_GET['age_min'];
}
if (isset($_GET['age_max'])) {
    $age_max = $_GET['age_max'];
}

// 根據查詢條件生成 SQL 語句
$sql = "SELECT id, username, email, age FROM users WHERE 1=1";
$params = [];
$types = '';

if ($search !== '') {
    $sql .= " AND username LIKE ?";
    $params[] = "%" . $search . "%";
    $types .= 's';
}
if ($search_email !== '') {
    $sql .= " AND email LIKE ?";
    $params[] = "%" . $search_email . "%";
    $types .= 's';
}
if ($age_min !== '') {
    $sql .= " AND age >= ?";
    $params[] = $age_min;
    $types .= 'i';
}
if ($age_max !== '') {
    $sql .= " AND age <= ?";
    $params[] = $age_max;
    $types .= 'i';
}

$stmt = $conn->prepare($sql);

if ($types !== '') {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>查看用戶資料</title>
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
            max-width: 1000px;
            width: 100%;
            text-align: center;
        }

        h2 {
            color: #7B7B7B;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            text-align: center;
            padding: 12px;
            border: 1px solid #ddd;
        }

        th {
            background-color: #9D9D9D;
            color: white;
        }

        td {
            background-color: white;
        }

        .btn-container {
            margin-top: 20px;
        }

        .back-btn, .edit-btn, .delete-btn {
            background-color: #9D9D9D;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .back-btn:hover, .edit-btn:hover, .delete-btn:hover {
            background-color: #6e6e6e;
        }

        .delete-btn {
            background-color: #ff6666;
        }

        .delete-btn:hover {
            background-color: #e65c5c;
        }

        .search-form {
            margin-bottom: 20px;
        }

        .search-form input[type="text"], .search-form input[type="number"] {
            padding: 10px;
            width: 200px;
            margin-right: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        .search-form input[type="submit"] {
            padding: 10px 20px;
            border: none;
            background-color: #9D9D9D;
            color: white;
            border-radius: 5px;
            cursor: pointer;
        }

        .search-form input[type="submit"]:hover {
            background-color: #6e6e6e;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>查看用戶資料</h2>

        <!-- 查詢表單 -->
        <form method="GET" action="view_users.php" class="search-form">
            <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" placeholder="輸入用戶名稱查詢">
            <input type="text" name="search_email" value="<?php echo htmlspecialchars($search_email); ?>" placeholder="輸入Email查詢">
            <input type="number" name="age_min" value="<?php echo htmlspecialchars($age_min); ?>" placeholder="最小年齡">
            <input type="number" name="age_max" value="<?php echo htmlspecialchars($age_max); ?>" placeholder="最大年齡">
            <input type="submit" value="查詢">
        </form>

        <?php if ($result->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>用戶名稱</th>
                        <th>Email</th>
                        <th>年齡</th>
                        <th>操作</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['id']); ?></td>
                            <td><?php echo htmlspecialchars($row['username']); ?></td>
                            <td><?php echo htmlspecialchars($row['email']); ?></td>
                            <td><?php echo htmlspecialchars($row['age']); ?></td>
                            <td>
                                <a href="edit_user.php?id=<?php echo $row['id']; ?>" class="edit-btn" style="background-color: #4CAF50;">修改</a>
                                <a href="delete_user.php?id=<?php echo $row['id']; ?>" class="delete-btn" style="background-color: #da190b;" onclick="return confirm('確定要刪除此用戶嗎?');">刪除</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>沒有找到匹配的用戶資料。</p>
        <?php endif; ?>

        <div class="btn-container">
            <a href="admin_dashboard.php" class="back-btn">返回控制台</a>
        </div>
    </div>
</body>
</html>

<?php
$conn->close();
?>
