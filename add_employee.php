<?php
session_start();
if(!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    // Nếu không phải admin, chuyển hướng hoặc hiển thị thông báo lỗi
    header("Location: login.php");
    exit();
}

require_once("entities/employee.class.php");

// Kiểm tra xem có dữ liệu được gửi từ form không
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Lấy dữ liệu từ form
    $Ma_NV = $_POST["Ma_NV"];
    $Ten_NV = $_POST["Ten_NV"];
    $Phai = $_POST["Phai"];
    $Noi_Sinh = $_POST["Noi_Sinh"];
    $Ma_Phong = $_POST["Ma_Phong"];
    $Luong = $_POST["Luong"];

    // Khởi tạo đối tượng Employee
    $employee = new Employee();

    // Thêm nhân viên vào cơ sở dữ liệu
    if ($employee->addEmployee($Ma_NV, $Ten_NV, $Phai, $Noi_Sinh, $Ma_Phong, $Luong)) {
        echo "Thêm nhân viên thành công.";
    } else {
        echo "Có lỗi xảy ra khi thêm nhân viên.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm nhân viên mới</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-qn...8Ts==" crossorigin="anonymous">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h2 class="card-title">Thêm nhân viên mới</h2>
                    </div>
                    <div class="card-body">
                        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                            <div class="mb-3">
                                <label for="Ma_NV" class="form-label">Mã nhân viên:</label>
                                <input type="text" id="Ma_NV" name="Ma_NV" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="Ten_NV" class="form-label">Tên nhân viên:</label>
                                <input type="text" id="Ten_NV" name="Ten_NV" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="Phai" class="form-label">Phái:</label>
                                <input type="text" id="Phai" name="Phai" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="Noi_Sinh" class="form-label">Nơi sinh:</label>
                                <input type="text" id="Noi_Sinh" name="Noi_Sinh" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="Ma_Phong" class="form-label">Tên Phòng:</label>
                                <select name="Ma_Phong" id="Ma_Phong" class="form-select">
                                    <?php
                                    require_once("config/db.class.php");
                                    $db = new Db();
                                    $connection = $db->connect();
                                    $sql = "SELECT * FROM phongban";
                                    $result = $connection->query($sql);
                                    if ($result->num_rows > 0) {
                                        while ($row = $result->fetch_assoc()) {
                                            echo "<option value='" . $row['Ma_Phong'] . "'>" . $row['Ten_Phong'] . "</option>";
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="Luong" class="form-label">Lương:</label>
                                <input type="text" id="Luong" name="Luong" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Thêm</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>