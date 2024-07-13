<?php
$servername = "localhost";
$username = "phpmyadmin"; // Default MySQL username
$password = "your_password"; // Default MySQL password
$dbname = "database01"; // Replace with your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['filename'])) {
    $fileName = $_GET['filename'];

    // Fetch file details from database
    $stmt = $conn->prepare("SELECT filename, filecontent, mimetype FROM files WHERE filename = ?");
    $stmt->bind_param("s", $fileName);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // File details
        $fileName = $row['filename'];
        $fileContent = $row['filecontent'];
        $mime = $row['mimetype'];

        // Send headers for file download
        header("Content-Type: " . $mime);
        header("Content-Disposition: attachment; filename=\"" . $fileName . "\"");

        // Output file content
        echo $fileContent;
    } else {
        // If file is not found, send a 404 response
        http_response_code(404);
        echo "File not found for filename: " . $fileName;
    }

    $stmt->close();
} else {
    // If filename parameter is not provided in URL
    http_response_code(400);
    echo "Bad request: File name parameter (filename) is missing.";
}

$conn->close();
?>


