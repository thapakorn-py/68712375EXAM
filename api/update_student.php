<?php
include 'condb.php';

$data = json_decode(file_get_contents("php://input"), true);

if (!is_array($data) || empty($data['student_id']) || empty($data['firstName']) || empty($data['lastName'])) {
    echo json_encode(["success" => false, "message" => "ข้อมูลไม่ครบ"]);
    exit;
}

try {
    $params = [
        ':id'        => $data['student_id'],
        ':firstName' => $data['firstName'],
        ':lastName'  => $data['lastName'],
        ':phone'     => isset($data['phone']) && $data['phone'] !== '' ? $data['phone'] : null,
        ':email'     => isset($data['email']) && $data['email'] !== '' ? $data['email'] : null
    ];

    $sql = "UPDATE students SET first_name = :firstName, last_name = :lastName, phone = :phone,
            email = :email WHERE student_id = :id";

    $stmt = $conn->prepare($sql);
    $stmt->execute($params);
    echo json_encode(["success" => true, "message" => "แก้ไขข้อมูลเรียบร้อย"]);
} catch (PDOException $e) {
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
}
