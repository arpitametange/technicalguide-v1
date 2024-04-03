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

// Get user inputs
$campaignId = $_POST['campaignId'];
$campaignName = $_POST['campaignName'];
$uniqueId = $_POST['uniqueId'];
$summarizedContent = $_POST['summarizedContent']; 
$whitepaperHeading = $_POST['whitepaperHeading']; 
$file = $_FILES['file']['tmp_name'];


$uploadDir = 'C:/Data-April/';


if (!file_exists($uploadDir)) {
    mkdir($uploadDir, 0777, true); 
}


$pdf = fopen($file, 'rb');


$pdfContent = '';
$page = 1;
while (($line = fgets($pdf)) !== false) {
    if (strpos($line, '/Type /Page') !== false) {
        if ($page > 1) {
            break;
        }
        $page++;
    }
}
while (($line = fgets($pdf)) !== false) {
    $pdfContent .= $line;
}


fclose($pdf);


$text = shell_exec("pdftotext - - <<< " . escapeshellarg($pdfContent));


$filePath = $uploadDir . basename($_FILES['file']['name']);

if (move_uploaded_file($_FILES['file']['tmp_name'], $filePath)) {

    $sql = "INSERT INTO $table (campaignId, campaignName, uniqueId, summarizedContent, whitepaperHeading, filePath) 
            VALUES ('$campaignId', '$campaignName', '$uniqueId', '$summarizedContent', '$whitepaperHeading', '$filePath')";

    if ($conn->query($sql) === TRUE) {
        echo "Record added successfully";
    } else {
        if ($conn->errno == 1062) { 
            echo "Error: Unique ID already exists.";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
} else {
    echo "Error uploading file.";
}

$conn->close();

?>
