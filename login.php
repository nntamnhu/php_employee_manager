<?php
session_start();
require_once("config/db.class.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    $db = new Db();
    $connection = $db->connect();

    // Xác thực thông tin đăng nhập
    $sql = "SELECT * FROM users WHERE username='$username' AND password='$password'";
    $result = $connection->query($sql);

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        $_SESSION["user_id"] = $user["Id"];
        $_SESSION["username"] = $user["username"];
        $_SESSION["role"] = $user["role"];
        
        // Chuyển hướng đến trang chính sau khi đăng nhập thành công
        header("Location: list_employee.php");
        exit();
    } else {
        echo "Đăng nhập không thành công. Vui lòng kiểm tra lại tên đăng nhập và mật khẩu.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
        }
        .login-container {
            width: 300px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .login-container h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        .login-container label {
            display: block;
            margin-bottom: 10px;
        }
        .login-container input[type="text"],
        .login-container input[type="password"] {
            width: 100%;
            padding: 8px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 3px;
            box-sizing: border-box;
        }
        .login-container input[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            border: none;
            border-radius: 3px;
            color: #fff;
            cursor: pointer;
        }
        .login-container input[type="submit"]:hover {
            background-color: #0056b3;
        }
        .login-container .error-msg {
            color: #ff0000;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Đăng nhập</h2>
        <form method="post">
            <label for="username">Tên đăng nhập:</label>
            <input type="text" id="username" name="username" required>
            
            <label for="password">Mật khẩu:</label>
            <input type="password" id="password" name="password" required>

            <input type="submit" value="Đăng nhập">
        </form>
        <?php
            // Thực hiện kiểm tra thông tin đăng nhập
            // Trong trường hợp này, bạn có thể thực hiện kiểm tra thông tin từ cơ sở dữ liệu
            // Ở đây chỉ làm ví dụ, không thực hiện kiểm tra thực sự
            if ($username === "admin" && $password === "admin123") {
                $_SESSION["username"] = $username;
                // Điều hướng sau khi đăng nhập thành công
                header("Location: list_employee.php");
                exit();
            } else {
                // Hiển thị thông báo lỗi nếu thông tin đăng nhập không chính xác
                echo "<p class='error-msg'>Tên đăng nhập hoặc mật khẩu không chính xác.</p>";
            }
        }
        ?>
    </div>
</body>
</html>

