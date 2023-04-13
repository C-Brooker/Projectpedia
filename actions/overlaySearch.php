<?php //Mysqli request to return clicked projects details in json format
include "../config/database.php";

$projectId = htmlspecialchars(trim($_GET['projectId'], '"'));

$stmt = mysqli_prepare($conn, "SELECT* FROM projects JOIN users ON projects.uid = users.uid WHERE pid = ?");
$stmt->bind_param("s", $projectId);
$stmt->execute();

$data = array();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

header('Content-Type: application/json');

$json = json_encode($data);

echo $json;
