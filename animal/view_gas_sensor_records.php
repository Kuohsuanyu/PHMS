<?php
session_start();
require_once('admin_session_check.php');

// 連接資料庫
$conn = new mysqli('localhost', 'root', '12345678', 'phmsfinal');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// 設定每頁顯示的記錄數量
$records_per_page = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $records_per_page;

// 處理搜尋條件
$search_gas_type = '';
$search_min_concentration = '';
$search_max_concentration = '';
$search_date = '';

if (isset($_GET['search_gas_type'])) {
    $search_gas_type = $_GET['search_gas_type'];
}
if (isset($_GET['search_min_concentration'])) {
    $search_min_concentration = $_GET['search_min_concentration'];
}
if (isset($_GET['search_max_concentration'])) {
    $search_max_concentration = $_GET['search_max_concentration'];
}
if (isset($_GET['search_date'])) {
    $search_date = $_GET['search_date'];
}

// 根據查詢條件生成 SQL 語句
$sql = "SELECT id, gas_type, concentration_ppm, recorded_at FROM gas_sensor_records WHERE 1=1";
$count_sql = "SELECT COUNT(*) AS total FROM gas_sensor_records WHERE 1=1";
$params = [];
$types = '';

if ($search_gas_type !== '') {
    $sql .= " AND gas_type LIKE ?";
    $count_sql .= " AND gas_type LIKE ?";
    $params[] = "%" . $search_gas_type . "%";
    $types .= 's';
}
if ($search_min_concentration !== '') {
    $sql .= " AND concentration_ppm >= ?";
    $count_sql .= " AND concentration_ppm >= ?";
    $params[] = $search_min_concentration;
    $types .= 'i';
}
if ($search_max_concentration !== '') {
    $sql .= " AND concentration_ppm <= ?";
    $count_sql .= " AND concentration_ppm <= ?";
    $params[] = $search_max_concentration;
    $types .= 'i';
}
if ($search_date !== '') {
    $sql .= " AND recorded_at LIKE ?";
    $count_sql .= " AND recorded_at LIKE ?";
    $params[] = $search_date . "%";
    $types .= 's';
}

// 加入排序、分頁的部分
$sql .= " ORDER BY recorded_at DESC LIMIT ? OFFSET ?";
$params[] = $records_per_page;
$params[] = $offset;
$types .= 'ii';

// 準備語句
$stmt = $conn->prepare($sql);

// 如果有參數，將參數與佔位符綁定
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();

// 查詢總記錄數，計算總頁數
$count_stmt = $conn->prepare($count_sql);

// 確保查詢總記錄數時只綁定必要的參數
if (!empty($params)) {
    // 刪除分頁參數，因為 `COUNT` 查詢不需要 `LIMIT` 和 `OFFSET`
    $count_params = array_slice($params, 0, -2);
    if (!empty($count_params)) {
        $count_stmt->bind_param($types, ...$count_params);
    }
}
$count_stmt->execute();
$count_result = $count_stmt->get_result();
$total_records = $count_result->fetch_assoc()['total'];
$total_pages = ceil($total_records / $records_per_page);
?>

<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>氣味感測器資料</title>
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <style>
        body {
            background: url('6.jpg') no-repeat center center fixed;
            background-size: cover;
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .container {
            background-color: rgba(255, 255, 255, 0.9);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 80%;
            max-width: 800px;
            text-align: center;
        }

        h1 {
            color: #7B7B7B;
            font-weight: bold;
        }

        table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }

        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: center;
            vertical-align: middle;
        }

        th {
            background-color: #9D9D9D;
            color: white;
        }

        td {
            background-color: white;
            color: #333;
        }

        .btn-container {
            margin-top: 30px;
        }

        .w3-button, .edit-btn, .delete-btn {
            background-color: #9D9D9D;
            color: white;
            padding: 10px 20px;
            margin: 5px;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .w3-button:hover, .edit-btn:hover, .delete-btn:hover {
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

        .search-form input[type="text"], .search-form input[type="number"], .search-form input[type="date"] {
            padding: 8px;
            font-size: 16px;
            margin-right: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .search-form button {
            padding: 10px 15px;
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
            color: black;
            padding: 8px 16px;
            text-decoration: none;
            background-color: #ddd;
            margin: 0 4px;
        }

        .pagination a:hover {
            background-color: #ccc;
        }

        .pagination .active {
            background-color: #9D9D9D;
            color: white;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>氣味感測器資料</h1>

        <!-- 查詢表單 -->
        <form method="GET" action="view_gas_sensor_records.php" class="search-form">
            <input type="text" name="search_gas_type" value="<?php echo htmlspecialchars($search_gas_type); ?>" placeholder="輸入氣體類型">
            <input type="number" name="search_min_concentration" value="<?php echo htmlspecialchars($search_min_concentration); ?>" placeholder="最小濃度 (ppm)">
            <input type="number" name="search_max_concentration" value="<?php echo htmlspecialchars($search_max_concentration); ?>" placeholder="最大濃度 (ppm)">
            <input type="date" name="search_date" value="<?php echo htmlspecialchars($search_date); ?>">
            <button type="submit">搜尋</button>
        </form>

        <?php if ($result->num_rows > 0): ?>
        <table class="w3-table-all">
            <thead>
                <tr>
                    <th>氣體類型</th>
                    <th>濃度 (ppm)</th>
                    <th>記錄時間</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($record = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($record['gas_type']); ?></td>
                    <td><?php echo htmlspecialchars($record['concentration_ppm']); ?></td>
                    <td><?php echo htmlspecialchars($record['recorded_at']); ?></td>
                    <td>
                        <a href="edit_gas_record.php?id=<?php echo $record['id']; ?>" class="edit-btn" style="background-color: #4CAF50;">修改</a>
                        <a href="delete_gas_record.php?id=<?php echo $record['id']; ?>" class="delete-btn" style = "background-color: #da190b;"onclick="return confirm('確定要刪除這條氣味紀錄嗎？');">刪除</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <!-- 分頁導航 -->
        <div class="pagination">
            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <a href="?page=<?php echo $i; ?>" class="<?php if ($i == $page) echo 'active'; ?>"><?php echo $i; ?></a>
            <?php endfor; ?>
        </div>

        <?php else: ?>
        <p class="no-records">目前沒有氣味感測器資料。</p>
        <?php endif; ?>

        <div class="btn-container">
            <a href="admin_dashboard.php" class="w3-button">返回控制台</a>
        </div>
    </div>
</body>
</html>

<?php
$stmt->close();
$count_stmt->close();
$conn->close();
?>
