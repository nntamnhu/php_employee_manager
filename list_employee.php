<?php
session_start();
if(!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    // Nếu không phải admin, chuyển hướng hoặc hiển thị thông báo lỗi
    header("Location: login.php");
    exit();
}
require_once("entities/employee.class.php");
require_once("config/db.class.php"); // Thêm đoạn này để khởi tạo kết nối đến cơ sở dữ liệu

// Số lượng nhân viên trên mỗi trang
$records_per_page = 5;

// Xác định trang hiện tại
if (isset($_GET['page']) && is_numeric($_GET['page'])) {
    $current_page = $_GET['page'];
} else {
    $current_page = 1;
}

// Tính toán offset
$offset = ($current_page - 1) * $records_per_page;

// Khởi tạo đối tượng Employee
$employee = new Employee();

// Khởi tạo kết nối cơ sở dữ liệu
$db = new Db();
$connection = $db->connect(); // Khởi tạo kết nối cơ sở dữ liệu

// Lấy tổng số nhân viên
$total_records = $employee->countEmployees();

// Tính toán tổng số trang
$total_pages = ceil($total_records / $records_per_page);

// Lấy danh sách nhân viên cho trang hiện tại
$employees = $employee->getEmployees($offset, $records_per_page);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh sách nhân viên</title>
    <style>
        /* CSS cho giao diện */
        table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        /* Style cho phân trang */
        .pagination {
            margin-top: 20px;
        }

        .pagination a {
            padding: 8px;
            text-decoration: none;
            border: 1px solid #ddd;
            margin: 0 4px;
            cursor: pointer;
        }

        .pagination a.active {
            background-color: #4CAF50;
            color: white;
        }

        /* Style cho menu */
        ul.menu {
            list-style-type: none;
            margin: 0;
            padding: 0;
        }

        ul.menu li {
            display: inline;
            margin-right: 10px;
        }

        ul.menu li a {
            text-decoration: none;
            color: #333;
            padding: 5px 10px;
            border: 1px solid #ccc;
            border-radius: 3px;
        }

        ul.menu li a:hover {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <!-- Hiển thị các chức năng chỉ dành cho admin -->
    <?php if($_SESSION['role'] === 'admin'): ?>
    <ul class="menu">
        <li><a href="add_employee.php">Thêm nhân viên</a></li>
        <li><a href="logout.php">Đăng xuất</a></li>
    </ul>
    <?php endif; ?>

    <!-- Hiển thị danh sách nhân viên -->
    <h2>THÔNG TIN NHÂN VIÊN</h2>
    <table>
        <tr>
            <th>Mã Nhân Viên</th>
            <th>Tên Nhân Viên</th>
            <th>Giới tính</th>
            <th>Nơi Sinh</th>
            <th>Tên Phòng</th>
            <th>Lương</th>
        </tr>
        <?php foreach ($employees as $employee): ?>
    <tr>
        <td><?php echo $employee["Ma_NV"]; ?></td>
        <td><?php echo $employee["Ten_NV"]; ?></td>
        <td>
            <?php if ($employee["Phai"] == "Nam"): ?>
                <img src='images/man.png' alt='Nam' width='50' height='50'>
            <?php else: ?>
                <img src='images/woman.png' alt='Nữ' width='50' height='50'>
            <?php endif; ?>
        </td>
        <td><?php echo $employee["Noi_Sinh"]; ?></td>
        <td>
            <?php
            $phongBan = $employee["Ma_Phong"];
            $tenPhong = ""; // Mặc định tên phòng
            $sql = "SELECT Ten_Phong FROM phongban WHERE Ma_Phong='$phongBan'";
            $result = $connection->query($sql);
            if ($result && $result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $tenPhong = $row['Ten_Phong'];
            }
            echo $tenPhong;
            ?>
        </td>
        <td><?php echo $employee["Luong"]; ?></td>
        <td>
            <?php if($_SESSION['role'] === 'admin'): ?>
                <a href="edit_employee.php?id=<?php echo $employee['Ma_NV']; ?>">Sửa</a> |
                <a href="delete_employee.php?Ma_NV=<?php echo $employee['Ma_NV']; ?>" onclick="return confirm('Bạn có chắc chắn muốn xóa nhân viên này không?');">Xóa</a>
            <?php endif; ?>
        </td>
    </tr>
<?php endforeach; ?>

    </table>

    <!-- Phân trang -->
    <div class="pagination">
        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <a href='list_employee.php?page=<?php echo $i; ?>' <?php if ($i == $current_page) echo "class='active'"; ?>><?php echo $i; ?></a>
        <?php endfor; ?>
    </div>
</body>
</html>

