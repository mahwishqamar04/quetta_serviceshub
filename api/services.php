<?php

// ==================== Services API ====================
// This API reads available services from the existing
// services table and returns them as JSON.
//
// No database structure is changed.
// No booking or CRUD logic is changed.

header('Content-Type: application/json');

include '../config.php';

$response = [
    'success' => false,
    'services' => [],
    'message' => ''
];

if (!$conn) {
    $response['message'] = 'Database connection failed.';
    echo json_encode($response);
    exit;
}

// Fetch available services from the existing services table
$sql = "SELECT id, name, price, description, image FROM services ORDER BY id ASC";

$result = $conn->query($sql);

if ($result) {

    while ($row = $result->fetch_assoc()) {

        $response['services'][] = [
            'id' => (int)$row['id'],
            'name' => $row['name'],
            'price' => $row['price'],
            'description' => $row['description'],
            'image' => $row['image']
        ];
    }

    $response['success'] = true;
    $response['message'] = 'Services loaded successfully.';

} else {

    $response['message'] = 'Unable to load services.';
}

echo json_encode($response);

?>