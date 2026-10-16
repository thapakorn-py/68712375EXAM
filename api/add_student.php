<?php
include 'condb.php';

$data = json_decode(file_get_contents("php://input"), true);

if (!is_array($data) || empty($data['firstName']) || empty($data['lastName'])) {
    echo json_encode(["success" => false, "message" => "ข้อมูลไม่ครบ"]);
    exit;
}

try {
    $stmt = $conn->prepare(
        "INSERT INTO students (first_name, last_name, phone, email)
         VALUES (:firstName, :lastName, :phone, :email)"
    );
    $stmt->execute([
        ':firstName' => $data['firstName'],
        ':lastName'  => $data['lastName'],
        ':phone'     => isset($data['phone']) && $data['phone'] !== '' ? $data['phone'] : null,
        ':email'     => isset($data['email']) && $data['email'] !== '' ? $data['email'] : null
    ]);
    echo json_encode(["success" => true, "message" => "เพิ่มข้อมูลเรียบร้อย"]);
} catch (PDOException $e) {
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
}
