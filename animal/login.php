if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 取得使用者輸入的帳號和密碼
    $username = $_POST['username'];
    $password = $_POST['password'];

    // 連接資料庫
    $conn = new mysqli("localhost", "root", "12345678", "phmsfinal");

    // 檢查連接是否成功
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // 印出帳號和密碼進行除錯
    var_dump($username);
    var_dump($password);

    // 查詢帳號與密碼是否匹配
    $sql = "SELECT * FROM users WHERE username = ? AND password = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // 如果帳號和密碼正確，重定向到使用者介面
        header("Location: user_dashboard.php");
        exit();
    } else {
        // 如果帳號或密碼不正確，顯示錯誤訊息
        echo "<script>alert('帳號或密碼錯誤！請再試一次');window.location.href='user.php';</script>";
    }

    // 關閉連接
    $stmt->close();
    $conn->close();
}
