<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

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

// Check if a file was uploaded via POST
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["file"])) {
    $file = $_FILES["file"];
    
    // Check for errors during file upload
    if ($file["error"] !== UPLOAD_ERR_OK) {
        die("File upload failed with error code: " . $file["error"]);
    }
    
    $fileName = $file["name"];
    $fileTmpName = $file["tmp_name"];
    $fileType = $file["type"];

    // Read file content
    $fileContent = file_get_contents($fileTmpName);
    if ($fileContent === false) {
        die("Failed to read file content.");
    }

    $mime = mime_content_type($fileTmpName);
    if ($mime === false) {
        die("Failed to determine file MIME type.");
    }

    // Check if the file MIME type is allowed
    $allowedMimes = ['application/pdf', 'image/jpeg', 'image/jpg'];
    if (!in_array($mime, $allowedMimes)) {
        die("Only PDF, JPG, and JPEG files are allowed...!");
    }

    // Prepare and execute SQL statement to insert into database
    $stmt = $conn->prepare("INSERT INTO files (filename, filecontent, mimetype) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $fileName, $fileContent, $mime);
    
    if ($stmt->execute()) {
        echo "File uploaded successfully.";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "No file uploaded or invalid request.";
}

$conn->close();
?>






