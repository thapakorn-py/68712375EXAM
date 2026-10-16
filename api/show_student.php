<?php
include 'condb.php';

try {
    // ดึงข้อมูลคนเดียว (ใช้ในหน้าแก้ไข)
    if (isset($_GET['id'])) {
        $stmt = $conn->prepare("SELECT student_id, first_name AS firstName, last_name AS lastName, phone, email FROM students WHERE student_id = :id");
        $stmt->execute([':id' => $_GET['id']]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            echo json_encode(["success" => false, "message" => "ไม่พบข้อมูลนักเรียน"]);
            exit;
        }
        echo json_encode(["success" => true, "data" => $row]);
        exit;
    }

    // ดึงข้อมูลทั้งหมด (ใช้ในหน้ารายชื่อ)
    $stmt = $conn->query("SELECT student_id, first_name AS firstName, last_name AS lastName, phone, email FROM students ORDER BY student_id ASC");
    echo json_encode(["success" => true, "data" => $stmt->fetchAll(PDO::FETCH_ASSOC)]);
} catch (PDOException $e) {
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
}
