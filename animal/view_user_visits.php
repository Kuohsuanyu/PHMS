<?php
session_start();
require_once('admin_session_check.php');

// 連接資料庫
$conn = new mysqli('localhost', 'root', '12345678', 'phmsfinal');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// 處理查詢條件
$search_user_id = '';
$search_page_name = '';
$start_date = '';
$end_date = '';

if (isset($_GET['search_user_id']) && $_GET['search_user_id'] !== '') {
    $search_user_id = $_GET['search_user_id'];
}

if (isset($_GET['search_page_name']) && $_GET['search_page_name'] !== '') {
    $search_page_name = $_GET['search_page_name'];
}

if (isset($_GET['start_date']) && $_GET['start_date'] !== '') {
    $start_date = $_GET['start_date'];
}

if (isset($_GET['end_date']) && $_GET['end_date'] !== '') {
    $end_date = $_GET['end_date'];
}

// 根據查詢條件查詢用戶造訪紀錄
$sql = "SELECT id, user_id, page_name, view_time, view_count FROM user_page_views WHERE 1=1";
$params = [];
$types = '';

if ($search_user_id !== '') {
    $sql .= " AND user_id = ?";
    $params[] = $search_user_id;
    $types .= 'i';
}

if ($search_page_name !== '') {
    $sql .= " AND page_name LIKE ?";
    $params[] = "%" . $search_page_name . "%";
    $types .= 's';
}

if ($start_date !== '' && $end_date !== '') {
    $sql .= " AND view_time BETWEEN ? AND ?";
    $params[] = $start_date . " 00:00:00";
    $params[] = $end_date . " 23:59:59";
    $types .= 'ss';
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
    <title>查看用戶造訪次數</title>
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
            margin: 5px;
        }

        .back-btn:hover, .edit-btn:hover, .delete-btn:hover {
            background-color: #6e6e6e;
        }

        .search-form {
            margin-bottom: 20px;
        }

        .search-form input[type="text"], .search-form input[type="date"] {
            padding: 8px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .search-form button {
            padding: 8px 12px;
            font-size: 16px;
            background-color: #9D9D9D;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .search-form button:hover {
            background-color: #6e6e6e;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>查看用戶造訪次數</h2>

        <!-- 搜尋框 -->
        <form class="search-form" action="view_user_visits.php" method="get">
            <input type="text" name="search_user_id" placeholder="輸入用戶ID查詢" value="<?php echo htmlspecialchars($search_user_id); ?>">
            <input type="text" name="search_page_name" placeholder="輸入頁面名稱查詢" value="<?php echo htmlspecialchars($search_page_name); ?>">
            <input type="date" name="start_date" value="<?php echo htmlspecialchars($start_date); ?>">
            <input type="date" name="end_date" value="<?php echo htmlspecialchars($end_date); ?>">
            <button type="submit">搜尋</button>
        </form>

        <?php if ($result->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>用戶ID</th>
                        <th>頁面名稱</th>
                        <th>造訪時間</th>
                        <th>造訪次數</th>
                        <th>操作</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['id']); ?></td>
                            <td><?php echo htmlspecialchars($row['user_id']); ?></td>
                            <td><?php echo htmlspecialchars($row['page_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['view_time']); ?></td>
                            <td><?php echo htmlspecialchars($row['view_count']); ?></td>
                            <td>
                                <a href="edit_user_page_view.php?id=<?php echo $row['id']; ?>" class="edit-btn" style="background-color: #4CAF50;">修改</a>
                                <a href="delete_user_page_view.php?id=<?php echo $row['id']; ?>" style="background-color: #da190b;" class="delete-btn" onclick="return confirm('確定要刪除嗎?');">刪除</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>目前沒有用戶造訪紀錄。</p>
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
