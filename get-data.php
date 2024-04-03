<?php

$servername = "localhost";
$username = "root";
$password = "";
$database = "tutorial"; 
$table = "technicalguide"; 

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$sql = "SELECT * FROM $table";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $data = array();
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }

    $response = array(
        "get-data" => $data
    );

    $json = json_encode($response, JSON_PRETTY_PRINT);
    echo $json;
} else {
    echo "No records found";
}

$conn->close();

?>
