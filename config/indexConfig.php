<?php
header('Content-Type: application/json');

$conn = new mysqli('localhost', 'root', '', 'colegio_imeg_bd');

// Check connection
if ($conn->connect_error) {
    echo json_encode(['error' => 'Connection failed: ' . $conn->connect_error]);
    exit();
}

// Query to get highlights
$sql = "SELECT * FROM highlights";
$result = $conn->query($sql);

if (!$result) {
    echo json_encode(['error' => 'Query failed: ' . $conn->error]);
    exit();
}

$highlights = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $highlights[] = $row;
    }
}

// Output as JSON
echo json_encode($highlights);

$conn->close();
?>
