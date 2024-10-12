<?php
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    // 建立資料庫連接
    $conn = new mysqli("localhost", "root", "12345678", "phmsfinal");

    // 檢查連接是否成功
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // 清除特定日期之前的記錄
    $clear_sql = "DELETE FROM total_visits WHERE visit_date < DATE_SUB(CURDATE(), INTERVAL 1 DAY)";
    $conn->query($clear_sql);  // 執行刪除查詢

    // 查詢今天的日期是否已存在於資料庫中
    $today = date('Y-m-d');
    $sql = "SELECT visit_count FROM total_visits WHERE visit_date = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $today);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // 如果存在當天的訪問記錄，更新 visit_count
        $row = $result->fetch_assoc();
        $visitCount = $row['visit_count'] + 1;

        $update_sql = "UPDATE total_visits SET visit_count = ? WHERE visit_date = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("is", $visitCount, $today);
        $update_stmt->execute();
    } else {
        // 如果不存在當天的訪問記錄，插入新記錄
        $visitCount = 1;
        $insert_sql = "INSERT INTO total_visits (visit_date, visit_count) VALUES (?, ?)";
        $insert_stmt = $conn->prepare($insert_sql);
        $insert_stmt->bind_param("si", $today, $visitCount);
        $insert_stmt->execute();
    }

    // 查詢當前總訪問次數
    $total_sql = "SELECT SUM(visit_count) AS total_visits FROM total_visits";
    $total_result = $conn->query($total_sql);
    $total_row = $total_result->fetch_assoc();
    $totalVisits = $total_row['total_visits'];

    // 回傳結果給前端
    echo json_encode(array("total_visits" => $totalVisits));

    // 關閉連接
    $conn->close();
}
?>
