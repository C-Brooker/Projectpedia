<?php
include "../config/database.php";

$nameSearch = "%" . htmlspecialchars(trim($_GET['nameSearch'], '"')) . "%";
$dateSearch = "%" . htmlspecialchars(trim($_GET['dateSearch'], '"')) . "%";
$userId = htmlspecialchars(trim($_GET['userId']));
$owned = htmlspecialchars($_GET['owned']);

if ($owned == "true") {
    $sql = 'SELECT * FROM projects WHERE title LIKE ? AND start_date LIKE ? AND uid = ?';
    $paramTypes = 'sss';
    $paramValues = [$nameSearch, $dateSearch, $userId];
} else {
    $sql = 'SELECT * FROM projects WHERE title LIKE ? AND start_date LIKE ?';
    $paramTypes = 'ss';
    $paramValues = [$nameSearch, $dateSearch];
}
$stmt = mysqli_prepare($conn, $sql);
$stmt->bind_param($paramTypes, ...$paramValues);
$stmt->execute();

$data = array();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

header('Content-Type: application/json');

$json = json_encode($data);

echo $json;
