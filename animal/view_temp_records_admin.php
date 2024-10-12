<?php
session_start();
require_once('admin_session_check.php');

// 連接資料庫
$conn = new mysqli('localhost', 'root', '12345678', 'phmsfinal');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// 頁碼處理
$records_per_page = 10;  // 每頁顯示 10 筆記錄
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $records_per_page;

// 處理搜尋條件
$start_date = '';
$end_date = '';

if (isset($_GET['start_date']) && $_GET['start_date'] !== '') {
    $start_date = $_GET['start_date'];
}

if (isset($_GET['end_date']) && $_GET['end_date'] !== '') {
    $end_date = $_GET['end_date'];
}

// 查詢條件
if ($start_date !== '' && $end_date !== '') {
    // 根據時間範圍篩選溫濕度紀錄
    $sql = "SELECT id, temperature, humidity, recorded_at FROM temp_humidity_records WHERE recorded_at BETWEEN ? AND ? ORDER BY recorded_at DESC LIMIT ? OFFSET ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssii", $start_date, $end_date, $records_per_page, $offset);
    $stmt->execute();
    $result = $stmt->get_result();
    
    // 查詢總記錄數
    $count_sql = "SELECT COUNT(*) AS total FROM temp_humidity_records WHERE recorded_at BETWEEN '$start_date' AND '$end_date'";
    $count_result = $conn->query($count_sql);
    $total_records = $count_result->fetch_assoc()['total'];
} else {
    // 查詢所有溫濕度紀錄
    $sql = "SELECT id, temperature, humidity, recorded_at FROM temp_humidity_records ORDER BY recorded_at DESC LIMIT ? OFFSET ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $records_per_page, $offset);
    $stmt->execute();
    $result = $stmt->get_result();
    
    // 查詢總記錄數
    $count_sql = "SELECT COUNT(*) AS total FROM temp_humidity_records";
    $count_result = $conn->query($count_sql);
    $total_records = $count_result->fetch_assoc()['total'];
}

// 計算總頁數
$total_pages = ceil($total_records / $records_per_page);

?>

<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>查看溫濕度紀錄</title>
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

        .search-form input[type="date"] {
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

        .pagination {
            margin-top: 20px;
        }

        .pagination a {
            color: #333;
            padding: 8px 16px;
            text-decoration: none;
            border: 1px solid #ddd;
            margin: 0 4px;
            border-radius: 5px;
        }

        .pagination a:hover {
            background-color: #ddd;
        }

        .pagination .active {
            background-color: #9D9D9D;
            color: white;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>查看溫濕度紀錄</h2>

        <!-- 搜尋框 -->
        <form class="search-form" action="view_temp_humidity_records.php" method="get">
            <input type="date" name="start_date" value="<?php echo htmlspecialchars($start_date); ?>">
            <input type="date" name="end_date" value="<?php echo htmlspecialchars($end_date); ?>">
            <button type="submit">搜尋</button>
        </form>

        <?php if ($result->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>溫度 (°C)</th>
                        <th>濕度 (%)</th>
                        <th>記錄時間</th>
                        <th>操作</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['id']); ?></td>
                            <td><?php echo htmlspecialchars($row['temperature']); ?></td>
                            <td><?php echo htmlspecialchars($row['humidity']); ?></td>
                            <td><?php echo htmlspecialchars($row['recorded_at']); ?></td>
                            <td>
                                <a href="edit_temp_humidity_record.php?id=<?php echo $row['id']; ?>" class="edit-btn" style="background-color: #4CAF50;">修改</a>
                                <a href="delete_temp_humidity_record.php?id=<?php echo $row['id']; ?>" class="delete-btn" onclick="return confirm('確定要刪除此記錄嗎？');">刪除</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>

            <!-- 分頁按鈕 -->
            <div class="pagination">
                <?php if ($page > 1): ?>
                    <a href="?page=<?php echo $page - 1; ?>&start_date=<?php echo $start_date; ?>&end_date=<?php echo $end_date; ?>">&laquo; 上一頁</a>
                <?php endif; ?>
                
                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <a href="?page=<?php echo $i; ?>&start_date=<?php echo $start_date; ?>&end_date=<?php echo $end_date; ?>" class="<?php echo ($i == $page) ? 'active' : ''; ?>"><?php echo $i; ?></a>
                <?php endfor; ?>

                <?php if ($page < $total_pages): ?>
                    <a href="?page=<?php echo $page + 1; ?>&start_date=<?php echo $start_date; ?>&end_date=<?php echo $end_date; ?>">下一頁 &raquo;</a>
                <?php endif; ?>
            </div>

        <?php else: ?>
            <p>目前沒有溫濕度紀錄。</p>
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
