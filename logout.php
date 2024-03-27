<?php
session_start();

// Xóa các biến phiên session
session_unset();

// Hủy phiên session
session_destroy();

// Chuyển hướng đến trang đăng nhập
header("Location: login.php");
exit();
?>
