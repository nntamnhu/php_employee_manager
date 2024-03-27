<?php
// Kiểm tra xem phiên đã được bắt đầu chưa
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    // Nếu không phải admin, chuyển hướng hoặc hiển thị thông báo lỗi
    header("Location: login.php");
    exit();
}

require_once("config/db.class.php");
require_once("entities/employee.class.php");

// Biến để kiểm tra xem xóa nhân viên đã thành công hay chưa
$deleteSuccess = false;

// Kiểm tra phương thức request là GET
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    // Kiểm tra xem có tham số Ma_NV được truyền không
    if(isset($_GET["Ma_NV"])) {
        $Ma_NV = $_GET["Ma_NV"];
        
        // Khởi tạo một đối tượng Employee
        $employee = new Employee();
        
        // Gọi phương thức xóa nhân viên và lưu kết quả vào biến
        $result = $employee->deleteEmployee($Ma_NV);
        
        // Kiểm tra kết quả và gán vào biến $deleteSuccess
        if ($result) {
            $deleteSuccess = true;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xóa nhân viên</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .alert {
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 9999;
            display: none;
        }
    </style>
</head>
<body>
    <?php if ($deleteSuccess): ?>
        <div class="alert alert-success" role="alert" id="deleteAlert">
            Xóa nhân viên thành công.
        </div>

        <script>
            // Hiển thị thông báo trong 3 giây, sau đó tự động ẩn đi
            document.addEventListener('DOMContentLoaded', function () {
                var deleteAlert = document.getElementById('deleteAlert');
                deleteAlert.style.display = 'block';
                setTimeout(function () {
                    deleteAlert.style.display = 'none';
                }, 3000);
            });
        </script>
    <?php endif; ?>
</body>
</html>
