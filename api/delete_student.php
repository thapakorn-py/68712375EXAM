<?php
include 'condb.php';

$data = json_decode(file_get_contents("php://input"), true);

if (empty($data['student_id'])) {
    echo json_encode(["success" => false, "message" => "ไม่พบรหัสที่ต้องการลบ"]);
    exit;
}

try {
    $stmt = $conn->prepare("DELETE FROM students WHERE student_id = :id");
    $stmt->execute([':id' => $data['student_id']]);

    if ($stmt->rowCount() === 0) {
        echo json_encode(["success" => false, "message" => "ไม่พบข้อมูลที่ต้องการลบ"]);
        exit;
    }
    echo json_encode(["success" => true, "message" => "ลบข้อมูลเรียบร้อย"]);
} catch (PDOException $e) {
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
}
