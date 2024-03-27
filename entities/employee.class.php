<?php
require_once("config/db.class.php");

class Employee
{
    public $Ma_NV;
    public $Ten_NV;
    public $Phai;
    public $Noi_Sinh;
    public $Ma_Phong;
    public $Luong;

    // Add a function to check if the logged-in user is an admin
    public function isAdmin() {
        // Check the user's role from the session
        if(isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
            return true;
        }
        return false;
    }
    
    // Hàm để thêm nhân viên vào cơ sở dữ liệu
    public function addEmployee($Ma_NV, $Ten_NV, $Phai, $Noi_Sinh, $Ma_Phong, $Luong)
    {
        // Kiểm tra xem người dùng có phải là admin hay không
        if(!$this->isAdmin()) {
            return false; // Chỉ admin mới có thể thêm nhân viên
        }

        $db = new Db();
        $connection = $db->connect();
        
        $sql = "INSERT INTO nhanvien (Ma_NV, Ten_NV, Phai, Noi_Sinh, Ma_Phong, Luong) VALUES ('$Ma_NV', '$Ten_NV', '$Phai', '$Noi_Sinh', '$Ma_Phong', '$Luong')";
        
        if ($connection->query($sql) === TRUE) {
            return true;
        } else {
            return false;
        }
    }

    // Hàm để xoá nhân viên từ cơ sở dữ liệu
    public function deleteEmployee($Ma_NV)
    {
        // Kiểm tra xem người dùng có phải là admin hay không
        if(!$this->isAdmin()) {
            return false; // Chỉ admin mới có thể xoá nhân viên
        }

        $db = new Db();
        $connection = $db->connect();
        
        $sql = "DELETE FROM nhanvien WHERE Ma_NV='$Ma_NV'";
        
        if ($connection->query($sql) === TRUE) {
            return true;
        } else {
            return false;
        }
    }

    // Hàm để cập nhật thông tin nhân viên trong cơ sở dữ liệu
    public function updateEmployee($Ma_NV, $newData)
    {
        // Kiểm tra xem người dùng có phải là admin hay không
        if(!$this->isAdmin()) {
            return false; // Chỉ admin mới có thể cập nhật thông tin nhân viên
        }

        $db = new Db();
        $connection = $db->connect();

        // Kiểm tra xem Ma_Phong có tồn tại trong bảng phongban hay không
        $Ma_Phong = $newData['Ma_Phong'];
        $checkSql = "SELECT * FROM phongban WHERE Ma_Phong='$Ma_Phong'";
        $checkResult = $connection->query($checkSql);
        if ($checkResult->num_rows == 0) {
            return false; // Nếu Ma_Phong không tồn tại trong bảng phongban, không thực hiện cập nhật
        }

        // Tạo chuỗi SET cho câu lệnh UPDATE
        $setStr = "";
        foreach ($newData as $key => $value) {
            $setStr .= "$key='$value', ";
        }
        $setStr = rtrim($setStr, ", "); // Loại bỏ dấu phẩy và khoảng trắng cuối cùng

        $sql = "UPDATE nhanvien SET $setStr WHERE Ma_NV='$Ma_NV'";

        if ($connection->query($sql) === TRUE) {
            return true;
        } else {
            return false;
        }
    }
    // public function updateEmployee($Ma_NV, $newData)
    // {
    //     // Kiểm tra xem người dùng có phải là admin hay không
    //     if(!$this->isAdmin()) {
    //         return false; // Chỉ admin mới có thể cập nhật thông tin nhân viên
    //     }

    //     $db = new Db();
    //     $connection = $db->connect();
        
    //     // Tạo chuỗi SET cho câu lệnh UPDATE
    //     $setStr = "";
    //     foreach ($newData as $key => $value) {
    //         $setStr .= "$key='$value', ";
    //     }
    //     $setStr = rtrim($setStr, ", "); // Loại bỏ dấu phẩy và khoảng trắng cuối cùng
        
    //     $sql = "UPDATE nhanvien SET $setStr WHERE Ma_NV='$Ma_NV'";
        
    //     if ($connection->query($sql) === TRUE) {
    //         return true;
    //     } else {
    //         return false;
    //     }
    //}

    // Hàm để lấy danh sách nhân viên từ cơ sở dữ liệu
    public function getEmployees($offset = null, $limit = null)
    {
        $db = new Db();
        $connection = $db->connect();
        
        if ($offset !== null && $limit !== null) {
            $sql = "SELECT * FROM nhanvien LIMIT $offset, $limit";
        } else {
            $sql = "SELECT * FROM nhanvien";
        }
        
        $result = $connection->query($sql);
        
        $employees = array();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $employees[] = $row;
            }
        }
        return $employees;
    }

    // Trong lớp Employee
    public function getEmployeeById($Ma_NV)
    {
        $db = new Db();
        $connection = $db->connect();

        $sql = "SELECT * FROM nhanvien WHERE Ma_NV='$Ma_NV'";
        $result = $connection->query($sql);

        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        } else {
            return null;
        }
    }

    // Hàm để đếm tổng số nhân viên trong cơ sở dữ liệu
    public function countEmployees()
    {
        $db = new Db();
        $connection = $db->connect();
        
        $sql = "SELECT COUNT(*) AS total FROM nhanvien";
        $result = $connection->query($sql);
        
        $row = $result->fetch_assoc();
        return $row['total'];
    }
}
?>
