<?php
session_start();
require_once('admin_session_check.php');

// 連接資料庫
$conn = new mysqli('localhost', 'root', '12345678', 'phmsfinal');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// 處理搜尋條件
$search_id = '';
$start_date = '';
$end_date = '';

if (isset($_GET['search_id']) && $_GET['search_id'] !== '') {
    $search_id = $_GET['search_id'];
}

if (isset($_GET['start_date']) && $_GET['start_date'] !== '') {
    $start_date = $_GET['start_date'];
}

if (isset($_GET['end_date']) && $_GET['end_date'] !== '') {
    $end_date = $_GET['end_date'];
}

// 查詢條件
if ($search_id !== '' || ($start_date !== '' && $end_date !== '')) {
    // 根據搜尋條件篩選
    $sql = "SELECT id, pet_id, clean_location, clean_time, cleanliness_level, notes FROM cleaning_records WHERE 1=1";
    
    if ($search_id !== '') {
        $sql .= " AND id = ?";
    }

    if ($start_date !== '' && $end_date !== '') {
        $sql .= " AND clean_time BETWEEN ? AND ?";
    }

    $sql .= " ORDER BY clean_time DESC";
    $stmt = $conn->prepare($sql);

    if ($search_id !== '' && $start_date !== '' && $end_date !== '') {
        $stmt->bind_param("iss", $search_id, $start_date, $end_date);
    } elseif ($search_id !== '') {
        $stmt->bind_param("i", $search_id);
    } elseif ($start_date !== '' && $end_date !== '') {
        $stmt->bind_param("ss", $start_date, $end_date);
    }

    $stmt->execute();
    $result = $stmt->get_result();
} else {
    // 查詢所有清潔紀錄
    $sql = "SELECT id, pet_id, clean_location, clean_time, cleanliness_level, notes FROM cleaning_records ORDER BY clean_time DESC";
    $result = $conn->query($sql);
}
?>

<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>查看清潔紀錄</title>
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

        .back-btn {
            background-color: #9D9D9D;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
        }

        .back-btn:hover {
            background-color: #6e6e6e;
        }

        .edit-btn, .delete-btn {
            padding: 5px 10px;
            border-radius: 5px;
            text-decoration: none;
            color: white;
        }

        .edit-btn {
            background-color: #4CAF50;
        }

        .delete-btn {
            background-color: #f44336;
        }

        .edit-btn:hover {
            background-color: #45a049;
        }

        .delete-btn:hover {
            background-color: #da190b;
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
        <h2>查看清潔紀錄</h2>

        <!-- 搜尋框 -->
        <form class="search-form" action="view_cleaning_records.php" method="get">
            <input type="text" name="search_id" placeholder="輸入清潔紀錄 ID 查詢" value="<?php echo htmlspecialchars($search_id); ?>">
            <input type="date" name="start_date" value="<?php echo htmlspecialchars($start_date); ?>">
            <input type="date" name="end_date" value="<?php echo htmlspecialchars($end_date); ?>">
            <button type="submit">搜尋</button>
        </form>

        <?php if ($result->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>寵物ID</th>
                        <th>清潔地點</th>
                        <th>清潔時間</th>
                        <th>整潔度</th>
                        <th>備註</th>
                        <th>操作</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['id']); ?></td>
                            <td><?php echo htmlspecialchars($row['pet_id']); ?></td>
                            <td><?php echo htmlspecialchars($row['clean_location']); ?></td>
                            <td><?php echo htmlspecialchars($row['clean_time']); ?></td>
                            <td><?php echo htmlspecialchars($row['cleanliness_level']); ?></td>
                            <td><?php echo htmlspecialchars($row['notes']); ?></td>
                            <td>
                                <a href="edit_cleaning_record.php?id=<?php echo $row['id']; ?>" class="edit-btn" style="background-color: #4CAF50;">修改</a>
                                <a href="delete_cleaning_record.php?id=<?php echo $row['id']; ?>" class="delete-btn" onclick="return confirm('確定要刪除此記錄嗎？');">刪除</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>目前沒有清潔紀錄。</p>
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
