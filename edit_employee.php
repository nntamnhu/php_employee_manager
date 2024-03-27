<?php
session_start();
require_once("entities/employee.class.php");

// Kiểm tra quyền truy cập của người dùng
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Kiểm tra xem có ID nhân viên được chọn không
if (!isset($_GET['id'])) {
    echo "ID nhân viên không được cung cấp.";
    exit();
}

// Lấy ID nhân viên từ URL
$id = $_GET['id'];

// Khởi tạo đối tượng Employee
$employee = new Employee();

// Lấy thông tin của nhân viên từ cơ sở dữ liệu
$employeeInfo = $employee->getEmployeeById($id);

// Kiểm tra xem nhân viên có tồn tại không
if (!$employeeInfo) {
    echo "Nhân viên không tồn tại.";
    exit();
}

// Xử lý khi form được gửi đi
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Lấy dữ liệu từ form
    $Ten_NV = $_POST["Ten_NV"];
    $Phai = $_POST["Phai"];
    $Noi_Sinh = $_POST["Noi_Sinh"];
    $Ma_Phong = $_POST["Ma_Phong"];
    $Luong = $_POST["Luong"];

    // Dữ liệu mới của nhân viên
    $newData = array(
        'Ten_NV' => $Ten_NV,
        'Phai' => $Phai,
        'Noi_Sinh' => $Noi_Sinh,
        'Ma_Phong' => $Ma_Phong,
        'Luong' => $Luong
    );

    // Cập nhật thông tin nhân viên trong cơ sở dữ liệu
    if ($employee->updateEmployee($id, $newData)) {
        echo "Cập nhật thông tin nhân viên thành công.";
    } else {
        echo "Có lỗi xảy ra khi cập nhật thông tin nhân viên.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sửa thông tin nhân viên</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-qn...8Ts==" crossorigin="anonymous">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h2 class="card-title">Sửa thông tin nhân viên</h2>
                    </div>
                    <div class="card-body">
                        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?id=' . $id; ?>">
                            <input type="hidden" name="Ma_NV" value="<?php echo $id; ?>">
                            <div class="mb-3">
                                <label for="Ten_NV" class="form-label">Tên nhân viên:</label>
                                <input type="text" id="Ten_NV" name="Ten_NV" class="form-control" value="<?php echo isset($employeeInfo['Ten_NV']) ? $employeeInfo['Ten_NV'] : ''; ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="Phai" class="form-label">Phái:</label>
                                <input type="text" id="Phai" name="Phai" class="form-control" value="<?php echo isset($employeeInfo['Phai']) ? $employeeInfo['Phai'] : ''; ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="Noi_Sinh" class="form-label">Nơi sinh:</label>
                                <input type="text" id="Noi_Sinh" name="Noi_Sinh" class="form-control" value="<?php echo isset($employeeInfo['Noi_Sinh']) ? $employeeInfo['Noi_Sinh'] : ''; ?>" required>
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
                                            $selected = ($row['Ma_Phong'] == $employeeInfo['Ma_Phong']) ? 'selected' : '';
                                            echo "<option value='" . $row['Ma_Phong'] . "' $selected>" . $row['Ten_Phong'] . "</option>";
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="Luong" class="form-label">Lương:</label>
                                <input type="text" id="Luong" name="Luong" class="form-control" value="<?php echo isset($employeeInfo['Luong']) ? $employeeInfo['Luong'] : ''; ?>" required>
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">Cập nhật</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>