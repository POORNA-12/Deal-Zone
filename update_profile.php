<?php
// Start session and include the database connection
session_start();
include 'config.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch user ID
$user_id = $_SESSION['user_id'];

// Check if a file was uploaded
if (isset($_FILES['profilePic']) && $_FILES['profilePic']['error'] === UPLOAD_ERR_OK) {
    $fileTmpPath = $_FILES['profilePic']['tmp_name'];
    $fileName = $_FILES['profilePic']['name'];
    $fileSize = $_FILES['profilePic']['size'];
    $fileType = $_FILES['profilePic']['type'];
    $fileNameCmps = explode(".", $fileName);
    $fileExtension = strtolower(end($fileNameCmps));
    
    // Sanitize file name and create a unique one
    $newFileName = $user_id . '_' . time() . '.' . $fileExtension;

    // Allowed file extensions
    $allowedfileExtensions = array('jpg', 'gif', 'png', 'jpeg');
    
    if (in_array($fileExtension, $allowedfileExtensions)) {
        // Directory for storing uploads
        $uploadFileDir = './uploads/';
        $dest_path = $uploadFileDir . $newFileName;

        // Move the file to the uploads directory
        if (move_uploaded_file($fileTmpPath, $dest_path)) {
            // Update user profile_pic in the database
            $sql = "UPDATE users SET profile_pic = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('si', $newFileName, $user_id);
            $stmt->execute();
        } else {
            echo 'There was an error moving the uploaded file.';
            exit();
        }
    } else {
        echo 'Invalid file extension.';
        exit();
    }
}

// Update other fields in the database
$firstName = $_POST['firstName'];
$lastName = $_POST['lastName'];
$contactNumber = $_POST['contactNumber'];
$profession = $_POST['profession'];
$country = $_POST['country'];
$state = $_POST['state'];
$district = $_POST['district'];
$city = $_POST['city'];
$landmarks = $_POST['landmarks'];
$houseNumber = $_POST['houseNumber'];
$bio = $_POST['bio'];

// Update the user information in the database
$sql = "UPDATE users SET firstname = ?, lastname = ?, contactNumber = ?, profession = ?, country = ?, state = ?, district = ?, city = ?, landmarks = ?, houseNumber = ?, bio = ? WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('sssssssssssi', $firstName, $lastName, $contactNumber, $profession, $country, $state, $district, $city, $landmarks, $houseNumber, $bio, $user_id);
$stmt->execute();

// Redirect back to the settings page or wherever appropriate
header("Location: 2.php");
exit();
?>
